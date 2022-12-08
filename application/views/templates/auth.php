<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= SITE_NAME ?></title>

<!--    favicon ICON-->
<!--    <link rel="icon" href="--><?//= $assets ?><!--../../uploads/logo.png" type="image/png" sizes="16x16">-->

    <!-- Global stylesheets -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="<?= $assets ?>css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css">
	<link href="<?= $assets ?>css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="<?= $assets ?>css/style.css" rel="stylesheet" type="text/css">
	<link href="<?= $assets ?>css/lnr-icon.css" rel="stylesheet" type="text/css">
	<link href="<?= $assets ?>css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- /global stylesheets -->

    <!-- Core JS files -->
    <script type="text/javascript" src="<?= $assets ?>js/plugins/loaders/pace.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>js/core/libraries/jquery.min.js"></script>
    <script type="text/javascript" src="<?= $assets ?>js/core/libraries/bootstrap.min.js"></script>

    <!-- /core JS files -->

    <!-- Theme JS files -->
    <script type="text/javascript" src="<?= $assets ?>js/plugins/ui/ripple.min.js"></script>
    <!-- /theme JS files -->


</head>
<script>
    $.ajaxSetup({
        data: { <?= $this->security->get_csrf_token_name() ?>:'<?= $this->security->get_csrf_hash() ?>'
    }
    });
</script>

<body class="login-container">

<!-- Main navbar -->
<div class="navbar navbar-inverse">
    <div class="navbar-header">

        <ul class="nav navbar-nav visible-xs-block">
            <li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li>
            <li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
        </ul>
    </div>

    <div class="navbar-collapse collapse " id="navbar-mobile">


    </div>
</div>
<!-- /main navbar -->

<!-- Page container -->
<div class="col-xl-9 col-lg-8 col-md-12">
	<div class="content  ">
		<?php echo $body; ?>
<!--		<div class="footer text-muted">-->
<!--			&copy; --><?php //echo date('Y'); ?><!-- <a href="http://alitainfotech.com/?ref=tre" target="_blank">Alita Infotech </a>-->
<!--		</div>-->
	</div>
</div>
<!-- /page container -->


<?php
if(isset($extra_js)){
    foreach ($extra_js as $js){
        echo '<script src="'.$assets.$js.'"></script>';
        echo "\n";
    }
}
?>


</body>
<script>
    function Select2Init() {
        $('.select').each(function () {
            var select = $(this);
            $("#" + select.attr('id')).select2({}).on('change.select2', function () {
                if ($("#" + select.attr('id')).valid()) {
                    $("#" + select.attr('id')).removeClass('invalid').addClass('success');
                }
            });
        });
    }
</script>
</html>
