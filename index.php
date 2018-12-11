<?php
    require_once("conn.php");
    if(!isWechat()){
        redirect("unsupported.php");
    }
    if(!isset($_SESSION["uid"])){
        wxIndex();
    }
?>

<!DOCTYPE html>
<html lang="zh-Hans-CN">

<head>
    <meta charset="UTF-8">
    <title>投票</title>
    <!-- Necessarily Declarations -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="format-detection" content="telephone=no">
    <meta name="renderer" content="webkit">
    <meta http-equiv="Cache-Control" content="no-siteapp" />
    <link rel="alternate icon" type="image/png" href="https://static.1cf.co/img/atsast/favicon.png">
    <!-- Loading Style -->
    <style>
    *{
        user-select:none;
    }
    loading > div{
        text-align: center;
    }
    loading p{
        font-weight:100;
    }
    loading{
        display:flex;
        z-index:999;
        position:fixed;
        top:0;
        bottom:0;
        right:0;
        left:0;
        justify-content:center;
        align-items:center;
        background: #f5f5f5;
        transition:.2s ease-out .0s;
        opacity:1;
    }

    .lds-ellipsis {
        display: inline-block;
        position: relative;
        width: 64px;
        height: 64px;
    }
    .lds-ellipsis div {
        position: absolute;
        top: 27px;
        width: 11px;
        height: 11px;
        border-radius: 50%;
        background: rgba(0,0,0,.54);
        animation-timing-function: cubic-bezier(0, 1, 1, 0);
    }
    .lds-ellipsis div:nth-child(1) {
        left: 6px;
        animation: lds-ellipsis1 0.6s infinite;
    }
    .lds-ellipsis div:nth-child(2) {
        left: 6px;
        animation: lds-ellipsis2 0.6s infinite;
    }
    .lds-ellipsis div:nth-child(3) {
        left: 26px;
        animation: lds-ellipsis2 0.6s infinite;
    }
    .lds-ellipsis div:nth-child(4) {
        left: 45px;
        animation: lds-ellipsis3 0.6s infinite;
    }
    @keyframes lds-ellipsis1 {
        0% {
            transform: scale(0);
        }
        100% {
            transform: scale(1);
        }
    }
    @keyframes lds-ellipsis3 {
        0% {
            transform: scale(1);
        }
        100% {
            transform: scale(0);
        }
    }
    @keyframes lds-ellipsis2 {
        0% {
            transform: translate(0, 0);
        }
        100% {
            transform: translate(19px, 0);
        }
    }
    </style>
</head>
<body>
    <!-- Loading -->
    <loading>
        <div>
            <div class="lds-ellipsis">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
            <p>加载中……</p>
        </div>
    </loading>
    <!-- Style -->
    <link rel="stylesheet" href="https://fonts.geekzu.org/css?family=Roboto:300,300i,400,400i,500,500i,700,700i">
    <link rel="stylesheet" href="https://static.1cf.co/css/bootstrap-material-design.min.css">
    <link rel="stylesheet" href="https://static.1cf.co/css/wemd-color-scheme.css">
    <link rel="stylesheet" href="https://static.1cf.co/fonts/MDI-WXSS/MDI.css">
    <link rel="stylesheet" href="https://static.1cf.co/css/animate.min.css">
    <style>
        .container{
            padding:0;
        }
        section{
            margin-top: 1rem;
            margin-bottom: 1rem;
            background: #fff;
            padding:1rem;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 20px;
        }
        h5{
            color: #7a8e97;
            margin-bottom: 2rem;
        }
        p{
            color: #7a8e97;
        }
        options{
            display: block;
            /* box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 30px; */
            border-radius: 4px;
            transition: .2s ease-out .0s;
            color: #7a8e97;
            background: #fff;
            position: relative;
            border: 1px solid rgba(0, 0, 0, 0.15);
            margin-top: 1rem;
            margin-bottom: 1rem;
            padding:1rem;
            overflow: hidden;
            z-index: 0;
            counter-increment: options;
            /* counter-reset: options 1; */
        }
        options.hover {
            box-shadow: rgba(0, 0, 0, 0.15) 0px 0px 20px;
        }
        options.disabled {
            display:none;
            pointer-events: none;
        }
        options::before {
            content: counter(options) ". ";
        }
    </style>
    <div class="container">
        <section class="animated" style="display:none;">
            <h5><i class="MDI help-circle-outline"></i> <span id="question"></span></h5>
            <div id="option_group">
            </div>
            <div class="text-right" id="submit_btn">
                
            </div>
        </section>
        <p class="text-center animated">Author: John Zhang</p>
    </div>

    <script src="https://static.1cf.co/js/jquery-3.2.1.min.js"></script>
    <script src="https://static.1cf.co/js/popper.min.js"></script>
    <script src="https://static.1cf.co/js/snackbar.min.js"></script>
    <script src="https://static.1cf.co/js/bootstrap-material-design.js"></script>
    <script>
        $(document).ready(function () { $('body').bootstrapMaterialDesign(); });

        window.addEventListener("load",function() {
            fetch();
        }, false);
        
        var selectedOption = null;
        var curQuestion = null;
        var submitProc=false;

        function submit(){
            if(submitProc) return;
            else submitProc=true;
            if(selectedOption===null){
                alert("请选择一个选项");
                return;
            }
            $.post("ajax.php",{
                action: "submit",
                qid: curQuestion,
                oid: selectedOption
            },function(result){
                submitProc=false;
                if(result.ret==200){
                    fetch();
                }else{
                    alert(result.desc);
                }
            });
        }

        function fetch(){
            $("section").removeClass("zoomIn");
            $("section").addClass("zoomOut");
            $("#submit_btn").html("");
            $.post("ajax.php",{
                action:"fetch",
            },function(result){
                if(result.ret==200){
                    $('loading').css({"opacity":"0","pointer-events":"none"});
                    $("#question").html(result.data.question.name);
                    $("#option_group").html("");
                    result.data.option.forEach(item => {
                        console.log(item);
                        $("#option_group").append('<options data-value="' + item.oid + '">' + item.name + '</options>');
                    });

                    selectedOption=null;
                    curQuestion=result.data.question.qid;

                    $("section").css("display","block");
                    $("section").removeClass("zoomOut");
                    $("section").addClass("zoomIn");
                    $("p").addClass("jackInTheBox");

                    $("options").click(function(){
                        $("[data-value='"+selectedOption+"']").removeClass("hover");
                        selectedOption = $(this).attr("data-value");
                        $(this).addClass("hover");
                        if($("#submit_btn").html()==""){
                            $("#submit_btn").html('<button type="button" class="btn btn-success animated jackInTheBox" style="will-change:scale;" onclick="submit()">提交</button>');
                        }
                    });
                }else if(result.ret==201){
                    location.href="success.php";
                }else{
                    alert("网络连接异常");
                }
            });
        }
    </script>
</body>

</html>