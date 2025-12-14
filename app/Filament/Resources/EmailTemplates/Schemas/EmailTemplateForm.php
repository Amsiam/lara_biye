<?php

namespace App\Filament\Resources\EmailTemplates\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms;

class EmailTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Template Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->disabled()
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('subject')
                            ->required()
                            ->maxLength(255)
                            ->columnSpan(1),
                        Forms\Components\RichEditor::make('content')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('placeholders')
                            ->label('Available Placeholders')
                            ->disabled()
                            ->columnSpanFull()
                            ->helperText('You can use these placeholders in the content or subject.'),
                    ])->columns(2),
            ]);
    }
}
