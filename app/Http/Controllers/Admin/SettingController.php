<?php

namespace App\Http\Controllers\Admin;

use App\Models\add;
use App\Models\Admin;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;


class SettingController extends Controller
{


    public function editMyProfile()
    {
        $html_breadcrumbs = [
            'title' => __('views.My Profile'),
            'subtitle' => __('views.Index'),

        ];

        $admin = auth()->guard('Admin')->user();
        $update_url = route('admin.update');
        $index_url = route('admin.dashboard.index');
        return view('admin.setting.edit',
            compact('admin', 'index_url', 'update_url', 'html_breadcrumbs'));
    }

    public function updateMyProfile(Request $request)
    {

        $rules = [
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',


        ];

        $this->validate($request, $rules);

        $admin = auth()->guard('Admin')->user();

        Admin::find($admin->id)
            ->update($request->only([
                'name',
                'mobile',
                'email',
                'active_payment_link'


            ]));


        if ($request->get('password')) {
            if (!\Hash::check($request->get('old_password'), $admin->password)) {
                return redirect()->route('admin.edit')->with(['incorrect_pass' => trans('auth.password_incorrect')]);

            }
            $user = Admin::find($admin->id);
            $user->password = \Hash::make($request->get('password'));
            $user->save();
        }


        \Session::flash('success', trans('admin.success_update'));

        return redirect()->route('admin.edit');
    } //<--- End Method


    public function index(Request $request)
    {


        $html_breadcrumbs = [
            'title' => __('views.Dashboard'),
            'subtitle' => __('views.Index'),
        ];

        return view('admin.dashboard.index', compact('html_breadcrumbs'));


    }


    public function lang(Request $request, $lang)
    {
        if (!in_array($lang, ['en', 'ar'])) {
            $lang = 'ar';
        }
        \Session::put('locale', $lang);
        return back();
    }






    public function editSettings()
    {
        $html_breadcrumbs = [
            'title' => __('views.Settings'),
            'subtitle' => __('views.Index'),

        ];

        $settings = Setting::pluck('value_ar', 'key');
        $settings_en = Setting::pluck('value_en', 'key');


        $update_url = route('admin.settings.update');
        $index_url = route('admin.dashboard.index');
        return view('admin.setting.edit_setting',
            compact('settings', 'settings_en', 'index_url', 'update_url', 'html_breadcrumbs'));
    }

    public function updateSettings(Request $request)
    {


        $rules = [
            'about_us_ar' => 'required',
            'about_us_en' => 'required',
            'privacy_policy_en' => 'required',
            'privacy_policy_ar' => 'required',
            'refer_ar' => 'required',
            'refer_en' => 'required',
            'twitter' => 'required',
            'facebook' => 'required',
            'linkedin' => 'required',
            'instagram' => 'required',
            'FAQs_ar' => 'required',
            'FAQs_en' => 'required',
            'tearms_ar' => 'required',
            'tearms_en' => 'required',
            'email' => 'required',
            'sharepoint' => 'required',
            'storypoint' => 'required',
            'addspoint' => 'required',
            'storydays' => 'required',
            'sharePrice' => 'required',
            'point_price' => 'required',
            'dayPrice' => 'required',
            'unreactstorytime' => 'required',
            'mobile' => 'required',
            'whatsapp' => 'required',
            'google_map_key' => 'required',


        ];

        $this->validate($request, $rules);

        $admin = auth()->guard('Admin')->user();
        $setting_about = Setting::where('key', 'about_us')->update([
            'value_ar' => $request->get('about_us_ar'),
            'value_en' => $request->get('about_us_en')
        ]);
        $setting_privacy_policy = Setting::where('key', 'privacy_policy')->update([
            'value_ar' => $request->get('privacy_policy_ar'),
            'value_en' => $request->get('privacy_policy_en')
        ]);
        $setting_refer = Setting::where('key', 'refer')->update([
            'value_ar' => $request->get('refer_ar'),
            'value_en' => $request->get('refer_en')
        ]);

        $setting_twitter = Setting::where('key', 'twitter')->update([
            'value_ar' => $request->get('twitter'),
            'value_en' => $request->get('twitter')
        ]);
        $setting_facebook = Setting::where('key', 'facebook')->update([
            'value_ar' => $request->get('facebook'),
            'value_en' => $request->get('facebook')
        ]);

        $setting_linkedin = Setting::where('key', 'linkedin')->update([
            'value_ar' => $request->get('linkedin'),
            'value_en' => $request->get('linkedin')
        ]);

        $setting_instagram = Setting::where('key', 'instagram')->update([
            'value_ar' => $request->get('instagram'),
            'value_en' => $request->get('instagram')
        ]);
        $setting_FAQs = Setting::where('key', 'FAQs')->update([
            'value_ar' => $request->get('FAQs_ar'),
            'value_en' => $request->get('FAQs_en')
        ]);

        $setting_tearms = Setting::where('key', 'tearms')->update([
            'value_ar' => $request->get('tearms_ar'),
            'value_en' => $request->get('tearms_en')
        ]);

        $setting_email = Setting::where('key', 'email')->update([
            'value_ar' => $request->get('email'),
            'value_en' => $request->get('email')
        ]);

        $setting_sharepoint = Setting::where('key', 'sharepoint')->update([
            'value_ar' => $request->get('sharepoint'),
            'value_en' => $request->get('sharepoint')
        ]);
        $setting_storypoint = Setting::where('key', 'storypoint')->update([
            'value_ar' => $request->get('storypoint'),
            'value_en' => $request->get('storypoint')
        ]);
        $setting_whatsapp = Setting::where('key', 'whatsapp')->update([
            'value_ar' => $request->get('whatsapp'),
            'value_en' => $request->get('whatsapp')
        ]);
        $setting_addspoint = Setting::where('key', 'addspoint')->update([
            'value_ar' => $request->get('addspoint'),
            'value_en' => $request->get('addspoint')
        ]);
        $setting_storydays = Setting::where('key', 'storydays')->update([
            'value_ar' => $request->get('storydays'),
            'value_en' => $request->get('storydays')
        ]);
        $setting_sharePrice = Setting::where('key', 'sharePrice')->update([
            'value_ar' => $request->get('sharePrice'),
            'value_en' => $request->get('sharePrice')
        ]);
        $setting_point_price = Setting::where('key', 'point_price')->update([
            'value_ar' => $request->get('point_price'),
            'value_en' => $request->get('point_price')
        ]);
        $setting_dayPrice = Setting::where('key', 'dayPrice')->update([
            'value_ar' => $request->get('dayPrice'),
            'value_en' => $request->get('dayPrice')
        ]);
        $setting_unreactstorytime = Setting::where('key', 'unreactstorytime')->update([
            'value_ar' => $request->get('unreactstorytime'),
            'value_en' => $request->get('unreactstorytime')
        ]);
        $setting_mobile = Setting::where('key', 'mobile')->update([
            'value_ar' => $request->get('mobile'),
            'value_en' => $request->get('mobile')
        ]);
        $setting_google_map_key = Setting::where('key', 'google_map_key')->update([
            'value_ar' => $request->get('google_map_key'),
            'value_en' => $request->get('google_map_key')
        ]);


        \Session::flash('success', trans('admin.success_update'));

        return redirect()->route('admin.settings.edit');
    } //<--- End Method


    public function adds()
    {
        $index_url = route('admin.adds.datatable');
        $edit_url = route('admin.adds.edit', 'id');
        $create_url = route('admin.adds.create');


        $object = new add();

        $html_breadcrumbs = [
            'title' => __('views.adds'),
            'subtitle' => __('views.Index'),
            'datatable' => true,
        ];


        return view(
            'admin.adds.index',
            compact(
                'html_breadcrumbs',


                'index_url',
                'object',
                'edit_url',
                'create_url',

            )
        );
    }


    public function createAdds(Request $request)
    {
        $settings = Setting::pluck('value_ar', 'key');

        // dd($categories->toArray());
        $index_url = route('admin.adds.index');
        $create_url = route('admin.adds.store');

        $html_breadcrumbs = [
            'title' => __('views.adds'),
            'subtitle' => __('views.Add'),
        ];

        return view(
            'admin.adds.create',
            compact('html_breadcrumbs', 'index_url', 'create_url','settings')
        );
    }

    public function addsStore(Request $request)
    {


        // dd($request->all());


        $rules = [
            'img' => 'required',
            'url' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'type' => 'required',
            //  'plan'               => 'required',

        ];

        $this->validate($request, $rules);


        $add = add::create([
            'img' => 'required',
            'url' => $request->get('url'),
            'lat' => $request->get('lat'),
            'long' => $request->get('long'),
            'type' => $request->get('type'),
        ]);

        if (($request->hasFile('img'))) {
//////

            $destinationPath = public_path('uploads/add/');
            $xy = $request->file('img');
            $unix_timestamp = date('m/d/Y h:i:s a', time());
            //return $xy;


            $extension = strtolower($xy->getClientOriginalExtension());
            //return $extension;
            if (in_array($extension, $this->image_extensions())) {

                $path = $request->file('img')->store('public/adds/photo');
                $path = str_replace('public', 'storage', $path);

                $add->img = $path;
                $add->save();


            }


        }
        \Session::flash('success', trans('تم ارسال بيانات الدفع بنجاح'));

        return redirect()->route('admin.adds.index');
    }


    public function Addsdatatable(Request $request)
    {


        //  dd($request->get('query')['neighborhood_id']);

        $adds = add::query();


        if ($request->get('type')) {


            $adds = $adds->where('type', '=', $request->get('type'));


        }


        $data = process_datatable_query($adds->orderBy('id', 'desc'), function (
            $query,
            $search
        ) {
            return $query
                ->where(function ($query) use ($search) {


                    $query->Where('lat', 'like', '%' . $search . '%')
                        ->orWhere('long', 'like', '%' . $search . '%');


                });


        });
        return $data;
    }

    public function edit($id)
    {
        $add = add::find($id);
        $settings = Setting::pluck('value_ar', 'key');

        // dd($categories->toArray());
        $index_url = route('admin.adds.index');
        $update_url = route('admin.adds.update', $id);

        $html_breadcrumbs = [
            'title' => __('views.adds'),
            'subtitle' => __('views.Edit'),
        ];

        return view(
            'admin.adds.edit',
            compact('html_breadcrumbs', 'index_url', 'update_url', 'add','settings')
        );
    }

    public function Addupdate(Request $request, $id)
    {

        $rules = [
            'img' => 'required',
            'url' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'type' => 'required',
            //  'plan'               => 'required',

        ];

        $this->validate($request, $rules);


        $add = add::find($id);
        if(!$add)
        {
            \Session::flash('error', trans('العنصر غير موجود'));
        }

        $add->update($request->only([

            'url' ,
            'lat',
            'long' ,
            'type' ,


        ]));
        $add = add::find($id);
        if (($request->hasFile('img'))) {
//////

            $destinationPath = public_path('uploads/add/');
            $xy = $request->file('img');
            $unix_timestamp = date('m/d/Y h:i:s a', time());
            //return $xy;


            $extension = strtolower($xy->getClientOriginalExtension());
            //return $extension;
            if (in_array($extension, $this->image_extensions())) {

                $path = $request->file('img')->store('public/adds/photo');
                $path = str_replace('public', 'storage', $path);

                $add->img = $path;
                $add->save();


            }


        }


        \Session::flash('success', trans('تم التعديل بنجاح'));

        return redirect()->route('admin.adds.index');
    }
}
