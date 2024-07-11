<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller {
    public function index(Request $request) {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Profile',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => '/admin', 'title' => 'Dashboard'],
            ['disabled' => true, 'url' => '#', 'title' => 'Profile'],
        ];
        $data = User::with('roles')->findOrFail(auth()->user()->id);
        return view('admin.profile.index', compact('config', 'breadcrumbs', 'data'));
    }

    public function edit() {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Edit Profile',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => '/admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => '/admin/profile', 'title' => 'Profile'],
            ['disabled' => true, 'url' => '#', 'title' => 'Edit Profile'],
        ];
        $data = User::with('roles')->findOrFail(auth()->user()->id);
        return view('admin.profile.form', compact('config', 'breadcrumbs', 'data'));
    }

    public function edit_password() {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Edit Password',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => '/admin', 'title' => 'Dashboard'],
            ['disabled' => false, 'url' => '/admin/profile', 'title' => 'Profile'],
            ['disabled' => true, 'url' => '#', 'title' => 'Edit Password'],
        ];
        return view('admin.profile.form-password', compact('config', 'breadcrumbs'));
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|unique:users,email,' . $id,
            'image' => 'nullable|image|mimes:jpg,png,jpeg',
        ], [
            'unique' => ':attribute is not available'
        ]);

        $data = User::findOrFail($id);
        if (!$validator->fails()) {
            $req = $validator->safe()->all();
            DB::beginTransaction();
            try {
                $data->update([
                    'name' => $req['name'],
                    'email' => $req['email'],
                ]);
                $destinationPath = public_path('/avatar');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 755, true);
                }
                if (!empty($req['image']) && file_exists($destinationPath . $req['image'])) {
                    unlink($destinationPath . $req['image']);
                }
                if (isset($req['image']) && !empty($req['image'])) {
                    $image = $req['image'];
                    $req['image'] = $data['username'] . '_' . time() . '.' . $image->extension();
                    $img = Image::make($image->path());
                    $img->fit(300, 300, function ($constraint) {
                        $constraint->upsize();
                    })->save($destinationPath . '/' . $req['image']);
                    $data->update(['image' => $req['image']]);
                }
                DB::commit();
                $response = response()->json(['message' => 'Data has been save', 'redirect' => '/admin/profile']);
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

    public function change_password(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required',
            'password_confirmation'  => 'required|same:password'
        ], [
            'same' => ':attribute is not same'
        ]);

        $data = User::findOrFail($id);
        if (!$validator->fails()) {
            DB::beginTransaction();
            $dataval = $validator->safe()->all();
            $credentials = [
                'username'  => $data->username,
                'password'  => $dataval['old_password'],
                'active' => 1
            ];
            if (Auth::attempt($credentials)) {
                if (isset($dataval['password']) && !empty($dataval['password'])) {
                    $password = Hash::make($dataval['password']);
                    $data->update([
                        'password' => $password
                    ]);
                }
                try {
                    DB::commit();
                    $response = response()->json(['message' => 'Data has been save', 'redirect' => '/admin/profile']);
                } catch (\Throwable $throw) {
                    DB::rollBack();
                    Log::error($throw);
                    $response = response()->json(['error' => $throw->getMessage()]);
                }
            } else {
                $response = response()->json(['error' => ['Old Password not match!']]);
            }
        } else {
            $response = response()->json(['error' => $validator->errors()]);
        }
        return $response;
    }
}
