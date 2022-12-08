<div class="panel panel-flat border-left-lg border-left-slate">
	<div class="panel-heading ">
		<h5 class="panel-title"><?= lang('account_details_heading') ?><a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>
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
			'id' => 'accountHolderDetails',
			'method' => 'post',
			'class' => 'form-horizontal'
		);
		echo form_open_multipart('', $form_id);
		$accountDetailsId = (isset($accountDetailsData['account_details_id']) && ($accountDetailsData['account_details_id'] != '')) ? $accountDetailsData['account_details_id'] : '';
		?>
		<input type="hidden" name="account_details_id" id="account_details_id"
			   value="<?= (isset($accountDetailsData['account_details_id']) && ($accountDetailsData['account_details_id'] != '')) ?
				   $accountDetailsData['account_details_id'] : '' ?>">
		<div class="tab-content">

			<div class="form-group">
				<label class="col-lg-3 control-label"><?= lang('employee') ?><span class="text-danger"> * </span></label>
				<div class="col-lg-9">
					<select name="emp_id" id="emp_id" class="form-control" data-placeholder="Select <?= lang('employee') ?> ">
						<option value=""></option>
					</select>
				</div>
			</div>

			<!-- Bank Name -->
			<div class="form-group">
				<label class="col-lg-3 control-label"><?= lang('bank_name') ?><span class="text-danger"> * </span></label>
				<div class="col-lg-9">
					<input type="text" name="bank_name" value="<?= (isset($accountDetailsData['bank_name']) && ($accountDetailsData['bank_name'] != '')) ? $accountDetailsData['bank_name'] : ''; ?>" id="bank_name" class="form-control"
						   placeholder="Enter <?= lang('bank_name') ?>">
				</div>
			</div>

			<!-- Holder Name -->
			<div class="form-group">
				<label class="col-lg-3 control-label"><?= lang('holder_name') ?><span class="text-danger"> * </span></label>
				<div class="col-lg-9">
					<input type="text" name="holder_name" value="<?= (isset($accountDetailsData['holder_name']) && ($accountDetailsData['holder_name'] != '')) ? $accountDetailsData['holder_name'] : ''; ?>" id="holder_name" class="form-control"
						   placeholder="Enter <?= lang('holder_name') ?>">
				</div>
			</div>

			<!-- Bank Code -->
			<div class="form-group">
				<label class="col-lg-3 control-label"><?= lang('bank_code') ?><span class="text-danger"> * </span></label>
				<div class="col-lg-9">
					<input type="text" name="bank_code" value="<?= (isset($accountDetailsData['bank_code']) && ($accountDetailsData['bank_code'] != '')) ? $accountDetailsData['bank_code'] : ''; ?>" id="bank_code" class="form-control"
						   placeholder="Enter <?= lang('bank_code') ?>">
				</div>
			</div>

			<!-- Account Number -->
			<div class="form-group">
				<label class="col-lg-3 control-label"><?= lang('account_number') ?><span class="text-danger"> * </span></label>
				<div class="col-lg-9">
					<input type="tel" name="account_number" value="<?= (isset($accountDetailsData['account_number']) && ($accountDetailsData['account_number'] != '')) ? $accountDetailsData['account_number'] : ''; ?>" id="account_number" class="form-control"
						   placeholder="Enter <?= lang('account_number') ?>" maxlength="16">
				</div>
			</div>

			<!-- create button -->
			<div class="text-right">
				<button type="button" class="btn btn-xs border-slate text-slate btn-flat cancel"
						onclick="window.location.href='<?php echo site_url('AccountDetails'); ?>'"><?= lang('cancel_btn') ?> <i class="icon-cross2 position-right"></i> </button>

				<button type="submit"
						class="btn btn-xs border-blue text-blue btn-flat btn-ladda btn-ladda-progress submit" data-spinner-color="#03A9F4" data-style="fade"><span class="ladda-label"><?= lang('submit_btn') ?></span>
					<i id="icon-hide" class="icon-arrow-right8 position-right"></i>
				</button>
			</div>
		</div>
	</div>
</div>
<?php echo form_close(); ?>
</div>

<script>
	var laddaSubmitBtn = Ladda.create(document.querySelector('.submit'));

	$(document).ready(function () {
		Select2Init();
		employeeNameDD('','#emp_id');

		$.validator.addMethod('filesize', function (value, element, param) {
			return this.optional(element) || (element.files[0].size <= param)
		});
		// Initialize
		var validator = $("#accountHolderDetails").validate({
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
					required: true,
				},
				bank_name : {
					required  : true
				},
				holder_name: {
					required: true
				},
				bank_code: {
					required: true,
				},
				account_number : {
					required: true,
					digits: true,
					maxlength: 16
				}
			},
			messages: {
				bank_name :{
					required: "Please Enter <?= lang('bank_name') ?>",
				},
				holder_name: {
					required: "Please Enter <?= lang('holder_name') ?>"
				},
				bank_code: {
					required: "Please Enter <?= lang('bank_code') ?>"
				},
				account_number: {
					required: "Please Enter <?= lang('account_number') ?>",
					digits: "Allow only Digits",
					maxlength: "Please Enter 16 Digit Number",
				}
			},
			submitHandler: function (e) {
				$(e).ajaxSubmit({
					url: '<?php echo site_url("AccountDetails/save");?>',
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
								window.location.href = '<?php echo site_url('AccountDetails');?>';
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

		<?php if((isset($accountDetailsData['emp_name']) && !empty($accountDetailsData['emp_name']))){ ?>
		var option = new Option("<?= $accountDetailsData['emp_name']; ?>", "<?= $accountDetailsData['emp_id']; ?>", true, true);
		$('#emp_id').append(option).trigger('change');
		<?php } ?>

		SwitcheryKeyGen();
		FileValidate();
		FileKeyGen();
	});
</script>
<?php if (isset($select2)) { ?>
	<?= $select2 ?>
<?php } ?>

