<div class="quicklink-sidebar-menu ctm-border-radius shadow-sm grow bg-white card">
	<div class="card-body">
		<ul class="list-group list-group-horizontal-lg">
			<li class="list-group-item text-center active button-5"><a class="text-white"><?= lang('employee_attendance_heading_list') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('EmployeeAttendance/attendanceCorrection'); ?>"><?= lang('employee_attendance_correction') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('EmployeeEntryLog'); ?>"><?= lang('employee_entry_heading_list') ?></a></li>
		</ul>
	</div>
	<div class="heading-elements">
			<a type="button" data-popup='custom-tooltip' data-original-title="<?= lang('delete_employee_entry') ?>" title="<?= lang('delete_employee_entry') ?>"
			   class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding deleteRecord"><i class="fa fa-trash"></i></a>
		</div>
</div>

<div class="panel panel-flat">
	<div class="table-responsive">
		<br/>
		<div class="row">
			<div class="input-daterange">
				
				<div class="col-md-2" style="padding-left:20px;">
					Date Range:
				</div>
				<div class="col-md-4">
					<input type="text" name="start_date" id="start_date" class="form-control" placeholder="Select Start Date" />
				</div>
				
				<div class="col-md-4" >
					<input type="text" name="end_date" id="end_date" class="form-control" placeholder="Select End Date" />
					
				</div>
				<div class="col-md-2 float-left">
				<button name="filter" id="filter" style="border-radius: 8px;padding: 5px 15px;" class="btn btn-outline-success">Filter</button>
			</div>
				
			</div>
			<br/>
		
			<table id="employeeEntryTable" class="table " cellspacing="0" width="100%">
			<thead>
			<tr>
				<th><input id="checkAll" type="checkbox"  class="dt-checkbox styled"></th>
				<th><?= lang('employee') ?></th>
				<th><?= lang('login_time') ?></th>
				<th><?= lang('logout_time') ?></th>
				<th><?= lang('login_date') ?></th>
				<th><?= lang('action') ?></th>
			</tr>
			</thead>
		</table>
		</div>
	</div>
</div>


<script>
	$(document).ready(function () {

		//Search with date range
		$("#start_date").datepicker({
			dateFormat: 'dd-mm-yy',
			todayBtn:  "linked",
			autoclose: true,
			todayHighlight: true,
			onSelect: function(selected) {

				$("#end_date").datepicker("option", "minDate", selected)
			}
		});
		$("#end_date").datepicker({
			dateFormat: 'dd-mm-yy',
			todayBtn:  "linked",
			autoclose: true,
			todayHighlight: true,
			minDate: $("#start_date").val(),
		});

		$("#filter").click(function(){
			var startDate = $("#start_date").val();
			var endDate = $("#end_date").val();
			if(startDate == "" && endDate == ""){
				swal({
					title: "<?= ucwords(lang('info')); ?>",
					text: "<?= lang('search_info'); ?>",
					confirmButtonColor: "<?= BTN_DELETE_INFO; ?>",
					type: "<?= lang('info'); ?>"
				},function(){
					return false;
				});
			}else{
				dt_DataTable.ajax.reload();
			}

		});

		dt_DataTable = $('#employeeEntryTable').DataTable({
			dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
			language: {
				search: '<span>Filter:</span> _INPUT_',
				searchPlaceholder: 'Type to filter...',
				lengthMenu: '<span>Show:</span> _MENU_',
				paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
			},
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?= site_url('EmployeeEntryLog/getEmployeeEntryLogListing'); ?>",
				"type": "post",
				"data": function (d) {
					return $.extend({}, d, {
						"<?= $this->security->get_csrf_token_name() ?>": '<?= $this->security->get_csrf_hash() ?>',
						"startDate": $("#start_date").val(),
						"endDate": $("#end_date").val()
					});
				}
			},
			"iDisplayLength": "<?= PERPAGE_DISPLAY ?>",
			"order": [[4, "DESC"]],
			"stripeClasses": [ 'alpha-slate', 'even-row' ],
			"columns": [
				{"data": "emp_id"},
				{"data": "emp_name"},
				{"data": "login_time"},
				{"data": "logout_time",
					"render": function (data, type, row) {
						var outTime = row['out_time'];
						var date = row['login_date'];
						var html = '';
						var fullDate  = new Date();
						var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) :(fullDate.getMonth()+1);
						var currentDate = ('0' + (fullDate.getDate())).slice(-2) + "-" + ('0' + twoDigitMonth).slice(-2) + "-" + fullDate.getFullYear();
						
						if(outTime == "00:00:00" && date == currentDate)
							var html = 'Online';
						else if(outTime == "00:00:00" && date != currentDate )
							html = outTime;
						else
							html = data;
						
						return html;
					}		
				},
				{"data": "login_date"},
				{
					"data": "actions",
					"render": function (data, type, row) {
						var html = '';
						var id = row['emp_log_id'];
						html += "<a href='javascript:void(0);' onclick='DeleteRecord(" + id + ")' data-popup='custom-tooltip' data-original-title='<?= lang('delete_employee_entry') ?>' title='<?= lang('delete_employee_entry') ?>'  class='btn btn-outline-danger btn-sm mr-1' ><i class='fa fa-trash'></i></a>";
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
						return '<label><input type="checkbox" class="dt-checkbox styled" id="ids_' + row['emp_log_id'] + '" name="ids[]" value="' + row['emp_log_id'] + '"/></label>';
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
		$('#employeeEntryTable input[class="dt-checkbox styled"]').prop('checked', false);
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
			$('#employeeEntryTable tbody tr').find('td:first :checkbox').each(function () {
				$(this).prop('checked', checkedStatus);
			});
			CheckboxKeyGen();
		});

	});

	//Delete function
	function DeleteRecord(employeeEntryId) {
		$('#employeeEntryTable tbody input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + employeeEntryId	).prop('checked', true);
		$('.deleteRecord').click();
		CheckboxKeyGen();
	}


	//Delete Record
	$(document).on('click', '.deleteRecord', function () {
		var deleteElement = $('#employeeEntryTable tbody input[class="dt-checkbox styled"]:checked');
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
						url: "<?= site_url("EmployeeEntryLog/delete")?>",
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
</script>


	<script>

		//Delete Time Cancel button click to remove checked value
		$(document).on('click', '.cancel', function () {
			$('#employeeEntryTable input[class="dt-checkbox styled"]').prop('checked', false);
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
				$('#employeeEntryTable     tbody tr').find('td:first :checkbox').each(function () {
					$(this).prop('checked', checkedStatus);
				});
				CheckboxKeyGen();
			});

		});
	</script>
	<script type="text/javascript" src="<?= $assets ?>/js/core/libraries/jquery_ui/widgets.min.js"></script>
	<style type="text/css">
		.datatable-header .dataTables_filter{
			width: auto;
			margin: 0 0 25px 20px;
		}
		.dataTables_filter input {
			width: 230px;
			padding : 0 15px;
			margin-left: 50px;
		}
		.dataTables_filter > label::after {
			content : none;
		}
	</style>

