<?php

namespace App\Http\Controllers\UserParent;

use App\Models\Student;
use App\Models\Setting;
use App\Models\StudentRec;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Models\SchoolTime;
use App\Models\StudentAbsent;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Student Report',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        return view('parent.dashboard', compact('config'));
    }

    public function getRecapAbsent(Request $request) //CONTROLLER TABLE RECAP ABSENT
    {
        if ($request->ajax()) {
            $nik = auth()->user()->nik;
            $studentAbsentRecap = StudentAbsent::select(
                DB::raw('CAST(COALESCE(SUM(CASE WHEN kehadiran = 1 THEN 1 ELSE 0 END), 0) AS UNSIGNED) as hadir_sum'),
                DB::raw('CAST(COALESCE(SUM(CASE WHEN kehadiran = 2 THEN 1 ELSE 0 END), 0) AS UNSIGNED) as izinsakit_sum'),
                DB::raw('CAST(COALESCE(SUM(CASE WHEN kehadiran = 3 THEN 1 ELSE 0 END), 0) AS UNSIGNED) as izin_sum'),
                DB::raw('CAST(COALESCE(SUM(CASE WHEN kehadiran = 4 THEN 1 ELSE 0 END), 0) AS UNSIGNED) as noinfo_sum'))
                ->where('nik', $nik)
                ->get();
    
            $studentAbsentRecap = $studentAbsentRecap->map(function ($item) {
                $item->tidakhadir_sum = $item->izinsakit_sum + $item->izin_sum + $item->noinfo_sum;
                return $item;
            });
            return response()->json($studentAbsentRecap);
        }
        return abort(404);
    }

    public function schoolTime(Request $request) //CONTROLLER HEADER TABLE DAILY ABSENT
    {
        if ($request->ajax()) {
            $schoolTimeName = SchoolTIme::select('name')->orderBy('id', 'asc')->get();
            return datatables::of($schoolTimeName)->make(true);
        }
        return abort(404);
    }

    public function dailyAbsent(Request $request) //CONTROLLER TABLE DAILY ABSENT
    {
        // if ($request->ajax()) {
            $nik = auth()->user()->nik;
            $studentAbsent = StudentAbsent::select('rec_date', 'kehadiran', 'note')
                ->where('nik', $nik)
                ->get();
            $studentRec = StudentRec::select('rec_date', 'rec_detail')
                ->where('student_nik', $nik)
                ->get();
            $studentAbsent->map(function ($item) {
                switch ($item->kehadiran) {
                    case '1':
                        $item->kehadiran = 'Hadir';
                        break;
                    case '2':
                        $item->kehadiran = 'Izin sakit';
                        break;
                    case '3':
                        $item->kehadiran = 'Izin';
                        break;
                    case '4':
                        $item->kehadiran = 'Tanpa keterangan';
                        break;
                    default:
                        $item->kehadiran = 'Unknown';
                        break;
                }
                return $item;
            });
            foreach ($studentAbsent as $sa) {
                foreach ($studentRec as $sr) {
                    if ($sr->rec_date == $sa->rec_date) {
                        $rd = json_decode($sr->rec_detail);
                        $sa->rec_detail = $rd;
                        $sa->schooltime_count = SchoolTime::count();
                    }
                }
            }
            return response()->json($studentAbsent);
            // return response()->json(Datatables::of($studentAbsent)->make(true));
        // } 
        // return abort(404);
    }
}