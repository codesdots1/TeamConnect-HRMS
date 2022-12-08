<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title"><?= lang('account_details_heading') ?></h5>
		<div class="heading-elements">
			<?php if($this->dt_ci_acl->checkAccess('AccountDetails|manage')) {?>
				<a  href="<?= site_url('AccountDetails/manage'); ?>" data-popup='custom-tooltip' data-original-title="<?= lang('add_account_details') ?>"
					title="<?= lang('add_account_details') ?>" class="btn btn-xs border-slate-400 text-slate-400 btn-flat  btn-icon btn-rounded">
					<i class="icon-plus3"></i></a>
			<?php }?>
			<?php if($this->dt_ci_acl->checkAccess('AccountDetails|delete')) {?>
				<a type="button" data-popup='custom-tooltip' data-original-title="<?= lang('delete_account_details') ?>"
				   title="<?= lang('delete_account_details') ?>" class="btn btn-xs border-danger-400 text-danger-400 btn-flat btn-icon btn-rounded deleteRecord">
					<i class="icon-trash"></i></a>
			<?php }?>
		</div>
	</div>

	<div class="table-responsive">
		<table id="accountDetailsTable" class="table " cellspacing="0" width="100%">
			<thead>
			<tr>
				<th><input id="checkAll" type="checkbox"  class="dt-checkbox styled"></th>
				<th><?= lang('employee') ?></th>
				<th><?= lang('bank_name') ?></th>
				<th><?= lang('holder_name') ?></th>
				<th><?= lang('action') ?></th>
			</tr>
			</thead>
		</table>
	</div>
</div>

<script>
	$(document).ready(function () {
		dt_DataTable = $('#accountDetailsTable').DataTable({
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
				"url": "<?= site_url('AccountDetails/getAccountDetailsListing'); ?>",
				"type": "post",
				"data": function (d) {
					return $.extend({}, d, {
						"<?= $this->security->get_csrf_token_name() ?>": '<?= $this->security->get_csrf_hash(); ?>'
					});
				}
			},
			"fnServerParams": function (aoData) { //send other data to server side
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
			"order": [[1, "ASC"]],
			"stripeClasses": [ 'alpha-slate', 'even-row' ],
			"columns": [
				{"data": "account_details_id"},
				{"data": "emp_name"},
				{"data": "bank_name"},
				{"data": "holder_name"},
				{
					"data": "actions",
					"render": function (data, type, row) {
						var html = '';
						var id = row['account_details_id'];
						<?php if($this->dt_ci_acl->checkAccess('AccountDetails|manage')) {?>
						html += "<a  href='<?= site_url('AccountDetails/manage/'); ?>" + id + "' data-popup='custom-tooltip' data-original-title='<?= lang('edit_account_details') ?>'  onclick='EditRecord(" + id + ")' title='<?= lang('edit_account_details') ?>' class='btn btn-xs border-slate-400 text-slate-400 btn-flat btn-icon btn-rounded'><i class='icon-pencil'></i></a>";
						<?php } ?>
						html += "&nbsp";
						<?php if($this->dt_ci_acl->checkAccess('AccountDetails|delete')) {?>
						html += "<a href='javascript:void(0);' onclick='DeleteRecord(" + id + ")' data-popup='custom-tooltip' data-original-title='<?= lang('delete_account_details') ?>' title='<?= lang('delete_account_details') ?>'  class='btn btn-xs border-danger-400 text-danger-400 btn-flat btn-icon btn-rounded' ><i class='icon-trash'></i></a>";
						<?php }?>
						html += "&nbsp";
						html += "<a href='<?= site_url('AccountDetails/getAccountDetailsDataListing/'); ?>" + id + "' data-popup='custom-tooltip' data-original-title='<?= lang('view_account_details') ?>' title='<?= lang('view_account_details') ?>' class='btn btn-xs border-slate-400 text-slate-400 btn-flat  btn-icon btn-rounded showAccountDetails'><i class='icon-eye'></i></button>";
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
						return '<label><input type="checkbox" class="dt-checkbox styled" id="ids_' + row['account_details_id'] + '" name="ids[]" value="' + row['account_details_id'] + '"/></label>';
					},
					"sortable": false,
					"searchable": false
				}
			],
			fnDrawCallback: function (oSettings) {
				CheckboxKeyGen();
				CustomToolTip();
				ScrollToTop();
			}
		});
	});

</script>

<script>
	$(document).on('click', '.cancel', function () {
		$('#accountDetailsTable input[class="dt-checkbox styled"]').prop('checked', false);
		CheckboxKeyGen();
	});

	$(document).ready(function () {

		SwitcheryKeyGen();
		CheckboxKeyGen('checkAll');
		CustomToolTip();

		$('#checkAll').click(function () {
			var checkedStatus = this.checked;
			$('#accountDetailsTable tbody tr').find('td:first :checkbox').each(function () {
				$(this).prop('checked', checkedStatus);
			});
			CheckboxKeyGen();
		});
	});

	//Delete function
	function DeleteRecord(accountDetailsId) {
		$('#accountDetailsTable tbody input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + accountDetailsId).prop('checked', true);
		$('.deleteRecord').click();
		CheckboxKeyGen();
	}

	//Delete Record
	$(document).on('click', '.deleteRecord', function () {
		var deleteElement = $('#accountDetailsTable tbody input[class="dt-checkbox styled"]:checked');
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
						url: "<?= site_url("AccountDetails/delete")?>",
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
	function EditRecord(accountDetailsId) {
		$('#accountDetailsTable  input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + accountDetailsId).prop('checked', true);
		CheckboxKeyGen();
	}
</script>
