<?php

namespace Database\Factories;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Factory as FakerFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Siswa>
 */
class SiswaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nis' => $this->faker->unique()->numerify('#####'),
            'nama' => $this->faker->name(),
            'ttl' => $this->faker->dateTimeThisCentury()->format('Y-m-d'),
            'gender' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'alamat' => '',
            'wa' => $this->faker->phoneNumber(),
            'kelas' => $this->faker->randomElement(['10', '11', '12']),
            'email' => $this->faker->unique()->safeEmail(),
            'foto' => 'https://via.placeholder.com/640x480.png?text=Foto+Siswa', // Placeholder image
        ];
    }
}
