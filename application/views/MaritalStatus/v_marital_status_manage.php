<div class="accordion add-employee" id="accordion-details">
	<?php
	//create  form open tag
	$form_id = array(
			'id'=>'maritalStatusDetails',
			'method'=>'post',
			'class'=>'form-horizontal'
	);
	echo form_open_multipart('', $form_id);
	$maritalStatusId = (isset($getMaritalStatusData['marital_status_id']) && ($getMaritalStatusData['marital_status_id'] != '')) ? $getMaritalStatusData['marital_status_id'] : '';        ?>
	<input type="hidden" name="marital_status_id" value="<?= $maritalStatusId ?>" id="marital_status_id">
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic1">
		<h4 class="cursor-pointer mb-0">
			<a class=" coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-one" aria-expanded="true">
				<?= lang('marital_status_heading') ?>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">

		<div id="basic-one" class="collapse show ctm-padding" aria-labelledby="basic1"
			 data-parent="#accordion-details">
			<form>
				<div class="row">
					<div class="col-12 form-group">
						<span><?= lang('marital_status') ?><span class="text-danger"> * </span></span>
						<input type="text" name="marital_status" id="marital_status" class="form-control"
							   value="<?= (isset($getMaritalStatusData['marital_status']) && ($getMaritalStatusData['marital_status'] != '')) ? $getMaritalStatusData['marital_status'] : ''; ?>"
							   placeholder="Enter <?= lang('marital_status') ?>">
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="submit-section text-center btn-add">
				<button type="button"
						onclick="window.location.href='<?php echo site_url('MaritalStatus'); ?>'"
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

        // Initialize
        jQuery.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-z ]+$/i.test(value);
        }, "Only Letters are allowed");
        var validator = $("#maritalStatusDetails").validate({
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
                'marital_status[]': {
                    required: true,
                }
            },
            messages: {
                'marital_status[]': {
                    required: "Please Select <?= lang('marital_status') ?>",
                }
            },submitHandler: function (e) {
                $(e).ajaxSubmit({
                    url: '<?php echo site_url("MaritalStatus/save");?>',
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
                                window.location.href = '<?php echo site_url('MaritalStatus');?>';
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
