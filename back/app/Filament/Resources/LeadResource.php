<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LeadResource\Pages;
use App\Models\Lead;
use App\Models\Member; 
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LeadResource extends Resource
{
    protected static ?string $model = Lead::class;
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Solicitudes Afiliación';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nombre')->required(),
                Forms\Components\TextInput::make('dni')->required(),
                Forms\Components\TextInput::make('empresa')->required(),
                Forms\Components\TextInput::make('telefono')->tel()->required(),
                Forms\Components\TextInput::make('email')
                    ->email() 
                    ->label('Correo Electrónico'),
                Forms\Components\Toggle::make('contactado')
                    ->label('¿Ya fue contactado?')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dni')
                    ->label('DNI')
                    ->searchable(),
                Tables\Columns\TextColumn::make('empresa')
                    ->label('Panadería')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('telefono')
                    ->label('WhatsApp')
                    ->icon('heroicon-m-phone')
                    ->copyable(),
                Tables\Columns\ToggleColumn::make('contactado')->label('¿Contactado?'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Solicitud')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('contactado')
                    ->options([
                        '1' => 'Contactados',
                        '0' => 'Pendientes',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('whatsapp')
                    ->label('WhatsApp')
                    ->color('success')
                    ->icon('heroicon-o-chat-bubble-left-right')
                    ->url(fn (Lead $record): string => "https://wa.me/54{$record->telefono}")
                    ->openUrlInNewTab(),
                
                Tables\Actions\Action::make('convertir_a_socio')
                    ->label('Convertir en Socio')
                    ->icon('heroicon-o-user-plus')
                    ->color('info')
                    ->mountUsing(fn (Forms\ComponentContainer $form, Lead $record) => $form->fill([
                        'nombre' => $record->nombre,
                        'dni' => $record->dni,
                        'empresa_actual' => $record->empresa,
                        'telefono' => $record->telefono,
                        'email' => $record->email, 
                        'fecha_afiliacion' => now()->format('Y-m-d'),
                    ]))
                    ->form([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('numero_afiliado')
                                    ->label('N° de Afiliado')
                                    ->required()
                                    ->unique('members', 'numero_afiliado'),
                                Forms\Components\DatePicker::make('fecha_afiliacion')
                                    ->label('Fecha de Afiliación')
                                    ->default(now())
                                    ->required(),
                                Forms\Components\TextInput::make('nombre')->required(),
                                Forms\Components\TextInput::make('dni')->label('DNI')->required(),
                                Forms\Components\TextInput::make('email')->label('Email'), 
                                Forms\Components\TextInput::make('empresa_actual')->required(),
                                Forms\Components\TextInput::make('telefono'),
                            ])
                    ])
                    ->action(function (array $data, Lead $record): void {
                        Member::create($data); 
                        $record->delete();

                        \Filament\Notifications\Notification::make()
                            ->title('¡Socio registrado con éxito!')
                            ->success()
                            ->send();
                    })
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}