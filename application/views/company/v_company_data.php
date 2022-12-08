<div class="accordion add-employee-attendance" id="accordion-details">
	<?php
	//create  form open tag
	$form_id = array(
			'id' => 'companyDetails',
			'method' => 'post',
			'class' => 'form-horizontal',
	);
	echo form_open_multipart('', $form_id);
	$companyId = (isset($getCompanyInfoData['company_id']) && ($getCompanyInfoData['company_id'] != '')) ? $getCompanyInfoData['company_id'] : '' ?>
	<input type="hidden" name="company_id" value="<?= $companyId ?>" id="company_id">
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic5">
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-five">
				<?= lang('view_company') ?>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-five" class="collapse show ctm-padding" aria-labelledby="headingFive"
			 data-parent="#accordion-details">
			<div class="row">

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('company_image') ?> : </span></b>
					<img src="<?= base_url().$this->config->item('company_logo') ?>" alt="" width="50px">
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('company_name') ?> : </label></span></b>
					<?= (isset($getCompanyInfoData['company_name']) && ($getCompanyInfoData['company_name']!= '')) ? $getCompanyInfoData['company_name'] : ''; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('email') ?> : </span></b>
					<?= (isset($getCompanyInfoData['email']) && ($getCompanyInfoData['email']!= '')) ? $getCompanyInfoData['email'] : ''; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('site_url') ?> : </span></b>
					<a><?= (isset($getCompanyInfoData['site_url']) && ($getCompanyInfoData['site_url']!= '')) ? $getCompanyInfoData['site_url'] : ''; ?></a>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('fax_no') ?> : </span></b>
					<?= (isset($getCompanyInfoData['fax_no']) && ($getCompanyInfoData['fax_no']!= '')) ? $getCompanyInfoData['fax_no'] : ''; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('description') ?> : </span></b>
					<?= (isset($getCompanyInfoData['description']) && ($getCompanyInfoData['description']!= '')) ? $getCompanyInfoData['description'] : ''; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('address') ?> : </span></b>
					<?= (isset($getCompanyInfoData['address']) && ($getCompanyInfoData['address']!= '')) ? $getCompanyInfoData['address'] : ''; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('postal_code') ?> : </span></b>
					<?= (isset($getCompanyInfoData['postal_code']) && ($getCompanyInfoData['postal_code']!= '')) ? $getCompanyInfoData['postal_code'] : ''; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('country') ?> : </span></b>
					<?= (isset($getCompanyInfoData['country_name']) && ($getCompanyInfoData['country_name']!= '')) ? $getCompanyInfoData['country_name'] : ''; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('state') ?> : </span></b>
					<?= (isset($getCompanyInfoData['state_name']) && ($getCompanyInfoData['state_name']!= '')) ? $getCompanyInfoData['state_name'] : ''; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('city') ?> : </span></b>
					<?= (isset($getCompanyInfoData['city_name']) && ($getCompanyInfoData['city_name']!= '')) ? $getCompanyInfoData['city_name'] : ''; ?>
				</div>


				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('is_active') ?> : </span></b>
					<?php
					if($getCompanyInfoData['is_active'] == 0){
						echo "In-Active";
					}else{
						echo "Active";
					}
					?>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="submit-section text-center btn-add">
				<button type="button" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"
						onclick="window.location.href='<?php echo site_url('Employee'); ?>'"><?= lang('back_btn') ?> <i class="icon-arrow-left5 position-right"></i> </button>
				<button type="submit" class="btn btn-theme disabled text-white ctm-border-radius button-1 submit">
					<i id="icon-hide" class="icon-arrow-right8 position-right"></i>
				</button>
			</div>
		</div>
	</div>
	<?php echo form_close(); ?>
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
		var validator = $("#companyDetails").validate({
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
