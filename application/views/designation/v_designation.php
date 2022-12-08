<div class="quicklink-sidebar-menu ctm-border-radius shadow-sm grow bg-white card">
	<div class="card-body">
		<ul class="list-group list-group-horizontal-lg">
			<li class="list-group-item text-center active button-5"><a class="text-white"><?= lang('designation_index_heading') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('Department'); ?>"><?= lang('department_index_heading') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('Role'); ?>"><?= lang('role_index_heading') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('LeaveReason'); ?>"><?= lang('leave_index_heading') ?></a></li>
		</ul>
	</div>
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2"><b><?= lang('designation_index_heading') ?></b></h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">
			<li class="nav-item pl-3">
				<button type="button" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding deleteRecord"><i
							class="fa fa-trash"></i></button>
			</li>
			<li class="nav-item pl-3">
				<a href="<?= site_url('Designation/manage'); ?>"
				   class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><i
							class="fa fa-plus"></i> <?= lang('add_designation') ?></a>
			</li>
		</ul>
	</div>
</div>

<div class="panel panel-flat">
	<div class="table-responsive">
		<table id="designationTable" class="table " cellspacing="0" width="100%">
			<thead>
			<tr>
				<th><input id="checkAll" type="checkbox"  class="dt-checkbox styled"></th>
				<th><?= lang('designation_name') ?></th>
				<th><?= lang('description') ?></th>
				<th><?= lang('action') ?></th>
			</tr>
			</thead>
		</table>
	</div>
</div>


<script>
	$(document).ready(function () {
		dt_DataTable = $('#designationTable').DataTable({
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
				"url": "<?= site_url('Designation/getDesignationListing'); ?>",
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
				{"data": "designation_id"},
				{"data": "designation_name"},
				{"data": "description",
					"render": function (data, type, row) {
						if(data == ''){
							var html = '<?= lang('no_description_added'); ?>';
							return html;
						}else{
							return data;
						}
					}
				},
				{
					"data": "actions",
					"render": function (data, type, row) {
						var html = '';
						var id = row['designation_id'];
						html += "<a  href='<?= site_url('Designation/manage/'); ?>" + id + "' data-popup='custom-tooltip' data-original-title='<?= lang('edit_designation') ?>'  onclick='EditRecord(" + id + ")' title='<?= lang('edit_designation') ?>' class='btn btn-outline-warning btn-sm mr-1'><i class='fa fa-pencil'></i></a>";
						html += "&nbsp";
						html += "<a href='javascript:void(0);' onclick='DeleteRecord(" + id + ")' data-popup='custom-tooltip' data-original-title='<?= lang('delete_designation') ?>' title='<?= lang('delete_designation') ?>'  class='btn btn-outline-danger btn-sm mr-1' ><i class='fa fa-trash'></i></a>";
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
						return '<label><input type="checkbox" class="dt-checkbox styled" id="ids_' + row['designation_id'] + '" name="ids[]" value="' + row['designation_id'] + '"/></label>';
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
		$('#designationTable input[class="dt-checkbox styled"]').prop('checked', false);
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
			$('#designationTable tbody tr').find('td:first :checkbox').each(function () {
				$(this).prop('checked', checkedStatus);
			});
			CheckboxKeyGen();
		});

	});

	//Delete function
	function DeleteRecord(designationId) {
		$('#designationTable tbody input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + designationId	).prop('checked', true);
		$('.deleteRecord').click();
		CheckboxKeyGen();
	}


	//Delete Record
	$(document).on('click', '.deleteRecord', function () {
		var deleteElement = $('#designationTable tbody input[class="dt-checkbox styled"]:checked');
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
							url: "<?= site_url("Designation/delete")?>",
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
	function EditRecord(designationId) {
		$('#designationTable  input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + designationId).prop('checked', true);
		//$('.editRecord').click();
		CheckboxKeyGen();

	}
</script>
