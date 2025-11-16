<?php

namespace App\Filament\Widgets;

use App\Models\Purchase;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestPurchases extends TableWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Purchase::query()
                    ->with(['user', 'package'])
                    ->latest()
                    ->limit(10)
            )
            ->heading('Latest Purchases')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('package.name')
                    ->label('Package')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('amount')
                    ->money('BDT')
                    ->sortable()
                    ->weight('semibold'),

                Tables\Columns\TextColumn::make('connections_purchased')
                    ->label('Connections')
                    ->suffix(' views')
                    ->alignCenter(),

                Tables\Columns\TextColumn::make('payment_stage')
                    ->label('Stage')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'initiated' => 'gray',
                        'pending' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'info',
                        'cancelled' => 'gray',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('connections_applied')
                    ->label('Applied')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Trx ID')
                    ->limit(15)
                    ->fontFamily('mono')
                    ->copyable()
                    ->placeholder('Pending'),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
