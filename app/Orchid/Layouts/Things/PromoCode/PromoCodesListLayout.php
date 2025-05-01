<?php

namespace App\Orchid\Layouts\Things\PromoCode;

use App\Models\PromoCode;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;

class PromoCodesListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'promoCodes';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', __('ID'))
                ->render(function (PromoCode $promoCode) {
                    return Link::make($promoCode->id)
                        ->route('platform.promocode.edit', $promoCode);
                }),
            TD::make('code', __('Код'))
                ->render(function (PromoCode $promoCode) {
                    return Link::make($promoCode->code)
                        ->route('platform.promocode.edit', $promoCode);
                }),
            TD::make('discount', __('Скидка'))
                ->render(function (PromoCode $promoCode) {
                    return $promoCode->discount;
                }),
            TD::make('type', __('Тип'))
                ->render(function (PromoCode $promoCode) {
                    return $promoCode->type === 'percentage' ? 'Процентная' : 'Фиксированная';
                }),
            TD::make('expires_at', __('Дата и время окончания'))
                ->render(function (PromoCode $promoCode) {
                    return \Carbon\Carbon::parse($promoCode->expires_at)->format('d.m.Y, H:i');
                }),
            TD::make('is_active', __('Активен'))
                ->render(function (PromoCode $promoCode) {
                    return $promoCode->is_active ? 'Да' : 'Нет';
                }),
        ];
    }
}
