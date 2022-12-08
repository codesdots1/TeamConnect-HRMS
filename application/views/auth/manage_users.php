<!-- Page -->


<div class="panel panel-flat">


    <div class="panel-heading">
        <h5 class="panel-title"><?= $title ?></h5>
        <div class="heading-elements">

            <a data-popup="custom-tooltip" data-original-title="Add User"  title="Add User"  class='btn btn-xs border-slate-400 text-slate-400 btn-flat  btn-icon btn-rounded addMasterRecord'
               href="<?php echo site_url('Auth/edit_user'); ?>" title="Add User"><i class="icon-plus3"></i></a>&nbsp

        </div>
    </div>
    <div class="table-responsive">
        <table id="dtUsers" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>

                <th ><span> Name</span></th>
                <th ><span> Email</span></th>
                <th>Group</th>
                <th>Status</th>
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

        dt_DataTable = $('#dtUsers').DataTable( {
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
                "url": "<?php echo site_url('auth/getUsers'); ?>",
                "type": "POST",
                "data": function ( d ) {
                    return $.extend( {}, d, {
                        "<?= $this->security->get_csrf_token_name() ?>": '<?= $this->security->get_csrf_hash() ?>'
                    } );
                }

            },
            "iDisplayLength": "<?= PERPAGE_DISPLAY ?>",
            "stripeClasses": [ 'alpha-slate', 'even-row' ],
            "order": [[ 0, "asc" ]],
            "columns": [
                {"data": "first_name" },
                {"data": "email" },
                {"data": "groups" },
                {"data": "active"},
                {"data": "actions"},
            ],
            "columnDefs":[
                {
                    "targets": 0,
                    "render": function ( data, type, row ) {
                        return row['first_name']+' '+row['last_name'];
                    },
                },
                {
                    "targets": 2, "sortable": true, "searchable": false
                },
                {
                    "targets": 3,
                    "sortable": false,
                    "searchable": false,
                    "render": function (data, type, row){
                        var checked = (data == 1)?"checked":"";
//                        return '<input type="checkbox" class="js-switchery checkbox checkbox-switchery switchery-xs userStatus" '+checked+' data-pointer="'+row['id']+'" value="1" />';
                        return '<div class="checkbox checkbox-switchery switchery-xs">' +
                            '<label>' +
                            '<input type="checkbox" class="js-switchery checkbox checkbox-switchery switchery-xs userStatus" '+checked+' data-pointer="'+row['id']+'" value="1" />' +
                            '</label>' +
                            '</div>';
                    }
                },
                {
                    "targets": 4,
                    "sortable": false,
                    "searchable": false,
                },
            ],
            "fnDrawCallback": function (oSettings, json) {
                var elems = Array.prototype.slice.call($("#dtUsers").find('.js-switchery'));
                elems.forEach(function(html) {
                    var settings =
                        {
//                            size: 'small',
//                            color: 'green'
                        color: '#455A64',

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

    $(document).on('click', '.userStatus', function () {
        var id = $(this).attr('data-pointer');
        var status = $(this).is(":checked") ? 1:0;
        $.ajax({
            type: 'post',
            url: '<?php echo base_url('auth/changeStatus/'); ?>',
            data: {id:id, status: status},
            dataType: 'json',
            success: function (data) {
                if (data) {
                    swal({
                        title: "Success",
                        text: "Status has been Changed",
                        confirmButtonColor: "#66BB6A",
                        type: "success"
                    });
                    dt_DataTable.ajax.reload();
                } else {
                    swal({
                        title: "Status Change Error.",
                        confirmButtonColor: "#2196F3"
                    });
                }
            }
        });
    });


    $(document).on('click', '.deleteMasterRecord', function () {
        var id = $(this).attr('data-pointer');
        swal({
                title: "Are you sure?",
                text: "You will not be able to recover this User or any related data!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#DD6B55',
                confirmButtonText: 'Delete',
                closeOnConfirm: false,
                //closeOnCancel: false
            },
            function() {
                $.ajax({
                    type: 'post',
                    url: '<?php echo base_url('/auth/deleteUser'); ?>',
                    data: {id:id},
                    dataType: 'json',
                    success: function (data) {
                        dt_DataTable.ajax.reload();
                    }
                });
                swal("Deleted!", "User has been deleted!", "success");
            });
    });

</script>
