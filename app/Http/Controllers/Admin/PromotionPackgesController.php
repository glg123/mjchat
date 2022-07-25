<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;

use App\Models\PromotionPackage;
use App\Models\PromotionPost;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class PromotionPackgesController extends Controller
{


    public function promotionPackges()
    {
        $index_url = route('admin.promotionPackges.datatable');
        $edit_url = route('admin.promotionPackges.show', 'id');


        $object = new PromotionPackage();

        $html_breadcrumbs = [
            'title' => __('views.promotionPackges'),
            'subtitle' => __('views.Index'),
            'datatable' => true,
        ];

        return view(
            'admin.promotionPackges.index',
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

        $finiceing = PromotionPackage::query();


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

    public function create()
    {
        $object = new PromotionPackage();
        $html_breadcrumbs = [
            'title' => __('views.promotionPackges'),
            'subtitle' => __('views.Add'),
        ];

        $index_url = route('admin.promotionPackges.index');
        $form_url = route('admin.promotionPackges.store', $object->id);
        $form_method = "POST";
        return view('admin.promotionPackges.create', compact('html_breadcrumbs', 'object', 'index_url', 'form_url', 'form_method'));
    }

    public function edit($id)
    {
        $package = PromotionPackage::find($id);


        if (!$package) {
            \Session::flash('error', trans('الطلب غير موجود'));
            return redirect()->back();
        }
        // dd($categories->toArray());
        $index_url = route('admin.promotionPackges.index');
        $update_url = route('admin.promotionPackges.update', $id);

        $html_breadcrumbs = [
            'title' => __('views.promotionPackges'),
            'subtitle' => __('views.Edit'),
        ];


        return view(
            'admin.promotionPackges.edit',
            compact('html_breadcrumbs', 'index_url', 'update_url', 'package')
        );
    }

    public function store(Request $request)
    {

        // dd($request->all());
        $rules = [
            'status' => 'required',
            'title_ar' => 'required',
            'title_en' => 'required',
            // 'type' => 'required',
            'type' => 'required|in:appearance_number,number_days',
            'description_ar' => 'required',
            'description_en' => 'required',
            //  'count_views' => 'required',
            // 'count_days' => 'required',
            'price' => 'required|numeric',
            'total_price' => 'required|numeric',
            'count_views' => 'sometimes|required_if:type,appearance_number',
            'count_days' => 'sometimes|required_if:type,number_days',

        ];

        $this->validate($request, $rules);
        PromotionPackage::create($request->only([
            'title_ar',
            'title_en',
            'type',
            'description_ar',
            'description_en',
            'description_en',
            'count_views',
            'count_days',
            'price',
            'total_price',
            'status',


        ]));


        \Session::flash('success', trans('admin.success_add'));

        return redirect()->route('admin.promotionPackges.index');
    } //<--- End Method

    public function update(Request $request)
    {

        // dd($request->all());
        $rules = [
            'status' => 'sometimes|required',
            'title_ar' => 'sometimes|required',
            'title_en' => 'sometimes|required',
            // 'type' => 'required',
            'type' => 'sometimes|required|in:appearance_number,number_days',
            'description_ar' => 'sometimes|required',
            'description_en' => 'sometimes|required',
            //  'count_views' => 'required',
            // 'count_days' => 'required',
            'price' => 'sometimes|required|numeric',
            'total_price' => 'sometimes|required|numeric',
            'count_views' => 'sometimes|required_if:type,appearance_number',
            'count_days' => 'sometimes|required_if:type,number_days',
            'packge_id' => 'required',
        ];

        $this->validate($request, $rules);

        $packge = PromotionPackage::find($request->get('packge_id'));

        if (!$packge) {
            \Session::flash('error', trans('views.not_found'));
        }

        $packge=   $packge->update($request->only([
            'title_ar',
            'title_en',
            'type',
            'description_ar',
            'description_en',
            'description_en',
            'count_views',
            'count_days',
            'price',
            'total_price',
            'status',


        ]));

       // $packge = PromotionPackage::find($request->get('packge_id'));
        \Session::flash('success', trans('admin.success_update'));

        return redirect()->route('admin.promotionPackges.show',$request->get('packge_id'));
    } //<--- End Method


    public function destroy($id)
    {

        if ($id == 1) {
            return "fail";
        }
        $checkDelete = PromotionPackage::query()->where('id', $id)->delete();
        if ($checkDelete)
            return "success";

        return "fail";
    }

    public function changeStatus(Request $request)
    {


        //  return $request->event;
        if ($request->event == 'delete') {
            PromotionPackage::query()->whereIn('id', $request->IDArray)->delete();
        } else {


            $user = PromotionPackage::query()->whereIn('id', $request->IDArray)
                ->update(['status' => $request->get('event')]);


        }
        return "" . $request->event . "";
    }
}
