<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('view_profile')
                ->label('View Frontend Profile')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn (): string => route('profile', ['profileId' => $this->record->id]))
                ->openUrlInNewTab()
                ->color('info'),
        ];
    }
}
