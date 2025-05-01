<?php

namespace App\Orchid\Screens\Things\PromoCode;

use App\Models\PromoCode;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Select;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Illuminate\Http\Request;

class PromoCodesEditScreen extends Screen
{
    public $promoCode;

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(PromoCode $promoCode): iterable
    {
        return [
            'promoCode' => $promoCode
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->promoCode->exists ? 'Редактировать промокод' : 'Создание промокода';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Создать')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->promoCode->exists),

            Button::make('Обновить')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->promoCode->exists),

            Button::make('Удалить')
                ->icon('trash')
                ->method('remove')
                ->confirm('Вы уверены, что хотите удалить этот промокод?')
                ->canSee($this->promoCode->exists),
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
                Input::make('promoCode.code')
                    ->title('Код промокода')
                    ->required(),
                Input::make('promoCode.discount')
                    ->type('number')
                    ->title('Размер скидки')
                    ->required(),
                Select::make('promoCode.type')
                    ->title('Тип скидки')
                    ->options([
                        'percentage' => 'Процентная',
                        'fixed' => 'Фиксированная'
                    ])
                    ->required(),
                DateTimer::make('promoCode.expires_at')
                    ->title('Дата окончания')
                    ->required(),
                Select::make('promoCode.is_active')
                    ->title('Активен')
                    ->options([
                        1 => 'Да',
                        0 => 'Нет',
                    ])
                    ->help('Выберите "Да", чтобы активировать промокод.')
            ])
        ];
    }

    public function createOrUpdate(Request $request)
    {
        $this->promoCode->fill($request->get('promoCode'))->save();

        Alert::info('Промокод успешно сохранен.');

        return redirect()->route('platform.promocode.list');
    }

    public function remove()
    {
        $this->promoCode->delete();

        Alert::info('Промокод успешно удален.');

        return redirect()->route('platform.promocode.list');
    }
}
