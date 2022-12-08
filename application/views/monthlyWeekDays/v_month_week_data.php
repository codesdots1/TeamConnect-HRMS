<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic5">
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-five">
				<?= lang('view_monthly_work') ?>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-five" class="collapse show ctm-padding" aria-labelledby="headingFive"
			 data-parent="#accordion-details">
			<div class="row">

				<div class="col-md-12 form-group">
					<b><span style="display: block"><?= lang('title') ?> : </span></b>
					<?= (isset($getMonthlyWeekDaysData['title'])) ? $getMonthlyWeekDaysData['title'] : ""; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('total_days') ?> : </label></span></b>
					<?= (isset($getMonthlyWeekDaysData['total_full_days'])) ? $getMonthlyWeekDaysData['total_full_days'] : ""; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('total_half_days') ?> : </label></span></b>
					<?= (isset($getMonthlyWeekDaysData['total_half_days'])) ? $getMonthlyWeekDaysData['total_half_days'] : ""; ?>
				</div>

				<div class="row">
					<div class="col-12">
						<div class="submit-section text-center btn-add">
							<button type="button" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"
									onclick="window.location.href='<?php echo site_url('MonthlyWeekDays') ?>'"><?= lang('back_btn') ?> <i class="icon-arrow-left5 position-right"></i> </button>
						</div>
					</div>
				</div>

				<div class="table-responsive">

					<table class="table table-striped custom-table">
						<thead>
						<tr>
							<th> Week Days </th>
							<th class="text-center">Monday</th>
							<th class="text-center">Tuesday</th>
							<th class="text-center">Wednesday</th>
							<th class="text-center">Thursday</th>
							<th class="text-center">Friday</th>
							<th class="text-center">Saturday</th>
							<th class="text-center">Sunday</th>
						</tr>
						</thead>
						<tbody>
						<?php for($i = 1 ; $i < 6; $i++){ ?>
							<tr>
								<td> Week <?= $i; ?> </td>
								<?php
								$workName 	  = array("Mon_full", "Tue_full", "Wed_full", "Thu_full", "Fri_full", "Sat_full", "Sun_full");
								$workWeekName = array("Mon_half", "Tue_half", "Wed_half", "Thu_half", "Fri_half", "Sat_half", "Sun_half");

								$daysName 	   = explode(',',$getMonthlyWeekDaysData['week_details'][$i-1]['week_days_name']);
								$workStateName = explode(',',$getMonthlyWeekDaysData['week_details'][$i-1]['work_week_state']);

								foreach ($workName as $index =>$value) { ?>
									<td class="text-center">
										<input type="checkbox" name="work_week<?= $i; ?>[]"  class="dt-checkbox styled full-day"
											   value="<?php echo $value;?>" <?php if(in_array($value,$workStateName)) { ?>
											checked="checked" <?php } ?> > Full </br></br>

										<input type="checkbox" name="work_week<?= $i; ?>[]"  class="dt-checkbox styled half-day"
											   value="<?php echo $workWeekName[$index];?>" <?php if(in_array($workWeekName[$index],$workStateName)) {?>
											checked="checked" <?php } ?> > Half
									</td>
									<?php
								} ?>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>

			</div>
		</div>
	</div>
	<?php echo form_close(); ?>
</div>

<script>
	$(document).ready(function () {
		$("input[type='checkbox']").change(function () {
			if ($(this).is(":checked")) {
				if ($(this).attr("class") == "dt-checkbox styled full-day") {
					$(this).parent('span').parent('div').parent('td').find('div:last-child span').removeClass('checked');
					$(this).parent("span").addClass('checked');
					$(this).parent('span').parent('div').parent('td').find('div:last-child span input.half-day').prop('checked', false);
					$(this).prop('checked', true);

				} else if ($(this).attr("class") == "dt-checkbox styled half-day") {
					$(this).parent('span').parent('div').parent('td').find('div:first-child span').removeClass('checked');
					$(this).parent("span").addClass('checked');
					$(this).parent('span').parent('div').parent('td').find('div:first-child span input.full-day').prop('checked', false);
					$(this).prop('checked', true);
				}
				updateCounter();
				updateWeekCounter();
			} else {
				$(this).parent('span').parent('div').parent('td').find('div:last-child span').removeClass('checked');
				$(this).parent("span").removeClass('checked');
				$(this).parent('span').parent('div').parent('td').find('div:last-child span input.half-day').prop('checked', false);
				$(this).prop('checked', false);
			}

		});
	});
</script>

<script>
	$(document).ready(function () {
		dt_DataTable = $('#employeeAttendanceDetailsTable').DataTable({
			dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
			language: {},

			fnDrawCallback: function (oSettings) {
				// Switchery
				// Initialize multiple switches
				CheckboxKeyGen();
				DtSwitcheryKeyGen();
				CustomToolTip();
				ScrollToTop();
			}
		});
	});

</script>

<script>

	//Delete Time Cancel button click to remove checked value
	$(document).on('click', '.cancel', function () {
		$('#mothlyWorkTable input[class="dt-checkbox styled"]').prop('checked', false);
		CheckboxKeyGen();
	});

	$(document).ready(function () {
		// Switchery
		// Initialize multiple switches
		SwitcheryKeyGen();
		CheckboxKeyGen('checkAll');
		CustomToolTip();

		///$('#checkAll').prop('checked', false);
		$('#checkAll').click(function () {
			var checkedStatus = this.checked;
			$('#mothlyWorkTable tbody tr').find('td:first :checkbox').each(function () {
				$(this).prop('checked', checkedStatus);
			});
			CheckboxKeyGen();
		});

	});
</script>
