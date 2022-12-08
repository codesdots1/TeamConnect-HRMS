<div class="quicklink-sidebar-menu ctm-border-radius shadow-sm grow bg-white card">
	<div class="card-body">
	<ul class="list-group list-group-horizontal-lg">
			<li class="list-group-item text-center active button-5"><a class="text-white"><?= lang('employee_heading_list') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('ShiftManagement'); ?>"><?= lang('shift_management_heading_list') ?></a></li>
		</ul>
	</div>
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2"><b><?= implode($empName); ?> People</b></h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">
			<li class="nav-item pl-3">
				<button type="button" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding deleteRecord"><i
							class="fa fa-trash"></i></button>
			</li>
			<li class="nav-item pl-3">
				<a href="<?= site_url('Employee/manage'); ?>"
				   class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><i
							class="fa fa-plus"></i> <?= lang('add_employee') ?></a>
			</li>

		</ul>
	</div>
</div>

<div class="panel panel-flat">
	<div class="table-responsive">
		<br/>
		<div class="dropdown">
			<div class="col-md-2" style="padding-left:20px;">
				Employee Type:
			</div>
			<div class="col-md-4">
				<select name="employee_filter" id="employee_filter" class="form-control dropdown" data-placeholder="Select <?= lang('employee_filter') ?> ">
					<option value=""></option>
				</select>
			</div>
			<div class="col-md-2 float-left">
				<button name="filter" id="filter" style="border-radius: 8px;padding: 5px 15px;" class="btn btn-outline-success">Filter</button>
			</div>
		</div>
		<br/>
		<table id="employeeTable" class="table table-hover" cellspacing="0" width="100%">
			<thead>
				<tr>
					<th><input id="checkAll" type="checkbox"  class="dt-checkbox styled"></th>
					<th><?= lang('image') ?></th>
					<th><?= lang('employee_code') ?></th>
					<th><?= lang('employee_name') ?></th>
					<th><?= lang('role') ?></th>
					<th><?= lang('status') ?></th>
					<th><?= lang('action') ?></th>
				</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {

		employeeTypeFilterDD('', '#employee_filter');

		dt_DataTable = $('#employeeTable').DataTable({
			dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
			language: {
				search: '<span>Filter:</span> _INPUT_',
				searchPlaceholder: 'Type to filter',
				lengthMenu: '<span>Show:</span> _MENU_',
				paginate: {'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;'}
			},
			"processing": true,
			"serverSide": true,
			"ajax": {
				"url": "<?= site_url('Employee/getEmployeeListing'); ?>",
				"type": "post",
				"data": function (d) {
					var params = {};
					params["<?= $this->security->get_csrf_token_name(); ?>"] = "<?= $this->security->get_csrf_hash() ?>";
					params["empId"] = "<?= $this->session->userdata['emp_id']; ?>";
					params["empStatus"] = $('#employee_filter :selected').val();
					return $.extend({}, d, params);
				}
			},
			"fnServerParams": function (aoData) {
				var params = {};
				var filterData = $("#advanceFilter").serializeArray();
				$.each(filterData, function (i, val) {
					var name = val.name;
					if (typeof params[name] == 'undefined') {
						params[name] = [];
					}
					params[name].push(val.value);
				});
				aoData.filterParams = params;
				server_params = aoData;
			},
			"iDisplayLength": "<?= PERPAGE_DISPLAY ?>",
			"order": [[3, "ASC"]],
			"stripeClasses": ['alpha-slate', 'even-row'],
			"columns": [
				{"data": "emp_id"},
				{
					"data": "employee_image",
					"render": function (data) {
						return '<img alt="avatar image" src="' + data + '" class="img-fluid avatar"/>';
					}
				},
				{"data": "employee_code"},
				//{"data": "emp_name"},
				{"data": "first_name"},
				{"data": "role"},
				{"data": "status",
					"render": function (data, type, row) {
						var is_checked = '';
						var id = row['emp_id'];
						if (data == 1) {
							is_checked = 'checked="checked"';
						}
						var html = '';
						html += '<div class="checkbox checkbox-switchery switchery-xs">';
						html += '<label class="switch">';
						html += '<input id="' + id + '" type="checkbox" class=" isActive" ' + is_checked + ' data-status="' + data + '" ><span class="slider round"></span>';
						html += '</label>';
						html += '</div>';
						return html;
					},
					"sortable": false,
					"searchable": false
				},
				{
					"data": "actions",
					"render": function (data, type, row) {
						var html = '';
						var id = row['emp_id'];
						html += "<a  href='<?= site_url('Employee/manage/'); ?>" + id + "' class='btn btn-outline-warning btn-sm mr-1' data-popup='custom-tooltip' data-original-title='<?= lang('edit_employee') ?>' title='<?= lang('edit_employee') ?>''><i class='fa fa-pencil'></i></a>";
						html += "&nbsp";
						html += "<a href='javascript:void(0);' onclick='DeleteRecord(" + id + ")' data-popup='custom-tooltip' data-original-title='<?= lang('delete_employee') ?>' title='<?= lang('delete_employee') ?>'  class='btn btn-outline-danger btn-sm mr-1' ><i class='fa fa-trash'></i></a>";
						html += "&nbsp";
						html += "<a href='<?= site_url('Employee/getEmployeeDataListing/'); ?>" + id + "' class='btn btn-outline-success btn-sm showEmployee' data-popup='custom-tooltip' data-original-title='<?= lang('view_employee') ?>' title='<?= lang('view_employee') ?>'><i class='fa fa-eye'></i></button>";
						return html;
					},
					"sortable": false,
					"searchable": false
				}
			],
			"columnDefs": [
				{
					"targets": 0,
					"width": "10%",
					"render": function (data, type, row) {
						return '<label><input type="checkbox" class="dt-checkbox styled" id="ids_' + row['emp_id'] + '" name="ids[]" value="' + row['emp_id'] + '"/></label>';
					},
					"sortable": false,
					"searchable": false
				},
				{
					"targets": 1,
					"sortable": false,
					"searchable": false
				}
			],
			fnDrawCallback: function (oSettings) {
				DtSwitcheryKeyGen();
				CheckboxKeyGen();
				CustomToolTip();
				ScrollToTop();
			}
		});
	});
</script>

<script>
	$(document).on('click', '.cancel', function () {
		$('#employeeTable input[class="dt-checkbox styled"]').prop('checked', false);
		CheckboxKeyGen();
		DtSwitcheryKeyGen();
	});

	$(document).ready(function () {
		CheckboxKeyGen('checkAll');
		CustomToolTip();
		$('#checkAll').click(function () {
			var checkedStatus = this.checked;
			$('#employeeTable tbody tr').find('td:first :checkbox').each(function () {
				$(this).prop('checked', checkedStatus);
			});
			CheckboxKeyGen();
		});
	});

	function DeleteRecord(empId) {
		$('#employeeTable tbody input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + empId).prop('checked', true);
		$('.deleteRecord').click();
		CheckboxKeyGen();
	}

	$(document).on('click', '.deleteRecord', function () {
		var deleteElement = $('#employeeTable tbody input[class="dt-checkbox styled"]:checked');
		var selectedLength = deleteElement.size();
		if (selectedLength == 0) {
			swal({
				title: "<?= ucwords(lang('info')); ?>",
				text: "<?= lang('delete_info'); ?>",
				confirmButtonColor: "<?= BTN_DELETE_INFO; ?>",
				type: "<?= lang('info'); ?>"
			}, function () {
				return false;
			});
		} else {
			var deleteId = [];
			$.each(deleteElement, function (i, ele) {
				deleteId.push($(ele).val());
			});

			swal({
				title: "<?= ucwords(lang('delete')); ?>", text: "<?= lang('delete_warning'); ?>",
				type: "<?= lang('warning'); ?>",
				showCancelButton: true,
				closeOnConfirm: false,
				confirmButtonColor: "<?= BTN_DELETE_WARNING; ?>",
				showLoaderOnConfirm: true
			},
			function () {
				$.ajax({
					type: "post",
					url: "<?= site_url("Employee/delete")?>",
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

</script>
<script>
	$(document).ready(function () {
		$('#filter').click(function () {
			dt_DataTable.ajax.reload();
		});
	});
</script>
<script>
	$(document).on('click', '.cancel', function () {
		$('#employeeTable input[class="dt-checkbox styled"]').prop('checked', false);
		CheckboxKeyGen();
	});
	$(document).ready(function () {
		SwitcheryKeyGen();
		CheckboxKeyGen('checkAll');
		CustomToolTip();
		$('#checkAll').click(function () {
			var checkedStatus = this.checked;
			$('#employeeTable     tbody tr').find('td:first :checkbox').each(function () {
				$(this).prop('checked', checkedStatus);
			});
			CheckboxKeyGen();
		});

	});
</script>
<?php if (isset($select2)) { ?>
	<?= $select2 ?>
<?php } ?>

<script type="text/javascript" src="<?= $assets ?>/js/core/libraries/jquery_ui/widgets.min.js"></script>
<style type="text/css">
	.datatable-header .dataTables_filter {
		width: auto;
		margin: 0 0 25px 20px;
	}

	.dataTables_filter input {
		width: 230px;
		padding: 0 15px;
		margin-left: 50px;
	}

	.dataTables_filter > label::after {
		content: none;
	}
</style>


