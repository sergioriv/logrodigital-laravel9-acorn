<?php

namespace Database\Seeders;

use App\Models\Group;
use App\Models\GroupStudent;
use App\Models\Headquarters;
use App\Models\Period;
use App\Models\PeriodType;
use App\Models\ResourceArea;
use App\Models\ResourceSubject;
use App\Models\SchoolYear;
use App\Models\Student;
use App\Models\StudyTime;
use App\Models\StudyYear;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubjectGroup;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $year2021 = SchoolYear::create(['name' => '2021', 'available' => FALSE]);
        $year = SchoolYear::create(['name' => '2022', 'available' => TRUE]);

        $principal = Headquarters::create(['name' => 'principal', 'available' => TRUE]);
        $mañana = StudyTime::create(['name' => 'mañana']);
        $preescolar = StudyYear::create(['name' => 'pre-escolar', 'available' => TRUE]);

        /* USER */
        $estudiante01 = User::create([
            'name' => 'estudiante',
            'email' => 'estudiante@logro.digital',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ])->assignRole(7);

        Student::create(['id' => $estudiante01->id,
            'first_name' => $estudiante01->name,
            'first_last_name' => 'last name',
            'institutional_email' => $estudiante01->email,
            'school_year_create' => $year->id,
            'headquarters_id' => $principal->id,
            'study_time_id' => $mañana->id,
            'study_year_id' => $preescolar->id
        ]);

    }
}
