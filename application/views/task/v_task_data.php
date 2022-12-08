<div class="panel panel-flat border-left-lg border-left-slate">
	<div class="panel-heading ">
		<h5 class="panel-title"><?= lang('view_task') ?><a class="heading-elements-toggle"><i class="icon-more"></i></a>
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
		$taskId = (isset($getTaskData['task_id']) && ($getTaskData['task_id'] != '')) ? $getTaskData['task_id'] : '' ?>
		<input type="hidden" name="task_id" value="<?= $taskId ?>" id="task_id">

		<div class="tabbable">
			<div class="tab-content">
				<legend><b> <i class="icon-rulers"></i> <?= lang('view_task') ?> </b> </legend>

				<div class="form-group">
					<b><label class="col-lg-3 control-label"><?= lang('project_name') ?> : </label></b>
					<div class="col-lg-9">
						<?= (isset($getTaskInfoData['project_name']) && ($getTaskInfoData['project_name']!= '')) ? $getTaskInfoData['project_name'] : ''; ?>
					</div>
				</div>

				<div class="form-group">
					<b><label class="col-lg-3 control-label"><?= lang('task_name') ?> : </label></b>
					<div class="col-lg-9">
						<?= (isset($getTaskInfoData['task_name']) && ($getTaskInfoData['task_name']!= '')) ? $getTaskInfoData['task_name'] : ''; ?>
					</div>
				</div>

				<div class="form-group">
					<b><label class="col-lg-3 control-label"><?= lang('description') ?> : </label></b>
					<div class="col-lg-9">
						<?php
						if($getTaskInfoData['description'] == ''){
							echo "No Description Added";
						}?>
						<?= (isset($getTaskInfoData['description']) && ($getTaskInfoData['description']!= '')) ? $getTaskInfoData['description'] : ''; ?>
					</div>
				</div>

				<div class="form-group">
					<b><label class="col-lg-3 control-label"><?= lang('is_active') ?> : </label></b>
					<div class="col-lg-9">
						<?php
						if($getTaskInfoData['is_active'] == 1){
							echo "In Progress";
						} else {
							echo "Not Started Yet";
						}
						?>
					</div>
				</div>

			</div>
		</div>
		<!-- create reset button-->
		<div class="text-right">
			<button type="button" class="btn btn-xs border-slate text-slate btn-flat cancel"
					onclick="window.location.href='<?php echo site_url('Task'); ?>'"><?= lang('back_btn') ?> <i class="icon-cross2 position-right"></i> </button>

			<button type="submit"
					class="btn btn-xs border-blue text-blue btn-flat btn-ladda btn-ladda-progress submit"
					data-spinner-color="#03A9F4" data-style="fade" disabled>
				<i id="icon-hide" class="icon-arrow-right8 position-right"></i>
			</button> <br/><br/>
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
