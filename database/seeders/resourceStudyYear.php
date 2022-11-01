<?php

namespace Database\Seeders;

use App\Models\ResourceStudyYear as ModelsResourceStudyYear;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class resourceStudyYear extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $preescolar = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'preschool'
        ]);
        $primero = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'first grade'
        ]);
        $segundo = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'second grade'
        ]);
        $tercero = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'third grade'
        ]);
        $cuarto = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'fourth grade'
        ]);
        $quinto = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'fifth grade'
        ]);
        $sexto = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'sixth grade'
        ]);
        $septimo = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'seventh grade'
        ]);
        $octavo = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'eighth grade'
        ]);
        $noveno = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'ninth grade'
        ]);
        $decimo = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'tenth grade'
        ]);
        $once = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'eleventh grade'
        ]);
        $ciclo_1 = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'first cycle'
        ]);
        $ciclo_2 = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'second cycle'
        ]);
        $ciclo_3 = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'third cycle'
        ]);
        $ciclo_4 = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'fourth cycle'
        ]);
        $ciclo_5 = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'fifth cycle'
        ]);
        $ciclo_6 = ModelsResourceStudyYear::create([
            'uuid' => Str::uuid()->toString(),
            'name' => 'sixth cycle'
        ]);

        /* actualizar el año siguiente de cada grado */

        $this->chooseNextYear($preescolar, $primero);
        $this->chooseNextYear($primero, $segundo);
        $this->chooseNextYear($segundo, $tercero);
        $this->chooseNextYear($tercero, $cuarto);
        $this->chooseNextYear($cuarto, $quinto);
        $this->chooseNextYear($quinto, $sexto);
        $this->chooseNextYear($sexto, $septimo);
        $this->chooseNextYear($septimo, $octavo);
        $this->chooseNextYear($octavo, $noveno);
        $this->chooseNextYear($noveno, $decimo);
        $this->chooseNextYear($decimo, $once);
        $this->chooseNextYear($ciclo_1, $ciclo_2);
        $this->chooseNextYear($ciclo_2, $ciclo_3);
        $this->chooseNextYear($ciclo_3, $ciclo_4);
        $this->chooseNextYear($ciclo_4, $ciclo_5);
        $this->chooseNextYear($ciclo_5, $ciclo_6);

    }

    private function chooseNextYear($a, $b)
    {
        $a->next_year = $b->uuid;
        $a->save();
    }
}
