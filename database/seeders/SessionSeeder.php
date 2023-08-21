<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            DB::beginTransaction();

            // create default session
            $data = [
                [
                    'name' => 'Sesi 1',
                    'time_start' => Carbon::parse('08:00:00'),
                    'time_end' => Carbon::parse('11:00:00'),
                ],
                [
                    'name' => 'Sesi 2',
                    'time_start' => Carbon::parse('13:00:00'),
                    'time_end' => Carbon::parse('15:00:00'),
                ],
                [
                    'name' => 'Sesi 3',
                    'time_start' => Carbon::parse('15:00:00'),
                    'time_end' => Carbon::parse('17:00:00'),
                ],
                [
                    'name' => 'Sesi 4',
                    'time_start' => Carbon::parse('23:49:00'),
                    'time_end' => Carbon::parse('23:53:00'),
                ]
            ];

            DB::table('sessions')->insert($data);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
