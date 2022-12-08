<div class="accordion add-employee-attendance" id="accordion-details">
	<?php
	//create  form open tag
	$form_id = array(
			'id'=>'leaveManageDetails',
			'method'=>'post',
			'class'=>'form-horizontal'
	);
	echo form_open_multipart('', $form_id);
	$leaveReasonManageId = (isset($getLeaveReasonManageData['time_sheet_id']) && ($getLeaveReasonManageData['time_sheet_id'] != '')) ? $getLeaveReasonManageData['time_sheet_id'] : '';
	?>
	<input type="hidden" name="time_sheet_id" value="<?= $leaveReasonManageId ?>" id="time_sheet_id">
	<?php
	if((isset($timeDetails['fill_minutes']) || isset($timeDetails['fill_hours']) ) && (isset($timeDetails['fill_date']))){
		echo '<div><marquee behavior="alternate" direction="left" class="time-info-rotate" scrolldelay = "50" style="margin-bottom:5px; color: floralwhite;">
			Your Time Sheet of Date '.@$timeDetails['fill_date'].' of '.ltrim(@$timeDetails['fill_hours'], '0').' '.ltrim(@$timeDetails['fill_minutes'], '0').' has been left. Please Fill that First</marquee></div>';
	} elseif ((isset($timeDetails['fill_minutes']) || isset($timeDetails['fill_hours']) ) && (isset($timeDetails['fill_date']))){
		echo '<div><marquee behavior="alternate" direction="left" class="time-info-rotate" scrolldelay = "50" style="margin-bottom:5px">
			Your Time Sheet of Date '.@$timeDetails['fill_date'].' of '.ltrim(@$timeDetails['fill_hours'], '0').' '.ltrim(@$timeDetails['fill_minutes'], '0').' has been left. Please Fill that First</marquee></div>';
	}
	?>

</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic5">
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-five">
				<?= lang('fill_leave') ?>
			</a>
		</h4>
		<?php
		if(isset($timeDetails['fill_date'])){
			$date = $timeDetails['fill_date'];
		} else{
			$date = date(PHP_DATE_FORMATE);;
		}
		?>
	</div>
	<div class="card-body p-0"><br/>
		<div id="basic-five" class="collapse show ctm-padding" aria-labelledby="headingFive"
			 data-parent="#accordion-details">
			<div class="row">

				<div class="col-md-12 form-group">
					<legend><small><?= lang('fill_leave') ?></small></legend>
				</div>

				<div class="col-md-12 form-group">
					<span><?= lang('leave_reason') ?><span class="text-danger"> * </span></span>
					<select name="leave_reason_id" id="leave_reason_id" class="form-control" placeholder="Select <?= lang('leave_reason') ?>">
						<option value=""></option>
					</select>
				</div>

				<?php if(isset($timeDetails['fill_date'])){ ?>

					<div class="col-md-6 form-group">
						<span><?= lang('time_sheet_date') ?><span class="text-danger"> * </span></span>
						<input type="text" class="form-control" id="time_sheet_date" name="time_sheet_date"
							   value="<?= $timeDetails['fill_date']; ?>"
							   placeholder="Select a <?= lang('time_sheet_date') ?>" readonly>
						<input type="hidden" id="sheet_active_date" name="sheet_active_date" value=""/>
					</div>
				<?php } else{ ?>
					<div class="col-md-6 form-group">
						<span><?= lang('time_sheet_date') ?><span class="text-danger"> * </span></span>
						<input type="text" class="form-control dtTimePicker" id="time_sheet_date" name="time_sheet_date"
							   value="<?= (isset($getTimeSheetData['time_sheet_date']) && ($getTimeSheetData['time_sheet_date'] != '')) ? $getTimeSheetData['time_sheet_date'] : date(PHP_DATE_FORMATE); ?>"
							   placeholder="Select a <?= lang('time_sheet_date') ?>" readonly>
						<input type="hidden" id="sheet_active_date" name="sheet_active_date" value=""/>
					</div>
				<?php } ?>

				<?php if(isset($timeDetails['fill_time'])){ ?>
					<div class="col-md-6 form-group">
						<span><?= lang('total_hours') ?><span class="text-danger"> * </span></span>
						<input type="text"name="hours" id="hours" class="form-control"
							   value="<?=  $timeDetails['fill_time']; ?>"
							   placeholder="Enter <?= lang('total_hours') ?>">
					</div>
				<?php } else {  ?>
					<div class="col-md-6 form-group">
						<span><?= lang('leave_hours') ?><span class="text-danger"> * </span></span>
						<input type="text" name="hours" id="hours" class="form-control"
							   value="<?= (isset($getLeaveReasonManageData['hours']) && ($getLeaveReasonManageData['hours'] != '')) ? $getLeaveReasonManageData['hours'] : ''; ?>"
							   placeholder="Enter <?= lang('leave_hours') ?>">
					</div>
				<?php } ?>
				<div class="col-md-12 form-group">
					<span><?= lang('note') ?><span class="text-danger"> * </span></span>
					<textarea name="note" id="note" placeholder="Enter Only 255 Character" class="form-control" rows="5" cols="5"><?php echo (isset($getLeaveReasonManageData['note']) && ($getLeaveReasonManageData['note'] != '')) ? $getLeaveReasonManageData['note'] : ''; ?></textarea>
					<label id="note-error" class="validation-error-label" for="note"></label>
				</div>

			</div>
		</div>
	</div>

	<?php
	$employeeId = $this->session->userdata('emp_id');
	if(!empty($getLeaveReasonManageData)){
		$attDate = $getLeaveReasonManageData['time_sheet_date'];
		$returnUrl = site_url('TimeSheet/getTimeSheetDetails/'.$employeeId.'/'.$attDate);
	}else{
		if(isset($timeDetails['fill_date'])){
			$date = $timeDetails['fill_date'];
		}else{
			$date = date(PHP_DATE_FORMATE);
		}
		$returnUrl = site_url('TimeSheet/getTimeSheetDetails/'.$employeeId.'/'.$date);
	}
	?>
	<div class="row">
		<div class="col-12">
			<div class="submit-section text-center btn-add">
				<button type="button"
						class="btn btn-theme text-white ctm-border-radius button-1 cancle"
						onclick="window.location.href='<?php echo $returnUrl; ?>'"><?= lang('cancel_btn') ?></button>
				<button type="submit"
						class="btn btn-theme text-white ctm-border-radius button-1 submit"><?= lang('submit_btn') ?></button>
			</div>
		</div>
	</div>
	<?php echo form_close(); ?>
</div>

<script>
	var laddaSubmitBtn = Ladda.create(document.querySelector('.submit'));

	$(document).ready(function () {

		numberInit();
		Select2Init();
		SwitcheryKeyGen();
		FileKeyGen();
		CheckboxKeyGen();
		leaveReasonDD('','#leave_reason_id');

		// Initialize
		jQuery.validator.addMethod("lettersonly", function(value, element) {
			return this.optional(element) || /^[a-z ]+$/i.test(value);
		}, "Only Letters are allowed");
		jQuery.validator.addMethod("numberSpecialCharacter", function(value, element) {
			return this.optional(element) || /^[0-9:. ]+$/i.test(value);
		}, "Only . : and Numbers are allowed");


		var validator = $("#leaveManageDetails").validate({
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
				leave_reason_id: {
					required: true,
				},
				hours: {
					required: true,
					numberSpecialCharacter : true
				},
				time_sheet_date: {
					required: true,
					validDate: true
				},
				note: {
					required: true,
				},
			},
			messages: {
				leave_reason_id: {
					required: "Please Select <?= lang('leave_reason') ?>",
				},
				time_sheet_date: {
					required: "Please Enter <?= lang('leave_manage_date') ?>"
				},
				hours: {
					required: "Please Enter <?= lang('leave_hours') ?>"
				},
				note: {
					required: "Please Enter <?= lang('note') ?>"
				},
			},submitHandler: function (e) {
				$(e).ajaxSubmit({
					url: '<?php echo site_url("TimeSheet/leaveSave");?>',
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
								window.location.href = '<?php echo $returnUrl; ?>';
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
		if($("#time_sheet_date").hasClass('dtTimePicker')){
			$("#time_sheet_date").datepicker({
				dateFormat: 'dd-mm-yy',
				todayBtn:  "linked",
				autoclose: true,
				maxDate: 0,
				todayHighlight: true,
				changeMonth: true,
				changeYear: true,
				onSelect: function (value, ui) {
					if ($(this).valid()) {
						$(this).removeClass('invalid').addClass('success');
					}
					var timeSheetDate = $(this).val();
					$('#sheet_active_date').val(timeSheetDate);
				}
			});
		}

		<?php if((isset($getLeaveReasonManageData['leave_reason_name']) && !empty($getLeaveReasonManageData['leave_reason_name']))){ ?>
		var option = new Option("<?= $getLeaveReasonManageData['leave_reason_name']; ?>", "<?= $getLeaveReasonManageData['leave_reason_id']; ?>", true, true);
		$('#leave_reason_id').append(option).trigger('change');
		<?php } ?>


	});

	$.validator.addMethod("validDate", function(value) {
		var currVal = value;
		if(currVal == '')
			return false;

		var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; //Declare Regex
		var dtArray = currVal.match(rxDatePattern); // is format OK?

		if (dtArray == null)
			return false;

		//Checks for mm/dd/yyyy format.
		dtDay = dtArray[1];
		dtMonth = dtArray[3];
		dtYear = dtArray[5];

		if (dtMonth < 1 || dtMonth > 12)
			return false;
		else if (dtDay < 1 || dtDay> 31)
			return false;
		else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31)
			return false;
		else if (dtMonth == 2)
		{
			var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
			if (dtDay> 29 || (dtDay ==29 && !isleap))
				return false;
		}
		return true;
	}, 'Please enter a valid birth date');
</script>
<?php if (isset($select2)) { ?>
	<?= $select2 ?>
<?php } ?>
