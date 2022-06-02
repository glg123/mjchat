<?php

namespace App\Http\Controllers\Admin;

use App\Models\add;
use App\Models\Admin;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


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


    public function city_select2(Request $request)
    {
        $search = $request->get('q', '');
        $data = City::where('name_ar', 'like', '%' . $search . '%')
            ->orWhere('name_en', 'like', '%' . $search . '%')
            ->paginate()->toArray();
        array_unshift($data['data'], [
            'id' => 'null',
            'title' => __('root'),
        ]);
        return $data;
    }


    public function neighborhood_select2(Request $request)
    {
        $search = $request->get('q', '');
        $data = Neighborhood::where('city_id', $request->city_id)->
        where('name_ar', 'like', '%' . $search . '%')
            ->orWhere('name_en', 'like', '%' . $search . '%')
            ->where('neighborhood_serial', $request->city_id)
            ->paginate()->toArray();
        array_unshift($data['data'], [
            'id' => 'null',
            'title' => __('root'),
        ]);
        return $data;
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
            'title'     => __('views.adds'),
            'subtitle'  => __('views.Index'),
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


    public function createAdds()
    {

        // dd($categories->toArray());
        $index_url = route('admin.adds.index');
        $create_url = route('admin.adds.create');

        $html_breadcrumbs = [
            'title'    => __('views.adds'),
            'subtitle' => __('views.Add'),
        ];

        return view(
            'admin.adds.create',
            compact('html_breadcrumbs', 'index_url', 'create_url')
        );
    }

    public function Addsdatatable(Request $request)
    {


        //  dd($request->get('query')['neighborhood_id']);

        $adds = add::query();


        if ($request->get('type')) {


            $adds = $adds->where('type','=',$request->get('type') );



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
        $user = User::find($id);

        // dd($categories->toArray());
        $index_url = route('admin.users.index');
        $update_url = route('admin.users.update', $id);

        $html_breadcrumbs = [
            'title'    => __('views.users'),
            'subtitle' => __('views.Edit'),
        ];

        return view(
            'admin.users.edit',
            compact('html_breadcrumbs', 'index_url', 'update_url', 'user')
        );
    }

    public function update(Request $request, $id)
    {


        // dd($request->all());
        $requestPayment = UserPayment::with('user')->find($id);


        $user = User::where('unique_code', $requestPayment->user->unique_code)->first();


        if (!isset($requestPayment)) {
            return redirect()->route('admin.payment_requests.index');
        }


        $rules = [
            'payment_method_id' => 'required',
            //  'plan'               => 'required',

        ];

        $this->validate($request, $rules);
        $plan = \App\Models\Plan::find($id);


        if (!$user) {
            \Session::flash('success', trans('المستخدم لم يقدم طلب دفع'));

        }


        if ($request->get('payment_method_id') == 2) {
            if ($plan) {
                $paymentCheck = UserPlan::where('unique_code', $requestPayment->user->unique_code)->first();

                if (!$paymentCheck) {
                    $user_plan = UserPlan::create([
                        'plan_id'        => $plan->id,
                        'user_id'        => $user->id,
                        'status'         => '0',
                        'unique_code'    => $requestPayment->user->unique_code,
                        'payment_method' => $request->get('payment_method_id'),
                        'payment_url'    => null,
                        'count_try'      => 0,
                        'total'          => $plan->price
                    ]);
                }


                $text = 'شكرا لإشتراكك معنا ونرحب بإنضمامك لعائلة عقارز';
                $message = 'http://aqarz.sa/plans/' . $user->unique_code;
                ini_set("smtp_port", "465");
                $banks = Bank::where('status', '1')->get();
                $userDet = UserPlan::with('user','plan')->where('unique_code', $requestPayment->user->unique_code)->first();



                $to = $user->email;


                $from = 'info@aqarz.sa';
                $name = 'Aqarz';
                $subject = 'شكرا لإشتراكك معنا';


                $logo = asset('logo.svg');
                $link = '#';

                $details = [
                    'to'       => $to,
                    'from'     => $from,
                    'logo'     => $logo,
                    'link'     => $link,
                    'subject'  => $subject,
                    'name'     => $name,
                    "message"  => $message,
                    "text_msg" => $text,
                    'banks'    => $banks,
                    'userDet'     => $userDet->user,
                    'planDet'     =>  $userDet->plan,
                ];


                // var_export (dns_get_record ( "host.name.tld") );

                // dd(444);
                \Mail::to($to)->send(new \App\Mail\NewBankMail($details));

                /* if (Mail::failures()) {
                     return response()->json([
                         'status'  => false,
                         'data'    => $details,
                         'message' => 'Nnot sending mail.. retry again...'
                     ]);
                 }*/


                $user_mobile = checkIfMobileStartCode($user->mobile, $user->country_code);
                $unifonicMessage = new UnifonicMessage();
                $unifonicClient = new UnifonicClient();
                $unifonicMessage->content = "تم ارسال معلومات الدفع الخاصة بك الي البريدالالكتروني ";
                $to = $user_mobile;
                $co = $message;
                $data = $unifonicClient->sendCustomer($to, $co);
                \Log::channel('single')->info($data);
                \Log::channel('slack')->info($data);

            }


        } else {

            $text = 'رابط الدفع الخاص بك هو : ';
            $message = 'http://aqarz.sa/plans/' . $user->unique_code;
            ini_set("smtp_port", "465");

            $to = $user->email;


            $from = 'info@aqarz.sa';
            $name = 'Aqarz';
            $subject = 'رابط الدفع';


            $logo = asset('logo.svg');
            $link = '#';

            $details = [
                'to'       => $to,
                'from'     => $from,
                'logo'     => $logo,
                'link'     => $link,
                'subject'  => $subject,
                'name'     => $name,
                "message"  => $message,
                "text_msg" => $text,
            ];


            // var_export (dns_get_record ( "host.name.tld") );

            // dd(444);
            \Mail::to($to)->send(new \App\Mail\NewMail($details));

            /* if (Mail::failures()) {
                 return response()->json([
                     'status'  => false,
                     'data'    => $details,
                     'message' => 'Nnot sending mail.. retry again...'
                 ]);
             }*/


            $user_mobile = checkIfMobileStartCode($user->mobile, $user->country_code);
            $unifonicMessage = new UnifonicMessage();
            $unifonicClient = new UnifonicClient();
            $unifonicMessage->content = "Your Verification Code Is: ";
            $to = $user_mobile;
            $co = $message;
            $data = $unifonicClient->sendCustomer($to, $co);
            \Log::channel('single')->info($data);
            \Log::channel('slack')->info($data);


            //  return $data;
        }


        \Session::flash('success', trans('تم ارسال بيانات الدفع بنجاح'));

        return redirect()->route('admin.payment_requests.index');
    }
}
