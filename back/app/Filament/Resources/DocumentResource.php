<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DocumentResource\Pages;
use App\Models\Document;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
// Importaciones de los componentes que vamos a usar
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    
    protected static ?string $navigationLabel = 'Documentos Gremiales';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        TextInput::make('title')
                            ->label('Título del Documento')
                            ->required()
                            ->placeholder('Ej: Escala Salarial Marzo 2026')
                            ->maxLength(255),

                        Select::make('type')
                            ->label('Categoría')
                            ->options([
                                'convenio' => 'Convenio Colectivo',
                                'escala' => 'Escala Salarial (PDF)',
                                'formulario' => 'Formulario / Trámite',
                                'otro' => 'Otro',
                            ])
                            ->required(),

                        FileUpload::make('file_path')
                            ->label('Archivo PDF')
                            ->directory('sindicato-docs')
                            ->acceptedFileTypes(['application/pdf'])
                            ->required()
                            ->preserveFilenames()
                            ->columnSpanFull(), 
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->label('Tipo')
                    ->badge() 
                    ->color(fn (string $state): string => match ($state) {
                        'convenio' => 'info',
                        'escala' => 'success',
                        'formulario' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Fecha de Carga')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'convenio' => 'Convenio Colectivo',
                        'escala' => 'Escala Salarial',
                        'formulario' => 'Formularios',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}