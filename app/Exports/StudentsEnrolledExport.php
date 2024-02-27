<?php

namespace App\Exports;

use App\Http\Controllers\SchoolYearController;
use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class StudentsEnrolledExport implements FromArray, WithHeadings, ShouldAutoSize, WithStyles/* , WithEvents */
{
    private $Y;
    private $attributes;
    private $request;

    public function __construct($attributes, $request)
    {
        $this->Y = SchoolYearController::current_year();
        $this->attributes = $attributes;
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function array(): array
    {
        $fn_gs = fn ($gs) =>
        $gs->withWhereHas('group',
            fn ($g) => $g->with('headquarters', 'studyYear', 'studyTime')
                        ->where('school_year_id', $this->Y->id)
                        ->whereIn('headquarters_id', $this->request->headquarters)
                        ->whereIn('study_time_id', $this->request->study_time)
                        ->whereIn('study_year_id', $this->request->study_year)
            );

        $students = Student::withWhereHas('groupYear', $fn_gs)
        ->when($this->request->has('inclusive'), function ($inclusive) { $inclusive->where('inclusive', TRUE); })
        ->when($this->request->has('repeats'), function ($repeats) { $repeats->where('status', 'repeat'); })
        ->get();

        $array = [];

        foreach ($students as $student) {

            $row = [];

            if ($this->attributes['headquarters'])
                array_push($row, $student->groupYear->group->headquarters->name);
            if ($this->attributes['study_time'])
                array_push($row, $student->groupYear->group->studyTime->name);
            if ($this->attributes['study_year'])
                array_push($row, $student->groupYear->group->studyYear->name);
            if ($this->attributes['group'])
                array_push($row, $student->groupYear->group->name);

            array_push($row,
                $student->first_last_name,
                $student->second_last_name,
                $student->first_name,
                $student->second_name,
            );

            if ($this->attributes['document'])
                array_push($row, $student->document_type_code, $student->document);
            if ($this->attributes['email'])
                array_push($row, $student->institutional_email);
            if ($this->attributes['telephone'])
                array_push($row, $student->telephone);
            if ($this->attributes['country'])
                array_push($row, $student->country?->name);
            if ($this->attributes['bith_city'])
                array_push($row, $student->birthCity?->department->name, $student->birthCity?->name);
            if ($this->attributes['birthdate'])
                array_push($row, $student->birthdate);
            if ($this->attributes['age'])
                array_push($row, $student->age());
            if ($this->attributes['gender'])
                array_push($row, $student->gender?->name);
            if ($this->attributes['rh'])
                array_push($row, $student->rh?->name);
            if ($this->attributes['zone'])
                array_push($row, __($student->zone));
            if ($this->attributes['residence_city'])
                array_push($row, $student->residenceCity?->name);
            if ($this->attributes['address'])
                array_push($row, $student->address);
            if ($this->attributes['social_stratum'])
                array_push($row, $student->social_stratum);
            if ($this->attributes['dwelling_type'])
                array_push($row, __($student->dwellingType?->name));
            if ($this->attributes['neighborhood'])
                array_push($row, $student->neighborhood);
            if ($this->attributes['sisben'])
                array_push($row, $student->sisben?->name);
            if ($this->attributes['health_manager'])
                array_push($row, $student->healthManager?->name);
            if ($this->attributes['tutor']) {
                array_push($row, $student->myTutorIs?->name);
                array_push($row, $student->myTutorIs?->telephone ?: $student->myTutorIs?->cellphone);
                array_push($row, $student->myTutorIs?->email);
            }
            if ($this->attributes['enrolled_date'])
                array_push($row, $student->enrolled_date);


            array_push($array, $row);
        }

        return $array;
    }

    public function headings(): array
    {
        $titles = [];

        if ($this->attributes['headquarters'])
            array_push($titles, "Sede");
        if ($this->attributes['study_time'])
            array_push($titles, "Jornada");
        if ($this->attributes['study_year'])
            array_push($titles, "Año de estudio");
        if ($this->attributes['group'])
            array_push($titles, "Grupo");

        array_push($titles,
            "Primer apellido",
            "Segundo apellido",
            "Primer nombre",
            "Segundo nombre",
        );

        if ($this->attributes['document'])
            array_push($titles, "Tipo doc.", "Documento");
        if ($this->attributes['email'])
            array_push($titles, "Correo electrónico");
        if ($this->attributes['telephone'])
            array_push($titles, "Teléfono");
        if ($this->attributes['country'])
            array_push($titles, "País de origen");
        if ($this->attributes['bith_city'])
            array_push($titles, "Departamento de nacimiento", "Ciudad de nacimiento");
        if ($this->attributes['birthdate'])
            array_push($titles, "Fecha de nacimiento");
        if ($this->attributes['age'])
            array_push($titles, "Edad (en años)");
        if ($this->attributes['gender'])
            array_push($titles, "Género");
        if ($this->attributes['rh'])
            array_push($titles, "RH");
        if ($this->attributes['zone'])
            array_push($titles, "Zona");
        if ($this->attributes['residence_city'])
            array_push($titles, "Ciudad de residencia");
        if ($this->attributes['address'])
            array_push($titles, "Dirección");
        if ($this->attributes['social_stratum'])
            array_push($titles, "Estrato social");
        if ($this->attributes['dwelling_type'])
            array_push($titles, "Tipo de vivienda");
        if ($this->attributes['neighborhood'])
            array_push($titles, "Barrio");
        if ($this->attributes['sisben'])
            array_push($titles, "Sisben");
        if ($this->attributes['health_manager'])
            array_push($titles, "Administradora de salud");
        if ($this->attributes['tutor']) {
            array_push($titles, "Nombre del acudiente");
            array_push($titles, "Teléfono del acudiente");
            array_push($titles, "Correo del acudiente");
        }
        if ($this->attributes['enrolled_date'])
            array_push($titles, "Fecha de matrícula");


        return $titles;
    }

    public function styles(Worksheet $sheet)
    {
        return [1 => ['font' => ['bold' => true]]];
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    /* public function registerEvents(): array
    {
        return [];
    } */
}
