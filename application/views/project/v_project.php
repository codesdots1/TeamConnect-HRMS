<div class="quicklink-sidebar-menu ctm-border-radius shadow-sm grow bg-white card">
	<div class="card-body">
		<ul class="list-group list-group-horizontal-lg">
			<li class="list-group-item text-center active button-5"><a class="text-white"><?= lang('project_heading_list') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('Task'); ?>"><?= lang('task_heading_list') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('EmployeeShift'); ?>"><?= lang('employee_shift_heading_list') ?></a></li>
		</ul>
	</div>
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2"><b><?= lang('project_heading_list') ?></b></h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">
			<li class="nav-item pl-3">
				<button type="button" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding deleteRecord"><i
							class="fa fa-trash"></i></button>
			</li>
			<li class="nav-item pl-3">
				<a href="<?= site_url('Project/manage'); ?>"
				   class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><i
							class="fa fa-plus"></i> <?= lang('add_project') ?></a>
			</li>

		</ul>
	</div>
</div>

<div class="panel panel-flat">
	<div class="table-responsive">
		<table id="projectTable" class="table " cellspacing="0" width="100%">
			<thead>
			<tr>
				<th><input id="checkAll" type="checkbox"  class="dt-checkbox styled"></th>
				<th><?= lang('project_name') ?></th>
				<th><?= lang('description') ?></th>
				<th><?= lang('is_active') ?></th>
				<th><?= lang('action') ?></th>
			</tr>
			</thead>
		</table>
	</div>
</div>


<script>
	$(document).ready(function () {
		dt_DataTable = $('#projectTable').DataTable({
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
				"url": "<?= site_url('Project/getProjectListing'); ?>",
				"type": "post",
				"data": function (d) {
					return $.extend({}, d, {
						"<?= $this->security->get_csrf_token_name() ?>": '<?= $this->security->get_csrf_hash() ?>'
					});
				}
			},
			"iDisplayLength": "<?= PERPAGE_DISPLAY ?>",
			"order": [[1, "ASC"]],
			"stripeClasses": [ 'alpha-slate', 'even-row' ],
			"columns": [
				{"data": "project_id"},
				{"data": "project_name"},
				{"data": "description",
					"render": function (data, type, row) {
						if(data == ''){
							var html = '<?= lang('no_description_added'); ?>';
							return html;
						}else{
							return data;
						}

					},
					"sortable": false
				},
				{"data": "is_active",
					"render": function (data, type, row) {
						var id = row['project_id'];
						var html = '';
						if (data == 1) {
							html = '<a href="#" class="btn btn-warning btn-lg disabled" role="button" aria-disabled="true">In Progress</a>';
						}else{
							html = '<a href="#" class="btn btn-dark btn-lg disabled" role="button" aria-disabled="true">Not Started Yet</a>';
						}
						return html;
					},

				},
				{
					"data": "actions",
					"render": function (data, type, row) {
						var html = '';
						var id = row['project_id'];
						html += "<a  href='<?= site_url('Project/manage/'); ?>" + id + "' data-popup='custom-tooltip' data-original-title='<?= lang('edit_project') ?>'  onclick='EditRecord(" + id + ")' title='<?= lang('edit_project') ?>' class='btn btn-outline-warning btn-sm mr-1'><i class='fa fa-pencil'></i></a>";
						html += "&nbsp";
						html += "<a href='javascript:void(0);' onclick='DeleteRecord(" + id + ")' data-popup='custom-tooltip' data-original-title='<?= lang('delete_project') ?>' title='<?= lang('delete_project') ?>'  class='btn btn-outline-danger btn-sm mr-1' ><i class='fa fa-trash'></i></a>";
						html += "&nbsp";
						html += "<a href='<?= site_url('Project/getProjectDataListing/'); ?>" + id + "' data-popup='custom-tooltip' data-original-title='<?= lang('view_project') ?>' title='<?= lang('view_project') ?>' class='btn btn-outline-success btn-sm showProject'><i class='fa fa-eye'></i></button>";
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
						return '<label><input type="checkbox" class="dt-checkbox styled" id="ids_' + row['project_id'] + '" name="ids[]" value="' + row['project_id'] + '"/></label>';
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
		$('#projectTable input[class="dt-checkbox styled"]').prop('checked', false);
		CheckboxKeyGen();
	});
	$(document).on('click', '.isActive', function () {
		var projectId   = $(this).attr('id');
		var isActive   = $(this).data("status");
		$.ajax({
			type: "post",
			url: "<?= site_url("Project/changeActive")?>",
			dataType: "json",
			data: {project_id: projectId, status: isActive},
			success: function (data) {
				if (data) {
					swal({
						title: "<?= ucwords(lang('success')); ?>",
						text: data['msg'],
						confirmButtonColor: "<?= BTN_SUCCESS; ?>",
						type: "<?= lang('success'); ?>"
					},function(){
						dt_DataTable.ajax.reload(null,false);
					});
				} else {
					swal({
						title: "<?= ucwords(lang('error')); ?>",
						text: data['msg'],
						type: "<?= lang('error'); ?>",
						confirmButtonColor: "<?= BTN_ERROR; ?>"
					});
				}
			}
		});
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
			$('#projectTable tbody tr').find('td:first :checkbox').each(function () {
				$(this).prop('checked', checkedStatus);
			});
			CheckboxKeyGen();
		});

	});

	//Delete function
	function DeleteRecord(projectId) {
		$('#projectTable tbody input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + projectId).prop('checked', true);
		$('.deleteRecord').click();
		CheckboxKeyGen();
	}


	//Delete Record
	$(document).on('click', '.deleteRecord', function () {
		var deleteElement = $('#projectTable tbody input[class="dt-checkbox styled"]:checked');
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
						url: "<?= site_url("Project/delete")?>",
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
	function EditRecord(projectId) {
		$('#projectTable  input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + projectId).prop('checked', true);
		//$('.editRecord').click();
		CheckboxKeyGen();

	}
</script>
