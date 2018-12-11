<?php
    require_once("conn.php");

    header('Content-Type:application/json; charset=utf-8');

    $action=@$_POST["action"];

    if (!isWechat() && $action!="admin") {
        exit(json_encode([
            "ret"=>"1001",
            "desc"=>"请使用微信浏览器",
            "data"=>null
        ]));
    }

    if (is_null($action)) {
        exit(json_encode([
            "ret"=>"1000",
            "desc"=>"参数不全",
            "data"=>null
        ]));
    }

    if (@!isset($_SESSION["uid"]) && $action!="admin") {
        exit(json_encode([
            "ret"=>"1002",
            "desc"=>"请先登录",
            "data"=>null
        ]));
    }

    if ($action=="submit") {
        $qid=@$_POST["qid"];
        $oid=@$_POST["oid"];

        if(is_null($qid) || is_null($oid)){
            exit(json_encode([
                "ret"=>"1000",
                "desc"=>"参数不全",
                "data"=>null
            ]));
        }

        $rs=$db->prepare("SELECT * from vote where uid=? and (answer=? or qid=?)");
        $rs->execute([$_SESSION["uid"],$oid,$qid]);
        $ret=$rs->fetchAll();

        if(!empty($ret)){
            exit(json_encode([
                "ret"=>"1004",
                "desc"=>"请勿重复投票",
                "data"=>null
            ]));
        }

        $rs=$db->prepare("INSERT into vote set uid=? ,answer=?, qid=?");
        $rs->execute([$_SESSION["uid"],$oid,$qid]);
        exit(json_encode([
            "ret"=>"200",
            "desc"=>"成功",
            "data"=>null
        ]));
    } elseif ($action=="fetch") {
        $rs=$db->prepare("SELECT * from question where qid not in (select qid from vote where uid=?)");
        $rs->execute([$_SESSION["uid"]]);
        $ret=$rs->fetchAll();
        if(empty($ret)){
            exit(json_encode([
                "ret"=>"201",
                "desc"=>"成功",
                "data"=>null
            ]));
        }
        $q_rand=array_rand($ret);
        $question=[
            "qid"=>$ret[$q_rand]["qid"],
            "name"=>$ret[$q_rand]["name"],
            "img"=>$ret[$q_rand]["img"],
        ];
        $option=[];
        $rs=$db->prepare("SELECT oid,name from `option` where oid not in (select answer from vote where uid=?)");
        $rs->execute([$_SESSION["uid"]]);
        $ret=$rs->fetchAll();
        foreach ($ret as $r) {
            array_push($option, [
                "oid"=>$r["oid"],
                "name"=>$r["name"]
            ]);
        }
        exit(json_encode([
            "ret"=>"200",
            "desc"=>"成功",
            "data"=>[
                "question"=>$question,
                "option"=>$option
            ]
        ]));
    } elseif ($action=="admin") {
        $pass=@$_POST["pass"];
        if(is_null($pass) ){
            exit(json_encode([
                "ret"=>"1000",
                "desc"=>"参数不全",
                "data"=>null
            ]));
        }
        if($pass=="sastsast+1s"){
            $_SESSION["admin"]=1;
            exit(json_encode([
                "ret"=>"200",
                "desc"=>"成功",
                "data"=>null
            ]));
        }else{
            exit(json_encode([
                "ret"=>"1005",
                "desc"=>"密码错误",
                "data"=>null
            ]));
        }
    } else {
        exit(json_encode([
            "ret"=>"1003",
            "desc"=>"参属非法",
            "data"=>null
        ]));
    }
