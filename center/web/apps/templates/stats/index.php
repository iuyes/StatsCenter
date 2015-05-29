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
                        <h2>接口调用统计</h2>
                        <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
                    <div role="content">

                        <div class="jarviswidget-editbox">

                        </div>

                        <div class="widget-body no-padding">
                            <div class="widget-body-toolbar" style="height: 40px;">

                            </div>

                            <div id="dt_basic_wrapper" class="dataTables_wrapper form-inline" role="grid">
                                <div class="dt-top-row">
                                    <div id="data_table_stats_length" class="dataTables_length"><span
                                            class="smart-form">
                                            <label class="select" style="width:60px">
                                                <select value="25" size="1" name="dt_basic_length" aria-controls="data_table_stats">
                                                    <option value="25" selected="selected">25</option>
                                                    <option value="50">50</option>
                                                    <option value="100">100</option>
                                                </select><i></i></label></span>
                                    </div>
                                    <div class="dataTables_filter" style="margin-left: 185px;">
                                        <?php include dirname(__DIR__) . '/include/filter.php'; ?>
                                        <div class="form-group">
                                            日期：
                                            <input type="text" class="form-control datepicker"
                                                   data-dateformat="yy-mm-dd" id="data_key"
                                                   readonly="readonly" value="<?= $_GET['date_key'] ?>"
                                                />
                                        </div>
                                    </div>
                                </div>
                            </div>

                                    <table id="data_table_stats" class="table table-bordered">
                                        <thead>
                                        <tr>
                                            <th style="width: 240px; overflow-x: hidden;">接口名称</th>
                                            <th style="width: 100px; overflow-x: hidden;">时间</th>
                                            <th style="width: 150px; overflow-x: hidden;">调用次数</th>
                                            <th style="width: 150px; overflow-x: hidden;">成功次数</th>
                                            <th style="width: 100px; overflow-x: hidden;">失败次数</th>
                                            <th style="width: 100px; overflow-x: hidden;">成功率</th>
                                            <th style="width: 100px; overflow-x: hidden;">响应最大值</th>
                                            <th style="width: 100px; overflow-x: hidden;">响应最小值</th>
                                            <th style="width: 100px; overflow-x: hidden;">平均响应时间</th>
                                            <th style="width: 100px; overflow-x: hidden;">失败平均时间</th>
                                            <th>操作</th>
                                        </tr>
                                        </thead>
                                        <tbody id="data_table_body"></tbody>
                                    </table>

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
    StatsG.filter.hour_start = 0;
    StatsG.filter.hour_end = 23;
    $(function() {
        pageSetUp();
        StatsG.filter = <?php echo json_encode($_GET);?>;
        getStatsData();
        $("#datepicker").datepicker("option",
            $.datepicker.regional[ 'zh-CN' ]);

        $("#module_id").change(function(e) {
            var module_id = e.currentTarget.value.split(':')[0];
            window.localStorage.module_id = module_id;
            StatsG.filter.module_id = window.localStorage.module_id;
//            $.ajax({
//                url: '/stats/getInterface/?module_id='+module_id,
//                dataType : 'json',
//                beforeSend: function() {
//                    $("#interface_id").html('');
//                },
//                success: function(data) {
//                    if (data.status == 200)
//                    {
//                        var line = '';
//                        var content = data.data;
//                        for ( id in content)
//                        {
//                            line = line + "<option value='" + id + "'>" + content[id] + "</option>";
//                        }
//                        $("#interface_id").html(line);
//                    }
//                }
//            });
            //location.href = '/stats/index/?module_id=' + module_id;
            StatsG.go();
        });
        $("#data_key").change(function(){
            window.localStorage.date_key = $(this).val();
            StatsG.filter.date_key = window.localStorage.date_key;
            StatsG.go();
        });
        $("#interface_id").change(function(e) {
            StatsG.filter.interface_id = e.currentTarget.value.split(':')[0];
            StatsG.filter.interface_name = e.currentTarget.value.split(':')[1];
            getStatsData();
        });
    });
</script>

</body>
</html>
