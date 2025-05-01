<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @param Dashboard $dashboard
     *
     * @return void
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * Register the application menu.
     *
     * @return Menu[]
     */
    public function menu(): array
    {
        return [
            Menu::make('Категории')
                ->icon('list')
                ->route('platform.category.list'),

            Menu::make('Товары')
                ->icon('list')
                ->route('platform.thing.list'),

            Menu::make('Размеры и количество')
                ->icon('list')
                ->route('platform.sizeAndCount.list'),

            Menu::make('Отзывы')
                ->icon('list')
                ->route('platform.review.list'),

            Menu::make('Заказы')
                ->icon('list')
                ->route('platform.order.list'),

            Menu::make('Возвраты')
                ->icon('list')
                ->route('platform.return.list'),

            Menu::make('Пользователи')
                ->icon('list')
                ->route('platform.user.list'),

            Menu::make('Промокоды')
                ->icon('list')
                ->route('platform.promocode.list'),
        ];
    }

    /**
     * Register permissions for the application.
     *
     * @return ItemPermission[]
     */
    public function permissions(): array
    {
        return [
        ];
    }
}
