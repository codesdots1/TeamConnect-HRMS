<div class="accordion add-employee" id="accordion-details">
	<?php
	//create  form open tag
	$form_id = array(
			'id'=>'taskDetails',
			'method'=>'post',
			'class'=>'form-horizontal'
	);
	echo form_open_multipart('', $form_id);
	$taskId = (isset($getTaskData['task_id']) && ($getTaskData['task_id'] != '')) ? $getTaskData['task_id'] : '';
	?>
	<input type="hidden" name="task_id" value="<?= $taskId ?>" id="task_id">
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic1">
		<h4 class="cursor-pointer mb-0">
			<a class=" coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-one" aria-expanded="true">
				<?= lang('task_heading') ?>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-one" class="collapse show ctm-padding" aria-labelledby="basic1"
			 data-parent="#accordion-details">
			<div class="row">
				<div class="col-md-6 form-group">
					<span><?= lang('project_name') ?><span class="text-danger"> * </span></span>
					<select name="project_id" id="project_id" class="form-control" data-placeholder="Select <?= lang('project_name') ?> ">
						<option value=""></option>
					</select>
				</div>

				<div class="col-md-6 form-group">
					<span><?= lang('task_name') ?><span class="text-danger"> * </span></span>
					<input type="text" name="task_name" id="task_name" class="form-control"
						   value="<?= (isset($getTaskData['task_name']) && ($getTaskData['task_name'] != '')) ? $getTaskData['task_name'] : ''; ?>"
						   placeholder="Enter <?= lang('task_name') ?>">
				</div>


				<div class="col-md-12 form-group">
					<span><?= lang('description') ?><span class="text-danger"> * </span></span>
					<textarea name="description" id="description" placeholder="Enter Only 255 Character"
							  class="form-control" rows="5"
							  cols="5"><?php echo (isset($getTaskData['description']) && ($getTaskData['description'] != '')) ? $getTaskData['description'] : ''; ?></textarea>
					<label id="description-error" class="validation-error-label" for="description"></label>
				</div>

				<div class="form-group col-md-4">
					<span><?= lang('is_active') ?></span>
					<div class="col-md-12">
						<div class="checkbox checkbox-switchery switchery-xs">
							<label>
								<input type="checkbox"
									   name="is_active" <?php if (isset($getProjectData['is_active']) && $getProjectData['is_active'] == 1) {
									echo 'checked="checked"';
								} else {
									echo '';
								} ?> id="is_active" class="switchery">
								<?php if (isset($getProjectData['is_active']) && $getProjectData['is_active'] == 1) {
									$approved = "In Progress";
								} else {
									$approved = "Not Started Yet";
								} ?>
								<span class="approve_status_text" style="margin-left:20px;"><?= $approved; ?></span>
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

		numberInit();
		Select2Init();
		SwitcheryKeyGen();
		FileKeyGen();
		CheckboxKeyGen();

		projectDD('','#project_id');

		// Initialize
		jQuery.validator.addMethod("lettersonly", function(value, element) {
			return this.optional(element) || /^[a-z ]+$/i.test(value);
		}, "Only Letters are allowed");


		var validator = $("#taskDetails").validate({
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
				project_id: {
					required: true,
				},
				task_name: {
					required: true,
					remote: {
						url: "<?php echo site_url( "Task/NameExist");?>",
						type: "post",
						data: {
							column_name: function () {
								return "task_name";
							},
							column_id: function () {
								return $("#task_id").val();
							},
							table_name: function () {
								return "tbl_task";
							}
						}
					}
				},
			},
			messages: {
				project_id: {
					required: "Please Select <?= lang('project_name') ?>",
				},
				task_name: {
					required: "Please Enter <?= lang('task_name') ?>",
					remote  : "<?= lang('task_name') ?> Already Exist",
				},
			},submitHandler: function (e) {
				$(e).ajaxSubmit({
					url: '<?php echo site_url("Task/save");?>',
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
								window.location.href = '<?php echo site_url('Task');?>';
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

		<?php if((isset($getTaskData['project_name']) && !empty($getTaskData['project_name']))){ ?>
		var option = new Option("<?= $getTaskData['project_name']; ?>", "<?= $getTaskData['project_id']; ?>", true, true);
		$('#project_id').append(option).trigger('change');
		<?php } ?>

		$('#is_active').click(function() {
			if($(this).is(":checked")){
				$(".approve_status_text").text('In Progress');
			}else{
				$(".approve_status_text").text('Not Started Yet');
			}
		});
	});
</script>
<?php if (isset($select2)) { ?>
	<?= $select2 ?>
<?php } ?>
