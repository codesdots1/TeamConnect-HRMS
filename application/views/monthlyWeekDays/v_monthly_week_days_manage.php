<div class="accordion add-employee" id="accordion-details">
	<?php
	//create  form open tag
	$form_id = array(
			'id'=>'workWeekDetails',
			'method'=>'post',
			'class'=>'form-horizontal'
	);
	echo form_open_multipart('', $form_id);
	$monthWorkId 	= (isset($getMonthlyWeekDaysData['month_work_id']) && ($getMonthlyWeekDaysData['month_work_id'] != '')) ? $getMonthlyWeekDaysData['month_work_id'] : '';
	$monthWorkDayId = (isset($getMonthlyWeekDaysData['month_work_day_id']) && ($getMonthlyWeekDaysData['month_work_day_id'] != '')) ? $getMonthlyWeekDaysData['month_work_day_id'] : '';
	?>
	<input type="hidden" name="month_work_id" value="<?= $monthWorkId ?>" id="month_work_id">
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic1">
		<h4 class="cursor-pointer mb-0">
			<a class=" coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-one" aria-expanded="true">
				<?= lang('monthly_work_heading') ?>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-one" class="collapse show ctm-padding" aria-labelledby="basic1"
			 data-parent="#accordion-details">
			<div class="row">

				<div class="col-md-12 form-group">
					<span><?= lang('title') ?><span class="text-danger"> * </span></span>
					<input type="text" name="title" id="title" class="form-control"
						   value="<?= (isset($getMonthlyWeekDaysData['title']) && ($getMonthlyWeekDaysData['title'] != '')) ? $getMonthlyWeekDaysData['title'] : ''; ?>"
						   placeholder="Enter <?= lang('title') ?>">
				</div>

				<div class="col-md-6 form-group">
					<span><?= lang('total_days') ?><span class="text-danger"> * </span></span>
					<input type="text" name="total_full_days" id="total_full_days" class="form-control"
						   value="<?= (isset($getMonthlyWeekDaysData['total_full_days']) && ($getMonthlyWeekDaysData['total_full_days'] != '')) ? $getMonthlyWeekDaysData['total_full_days'] : ''; ?>"
						   placeholder="<?= lang('total_days') ?>" readonly>
				</div>

				<div class="col-md-6 form-group">
					<span><?= lang('total_half_days') ?><span class="text-danger"> * </span></span>
					<input type="text" name="total_half_days" id="total_half_days" class="form-control"
						   value="<?= (isset($getMonthlyWeekDaysData['total_half_days']) && ($getMonthlyWeekDaysData['total_half_days'] != '')) ? $getMonthlyWeekDaysData['total_half_days'] : ''; ?>"
						   placeholder="<?= lang('total_half_days') ?>" readonly>
				</div>

				<div class="table-responsive m-t-15">
					<table class="table table-striped custom-table">
						<thead>
						<tr>
							<th> Week Days <span class="text-danger"> * </span></th>
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
								<input type="hidden" id="month_work_day_id" name="month_work_day_id<?= $i; ?>" value="">
								<?php
								$workDayFull 	  = array("Mon_full", "Tue_full", "Wed_full", "Thu_full", "Fri_full", "Sat_full", "Sun_full");
								$workDayHalf 	  = array("Mon_half", "Tue_half", "Wed_half", "Thu_half", "Fri_half", "Sat_half", "Sun_half");

								if(isset($getMonthlyWeekDaysData['month_work_id']) && ($getMonthlyWeekDaysData['month_work_id'] != '')){

									$daysName 	   = explode(',',$getMonthlyWeekDaysData['week_details'][$i-1]['week_days_name']);
									$workStateName = explode(',',$getMonthlyWeekDaysData['week_details'][$i-1]['work_week_state']);


									foreach ($workDayFull as $index =>$value) { ?>
										<td class="text-center">
											<input type="checkbox" name="work_week<?= $i; ?>[]"  class="dt-checkbox styled full-day"
												   value="<?php echo $value;?>" <?php if(in_array($value,$workStateName)) { ?>
												checked="checked" <?php } ?> > Full </br></br>

											<input type="checkbox" name="work_week<?= $i; ?>[]"  class="dt-checkbox styled half-day"
												   value="<?php echo $workDayHalf[$index];?>" <?php if(in_array($workDayHalf[$index],$workStateName)) {?>
												checked="checked" <?php } ?> > Half
										</td>
										<?php
									}

								} else{
									foreach ($workDayFull as $index =>$value) { ?>
										<td class="text-center">
											<input type="checkbox" name="work_week<?= $i; ?>[]"  class="dt-checkbox styled full-day"
												   value="<?php echo $value;?>"> Full </br></br>

											<input type="checkbox" name="work_week<?= $i; ?>[]"  class="dt-checkbox styled half-day"
												   value="<?php echo $workDayHalf[$index];?>"> Half
										</td>
										<?php
									}
								} ?>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</div>

			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="submit-section text-center btn-add">
				<button type="button"
						onclick="window.location.href='<?php echo site_url('MonthlyWeekDays'); ?>'"
						class="btn btn-theme text-white ctm-border-radius button-1 cancel"><?= lang('cancel_btn') ?></button>
				<button class="btn btn-theme text-white ctm-border-radius button-1 submit"><?= lang('submit_btn') ?></button>
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
	var laddaSubmitBtn = Ladda.create(document.querySelector('.submit'));

	$(document).ready(function () {

		numberInit();
		Select2Init();
		SwitcheryKeyGen();
		FileKeyGen();
		CheckboxKeyGen();

		// Initialize
		jQuery.validator.addMethod("lettersonly", function(value, element) {
			return this.optional(element) || /^[a-z ]+$/i.test(value);
		}, "Only Letters are allowed");
		var validator = $("#workWeekDetails").validate({
			ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
			errorClass: 'validation-error-label',
			successClass: 'validation-valid-label',
			highlight: function (element, errorClass) {
				$(element).removeClass(errorClass);
			},
			unhighlight: function (element, errorClass) {
				$(element).removeClass(errorClass);
			},

			// Different components require proper error label placement
			errorPlacement: function (error, element) {

				// Styled checkboxes, radios, bootstrap switch
				if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container')) {
					if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
						error.appendTo(element.parent().parent().parent().parent());
					}
					else {
						error.appendTo(element.parent().parent().parent().parent().parent());
					}
				}

				// Unstyled checkboxes, radios
				else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
					error.appendTo(element.parent().parent().parent());
				}

				// Input with icons and Select2
				else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
					error.appendTo(element.parent());
				}

				// Inline checkboxes, radios
				else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
					error.appendTo(element.parent().parent());
				}

				// Input group, styled file input
				else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
					error.appendTo(element.parent().parent());
				}

				else {
					error.insertAfter(element);
				}
			},
			validClass: "validation-valid-label",
			success: function (label) {
				label.addClass("validation-valid-label").text("Success.")
			},
			rules: {
				title: {
					required: true,
				},
				// days_per_week: {
				// 	required: true,
				// },
				'week_days_name[]': {
					required: true,
				}
			},
			messages: {
				title: {
					required: "Please Enter <?= lang('title') ?>",
				},
				//days_per_week: {
				//	required: "Please Enter <?//= lang('days_per_week') ?>//",
				//},
				'week_days_name[]': {
					required: "Please Select <?= lang('week_days_name') ?>",
				}
			},submitHandler: function (e) {
				$(e).ajaxSubmit({
					url: '<?php echo site_url("MonthlyWeekDays/save");?>',
					type: 'post',
					beforeSubmit: function (formData, jqForm, options) {
						laddaStart();
					},
					complete: function () {
						laddaStop();
					},
					dataType: 'json',
					clearForm: false,
					success: function (resObj) {
						if (resObj.success) {
							swal({
								title: "<?= ucwords(lang('success')); ?>",
								text: resObj.msg,
								confirmButtonColor: "<?= BTN_SUCCESS; ?>",
								type: "<?= lang('success'); ?>"
							}, function () {
								window.location.href = '<?php echo site_url('MonthlyWeekDays');?>';
							});
						} else {
							swal({
								title: "<?= ucwords(lang('error')); ?>",
								text: resObj.msg,
								type: "<?= lang('error'); ?>",
								confirmButtonColor: "<?= BTN_ERROR; ?>"
							});
						}
					}
				});
			}
		});


		$(".full-day").change(function() {
			updateCounter();
		});

		$(".half-day").change(function() {
			updateWeekCounter();
		});
		
		
	});
	function updateCounter() {
		var len = $('.full-day').filter(':checked').length;
		if(len > 0){
			$("#total_full_days").val(len);
		}else{
			$("#total_full_days").val('');
		}
	}

	function updateWeekCounter() {
		var len = $('.half-day').filter(':checked').length;
		if(len > 0){
			$("#total_half_days").val(len);
		}else{
			$("#total_half_days").val('');
		}
	}

</script>
<style type="text/css">
	#total_full_days{
		color: #999999;
		cursor: not-allowed;
	}
	#total_half_days{
		color: #999999;
		cursor: not-allowed;
	}
</style>
