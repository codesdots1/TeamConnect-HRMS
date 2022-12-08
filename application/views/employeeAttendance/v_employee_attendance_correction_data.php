<div class="heading-elements">
	<?php
	$empId = $this->session->userdata('emp_id');
	$correctionId = (isset($correctionData['attendance_correction_id']) && ($correctionData['attendance_correction_id'] != '')) ?  $correctionData['attendance_correction_id'] : '';
	$rejected = (isset($correctionData['approved']) && ($correctionData['approved'] != '')) ?  $correctionData['approved'] : '';
	$approved = (isset($correctionData['rejected']) && ($correctionData['rejected'] != '')) ?  $correctionData['rejected'] : '';
	if($rejected == 'false' && $approved == 'false'){
		?>
		<a  href="<?= site_url('EmployeeAttendance/attendanceCorrectionManage/'.$empId.'/'.$correctionId); ?>" data-popup='custom-tooltip' data-original-title='<?= lang('edit_correction_details') ?>' title='<?= lang('edit_correction_details') ?>' class='btn btn-xs border-slate-400 text-slate-400 btn-flat btn-icon btn-rounded showDetails'><?= lang('edit_request'); ?></a>

	<?php } ?>

	<ul class="icons-list">
		<li><a data-action="collapse"></a></li>
	</ul>
</div>
<div class="accordion add-employee-attendance" id="accordion-details">
	<?php
	//create  form open tag
	$form_id = array(
			'id' => 'attendanceCorrectionData',
			'method' => 'post',
			'class' => 'form-horizontal',
	);
	echo form_open_multipart('', $form_id);
	$correctionId = (isset($correctionData['attendance_correction_id']) && ($correctionData['attendance_correction_id'] != '')) ? $correctionData['attendance_correction_id'] : '' ?>
	<input type="hidden" name="attendance_correction_id" value="<?= $correctionId ?>" id="attendance_correction_id">

</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic5">
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-five">
				<?= lang('view_attendance_correction_details') ?>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-five" class="collapse show ctm-padding" aria-labelledby="headingFive"
			 data-parent="#accordion-details">
			<div class="row">

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('employee_name') ?> : </span></b>
					<?= (isset($correctionData['name']) && ($correctionData['name']!= '')) ? $correctionData['name'] : ''; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('old_login_time') ?> : </label></span></b>
					<?= (isset($correctionData['old_login_time']) && ($correctionData['old_login_time']!= '')) ? $correctionData['old_login_time'] : ''; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('old_logout_time') ?> : </span></b>
					<?= (isset($correctionData['old_logout_time']) && ($correctionData['old_logout_time']!= '')) ? $correctionData['old_logout_time'] : ''; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('new_login_time') ?> : </span></b>
					<?= (isset($correctionData['login_time']) && ($correctionData['login_time']!= '')) ? $correctionData['login_time'] : ''; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('attendance_date') ?> : </span></b>
					<?= (isset($correctionData['attendance_date']) && ($correctionData['attendance_date']!= '')) ? $correctionData['attendance_date'] : ''; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span style="display: block"><?= lang('status') ?> : </span></b>
					<?php $status = (isset($correctionData['status']) && ($correctionData['status']!= '')) ? $correctionData['status'] : '';
					if($status != ""){
						$statusArray = explode("|",$status);
						if($statusArray[0]  == 'true')
						{
							echo '<a href="#" class="btn btn-danger btn-lg disabled" role="button" aria-disabled="true">Rejected</a>';

						}else{
							if($statusArray[1]  == 'true')
								echo '<a href="#" class="btn btn-success btn-lg disabled" role="button" aria-disabled="true">Approved</a>';
							else
								echo '<a href="#" class="btn btn-dark btn-lg disabled" role="button" aria-disabled="true">UnApproved</a>';

						}
					}
					?>
				</div>

				<?php if(isset($correctionData['note']) && ($correctionData['note']!= '')){ ?>
					<div class="col-md-12 form-group">
						<b><span style="display: block"><?= lang('note') ?> : </span></b>
						<?= (isset($correctionData['note']) && ($correctionData['note']!= '')) ? $correctionData['note'] : ''; ?>
					</div>
				<?php } ?>
			</div>
			<?php if(isset($_POST['postdata']) && $_POST['postdata'] == 'correctionpage'){
				$cancelUrl = site_url('EmployeeAttendance/correctionList/'.$this->session->userdata['emp_id']);
			}else{
				$date = (isset($correctionData['attendance_date']) && ($correctionData['attendance_date']!= '')) ? $correctionData['attendance_date'] : '';
				$cancelUrl = site_url('EmployeeAttendance/attendanceDetails/'.$this->session->userdata['emp_id'].'/'.$date);
			}
			?>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="submit-section text-center btn-add">
				<button type="button" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"
						onclick="window.location.href='<?php echo $cancelUrl; ?>'"><?= lang('back_btn') ?> <i class="icon-arrow-left5 position-right"></i> </button>
				<button type="submit" class="btn btn-theme disabled text-white ctm-border-radius button-1 submit">
					<i id="icon-hide" class="icon-arrow-right8 position-right"></i>
				</button>
			</div>
		</div>
	</div>
	<?php echo form_close(); ?>
</div>
<!-- Added hiden form to post some variable in details form which will help to set url for back button -->
<form method="post" name="redirect" class="redirect" action="">
	<input type="hidden" class="post" name="postdata" value="listing">
	<input type="submit" style="display: none;">
</form>
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
	<?php if(isset($_POST['postdata']) && $_POST['postdata'] == 'correctionpage'){ ?>
	$(document).on('click', '.showDetails', function(e) {
		e.preventDefault(); 
		var link = $(this).attr('href');
		$(".redirect").attr('action', link);
		$('.post').attr("value",'correctionpage');
		$('.redirect').submit();
	});
	<?php } ?>
	
	function FileKeyGen() {
		$(".file-styled-primary").uniform({
			fileButtonClass: 'action btn bg-blue'
		});
	}

</script>
<style type="text/css">
.heading-elements .icons-list{
	float: right !important;
}
</style>
