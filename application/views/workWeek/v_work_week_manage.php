<div class="accordion add-employee" id="accordion-details">
	<?php
	//create  form open tag
	$form_id = array(
			'id'=>'workWeekDetails',
			'method'=>'post',
			'class'=>'form-horizontal'
	);
	echo form_open_multipart('', $form_id);
	$worWeekId = (isset($getWorkWeekData['work_week_id']) && ($getWorkWeekData['work_week_id'] != '')) ? $getWorkWeekData['work_week_id'] : '';
	?>
	<input type="hidden" name="work_week_id" value="<?= $worWeekId ?>" id="work_week_id">
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic1">
		<h4 class="cursor-pointer mb-0">
			<a class=" coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-one" aria-expanded="true">
				<?= lang('work_week_heading') ?>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-one" class="collapse show ctm-padding" aria-labelledby="basic1"
			 data-parent="#accordion-details">
			<div class="row">

				<div class="col-md-6 form-group">
					<span><?= lang('title') ?><span class="text-danger"> * </span></span>
					<input type="text" name="title" id="title" class="form-control"
						   value="<?= (isset($getWorkWeekData['title']) && ($getWorkWeekData['title'] != '')) ? $getWorkWeekData['title'] : ''; ?>"
						   placeholder="Enter <?= lang('title') ?>">
				</div>

				<div class="col-md-6 form-group">
					<span><?= lang('days_per_week') ?><span class="text-danger"> * </span></span>
					<input type="text" name="days_per_week" id="days_per_week" class="form-control"
						   value="<?= (isset($getWorkWeekData['days_per_week']) && ($getWorkWeekData['days_per_week'] != '')) ? $getWorkWeekData['days_per_week'] : ''; ?>"
						   placeholder="<?= lang('select_days') ?>" readonly>
				</div>


				<div class="table-responsive m-t-15">
					<table class="table table-striped custom-table">
						<thead>
						<tr>
							<th> Week Days <span class="text-danger"> * </span></th>
							<th class="text-center">Monday</th>
							<th class="text-center">Tuesday</th>
							<th class="text-center">Wednesday</th>
							<th class="text-center">Thursday</th>
							<th class="text-center">Friday</th>
							<th class="text-center">Saturday</th>
							<th class="text-center">Sunday</th>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td>Days Name</td>

							<?php
							$workName = array("Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun");
							if(isset($getWorkWeekData['work_week_id']) && ($getWorkWeekData['work_week_id'] != '')){

								$daysName = explode(',',$getWorkWeekData['days_name']);

								foreach ($workName as $key) {
									?>
									<td class="text-center">
										<input type="checkbox" name="days_name[]"  class="dt-checkbox styled"  value="<?php echo $key;?>" <?php if(in_array($key,$daysName)) {?> checked="checked" <?php }?>>
									</td>
									<?php
								}
							}else{

								foreach ($workName as $key) {
									?>
									<td class="text-center">
										<input type="checkbox" name="days_name[]" class="dt-checkbox styled" value="<?php echo $key;?>">
									</td>
									<?php
								}
							}
							?>

						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="submit-section text-center btn-add">
				<button type="button"
						onclick="window.location.href='<?php echo site_url('WorkWeek'); ?>'"
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
		// Full featured editor
//        CKEDITOR.replace( 'long_description', {
//            height: '400px',
//            extraPlugins: 'forms'
//        });
		numberInit();
		Select2Init();
		SwitcheryKeyGen();
		FileKeyGen();
		CheckboxKeyGen();

		// Initialize
		jQuery.validator.addMethod("lettersonly", function(value, element) {
			return this.optional(element) || /^[a-z ]+$/i.test(value);
		}, "Only Letters are allowed");
		var validator = $("#workWeekDetails").validate({
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
				title: {
					required: true,
				},
				days_per_week: {
					required: true,
				},
				'days_name[]': {
					required: true,
				}
			},
			messages: {
				title: {
					required: "Please Enter <?= lang('title') ?>",
				},
				days_per_week: {
					required: "Please Enter <?= lang('days_per_week') ?>",
				},
				'days_name[]': {
					required: "Please Select <?= lang('days_name') ?>",
				}
			},submitHandler: function (e) {
				$(e).ajaxSubmit({
					url: '<?php echo site_url("WorkWeek/save");?>',
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
								window.location.href = '<?php echo site_url('WorkWeek');?>';
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
		 $("input[name='days_name[]'").change(function() {
			updateCounter();
		 });
		
		
	});
		function updateCounter() {
			var len = $("input[name='days_name[]']:checked").length;
			if(len>0){
				$("#days_per_week").val(len);
			}else{
				$("#days_per_week").val('');
			}
		}

</script>
<style type="text/css">
#days_per_week{
	color: #999999;
	cursor: not-allowed;
}
</style>
