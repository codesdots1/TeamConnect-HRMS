<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title"><?= lang('company_heading') ?></h5>
		<div class="heading-elements">

			<a  href="<?= site_url('Company/manage'); ?>" data-popup='custom-tooltip' data-original-title="<?= lang('add_company') ?>"title="<?= lang('add_company') ?>" class="btn btn-xs border-slate-400 text-slate-400 btn-flat  btn-icon btn-rounded"><i class="icon-plus3"></i></a>
			<a type="button" data-popup='custom-tooltip' data-original-title="<?= lang('delete_company') ?>" title="<?= lang('delete_company') ?>" class="btn btn-xs border-danger-400 text-danger-400 btn-flat btn-icon btn-rounded deleteRecord"><i class="icon-trash"></i></a>

		</div>
	</div>

	<div class="table-responsive">

		<table id="companyTable" class="table " cellspacing="0" width="100%">
			<thead>
			<tr>
				<th><input id="checkAll" type="checkbox"  class="dt-checkbox styled"></th>
				<th><?= lang('company_name') ?></th>
				<th><?= lang('site_name') ?></th>
				<th><?= lang('email') ?></th>
				<th><?= lang('is_active') ?></th>
				<th><?= lang('action') ?></th>
			</tr>
			</thead>
		</table>
	</div>
</div>


<script>
	$(document).ready(function () {
		dt_DataTable = $('#companyTable').DataTable({
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
				"url": "<?= site_url('Company/getCompanyListing'); ?>",
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
				{"data": "company_id"},
				{"data": "company_name"},
				{"data": "site_name"},
				{"data": "email"},
				{"data": "is_active",
					"render": function (data, type, row) {
						var is_checked = '';
						var id = row['company_id'];
						if (data == 1) {
							is_checked = 'checked="checked"';
						}
						var html = '';
						html += '<div class="checkbox checkbox-switchery switchery-xs">';
						html += '<label>';
						html += '<input id="' + id + '" type="checkbox" class="dt_switchery isActive" ' + is_checked + ' data-status="' + data + '"  >';
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
						var id = row['company_id'];
						var empId = '<?php echo  $this->session->userdata['emp_id'] ?>';
						html += "<a  href='<?= site_url('Company/manage/'); ?>" + id + "' data-popup='custom-tooltip' data-original-title='<?= lang('edit_company') ?>'  onclick='EditRecord(" + id + ")' title='<?= lang('edit_company') ?>' class='btn btn-xs border-slate-400 text-slate-400 btn-flat btn-icon btn-rounded'><i class='icon-pencil'></i></a>";
						html += "&nbsp";
						html += "<a href='javascript:void(0);' onclick='DeleteRecord(" + id + ")' data-popup='custom-tooltip' data-original-title='<?= lang('delete_company') ?>' title='<?= lang('delete_company') ?>'  class='btn btn-xs border-danger-400 text-danger-400 btn-flat btn-icon btn-rounded' ><i class='icon-trash'></i></a>";
						html += "&nbsp";
						html += "<a href='<?= site_url('Company/getCompanyDataListing/'); ?>" + empId + "/" + id + "' data-popup='custom-tooltip' data-original-title='<?= lang('view_company') ?>' title='<?= lang('view_company') ?>' class='btn btn-xs border-slate-400 text-slate-400 btn-flat  btn-icon btn-rounded showCompany'><i class='icon-eye'></i></button>";
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
						return '<label><input type="checkbox" class="dt-checkbox styled" id="ids_' + row['company_id'] + '" name="ids[]" value="' + row['company_id'] + '"/></label>';
					},
					"sortable": false,
					"searchable": false
				}
			],
			fnDrawCallback: function (oSettings) {
				// Switchery
				// Initialize multiple switches
				CheckboxKeyGen();
				CustomToolTip();
				ScrollToTop();
				DtSwitcheryKeyGen();
			}
		});
	});

</script>

<script>

	//Delete Time Cancel button click to remove checked value
	$(document).on('click', '.cancel', function () {
		$('#companyTable input[class="dt-checkbox styled"]').prop('checked', false);
		CheckboxKeyGen();
	});

	$(document).on('click', '.isActive', function () {
		var companyId   = $(this).attr('id');
		var isActive   			  = $(this).data("status");
		$.ajax({
			type: "post",
			url: "<?= site_url("Company/changeActive")?>",
			dataType: "json",
			data: {company_id: companyId, status: isActive},
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
			$('#companyTable tbody tr').find('td:first :checkbox').each(function () {
				$(this).prop('checked', checkedStatus);
			});
			CheckboxKeyGen();
		});

	});

	//Delete function
	function DeleteRecord(companyId) {
		$('#companyTable tbody input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + companyId).prop('checked', true);
		$('.deleteRecord').click();
		CheckboxKeyGen();
	}


	//Delete Record
	$(document).on('click', '.deleteRecord', function () {
		var deleteElement = $('#companyTable tbody input[class="dt-checkbox styled"]:checked');
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
						url: "<?= site_url("Company/delete")?>",
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
	function EditRecord(companyId) {
		$('#companyTable  input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + companyId).prop('checked', true);
		//$('.editRecord').click();
		CheckboxKeyGen();

	}
</script>
