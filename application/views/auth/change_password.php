<div class="accordion add-employee" id="accordion-details">
	<?php
	//create  form open tag
	$form_id = array(
			'id' => 'change_password',
			'method' => 'post',
			'class' => 'form-horizontal',
	);
	echo form_open_multipart('', $form_id);
	?>
	<!-- Login User id-->
	<input type="hidden" name="user_id" value="<?php echo $user_id = (isset($user_id) && ($user_id != '')) ? $user_id : ''?>" id="user_id">
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic6">
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-six">
				CHANGE PASSWORD
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-six" class="collapse show ctm-padding" aria-labelledby="headingSix"
			 data-parent="#accordion-details">
			<div class="row">
				<div class="col-md-6 form-group">
					<span><?php echo lang('change_password_old_password_label', 'old_password');?></span>
					<input type="password" name="old" value="" id="old" class="form-control"
						   placeholder="Enter Old Password">
				</div>
				<div class="col-md-6 form-group">
					<span><?php echo sprintf(lang('change_password_new_password_label'), $min_password_length); ?></span>
					<input type="password" name="new" value="" id="new" class="form-control" pattern="<?php echo '^.{'.$this->data['min_password_length'].'}.*$' ?>"
						   placeholder="Enter New Password">
				</div>
				<div class="col-md-6 form-group">
					<span><?php echo lang('change_password_new_password_confirm_label', 'new_password_confirm');?></span>
					<input type="password" name="new_confirm" value="" id="new_confirm" class="form-control" pattern="<?php echo '^.{'.$this->data['min_password_length'].'}.*$' ?>"
						   placeholder="Enter Confirm Password">
				</div>

			</div>
		</div>

		<div class="row">
			<div class="col-12">
				<div class="submit-section text-center btn-add">
					<button type="reset" name="reset" id="reset" value="Clear"
							class="btn btn-theme text-white ctm-border-radius button-1 clear"><?= lang('cancel_btn') ?></button>
					<button type="submit" value="Change Password"
							class="btn btn-theme text-white ctm-border-radius button-1 submit">Change Password</button>
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
	</div>
</div>

<script>
    $(document).ready(function () {
        $(".clear").click(function () {
            $("form#change_password .validation-error-label").html('');
        })
        // Initialize
        var validator = $("#change_password").validate({
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
                old: {
                    required: true,
                    remote: {
                        url: "<?php echo site_url( "Auth/checkOldPassword");?>",
                        type: "post",
                        data: {
                            old: function () {
                                return $("#old").val();
                            }
                        }
                    }

                },

                new: {
                    required: true,
                    remote: {
                        url: "<?php echo site_url("Auth/newPasswordNotSameAsOldPassword");?>",
                        type: "post",
                        data: {
                            new: function () {
                                return $("#new").val();
                            },
                            id: function () {
                                return $("#user_id").val();
                            }
                        }
                    },
                    minlength: '<?php echo $this->config->item('min_password_length', 'ion_auth')?>',
                    maxlength: '<?php echo $this->config->item('max_password_length', 'ion_auth')?>'
                },
                new_confirm: {
                    required: true,
                    equalTo: "#new"
                }


            },
            messages: {
                old: {
                    required: "Please enter old password",
                    remote: "Old password is wrong"

                },
                new: {
                    required: "Please enter new password",
                    remote: "New password and old password cannot be same"
                },
                new_confirm: {
                    required: "Please enter confirm password",
                    equalTo: "New password and Confirm password must be same"
                }

            },
            submitHandler: function (e) {
                $(e).ajaxSubmit({
                    url: '<?php echo site_url() . "auth/change_password";?>',
                    type: 'post',
                    beforeSubmit: function (formData, jqForm, options) {
                        //$(e).find('button').hide();
                        $('#loader').show();
                    },
                    complete: function () {
                        $('#loader').hide();
                        //(e).find('button').show();
                    },
                    dataType: 'json',
                    clearForm: false,
                    success: function (resObj, statusText) {
                        if (resObj.success) {
                            swal({title: "Success", text: resObj.msg, type: "success"}, function () {
                                window.location.href = '<?php echo site_url('auth/logout');?>';
                            });
                        } else {
                            swal({title: "Error", text: resObj.msg, type: "error"}, function () {
                                window.location.href = '<?php echo site_url('auth/change_password');?>';
                            });
                        }
                    }
                });
            }
        });

    });


</script>
