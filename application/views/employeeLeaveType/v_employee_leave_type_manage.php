<div class="accordion add-employee-attendance" id="accordion-details">
	<?php

	//create  form open tag
	$form_id = array(
			'id' => 'employeeLeaveTypeDetails',
			'method' => 'post',
			'class' => 'form-horizontal',
	);
	echo form_open_multipart('', $form_id);
	$leaveTypeId = (isset($getEmployeeLeaveData['leave_id']) && ($getEmployeeLeaveData['leave_id'] != '')) ? $getEmployeeLeaveData['leave_id'] : '' ?>
	<?php
	if($leaveTypeId != ''){
		$leaveTypeData['leave_taken'] = $leaveTypeData['leave_taken'] - $getEmployeeLeaveData['no_of_days'];
		if(strtolower($leaveTypeData['available_leave']) != 'unlimited'){
			$leaveTypeData['available_leave'] = $leaveTypeData['available_leave'] + $getEmployeeLeaveData['no_of_days'];
		}
	}

	?>
	<input type="hidden" name="leave_id" value="<?= $leaveTypeId ?>" id="leave_id">
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic5">
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-five">
				<?= lang('employee_leave_type_heading') ?>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-five" class="collapse show ctm-padding" aria-labelledby="headingFive"
			 data-parent="#accordion-details">
			<div class="row">
				<div class="col-md-6 form-group">
					<span><?= lang('name') ?><span class="text-danger"> * </span></span>
					<select name="emp_id" id="emp_id" class="form-control" placeholder="Select <?= lang('employee_name') ?>">
						<option value=""></option>
					</select>
					<input type="hidden" id="emp_active_id" name="emp_active_id" value=""/>
				</div>

				<div class="col-md-6 form-group">
					<span><?= lang('leave_type') ?><span class="text-danger"> * </span></span>
					<select name="leave_type_id" id="leave_type_id" class="form-control" placeholder="Select <?= lang('leave_type') ?>">
						<option value=""></option>
					</select>
				</div>

				<table id="leaveData" class="table hideRemainingDays">
					<thead>
					<tr>
						<th> Total Leave </th>
						<th> No of Leave Taken </th>
						<th> Available Leave </th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td id="total_leave"><?php echo (isset($leaveTypeData['total_leave']) && ($leaveTypeData['total_leave'] != '')) ? $leaveTypeData['total_leave'] : 0;  ?></td>
						<td id="leave_taken"><?php echo (isset($leaveTypeData['leave_taken']) && ($leaveTypeData['leave_taken'] != '')) ? $leaveTypeData['leave_taken'] : 0;  ?></td>
						<td id="available_leave"><?php echo (isset($leaveTypeData['available_leave']) && ($leaveTypeData['available_leave'] != '')) ? $leaveTypeData['available_leave'] : 0;  ?> </td>
						<?php
						$availableLeave = (isset($leaveTypeData['available_leave']) && ($leaveTypeData['available_leave'] != '')) ? $leaveTypeData['available_leave'] : 0;
						if(strtolower($availableLeave) == 'unlimited'){
							$availableLeave = '500';
						}
						?>
						<input type="hidden" id="available_total_leave" name="available_total_leave" value="<?php echo $availableLeave; ?>" />
					</tr>
					</tbody>
				</table>
				<?php if(isset($this->session->userdata['role']) && ($this->session->userdata['role'] == 'admin')){
					$setDate = date(PHP_DATE_FORMATE);
				} else{
					$setDate = date(PHP_DATE_FORMATE,strtotime('tomorrow'));
				}
				?>

				<div class="col-md-6 form-group">
					<span><?= lang('leave_from_date') ?><span class="text-danger"> * </span></span>
					<input type="text" class="form-control" id="leave_from_date" name="leave_from_date"
						   value="<?= (isset($getEmployeeLeaveData['leave_from_date'])) ? $getEmployeeLeaveData['leave_from_date'] : $setDate; ?>"
						   placeholder="Select a <?= lang('leave_from_date') ?>" readonly >
				</div>

				<div class="col-md-6 form-group">
					<span><?= lang('leave_to_date') ?><span class="text-danger"> * </span></span>
					<input type="text" class="form-control" id="leave_to_date" name="leave_to_date"
						   value="<?= (isset($getEmployeeLeaveData['leave_to_date'])) ? $getEmployeeLeaveData['leave_to_date'] : $setDate; ?>"
						   placeholder="Select a <?= lang('leave_to_date') ?>" readonly>
				</div>

				<div class="col-md-6 form-group">
					<span><?= lang('no_of_days') ?><span class="text-danger"> * </span></span>
					<input type="text" name="no_of_days"
						   value="<?= (isset($getEmployeeLeaveData['no_of_days']) && ($getEmployeeLeaveData['no_of_days'] != '')) ? $getEmployeeLeaveData['no_of_days'] : ''; ?>"
						   id="no_of_days" class="form-control" placeholder="Enter <?= lang('no_of_days') ?>">
				</div>

				<div class="col-md-6 form-group" id="half_day_block" style="display:none;">
					<span style="display:block;"><?= lang('half_day') ?><span class="text-danger"> * </span></span>
						<?php if(isset($getEmployeeLeaveData['half_day']) &&  strtolower($getEmployeeLeaveData['half_day']) == "second_half"){?>
							<span><input type="radio" name="half_day" id="half_day" value = "first_half"> First Half</span>
							<span><input type="radio" name="half_day" id="half_day" value = "second_half" style="margin-left:60px;" checked> Second Half</span>
						<?php } else{ ?>
							<span><input type="radio" name="half_day" id="half_day" value = "first_half" checked> First Half</span>
							<span><input type="radio" name="half_day" id="half_day" value = "second_half" style="margin-left:60px;"> Second Half</span>
						<?php } ?>
				</div>

				<div class="col-md-12 form-group">
					<span><?= lang('leave_reason') ?><span class="text-danger"> * </span></span>
					<textarea name="leave_reason" id="leave_reason" placeholder="Enter Only 255 Character" class="form-control" rows="5" cols="5"><?php echo (isset($getEmployeeLeaveData['leave_reason']) && ($getEmployeeLeaveData['leave_reason'] != '')) ? $getEmployeeLeaveData['leave_reason'] : ''; ?></textarea>
					<label id="leave_reason-error" class="validation-error-label" for="leave_reason"></label>
				</div>

				<?php
				if(isset($this->session->userdata['role']) && ($this->session->userdata['role'] != 'employee') && ($this->session->userdata['role'] != 'team leader')   && ($this->session->userdata['emp_id'])){ ?>
					<div class="col-4 form-group">
						<span class="col-md-6 control-label"><?= lang('approved') ?></span>
						<div class="col-lg-9">
							<div class="checkbox checkbox-switchery switchery-xs">
								<label>
									<input type="checkbox"
										   name="approved" <?php if (isset($getEmployeeLeaveData['is_active']) && $getEmployeeLeaveData['is_active'] == 1) {
										echo 'checked="checked"';
									} else {
										echo '';
									} ?> id="approved" class="switchery">
									<?php if (isset($getEmployeeLeaveData['is_active']) && $getEmployeeLeaveData['is_active'] == 1) {
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
										   name="rejected" <?php if (isset($getEmployeeLeaveData['is_rejected']) && $getEmployeeLeaveData['is_rejected'] == 1) {
										echo 'checked="checked"';
									} else {
										echo '';
									} ?> id="rejected" class="switchery">
									<?php  if (isset($getEmployeeLeaveData['is_rejected']) && $getEmployeeLeaveData['is_rejected'] == 1) {
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
						<textarea name="note" id="note" placeholder="" class="form-control" rows="5" cols="20"><?php echo (isset($getEmployeeLeaveData['note']) && ($getEmployeeLeaveData['note'] != '')) ? $getEmployeeLeaveData['note'] : ''; ?></textarea>
						<label id="description-error" class="validation-error-label" for="description"></label>
					</div>

				<?php } ?>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="submit-section text-center btn-add">
				<button type="button"
						class="btn btn-theme text-white ctm-border-radius button-1 cancle"
						onclick="window.location.href='<?php echo site_url('EmployeeLeaveType'); ?>'"><?= lang('cancel_btn') ?></button>
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
		// Full featured editor
		numberInit();
		Select2Init();
		SwitcheryKeyGen();
		leaveTypeDD('','#leave_type_id');
		employeeNameDD('','#emp_id');
		TimePickerInit();
		FileKeyGen();

		var leaveTypeId = $("#leave_type_id").val();

		var remain 	    = $('.hideRemainingDays');
		if(leaveTypeId != 0){
			remain.show();
		} else{
			remain.hide();

		}

		var totalDays = '<?= (isset($getEmployeeLeaveData['no_of_days']) && ($getEmployeeLeaveData['no_of_days'] != '')) ? $getEmployeeLeaveData['no_of_days'] : '';  ?>';
		if(totalDays == ""){
			$("#no_of_days").val(daydiff(parseDate($('#leave_from_date').val()), parseDate($('#leave_to_date').val())));
		} else{
			$("#no_of_days").val(totalDays);
		}

		$("#leave_from_date").datepicker({
			dateFormat: "<?= DATE_FORMATE ?>",
			todayBtn:  "linked",
			autoclose: true,
			minDate: "<?= (isset($this->session->userdata['role']) && ($this->session->userdata['role'] == 'admin') && ($this->session->userdata['role'] == 'hr')) ? NULL : + 1 ; ?>",
			todayHighlight: true,
			onSelect: function(selected) {

				$("#leave_to_date").datepicker("option", "minDate", selected)
				if ($(this).valid()) {
					$(this).removeClass('invalid').addClass('success');
				}
				var id = $(this).attr('id');
				$("input[name="+id+"]").val($(this).val());
				$("#no_of_days").val(
					daydiff(parseDate($('#leave_from_date').val()), parseDate($('#leave_to_date').val()))
				);

				var empId = $("#emp_id").val();
				var leaveTypeId = $("#leave_type_id").val();
				var fromYear = extractYear($(this).val());
				var toYear = extractYear($("#leave_to_date").val());
				if(leaveTypeId != 0 && empId != 0 && (fromYear == toYear)){
					getTotalDays(leaveTypeId,empId,fromYear);
				}
			}
		});
		$("#leave_to_date").datepicker({
			dateFormat: "<?= DATE_FORMATE; ?>",
			todayBtn:  "linked",
			autoclose: true,
			todayHighlight: true,
			minDate: $("#leave_from_date").val(),
			onSelect: function(selected) {
				if ($(this).valid()) {
					$(this).removeClass('invalid').addClass('success');
				}

				var id = $(this).attr('id');
				$("input[name="+id+"]").val($(this).val());
				$("#no_of_days").val(
					daydiff(parseDate($('#leave_from_date').val()), parseDate($('#leave_to_date').val()))
				);

				var leaveTypeId = $("#leave_type_id").val();
				var empId = $("#emp_id").val();
				var toYear = extractYear($(this).val());
				var fromYear = extractYear($("#leave_from_date").val());

				if(leaveTypeId != 0 && empId != 0 && (fromYear == toYear)){
					getTotalDays(leaveTypeId,empId,fromYear);
				}
			}
		});


		// Initialize
		jQuery.validator.addMethod("lettersonly", function(value, element) {
			return this.optional(element) || /^[a-z ]+$/i.test(value);
		}, "Only Letters are allowed");

		jQuery.validator.addMethod('dateRange', function (value, element, param) {
			return this.optional(element) || daydiff(parseDate($('#leave_from_date').val()), parseDate($('#leave_to_date').val())) <= ($("#available_total_leave").val())
			}, 'Invalid value');


		jQuery.validator.addMethod('sameYear', function (value, element, param) {
			return this.optional(element) || (extractYear(value)) == (extractYear($(param).val()))
		}, 'Invalid value');

		jQuery.validator.addMethod('requiredFirst', function (value, element, param) {
			return this.optional(element) || ($(param).val() != '')
		}, 'First select leave type');


		jQuery.validator.addMethod('lessValue', function (value, element, param) {
			return this.optional(element) || parseInt(value) <= parseInt($(param).val());
		}, 'Invalid value');

		var validator = $("#employeeLeaveTypeDetails").validate({
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
				leave_type_id:{
					required: true
				},
				leave_from_date:{
					required: true,
					requiredFirst : '#leave_type_id',
					sameYear: '#leave_from_date'
				},

				leave_to_date:{
					required: true,
					requiredFirst : '#leave_type_id',
					sameYear: '#leave_from_date',
					dateRange : '#available_total_leave'
				},
				no_of_days: {
					required: true
				},
				leave_reason: {
					required: true
				},
			},
			messages: {
				leave_type_id:{
					required: "Please select <?= lang('leave_type')?>"
				},
				leave_from_date:{
					required: "Please Select <?= lang('leave_from_date')?>",
					sameYear: "You can take leave within the same year. For another year, you have to apply again."
				},
				leave_to_date:{
					required: "Please Select <?= lang('leave_to_date')?>",
					sameYear: "You can take leave within the same year. For another year, you have to apply again.",
					dateRange: "You can't take leave more than available leave days."
				},
				no_of_days: {
					required: "Please Enter <?= lang('no_of_days')?>",
				},
				leave_reason: {
					required: "Please Enter <?= lang('leave_reason')?>"
				}
			},
			submitHandler: function (e) {
				var role = '<?php echo $this->session->userdata['role']; ?>';
				$(e).ajaxSubmit({
					url: '<?php echo site_url("EmployeeLeaveType/save");?>',
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
								if(role != '' && role == 'admin' && role == 'hr'){
									window.location.href = '<?php echo site_url('EmployeeLeaveType'); ?>';
								}else{
									window.location.href = '<?php echo site_url('EmployeeLeaveType/index/'.$this->session->userdata['emp_id']);?>';
								}
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


		$('#rejected').click(function() {
			if($(this).is(":checked")){
				$(".rejected_status_text").text('Rejected');
				if($('#approved').is(":checked")){
					$('#approved').click();
				}
				$('#note-block').show();
			} else{
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

		var days = $('#no_of_days').val();
		if (days.indexOf('.') != -1 || days.indexOf(':') != -1) {
				var myString = days.substr(days.indexOf(".") + 1);
				if(myString != "0")
					$('#half_day_block').show();
				else
					$('#half_day_block').hide();
		}

		$('#no_of_days').change(function(){
			var days = $(this).val();
			if (days.indexOf('.') != -1 || days.indexOf(':') != -1) {
				var myString = days.substr(days.indexOf(".") + 1);
				if(myString != "0")
					$('#half_day_block').show();
				else
					$('#half_day_block').hide();
			}else{
			  $('#half_day_block').hide();
			}
		});
	});


	function parseDate(str) {
		var mdy = str.split('-')
		return new Date(mdy[2], mdy[1]-1, mdy[0]);
	}

	function daydiff(first, second) {
		var total = ((second-first)/(1000*60*60*24)) + 1;
		if(total > 0)
			return total;
		else
			return "";
	}

	function extractYear(str) {
		var mdy = str.split('-')
		return mdy[2];
	}

	$.validator.addMethod("validDate", function(value) {
		var currVal = value;
		if(currVal == '')
			return false;

		var rxDatePattern   = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; //Declare Regex
		var dtArray 		= currVal.match(rxDatePattern); // is format OK?

		if (dtArray == null)
			return false;

		//Checks for mm/dd/yyyy format.
		dtDay 	= dtArray[1];
		dtMonth = dtArray[3];
		dtYear 	= dtArray[5];

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
	}, 'Please Enter a Valid Date');

		<?php if((isset($getEmployeeLeaveData['leave_type']) && !empty($getEmployeeLeaveData['leave_type']))){ ?>
		var option = new Option("<?= $getEmployeeLeaveData['leave_type']; ?>", "<?= $getEmployeeLeaveData['leave_type_id']; ?>", true, true);
		$('#leave_type_id').append(option).trigger('change');
		<?php } ?>

		<?php if((isset($getEmployeeLeaveData['emp_name']) && !empty($getEmployeeLeaveData['emp_name']))){ ?>
		var option = new Option("<?= $getEmployeeLeaveData['emp_name']; ?>", "<?= $getEmployeeLeaveData['emp_id']; ?>", true, true);
		$('#emp_id').prop('disabled', true);
		$('#emp_active_id').val('<?= $getEmployeeLeaveData['emp_id']; ?>');
		$('#emp_id').append(option).trigger('change');
		<?php } ?>



		$('#leave_type_id').change(function () {

			var leaveTypeId = $(this).val();
			var empId = $("#emp_id").val();
			var remain 	  = $('.hideRemainingDays');

			if(leaveTypeId != 0){
				remain.show();
				getTotalDays(leaveTypeId,empId);

			} else{
				remain.hide();
			}

		});

		$('#emp_id').change(function () {
			var leaveTypeId = $("#leave_type_id").val();
			var empId = $(this).val();
			$('#emp_active_id').val(empId);
			if(leaveTypeId != 0 && empId != 0){
				getTotalDays(leaveTypeId,empId);
			}
		});


	function getTotalDays(leaveTypeId,empId,year =''){
		$.ajax({
			type: "post",
			url: "<?= site_url("EmployeeLeaveType/getTotalDays")?>",
			dataType: "json",
			data: {leaveTypeId: leaveTypeId,empId: empId, year: year},
			success: function (data) {
				if (data['success']) {
					$("#total_leave").text(data['total_leave']);
					$("#leave_taken").text(data['leave_taken']);
					$("#available_leave").text(data['available_leave']);
					$("#available_total_leave").val(data['available_days']);
				}
			}
		});
	}

	function FileKeyGen() {
		$(".file-styled-primary").uniform({
			fileButtonClass: 'action btn bg-blue'
		});
	}



</script>
<?php if (isset($select2)) { ?>
	<?= $select2 ?>
<?php } ?>
