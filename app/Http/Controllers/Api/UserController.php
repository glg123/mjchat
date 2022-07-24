<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\aboutUserResource;
use App\Http\Resources\AllStoriesResource;
use App\Http\Resources\MapResource;
use App\Http\Resources\PostResource;
use App\Http\Resources\SearchResponse;
use App\Http\Resources\SingleStoryResource;

use App\Models\Post;
use App\Models\storyView;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{

    public function userShow(Request $request, $id)
    {
        $user = $request->user();
        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }


        $userShow = User::find($id);
        $user->check_id = $user->id;
        $request->merge([
            'id' => $id,
            'check_id' => $user->id,
        ]);

        if (!$userShow) {
            return JsonResponse::fail(__('views.not found'));
        }

        $userShow = aboutUserResource::collection([$userShow]);
        return JsonResponse::success($userShow[0], __('views.Done'));
    }//end otheruserProfile

    public function userPosts(Request $request, $id)
    {
        $user = $request->user();
        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }

        $userPost = User::find($id);
        $posts = $userPost->posts()->latest()->paginate(5);
        foreach ($posts as $key => $single) {
            $posts[$key]->check_id = $user->id;
        }
        $posts = PostResource::collection($posts)->response()->getData(true);
        return JsonResponse::success($posts, __('views.Done'));
    }//end otheruserProfile

    public function HomePosts(Request $request)
    {
        $user = $request->user();
        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }

        $user = User::find($user->id);
        $block_users = $user->blockUsers()->pluck('block_user_id');

        $rules = Validator::make($request->all(), [

            'type' => 'required',
        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }
        $PromotionPost = Post::whereHas('PromotionPost')
            ->select('posts.*')
            ->whereRaw(' deleted_at is null')->paginate(5);
        $PromotionPost = AllStoriesResource::collection($PromotionPost)->response()->getData(true);
        if ($request->type == 1) {
            $q = Post::distance($request->lat, $request->long);
            $stories = $q->whereNotIn('user_id', $block_users->toArray())->orderBy('distance', 'ASC')->where('type', '=', 2)->latest()->paginate(5);
            foreach ($stories as $key => $single) {
                $stories[$key]->check_id = $user->id;
            }
            $storyResource = PostResource::collection($stories)->response()->getData(true);

            $homePost = ['posts' => $storyResource, 'PromotionPost' => $PromotionPost];
            return JsonResponse::success($homePost, __('views.Done'));
        } else {
            $arr = [];
            $user = User::find($user->id);
            $following = $user->following()->pluck('users.id');
            $posts = Post::whereIn('user_id', $following->toArray())
                ->whereNotIn('user_id', $block_users->toArray())->paginate(5);

            $resource = PostResource::collection($posts)->response()->getData(true);
            $homePost = ['posts' => $resource, 'PromotionPost' => $PromotionPost];

            return JsonResponse::success($homePost, __('views.Done'));

        }

    }

    public function users(Request $request)
    {
        $rules = Validator::make($request->all(), [

            //    'search' => 'required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }
        $users = User::query();

        if ($request->get('search')) {
            $users = $users->where('first_name', 'like', '%' . trim($request->search) . '%');
        }


        $users = $users->latest()->paginate(10);
        $users = SearchResponse::collection($users)->response()->getData(true);
        return JsonResponse::success($users, __('views.Done'));

    }
}
