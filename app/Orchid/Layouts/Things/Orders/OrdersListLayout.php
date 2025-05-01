<?php

namespace App\Orchid\Layouts\Things\Orders;

use App\Models\Receipt;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Illuminate\Support\HtmlString;

class OrdersListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'receipts';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID Заказа')
            ->render(fn(Receipt $receipt) => Link::make(name: "Заказ #{$receipt->id}")
                ->route('platform.order.edit', $receipt)),
            TD::make('user_id', __('ID Покупателя'))
                ->render(function (Receipt $receipt) {
                    return Link::make($receipt->user_id)
                        ->route('platform.order.edit', $receipt);
                }),
            TD::make('user.name', 'Покупатель')
                ->render(fn(Receipt $return) => $return->user->name ?? '—'),

            TD::make('items', 'Товары')
            ->render(function (Receipt $r) {
                $html = '<ul>';
                foreach ($r->items as $item) {
                    $html .= "<li>
                        <strong>{$item->thing->name}</strong>
                        — {$item->quantity} шт., размер {$item->size}, {$item->price} ₽
                    </li>";
                }
                $html .= '</ul>';
                return new HtmlString($html);
            }),
            TD::make('status', __('Статус'))
                ->render(function (Receipt $receipt) {
                    return Link::make($receipt->status)
                        ->route('platform.order.edit', $receipt);
                }),
            TD::make('promoCode', __('Промокод'))
                ->render(function (Receipt $receipt) {
                    return $receipt->promoCode
                        ? Link::make($receipt->promoCode->code)->route('platform.order.edit', $receipt)
                        : '-';
                }),
            TD::make('created_at', __('Дата и время создания'))
                ->render(function (Receipt $receipt) {
                    return Link::make($receipt->created_at)
                        ->route('platform.order.edit', $receipt);
                }),
            TD::make('updated_at', __('Дата и время обновления'))
                ->render(function (Receipt $receipt) {
                    return Link::make($receipt->updated_at)
                        ->route('platform.order.edit', $receipt);
                }),
        ];
    }
}
