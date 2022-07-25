<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;

use App\Models\PromotionPost;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class OrderController extends Controller
{


    public function orders()
    {
        $index_url = route('admin.orders.datatable');
        $edit_url = route('admin.orders.show', 'id');


        $object = new PromotionPost();

        $html_breadcrumbs = [
            'title' => __('views.orders'),
            'subtitle' => __('views.Index'),
            'datatable' => true,
        ];

        return view(
            'admin.orders.index',
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

        $finiceing = PromotionPost::with('post')
            ->with('user')
            ->with('PromotionPackage');


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
        $order = PromotionPost::find($id);


        if (!$order) {
            \Session::flash('error', trans('الطلب غير موجود'));
            return redirect()->back();
        }
        // dd($categories->toArray());
        $index_url = route('admin.orders.index');
        $update_url = route('admin.orders.update', $id);

        $html_breadcrumbs = [
            'title' => __('views.orders'),
            'subtitle' => __('views.Edit'),
        ];


        return view(
            'admin.orders.edit',
            compact('html_breadcrumbs', 'index_url', 'update_url', 'order')
        );
    }


    public function update(Request $request)
    {


        $rules = [
            'status' => 'required',
            'order_id' => 'required',


        ];

        $this->validate($request, $rules);
        $order = PromotionPost::where('id', $request->get('order_id'))->first();

        if(!$order)
        {
            \Session::flash('error', trans('admin.not_found'));
        }
        if ($order) {
            $order->status = $request->get('status');
            $order->save();
        }



        \Session::flash('success', trans('admin.success_update'));

        return redirect()->route('admin.orders.show',$request->get('order_id'));
    } //<--- End Method


    public function destroy($id)
    {

        if ($id == 1) {
            return "fail";
        }
        $checkDelete = PromotionPost::query()->where('id', $id)->delete();
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


            $user = PromotionPost::query()->whereIn('id', $request->IDArray)
                ->update(['status' => $request->get('event')]);


        }
        return "" . $request->event . "";
    }
}
