<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\MapResource;
use App\Http\Resources\SingleStoryResource;

use App\Models\Post;
use App\Models\PostReport;
use App\Models\SaveStory;
use App\Models\Setting;
use App\Models\ShareStory;
use App\Models\Storyblock;
use App\Models\storyView;
use App\Models\Strorylike;
use App\Models\User;
use App\Models\user_location;
use App\Models\UserLocation;
use App\Models\UserNotifaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class StoryController extends Controller
{

    protected function storymap(Request $request)
    {


        $rules = Validator::make($request->all(), [

            'lat' => 'required',
            'long' => 'required',
        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }
        $user = $request->user();

        if (!$user) {
            $user_id = 0;
        } else {
            $user_id = $user->id;
        }


        $data = [
            'lat' => $request->lat,
            'long' => $request->long,
            'user_id' => $user_id,
        ];

        $request->merge([
            //  'password'          => app('hash')->make($request->input('password')),
            'user_id' => $user_id,

        ]);


        $map = MapResource::collection($data);
        return JsonResponse::success($map, __('views.Done'));


    }//end of index


    public function singleStory(Request $request)

    {


        $rules = Validator::make($request->all(), [

            'story_id' => 'required|exists:posts,id'
        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        $user = $request->user();
        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }


        StoryView::updateOrCreate([
            'user_id' => $user->id,
            'story_id' => $request->get('story_id'),
        ]);
        $count = DB::table('story_views')
            ->where('user_id', $user->id)
            ->where('story_id', $request->story_id)->count();
        $story = Post::find($request->story_id);
        $story['current_user'] = $user->id;

        $story = SingleStoryResource::collection([$story]);
        return JsonResponse::success($story[0], __('views.Done'));

    }


    public function newPost(Request $request)
    {
        $user = $request->user();
        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }

        $rules = Validator::make($request->all(), [

            'type' => 'required|numeric',
            'media' => 'required',
            'lat' => 'required',
            'long' => 'required',
            'comment' => 'required',
        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        $path = $request->file('media')->store('public/posts/photo');

        $path = str_replace('public', 'storage', $path);
        //    $estate->instrument_file = 'https://aqarz.s3.me-south-1.amazonaws.com/' . $path;

        $request->merge([
            //  'password'          => app('hash')->make($request->input('password')),
            'media' => $path,
            'user_id' => $user->id,

        ]);


        try {

            $post = Post::create($request->only([
                'user_id',
                'comment',

                'lat',
                'long',
                'status',
                'recommended',
                'type',
                'has_comments',
                'peremotion_type',
                'shares',
                'shared',
                'start_date',
                'expire_date',

            ]));
            $post->media = $path;
            $post->save();

            $this->addlocation($user->id, $request->get('lat'), $request->get('long'), $request->get('address'));


            try {
                return JsonResponse::success([], __("views.Done"));
            } catch (ModelNotFoundException $exception) {

                return JsonResponse::fail($exception->getMessage(), 400);


            }

        } catch (\Exception $e) {

            return JsonResponse::fail($e->getMessage(), 400);


        }


    }

    public function updatePost(Request $request)
    {
        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }


        $rules = Validator::make($request->all(), [

            'post_id' => 'required|exists:posts,id',
        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }


        try {
            $user = User::find($user->id);
            if (!$user) {
                //return JsonResponse::fail('Credentials not match', 401);

                return JsonResponse::fail(__('views.not authorized'));

            }
            $post = $user->post_story()->find($request->get('post_id'));
            if (!$post) {
                return JsonResponse::fail(__('views.not found'));
            }
            $post = $post->update($request->only([
                'user_id',
                'comment',

                'lat',
                'long',
                'status',
                'recommended',
                'type',
                'has_comments',
                'peremotion_type',
                'shares',
                'shared',
                'start_date',
                'expire_date',

            ]));

            if ($request->hasFile('media')) {
                $path = $request->file('media')->store('public/posts/photo');

                $path = str_replace('public', 'storage', $path);
                $post->media = $path;
                $post->save();
            }


            try {
                return JsonResponse::success([], __("views.Done"));
            } catch (ModelNotFoundException $exception) {

                return JsonResponse::fail($exception->getMessage(), 400);


            }

        } catch (\Exception $e) {

            return JsonResponse::fail($e->getMessage(), 400);


        }


    }

    public function addlocation($user_id, $lat, $long, $address)
    {
        $new = UserLocation::updateOrCreate([
            'user_id' => $user_id,
            'lat' => $lat,
            'long' => $long,
            'address' => $address,
        ]);
    }//end addlocation

    // end store

    public function deletePost(Request $request, $id)
    {
        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }
        try {
            $user = User::find($user->id);
            if (!$user) {
                //return JsonResponse::fail('Credentials not match', 401);

                return JsonResponse::fail(__('views.not authorized'));

            }
            $post = $user->post_story()->find($id);
            if (!$post) {
                return JsonResponse::fail(__('views.not found'));
            }


            try {
                if (file_exists(public_path($post->getAttributes()['media']))) {
                    unlink(public_path($post->getAttributes()['media']));
                    //   unlink()
                }
                $post->delete();
                return JsonResponse::success([], __("views.Done"));
            } catch (ModelNotFoundException $exception) {

                return JsonResponse::fail($exception->getMessage(), 400);


            }

        } catch (\Exception $e) {

            return JsonResponse::fail($e->getMessage(), 400);


        }
    }

    public function sendPostReport(Request $request)
    {

        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }
        $rules = Validator::make($request->all(), [

            'post_id' => 'required|exists:posts,id',
            'message_reasons_id' => 'required|exists:message_resons,id',
            'content' => 'required'

        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        try {
            $request->merge([

                'user_id' => $user->id,

            ]);
            $reportChek = PostReport::where('user_id', $user->id)
                ->where('post_id', $request->get('post_id'))->first();

            if ($reportChek) {
                return JsonResponse::success([], __('views.Done'));
            }

            $report = PostReport::create($request->only([
                'post_id',
                'message_reasons_id',
                'content',
                'user_id',

            ]));

            return JsonResponse::success([], __('views.Done'));

        } catch (\Exception $exception) {
            return JsonResponse::fail($exception->getMessage(), 400);
        }

    }//end feed back


    public function sharePost(Request $request)
    {

        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }
        $rules = Validator::make($request->all(), [

            'story_id' => 'required|exists:posts,id',
            'lat' => 'required',
            'long' => 'required',

        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }
        $data = [
            'user_id' => $user->id,
            'story_id' => $request->story_id,
        ];

       // $points = DB::table('settings')->first();
        $sharePrice = Setting::where('key', 'sharePrice')->first()->value_ar;
        $user = User::find($user->id);
        $user->points = $sharePrice;
        $user->save();
        $share = ShareStory::updateOrCreate($data);
        $article = Post::find($request->story_id);
        $new = $article->replicate();
        $new->user_id = $user->id;
        $new->created_at = Carbon::now();
        $new->lat = $request->lat;
        $new->long = $request->long;
        $new->save();

        return JsonResponse::success([], __("views.Done"));


    }


    public function savePost(Request $request)
    {

        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }
        $rules = Validator::make($request->all(), [

            'story_id' => 'required|exists:posts,id',
            'status' => 'required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }
        $data = [
            'user_id' => $user->id,
            'story_id' => $request->story_id,
        ];


        try {
            $save = SaveStory::updateOrCreate($data, ['status' => $request->status]);

            return JsonResponse::success([], __("views.Done"));

        }
        catch (\Exception $exception)
        {
            return JsonResponse::fail($exception->getMessage(), 400);
        }


    }

    public function likePost(Request $request)
    {

        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }
        $rules = Validator::make($request->all(), [

            'story_id' => 'required|exists:posts,id',
            'status' => 'required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }
        $data = [
            'user_id' => $user->id,
            'story_id' => $request->story_id,
        ];


        try {
            $like = Strorylike::updateOrCreate($data, ['status' => $request->status]);
            $story = Post::find($request->story_id);
            UserNotifaction::create([
                'user_id' => $story->user_id,
                'owner_id' => $user->id,
                'post_id' => $request->story_id,
                'type' => 2,
            ]);
            sendNotfication($story->user_id, $request->story_id, __('api.Some One Like Your Post'));

            return JsonResponse::success([], __("views.Done"));
        }
        catch (\Exception $exception)
        {
            return JsonResponse::fail($exception->getMessage(), 400);
        }



    }


    public function blockStory(Request $request){


        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }
        $rules = Validator::make($request->all(), [

            'story_id' => 'required|exists:posts,id',



        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }


        try {

            Storyblock::create([
                'user_id'        =>      $user->id,
                'story_id'        =>      $request->story_id,
                //       'comment'        =>      $request->comment,
            ]);
            return JsonResponse::success([], __("views.Done"));
        }
        catch (\Exception $exception)
        {
            return JsonResponse::fail($exception->getMessage(), 400);
        }


    }
//sharePost
}
