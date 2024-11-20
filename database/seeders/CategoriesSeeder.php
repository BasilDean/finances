<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = array(
            ['title' => 'Еда'],
            ['title' => 'Жилье'],
            ['title' => 'Транспорт'],
            ['title' => 'Комунальные платежи'],
            ['title' => 'Страховка'],
            ['title' => 'Здоровье'],
            ['title' => 'Сбережения и инвестиции'],
            ['title' => 'Образование'],
            ['title' => 'Личные расходы'],
            ['title' => 'Развлечения'],
            ['title' => 'Подарки'],
            ['title' => 'Оплата долгов'],
            ['title' => 'Разное'],
            ['title' => 'Продукты', 'parent_id' => 1],
            ['title' => 'Кафе и рестораны', 'parent_id' => 1],
            ['title' => 'Напитки и снеки', 'parent_id' => 1],
            ['title' => 'Аренда', 'parent_id' => 2],
            ['title' => 'Ипотека', 'parent_id' => 2],
            ['title' => 'Страхование жилья', 'parent_id' => 2],
            ['title' => 'Комунальные платежи', 'parent_id' => 2],
            ['title' => 'Ремонт', 'parent_id' => 2],
            ['title' => 'Покупка мебели/техники', 'parent_id' => 2],
            ['title' => 'Публичный транспорт', 'parent_id' => 3],
            ['title' => 'Такси', 'parent_id' => 3],
            ['title' => 'Бензин', 'parent_id' => 3],
            ['title' => 'Автостраховка', 'parent_id' => 3],
            ['title' => 'Аренда машины', 'parent_id' => 3],
            ['title' => 'Ремонт и обслуживание машины', 'parent_id' => 3],
            ['title' => 'Билеты на самолёт/поезд', 'parent_id' => 3],
            ['title' => 'Электричество', 'parent_id' => 4],
            ['title' => 'Вода', 'parent_id' => 4],
            ['title' => 'Газ', 'parent_id' => 4],
            ['title' => 'Интернет', 'parent_id' => 4],
            ['title' => 'Мобильная связь', 'parent_id' => 4],
            ['title' => 'Вывоз мусора', 'parent_id' => 4],
            ['title' => 'Медицинская страховка', 'parent_id' => 5],
            ['title' => 'Страхование жизни', 'parent_id' => 5],
            ['title' => 'Автострахование', 'parent_id' => 5],
            ['title' => 'Страхование недвижимости', 'parent_id' => 5],
            ['title' => 'Походы к врачу', 'parent_id' => 6],
            ['title' => 'Стоматология', 'parent_id' => 6],
            ['title' => 'Лекарства', 'parent_id' => 6],
            ['title' => 'Финасовая подушка', 'parent_id' => 7],
            ['title' => 'Пенсионный счёт', 'parent_id' => 7],
            ['title' => 'Накопления', 'parent_id' => 7],
            ['title' => 'Инвестиции', 'parent_id' => 7],
            ['title' => 'Репетиторы', 'parent_id' => 8],
            ['title' => 'Книги и материалы', 'parent_id' => 8],
            ['title' => 'Оплата обучения/курсов', 'parent_id' => 8],
            ['title' => 'Кредит на учёбу', 'parent_id' => 8],
            ['title' => 'Забота о себе', 'parent_id' => 9],
            ['title' => 'Одежда', 'parent_id' => 9],
            ['title' => 'Абонемент в спортзал', 'parent_id' => 9],
            ['title' => 'Подписки', 'parent_id' => 9],
            ['title' => 'Кино/Театр', 'parent_id' => 10],
            ['title' => 'Спортивные события', 'parent_id' => 10],
            ['title' => 'Хобби', 'parent_id' => 10],
            ['title' => 'Отпуск/путешествия', 'parent_id' => 10],
            ['title' => 'Подарки', 'parent_id' => 11],
            ['title' => 'Празднование', 'parent_id' => 11],
            ['title' => 'Ипотека', 'parent_id' => 12],
            ['title' => 'Личный долг', 'parent_id' => 12],
            ['title' => 'Непридвиденные расходы', 'parent_id' => 13]
        );
        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
