<?php

namespace App\Filament\Resources\Clientes\Tables;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\TernaryFilter;

class ClientesTable
{
    public static function columns(): array
    {
        return [
            // Estado Activo
            BooleanColumn::make('is_active')
                ->label('Activo')
                ->sortable()
                ->toggleable()
                ->trueColor('success')
                ->falseColor('danger'),

            // Tipo de Cliente
            TextColumn::make('tipo_cliente')
                ->label('Tipo')
                ->badge()
                ->color(fn (string $state): string => match ($state) {
                    'natural' => 'success',
                    'juridica' => 'info',
                })
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'natural' => 'Natural',
                    'juridica' => 'Jurídica',
                })
                ->sortable(),

            // Identificación
            TextColumn::make('identificacion')
                ->label('Identificación')
                ->searchable()
                ->sortable()
                ->copyable(),

            // Nombre/Razón Social
            TextColumn::make('nombre_completo')
                ->label('Nombre/Razón Social')
                ->getStateUsing(function ($record) {
                    if ($record->tipo_cliente === 'natural') {
                        return trim("{$record->primer_nombre} {$record->segundo_nombre} {$record->apellido_paterno} {$record->apellido_materno}");
                    }
                    return $record->razon_social ?? '-';
                })
                ->searchable(['primer_nombre', 'segundo_nombre', 'apellido_paterno', 'apellido_materno', 'razon_social'])
                ->wrap(),

            // Email
            TextColumn::make('email')
                ->label('Correo')
                ->searchable()
                ->copyable()
                ->icon('heroicon-m-envelope')
                ->toggleable()
                ->default('-'),

            // Teléfono
            TextColumn::make('telefono')
                ->label('Teléfono')
                ->searchable()
                ->copyable()
                ->icon('heroicon-m-phone')
                ->toggleable()
                ->default('-'),

            // Auditoría
            TextColumn::make('creator.name')
                ->label('Creado por')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('created_at')
                ->label('Registrado')
                ->dateTime('d/m/Y H:i')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->label('Última Modificación')
                ->dateTime('d/m/Y H:i')
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('deleted_at')
                ->label('Eliminado')
                ->dateTime('d/m/Y H:i')
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function filters(): array
    {
        return [
            // Filtrar por estado activo/inactivo
            TernaryFilter::make('is_active')
                ->label('Estado del Cliente')
                ->placeholder('Todos')
                ->trueLabel('Solo Activos')
                ->falseLabel('Solo Inactivos')
                ->queries(
                    true: fn ($query) => $query->where('is_active', true),
                    false: fn ($query) => $query->where('is_active', false),
                ),

            // Filtrar por tipo
            Filter::make('tipo_cliente')
                ->form([
                    Select::make('tipo_cliente')
                        ->options([
                            'natural' => 'Persona Natural',
                            'juridica' => 'Persona Jurídica',
                        ])
                        ->searchable(),
                ])
                ->query(fn ($query, array $data) => 
                    $query->when($data['tipo_cliente'] ?? null, 
                        fn ($q, $tipo) => $q->where('tipo_cliente', $tipo)
                    )
                ),
        ];
    }

    public static function actions(): array
    {
        return [
            // Editar
            EditAction::make(),

            // Desactivar/Activar (Borrado Lógico)
            Action::make('toggleActive')
                ->label(fn ($record) => $record->is_active ? 'Desactivar' : 'Activar')
                ->icon(fn ($record) => $record->is_active ? 'heroicon-o-x-circle' : 'heroicon-o-check-circle')
                ->color(fn ($record) => $record->is_active ? 'warning' : 'success')
                ->action(function ($record) {
                    $record->update(['is_active' => !$record->is_active]);
                })
                ->requiresConfirmation(),

            // Eliminar Permanentemente
            DeleteAction::make()
                ->label('Eliminar Permanentemente')
                ->modalHeading('⚠️ Eliminar Permanentemente')
                ->modalDescription('Esta acción es irreversible. Se eliminarán todos los datos del cliente.')
                ->color('danger')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation(),
        ];
    }

    public static function bulkActions(): array
    {
        return [
            // Desactivar en lote
            Action::make('desactivarEnLote')
                ->label('Desactivar Seleccionados')
                ->icon('heroicon-o-x-circle')
                ->color('warning')
                ->action(function ($records) {
                    $records->each->update(['is_active' => false]);
                })
                ->requiresConfirmation()
                ->deselectRecordsAfterCompletion(),

            // Activar en lote
            Action::make('activarEnLote')
                ->label('Activar Seleccionados')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->action(function ($records) {
                    $records->each->update(['is_active' => true]);
                })
                ->requiresConfirmation()
                ->deselectRecordsAfterCompletion(),

            // Eliminar permanentemente
            DeleteBulkAction::make()
                ->label('Eliminar Permanentemente')
                ->color('danger')
                ->icon('heroicon-o-trash'),
        ];
    }
}