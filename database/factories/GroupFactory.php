<?php

namespace Database\Factories;

use App\Http\Controllers\SchoolYearController;
use App\Models\Headquarters;
use App\Models\StudyTime;
use App\Models\StudyYear;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'school_year_id' => SchoolYearController::current_year()->id,
            'headquarters_id' => Headquarters::all()->random()->id,
            'study_time_id' => StudyTime::all()->random()->id,
            'study_year_id' => StudyYear::all()->random()->id,
            'teacher_id' => null,
            'name' => $this->faker->streetName(),
        ];
    }
}
