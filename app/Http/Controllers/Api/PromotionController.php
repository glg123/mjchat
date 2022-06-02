<?php

namespace App\Http\Controllers\Api;

use App\Helpers\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\AllStoriesResource;
use App\Http\Resources\MapResource;
use App\Http\Resources\SingleStoryResource;

use App\Models\Post;
use App\Models\PostReport;
use App\Models\PromotionPackage;
use App\Models\SaveStory;
use App\Models\Setting;
use App\Models\ShareStory;
use App\Models\Storyblock;
use App\Models\storyView;
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


class PromotionController extends Controller
{


    public function PromotionPackages(Request $request)

    {


        $user = $request->user();
        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }

        $PromotionPackages = PromotionPackage::where('status', 1)->get();

        return JsonResponse::success($PromotionPackages, __('views.Done'));

    }

    public function PromotionPackageShow(Request $request,$id)

    {


        $user = $request->user();
        if (!$request->user()) {
            //return JsonResponse::fail('Credentials not match', 401);

            return JsonResponse::fail(__('views.not authorized'));

        }

        $PromotionPackage = PromotionPackage::find($id);
        if(!$PromotionPackage)
        {
            return JsonResponse::fail(__('views.not found'));
        }

        return JsonResponse::success($PromotionPackage, __('views.Done'));

    }
}
