<div class="panel panel-flat border-left-lg border-left-slate">
	<div class="panel-heading ">
		<h5 class="panel-title"><?= lang('view_employee_leave') ?><a class="heading-elements-toggle"><i class="icon-more"></i></a>
		</h5>
		<div class="heading-elements">
			<ul class="icons-list">
				<li><a data-action="collapse"></a></li>
			</ul>
		</div>
	</div>
	<div class="panel-body">
		<?php
		//create  form open tag
		$form_id = array(
			'id' => 'employeeLeaveDetails',
			'method' => 'post',
			'class' => 'form-horizontal',
		);
		echo form_open_multipart('', $form_id);
		$timeSheetId = (isset($getTimeSheetData['time_sheet_id']) && ($getTimeSheetData['time_sheet_id'] != '')) ? $getTimeSheetData['time_sheet_id'] : '' ?>
		<input type="hidden" name="time_sheet_id" value="<?= $timeSheetId ?>" id="time_sheet_id">

		<div class="tabbable">
			<div class="tab-content">
				<legend><b> <i class="icon-calendar"></i> <?= lang('employee_task_report') ?> </b> </legend>

				<div class="form-group">
					<b><label class="col-lg-3 control-label"><?= lang('name') ?> : </label></b>
					<div class="col-lg-9">
						<?= (isset($getTimeSheetData['emp_name']) && ($getTimeSheetData['emp_name']!= '')) ? $getTimeSheetData['emp_name'] : ''; ?>
					</div>
				</div>

				<div class="form-group">
					<b><label class="col-lg-3 control-label"><?= lang('project') ?> : </label></b>
					<div class="col-lg-9">
						<?= (isset($getTimeSheetData['project_name']) && ($getTimeSheetData['project_name']!= '')) ? $getTimeSheetData['project_name'] : ''; ?>
					</div>
				</div>

				<div class="form-group">
					<b><label class="col-lg-3 control-label"><?= lang('task') ?> : </label></b>
					<div class="col-lg-9">
						<?= (isset($getTimeSheetData['task_name']) && ($getTimeSheetData['task_name']!= '')) ? $getTimeSheetData['task_name'] : ''; ?>
					</div>
				</div>

				<div class="form-group">
					<b><label class="col-lg-3 control-label"><?= lang('leave_reason') ?> : </label></b>
					<div class="col-lg-9">
						<?= (isset($getTimeSheetData['leave_reason_name']) && ($getTimeSheetData['leave_reason_name']!= '')) ? $getTimeSheetData['leave_reason_name'] : ''; ?>
					</div>
				</div>


				<div class="form-group">
					<b><label class="col-lg-3 control-label"><?= lang('date') ?> : </label></b>
					<div class="col-lg-9">
						<?= (isset($getTimeSheetData['time_sheet_date']) && ($getTimeSheetData['time_sheet_date']!= '')) ? $getTimeSheetData['time_sheet_date'] : ''; ?>
					</div>
				</div>

				<div class="form-group">
					<b><label class="col-lg-3 control-label"><?= lang('hours') ?> : </label></b>
					<div class="col-lg-9">
						<?= (isset($getTimeSheetData['hours']) && ($getTimeSheetData['hours']!= '')) ? $getTimeSheetData['hours'] : ''; ?> hours
					</div>
				</div>


				<?php if(isset($getTimeSheetData['note']) && ($getTimeSheetData['note']!= '')){ ?>
					<div class="form-group">
						<b><label class="col-lg-3 control-label"><?= lang('note') ?> : </label></b>
						<div class="col-lg-9">
							<?= (isset($getTimeSheetData['note']) && ($getTimeSheetData['note']!= '')) ? $getTimeSheetData['note'] : ''; ?>
						</div>
					</div>
				<?php } ?>

			</div>
		</div>
		<!-- create reset button-->
		<div class="text-right">
			<button type="button" class="btn btn-xs border-slate text-slate btn-flat cancel"
					onclick="window.location.href='<?php  echo (isset($this->session->userdata['role']) && ($this->session->userdata['role'] != 'admin')) ? site_url('Report/EmployeeTaskReport/'.$this->session->userdata['emp_id']) : site_url('Report/EmployeeTaskReport'); ?>'"><?= lang('back_btn') ?> <i class="icon-arrow-left5 position-right"></i> </button>

			<button type="submit" disabled class="btn btn-xs border-blue text-blue btn-flat btn-ladda btn-ladda-progress submit"
					data-spinner-color="#03A9F4" data-style="fade">
				<i id="icon-hide" class="icon-arrow-right8 position-right"></i>
			</button>

		</div>
		<?php echo form_close(); ?>
	</div>
</div>
<script>
	var laddaSubmitBtn = Ladda.create(document.querySelector('.submit'));

	$(document).ready(function () {
		// Full featured editor
		numberInit();


		// Initialize
		jQuery.validator.addMethod("lettersonly", function(value, element) {
			return this.optional(element) || /^[a-z ]+$/i.test(value);
		}, "Only Letters are allowed");
		var validator = $("#holidayCalendarDetails").validate({
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
			rules: {},
			messages: {},
			submitHandler: function (e) {}
		});

	});


	function FileKeyGen() {
		$(".file-styled-primary").uniform({
			fileButtonClass: 'action btn bg-blue'
		});
	}

</script>
