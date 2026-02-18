<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Concerns\Translatable;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PageResource extends Resource
{
    use Translatable;

    protected static ?string $model = Page::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 2;

    /**
     * Content block definitions shared between top-level Builder and nested column Builder.
     */
    private static function getContentBlocks(): array
    {
        return [
            Forms\Components\Builder\Block::make('hero')
                ->label('Hero Section')
                ->icon('heroicon-o-sparkles')
                ->schema([
                    Forms\Components\TextInput::make('title')->required(),
                    Forms\Components\TextInput::make('subtitle'),
                    Forms\Components\FileUpload::make('background_image')->image()->directory('pages'),
                    Forms\Components\TextInput::make('cta_text'),
                    Forms\Components\TextInput::make('cta_url'),
                ]),
            Forms\Components\Builder\Block::make('text')
                ->label('Text Content')
                ->icon('heroicon-o-bars-3-bottom-left')
                ->schema([
                    Forms\Components\RichEditor::make('content')->required(),
                ]),
            Forms\Components\Builder\Block::make('image')
                ->label('Image')
                ->icon('heroicon-o-photo')
                ->schema([
                    Forms\Components\FileUpload::make('image')->image()->required()->directory('pages'),
                    Forms\Components\TextInput::make('caption'),
                    Forms\Components\Select::make('alignment')
                        ->options(['left' => 'Left', 'center' => 'Center', 'right' => 'Right'])
                        ->default('center'),
                ]),
            Forms\Components\Builder\Block::make('gallery')
                ->label('Image Gallery')
                ->icon('heroicon-o-squares-2x2')
                ->schema([
                    Forms\Components\Repeater::make('images')
                        ->schema([
                            Forms\Components\FileUpload::make('image')->image()->required()->directory('pages'),
                            Forms\Components\TextInput::make('alt_text'),
                        ])
                        ->columns(2),
                ]),
            Forms\Components\Builder\Block::make('cta')
                ->label('Call to Action')
                ->icon('heroicon-o-megaphone')
                ->schema([
                    Forms\Components\TextInput::make('title')->required(),
                    Forms\Components\Textarea::make('description'),
                    Forms\Components\TextInput::make('button_text')->required(),
                    Forms\Components\TextInput::make('button_url')->required(),
                ]),
            Forms\Components\Builder\Block::make('contact_form')
                ->label('Contact Form')
                ->icon('heroicon-o-envelope')
                ->schema([
                    Forms\Components\TextInput::make('title')->default('Contact Us'),
                    Forms\Components\Textarea::make('description'),
                ]),
            Forms\Components\Builder\Block::make('latest_posts')
                ->label('Latest Posts')
                ->icon('heroicon-o-newspaper')
                ->schema([
                    Forms\Components\TextInput::make('title')->default('Latest Posts'),
                    Forms\Components\TextInput::make('count')
                        ->label('Number of posts')
                        ->numeric()
                        ->default(6)
                        ->minValue(1)
                        ->maxValue(20),
                    Forms\Components\Select::make('columns')
                        ->options(['2' => '2 Columns', '3' => '3 Columns', '4' => '4 Columns'])
                        ->default('3'),
                ]),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Page Details')->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $state, Forms\Set $set) =>
                        $set('slug', Str::slug($state))
                    ),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\Select::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                    ])
                    ->default('draft')
                    ->required(),
                Forms\Components\Select::make('template')
                    ->options([
                        'default' => 'Default',
                        'full-width' => 'Full Width',
                        'sidebar' => 'With Sidebar',
                    ])
                    ->default('default'),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
            ])->columns(2),

            Forms\Components\Section::make('Page Builder')
                ->description('Build your page layout using blocks. Drag and drop to reorder.')
                ->schema([
                    Forms\Components\Builder::make('layout_data')
                        ->label('')
                        ->blocks([
                            ...self::getContentBlocks(),

                            Forms\Components\Builder\Block::make('columns')
                                ->label('Column Layout')
                                ->icon('heroicon-o-view-columns')
                                ->schema([
                                    Forms\Components\Grid::make(2)->schema([
                                        Forms\Components\Select::make('layout')
                                            ->label('Layout')
                                            ->options([
                                                'equal' => 'Equal Widths',
                                                '2-1' => 'Wide + Narrow (2/3 + 1/3)',
                                                '1-2' => 'Narrow + Wide (1/3 + 2/3)',
                                                '1-1-1' => 'Three Equal',
                                                '2-1-1' => 'Wide + 2 Narrow (1/2 + 1/4 + 1/4)',
                                                '1-1-1-1' => 'Four Equal',
                                            ])
                                            ->default('equal')
                                            ->required(),
                                        Forms\Components\Select::make('gap')
                                            ->options([
                                                'none' => 'None',
                                                'small' => 'Small',
                                                'medium' => 'Medium',
                                                'large' => 'Large',
                                            ])
                                            ->default('medium'),
                                    ]),
                                    Forms\Components\Repeater::make('column_items')
                                        ->label('Columns')
                                        ->schema([
                                            Forms\Components\Builder::make('blocks')
                                                ->label('Column Content')
                                                ->blocks(self::getContentBlocks())
                                                ->reorderableWithButtons()
                                                ->collapsible()
                                                ->columnSpanFull(),
                                        ])
                                        ->defaultItems(2)
                                        ->minItems(2)
                                        ->maxItems(4)
                                        ->collapsible()
                                        ->itemLabel(fn (array $state, int $index): string => 'Column ' . ($index + 1))
                                        ->columnSpanFull(),
                                ]),
                        ])
                        ->reorderableWithButtons()
                        ->collapsible()
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('SEO')->schema([
                Forms\Components\TextInput::make('meta_title')->maxLength(255),
                Forms\Components\Textarea::make('meta_description')->rows(2),
            ])->columns(2)->collapsed(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                    ]),
                Tables\Columns\TextColumn::make('template'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
