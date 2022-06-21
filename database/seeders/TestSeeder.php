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
        $corzo = Headquarters::create(['name' => 'corzo', 'available' => TRUE]);

        $mañana = StudyTime::create(['name' => 'mañana']);
        $tarde = StudyTime::create(['name' => 'tarde']);
        $noche = StudyTime::create(['name' => 'noche']);

        $sexto = StudyYear::create(['name' => 'sexto', 'available' => TRUE]);
        $septimo = StudyYear::create(['name' => 'septimo', 'available' => TRUE]);

        $r_area_ciencias = ResourceArea::create(['name' => 'ciencias naturales']);
        $r_area_matematicas = ResourceArea::create(['name' => 'matematicas']);

        $r_materia_biologia = ResourceSubject::create(['name' => 'biologia']);
        $r_materia_geometria = ResourceSubject::create(['name' => 'geometria']);

        $ciencias_biologia = Subject::create(['school_year_id' => $year2021->id, 'resource_area_id' => $r_area_ciencias->id, 'resource_subject_id' => $r_materia_biologia->id]);
        $ciencias_biologia = Subject::create(['school_year_id' => $year2021->id, 'resource_area_id' => $r_area_ciencias->id, 'resource_subject_id' => $r_materia_biologia->id]);
        $matematicas_geometria = Subject::create(['school_year_id' => $year->id, 'resource_area_id' => $r_area_matematicas->id, 'resource_subject_id' => $r_materia_geometria->id]);
        $matematicas_geometria = Subject::create(['school_year_id' => $year->id, 'resource_area_id' => $r_area_matematicas->id, 'resource_subject_id' => $r_materia_geometria->id]);

        Group::create([
            'school_year_id' => $year2021->id,
            'headquarters_id' => $principal->id,
            'study_time_id' => $mañana->id,
            'study_year_id' => $sexto->id,
            'name' => '601']);

        Group::create([
            'school_year_id' => $year2021->id,
            'headquarters_id' => $principal->id,
            'study_time_id' => $mañana->id,
            'study_year_id' => $sexto->id,
            'name' => '602']);

        Group::create([
            'school_year_id' => $year2021->id,
            'headquarters_id' => $principal->id,
            'study_time_id' => $tarde->id,
            'study_year_id' => $septimo->id,
            'name' => '701']);

        Group::create([
            'school_year_id' => $year2021->id,
            'headquarters_id' => $principal->id,
            'study_time_id' => $tarde->id,
            'study_year_id' => $septimo->id,
            'name' => '702']);

        $grupo601 = Group::create([
            'school_year_id' => $year->id,
            'headquarters_id' => $principal->id,
            'study_time_id' => $mañana->id,
            'study_year_id' => $sexto->id,
            'name' => '601']);

        $grupo602 = Group::create([
            'school_year_id' => $year->id,
            'headquarters_id' => $principal->id,
            'study_time_id' => $mañana->id,
            'study_year_id' => $sexto->id,
            'name' => '602']);

        $grupo701 = Group::create([
            'school_year_id' => $year->id,
            'headquarters_id' => $principal->id,
            'study_time_id' => $tarde->id,
            'study_year_id' => $septimo->id,
            'name' => '701']);

        $grupo702 = Group::create([
            'school_year_id' => $year->id,
            'headquarters_id' => $principal->id,
            'study_time_id' => $tarde->id,
            'study_year_id' => $septimo->id,
            'name' => '702']);

        $p_study = PeriodType::create(['name' => 'study']);
        $p_resit = PeriodType::create(['name' => 'resit']);

        $periodo1_mañana = Period::create([
            'school_year_id' => $year->id,
            'headquarters_id' => $principal->id,
            'study_time_id' => $mañana->id,
            'period_type_id' => $p_study->id,
            'name' => 'periodo 1',
            'start' => '2022-01-01',
            'end' => '2022-01-31']);

        $periodo2_mañana = Period::create([
            'school_year_id' => $year->id,
            'headquarters_id' => $principal->id,
            'study_time_id' => $mañana->id,
            'period_type_id' => $p_study->id,
            'name' => 'periodo 2',
            'start' => '2022-02-01',
            'end' => '2022-02-28']);

        $periodo3_mañana = Period::create([
            'school_year_id' => $year->id,
            'headquarters_id' => $principal->id,
            'study_time_id' => $mañana->id,
            'period_type_id' => $p_study->id,
            'name' => 'periodo 3',
            'start' => '2022-03-01',
            'end' => '2022-03-31']);

        $periodo1_tarde = Period::create([
            'school_year_id' => $year->id,
            'headquarters_id' => $principal->id,
            'study_time_id' => $tarde->id,
            'period_type_id' => $p_study->id,
            'name' => 'periodo 1',
            'start' => '2022-01-01',
            'end' => '2022-01-31']);

        $periodo2_tarde = Period::create([
            'school_year_id' => $year->id,
            'headquarters_id' => $principal->id,
            'study_time_id' => $tarde->id,
            'period_type_id' => $p_study->id,
            'name' => 'periodo 2',
            'start' => '2022-02-01',
            'end' => '2022-02-28']);


        /* USER */
        $docente01 = User::create(['name' => 'docente01', 'email' => 'docente01@logro']);
        $docente02 = User::create(['name' => 'docente02', 'email' => 'docente02@logro']);
        $docente03 = User::create(['name' => 'docente03', 'email' => 'docente03@logro']);

        Teacher::create(['id' => $docente01->id, 'telephone' => '00001']);
        Teacher::create(['id' => $docente02->id, 'telephone' => '00002']);
        Teacher::create(['id' => $docente03->id, 'telephone' => '00003']);

        $biologia601 = TeacherSubjectGroup::create([
            'teacher_id' => $docente01->id,
            'subject_id' => $ciencias_biologia->id,
            'group_id' => $grupo601->id]);

        $biologia602 = TeacherSubjectGroup::create([
            'teacher_id' => $docente01->id,
            'subject_id' => $ciencias_biologia->id,
            'group_id' => $grupo602->id]);

        $biologia701 = TeacherSubjectGroup::create([
            'teacher_id' => $docente02->id,
            'subject_id' => $ciencias_biologia->id,
            'group_id' => $grupo701->id]);

        $biologia702 = TeacherSubjectGroup::create([
            'teacher_id' => $docente02->id,
            'subject_id' => $ciencias_biologia->id,
            'group_id' => $grupo702->id]);

        $geometria701 = TeacherSubjectGroup::create([
            'teacher_id' => $docente03->id,
            'subject_id' => $matematicas_geometria->id,
            'group_id' => $grupo701->id]);

        $geometria702 = TeacherSubjectGroup::create([
            'teacher_id' => $docente03->id,
            'subject_id' => $matematicas_geometria->id,
            'group_id' => $grupo702->id]);



        /* USER */
        $estudiante01 = User::create(['name' => 'estudiante01', 'email' => 'estudiante01@logro']);
        $estudiante02 = User::create(['name' => 'estudiante02', 'email' => 'estudiante02@logro']);
        $estudiante03 = User::create(['name' => 'estudiante03', 'email' => 'estudiante03@logro']);
        $estudiante04 = User::create(['name' => 'estudiante04', 'email' => 'estudiante04@logro']);
        $estudiante05 = User::create(['name' => 'estudiante05', 'email' => 'estudiante05@logro']);
        $estudiante06 = User::create(['name' => 'estudiante06', 'email' => 'estudiante06@logro']);
        $estudiante07 = User::create(['name' => 'estudiante07', 'email' => 'estudiante07@logro']);
        $estudiante08 = User::create(['name' => 'estudiante08', 'email' => 'estudiante08@logro']);

        Student::create(['id' => $estudiante01->id, 'telephone' => '00001']);
        Student::create(['id' => $estudiante02->id, 'telephone' => '00002']);
        Student::create(['id' => $estudiante03->id, 'telephone' => '00003']);
        Student::create(['id' => $estudiante04->id, 'telephone' => '00004']);
        Student::create(['id' => $estudiante05->id, 'telephone' => '00005']);
        Student::create(['id' => $estudiante06->id, 'telephone' => '00006']);
        Student::create(['id' => $estudiante07->id, 'telephone' => '00007']);
        Student::create(['id' => $estudiante08->id, 'telephone' => '00008']);

        $est01_grupo601 = GroupStudent::create(['group_id' => $grupo601->id, 'student_id' => $estudiante01->id]);
        $est02_grupo601 = GroupStudent::create(['group_id' => $grupo601->id, 'student_id' => $estudiante02->id]);
        $est03_grupo602 = GroupStudent::create(['group_id' => $grupo602->id, 'student_id' => $estudiante03->id]);
        $est04_grupo602 = GroupStudent::create(['group_id' => $grupo602->id, 'student_id' => $estudiante04->id]);
        $est05_grupo701 = GroupStudent::create(['group_id' => $grupo701->id, 'student_id' => $estudiante05->id]);
        $est06_grupo701 = GroupStudent::create(['group_id' => $grupo701->id, 'student_id' => $estudiante06->id]);
        $est07_grupo702 = GroupStudent::create(['group_id' => $grupo702->id, 'student_id' => $estudiante07->id]);
        $est08_grupo702 = GroupStudent::create(['group_id' => $grupo702->id, 'student_id' => $estudiante08->id]);



    }
}
