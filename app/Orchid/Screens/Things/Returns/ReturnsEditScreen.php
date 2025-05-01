<?php

namespace App\Orchid\Screens\Things\Returns;

use App\Models\ReturnRequest;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Alert;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Select;

class ReturnsEditScreen extends Screen
{
    public $return;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(ReturnRequest $return): iterable
    {
        $this->return = $return;

        return [
            'return' => $return,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Редактирование возврата';
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
                ->method('updateStatus')
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
                Select::make('return.status')
                    ->title('Статус возврата')
                    ->options([
                        'pending' => 'На рассмотрении',
                        'approved' => 'Одобрено',
                        'rejected' => 'Отклонено',
                    ])
                    ->value($this->return->status) // передаем текущее значение статуса
                    ->required(),
            ])
        ];
    }

    public function updateStatus(Request $request)
    {
        $return = ReturnRequest::findOrFail($request->route('return'));
        $return->status = $request->input('return.status');
        $return->save();

        Alert::info('Статус обновлён.');
        return redirect()->route('platform.return.list');
    }
}
