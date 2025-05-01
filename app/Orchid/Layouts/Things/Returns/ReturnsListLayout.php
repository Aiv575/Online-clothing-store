<?php

namespace App\Orchid\Layouts\Things\Returns;

use App\Models\ReturnModel;
use App\Models\ReturnRequest;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Fields\Input;
use Illuminate\Support\HtmlString;

class ReturnsListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'returns';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'ID Возврата')
                ->render(fn(ReturnRequest $return) => Link::make("Возврат #{$return->id}")
                    ->route('platform.return.edit', $return)),
            TD::make('user_id', __('ID Покупателя'))
                ->render(function (ReturnRequest $receipt) {
                    return Link::make($receipt->user_id)
                        ->route('platform.return.edit', $receipt);
                }),
            TD::make('user.name', 'Покупатель')
                ->render(fn(ReturnRequest $return) => $return->user->name ?? '—'),

            TD::make('receipt.id', 'Чек #')
                ->render(fn(ReturnRequest $return) => $return->receipt?->id ?? '—'),

            TD::make('Товары', 'Товары')
                ->render(function (ReturnRequest $return) {
                    return $return->items->map(function ($item) {
                        $img = $item->image
                            ? '<img src="' . asset('storage/' . $item->image) . '" style="height:150px; border-radius:4px;">'
                            : '';

                        return "
                            <div style='margin-bottom: 10px;'>
                                <strong>ID товара:</strong> {$item->thing_id}<br>
                                <strong>Размер:</strong> {$item->size}<br>
                                <strong>Кол-во:</strong> {$item->quantity}<br>
                                <strong>Цена:</strong> {$item->price} ₽<br>
                                <strong>Причина:</strong> {$item->reason}<br><br>
                                {$img}<br>
                            </div>
                        ";
                    })->implode('<hr>');
                })->cantHide()->width('300'),
            TD::make('total_refund', 'Сумма возврата')
                ->render(fn(ReturnRequest $return) => number_format($return->total_refund) . ' ₽'),

            TD::make('status', 'Статус')
                ->render(fn(ReturnRequest $return) => ucfirst($return->status)),

            TD::make('created_at', 'Дата и время создания')
                ->render(fn(ReturnRequest $return) => $return->created_at->format('d.m.Y H:i')),

            TD::make('updated_at', 'Дата и время обновления')
                ->render(fn(ReturnRequest $return) => $return->updated_at->format('d.m.Y H:i')),
        ];
    }
}
