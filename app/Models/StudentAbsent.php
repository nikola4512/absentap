<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\StudentRec;
use App\Models\SchoolTime;
use Carbon\Carbon;

class StudentAbsent extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function student()
    {
        return $this->hasOne(Student::class, 'nik', 'student_nik');
    }
}
