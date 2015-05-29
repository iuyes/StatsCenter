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
                                                </select><i></i></label></span></div>
                                    <div class="dataTables_filter" style="margin-left: 185px;">
                                    </div>
                                </div>
                            </div>

                            <table id="data_table_stats" class="table table-bordered" style="table-layout:fixed; word-break: break-all; overflow:hidden;">
                                <thead>
                                <tr>
                                    <th style="width: 15%; overflow-x: hidden;">模块名称</th>
                                    <th style="width: 15%; overflow-x: hidden;">接口名称</th>
                                    <th style="width: 10%; overflow-x: hidden;">染色ID</th>
                                    <th style="width: 10%; overflow-x: hidden;">时间</th>
                                    <th style="width: 10%; overflow-x: hidden;">用户</th>
                                    <th style="width: 10%; overflow-x: hidden;">IP</th>
                                    <th style="width: 5%; overflow-x: hidden;">Level</th>
                                    <th style="max-width: 30%; overflow-x: hidden; ">内容</th>
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
    $(function() {
        var detailsG = {
            config: {
                'green_rate': 80,
                'data_table': {
                    "sPaginationType": "bootstrap_full",
                    "iDisplayLength": 25,
                    "aaSorting": [[0, "desc"]],
                    "oLanguage": {
                        "sInfo": "总计：_TOTAL_ ，当前：_START_ 到 _END_",
                        "oPaginate": {
                            "sFirst": "首页",
                            "sPrevious": "前一页",
                            "sNext": "后一页",
                            "sLast": "尾页"
                        }
                    }
                }
            }
        };

        pageSetUp();
        LogsG.filter = <?php echo json_encode($_GET);?>;
        var log_details = <?php echo json_encode($data);?>;
        renderLogs(log_details);

        function renderLogs(data)
        {
            if (data.status == 200) {
                for (i = 0; i < data.content.length; i++) {
                    line = "<tr height='32'>";
                        line += '<td>' + data.module['name'] + '</td>';
                        line += '<td>' + data.interface['name'] + '</td>';
                        line += '<td>' + data.content[i]['special_id'] + '</td>';
                        line += '<td>' + data.content[i]['date_time'] + '</td>';
                        line += '<td>' + data.content[i]['user_id'] + '</td>';
                        line += '<td>' + data.content[i]['client_ip'] + '</td>';
                        line += '<td>' + data.content[i]['level'] + '</td>';
                        line += '<td style="width: 300px; white-space:nowrap; overflow-x: hidden;">' + data.content[i]['txt'] + '</td>';
                    line += "</tr>";
                    $('#data_table_body').append(line);
                }
            }
            $('#data_table_stats').DataTable().clear().destroy();
            ListsG.dataTable = $('#data_table_stats').dataTable(detailsG.config.data_table);
        }
    });
</script>

</body>
</html>
