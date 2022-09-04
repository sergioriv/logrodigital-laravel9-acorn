<?php

namespace Database\Factories;

use App\Http\Controllers\SchoolYearController;
use App\Models\City;
use App\Models\Country;
use App\Models\Disability;
use App\Models\DocumentType;
use App\Models\DwellingType;
use App\Models\Gender;
use App\Models\Headquarters;
use App\Models\HealthManager;
use App\Models\Rh;
use App\Models\SchoolYear;
use App\Models\Sisben;
use App\Models\StudyTime;
use App\Models\StudyYear;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $user = User::create([
            'name' => $this->faker->firstName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ])->assignRole(7);

        return [
            'id' => $user->id,
            'first_name' => $user->name,
            'second_name' => $this->faker->firstName(),
            'first_last_name' => $this->faker->lastName(),
            'second_last_name' => $this->faker->lastName(),
            'institutional_email' => $user->email,
            'document_type_code' => DocumentType::all()->random()->code,
            'document' => $this->faker->numberBetween(100000, 199999999),
            'country_id' => 42,
            'birth_city_id' => City::all()->random()->id,
            'gender_id' => Gender::all()->random()->id,
            'rh_id' => Rh::all()->random()->id,

            'zone' => $this->faker->randomElement(['rural', 'urban']),
            'residence_city_id' => City::all()->random()->id,
            'address' => $this->faker->streetName(),
            'social_stratum' => $this->faker->numberBetween(1, 6),
            'dwelling_type_id' => DwellingType::all()->random()->id,
            'neighborhood' => $this->faker->citySuffix(),
            'electrical_energy' => $this->faker->randomElement([true, false]),
            'natural_gas' => $this->faker->randomElement([true, false]),
            'sewage_system' => $this->faker->randomElement([true, false]),
            'aqueduct' => $this->faker->randomElement([true, false]),
            'internet' => $this->faker->randomElement([true, false]),
            'lives_with_father' => $this->faker->randomElement([true, false]),
            'lives_with_mother' => $this->faker->randomElement([true, false]),
            'lives_with_siblings' => $this->faker->randomElement([true, false]),
            'lives_with_other_relatives' => $this->faker->randomElement([true, false]),

            'health_manager_id' => HealthManager::all()->random()->id,
            'school_insurance' => $this->faker->numberBetween(1000, 59999),
            'sisben_id' => Sisben::all()->random()->id,
            'disability_id' => Disability::all()->random()->id,

            'school_year_create' => SchoolYearController::current_year()->id,
            'headquarters_id' => Headquarters::all()->random()->id,
            'study_time_id' => StudyTime::all()->random()->id,
            'study_year_id' => StudyYear::all()->random()->id,
            'data_treatment' => TRUE
        ];
    }
}
