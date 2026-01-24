<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * @return void
     */
    public function run()
    {
        DB::table('task_statuses')->insert([
            [
                'name' => 'новая',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'завершена',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'выполняется',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => '	в архиве',
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}
