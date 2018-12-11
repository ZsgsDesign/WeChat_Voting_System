<?php
    require_once("conn.php");
?>

<!DOCTYPE html>
<html lang="zh-Hans-CN">

<head>
    <meta charset="UTF-8">
    <title>投票结果</title>
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
    <style>
        card {
            display: block;
            box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 30px;
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
            width:100%;
        }
        card:hover{
            box-shadow: rgba(0, 0, 0, 0.15) 0px 0px 40px;
        }
        .container{
            height:100vh;
            display:flex;
            justify-content:center;
            align-items:center;
        }
        a:hover {
            text-decoration: none;
        }
    </style>
    <div class="container">
        <card>
            <h5><i class="MDI certificate"></i> 投票结果</h5>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <a href="detail.php?oid=1">
                        <card class="text-center">
                            <h5 class="mb-0">最想去的学校</h5>
                        </card>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <a href="detail.php?oid=2">
                        <card class="text-center">
                            <h5 class="mb-0">最佳演讲</h5>
                        </card>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <a href="detail.php?oid=3">
                        <card class="text-center">
                            <h5 class="mb-0">最具创意</h5>
                        </card>
                        </a>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <a href="detail.php?oid=4">
                        <card class="text-center">
                            <h5 class="mb-0">最佳表演</h5>
                        </card>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <a href="detail.php?oid=5">
                        <card class="text-center">
                            <h5 class="mb-0">最具魅力</h5>
                        </card>
                    </a>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <a href="detail.php?oid=6">
                        <card class="text-center">
                            <h5 class="mb-0">看上去是特等奖其实它真的是</h5>
                        </card>
                    </a>
                </div>
            </div>
        </card>
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