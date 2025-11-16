<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All Users'),
            'admins' => Tab::make('Admins')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('is_admin', true))
                ->badge(fn () => static::getResource()::getModel()::where('is_admin', true)->count())
                ->icon('heroicon-o-shield-check'),
            'profile_verified' => Tab::make('Profile Verified')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('profile_verified_at'))
                ->badge(fn () => static::getResource()::getModel()::whereNotNull('profile_verified_at')->count())
                ->icon('heroicon-o-check-badge'),
            'profile_not_verified' => Tab::make('Profile Not Verified')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('profile_verified_at'))
                ->badge(fn () => static::getResource()::getModel()::whereNull('profile_verified_at')->count())
                ->icon('heroicon-o-shield-exclamation'),
            'email_verified' => Tab::make('Email Verified')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNotNull('email_verified_at'))
                ->badge(fn () => static::getResource()::getModel()::whereNotNull('email_verified_at')->count())
                ->icon('heroicon-o-envelope-open'),
            'unverified' => Tab::make('Not Verified')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereNull('email_verified_at')->whereNull('profile_verified_at'))
                ->badge(fn () => static::getResource()::getModel()::whereNull('email_verified_at')->whereNull('profile_verified_at')->count())
                ->icon('heroicon-o-x-circle'),
            'today' => Tab::make('Joined Today')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDate('created_at', today()))
                ->badge(fn () => static::getResource()::getModel()::whereDate('created_at', today())->count())
                ->icon('heroicon-o-calendar'),
        ];
    }
}
