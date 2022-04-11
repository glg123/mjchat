<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::group(['prefix' => 'admin'], function () {
    Route::get('login', 'Auth\LoginController@showLoginFormAdmin')->name('loginAdmin');
    Route::post('login', 'Auth\LoginController@loginAdmin')->name('sendLoginAdmin');
});
Route::group(['middleware' => 'SetLocalizationFrontend'], function () {


    $locales_available = ['ar', 'en'];
    $current_local = request()->segment(1);


    if (!in_array($current_local, $locales_available)) {
        $current_local = '';
    }
    Route::prefix($current_local)->group(function () {
        Route::group(['prefix' => 'admin'], function () {


            Route::get('lang/{id}', function ($id) {


                // dd(request()->cookie('locale'));
                //session(['lang' => request()->local]);
                $locale = $id;
                $previous_locale = 'ar';
                if ($locale == 'ar') {
                    $previous_locale = 'en';
                }

                if ($previous_locale == 'ar') {
                    $locale = 'en';
                }

                $path = parse_url(url()->previous());
                if (isset($path['path'])) {
                    $path = parse_url(url()->previous())['path'];

                } else {
                    $path = '';

                }
                $path = str_replace(['/ar', '/en'], ['', ''], $path);
                $previous_url = $locale . $path;
                App::setLocale($locale);
                if (in_array($path, ['/lang', 'lang']))
                    $previous_url = $locale;


                return redirect($previous_url)->withCookie(cookie()->forever('locale', $locale));
            })->name('lang.switch')->where(['id' => '[a-z]+']);


            Route::get('dashboard', 'Admin\SettingController@index')->name('admin.dashboard.index');
            Route::get('/dashboard/lang/{lang}', 'Admin\SettingController@lang')->name('admin.dashboard.lang'); // new


            Route::get('/edit', 'Admin\SettingController@edit')->name('admin.settings.edit');
            Route::post('/edit', 'Admin\SettingController@update')->name('admin.settings.update');


            Route::post('/logout', 'Auth\LoginController@logout')->name('admin.logout'); // TODO


        }); // end middleware Role IMAGE        });

    });
});



Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
