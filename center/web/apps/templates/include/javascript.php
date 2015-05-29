<script src="/static/smartadmin/js/libs/jquery-2.0.2.min.js"></script>
<script src="<?=WEBROOT?>/static/jquery-ui/js/jquery-ui-1.10.4.custom.min.js" type="text/javascript"></script>
<script src="<?=WEBROOT?>/static/js/jquery-ui-zh.js" type="text/javascript"></script>
<script src="/static/smartadmin/js/app.js"></script>
<script src="/static/smartadmin/js/plugin/flot/jquery.flot.cust.js"></script>
<script src="/static/smartadmin/js/plugin/flot/jquery.flot.resize.js"></script>
<script src="/static/smartadmin/js/plugin/flot/jquery.flot.tooltip.js"></script>

<script src="/static/smartadmin/js/bootstrap/bootstrap.min.js"></script>
<script src="/static/smartadmin/js/notification/SmartNotification.min.js"></script>
<script src="/static/smartadmin/js/smartwidgets/jarvis.widget.min.js"></script>
<script src="/static/smartadmin/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
<script src="/static/smartadmin/js/plugin/sparkline/jquery.sparkline.min.js"></script>
<script src="/static/smartadmin/js/plugin/jquery-validate/jquery.validate.min.js"></script>
<script src="/static/smartadmin/js/plugin/masked-input/jquery.maskedinput.min.js"></script>
<script src="/static/smartadmin/js/plugin/select2/select2.min.js"></script>
<script src="/static/smartadmin/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>
<script src="/static/smartadmin/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
<script src="/static/smartadmin/js/plugin/fastclick/fastclick.js"></script>

<script src="/static/smartadmin/js/plugin/vectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="/static/smartadmin/js/plugin/vectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="/static/smartadmin/js/plugin/datatables/jquery.dataTables-cust.min.js"></script>

<script src="/static/smartadmin/js/plugin/datatables/ColReorder.min.js"></script>
<script src="/static/smartadmin/js/plugin/datatables/FixedColumns.min.js"></script>
<script src="/static/smartadmin/js/plugin/datatables/ColVis.min.js"></script>
<script src="/static/smartadmin/js/plugin/datatables/ZeroClipboard.js"></script>
<script src="/static/smartadmin/js/plugin/datatables/media/js/TableTools.min.js"></script>
<script src="/static/smartadmin/js/plugin/datatables/DT_bootstrap.js"></script>
<script src="/static/smartadmin/js/plugin/fullcalendar/jquery.fullcalendar.min.js"></script>

<script src="<?=WEBROOT?>/static/js/esl.js"></script>
<script src="<?=WEBROOT?>/static/js/charts.js"></script>
<script src="<?=WEBROOT?>/static/js/php.js" type="text/javascript"></script>
<script src="<?=WEBROOT?>/static/app/js/stats.js" type="text/javascript"></script>
<script src="<?=WEBROOT?>/static/app/js/list.js" type="text/javascript"></script>

<!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)-->
<script data-pace-options='{ "restartOnRequestAfter": true }' src="/static/smartadmin/js/plugin/pace/pace.min.js"></script>

<!-- JS TOUCH : include this plugin for mobile drag / drop touch events
<script src="/static/smartadmin/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> -->

<!--[if IE 7]>
<h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
<![endif]-->

<script>
    if (window.localStorage.module_id) {
        $('#stats_index_link').attr('href', $('#stats_index_link').attr('href') + '?module_id=' + window.localStorage.module_id);
    }
    if (window.localStorage.module_id && window.localStorage.interface_id) {
        $('#logs_index_link').attr('href', $('#logs_index_link').attr('href') + '?module_id=' + window.localStorage.module_id + '&interface_id=' + window.localStorage.interface_id);
    }
</script>