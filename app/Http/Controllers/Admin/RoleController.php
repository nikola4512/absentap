<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller {

    function __construct() {
        $this->middleware('permission:role-list', ['only' => ['index']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    private function fromCamelCase($camelCaseString) {
        $splitCamelArray = preg_split('/(?=[A-Z])/', $camelCaseString);
        return ucwords(implode(" ", $splitCamelArray));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Roles',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => 'admin/users', 'title' => 'Users'],
            ['disabled' => false, 'url' => '#', 'title' => 'Roles'],
        ];

        if ($request->ajax()) {
            $data = Role::where('name', '!=', 'Super Admin')->get();
            return DataTables::of($data)->addIndexColumn()->addColumn('action', function ($row) {
                $actionBtn = '<div class="dropdown">
                <button type="button" class="btn btn-sm btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-cog fa-fw"></i> Aksi</button>
                <ul class="dropdown-menu">';
                if (auth()->user()->hasPermissionTo('role-edit') || auth()->user()->hasRole('Super Admin')) {
                    $actionBtn .= '<li><a class="dropdown-item" href="admin/users/roles/' . $row->id . '/edit">Ubah</a></li>';
                };
                if (auth()->user()->hasPermissionTo('role-delete') && $row->id > 3 || auth()->user()->hasRole('Super Admin') && $row->id > 3) {
                    $actionBtn .= '<li><a href="#" data-bs-toggle="modal" data-bs-target="#modalDelete" data-bs-id="' . $row->id . '" class="delete dropdown-item">Hapus</a></li>';
                };
                $actionBtn .= '</ul></div> ';
                return $actionBtn;
            })->rawColumns(['action'])->make(true);
        }
        return view('admin.roles.index', compact('config', 'breadcrumbs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Tambah Roles',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => 'admin/users', 'title' => 'Users'],
            ['disabled' => false, 'url' => 'admin/users/roles', 'title' => 'Roles'],
            ['disabled' => true, 'url' => '#', 'title' => 'Tambah'],
        ];
        $permission = Permission::get();
        $perms = array();
        $firstName = '';
        foreach ($permission as $key => $value) {
            $get = explode('-', $value['name']);
            if ($firstName != $this->fromCamelCase($get[0])) {
                $firstName = $this->fromCamelCase($get[0]);
                $perms[$firstName] = array();
                $value['func_name'] = isset($get[1]) && !empty($get[1]) ? $this->fromCamelCase($get[1]) : $firstName;
                array_push($perms[$firstName], $value);
            } else {
                $value['func_name'] = isset($get[1]) && !empty($get[1]) ? $this->fromCamelCase($get[1]) : $firstName;
                array_push($perms[$firstName], $value);
            }
        }
        return view('admin.roles.form', compact('config', 'breadcrumbs', 'perms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        if (!$validator->fails()) {
            $data = $validator->safe()->all();
            DB::beginTransaction();
            try {
                $role = Role::create(['name' => $data['name']]);
                $role->syncPermissions($data['permission']);
                DB::commit();
                $response = response()->json(['message' => 'Data has been save', 'redirect' => 'admin/users/roles']);
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

    /**
     * Display the specified resource.
     */
    public function show($id) {
        // $role = Role::find($id);
        // $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
        //     ->where("role_has_permissions.role_id", $id)
        //     ->get();

        // return view('roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Edit Roles',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => 'admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => 'admin/users', 'title' => 'Users'],
            ['disabled' => false, 'url' => 'admin/users/roles', 'title' => 'Roles'],
            ['disabled' => true, 'url' => '#', 'title' => 'Tambah'],
        ];
        $role = Role::find($id);
        $permission = Permission::get();
        $perms = array();
        $firstName = '';
        foreach ($permission as $key => $value) {
            $get = explode('-', $value['name']);
            if ($firstName != $this->fromCamelCase($get[0])) {
                $firstName = $this->fromCamelCase($get[0]);
                $perms[$firstName] = array();
                $value['func_name'] = isset($get[1]) && !empty($get[1]) ? $this->fromCamelCase($get[1]) : $firstName;
                array_push($perms[$firstName], $value);
            } else {
                $value['func_name'] = isset($get[1]) && !empty($get[1]) ? $this->fromCamelCase($get[1]) : $firstName;
                array_push($perms[$firstName], $value);
            }
        }
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
        return view('admin.roles.form', compact('config', 'breadcrumbs', 'role', 'perms', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:roles,name,' . $id,
            'permission' => 'required',
        ]);

        if (!$validator->fails()) {
            $data = $validator->safe()->all();
            DB::beginTransaction();
            try {
                $role = Role::find($id);
                $role->name = $data['name'];
                $role->save();
                $role->syncPermissions($data['permission']);
                DB::commit();
                $response = response()->json(['message' => 'Data has been save', 'redirect' => 'admin/users/roles']);
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        $data = Role::findOrFail($id);
        if ($data->delete()) {
            $response = response()->json(['status' => 'success', 'message' => 'Data has been delete']);
        } else {
            $response = response()->json(['status' => 'failed', 'message' => 'Data cant delete']);
        }

        return $response;
    }
}
