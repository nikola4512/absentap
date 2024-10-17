<?php

namespace App\Http\Controllers\UserParent;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Student;
use App\Models\StudentAbsent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PermissionController extends Controller
{

    public function index(Request $request){
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Dashboard',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $data['murid'] = Student::count();
        return view('parent.permission', compact('config', 'data'));
    }

    public function store(Request $request){
        DB::beginTransaction();
        try{
            $currentDate = date('Y-m-d');
            $nik = auth()->user()->nik;
            StudentAbsent::updateOrCreate(
                ['nik' => $nik, 'rec_date' => $currentDate],
                ['rec_date' => $currentDate, 'nik' => $nik, 'kehadiran' => $request['permission-type'], 'note' => $request->note]
            );
            DB::commit();
            return response()->json(['message' => 'Data has been save', 'redirect' => 'parent/permission']);
        } catch(\Throwable $throw){
            DB::rollBack();
            Log::error($throw);
            return response()->json(['error' => $throw->getMessage()]);
        }


    }
}
