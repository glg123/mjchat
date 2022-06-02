<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\JsonResponse;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Models\User;
use Validator;
use Illuminate\Http\Request;
use App\Models\AdminSettings;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    public function username()
    {
        return 'email';
    }
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin/login';
    protected $redirectToAdmin = '/admin/dashboard';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->middleware('guest', ['except' => 'logout']);
    }


    public function loginAdminOld(Request $request)
    {


        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }


        if ($this->attemptLogin($request)) {

            return $this->sendLoginResponseAdmin($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function loginAdmin(Request $request)
    {
        // TODO when facebook user doesn't have email just phone number

        $rules = Validator::make($request->all(), [

            'email' => 'required_if:referer,local|max:255',
            'password' => 'required_if:referer,local|min:3',
            //  'device_token' => 'sometimes|required',
            //   'device_type'  => 'required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }


        $username = $request->email;
        $mobile = 0;
        $old_mobile = "";
        $username_column = 'email';




        $request->merge([
            $username_column => $request->email,
            'status' => true
        ]);
        $credentials = $request->only($username_column, 'password', 'status');

        $class = new Admin();


        // $user = $class::where('email', 'a@a.a')->first();
        $user = $class::where($username_column, $username)->first();



        if ((!$user) || (app('hash')->check($credentials['password'], $user->password) === false)) {
            $errors = [$this->username() => __('validation.password')];


            return redirect()->route('loginAdmin')->withErrors($errors);

        }


        if ($user->api_token == null) {
            $user->api_token = hash('sha512', time());

        }
        // $user->device_token = $request->get('device_token');
        $user->save();
        $user = Auth::guard('Admin')->loginUsingId($user->id);


        return redirect()->intended(route('admin.dashboard.index'));

        //  return response()->success("User Profile", $user);
        //  return ['data' => $user];
    }

    protected function attemptLogin(Request $request)
    {

        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }

    protected function sendLoginResponseAdmin(Request $request)
    {


        $request->session()->regenerate();

        $this->clearLoginAttempts($request);


        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($this->redirectPathAdmin());


    }


    public function redirectPathAdmin()
    {
        if (method_exists($this, 'redirectToAdmin')) {
            return $this->redirectToAdmin();
        }

        return property_exists($this, 'redirectToAdmin') ? $this->redirectToAdmin : '/admin/home';
    }


    /**
     * Get the failed login message.
     *
     * @return string
     */
    protected function getFailedLoginMessage()
    {
        return trans('auth.error_logging');
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        //  $request->session()->invalidate();
        //   $lang=   \Session::get('locale');


        //  return redirect()->back();
        return $this->loggedOut($request) ?: redirect('admin/login');
    }


    public function showLoginFormAdmin()
    {

        return view('auth.loginAdmin');
    }

    protected function guard()
    {
        return Auth::guard('Admin');
    }

}
