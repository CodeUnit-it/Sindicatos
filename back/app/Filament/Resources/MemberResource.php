<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Filament\Resources\MemberResource\RelationManagers;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Miembros';

    protected static ?string $pluralLabel = 'Miembros';

    protected static ?string $modelLabel = 'Miembro';
    

    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Datos Personales')
                ->schema([
                    Forms\Components\TextInput::make('nombre')->required(),
                    Forms\Components\TextInput::make('dni')->required()->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('cuil')->label('CUIL'),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->label('Correo Electrónico'),
                    Forms\Components\TextInput::make('telefono')->tel(),
                ])->columns(2),
                
            Forms\Components\Section::make('Información Gremial')
                ->schema([
                    Forms\Components\TextInput::make('numero_afiliado')
                        ->label('N° de Afiliado')
                        ->required()
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('empresa_actual')->required(),
                    Forms\Components\DatePicker::make('fecha_afiliacion')->default(now()),
                    Forms\Components\Select::make('estado')
                        ->options([
                            'activo' => 'Activo',
                            'jubilado' => 'Jubilado',
                            'baja' => 'Baja',
                        ])->default('activo')->required(),
                ])->columns(2),
        ]);
}
public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('numero_afiliado')->label('N°')->sortable(),
            Tables\Columns\TextColumn::make('nombre')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('dni')->label('DNI'),
            Tables\Columns\TextColumn::make('email')
                ->label('Email')
                ->icon('heroicon-m-envelope')
                ->copyable() 
                ->searchable(),
            Tables\Columns\TextColumn::make('empresa_actual')->label('Empresa'),
            Tables\Columns\BadgeColumn::make('estado')
                ->colors([
                    'success' => 'activo',
                    'warning' => 'jubilado',
                    'danger' => 'baja',
                ]),
            Tables\Columns\TextColumn::make('fecha_afiliacion')->date('d/m/Y'),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('estado')
                ->options([
                    'activo' => 'Activo',
                    'jubilado' => 'Jubilado',
                    'baja' => 'Baja',
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
