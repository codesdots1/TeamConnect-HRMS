<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>TRE</title>

<!--    <link rel="icon" href="--><?//= $assets ?><!--../../uploads/logo.png" type="image/png" sizes="16x16">-->
    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
          type="text/css">
    <link href="<?= $assets ?>/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
    <link href="<?= $assets ?>/css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
    <link href="<?= $assets ?>/css/bootstrap.css" rel="stylesheet" type="text/css">
    <link href="<?= $assets ?>/css/core.css" rel="stylesheet" type="text/css">
    <link href="<?= $assets ?>/css/components.css" rel="stylesheet" type="text/css">
    <link href="<?= $assets ?>/css/colors.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->


    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <!-- Extra Css-->
    <?php
    if (isset($extra_css)) {
        foreach ($extra_css as $css) {
            echo '<link rel="stylesheet" href="' . $assets . $css . '">';
        }
    }
    ?>

    <!-- Core JS files -->
    <script type="text/javascript" src="<?= $assets ?>/js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>/js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>/js/core/libraries/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>/js/plugins/loaders/blockui.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>/js/plugins/ui/nicescroll.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>/js/plugins/ui/drilldown.js"></script>
    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="<?= $assets ?>/js/plugins/visualization/d3/d3.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>/js/plugins/visualization/d3/d3_tooltip.js"></script>
    <script type="text/javascript" src="<?= $assets ?>/js/plugins/forms/styling/switchery.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>/js/plugins/forms/styling/uniform.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
    <script type="text/javascript" src="<?= $assets ?>/js/plugins/ui/moment/moment.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>/js/plugins/pickers/daterangepicker.js"></script>
    <script type="text/javascript" src="<?= $assets ?>/js/plugins/forms/validation/validate.min.js"></script>

    <script type="text/javascript" src="<?= $assets ?>/js/core/app.js"></script>
    <!--    <script type="text/javascript" src="--><? //= $assets ?><!--/js/pages/dashboard.js"></script>-->

    <script type="text/javascript" src="<?= $assets ?>/js/plugins/ui/ripple.min.js"></script>
    <!-- /theme JS files -->

    <?php
    if (isset($extra_js)) {
        foreach ($extra_js as $js) {
            echo '<script src="' . $assets . $js . '"></script>';
        }
    }
    ?>
    <script>
        $.ajaxSetup({
            data: { <?= $this->security->get_csrf_token_name() ?>:
        '<?= $this->security->get_csrf_hash() ?>'
        }
        });



    </script>

</head>

<body>

<!-- Main navbar -->
<div class="navbar navbar-inverse bg-indigo">
    <div class="navbar-header">
        <a class="navbar-brand" href="<?= base_url("Dashboard"); ?>">TRE</a>

        <ul class="nav navbar-nav pull-right visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse" id="navbar-mobile">


        <ul class="nav navbar-nav navbar-right">


            <li class="dropdown dropdown-user">
                <a class="dropdown-toggle" data-toggle="dropdown">
                    <!--                    <img src="--><? //= $assets ?><!--/images/placeholder.jpg" alt="">-->
                    <span><?= ucwords($user_display_name); ?></span>
                    <span></span>
                    <i class="caret"></i>
                </a>

                <ul class="dropdown-menu dropdown-menu-right">
                    <!--                    <li><a href="#"><i class="icon-user-plus"></i> My profile</a></li>-->
                    <!--                    <li class="divider"></li>-->
                    <li><a href="<?php echo base_url("Auth/change_password"); ?>"><i class="icon-cog5"></i> Change
                            Password</a></li>
                    <li><a href="<?php echo base_url('Auth/logout'); ?>"><i class="icon-switch2"></i> Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->

<!-- Second navbar -->
<div class="navbar navbar-default" id="navbar-second">
    <ul class="nav navbar-nav no-border visible-xs-block">
        <li><a class="text-center collapsed" data-toggle="collapse" data-target="#navbar-second-toggle"><i
                        class="icon-menu7"></i></a></li>
    </ul>

    <div class="navbar-collapse collapse" id="navbar-second-toggle">
        <ul class="nav navbar-nav navbar-nav-material">
            <?php
            $CI =& get_instance();
            ?>
            <li class="dropdown mega-menu mega-menu-wide">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-puzzle4 position-left"></i>
                    Masters <span class="caret"></span></a>

                <div class="dropdown-menu dropdown-content">
                    <div class="dropdown-content-body">
                        <div class="row">

                            <div class="col-md-3">
                                <span class="menu-heading underlined">Master</span>
                                <ul class="menu-list">

                                    <!-- blank li and ul means main menu active link automatic set -->
                                    <li>
                                        <ul>

                                        </ul>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined">Front End </span>
                                <ul class="menu-list">

                                    <li>

                                        <ul>

                                        </ul>
                                    </li>


                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined">Customer</span>
                                <ul class="menu-list">
                                </ul>
                            </div>
                            <div class="col-md-3">
                                <span class="menu-heading underlined">Others</span>
                                <ul class="menu-list">
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </li>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-coins"></i> <?= lang('index_currency') ?> <span class="caret"></span>
                </a>

                <ul class="dropdown-menu width-200">

                    <li class="dropdown-header"><?= lang('index_currency') ?></li>

                    <li class="dropdown-submenu">
                        <?php
                        //Currency
                        if ($CI->dt_ci_acl->checkAccess("Currency|index")) {
                            echo '<li ' . $CI->dt_ci_acl->getActiveMenu("Currency/index") . '><a  href="' . base_url() . 'Currency/index"><i class="icon-eye"></i><span class="sidebar-mini-hide">'. lang('index_currency') .'</span></a></li>';
                        }
                        if ($CI->dt_ci_acl->checkAccess("Currency|add_edit")) {
                            echo '<li  ' . $CI->dt_ci_acl->getActiveMenu("Currency/add_edit") . ' ' . $CI->dt_ci_acl->getActiveMenu("Currency/add_edit/" . $this->uri->segment(3)) . '><a href="' . base_url() . 'Currency/add_edit"><i class="icon-plus3"></i><span class="sidebar-mini-hide">'. lang('index_add_currency') .'</span></a></li>';
                        }
                        ?>


                    </li>
                </ul>
            </li>



            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="icon-users"></i> User <span class="caret"></span>
                </a>

                <ul class="dropdown-menu width-200">

                    <li class="dropdown-header">User</li>

                    <li class="dropdown-submenu">
                        <?php
                        //User
                        if ($CI->dt_ci_acl->checkAccess("Auth|index")) {
                            echo '<li ' . $CI->dt_ci_acl->getActiveMenu("Auth/index") . '><a  href="' . base_url() . 'Auth/index"><i class="icon-eye"></i><span class="sidebar-mini-hide">User</span></a></li>';
                        }
                        if ($CI->dt_ci_acl->checkAccess("Auth|edit_user")) {
                            echo '<li ' . $CI->dt_ci_acl->getActiveMenu("Auth/edit_user") . ' ' . $CI->dt_ci_acl->getActiveMenu("auth/edit_user/" . $this->uri->segment(3)) . '><a  href="' . base_url() . 'Auth/edit_user"><i class="icon-plus3"></i><span class="sidebar-mini-hide">Add User</span></a></li>';
                        }

                        //Group
                        if ($CI->dt_ci_acl->checkAccess("Auth|manage_groups")) {
                            echo '<li ' . $CI->dt_ci_acl->getActiveMenu("Auth/manage_groups") . '><a  href="' . base_url() . 'Auth/manage_groups"><i class="icon-eye"></i><span class="sidebar-mini-hide">Group</span></a></li>';
                        }
                        if ($CI->dt_ci_acl->checkAccess("Auth|edit_group")) {
                            echo '<li ' . $CI->dt_ci_acl->getActiveMenu("Auth/edit_group") . ' ' . $CI->dt_ci_acl->getActiveMenu("auth/edit_group/" . $this->uri->segment(3)) . '><a  href="' . base_url() . 'Auth/edit_group"><i class="icon-plus3"></i><span class="sidebar-mini-hide">Add Group</span></a></li>';
                        }
                        ?>
                    </li>
                </ul>
            </li>

        </ul>

    </div>
</div>




<!-- /second navbar -->

<!-- Page container -->
<div class="page-container">

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="content-wrapper">

            <?php echo $body; ?>

        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

</div>
<!-- /page container -->

<!-- Footer -->
<div class="footer text-muted">
    &copy; <?php echo date('Y');?> <a href="#" target="_blank">Samaj</a>
</div>
<!-- /footer -->

</body>

<script>
    var dt_DataTable;
</script>
</html>
