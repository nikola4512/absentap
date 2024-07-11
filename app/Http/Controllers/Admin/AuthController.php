<?php

namespace App\Http\Controllers\admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller {
    public function index() {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Login',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        return view('admin.login', compact('config', 'data'));
    }

    public function authenticate(Request $request) {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);

        if (!$validator->fails()) {
            $data = $validator->safe()->all();
            $credentials = [
                'username'     => $data['username'],
                'password'  => $data['password'],
                'active' => 1
            ];
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $response = response()->json(['status' => 'success', 'message' => 'Login Success, Please wait!', 'redirect' => '/admin']);
            } else {
                $response = response()->json(['status' => 'error', 'message' => 'Username or Password or Status not match!']);
            }
        } else {
            $response = response()->json(['error' => $validator->errors()]);
        }
        return $response;
    }

    public function logout(Request $request) {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
