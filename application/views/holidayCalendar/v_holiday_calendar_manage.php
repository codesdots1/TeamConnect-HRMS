<div class="accordion add-employee-attendance" id="accordion-details">
	<?php
	//create  form open tag
	$form_id = array(
			'id' => 'holidayCalendarDetails',
			'method' => 'post',
			'class' => 'form-horizontal',
	);
	echo form_open_multipart('', $form_id);
	$holidayCalendarId = (isset($getHolidayCalendarData['holiday_calendar_id']) && ($getHolidayCalendarData['holiday_calendar_id'] != '')) ? $getHolidayCalendarData['holiday_calendar_id'] : '' ?>
	<input type="hidden" name="holiday_calendar_id" value="<?= $holidayCalendarId ?>" id="holiday_calendar_id">
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic5">
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-five">
				<?= lang('holiday_calendar_heading') ?>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-five" class="collapse show ctm-padding" aria-labelledby="headingFive"
			 data-parent="#accordion-details">
			<div class="row">

				<div class="col-md-12 form-group">
					<span><?= lang('title') ?><span class="text-danger"> * </span></span>
					<input type="text" name="title"
						   value="<?= (isset($getHolidayCalendarData['title']) && ($getHolidayCalendarData['title'] != '')) ? $getHolidayCalendarData['title'] : ''; ?>"
						   id="title" class="form-control" placeholder="Enter <?= lang('title') ?>">
				</div>

				<div class="col-md-12 form-group">
					<span><?= lang('description') ?> <span class="text-danger"> * </span></span>
					<textarea name="description" id="description" placeholder="Enter Only 255 Character" class="form-control" rows="5" cols="5"><?php echo (isset($getHolidayCalendarData['description']) && ($getHolidayCalendarData['description'] != '')) ? $getHolidayCalendarData['description'] : ''; ?></textarea>
					<label id="description-error" class="validation-error-label" for="description"></label>
				</div>

				<div class="col-md-6 form-group">
					<span><?= lang('holiday_from_date') ?><span class="text-danger"> * </span></span>
					<input type="text" class="form-control" id="holiday_from_date" name="holiday_from_date"
						   value="<?= (isset($getHolidayCalendarData['holiday_from_date']) && ($getHolidayCalendarData['holiday_from_date'] != '')) ? $getHolidayCalendarData['holiday_from_date'] : ''; ?>"
						   placeholder="Select a <?= lang('holiday_from_date') ?>" readonly>
				</div>

				<div class="col-md-6 form-group">
					<span><?= lang('holiday_to_date') ?><span class="text-danger"> * </span></span>
					<input type="text" class="form-control" id="holiday_to_date" name="holiday_to_date"
						   value="<?= (isset($getHolidayCalendarData['holiday_to_date']) && ($getHolidayCalendarData['holiday_to_date'] != '')) ? $getHolidayCalendarData['holiday_to_date'] : date(PHP_DATE_FORMATE); ?>"
						   placeholder="Select a <?= lang('holiday_to_date') ?>" readonly>
				</div>


				<div class="col-md-6 form-group">
					<span><?= lang('total_days') ?><span class="text-danger"> * </span></span>
					<input type="text" name="total_days"
						   value="<?= (isset($getHolidayCalendarData['total_days']) && ($getHolidayCalendarData['total_days'] != '')) ? $getHolidayCalendarData['total_days'] : date(PHP_DATE_FORMATE); ?>"
						   id="total_days" class="form-control" placeholder="Enter <?= lang('total_days') ?>" readonly>
				</div>

				<div class="col-md-6 form-group">
					<span><?= lang('holiday_type') ?><span class="text-danger"> * </span></span>
					<select name="holiday_type" id="holiday_type" class="form-control" data-placeholder="Select <?= lang('holiday_type') ?> ">
						<option value="">Select Holiday Type</option>
						<option value="Public Holiday">Public Holiday</option>
						<option value="Optional Holiday">Optional Holiday</option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="submit-section text-center btn-add">
				<button type="button"
						class="btn btn-theme text-white ctm-border-radius button-1 cancle"
						onclick="window.location.href='<?php echo site_url('EmployeeAttendance'); ?>'"><?= lang('cancel_btn') ?></button>
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
		
		$("#total_days").val(daydiff(parseDate($('#holiday_from_date').val()), parseDate($('#holiday_to_date').val())));
		numberInit();
		Select2Init();
		SwitcheryKeyGen();
		FileKeyGen();
		TimePickerInit();
		$("#holiday_from_date").datepicker({
			dateFormat: "<?= DATE_FORMATE ?>",
			todayBtn:  "linked",
			autoclose: true,
			todayHighlight: true,
			minDate: 0,
			onSelect: function(selected) {
				$("#holiday_to_date").datepicker("option", "minDate", selected)
				var id = $(this).attr('id');
				$("input[name="+id+"]").val($(this).val());
				$("#total_days").val(
				daydiff(parseDate($('#holiday_from_date').val()), parseDate($('#holiday_to_date').val()))
				);
			}
		});
		
		$("#holiday_to_date").datepicker({
			dateFormat: "<?= DATE_FORMATE ?>",
			todayBtn:  "linked",
			autoclose: true,
			todayHighlight: true,
			minDate: $("#holiday_from_date").val(),
			onSelect: function(selected) {
				var id = $(this).attr('id');
				$("input[name="+id+"]").val($(this).val());
				$("#total_days").val(
				daydiff(parseDate($('#holiday_from_date').val()), parseDate($('#holiday_to_date').val()))
				);
			}
		});

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
			rules: {
				title:{
					required: true
				},
				description:{
					required: true
				},
				holiday_from_date: {
					required: true
				},
				holiday_to_date: {
					required: true
				},
				holiday_type: {
					required: true,
				},
				total_days: {
					required: true
				}
			},
			messages: {
				title:{
					required: "Please Enter <?= lang('title') ?>"
				},
				description:{
					required: "Please Enter <?= lang('description') ?>"
				},
				holiday_type:{
					required: "Please Enter <?= lang('holiday_type') ?>"
				},
				holiday_from_date: {
					required: "Please Select <?= lang('holiday_from_date') ?>"
				},
				holiday_to_date: {
					required: "Please Select <?= lang('holiday_to_date') ?>"
				},
				total_days: {
					required: "Please Select <?= lang('total_days') ?>"
				}
			},
			submitHandler: function (e) {

				$(e).ajaxSubmit({
					url: '<?php echo site_url("HolidayCalendar/save");?>',
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
								window.location.href = '<?php echo site_url('HolidayCalendar');?>';
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

		<?php if((isset($getHolidayCalendarData['holiday_type']) && !empty($getHolidayCalendarData['holiday_type']))){ ?>
		var option = new Option("<?= $getHolidayCalendarData['holiday_type']; ?>", "<?= $getHolidayCalendarData['holiday_type']; ?>", true, true);
		$('#holiday_type').append(option).trigger('change');
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
	
	function FileKeyGen() {
		$(".file-styled-primary").uniform({
			fileButtonClass: 'action btn bg-blue'
		});
	}

</script>
