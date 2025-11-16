<?php

namespace App\Filament\Resources\Settings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('key')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('Unique identifier for this setting (e.g., site.phone, social.facebook)'),

                Textarea::make('value')
                    ->required()
                    ->maxLength(65535)
                    ->helperText('The value for this setting'),

                Select::make('type')
                    ->required()
                    ->options([
                        'text' => 'Text',
                        'number' => 'Number',
                        'url' => 'URL',
                        'email' => 'Email',
                        'phone' => 'Phone',
                    ])
                    ->default('text'),

                Select::make('group')
                    ->required()
                    ->options([
                        'general' => 'General',
                        'contact' => 'Contact Information',
                        'social' => 'Social Media',
                        'stats' => 'Statistics',
                    ])
                    ->default('general'),
            ]);
    }
}
