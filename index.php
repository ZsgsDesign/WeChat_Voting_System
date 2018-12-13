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
    <link rel="alternate icon" type="image/png" href="https://static.1cf.co/img/voting/favicon.png">
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
            padding:0;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 20px;
            /*overflow: hidden;*/
        }
        section > div{
            padding:1rem;
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
        options.selected {
            box-shadow: rgba(0, 0, 0, 0.15) 0px 0px 20px;
        }
        options.disabled {
            display:none;
            pointer-events: none;
        }
        options::before {
            content: counter(options) ". ";
        }
        options.opt-focus{
            box-shadow: rgba(0, 0, 0, 0.15) 0px 0px 40px;
        }
        img{
            width:100%;
            height:61.8vw;
            object-fit: cover;
            display: block;
            /*box-shadow: rgba(0, 0, 0, 0.25) 0px 0px 20px;*/
        }
    </style>
    <div class="container">
        <section class="animated" style="display:none;">
            <img src="">
            <div>
                <h5><i class="MDI bank"></i> <span id="question"></span></h5>
                <div id="option_group">
                </div>
                <div class="text-right" id="submit_btn">
                    
                </div>
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
        
        function optBlur(ele){
            $(ele).removeClass("opt-focus");
        }
        
        function optFocus(ele){
            $(ele).addClass("opt-focus");
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
                    $("img").attr({
                        "data-preload": result.data.question.img,
                        "src": imgLoading
                    });
                    // console.log(imgLoading);
                    startDelect($("img").attr("data-preload"));
                    $("#option_group").html("");
                    result.data.option.forEach(item => {
                        // console.log(item);
                        $("#option_group").append('<options data-value="' + item.oid + '" ontouchstart="optFocus(this)" ontouchend="optBlur(this)" ontouchcancel="optBlur(this)">' + item.name + '</options>');
                    });

                    selectedOption=null;
                    curQuestion=result.data.question.qid;

                    $("section").css("display","block");
                    $("section").removeClass("zoomOut");
                    $("section").addClass("zoomIn");
                    $("p").addClass("jackInTheBox");

                    $("options").click(function(){
                        $("[data-value='"+selectedOption+"']").removeClass("selected");
                        selectedOption = $(this).attr("data-value");
                        $(this).addClass("selected");
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
        

        var imgErr="data:image/jpeg;base64,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";
        var imgLoading="data:image/jpeg;base64,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";


        function startDelect(url) {
            var val = url;
            var img = new Image();
            
            img.onload = function() {
                if (img.complete == true) {
                    displayImg(img);
                }
            }
            
            img.onerror = function() {
                img.src = imgErr;
            }
            
            img.src = val;
        }


        function displayImg(obj) {
            $("img").attr("src",obj.src);
        }

    </script>
</body>

</html>