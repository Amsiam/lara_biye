<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Actions;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Schemas\Schema;

class AcceptedConnectionsRelationManager extends RelationManager
{
    // Note: This only shows connections where the current user was the SENDER.
    // To show ALL connections (sent + received), we might need a unified query or two managers.
    // For now, let's use 'connectedUsers' (Sent & Accepted). 
    // Ideally, "Connections" implies bidirectional.
    // The User model has `connectedUsers` (I sent) and `rConnectedUsers` (I received).
    // A true "Friend List" merges both.
    // But RelationManager works on a single relationship.
    // Let's stick to `connectedUsers` for now and clarify it's "Connections (Initiated)".
    // OR... we can try to use a merged relationship if one exists, but User model doesn't seem to have one ready.
    // Let's check User.php again. It doesn't have a 'friends' relationship that merges them.
    // So for now, I will create two tabs or just show one. 
    // Actually, `connectedUsers` are those I sent requests to. `rConnectedUsers` are those who sent to me.
    // If status is ACCEPTED, they are connected.
    // Let's create `AcceptedConnectionsRelationManager` for `connectedUsers` (Initiated by this user).
    // And maybe `ReceivedConnectionsRelationManager`? 
    // Or just rename this to "Connections (Sent)". 
    // Let's stick to "Connections (Sent)" for accuracy unless I refactor the model.
    protected static string $relationship = 'connectedUsers';

    protected static ?string $title = 'Connections (Initiated By User)';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn(Builder $query) => $query->where('connected.status', 'ACCEPTED'))
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->icon('heroicon-m-envelope')
                    ->copyable(),
                Tables\Columns\TextColumn::make('mobile')
                    ->searchable()
                    ->icon('heroicon-m-phone')
                    ->copyable(),
                Tables\Columns\TextColumn::make('pivot.updated_at')
                    ->label('Connected At') // Usually updated_at when status changes to ACCEPTED
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }
}
