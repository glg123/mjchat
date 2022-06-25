<?php


use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
