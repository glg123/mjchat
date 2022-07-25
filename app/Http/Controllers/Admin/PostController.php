<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\JsonResponse;
use App\Models\Admin;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class PostController extends Controller
{


    public function posts(Request $request)
    {
        $index_url = route('admin.posts.datatable');
        $edit_url = route('admin.posts.show', 'id');
        $posts = Post::with('owner');

        if ($request->get('search')) {
            $search = trim($request->get('search'));
            $posts
                ->where('lat', 'like', '%' . $search . '%')
                ->orwhere('comment', 'like', '%' . $search . '%')
                ->orWhere('long', 'like', '%' . $search . '%');
        }
        $html_breadcrumbs = [
            'title' => __('views.posts'),
            'subtitle' => __('views.Index'),
            'datatable' => true,
        ];

        $posts = $posts->orderBy('id', 'desc')->paginate(12);
        return view(
            'admin.posts.list',
            compact(
                'html_breadcrumbs',


                'index_url',
                'posts',
                'edit_url'

            )
        );
    }


    public function edit($id)
    {
        $post = Post::find($id);

        if (!$post) {
            \Session::flash('error', trans('المنشور غير موجود'));
            return redirect()->back();
        }
        // dd($categories->toArray());
        $index_url = route('admin.posts.index');
        $update_url = route('admin.posts.update', $id);

        $html_breadcrumbs = [
            'title' => __('views.posts'),
            'subtitle' => __('views.Edit'),
        ];


        return view(
            'admin.posts.edit',
            compact('html_breadcrumbs', 'index_url', 'update_url', 'post')
        );
    }

    public function show($id)
    {
        $post = Post::with('comments')->withCount('comments')->find($id);


        if (!$post) {
            \Session::flash('error', trans('المنشور غير موجود'));
            return redirect()->back();
        }
        // dd($categories->toArray());
        $index_url = route('admin.posts.index');
        $update_url = route('admin.posts.update', $id);

        $html_breadcrumbs = [
            'title' => __('views.posts'),
            'subtitle' => __('views.Edit'),
        ];


        return view(
            'admin.posts.show',
            compact('html_breadcrumbs', 'index_url', 'update_url', 'post')
        );
    }

    public function DeleteComment(Request $request)
    {


        $rules = Validator::make($request->all(), [
            'comment_id' => 'required',
        ]);

        if ($rules->fails()) {
            return ['status' => 'error', 'msg' => $rules->errors()->first()];
            return JsonResponse::fail($rules->errors()->first(), 400);
        }
        $comment = Comment::find($request->get('comment_id'));
        if (!$comment) {
            return ['status' => 'error', 'msg' => __('views.not_found')];
        }

        $comment->delete();

        return ['status' => 'success', 'msg' => __('views.done')];

    } //<--- End Method


    public function changeStatus(Request $request)
    {


        //  return $request->event;
        if ($request->event == 'delete') {
            User::query()->whereIn('id', $request->IDArray)->delete();
        } else {


            if ($request->event == 'active') {
                $x = 1;
            }
            if ($request->event == 'not_active') {
                $x = 2;
            }
            if ($request->event == 'block') {
                $x = 3;
            }

            $user = User::query()->whereIn('id', $request->IDArray)
                ->update(['status' => $x]);


        }
        return "" . $request->event . "";
    }


    public function postComment(Request $request)
    {


        $rules = Validator::make($request->all(), [
            'post_id' => 'required',
        ]);

        if ($rules->fails()) {
            return ['status' => 'error', 'msg' => $rules->errors()->first()];
            return JsonResponse::fail($rules->errors()->first(), 400);
        }
        $post = Post::find($request->get('post_id'));
        if (!$post) {
            return ['status' => 'error', 'msg' => __('views.not_found')];
        }
        $comment = Comment::with('post', 'user', 'comments.user')
            ->where('post_id', $request->get('post_id'))
            ->orderBy('id')
            ->get();
        return ['comments' => $comment, 'post' => $post, 'status' => 'success'];

    } //<--- End Method
}
