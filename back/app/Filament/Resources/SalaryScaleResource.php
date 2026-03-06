<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SalaryScaleResource\Pages;
use App\Filament\Resources\SalaryScaleResource\RelationManagers;
use App\Models\SalaryScale;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalaryScaleResource extends Resource
{
    protected static ?string $model = SalaryScale::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Escalas Salariales';

    protected static ?string $pluralLabel = 'Escalas Salariales';

    protected static ?string $modelLabel = 'Escala Salarial';

   public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\TextInput::make('category')
                ->required()
                ->placeholder('Ej: Oficial Panadero'),
            Forms\Components\TextInput::make('basic_salary')
                ->numeric()
                ->prefix('$')
                ->required(),
            Forms\Components\TextInput::make('non_remunerative')
                ->numeric()
                ->prefix('$'),
            Forms\Components\DatePicker::make('effective_date')
                ->required(),
            Forms\Components\Toggle::make('is_active')
                ->label('Escala Vigente')
                ->default(true),
        ]);
}
  public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('category')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('basic_salary')
                    ->label('Sueldo Básico')
                    ->money('ARS') 
                    ->sortable(),

                Tables\Columns\TextColumn::make('non_remunerative')
                    ->label('No Remunerativo')
                    ->money('ARS')
                    ->placeholder('N/A'), 

                Tables\Columns\TextColumn::make('effective_date')
                    ->label('Fecha Vigencia')
                    ->date('d/m/Y')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Vigente')
                    ->boolean() 
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Solo Vigentes'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListSalaryScales::route('/'),
            'create' => Pages\CreateSalaryScale::route('/create'),
            'edit' => Pages\EditSalaryScale::route('/{record}/edit'),
        ];
    }
}
