<?php

namespace App\Http\Controllers\admin;

use DB;
use File;
use DataTables;
use Illuminate\Http\Request;
use App\Models\admin\Setting;
use App\Http\Controllers\Controller;
use Intervention\Image\Facades\Image;

class SettingController extends Controller
{
    private static $module = "settings";

    public function main(){
        if (!isAllowed(static::$module, "main")) {
            abort(403);
        }
        
        return view('administrator.settings.main');
    }

    public function frontpage(){
        if (!isAllowed(static::$module, "frontpage")) {
            abort(403);
        }
        
        return view('administrator.settings.frontpage');
    }

    public function admin(){
        if (!isAllowed(static::$module, "admin")) {
            abort(403);
        }
        
        return view('administrator.settings.admin');
    }

    public function admin_general()
    {
        //Check permission
        if (!isAllowed(static::$module, "admin_general")) {
            abort(403);
        }
        $settings = Setting::get()->toArray();
        
        $settings = array_column($settings, 'value', 'name');

        // Ambil pengaturan dari database dan tampilkan di halaman
        return view('administrator.settings.admin.index', compact('settings'));
    }

    public function admin_general_update(Request $request)
    {
        // return $request;
        //Check permission
        if (!isAllowed(static::$module, "admin_general")) {
            abort(403);
        }

        

        $settings = Setting::get()->toArray();
        $settings = array_column($settings, 'value', 'name');

        
        $data_settings = [];
        $data_settings["nama_app_admin"] = $request->nama_app_admin;

        if (!empty($request->footer_app_admin)) {
            $data_settings["footer_app_admin"] = $request->footer_app_admin;
        }

        if (!empty($request->admin_main_background_color)) {
            $data_settings["admin_main_background_color"] = $request->admin_main_background_color;
        }
        

        if ($request->hasFile('logo_app_admin')) {
            if (array_key_exists("logo_app_admin", $settings)) {
                $imageBefore = $settings["logo_app_admin"];
                if (!empty($settings["logo_app_admin"])) {
                    $image_path = "./administrator/assets/media/settings/" . $settings["logo_app_admin"];
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
            }

            $image = $request->file('logo_app_admin');
            $fileName  =  'logo_app_admin.' . $image->getClientOriginalExtension();
            $path = upload_path('settings') . $fileName;
            Image::make($image->getRealPath())->save($path, 100);
            $data_settings['logo_app_admin'] = $fileName;
        }

        if ($request->hasFile('favicon')) {
            if (array_key_exists("favicon", $settings)) {
                $imageBefore = $settings["favicon"];
                if (!empty($settings["favicon"])) {
                    $image_path = "./administrator/assets/media/settings/" . $settings["favicon"];
                    if (File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }
            }

            $image = $request->file('favicon');
            $fileName  =  'favicon.' . $image->getClientOriginalExtension();
            $path = upload_path('settings') . $fileName;
            Image::make($image->getRealPath())->save($path, 100);
            $data_settings['favicon'] = $fileName;
        }
        

        $logs = []; // Buat array kosong untuk menyimpan log

        foreach ($data_settings as $key => $value) {
            $data = [];

            if (array_key_exists($key, $settings)) {
                $data["value"] = $value;
                $set = Setting::where('name', $key)->first();
                $set->update($data);

                $logs[] = ['---'.$key.'---' => ['Data Sebelumnya' => ['value' => $settings[$key]], 'Data terbaru' => ['value' => $value]]];
            } else {
                $data["name"] = $key;
                $data["value"] = $value;
                $set = Setting::create($data);

                $logs[] = $set;
            }
        }

        //Write log
        createLog(static::$module, __FUNCTION__, 0,$logs);

        return redirect(route('admin.settings.admin.general'))->with(['success' => 'Data berhasil di update.']);
    }

    public function admin_smtp()
    {
        //Check permission
        if (!isAllowed(static::$module, "admin_smtp")) {
            abort(403);
        }
        $settings = Setting::get()->toArray();
        
        $settings = array_column($settings, 'value', 'name');

        // Ambil pengaturan dari database dan tampilkan di halaman
        return view('administrator.settings.admin.smtp', compact('settings'));
    }

    public function admin_smtp_update(Request $request)
    {
        // return $request;
        //Check permission
        if (!isAllowed(static::$module, "admin_smtp")) {
            abort(403);
        }

        $settings = Setting::get()->toArray();
        $settings = array_column($settings, 'value', 'name');

        
        $data_settings = [];
        $data_settings["smtp_host_admin"] = $request->smtp_host_admin;
        $data_settings["smtp_security_admin"] = $request->smtp_security_admin;
        $data_settings["smtp_port_admin"] = $request->smtp_port_admin;
        $data_settings["smtp_user_admin"] = $request->smtp_user_admin;
        $data_settings["smtp_password_admin"] = $request->smtp_password_admin;


        $logs = []; // Buat array kosong untuk menyimpan log

        foreach ($data_settings as $key => $value) {
            $data = [];

            if (array_key_exists($key, $settings)) {
                $data["value"] = $value;
                $set = Setting::where('name', $key)->first();
                $set->update($data);

                $logs[] = ['---'.$key.'---' => ['Data Sebelumnya' => ['value' => $settings[$key]], 'Data terbaru' => ['value' => $value]]];
            } else {
                $data["name"] = $key;
                $data["value"] = $value;
                $set = Setting::create($data);

                $logs[] = $set;
            }
        }

        //Write log
        createLog(static::$module, __FUNCTION__, 0,$logs);

        return redirect(route('admin.settings.admin.smtp'))->with(['success' => 'Data berhasil di update.']);
    }
}
