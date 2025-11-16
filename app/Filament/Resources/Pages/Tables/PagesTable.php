<?php

namespace App\Filament\Resources\Pages\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->label('Page Title'),

                TextColumn::make('slug')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Slug copied!')
                    ->label('URL Slug')
                    ->toggleable(),

                TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'legal' => 'danger',
                        'support' => 'success',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable()
                    ->label('Category'),

                TextColumn::make('order')
                    ->numeric()
                    ->sortable()
                    ->label('Order'),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active')
                    ->sortable(),

                IconColumn::make('show_in_footer')
                    ->boolean()
                    ->label('In Footer')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Created'),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->label('Last Updated'),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->options([
                        'legal' => 'Legal',
                        'support' => 'Support',
                    ]),

                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->trueLabel('Active Only')
                    ->falseLabel('Inactive Only')
                    ->native(false),

                TernaryFilter::make('show_in_footer')
                    ->label('Footer Visibility')
                    ->trueLabel('Shown in Footer')
                    ->falseLabel('Hidden from Footer')
                    ->native(false),
            ])
            ->recordActions([
                ViewAction::make()
                    ->url(fn ($record) => route('page', $record->slug))
                    ->openUrlInNewTab(),
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
