<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::table('labels')->insert([
             [
                 'name' => 'ошибка',
                 'description' => 'Ошибка в коде или дизайне',
                 'created_at' => Carbon::now(),
             ],
             [
                 'name' => 'документация',
                 'description' => 'Улучшение или добавление документации',
                 'created_at' => Carbon::now(),
             ],
             [
                 'name' => 'дубликат',
                 'description' => 'Повтор другой задачи',
                 'created_at' => Carbon::now(),
             ],
             [
                 'name' => 'доработка',
                 'description' => 'Улучшение существующей функциональности',
                 'created_at' => Carbon::now(),
             ],
         ]);
    }
}
