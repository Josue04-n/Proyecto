<?php

namespace App\Filament\Resources\Clientes;

use App\Filament\Resources\Clientes\Pages\CreateCliente;
use App\Filament\Resources\Clientes\Pages\EditCliente;
use App\Filament\Resources\Clientes\Pages\ListClientes;
use App\Filament\Resources\Clientes\Schemas\ClienteForm;
use App\Filament\Resources\Clientes\Tables\ClientesTable;
use App\Models\Cliente;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Tables\Table;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-rectangle-stack';

    // CORRECCIÓN 2: Esto debe ser una columna de tu BD (ej: razon_social)
    protected static ?string $recordTitleAttribute = 'razon_social';

    public static function form(Schema $schema): Schema
    {
        // Llamada a tu archivo separado (Correcto)
        return $schema->schema(ClienteForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(ClientesTable::columns())
            ->actions(ClientesTable::actions())
            ->bulkActions(ClientesTable::bulkActions());
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
            'index' => ListClientes::route('/'),
            'create' => CreateCliente::route('/create'),
            'edit' => EditCliente::route('/{record}/edit'),
        ];
    }
}