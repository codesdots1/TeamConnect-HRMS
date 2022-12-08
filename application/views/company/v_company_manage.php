<div class="panel panel-flat border-left-lg border-left-slate">
	<div class="panel-heading ">
		<h5 class="panel-title"><?= lang('company_form') ?><a class="heading-elements-toggle"><i class="icon-more"></i></a>
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
			'id'		=> 'companyDetails',
			'method'	=> 'post',
			'class'		=> 'form-horizontal'
		);
		echo form_open_multipart('', $form_id);
		$companyId = (isset($getCompanyData['company_id']) && ($getCompanyData['company_id'] != '')) ? $getCompanyData['company_id'] : '';
		?>
		<input type="hidden" name="company_id" value="<?= $companyId ?>" id="company_id">

		<div class="tabbable">
			<div class="tab-content">

				<div class="form-group">
					<label class="col-lg-3 control-label"><?= lang('company_name') ?><span class="text-danger"> * </span></label>
					<div class="col-lg-9">
						<input type="text" name="company_name" id="company_name" class="form-control"
							   value="<?= (isset($getCompanyData['company_name']) && ($getCompanyData['company_name'] != '')) ? $getCompanyData['company_name'] : ''; ?>"
							   placeholder="Enter <?= lang('company_name') ?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-3 control-label"><?= lang('email') ?><span class="text-danger"> * </span></label>
					<div class="col-lg-9">
						<input type="email" name="email" id="email" class="form-control"
							   value="<?= (isset($getCompanyData['email']) && ($getCompanyData['email'] != '')) ? $getCompanyData['email'] : ''; ?>"
							   placeholder="Enter <?= lang('email') ?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-3 control-label"><?= lang('site_name') ?><span class="text-danger"> * </span></label>
					<div class="col-lg-9">
						<input type="text" name="site_name" id="site_name" class="form-control"
							   value="<?= (isset($getCompanyData['site_name']) && ($getCompanyData['site_name'] != '')) ? $getCompanyData['site_name'] : ''; ?>"
							   placeholder="Enter <?= lang('site_name') ?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-3 control-label"><?= lang('site_url') ?><span class="text-danger"> * </span></label>
					<div class="col-lg-9">
						<input type="text" name="site_url" id="site_url" class="form-control"
							   value="<?= (isset($getCompanyData['site_url']) && ($getCompanyData['site_url'] != '')) ? $getCompanyData['site_url'] : ''; ?>"
							   placeholder="Enter <?= lang('site_url') ?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-3 control-label"><?= lang('phone_no') ?><span class="text-danger"> * </span></label>
					<div class="col-lg-9">
						<input type="tel" name="phone_no" id="phone_no" class="form-control"
							   value="<?= (isset($getCompanyData['phone_no']) && ($getCompanyData['phone_no'] != '')) ? $getCompanyData['phone_no'] : ''; ?>"
							   placeholder="Enter <?= lang('phone_no') ?>" maxlength="10">
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-3 control-label"><?= lang('fax_no') ?><span class="text-danger"> * </span></label>
					<div class="col-lg-9">
						<input type="tel" name="fax_no" id="fax_no" class="form-control"
							   value="<?= (isset($getCompanyData['fax_no']) && ($getCompanyData['fax_no'] != '')) ? $getCompanyData['fax_no'] : ''; ?>"
							   placeholder="Enter <?= lang('fax_no') ?>" maxlength="15">
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-3 control-label"><?= lang('description') ?><span class="text-danger"> * </span></label>
					<div class="col-lg-9">
						<textarea name="description" id="description" placeholder="Enter Only 255 Character" class="form-control" rows="5" cols="5"><?php echo (isset($getCompanyData['description']) && ($getCompanyData['description'] != '')) ? $getCompanyData['description'] : ''; ?></textarea>
						<label id="description-error" class="validation-error-label" for="description"></label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-3 control-label"><?= lang('address') ?><span class="text-danger"> * </span></label>
					<div class="col-lg-9">
						<textarea name="address" id="address" placeholder="Enter Only 255 Character" class="form-control" rows="5" cols="5"><?php echo (isset($getCompanyData['address']) && ($getCompanyData['address'] != '')) ? $getCompanyData['address'] : ''; ?></textarea>
						<label id="address-error" class="validation-error-label" for="address"></label>
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-3 control-label"><?= lang('postal_code') ?><span class="text-danger"> * </span></label>
					<div class="col-lg-9">
						<input type="tel" name="postal_code" id="postal_code" class="form-control" maxlength="6" minlength="6"
							   placeholder="Enter <?= lang('postal_code') ?>"
							   value="<?= (isset($getCompanyData['postal_code']) && ($getCompanyData['postal_code'] != '')) ? $getCompanyData['postal_code'] : ''; ?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-3 control-label"><?= lang('country') ?><span class="text-danger"> * </span></label>
					<div class="col-lg-9">
						<select name="country_id" id="country_id" class="form-control" data-placeholder="Select <?= lang('country') ?> ">
							<option value=""></option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-3 control-label"><?= lang('state') ?><span class="text-danger"> * </span></label>
					<div class="col-lg-9">
						<select name="state_id" id="state_id" class="form-control" data-placeholder="Select <?= lang('state') ?> ">
							<option value=""></option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-3 control-label"><?= lang('city') ?><span class="text-danger"> * </span></label>
					<div class="col-lg-9">
						<select name="city_id" id="city_id" class="form-control" data-placeholder="Select <?= lang('city') ?> ">
							<option value=""></option>
						</select>
					</div>
				</div>

				<div class="form-group">
					<label class="col-lg-3 control-label"><?= lang('is_active') ?></label>
					<div class="col-lg-9">
						<div class="checkbox checkbox-switchery switchery-xs">
							<label>
								<input type="checkbox"
									   name="is_active" <?php if (isset($getCompanyData['is_active']) && $getCompanyData['is_active'] == 1) {
									echo 'checked="checked"';
								} else if (isset($getCompanyData['is_active']) && $getCompanyData['is_active'] == 0) {
									echo '';
								}else{
									echo 'checked="checked"';
								} ?> id="is_active" class="switchery">
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<!-- create reset button-->
	<div class="text-right">
		<button type="button" class="btn btn-xs border-slate text-slate btn-flat cancel"
				onclick="window.location.href='<?php echo site_url('Company'); ?>'"><?= lang('cancel_btn') ?> <i class="icon-cross2 position-right"></i> </button>

		<button type="submit"
				class="btn btn-xs border-blue text-blue btn-flat btn-ladda btn-ladda-progress submit"
				data-spinner-color="#03A9F4" data-style="fade"><span class="ladda-label"><?= lang('submit_btn') ?></span>
			<i id="icon-hide" class="icon-arrow-right8 position-right"></i>
		</button> <br/><br/>
	</div>
	<?php echo form_close(); ?>
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
			countryDD('','#country_id');
			stateDD('','#state_id');
			cityDD('','#city_id');

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
				rules: {
					company_name: {
						required: true,
					},
					site_name: {
						required: true,
					},
					email: {
						required: true,
						validEmail: true,
						remote: {
							url: "<?php echo site_url( "Company/NameExist");?>",
							type: "post",
							data: {
								column_name: function () {
									return "email";
								},
								column_id: function () {
									return $("#company_id").val();
								},
								table_name: function () {
									return "tbl_companies";
								}
							}
						}
					},
					site_url: {
						required: true,
					},
					phone_no: {
						required: true,
						digits: true,
						maxlength: 10
					},
					fax_no: {
						required: true,
						digits: true,
						maxlength: 15
					},
					address: {
						required: true,
					},
					description: {
						required: true,
					},
					country_id: {
						required: true,
					},
					state_id: {
						required: true,
					},
					city_id: {
						required: true,
					},
					postal_code: {
						required: true,
					},
					status: {
						required: true,
					},
				},
				messages: {
					company_name: {
						required: "Please Enter <?= lang('company_name') ?>",
					},
					site_name: {
						required: "Please Enter <?= lang('site_name') ?>",
					},
					email: {
						required: "Please Enter <?= lang('email') ?>",
						remote  : "<?= lang('email') ?> Already Exist"
					},
					site_url: {
						required: "Please Enter <?= lang('site_url') ?>",
					},
					phone_no: {
						required: "Please Enter <?= lang('phone_no') ?>",
						digits: "Please Enter Only Digits",
						maxlength: "Enter Only 10 Digits"
					},
					fax_no: {
						required: "Please Enter <?= lang('fax_no') ?>",
						digits: "Please Enter Only Digits",
						maxlength: "Enter Only 15 Digits"
					},
					address: {
						required: "Please Enter <?= lang('address') ?>",
					},
					description: {
						required: "Please Select <?= lang('description') ?>",
					},
					country_id: {
						required: "Please Select <?= lang('country') ?>",
					},
					state_id: {
						required: "Please Select <?= lang('state') ?>",
					},
					city_id: {
						required: "Please Enter <?= lang('city') ?>",
					},
					postal_code: {
						required: "Please Enter <?= lang('postal_code') ?>",
					},
					status: {
						required: "Please Select <?= lang('status') ?>",
					}
				},submitHandler: function (e) {
					$(e).ajaxSubmit({
						url: '<?php echo site_url("Company/save");?>',
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
									window.location.href = '<?php echo site_url('Company');?>';
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
			
		$("#country_id").change(function(){	
			var country = '<?= isset($getCompanyData['country_name']) ? $getCompanyData['country_name'] : "" ?>';
			if(country != ""){
				if($(this).text().trim() != country.trim()){
					var option = new Option("", "", true, true);
					$('#state_id').append(option).trigger('change');
					$('#city_id').append(option).trigger('change');
				}
			}else{
				var option = new Option("", "", true, true);
					$('#state_id').append(option).trigger('change');
					$('#city_id').append(option).trigger('change');
			}
		});
		
		$("#state_id").change(function(){	
			var state = '<?= isset($getCompanyData['state_name']) ? $getCompanyData['state_name'] : ""  ?>';
			if(state != ""){
				if($(this).text().trim() != state.trim()){
					var option = new Option("", "", true, true);
					$('#city_id').append(option).trigger('change');
				}
				
			}else{
				var option = new Option("", "", true, true);
				$('#city_id').append(option).trigger('change');
			}
		});



			<?php if((isset($getCompanyData['city_name']) && !empty($getCompanyData['city_name']))){ ?>
			var option = new Option("<?= $getCompanyData['city_name']; ?>", "<?= $getCompanyData['city_id']; ?>", true, true);
			$('#city_id').append(option).trigger('change');
			<?php } ?>

			<?php if((isset($getCompanyData['country_name']) && !empty($getCompanyData['country_name']))){ ?>
			var option = new Option("<?= $getCompanyData['country_name']; ?>", "<?= $getCompanyData['country_id']; ?>", true, true);
			$('#country_id').append(option).trigger('change');
			<?php } ?>

			<?php if((isset($getCompanyData['state_name']) && !empty($getCompanyData['state_name']))){ ?>
			var option = new Option("<?= $getCompanyData['state_name']; ?>", "<?= $getCompanyData['state_id']; ?>", true, true);
			$('#state_id').append(option).trigger('change');
			<?php } ?>

		});
	</script>
</div>
<?php if (isset($select2)) { ?>
	<?= $select2 ?>
<?php } ?>
