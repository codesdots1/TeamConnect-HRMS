<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= SITE_NAME ?></title>

	<!--    <link rel="icon" href="--><? //= $assets ?><!--../../uploads/logo.png" type="image/png" sizes="16x16">-->
	<!-- Global stylesheets -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet"
		  type="text/css">

	<link href="<?= $assets ?>css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?= $assets ?>css/icons/fontawesome/styles.min.css" rel="stylesheet" type="text/css">
	<link href="<?= $assets ?>css/components.css" rel="stylesheet" type="text/css">
	<link href="<?= $assets ?>css/components.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<link href="<?= $assets ?>css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="<?= $assets ?>css/bootstrap.css" rel="stylesheet" type="text/css">

	<!-- Linearicon Font -->
	<link rel="stylesheet" href="<?= $assets ?>css/lnr-icon.css">

	<!-- Fontawesome CSS -->
	<link rel="stylesheet" href="<?= $assets ?>css/font-awesome.min.css">

	<link href="<?= $assets; ?>global/vendor/dropzonejs/dropzone.css" rel="stylesheet" type="text/css">


	<!-- Select2 CSS -->


	<!-- Tagsinput CSS -->
	<link rel="stylesheet" href="<?= $assets ?>js/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css" type="text/css">
	<script src="https://kit.fontawesome.com/821a305eea.js" crossorigin="anonymous"></script>

	<!-- Custom CSS -->
	<link rel="stylesheet" href="<?= $assets ?>css/style.css">

	<script src="https://kit.fontawesome.com/821a305eea.js" crossorigin="anonymous"></script>

	<!--/New Theme link-->

	<style>
		/*            .pageTitle {
						padding: 0px 25px 20px 0 !important;
					}

					.odd-color {
						background-color: #ECEFF1 !important;
					}

					.modal-open .ui-datepicker {
						z-index: 2000 !important
					}*/
		/* Chrome, Safari, Edge, Opera */
		input::-webkit-outer-spin-button,
		input::-webkit-inner-spin-button {
			-webkit-appearance: none;
			margin: 0;
		}

		/* Firefox */
		input[type=number] {
			-moz-appearance: textfield;
		}

		.modal-backdrop {
			z-index: 0;
		}

		.datatable-footer {
			width: 100%;
		}

	</style>

	<!-- Extra Css-->
	<?php
	if (isset($extra_css)) {
		foreach ($extra_css as $css) {
			echo '<link rel="stylesheet" href="' . $assets . $css . '">';
		}
	}
	?>

	<!--Core JS files-->
	<script src="<?= $assets ?>js/Chart.min.js"></script>

	<!-- Sticky sidebar JS -->


	<!-- Custom Js -->
	<!--	<script src="--><? //= $assets ?><!--js/script.js"></script>-->
	<script type="text/javascript" src="<?= $assets; ?>global/vendor/dropzonejs/dropzone.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/plugins/loaders/pace.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/core/libraries/jquery.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/core/libraries/bootstrap.min.js"></script>
	<!--/core JS files-->

	<!--Theme JS files-->
	<script type="text/javascript" src="<?= $assets ?>js/core/libraries/jquery_ui/core.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/plugins/forms/styling/switchery.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/plugins/forms/validation/validate.min.js"></script>

	<script type="text/javascript" src="<?= $assets ?>js/plugins/ui/ripple.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/plugins/buttons/spin.min.js"></script>
	<script type="text/javascript" src="<?= $assets ?>js/plugins/buttons/ladda.min.js"></script>


	<?php if (isset($datePicker)) { ?>
		<!--		<script type="text/javascript" src="--><? //= $assets ?><!--js/plugins/pickers/pickadate/picker.js"></script>-->
		<!--		<script type="text/javascript" src="--><? //= $assets ?><!--js/plugins/notifications/jgrowl.min.js"></script>-->
		<!---->
		<!--		<script type="text/javascript" src="--><? //= $assets ?><!--js/plugins/ui/moment/moment.min.js"></script>-->
		<!--		<script type="text/javascript" src="--><? //= $assets ?><!--js/plugins/pickers/daterangepicker.js"></script>-->
		<!--		<script type="text/javascript" src="--><? //= $assets ?><!--js/plugins/pickers/anytime.min.js"></script>-->
		<!--		<script type="text/javascript" src="--><? //= $assets ?><!--js/plugins/pickers/pickadate/picker.js"></script>-->
		<!--		<script type="text/javascript" src="--><? //= $assets ?><!--js/plugins/pickers/pickadate/picker.date.js"></script>-->
		<!--		<script type="text/javascript" src="--><? //= $assets ?><!--js/plugins/pickers/pickadate/picker.time.js"></script>-->
		<!--		<script type="text/javascript" src="--><? //= $assets ?><!--js/pages/picker_date.js"></script>-->
	<?php } ?>


	<!-- /theme JS files -->


	<?php if (isset($datePicker)) { ?>
		<script type="text/javascript" src="<?= $assets ?>js/plugins/pickers/pickadate/picker.js"></script>
		<script type="text/javascript" src="<?= $assets ?>js/plugins/notifications/jgrowl.min.js"></script>

		<script type="text/javascript" src="<?= $assets ?>js/plugins/ui/moment/moment.min.js"></script>
		<script type="text/javascript" src="<?= $assets ?>js/plugins/pickers/daterangepicker.js"></script>
		<script type="text/javascript" src="<?= $assets ?>js/plugins/pickers/anytime.min.js"></script>
		<script type="text/javascript" src="<?= $assets ?>js/plugins/pickers/pickadate/picker.js"></script>
		<script type="text/javascript" src="<?= $assets ?>js/plugins/pickers/pickadate/picker.date.js"></script>
		<script type="text/javascript" src="<?= $assets ?>js/plugins/pickers/pickadate/picker.time.js"></script>
		<script type="text/javascript" src="<?= $assets ?>js/pages/picker_date.js"></script>
	<?php } ?>


	<script>
		$.ajaxSetup({
			data: {<?= $this->security->get_csrf_token_name() ?>:
		'<?= $this->security->get_csrf_hash() ?>'
		}
		})
		;

	</script>

</head>


<body>
<?php
$CI = &get_instance();
$first_name = $this->session->userdata('first_name');
$last_name = $this->session->userdata('last_name');
$name = ucfirst($first_name) . ' ' . ucfirst($last_name);
$role_id = $this->session->userdata('role_id');
?>
<!-- Main navbar -->
<div class="inner-wrapper">
	<!-- Loader -->
	<!--	<div id="loader-wrapper">-->
	<!--		<div class="loader">-->
	<!--			<div class="dot"></div>-->
	<!--			<div class="dot"></div>-->
	<!--			<div class="dot"></div>-->
	<!--			<div class="dot"></div>-->
	<!--			<div class="dot"></div>-->
	<!--		</div>-->
	<!--	</div>-->
	<header class="header">

		<!-- Top Header Section -->
		<div class="top-header-section">
			<div class="container-fluid">
				<div class="row align-items-center">
					<div class="col-lg-3 col-md-3 col-sm-3 col-6">
						<div class="logo my-3 my-sm-0">
							<a href="<?= base_url(); ?>">
								<img src="<?= $assets ?>images/logo.png" alt="logo image" class="img-fluid" width="100">
							</a>
						</div>
					</div>
					<div class="col-lg-9 col-md-9 col-sm-9 col-6 text-right">
						<div class="user-block d-none d-lg-block">
							<div class="row align-items-center">
								<div class="col-lg-12 col-md-12 col-sm-12">

									<!-- User notification-->
									<div class="user-notification-block align-right d-inline-block">
										<ul class="list-inline m-0">
											<li class="list-inline-item" data-toggle="tooltip" data-placement="top"
												title="" data-original-title="Apply Leave">
												<a href="<?= base_url('EmployeeLeaveType'); ?>"
												   class="font-23 menu-style text-white align-middle">
													<span class="lnr lnr-briefcase position-relative"></span>
												</a>
											</li>
										</ul>
									</div>

									<!-- user info-->
									<div class="user-info align-right dropdown d-inline-block header-dropdown">
										<a href="javascript:void(0)" data-toggle="dropdown"
										   class=" menu-style dropdown-toggle">
											<div class="user-avatar d-inline-block">
												<span style="color:white;"><?= $user_display_name; ?></span>
												<img src="<?= base_url() . $this->config->item('employee_image') . $employee_image ?>"
													 alt="user avatar" class="rounded-circle img-fluid" width="55">
											</div>
										</a>

										<!-- Notifications -->
										<div class="dropdown-menu notification-dropdown-menu shadow-lg border-0 p-3 m-0 dropdown-menu-right">
											<a class="dropdown-item p-2"
											   href="<?= base_url('Company/getCompanyDataListing/' . $employee_id . '/' . $company_id); ?>">
											<span class="media align-items-center">
												<span class="lnr lnr-briefcase mr-3"></span>
												<span class="media-body text-truncate">
													<span class="text-truncate">Compnay Profile</span>
												</span>
											</span>
											</a>
											<a class="dropdown-item p-2"
											   href="<?= base_url('Employee/getEmployeeDataListing/' . $employee_id); ?>">
											<span class="media align-items-center">
												<span class="lnr lnr-eye mr-3"></span>
												<span class="media-body text-truncate">
													<span class="text-truncate">View Profile</span>
												</span>
											</span>
											</a>
											<a class="dropdown-item p-2"
											   href="<?= base_url('Employee/manage/' . $employee_id); ?>">
											<span class="media align-items-center">
												<span class="lnr lnr-pencil mr-3"></span>
												<span class="media-body text-truncate">
													<span class="text-truncate">Edit Profile</span>
												</span>
											</span>
											</a>
											<a class="dropdown-item p-2"
											   href="<?= base_url('Auth/change_password'); ?>">
											<span class="media align-items-center">
												<span class="lnr lnr-lock mr-3"></span>
												<span class="media-body text-truncate">
													<span class="text-truncate">Change Password</span>
												</span>
											</span>
											</a>
											<a class="dropdown-item p-2" href="<?= base_url('Auth/logout'); ?>">
											<span class="media align-items-center">
												<span class="lnr lnr-power-switch mr-3"></span>
												<span class="media-body text-truncate">
													<span class="text-truncate">Logout</span>
												</span>
											</span>
											</a>
										</div>
										<!-- Notifications -->
									</div>
									<!-- /User info-->
								</div>
							</div>
						</div>

						<div class="d-block d-lg-none">
							<a href="javascript:void(0)">
								<span class="fa fa-bars fa-lg d-block display-5 text-white" style="margin-right:6px"
									  id="open_navSidebar"></span>
							</a>

							<!-- Offcanvas menu -->
							<div class="offcanvas-menu" id="offcanvas_menu">
								<span class="lnr lnr-cross float-left display-6 position-absolute t-1 l-1 text-white"
									  id="close_navSidebar"></span>
								<div class="user-info align-center bg-theme text-center">
									<a href="javascript:void(0)" class="d-block menu-style text-white">
										<div class="user-avatar d-inline-block mr-3">
											<img src="<?= base_url() . $this->config->item('employee_image') . $employee_image ?>"
												 alt="user avatar" class="rounded-circle img-fluid" width="55">
										</div>
									</a>
								</div>
								<hr>

								<div class="user-menu-items px-3 m-0">
									<a class="px-0 pb-2 pt-0" href="<?= base_url(); ?>">
									<span class="media align-items-center">
										<span class="lnr lnr-home mr-3"></span>
										<span class="media-body text-truncate text-left">
											<span class="text-truncate text-left">Dashboard</span>
										</span>
									</span>
									</a>

									<a class="p-2" href='<?= base_url('Employee'); ?>Employee'>
									<span class="media align-items-center">
										<span class="lnr lnr-users mr-3"></span>
										<span class="media-body text-truncate text-left">
											<span class="text-truncate text-left">Employees</span>
										</span>
									</span>
									</a>


									<a class="p-2" href="<?= base_url('Company'); ?>">
									<span class="media align-items-center">
										<span class="lnr lnr-apartment mr-3"></span>
										<span class="media-body text-truncate text-left">
											<span class="text-truncate text-left">Company</span>
										</span>
									</span>
									</a>

									<a class="p-2" href="<?= base_url('HolidayCalendar'); ?>">
									<span class="media align-items-center">
										<span class="lnr lnr-calendar-full mr-3"></span>
										<span class="media-body text-truncate text-left">
											<span class="text-truncate text-left">Calendar</span>
										</span>
									</span>
									</a>

									<a class="p-2" href="<?= base_url('EmployeeLeaveType'); ?>">
									<span class="media align-items-center">
										<span class="lnr lnr-briefcase mr-3"></span>
										<span class="media-body text-truncate text-left">
											<span class="text-truncate text-left">Leave</span>
										</span>
									</span>
									</a>

									<a class="p-2" href="<?= base_url('TimeSheet'); ?>">
									<span class="media align-items-center">
										<span class="lnr lnr-list mr-3"></span>
										<span class="media-body text-truncate text-left">
											<span class="text-truncate text-left">TimeSheet</span>
										</span>
									</span>
									</a>

									<a class="p-2" href="<?= base_url('Report/EmployeeAttendanceReport'); ?>">
									<span class="media align-items-center">
										<span class="lnr lnr-rocket mr-3"></span>
										<span class="media-body text-truncate text-left">
											<span class="text-truncate text-left">Reports</span>
										</span>
									</span>
									</a>

									<a class="p-2" href="<?= base_url('TeamManagement'); ?>">
									<span class="media align-items-center">
										<span class="lnr lnr-sync mr-3"></span>
										<span class="media-body text-truncate text-left">
											<span class="text-truncate text-left">Team Management</span>
										</span>
									</span>
									</a>

									<a class="p-2" href="<?= base_url('ProjectManagement'); ?>">
									<span class="media align-items-center">
										<span class="lnr lnr-laptop mr-3"></span>
										<span class="media-body text-truncate text-left">
											<span class="text-truncate text-left">Project Management</span>
										</span>
									</span>
									</a>

									<a class="p-2" href="<?= base_url('Payroll'); ?>">
									<span class="media align-items-center">
										<span class="lnr lnr-laptop-phone mr-3"></span>
										<span class="media-body text-truncate text-left">
											<span class="text-truncate text-left">Payroll</span>
										</span>
									</span>
									</a>

									<a class="p-2" href="<?= base_url('TaskManagement'); ?>">
									<span class="media align-items-center">
										<span class="lnr lnr-code mr-3"></span>
										<span class="media-body text-truncate text-left">
											<span class="text-truncate text-left">Task Management</span>
										</span>
									</span>
									</a>

									<a class="p-2" href="<?= base_url('Auth/logout'); ?>">
									<span class="media align-items-center">
										<span class="lnr lnr-power-switch mr-3"></span>
										<span class="media-body text-truncate text-left">
											<span class="text-truncate text-left">Logout</span>
										</span>
									</span>
									</a>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /Top Header Section -->

	</header>
	<div class="page-wrapper">
		<div class="container-fluid">
			<div class="row">
				<div class="col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
					<aside class="sidebar sidebar-user">
						<div class="row">
							<div class="col-12">
								<div class="card ctm-border-radius shadow-sm grow">
									<div class="card-body py-4">
										<div class="row">
											<div class="col-md-12 mr-auto text-left">
												<div class="custom-search input-group">
													<div class="custom-breadcrumb">
														<ol class="breadcrumb no-bg-color d-inline-block p-0 m-0 mb-2">
															<li class="breadcrumb-item d-inline-block"><a
																		href="<?= base_url(); ?>"
																		class="text-dark">Home</a></li>
															<?php if ($this->uri->segment(1) != '' && $this->uri->segment(2) == '') { ?>
																<li class="breadcrumb-item d-inline-block active"><?= ucwords(str_replace('_', ' ', $this->uri->segment(1))); ?></li>
															<?php } else if ($this->uri->segment(1) == '') { ?>
																<li class="breadcrumb-item d-inline-block active">
																	Dashboard
																</li>
															<?php } ?>
														</ol>
														<?php if ($this->uri->segment(1) == '') { ?>
															<h4 class="text-dark"><?= $user_display_name; ?>
																Dashboard</h4>
														<?php } else { ?>
															<h3 class="text-dark"><?= $name . " " . ucwords(str_replace('_', ' ', $this->uri->segment(1))); ?></h3>
														<?php } ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php $controller = $this->router->fetch_class();
						if (strtolower($controller) == 'dashboard') { ?>
							<div class="user-card card shadow-sm bg-white text-center ctm-border-radius grow">
								<div class="user-info card-body">
									<div class="user-avatar mb-4">
										<img src="<?= base_url() . $this->config->item('employee_image') . $employee_image ?>"
											 alt="User Avatar" class="img-fluid rounded-circle" width="100">
									</div>
									<div class="user-details">
										<h3 style="color: black;"><b>Welcome <?= $user_display_name; ?></b></h3>
										<h4><p style="color: black;"><?php echo date("D") . ", " . date('d M Y'); ?></p>
										</h4>
									</div>
								</div>
							</div>
						<?php } ?>
						<!-- Sidebar -->
						<div class="sidebar-wrapper d-lg-block d-md-none d-none">
							<div class="card ctm-border-radius shadow-sm border-none grow">
								<div class="card-body">
									<div class="row no-gutters">
										<div class="col-12 align-items-center text-center">
											<?php
											$controller = $this->router->fetch_class();
											$action = $this->router->fetch_method();
											$segment = $CI->uri->segment(3);
											$class = '';

											if (strtolower($controller) == 'dashboard')
												$class = 'active';
											?>
											<a href="<?= base_url(); ?>"
											   class=" <?= $class ?> text-white active p-4 first-slider-btn second-slider-btn ctm-border-right ctm-border-left ctm-border-top ">
												<span class="lnr lnr-home pr-0 pb-lg-2 font-23"></span>
												<span class="">Dashboard</span>
											</a>
										</div>

										<?php if(strtolower($role) == 'admin'){?>
										<div class="col-12 align-items-center shadow-none text-center">
											<a href="<?= base_url('Auth/manage_groups'); ?>"
											   class="text-dark p-4 ctm-border-right  ctm-border-left">
												<span class="lnr lnr-hand pr-0 pb-lg-2 font-23"></span>
												<span class="">Human Resources</span></a>
										</div>
										<?php } ?>

										<div class="col-6 align-items-center shadow-none text-center">
											<a href="<?= base_url('Employee'); ?>"
											   class="text-dark p-4 ctm-border-left ctm-border-top">
												<span class="lnr lnr-users pr-0 pb-lg-2 font-23"></span>
												<span class="">Employees Management</span></a>
										</div>


										<div class="col-6 align-items-center shadow-none text-center">
											<a href="<?= base_url('EmployeeAttendance'); ?>"
											   class="text-dark p-4 ctm-border-right  ctm-border-left">
												<span class="lnr lnr-book pr-0 pb-lg-2 font-23"></span>
												<span class="">Attendance Management</span></a>
										</div>

										<?php
										if ($CI->dt_ci_acl->checkAccess("Holiday|index")) {
											echo '<div class="col-6 align-items-center shadow-none text-center" ' .
													$CI->dt_ci_acl->getActiveMenu("Holiday/index") . ' ' .
													$CI->dt_ci_acl->getActiveMenu("Holiday") . '><a class="text-dark p-4 ctm-border-left"  href="' . base_url() . 'Holiday">
										<span class="lnr lnr-calendar-full pr-0 pb-lg-2 font-23"></span>
										<span class="">Calendar</span></a></div>';
										} ?>

										<div class="col-6 align-items-center shadow-none text-center">
											<a href="<?= base_url('EmployeeLeaveType'); ?>"
											   class="text-dark p-4 ctm-border-right ctm-border-left"><span
														class="lnr lnr-briefcase pr-0 pb-lg-2 font-23">
											</span><span class="">Leave</span></a>
										</div>
										<div class="col-6 align-items-center shadow-none text-center">
											<a href="<?= base_url('TimeSheet'); ?>"
											   class="text-dark p-4  ctm-border-left"><span
														class="lnr lnr-list pr-0 pb-lg-2 font-23"></span>
												<span class="">TimeSheet</span></a>
										</div>

										<?php if(strtolower($role)  != 'employee'){ ?>
										<?php
											if ($CI->dt_ci_acl->checkAccess("Holiday|index")) {
											echo '<div class="col-6 align-items-center shadow-none text-center" ' .
												$CI->dt_ci_acl->getActiveMenu("Report/EmployeeAttendanceReport") . ' ' .
												$CI->dt_ci_acl->getActiveMenu("Report/EmployeeAttendanceReport") . '>
												<a class="text-dark p-4 ctm-border-right ctm-border-left" href="' . base_url() . 'Report/EmployeeAttendanceReport">
												<span class="lnr lnr-rocket pr-0 pb-lg-2 font-23"></span>
												<span class="">Reports </span></a></div>';
										}?>
										<?php } ?>

										<div class="col-6 align-items-center shadow-none text-center">
											<a href="<?= base_url('TeamManagement'); ?>"
											   class="text-dark p-4 ctm-border-left"><span
														class="lnr lnr-users pr-0 pb-lg-2 font-23"></span>
												<span class="">Team</span></a>
										</div>

										<?php
										if ($CI->dt_ci_acl->checkAccess("Payroll|index")) {
											echo '<div class="col-6 align-items-center shadow-none text-center" ' .
													$CI->dt_ci_acl->getActiveMenu("Payroll") . ' ' .
													$CI->dt_ci_acl->getActiveMenu("Payroll/index"). '>
										<a class="text-dark p-4  ctm-border-right ctm-border-left"
										 href="' . base_url() . 'Payroll">
										 <span class="fa fa-credit-card pr-0 pb-lg-2 font-23"></span>
										 <span class="">' . lang('payroll') . '</span></a></div>';
										}
										?>

										<?php
										echo '<div class="col-6 align-items-center shadow-none text-center" ' .
												$CI->dt_ci_acl->getActiveMenu("ProjectManagement/index/".$this->uri->segment(3)) . ' '.
												$CI->dt_ci_acl->getActiveMenu("ProjectManagement/getProjectManagementListing/".$this->uri->segment(3)) .' '.
												$CI->dt_ci_acl->getActiveMenu("ProjectManagement/manage") . ' ' .
												$CI->dt_ci_acl->getActiveMenu("ProjectManagement/manage/" . $this->uri->segment(3)) . '>
										<a class="text-dark p-4 ctm-border-left ctm-border-top" href="' . base_url() . 'ProjectManagement/index/'.$employee_id.'">
										<span class="lnr lnr-users pr-0 pb-lg-2 font-23"></span><span class="">' . lang('project_management') . '
										</span></a></div>';
										?>


										<?php
										echo '<div class="col-6 align-items-center shadow-none text-center" ' .
												$CI->dt_ci_acl->getActiveMenu("TaskManagement/index/".$this->uri->segment(3)) . ' '.
												$CI->dt_ci_acl->getActiveMenu("TaskManagement/getTaskManagementListing/".$this->uri->segment(3)) .' '.
												$CI->dt_ci_acl->getActiveMenu("TaskManagement/manage") . ' ' .
												$CI->dt_ci_acl->getActiveMenu("TaskManagement/manage/" . $this->uri->segment(3)) . '>
										<a class="text-dark p-4 ctm-border-left ctm-border-top" href="' . base_url() . 'TaskManagement/index/'.$employee_id.'">
										<span class="lnr lnr-code pr-0 pb-lg-2 font-23"></span><span class="">' . lang('task_management') . '
										</span></a></div>';
										?>

										<div class="col-12 align-items-center shadow-none text-center">
											<a href="<?= base_url('Country'); ?>"
											   class="text-dark p-4 ctm-border-right  ctm-border-left">
												<span class="lnr lnr-cog pr-0 pb-lg-2 font-23"></span>
												<span class="">Settings</span></a>
										</div>

									</div>
								</div>
							</div>
						</div>
						<!-- /Sidebar -->
					</aside>
				</div>

				<!-- Page container -->
				<div class="col-xl-9 col-lg-8 col-md-12">
					<div class="content  ">
						<?php echo $body; ?>
						<!--					<div class="footer text-muted">-->
						<!--						&copy; -->
						<?php //echo date('Y'); ?><!-- <a href="http://alitainfotech.com/?ref=tre" target="_blank">Alita Infotech </a>-->
						<!--					</div>-->
					</div>
				</div>
				<!-- /page container -->
			</div>
		</div>
	</div>
	<!-- /main navbar -->
	<?php
	if (isset($extra_js)) {
	foreach ($extra_js as $js) {
	echo '<script type="text/javascript" src="' . $assets . $js . '"></script>';
	echo "\n";
	}
	}
	?>
</body>

<script>

	$(document).ready(function () {
		$('select').on("select2:close", function () {
			$(this).focus();
		});
	})

	var dt_DataTable;


	//var laddaSubmitBtn = Ladda.create(document.querySelector('#submit'));

	function laddaStart() {
		laddaSubmitBtn.start();
		$("#icon-hide").removeClass('icon-arrow-right8');
	}


	function laddaStop() {
		laddaSubmitBtn.stop();
		$("#icon-hide").addClass('icon-arrow-right8');
	}

	function DtSwitcheryKeyGen() {
		var switcherySettings =
				{
					color: '#455A64'
				};

		if (Array.prototype.forEach) {
			var elems = Array.prototype.slice.call(document.querySelectorAll('.dt_switchery'));
			elems.forEach(function (html) {
				var switchery = new Switchery(html, switcherySettings);
			});
		} else {
			var elems = document.querySelectorAll('.dt_switchery');
			for (var i = 0; i < elems.length; i++) {
				var switchery = new Switchery(elems[i], switcherySettings);
			}
		}
	}

	function SwitcheryKeyGen() {
		var switcherySettings =
				{
					color: '#455A64'
				};

		if (Array.prototype.forEach) {
			var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
			elems.forEach(function (html) {
				var switchery = new Switchery(html, switcherySettings);
			});
		} else {
			var elems = document.querySelectorAll('.switchery');
			for (var i = 0; i < elems.length; i++) {
				var switchery = new Switchery(elems[i], switcherySettings);
			}
		}
	}

	function CheckboxKeyGen(Type) {
		if (Type == 'checkAll') {
			$('#checkAll').prop('checked', false);
		}
		$(".styled").uniform({
			checkboxClass: 'checker'
		});
	}

	function RadioKeyGen() {
		$(".styledRadio").uniform({
			radioClass: 'choice'
		});
	}

	function FileKeyGen() {
		// Primary file input
		$(".file-styled-primary").uniform({
			fileButtonClass: 'action btn bg-blue'
		});
	}

	function CustomToolTip() {

		$('[data-popup=custom-tooltip]').tooltip({
			trigger: 'hover',
			template: '<div class="tooltip"><div class="bg-grey-800"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div></div>'
		});
	}

	var id = "";

	function startDateInit(id) {
		$(id).datepicker({
			dateFormat: "<?= DATE_FORMATE ?>",
			todayBtn: "linked",
			autoclose: true,
			todayHighlight: true,
			changeMonth: true,
			changeYear: true,
			//container: '#fiscalYearModal modal-body',
			onSelect: function (selected) {
				$("#end_date").datepicker("option", "minDate", selected)
			}
		}).on('changeDate', function () {
			// $(this).valid();  // triggers the validation test
			if ($(id).valid()) {
				$(id).removeClass('invalid').addClass('success');
			}
		});
	}


	function endDateInit(id) {
		$(id).datepicker({
			dateFormat: "<?= DATE_FORMATE ?>",
			todayBtn: "linked",
			autoclose: true,
			todayHighlight: true,
			changeMonth: true,
			changeYear: true,
			// container: '#fiscalYearModal modal-body',
			onSelect: function (selected) {
				//$("#start_date").datepicker("option", "maxDate", selected)
			}
		}).on('changeDate', function () {
			$(this).valid();  // triggers the validation test
		});
	}


	function dateInit(id) {
		$(id).datepicker({
			dateFormat: "<?= DATE_FORMATE ?>",
			todayBtn: "linked",
			autoclose: true,
			todayHighlight: true,
			maxDate: new Date(),
		}).on('changeDate', function () {
			// $(this).valid();  // triggers the validation test
			if ($(id).valid()) {
				$(id).removeClass('invalid').addClass('success');
			}

		});
	}

	function CountryStateCityDD(countryId = '', stateId = '', cityId = '') {
		$("#country_id").select2({
			ajax: {
				url: "<?= site_url('Country/getCountryDD') ?>",
				dataType: 'json',
				type: 'post',
				delay: 250,
				data: function (params) {
					return {
						filter_param: params.term || '', // search term
						countryIdActive: countryId,
						page: params.page || 1
					};
				},
				processResults: function (data, params) {
					return {
						results: data.result,
						pagination: {
							more: (data.page * 10) < data.totalRows

						}
					};
				},
				//cache: true
			},
			placeholder: 'Search For Your Country',
			escapeMarkup: function (markup) {
				return markup;
			}, // let our custom formatter work
			//minimumInputLength: 2,
		}).on('select2:select', function () {
			if ($("#" + $(this).attr('id')).valid()) {
				$("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
			}
			$('#state_id').val(null).trigger('change');
			$('#state_id-error').html("");
			$('#city_id').val(null).trigger('change');
			$('#city_id-error').html("");
		});


		$("#state_id").select2({
			ajax: {
				url: "<?= site_url('State/getStateDD') ?>",
				dataType: 'json',
				type: 'post',
				delay: 250,
				data: function (params) {
					return {
						filter_param: params.term || '', // search term
						countryId: $("#country_id").val(),
						stateIdActive: stateId,
						page: params.page || 1
					};
				},
				processResults: function (data, params) {
					// parse the results into the format expected by Select2
					// since we are using custom formatting functions we do not need to
					// alter the remote JSON data, except to indicate that infinite
					// scrolling can be used
//                    params.page = params.page || 1;

					return {
						results: data.result,
						pagination: {
							more: (data.page * 10) < data.totalRows

						}
					};
				},
				//cache: true
			},
			placeholder: 'Search For Your State',
			escapeMarkup: function (markup) {
				return markup;
			}, // let our custom formatter work
			//minimumInputLength: 2,
		}).on('select2:select', function () {
			if ($("#" + $(this).attr('id')).valid()) {
				$("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
			}
			$('#city_id').val(null).trigger('change');
			$('#city_id-error').html("");
		});


		$("#city_id").select2({
			ajax: {
				url: "<?= site_url('City/getCityDD') ?>",
				dataType: 'json',
				type: 'post',
				delay: 250,
				data: function (params) {
					return {
						filter_param: params.term || '', // search term
						stateId: $("#state_id").val(),
						cityIdActive: cityId,
						page: params.page || 1
					};
				},
				processResults: function (data, params) {
					// parse the results into the format expected by Select2
					// since we are using custom formatting functions we do not need to
					// alter the remote JSON data, except to indicate that infinite
					// scrolling can be used
//                    params.page = params.page || 1;

					return {
						results: data.result,
						pagination: {
							more: (data.page * 10) < data.totalRows

						}
					};
				},
				//cache: true
			},
			placeholder: 'Search For Your City',
			escapeMarkup: function (markup) {
				return markup;
			}, // let our custom formatter work
			//minimumInputLength: 2,


		}).on('select2:select', function () {
			if ($("#" + $(this).attr('id')).valid()) {
				$("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
			}
		});
	}

	$(".filter_data").on('click', function () {
		$("#submit_btn").val('true');
		dt_DataTable.draw();
	});

	$(".clear_filter").on('click', function () {
		DtFormClear('advanceFilter');
		$("#created_at_start_date").val('');
		$("#created_at_end_date").val('');
		dt_DataTable.draw();
	});


	function calculateTotal() {
		var all_exchange_rate = $.parseJSON($("#all_exchange_rate").text());
		if (all_exchange_rate != '') {
			var total = 0;
			var customer_currency_id = parseInt($(".customer_currency_id").val()) + 0;
			var company_currency_id = parseInt($(".company_currency_id").val()) + 0;
			var exchange_rate = parseFloat($(".conversion_rate").val());
			console.log(typeof exchange_rate);
			$(".item_total_amount").each(function () {

				var row = $(this);
				var quantity = parseFloat(row.parents("tr").find(".item_quantity").val());
				var rate = parseFloat(row.attr('data-original_rate'));

				var amount = parseFloat(row.val());
				var currency_id = parseFloat(row.attr('data-item_currency'));

				var found = false;

				quantity = isNaN(quantity) ? 0 : quantity;
				rate = isNaN(rate) ? 0 : rate;
				amount = isNaN(amount) ? 0 : amount;

				if (currency_id == company_currency_id) {
					amount = (rate * quantity).toFixed(2);
				} else {
					$.each(all_exchange_rate, function (index, value) {
						var exchange_rate = parseFloat(value.exchange_rate);
						if (value.from_currency == currency_id && value.to_currency == company_currency_id) {
							rate = (rate / parseFloat(value.exchange_rate)).toFixed(2); // convert rate to customer currency exchange rate
							amount = (rate * quantity).toFixed(2);
							found = true;
							return false;
						}
					});
					if (!found) {
						amount = (rate * quantity).toFixed(2);
					}
				}
				row.parents("tr").find("td:eq(2) input").val(rate); // set rate
				row.val(amount); // set total
				total += parseFloat(amount); // addition to grand total
			});

			$("#company_total").val(total.toFixed(2));
			$("#company_net_total").val(total.toFixed(2));

			var total_tax_amount = parseFloat(total);
			var tax_total = 0;
			$(".tax_rate").each(function () {
				var tax_rate = parseFloat($(this).val());
				var tax_amount = 0;
				if (tax_rate > 0) {
					tax_amount = ((total * tax_rate) / 100).toFixed(2);
					total_tax_amount = total_tax_amount + parseFloat(tax_amount);
					tax_total = tax_total + parseFloat(tax_amount);
					$(this).closest("tr").find('.tax_amount').val(tax_amount);
					$(this).closest("tr").find('.tax_total').val(total_tax_amount.toFixed(2));
				}
			});
			$("#base_total_taxes").val(tax_total.toFixed(2));
			$("#total_taxes").val((tax_total * exchange_rate).toFixed(2));

			total = total_tax_amount;

			var apply_discount_on = $("#apply_discount_on").val();
			var additional_discount_percentage = parseFloat($("#additional_discount_percentage").val());
			var base_discount_amount = 0;
			var discount_amount = 0;

			if (additional_discount_percentage > 0) {
				var amount = parseFloat((apply_discount_on == 'grand total') ? total : (apply_discount_on == 'net total' ? $("#company_total").val() : 0));
				if (amount > 0) {
					var disc = (amount * additional_discount_percentage) / 100;
					base_discount_amount = disc.toFixed(2);
					discount_amount = (base_discount_amount * exchange_rate).toFixed(2);
				}
			}
			total = total - base_discount_amount;
			var round_total = Math.ceil(total);
			var round_adjustment = (round_total - total).toFixed(2);

			$("#base_discount_amount").val(base_discount_amount);
			$("#discount_amount").val(discount_amount);

			$("#base_grand_total").val(total.toFixed(2));
			$("#base_rounding_adjustment").val(round_adjustment);
			$("#base_rounded_total").val(round_total);

			var customer_total = (parseFloat($("#company_total").val()) * exchange_rate).toFixed(2);
			$("#customer_total").val(customer_total);
			$("#customer_net_total").val(customer_total);

			var grand_total = (total * exchange_rate).toFixed(2);
			round_adjustment = (round_adjustment * exchange_rate).toFixed(2);
			round_total = (round_total * exchange_rate).toFixed(2);

			$("#grand_total").val(grand_total);
			$("#rounding_adjustment").val(round_adjustment);
			$("#rounded_total").val(round_total);
		}

	}

	function fetchAllCurrencyExchange(for_data, currency_id) {

		$.ajax({
			type: "POST",
			url: "<?php echo site_url('CurrencyExchange/getExchangeRate');?>",
			dataType: "json",
			data: {currency_id: currency_id},
			success: function (data) {
				$("#" + for_data + "_exchange_rate").text(JSON.stringify(data));
				$("." + for_data + "_currency_id").val(currency_id);
				getConversionRate();
			}
		});
	}

	function getConversionRate() {
		var customer_currency_id = parseInt($(".customer_currency_id").val());
		var company_currency_id = parseInt($(".company_currency_id").val());
		if (customer_currency_id == company_currency_id) {
			$(".conversion_rate").val(1);
		} else if (customer_currency_id > 0 && company_currency_id > 0) {
			var customer_exchange_rate = $.parseJSON($("#customer_exchange_rate").text());
			$.each(customer_exchange_rate, function (index, value) {
				if (value.from_currency == customer_currency_id && value.to_currency == company_currency_id) {
					$(".conversion_rate").val(value.exchange_rate);
					return false;
				}
			});
		}
		calculateTotal();
	}

	// supplier start
	function fetchAllCurrencyExchangeSupplier(for_data, currency_id) {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('CurrencyExchange/getExchangeRate');?>",
			dataType: "json",
			data: {currency_id: currency_id},
			success: function (data) {
				$("#" + for_data + "_exchange_rate").text(JSON.stringify(data));
				$("." + for_data + "_currency_id").val(currency_id);
				getConversionRateSupplier();
			}
		});
	}


	function getConversionRateSupplier() {
		var customer_currency_id = parseInt($("#currency_id").val());

		// console.log("customer_currency_id");
		//console.log(customer_currency_id);
		//naitik var customer_currency_id = parseInt($(".supplier_currency_id").val());
		var company_currency_id = parseInt($(".company_currency_id").val());

		//console.log("company_currency_id");
		//console.log(company_currency_id);

		if (customer_currency_id == company_currency_id) {
			$(".conversion_rate").val(1);
		} else if (customer_currency_id > 0 && company_currency_id > 0) {

			var customer_exchange_rate = $.parseJSON($("#supplier_exchange_rate").text());
			//  console.log("else if");
			// console.log(customer_exchange_rate);


			$.each(customer_exchange_rate, function (index, value) {
				// naitik if (value.from_currency == customer_currency_id && value.to_currency == company_currency_id) {
				if (value.from_currency == company_currency_id && value.to_currency == customer_currency_id) {
					$(".conversion_rate").val(value.exchange_rate);
					return false;
				}
			});
		}
		calculateTotalSupplier();
	}


	function calculateTotalSupplier() {

		//console.log("calculateTotalSupplier");

		var json = $("#all_exchange_rate").text();

		if (json != '') {

			var total = 0;

			var displaytotal = 0;

			var taxDisplayamount = 0;

			var customer_currency_id = parseInt($(".supplier_currency_id").val()) + 0;

			var company_currency_id = parseInt($(".company_currency_id").val()) + 0;


			var exchange_rate = parseFloat($(".conversion_rate").val()).toFixed(2);


			var all_exchange_rate = $.parseJSON($("#all_exchange_rate").text());


			$(".item_total_amount").each(function () {

				var row = $(this);

//                var quantity = parseFloat(row.parents("tr").find("td:eq(1) input").val());
				var quantity = parseFloat(row.parents("tr").find(".item_quantity").val());


				var rate = parseFloat(row.attr('data-original_rate'));


				var amount = parseFloat(row.val());


				var currency_id = parseFloat(row.attr('data-item_currency'));

				var found = false;

				quantity = isNaN(quantity) ? 0 : quantity;

				rate = isNaN(rate) ? 0 : rate;

				amount = isNaN(amount) ? 0 : amount;


				if (currency_id == company_currency_id) {
					itemDisplayRate = parseFloat(rate * exchange_rate);
					amount = (rate * quantity).toFixed(2);
					itemDisplayamount = (itemDisplayRate * quantity).toFixed(2);
				} else {
					$.each(all_exchange_rate, function (index, value) {
						//var exchange_rate = parseFloat(value.exchange_rate);
						if (value.from_currency == currency_id && value.to_currency == company_currency_id) {

							rate = (rate * parseFloat(value.exchange_rate)).toFixed(2); // convert rate to customer currency exchange rate


							itemDisplayRate = parseFloat(rate * exchange_rate);

							itemDisplayamount = (itemDisplayRate * quantity).toFixed(2);


							amount = (rate * quantity).toFixed(2);

							found = true;

							return false;
						}
					});

					if (!found) {
						itemDisplayRate = parseFloat(rate * exchange_rate);
						itemDisplayamount = (itemDisplayRate * quantity).toFixed(2);
						amount = (rate * quantity).toFixed(2);
					}
				}

//                row.parents("tr").find("td:eq(2) input").val(itemDisplayRate); // set rate
				row.parents("tr").find(".item_rate").val(itemDisplayRate); // set rate


				row.val(itemDisplayamount);   // set total
				total += parseFloat(amount);   // addition to grand total
				displaytotal += parseFloat(itemDisplayamount); // addition to grand total
			});


			$("#base_total").val(total.toFixed(2));
			$("#base_net_total").val(total.toFixed(2));

			var total_tax_amount = parseFloat(total);
			var taxDisplayamount = parseFloat(displaytotal);

			var tax_total = 0;

			$(".tax_rate").each(function () {
				var tax_rate = parseFloat($(this).val());
				var tax_amount = 0;
				if (tax_rate > 0) {
					tax_amount = ((total * tax_rate) / 100).toFixed(2);
					taxDisplayRate = parseFloat(tax_amount * exchange_rate);
					taxDisplayamount = taxDisplayamount + parseFloat(taxDisplayRate);
					total_tax_amount = total_tax_amount + parseFloat(tax_amount);
					tax_total = tax_total + parseFloat(tax_amount);
					$(this).closest("tr").find('.tax_amount').val(taxDisplayRate);
					$(this).closest("tr").find('.tax_total').val(taxDisplayamount.toFixed(2));
				}
			});


			$("#base_total_taxes_and_charges").val(tax_total.toFixed(2));
			$("#total_taxes_and_charges").val((tax_total * exchange_rate).toFixed(2));

			total = total_tax_amount;

			var apply_discount_on = $("#apply_discount_on").val();
			var additional_discount_percentage = parseFloat($("#additional_discount_percentage").val());
			var base_discount_amount = 0;
			var discount_amount = 0;

			if (additional_discount_percentage > 0) {
				var amount = parseFloat((apply_discount_on == 'grand total') ? total : (apply_discount_on == 'net total' ? $("#base_total").val() : 0));
				if (amount > 0) {
					var disc = (amount * additional_discount_percentage) / 100;
					base_discount_amount = disc.toFixed(2);
					discount_amount = (base_discount_amount * exchange_rate).toFixed(2);
				}
			}

			total = total - base_discount_amount;

			var round_total = Math.ceil(total);
			var round_adjustment = (round_total - total).toFixed(2);

			$("#base_discount_amount").val(base_discount_amount);
			$("#discount_amount").val(discount_amount);


			$("#base_grand_total").val(total.toFixed(2));
			$("#base_rounding_adjustment").val(round_adjustment);
			$("#base_rounded_total").val(round_total);


			var customer_total = (parseFloat($("#base_total").val()) * exchange_rate).toFixed(2);

			$("#total").val(customer_total);
			$("#net_total").val(customer_total);

			var grand_total = (total * exchange_rate).toFixed(2);
			round_adjustment = (round_adjustment * exchange_rate).toFixed(2);
			round_total = (round_total * exchange_rate).toFixed(2);

			$("#grand_total").val(grand_total);
			$("#rounding_adjustment").val(round_adjustment);
			$("#rounded_total").val(round_total);
		}
	}

	// supplier end


	function fetchAllCurrencyExchangeHire(for_data, currency_id) {

		$.ajax({
			type: "POST",
			url: "<?php echo site_url('CurrencyExchange/getExchangeRate');?>",
			dataType: "json",
			data: {currency_id: currency_id},
			success: function (data) {
				$("#" + for_data + "_exchange_rate").text(JSON.stringify(data));
				$("." + for_data + "_currency_id").val(currency_id);

				getConversionRateHire();

			}
		});
	}

	function getConversionRateHire() {
		var customer_currency_id = parseInt($(".customer_currency_id").val());
		var company_currency_id = parseInt($(".company_currency_id").val());
		if (customer_currency_id == company_currency_id) {
			$(".conversion_rate").val(1);
		} else if (customer_currency_id > 0 && company_currency_id > 0) {
			var customer_exchange_rate = $.parseJSON($("#customer_exchange_rate").text());
			$.each(customer_exchange_rate, function (index, value) {
				if (value.from_currency == customer_currency_id && value.to_currency == company_currency_id) {
					$(".conversion_rate").val(value.exchange_rate);
					return false;
				}
			});
		}
		calculateTotalHire();
	}


	function calculateTotalHire() {
		var total = 0;

		var customer_currency_id = parseInt($(".customer_currency_id").val()) + 0;
		var company_currency_id = parseInt($(".company_currency_id").val()) + 0;
		var exchange_rate = parseFloat($(".conversion_rate").val()).toFixed(2);

		var all_exchange_rate = $.parseJSON($("#all_exchange_rate").text());
		$(".item_total_amount").each(function () {

			var row = $(this);
			var quantity = parseFloat(row.parents("tr").find("td:eq(1) input").val());
			var rate = parseFloat(row.attr('data-original_rate'));

			var amount = parseFloat(row.val());
			var currency_id = parseFloat(row.attr('data-item_currency'));

			var found = false;

			quantity = isNaN(quantity) ? 0 : quantity;
			rate = isNaN(rate) ? 0 : rate;
			amount = isNaN(amount) ? 0 : amount;

			if (currency_id == company_currency_id) {
				amount = (rate * quantity).toFixed(2);
			} else {
				$.each(all_exchange_rate, function (index, value) {
					var exchange_rate = parseFloat(value.exchange_rate);
					if (value.from_currency == currency_id && value.to_currency == company_currency_id) {
						rate = (rate / parseFloat(value.exchange_rate)).toFixed(2); // convert rate to customer currency exchange rate
						amount = (rate * quantity).toFixed(2);
						found = true;
						return false;
					}
				});
				if (!found) {
					amount = (rate * quantity).toFixed(2);
				}
			}
			row.parents("tr").find("td:eq(2) input").val(rate); // set rate
			row.val(amount); // set total
			total += parseFloat(amount); // addition to grand total
		});

		$("#base_total").val(total.toFixed(2));
		$("#base_net_total").val(total.toFixed(2));

		var total_tax_amount = parseFloat(total);
		var tax_total = 0;
		$(".tax_rate").each(function () {
			var tax_rate = parseFloat($(this).val());
			var tax_amount = 0;
			if (tax_rate > 0) {
				tax_amount = ((total * tax_rate) / 100).toFixed(2);
				total_tax_amount = total_tax_amount + parseFloat(tax_amount);
				tax_total = tax_total + parseFloat(tax_amount);
				$(this).closest("tr").find('.tax_amount').val(tax_amount);
				$(this).closest("tr").find('.tax_total').val(total_tax_amount.toFixed(2));
			}
		});

		$("#base_total_taxes_and_charges").val(tax_total.toFixed(2));
		$("#total_taxes_and_charges").val((tax_total * exchange_rate).toFixed(2));

		total = total_tax_amount;

		var apply_discount_on = $("#apply_discount_on").val();
		var additional_discount_percentage = parseFloat($("#additional_discount_percentage").val());
		var base_discount_amount = 0;
		var discount_amount = 0;

		if (additional_discount_percentage > 0) {
			var amount = parseFloat((apply_discount_on == 'grand total') ? total : (apply_discount_on == 'net total' ? $("#base_total").val() : 0));
			if (amount > 0) {
				var disc = (amount * additional_discount_percentage) / 100;
				base_discount_amount = disc.toFixed(2);
				discount_amount = (base_discount_amount * exchange_rate).toFixed(2);
			}
		}
		total = total - base_discount_amount;
		var round_total = Math.ceil(total);
		var round_adjustment = (round_total - total).toFixed(2);

		$("#base_discount_amount").val(base_discount_amount);
		$("#discount_amount").val(discount_amount);

		$("#base_grand_total").val(total.toFixed(2));
		$("#base_rounding_adjustment").val(round_adjustment);
		$("#base_rounded_total").val(round_total);

		var customer_total = (parseFloat($("#base_total").val()) * exchange_rate).toFixed(2);
		$("#total").val(customer_total);
		$("#net_total").val(customer_total);

		var grand_total = (total * exchange_rate).toFixed(2);
		round_adjustment = (round_adjustment * exchange_rate).toFixed(2);
		round_total = (round_total * exchange_rate).toFixed(2);

		$("#grand_total").val(grand_total);
		$("#rounding_adjustment").val(round_adjustment);
		$("#rounded_total").val(round_total);
	}


	//    commom module

	function fetchAllCurrencyExchange1(for_data, currency_id) {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('CurrencyExchange/getExchangeRate');?>",
			dataType: "json",
			data: {currency_id: currency_id},
			success: function (data) {
				$("." + for_data + "_exchange_rate").text(JSON.stringify(data));
				$("." + for_data + "_currency_id").val(currency_id);

				getConversionRate1();
			}
		});
	}

	function getConversionRate1() {
		var customer_currency_id = parseInt($("#currency_id").val());
		//var customer_currency_id = parseInt($(".customer_currency_id").val());
		var company_currency_id = parseInt($(".company_currency_id").val());

		if (customer_currency_id == company_currency_id) {
			$(".conversion_rate").val(1);
		} else if (customer_currency_id > 0 && company_currency_id > 0) {
			var customer_exchange_rate = $.parseJSON($(".customer_exchange_rate").text());
			$.each(customer_exchange_rate, function (index, value) {
				if (value.from_currency == company_currency_id && value.to_currency == customer_currency_id) {
					// if (value.from_currency == customer_currency_id && value.to_currency == company_currency_id) {
					$(".conversion_rate").val(value.exchange_rate);
					return false;
				}
			});
		}
		calculateTotal1();
	}


	function calculateTotal1() {

		var total = 0;
		var displaytotal = 0;
		var taxDisplayamount = 0;
		var customer_currency_id = parseInt($(".customer_currency_id").val()) + 0;
		var company_currency_id = parseInt($(".company_currency_id").val()) + 0;
		var exchange_rate = parseFloat($(".conversion_rate").val()).toFixed(2);

		var all_exchange_rate = $.parseJSON($("#all_exchange_rate").text());

		$(".item_total_amount").each(function () {

			var row = $(this);
			//var quantity = parseFloat(row.parents("tr").find("td:eq(1) input").val());
			var quantity = parseFloat(row.parents("tr").find(".item_quantity").val());
			var rate = parseFloat(row.attr('data-change_rate'));
			var rateOrg = parseFloat(row.attr('data-original_rate'));
			//var changeRate = parseFloat(row.attr('data-original_rate'));
			//var rate = parseFloat(row.parents("tr").find(".item_rate").val());
			//console.log(row.parents("tr").find(".item_rate").val());

			var amount = parseFloat(row.val());
			var currency_id = parseFloat(row.attr('data-item_currency'));

			var found = false;

			quantity = isNaN(quantity) ? 0 : quantity;
			rate = isNaN(rate) ? 0 : rate;
			amount = isNaN(amount) ? 0 : amount;
			//var itemDisplayRate = rateOrg;
			if (currency_id == company_currency_id) {
				//amount = (rate * quantity).toFixed(2);

				// agentx change start
				if (parseFloat(rateOrg) == parseFloat(rate)) {
					itemDisplayRate = parseFloat(rate * exchange_rate);

				} else {
					itemDisplayRate = rate;
				}
				amount = (rate * quantity).toFixed(2);
				itemDisplayamount = (itemDisplayRate * quantity).toFixed(2);
				// agentx change end

			} else {

				$.each(all_exchange_rate, function (index, value) {
					var exchange_rate = parseFloat(value.exchange_rate);
					if (value.from_currency == currency_id && value.to_currency == company_currency_id) {
						if (parseFloat(rateOrg) == parseFloat(rate)) {
							rate = (rate / parseFloat(value.exchange_rate)).toFixed(2); // convert rate to customer currency exchange rate
						}
						amount = (rate * quantity).toFixed(2);
						// agentx change start
						if (parseFloat(rateOrg) == parseFloat(rate)) {
							itemDisplayRate = parseFloat(rate * exchange_rate);
						} else {
							itemDisplayRate = rate;
						}
						itemDisplayamount = (itemDisplayRate * quantity).toFixed(2);
						// agentx change start
						found = true;
						return false;
					}
				});
				if (!found) {
					if (parseFloat(rateOrg) == parseFloat(rate)) {
						console.log(1);
						itemDisplayRate = parseFloat(rate * exchange_rate);
					} else {
						itemDisplayRate = rate;
					}
					itemDisplayamount = (itemDisplayRate * quantity).toFixed(2);
					amount = (rate * quantity).toFixed(2);
				}
			}
			// row.parents("tr").find("td:eq(2) input").val(rate); // set rate
			//row.parents("tr").find(".item_rate").val(rate);
			//row.val(amount); // set total
			//total += parseFloat(amount); // addition to grand total
			console.log(itemDisplayRate);
			row.parents("tr").find(".item_rate").val(parseFloat(itemDisplayRate).toFixed(2)); // set rate
			$(this).closest("tr").find(".item_total_amount").attr("data-change_rate", parseFloat(itemDisplayRate).toFixed(2));
			row.val(itemDisplayamount); // set total
			total += parseFloat(amount); // addition to grand total
			displaytotal += parseFloat(itemDisplayamount); // addition to grand total
		});

		$("#base_total").val(total.toFixed(2));
		$("#base_net_total").val(total.toFixed(2));

		var total_tax_amount = parseFloat(total);
		var tax_total = 0;
		$(".tax_rate").each(function () {
			var tax_rate = parseFloat($(this).val());
			var tax_amount = 0;
			if (tax_rate > 0) {
				tax_amount = ((total * tax_rate) / 100).toFixed(2);
				taxDisplayRate = parseFloat(tax_amount * exchange_rate);
				taxDisplayamount = taxDisplayamount + parseFloat(taxDisplayRate);
				total_tax_amount = total_tax_amount + parseFloat(tax_amount);

				tax_total = tax_total + parseFloat(tax_amount);
				$(this).closest("tr").find('.tax_amount').val(taxDisplayRate);
				//$(this).closest("tr").find('.tax_amount').val(tax_amount);
				$(this).closest("tr").find('.tax_total').val(total_tax_amount.toFixed(2));
			}
		});
		$("#base_total_taxes_and_charges").val(tax_total.toFixed(2));
		$("#total_taxes_and_charges").val((tax_total * exchange_rate).toFixed(2));

		total = total_tax_amount;

		var apply_discount_on = $("#apply_discount_on").val();
		var additional_discount_percentage = parseFloat($("#additional_discount_percentage").val());
		var base_discount_amount = 0;
		var discount_amount = 0;

		if (additional_discount_percentage > 0) {
			var amount = parseFloat((apply_discount_on == 'grand total') ? total : (apply_discount_on == 'net total' ? $("#base_total").val() : 0));
			if (amount > 0) {
				var disc = (amount * additional_discount_percentage) / 100;
				base_discount_amount = disc.toFixed(2);
				discount_amount = (base_discount_amount * exchange_rate).toFixed(2);
			}
		}
		total = total - base_discount_amount;
		var round_total = Math.ceil(total);
		var round_adjustment = (round_total - total).toFixed(2);

		$("#base_discount_amount").val(base_discount_amount);
		$("#discount_amount").val(discount_amount);


		$("#base_grand_total").val(total.toFixed(2));
		$("#base_rounding_adjustment").val(round_adjustment);
		$("#base_rounded_total").val(round_total);


		var customer_total = (parseFloat($("#base_total").val()) * exchange_rate).toFixed(2);
		$("#total").val(customer_total);
		$("#net_total").val(customer_total);

		var grand_total = (total * exchange_rate).toFixed(2);
		round_adjustment = (round_adjustment * exchange_rate).toFixed(2);
		round_total = (round_total * exchange_rate).toFixed(2);

		$("#grand_total").val(grand_total);
		$("#rounding_adjustment").val(round_adjustment);
		$("#rounded_total").val(round_total);
	}


	function getAllExchangeRates() {
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('CurrencyExchange/getAllExchangeRate');?>",
			dataType: "json",
			success: function (data) {
				$("#all_exchange_rate").text(JSON.stringify(data));
			}
		});
	}

	function Select2Init() {
		$('.select').each(function () {
			var select = $(this);
			$("#" + select.attr('id')).select2({
				allowClear: true,
				containerCssClass: 'border-primary'
			}).on('change.select2', function () {
				if ($("#" + select.attr('id')).valid()) {
					$("#" + select.attr('id')).removeClass('invalid').addClass('success');
				}
			});
		});

		// Menu border and text color
		$('.select-border-color').select2({
			dropdownCssClass: 'border-primary',
			containerCssClass: 'border-primary text-primary-700'
		});
	}

	function Select2TagsInit() {
		$('.select-tag').each(function () {
			var select = $(this);
			$("#" + select.attr('id')).select2({
				tags: true,
				tokenSeparators: [',', ' '],
				allowClear: true,
				containerCssClass: 'border-primary'
			}).on('change.select2', function () {
				if ($("#" + select.attr('id')).valid()) {
					$("#" + select.attr('id')).removeClass('invalid').addClass('success');
				}
			});
		});

		// Menu border and text color
		$('.select-border-color').select2({
			dropdownCssClass: 'border-primary',
			containerCssClass: 'border-primary text-primary-700'
		});
	}


	function numberInit() {
		$('.numberInit').each(function () {
			var number = $(this);
			$("#" + number.attr('id')).keydown(function (e) {
				// Allow: backspace, delete, tab, escape, enter and .
				if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
						// Allow: Ctrl+A, Command+A
						(e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
						// Allow: home, end, left, right, down, up
						(e.keyCode >= 35 && e.keyCode <= 40)) {
					// let it happen, don't do anything
					return;
				}
				// Ensure that it is a number and stop the keypress
				if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
					e.preventDefault();
				}
			});
		});
	}

	function DtFormClear(formName) {

		$("form#" + formName + " input[type=checkbox]").prop('checked', false);
		$("form#" + formName + " input[type=checkbox]").siblings().remove();
		$("form#" + formName + " input[type=text]").val('');
		$("form#" + formName + " input[type=tel]").val('');
		$("form#" + formName + " input[type=email]").val('');
		$("form#" + formName + " input[type=radio]").prop('checked', false);
		$("form#" + formName + " textarea").val('');
		$('form#' + formName + " input[type=file]").val('');
		$('form#' + formName + " input[type=file]").parents('.form-group').find('img').attr('src', null);
		$("form#" + formName + " input[type=file]").parents('.form-group').find('img').hide();
		$("form#" + formName + " input[type=file]").parents('.form-group').find('span[class=filename]').html('No File Selected');

		$('textarea.ckeditor').each(function () {
			var $textarea = $(this);
			CKEDITOR.instances[$textarea.attr('name')].setData("");
		});

		$("form#" + formName + " select").val('').trigger('change.select2');

		$("form#" + formName + " .validation-error-label").html('');
		CheckboxKeyGen();
		SwitcheryKeyGen();
		RadioKeyGen();
	}

	function DtFormFill(formName, data) {
		$.each(data, function (key, val) {
			//Get Form fields Type
			var inputType = $('form#' + formName + ' #' + key).prop("type");

			// CheckBox
			if (inputType == 'checkbox') {
				$('form#' + formName + ' #' + key).siblings().remove();
				if (val == 1) {
					$('form#' + formName + ' #' + key).prop("checked", true);
				} else {
					$('form#' + formName + ' #' + key).removeAttr('checked');
				}
			}
			//radio
			else if (inputType == 'radio') {
				$('form#' + formName + ' input[value="' + val + '"]').prop("checked", true);
			}
			//select2 or select or select multiple
			else if (inputType == 'select-one' || inputType == 'select-multiple') {
				if ($('form#' + formName + ' #' + key).length) {
					if (key == 'country_id') {
						var option = new Option(data['country_name'], data['country_id'], true, true);
						$('form#' + formName + ' #' + key).append(option).trigger('change');
					} else if (key == 'state_id') {
						var option = new Option(data['state_name'], data['state_id'], true, true);
						$('form#' + formName + '  #' + key).append(option).trigger('change');
					} else if (key == 'city_id') {
						var option = new Option(data['city_name'], data['city_id'], true, true);
						$('form#' + formName + '  #' + key).append(option).trigger('change');
					}
//                    else if (key == 'samaj_id') {
//                        var option = new Option(data['samaj_name'], data['surname_id'], true, true);
//                        $('form#' + formName + '  #' + key).append(option).trigger('change');
//                    }
					else {
						$('form#' + formName + '  #' + key).val(val).trigger('change');
					}
					$('#' + key + '-error').html("");
				}
			}
			//textarea or ckeditor
			else if (inputType == 'textarea') {
				if ($('form#' + formName + ' #' + key).length) {
					if ($('form#' + formName + ' #' + key).hasClass('ckeditor')) {
						CKEDITOR.instances[key].setData(val);
					} else {
						$('form#' + formName + ' #' + key).val(val);
					}
				}
			}
			//file
			else if (inputType == 'file') {
				if ($('form#' + formName + ' #' + key).length) {
					var path = '';
					if (formName == 'ManufacturerDetails') {
						path = "<?= base_url().$this->config->item('logo_path'); ?>";
					}
					console.log(val);
					if (path != '' && val != '') {
						$('form#' + formName + ' #' + key).parents('.form-group').find('img').attr('src', path + val);
						$('form#' + formName + ' #' + key).parents('.form-group').find('img').show();
						$("form#" + formName + " input[type=file]").parents('.form-group').find('span[class=filename]').html('No File Selected');
					} else {
						$('form#' + formName + ' #' + key).parents('.form-group').find('img').attr('src', null);
						$('form#' + formName + ' #' + key).parents('.form-group').find('img').hide();
						$("form#" + formName + " input[type=file]").parents('.form-group').find('span[class=filename]').html('No File Selected');
					}
//                    $('form#' + formName + ' #' + key).val(val);
				}
			}

			// /common
			else {
				if ($('form#' + formName + ' #' + key).length) {
					$('form#' + formName + ' #' + key).val(val);
				}
			}
		});
		CheckboxKeyGen();
		SwitcheryKeyGen();
		RadioKeyGen();
	}


	function ScrollToTop() {
		window.scroll({
			top: 0,
			left: 0,
			behavior: 'smooth'
		});
	}

	function TimePickerInit() {
		// Time picker
		$(".dtTimePicker").AnyTime_picker({
			format: "%H:%i:%s"
		});
	}

	function FileValidate() {
		$(document).on('change', 'input[type="file"]', function () {
			if ($("#" + $(this).attr('id')).valid()) {
				$("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
			}
		});

	}

	function addValidation(type, selecter, rules) {
		$('' + type + '' + selecter + '').each(function () {
			$(this).rules('add', rules);
		});

	}

	// jQuery.validator.addMethod("validEmail", function(value, element, param) {
	//     var reg = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
	//     if(reg.test(value)){
	//         return true;
	//     }else{
	//         return false;
	//     }
	// }, "Please enter a valid email address");

	// jQuery.validator.addMethod("aadhar", function(value, element) {
	//     return this.optional(element) || /^[0-9]{12}$/i.test(value);
	// }, "Please Enter Valid Aadhar card no");

</script>
</html>


