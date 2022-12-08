<div class="panel panel-flat">


    <div class="panel-heading">
        <h5 class="panel-title"><?= $title ?></h5>
        <div class="heading-elements">

            <a data-popup="custom-tooltip" data-original-title="Add Group"  title="Add Group"     class='btn btn-xs border-slate-400 text-slate-400 btn-flat  btn-icon btn-rounded  addMasterRecord'
               href="<?php echo site_url('Auth/edit_group'); ?>" ><i class="icon-plus3"></i></a>&nbsp

        </div>
    </div>
    <div class="table-responsive">
        <table id="dtGroups" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>

                <th ><span> Group Name</span></th>
                <th ><span> Group Description</span></th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="5" class="dataTables_empty">Loading! Please wait..</td>
            </tr>
            </tbody>
        </table>
    </div>


</div>




<script>
    var dt_DataTable;
    $(document).ready(function () {

        dt_DataTable = $('#dtGroups').DataTable( {
            dom: '<"datatable-header"fl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
            language: {
                search: '<span>Filter:</span> _INPUT_',
                searchPlaceholder: 'Type to filter...',
                lengthMenu: '<span>Show:</span> _MENU_',
                paginate: { 'first': 'First', 'last': 'Last', 'next': '&rarr;', 'previous': '&larr;' }
            },
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('auth/getGroups'); ?>",
                "type": "POST",
                "data": function ( d ) {
                    return $.extend( {}, d, {
                        "<?= $this->security->get_csrf_token_name() ?>": '<?= $this->security->get_csrf_hash() ?>'
                    } );
                }

            },
            "order": [[ 0, "asc" ]],
            "columns": [
                {"data": "name" },
                {"data": "description" },

                {"data": "actions"},
            ],
            "columnDefs":[
                { "targets": 0,},
                {
                    "targets": 2, "sortable": true, "searchable": false
                },

            ],
            "fnDrawCallback": function (oSettings, json) {
                var elems = Array.prototype.slice.call($("#dtUsers").find('.js-switchery'));
                elems.forEach(function(html) {
                    var settings =
                        {
                            size: 'small',
                            color: 'green'

                        };
                    if($(html).attr("data-switchery") != "true"){
                        var switchery = Switchery(html, settings);
                    }
                });
                CustomToolTip();
                ScrollToTop();

            }
        } );
    });





</script>