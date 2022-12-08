<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-body align-center">
		<h5 class="panel-title"><?= lang('employee_attendance_heading_list') ?></h5>
		<?php
		if($getEmployeeAttendanceDetails['emp_id'] != "" && $getEmployeeAttendanceDetails['attendance_date']){
			$addUrl = site_url('EmployeeAttendance/manage/'.$getEmployeeAttendanceDetails['emp_id'].'/'.$getEmployeeAttendanceDetails['attendance_date']);
		}
		?>
		<?php
		$role = $this->session->userdata('role');
		$emp_id = $this->session->userdata('emp_id');
		if($role == 'admin' && $getEmployeeAttendanceDetails['emp_id'] == $emp_id){
			?>
			<ul class="nav nav-tabs float-right border-0 tab-list-emp">
				<li class="nav-item pl-3">
					<button type="button" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding deleteRecord"><i
								class="fa fa-trash"></i></button>
				</li>
				<li class="nav-item pl-3">
					<a href="<?= $addUrl; ?>"
					   class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><i
								class="fa fa-plus"></i> <?= lang('add_time_sheet') ?></a>
				</li>

			</ul>
		<?php } ?>
	</div>
	<?php
	if((isset($getEmployeeAttendanceDetails['out_time'])) && (isset($getEmployeeAttendanceDetails['attendance_date']))
			&& ($getEmployeeAttendanceDetails['out_time'] == "00:00:00" || $getEmployeeAttendanceDetails['min_out_time'] == "00:00:00") &&
			($getEmployeeAttendanceDetails['attendance_date'] == date("d-m-Y")))
	{
		$getEmployeeAttendanceDetails['logout_time'] = "In";
	}elseif((isset($getEmployeeAttendanceDetails['out_time'])) && (isset($getEmployeeAttendanceDetails['attendance_date']))
			&& ($getEmployeeAttendanceDetails['out_time'] == "00:00:00" || $getEmployeeAttendanceDetails['min_out_time'] == "00:00:00") &&
			($getEmployeeAttendanceDetails['attendance_date'] != date("d-m-Y")) ){

		$getEmployeeAttendanceDetails['logout_time'] = '00:00:00';
	}
	if(isset($getEmployeeAttendanceDetails['total_time']) && strpos($getEmployeeAttendanceDetails['total_time'],'-') !== false){

		$getEmployeeAttendanceDetails['total_time'] = '00:00:00';
	}
	?>
</div>
<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic5">
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-five">
				<?= lang('employee_attendance_heading_list') ?>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-five" class="collapse show ctm-padding" aria-labelledby="headingFive"
			 data-parent="#accordion-details">
			<div class="row">

				<div class="col-md-6 form-group">
					<b><span>Name : </span></b>
					<?= (isset($getEmployeeAttendanceDetails['emp_name'])) ? $getEmployeeAttendanceDetails['emp_name'] : ""; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span>Email : </label></span></b>
					<?= (isset($getEmployeeAttendanceDetails['email'])) ? $getEmployeeAttendanceDetails['email'] : ""; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span>Entry Time : </label></span></b>
					<?= (isset($getEmployeeAttendanceDetails['login_time'])) ? $getEmployeeAttendanceDetails['login_time'] : ""; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span>Exit Time : </label></span></b>
					<?= (isset($getEmployeeAttendanceDetails['logout_time'])) ? $getEmployeeAttendanceDetails['logout_time'] : ""; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span>Total Time : </label></span></b>
					<?= (isset($getEmployeeAttendanceDetails['total_time'])) ? $getEmployeeAttendanceDetails['total_time'] : ""; ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span>Date : </label></span></b>
					<?= (isset($getEmployeeAttendanceDetails['attendance_date'])) ? $getEmployeeAttendanceDetails['attendance_date'] : ""; ?>
				</div>

				<div class="col-md-12 form-group">
					<button type="button" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"
							onclick="window.location.href='<?php  echo (isset($this->session->userdata['role']) && ($this->session->userdata['role'] != 'admin')) ?
									site_url('EmployeeAttendance/index/'.$this->session->userdata['emp_id']) : site_url('EmployeeAttendance'); ?>'"><?= lang('back_btn') ?> <i class="icon-arrow-left5 position-right"></i> </button>
				</div>
			</div>

			<div class="table-responsive">
				<table id="employeeAttendanceDetailsTable" class="table " cellspacing="0" width="100%">
					<thead>
					<tr>
						<th><input id="checkAll" type="checkbox"  class="dt-checkbox styled"></th>
						<th><?= lang('login_time') ?></th>
						<th><?= lang('logout_time') ?></th>
						<th><?= lang('total_time') ?></th>

						<th><?= lang('action') ?></th>

					</tr>
					</thead>
				</table>
			</div>
		</div>
	</div>
	<?php echo form_close(); ?>
</div>

<script>
	$(document).ready(function () {
	
		var role = '<?php echo  $this->session->userdata('role') ?>';
		var employeeId = '<?php echo  $this->session->userdata('emp_id') ?>';
		dt_DataTable = $('#employeeAttendanceDetailsTable').DataTable({
			dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
			language: {
				//search: '<span>Filter:</span> _INPUT_',
				//searchPlaceholder: 'Type to filter...',
				lengthMenu: '<span>Show:</span> _MENU_',
				paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
			},
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?= site_url('EmployeeAttendance/getEmployeeAttendanceDataListing'); ?>",
				"type": "post",
				dataType: "json",
				"data": function (d) {
					return $.extend({}, d, {
						"<?= $this->security->get_csrf_token_name() ?>": '<?= $this->security->get_csrf_hash() ?>',
						"empId": "<?= $empId; ?>",
						"attendaceDate": "<?= $attendaceDate; ?>"
					});
				}
			},
			"iDisplayLength": "<?= PERPAGE_DISPLAY ?>",
			"order": [[1, "ASC"]],
			"stripeClasses": [ 'alpha-slate', 'even-row' ],
			"columns": [
				{"data": "employee_attendance_id"},
				{"data": "login_time"},
				{"data": "logout_time",
				
					"render": function (data, type, row) {
						var outTime = row['out_time'];
						var date = row['attendance_date'];
						var html = '';
						var fullDate  = new Date();
						var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) :(fullDate.getMonth()+1);
						var currentDate = ('0' + (fullDate.getDate())).slice(-2) + "-" + ('0' + twoDigitMonth).slice(-2) + "-" + fullDate.getFullYear();
						
						if(outTime == "00:00:00" && date == currentDate )
							 html = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; In';
						else if(outTime == "00:00:00" && date != currentDate )
							html = outTime;
						else
							html = data;
						return html;
					}				
				
				},
				{"data": "total_time",
					"render": function (data, type, row) {
						var html = '';
						if(data.indexOf('-') >  -1)
							html = '00:00:00';
						else
							html = data;
						
						return html;
							
					}
				},
				{
					"data": "actions",
					"render": function (data, type, row) {
						var html = '';
						var id = row['employee_attendance_id'];
						var status = row['correction_status'];
						var correctionId = row['correction_id'];
						if(role == 'admin' || employeeId == row['emp_id']){
							if(role == "admin"){
								html += "<a  href='<?= site_url('EmployeeAttendance/manage/'); ?>" + id + "' data-popup='custom-tooltip' data-original-title='<?= lang('edit_employee_attendance') ?>'  onclick='EditRecord(" + id + ")' title='<?= lang('edit_employee_attendance') ?>' class='btn btn-outline-warning btn-sm mr-1'><i class='fa fa-pencil'></i></a>";
								html += "&nbsp";
								html += "<a href='javascript:void(0);' onclick='DeleteRecord(" + id + ")' data-popup='custom-tooltip' data-original-title='<?= lang('delete_employee_attendance') ?>' title='<?= lang('delete_employee_attendance') ?>'  class='btn btn-outline-danger btn-sm mr-1' ><i class='fa fa-trash'></i></a>";
							}else{
								$('.heading-elements').hide();
								if(status == 'applyRequest')
									html += "<a  href='<?= site_url('EmployeeAttendance/correction/'); ?>" + employeeId + "/"+ id +"' data-popup='custom-tooltip' data-original-title='<?= lang('attendance_correction_request') ?>'  onclick='EditRecord(" + id + ")' title='<?= lang('attendance_correction_request') ?>' class='btn btn-outline-warning btn-sm mr-1'><?= lang('correction_request'); ?></a>";
								else if(status == 'requested')
									html += "<a  href='<?= site_url('EmployeeAttendance/getAttendanceCorrectionData/'); ?>" + employeeId + "/"+ correctionId +"' data-popup='custom-tooltip' data-original-title='<?= lang('view_correction_request') ?>' title='<?= lang('view_correction_request') ?>' class='btn btn-outline-warning btn-sm mr-1'><?= lang('request_sent'); ?></a>";
								else if(status == 'rejected')
									html += "<a  href='<?= site_url('EmployeeAttendance/getAttendanceCorrectionData/'); ?>" + employeeId + "/"+ correctionId +"' data-popup='custom-tooltip' data-original-title='<?= lang('view_correction_details') ?>'  title='<?= lang('view_correction_details') ?>' class='btn btn-outline-warning btn-sm mr-1'><?= lang('request_rejected'); ?></a>";
								else if(status == 'approved')
									html += "<a  href='<?= site_url('EmployeeAttendance/getAttendanceCorrectionData/'); ?>" + employeeId + "/"+ correctionId +"' data-popup='custom-tooltip' data-original-title='<?= lang('view_correction_details') ?>'  title='<?= lang('view_correction_details') ?>' class='btn btn-outline-warning btn-sm mr-1'><?= lang('request_approved'); ?></a>";
							}
						}else{
							html = '------------';
						}
						return html;
						
					},
					"sortable": false,
					"searchable": false
				},

			],
			"columnDefs": [
				{
					"targets": 0,
					"width": "10%",
					"render": function (data, type, row) {
						return '<label><input type="checkbox" class="dt-checkbox styled" id="ids_' + row['employee_attendance_id'] + '" name="ids[]" value="' + row['employee_attendance_id'] + '"/></label>';
					},
					"sortable": false,
					"searchable": false
				}
			],

			fnDrawCallback: function (oSettings) {
				// Switchery
				// Initialize multiple switches
				CheckboxKeyGen();
				DtSwitcheryKeyGen();
				CustomToolTip();
				ScrollToTop();
			}
		});
	});

</script>

<script>

	//Delete Time Cancel button click to remove checked value
	$(document).on('click', '.cancel', function () {
		$('#employeeAttendanceDetailsTable input[class="dt-checkbox styled"]').prop('checked', false);
		CheckboxKeyGen();
	});

	$(document).ready(function () {
		// Switchery
		// Initialize multiple switches
		SwitcheryKeyGen();
		CheckboxKeyGen('checkAll');
		CustomToolTip();

		///$('#checkAll').prop('checked', false);
		$('#checkAll').click(function () {
			var checkedStatus = this.checked;
			$('#employeeAttendanceDetailsTable tbody tr').find('td:first :checkbox').each(function () {
				$(this).prop('checked', checkedStatus);
			});
			CheckboxKeyGen();
		});

	});

	//Delete function
	function DeleteRecord(employeeAttendanceId) {
		$('#employeeAttendanceDetailsTable tbody input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + employeeAttendanceId).prop('checked', true);
		$('.deleteRecord').click();
		CheckboxKeyGen();
	}


	//Delete Record
	$(document).on('click', '.deleteRecord', function () {
		var deleteElement = $('#employeeAttendanceDetailsTable tbody input[class="dt-checkbox styled"]:checked');
		var selectedLength = deleteElement.size();
		//  CheckboxKeyGen();
		if (selectedLength == 0) {
			swal({
				title: "<?= ucwords(lang('info')); ?>",
				text: "<?= lang('delete_info'); ?>",
				confirmButtonColor: "<?= BTN_DELETE_INFO; ?>",
				type: "<?= lang('info'); ?>"
			},function(){
				return false;
			});
		} else {
			var deleteId = [];
			$.each(deleteElement, function (i, ele) {
				deleteId.push($(ele).val());
			});

			swal({
					title: "<?= ucwords(lang('delete')); ?>",
					text: "<?= lang('delete_warning'); ?>",
					type: "<?= lang('warning'); ?>",
					showCancelButton: true,
					closeOnConfirm: false,
					confirmButtonColor: "<?= BTN_DELETE_WARNING; ?>",
					showLoaderOnConfirm: true
				},
				function () {
					$.ajax({
						type: "post",
						url: "<?= site_url("EmployeeAttendance/delete")?>",
						dataType: "json",
						data: {deleteId: deleteId},
						success: function (data) {
							if (data['success']) {
								swal({
									title: "<?= ucwords(lang('success'))?>",
									text: data['msg'],
									type: "<?= lang('success')?>",
									confirmButtonColor: "<?= BTN_SUCCESS; ?>",
								});
								dt_DataTable.ajax.reload();
								//$('#checkAll').prop('checked', false);
								CheckboxKeyGen('checkAll');
							} else {
								swal({
									title: "<?= ucwords(lang('error')); ?>",
									text: data['msg'],
									type: "<?= lang('error'); ?>",
									confirmButtonColor: "<?= BTN_ERROR; ?>"
								});
								dt_DataTable.ajax.reload();
								CheckboxKeyGen('checkAll');
							}
						}
					});
				});
		}
	});


	//Edit function
	function EditRecord(employeeAttendanceId) {
		$('#employeeAttendanceDetailsTable  input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + employeeAttendanceId).prop('checked', true);
		//$('.editRecord').click();
		CheckboxKeyGen();

	}
</script>

<style type="text/css">
#employeeAttendanceDetailsTable_filter{
	display : none;
}
</style>
