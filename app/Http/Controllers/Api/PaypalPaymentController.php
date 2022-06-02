<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PromotionPackage;
use App\Models\PromotionPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalPaymentController extends Controller
{
    public function __construct()
    {
        $this->provider = new PayPalClient();
    }


    public function payment(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        $this->provider->setApiCredentials(config('paypal'));
        $token = $this->provider->getAccessToken();


        $this->provider->setAccessToken($token);
        $order = $this->provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [

                    "amount" => [
                        "currency_code" => "USD",
                        "value" => 20
                    ],
                    'description' => 'test'
                ]
            ],
        ]);


        dd($data);
        $mergeData = array_merge($data, ['status' => 'PENDING', 'vendor_order_id' => 1]);
        DB::beginTransaction();
        Order::create($mergeData);
        DB::commit();
        return response()->json($order);


        //return redirect($order['links'][1]['href'])->send();
        // echo('Create working');
    }


    public function createTransaction()
    {
        return view('transaction');
    }

    /**
     * process transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function processTransaction(Request $request)
    {
        $user = $request->user();
        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }

        $rules = Validator::make($request->all(), [

            'promotion_id' => 'required|exists:promotion_packges,id',
            'post_id' => 'required|exists:posts,id',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        $promotion = PromotionPackage::find($request->get('promotion_id'));
        if (!$promotion) {
            return JsonResponse::fail(__('views.not found'));
        }

        $post = Post::find($request->get('post_id'));
        if (!$post) {
            return JsonResponse::fail(__('views.not found'));
        }
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();
// "invoice_id" => $user->id . "-" . $user->id,
        $response = $provider->createOrder([
            "intent" => "CAPTURE",

            "application_context" => [
                "return_url" => route('successTransaction'),
                "cancel_url" => route('cancelTransaction'),
            ],
            "purchase_units" => [
                [

                    "custom_id" => $user->id . "-" . $promotion->id . "-" . $post->id,
                    'user_id' => $user->id,
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $promotion->total_price,
                        'promotion_id' => 'test',
                        'user_id' => $user->id,
                    ]
                ],

                //   'reference_id'=>5
            ]
        ]);


        if (isset($response['id']) && $response['id'] != null) {

            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return JsonResponse::success(['url' => $links['href']], __('views.Done'));


                    //return $links['href'];
                    // return redirect()->away($links['href']);
                }
            }
            return JsonResponse::fail($response['message'] ?? 'Something went wrong.', 400);

            //    return $response;
            // return redirect()
            //     ->route('createTransaction')
            //     ->with('error', 'Something went wrong.');

        } else {
            return JsonResponse::fail($response['message'] ?? 'Something went wrong.', 400);
            /* return redirect()
                 ->route('createTransaction')
                 ->with('error', $response['message'] ?? 'Something went wrong.');*/
        }
    }

    /**
     * success transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function successTransaction(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);


        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            // dd($response['purchase_units'][0]['payments']['captures'][0]['custom_id']);
            $custom_array_ids = explode('-', $response['purchase_units'][0]['payments']['captures'][0]['custom_id']);
            $order_create_at = date($response['purchase_units'][0]['payments']['captures'][0]['create_time']);              // returns Saturday, January 30 10 02:06:34
            $order_create_at_timestamp = strtotime($order_create_at);
            $new_order_create_at = date('Y-m-d H:i:s', $order_create_at_timestamp);


            $order_update_at = date($response['purchase_units'][0]['payments']['captures'][0]['update_time']);              // returns Saturday, January 30 10 02:06:34
            $order_update_at_timestamp = strtotime($order_update_at);
            $new_order_update_at = date('Y-m-d H:i:s', $order_update_at_timestamp);
            $promotion = PromotionPackage::find($custom_array_ids[1]);
            if (!$promotion) {
                return JsonResponse::fail('Package Not Found', 400);

            }

            $request->merge([
                //  'password'          => app('hash')->make($request->input('password')),
                'user_id' => $custom_array_ids[0],
                'promotion_id' => $custom_array_ids[1],
                'post_id' => $custom_array_ids[2],
                'order_id' => $response['id'],
                'order_status' => $response['status'],
                'order_create_at' => $response['purchase_units'][0]['payments']['captures'][0]['create_time'],
                'order_updated_at' => $response['purchase_units'][0]['payments']['captures'][0]['update_time'],
                'total_price' => $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'],
                'currency_code' => $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'],
                'payer_name' => $response['payer']['name']['given_name'] . ' ' . $response['payer']['name']['surname'],
                'payer_email_address' => $response['payer']['email_address'],
                'count_views' => $promotion->count_views,
                'remaining_views' => $promotion->count_views,
                'start_date' => date('Y-m-d'),
                'end_date' => date('Y-m-d'),
                //  'mobile'            => $mobile,
            ]);
            $order = PromotionPost::create($request->only([
                'promotion_id',
                'post_id',
                'start_date',
                'end_date',
                'status',
                'count_views',
                'remaining_views',
                'order_id',
                'order_status',
                'order_create_at',
                'order_updated_at',
                'total_price',
                'payer_name',
                'payer_email_address',
                'payer_id',


            ]));
            return JsonResponse::success($order, __('views.Done'));

        } else {
            return JsonResponse::fail($response['message'] ?? 'Something went wrong.', 400);

        }
    }

    /**
     * cancel transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelTransaction(Request $request)
    {

        return JsonResponse::fail($response['message'] ?? 'You have canceled the transaction.', 400);


    }
}
