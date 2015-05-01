<?php

$TASK_ID=$_SERVER['argv'][1];
debug("started: " . $TASK_ID);
connect();
$task=get_task($TASK_ID);

$token = $task["token"];

if($task['byUsername']==1){

    $users=getUserFollowers($task);
    foreach($users as $user){
        follow($task, $user);

        sleep(rand(30,50));

        if(is_stopped($TASK_ID))
            break;
    }
}
else
    for ($i = 0; $i <= $task['count'] - 1 ; $i++) {

        $tags = explode('#', $task['tags']);

        $rand_key = array_rand($tags);

        $tag = $tags[$rand_key];

        $media=getUsernameAndIdsbyTag($tag, $token);

        if($task['type']==0)
            follow($task, $media);
        else
            sendLike($task, $media);

        sleep(rand(30,50));

        if(is_stopped($TASK_ID))
             break;
    }
if(!is_stopped($TASK_ID))
	done_task($TASK_ID);

debug("closed: " . $TASK_ID);



function getUserFollowers($task)
{
    $user_name = $task['tags'];
    $token = $task['token'];
    $url = "https://api.instagram.com/v1/users/search?q=$user_name" . "&access_token=$token";
    $response =json_decode( httpGet($url));

  //  $response = json_decode(file_get_contents($url));
    $user_id=$response->data[0]->id;

    if(!isset($user_id))
        return 1;

    $next="";
    $result=array();
    do {
        $url = "https://api.instagram.com/v1/users/$user_id/followed-by?" . "access_token=$token" . "&cursor=$next";
        $response =json_decode( httpGet($url));

//        $response = json_decode(file_get_contents($url));

        $data = $response->data;
        $next = $response->pagination->next_cursor;


        foreach ($data as $d) {
            if( $task['count']-1 <count( $result))
                break;

            if (checkUser($d->id, $token)) {
                $user['username'] = $d->username;
                $user['user_id'] = $d->id;
                $user['link'] = '';
                $result[] = $user;
            }
        }

    }while($task['count']-1 > count($result) && isset($next));

    return $result;
}


function debug($message){
    $file = __DIR__ . "/data";
    file_put_contents("$file","|". json_encode($message). "|",FILE_APPEND);
}

function is_stopped($id){

    $qr_result = mysql_query("SELECT id FROM tasks WHERE status=3 AND id=$id")
		or die(mysql_error());
   $row = mysql_fetch_array($qr_result);

  if(!empty($row['id'])){
    	return true;
  }
  	return false;
}


function done_task($id){
    $qr_result = mysql_query("UPDATE tasks SET status=1 WHERE id=$id")
		or die(mysql_error());
}


function add_row($task_id,$user_id,$username,$resource_id,$responce){
        $qr_result = mysql_query("INSERT INTO actions (task_id,target_user_id,username,resource_id,responce) VALUES ($task_id,'$user_id','$username','$resource_id','$responce')")
		or die(mysql_error());
}


function  getUsernameAndIdsbyTag($tag,$token){

    $url = "https://api.instagram.com/v1/tags/$tag/media/recent?" . "access_token=$token" . "&count=5";
  	//$response =json_decode( file_get_contents($url));
    echo 'result';
    var_dump(json_decode( httpGet($url)));
    die();
    $response =json_decode( httpGet($url));

    $data = $response->data;
    foreach($data as $d){
        if(checkUser($d->user->id,$token)){
            $result['id'] = $d->id;
            $result['username'] = $d->user->username;
            $result['user_id'] = $d->user->id;
            $result['link'] = $d->link;
            return $result;
        }
    }
}

function checkUser($user_id,$token){
    $url = "https://api.instagram.com/v1/users/$user_id/relationship?" . "access_token=$token";
    $response =json_decode( httpGet($url));

  //  $response =json_decode( file_get_contents($url));
    if ($response->data->outgoing_status == 'none' && $response->data->target_user_is_private==false)
        return true;
    return false;
}

function get_task($task_id){
    $qr_result = mysql_query("SELECT t.*,a.token FROM tasks t INNER JOIN accounts a ON t.account_id=a.id WHERE t.id=$task_id AND status=0")
		or die(mysql_error());
  $row = mysql_fetch_array($qr_result);
 
  $result=array(
    'id' => $task_id,
    'count' => $row['count'],
    'tags' => $row['tags'],
    'type' => $row['type'],
    'token' => $row['token'],
    'byUsername' => $row['byUsername'] );

   mysql_query("UPDATE tasks SET status=2 WHERE id=$task_id")
  	or die(mysql_error());  
    return $result;
}

function  sendLike($task,$media){

    $media_id=$media['id'];
    $token=$task["token"];
  	$url="https://api.instagram.com/v1/media/$media_id/likes";
  
  $params = array(
   "access_token" =>  $token
   );
  
  $result= json_decode(httpPost($url, $params));

   add_row($task['id'],$media['user_id'],$media['username'],$media['link'],$result->meta->code);
}

function  follow($task,$media){

  	$target_id=$media['user_id'];
    $token=$task["token"];
  	$url="https://api.instagram.com/v1/users/$target_id/relationship";
    
  
  $params = array(
   "access_token" =>  $token,
   "action" =>  'follow'
   );
  $result= json_decode(httpPost($url, $params));

   add_row($task['id'],$media['user_id'],$media['username'],$media['link'],$result->meta->code);
}


function httpPost($url,$params)
{
  $postData = '';
   foreach($params as $k => $v)
   { 
      $postData .= $k . '='.$v.'&'; 
   }
   rtrim($postData, '&');
 
    $ch = curl_init();
    $proxy='212.164.20.7:1085:';

    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_POST, count($postData));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
 
    $output=curl_exec($ch);
 
    curl_close($ch);
    var_dump($output);
    return $output;
 }

function httpGet($url)
{
     $ch = curl_init();

    $proxy='212.164.20.7:1085:';
    curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);

    $output=curl_exec($ch);
    curl_close($ch);
    var_dump($output);
    return $output;
 }



function connect(){
    $connection = mysql_connect('localhost', 'root', '');//bycnfcntkkfh,fpf
    if (!$connection){
        die("Database Connection Failed" . mysql_error());
    }
    $select_db = mysql_select_db('symfony');
    if (!$select_db){
        die("Database Selection Failed" . mysql_error());
    }
    mysql_query("SET NAMES 'utf8'");
    mysql_query("SET CHARACTER SET utf8 ");
}