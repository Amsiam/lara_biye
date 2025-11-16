<?php

namespace App\Filament\Resources\Faqs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category')
                    ->badge()
                    ->searchable()
                    ->sortable()
                    ->color('info'),

                TextColumn::make('question')
                    ->searchable()
                    ->limit(50)
                    ->wrap()
                    ->tooltip(fn ($record) => $record->question),

                TextColumn::make('answer')
                    ->limit(60)
                    ->wrap()
                    ->toggleable()
                    ->tooltip(fn ($record) => $record->answer),

                TextColumn::make('order')
                    ->numeric()
                    ->sortable()
                    ->label('Order'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'Getting Started' => 'Getting Started',
                        'Profile & Privacy' => 'Profile & Privacy',
                        'Search & Connections' => 'Search & Connections',
                        'Packages & Payment' => 'Packages & Payment',
                        'Safety & Security' => 'Safety & Security',
                        'Technical Issues' => 'Technical Issues',
                    ])
                    ->multiple(),

                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only')
                    ->native(false),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order', 'asc');
    }
}
