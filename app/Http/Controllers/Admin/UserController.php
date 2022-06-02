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
            'title'     => __('views.users'),
            'subtitle'  => __('views.Index'),
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
