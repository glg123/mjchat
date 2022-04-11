<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\appinfoResource;

use App\Http\Resources\MassageRessonsResource;
use App\Http\Resources\PromocodeResource;
use App\Http\Resources\userAddsResource;
use App\Models\add;
use App\Models\Country;
use App\Models\MessageReson;
use App\Models\Messages;
use App\Models\Promocode;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{


    public function settings(Request $request)
    {

        $local = (app('request')->hasHeader('Accept-Language')) ? app('request')->header('Accept-Language') : 'ar';

        $cloum = 'value_' . $local;


        $about_us = Setting::where('key', 'about_us')->first()->$cloum;
        $privacy_policy = Setting::where('key', 'privacy_policy')->first()->$cloum;
        $face_book = Setting::where('key', 'facebook')->first()->$cloum;
        $twitter = Setting::where('key', 'twitter')->first()->$cloum;
        $insta = Setting::where('key', 'instagram')->first()->$cloum;
        $linkedin = Setting::where('key', 'linkedin')->first()->$cloum;
        $tearms = Setting::where('key', 'tearms')->first()->$cloum;
        $email = Setting::where('key', 'email')->first()->$cloum;
        $sharepoint = Setting::where('key', 'sharepoint')->first()->$cloum;
        $storypoint = Setting::where('key', 'storypoint')->first()->$cloum;
        $addspoint = Setting::where('key', 'addspoint')->first()->$cloum;
        $storydays = Setting::where('key', 'storydays')->first()->$cloum;
        $sharePrice = Setting::where('key', 'sharePrice')->first()->$cloum;
        $point_price = Setting::where('key', 'point_price')->first()->$cloum;
        $dayPrice = Setting::where('key', 'dayPrice')->first()->$cloum;
        $mobile = Setting::where('key', 'mobile')->first()->$cloum;
        $whatsapp = Setting::where('key', 'whatsapp')->first()->$cloum;
        $unreactstorytime = Setting::where('key', 'unreactstorytime')->first()->$cloum;
        $countries = Country::where('status', 1)->get();
        $MessageReason = MessageReson::get();
        $MessageReason=MassageRessonsResource::collection($MessageReason);

        $setting = [
            'about_us' => $about_us,
            'privacy_policy' => $privacy_policy,
            'face_book' => $face_book,
            'twitter' => $twitter,
            'insta' => $insta,
            'linkedin' => $linkedin,
            'tearms' => $tearms,
            'email' => $email,
            'whatsapp' => $whatsapp,
            'mobile' => $mobile,
            'sharepoint' => $sharepoint,
            'storypoint' => $storypoint,
            'addspoint' => $addspoint,
            'storydays' => $storydays,
            'sharePrice' => $sharePrice,
            'point_price' => $point_price,
            'dayPrice' => $dayPrice,
            'unreactstorytime' => $unreactstorytime,
            'countries' => $countries,
            'message_reasons' => $MessageReason,


        ];
        return JsonResponse::success($setting, __('views.Setting'));
        //  return ['data' => $user];
    }


    public function sendFeedback(Request $request)
    {
        $rules = Validator::make($request->all(), [

            'email' => 'required',
            'message_reasons_id' => 'required|exists:message_resons,id',
            'content' => 'required'

        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        try {
            $message = Messages::create($request->only([
                'email',
                'message_reasons_id',
                'content',

            ]));

            return JsonResponse::success([], __('views.Done'));

        } catch (\Exception $exception) {
            return JsonResponse::fail($exception->getMessage(), 400);
        }

    }//end feed back


    public function checkPromo(Request $request)
    {
        $rules = Validator::make($request->all(), [

            'code' => 'required',

        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        try {
            $promo = Promocode::where('code', $request->get('code'))->first();

            if ($promo) {
                $promo = new PromocodeResource($promo);
                return JsonResponse::success($promo, __('views.Done'));
            } else {
                return JsonResponse::fail(__('views.no_promo_code'), 400);
            }
        } catch (\Exception $exception) {
            return JsonResponse::fail($exception->getMessage(), 400);
        }


    }//end check promocode

    public function Adds_system(Request $request)
    {

        $rules = Validator::make($request->all(), [

            'type' => 'required|in:1,2,3',
            'lat' => 'required',
            'long' => 'required',

        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        try {

            $adds = add::distance($request->lat, $request->long)
                ->where('type', $request->get('type'));
         //    ->having('distance', [$request->get('distance')]);
            $addsx = $adds->orderBy('distance', 'ASC')
              //  ->selectRaw('id','distance')
                ->inRandomOrder()->first();
            $addsx = userAddsResource::collection([$addsx]);

         //   $addsx = new userAddsResource([$addsx]);

            return JsonResponse::success($addsx[0], __('views.Done'));


        } catch (\Exception $x) {
            return JsonResponse::fail($x->getMessage(), 400);
        }

    }
}
