<div class="accordion add-employee-attendance" id="accordion-details">
	<?php
	//create  form open tag
	$form_id = array(
			'id'=>'employeeAttendanceCorrectionManage',
			'method'=>'post',
			'class'=>'form-horizontal'
	);
	echo form_open_multipart('', $form_id);
	$employeeAttendanceId = (isset($getEmployeeAttendanceData['employee_attendance_id']) && ($getEmployeeAttendanceData['employee_attendance_id'] != '')) ? $getEmployeeAttendanceData['employee_attendance_id'] : '';
	$attendanceCorrectionId = (isset($getEmployeeAttendanceData['attendance_correction_id']) && ($getEmployeeAttendanceData['attendance_correction_id'] != '')) ? $getEmployeeAttendanceData['attendance_correction_id'] : '';
	?>
	<input type="hidden" name="employee_attendance_id" value="<?= $employeeAttendanceId ?>" id="employee_attendance_id">
	<input type="hidden" name="attendance_correction_id" value="<?= $attendanceCorrectionId ?>" id="attendance_correction_id">
</div>
<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic2">
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-two">
				<b>EMPLOYEE ATTENDANCE CORRECTION FORM</b>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-two" class="collapse show ctm-padding" aria-labelledby="#basic2"
			 data-parent="#accordion-details">
			<div class="row">
				<div class="col-4 form-group">
					<span><?= lang('employee_name') ?></span>
					<select name="emp_id" id="emp_id" class="form-control" placeholder="Select <?= lang('employee_name') ?> ">
						<option value=""></option>
					</select>
					<input type="hidden" id="emp_active_id" name="emp_active_id" value=""/>
				</div>
				<div class="col-4 form-group">
					<span><?= lang('old_login_time') ?></span>
					<input type="text" class="form-control dtTimePicker" id="old_login_time"
						   name="old_login_time" value="<?= (isset($getEmployeeAttendanceData['old_login_time'])) ? $getEmployeeAttendanceData['old_login_time'] : date(PHP_TIME_FORMATE); ?>"
						   placeholder="Select a <?= lang('login_time') ?>" disabled="disabled">
				</div>
				<div class="col-4 form-group">
					<span><?= lang('new_login_time') ?></span>
					<input type="text" class="form-control dtTimePicker" id="login_time"
						   name="login_time" value="<?= (isset($getEmployeeAttendanceData['login_time'])) ? $getEmployeeAttendanceData['login_time'] : date(PHP_TIME_FORMATE); ?>"
						   placeholder="Select a <?= lang('login_time') ?>" readonly>
				</div>
				<div class="col-4 form-group">
					<span><?= lang('old_logout_time') ?></span>
					<input type="text" class="form-control dtTimePicker" id="old_logout_time"
						   name="old_logout_time" value="<?= (isset($getEmployeeAttendanceData['old_logout_time'])) ? $getEmployeeAttendanceData['old_logout_time'] : date(PHP_TIME_FORMATE); ?>"
						   placeholder="Select a <?= lang('logout_time') ?>" disabled="disabled">
				</div>
				<div class="col-4 form-group">
					<span><?= lang('new_logout_time') ?></span>
					<input type="text" class="form-control dtTimePicker" id="logout_time"
						   name="logout_time" value="<?= (isset($getEmployeeAttendanceData['logout_time'])) ? $getEmployeeAttendanceData['logout_time'] : date(PHP_TIME_FORMATE); ?>"
						   placeholder="Select a <?= lang('logout_time') ?>" readonly>
				</div>
				<div class="col-4 form-group">
					<span><?= lang('attendance_date') ?></span>
					<input type="text" class="form-control" id="attendance_date" name="attendance_date"
						   value="<?= (isset($getEmployeeAttendanceData['attendance_date'])) ? $getEmployeeAttendanceData['attendance_date'] : date(PHP_DATE_FORMATE); ?>"
						   placeholder="Select a <?= lang('attendance_date') ?>" readonly>
					<input type="hidden" id="attendance_active_date" name="attendance_active_date" value=""/>
				</div>

				<?php
				$role = $this->session->userdata('role');
				if($role == 'admin'){
					?>
					<div class="col-4 form-group">
						<span class="col-md-6 control-label"><?= lang('approved') ?></span>
						<div class="col-lg-9">
							<div class="checkbox checkbox-switchery switchery-xs">
								<label>
									<input type="checkbox"
										   name="approved" <?php if (isset($getEmployeeAttendanceData['approved']) && $getEmployeeAttendanceData['approved'] == 'true') {
										echo 'checked="checked"';
									} else {
										echo '';
									} ?> id="approved" class="switchery">
									<?php if (isset($getEmployeeLeaveData['approved']) && $getEmployeeLeaveData['approved'] == 'true') {
										$status = "Approved";
									}else{
										$status = "Unapproved";
									}?>
									<span class="approve_status_text" style="margin-left:20px;"><?= $status; ?></span>
								</label>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-md-6 control-label"><?= lang('rejected') ?></label>
						<div class="col-lg-9">
							<div class="checkbox checkbox-switchery switchery-xs">
								<label>
									<input type="checkbox"
										   name="rejected" <?php if (isset($getEmployeeAttendanceData['rejected']) && $getEmployeeAttendanceData['rejected'] == 'true') {
										echo 'checked="checked"';
									} else {
										echo '';
									} ?> id="rejected" class="switchery">
									<?php if (isset($getEmployeeLeaveData['rejected']) && $getEmployeeLeaveData['rejected'] == 'true') {
										$status = "Rejected";
									}else{
										$status = "Not Rejected";
									}?>
									<span class="rejected_status_text" style="margin-left:20px;"><?= $status; ?></span>
								</label>
							</div>
						</div>
					</div>

					<div class="col-md-12 form-group" id ="note-block" style="display:none;">
						<span><?= lang('note') ?><span class="text-danger"> * </span></span>
						<textarea name="note" id="note" placeholder="" class="form-control" rows="5" cols="20"><?php echo (isset($getEmployeeAttendanceData['note']) && ($getEmployeeAttendanceData['note'] != '')) ? $getEmployeeAttendanceData['note'] : ''; ?></textarea>
						<label id="description-error" class="validation-error-label" for="description"></label>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php
	$empId = $this->session->userdata('emp_id');
	if($role != 'admin' ){
		$url = site_url('EmployeeAttendance/getAttendanceCorrectionData/'.$empId.'/'.$attendanceCorrectionId);
	}else{
		$url = site_url('EmployeeAttendance/attendanceCorrection');
	}


	?>
	<div class="row">
		<div class="col-12">
			<div class="submit-section text-center btn-add">
				<button type="button"
						class="btn btn-theme text-white ctm-border-radius button-1 cancle"
						><?= lang('cancel_btn') ?></button>
				<button type="submit"
						class="btn btn-theme text-white ctm-border-radius button-1 submit"><?= lang('submit_btn') ?></button>
			</div>
		</div>
	</div>
	<?php echo form_close(); ?>
</div>

<!-- Added hiden form to post some variable in details form which will help to set url for back button -->
<form method="post" name="redirect" class="redirect" action="">
	<input type="hidden" class="postdata" name="postdata" value="listing">
	<input type="submit" style="display: none;">
</form>

<script>
	var laddaSubmitBtn = Ladda.create(document.querySelector('.submit'));

	$(document).ready(function () {
		<!-- Added hiden form to post some variable in details form which will help to set url for back button -->
		<?php if(isset($_POST['postdata']) && $_POST['postdata'] == 'correctionpage'){ ?>
		$('.cancel').click(function(e){
			e.preventDefault();
			var link = '<?= $url ?>' ;
			$(".redirect").attr('action', link);
			$('.postdata').attr("value",'correctionpage');
			$('.redirect').submit();
		});
		<?php }else{ ?>
		
		$('.cancel').click(function(){
			window.location.href='<?php echo $url; ?>';
		});
		
		<?php } ?>
		
		numberInit();
		Select2Init();
		SwitcheryKeyGen();
		FileKeyGen();
		CheckboxKeyGen();
		TimePickerInit();
		employeeNameDD('','#emp_id');
		// Initialize
		jQuery.validator.addMethod("lettersonly", function(value, element) {
			return this.optional(element) || /^[a-z ]+$/i.test(value);
		}, "Only Letters are allowed");
		var currentDate = $('#attendance_date').val();
		$('#attendance_active_date').val(currentDate);

		jQuery.validator.addMethod('greater', function (value, element, param) {
			//return this.optional(element) || parseInt(value) > parseInt($(param).val());
			return this.optional(element) || (parseInt(value.split(':')[0], 10) > parseInt($(param).val().split(':')[0], 10) ||
				parseInt(value.split(':')[1], 10) > parseInt($(param).val().split(':')[1], 10) ||
				parseInt(value.split(':')[2], 10) > parseInt($(param).val().split(':')[2], 10));
		}, 'Invalid value');

		var validator = $("#employeeAttendanceCorrectionManage").validate({
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
				emp_id: {
					required: true,
				},
				login_time: {
					required: true,
				},
				logout_time: {
					required: true,
					greater: '#login_time'
				},
				total_time: {
					required: true,
				},
				attendance_date: {
					required: true,
				},
			},
			messages: {
				emp_id: {
					required: "Please Enter <?= lang('employee_name') ?>",
				},
				login_time: {
					required: "Please Select <?= lang('login_time') ?>",
				},
				logout_time: {
					required: "Please Select <?= lang('logout_time') ?>",
					greater: "Please select <?= lang('logout_time') ?> greated than <?= lang('login_time') ?>",
				},
				total_time: {
					required: "Please Enter <?= lang('total_time') ?>",
				},
				attendance_date: {
					required: "Please Select <?= lang('attendance_date') ?>",
				},
			},submitHandler: function (e) {
				//alert("here");
				$(e).ajaxSubmit({
					url: '<?php echo site_url("EmployeeAttendance/saveAttendanceCorrection"); ?>',
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
								<?php if(isset($_POST['postdata']) && $_POST['postdata'] == 'correctionpage'){ ?>
									var link = '<?= $url ?>' ;
									$(".redirect").attr('action', link);
									$('.postdata').attr("value",'correctionpage');
									$('.redirect').submit();
								<?php }else{ ?>
									window.location.href = '<?php echo $url; ?>';
								<?php } ?>
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

		<?php if((isset($getEmployeeAttendanceData['emp_name']) && !empty($getEmployeeAttendanceData['emp_name']))){ ?>
		var option = new Option("<?= $getEmployeeAttendanceData['emp_name']; ?>", "<?= $getEmployeeAttendanceData['emp_id']; ?>", true, true);
		$('#emp_id').append(option).trigger('change');
		$('#emp_id').prop('disabled', true);
		$('#emp_active_id').val('<?= $getEmployeeAttendanceData['emp_id']; ?>');
		$('#attendance_active_date').val('<?= $getEmployeeAttendanceData['attendance_date']; ?>');
		$('#attendance_date').prop('disabled', true);
		$('#attendance_date').css('color', '#999999');
		<?php } ?>


		$("#attendance_date").datepicker({
			dateFormat: 'dd-mm-yy',
			todayBtn:  "linked",
			autoclose: true,
			todayHighlight: true,
			changeMonth: true,
			changeYear: true,
			onSelect: function (value, ui) {
				if ($(this).valid()) {
					$(this).removeClass('invalid').addClass('success');
				}
				var attendanceDate = $(this).val();
				$('#attendance_active_date').val(attendanceDate);
			}
		});
		
		$('#emp_id').change(function () {
			var empId = $(this).val();
			$('#emp_active_id').val(empId);
		});


		$("#logout_time").change(function(){
			calculateTime();
		});
		
		
		$('#rejected').click(function() {
			if($(this).is(":checked")){
				$(".rejected_status_text").text('Rejected');
				if($('#approved').is(":checked")){
					$('#approved').click();
				}
				$('#note-block').show();
			}else{
				$('#note-block').hide();
				$(".rejected_status_text").text('Non Rejected');
			}
		});
		
		$('#approved').click(function() {
			if($(this).is(":checked")){
				$(".approve_status_text").text('Approved');
				if($('#rejected').is(":checked")){
					$('#rejected').click();
				}
			}else{
				$(".approve_status_text").text('Unapproved');
			}
			$('#note-block').hide();
		});
		
		
	});
	
	function calculateTime(){
			var hours = parseInt($("#logout_time").val().split(':')[0], 10) - parseInt($("#login_time").val().split(':')[0], 10);

			var mins2 = parseInt($("#logout_time").val().split(':')[1], 10),
				mins1 =  parseInt($("#login_time").val().split(':')[1], 10)
			if(mins2 >= mins1) {
				minutes = mins2 - mins1;
			}
			else {
				minutes = (mins2 + 60) - mins1;
				hours--;
			}


			var sec2 = parseInt($("#logout_time").val().split(':')[2], 10),
				sec1 =  parseInt($("#login_time").val().split(':')[2], 10)
			if(sec2 >= sec1) {
				seconds = sec2 - sec1;
			}
			else {
				seconds = (sec2 + 60) - sec1;
				minutes--;
			}

			hours = ('0' + hours).slice(-2);
			minutes = ('0' + minutes).slice(-2);
			seconds = ('0' + seconds).slice(-2);

			if(hours > 0 || minutes > 0 || seconds > 0)
				$("#total_time").val(hours+":"+minutes+":"+seconds);
		}
</script>
<style type="text/css">
input[type=text][disabled="disabled"]
{
            color: #999999;
}
</style>
<?php if (isset($select2)) { ?>
	<?= $select2 ?>
<?php } ?>
