<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use BackedEnum;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Illuminate\Contracts\Support\Htmlable;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 1;

    public static function getNavigationIcon(): string|BackedEnum|Htmlable|null
    {
        return 'heroicon-o-users';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Users';
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account Information')
                    ->components([
                        Infolists\Components\TextEntry::make('name')
                            ->label('Full Name')
                            ->size('lg')
                            ->weight('bold'),
                        Infolists\Components\TextEntry::make('email')
                            ->copyable()
                            ->icon('heroicon-m-envelope'),
                        Infolists\Components\IconEntry::make('email_verified_at')
                            ->label('Email Verified')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-badge')
                            ->falseIcon('heroicon-o-x-circle')
                            ->trueColor('success')
                            ->falseColor('danger'),
                        Infolists\Components\IconEntry::make('profile_verified_at')
                            ->label('Profile Verified by Admin')
                            ->boolean()
                            ->trueIcon('heroicon-o-shield-check')
                            ->falseIcon('heroicon-o-shield-exclamation')
                            ->trueColor('info')
                            ->falseColor('gray'),
                        Infolists\Components\TextEntry::make('verification_notes')
                            ->label('Verification Notes')
                            ->placeholder('No notes')
                            ->columnSpanFull()
                            ->visible(fn(User $record): bool => $record->isProfileVerified()),
                    ])
                    ->columns(2),

                Section::make('Verification Documents')
                    ->description('National ID, Student ID and University information for verification')
                    ->components([
                        Infolists\Components\TextEntry::make('basicInfo.nid')
                            ->label('National ID (NID)')
                            ->copyable()
                            ->placeholder('Not provided')
                            ->icon('heroicon-o-identification'),
                        Infolists\Components\TextEntry::make('basicInfo.student_id')
                            ->label('Student ID')
                            ->copyable()
                            ->placeholder('Not provided')
                            ->icon('heroicon-o-academic-cap'),
                        Infolists\Components\TextEntry::make('basicInfo.university')
                            ->label('University')
                            ->placeholder('Not provided')
                            ->columnSpanFull()
                            ->icon('heroicon-o-building-library'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Basic Information')
                    ->components([
                        Infolists\Components\TextEntry::make('basicInfo.gender')
                            ->badge()
                            ->color(fn(string $state): string => match ($state) {
                                'male' => 'info',
                                'female' => 'warning',
                                default => 'gray',
                            }),
                        Infolists\Components\TextEntry::make('basicInfo.date_of_birth')
                            ->label('Date of Birth')
                            ->date('M d, Y')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('basicInfo.marital_status')
                            ->label('Marital Status')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('basicInfo.religion')
                            ->placeholder('Not provided'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Physical Attributes')
                    ->components([
                        Infolists\Components\TextEntry::make('physical_attr.height')
                            ->suffix(' cm')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('physical_attr.weight')
                            ->suffix(' kg')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('physical_attr.body_type')
                            ->label('Body Type')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('physical_attr.complexion')
                            ->placeholder('Not provided'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Education & Career')
                    ->components([
                        Infolists\Components\TextEntry::make('education.highest_education')
                            ->label('Highest Education')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('education.profession')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('education.working_with')
                            ->label('Working With')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('education.monthly_income')
                            ->label('Monthly Income')
                            ->placeholder('Not provided'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Location')
                    ->components([
                        Infolists\Components\TextEntry::make('location.country')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('location.city')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('location.state')
                            ->placeholder('Not provided'),
                        Infolists\Components\TextEntry::make('location.postal_code')
                            ->label('Postal Code')
                            ->placeholder('Not provided'),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Account Statistics')
                    ->components([
                        Infolists\Components\TextEntry::make('connection.connection')
                            ->label('Available Connections')
                            ->suffix(' views')
                            ->default('0'),
                        Infolists\Components\TextEntry::make('purchases_count')
                            ->label('Total Purchases')
                            ->counts('purchases'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Joined')
                            ->dateTime('M d, Y - h:i A'),
                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('M d, Y - h:i A'),
                    ])
                    ->columns(2)
                    ->collapsible(),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Account Information')
                    ->components([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->native(false),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            ->dehydrateStateUsing(fn($state) => bcrypt($state))
                            ->dehydrated(fn($state) => filled($state))
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->maxLength(255),

                        Forms\Components\Toggle::make('is_admin')
                            ->label('Admin Access')
                            ->helperText('Grant this user access to the admin panel')
                            ->default(false),
                    ])
                    ->columns(2),

                Section::make('Profile Statistics')
                    ->components([
                        Infolists\Components\TextEntry::make('connections_count')
                            ->label('Available Connections')
                            ->formatStateUsing(fn(User $record): string => $record->connection?->connection ?? '0'),

                        Infolists\Components\TextEntry::make('total_purchases')
                            ->label('Total Purchases')
                            ->formatStateUsing(fn(User $record): string => '৳' . number_format($record->purchases()->sum('amount'), 2)),

                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Joined')
                            ->formatStateUsing(fn(User $record): string => $record->created_at->diffForHumans()),

                        Infolists\Components\TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->formatStateUsing(fn(User $record): string => $record->updated_at->diffForHumans()),
                    ])
                    ->columns(2)
                    ->hidden(fn(string $operation): bool => $operation === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->icon('heroicon-m-envelope'),

                Tables\Columns\IconColumn::make('email_verified_at')
                    ->label('Email Verified')
                    ->boolean()
                    ->sortable()
                    ->alignCenter()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->toggleable(),

                Tables\Columns\IconColumn::make('profile_verified_at')
                    ->label('Profile Verified')
                    ->boolean()
                    ->sortable()
                    ->alignCenter()
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-shield-exclamation')
                    ->trueColor('info')
                    ->falseColor('gray')
                    ->tooltip(fn(User $record): ?string => $record->verification_notes),

                Tables\Columns\TextColumn::make('basicInfo.gender')
                    ->label('Gender')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'male' => 'info',
                        'female' => 'warning',
                        default => 'gray',
                    })
                    ->toggleable(),

                Tables\Columns\TextColumn::make('basicInfo.nid')
                    ->label('NID')
                    ->searchable()
                    ->copyable()
                    ->toggleable()
                    ->placeholder('Not provided'),

                Tables\Columns\TextColumn::make('basicInfo.student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->copyable()
                    ->toggleable()
                    ->placeholder('Not provided'),

                Tables\Columns\TextColumn::make('basicInfo.university')
                    ->label('University')
                    ->searchable()
                    ->toggleable()
                    ->placeholder('Not provided')
                    ->wrap(),

                Tables\Columns\TextColumn::make('connection.connection')
                    ->label('Connections')
                    ->default('0')
                    ->suffix(' views')
                    ->alignCenter()
                    ->sortable(),

                Tables\Columns\TextColumn::make('purchases_count')
                    ->label('Purchases')
                    ->counts('purchases')
                    ->alignCenter()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Joined')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Email Verification')
                    ->nullable()
                    ->trueLabel('Verified only')
                    ->falseLabel('Unverified only')
                    ->native(false),

                Tables\Filters\TernaryFilter::make('profile_verified_at')
                    ->label('Profile Verification')
                    ->nullable()
                    ->trueLabel('Verified profiles')
                    ->falseLabel('Unverified profiles')
                    ->native(false),

                Tables\Filters\SelectFilter::make('gender')
                    ->relationship('basicInfo', 'gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                    ])
                    ->native(false),

                Tables\Filters\Filter::make('created_at')
                    ->schema([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Joined From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Joined Until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn($query, $date) => $query->whereDate('created_at', '>=', $date))
                            ->when($data['created_until'], fn($query, $date) => $query->whereDate('created_at', '<=', $date));
                    }),
            ])
            ->recordActions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\Action::make('verify_profile')
                    ->label('Verify Profile')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->hidden(fn(User $record): bool => $record->isProfileVerified())
                    ->requiresConfirmation()
                    ->modalHeading('Verify User Profile')
                    ->modalDescription('Are you sure you want to verify this user\'s profile? This will show a verified badge on their profile.')
                    ->modalSubmitActionLabel('Verify Profile')
                    ->form([
                        Forms\Components\Textarea::make('verification_notes')
                            ->label('Verification Notes (Optional)')
                            ->placeholder('e.g., Verified via NID card, Student ID verified, etc.')
                            ->rows(3),
                    ])
                    ->action(function (User $record, array $data): void {
                        $record->update([
                            'profile_verified_at' => now(),
                            'verification_notes' => $data['verification_notes'] ?? null,
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Profile Verified')
                            ->success()
                            ->body('The user profile has been successfully verified.')
                            ->send();
                    }),
                Actions\Action::make('unverify_profile')
                    ->label('Remove Verification')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(User $record): bool => $record->isProfileVerified())
                    ->requiresConfirmation()
                    ->modalHeading('Remove Profile Verification')
                    ->modalDescription('Are you sure you want to remove the verification from this user\'s profile?')
                    ->action(function (User $record): void {
                        $record->update([
                            'profile_verified_at' => null,
                            'verification_notes' => null,
                        ]);

                        \Filament\Notifications\Notification::make()
                            ->title('Verification Removed')
                            ->warning()
                            ->body('The profile verification has been removed.')
                            ->send();
                    }),
                Actions\Action::make('view_profile')
                    ->label('View Profile')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn(User $record): string => route('profile', ['profileId' => $record->id]))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordUrl(null);
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDate('created_at', today())->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'New users today';
    }
}
