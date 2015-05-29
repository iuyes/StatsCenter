<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="utf-8">

    <title> 多玩网数据统计中心 </title>
    <meta name="description" content="">
    <meta name="author" content="">

    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <link rel="stylesheet" type="text/css" media="screen" href="/static/smartadmin/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="/static/smartadmin/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" media="screen" href="/static/smartadmin/css/smartadmin-production.css">
    <link rel="stylesheet" type="text/css" media="screen" href="/static/smartadmin/css/smartadmin-skins.css">
    <link rel="stylesheet" type="text/css" media="screen" href="/static/smartadmin/css/demo.css">
    <link rel="stylesheet" href="<?=WEBROOT?>/static/css/style.css">
    <link rel="shortcut icon" href="/static/app/img/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/static/app/img/favicon.ico" type="image/x-icon">
</head>
<body class="">
<header style="background: #E4E4E4;color: #22201F" id="header">
    <span><img style="vertical-align:top;padding: 8px" src="/static/app/img/logo.png" /></span>
    <span id="logo" style="margin-left: 0px"><strong style="font-size: 18px;">多玩网统计中心</strong></span>
    <span style="float: right;padding: 15px 5px;font-weight: bolder">
        <span style="text-transform: none;">
                    <a style="text-decoration: none" href="/user/edit">用户：<?= $_COOKIE['username'] ?>
        </span>
        <span style="text-transform: none;padding: 15px 5px;">
                    <a style="text-decoration: none;font-weight: bolder" href="/page/logout/">退出</a>
        </span>
    </span>
</header>
    <aside id="left-panel">
        <!--            --><?php //include __DIR__.'/../include/login_info.php'; ?>
        <?php include __DIR__.'/../include/leftmenu.php'; ?>
        <span class="minifyme"> <i class="fa fa-arrow-circle-left hit"></i> </span>
    </aside>