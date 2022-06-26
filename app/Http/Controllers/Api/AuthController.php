<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;

use App\Http\Resources\appuserabdResponse;
use App\Http\Resources\OtherUserResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserSimpleResource;
use App\Jobs\OtpJob;
use App\Models\follow_user;
use App\Models\Skill;
use App\Models\User;
use App\Models\Userblock;
use App\Models\UserNotifaction;
use App\Models\UserSkill;
use App\Models\UserWallet;
use App\Unifonic\UnifonicMessage;
use Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{

    public function checkPhone(Request $request)
    {
        $rules = Validator::make($request->all(), [

            'mobile' => 'required|unique:users,mobile',
            'country_code' => 'required',
            'code' => 'required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }
        $mobile = '';
        if ($request->get('mobile')) {
            if (startsWith($request->get('mobile'), '0')) {
                $mobile = substr($request->get('mobile'), 1, strlen($request->get('mobile')));
            } else {
                if (startsWith($request->get('mobile'), '00')) {
                    $mobile = substr($request->get('mobile'), 2, strlen($request->get('mobile')));
                } else {
                    $mobile = trim($request->get('mobile'));
                }
            }
        }
        try {
            $user = User::whereMobile($mobile)
                ->whereConfirmationCode($request->get('code'))
                ->first();
            if (!$user) {
                return JsonResponse::fail(__('views.not found'));
            }

            if ($user->confirmation_code) {
                $confirmation_code = $user->confirmation_code;
                return JsonResponse::success(['code' => $confirmation_code], __('views.We Send Activation Code To Your Email'));

            }
        } catch (\Exception $e) {
            return JsonResponse::fail($e->getMessage(), 400);
        }

        return JsonResponse::fail(__('views.not found'));

    }//end of checkPhone

    public function checkUserName(Request $request)
    {
        $rules = Validator::make($request->all(), [

            'user_name' => 'required|unique:users,user_name',

        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        return JsonResponse::success([], __('views.available'));


    } // end of checkUserName

    public function checkEmail(Request $request)
    {
        $rules = Validator::make($request->all(), [

            'email' => 'required|unique:users,email',

        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        return JsonResponse::success([], __('views.available'));


    }//end of checkEmail


    public function loginOld(Request $request)
    {
        // TODO when facebook user doesn't have email just phone number


        $rules = Validator::make($request->all(), [

            'username' => 'required_if:referer,local|max:255',
            'password' => 'required_if:referer,local|min:3',
            'device_token' => 'sometimes|required',
            'device_type' => 'required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }


        $username = $request->username;
        $mobile = 0;
        $old_mobile = "";


        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $username_column = 'email';
        } else {

            $username_column = 'mobile';


            if (true) {
                $old_mobile = trim($request->get('username'));


                $mobile = 0;
                if (startsWith($old_mobile, '0')) {
                    $mobile = substr($old_mobile, 1, strlen($old_mobile));


                } else {
                    if (startsWith($old_mobile, '00')) {
                        $mobile = substr($old_mobile, 2, strlen($old_mobile));
                    } else {
                        $mobile = trim($request->get('username'));
                    }
                }
                //  dd($mobile);


                $global_mobile = intval($request->get('country_code')) . intval($mobile);
                $request->merge(['username' => $global_mobile]);
            }
        }

        $request->merge([
            $username_column => $request->username,
            'status' => '0'
        ]);
        $credentials = $request->only($username_column, 'password', 'status');

        $class = new User();

        $user = $class::where($username_column, $credentials[$username_column])->first();
        if (!$user && $mobile) { //  TODO remove later after 2019-12-30


            //$user = $class::where($username_column, $old_mobile)->where('country_code', 966)->first();
            $user = $class::where($username_column, $mobile)->first();
        }

        if ((!$user) ||
            (app('hash')->check($credentials['password'], $user->password) === false)) {
            return JsonResponse::fail('UserPasswordMismatchError', 400);
        }


        if ($user->api_token == null) {
            // $user->api_token = hash('sha512', time());
            $user->api_token = $user->createToken('api_token')->plainTextToken;

        }

        if ($user->status == 2) {
            return JsonResponse::fail(__('views.UserBlock'), 400);

        }
        if ($user->status == 0) {
            return JsonResponse::fail(__('views.UserMustActivated'), 400);

        }
        $user->device_token = $request->get('device_token');
        $user->api_token = $user->createToken('api_token')->plainTextToken;

        $user->save();


        return JsonResponse::success($user, "User Profile");
        //  return ['data' => $user];
    }

    public function login(Request $request)
    {
        // TODO when facebook user doesn't have email just phone number


        $rules = Validator::make($request->all(), [

            'username' => 'required_if:referer,local|max:255',
            'password' => 'required_if:referer,local|min:3',
            'device_token' => 'sometimes|required',
            'device_type' => 'required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }


        $username = $request->username;
        $mobile = 0;
        $old_mobile = "";


        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $username_column = 'email';
        } else {

            $username_column = 'mobile';


            if (true) {
                $old_mobile = trim($request->get('username'));


                $mobile = 0;
                if (startsWith($old_mobile, '0')) {
                    $mobile = substr($old_mobile, 1, strlen($old_mobile));


                } else {
                    if (startsWith($old_mobile, '00')) {
                        $mobile = substr($old_mobile, 2, strlen($old_mobile));
                    } else {
                        $mobile = trim($request->get('username'));
                    }
                }
                //  dd($mobile);


                $global_mobile = intval($request->get('country_code')) . intval($mobile);
                $request->merge(['username' => $global_mobile]);
            }
        }

        $request->merge([
            $username_column => $request->username,
            'status' => '0'
        ]);
        $credentials = $request->only($username_column, 'password', 'status');

        $class = new User();

        $user = $class::where($username_column, $credentials[$username_column])->first();
        if (!$user && $mobile) { //  TODO remove later after 2019-12-30


            //$user = $class::where($username_column, $old_mobile)->where('country_code', 966)->first();
            $user = $class::where($username_column, $mobile)->first();
        }

        if ((!$user) ||
            (app('hash')->check($credentials['password'], $user->password) === false)) {
            return JsonResponse::fail('UserPasswordMismatchError', 400);
        }


        if ($user->api_token == null) {
            // $user->api_token = hash('sha512', time());
            $user->api_token = $user->createToken('api_token')->plainTextToken;

        }

        if ($user->status == 2) {
            return JsonResponse::fail(__('views.UserBlock'), 400);

        }
        if ($user->status == 0) {
            return JsonResponse::fail(__('views.UserMustActivated'), 400);

        }
        $user->device_token = $request->get('device_token');
        $user->api_token = $user->createToken('api_token')->plainTextToken;

        $user->save();
        $user = User::find($user->id);
        $user = UserResource::collection([$user]);
        return JsonResponse::success($user[0], "User Profile");
        //  return ['data' => $user];
    }

    public function store(Request $request)
    {


        $rules = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required|string',
            'date_of_birth' => 'required|date|before:2003-01-01',
            'password' => 'required',
            'user_name' => 'required|unique:users,user_name',
            'mobile' => 'required|unique:users,mobile',
            'country_code' => 'required',

            //  'password'     => 'required_if:referer,local|min:3',
            'device_token' => 'sometimes|required',
            'device_type' => 'sometimes|required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }
        $mobile = '';
        if ($request->get('mobile')) {
            if (startsWith($request->get('mobile'), '0')) {
                $mobile = substr($request->get('mobile'), 1, strlen($request->get('mobile')));
            } else {
                if (startsWith($request->get('mobile'), '00')) {
                    $mobile = substr($request->get('mobile'), 2, strlen($request->get('mobile')));
                } else {
                    $mobile = trim($request->get('mobile'));
                }
            }
        }

        $confirmation_code = substr(str_shuffle("0123456789"), 0, 6);
        $confirmation_code2 = substr(str_shuffle("0123456789"), 0, 6);

        $request->merge([
            //  'password'          => app('hash')->make($request->input('password')),
            'confirmation_code' => $confirmation_code,
            'mobile' => $mobile,
            'confirmation_password_code' => $confirmation_code2,
            'api_token' => hash('sha512', time()),
            'status' => '0',
            'password' => Hash::make($request->get('password')),
            //  'mobile'            => $mobile,
        ]);


        try {

            $user = User::create($request->only([
                'first_name',
                'last_name',
                'email',
                'password',
                'api_token',
                'account_type',
                'date_of_birth',
                'user_name',
                'mobile',
                'country_code',
                'points',
                'logo',
                'comment_privacy',
                'confirmation_code',
                'confirmation_password_code',
                'country_id',
                'gender',

            ]));

            $token = $user->createToken('api_token')->plainTextToken;
            $user->api_token = $token;
            $user->save();

            try {
                return JsonResponse::success(['code' => $confirmation_code], __('views.We Send Activation Code To Your Email'));
            } catch (ModelNotFoundException $exception) {

                return JsonResponse::fail($exception->getMessage(), 400);


            }

        } catch (\Exception $e) {

            return JsonResponse::fail($e->getMessage(), 400);


        }


    }


    public function verifyNew(Request $request)
    {


        $rules = Validator::make($request->all(), [

            'mobile' => 'required|numeric',
            'code' => 'required|numeric',


        ]);
//

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        $mobile = '';
        if ($request->get('mobile')) {
            if (startsWith($request->get('mobile'), '0')) {
                $mobile = substr($request->get('mobile'), 1, strlen($request->get('mobile')));
            } else {
                if (startsWith($request->get('mobile'), '00')) {
                    $mobile = substr($request->get('mobile'), 2, strlen($request->get('mobile')));
                } else {
                    $mobile = trim($request->get('mobile'));
                }
            }
        }
        try {
            $user = User::whereMobile($mobile)
                ->whereConfirmationCode($request->get('code'))
                ->first();


            if (!$user) {
                return JsonResponse::fail(__('views.not found'));
            }
            $success = 'false';
            if ($user->confirmation_code == $request->get('code')) {
                $success = 'true';
            }


            if (isset($success) && $success == 'true' && $request->get('code') == $user->confirmation_code) {
                {


                    $user = User::whereMobile($mobile)
                        ->whereConfirmationCode($request->get('code'))
                        ->first();

                    //Update Current User Verifed_at timestamp
                    $user->update([
                        "email_verified_at" => date(now()),
                        'confirmation_code' => null,
                        'status' => '1',

                    ]);
                    $user = User::whereMobile($user->mobile)->first();

                    $user->api_token = $user->createToken('api_token')->plainTextToken;
                    $user->save();

                    $user = UserResource::collection([$user]);
                    return JsonResponse::success($user[0], __("views.Phone Verified"));
                }

            }
            return JsonResponse::fail(__("views.Incorrect Code"));
        } catch (\Exception $e) {
            return JsonResponse::fail($e->getMessage(), 400);
        }

    }

    public function forgetPassword(Request $request)
    {


        $rules = Validator::make($request->all(), [

            'mobile' => 'required|numeric',
            'country_code' => 'required',


        ]);


        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }


        try {
            $mobile = '';
            if ($request->get('mobile')) {
                if (startsWith($request->get('mobile'), '0')) {
                    $mobile = substr($request->get('mobile'), 1, strlen($request->get('mobile')));
                } else {
                    if (startsWith($request->get('mobile'), '00')) {
                        $mobile = substr($request->get('mobile'), 2, strlen($request->get('mobile')));
                    } else {
                        $mobile = trim($request->get('mobile'));
                    }
                }
            }
            $user = User::whereMobile($mobile)->first();
            if (!$user) {
                return JsonResponse::fail(__('views.not found'));
            }
            return JsonResponse::success(['code' => $user->confirmation_password_code], __("views.Email Send"));

        } catch (\Exception $e) {
            return JsonResponse::fail(__("views.not found"));
        }


    }


    public function show(Request $request)
    {


        //dd($request->header());
        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }
        try {
            $user = $request->user();

            $user = User::with('locations')->where('id', $user->id)
                ->first();


            $user = UserResource::collection([$user]);

            //  $user->api_token = $request->user()->currentAccessToken();
            return JsonResponse::success($user[0], __('views.User Profile'));
            //  return ['data' => $user];

        } catch (\Exception $e) {
            return JsonResponse::fail($e->getMessage(), 400);
        }


        // $user->api_token = $user->createToken('auth_token')->plainTextToken;


    }


    public function logout()
    {


        if (auth()->check()) {
            auth()->user()->tokens()->delete();

            //     request()->session()->invalidate();

            //    request()->session()->regenerateToken();

            //  Auth::logout();
            return JsonResponse::success([], __('views.User Profile'));
        }
        return JsonResponse::fail(__('views.not authorized'));


    }


    /**
     * Store a newly updated resource in storage.
     *
     * @SWG\Post(
     *   path="/update/my/profile",
     *   tags={"Auth"},
     *   summary="تعديل ملفي الشخصي",
     *   @SWG\Parameter(
     *     name="location",
     *     in="body",
     *     required=true,
     *     @SWG\Schema(
     *        type="object",
     *        @SWG\Property(
     *          property="name",
     *          type="string",
     *          description="sometimes|required",
     *          example="",
     *        ),
     *     )
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="User Object",
     *     @SWG\Schema(
     *       type="object",
     *       @SWG\Property(
     *       property="status",
     *          type="string",
     *       example="true",
     *            description="حالة الطلب",
     *       ),
     *       @SWG\Property(
     *       property="message",
     *          type="string",
     *          description="رسالة النظام",
     *       ),
     *       @SWG\Property(
     *         property="result",
     *         ref="#/definitions/User"
     *       ),
     *       @SWG\Property(
     *       property="errors",
     *          type="string",
     *              example="null",
     *        description="الاخطاء",
     *       ),
     *     )
     *   ),
     *   @SWG\Response(
     *     response=500,
     *     description="ModelNotFound",
     *     @SWG\Schema(ref="#/definitions/ModelNotFound")
     *   ),
     *   @SWG\Response(
     *     response=400,
     *     description="ValidationError",
     *     @SWG\Schema(
     *       ref="#/definitions/ValidationError"
     *     )
     *   )
     * )
     */
    public function update(Request $request)
    {
        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }
        $user = $request->user();


        $rules = Validator::make($request->all(), [


         //   'email' => 'sometimes|required|unique:users,email,' . $user->id . ',id,deleted_at,NULL',
         //   'first_name' => 'sometimes|required',
         //   'last_name' => 'sometimes|required',
         //   'gender' => 'sometimes|required|string',
         //   'date_of_birth' => 'sometimes|required|date|before:2003-01-01',
            // 'user_name' => 'sometimes|required|unique:users,user_name',
            'user_name' => ['sometimes','required', 'string', 'max:255', 'unique:users',
                'regex:/^[A-Za-z0-9]+(?:[ _-][A-Za-z0-9]+)*$/'],
        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        try {

            $user = User::find($user->id);

            if (!$user) {
                return JsonResponse::fail(__('views.not authorized'));

            }

            $user->update($request->only([

                'first_name',
                'user_name',
                'last_name',
                'email',
                'mobile',
                'gender',
                'date_of_birth',
                'user_name',
                'comment_privacy',
                'bio',
                'iban_number',
                'facebook_link',
                'twitter_link',
                'instagram_link',
                'provider',
                'provider_id',
                'date_of_birth',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'country_id',
                'suspend',


            ]));


            $user = User::find($user->id);




            $user = User::where('id', $user->id)
                ->first();


            $user = UserResource::collection([$user]);

            //  $user->api_token = $request->user()->currentAccessToken();
            return JsonResponse::success($user[0], __('views.User Profile'));


        } catch (\Exception $e) {
            return JsonResponse::fail($e->getMessage(), 400);
        }


        //  return JsonResponse::success($user[0], __("views.User Profile"));
        //   return response(null, Response::HTTP_NO_CONTENT);
    }


    public
    function ResetToken(Request $request)
    {

        $rules = Validator::make($request->all(), [

            'confirmation_password_code' => 'required|numeric',
            'mobile' => 'required|numeric',
            'country_code' => 'required',

            // 'country_code'              => 'sometimes|required',

        ]);


        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }


        try {


            $confirmation_code = trim($request['confirmation_password_code']);


            $western_arabic = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
            $eastern_arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
            $confirmation_code = str_replace($eastern_arabic, $western_arabic, $confirmation_code);
            $confirmation_code = str_replace(['+', '-'], '', filter_var($confirmation_code, FILTER_SANITIZE_NUMBER_INT));

            /* $user = Client::where('confirmed', 1)->where('confirmation_code', $confirmation_code)->first(); */
            $user = User::where('confirmation_password_code', $confirmation_code)->first();


            if ($user) {


                return JsonResponse::success(['code' => $request['confirmation_password_code']], "Code True");


            } else {


                return \Illuminate\Support\Facades\Response::json([
                    "status" => false,
                    "message" => 'code not valid',
                    'errors' => null,

                ], 400);


                throw ValidationException::withMessages([
                    'confirmation_code' => [trans('messages.confirmation_mismatch')],
                ]);
            }

        } catch (\Exception $e) {
            return JsonResponse::fail($e->getMessage(), 400);
        }

    }


    public
    function updatePasswordByPhone(Request $request)
    {


        $rules = Validator::make($request->all(), [

            'password' => 'required|confirmed',
            'password_confirmation' => 'required',
            'code' => 'required|numeric',
            // 'country_code'              => 'sometimes|required',

        ]);


        if ($rules->fails()) {


            return JsonResponse::fail($rules->errors()->first(), 400);
        }


        try {
            $user = User::where('confirmation_password_code', $request->get('code'))->first();


            if ($user) {

                $user->update(["password" => app('hash')->make($request->password), 'confirmation_password_code' => null]);


                $user = User::where('id', $user->id)
                    ->first();


                $user = UserResource::collection([$user]);

                //  $user->api_token = $request->user()->currentAccessToken();
                return JsonResponse::success($user[0], __('views.User Profile'));
                //   return JsonResponse::success($user, "User Password Updated!");

            }
            return JsonResponse::fail("Incorrect Password!");
        } catch (\Exception $ex) {
            return JsonResponse::fail("Incorrect Password!");
        }


    }

    public
    function Mynotification(Request $request)
    {

        //dd($request->header());
        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }

        try {
            $user = $request->user();
            $user = User::with('wallet')->find($user->id);
            // $user->api_token = $user->createToken('auth_token')->plainTextToken;

            if ($user == null) {
                return JsonResponse::fail(__('views.not authorized'));
            }
            $notifications = $user->notifications;

            //  $user = User::find($user->id);
            //  $user->api_token = $request->user()->currentAccessToken();
            return JsonResponse::success($notifications, __('views.User Profile'));
        } catch (\Exception $ex) {
            return JsonResponse::fail(__('views.not found'));
        }

        //
    }

    public
    function uploadAvatar(Request $request)
    {


        $rules = Validator::make($request->all(), [

            'logo' => 'required|image|mimes:jpeg,bmp,png',

        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }

        try {
            $user = $request->user();

            $user = User::find($user->id);


            if ($user == null) {
                return JsonResponse::fail(__("views.not authorized"));
            }


            $path = $request->file('logo')->store('public/users/photo');

            $path = str_replace('public', 'storage', $path);
            //    $estate->instrument_file = 'https://aqarz.s3.me-south-1.amazonaws.com/' . $path;


            $user->update(['img' => $path]);
            $user = User::find($user->id);
            $user = UserResource::collection([$user]);

            //  $user->api_token = $request->user()->currentAccessToken();
            return JsonResponse::success($user[0], __('views.User Profile'));

        } catch (\Exception $ex) {
            return JsonResponse::fail(__('views.not found'));


        }


    }

    public
    function updatePassword(Request $request)
    {


        $rules = Validator::make($request->all(), [

            //  'password' => 'required',
            'password' => 'required',
            'old_password' => 'required',
            //  'password_confirmation' => 'required',

            // 'country_code'              => 'sometimes|required',

        ]);


        if ($rules->fails()) {

            //   return $rules->errors()->first();
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }
        $user = $request->user();

        $user = User::find($user->id);

        if ($user == null) {
            return JsonResponse::fail(__("views.not authorized"));
        }

        if ($user->password) {
            if (!\Hash::check($request->old_password, $user->password)) {
                return JsonResponse::fail(__("views.old password wrong"));
            }


            if ($user) {

                $user->update(["password" => app('hash')->make($request->password)]);

                $user = User::find($user->id);
                $user = UserResource::collection([$user]);

                //  $user->api_token = $request->user()->currentAccessToken();
                return JsonResponse::success($user[0], __('views.User Profile'));
                //  return JsonResponse::success(__("views.User Password Updated!"));

            }
            return JsonResponse::fail(__("views.Incorrect Password!"));
        }


    }


    public function myStoires(Request $request)
    {

        $user = $request->user();
        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }


        $user = User::find($user->id);
        $posts = $user->stories()->latest()->paginate(5);
        $posts = PostResource::collection($posts)->response()->getData(true);
        return JsonResponse::success($posts, __('views.Done'));


    }

    public function myPosts(Request $request)
    {

        $user = $request->user();
        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }


        $user = User::find($user->id);
        $posts = $user->posts()->latest()->paginate(5);
        $posts = PostResource::collection($posts)->response()->getData(true);
        return JsonResponse::success($posts, __('views.Done'));


    }

    public function blockUser(Request $request)
    {


        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }
        $rules = Validator::make($request->all(), [

            'block_user_id' => 'required|exists:users,id',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }


        try {
            $checkUserBlock = Userblock::where('user_id', $user->id)
                ->where('block_user_id', $request->get('block_user_id'))
                ->first();

            if ($user->id != $request->get('block_user_id')) {
                if ($checkUserBlock) {
                    if ($checkUserBlock->status == '0') {
                        $checkUserBlock->status = '1';
                        $checkUserBlock->save();
                    } else {
                        return JsonResponse::success([], __('views.Done'));

                    }

                    return JsonResponse::success([], __('views.Done'));
                } else {
                    Userblock::create([
                        'user_id' => $user->id,
                        'block_user_id' => $request->get('block_user_id'),

                        //       'comment'        =>      $request->comment,
                    ]);
                    $checkFollow = follow_user::where('follower_id', $user->id)
                        ->where('follow_id', $request->get('block_user_id'))
                        ->where('status', '1')
                        ->first();

                    if ($checkFollow) {
                        $checkFollow->status = '0';
                        $checkFollow->save();
                    }
                    return JsonResponse::success([], __('views.Done'));
                }

            }

            return JsonResponse::fail('You Cant Block Yourself', 400);
        } catch (\Exception $exception) {
            return JsonResponse::fail($exception->getMessage(), 400);
        }


    }

    public function unblockUser(Request $request)
    {

        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }
        $rules = Validator::make($request->all(), [

            'block_user_id' => 'required|exists:users,id',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }


        try {
            $checkUserBlock = Userblock::where('user_id', $user->id)
                ->where('block_user_id', $request->get('block_user_id'))
                ->first();

            if ($user->id != $request->get('block_user_id')) {
                if ($checkUserBlock) {
                    if ($checkUserBlock->status == '1') {
                        $checkUserBlock->status = '0';
                        $checkUserBlock->save();
                    } else {
                        return JsonResponse::success([], __('views.Done'));

                    }

                    return JsonResponse::success([], __('views.Done'));
                } else {
                    Userblock::create([
                        'user_id' => $user->id,
                        'status' => '0',
                        'block_user_id' => $request->get('block_user_id'),

                        //       'comment'        =>      $request->comment,
                    ]);
                    $checkFollow = follow_user::where('follower_id', $user->id)
                        ->where('follow_id', $request->get('block_user_id'))
                        ->where('status', '0')
                        ->first();

                    if ($checkFollow) {
                        $checkFollow->status = '0';
                        $checkFollow->save();
                    }
                    return JsonResponse::success([], __('views.Done'));
                }

            }

            return JsonResponse::fail('You Cant Block Yourself', 400);
        } catch (\Exception $exception) {
            return JsonResponse::fail($exception->getMessage(), 400);
        }


    }

    public function blockUsersList(Request $request)
    {

        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $user = User::find($user->id);
        $block_users = $user->blockUsers()->paginate(5);


        $resource = appuserabdResponse::collection($block_users)->response()->getData(true);
        return JsonResponse::success($resource, __('views.Done'));

    }


    public function myfollowers(Request $request)
    {
        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $user = User::find($user->id);
        $followers = $user->flowers()->paginate(5);
        $resource = appuserabdResponse::collection($followers)->response()->getData(true);
        return JsonResponse::success($resource, __('views.Done'));
    }

    public function myfollowing(Request $request)
    {
        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $user = User::find($user->id);
        $followers = $user->following()->paginate(5);
        $resource = appuserabdResponse::collection($followers)->response()->getData(true);
        return JsonResponse::success($resource, __('views.Done'));
    }

    public function followuser(Request $request)
    {
        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }
        $user = User::find($user->id);
        $block_users = $user->blockUsers()->pluck('block_user_id');


        $rules = Validator::make($request->all(), [

            'user_id' => 'required|exists:users,id',
            'status' => 'required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }


        if (in_array($request->get('user_id'), $block_users->toArray())) {
            return JsonResponse::fail('You Cant Follow Blocked User,Un Block Him To Follow', 400);


        }


        if ($request->status == 1) {
            $data = ['follower_id' => $user->id, 'follow_id' => $request->user_id];
            follow_user::updateOrCreate($data, ['status' => 1]);
            UserNotifaction::create([
                'user_id' => $request->user_id,
                'owner_id' => $user->id,
                'type' => 1,
            ]);


            // send_push_to_topic('MJTopic', 'test', 'test', '0');
            //     dd($client->device_token);*/
            $client = User::find($request->user_id);
            if ($client) {
                sendNotfication($request->user_id, 0, __('api.Some One Follow You'));

            }
            return JsonResponse::success([], __('views.Done'));
        } else {
            DB::table('follow_users')->where('follow_id', $request->user_id)->where('follower_id', $user->id)->delete();
            return JsonResponse::success([], __('views.Done'));
        }

    }//end followuser

    public function myVideos(Request $request)
    {
        $user_id = $this->getUserID($request->bearerToken());
        $user = appuser::find($user_id);
        $followers = $user->myVideos()->paginate(5);
        $resource = appuserabdResponse::collection($followers)->response()->getData(true);
        return $this->returnData('posts', $resource);
    }



    public function userStoires(Request $request)
    {

        $user = $request->user();
        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }

        $rules = Validator::make($request->all(), [

            'user_id' => 'required|exists:users,id',



        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }
        $userProfile = User::find($request->get('user_id'));
        $posts = $userProfile->stories()->latest()->paginate(5);
        $posts = PostResource::collection($posts)->response()->getData(true);
        return JsonResponse::success($posts, __('views.Done'));


    }

    public function userPosts(Request $request)
    {

        $user = $request->user();
        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }
        $rules = Validator::make($request->all(), [

            'user_id' => 'required|exists:users,id',



        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        $userProfile = User::find($request->get('user_id'));
        $posts = $userProfile->posts()->latest()->paginate(5);
        $posts = PostResource::collection($posts)->response()->getData(true);
        return JsonResponse::success($posts, __('views.Done'));


    }
    public function userfollowers(Request $request)
    {
        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $rules = Validator::make($request->all(), [

            'user_id' => 'required|exists:users,id',



        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        $userProfile = User::find($request->get('user_id'));
        $followers = $userProfile->flowers()->paginate(5);
        $resource = appuserabdResponse::collection($followers)->response()->getData(true);
        return JsonResponse::success($resource, __('views.Done'));
    }

    public function userfollowing(Request $request)
    {
        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $rules = Validator::make($request->all(), [

            'user_id' => 'required|exists:users,id',



        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        $userProfile = User::find($request->get('user_id'));
        $followers = $userProfile->following()->paginate(5);
        $resource = appuserabdResponse::collection($followers)->response()->getData(true);
        return JsonResponse::success($resource, __('views.Done'));
    }
}
