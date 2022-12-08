<script src='https://www.google.com/recaptcha/api.js'></script>

<div class="inner-wrapper login-body">
	<div class="login-wrapper">
		<div class="container">
			<div class="loginbox shadow-sm grow">
				<div class="login-left">
					<img class="img-fluid" src="<?= $assets ?>images/logo.png" alt="Logo">
				</div>
				<div class="login-right">
					<div class="login-right-wrap">
						<h1>Forgot Password</h1>
						<p class="account-subtitle"><?php echo sprintf(lang('forgot_password_subheading'), $identity_label); ?></p>


						<!-- Form -->
						<?php
						$form_id = array(
								'id' => 'forgotPassword',
								'method' => 'post',
								'class' => 'form-horizontal'
						);
						echo form_open("auth/forgot_password", $form_id); ?>
						<div class="form-group">
							<input type="email" class="form-control" id="inputEmail" required name="identity"  placeholder="Username">
							<div class="form-control-feedback">
								<i class="icon-user text-muted"></i>
							</div>
						</div>
						<div class="form-group has-feedback">
							<div class="g-recaptcha" data-sitekey="<?php echo $this->config->item("recaptcha_site_key") ?>"></div>
							<label id="g-recaptcha-response-error" class="validation-error-label" for="g-recaptcha-response"></label>
						</div>
						<!-- /Form -->

						<button type="submit" id="submit" class="btn btn-theme button-1 text-white ctm-border-radius btn-block"><?= lang('forgot_password_submit_btn') ?> </button>

						<button type="button" id="wait" class="btn btn-theme button-1 text-white ctm-border-radius btn-block" disabled>Please Wait</button>

						<a href="<?= site_url("auth/login"); ?>" class="btn btn-info btn-block content-group legitRipple">
							<i class="fa fa-arrow-left"></i>&nbsp<?php echo lang('login_forgot_submit_btn') ?></a>

						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
    $(document).ready(function () {
        $("#wait").hide();
        var validator = $("#forgotPassword").validate({

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
                identity: {
                    required: true,
                    email: true,
                    remote: {
                        url: "<?php echo site_url( "Auth/checkEmailExist");?>",
                        type: "post",
                        identity: {
                            old: function () {
                                return $("#identity").val();
                            }
                        }
                    }
                },
                'g-recaptcha-response': {
                    required: true
                }
            },
            messages: {
                identity: {
                    required: "Please enter email",
                    email: "Please enter valid email address",
                    remote: "No record of that email address"
                },
                'g-recaptcha-response': {
                    required: 'Please complete captcha challenge',
                }
            },
            submitHandler: function (form) {
                $('#submit').hide();
                $("#wait").show();
                form.submit();
            }
        });
    });
</script>
