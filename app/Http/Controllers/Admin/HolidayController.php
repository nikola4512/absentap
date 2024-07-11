<?php

namespace App\Http\Controllers\Admin;

use DateTime;
use Carbon\Carbon;
use App\Models\Holiday;
use App\Models\Setting;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class HolidayController extends Controller
{

    function __construct()
    {
        $this->middleware('permission:HolidayDate', ['only' => ['index', 'store', 'delete']]);
    }

    public function index(Request $request)
    {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Holiday',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => '#', 'title' => 'Holiday'],
        ];

        Carbon::setLocale('id');

        if ($request->ajax()) {
            $active = $request['active'];
            $data = Holiday::all();
            return DataTables::of($data)->addIndexColumn()
                ->editColumn('date', function ($date) {
                    return Carbon::parse($date->date)->translatedFormat('l, d F Y');
                })
                ->addColumn('checkbox', function ($row) {
                    return '<input type="checkbox" class="holiday_checkbox" value="' . $row->id . '" />';
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="#" data-bs-toggle="modal" data-bs-target="#modalView" data-bs-id="' . $row->id . '" class="btn btn-sm btn-outline-primary">Detail</a>';
                    return $actionBtn;
                })->rawColumns(['checkbox', 'action'])->make(true);
        }
        return view('admin.holiday', compact('config', 'breadcrumbs'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|max:255',
            'name' => 'required|max:255',
        ]);

        if (!$validator->fails()) {
            $data = $validator->safe();
            DB::beginTransaction();
            try {
                $date = explode('to', $data['date']);
                if (!empty(trim($date[1]))) {
                    $start = new DateTime(trim($date[0]));
                    $end = new DateTime(trim($date[1]));
                    $end = $end->modify('+1 day');
                    $interval = new \DateInterval('P1D');
                    $period = new \DatePeriod($start, $interval, $end);
                    foreach ($period as $date) {
                        $multiInsert['date'] = $date->format('Y-m-d');
                        $multiInsert['name'] = $data['name'];
                        Holiday::create($multiInsert);
                    }
                } else {
                    $data['date'] = trim($data['date']);
                    Holiday::create($data);
                }
                DB::commit();
                $response = response()->json(['message' => 'Data has been save', 'redirect' => 'admin/holidays']);
            } catch (\Throwable $throw) {
                DB::rollBack();
                Log::error($throw);
                $response = response()->json(['error' => $throw->getMessage()]);
            }
        } else {
            $response = response()->json(['error' => $validator->errors()]);
        }
        return $response;
    }

    public function delete(Request $request)
    {
        $id_array = $request->input('id');
        $data = Holiday::whereIn('id', $id_array);
        if ($data->delete()) {
            $response = response()->json(['status' => 'success', 'message' => 'Data has been delete']);
        } else {
            $response = response()->json(['status' => 'failed', 'message' => 'Data cant delete']);
        }
        return $response;
    }
}