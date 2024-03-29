<?php
use GeoIp2\Database\Reader;
use App\Models\Countries;
use App\Models\Cities;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


function geoip($ip_address = ''){

    // This creates the Reader object, which should be reused across
    // lookups.
    $reader = new Reader(base_path('database/geoip_db/GeoLite2-City.mmdb'));

    // Replace "city" with the appropriate method for your database, e.g.,
    // "country".
    try{
    $record = $reader->city($ip_address);

    $country_isoCode = $record->country->isoCode; // 'US'
    $country_name = $record->country->name; // 'United States'
    $state_geonameId = $record->mostSpecificSubdivision->geonameId; // 'Minnesota'
    $state_name = $record->mostSpecificSubdivision->name; // 'Minnesota'
    $state_isoCode = $record->mostSpecificSubdivision->isoCode; // 'MN'
    $city_geonameId = $record->city->geonameId; // '576'
    $city_name = $record->city->name; // 'Minneapolis'
    $city_code = $record->postal->code; // '55455'

    $city = Cities::where('geoname_id',$city_geonameId)->first();
    if(!$city){
    $country = Countries::where('iso_code_2',$country_isoCode)->first();
    $city = Cities::forceCreate([
     'country_id' => $country->id,
     'name_ar'    => $city_name,
     'name_en'    => $city_name,
     'code'       => $city_code,
     'geoname_id' => $city_geonameId,
     'status'     => 1,
    ]);
    }
    $country_id = $city->country_id;
    $city_id    = $city->id;

    // print($record->location->latitude . "<p>"); // 44.9733
    // print($record->location->longitude . "<p>"); // -93.2323
    // print($record->traits->network . "<p>"); // '128.101.101.101/32'

   return compact('country_id','country_isoCode','country_name','state_geonameId','state_name','state_isoCode','city_geonameId','city_id','city_name','city_code');
   }
   catch(\Exception $e){

    return [];

   }


 }


 function sendNotfication($userID,$orderID,$msg){

    $usertoken=DB::table('fcm_tokens')->where('user_id','=',$userID)->value('device_token');
    pushNotification([
        'order_id' => $orderID,
        'title'=> '' ,
        'body'=>$msg,
        'click_action'=> "ACTION_NORMAL" ,
        'device_token' => [$usertoken],
        'id'=> null
    ]);
}

 function pushNotification($notification)
{

    $auth_key = "AAAAPXVOc4I:APA91bEXLUv4NC7ZnePZn2gF5zEyOls2ZDZHjqlZZFSVFkDvfl7GzIQzNwJt7xSgYbHr_BzBvwUExmjyWmI-qyONCs_3Y6owTnxckPRv4i35AvPSjdPNRPImwkCgBVDdLIXytLuOBe9-";
    $device_token = $notification['device_token'];


    $data = [
        'title' => $notification['title'] ,
        'body' => $notification['body'],
        'order_id' =>$notification['order_id'],

        //  'icon' => 'https://drive.google.com/file/d/1yLZw2bYmDoH8SsAOp0KJFhh0l9EO9JwZ/view',
        'banner' => '0',
        'sound' => 'default',
        "priority" => "high",
    ];
    $notification = [
        'title' => $notification['title'] ,
        'body' => $notification['body'],
        'sound' => 'default',
        //'icon' => 'https://drive.google.com/file/d/1yLZw2bYmDoH8SsAOp0KJFhh0l9EO9JwZ/view',
        'banner' => '0',
        "priority" => "high",
        'data' => $data
    ];
    $fields = json_encode([
        'registration_ids' => $device_token,
        'notification' => $notification,
        'data' => $data,
    ]);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: key=' . $auth_key, 'Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }


    curl_close($ch);

}

function get_datatable_params($data)
{
    $params = [];
    foreach ($data as $key => $value) {
        switch ($key) {
            case "pagination":
                $params["pagination"] = [
                    "page" => $value["page"],
                    "perpage" => $value["perpage"]
                ];
                break;
            case "query":
                $params["search"] = isset($value['generalSearch']) ? $value['generalSearch'] : '';
                $params['date_range'] = isset($value['date_range']) ? $value['date_range'] : '';
                break;
            case "sort":
                $params["sort"] = [
                    "column" => $value["field"],
                    "dir" => $value["sort"]
                ];
                break;
        }
    }
    return $params;
}

function process_datatable_query($query, $search_callback = null, $post_query = null)
{



    $meta = [
        'page' => 1,
        'pages' => 0,
        'perpage' => 12,
        'total' => 0,
        'sort' => '',
        'field' => ''
    ];
    $datatable_params = get_datatable_params(request()->all());

    if ($datatable_params['search'] && ($search_callback !== null)) {
        $value = $datatable_params['search'];
        $query = $search_callback($query, $value);
    }

    $meta['total'] = $query->count();
    $meta['pages'] = (int)ceil($meta['total']/$meta['perpage']);

    if ($datatable_params['pagination']) {
        $value = $datatable_params['pagination'];
        $meta['page'] = (int)$value["page"];
        $meta['perpage'] = (int)$value["perpage"];
        $offset = ($value["page"] - 1) * $value["perpage"];
        $query->offset($offset)->limit($value["perpage"]);
    }
    if (isset($datatable_params['sort']) && $datatable_params['sort']) {
        $value = $datatable_params['sort'];
        $query->orderBy($value['column'], $value['dir']);
        $meta['sort'] = $value['dir'];
        $meta['field'] = $value['column'];
    }

    if ($post_query) {
        $data = $query->get()->pipe($post_query)->toArray();
        // $data = $query->get()->toArray();
    } else {
        $data = $query->get()->toArray();
    }
    return [
      'meta' => $meta,
      'data' => $data,
    ];
}

function get_admin_menu_list()
{

    return $menu_list = [
        [
            'url' => route('admin.dashboard.index'),
            'icon' => 'flaticon2-analytics',
            'text' => __('views.Home'),
            'is_active' => str_contains(
                request()
                    ->route()
                    ->getName(),
                'admin.dashboard.index'
            ),
            'children' => [],
        ],
        [

            'url' => route('admin.settings.edit'),
            'icon' => 'flaticon2-analytics',
            'text' => __('views.Settings'),
            'is_active' => str_contains(
                request()->route()->getName(),
                'admin.settings.edit'
            ),
            'children' => [],
        ],
        [

            'url' => route('admin.users.index'),
            'icon' => 'flaticon2-user',
            'text' => __('views.Users'),
            'is_active' => str_contains(
                request()->route()->getName(),
                'admin.users.index'
            ),
            'children' => [],
        ],
        [

            'url' => route('admin.adds.index'),
            'icon' => 'flaticon2-image-file',
            'text' => __('views.adds'),
            'is_active' => str_contains(
                request()->route()->getName(),
                'admin.adds.index'
            ),
            'children' => [],
        ],
        [

            'url' => route('admin.posts.index'),
            'icon' => 'flaticon2-location',
            'text' => __('views.posts'),
            'is_active' => str_contains(
                request()->route()->getName(),
                'admin.posts.index'
            ),
            'children' => [],
        ],
        [

            'url' => route('admin.orders.index'),
            'icon' => 'flaticon2-percentage',
            'text' => __('views.orders'),
            'is_active' => str_contains(
                request()->route()->getName(),
                'admin.orders.index'
            ),
            'children' => [],
        ],
        [

            'url' => route('admin.promotionPackges.index'),
            'icon' => 'flaticon2-delivery-package',
            'text' => __('views.promotionPackges'),
            'is_active' => str_contains(
                request()->route()->getName(),
                'admin.promotionPackges.index'
            ),
            'children' => [],
        ],
    ];
}


function format_duration($seconds, $format)
{
    $seconds = intval($seconds);
    $start = new DateTime('@0'); // Unix epoch
    $start->add(new DateInterval("PT{$seconds}S"));
    return $start->format($format);
}

function starts_with($haystack, $needles)
{
    return Str::startsWith($haystack, $needles);
}

function get_soundex($word){
    $count=0;
    $orginal_word=trim($word);
    $word_length=strlen($word);
    $soundex_name="";
    $soundex_char='';
    if($word_length>1){
        while($count<=$word_length+1){

            $checked_char = mb_substr($orginal_word,$count,1);
            if($count==0 && mb_substr($orginal_word,0,2)=="ال"){
                $count=1;
                $soundex_char ='';
            }
            elseif($count==$word_length && $checked_char=="ت"){
                $soundex_char ='ه';
            }
            elseif($checked_char=='ء'){
                $soundex_char ='';
            }
            elseif($checked_char=='آ'){
                $soundex_char ='ا';
            }
            elseif($checked_char=='أ'){
                $soundex_char ='ا';
            }
            elseif($checked_char=='ؤ'){
                $soundex_char ='و';
            }
            elseif($checked_char=='إ'){
                $soundex_char ='ا';
            }
            elseif($checked_char=='ئ'){
                $soundex_char ='ي';
            }
            elseif($checked_char=='ة'){
                $soundex_char ='ه';
            }
            elseif($checked_char=='ى'){
                $soundex_char ='ا';
            }
            elseif($checked_char==' '){
                $soundex_char ='-';
            }
            else{
                $soundex_char =$checked_char;
            }
            $count++;
            if($count>$word_length+1){
                break;
            }

            $soundex_name=$soundex_name.$soundex_char;

        }
    }
  return  $soundex_name;
}




function add_to_redirect_url_list($from_url, $to_url, $type = 'redirect')
{
    // type: "in:redirect|permanent"

    $domain_name = url('/');
    $from_url = str_replace($domain_name, '', $from_url);
    $to_url = str_replace($domain_name, '', $to_url);

    // this follow the current active mysql connection
    \DB::table('url_rewrites')->where('from_url', $from_url)->delete();

    /* // TODO prevent redirect loop bug */

    \DB::table('url_rewrites')->insert([
        'from_url' => $from_url,
        'to_url' => $to_url,
        'type' => $type,
        'created_at' => date("Y-m-d H:i:s"),
        'updated_at' => date("Y-m-d H:i:s"),
    ]);
    return true;
}

function dump_rewrite_rules_to_file()
{
    // get data from all mysql connections
    $images_url = \DB::connection('mysql')->table('url_rewrites')
        ->select(['from_url', 'to_url', 'type'])
        ->get()
        ->map(function ($row) {
            return "rewrite \"^{$row->from_url}$\" {$row->to_url} redirect;";
        });

    $videos_url = \DB::connection('mysqlVideo')->table('url_rewrites')
        ->select(['from_url', 'to_url', 'type'])
        ->get()
        ->map(function ($row) {
            return "rewrite \"^{$row->from_url}$\" {$row->to_url} redirect;";
        });

    $content = $images_url
        ->merge($videos_url)
        ->pipe(function ($collection) {
            return implode("\n", $collection->toArray());
        });

    file_put_contents(public_path('nginx.conf'), $content);
    devops_reload_nginx();
    return true;
}


function devops_reload_nginx()
{
    $devops_commands = base_path('storage/devops_commands');
    if (!file_exists($devops_commands)) {
        mkdir($devops_commands, 0755, true);
    }

    file_put_contents($devops_commands . "/nginx_reload", "1");
    return true;
}




function startsWith ($string, $startString)
{
    $len = strlen($startString);
    return (substr($string, 0, $len) === $startString);
}
function state_estate($id)
{
    $array=['1'=>'جديد','2'=>'مستعمل'];

    return $array[$id];
}

function dirctions($id)
{
    $array=['شمال','جنوب','شرق','غرب'];

    return $array[$id-1];
}

function checkIfMobileStartCode($mobile,$country_code = null){

    $western_arabic = array('0','1','2','3','4','5','6','7','8','9');
    $eastern_arabic = array('٠','١','٢','٣','٤','٥','٦','٧','٨','٩');
    $mobile = str_replace($eastern_arabic, $western_arabic, $mobile);
    $mobile = str_replace(['+', '-'], '', filter_var($mobile, FILTER_SANITIZE_NUMBER_INT));

    if(strlen($mobile) > 10){
        return intval($mobile);
    }

    if(!$country_code)
        $country_code = '966';

    $start_with_code = substr($mobile, 0, strlen($country_code)) === $country_code;
    if($start_with_code)
        return $mobile;

    if (strpos($mobile, "00") === 0 || strpos($mobile, "+") === 0){
        return intval($mobile);
    }

    $mobile = intval($country_code).intval($mobile);

    return $mobile;

}
function str_random($length = 16)
{
    return Str::random($length);
}

function nearest($lat, $lng, $radius = 30)
{
    // Km
    if (empty($radius)) $radius = 30;
    $angle_radius = $radius / 111;
    $location['min_lat'] = $lat - $angle_radius;
    $location['max_lat'] = $lat + $angle_radius;
    $location['min_lng'] = $lng - $angle_radius;
    $location['max_lng'] = $lng + $angle_radius;

    return (object)$location;

}
