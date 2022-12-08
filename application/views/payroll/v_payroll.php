<div class="accordion add-employee-attendance" id="accordion-details">
	<?php
	//create  form open tag
	$form_id = array(
			'id' => 'PayrollyDetails',
			'method' => 'post',
			'class' => 'form-horizontal',
	);
	echo form_open_multipart('', $form_id); ?>
</div>
<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic2"><br/>
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-two">
				<b><?= lang('generate_salary_slip') ?></b>
			</a>
		</h4>
	</div>

	<div class="card shadow-sm grow ctm-border-radius">
		<div class="card-body align-center">
			SALARY SLIP UPLOAD
			<ul class="nav nav-tabs float-right border-0 tab-list-emp">
				<li class="nav-item">
					<button class="btn btn-theme button-3 text-white ctm-border-radius p-4 ctm-btn-padding bulkUpload">
						<i class="fa fa-upload" aria-hidden="true"></i></button>
				</li>
			</ul>
		</div>
	</div>

	<div class="card-body p-0">
		<div id="basic-two" class="collapse show ctm-padding" aria-labelledby="#basic2"
			 data-parent="#accordion-details">
			<div class="row">
				<div class="col-6 form-group">
					<div class="col-md-6">
						Select <?= lang('employee') ?>:
					</div>
					<select name="emp_id" id="emp_id" class="form-control" placeholder="Select <?= lang('employee_name') ?> ">
						<option value=""></option>
					</select>
				</div>
				<div class="col-4 form-group">
					<div class="col-md-6">
						Select <?= lang('month') ?>:
					</div>
					<?php $currentMonth = date('Y-m-d');
					$datestring = $currentMonth . " first day of last month";
					$dt=date_create($datestring);
					$lastMonth = $dt->format('F Y'); 					  ?>
					<div class="col-lg-8">
						<input type="text" class="form-control dtTimePicker" id="month"
							   name="month" value="<?= $lastMonth;  ?>"
							   placeholder="Select a <?= lang('logout_time') ?>" readonly>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="submit-section text-center btn-add">
				<button  name="export" id="export" class="btn btn-theme text-white ctm-border-radius button-1"
						 style="border-radius: 8px;padding: 5px 15px;margin-left:177px;" data-popup='custom-tooltip'
						 title="<?= lang('export_to_pdf') ?>" title="<?= lang('export_to_pdf') ?>" >
					<i class="fa fa-file-pdf-o"></i>&nbsp; <?= lang('export_to_pdf');?></button>

				<button  name="generate_salary" id="generate_salary" class="btn btn-theme text-white ctm-border-radius button-1"
						 style="border-radius: 8px;padding: 5px 15px;margin-left:5px;" data-popup='custom-tooltip'
						 data-original-title="<?= lang('generate_salary_slip') ?>"
						 title="<?= lang('generate_salary_slip') ?>" >&#10004; <?= lang('generate_salary_slip'); ?></button>
			</div>
		</div>
	</div>
	<?php echo form_close(); ?>
	<div class="row salary_slip_form">
</div>

<script>
	var laddaSubmitBtn = Ladda.create(document.querySelector('#generate_salary'));

	$(document).ready(function () {
		// Full featured editor
		numberInit();
		employeeNameDD('','#emp_id');
		
		
		$("#month").datepicker({
			 changeMonth: true,
			 changeYear: true,
			 maxDate: '-1M',
			 dateFormat: 'MM yy',
			 onClose: function() {
				var iMonth = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				var iYear = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				$(this).datepicker('setDate', new Date(iYear, iMonth, 1));
			 },
			 onSelect: function (value, ui) {
				if ($(this).valid()) {
					$(this).removeClass('invalid').addClass('success');
				}
			 },
			 beforeShow: function() {
			   if ((selDate = $(this).val()).length > 0)
			   {
				  iYear = selDate.substring(selDate.length - 4, selDate.length);
				  iMonth = jQuery.inArray(selDate.substring(0, selDate.length - 5),
				  $(this).datepicker('option', 'monthNames'));
				  $(this).datepicker('option', 'defaultDate', new Date(iYear, iMonth, 1));
				  $(this).datepicker('setDate', new Date(iYear, iMonth, 1));
			   }
			}
		});

		// Initialize
		jQuery.validator.addMethod("lettersonly", function(value, element) {
			return this.optional(element) || /^[a-z ]+$/i.test(value);
		}, "Only Letters are allowed");
		var validator = $("#PayrollyDetails").validate({
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
					required: true
				},
				month: {
					required: true
				}
			},
			messages: {
				emp_id:{
					required: "Please Select <?= lang('employee') ?>",
				},
				month:{
					required: "Please Select <?= lang('month') ?>",
				}
			},
			submitHandler: function (e) {
				var submitButtonName =  $(this.submitButton).attr("name");
				if(submitButtonName == 'export'){
					var url = '<?php echo site_url("Payroll/ExportToPDF")?>';
					$("#PayrollyDetails").attr('action', url);
					e.submit();
				}else{
					$(e).ajaxSubmit({
						url: '<?php echo site_url("Payroll/GenerateSalarySlip");?>',
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
							$('.salary_slip_form').html(resObj);
						}
					});
				}
		}

	  });
	});


	function FileKeyGen() {
		$(".file-styled-primary").uniform({
			fileButtonClass: 'action btn bg-blue'
		});
	}

</script>
<?php if (isset($select2)) { ?>
	<?= $select2 ?>
<?php } ?>
<style>

.well{
	background-color:white;
	border:none;
	margin-left: 10px;
	margin-top: 40px;
}
.ui-datepicker-calendar {
	display: none;
}​
#PayrollyDetails .ui-datepicker {
	padding : 0; 
}
</style>

