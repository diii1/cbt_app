<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SchoolProfile;

class SchoolProfileSeeder extends Seeder
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

            SchoolProfile::create([
                'name' => 'MTs Faqih Hasyim',
                'contact' => '03124511452',
                'email' => 'mts.faqih@cbt.com',
                'address' => 'Jl. Siwalan Panji No. 1',
                'district' => 'Buduran',
                'regency' => 'Sidoarjo',
                'province' => 'Jawa Timur',
                'acreditation' => 'A',
            ]);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }
}
