<?php
class db
{
    public $host = 'localhost';
    public $username = 'root';
    public $password = 'root';
    public $dbname = 'voting';
}

// 连接数据库

$db;
$db = new db();
$dsn = "mysql:host=".$db->host.";dbname=".$db->dbname.";charset=utf8";
$db = new PDO($dsn, $db->username, $db->password);
date_default_timezone_set('Asia/Shanghai');

// 其他配置
session_start();

$config = [
    // "domain"=>"https://mundb.xyz/voting"
    "domain" => "https://sast.njupt.edu.cn/atsast/voting"
];

// 函数

/**
 * 获取ip
 */

function getIP() {
    if (@$_SERVER["HTTP_X_FORWARDED_FOR"]) {
        $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
    } elseif (@$_SERVER["HTTP_CLIENT_IP"]) {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    } elseif (@$_SERVER["REMOTE_ADDR"]) {
        $ip = $_SERVER["REMOTE_ADDR"];
    } elseif (@getenv("HTTP_X_FORWARDED_FOR")) {
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    } elseif (@getenv("HTTP_CLIENT_IP")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } elseif (@getenv("REMOTE_ADDR")) {
        $ip = getenv("REMOTE_ADDR");
    } else {
        $ip = "Unknown";
    }
    return $ip;
}

function redirect($to) {
    header("Location: ".$to);
    exit;
}

function https_request($url, $data = null) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return $output;
}

// 微信相关

$wechat = [
    'OPEN_APPID' => '', //应用　AppID
    'OPEN_APPSECRET' => '',//应用 AppSecret
    'OPEN_CALLBACKURL' => "{$config['domain']}/wxBack.php" //微信用户使用微信扫描二维码并且确认登录后，PC端跳转路径
];

/**
 * 判断是否是微信
 */

function isWechat() {
    global $wechat;
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
        return true;
    } else {
        return false;
    }
}

/**
 * 获取用户的 AccessToken
 */

function getAccessToken() {
    global $wechat;
    return "//TODO";
}

/**
 * 根据用户已获得的 openID 获取其他信息 （主要为 unionID）
 * @param $openID
 */

function getUnionidByOpenid($openID) {
    global $wechat;
    $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".getAccessToken()."&openid=".$openID."&lang=zh_CN";
    $jsonRes = http_get($url);
    if ($jsonRes) {
        $arrRes = json_decode($jsonRes, true);
        return $arrRes['unionid'];
    } else {
        return '';
    }
}

/**
 * 微信登录
 */

function wxIndex() {
    global $wechat;
    $state = md5(uniqid(rand(), TRUE));
    $_SESSION["wx_state"] = $state;
    $callback = urlencode($wechat["OPEN_CALLBACKURL"]);
    $wxurl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$wechat["OPEN_APPID"]."&redirect_uri=".$callback."&response_type=code&scope=snsapi_base&state=".$state."#wechat_redirect";
    header("Location:$wxurl");
}

/**
 * 微信回调
 */

function wxBack() {
    global $wechat;
    if ($_GET['state'] != $_SESSION["wx_state"]) {
        return false;
    }
    $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$wechat["OPEN_APPID"].'&secret='.$wechat["OPEN_APPSECRET"].'&code='.$_GET['code'].'&grant_type=authorization_code';
    $arr = https_request($url);
    $ret = json_decode($arr,true);
    return $ret["openid"];
    // var_dump($arr);
    // $url='https://api.weixin.qq.com/sns/userinfo?access_token='.$arr['access_token'].'&openid='.$arr['openid'].'&lang=zh_CN';
    // $user_info = file_get_contents($url);
    // dealWithWxLogin($user_info);
    // return true;
}

/**
 * 根据微信授权用户的信息 进行下一步的梳理
 * @param $user_info
 */

function dealWithWxLogin($user_info) {
    global $wechat;
    // $_SESSION["uid"] = 1;
    var_dump($user_info);
}