<?php

namespace App\Filament\Resources\Clientes\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class ClienteForm
{
    public static function schema(): array
    {
        return [
            // 1. TIPO DE CLIENTE
            Select::make('tipo_cliente')
                ->label('Tipo de Cliente')
                ->options([
                    'natural' => 'Persona Natural',
                    'juridica' => 'Persona Jurídica (Empresa)',
                ])
                ->default('natural')
                ->required()
                ->live()
                ->afterStateUpdated(fn ($state, Set $set) => 
                    $state === 'natural' ? $set('razon_social', null) : $set('primer_nombre', null)
                ),

            // 2. IDENTIFICACIÓN
            TextInput::make('identificacion')
                ->label('Identificación (Cédula/RUC)')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(20),

            // 3. RAZÓN SOCIAL (Solo para Empresa)
            TextInput::make('razon_social')
                ->label('Razón Social')
                ->required(fn (Get $get) => $get('tipo_cliente') === 'juridica')
                ->visible(fn (Get $get) => $get('tipo_cliente') === 'juridica')
                ->maxLength(255),

            // 4. PRIMER NOMBRE (Solo para Natural)
            TextInput::make('primer_nombre')
                ->label('Primer Nombre')
                ->required(fn (Get $get) => $get('tipo_cliente') === 'natural')
                ->visible(fn (Get $get) => $get('tipo_cliente') === 'natural')
                ->maxLength(255),

            // 5. SEGUNDO NOMBRE (Solo para Natural)
            TextInput::make('segundo_nombre')
                ->label('Segundo Nombre')
                ->visible(fn (Get $get) => $get('tipo_cliente') === 'natural')
                ->maxLength(255),

            // 6. APELLIDO PATERNO (Solo para Natural)
            TextInput::make('apellido_paterno')
                ->label('Apellido Paterno')
                ->required(fn (Get $get) => $get('tipo_cliente') === 'natural')
                ->visible(fn (Get $get) => $get('tipo_cliente') === 'natural')
                ->maxLength(255),

            // 7. APELLIDO MATERNO (Solo para Natural)
            TextInput::make('apellido_materno')
                ->label('Apellido Materno')
                ->visible(fn (Get $get) => $get('tipo_cliente') === 'natural')
                ->maxLength(255),

            // 8. EMAIL
            TextInput::make('email')
                ->label('Correo Electrónico')
                ->email()
                ->unique(ignoreRecord: true)
                ->maxLength(255),

            // 9. TELÉFONO
            TextInput::make('telefono')
                ->label('Teléfono')
                ->tel()
                ->maxLength(20),

            // 10. DIRECCIÓN
            Textarea::make('direccion')
                ->label('Dirección')
                ->maxLength(65535),
        ];
    }
}