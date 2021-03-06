<?php
date_default_timezone_set('UTC');

function follow_by_username(){
    global $inst;
    global $task;
    var_dump('start searching');
    $users = $inst->get_followers($task['tags'], $task['count'] + 20 );
    if (in_array($inst->get_task_status(),[3,4])){
        exit;
    }
    $inst->set_task_status(2);
    $inst->change_token();

    $errors = 0;
    $success = 0;
    foreach ($users as $user)
    {
        var_dump($user);
        $result = $inst->follow($user['user_id']);
        if(isset($result) && $result->meta->code == 200){
            $errors = 0;
            $success++;
            $inst->add_row($user['username']);
        }
        else
            $errors++;

        sleep(sleepTime($task['speed']));

        if($task['optionAddLike'] == 1) {
            $media = $inst->get_last_user_media($user['user_id']);
            if (isset($media)) {
                $result = $inst->like($media->data[0]->id);
                if (isset($result) && $result->meta->code == 200)
                    $inst->add_row($media->data[0]->link);
                sleep(sleepTime(3));
            }
        }

        if (in_array($inst->get_task_status(),[3,4])){
            break;
        }
        if($errors > 8){
            $inst->set_task_status(4);
            break;
        }
        if($success == $task['count'] ){
            break;
        }
    }
    if ($inst->get_task_status() == 2)
        $inst->set_task_status(1);
}

function follow_by_tags(){

    global $inst;
    global $task;

    if(count($inst->OPTIONS['optionGeo']) > 0)
        $users = $inst->get_media_by_geo($inst->OPTIONS['optionGeo'],$task['count']);
    else
        $users = $inst->get_media_by_tags($task['tags'],$task['count'] + 20);
   // $users = $inst->get_followers_by_tags($task['tags'],$task['count']);
    if (in_array($inst->get_task_status(),[3,4]))
        exit;
    $inst->set_task_status(2);
    $inst->change_token();


    $errors = 0;
    $success = 0;
    foreach ($users as $user)
    {
        var_dump($user);
        $result = $inst->follow($user['user_id']);
        if(isset($result) && $result->meta->code == 200){
            $errors = 0;
            $success++;
            $inst->add_row($user['username']);
        }
        else
            $errors++;

        sleep(sleepTime($task['speed']));

        if($task['optionAddLike']) {
            $media = $inst->get_last_user_media($user['user_id']);
            if (isset($media)) {
                $result = $inst->like($media->data[0]->id);
                if (isset($result) && $result->meta->code == 200)
                    $inst->add_row($media->data[0]->link);
                sleep(sleepTime(3));
            }
        }

        if (in_array($inst->get_task_status(),[3,4])){
            break;
        }
        if($errors > 8){
            $inst->set_task_status(4);
            break;
        }
        if($success == $task['count'] ){
            break;
        }
    }
    if ($inst->get_task_status() == 2)
        $inst->set_task_status(1);
}


function follow_by_list(){

    global $inst;
    global $task;

    $users = $inst->get_followers_by_list();
    if (in_array($inst->get_task_status(),[3,4]))
        exit;
    $inst->set_task_status(2);
    $inst->change_token();


    $errors = 0;
    foreach ($users as $user)
    {
        var_dump($user);
        $result = $inst->follow($user['user_id']);
        if(isset($result) && $result->meta->code == 200){
            $errors = 0;
            $inst->add_row($user['username']);
        }
        else
            $errors++;

        sleep(sleepTime($task['speed']));

        if($task['optionAddLike']) {
            $media = $inst->get_last_user_media($user['user_id']);
            if (isset($media)) {
                $result = $inst->like($media->data[0]->id);
                if (isset($result) && $result->meta->code == 200)
                    $inst->add_row($media->data[0]->link);
                sleep(sleepTime(3));
            }
        }

        if (in_array($inst->get_task_status(),[3,4])){
            break;
        }
        if($errors > 8){
            $inst->set_task_status(4);
            break;
        }
    }
    if ($inst->get_task_status() == 2)
        $inst->set_task_status(1);
}

function follow_by_geo(){

    global $inst;
    global $task;

    $users = $inst->get_media_by_geo($task['tags'],$task['count']);
    if (in_array($inst->get_task_status(),[3,4]))
        exit;
    $inst->set_task_status(2);
    $inst->change_token();


    $errors = 0;
    foreach ($users as $user)
    {
        var_dump($user);
        $result = $inst->follow($user['user_id']);
        if(isset($result) && $result->meta->code == 200){
            $errors = 0;
            $inst->add_row($user['username']);
        }
        else
            $errors++;

        sleep(sleepTime($task['speed']));

        if (in_array($inst->get_task_status(),[3,4])){
            break;
        }
        if($errors > 8){
            $inst->set_task_status(4);
            break;
        }
    }
    if ($inst->get_task_status() == 2)
        $inst->set_task_status(1);
}

function liking_by_tags(){

    global $inst;
    global $task;

    if(count($inst->OPTIONS['optionGeo']) > 0)
        $users = $inst->get_media_by_geo($inst->OPTIONS['optionGeo'],$task['count']);
    else
        $users = $inst->get_media_by_tags($task['tags'],$task['count']);

    if (in_array($inst->get_task_status(),[3,4]))
        exit;
    $inst->set_task_status(2);
    $inst->change_token();


    $errors = 0;
    $success = 0;
    foreach ($users as $user)
    {
        var_dump($user);
        $result = $inst->like($user['resource_id']);
        if(isset($result) && $result->meta->code == 200){
            $errors = 0;
            $inst->add_row($user['link']);
            if(++$success == $task['count'] ){
                break;
            }
        }else
            $errors++;

        sleep(sleepTime($task['speed']));

        if (in_array($inst->get_task_status(),[3,4])){
            break;
        }
        if($errors > 8){
            $inst->set_task_status(4);
            break;
        }
    }
    if ($inst->get_task_status() == 2)
        $inst->set_task_status(1);
}


function liking_by_geo(){

    global $inst;
    global $task;

    $users = $inst->get_media_by_geo($task['tags'],$task['count']);
    if (in_array($inst->get_task_status(),[3,4]))
        exit;
    $inst->set_task_status(2);
    $inst->change_token();


    $errors = 0;
    foreach ($users as $user)
    {
        var_dump($user);
        $result = $inst->like($user['resource_id']);
        if(isset($result) && $result->meta->code == 200){
            $errors = 0;
            $inst->add_row($user['link']);
        }
        else
            $errors++;

        sleep(sleepTime($task['speed']));

        if (in_array($inst->get_task_status(),[3,4])){
            break;
        }
        if($errors > 8){
            $inst->set_task_status(4);
            break;
        }
    }
    if ($inst->get_task_status() == 2)
        $inst->set_task_status(1);
}

function unfollowing(){
    global $inst;
    global $task;
    var_dump('start unfollowing');
    $users = $inst->get_followers_revers($task['count'] + 20 );
    if (in_array($inst->get_task_status(),[3,4]))
        exit;
    $inst->set_task_status(2);
    $inst->change_token();


    $errors = 0;
    $success = 0;
    foreach ($users as $user)
    {
        var_dump($user);
        $result = $inst->unfollow($user['user_id']);
        if(isset($result) && $result->meta->code == 200){
            $errors = 0;
            $success++;
            $inst->add_row($user['username']);
        }
        else
            $errors++;

        sleep(sleepTime($task['speed']));

        if (in_array($inst->get_task_status(),[3,4])){
            break;
        }
        if($errors > 8){
            $inst->set_task_status(4);
            break;
        }
        if($success == $task['count'] ){
            break;
        }
    }
    if ($inst->get_task_status() == 2)
        $inst->set_task_status(1);
}


include_once("instagram.php");

$TASK_ID = $_SERVER['argv'][1];

$inst = new Instagram($TASK_ID);
$task = $inst->TASK_INFO;

try{
//0 - following by username
//10 - following by tags
//1 - liking by username
//11 - liking by tags
//3 - un following

    switch ($task['type']) {
        case 0:
            follow_by_username();
            break;
        case 10:
            follow_by_tags();
            break;
        case 20:
            follow_by_list();
            break;
        case 30:
            follow_by_geo();
            break;
        case 1:
            liking_by_username();
            break;
        case 11:
            liking_by_tags();
            break;
        case 31:
            liking_by_geo();
            break;
        case 3:
            unfollowing();
            break;
    };
    $inst->close_task();
}
catch (Exception $e){

    $m=substr($e->getMessage(),0,200);
    $task=$_SERVER['argv'][1];

    mysql_query("INSERT INTO errors (task_id,message) VALUES ($task,'$m')")
    or die(mysql_error());
    $qr_result = mysql_query("UPDATE tasks SET status=4 WHERE id=$task")
    or die(mysql_error());
}


function liking_by_username(){}


function sleepTime($interval_id){
    $time = 60;
    switch ($interval_id) {
        case 0:
        case 3:
            return rand(0.5 * $time, $time);
        case 1:
        case 4:
            return rand($time, 1.5 * $time);
        case 2:
            return rand(1.5 * $time, 2 * $time);
        default:
            return rand($time, 2 * $time);
    }
}

