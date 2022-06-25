<?php

namespace App\Http\Resources;

use App\Http\Resources\userAddsResource;
use App\Models\add;
use App\Models\Post;
use Illuminate\Http\Resources\Json\JsonResource;

class MapResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        $q=Post::distance($request->get('lan'),$request->get('lang'));
        $stories=$q->orderBy('distance', 'ASC')
            ->where('type','=',1)
            ->get();
        $storyResource=AllStoriesResource::collection($stories);
        $q2=Post::distance($request->get('lan'),$request->get('lang'));
        $stories2=$q2->orderBy('distance', 'ASC')
            ->where('recommended','=',1)->get();
        $storyResource2=AllStoriesResource::collection($stories2);
        $adds=add::distance($request->get('lan'),$request->get('lang'));
        $addsx=$adds->orderBy('distance', 'ASC')->where('type',1)->inRandomOrder()->first();
        if($addsx==null){
            $banar=(object)[];
        }else{
            $banar=new userAddsResource($addsx);
        }
        return [
                        'recommended'       =>    $storyResource2,
                        'stories'           =>    $storyResource,
                        'ads'               =>    $banar,
        ];
    }
}
