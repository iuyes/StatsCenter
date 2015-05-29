<?php include __DIR__.'/../include/header.php'; ?>
<!-- END NAVIGATION -->

<!-- MAIN PANEL -->
<div id="main" role="main">

    <!-- RIBBON -->
    <div id="ribbon">

    <span class="ribbon-button-alignment">
        <span id="refresh" class="btn btn-ribbon" data-title="refresh" rel="tooltip"
              data-placement="bottom"
              data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings."
              data-html="true"><i class="fa fa-refresh"></i></span> </span>

        <!-- breadcrumb -->
        <ol class="breadcrumb">
            <li>Home</li>
            <li>Dashboard</li>
        </ol>

    </div>

    <div id="content">
        <!-- row -->
        <div class="row">

            <!-- NEW WIDGET START -->
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12 sortable-grid ui-sortable">

                <div class="jarviswidget jarviswidget-color-darken jarviswidget-sortable" id="wid-id-0"
                     data-widget-editbutton="false" role="widget" style="">
                    <header role="heading">
                        <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                        <h2>日志统计</h2>
                        <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
                    <div role="content">

                        <div class="jarviswidget-editbox">

                        </div>

                        <div class="widget-body no-padding">
                            <div class="widget-body-toolbar" style="height: 40px;">
                                <div id="log-tip">
                                    <span style="padding-left: 100px"><a id="connect" class="btn btn-success btn-sm" href="javascript:connect();">连接</a></span>
                                    <span style="padding-left: 50px"><a id="connect" class="btn btn-info btn-sm" href="javascript:term.clear();">清屏</a></span>
                                    <span style="padding-left: 50px"><a id="exit" class="btn btn-danger btn-sm" href="javascript:exit();">断开</a></span>
                                </div>
                            </div>
                            <div class="well no-padding">
                                <div id="tabs2" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
                                    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
                                        <li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="tabs-1" aria-labelledby="ui-id-27" aria-selected="true">
                                            <a href="#tabs-1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-27">
                                                实时日志详情
                                            </a>
                                        </li>
                                    </ul>
<!--                                    id="term_log"-->
                                    <div class="term">
                                        <div class="log-bg">
                                            <div class="log">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end widget content -->

                </div>
                <!-- end widget div -->

        </div>
        <div class="jarviswidget jarviswidget-color-blueDark jarviswidget-sortable" id="wid-id-1"
             data-widget-editbutton="false" role="widget" style="">


        </div>
        <div class="jarviswidget jarviswidget-color-blueDark jarviswidget-sortable" id="wid-id-2"
             data-widget-editbutton="false" role="widget" style="">

        </div>
        <div class="jarviswidget jarviswidget-color-blueDark jarviswidget-sortable" id="wid-id-3"
             data-widget-editbutton="false" role="widget" style="">

        </div>
        </article>
        <!-- WIDGET END -->
    </div>
</div>
<!-- END MAIN CONTENT -->

</div>
<!-- END MAIN PANEL -->

<!-- SHORTCUT AREA : With large tiles (activated via clicking user name tag)
Note: These tiles are completely responsive,
you can add as many as you like
-->
<div id="shortcut">
    <ul>
        <li>
            <a href="#inbox.html" class="jarvismetro-tile big-cubes bg-color-blue"> <span class="iconbox"> <i
                        class="fa fa-envelope fa-4x"></i> <span>Mail <span
                            class="label pull-right bg-color-darken">14</span></span> </span> </a>
        </li>
        <li>
            <a href="#calendar.html" class="jarvismetro-tile big-cubes bg-color-orangeDark"> <span class="iconbox"> <i
                        class="fa fa-calendar fa-4x"></i> <span>Calendar</span> </span> </a>
        </li>
        <li>
            <a href="#gmap-xml.html" class="jarvismetro-tile big-cubes bg-color-purple"> <span class="iconbox"> <i
                        class="fa fa-map-marker fa-4x"></i> <span>Maps</span> </span> </a>
        </li>
        <li>
            <a href="#invoice.html" class="jarvismetro-tile big-cubes bg-color-blueDark"> <span class="iconbox"> <i
                        class="fa fa-book fa-4x"></i> <span>Invoice <span
                            class="label pull-right bg-color-darken">99</span></span> </span> </a>
        </li>
        <li>
            <a href="#gallery.html" class="jarvismetro-tile big-cubes bg-color-greenLight"> <span class="iconbox"> <i
                        class="fa fa-picture-o fa-4x"></i> <span>Gallery </span> </span> </a>
        </li>
        <li>
            <a href="javascript:void(0);" class="jarvismetro-tile big-cubes selected bg-color-pinkDark"> <span
                    class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>My Profile </span> </span> </a>
        </li>
    </ul>
</div>
<?php include dirname(__DIR__).'/include/javascript.php'; ?>
<script src="<?=WEBROOT?>/static/js/jquery.terminal.js" type="text/javascript"></script>
<script src="<?=WEBROOT?>/static/js/jquery.json.js" type="text/javascript"></script>
<script>
    var config = <?php echo json_encode($config);?>;
    var GET = getRequest();
    var running = 0;
    var term = {};
    term.ps1_flag = "<span class='ps1'> ></span>";
    term.welcome = "<span>欢迎来到多玩实时日志</span>";
    term.echo = function($msg) {
        var line = "<span>"+$msg+"</span>";
        $(".log").append(line);
    };

    term.clear = function() {
        $(".log").empty();
    }

    term.ps1 = function() {
        $(".log-bg").append(term.ps1_flag).focus();
    }

    term.resize = function () {
        var _term = $(".term").innerHeight();
        var _log = $(".log-bg").innerHeight();
        if (_term < _log) {
            $(".term").scrollTop(_log - _term + 50);
        }
    }
    $(document).ready(function () {
        term.echo(term.welcome);
        term.ps1();
        connect();
    });
    function connect()
    {
        if (running == 1) {
            term.echo("服务已经开启");
        } else {
            if (window.WebSocket || window.MozWebSocket) {
                ws = new WebSocket(config.server);
                listen();
            }
        }
    }

    function exit()
    {
        if (running == 0) {
            term.echo("服务没有开启");
        } else if (running == 1) {
            ws.close();
        } else if (running == 2) {
            term.echo("服务已关闭");
        } else if (running == 1) {
            term.echo("服务异常");
        }
    }
    var self = $(".log");
    function on_scrollbar_show_resize() {
        var scroll_bars = have_scrollbars(self);
        return function() {
            if (scroll_bars !== have_scrollbars(self)) {
                self.resize();
                scroll_bars = have_scrollbars(self);
            }
        };
    }
    function have_scrollbars(div) {

        return div.get(0).scrollHeight > div.innerHeight();
    }
    function listen()
    {
        ws.onopen = function (e) {
            term.echo("连接服务成功");
            running = 1;
            //模块id 和接口id 合法才可以连接服务器
            msg = {};
            msg.module_id = GET['module_id'];
            msg.interface_id = GET['interface_id'];
            if (msg.module_id == undefined || msg.interface_id == undefined) {
                term.echo("必须指定模块和接口");
                ws.close();
                return false;
            }
            msg.cmd = 'login';
            //后续增加用户权限
            ws.send($.toJSON(msg));
        };
        ws.onmessage = function (e) {
            var log = $.evalJSON(e.data);
            if (log.cmd == 'server')
            {
                var line = "<span class='log-ip'>"+log.client_ip+"</span> - - <span class='log-time'>"+log.time+"</span> - - <span class='log-content'>"+log.txt+"</span>";
                term.echo(line);
                term.resize();
            }
        };
        /**
         * 连接关闭事件
         */
        ws.onclose = function (e) {
            term.echo("关闭服务器成功");
            running = 2;
        };
        /**
         * 异常事件
         */
        ws.onerror = function (e) {
            term.echo("服务器发生异常");
            running = 3;
        };
    }
    function getRequest() {
        var url = location.search; // 获取url中"?"符后的字串
        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
            var str = url.substr(1);

            strs = str.split("&");
            for (var i = 0; i < strs.length; i++) {
                var decodeParam = decodeURIComponent(strs[i]);
                var param = decodeParam.split("=");
                theRequest[param[0]] = param[1];
            }

        }
        return theRequest;
    }
</script>

</body>
</html>
