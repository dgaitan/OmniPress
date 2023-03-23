<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\WooCommerce\Order;
use App\Models\WooCommerce\Customer;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date_created')->format('F j, Y'),
                Forms\Components\Select::make('customer')
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return Customer::where('email', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%")
                            ->limit(50)
                            ->pluck('email', 'id');
                    })
                    ->relationship('customer', 'email')
                    ->getOptionLabelUsing(fn ($value): ?string => Customer::find($value)?->email),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_id')->searchable(),
                Tables\Columns\TextColumn::make('date_created')->dateTime('F j, Y'),
                Tables\Columns\TextColumn::make('customer.email'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'processing',
                        'danger'  => 'cancelled',
                        'success' => 'completed'
                    ])
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }    
}
