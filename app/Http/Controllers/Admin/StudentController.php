<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use App\Models\Student;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\StudentImport;
use File;

class StudentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:student', ['only' => ['index', 'read', 'update']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Students',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => '#', 'title' => 'Students'],
        ];

        if ($request->ajax()) {
            $active = $request['active'];
            $data = Student::when($active, function ($query, $active) {
                if ($active == 'non_active') {
                    return $query->where('status', '0');
                } else {
                    return $query->where('status', '1');
                }
            });
            return DataTables::of($data)->addIndexColumn()->addColumn('action', function ($row) {
                $actionBtn = '<a href="#" data-bs-toggle="modal" data-bs-target="#modalView" data-bs-id="' . $row->id . '" class="btn btn-sm btn-outline-primary">Detail</a>';
                return $actionBtn;
            })->rawColumns(['action'])->make(true);
        }
        return view('admin.student', compact('config', 'breadcrumbs'));
    }

    public function read($id)
    {
        $data = Student::where('id', $id)->first();
        $response = response()->json(['data' => $data]);
        return $response;
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'card_code' => 'nullable|unique:students,card_code'
        ]);

        if (!$validator->fails()) {
            $data = Student::findOrFail($id);
            $req['card_code'] = $request->card_code;
            DB::beginTransaction();
            try {
                $data->update($req);
                DB::commit();
                $response = response()->json(['status' => 'success', 'message' => 'Data has been updated']);
            } catch (\Throwable $throw) {
                DB::rollBack();
                Log::error($throw);
                $response = response()->json(['error' => $throw->getMessage()]);
            }
        } else {
            $error = $validator->errors()->first();
            if ($error == 'Kartu Telah Terdaftar') {
                $response = response()->json(['error' => $error]);
            } else {
                $response = response()->json(['error' => $validator->errors()]);
            }
        }
        return $response;
    }

    public function import_excel(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        if (!$validator->fails()) {
            $file = $request->file('file');
            DB::beginTransaction();
            try {
                $nama_file = rand() . $file->getClientOriginalName();
                $file->move('file_import', $nama_file);
                Excel::import(new StudentImport, public_path('/file_import/' . $nama_file));
                DB::commit();
                if (File::exists(public_path('/file_import/' . $nama_file))) {
                    File::delete(public_path('/file_import/' . $nama_file));
                }
                $response = response()->json(['status' => 'success', 'message' => 'Data has been imported']);
            } catch (\Throwable $throw) {
                DB::rollBack();
                Log::error($throw);
                $response = response()->json(['error' => $throw->getMessage()]);
            }
        } else {
            $error = $validator->errors()->first();
            $response = response()->json(['error' => $error]);
        }
        return $response;
    }
}