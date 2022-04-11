<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\aboutUserResource;
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

    public function userShow(Request $request,$id)
    {
        $user = $request->user();
        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }



        $userShow = User::find($id);
        $user->check_id = $user->id;
        $userShow = aboutUserResource::collection([$userShow]);
        return JsonResponse::success($userShow[0], __('views.Done'));
    }//end otheruserProfile

    public function userPosts(Request $request,$id)
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
        $posts = PostResource::collection($posts);
        return JsonResponse::success($posts, __('views.Done'));
    }//end otheruserProfile


    public function users(Request $request)
    {
        $rules = Validator::make($request->all(), [

        //    'search' => 'required',


        ]);

        if ($rules->fails()) {
            return JsonResponse::fail($rules->errors()->first(), 400);
        }
        $users = User::query();

            if($request->get('search'))
            {
                $users=$users->where('first_name', 'like', '%' . trim($request->search) . '%');
            }


        $users=$users->latest()->paginate(10);
        $users = SearchResponse::collection($users);
        return JsonResponse::success($users, __('views.Done'));

    }
}
