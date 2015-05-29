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
        <div class="row">
            <article class="col-sm-12 sortable-grid ui-sortable">
                <div class="jarviswidget jarviswidget-sortable" id="wid-id-0" data-widget-togglebutton="false"
                     data-widget-editbutton="false" data-widget-fullscreenbutton="false" data-widget-colorbutton="false"
                     data-widget-deletebutton="false" role="widget" style="width: 800px">
                    <header role="heading">
                        <ul class="nav nav-tabs pull-left in">
                            <li class="active">
                                <a><i class="fa fa-clock-o"></i>
                                    <span class="hidden-mobile hidden-tablet"><?= $data['title'] ?></span>
                                </a>
                            </li>
                        </ul>
                        <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span>
                    </header>

                    <!-- widget div-->
                    <div class="no-padding" role="content">
                        <div class="widget-body">
                            <form class="smart-form" method="post">
                                <?php include dirname(__DIR__) . '/include/msg.php'; ?>
                                <input type="hidden" class="input" name="id" value="<?= $data['data']['id'] ?>">
                                <fieldset>
                                    <section>
                                        <label class="label">接口名称</label>
                                        <label class="input">
                                            <input type="text" class="input" name="name"  value="<?= $data['data']['name'] ?>">
                                        </label>
                                    </section>
                                </fieldset>
                                <fieldset>
                                    <section>
                                        <label class="label">模块名称</label>
                                        <label class="select">
                                            <?=$form['module_id']?>
                                            <i></i>
                                        </label>
                                    </section>
                                </fieldset>
                                <fieldset>
                                    <section>
                                        <label class="label">接口别名</label>
                                        <label class="input">
                                            <input type="text" class="input" name="alias"  value="<?= $data['data']['alias'] ?>">
                                        </label>
                                    </section>
                                </fieldset>
                                <fieldset>
                                    <section>
                                        <label class="label">成功率阀值(0-100)</label>
                                        <label class="input">
                                            <input type="text" class="input" name="succ_hold"  value="<?= $data['data']['succ_hold'] ?>">
                                        </label>
                                    </section>
                                </fieldset>
                                <fieldset>
                                    <section>
                                        <label class="label">调用量波动阀值(0-100)</label>
                                        <label class="input">
                                            <input type="text" class="input" name="wave_hold"  value="<?= $data['data']['wave_hold'] ?>">
                                        </label>
                                    </section>
                                </fieldset>
                                <fieldset>
                                    <section>
                                        <label class="label">报警策略</label>
                                        <label class="radio state-success" style="display: inline-block">
                                            <input type="radio" name="enable_alert" value="1" <?php echo $data['data']['enable_alert']==1?'checked':'' ;?>>
                                            <i></i>开启
                                        </label>
                                        <label class="radio state-error" style="display: inline-block">
                                            <input type="radio" name="enable_alert" value="2" <?php echo $data['data']['enable_alert']==2?'checked':'' ;?>>
                                            <i></i>关闭
                                        </label>
                                        <label class="label">报警间隔时间(分钟)</label>
                                        <label class="input" style="padding-bottom: 10px">
                                            <input type="text" class="input" name="alert_int"  value="<?= $data['data']['alert_int'] ?>">
                                        </label>
                                        <label class="checkbox state-success" style="display: inline-block;">
                                            <input type="checkbox" name="alert_types[]" value="1" <?php echo (is_array($data['data']['alert_types'])&&in_array(1,$data['data']['alert_types']))?'checked':'' ;?>>
                                            <i></i>弹窗
                                        </label>
                                        <label class="checkbox state-success" style="display: inline-block;">
                                            <input type="checkbox" name="alert_types[]" value="2" <?php echo (is_array($data['data']['alert_types'])&&in_array(2,$data['data']['alert_types']))?'checked':'' ;?>>
                                            <i></i>短信
                                        </label><span class="note">(使用手机短信报警前,请确认在本系统用户管理中绑定手机号正确)</span>
                                        <label class="label">报警通知列表</label>
                                        <div class="form-group">
                                            <?=$form['alert_uids']?>
                                        </div>

                                    </section>
                                </fieldset>
<!--                                <fieldset>
                                    <section>
                                        <label class="label">短信报警通知</label>
                                        <label class="radio state-success" style="display: inline-block">
                                            <input type="radio" name="enable_msg" value="1" <?php echo $data['data']['enable_msg']==1?'checked':'' ;?>>
                                            <i></i>开启
                                        </label>
                                        <label class="radio state-error" style="display: inline-block">
                                            <input type="radio" name="enable_msg" value="2" <?php echo $data['data']['enable_msg']==2?'checked':'' ;?>>
                                            <i></i>关闭
                                        </label>
                                        <label class="label">MSG报警时间间隔(分钟)</label>
                                        <label class="input">
                                            <input type="text" class="input" name="msg_int"  value="<?= $data['data']['msg_int'] ?>">
                                        </label>
                                        <label class="label">选择通知列表</label>
                                        <div class="form-group">
                                            <?=$form['msg_uids']?>
                                        </div>
                                    </section>
                                </fieldset>-->
                                <fieldset>
                                    <section>
                                        <label class="label">负责人</label>
                                        <div class="form-group">
                                            <?=$form['owner_uid']?>
                                        </div>
                                    </section>
                                </fieldset>
                                <fieldset>
                                    <section>
                                        <label class="label">备份负责人</label>
                                        <div class="form-group">
                                            <?=$form['backup_uids']?>
                                        </div>
                                    </section>
                                </fieldset>
                                <fieldset>
                                    <section>
                                        <label class="label">接口介绍</label>
                                        <label class="textarea textarea-resizable">
                                            <textarea rows="3" class="custom-scroll" name="intro" value=""><?= $data['data']['intro'] ?></textarea>
                                        </label>
                                    </section>
                                </fieldset>
                                <footer>
                                    <button type="submit" class="btn btn-primary">
                                        Submit
                                    </button>
                                    <button type="button" class="btn btn-default" onclick="window.history.back();">
                                        Back
                                    </button>
                                </footer>
                            </form>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </div>

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
                <a href="#gallery.html" class="jarvismetro-tile big-cubes bg-color-greenLight"> <span
                        class="iconbox"> <i
                            class="fa fa-picture-o fa-4x"></i> <span>Gallery </span> </span> </a>
            </li>
            <li>
                <a href="javascript:void(0);" class="jarvismetro-tile big-cubes selected bg-color-pinkDark"> <span
                        class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>My Profile </span> </span> </a>
            </li>
        </ul>
    </div>
    <?php include dirname(__DIR__) . '/include/javascript.php'; ?>
    <script >
        pageSetUp();
        if (window.localStorage.module_id) {
            $('#module_id').val(window.localStorage.module_id);
        }
    </script>
</body>
</html>
