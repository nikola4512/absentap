<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class SettingController extends Controller {

    function __construct() {
        $this->middleware('permission:settings', ['only' => ['index', 'store', 'update_identity']]);
    }

    public function index() {
        $data = Setting::whereIn('name', ['web_title', 'web_description'])->get();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $config = [
            'title' => $data[0]->value . ' - Settings',
            'description' => $data[1]->value,
            'first_logo' => $identity[0]->value,
            'second_logo' => $identity[1]->value,
            'first_title' => $identity[2]->value,
            'second_title' => $identity[3]->value
        ];
        $breadcrumbs = [
            ['disabled' => false, 'url' => '/admin', 'title' => 'Dashboard'],
            ['disabled' => true, 'url' => '#', 'title' => 'Settings'],
        ];
        $data = [];
        $identity = [];
        $notin = ['logo_one', 'logo_two', 'title_one', 'title_two'];
        foreach (Setting::whereIn('type', ['text', 'textarea'])->get() as $key => $item) {
            if (in_array($item['name'], $notin)) {
                $get[$item['name']] = $item['value'];
                $identity = $get;
            } else {
                $getlive[$key] = $item;
                $data = $getlive;
            }
        }
        return view('admin.settings', compact('config', 'breadcrumbs', 'data', 'identity'));
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), []);

        if (!$validator->fails()) {
            DB::beginTransaction();
            try {
                $data = Setting::findOrFail($request['pk']);
                $data->update([
                    'value' => $request['value'],
                ]);
                DB::commit();
                $response = response()->json(['status' => 'success', 'message' => 'Data has been save', 'redirect' => 'admin/settings']);
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

    public function update_identity(Request $request) {
        DB::beginTransaction();
        $identity = Setting::whereIn('name', ['logo_one', 'logo_two', 'title_one', 'title_two'])->get();
        $old_image_one = $identity[0]->value;
        $old_image_two = $identity[1]->value;
        try {
            if (isset($request['logo_one']) && !empty($request['logo_one'])) {
                if ($old_image_one != 'images/logomsonly.png') {
                    unlink($old_image_one);
                }
                $image = $request['logo_one'];
                $name_image = 'logo_one.' . $image->extension();
                $img = Image::make($image->path());
                $img->save(public_path('/images') . '/' . $name_image);
                Setting::where('name', '=', 'logo_one')->update(['value' => 'images/' . $name_image]);
            }
            if (!empty($old_image_two) && $old_image_two != 'images/logomsonly.png') {
                unlink($old_image_two);
                Setting::where('name', '=', 'logo_two')->update(['value' => '']);
            }
            if (isset($request['logo_two']) && !empty($request['logo_two'])) {
                $image2 = $request['logo_two'];
                $name_image2 = 'logo_two.' . $image2->extension();
                $img2 = Image::make($image2->path());
                $img2->save(public_path('/images') . '/' . $name_image2);
                Setting::where('name', '=', 'logo_two')->update(['value' => 'images/' . $name_image2]);
            }
            if (isset($request['title_one']) && !empty($request['title_one'])) {
                Setting::where('name', '=', 'title_one')->update(['value' => $request['title_one']]);
            }
            if (isset($request['title_two']) && !empty($request['title_two'])) {
                Setting::where('name', '=', 'title_two')->update(['value' => $request['title_two']]);
            }
            DB::commit();
            $response = response()->json(['message' => 'Data has been save', 'redirect' => 'admin/settings']);
        } catch (\Throwable $throw) {
            DB::rollBack();
            Log::error($throw);
            $response = response()->json(['error' => $throw->getMessage()]);
        }
        return $response;
    }
}