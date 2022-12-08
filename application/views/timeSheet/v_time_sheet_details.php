<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-body align-center">
		<h5 class="panel-title"><?= lang('time_sheet_index') ?></h5>
		<?php
		if(isset($timeDetails['fill_date'])){
			$date = $timeDetails['fill_date'];
		}else{
			$date = date(PHP_DATE_FORMATE);
		}
		$addUrl = site_url('TimeSheet/manage/'.$this->session->userdata('emp_id').'/'.$date);

		if(strtolower($this->session->userdata['role']) != 'admin'){
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
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-header" id="basic5">
		<h4 class="cursor-pointer mb-0">
			<a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse"
			   data-target="#basic-five">
				<?= lang('time_sheet_index') ?>
			</a>
		</h4>
	</div>
	<div class="card-body p-0">
		<div id="basic-five" class="collapse show ctm-padding" aria-labelledby="headingFive"
			 data-parent="#accordion-details">
			<div class="row">

				<div class="col-md-6 form-group">
					<b><span>Name : </span></b>
					<?= (isset($getTimeSheetDetails['emp_name'])) ? $getTimeSheetDetails['emp_name'] : $this->session->userdata('emp_name'); ?>
				</div>

				<div class="col-md-6 form-group">
					<b><span>Total Working Hours: </label></span></b>
					<?= (isset($getTimeSheetDetails['hours'])) ? $getTimeSheetDetails['hours'] : "0h"; ?>
				</div>

				<?php
				if($this->uri->segment(4) != ""){
					$d = $this->uri->segment(4);
				}else{ $d = ""; }
				?>
				<div class="col-md-6 form-group">
					<b><span>Date : </label></span></b>
					<?= (isset($getTimeSheetDetails['time_sheet_date'])) ? $getTimeSheetDetails['time_sheet_date'] : $d ; ?>
				</div>

				<div class="col-md-12 form-group">
					<button type="button" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"
							onclick="window.location.href='<?php  echo (isset($this->session->userdata['role']) && ($this->session->userdata['role'] != 'admin')) ?
						site_url('TimeSheet/index/'.$this->session->userdata['emp_id']) : site_url('TimeSheet'); ?>'"><?= lang('back_btn') ?> <i class="icon-arrow-left5 position-right"></i> </button>
				</div>

			</div>

			<div class="table-responsive">
				<?php
				if((isset($timeDetails['fill_minutes']) || isset($timeDetails['fill_hours']) ) && (isset($timeDetails['fill_date']))){
					echo '<div><marquee behavior="slide" direction="left" class="time-info-rotate" scrolldelay = "50" style="margin-bottom:5px; color: floralwhite;">
			Your Time Sheet of Date '.@$timeDetails['fill_date'].' of '.ltrim(@$timeDetails['fill_hours'], '0').' '.ltrim(@$timeDetails['fill_minutes'], '0').' has been left. Please Fill that First</marquee></div>';
				}
				?>
				<table id="timeSheetDetailsTable" class="table " cellspacing="0" width="100%">
					<thead>
					<tr>
						<th><input id="checkAll" type="checkbox"  class="dt-checkbox styled"></th>
						<th><?= lang('project_name') ?></th>
						<th><?= lang('task_name') ?></th>
						<th><?= lang('leave_reason') ?></th>
						<th><?= lang('note') ?></th>
						<th style="width:5%"><?= lang('hours') ?></th>
						<?php if(strtolower($this->session->userdata['role']) != 'admin'){ ?>
							<th><?= lang('action') ?></th>
						<?php }else{ ?>
							<th></th>
						<?php } ?>

					</tr>
					</thead>
				</table>
				<div class="col-md-6 form-group">
					<legend style="margin-left:20px"> <b>Current Page Hours :<b> <b><?= $getTimeSheetDetails['hours']?></b> </legend>
				</div>
			</div>
		</div>
	</div>
	<?php echo form_close(); ?>
</div>

<script>
	$(document).ready(function () {

		var role = '<?php echo  $this->session->userdata['role'] ?>';
		var employeeId = '<?php echo  $this->session->userdata['emp_id'] ?>';
		dt_DataTable = $('#timeSheetDetailsTable').DataTable({
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
				"url": "<?= site_url('TimeSheet/getTimeSheetDataListing'); ?>",
				"type": "post",
				dataType: "json",
				"data": function (d) {
					return $.extend({}, d, {
						"<?= $this->security->get_csrf_token_name() ?>": '<?= $this->security->get_csrf_hash() ?>',
						"empId": "<?= $empId; ?>",
						"timeSheetDate": "<?= $timeSheetDate; ?>"
					});
				}
			},
			"iDisplayLength": "<?= PERPAGE_DISPLAY ?>",
			"order": [[1, "ASC"]],
			"stripeClasses": [ 'alpha-slate', 'even-row' ],
			"columns": [
				{"data": "time_sheet_id"},
				{"data": "project_name"},
				{"data": "task_name"},
				{"data": "leave_reason_name"},
				{"data": "note"},
				{"data": "hours",
					"render": function (data, type, row) {
						var html = data+" Hours";
						return html;
					}
				
				},
				{
					"data": "actions",
					"render": function (data, type, row) {
						var html = '';
						var id = row['time_sheet_id'];
						var leaveReason = row['leave_reason_name'];
						var date = "<?= $getTimeSheetDetails['time_sheet_date'] ?>";
						if(role != "admin"){
							if(leaveReason == null){
								html += "<a  href='<?= site_url('TimeSheet/manage/'); ?>" + id + "/"+ date +"' data-popup='custom-tooltip' data-original-title='<?= lang('edit_time_sheet') ?>'  onclick='EditRecord(" + id + ")' title='<?= lang('edit_time_sheet') ?>' class='btn btn-outline-warning btn-sm mr-1'><i class='fa fa-pencil'></i></a>";
							}else{
								html += "<a  href='<?= site_url('TimeSheet/leaveManage/'); ?>" + id + "/"+ date +"' data-popup='custom-tooltip' data-original-title='<?= lang('edit_leave_reason') ?>'  onclick='EditRecord(" + id + ")' title='<?= lang('edit_leave_reason') ?>' class='btn btn-outline-warning btn-sm mr-1'><i class='fa fa-pencil'></i></a>";
							}
							html += "&nbsp";
							html += "<a href='javascript:void(0);' onclick='DeleteRecord(" + id + ")' data-popup='custom-tooltip' data-original-title='<?= lang('delete_time_sheet') ?>' title='<?= lang('delete_time_sheet') ?>'  class='btn btn-outline-danger btn-sm mr-1' ><i class='fa fa-trash'></i></a>";
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
						return '<label><input type="checkbox" class="dt-checkbox styled" id="ids_' + row['time_sheet_id'] + '" name="ids[]" value="' + row['time_sheet_id'] + '"/></label>';
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
		$('#timeSheetDetailsTable input[class="dt-checkbox styled"]').prop('checked', false);
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
			$('#timeSheetDetailsTable tbody tr').find('td:first :checkbox').each(function () {
				$(this).prop('checked', checkedStatus);
			});
			CheckboxKeyGen();
		});

	});

	//Delete function
	function DeleteRecord(timeSheetId) {
		$('#timeSheetDetailsTable tbody input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + timeSheetId).prop('checked', true);
		$('.deleteRecord').click();
		CheckboxKeyGen();
	}


	//Delete Record
	$(document).on('click', '.deleteRecord', function () {
		var deleteElement = $('#timeSheetDetailsTable tbody input[class="dt-checkbox styled"]:checked');
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
					url: "<?= site_url("TimeSheet/delete")?>",
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
	function EditRecord(timeSheetId) {
		$('#timeSheetDetailsTable  input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + timeSheetId).prop('checked', true);
		CheckboxKeyGen();

	}
</script>

<style type="text/css">
	#timeSheetDetailsTable_filter{
		display : none;
	}
</style>
