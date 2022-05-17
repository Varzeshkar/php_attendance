<?php

session_start();
require_once __DIR__ . "/vendor/autoload.php";

//config

//define("BASE_PATH" , __DIR__ );
//define("CURRENT_DOMAIN" , currentDomain() ."/project");
//define("DISPLAY_ERROR" , true);
//define("DB_HOST" , "localhost");
//const DB_NAME = "project";
//define("DB_USERNAME" , "root");
//define("DB_PASSWORD" , "mysql");
const BASE_PATH = __DIR__;
define("CURRENT_DOMAIN" , currentDomain() ."/project");
const DISPLAY_ERROR = true;
const DB_HOST = "localhost";
const DB_NAME = "php_attendance";
const DB_USERNAME = "root";
const DB_PASSWORD = "mysql";

//mail

define('MAIL_HOST', 'smtp.gmail.com');
define('SMTP_AUTH', true);
define('MAIL_USERNAME', 'coding.php.js@gmail.com');
define('MAIL_PASSWORD', 'PHP_JS_1400');
define('MAIL_PORT', 587);
define('SENDER_MAIL', 'coding.php.js@gmail.com');
define('SENDER_NAME', 'دوره آنلاین php جامع');




require_once "database/DataBase.php";
//$db = new \database\Database();
require_once "database/CreateDB.php";
//$db = new \database\CreateDB();
//$db->run();

require_once "activities/Admin/Category.php";
require_once "activities/Admin/Post.php";
require_once "activities/Admin/Banner.php";
require_once "activities/Admin/Comment.php";
require_once "activities/Admin/User.php";
require_once "activities/Admin/Menu.php";
require_once "activities/Admin/Websetting.php";
require_once "activities/Auth/Auth.php";
require_once "activities/Admin/Dashboard.php";
//Auth
require_once "activities/Auth/Auth.php";
spl_autoload_register(function ($className){
    $path = BASE_PATH . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR;
    include  $path . $className . ".php";
});

// $auth = new \Auth\Auth();
// $auth->sendMail("dearhessan@gmail.com" , "cheashi is cooming" , "halle seyed");


function jalaliData($date){
    return \Parsidev\Jalali\jdate::forge($date)->format('datetime');
}
//echo jalaliData('today');
//exit();


function uri($reservedUrl, $class, $method, $requestMethod = "GET"){
    //current url Array

    $currentUrl = explode("?" , currentUrl())[0];
    $currentUrl = str_replace(CURRENT_DOMAIN , "" ,$currentUrl);
    $currentUrl = trim($currentUrl , "/");
    $currentUrlArray = explode("/" , $currentUrl);
    $currentUrlArray = array_filter($currentUrlArray);

    //reserved Url Array

    $reservedUrl = trim($reservedUrl , "/");
    $reservedUrlArray = explode("/" , $reservedUrl);
    $reservedUrlArray = array_filter($reservedUrlArray);

    if (sizeof($currentUrlArray) != sizeof($reservedUrlArray) || methodField() != $requestMethod){
        return false;
    }

    $parameters = [];
    for ($key = 0 ; $key < sizeof($reservedUrlArray); $key++){
        if ($reservedUrlArray[$key][0] == "{" and $reservedUrlArray[$key][strlen($reservedUrlArray[$key]) -1 ] == "}" )
        {
            array_push($parameters , $currentUrlArray[$key]);
        }
        elseif ($currentUrlArray[$key] != $reservedUrlArray[$key]){
            return false;

        }
    }

    if (methodField() == "POST"){
        $request = isset($_FILES) ? array_merge($_POST , $_FILES) : $_POST;
        $parameters = array_merge([$request] , $parameters);
    }


    $object = new $class;
    call_user_func_array([$object , $method] , $parameters);

    exit();
}


//helper

function dd($var){
    echo "<pre>";
    dump($var);
    exit();
}

function asset($src){
    $domain = trim(CURRENT_DOMAIN , "/ ");
    $src = $domain .'/' . trim($src , "/ ");
    return $src;
}

function protocol(){
    return stripos($_SERVER['SERVER_PROTOCOL'] , "https") === true ? "https://" : "http://" ;
}

function currentDomain(){
    return protocol() . $_SERVER['HTTP_HOST'];
}
//echo currentDomain();
//exit();

function url($url){
    $domain = trim(CURRENT_DOMAIN , "/ ");
    $url = $domain .'/' . trim($url , "/ ");
    return $url;
}
//echo url( "url/admin/create");

function currentUrl(){
    return currentDomain() . $_SERVER['REQUEST_URI'];
}


function methodField(){
    return $_SERVER['REQUEST_METHOD'];
}

function displayError($displayError){
    if ($displayError){
        ini_set('display_errors' , 1);
        ini_set('display_startup_errors' , 1);
        error_reporting(E_ALL);
    }
    else{
        ini_set('display_errors' , 0);
        ini_set('display_startup_errors' , 0);
        error_reporting(0);
    }
}
displayError(DISPLAY_ERROR);


global $flashMessage;
if(isset($_SESSION['flash_message'])){
        $flashMessage = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
}


function flash($name, $value = null)
{
    if($value === null){
        global $flashMessage;
        $message = isset($flashMessage[$name]) ? $flashMessage[$name] : '';
        return $message;
    }
    else{
        $_SESSION['flash_message'][$name] = $value;
    }

}



//Routes

//DashBoard
uri("admin" , "Admin\Dashboard" , "index");


//Category
uri("admin/category" , "Admin\Category" , "index");
uri("admin/category/create" , "Admin\Category" , "create");
uri("admin/category/store" , "Admin\Category" , "store" , "POST");
uri("admin/category/edit/{id}" , "Admin\Category" , "edit" );
uri("admin/category/update/{id}" , "Admin\Category" , "update" , "POST" );
uri("admin/category/delete/{id}" , "Admin\Category" , "delete"  );



//Post
uri("admin/post" , "Admin\Post" , "index");
uri("admin/post/create" , "Admin\Post" , "create");
uri("admin/post/store" , "Admin\Post" , "store" , "POST");
uri("admin/post/edit/{id}" , "Admin\Post" , "edit" );
uri("admin/post/update/{id}" , "Admin\Post" , "update" , "POST" );
uri("admin/post/delete/{id}" , "Admin\Post" , "delete");
uri("admin/post/selected/{id}" , "Admin\Post" , "selected");
uri("admin/post/breaking-news/{id}" , "Admin\Post" , "breakingNews");


//Banner
uri("admin/banner" , "Admin\Banner" , "index");
uri("admin/banner/create" , "Admin\Banner" , "create");
uri("admin/banner/store" , "Admin\Banner" , "store" , "POST");
uri("admin/banner/edit/{id}" , "Admin\Banner" , "edit" );
uri("admin/banner/update/{id}" , "Admin\Banner" , "update" , "POST" );
uri("admin/banner/delete/{id}" , "Admin\Banner" , "delete");
uri("admin/banner/selected/{id}" , "Admin\Banner" , "selected");
uri("admin/banner/breaking-news/{id}" , "Admin\Banner" , "breakingNews");

//User
uri("admin/user" , "Admin\User" , "index");
uri("admin/user/edit/{id}" , "Admin\User" , "edit" );
uri("admin/user/update/{id}" , "Admin\User" , "update" , "POST" );
uri("admin/user/delete/{id}" , "Admin\User" , "delete"  );
uri("admin/user/permission/{id}" , "Admin\User" , "permission");



//Comment
uri("admin/comment" , "Admin\Comment" , "index");
uri("admin/comment/edit/{id}" , "Admin\Comment" , "edit" );
uri("admin/comment/update/{id}" , "Admin\Comment" , "update" , "POST" );
uri("admin/comment/delete/{id}" , "Admin\Comment" , "delete"  );
uri("admin/comment/change-status/{id}" , "Admin\Comment" , "changeStatus");

//Menu
uri("admin/menu" , "Admin\Menu" , "index");
uri("admin/menu/create" , "Admin\Menu" , "create");
uri("admin/menu/store" , "Admin\Menu" , "store" , "POST");
uri("admin/menu/edit/{id}" , "Admin\Menu" , "edit" );
uri("admin/menu/update/{id}" , "Admin\Menu" , "update" , "POST" );
uri("admin/menu/delete/{id}" , "Admin\Menu" , "delete"  );

//webSetting
uri('admin/websetting', 'Admin\Websetting', 'index');
uri('admin/websetting/edit/{id}', 'Admin\Websetting', 'edit');
uri('admin/websetting/update/{id}', 'Admin\Websetting', 'update', 'POST');


//AuthRoutes
uri('register', 'Auth\Auth', 'register');
uri('register/store', 'Auth\Auth', 'registerStore', 'POST');
uri('activation/{verify_token}', 'Auth\Auth', 'activation');
uri('login', 'Auth\Auth', 'login');
uri('check-login', 'Auth\Auth', 'checkLogin', 'POST');
uri('logout', 'Auth\Auth', 'logout');
uri('forgot', 'Auth\Auth', 'forgot');
uri('forgot/request', 'Auth\Auth', 'forgotRequest', 'POST');
uri('reset-password-form/{forgot_token}', 'Auth\Auth', 'resetPasswordView');
uri('reset-password/{forgot_token}', 'Auth\Auth', 'resetPassword', "POST");




dump(
    ["message" =>"dump"]
);




echo "<br>";
echo "404 ! page not found";