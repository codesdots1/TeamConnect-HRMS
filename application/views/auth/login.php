<div class="inner-wrapper login-body">
	<div class="login-wrapper">
		<div class="container">
			<div class="loginbox shadow-sm grow">
				<div class="login-left">
					<img class="img-fluid" src="<?= $assets ?>images/logo.png" alt="Logo">
				</div>
				<div class="login-right">
					<div class="login-right-wrap">
						<h1>Login</h1>
						<p class="account-subtitle"><?php echo lang('login_heading'); ?> to our dashboard</p>

						<!-- Form -->
						<?php
						$form_id = array(
								'id' => 'loginfrm',
								'method' => 'post',
								'class' => 'form-horizontal'
						);


						echo form_open("auth/login", $form_id); ?>
							<div class="form-group">
								<input type="email" class="form-control" id="inputEmail" required name="identity"  placeholder="Username">
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
							</div>
							<div class="form-group">
								<input class="form-control" type="password" id="inputPassword" name="password" required placeholder="Password">
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
							</div>
							<div class="form-group">
								<button class="btn btn-theme button-1 text-white ctm-border-radius btn-block" type="submit">Login</button>
							</div>
						<!-- /Form -->

						<div class="text-center forgotpass"><a href="<?php echo base_url('Auth/forgot_password') ?>">Forgot Password?</a></div>
						<?php echo form_close(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {

		var validator = $("#loginfrm").validate({
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
				},
				password: {
					required: true,
				},
				'g-recaptcha-response': {
					required: true
				}
			},
			messages: {
				identity: {
					required: "Please Enter Your Email ",
				},
				password: {
					required: "Please Enter <?= lang('password') ?>"
				},
				'g-recaptcha-response': {
					required: '<?=lang('captcha_challenge') ?>',
				}
			},
			submitHandler: function (form) {
				form.submit();
			}
		});
	});
</script>

