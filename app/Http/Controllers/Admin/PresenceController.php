<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use Carbon\Carbon;
use App\Models\Setting;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentRec;
use App\Models\StudentAbsent;
use Illuminate\Support\Facades\Validator;
use Mockery\Undefined;

class PresenceController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:Parentreport', ['only' => ['index', 'store', 'delete']]);
    }

    public function index(Request $request)
    {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Presence',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => '#', 'title' => 'Presence'],
        ];

        Carbon::setLocale('id');

        if ($request->ajax()) {
            $students = DB::table('students')->get();

            $data = $students->map(function ($student) {
                $absentData = DB::table('student_absents')
                    ->select(
                        DB::raw('SUM(CASE WHEN kehadiran = 1 THEN 1 ELSE 0 END) as hadir_sum'),
                        DB::raw('SUM(CASE WHEN kehadiran = 2 THEN 1 ELSE 0 END) as izinsakit_sum'),
                        DB::raw('SUM(CASE WHEN kehadiran = 3 THEN 1 ELSE 0 END) as izin_sum'),
                        DB::raw('SUM(CASE WHEN kehadiran = 4 THEN 1 ELSE 0 END) as noinfo_sum')
                    )
                    ->where('nik', $student->nik)
                    ->groupBy('nik')
                    ->first();

                return [
                    'nik' => $student->nik,
                    'nama' => $student->nama,
                    'hadir_sum' => $absentData->hadir_sum ?? 0,
                    'izinsakit_sum' => $absentData->izinsakit_sum ?? 0,
                    'izin_sum' => $absentData->izin_sum ?? 0,
                    'noinfo_sum' => $absentData->noinfo_sum ?? 0,
                    'tidakhadir_sum' => ($absentData->izinsakit_sum ?? 0) + ($absentData->izin_sum ?? 0) + ($absentData->noinfo_sum ?? 0)
                ];
            });
            return DataTables::of($data)->addIndexColumn()->make(true);
        }
        return view('admin.presence', compact('config', 'breadcrumbs'));
    }

    public function update(Request $request) {
        try {
            DB::beginTransaction();
            $niks = Student::pluck('nik');
            $todayDate = date('Y-m-d');
            $kehadiran = null;
            foreach ($niks as $nik) {
                $isExists = StudentRec::where('student_nik', $nik)
                    ->where('rec_date', $todayDate)
                    ->exists();

                $hasDone =StudentAbsent::where('student_nik', $nik)
                    ->where('rec_date', $todayDate)
                    ->exists();
    
                if(!$hasDone) {
                    if($isExists) {
                        $studentAbsentRec = StudentRec::where('student_nik', $nik)
                            ->where('rec_date', $todayDate)
                            ->first();
                        $kehadiran = ($studentAbsentRec->rec_times == $studentAbsentRec->rec_sum) ? 1 : 4;
                    } else {
                        $kehadiran = 4;
                    }
                    StudentAbsent::create([
                        'rec_date' => $todayDate,
                        'nik' => $nik,
                        'kehadiran' => $kehadiran,
                    ]);
                }
            }
            DB::commit();
            return response()->json(['message' => 'Data has been synchronized', 'redirect' => 'parent/permission']);
        } catch (\Throwable $throw) {
            DB::rollBack();
            Log::error($throw);
            return response()->json(['error' => $throw->getMessage()]);
        }
    }
}