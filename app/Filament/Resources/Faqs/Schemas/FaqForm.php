<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category')
                    ->required()
                    ->options([
                        'Getting Started' => 'Getting Started',
                        'Profile & Privacy' => 'Profile & Privacy',
                        'Search & Connections' => 'Search & Connections',
                        'Packages & Payment' => 'Packages & Payment',
                        'Safety & Security' => 'Safety & Security',
                        'Technical Issues' => 'Technical Issues',
                    ])
                    ->searchable()
                    ->label('Category')
                    ->helperText('Select a category for this FAQ'),

                TextInput::make('question')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->placeholder('How do I create an account?')
                    ->label('Question'),

                Textarea::make('answer')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull()
                    ->placeholder('Provide a detailed answer to the question...')
                    ->helperText('Use clear and concise language')
                    ->label('Answer'),

                TextInput::make('order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower numbers appear first')
                    ->label('Display Order'),

                Toggle::make('is_active')
                    ->default(true)
                    ->label('Active')
                    ->helperText('Only active FAQs will be shown to users'),
            ])
            ->columns(2);
    }
}
