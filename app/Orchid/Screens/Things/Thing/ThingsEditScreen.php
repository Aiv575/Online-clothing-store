<?php

namespace App\Orchid\Screens\Things\thing;

use App\Models\Thing;
use Illuminate\Support\Str;
use Orchid\Screen\Fields\Matrix;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Relation;
use App\Models\Category;
use App\Models\SizeAndCount;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Alert;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Cropper;
use Illuminate\Support\Facades\Storage;


class ThingsEditScreen extends Screen
{
    public $thing;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Thing $thing): iterable
    {
        return [
            'thing' => $thing
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->thing->exists ? 'Редактировать товар' : 'Создание товар';
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
                ->canSee(!$this->thing->exists),

            Button::make('Обновить')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->thing->exists),

            Button::make('Удалить')
                ->icon('trash')
                ->method('remove')
                ->confirm('Вы уверены, что хотите удалить этот товар?')
                ->canSee($this->thing->exists),
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
                Input::make('thing.name')
                    ->title('Название')
                    ->required(),
                Input::make('thing.description')
                    ->title('Описание')
                    ->required(),
                Input::make('thing.price')
                    ->type('number')
                    ->title('Цена')
                    ->required(),
                Relation::make('thing.category_id')
                    ->title('Id категории')
                    ->fromModel(Category::class, 'name', 'id')
                    ->required(),
                Cropper::make('thing.image')
                    ->title('Изображение товара')
                    ->targetId()
                    ->width(500)
                    ->height(500)
                    ->required(),
                Matrix::make('size_and_counts')
                    ->title('Размеры и количество')
                    ->columns([
                        'Размер' => 'size',
                        'Количество' => 'count',
                    ])
                    ->fields([
                        'size' => Input::make()
                                    ->required(),
                        'count' => Input::make()
                                    ->type('number')
                                    ->required(),
                    ])
                    ->addButtonLabel('Добавить размер'),
            ])
        ];
    }

    public function createOrUpdate(Request $request)
    {
        $this->thing->fill($request->get('thing'))->save();

        $this->thing->sizeAndCount()->delete();
        foreach ($request->get('size_and_counts', []) as $item) {
            SizeAndCount::create([
                'thing_id' => $this->thing->id,
                'size' => $item['size'],
                'count' => $item['count'],
            ]);
        }

        Alert::info('Товар успешно сохранен.');

        return redirect()->route('platform.thing.list');
    }

    public function remove()
    {
        $this->thing->delete();

        Alert::info('Вы успешно удалили вещь.');

        return redirect()->route('platform.thing.list');
    }
}
