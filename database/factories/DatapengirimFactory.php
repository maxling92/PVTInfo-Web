<?php

namespace Database\Factories;

use App\Models\Datapengirim;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatapengirimFactory extends Factory
{
    protected $model = Datapengirim::class;

    public function definition()
    {
        $faker = \Faker\Factory::create('id_ID');
        $birthYear = $this->faker->numberBetween(1965, 1995);
        $jabatan = $birthYear <= 1980 ? 'Supir Senior' : 'Supir Junior';

        return [
            'nama_observant' => $faker->unique()->name(),
            'jabatan' => $jabatan,
            'tgllahir' => $faker->dateTimeBetween('1965-01-01', '1995-12-31')->format('Y-m-d'),
            'nama_perusahaan' => 'Petros'
        ];
    }
}

