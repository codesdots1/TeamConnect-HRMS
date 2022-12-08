<div class="quicklink-sidebar-menu ctm-border-radius shadow-sm grow bg-white card">
	<div class="card-body">
		<ul class="list-group list-group-horizontal-lg">
			<li class="list-group-item text-center active button-5"><a class="text-white"><?= lang('country_heading') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('State'); ?>"><?= lang('state_heading') ?></a></li>
			<li class="list-group-item text-center button-6"><a class="text-dark" href="<?= site_url('City'); ?>"><?= lang('city_heading') ?></a></li>
		</ul>
	</div>
</div>

<div class="card shadow-sm grow ctm-border-radius">
	<div class="card-body align-center">
		<h3 class="card-title float-left mb-0 mt-2">233 Country</h3>
		<ul class="nav nav-tabs float-right border-0 tab-list-emp">
			<li class="nav-item pl-3">
				<button type="button" class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding deleteRecord"><i
							class="fa fa-trash"></i></button>
			</li>
			<li class="nav-item pl-3">
				<a class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding addCountry"><i
							class="fa fa-plus"></i> <?= lang('add_country') ?></a>
			</li>

		</ul>
	</div>
</div>

<div class="panel panel-flat">
	<div class="table-responsive">
		<table id="countryTable" class="table" cellspacing="0" width="100%">
			<thead>
			<tr>
				<th><input id="checkAll" type="checkbox"  class="dt-checkbox styled"></th>
				<th><?= lang('country_name') ?></th>
				<th><?= lang('is_active') ?></th>
				<th><?= lang('action') ?></th>
			</tr>
			</thead>
		</table>
	</div>
</div>

<?= $v_countryModal; ?>

<!--Display the country list-->
<script>
    $(document).ready(function () {

        dt_DataTable = $('#countryTable').DataTable({
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
                "url": "<?= site_url('Country/getCountryListing'); ?>",
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
                {"data": "country_id"},
                {"data": "country_name"},
				{"data": "is_active",
					"render": function (data, type, row) {
						var is_checked = '';
						var id = row['country_id'];
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
						var id = row['country_id'];
						html += "<button value='"+id+"' data-popup='custom-tooltip' data-original-title='<?= lang('edit_country') ?>'  title='<?= lang('edit_country') ?>' class='btn btn-outline-warning btn-sm mr-1 editRecord'  ><i class='fa fa-pencil'></i></button>";
						html += "&nbsp";
						html += "<a href='javascript:void(0);' data-popup='custom-tooltip' onclick='DeleteRecord(" + id + ")' data-original-title='<?= lang('delete_country') ?>' title='<?= lang('delete_country') ?>'  class='btn btn-outline-danger btn-sm mr-1' ><i class='fa fa-trash'></i></a>";
						return html;
					},
					"sortable": false,
					"searchable": false
				},

            ],
			"columnDefs": [
				{
					"targets": 0,
					"render": function (data, type, row) {
						return '<label><input type="checkbox" class="dt-checkbox styled" id="ids_' + row['country_id'] + '" name="ids[]" value="' + row['country_id'] + '"/></label>';
					},
					"sortable": false,
					"searchable": false
				},
				{
					"targets": 3,
					"sortable": false,
					"searchable": false
				},
			],

            fnDrawCallback: function (oSettings) {
                // Switchery
                // Initialize multiple switches
                DtSwitcheryKeyGen();
                CheckboxKeyGen('checkAll');
                CustomToolTip();
                ScrollToTop();
            }
        });
    });

</script>
