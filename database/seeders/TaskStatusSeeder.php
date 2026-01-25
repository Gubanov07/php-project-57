<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
                'name' => 'в работе',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'на тестировании',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => '	завершена',
                'created_at' => Carbon::now(),
            ],
        ]);
    }
}
