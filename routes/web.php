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


            Route::get('/edit/my/profile', 'Admin\SettingController@editMyProfile')->name('admin.edit');
            Route::post('/edit', 'Admin\SettingController@updateMyProfile')->name('admin.update');


            Route::get('/settings', 'Admin\SettingController@editSettings')->name('admin.settings.edit');
            Route::post('/settings', 'Admin\SettingController@updateSettings')->name('admin.settings.update');

            Route::get('/users', 'Admin\UserController@users')->name('admin.users.index');
            Route::get('/user/{id}/show', 'Admin\UserController@edit')->name('admin.users.show');
            Route::get('/user/{id}/update', 'Admin\UserController@update')->name('admin.users.update');
            Route::post('/user/status', 'Admin\UserController@changeStatus')->name('admin.users.status');
            Route::post('/user/delete/{id}', 'Admin\UserController@changeStatus')->name('admin.users.delete');
            Route::post('/users/datatable', 'Admin\UserController@datatable')->name('admin.users.datatable');



            Route::get('/posts', 'Admin\PostController@posts')->name('admin.posts.index');
            Route::post('/post/comment', 'Admin\PostController@postComment')->name('admin.postComment.show');
            Route::get('/post/{id}/show', 'Admin\PostController@edit')->name('admin.posts.show');
            Route::get('/post/{id}/update', 'Admin\PostController@update')->name('admin.posts.update');
            Route::post('/post/status', 'Admin\PostController@changeStatus')->name('admin.posts.status');
            Route::post('/post/delete/{id}', 'Admin\PostController@changeStatus')->name('admin.posts.delete');
            Route::post('/post/datatable', 'Admin\PostController@datatable')->name('admin.posts.datatable');




            Route::get('/adds', 'Admin\SettingController@adds')->name('admin.adds.index');
            Route::get('/adds/create', 'Admin\SettingController@createAdds')->name('admin.adds.create');
            Route::post('/adds', 'Admin\SettingController@addsStore')->name('admin.adds.store');
            Route::get('/add/{id}/edit', 'Admin\SettingController@edit')->name('admin.adds.edit');
            Route::post('/add/{id}/update', 'Admin\SettingController@Addupdate')->name('admin.adds.update');
            Route::post('/adds/datatable', 'Admin\SettingController@Addsdatatable')->name('admin.adds.datatable');


            Route::post('/logout', 'Auth\LoginController@logout')->name('admin.logout'); // TODO


        }); // end middleware Role IMAGE        });

    });
});



Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
