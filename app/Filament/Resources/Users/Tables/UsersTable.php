<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Columns\CheckboxColumn;

class UsersTable
{
    public static function columns(): array
    {
        return [
            TextColumn::make('name')
                ->label('Nombre')
                ->searchable(),

            TextColumn::make('email')
                ->searchable(),

            CheckboxColumn::make('is_active')
                ->label('Acceso')
                ->alignment('center')
                ->sortable()
                ->disabled(fn () => !auth()->user()->can('Update:User')),

            TextColumn::make('roles.name')
                ->label('Roles')
                ->badge()
                ->color(fn(string $state):string => match ($state) {
                    'Super Admin' => 'danger',
                    'Administrador' => 'danger',
                    'Ventas' => 'success',
                    default => 'gray',
                }), 
                
            
        ];
    }
    public static function filters(): array
    {
        return [
            TernaryFilter::make('is_active')
                ->label('Estado de Acceso')
                ->placeholder('Todos los Usuarios')
                ->trueLabel('Usuarios Activos')
                ->falseLabel('Usuarios Bloqueados')
                ->native(false),

        ];
    }
}
