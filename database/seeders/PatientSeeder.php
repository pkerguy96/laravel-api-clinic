<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use App\Models\Patient;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Faker\Factory as Faker;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Seed 10 patients
        for ($i = 1; $i <= 10; $i++) {
            DB::table('patients')->insert([
                'nom' => Str::random(10),
                'prenom' => Str::random(10),
                'cin' => Str::random(6),
                'mutuelle' => Str::random(10),
                'date' => Carbon::now()->subYears(rand(18, 60))->format('Y-m-d'),
                'address' => Str::random(10),
                'sex' => 'male',
                'phone_number' => $faker->randomNumber(6),
            ]);
        }
    }
}
