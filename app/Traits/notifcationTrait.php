<?php
namespace App\Traits;
trait notifcationTrait
{
  public function broadCastNotification($title, $body , $topic )
  {
    $auth_key = "AAAAPXVOc4I:APA91bEXLUv4NC7ZnePZn2gF5zEyOls2ZDZHjqlZZFSVFkDvfl7GzIQzNwJt7xSgYbHr_BzBvwUExmjyWmI-qyONCs_3Y6owTnxckPRv4i35AvPSjdPNRPImwkCgBVDdLIXytLuOBe9-";
    $topic = "/topics/$topic";
    $data = [
      'title' => $title,
      'body' => $body,
    //  'icon' => 'https://drive.google.com/file/d/1yLZw2bYmDoH8SsAOp0KJFhh0l9EO9JwZ/view',
      'banner' => '0',
      'sound' => 'default',
      "priority" => "high",
    ];
    $notification = [
      'title' => $title,
      'body' => $body,
      'sound' => 'default',
      //'icon' => 'https://drive.google.com/file/d/1yLZw2bYmDoH8SsAOp0KJFhh0l9EO9JwZ/view',
      'banner' => '0',
      "priority" => "high",
      'data' => $data
    ];
    $fields = json_encode([
      'to' => $topic,
      'notification' => $notification,
      'data' => $data
    ]);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: key=' . $auth_key, 'Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    $result = curl_exec($ch);
    curl_close($ch);
  }
  public function pushNotification($notification)
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
}
