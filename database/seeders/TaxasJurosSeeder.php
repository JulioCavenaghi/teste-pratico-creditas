<?php

namespace Database\Seeders;

use App\Models\TaxaJuro;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaxasJurosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaxaJuro::insert([
            ['idade_min' => 0, 'idade_max' => 25, 'taxa_juros' => 5.00  , 'moeda'=>'BRL', 'created_at' => Carbon::now()],
            ['idade_min' => 0, 'idade_max' => 25, 'taxa_juros' => 4.00  , 'moeda'=>'USD', 'created_at' => Carbon::now()],
            ['idade_min' => 0, 'idade_max' => 25, 'taxa_juros' => 3.00  , 'moeda'=>'EUR', 'created_at' => Carbon::now()],
            ['idade_min' => 26, 'idade_max' => 40, 'taxa_juros' => 3.00 , 'moeda'=>'BRL', 'created_at' => Carbon::now()],
            ['idade_min' => 26, 'idade_max' => 40, 'taxa_juros' => 2.50 , 'moeda'=>'USD', 'created_at' => Carbon::now()],
            ['idade_min' => 26, 'idade_max' => 40, 'taxa_juros' => 3.00 , 'moeda'=>'EUR', 'created_at' => Carbon::now()],
            ['idade_min' => 41, 'idade_max' => 60, 'taxa_juros' => 2.00 , 'moeda'=>'BRL', 'created_at' => Carbon::now()],
            ['idade_min' => 41, 'idade_max' => 60, 'taxa_juros' => 2.30 , 'moeda'=>'USD', 'created_at' => Carbon::now()],
            ['idade_min' => 41, 'idade_max' => 60, 'taxa_juros' => 0.80 , 'moeda'=>'EUR', 'created_at' => Carbon::now()],
            ['idade_min' => 61, 'idade_max' => 130, 'taxa_juros' => 4.00, 'moeda'=>'BRL', 'created_at' => Carbon::now()],
            ['idade_min' => 61, 'idade_max' => 130, 'taxa_juros' => 4.00, 'moeda'=>'USD', 'created_at' => Carbon::now()],
            ['idade_min' => 61, 'idade_max' => 130, 'taxa_juros' => 4.00, 'moeda'=>'EUR', 'created_at' => Carbon::now()],
        ]);
    }
}
