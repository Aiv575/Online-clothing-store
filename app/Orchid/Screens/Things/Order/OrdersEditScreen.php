<?php

namespace App\Orchid\Screens\Things\Order;

use App\Models\Receipt;
use App\Models\SizeAndCount;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Alert;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Select;

class OrdersEditScreen extends Screen
{
    public $receipt;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Receipt $receipt): iterable
    {
        return [
            'receipt' => $receipt // передаем все данные о заказе для отображения
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Редактирование заказа';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Обновить статус')
                ->icon('note')
                ->method('updateStatus') // метод для обновления только статуса
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            Layout::rows([
                Select::make('receipt.status')
                    ->title('Статус заказа')
                    ->options([
                        'paid' => 'Оплачен',
                        'processing' => 'В процессе',
                        'route' => 'В пути',
                        'completed' => 'Завершён',
                    ])
                    ->value($this->receipt->status) // передаем текущее значение статуса
                    ->required(),
            ])
        ];
    }

    /**
     * Метод для обновления только статуса заказа.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request)
    {
        $status = $request->input('receipt.status');

        $receipt = Receipt::with('items')->find($request->route('receipt'));

        // Обновляем статус
        $receipt->status = $status;
        $receipt->save();

        Alert::info('Статус обновлен.');

        return redirect()->route('platform.order.list');
    }

}
