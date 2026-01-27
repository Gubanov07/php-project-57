<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tasks')->insert([
            [
                'status_id' => 1,
                'name' => 'Исправить баг в авторизации',
                'description' => 'Пользователи не могут войти с правильными credentials',
                'created_by_id' => 1,
                'assigned_to_id' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'status_id' => 2,
                'name' => 'Добавить пагинацию на страницу задач',
                'description' => 'При большом количестве задач страница грузится медленно',
                'created_by_id' => 2,
                'assigned_to_id' => 2,
                'created_at' => Carbon::now(),
            ],
            [
                'status_id' => 3,
                'name' => 'Реализовать поиск по задачам',
                'description' => 'Пользователи хотят искать задачи по названию и описанию',
                'created_by_id' => 2,
                'assigned_to_id' => 1,
                'created_at' => Carbon::now(),
            ],
            [
                'status_id' => 4,
                'name' => 'Доработать команду подготовки БД',
                'description' => 'За одно добавить тестовых данных',
                'created_by_id' => 1,
                'assigned_to_id' => 2,
                'created_at' => Carbon::now(),
            ],
            [
                'status_id' => 1,
                'name' => 'Оптимизировать производительность базы данных',
                'description' => 'Добавить индексы на часто используемые поля',
                'created_by_id' => 3,
                'assigned_to_id' => 4,
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}
