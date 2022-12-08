<div class="accordion add-employee" id="accordion-details">
	<?php
	//create  form open tag
	$form_id = array(
			'id'=>'employeeShiftDetails',
			'method'=>'post',
			'class'=>'form-horizontal'
	);
	echo form_open_multipart('', $form_id);
	$shiftId = (isset($getEmployeeShiftData['shift_id']) && ($getEmployeeShiftData['shift_id'] != '')) ? $getEmployeeShiftData['shift_id'] : '';
	?>
	<input type="hidden" name="shift_id" value="<?= $shiftId ?>" id="shift_id">
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic1">
		<h4 class="cursor-pointer mb-0">
			<a class=" coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-one" aria-expanded="true">
				<?= lang('employee_shift_heading') ?>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-one" class="collapse show ctm-padding" aria-labelledby="basic1"
			 data-parent="#accordion-details">
			<div class="row">
				<div class="col-md-6 form-group">
					<span><?= lang('shift_name') ?><span class="text-danger"> * </span></span>
					<input type="text" name="shift_name" id="shift_name" class="form-control"
						   value="<?= (isset($getEmployeeShiftData['shift_name']) && ($getEmployeeShiftData['shift_name'] != '')) ? $getEmployeeShiftData['shift_name'] : ''; ?>"
						   placeholder="Enter <?= lang('shift_name') ?>">
				</div>

				<div class="col-md-6 form-group">
					<span><?= lang('start_time') ?><span class="text-danger"> * </span></span>
					<input type="text" class="form-control dtTimePicker" id="start_time"
						   name="start_time" value="<?= (isset($getEmployeeShiftData['start_time'])) ? $getEmployeeShiftData['start_time'] : date(PHP_TIME_FORMATE); ?>"
						   placeholder="Select a <?= lang('start_time') ?>" readonly>
				</div>

				<div class="col-md-6 form-group">
					<span><?= lang('end_time') ?><span class="text-danger"> * </span></span>
					<input type="text" class="form-control dtTimePicker" id="end_time"
						   name="end_time" value="<?= (isset($getEmployeeShiftData['end_time'])) ? $getEmployeeShiftData['end_time'] : date(PHP_TIME_FORMATE); ?>"
						   placeholder="Select a <?= lang('end_time') ?>" readonly>
				</div>

				<div class="col-md-6 form-group">
					<span><?= lang('total_hours') ?><span class="text-danger"> * </span></span>
					<input type="text" name="total_hours" id="total_hours" class="form-control"
						   value="<?= (isset($getEmployeeShiftData['total_hours']) && ($getEmployeeShiftData['total_hours'] != '')) ? $getEmployeeShiftData['total_hours'] : ''; ?>"
						   placeholder="Enter <?= lang('total_hours') ?>">
				</div>


				<div class="form-group col-md-4">
					<span><?= lang('is_active') ?></span>
					<div class="col-md-12">
						<div class="checkbox checkbox-switchery switchery-xs">
							<label class="switch">
								<input type="checkbox"
									   name="is_active" <?php if (isset($getEmployeeShiftData['is_active']) && $getEmployeeShiftData['is_active'] == 1) {
									echo 'checked="checked"';
								} else {
									echo '';
								} ?> id="is_active" class="switchery">
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="submit-section text-center btn-add">
				<button type="button"
						onclick="window.location.href='<?php echo site_url('ShiftManagement'); ?>'"
						class="btn btn-theme text-white ctm-border-radius button-1 cancel"><?= lang('cancel_btn') ?></button>
				<button class="btn btn-theme text-white ctm-border-radius button-1 submit"><?= lang('submit_btn') ?></button>
			</div>
		</div>
	</div>
	<?php echo form_close(); ?>
</div>

<script>
	var laddaSubmitBtn = Ladda.create(document.querySelector('.submit'));

	$(document).ready(function () {
		// Full featured editor
//        CKEDITOR.replace( 'long_description', {
//            height: '400px',
//            extraPlugins: 'forms'
//        });

		function TimePickerInit() {
        // Time picker
        $(".dtTimePicker").AnyTime_picker({
            format: "%r"
        });
    }
			
		numberInit();
		Select2Init();
		SwitcheryKeyGen();
		FileKeyGen();
		CheckboxKeyGen();
		TimePickerInit();
		// Initialize
		jQuery.validator.addMethod("lettersonly", function(value, element) {
			return this.optional(element) || /^[a-z ]+$/i.test(value);
		}, "Only Letters are allowed");
		
		
		var validator = $("#employeeShiftDetails").validate({
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
				shift_name: {
					required: true,
				},
				start_time: {
					required: true,
				},
				end_time: {
					required: true,
				},
				total_hours: {
					required: true,
				},
			},
			messages: {
				shift_name: {
					required: "Please Enter <?= lang('shift_name') ?>",
				},
				start_time: {
					required: "Please Enter <?= lang('start_time') ?>",
				},
				end_time: {
					required: "Please Select <?= lang('end_time') ?>",
				},
				total_hours: {
					required: "Please Select <?= lang('total_hours') ?>",
				},
			},submitHandler: function (e) {
				$(e).ajaxSubmit({
					url: '<?php echo site_url("EmployeeShift/save");?>',
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
								window.location.href = '<?php echo site_url('EmployeeShift');?>';
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
		
		$(".dtTimePicker").change(function(){
			calculateTime();
		});
	

		function calculateTime(){
			var startValue = $("#start_time").val();
			var endValue = $("#end_time").val();
			var month = '01';
			if(startValue.substr(startValue.length - 2) == "PM" && endValue.substr(endValue.length - 2) == "AM" ||
				((startValue.substr(startValue.length - 2) == "PM" && endValue.substr(endValue.length - 2) == "PM") && endValue.split(':')[0] < startValue.split(':')[0]) ||
				((startValue.substr(startValue.length - 2) == "AM" && endValue.substr(endValue.length - 2) == "AM") && endValue.split(':')[0] < startValue.split(':')[0]) ){
				month = '02';
			}
			var timeStart = new Date("01/01/2007 " + startValue.split(':')[0]+":"+startValue.split(':')[1]+" "+startValue.substr(startValue.length - 2));
			var timeEnd = new Date("01/"+month+"/2007 " + endValue.split(':')[0]+":"+endValue.split(':')[1]+" "+endValue.substr(endValue.length - 2));

			var diff = (timeEnd - timeStart) / 60000; //dividing by seconds and milliseconds

			var minutes = diff % 60;
			var hours = (diff - minutes) / 60;
			
			
			if(hours > 0 || minutes > 0){
				if(minutes == "00"){
					$("#total_hours").val(parseInt(hours)+" hours ");
				}else{
					$("#total_hours").val(parseInt(hours)+" hours "+parseInt(minutes)+" minutes ");
				}
			}
		}
		
	
		

	});
</script>
<style type="text/css">
#total_hours {
    color: #999999;
    cursor: not-allowed;
}
</style>
