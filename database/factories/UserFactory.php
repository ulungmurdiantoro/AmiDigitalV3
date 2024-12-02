<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Jenjang;
use App\Models\StandarAkreditasi;
use App\Models\Jurusan;
use App\Models\Fakultas;
use App\Models\KategoriDokumen;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'users_code' => $this->faker->unique()->numerify('USER###'),
            'user_id' => $this->faker->user_id(),
            'user_nama' => $this->faker->name(),
            'user_jabatan' => $this->faker->jobTitle(),
            'user_penempatan' => $this->faker->city(),
            'username' => $this->faker->unique()->userName(),
            'password' => static::$password ??= Hash::make('password'),
            'user_level' => $this->faker->randomElement(['admin', 'user', 'auditor']),
            'user_status' => $this->faker->randomElement(['active', 'inactive']),
        ];
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn () => ['user_level' => 'admin']);
    }

    /**
     * Indicate that the user is active.
     */
    public function active(): static
    {
        return $this->state(fn () => ['user_status' => 'active']);
    }
}

class JenjangFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Jenjang::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jenjang_kode' => $this->faker->unique()->numerify('JENJANG###'),
            'jenjang_nama' => $this->faker->name(),
        ];
    }

}

class StandarAkreditasiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StandarAkreditasi::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'standar_akreditasis_kode' => $this->faker->unique()->numerify('STANDARAKRE###'),
            'standar_akreditasis_nama' => $this->faker->name(),
        ];
    }

}

class JurusanFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Jurusan::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'jurusan_kode' => $this->faker->unique()->numerify('JURUSAN###'),
            'jurusan_nama' => $this->faker->name(),
        ];
    }

}

class FakultasFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Fakultas::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fakultas_kode' => $this->faker->unique()->numerify('FAKULTAS###'),
            'fakultas_nama' => $this->faker->name(),
        ];
    }

}

class KategoriDokumenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = KategoriDokumen::class;

    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kategori_dokumen' => $this->faker->name(),
        ];
    }

}
