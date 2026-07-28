<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    private static ?string $password;

    private static array $indonesianNames = [
        'Rizky Pratama', 'Sari Dewi', 'Bima Santoso', 'Nadia Putri',
        'Farhan Ramadhan', 'Aulia Rahmawati', 'Dimas Aditya', 'Kirana Salsabila',
        'Hafizh Maulana', 'Zahra Amelia', 'Reza Mahendra', 'Indah Permata',
        'Gilang Purnomo', 'Anisa Fitriani', 'Wahyu Setiawan', 'Putri Maharani',
        'Andi Kusuma', 'Mega Lestari', 'Bagas Dwi', 'Cindy Cahyani',
    ];

    private static int $nameIndex = 0;

    public function definition(): array
    {
        $name = self::$indonesianNames[self::$nameIndex % count(self::$indonesianNames)];
        self::$nameIndex++;

        $username = Str::slug(explode(' ', $name)[0]) . '_' . $this->faker->numberBetween(10, 99);

        return [
            'name'              => $name,
            'username'          => $username,
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => self::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
            'bio'               => $this->faker->optional(0.6)->sentence(rand(8, 15)),
            'role'              => $this->faker->randomElement(['student', 'student', 'student', 'lecturer']),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attr) => ['email_verified_at' => null]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attr) => ['role' => 'admin']);
    }
}
