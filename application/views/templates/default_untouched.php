<?php
$CI =& get_instance();
?>
<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="author" content="">
    <title><?php echo isset($title)?$title:"Syncode BI Tool"; ?></title>
    <link rel="apple-touch-icon" href="<?php echo $assets; ?>images/apple-touch-icon.png">
    <link rel="shortcut icon" href="<?php echo $assets; ?>images/favicon.png">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/css/bootstrap-extend.min.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>css/site.min.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>css/custom.css">
    <!-- Plugins -->
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/animsition/animsition.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/asscrollable/asScrollable.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/switchery/switchery.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/intro-js/introjs.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/slidepanel/slidePanel.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/flag-icon-css/flag-icon.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/waves/waves.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/chartist/chartist.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/jvectormap/jquery-jvectormap.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.css">
<!--    <link rel="stylesheet" href="--><?php //echo $assets; ?><!--examples/css/dashboard/v1.css">-->

    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/datatables-bootstrap/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/datatables-fixedheader/dataTables.fixedHeader.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/datatables-responsive/dataTables.responsive.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/examples/css/tables/datatable.css">

    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/bootstrap-datepicker/bootstrap-datepicker.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/select2/select2.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/ladda/ladda.css">

    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/bootstrap-sweetalert/sweetalert.css">

    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/formvalidation/formValidation.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/examples/css/forms/validation.css">

    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/dropify/dropify.css">

    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/dropzonejs/dropzone.css">

    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/toastr/toastr.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/daterangepicker/daterangepicker.css">

    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/bootstrap-touchspin/bootstrap-touchspin.css">

    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/datatable-buttons/buttons.dataTables.css">

    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/datatable-colreorder/colReorder.dataTables.min.css">

    <link rel="stylesheet" href="<?php echo $assets; ?>/global/vendor/jstree/jstree.css">

<!--    <link rel="stylesheet" href="--><?php //echo $assets; ?><!--/global/vendor/datatables-colvis/dataTables.colVis.min.css">-->

    <!-- Fonts -->
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/fonts/material-design/material-design.min.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/fonts/brand-icons/brand-icons.min.css">
    <link rel="stylesheet" href="<?php echo $assets; ?>/global/fonts/font-awesome/font-awesome.css">


    <!-- Extra Css-->
    <?php
        if(isset($extra_css)){
            foreach ($extra_css as $css){
                echo '<link rel="stylesheet" href="'.$assets.$css.'">';
            }
        }
    ?>

    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
    <!--[if lt IE 9]>
    <script src="<?php echo $assets; ?>/global/vendor/html5shiv/html5shiv.min.js"></script>
    <![endif]-->
    <!--[if lt IE 10]>
    <script src="<?php echo $assets; ?>/global/vendor/media-match/media.match.min.js"></script>
    <script src="<?php echo $assets; ?>/global/vendor/respond/respond.min.js"></script>
    <![endif]-->
    <!-- Scripts -->
    <script src="<?php echo $assets; ?>/global/vendor/breakpoints/breakpoints.js"></script>
    <script>
        Breakpoints();
    </script>
</head>
<body class="animsition dashboard">
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->
<nav class="site-navbar navbar navbar-default navbar-inverse navbar-fixed-top navbar-mega"
     role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided"
                data-toggle="menubar">
            <span class="sr-only">Toggle navigation</span>
            <span class="hamburger-bar"></span>
        </button>
        <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse"
                data-toggle="collapse">
            <i class="icon md-more" aria-hidden="true"></i>
        </button>
        <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
            <a href="<?= base_url() ?>"><img class="navbar-brand-logo logo-filter" src="<?php echo $assets; ?>images/logo.png" title="Remark"></a>
<!--            <span class="navbar-brand-text hidden-xs-down"> Remark</span>-->
        </div>
        <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-search"
                data-toggle="collapse">
            <span class="sr-only">Toggle Search</span>
            <i class="icon md-search" aria-hidden="true"></i>
        </button>
    </div>
    <div class="navbar-container container-fluid">
        <!-- Navbar Collapse -->
        <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
            <!-- Navbar Toolbar -->
            <ul class="nav navbar-toolbar">
                <li class="nav-item hidden-float" id="toggleMenubar">
                    <a class="nav-link" data-toggle="menubar" href="#" role="button" id="toggleMenuButton">
                        <i class="icon hamburger hamburger-arrow-left">
                            <span class="sr-only">Toggle menubar</span>
                            <span class="hamburger-bar"></span>
                        </i>
                    </a>
                </li>
                <li class="nav-item hidden-sm-down" id="toggleFullscreen">
                    <a class="nav-link icon icon-fullscreen" data-toggle="fullscreen" href="#" role="button">
                        <span class="sr-only">Toggle fullscreen</span>
                    </a>
                </li>
            </ul>
            <!-- End Navbar Toolbar -->
            <!-- Navbar Toolbar Right -->
            <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
                <li class="nav-item dropdown">
                    <span class="nav-link" style="padding-bottom: 0; padding-top: 10px !important;">Hi <?= $user_display_name; ?></span>
                    <span class="nav-link" style="padding-bottom: 0; padding-top: 0;"><?= date('d-m-Y H:i:s') ?></span>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false"
                       data-animation="scale-up" role="button">
              <span class="avatar avatar-online">
                <img src="<?php echo $assets; ?>/global/portraits/no_avatar.jpg" alt="...">
                <i></i>
              </span>
                    </a>
                    <div class="dropdown-menu" role="menu">
                        <a class="dropdown-item" href="<?= base_url('auth/view_profile') ?>" role="menuitem"><i class="icon md-account"
                                                                                              aria-hidden="true"></i>
                            Profile</a>
                        <a class="dropdown-item" href="<?php echo base_url('auth/logout');?>" role="menuitem"><i class="icon md-power"
                                                                                              aria-hidden="true"></i>
                            Logout</a>
                    </div>
                </li>
<!--                <li class="nav-item dropdown">-->
<!--                    <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" title="Notifications"-->
<!--                       aria-expanded="false" data-animation="scale-up" role="button">-->
<!--                        <i class="icon md-notifications" aria-hidden="true"></i>-->
<!--                        <span class="tag tag-pill tag-danger up">5</span>-->
<!--                    </a>-->
<!--                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-media" role="menu">-->
<!--                        <div class="dropdown-menu-header">-->
<!--                            <h5>NOTIFICATIONS</h5>-->
<!--                            <span class="tag tag-round tag-danger">New 5</span>-->
<!--                        </div>-->
<!--                        <div class="list-group">-->
<!--                            <div data-role="container">-->
<!--                                <div data-role="content">-->
<!--                                    <a class="list-group-item dropdown-item" href="javascript:void(0)" role="menuitem">-->
<!--                                        <div class="media">-->
<!--                                            <div class="media-left p-r-10">-->
<!--                                                <i class="icon md-receipt bg-red-600 white icon-circle"-->
<!--                                                   aria-hidden="true"></i>-->
<!--                                            </div>-->
<!--                                            <div class="media-body">-->
<!--                                                <h6 class="media-heading">A new order has been placed</h6>-->
<!--                                                <time class="media-meta" datetime="2017-06-12T20:50:48+08:00">5 hours-->
<!--                                                    ago-->
<!--                                                </time>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </a>-->
<!--                                    <a class="list-group-item dropdown-item" href="javascript:void(0)" role="menuitem">-->
<!--                                        <div class="media">-->
<!--                                            <div class="media-left p-r-10">-->
<!--                                                <i class="icon md-account bg-green-600 white icon-circle"-->
<!--                                                   aria-hidden="true"></i>-->
<!--                                            </div>-->
<!--                                            <div class="media-body">-->
<!--                                                <h6 class="media-heading">Completed the task</h6>-->
<!--                                                <time class="media-meta" datetime="2017-06-11T18:29:20+08:00">2 days-->
<!--                                                    ago-->
<!--                                                </time>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </a>-->
<!--                                    <a class="list-group-item dropdown-item" href="javascript:void(0)" role="menuitem">-->
<!--                                        <div class="media">-->
<!--                                            <div class="media-left p-r-10">-->
<!--                                                <i class="icon md-settings bg-red-600 white icon-circle"-->
<!--                                                   aria-hidden="true"></i>-->
<!--                                            </div>-->
<!--                                            <div class="media-body">-->
<!--                                                <h6 class="media-heading">Settings updated</h6>-->
<!--                                                <time class="media-meta" datetime="2017-06-11T14:05:00+08:00">2 days-->
<!--                                                    ago-->
<!--                                                </time>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </a>-->
<!--                                    <a class="list-group-item dropdown-item" href="javascript:void(0)" role="menuitem">-->
<!--                                        <div class="media">-->
<!--                                            <div class="media-left p-r-10">-->
<!--                                                <i class="icon md-calendar bg-blue-600 white icon-circle"-->
<!--                                                   aria-hidden="true"></i>-->
<!--                                            </div>-->
<!--                                            <div class="media-body">-->
<!--                                                <h6 class="media-heading">Event started</h6>-->
<!--                                                <time class="media-meta" datetime="2017-06-10T13:50:18+08:00">3 days-->
<!--                                                    ago-->
<!--                                                </time>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </a>-->
<!--                                    <a class="list-group-item dropdown-item" href="javascript:void(0)" role="menuitem">-->
<!--                                        <div class="media">-->
<!--                                            <div class="media-left p-r-10">-->
<!--                                                <i class="icon md-comment bg-orange-600 white icon-circle"-->
<!--                                                   aria-hidden="true"></i>-->
<!--                                            </div>-->
<!--                                            <div class="media-body">-->
<!--                                                <h6 class="media-heading">Message received</h6>-->
<!--                                                <time class="media-meta" datetime="2017-06-10T12:34:48+08:00">3 days-->
<!--                                                    ago-->
<!--                                                </time>-->
<!--                                            </div>-->
<!--                                        </div>-->
<!--                                    </a>-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <div class="dropdown-menu-footer">-->
<!--                            <a class="dropdown-menu-footer-btn" href="javascript:void(0)" role="button">-->
<!--                                <i class="icon md-settings" aria-hidden="true"></i>-->
<!--                            </a>-->
<!--                            <a class="dropdown-item" href="javascript:void(0)" role="menuitem">-->
<!--                                All notifications-->
<!--                            </a>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </li>-->
<!--                <li class="nav-item" id="toggleChat">-->
<!--                    <a class="nav-link" data-toggle="site-sidebar" href="javascript:void(0)" title="Chat"-->
<!--                       data-url="--><?//= base_url('site-sidebar.tpl') ?><!--">-->
<!--                        <i class="icon md-comment" aria-hidden="true"></i>-->
<!--                    </a>-->
<!--                </li>-->
            </ul>
            <!-- End Navbar Toolbar Right -->
        </div>
        <!-- End Navbar Collapse -->
        <!-- Site Navbar Seach -->
        <div class="collapse navbar-search-overlap" id="site-navbar-search">
            <form role="search">
                <div class="form-group">
                    <div class="input-search">
                        <i class="input-search-icon md-search" aria-hidden="true"></i>
                        <input type="text" class="form-control" name="site-search" placeholder="Search...">
                        <button type="button" class="input-search-close icon md-close" data-target="#site-navbar-search"
                                data-toggle="collapse" aria-label="Close"></button>
                    </div>
                </div>
            </form>
        </div>
        <!-- End Site Navbar Seach -->
    </div>
</nav>
<div class="site-menubar">
    <div class="site-menubar-body">
        <div>
            <div>
                <ul class="site-menu" data-plugin="menu">
                    <li class="site-menu-item">
                        <a class="animsition-link" href="<?= base_url('') ?>">
                            <i class="site-menu-icon md-view-dashboard" aria-hidden="true"></i>
                            <span class="site-menu-title">Dashboard</span>
                        </a>
                    </li>
                    <li class="site-menu-item has-sub">
                        <a href="javascript:void(0)">
                            <i class="site-menu-icon md-view-list-alt" aria-hidden="true"></i>
                            <span class="site-menu-title">Data Masters</span>
                        </a>
                        <ul class="site-menu-sub">
                            <?php if ($CI->dt_ci_acl->checkAccess("sku|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('/sku') ?>">
                                    <span class="site-menu-title">SKU Master</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("category|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('/category') ?>">
                                    <span class="site-menu-title">Category Master</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("region|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('/region') ?>">
                                    <span class="site-menu-title">Region Master</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("node|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('/node') ?>">
                                    <span class="site-menu-title">Node Master</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("bufferNorm|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('/bufferNorm') ?>">
                                    <span class="site-menu-title">Buffer Norm Master</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("additionalIndent|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('/additionalIndent') ?>">
                                    <span class="site-menu-title">Additional Indent</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("csdSku|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('/csdSku') ?>">
                                    <span class="site-menu-title">CSD SKU</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("promo|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('/promo') ?>">
                                    <span class="site-menu-title">Promo Calendar</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("autouploads|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('/autouploads') ?>">
                                    <span class="site-menu-title">Uploads</span>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <li class="site-menu-item has-sub">
                        <a href="javascript:void(0)">
                            <i class="site-menu-icon md-chart" aria-hidden="true"></i>
                            <span class="site-menu-title">Reports</span>
                        </a>
                        <ul class="site-menu-sub">
                            <?php if ($CI->dt_ci_acl->checkAccess("report_replenishment|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_replenishment') ?>">
                                    <span class="site-menu-title">Replenishment Report</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_replenishment|csd")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_replenishment/csd') ?>">
                                    <span class="site-menu-title">CSD Replenishment Report</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_dispatch|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_dispatch') ?>">
                                    <span class="site-menu-title">Dispatch Summary</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_warehouseorder|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_warehouseorder') ?>">
                                    <span class="site-menu-title">Warehouse Orders</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_demand|sku")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_demand/sku') ?>">
                                    <span class="site-menu-title">Demand Comparison (SKU)</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_demand|node")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_demand/node') ?>">
                                    <span class="site-menu-title">Demand Comparison (Depot)</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_demandbifurcation|sku")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_demandbifurcation/sku') ?>">
                                    <span class="site-menu-title">Demand Bifurcation (SKU)</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_demandbifurcation|node")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_demandbifurcation/node') ?>">
                                    <span class="site-menu-title">Demand Bifurcation (Depot)</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_supplyshortage|sku")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_supplyshortage/sku') ?>">
                                    <span class="site-menu-title">Supply Node Shortage (SKU)</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_supplyshortage|node")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_supplyshortage/node') ?>">
                                    <span class="site-menu-title">Supply Node Shortage (Depot)</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_clgsalesbs|sku")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_clgsalesbs/sku') ?>">
                                    <span class="site-menu-title">Closing Vs Sales Vs BS (SKU)</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_clgsalesbs|node")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_clgsalesbs/node') ?>">
                                    <span class="site-menu-title">Closing Vs Sales Vs BS (Depot)</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_buffernormsales|sku")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_buffernormsales/sku') ?>">
                                    <span class="site-menu-title">BS Vs Sales (SKU)</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_distoverview|index")) { ?>

                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_distoverview/index') ?>">
                                    <span class="site-menu-title">Distribution Overview</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_distoverview|primaryNode")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_distoverview/primaryNode') ?>">
                                    <span class="site-menu-title">Distribution Overview (Primary Node wise)</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_distplan|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_distplan/index') ?>">
                                    <span class="site-menu-title">Distribution Planning</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php /*if ($CI->dt_ci_acl->checkAccess("report_intransitbufferzone|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_intransitbufferzone') ?>">
                                    <span class="site-menu-title">Intransit Buffer Zone</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_buffertrend|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_buffertrend') ?>">
                                    <span class="site-menu-title">Buffer Trends</span>
                                </a>
                            </li>
                            <?php }*/ ?>




                            <?php if ($CI->dt_ci_acl->checkAccess("report_intransitbufferzone|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_intransitbufferzone') ?>">
                                    <span class="site-menu-title">Intransit Buffer Zone</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_closingbufferzone|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_closingbufferzone') ?>">
                                    <span class="site-menu-title">Closing Buffer Zone</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_intransitbufferzonetrend|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_intransitbufferzonetrend') ?>">
                                    <span class="site-menu-title">Intransit Buffer Zone Trend</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_closingbufferzonetrend|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_closingbufferzonetrend') ?>">
                                    <span class="site-menu-title">Closing Buffer Zone Trend</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_availabilityreport|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_availabilityreport') ?>">
                                    <span class="site-menu-title">Availability Report</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_availabilityclosingreport|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_availabilityclosingreport') ?>">
                                    <span class="site-menu-title">Availability Closing Report</span>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if ($CI->dt_ci_acl->checkAccess("report_buffertrend|index")) { ?>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('report_buffertrend') ?>">
                                    <span class="site-menu-title">Buffer Trends</span>
                                </a>
                            </li>
                            <?php } ?>

                        </ul>
                    </li>
                    <?php if ($CI->dt_ci_acl->checkAdmin()) { ?>
                    <li class="site-menu-item has-sub">
                        <a href="javascript:void(0)">
                            <i class="site-menu-icon fa fa-user-secret" aria-hidden="true"></i>
                            <span class="site-menu-title">User Access</span>
                        </a>
                        <ul class="site-menu-sub">
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('/auth'); ?>">
                                    <span class="site-menu-title">Manage Users</span>
                                </a>
                            </li>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('/auth/manage_groups'); ?>">
                                    <span class="site-menu-title">Manage Groups</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <?php if ($CI->dt_ci_acl->checkAdmin()) { ?>
                    <li class="site-menu-item has-sub">
                        <a href="javascript:void(0)">
                            <i class="site-menu-icon fa fa-gear" aria-hidden="true"></i>
                            <span class="site-menu-title">Settings</span>
                        </a>
                        <ul class="site-menu-sub">
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('/settings/bufferZoneSettings/closingStock'); ?>">
                                    <span class="site-menu-title">Buffer Zone (Closing Stock)</span>
                                </a>
                            </li>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('/settings/bufferZoneSettings/intransitStock'); ?>">
                                    <span class="site-menu-title">Buffer Zone (In-transit)</span>
                                </a>
                            </li>
                            <li class="site-menu-item">
                                <a class="animsition-link" href="<?= base_url('/settings/replenishmentQty'); ?>">
                                    <span class="site-menu-title">Replenishment Quantity</span>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Core  -->
<script src="<?php echo $assets; ?>/global/vendor/babel-external-helpers/babel-external-helpers.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/jquery/jquery.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/tether/tether.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/bootstrap/bootstrap.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/animsition/animsition.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/mousewheel/jquery.mousewheel.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/asscrollbar/jquery-asScrollbar.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/asscrollable/jquery-asScrollable.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/ashoverscroll/jquery-asHoverScroll.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/waves/waves.js"></script>
<!-- Plugins -->
<script src="<?php echo $assets; ?>/global/vendor/switchery/switchery.min.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/intro-js/intro.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/screenfull/screenfull.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/slidepanel/jquery-slidePanel.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/chartist/chartist.min.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.min.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/jvectormap/jquery-jvectormap.min.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/jvectormap/maps/jquery-jvectormap-world-mill-en.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/matchheight/jquery.matchHeight-min.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/peity/jquery.peity.min.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/datatables/jquery.dataTables.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/datatables-fixedheader/dataTables.fixedHeader.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/datatables-responsive/dataTables.responsive.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/datatables-tabletools/dataTables.tableTools.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/jstree/jstree.js"></script>

<!-- Scripts -->
<script src="<?php echo $assets; ?>/global/js/State.js"></script>
<script src="<?php echo $assets; ?>/global/js/Component.js"></script>
<script src="<?php echo $assets; ?>/global/js/Plugin.js"></script>
<script src="<?php echo $assets; ?>/global/js/Base.js"></script>
<script src="<?php echo $assets; ?>/global/js/Config.js"></script>
<script src="<?php echo $assets; ?>js/Section/Menubar.js"></script>
<script src="<?php echo $assets; ?>js/Section/Sidebar.js"></script>
<script src="<?php echo $assets; ?>js/Section/PageAside.js"></script>
<script src="<?php echo $assets; ?>js/Plugin/menu.js"></script>
<!-- Config -->
<script src="<?php echo $assets; ?>/global/js/config/colors.js"></script>
<script src="<?php echo $assets; ?>js/config/tour.js"></script>
<script>
    Config.set('assets', '<?php echo $assets; ?>');
</script>
<!-- Page -->
<script src="<?php echo $assets; ?>js/Site.js"></script>
<script src="<?php echo $assets; ?>js/custom.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/chart-js/Chart.js"></script>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script src="<?php echo $assets; ?>/global/js/Plugin/asscrollable.js"></script>
<script src="<?php echo $assets; ?>/global/js/Plugin/slidepanel.js"></script>
<script src="<?php echo $assets; ?>/global/js/Plugin/switchery.js"></script>
<script src="<?php echo $assets; ?>/global/js/Plugin/matchheight.js"></script>
<script src="<?php echo $assets; ?>/global/js/Plugin/jvectormap.js"></script>
<script src="<?php echo $assets; ?>/global/js/Plugin/peity.js"></script>
<!--<script src="--><?php //echo $assets; ?><!--examples/js/dashboard/v1.js"></script>-->

<script src="<?php echo $assets; ?>/examples/js/tables/datatable.js"></script>
<script src="<?php echo $assets; ?>/global/js/Plugin/datatables.js"></script>

<script src="<?php echo $assets; ?>/global/js/Plugin/jquery-placeholder.js"></script>
<script src="<?php echo $assets; ?>/global/js/Plugin/material.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/bootstrap-datepicker/bootstrap-datepicker.js"></script>
<script src="<?php echo $assets; ?>/global/js/Plugin/bootstrap-datepicker.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/select2/select2.full.min.js"></script>
<script src="<?php echo $assets; ?>/global/js/Plugin/select2.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/ladda/spin.min.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/ladda/ladda.min.js"></script>
<script src="<?php echo $assets; ?>/global/js/Plugin/ladda.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/jquery-validation/jquery.validate.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/jquery-validation/jquery.form.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/bootstrap-sweetalert/sweetalert.js"></script>
<script src="<?php echo $assets; ?>/global/js/Plugin/bootstrap-sweetalert.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/formvalidation/formValidation.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/formvalidation/framework/bootstrap4.min.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/velocity-js/velocity.min.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/dropify/dropify.min.js"></script>
<script src="<?php echo $assets; ?>/global/js/Plugin/dropify.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/dropzonejs/dropzone.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/toastr/toastr.js"></script>
<script src="<?php echo $assets; ?>/global/js/Plugin/toastr.js"></script>

<script src="<?php echo $assets; ?>/global/js/Plugin/panel.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/moment/moment.min.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/daterangepicker/daterangepicker.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/bootstrap-touchspin/bootstrap-touchspin.min.js"></script>
<script src="<?php echo $assets; ?>/global/js/Plugin/bootstrap-touchspin.js"></script>


<script src="<?php echo $assets; ?>/global/vendor/datatable-buttons/dataTables.buttons.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/datatable-buttons/jszip.min.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/datatable-buttons/pdfmake.min.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/datatable-buttons/buttons.html5.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/datatable-buttons/vfs_fonts.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/datatable-buttons/buttons.print.min.js"></script>
<script src="<?php echo $assets; ?>/global/vendor/datatable-buttons/buttons.colVis.min.js"></script>

<script src="<?php echo $assets; ?>/global/vendor/datatable-colreorder/dataTables.colReorder.min.js"></script>

<!--<script src="--><?php //echo $assets; ?><!--/global/vendor/datatables-colvis/dataTables.colVis.min.js"></script>-->


<!-- Extra JS-->
<?php
if(isset($extra_js)){
    foreach ($extra_js as $js){
        echo '<script src="'.$assets.$js.'"></script>';
    }
}
?>

<!-- body -->
<?php echo $body; ?>
<!-- body ends -->

<!-- Footer -->
<footer class="site-footer">
    <div class="site-footer-legal">© <?php echo date('Y'); ?> <a href="http://www.syncoreindia.com/">SynCore Consulting</a></div>
    <div class="site-footer-right">
        Developed & Manged by <a href="#" target="_blank">Samaj</a>
    </div>
</footer>
</body>
</html>