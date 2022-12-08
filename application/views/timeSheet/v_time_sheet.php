<div class="quicklink-sidebar-menu ctm-border-radius shadow-sm grow bg-white card">
	<div class="card-body">
		<ul class="list-group list-group-horizontal-lg">
			<li class="list-group-item text-center active button-5"><a class="text-white"><?= lang('time_sheet_heading_list') ?></a></li>
		</ul>
	</div>
</div>

<?php if(strtolower($this->session->userdata['role']) == 'admin'){ ?>
	<div class="card shadow-sm grow ctm-border-radius">
		<div class="card-body align-center">
			<h3 class="card-title float-left mb-0 mt-2"><?= implode($empName);?> People</h3>
		</div>
	</div>
<?php } ?>


<?php if(strtolower($this->session->userdata['role']) != 'admin'){ ?>
<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-body align-center">
		<h4 class="card-title float-left mb-0 mt-2"><?= lang('time_sheet_heading_list') ?></h4>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">
			<?php
			if(isset($timeDetails['fill_date'])){
				$date = $timeDetails['fill_date'];
			} else{
				$date = date(PHP_DATE_FORMATE);
			}

			?>
			<li class="nav-item pl-3">
				<button type="button" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding deleteRecord"><i
							class="fa fa-trash"></i></button>
			</li>
			<li class="nav-item pl-3">
				<a href="<?= site_url('TimeSheet/manage/').$this->session->userdata('emp_id').'/'.$date; ?>"
				   class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding"><i
							class="fa fa-plus"></i> <?= lang('add_time_sheet') ?></a>
			</li>

		</ul>
	</div>
</div>
<?php } ?>

<div class="panel panel-flat">
	<div class="table-responsive">
		<?php  
		if((isset($timeDetails['fill_minutes']) || isset($timeDetails['fill_hours']) ) && (isset($timeDetails['fill_date']))){
			echo '<div><marquee behavior="alternate" direction="left" class="time-info-rotate" scrolldelay = "50" style="margin-top:10px; color: tomato;">
			Your Time Sheet of Date '.@$timeDetails['fill_date'].' of '.ltrim(@$timeDetails['fill_hours'], '0').' '.ltrim(@$timeDetails['fill_minutes'], '0').' has been left. Please Fill that First</marquee></div>';
		} elseif ((isset($timeDetails['fill_minutes']) || isset($timeDetails['fill_hours']) ) && (isset($timeDetails['fill_date']))){
			echo '<div><marquee behavior="alternate" direction="left" class="time-info-rotate" scrolldelay = "50" style="margin-bottom:5px">
			Your Time Sheet of Date '.@$timeDetails['fill_date'].' of '.ltrim(@$timeDetails['fill_hours'], '0').' '.ltrim(@$timeDetails['fill_minutes'], '0').' has been left. Please Fill that First</marquee></div>';
		}
		?>
		<table id="timeSheetTable" class="table " cellspacing="0" width="100%">
			<thead>
			<tr>
				<th><input id="checkAll" type="checkbox"  class="dt-checkbox styled"></th>
				<th><?= lang('employee_name') ?></th>
				<th><?= lang('total_hours') ?></th>
				<th><?= lang('time_sheet_date') ?></th>
				<th><?= lang('action') ?></th>
			</tr>
			</thead>
		</table>
	</div>
</div>

<script>
	$(document).ready(function () {
		dt_DataTable = $('#timeSheetTable').DataTable({
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
				"url": "<?= site_url('TimeSheet/getTimeSheetListing'); ?>",
				"type": "post",
				"data": function (d) {
					var params = {};
					params["<?= $this->security->get_csrf_token_name(); ?>"] = "<?= $this->security->get_csrf_hash() ?>";
					params["empId"] = "<?= $this->session->userdata['emp_id']; ?>";
					return $.extend({}, d,params);
				}
			},
			"iDisplayLength": "<?= PERPAGE_DISPLAY ?>",
			"order": [[3, "DESC"]],
			"stripeClasses": [ 'alpha-slate', 'even-row' ],
			"columns": [
				{"data": "time_sheet_id"},
				{"data": "emp_name"},
				{"data": "hours",
					"render": function (data, type, row) {
						var html = data+" Hours";
						return html;
					}
				},
				{"data": "time_sheet_date"},
				//{"data": "week_days_name"},
				{
					"data": "actions",
					"render": function (data, type, row) {
						var html = '';
						var id = row['time_sheet_id'];
						var empId = row['emp_id'];
						var date = row['time_sheet_date'];
						var role = '<?php echo  $this->session->userdata['role'] ?>';
						if(role != "admin"){
							html += "<a href='javascript:void(0);' onclick='DeleteRecord(" + id + ")' data-popup='custom-tooltip' data-original-title='<?= lang('delete_time_sheet') ?>' title='<?= lang('delete_time_sheet') ?>'  class='btn btn-outline-danger btn-sm mr-1' ><i class='fa fa-trash'></i></a>";
						}
						html += "<a href='<?= site_url('TimeSheet/getTimeSheetDetails/'); ?>" + empId +"/"+ date+"' data-popup='custom-tooltip' data-original-title='<?= lang('view_time_sheet') ?>' title='<?= lang('view_time_sheet') ?>' class='btn btn-outline-success btn-sm showEmployee'><i class='fa fa-eye'></i></button>";
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
		$('#timeSheetTable input[class="dt-checkbox styled"]').prop('checked', false);
		CheckboxKeyGen();
	});

	$(document).ready(function () {
		// Switchery
		// Initialize multiple switches
		SwitcheryKeyGen();
		CheckboxKeyGen('checkAll');
		CustomToolTip();


		$('#checkAll').click(function () {
			var checkedStatus = this.checked;
			$('#timeSheetTable tbody tr').find('td:first :checkbox').each(function () {
				$(this).prop('checked', checkedStatus);
			});
			CheckboxKeyGen();
		});

	});

	//Delete function
	function DeleteRecord(timeSheetId) {
		$('#timeSheetTable tbody input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + timeSheetId).prop('checked', true);
		$('.deleteRecord').click();
		CheckboxKeyGen();
	}


	//Delete Record
	$(document).on('click', '.deleteRecord', function () {
		var deleteElement = $('#timeSheetTable tbody input[class="dt-checkbox styled"]:checked');
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
		$('#timeSheetTable  input[class="dt-checkbox styled"]').prop('checked', false);
		$('#ids_' + timeSheetId).prop('checked', true);
		CheckboxKeyGen();
	}
</script>
