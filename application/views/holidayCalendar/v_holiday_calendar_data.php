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
				<?= lang('view_holiday_calendar') ?>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-five" class="collapse show ctm-padding" aria-labelledby="headingFive"
			 data-parent="#accordion-details">
			<div class="row">

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('title') ?> : </span></b>
					<?= (isset($getHolidayCalendarData['title']) && ($getHolidayCalendarData['title'] != '')) ? $getHolidayCalendarData['title'] : ''; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('holiday_from_date') ?> : </label></span></b>
					<?= (isset($getHolidayCalendarData['holiday_from_date']) && ($getHolidayCalendarData['holiday_from_date'] != '')) ? $getHolidayCalendarData['holiday_from_date'] : ''; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('holiday_to_date') ?> : </span></b>
					<?= (isset($getHolidayCalendarData['holiday_to_date']) && ($getHolidayCalendarData['holiday_to_date'] != '')) ? $getHolidayCalendarData['holiday_to_date'] : ''; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('holiday_type') ?> : </span></b>
					<?= (isset($getHolidayCalendarData['holiday_type']) && ($getHolidayCalendarData['holiday_type'] != '')) ? $getHolidayCalendarData['holiday_type'] : ''; ?>
				</div>

				<div class="col-md-12 form-group">
					<b><span style="display: block"><?= lang('description') ?> : </span></b>
					<?= (isset($getHolidayCalendarData['description']) && ($getHolidayCalendarData['description'] != '')) ? $getHolidayCalendarData['description'] : ''; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="submit-section text-center btn-add">
				<button type="button" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"
						onclick="window.location.href='<?php echo site_url('HolidayCalendar') ?>'"><?= lang('back_btn') ?> <i class="icon-arrow-left5 position-right"></i> </button>
				<button type="submit" class="btn btn-theme disabled text-white ctm-border-radius button-1 submit">
					<i id="icon-hide" class="icon-arrow-right8 position-right"></i>
				</button>
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
