<div class="accordion add-employee-attendance" id="accordion-details">
	<?php
	//create  form open tag
	$form_id = array(
			'id'=>'teamDetails',
			'method'=>'post',
			'class'=>'form-horizontal'
	);
	echo form_open_multipart('', $form_id);
	$teamId = (isset($getTeamData['team_id']) && ($getTeamData['team_id'] != '')) ? $getTeamData['team_id'] : ''; ?>

	<input type="hidden" name="team_id" value="<?= $teamId ?>" id="team_id">
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic5">
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-five">
				<?= lang('team_management_heading') ?>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-five" class="collapse show ctm-padding" aria-labelledby="headingFive"
			 data-parent="#accordion-details">
			<div class="row">
				<div class="col-md-6 form-group">
					<span><?= lang('team_name') ?><span class="text-danger"> * </span></span>
					<input type="text" name="team_name"
						   value="<?= (isset($getTeamData['team_name']) && ($getTeamData['team_name'] != '')) ? $getTeamData['team_name'] : ''; ?>"
						   id="team_name" class="form-control" placeholder="Enter <?= lang('team_name') ?>">
				</div>

				<div class="col-md-6 form-group">
					<span><?= lang('team_head') ?><span class="text-danger"> * </span></span>
					<select name="emphead" id="emphead" class="form-control" data-placeholder="Select <?= lang('team_head') ?> ">
						<option value=""></option>
						<input type="hidden" id="emp_active_id" name="emp_active_id" value=""/>
					</select>
				</div>

				<div class="col-md-6 form-group">
					<span><?= lang('projects') ?><span class="text-danger"> * </span></span>
					<select name="projectlistdd[]" id="projectlistdd" class="form-control" data-placeholder="Select <?= lang('projects') ?> ">
						<option value=""></option>
					</select>
				</div>

				<div class="col-md-6 form-group">
					<span><?= lang('team_members') ?><span class="text-danger"> * </span></span>
					<select name="emplistdd[]" id="emplistdd" class="form-control" data-placeholder="Select <?= lang('team_members') ?> ">
						<option value=""></option>
					</select>
				</div>

				<div class="col-md-12 form-group">
					<span><?= lang('description') ?><span class="text-danger"> * </span></span>
					<textarea name="description" id="description" placeholder = "Enter Only 255 Character" class="form-control" rows="5" cols="5"><?php echo (isset($getTeamData['description']) && ($getTeamData['description'] != '')) ? $getTeamData['description'] : ''; ?></textarea>
					<label id="description-error" class="validation-error-label" for="description"></label>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="submit-section text-center btn-add">
				<button type="button"
						class="btn btn-theme text-white ctm-border-radius button-1 cancle"
						onclick="window.location.href='<?php echo site_url('TeamManagement'); ?>'"><?= lang('cancel_btn') ?></button>
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

		numberInit();
		Select2Init();
		SwitcheryKeyGen();
		FileKeyGen();
		CheckboxKeyGen();

		teamHeadDD('','#emphead');
		teamMembersDD('','#emplistdd');
		projectDD('','#projectlistdd');

		var role  = '<?= $this->session->userdata['role'] ?>';
		var empId = '<?= $this->session->userdata['emp_id'] ?>';

		if(role == 'team leader' && empId == <?= $this->session->userdata['emp_id'];?>){
			var option = new Option("<?= $user_display_name." | ".$this->session->userdata['email']." | ".$this->session->userdata['role']; ?>", "<?= $this->session->userdata['emp_id'];?>", true, true);
			$('#emphead').prop('disabled', true);
			$('#emp_active_id').val('<?= $this->session->userdata['emp_id'];?>');
			$('#emphead').append(option).trigger('change');
		}


		// Initialize
		jQuery.validator.addMethod("lettersonly", function(value, element) {
			return this.optional(element) || /^[a-z ]+$/i.test(value);
		}, "Only Letters are allowed");
		var validator = $("#teamDetails").validate({
			ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
			errorClass: 'validation-error-label',
			successClass: 'validation-valid-label',
			highlight: function (element, errorClass) {
				$(element).removeClass(errorClass);
			},
			unhighlight: function (element, errorClass) {
				$(element).removeClass(errorClass);
			},

			validClass: "validation-valid-label",
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
			success: function (label) {
				label.addClass("validation-valid-label").text("Success.")
			},
			ignore: [],
			rules: {
				team_name: {
					required: true,
					remote: {
						url: "<?php echo site_url( "TeamManagement/NameExist");?>",
						type: "post",
						data: {
							column_name: function () {
								return "team_name";
							},
							column_id: function () {
								return $("#team_id").val();
							},
							table_name: function () {
								return "tbl_team";
							}
						}
					}
				},
				'emplistdd[]': {
					required: true,
				},
				'projectlistdd[]': {
					required: true,
				},
				emphead: {
					required: true,
				}
			},
			messages: {
				team_name: {
					required: "Please select <?= lang('team_name') ?>",
					remote: "<?= lang('team_name') ?> Already Exist",
				},
				'emplistdd[]': {
					required: "Please select <?= lang('team_members') ?>",
				},
				'projectlistdd[]': {
					required: "Please select <?= lang('project') ?>",
				},
				emphead: {
					required: "Please select <?= lang('team_head') ?>",
				},
			},submitHandler: function (e) {
				var role = '<?php echo $this->session->userdata['role']; ?>';
				$(e).ajaxSubmit({
					url: '<?php echo site_url("TeamManagement/save");?>',
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
								if(role != '' && role == 'team leader' && role == 'hr'){
									window.location.href = '<?php echo site_url('TeamManagement');?>';
								} else {
									window.location.href = '<?php echo site_url('TeamManagement/index/'.$this->session->userdata['emp_id']);?>';
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

		<?php if((isset($getTeamData['team_head']) && !empty($getTeamData['team_head']))){ ?>
		var option = new Option("<?= $getTeamData['team_head']; ?>", "<?= $getTeamData['team_head_id']; ?>", true, true);
		$('#emphead').prop('disabled', true);
		$('#emp_active_id').val('<?= $getTeamData['team_head_id']; ?>');
		$('#emphead').append(option).trigger('change');
		<?php } ?>

		<?php if((isset($getTeamData['team_members']) && !empty($getTeamData['team_members']))){
		foreach($getTeamData['team_members'] as $key => $value) { ?>
		var option = new Option("<?= $value; ?>", "<?= $key; ?>", true, true);
		$('#emplistdd').append(option).trigger('change');
		<?php }} ?>

		<?php if((isset($getTeamData['projects']) && !empty($getTeamData['projects']))){
		foreach($getTeamData['projects'] as $key => $value) { ?>
		var option = new Option("<?= $value; ?>", "<?= $key; ?>", true, true);
		$('#projectlistdd').append(option).trigger('change');
		<?php }} ?>


		$("#emphead").change(function(){
			var teamhead = '<?= isset($getTeamData['team_head']) ? $getTeamData['team_head'] : "" ?>';
			var empId = $('#emphead').val();
			$('#emp_active_id').val(empId);
			if(teamhead != ""){
				if($(this).text().trim() != teamhead.trim()){
					$('#emplistdd > option').prop("selected", false);
					$("li.select2-selection__choice").remove();
				}
			} else{
				$('#emplistdd > option').prop("selected", false);
				$("li.select2-selection__choice").remove();
			}
		});
	});

</script>
<?php if (isset($select2)) { ?>
	<?= $select2 ?>
<?php } ?>
