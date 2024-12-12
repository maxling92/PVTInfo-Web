<?php

namespace Database\Factories;

use App\Models\Datahasil;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatahasilFactory extends Factory
{
    protected $model = Datahasil::class;

    public function definition()
    {
        return [
         'waktu_milidetik' => $this->faker->numberBetween(230, 1000),
        ];
    }
}
