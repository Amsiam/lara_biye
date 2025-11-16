<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseResource\Pages;
use App\Models\Purchase;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Illuminate\Contracts\Support\Htmlable;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Purchases';

    protected static ?string $modelLabel = 'Purchase';

    protected static ?string $pluralModelLabel = 'Purchases';

    public static function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return 'heroicon-o-banknotes';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Shop';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Purchase Information')
                    ->components([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->disabled(),

                        Forms\Components\Select::make('package_id')
                            ->relationship('package', 'name')
                            ->required()
                            ->disabled(),

                        Forms\Components\TextInput::make('amount')
                            ->required()
                            ->numeric()
                            ->prefix('৳')
                            ->disabled(),

                        Forms\Components\TextInput::make('connections_purchased')
                            ->required()
                            ->numeric()
                            ->suffix(' views')
                            ->disabled(),
                    ])
                    ->columns(2),

                Section::make('Payment Details')
                    ->components([
                        Forms\Components\TextInput::make('transaction_id')
                            ->label('Transaction ID')
                            ->disabled(),

                        Forms\Components\TextInput::make('payment_id')
                            ->label('Payment ID')
                            ->disabled(),

                        Forms\Components\TextInput::make('invoice_number')
                            ->label('Invoice Number')
                            ->disabled(),

                        Forms\Components\TextInput::make('payment_method')
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->options([
                                'completed' => 'Completed',
                                'pending' => 'Pending',
                                'failed' => 'Failed',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\Select::make('payment_stage')
                            ->options([
                                'initiated' => 'Initiated',
                                'pending' => 'Pending',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                                'refunded' => 'Refunded',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\Toggle::make('connections_applied')
                            ->label('Connections Applied')
                            ->disabled()
                            ->inline(false),

                        Forms\Components\Toggle::make('is_refunded')
                            ->label('Refunded')
                            ->inline(false),

                        Forms\Components\Textarea::make('error_message')
                            ->label('Error Message')
                            ->rows(3)
                            ->columnSpanFull()
                            ->disabled(),
                    ])
                    ->columns(2),

                Section::make('Response Data')
                    ->components([
                        Forms\Components\Textarea::make('payment_response')
                            ->label('Payment Response (JSON)')
                            ->rows(10)
                            ->disabled()
                            ->columnSpanFull()
                            ->formatStateUsing(fn ($state) => is_array($state) ? json_encode($state, JSON_PRETTY_PRINT) : $state),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Customer')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('package.name')
                    ->label('Package')
                    ->searchable()
                    ->sortable()
                    ->badge(),

                Tables\Columns\TextColumn::make('amount')
                    ->money('BDT')
                    ->sortable()
                    ->weight(FontWeight::SemiBold),

                Tables\Columns\TextColumn::make('connections_purchased')
                    ->label('Views')
                    ->suffix(' views')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('transaction_id')
                    ->label('Trx ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->fontFamily('mono')
                    ->copyable(),

                Tables\Columns\TextColumn::make('invoice_number')
                    ->label('Invoice #')
                    ->searchable()
                    ->toggleable()
                    ->fontFamily('mono')
                    ->copyable(),

                Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->color('info')
                    ->toggleable(),

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
                    })
                    ->sortable(),

                Tables\Columns\IconColumn::make('connections_applied')
                    ->label('Applied')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'pending' => 'warning',
                        'failed' => 'danger',
                        default => 'gray',
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Purchase Date')
                    ->dateTime('M d, Y H:i')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('package')
                    ->relationship('package', 'name')
                    ->preload(),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'completed' => 'Completed',
                        'pending' => 'Pending',
                        'failed' => 'Failed',
                        'refunded' => 'Refunded',
                    ])
                    ->native(false),

                Tables\Filters\Filter::make('created_at')
                    ->schema([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn ($query, $date) => $query->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\Action::make('applyConnections')
                    ->label('Apply Connections')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->visible(fn (Purchase $record): bool => !$record->connections_applied && $record->payment_stage === 'completed')
                    ->requiresConfirmation()
                    ->modalHeading('Apply Connections')
                    ->modalDescription(fn (Purchase $record): string => "This will add {$record->connections_purchased} connections to {$record->user->name}'s account.")
                    ->modalSubmitActionLabel('Apply Connections')
                    ->action(function (Purchase $record) {
                        $success = $record->applyConnections();

                        if ($success) {
                            \Filament\Notifications\Notification::make()
                                ->success()
                                ->title('Connections Applied')
                                ->body("Successfully added {$record->connections_purchased} connections to user's account.")
                                ->send();
                        } else {
                            \Filament\Notifications\Notification::make()
                                ->warning()
                                ->title('Already Applied')
                                ->body('Connections have already been applied to this purchase.')
                                ->send();
                        }
                    }),
                Actions\Action::make('markCompleted')
                    ->label('Mark as Completed')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Purchase $record): bool => $record->payment_stage !== 'completed')
                    ->requiresConfirmation()
                    ->modalHeading('Mark Payment as Completed')
                    ->modalDescription('This will mark the payment as completed. Connections will NOT be automatically applied.')
                    ->form([
                        Forms\Components\TextInput::make('transaction_id')
                            ->label('Transaction ID (Optional)')
                            ->maxLength(255),
                    ])
                    ->action(function (Purchase $record, array $data) {
                        $record->update([
                            'payment_stage' => Purchase::STAGE_COMPLETED,
                            'status' => Purchase::STATUS_COMPLETED,
                            'payment_completed_at' => now(),
                            'transaction_id' => $data['transaction_id'] ?? $record->transaction_id,
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Payment Marked as Completed')
                            ->body('Use "Apply Connections" action to add connections to user account.')
                            ->send();
                    }),
                Actions\Action::make('markFailed')
                    ->label('Mark as Failed')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Purchase $record): bool => $record->payment_stage !== 'failed')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\Textarea::make('error_message')
                            ->label('Error Message')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (Purchase $record, array $data) {
                        $record->markAsFailed($data['error_message']);

                        \Filament\Notifications\Notification::make()
                            ->success()
                            ->title('Payment Marked as Failed')
                            ->send();
                    }),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListPurchases::route('/'),
            'view' => Pages\ViewPurchase::route('/{record}'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'completed')
            ->whereDate('created_at', today())
            ->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'Completed purchases today';
    }
}
