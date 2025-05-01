<?php

namespace App\Orchid\Screens;

use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layout;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout as LayoutFacade;

class UsersListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Пользователи';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Список всех зарегистрированных пользователей';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'users' => User::withCount(['receipts', 'returns'])
                ->filters()
                ->defaultSort('id', 'desc')
                ->paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            LayoutFacade::table('users', [
                TD::make('id', 'ID')
                    ->sort()
                    ->filter(TD::FILTER_NUMERIC)
                    ->width('100px'),

                TD::make('avatar', 'Аватар')
                    ->width('80px')
                    ->render(function (User $user) {
                        // Проверяем, является ли аватар дефолтным
                        $avatarUrl = $user->avatar === 'default.jpg'
                            ? asset('assets/images/default.jpg')
                            : Storage::url($user->avatar);

                        return "<img src='{$avatarUrl}' class='rounded-circle' width='40' height='40' alt='Аватар'>";
                    }),

                TD::make('name', 'Имя')
                    ->sort()
                    ->filter(TD::FILTER_TEXT)
                    ->render(fn(User $user) => $user->name),

                TD::make('email', 'Email')
                    ->sort()
                    ->filter(TD::FILTER_TEXT),

                TD::make('address', 'Адрес')
                    ->width('200px')
                    ->defaultHidden(),

                TD::make('receipts_count', 'Заказы')
                    ->sort()
                    ->alignCenter()
                    ->render(function (User $user) {
                        return Link::make($user->receipts_count)
                            ->route('platform.order.list', ['filter[user_id]' => $user->id]);
                    }),

                TD::make('returns_count', 'Возвраты')
                    ->sort()
                    ->alignCenter()
                    ->defaultHidden(),

                TD::make('created_at', 'Дата регистрации')
                    ->sort()
                    ->render(function (User $user) {
                        return $user->created_at->format('d.m.Y H:i');
                    }),

            ]),
        ];
    }
}
