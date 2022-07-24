<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\AllStoriesResource;
use App\Http\Resources\CommentResource;
use App\Http\Resources\GropChatMemberResource;
use App\Http\Resources\GropChatResource;
use App\Http\Resources\MapResource;
use App\Http\Resources\SingleStoryResource;

use App\Http\Resources\userAddsResource;
use App\Models\add;
use App\Models\appuser;
use App\Models\Comment;
use App\Models\CommentLikes;
use App\Models\GroupChat;
use App\Models\GroupChatMember;
use App\Models\Post;
use App\Models\PostReport;
use App\Models\SaveStory;
use App\Models\Setting;
use App\Models\ShareStory;
use App\Models\Storyblock;
use App\Models\StoryView;
use App\Models\Strorylike;
use App\Models\User;

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

    protected function storymap2(Request $request)
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


        $q = Post::distance($request->get('lan'), $request->get('lang'));
        $stories = $q->orderBy('distance', 'ASC')
            ->where('type', '=', 1)
            ->get();
        $storyResource = AllStoriesResource::collection($stories);


        $map = MapResource::collection($data)->response()->getData(true);
        return JsonResponse::success($map, __('views.Done'));


    }//end of index


    public function storymap(Request $request)
    {

        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }
        $rules = Validator::make($request->all(), [
            //   'is_list' => 'required',
            'lat' => 'required',
            'long' => 'required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }
        $followers = $user->following()->pluck('users.id');
        $array = array_map('intval', $followers->toArray());
        $array = join(",", $array);
        $array = '(' . $array . ')';
        //  $query .= ' and city_id   IN ("' . $array . '") ';


        $friendPost = DB::table('posts')
            ->select('posts.*')
            ->whereRaw(' deleted_at is null');
         //   ->whereRaw('  user_id   IN ' . $array);

        $PromotionPost = Post::whereHas('PromotionPost')
            ->select('posts.*')
            ->whereRaw(' deleted_at is null');


        $adds = add::whereRaw(' deleted_at is null');

        $distance = 500;
        if ($request->get('lat') && $request->get('long')) {


            $attitude = $request->get('lat');
            $longitude = $request->get('long');
            $distanceL = $distance;


            if ($attitude && $longitude) {
                $location = nearest($attitude, $longitude, $distanceL);




                $friendPost=    $friendPost->whereRaw('  posts.lat  between ' . $location->min_lat .
                    ' and ' . $location->max_lat .
                    ' and posts.long between '
                    . $location->min_lng .
                    ' and ' . $location->max_lng .
                    ' and posts.deleted_at is null'
                );


                $PromotionPost=    $PromotionPost->whereRaw('  posts.lat  between ' . $location->min_lat .
                    ' and ' . $location->max_lat .
                    ' and posts.long between '
                    . $location->min_lng .
                    ' and ' . $location->max_lng .
                    ' and posts.deleted_at is null'
                );
                $adds=   $adds->whereRaw('  adds.lat  between ' . $location->min_lat .
                    ' and ' . $location->max_lat .
                    ' and adds.long between '
                    . $location->min_lng .
                    ' and ' . $location->max_lng .
                    ' and adds.deleted_at is null'
                );
            }


        }
        $friendPost = $friendPost->orderByRaw(DB::Raw(' `posts`.`id` desc '))->get();
        $PromotionPost = $PromotionPost->orderByRaw(DB::Raw(' `posts`.`id` desc '))->get();
        $adds = $adds->orderByRaw(DB::Raw(' `adds`.`id` desc '))->get();
        $friendPost=AllStoriesResource::collection($friendPost);
        $PromotionPost=AllStoriesResource::collection($PromotionPost);
        $adds= userAddsResource::collection($adds);
$array=[
    'friendStories'=>$friendPost,
    'PromotionStories'=>$PromotionPost,
    'adds'=>$adds,

];
        return JsonResponse::success($array, __('views.Done'));
    }


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


        $storyView = StoryView::updateOrCreate([
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

        } catch (\Exception $exception) {
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
        } catch (\Exception $exception) {
            return JsonResponse::fail($exception->getMessage(), 400);
        }


    }


    public function blockStory(Request $request)
    {


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
                'user_id' => $user->id,
                'story_id' => $request->story_id,
                //       'comment'        =>      $request->comment,
            ]);
            return JsonResponse::success([], __("views.Done"));
        } catch (\Exception $exception) {
            return JsonResponse::fail($exception->getMessage(), 400);
        }


    }


    public function mysavedPosts(Request $request)
    {

        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $user = User::find($user->id);
        $posts = $user->savedPosts()->where('fav_stories.status', 1)->paginate();
        $resource = AllStoriesResource::collection($posts)->response()->getData(true);;
        return JsonResponse::success($resource, __("views.Done"));
    }

//sharePost


    public function PostComments(Request $request, $id)
    {
        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $user = User::find($user->id);
        $comments = Comment::with('comments')
            ->where('post_id', $id)
            //->where('perant_id', '=', null)
            ->latest()->paginate(10);
        foreach ($comments as $key => $ab) {
            $comments[$key]->cur_id = $user->id;
        }
        $resource = CommentResource::collection($comments)->response()->getData(true);;

        return JsonResponse::success($resource, __("views.Done"));

    }

    public function sendPostComments(Request $request)
    {
        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $user = User::find($user->id);


        $rules = Validator::make($request->all(), [

            'post_id' => 'required|exists:posts,id',
            'perant_id' => 'sometimes|required|exists:comments,id',
            'text' => 'required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        $post = Post::find($request->post_id);
        $owner = User::find($post->user_id);


        //  $current_user=appuser::find($user_id);
        if ($owner->comment_privacy == 1) {
            $show = 1;
        } else if ($owner->comment_privacy == 2) {
            $follwoing = DB::table('follow_users')->where('follower_id', '=', $owner->id)->where('follow_id', '=', $user->id)->where('status', '=', 1)->count();
            $follwoed = DB::table('follow_users')->where('follow_id', '=', $user->id)->where('follower_id', '=', $owner->id)->where('status', '=', 1)->count();


            if ($follwoing == 0 || $follwoed == 0) {
                $show = 0;
            } else {

                $show = 1;
            }
        } else if ($owner->comment_privacy == 3) {
            $follwoing = DB::table('follow_users')->where('follower_id', '=', $owner->id)->where('follow_id', '=', $user->id)->where('status', '=', 1)->count();
            if ($follwoing == 0) {
                $show = 0;
            } else {
                $show = 1;
            }
        } else {
            $follwoed = DB::table('follow_users')
                ->where('follow_id', '=', $owner->id)->where('follower_id', '=', $user->id)
                ->where('status', '=', 1)->count();
            if ($follwoed == 0) {
                $show = 0;
            } else {
                $show = 1;
            }
        }

        if ($owner->id == $user->id) {
            $show = 1;
        }
        if ($show == 1) {
            $data = $request->except('comment_id');
            $data['user_id'] = $user->id;
            if ($request->has('comment_id')) {
                $data['perant_id'] = $request->comment_id;
            }
            Comment::create($data);
            $story = Post::find($request->post_id);
            UserNotifaction::create([
                'user_id' => $story->user_id,
                'owner_id' => $user->id,
                'post_id' => $request->post_id,
                'type' => 3,
            ]);
            // $this->sendNotfication($story->user_id, $request->post_id, __('api.Some One Comment On Your Post'));

            sendNotfication($story->user_id, $request->story_id, __('api.Some One Comment On Your Post'));
            return JsonResponse::success([], __("views.Done"));


        } else {
            return JsonResponse::fail('عفو لا يمكنك التعليق ', 400);


        }


    }


    public function DeleteComment(Request $request, $id)
    {
        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $user = User::find($user->id);
        $findComment = $user->comments()->find($id);
        if (!$findComment) {
            return JsonResponse::success([], __("views.not found"));
        }
        $likes = CommentLikes::where('comment_id', $findComment->id)->delete();
        Comment::destroy($id);
        return JsonResponse::success([], __("views.Done"));
    }


    public function likeComment(Request $request)
    {
        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $user = User::find($user->id);


        $rules = Validator::make($request->all(), [

            'comment_id' => 'required|exists:comments,id',
            'status' => 'required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }


        $checkLike = CommentLikes::where('user_id', $user->id)
            ->where('comment_id', $request->get('comment_id'))->first();

        if (!$checkLike) {
            $data = $request->all();
            $data['user_id'] = $user->id;
            CommentLikes::create($data);
            $commet = Comment::find($request->get('comment_id'));
            UserNotifaction::create([
                'user_id' => $commet->user_id,
                'owner_id' => $user->id,
                'post_id' => $commet->post_id,
                'type' => 3,
            ]);
            sendNotfication($commet->user_id, $request->post_id, __('api.Some One Like Your Comment'));


        }
        if ($checkLike->status != $request->get('status')) {
            $checkLike->status = $request->get('status');
            $checkLike->save();
        }
        return JsonResponse::success([], __("views.Done"));

    }//end LikeComments


    public function mygroups(Request $request)
    {
        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $user = User::find($user->id);


        $groups = $user->mygroups();

        if ($request->get('search')) {

            $groups = $groups->Where('name', 'like', '%' . $request->get('search') . '%');
        }

        if ($request->get('group_id')) {

            $groups = $groups->where('id', $request->get('group_id'));

        }
        $groups = $groups->paginate();
        return JsonResponse::success($groups, __("views.Done"));

    }


    public function myGroupMember(Request $request)
    {
        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $user = User::find($user->id);


        $groups = $user->mygroups();

        if ($request->get('search')) {

            $groups = $groups->WhereHas('user', function ($query) use ($request) {
                $query->Where('first_name', 'like', '%' . $request->get('search') . '%')
                    ->Where('last_name', 'like', '%' . $request->get('search') . '%');

            });

        }

        if ($request->get('group_id')) {

            $groups = $groups->where('group_id', $request->get('group_id'));

        }
        $groups = $groups->paginate();

        $groups = GropChatMemberResource::collection($groups);

        return JsonResponse::success($groups, __("views.Done"));

    }

    public function createGroup(Request $request)
    {
        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $user = User::find($user->id);


        $rules = Validator::make($request->all(), [

            'group_name' => 'required',
            'members' => 'required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }

        $group = GroupChat::create([
            'user_id' => $user->id,
            'name' => $request->group_name
        ]);
        if ($group) {
            $memberIds = explode(',', $request->members);
            foreach ($memberIds as $single) {
                $checkUser = User::find($single);
                if ($checkUser) {
                    GroupChatMember::create([
                        'group_id' => $group->id,
                        'user_id' => $single,
                    ]);
                }

            }

            return JsonResponse::success(['msg' => 'Group created Successfully', 'id' => $group->id], __("views.Done"));

        } else {
            return JsonResponse::fail('Dos not created Successfully', 400);


        }

    }


    public function removeGroupMembers(Request $request)
    {
        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $user = User::find($user->id);


        $rules = Validator::make($request->all(), [

            'member_id' => 'required',
            'group_id' => 'required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }


        $groups = $user->mygroups()->where('id', $request->get('group_id'))->first();

        $checkUser = $groups->members()->where('user_id', $request->get('member_id'))->first();

        if (!$checkUser) {

            return JsonResponse::fail('No Member Found', 400);
        }
        $checkUser->delete();
        return JsonResponse::success([], __("views.Done"));


    }

    public function addGroupMembers(Request $request)
    {


        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $user = User::find($user->id);


        $rules = Validator::make($request->all(), [

            'member_id' => 'required',
            'group_id' => 'required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }
        $groups = $user->mygroups()->where('id', $request->get('group_id'))->first();

        $checkUser = $groups->members()->where('user_id', $request->get('member_id'))->first();

        if (!$checkUser) {
            $User = User::find($request->get('member_id'));

            if (!$User) {

                return JsonResponse::fail('No User Found', 400);
            }

            $member = GroupChatMember::create([
                'user_id' => $User->id,
                'group_id' => $groups->id,
            ]);

            return JsonResponse::success($member, __("views.Done"));


        } elseif ($checkUser) {

            return JsonResponse::fail('User Added Before', 400);

        }


    }

    public function leaveGroup(Request $request)
    {


        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $user = User::find($user->id);
        $rules = Validator::make($request->all(), [

            'group_id' => 'required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }


        $groups = GroupChat::where('id', $request->get('group_id'))->first();

        $checkUser = $groups->members()->where('user_id', $user->id)->first();

        if (!$checkUser) {
            return JsonResponse::fail('No User Found', 400);

        } elseif ($checkUser) {
            $checkUser->delete();
            return JsonResponse::success([], __("views.Done"));

        }

        return JsonResponse::success([], __("views.Done"));

    }


    public function deleteGroup(Request $request, $id)
    {
        $user = $request->user();
        if (!$request->user()) {

            return JsonResponse::fail(__('views.not authorized'));

        }

        $user = User::find($user->id);
        $group = $user->mygroups()->where('id', $id)->first();
        $member = $group->groups_members()->delete();
        $group->delete();
        return JsonResponse::success([], __("views.Done"));
    }
}
