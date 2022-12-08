<div class="accordion add-employee" id="accordion-details">
	<?php
	//create  form open tag
	$form_id = array(
			'id' => 'employeeTypeDetails',
			'method' => 'post',
			'class' => 'form-horizontal',
	);
	echo form_open_multipart('', $form_id);
	$typeId = (isset($getEmployeeTypeData['type_id']) && ($getEmployeeTypeData['type_id'] != '')) ? $getEmployeeTypeData['type_id'] : '' ?>
	<input type="hidden" name="type_id" value="<?= $typeId ?>" id="type_id">
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic1">
		<h4 class="cursor-pointer mb-0">
			<a class=" coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-one" aria-expanded="true">
				<?= lang('employee_type_form') ?>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-one" class="collapse show ctm-padding" aria-labelledby="basic1"
			 data-parent="#accordion-details">
			<div class="row">

				<div class="col-md-12 form-group">
					<span><?= lang('type_name') ?><span class="text-danger"> * </span></span>
					<input type="text" name="type_name"
						   value="<?= (isset($getEmployeeTypeData['type_name']) && ($getEmployeeTypeData['type_name'] != '')) ? $getEmployeeTypeData['type_name'] : ''; ?>"
						   id="title" class="form-control" placeholder="Enter <?= lang('type_name') ?>">
				</div>

				<div class="col-md-12 form-group">
					<span><?= lang('description') ?><span class="text-danger"> * </span></span>
					<textarea name="description" id="description" placeholder="Enter Only 255 Character" class="form-control" rows="5" cols="5"><?php echo (isset($getEmployeeTypeData['description']) && ($getEmployeeTypeData['description'] != '')) ? $getEmployeeTypeData['description'] : ''; ?></textarea>
					<label id="description-error" class="validation-error-label" for="description"></label>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="submit-section text-center btn-add">
				<button type="button"
						onclick="window.location.href='<?php echo site_url('EmployeeType'); ?>'"
						class="btn btn-theme text-white ctm-border-radius button-1 cancel"><?= lang('cancel_btn') ?>
					<i id="fa-hide" class="fa fa-times position-left"></i></button>
				<button class="btn btn-theme text-white ctm-border-radius button-1 submit"><?= lang('submit_btn') ?>
					<i id="fa-hide" class="fa fa-arrow-right position-right"></i></button>
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
		FileKeyGen();

		// Initialize
		jQuery.validator.addMethod("lettersonly", function(value, element) {
			return this.optional(element) || /^[a-z ]+$/i.test(value);
		}, "Only Letters are allowed");
		var validator = $("#employeeTypeDetails").validate({
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
				type_name:{
					required: true
				}
			},
			messages: {
				type_name:{
					required: "Please Enter <?= lang('type_name') ?>"
				}
			},
			submitHandler: function (e) {

				$(e).ajaxSubmit({
					url: '<?php echo site_url("EmployeeType/save");?>',
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
								window.location.href = '<?php echo site_url('EmployeeType');?>';
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

	});

	function FileKeyGen() {
		$(".file-styled-primary").uniform({
			fileButtonClass: 'action btn bg-blue'
		});
	}

</script>
