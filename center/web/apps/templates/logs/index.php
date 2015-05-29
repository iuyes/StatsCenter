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
                            <div class="widget-body-toolbar" style="height: 60px;">

                            </div>

                            <div id="dt_basic_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                <div class="dt-top-row">
                                    <div class="dataTables_filter" style="top:-56px">
                                        <form id="checkout-form" class="smart-form" novalidate="novalidate">
                                            <div class="form-group" style="width: 200px;">
                                                <select class="select2" id="module_id">
                                                    <option value="">所有模块</option>
                                                    <?php foreach ($modules as $m): ?>
                                                        <option value="<?= $m['id'] ?>: <?= $m['name'] ?>"
                                                            <?php if ($m['id'] == $_GET['module_id']) echo 'selected="selected"'; ?> ><?= $m['id'] ?>
                                                            : <?= $m['name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group" style="width: 200px;">
                                                <select id="interface_id" class="select2">
                                                    <option value="">所有接口</option>
                                                    <?php foreach ($interfaces as $m): ?>
                                                        <option value="<?= $m['id'] ?>: <?= $m['name'] ?>"
                                                            <?php if ($m['id'] == $_GET['interface_id']) echo 'selected="selected"'; ?> >
                                                            <?= $m['id'] ?>: <?= $m['name'] ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            用户:
                                            <div class="form-group" style="width: 100px;">
                                                <label class="input">
                                                    <input type="text" name="user_id" id="user_id" placeholder="用户ID" value="<?= $_GET['user_id']?>">
                                                </label>
                                            </div>
                                            串号:
                                            <div class="form-group" style="width: 100px;">
                                                <label class="input">
                                                    <input type="text" name="special_id" id="special_id" placeholder="串号ID" value="<?= $_GET['special_id']?>">
                                                </label>
                                            </div>
                                            IP:
                                            <div class="form-group" style="width: 120px;">
<!--                                                    <input type="text" name="client_ip" id="client_ip" placeholder="IP" value="--><?//= $_GET['client_ip']?><!--">-->
                                                    <?php echo $form['client'];?>

                                            </div>
                                            <div class="form-group" style="width: 120px;">
                                                <select class="select2" id="level">
                                                    <option value="">日志等级</option>
                                                    <?php foreach ($levels as $id => $m): ?>
                                                        <option value="<?= $id ?>"
                                                            <?php if ($id == $_GET['level']) echo 'selected="selected"'; ?> ><?= $m ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            时间:
                                            <div class="form-group">

                                                <label class="select">
                                                    <select class="input-sm" id="filter_hour_s">
                                                        <option value='00' selected="selected">00</option>
                                                        <?php
                                                        for ($i = 1; $i < 24; $i++) {
                                                            $v = $i >= 10 ? $i : '0' . $i;
                                                            if (!empty($_GET['hour_start']) and $_GET['hour_start'] == $v)
                                                            {
                                                                echo "<option value='$v' selected='selected'>$v</option>\n";
                                                            } else {
                                                                echo "<option value='$v'>$v</option>\n";
                                                            }

                                                        }
                                                        ?>
                                                    </select>
                                                </label>
                                            </div>
                                            -
                                            <div class="form-group">
                                                <label class="select">
                                                    <select class="input-sm" id="filter_hour_e">
                                                        <?php
                                                        for ($i = 0; $i < 23; $i++) {
                                                            $v = $i >= 10 ? $i : '0' . $i;
                                                            if (!empty($_GET['hour_end']) and $_GET['hour_end'] == $v)
                                                            {
                                                                echo "<option value='$v' selected='selected'>$v</option>\n";
                                                            } else {
                                                                echo "<option value='$v'>$v</option>\n";
                                                            }
                                                        }
                                                        ?>
                                                        <?php
                                                        if (empty($_GET['hour_end']))
                                                        {
                                                            echo '<option value="23" selected="selected">23</option>';
                                                        }
                                                        ?>

                                                    </select>
                                                </label>
                                            </div>
                                            日期：
                                            <div class="form-group">
                                                <input type="text" class="form-control datepicker"
                                                       data-dateformat="yy-mm-dd" id="data_key"
                                                       readonly="readonly" value="<?= $_GET['date_key'] ?>"
                                                    />
                                            </div>
                                            <div class='form-group'>
                                                <a id='submit' class='btn btn-success' style='padding:6px 12px' href='javascript:void(0)'>提交查询</a>
                                            </div>
                                            <?php
                                            if (!empty($_GET['interface_id']) and !empty($_GET['module_id']))
                                            {
                                                echo "<div style='padding-left:20px' class='form-group'>
                                                        <a style='padding:6px 12px' href='/logs/runtime/?interface_id=".$_GET['interface_id']."&module_id=".$_GET['module_id']."' class='btn btn-primary'>查看实时日志</a>
                                                    </div>";
                                            }
                                            ?>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="well no-padding">
                                <div id="log-tip">
                                    <?php
                                    if ($logs['status'] == 200) {
                                        ?>
                                        <div class="alert alert-success fade in">
                                            <button class="close" data-dismiss="alert">
                                                ×
                                            </button>
                                            <i class="fa-fw fa fa-check"></i>
                                            <strong><?php echo $logs['msg'];?></strong>
                                        </div>
                                    <?php
                                    } elseif ($logs['status'] == 400) {
                                        ?>
                                        <div class="alert alert-danger fade in">
                                            <button class="close" data-dismiss="alert">
                                                ×
                                            </button>
                                            <i class="fa-fw fa fa-check"></i>
                                            <strong><?php echo $logs['msg'];?></strong>
                                        </div>
                                    <?php
                                    }
                                    ?>
                                </div>
                                <div id="tabs2" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
                                    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
                                        <li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="tabs-1" aria-labelledby="ui-id-27" aria-selected="true">
                                            <a href="#tabs-1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-27">
                                                历史日志详情
                                            </a>
                                        </li>
                                    </ul>
                                    <div style="background-color: black;overflow-y: scroll;height: 600px;" id="tabs-1" aria-labelledby="ui-id-27" class="log-box ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-expanded="true" aria-hidden="false">
                                        <?php foreach ($logs['content'] as $m): ?>
                                            <span class="logs">
                                                <span style="font-weight:bold;font-style:italic;color:#5f895f;text-shadow:0 0 5px #5f895f;"><?= $m['client_ip'] ?></span>
                                                - -
                                                <span style="font-weight:bold;font-style:italic;color:#c26565;"><?= date("Y-m-d H:i:s",$m['time']) ?> </span>
                                                - -
                                                <span style="font-weight:bold;"><?= $m['txt'] ?></span>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                    <div class="pager-box">
                                        <?php echo $pager['render'];?>
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
<script>
    $(function() {
        pageSetUp();
        var LogsG = {};
        LogsG.filter = <?php echo json_encode($_GET);?>;
        $("#datepicker").datepicker("option",
            $.datepicker.regional[ 'zh-CN' ]);

        $("#module_id").change(function(e) {
            var module_id = e.currentTarget.value.split(':')[0];
            window.localStorage.module_id = module_id;
            LogsG.filter.module_id = module_id;
            $("#client_ip").empty();
            LogsG.getInterfaceList();
        });
        $("#interface_id").change(function(e) {
            var interface_id = e.currentTarget.value.split(':')[0];
            window.localStorage.interface_id = interface_id;
            LogsG.filter.interface_id = interface_id;
            LogsG.getIpList();
        });
        $("#client_ip").change(function(e) {
            var client_ip = e.currentTarget.value;
            LogsG.filter.client_ip = client_ip;
        });
        $("#filter_hour_s").change(function() {
            LogsG.filterByHour();
        });
        $("#filter_hour_e").change(function() {
            LogsG.filterByHour();
        });
        $("#data_key").change(function() {
            LogsG.filter.date_key = $(this).val();

        });
        $("#submit").click(function(){
            LogsG.go();
        });
        LogsG.go = function() {
            LogsG.filter.special_id = $("#special_id").val();
            LogsG.filter.user_id = $("#user_id").val();
            LogsG.filter.level = $("#level").val();
            var url = '/logs/index/?';
            for (var o in LogsG.filter) {
                url += o + '=' + LogsG.filter[o] + '&';
            }
            location.href = url;
        };
        LogsG.getIpList = function(o) {
            $.ajax({
                url: "/logs/get_ip/?module_id="+LogsG.filter.module_id+"&interface_id="+LogsG.filter.interface_id,
                dataType : 'json',
                beforeSend:function() {
                    $("#client_ip").prev().children('.select2-choice').children('.select2-chosen').empty();
                    $("#client_ip").empty();
                },
                success: function(data) {
                    var line = '<option value="">请选择</option>';
                    if (data.status == 0) {
                        for (i = 0; i < data.client_ids.length; i++) {
                            line += '<option value="'+ data.client_ids[i] +'">' + data.client_ids[i] + '</option>';
                        }
                    }
                    $('#client_ip').html(line);
                }
            });
        }

        LogsG.getInterfaceList = function() {
            $.ajax({
                url: "/logs/get_interface/?module_id="+LogsG.filter.module_id,
                dataType : 'json',
                beforeSend:function() {
                    $("#interface_id").prev().children('.select2-choice').children('.select2-chosen').empty();
                    $("#interface_id").empty();
                },
                success: function(data) {
                    var line = '<option value="">请选择</option>';
                    if (data.status == 0) {
                        for (i = 0; i < data.interface_ids.length; i++) {
                            line += '<option value="'+ data.interface_ids[i]['id'] +'">' + data.interface_ids[i]['id']+':'+data.interface_ids[i]['name'] + '</option>';
                        }
                    }
                    $('#interface_id').html(line);
                }
            });
        }
        LogsG.filterByHour = function () {
            LogsG.filter.hour_start = $('#filter_hour_s').val();
            LogsG.filter.hour_end = $('#filter_hour_e').val();
        };
        setInterval(function(){
            $("#log-tip").hide(1500);
        },3000);
    });
</script>

</body>
</html>
