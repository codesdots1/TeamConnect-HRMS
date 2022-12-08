<div class="accordion add-employee-attendance" id="accordion-details">
	<?php
	//create  form open tag
	$form_id = array(
			'id' => 'employeeDetails',
			'method' => 'post',
			'class' => 'form-horizontal',
	);
	echo form_open_multipart('', $form_id);

	$employeeId = (isset($getEmployeeData['emp_id']) && ($getEmployeeData['emp_id'] != '')) ? $getEmployeeData['emp_id'] : '' ?>
	<input type="hidden" name="emp_id" value="<?= $employeeId ?>" id="emp_id">
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic1">
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-one">
				PROFILE PHOTO
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-one" class="collapse show ctm-padding" aria-labelledby="#basic1"
			 data-parent="#accordion-details">
			<div class="row">
				<div class="col-md-6 form-group">
					<div disabled class="form-group" id="imageListing">

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic2">
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-two">
				PERSONAL INFORMATION
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-two" class="collapse show ctm-padding" aria-labelledby="#basic2"
			 data-parent="#accordion-details">
			<div class="row">

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('employee_code') ?> : </span></b>
					<?= (isset($getEmployeeData['employee_code']) && ($getEmployeeData['employee_code'] != '')) ? $getEmployeeData['employee_code'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('first_name') ?> : </label></span></b>
					<?= (isset($getEmployeeData['first_name']) && ($getEmployeeData['first_name'] != '')) ? $getEmployeeData['first_name'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('last_name') ?> : </label></span></b>
					<?= (isset($getEmployeeData['last_name']) && ($getEmployeeData['last_name'] != '')) ? $getEmployeeData['last_name'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block">Personal Email : </label></span></b>
					<?= (isset($getEmployeeData['personal_email']) && ($getEmployeeData['personal_email'] != '')) ? $getEmployeeData['personal_email'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('mobile_no') ?> : </label></span></b>
					<?= (isset($getEmployeeData['mobile_no']) && ($getEmployeeData['mobile_no'] != '')) ? $getEmployeeData['mobile_no'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('gender') ?> : </label></span></b>
					<?= (isset($getEmployeeData['gender_name']) && ($getEmployeeData['gender_name'] != '')) ? $getEmployeeData['gender_name'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('marital_status') ?> : </label></span></b>
					<?= (isset($getEmployeeData['marital_status']) && ($getEmployeeData['marital_status'] != '')) ? $getEmployeeData['marital_status'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('birth_date') ?> : </label></span></b>
					<?= (isset($getEmployeeData['birth_date']) && ($getEmployeeData['birth_date'] != '')) ? $getEmployeeData['birth_date'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('age') ?> : </label></span></b>
					<?= (isset($getEmployeeData['age']) && ($getEmployeeData['age'] != '')) ? $getEmployeeData['age'] : '-'; ?>
				</div>

			</div>
		</div>
	</div>
</div>


<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic3">
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-three">
				GENERAL INFORMATION
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-three" class="collapse show ctm-padding" aria-labelledby="#basic3"
			 data-parent="#accordion-details">
			<div class="row">

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('email') ?> : </span></b>
					<?= (isset($getEmployeeData['email']) && ($getEmployeeData['email'] != '')) ? $getEmployeeData['email'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('hire_date') ?> : </span></b>
					<?= (isset($getEmployeeData['hire_date']) && ($getEmployeeData['hire_date'] != '')) ? $getEmployeeData['hire_date'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('designation') ?> : </span></b>
					<?= (isset($getEmployeeData['designation_name']) && ($getEmployeeData['designation_name'] != '')) ? $getEmployeeData['designation_name'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('department') ?> : </span></b>
					<?= (isset($getEmployeeData['dept_name']) && ($getEmployeeData['dept_name'] != '')) ? $getEmployeeData['dept_name'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('country') ?> : </span></b>
					<?= (isset($getEmployeeData['country_name']) && ($getEmployeeData['country_name'] != '')) ? $getEmployeeData['country_name'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('state') ?> : </span></b>
					<?= (isset($getEmployeeData['state_name']) && ($getEmployeeData['state_name'] != '')) ? $getEmployeeData['state_name'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('city') ?> : </span></b>
					<?= (isset($getEmployeeData['city_name']) && ($getEmployeeData['city_name'] != '')) ? $getEmployeeData['city_name'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('postal_code') ?> : </label></span></b>
					<?= (isset($getEmployeeData['postal_code']) && ($getEmployeeData['postal_code'] != '')) ? $getEmployeeData['postal_code'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('type') ?> : </label></span></b>
					<?= (isset($getEmployeeData['type_name']) && ($getEmployeeData['type_name'] != '')) ? $getEmployeeData['type_name'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('status') ?> : </label></span></b>
					<?php
					if(isset($getEmployeeData['status']) == 0){
						echo '<a href="#" class="btn btn-danger btn-lg disabled" role="button" aria-disabled="true">DeActive</a>';
					} else {
						echo '<a href="#" class="btn btn-success btn-lg disabled" role="button" aria-disabled="true">Active</a>';
					}
					?>
				</div>

				<?php
				if(isset($getEmployeeData['country_name']) == 'India'){ ?>
				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('aadhar_card_no') ?> : </span></b>
					<?= (isset($getEmployeeData['aadhar_card_no']) && ($getEmployeeData['aadhar_card_no'] != '')) ? $getEmployeeData['aadhar_card_no'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('pan_card_no') ?> : </span></b>
					<?= (isset($getEmployeeData['pan_card_no']) && ($getEmployeeData['pan_card_no'] != '')) ? $getEmployeeData['pan_card_no'] : '-'; ?>
				</div>

				<?php } ?>

			</div>
		</div>
	</div>
</div>


<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic4">
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-four">
				SALARY INFORMATION
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-four" class="collapse show ctm-padding" aria-labelledby="#basic4"
			 data-parent="#accordion-details">
			<div class="row">

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('salary') ?> : </span></b>
					<?= (isset($getEmployeeData['amount']) && ($getEmployeeData['amount'] != '')) ? $getEmployeeData['amount'] : '-'; ?>
				</div>

				<?php if (isset($getEmployeeData['country_name']) == 'India') { ?>

				<div class="col-md-6 form-group">
					<b><span><?= lang('esic') ?> : </span></b>
					<?= (isset($getEmployeeData['esic']) && ($getEmployeeData['esic'] != '')) ? $getEmployeeData['esic'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span><?= lang('epf') ?> : </span></b>
					<?= (isset($getEmployeeData['epf']) && ($getEmployeeData['epf'] != '')) ? $getEmployeeData['epf'] : '-'; ?>
				</div>

				<?php } ?>

				<?php if (isset($getEmployeeData['esic']) == 'yes' && ($getEmployeeData['esic']) == 'yes') { ?>

				<div class="col-md-6 form-group">
					<b><span><?= lang('ip_no') ?> : </span></b>
					<?= (isset($getEmployeeData['ip_no']) && ($getEmployeeData['ip_no'] != '')) ? $getEmployeeData['ip_no'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span><?= lang('uan_no') ?> : </span></b>
					<?= (isset($getEmployeeData['uan_no']) && ($getEmployeeData['uan_no'] != '')) ? $getEmployeeData['uan_no'] : '-'; ?>
				</div>
				<?php } ?>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('role') ?> : </span></b>
					<?= (isset($getEmployeeData['role']) && ($getEmployeeData['role'] != '')) ? $getEmployeeData['role'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('work_week_of_days') ?> : </span></b>
					<?= (isset($getEmployeeData['days_per_week']) && ($getEmployeeData['days_per_week'] != '')) ? $getEmployeeData['days_per_week'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('work_week_title') ?> : </span></b>
					<?= (isset($getEmployeeData['work_week_title']) && ($getEmployeeData['work_week_title'] != '')) ? $getEmployeeData['work_week_title'] : '-'; ?>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic5">
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-five">
				BANK INFORMATION
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-five" class="collapse show ctm-padding" aria-labelledby="#basic5"
			 data-parent="#accordion-details">
			<div class="row">

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('bank_name') ?> : </span></b>
					<?= (isset($getEmployeeData['bank_name']) && ($getEmployeeData['bank_name'] != '')) ? $getEmployeeData['bank_name'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('holder_name') ?> : </span></b>
					<?= (isset($getEmployeeData['holder_name']) && ($getEmployeeData['holder_name'] != '')) ? $getEmployeeData['holder_name'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('bank_code') ?> : </span></b>
					<?= (isset($getEmployeeData['bank_code']) && ($getEmployeeData['bank_code'] != '')) ? $getEmployeeData['bank_code'] : '-'; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('account_number') ?> : </span></b>
					<?= (isset($getEmployeeData['account_number']) && ($getEmployeeData['account_number'] != '')) ? $getEmployeeData['account_number'] : '-'; ?>
				</div>

			</div>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-12">
		<div class="submit-section text-center btn-add">
			<button type="button" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"
					onclick="window.location.href='<?php echo site_url('Employee') ?>'"><?= lang('back_btn') ?> <i class="icon-arrow-left5 position-right"></i> </button>
			<button type="submit" class="btn btn-theme disabled text-white ctm-border-radius button-1 submit">
				<i id="icon-hide" class="icon-arrow-right8 position-right"></i>
			</button>
		</div>
	</div>
</div>
<?php echo form_close(); ?>

</div>

</div>
<script>
	var laddaSubmitBtn = Ladda.create(document.querySelector('.submit'));

	$(document).ready(function () {
		// Full featured editor

		ImageLoad();
		numberInit();
		Select2Init();
		SwitcheryKeyGen();
		FileKeyGen();
		departmentDD('', '#department_id');
		roleDD('', '#role_id');
		workWeekDD('', '#work_week_id');
		monthlyWorkingDaysDD('', '#monthly_working_days');
		maritalStatusDD('', '#marital_status_id');
		genderDD('', '#gender_id');
		countryDD('', '#country_id');
		stateDD('', '#state_id');
		cityDD('', '#city_id');
		typeDD('', '#type_id');
		employeeShiftDD('', '#shift_id');
		designationDD('', '#designation_id');

		$('.hideESICInfo').hide();
		$('.hideEPFInfo').hide();
		$('.hideInfo').hide();

		var radioValue = $("input[name='esic']:checked").val();
		if (radioValue == 'yes') {
			$('.hideESICInfo').show();
		} else {
			// $('#ip_no').val('');
			$('.hideESICInfo').hide();
		}

		var radioValueInfo = $("input[name='epf']:checked").val();
		if (radioValueInfo == 'yes') {
			$('.hideEPFInfo').show();
		} else {
			// $('#uan_no').val('');
			$('.hideEPFInfo').hide();
		}

		// Initialize
		jQuery.validator.addMethod("lettersonly", function (value, element) {
			return this.optional(element) || /^[a-z ]+$/i.test(value);
		}, "Only Letters are allowed");
		jQuery.validator.addMethod("AlfaNumericOnly", function (value, element) {
			return this.optional(element) || /^[a-z0-9 ]+$/i.test(value);
		}, "Only Letters and Numbers are allowed");
		jQuery.validator.addMethod("PlusNumericOnly", function (value, element) {
			return this.optional(element) || /^[0-9\+ ]+$/i.test(value);
		}, "Only Plus sign and Numbers are allowed");
		var validator = $("#employeeDetails").validate({
			ignore: 'input[type=hidden], .select2-search__field, :hidden', // ignore hidden fields
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
					} else {
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
				} else {
					error.insertAfter(element);
				}
			},
			validClass: "validation-valid-label",
			success: function (label) {
				label.addClass("validation-valid-label").text("Success.")
			},
			rules: {
				employee_code: {
					required: true,
					remote: {
						url: "<?php echo site_url("Employee/NameExist");?>",
						type: "post",
						data: {
							column_name: function () {
								return "employee_code";
							},
							column_id: function () {
								return $("#emp_id").val();
							},
							table_name: function () {
								return "tbl_employee";
							}
						}
					}
				},
				first_name: {
					required: true,
					lettersonly: true,
				},
				last_name: {
					required: true,
					lettersonly: true,
				},
				bank_name: {
					required: true,
					lettersonly: true,
				},
				bank_code: {
					required: true,
					minlength: 11,
					maxlength: 11,
					AlfaNumericOnly: true
				},
				holder_name: {
					required: true,
					lettersonly: true,
				},
				account_number: {
					required: true,
					maxlength: 16,
					AlfaNumericOnly: true
				},
				mobile_no: {
					required: true,
					minlength: 10,
					PlusNumericOnly: true
					//remote: {
					//	url: "<?php //echo site_url( "Employee/NameExist");?>//",
					//	type: "post",
					//	data: {
					//		column_name: function () {
					//			return "mobile_no";
					//		},
					//		column_id: function () {
					//			return $("#emp_id").val();
					//		},
					//		table_name: function () {
					//			return "tbl_employee";
					//		}
					//	}
					//}
				},
				password: {
					required: true,
					minlength: 8
				},
				type_id: {
					required: true
				},
				amount: {
					required: true,
					number: true
				},
				age: {
					required: true,
					number: true
				},
				designation_id: {
					required: true
				},
				address: {
					required: true
				},
				country_id: {
					required: true
				},
				state_id: {
					required: true
				},
				account_details_id: {
					required: true
				},
				role_id: {
					required: true
				},
				work_week_id: {
					required: true
				},
				monthly_working_days: {
					required: true
				},
				email: {
					required: true,
					email: true,
					validEmail: true,
					remote: {
						url: "<?php echo site_url("Employee/NameExist");?>",
						type: "post",
						data: {
							column_name: function () {
								return "email";
							},
							column_id: function () {
								return $("#emp_id").val();
							},
							table_name: function () {
								return "tbl_employee";
							}
						}
					}
				},
				department_id: {
					required: true
				},
				marital_status_id: {
					required: true

				},
				gender_id: {
					required: true

				},
				birth_date: {
					required: true,
					validDate: true
				},
				hire_date: {
					required: true,
					validDate: true
				},
				city_id: {
					required: true
				},
				shift_id: {
					required: true
				},
				postal_code: {
					required: true,
					number: true,
					maxlength: 6,
					minlength: 6
				},
				aadhar_card_no: {
					required: true,
					maxlength: 12,
					minlength: 12
				},
				pan_card_no: {
					required: true,
					AlfaNumericOnly: true,
					maxlength: 10,
					minlength: 10
				},
				ip_no: {
					required: true,
				},
				uan_no: {
					required: true,
					maxlength: 12,
				},

			},
			messages: {
				employee_code: {
					required: "Please Enter <?= lang('employee_code') ?>",
					remote: "<?= lang('employee_code') ?> is Already Exits",
				},
				first_name: {
					required: "Please Enter <?= lang('first_name') ?>"
				},
				last_name: {
					required: "Please Enter <?= lang('last_name') ?>"
				},
				bank_name: {
					required: "Please Enter <?= lang('bank_name') ?>"
				},
				bank_code: {
					required: "Please Enter <?= lang('bank_code') ?>"
				},
				holder_name: {
					required: "Please Enter <?= lang('holder_name') ?>"
				},
				account_number: {
					required: "Please Enter <?= lang('account_number') ?>"
				},
				password: {
					required: "Please Enter <?= lang('password') ?>"
				},
				mobile_no: {
					required: "Please Enter <?= lang('mobile_no') ?>",
				},
				amount: {
					required: "Please Select <?= lang('salary') ?>"
				},
				age: {
					required: "Please Select <?= lang('age') ?>"
				},
				type_id: {
					required: "Please Enter <?= lang('type') ?>"
				},
				department_id: {
					required: "Please Select <?= lang('department') ?>"
				},
				designation_id: {
					required: "Please Enter <?= lang('designation') ?>"
				},
				email: {
					required: "Please Enter <?= lang('email') ?>",
					remote: "<?= lang('email') ?> is Already Exits",
				},
				marital_status_id: {
					required: "Please Select <?= lang('marital_status') ?>"
				},
				account_details_id: {
					required: "Please Select <?= lang('account_details') ?>"
				},
				gender_id: {
					required: "Please Enter <?= lang('gender') ?>"
				},
				birth_date: {
					required: "Please Enter <?= lang('birth_date') ?>"
				},
				hire_date: {
					required: "Please Enter <?= lang('hire_date') ?>"
				},
				role_id: {
					required: "Please Select <?= lang('role') ?>"
				},
				state_id: {
					required: "Please Select <?= lang('state') ?>"
				},
				country_id: {
					required: "Please Select <?= lang('country') ?>"
				},
				city_id: {
					required: "Please Select <?= lang('city') ?>"
				},
				postal_code: {
					required: "Please Enter <?= lang('postal_code') ?>",
					maxlength: "It allows only 6 digits"
				},
				address: {
					required: "Please Enter <?= lang('address') ?>"
				},
				work_week_id: {
					required: "Please Select <?= lang('work_week') ?>"
				},
				monthly_working_days: {
					required: "Please Select <?= lang('monthly_working_days') ?>"
				},
				shift_id: {
					required: "Please Select <?= lang('shift') ?>"
				},
				aadhar_card_no: {
					required: "Please Select <?= lang('aadhar_card_no') ?>"
				},
				pan_card_no: {
					required: "Please Select <?= lang('pan_card_no') ?>"
				},
				ip_no: {
					required: "Please Select <?= lang('ip_no') ?>"
				},
				uan_no: {
					required: "Please Select <?= lang('uan_no') ?>",
					maxlength: "Enter only <?= lang('uan_no') ?> 12 Digits "
				},
			},
			submitHandler: function (e) {
				var loginEmpId = '<?php echo $this->session->userdata['emp_id'] ?>';
				var empId = '<?php echo $employeeId; ?>';
				$(e).ajaxSubmit({
					url: '<?php echo site_url("Employee/save");?>',
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
								if (loginEmpId == empId)
									window.location.href = '<?php echo site_url('Employee/getEmployeeDataListing/' . $employeeId);?>';
								else
									window.location.href = '<?php echo site_url('Employee');?>';
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

		$("#country_id").change(function () {
			var country = '<?= isset($getEmployeeData['country_name']) ? $getEmployeeData['country_name'] : "" ?>';
			if (country != "") {
				if ($(this).text().trim() != country.trim()) {
					var option = new Option("", "", true, true);
					$('#state_id').append(option).trigger('change');
					$('#city_id').append(option).trigger('change');
				}
			} else {
				var option = new Option("", "", true, true);
				$('#state_id').append(option).trigger('change');
				$('#city_id').append(option).trigger('change');
			}
		});

		$("#state_id").change(function () {
			var state = '<?= isset($getEmployeeData['state_name']) ? $getEmployeeData['state_name'] : ""  ?>';
			if (state != "") {
				if ($(this).text().trim() != state.trim()) {
					var option = new Option("", "", true, true);
					$('#city_id').append(option).trigger('change');
				}

			} else {
				var option = new Option("", "", true, true);
				$('#city_id').append(option).trigger('change');
			}
		});


		$("input[name='esic']").change(function () {
			var radioValue = $(this).val();
			if (radioValue == 'yes') {
				$('.hideESICInfo').show();
			} else {
				$('.hideESICInfo').hide();
			}
		});

		$("input[name='epf']").change(function () {
			var radioValueInfo = $(this).val();
			if (radioValueInfo == 'yes') {
				$('.hideEPFInfo').show();
			} else {
				$('.hideEPFInfo').hide();
			}
		});

		$("#country_id").change(function () {
			var value = $('#country_id :selected').text();
			var conInfo = $('.hideInfo');
			if (value == 'India') {
				$('.hideInfo').show();
			} else {
				conInfo.hide();
			}
		});

		$("#birth_date").datepicker({
			dateFormat: 'dd-mm-yy',
			todayBtn: "linked",
			autoclose: true,
			minDate: new Date('1951', 0, 1),
			maxDate: '-20y',
			yearRange: '1951:-20y',
			todayHighlight: true,
			changeMonth: true,
			changeYear: true,
			onSelect: function (value, ui) {
				if ($(this).valid()) {
					$(this).removeClass('invalid').addClass('success');
				}
				var id = $(this).attr('id');
				$("input[name=" + id + "]").val($(this).val());

				var today = new Date();
				var age = today.getFullYear() - ui.selectedYear;
				$('#age').val(age);
			}


		});

		$("#hire_date").datepicker({
			dateFormat: 'dd-mm-yy',
			todayBtn: "linked",
			autoclose: true,
			minDate: new Date(new Date().getFullYear(), 0, 1),
			maxDate: new Date((new Date().getFullYear()) + 1, 11, 31),
			todayHighlight: true,
			changeMonth: true,
			changeYear: true,
			onSelect: function (value, ui) {
				if ($(this).valid()) {
					$(this).removeClass('invalid').addClass('success');
				}
			}
		});


		<?php if((isset($getEmployeeData['gender_name']) && !empty($getEmployeeData['gender_name']))){ ?>
		var option = new Option("<?= $getEmployeeData['gender_name']; ?>", "<?= $getEmployeeData['gender_id']; ?>", true, true);
		$('#gender_id').append(option).trigger('change');
		<?php } ?>

		<?php if((isset($getEmployeeData['type_name']) && !empty($getEmployeeData['type_name']))){ ?>
		var option = new Option("<?= $getEmployeeData['type_name']; ?>", "<?= $getEmployeeData['type_id']; ?>", true, true);
		$('#type_id').append(option).trigger('change');
		<?php } ?>

		<?php if((isset($getEmployeeData['city_name']) && !empty($getEmployeeData['city_name']))){ ?>
		var option = new Option("<?= $getEmployeeData['city_name']; ?>", "<?= $getEmployeeData['city_id']; ?>", true, true);
		$('#city_id').append(option).trigger('change');
		<?php } ?>

		<?php if((isset($getEmployeeData['marital_status']) && !empty($getEmployeeData['marital_status']))){ ?>
		var option = new Option("<?= $getEmployeeData['marital_status']; ?>", "<?= $getEmployeeData['marital_status_id']; ?>", true, true);
		$('#marital_status_id').append(option).trigger('change');
		<?php } ?>

		<?php if((isset($getEmployeeData['state_name']) && !empty($getEmployeeData['state_name']))){ ?>
		var option = new Option("<?= $getEmployeeData['state_name']; ?>", "<?= $getEmployeeData['state_id']; ?>", true, true);
		$('#state_id').append(option).trigger('change');
		<?php } ?>

		<?php if((isset($getEmployeeData['country_name']) && !empty($getEmployeeData['country_name']))){ ?>
		var option = new Option("<?= $getEmployeeData['country_name']; ?>", "<?= $getEmployeeData['country_id']; ?>", true, true);
		$('#country_id').append(option).trigger('change');
		<?php } ?>

		<?php if((isset($getEmployeeData['bank_name']) && !empty($getEmployeeData['bank_name']))){ ?>
		var option = new Option("<?= $getEmployeeData['bank_name']; ?>", "<?= $getEmployeeData['account_details_id']; ?>", true, true);
		$('#account_details_id').append(option).trigger('change');
		<?php } ?>

		<?php if((isset($getEmployeeData['dept_name']) && !empty($getEmployeeData['dept_name']))){ ?>
		var option = new Option("<?= $getEmployeeData['dept_name']; ?>", "<?= $getEmployeeData['department_id']; ?>", true, true);
		$('#department_id').append(option).trigger('change');
		<?php } ?>

		<?php if((isset($getEmployeeData['role']) && !empty($getEmployeeData['role']))){ ?>
		var option = new Option("<?= $getEmployeeData['role']; ?>", "<?= $getEmployeeData['role_id']; ?>", true, true);
		$('#role_id').append(option).trigger('change');
		<?php } ?>

		<?php if((isset($getEmployeeData['days_title']) && !empty($getEmployeeData['days_title']))){ ?>
		var option = new Option("<?= $getEmployeeData['days_title']; ?>", "<?= $getEmployeeData['work_week_id']; ?>", true, true);
		$('#work_week_id').append(option).trigger('change');
		<?php } ?>

		<?php if((isset($getEmployeeData['working_days_title']) && !empty($getEmployeeData['working_days_title']))){ ?>
		var option = new Option("<?= $getEmployeeData['working_days_title']; ?>", "<?= $getEmployeeData['month_work_id']; ?>", true, true);
		$('#monthly_working_days').append(option).trigger('change');
		<?php } ?>

		<?php if((isset($getEmployeeData['shift_name']) && !empty($getEmployeeData['shift_name']))){ ?>
		var option = new Option("<?= $getEmployeeData['shift_name']; ?>", "<?= $getEmployeeData['shift_id']; ?>", true, true);
		$('#shift_id').append(option).trigger('change');
		<?php } ?>

		<?php if((isset($getEmployeeData['type']) && !empty($getEmployeeData['type']))){ ?>
		var option = new Option("<?= $getEmployeeData['type']; ?>", "<?= $getEmployeeData['type']; ?>", true, true);
		$('#type').append(option).trigger('change');
		<?php } ?>

		<?php if((isset($getEmployeeData['designation_name']) && !empty($getEmployeeData['designation_name']))){ ?>
		var option = new Option("<?= $getEmployeeData['designation_name']; ?>", "<?= $getEmployeeData['designation_id']; ?>", true, true);
		$('#designation_id').append(option).trigger('change');
		<?php } ?>

	});

	$.validator.addMethod("validDate", function (value) {
		var currVal = value;
		if (currVal == '')
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
		else if (dtDay < 1 || dtDay > 31)
			return false;
		else if ((dtMonth == 4 || dtMonth == 6 || dtMonth == 9 || dtMonth == 11) && dtDay == 31)
			return false;
		else if (dtMonth == 2) {
			var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
			if (dtDay > 29 || (dtDay == 29 && !isleap))
				return false;
		}
		return true;
	}, 'Please enter a valid birth date');


	function ImageLoad() {
		var employeeId = $('#emp_id').val();
		$.ajax({
			type: "post",
			url: "<?php echo site_url('Employee/imageLoad');?>",
			dataType: "json",
			async: false,
			data: {emp_id: employeeId},
			beforeSend: function (formData, jqForm, options) {
//                var dialog = bootbox.dialog({
//                    message: 'Please have patience, images are loading',
//                });
			},
			complete: function () {
			},
			success: function (resObj) {
				$('#imageListing').html(resObj);
			}
		});
	}

	function deleteImage(imageId, imageUrl) {

		swal({
				title: "<?= ucwords(lang('delete')); ?>",
				text: "<?= lang('delete_warning'); ?>",
				type: "<?= lang('warning'); ?>",
				showCancelButton: true,
				closeOnConfirm: false,
				confirmButtonColor: "<?= BTN_DELETE_WARNING; ?>",
				showLoaderOnConfirm: true
			},
			function () {
				$.ajax({
					type: "POST",
					url: "<?php echo site_url('Employee/imageDelete');?>",
					dataType: "json",
					data: {imageId: imageId, imageUrl: imageUrl},
					success: function (data) {
						if (data['success']) {
							swal({
								title: "<?= ucwords(lang('success'))?>",
								text: data['msg'],
								type: "<?= lang('success')?>",
								confirmButtonColor: "<?= BTN_SUCCESS; ?>"
							}, function () {
								ImageLoad();
							});
						} else {
							swal({
								title: "<?= ucwords(lang('error')); ?>",
								text: data['msg'],
								type: "<?= lang('error'); ?>",
								confirmButtonColor: "<?= BTN_ERROR; ?>"
							}, function () {
								//ImageLoad();
							});
						}
					}
				});
			});
	}

	function FileKeyGen() {
		$(".file-styled-primary").uniform({
			fileButtonClass: 'action btn bg-blue'
		});
	}

</script>
<style type="text/css">
	#age, #employee_code {
		cursor: no-drop;
		color: #999999;
	}

</style>
<?php if (isset($select2)) { ?>
	<?= $select2 ?>
<?php } ?>
