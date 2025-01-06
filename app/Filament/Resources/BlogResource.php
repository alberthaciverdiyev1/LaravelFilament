<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Title')
                    ->required()->rules('required|min:3|max:255'),
                TextInput::make('slug')
                    ->label('Slug')
                    ->required(),
                Select::make('category_id')->label('Category')
                    ->options(function () {
                        return Category::pluck('name', 'id')->toArray();
                    })->native(false)
                    ->searchable()->required(),
                FileUpload::make('image')
                    ->label('Image')
                    ->image()
                    ->required()
                    ->disk('public')
                    ->directory('blogs'),
//                    ->maxSize(1024)
                RichEditor::make('content')
                    ->label('Content')
                    ->required()->columnSpan(2),
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns(components: [
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                BooleanColumn::make('is_published')
                    ->label('Published')
                    ->sortable(),
               ImageColumn::make('image')
               ->label('Image')
               ->disk('public')
               ->directory('blogs')
               ->width(100)
               ->height(100),
                ImageColumn::make('image')


            ])
            ->filters([
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }


    public static function getRelations(): array
    {
        return [
        ];
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit' => Pages\EditBlog::route('/{record}/edit')
        ];
    }
}
