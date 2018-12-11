<?php
    require_once("conn.php");
    if (isWechat()) {
        redirect("index.php");
    }
?>

<!DOCTYPE html>
<html lang="zh-Hans-CN">

<head>
    <meta charset="UTF-8">
    <title>不受支持的浏览器</title>
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
    <style>
    i{
        font-size: 7rem;
        line-height: 1;
        margin-bottom: 1rem;
        display: block;
        color: #7a8e97;
    }

    p{
        font-size: 1rem;
        color: #7a8e97;
    }
    .container{
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    </style>
    <div class="container text-center">
        <div>
            <i class="MDI cellphone-link-off"></i>
            <p>请使用微信浏览器打开</p>
        </div>
    </div>

    <script src="https://static.1cf.co/js/jquery-3.2.1.min.js"></script>
    <script src="https://static.1cf.co/js/popper.min.js"></script>
    <script src="https://static.1cf.co/js/snackbar.min.js"></script>
    <script src="https://static.1cf.co/js/bootstrap-material-design.js"></script>
    <script>
        $(document).ready(function () { $('body').bootstrapMaterialDesign(); });
        window.addEventListener("load",function() {
            $('loading').css({"opacity":"0","pointer-events":"none"});
        }, false);
    </script>
</body>

</html>