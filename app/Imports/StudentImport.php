<?php

namespace App\Imports;

use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Student([
            'nama' => $row['nama'],
            'nik' => $row['nik'],
            'nisn' => $row['nisn'],
            'rombel' => $row['rombel_saat_ini'],
            'password' => Hash::make($row['nik']),
            'jk' => $row['jk']
        ]);
    }
}