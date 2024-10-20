<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\StudentRec;

class Student extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];

    public function student_rec()
    {
        return $this->hasMany(StudentRec::class,'nik', 'student_nik');
    }
}