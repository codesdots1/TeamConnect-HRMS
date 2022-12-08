<div class="panel panel-flat">
	<div class="panel-heading">
		<h5 class="panel-title"><?= lang('holiday_calendar_heading_list') ?></h5>
		<div class="heading-elements"></div>
	</div>

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
				<div class="col-md-2" style="padding: 0 40px;">
					<button name="filter" id="filter" class="btn btn-xs border-slate-400 text-slate-400 btn-flat " style="border-radius: 8px;padding: 5px 15px;" data-popup='custom-tooltip' data-original-title="<?= lang('search_holiday') ?>" title="<?= lang('search_holiday') ?>" >Filter</button>
				</div>
				
			</div>
			<br/>
		
		<table id="holidayTable" class="table " cellspacing="0" width="100%">
			<thead>
			<tr>
				<th><input id="checkAll" type="checkbox"  class="dt-checkbox styled"></th>
				<th><?= lang('title') ?></th>
				<th><?= lang('from_date') ?></th>
				<th><?= lang('to_date') ?></th>
				<th><?= lang('holiday_type') ?></th>
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
		
		// End Search with date range
		
		dt_DataTable = $('#holidayTable').DataTable({
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
				"url": "<?= site_url('Holiday/getHolidayCalendarListing'); ?>",
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
			"order": [[1, "ASC"]],
			"stripeClasses": [ 'alpha-slate', 'even-row' ],
			"columns": [
				{"data": "holiday_calendar_id"},
				{"data": "title"},
				{"data": "holiday_from_date"},
				{"data": "holiday_to_date"},
				{"data": "holiday_type"},

			],
			"columnDefs": [
				{
					"targets": 0,
					"width": "10%",
					"render": function (data, type, row) {
						return '<label><input type="checkbox" class="dt-checkbox styled" id="ids_' + row['holiday_calendar_id'] + '" name="ids[]" value="' + row['holiday_calendar_id'] + '"/></label>';
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
		$('#holidayTable input[class="dt-checkbox styled"]').prop('checked', false);
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
			$('#holidayTable tbody tr').find('td:first :checkbox').each(function () {
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
