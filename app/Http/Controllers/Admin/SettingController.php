<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class SettingController extends Controller
{


    public function edit()
    {
        $html_breadcrumbs = [
            'title' => __('views.My Profile'),
            'subtitle' => __('views.Index'),

        ];

        $admin = auth()->guard('Admin')->user();
        $update_url = route('admin.settings.update');
        $index_url = route('admin.dashboard.index');
        return view('admin.setting.edit',
            compact('admin', 'index_url', 'update_url', 'html_breadcrumbs'));
    }

    public function update(Request $request)
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
                return redirect()->route('admin.settings.edit')->with(['incorrect_pass' => trans('auth.password_incorrect')]);

            }
            $user = Admin::find($admin->id);
            $user->password = \Hash::make($request->get('password'));
            $user->save();
        }


        \Session::flash('success', trans('admin.success_update'));

        return redirect()->route('admin.settings.edit');
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


    public function getUser($id)
    {
        $user = User::findOrFail($id);

        if ($user) {
            return ['status' => true, 'data' => $user];
        } else {
            return ['status' => false, 'data' => []];
        }
    }


}
