<?php

namespace App\Orchid\Screens\Things\PromoCode;

use Orchid\Screen\Screen;
use App\Models\PromoCode;
use Orchid\Screen\Actions\Link;
use App\Orchid\Layouts\Things\PromoCode\PromoCodesListLayout;

class PromoCodesListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'promoCodes' => PromoCode::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Промокоды';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Создать новый')
                ->icon('pencil')
                ->route('platform.promocode.edit')
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
            PromoCodesListLayout::class,
        ];
    }
}
