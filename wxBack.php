<?php
    require_once("conn.php");
    if(!isWechat()) {
        redirect("unsupported.php");
    }
    
    $openid=wxBack();

    $rs=$db->prepare("SELECT * from user where OPEN_ID=?");
    $rs->execute([$openid]);
    $ret=$rs->fetchAll();
    if(empty($ret)){
        $rs=$db->prepare("insert into user set OPEN_ID=?");
        $rs->execute([$openid]);
        // $rs=$db->prepare("SELECT * from user where OPEN_ID=?");
        // $rs->execute([$openid]);
        // $ret=$rs->fetchAll();
        $_SESSION["uid"]=$db->lastInsertId();
    }else{
        $_SESSION["uid"]=$ret[0]["uid"];
    }
    redirect("./");