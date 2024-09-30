<?php

namespace Database\Factories;

use App\Models\Datapengukuran;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatapengukuranFactory extends Factory
{
    protected $model = Datapengukuran::class;

    public function definition()
    {
        $faker = \Faker\Factory::create('id_ID');
        $jenistest = $faker->randomElement(['AUDIO', 'VISUAL']);
        $tanggal = $faker->dateTimeBetween('2024-01-01', '2024-07-31');
        $lokasi = $faker->city;
        $gagal = $faker->numberBetween(0, 3);

        return [
            'tanggal' => $tanggal->format('Y-m-d H:i'),
            'lokasi' => $lokasi,
            'jenistest' => $jenistest,
            'gagal' => $gagal,
            'nama_observant' => null, // Placeholder, will be set in the seeder
        ];
    }

    public function withNamaDataAndObserver($nama_observant)
    {
        return $this->state(function (array $attributes) use ($nama_observant) {
            return [
                'namadata' => $nama_observant . '-' . $attributes['tanggal'],
                'nama_observant' => $nama_observant, // Set the observer name here
            ];
        });
    }
}
