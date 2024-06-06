<?php

namespace Database\Seeders;

use App\Models\AttendanceStudent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddIdAttentanceStudent extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();
        foreach ( AttendanceStudent::cursor() as $key => $attend ) {
            DB::update("UPDATE attendance_students SET id = ? WHERE attendance_id = ? AND student_id = ?", [
                ($key+1),
                $attend->attendance_id,
                $attend->student_id,
            ]);
        }
        DB::commit();
    }
}
