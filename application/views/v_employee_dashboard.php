<div class="quicklink-sidebar-menu ctm-border-radius shadow-sm bg-white card grow">
	<div class="card-body">
		<ul class="list-group list-group-horizontal-lg">
			<?php
			$role = $this->session->userdata('role');
			if($role == 'admin'){ ?>
			<li class="list-group-item text-center active button-5"><a href="<?= base_url();?>" class="text-white">Admin Dashboard</a></li>
			<?php } if($role == 'employee'){ ?>
			<li class="list-group-item text-center active button-5"><a class="text-dark" href="<?= base_url();?>">Employees Dashboard</a></li>
			<?php } ?>
		</ul>
	</div>
</div>
<!-- Widget -->
<div class="row">
	<div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12">
		<div class="card dash-widget ctm-border-radius shadow-sm grow">
			<div class="card-body">
				<div class="card-icon bg-primary">
					<i class="fa fa-laptop" aria-hidden="true"></i>
				</div>
				<div class="card-right">
					<h4 class="card-title">Projects</h4>
					<p class="card-text"><?= implode($empTotalProject);?></p>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-lg-6 col-sm-6 col-12">
		<div class="card dash-widget ctm-border-radius shadow-sm grow">
			<div class="card-body">
				<div class="card-icon bg-warning">
					<i class="fa fa-building-o"></i>
				</div>
				<div class="card-right">
					<h4 class="card-title">Companies</h4>
					<p class="card-text">1</p>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-lg-6 col-sm-6 col-12">
		<div class="card dash-widget ctm-border-radius shadow-sm grow">
			<div class="card-body">
				<div class="card-icon bg-danger">
					<i class="fa fa-suitcase" aria-hidden="true"></i>
				</div>
				<div class="card-right">
					<h4 class="card-title">Leaves</h4>
					<p class="card-text"><?= implode($employeeTotalLeave);?></p>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xl-3 col-lg-6 col-sm-6 col-12">
		<div class="card dash-widget ctm-border-radius shadow-sm grow">
			<div class="card-body">
				<div class="card-icon bg-success">
					<i class="fa fa-code" aria-hidden="true"></i>
				</div>
				<div class="card-right">
					<h4 class="card-title">Tasks</h4>
					<p class="card-text">8</p>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- / Widget -->

<!-- Chart -->
<div class="row">
	<div class="col-md-6 d-flex">
		<div class="card ctm-border-radius shadow-sm flex-fill grow">
			<div class="card-header">
				<h4 class="card-title mb-0 d-inline-block">Permission</h4>
				<a href="<?= base_url('EmployeeAttendance')?>" class="d-inline-block float-right text-primary"><i class="fa fa-book"></i></a>
			</div>
			<div class="card-body text-center">
				<?php if($empTotalTime == ''){?>
					<div class="time-list">
						<div class="dash-stats-list">
							<span class="btn btn-outline-primary">00:00:00</span>
							<p class="mb-0">Total Hrs</p>
						</div>
					</div>
				<?php } else{ ?>
				<div class="time-list">
					<div class="dash-stats-list">
						<span class="btn btn-outline-primary"><?= $empTotalTime['login_time']?></span>
						<p class="mb-0">Entry Time</p>
					</div>
					<div class="dash-stats-list">
						<span class="btn btn-outline-primary"><?= $empTotalTime['logout_time']?></span>
						<p class="mb-0">Exit Time</p>
					</div>
					<div class="dash-stats-list">
						<span class="btn btn-outline-primary"><?= $empTotalTime['total_time']?></span>
						<p class="mb-0">Total Hrs</p>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-md-6 d-flex">
		<div class="card ctm-border-radius shadow-sm flex-fill grow">
			<div class="card-header">
				<h4 class="card-title mb-0 d-inline-block">Leave</h4>
				<a href="<?= base_url('EmployeeLeaveType')?>" class="d-inline-block float-right text-primary"><i class="fa fa-suitcase"></i></a>
			</div>
			<div class="card-body text-center">
				<div class="time-list">
					<div class="dash-stats-list">
						<span class="btn btn-outline-primary"><?= implode($employeeLeaveTaken)?> Days</span>
						<p class="mb-0" style="color: #0a7523">Taken</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- / Chart -->

<div class="row">
	<div class="col-lg-6">
		<div class="card ctm-border-radius shadow-sm grow">
			<div class="card-header">
				<h4 class="card-title mb-0 d-inline-block">Today</h4>
			</div>
			<div class="card-body recent-activ">
				<div class="recent-comment">
					<a href="javascript:void(0)" class="dash-card text-dark">
						<div class="dash-card-container">
							<div class="dash-card-icon text-primary">
								<i class="fa fa-birthday-cake" aria-hidden="true"></i>
							</div>
							<?php
							if($empBirthday == ''){ ?>
								<div class="dash-card-content">
									<h6 class="mb-0">No Birthday Today</h6>
								</div>
							<?php } else {?>
								<div class="dash-card-content">
									<h6 class="mb-0"><?= implode($empBirthday); ?> is Birthday Today </h6>
								</div>
							<?php } ?>
						</div>
					</a>
					<hr>
					<a href="javascript:void(0)" class="dash-card text-dark">
						<div class="dash-card-container">
							<div class="dash-card-icon text-warning">
								<i class="fa fa-child" aria-hidden="true"></i>
							</div>
							<div class="dash-card-content">
								<h6 class="mb-0">Ralph Baker is off sick today</h6>
							</div>
							<div class="dash-card-avatars">
								<div class="e-avatar"><img class="img-fluid" src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="Avatar"></div>
							</div>
						</div>
					</a>
					<hr>
					<a href="javascript:void(0)" class="dash-card text-dark">
						<div class="dash-card-container">
							<div class="dash-card-icon text-success">
								<i class="fa fa-bed" aria-hidden="true"></i>
							</div>
							<?php foreach($employeeLeave as $value){ ?>
								<?php if($value == ''){?>
									<div class="dash-card-content">
										<h6 class="mb-0">No Employee Leave on today</h6>
									</div><?php } else { ?>
									<div class="dash-card-content">
										<h6 class="mb-0"> <?= $value['employee'];?> is <?=  $value['leave_type']?> today</h6>
									</div>
								<?php } }?>
						</div>
					</a>
					<hr>
					<a href="javascript:void(0)" class="dash-card text-dark">
						<div class="dash-card-container">
							<div class="dash-card-icon text-danger">
								<i class="fa fa-suitcase"></i>
							</div>
							<div class="dash-card-content">
								<h6 class="mb-0">Danny ward is away today</h6>
							</div>
							<div class="dash-card-avatars">
								<div class="e-avatar"><img class="img-fluid" src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="Avatar"></div>
							</div>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-md-12 d-flex">

		<!-- Team Leads List -->
		<div class="card flex-fill team-lead shadow-sm grow">
			<div class="card-header">
				<h4 class="card-title mb-0 d-inline-block">Team Leads </h4>
				<a href="<?= base_url('TeamManagement'); ?>" class="dash-card float-right mb-0 text-primary"> Manage Team </a>
			</div>
			<?php foreach ($teamLeads as $value){ ?>
				<div class="card-body">
					<div class="media mb-3">
						<div class="media-body">
							<h6 class="m-0"><?= $value['teamHead']; ?><br/></h6>
							<p class="mb-0 ctm-text-sm"><?= $value['team_name']?></p>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>

	<div class="col-lg-6 col-md-12 d-flex">

		<!-- Recent Activities -->
		<div class="card recent-acti flex-fill shadow-sm grow">
			<div class="card-header">
				<h4 class="card-title mb-0 d-inline-block">Recent Activities</h4>
				<a href="javascript:void(0)" class="d-inline-block float-right text-primary"><i class="lnr lnr-sync"></i></a>
			</div>
			<?php foreach ($name as $value){?>
				<div class="card-body recent-activ admin-activ">
					<div class="recent-comment">
						<div class="notice-board">
							<div class="heading-elements btn btn-success btn-lg disabled">
								New Projects
							</div>
							<div class="notice-body">
								<h6 class="mb-0"><?= $value['name'];?></h6>
								<span class="ctm-text-sm"><?= $value['created_at']?></span>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
	<!-- / Recent Activities -->

	<div class="col-lg-6 col-md-12 d-flex">

		<!-- Today -->
		<div class="card flex-fill today-list shadow-sm grow">
			<div class="card-header">
				<h4 class="card-title mb-0 d-inline-block">Your Upcoming Leave</h4>
				<a class="d-inline-block float-right text-primary"><i class="fa fa-suitcase"></i></a>
			</div>
			<?php
			foreach ($upcomingHoliday as $value){ ?>
				<div class="card-body">
					<div class="media mb-3">
						<div class="media-body">
							<h6 class="m-0"><?= $value['holiday_from_date']; ?><br/></h6>
							<p class="mb-0 ctm-text-sm"><?= $value['title']?></p>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
