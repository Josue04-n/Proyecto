<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';


    public static function form(Schema $form): Schema
    {
        return $form->schema(UserForm::schema());
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns(UsersTable::columns())
        ->filters(UsersTable::filters())
        ->actions([
            EditAction::make()
                ->visible(fn () => auth()->user()->can('Update:User')),
            DeleteAction::make()
                ->visible(fn () => auth()->user()->can('Delete:User')),
        ])
        ->bulkActions([
            BulkActionGroup::make([
                DeleteBulkAction::make()
                    ->visible(fn () => auth()->user()->can('Delete:User')),
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
