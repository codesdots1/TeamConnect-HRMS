<div class="panel panel-flat border-left-lg border-left-slate">
	<div class="panel-heading ">
		<h5 class="panel-title"><?= lang('salary_heading') ?><a class="heading-elements-toggle"><i class="icon-more"></i></a>
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
			'id'=>'salaryDetails',
			'method'=>'post',
			'class'=>'form-horizontal'
		);
		echo form_open_multipart('', $form_id);
		$salaryId = (isset($getSalaryData['salary_id']) && ($getSalaryData['salary_id'] != '')) ? $getSalaryData['salary_id'] : '';
		?>
		<input type="hidden" name="salary_id" value="<?= $salaryId ?>" id="salary_id">

		<div class="tabbable">
			<div class="tab-content">

				<div class="form-group">
					<label class="col-lg-3 control-label"><?= lang('amount') ?><span class="text-danger"> * </span></label>
					<div class="col-lg-9">
						<input type="tel" name="amount" id="amount" class="form-control"
							   value="<?= (isset($getSalaryData['amount']) && ($getSalaryData['amount'] != '')) ? $getSalaryData['amount'] : ''; ?>"
							   placeholder="Enter <?= lang('amount') ?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-3 control-label"><?= lang('esic') ?><span class="text-danger"> * </span></label>
					<div class="col-lg-9">
						<input type="tel" name="esic" id="esic" class="form-control"
							   value="<?= (isset($getSalaryData['esic']) && ($getSalaryData['esic'] != '')) ? $getSalaryData['esic'] : ''; ?>"
							   placeholder="Enter <?= lang('esic') ?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-3 control-label"><?= lang('pf') ?><span class="text-danger"> * </span></label>
					<div class="col-lg-9">
						<input type="tel" name="pf" id="pf" class="form-control"
							   value="<?= (isset($getSalaryData['pf']) && ($getSalaryData['pf'] != '')) ? $getSalaryData['pf'] : ''; ?>"
							   placeholder="Enter <?= lang('pf') ?>">
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- create reset button-->
	<div class="text-right">
		<button type="button" class="btn btn-xs border-slate text-slate btn-flat cancel"
				onclick="window.location.href='<?php echo site_url('Salary'); ?>'"><?= lang('cancel_btn') ?> <i class="icon-cross2 position-right"></i> </button>

		<button type="submit"
				class="btn btn-xs border-blue text-blue btn-flat btn-ladda btn-ladda-progress submit"
				data-spinner-color="#03A9F4" data-style="fade"><span
				class="ladda-label"><?= lang('submit_btn') ?></span>
			<i id="icon-hide" class="icon-arrow-right8 position-right"></i>
		</button> <br/><br/>
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
		var validator = $("#salaryDetails").validate({
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
				amount: {
					required: true,
				},
				esic: {
					required: true,
				},
				pf: {
					required: true,
				}
			},
			messages: {
				amount: {
					required: "Please Enter <?= lang('amount') ?>",
				},
				esic: {
					required: "Please Enter <?= lang('esic') ?>",
				},
				pf: {
					required: "Please Enter <?= lang('pf') ?>",
				}
			},submitHandler: function (e) {
				$(e).ajaxSubmit({
					url: '<?php echo site_url("Salary/save");?>',
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
								window.location.href = '<?php echo site_url('Salary');?>';
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
</script>
