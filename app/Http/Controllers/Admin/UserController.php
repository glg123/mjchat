<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;

use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{


    public function users()
    {
        $index_url = route('admin.users.datatable');
        $edit_url = route('admin.users.show', 'id');


        $object = new User();

        $html_breadcrumbs = [
            'title' => __('views.users'),
            'subtitle' => __('views.Index'),
            'datatable' => true,
        ];


        return view(
            'admin.users.index',
            compact(
                'html_breadcrumbs',


                'index_url',
                'object',
                'edit_url'

            )
        );
    }

    public function datatable(Request $request)
    {


        //  dd($request->get('query')['neighborhood_id']);

        $finiceing = User::query();


        if ($request->get('offer_status')) {

            if ($request->get('offer_status') == 'have_offers') {
                $finiceing = $finiceing->whereHas('offers');
            } elseif ($request->get('offer_status') == 'have_offers') {
                $finiceing = $finiceing->doesntHave('offers');
            } elseif ($request->get('offer_status') == 'have_active_offers') {
                $finiceing = $finiceing->where('status', 'active');
            }


        }


        $data = process_datatable_query($finiceing->orderBy('id', 'desc'), function (
            $query,
            $search
        ) {
            return $query
                ->where(function ($query) use ($search) {


                    $query->where('mobile', 'like', '%' . $search . '%')
                        ->orWhere('first_name', 'like', '%' . $search . '%')
                        ->orWhere('last_name', 'like', '%' . $search . '%');


                });


        });
        return $data;
    }

    public function edit($id)
    {
        $user = User::find($id);

        if (!$user) {
            \Session::flash('error', trans('المستخدم غير موجود'));
            return redirect()->back();
        }
        // dd($categories->toArray());
        $index_url = route('admin.users.index');
        $update_url = route('admin.users.update', $id);

        $html_breadcrumbs = [
            'title' => __('views.users'),
            'subtitle' => __('views.Edit'),
        ];


        return view(
            'admin.users.edit',
            compact('html_breadcrumbs', 'index_url', 'update_url', 'user')
        );
    }




    public function destroy($id)
    {

        if ($id == 1) {
            return "fail";
        }
        $checkDelete = User::query()->where('id', $id)->delete();
        if ($checkDelete)
            return "success";

        return "fail";
    }

    public function changeStatus(Request $request)
    {


        //  return $request->event;
        if ($request->event == 'delete') {
            User::query()->whereIn('id', $request->IDArray)->delete();
        } else {


            if ($request->event == 'active') {
                $x = 1;
            }
            if ($request->event == 'not_active') {
                $x = 2;
            }
            if ($request->event == 'block') {
                $x = 3;
            }

            $user = User::query()->whereIn('id', $request->IDArray)
                ->update(['status' => $x]);


        }
        return "" . $request->event . "";
    }
}
