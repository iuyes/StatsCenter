        <?php include __DIR__.'/../include/header.php'; ?>
		<div id="main" role="main">

			<!-- RIBBON -->
			<div id="ribbon">
<!--				<span class="ribbon-button-alignment"> <span id="refresh" class="btn btn-ribbon" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true"><i class="fa fa-refresh"></i></span> </span>-->

				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<li>Home</li><li>Dashboard</li>
				</ol>
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

            <div class="row">
            <article class="col-sm-12 sortable-grid ui-sortable">
            <!-- new widget -->

            <!-- end widget -->

            <div class="jarviswidget jarviswidget-sortable" id="wid-id-0" data-widget-togglebutton="false" data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false" role="widget" style="">
            <!-- widget options:
            usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">

            data-widget-colorbutton="false"
            data-widget-editbutton="false"
            data-widget-togglebutton="false"
            data-widget-deletebutton="false"
            data-widget-fullscreenbutton="false"
            data-widget-custombutton="false"
            data-widget-collapsed="true"
            data-widget-sortable="false"

            -->
            <header role="heading">
                <span class="widget-icon"> <i class="glyphicon glyphicon-stats txt-color-darken"></i> </span>
                <h2>介绍 </h2>

                <ul class="nav nav-tabs pull-right in" id="myTab">
                    <li class="active">
                        <a data-toggle="tab" href="#s1"><i class="fa fa-clock-o"></i> <span class="hidden-mobile hidden-tablet">介绍</span></a>
                    </li>
                </ul>

                <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>

            <!-- widget div-->
            <div class="no-padding" role="content">
                <div class="widget-body">
                    <!-- content -->
                    <div id="myTabContent" class="tab-content">
<div class="tab-pane fade active in padding-10 no-padding-bottom" id="s1">
    <p>联系人：韩天峰/魏文唅/石光启 </p>

    <p>
        SDK下载：<a href="http://code.yy.com/duowan_base_tech/module_stats_sdk">
            http://code.yy.com/duowan_base_tech/module_stats_sdk
        </a>
    </p>

    <p>C++ SDK：联系 饶超勋</p>
</div>
                        <!-- end s1 tab pane -->

                        <div class="tab-pane fade" id="s2">
                            <div class="widget-body-toolbar bg-color-white">

                                <form class="form-inline" role="form">

                                    <div class="form-group">
                                        <label class="sr-only" for="s123">Show From</label>
                                        <input type="email" class="form-control input-sm" id="s123" placeholder="Show From">
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control input-sm" id="s124" placeholder="To">
                                    </div>

                                    <div class="btn-group hidden-phone pull-right">
                                        <a class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown"><i class="fa fa-cog"></i> More <span class="caret"> </span> </a>
                                        <ul class="dropdown-menu pull-right">
                                            <li>
                                                <a href="javascript:void(0);"><i class="fa fa-file-text-alt"></i> Export to PDF</a>
                                            </li>
                                            <li>
                                                <a href="javascript:void(0);"><i class="fa fa-question-sign"></i> Help</a>
                                            </li>
                                        </ul>
                                    </div>

                                </form>

                            </div>
                            <div class="padding-10">
                                <div id="statsChart" class="chart-large has-legend-unique" style="padding: 0px; position: relative;"><canvas class="base" width="100" height="235"></canvas><canvas class="overlay" width="100" height="235" style="position: absolute; left: 0px; top: 0px;"></canvas><div class="tickLabels" style="font-size:smaller"><div class="xAxis x1Axis" style="color:#545454"><div class="tickLabel" style="position:absolute;text-align:center;left:7px;top:235px;width:7px">JAN</div><div class="tickLabel" style="position:absolute;text-align:center;left:13px;top:235px;width:7px">FEB</div><div class="tickLabel" style="position:absolute;text-align:center;left:20px;top:235px;width:7px">MAR</div><div class="tickLabel" style="position:absolute;text-align:center;left:27px;top:235px;width:7px">APR</div><div class="tickLabel" style="position:absolute;text-align:center;left:33px;top:235px;width:7px">MAY</div><div class="tickLabel" style="position:absolute;text-align:center;left:40px;top:235px;width:7px">JUN</div><div class="tickLabel" style="position:absolute;text-align:center;left:47px;top:235px;width:7px">JUL</div><div class="tickLabel" style="position:absolute;text-align:center;left:53px;top:235px;width:7px">AUG</div><div class="tickLabel" style="position:absolute;text-align:center;left:60px;top:235px;width:7px">SEP</div><div class="tickLabel" style="position:absolute;text-align:center;left:67px;top:235px;width:7px">OCT</div><div class="tickLabel" style="position:absolute;text-align:center;left:73px;top:235px;width:7px">NOV</div><div class="tickLabel" style="position:absolute;text-align:center;left:80px;top:235px;width:7px">DEC</div><div class="tickLabel" style="position:absolute;text-align:center;left:87px;top:235px;width:7px">JAN+1</div></div><div class="yAxis y1Axis" style="color:#545454"><div class="tickLabel" style="position:absolute;text-align:right;top:225px;right:100px;width:0px">0</div><div class="tickLabel" style="position:absolute;text-align:right;top:171px;right:100px;width:0px">20</div><div class="tickLabel" style="position:absolute;text-align:right;top:118px;right:100px;width:0px">40</div><div class="tickLabel" style="position:absolute;text-align:right;top:64px;right:100px;width:0px">60</div><div class="tickLabel" style="position:absolute;text-align:right;top:10px;right:100px;width:0px">80</div></div></div><div class="legend"><div style="position: absolute; width: 0px; height: 0px; top: -22px; right: 5px; opacity: 1;"> </div><table style="position:absolute;top:-22px;right:5px;;font-size: 11px; color:#545454"><tbody><tr><td class="legendColorBox"><div style=""><div style="border:2px solid rgb(86,138,137);overflow:hidden"></div></div></td><td class="legendLabel"><span>Twitter</span></td><td class="legendColorBox"><div style=""><div style="border:2px solid rgb(50,118,177);overflow:hidden"></div></div></td><td class="legendLabel"><span>Facebook</span></td></tr></tbody></table></div></div>
                            </div>

                        </div>
                        <!-- end s2 tab pane -->

                        <div class="tab-pane fade" id="s3">

                            <div class="widget-body-toolbar bg-color-white smart-form" id="rev-toggles">

                                <div class="inline-group">

                                    <label for="gra-0" class="checkbox">
                                        <input type="checkbox" name="gra-0" id="gra-0" checked="checked">
                                        <i></i> Target </label>
                                    <label for="gra-1" class="checkbox">
                                        <input type="checkbox" name="gra-1" id="gra-1" checked="checked">
                                        <i></i> Actual </label>
                                    <label for="gra-2" class="checkbox">
                                        <input type="checkbox" name="gra-2" id="gra-2" checked="checked">
                                        <i></i> Signups </label>
                                </div>

                                <div class="btn-group hidden-phone pull-right">
                                    <a class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown"><i class="fa fa-cog"></i> More <span class="caret"> </span> </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="javascript:void(0);"><i class="fa fa-file-text-alt"></i> Export to PDF</a>
                                        </li>
                                        <li>
                                            <a href="javascript:void(0);"><i class="fa fa-question-sign"></i> Help</a>
                                        </li>
                                    </ul>
                                </div>

                            </div>

                            <div class="padding-10">
                                <div id="flotcontainer" class="chart-large has-legend-unique" style="padding: 0px; position: relative;"><canvas class="base" width="100" height="235"></canvas><canvas class="overlay" width="100" height="235" style="position: absolute; left: 0px; top: 0px;"></canvas><div class="tickLabels" style="font-size:smaller"><div class="xAxis x1Axis" style="color:#545454"><div class="tickLabel" style="position:absolute;text-align:center;left:5px;top:235px;width:16px">2013</div><div class="tickLabel" style="position:absolute;text-align:center;left:27px;top:235px;width:16px">2014</div><div class="tickLabel" style="position:absolute;text-align:center;left:49px;top:235px;width:16px">2015</div><div class="tickLabel" style="position:absolute;text-align:center;left:71px;top:235px;width:16px">2016</div></div><div class="yAxis y1Axis" style="color:#545454"><div class="tickLabel" style="position:absolute;text-align:right;top:225px;right:100px;width:0px">0</div><div class="tickLabel" style="position:absolute;text-align:right;top:182px;right:100px;width:0px">250</div><div class="tickLabel" style="position:absolute;text-align:right;top:139px;right:100px;width:0px">500</div><div class="tickLabel" style="position:absolute;text-align:right;top:96px;right:100px;width:0px">750</div><div class="tickLabel" style="position:absolute;text-align:right;top:53px;right:100px;width:0px">1000</div><div class="tickLabel" style="position:absolute;text-align:right;top:10px;right:100px;width:0px">1250</div></div></div><div class="legend"><div style="position: absolute; width: 0px; height: 0px; top: -22px; right: 5px; opacity: 1;"> </div><table style="position:absolute;top:-22px;right:5px;;font-size: 11px; color:#545454"><tbody><tr><td class="legendColorBox"><div style=""><div style="border:2px solid rgb(147,19,19);overflow:hidden"></div></div></td><td class="legendLabel"><span>Target Profit</span></td><td class="legendColorBox"><div style=""><div style="border:2px solid #3276B1;overflow:hidden"></div></div></td><td class="legendLabel"><span>Actual Profit</span></td><td class="legendColorBox"><div style=""><div style="border:2px solid #71843F;overflow:hidden"></div></div></td><td class="legendLabel"><span>Actual Signups</span></td></tr></tbody></table></div></div>
                            </div>
                        </div>
                        <!-- end s3 tab pane -->
                    </div>

                    <!-- end content -->
                </div>

            </div>
            <!-- end widget div -->
            </div></article>
		</div>
		<!-- END MAIN PANEL -->

		<!-- SHORTCUT AREA : With large tiles (activated via clicking user name tag)
		Note: These tiles are completely responsive,
		you can add as many as you like
		-->
		<div id="shortcut">
			<ul>
				<li>
					<a href="#inbox.html" class="jarvismetro-tile big-cubes bg-color-blue"> <span class="iconbox"> <i class="fa fa-envelope fa-4x"></i> <span>Mail <span class="label pull-right bg-color-darken">14</span></span> </span> </a>
				</li>
				<li>
					<a href="#calendar.html" class="jarvismetro-tile big-cubes bg-color-orangeDark"> <span class="iconbox"> <i class="fa fa-calendar fa-4x"></i> <span>Calendar</span> </span> </a>
				</li>
				<li>
					<a href="#gmap-xml.html" class="jarvismetro-tile big-cubes bg-color-purple"> <span class="iconbox"> <i class="fa fa-map-marker fa-4x"></i> <span>Maps</span> </span> </a>
				</li>
				<li>
					<a href="#invoice.html" class="jarvismetro-tile big-cubes bg-color-blueDark"> <span class="iconbox"> <i class="fa fa-book fa-4x"></i> <span>Invoice <span class="label pull-right bg-color-darken">99</span></span> </span> </a>
				</li>
				<li>
					<a href="#gallery.html" class="jarvismetro-tile big-cubes bg-color-greenLight"> <span class="iconbox"> <i class="fa fa-picture-o fa-4x"></i> <span>Gallery </span> </span> </a>
				</li>
				<li>
					<a href="javascript:void(0);" class="jarvismetro-tile big-cubes selected bg-color-pinkDark"> <span class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>My Profile </span> </span> </a>
				</li>
			</ul>
		</div>
        <?php include dirname(__DIR__).'/include/javascript.php'; ?>
	</body>
</html>
