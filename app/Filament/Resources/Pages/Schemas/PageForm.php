<?php

namespace App\Filament\Resources\Pages\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state)))
                    ->columnSpanFull()
                    ->label('Page Title'),

                TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('URL-friendly version of the title. Auto-generated from title.')
                    ->columnSpanFull()
                    ->label('URL Slug'),

                Select::make('category')
                    ->required()
                    ->options([
                        'legal' => 'Legal',
                        'support' => 'Support',
                    ])
                    ->default('legal')
                    ->label('Category')
                    ->helperText('Category determines where the page appears'),

                RichEditor::make('content')
                    ->required()
                    ->columnSpanFull()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'strike',
                        'h2',
                        'h3',
                        'bulletList',
                        'orderedList',
                        'link',
                        'blockquote',
                        'codeBlock',
                    ])
                    ->label('Page Content'),

                Textarea::make('meta_description')
                    ->rows(3)
                    ->maxLength(160)
                    ->columnSpanFull()
                    ->helperText('SEO meta description (max 160 characters)')
                    ->label('Meta Description'),

                TextInput::make('meta_keywords')
                    ->maxLength(255)
                    ->helperText('Comma-separated keywords for SEO')
                    ->label('Meta Keywords'),

                TextInput::make('order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower numbers appear first in menus')
                    ->label('Display Order'),

                Toggle::make('is_active')
                    ->default(true)
                    ->label('Active')
                    ->helperText('Only active pages are visible to users'),

                Toggle::make('show_in_footer')
                    ->default(true)
                    ->label('Show in Footer')
                    ->helperText('Display this page link in the footer'),
            ])
            ->columns(2);
    }
}
