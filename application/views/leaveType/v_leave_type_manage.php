<div class="accordion add-employee" id="accordion-details">
	<?php
	//create  form open tag
	$form_id = array(
			'id'=>'leaveTypeDetails',
			'method'=>'post',
			'class'=>'form-horizontal'
	);
	echo form_open_multipart('', $form_id);
	$leaveTypeId = (isset($getLeaveTypeData['leave_type_id']) && ($getLeaveTypeData['leave_type_id'] != '')) ? $getLeaveTypeData['leave_type_id'] : '';
	?>
	<input type="hidden" name="leave_type_id" value="<?= $leaveTypeId ?>" id="leave_type_id">
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic1">
		<h4 class="cursor-pointer mb-0">
			<a class=" coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-one" aria-expanded="true">
				<?= lang('leave_type_heading') ?>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-one" class="collapse show ctm-padding" aria-labelledby="basic1"
			 data-parent="#accordion-details">
			<div class="row">

				<div class="col-md-12 form-group">
					<span><?= lang('leave_type') ?><span class="text-danger"> * </span></span>
					<input type="text" name="leave_type" id="leave_type" class="form-control"
						   value="<?= (isset($getLeaveTypeData['leave_type']) && ($getLeaveTypeData['leave_type'] != '')) ? $getLeaveTypeData['leave_type'] : ''; ?>"
						   placeholder="Enter <?= lang('leave_type') ?>">
				</div>

				<div class="col-md-12 form-group">
					<span><?= lang('payment_status') ?><span class="text-danger"> * </span></span>
					<?php if(isset($getLeaveTypeData['payment_status']) &&  $getLeaveTypeData['payment_status'] == "yes"){?>
						<input type="radio" name="payment_status" id="payment_status" value = "no" style="margin: 22px 0 0;"> Leave Without Pay
						<input type="radio" name="payment_status" id="payment_status" value = "yes" style="margin-left:60px;" checked> Paid Leave
					<?php } else{ ?>
						<input type="radio" name="payment_status" id="payment_status" value = "no" checked> Leave Without Pay
						<input type="radio" name="payment_status" id="payment_status" value = "yes" style="margin-left:60px;"> Paid Leave
					<?php } ?>
				</div>

				<div class="col-md-12 form-group">
					<span><?= lang('total_days_type') ?><span class="text-danger"> * </span></span>
					<?php if(isset($getLeaveTypeData['days_type']) &&  strtolower($getLeaveTypeData['days_type']) == "increamental"){?>
						<input type="radio" name="no_of_days_type" id="no_of_days_type" value = "Static"> Static
						<input type="radio" name="no_of_days_type" id="no_of_days_type" value = "Unlimited" style="margin-left:60px;"> Unlimited
						<span style="display:none;" class="increament-section">
						<input type="radio" name="no_of_days_type" id="no_of_days_type" value = "Increamental" style="margin-left:60px;" checked> Increamented every month
						</span>
					<?php }else if(isset($getLeaveTypeData['days_type']) &&  strtolower($getLeaveTypeData['days_type']) == "unlimited"){?>
						<input type="radio" name="no_of_days_type" id="no_of_days_type" value = "Static"> Static
						<input type="radio" name="no_of_days_type" id="no_of_days_type" value = "Unlimited" style="margin-left:60px;" checked> Unlimited
						<span style="display:none;" class="increament-section">
						<input type="radio" name="no_of_days_type" id="no_of_days_type" value = "Increamental" style="margin-left:60px;"> Increamented every month
						</span>
					<?php } else{ ?>
						<input type="radio" name="no_of_days_type" id="no_of_days_type" value = "Static" checked> Static
						<input type="radio" name="no_of_days_type" id="no_of_days_type" value = "Unlimited" style="margin-left:60px;"> Unlimited
						<span style="display:none;" class="increament-section">
						<input type="radio" name="no_of_days_type" id="no_of_days_type" value = "Increamental" style="margin-left:60px;"> Increamented every month
						</span>
					<?php } ?>
				</div>

				<div class="col-md-12 form-group" id="days_increament_block" style="display:none;">
					<span><?= lang('no_of_days_per_month_increament') ?><span class="text-danger"> * </span></span>
					<input type="tel" name="increament_days" id="increament_days" class="form-control"
						   value="<?= (isset($getLeaveTypeData['increament_days']) && ($getLeaveTypeData['increament_days'] != '')) ? $getLeaveTypeData['increament_days'] : 1; ?>"
						   placeholder="Enter <?= lang('no_of_days_per_month_increament') ?>">
				</div>

				<div class="col-md-12 form-group" id="monthly_status_block" style="display:none;">
					<span><?= lang('monthly_status') ?><span class="text-danger"> * </span></span>
					<?php if(isset($getLeaveTypeData['monthly_status']) &&  strtolower($getLeaveTypeData['monthly_status']) == "gone"){?>
						<input type="radio" name="monthly_status" id="monthly_status" value = "deposit"> Amount Deposite
						<input type="radio" name="monthly_status" id="monthly_status" value = "carry_forward" style="margin-left:60px;"> Carry Forward
						<input type="radio" name="monthly_status" id="monthly_status" value = "gone" style="margin-left:60px;" checked> Gone Off
					<?php }else if(isset($getLeaveTypeData['monthly_status']) &&  strtolower($getLeaveTypeData['monthly_status']) == "carry_forward"){?>
						<input type="radio" name="monthly_status" id="monthly_status" value = "deposit"> Amount Deposite
						<input type="radio" name="monthly_status" id="monthly_status" value = "carry_forward" style="margin-left:60px;" checked> Carry Forward
						<input type="radio" name="monthly_status" id="monthly_status" value = "gone" style="margin-left:60px;"> Gone Off
					<?php } else{ ?>
						<input type="radio" name="monthly_status" id="monthly_status" value = "deposit" checked> Amount Deposite
						<input type="radio" name="monthly_status" id="monthly_status" value = "carry_forward" style="margin-left:60px;"> Carry Forward
						<input type="radio" name="monthly_status" id="monthly_status" value = "gone" style="margin-left:60px;"> Gone Off
					<?php } ?>
				</div>

				<div class="col-md-12 form-group" id="yearly_status_block" style="display:none;">
					<span><?= lang('yearly_status') ?><span class="text-danger"> * </span></span>
					<?php if(isset($getLeaveTypeData['yearly_status']) &&  strtolower($getLeaveTypeData['yearly_status']) == "gone"){?>
						<input type="radio" name="yearly_status" id="yearly_status" value = "deposit"> Amount Deposite
						<input type="radio" name="yearly_status" id="yearly_status" value = "gone" style="margin-left:60px;" checked> Gone Off
					<?php } else{ ?>
						<input type="radio" name="yearly_status" id="yearly_status" value = "deposit" checked> Amount Deposite
						<input type="radio" name="yearly_status" id="yearly_status" value = "gone" style="margin-left:60px;"> Gone Off
					<?php } ?>
				</div>

				<div class="col-md-12 form-group" id="total_days_block">
					<span><?= lang('total_days') ?><span class="text-danger"> * </span></span>
					<input type="tel" name="no_of_days" id="no_of_days" class="form-control"
						   value="<?= (isset($getLeaveTypeData['no_of_days']) && ($getLeaveTypeData['no_of_days'] != '')) ? $getLeaveTypeData['no_of_days'] : ''; ?>"
						   placeholder="Enter <?= lang('total_days') ?>">
				</div>

				<div class="col-md-12 form-group">
					<span><?= lang('description') ?><span class="text-danger"> * </span></span>
					<textarea name="description" id="description" placeholder="Enter Only 255 Character" class="form-control" rows="5" cols="5"><?php echo (isset($getLeaveTypeData['description']) && ($getLeaveTypeData['description'] != '')) ? $getLeaveTypeData['description'] : ''; ?></textarea>
					<label id="description-error" class="validation-error-label" for="description"></label>
				</div>

			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="submit-section text-center btn-add">
				<button type="button"
						onclick="window.location.href='<?php echo site_url('LeaveType'); ?>'"
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
//        CKEDITOR.replace( 'long_description', {
//            height: '400px',
//            extraPlugins: 'forms'
//        });
		numberInit();
		Select2Init();
		SwitcheryKeyGen();
		FileKeyGen();
		
	

		// Initialize
		jQuery.validator.addMethod("lettersonly", function(value, element) {
			return this.optional(element) || /^[a-z ]+$/i.test(value);
		}, "Only Letters are allowed");
		var validator = $("#leaveTypeDetails").validate({
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
				leave_type: {
					required: true,
				},
				no_of_days: {
					required: true,
				}
			},
			messages: {
				leave_type: {
					required: "Please Enter <?= lang('leave_type') ?>",
				},
				no_of_days: {
					required: "Please Enter <?= lang('no_of_days') ?>",
				},
				description: {
					required: "Please Enter <?= lang('description') ?>",
				}
			},submitHandler: function (e) {
				$(e).ajaxSubmit({
					url: '<?php echo site_url("LeaveType/save");?>',
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
								window.location.href = '<?php echo site_url('LeaveType');?>';
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
		
		if($('input:radio[name="payment_status"]:checked').val() == "yes"){
			$(".increament-section").show();
		}
		
		$('input:radio[name="payment_status"]').click(function(){
			if($(this).is(":checked")){
				if($(this).val() == 'yes'){
					$(".increament-section").show();
				}else{
					$(".increament-section").hide();
					$("input[name=no_of_days_type][value=Static]").attr('checked', true);
				}
			}
		});
		
		if($('input:radio[name="no_of_days_type"]:checked').val() == "Increamental"){
			$("#days_increament_block").show();
			$("#monthly_status_block").show();
			if($('input:radio[name="monthly_status"]:checked').val() == "carry_forward")
				$("#yearly_status_block").show();
			$("#total_days_block").hide();
		}else if($('input:radio[name="no_of_days_type"]:checked').val() == "Unlimited"){
			$("#days_increament_block").hide();
			$("#total_days_block").hide();
		}else{
			$("#days_increament_block").hide();
		}
		$('input:radio[name="no_of_days_type"]').click(function(){
			if($(this).is(":checked")){
				if($(this).val() == 'Increamental'){
					$("#no_of_days").val('0');
					$("#increament_days").val('1');
					$("#total_days_block").show();
					$("#days_increament_block").show();
					$("#monthly_status_block").show();
					
					$("#total_days_block").hide();
				}else if($(this).val() == 'Unlimited'){
					$("#days_increament_block").hide();
					$("#total_days_block").hide();
					$("#no_of_days").val(500);
				}else{
					$("#days_increament_block").hide();
					$("#total_days_block").show();
					$("#no_of_days").val('');
				}
			}
		});
		$('input:radio[name="monthly_status"]').click(function(){
			if($(this).is(":checked")){
				if($(this).val() == 'carry_forward'){
					$("#yearly_status_block").show();
				}else{
					$("#yearly_status_block").hide();
				}
			}
		});
		/*$("#increament_days").keyup(function(){
			var days = $(this).val();
			if($("#no_of_days").val() == '0')
				$("#no_of_days").val(days);		
		});*/

	});
</script>
