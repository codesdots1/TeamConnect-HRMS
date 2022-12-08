<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2">7 People</h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">
			<li class="nav-item">
				<a class="nav-link border-0 font-23 grid-view" href="<?= site_url('Employee/getCurrentEmployee'); ?>"><i
						class="fa fa-th-large" aria-hidden="true"></i></a>
			</li>
			<li class="nav-item">
				<a class="nav-link border-0 font-23 active list-view" href="employees-list.html"><i
						class="fa fa-list-ul" aria-hidden="true"></i></a>
			</li>
			<li class="nav-item pl-3">
				<a href="<?= site_url('Employee/manage'); ?>"
				   class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><i
						class="fa fa-plus"></i> <?= lang('add_employee') ?></a>
			</li>

		</ul>
	</div>
</div>
<div class="ctm-border-radius shadow-sm grow card">
	<div class="card-body">

		<!--Content tab-->
		<div class="row people-grid-row">
			<div class="col-md-6 col-lg-6 col-xl-4">
				<div class="card widget-profile">
					<div class="card-body">
						<div class="pro-widget-content text-center">
							<div class="profile-info-widget">
								<a href="employment.html" class="booking-doc-img">
									<img src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="User Image">
								</a>
								<div class="profile-det-info">
									<h4><a href="employment.html" class="text-primary">Maria Cotton</a></h4>
									<div>
										<p class="mb-0"><b>PHP Team Lead</b></p>
										<p class="mb-0 ctm-text-sm">mariacotton@example.com</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6 col-xl-4">
				<div class="card widget-profile">
					<div class="card-body">
						<div class="pro-widget-content text-center">
							<div class="profile-info-widget">
								<a href="employment.html" class="booking-doc-img">
									<img src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="User Image">
								</a>
								<div class="profile-det-info">
									<h4><a href="employment.html" class="text-primary">Danny Ward</a></h4>
									<div>
										<p class="mb-0"><b>Designing Team Lead</b></p>
										<p class="mb-0 ctm-text-sm">dannyward@example.com</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6 col-xl-4">
				<div class="card widget-profile">
					<div class="card-body">
						<div class="pro-widget-content text-center">
							<div class="profile-info-widget">
								<a href="employment.html" class="booking-doc-img">
									<img src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="User Image">
								</a>
								<div class="profile-det-info">
									<h4><a href="employment.html" class="text-primary">Linda Craver</a></h4>
									<div>
										<p class="mb-0"><b>IOS Team Lead</b></p>
										<p class="mb-0 ctm-text-sm">lindacraver@example.com</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6 col-xl-4">
				<div class="card widget-profile">
					<div class="card-body">
						<div class="pro-widget-content text-center">
							<div class="profile-info-widget">
								<a href="employment.html" class="booking-doc-img">
									<img src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="User Image">
								</a>
								<div class="profile-det-info">
									<h4><a href="employment.html" class="text-primary">Jenni Sims</a></h4>
									<div>
										<p class="mb-0"><b>Android Team Lead</b></p>
										<p class="mb-0 ctm-text-sm">jennisims@example.com</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6 col-xl-4">
				<div class="card widget-profile">
					<div class="card-body">
						<div class="pro-widget-content text-center">
							<div class="profile-info-widget">
								<a href="employment.html" class="booking-doc-img">
									<img src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="User Image">
								</a>
								<div class="profile-det-info">
									<h4><a href="employment.html" class="text-primary">John Gibbs</a></h4>
									<div>
										<p class="mb-0"><b>	Business Team Lead</b></p>
										<p class="mb-0 ctm-text-sm">johndrysdale@example.com</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6 col-xl-4">
				<div class="card widget-profile">
					<div class="card-body">
						<div class="pro-widget-content text-center">
							<div class="profile-info-widget">
								<a href="employment.html" class="booking-doc-img">
									<img src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="User Image">
								</a>
								<div class="profile-det-info">
									<h4><a href="employment.html" class="text-primary">Stacey Linville</a></h4>
									<div>
										<p class="mb-0"><b>	Testing Team Lead</b></p>
										<p class="mb-0 ctm-text-sm">staceylinville@example.com</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6 col-xl-4">
				<div class="card widget-profile last-card-row">
					<div class="card-body">
						<div class="pro-widget-content text-center">
							<div class="profile-info-widget">
								<a href="employment.html" class="booking-doc-img">
									<img src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="User Image">
								</a>
								<div class="profile-det-info">
									<h4><a href="employment.html" class="text-primary">Richard Wilson</a></h4>
									<div>
										<p class="mb-0"><b>	Operation Manager</b></p>
										<p class="mb-0 ctm-text-sm">richardwilson@example.com</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6 col-xl-4">
				<div class="card widget-profile last-card-row">
					<div class="card-body">
						<div class="pro-widget-content text-center">
							<div class="profile-info-widget">
								<a href="employment.html" class="booking-doc-img">
									<img src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="User Image">
								</a>
								<div class="profile-det-info">
									<h4><a href="employment.html" class="text-primary">Daniel Griffing</a></h4>
									<div>
										<p class="mb-0"><b>	Designing Team</b></p>
										<p class="mb-0 ctm-text-sm">danielgriffing@example.com</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6 col-xl-4">
				<div class="card widget-profile last-card-row1">
					<div class="card-body">
						<div class="pro-widget-content text-center">
							<div class="profile-info-widget">
								<a href="employment.html" class="booking-doc-img">
									<img src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="User Image">
								</a>
								<div class="profile-det-info">
									<h4><a href="employment.html" class="text-primary">Michelle Fairfax</a></h4>
									<div>
										<p class="mb-0"><b>PHP Team</b></p>
										<p class="mb-0 ctm-text-sm">michellefairfax@example.com</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6 col-xl-4">
				<div class="card widget-profile">
					<div class="card-body">
						<div class="pro-widget-content text-center">
							<div class="profile-info-widget">
								<a href="employment.html" class="booking-doc-img">
									<img src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="User Image">
								</a>
								<div class="profile-det-info">
									<h4><a href="employment.html" class="text-primary">Danny Ward</a></h4>
									<div>
										<p class="mb-0"><b>Designing Team Lead</b></p>
										<p class="mb-0 ctm-text-sm">dannyward@example.com</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6 col-xl-4">
				<div class="card widget-profile">
					<div class="card-body">
						<div class="pro-widget-content text-center">
							<div class="profile-info-widget">
								<a href="employment.html" class="booking-doc-img">
									<img src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="User Image">
								</a>
								<div class="profile-det-info">
									<h4><a href="employment.html" class="text-primary">Danny Ward</a></h4>
									<div>
										<p class="mb-0"><b>Designing Team Lead</b></p>
										<p class="mb-0 ctm-text-sm">dannyward@example.com</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6 col-xl-4">
				<div class="card widget-profile">
					<div class="card-body">
						<div class="pro-widget-content text-center">
							<div class="profile-info-widget">
								<a href="employment.html" class="booking-doc-img">
									<img src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="User Image">
								</a>
								<div class="profile-det-info">
									<h4><a href="employment.html" class="text-primary">Danny Ward</a></h4>
									<div>
										<p class="mb-0"><b>Designing Team Lead</b></p>
										<p class="mb-0 ctm-text-sm">dannyward@example.com</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6 col-xl-4">
				<div class="card widget-profile">
					<div class="card-body">
						<div class="pro-widget-content text-center">
							<div class="profile-info-widget">
								<a href="employment.html" class="booking-doc-img">
									<img src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="User Image">
								</a>
								<div class="profile-det-info">
									<h4><a href="employment.html" class="text-primary">Danny Ward</a></h4>
									<div>
										<p class="mb-0"><b>Designing Team Lead</b></p>
										<p class="mb-0 ctm-text-sm">dannyward@example.com</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6 col-xl-4">
				<div class="card widget-profile">
					<div class="card-body">
						<div class="pro-widget-content text-center">
							<div class="profile-info-widget">
								<a href="employment.html" class="booking-doc-img">
									<img src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="User Image">
								</a>
								<div class="profile-det-info">
									<h4><a href="employment.html" class="text-primary">Danny Ward</a></h4>
									<div>
										<p class="mb-0"><b>Designing Team Lead</b></p>
										<p class="mb-0 ctm-text-sm">dannyward@example.com</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-lg-6 col-xl-4">
				<div class="card widget-profile">
					<div class="card-body">
						<div class="pro-widget-content text-center">
							<div class="profile-info-widget">
								<a href="employment.html" class="booking-doc-img">
									<img src="<?= base_url().$this->config->item('employee_image').$employee_image ?>" alt="User Image">
								</a>
								<div class="profile-det-info">
									<h4><a href="employment.html" class="text-primary">Danny Ward</a></h4>
									<div>
										<p class="mb-0"><b>Designing Team Lead</b></p>
										<p class="mb-0 ctm-text-sm">dannyward@example.com</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
