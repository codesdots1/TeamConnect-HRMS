
<?php
if(isset($filterModel) && $filterModel != '') {
    echo $filterModel;
}
?>

<div class="panel panel-flat">
    <div class="panel-heading">
        <h5 class="panel-title"><?= lang('activity_log_heading') ?></h5>
        <div class="heading-elements">
            <a href="javascript:void(0)" title="<?= lang('export_activity_log') ?>" class="btn btn-xs border-slate-400 text-slate-400 btn-flat  btn-icon btn-rounded"
               data-popup='custom-tooltip' onclick="dataExport()" data-original-title="<?= lang('export_activity_log') ?>"><i class="icon-file-excel"></i></a>

            <a type="button"  data-original-title="<?= lang('delete_activity_log') ?>"
               data-popup='custom-tooltip' title="<?= lang('delete_activity_log') ?>"
                class="btn btn-xs border-danger-400 text-danger-400 btn-flat btn-icon btn-rounded deleteRecord"><i class="icon-trash"></i></a>
        </div>
    </div>

    <div class="table-responsive">

        <table id="activityLogTable" class="table " cellspacing="0" width="100%">
            <input type="hidden" id="allowFilter" value="<?= (SEARCH_FILTER_EXPORT == 1) ? 1 : 0 ?>">
            <thead>
            <tr>
                <th><input id="checkAll" type="checkbox"  class="dt-checkbox styled"></th>
                <th><?= lang('description') ?></th>
                <th><?= lang('module') ?></th>
                <th><?= lang('user_name') ?></th>
                <th><?= lang('user_browser') ?></th>
                <th><?= lang('user_platform') ?></th>
                <th><?= lang('user_ip') ?></th>
                <th><?= lang('date') ?></th>
            </tr>
            </thead>
        </table>
    </div>
</div>


<script>
    $(document).ready(function () {
        dt_DataTable = $('#activityLogTable').DataTable({
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
                "url": "<?= site_url('ActivityLog/getActivityLogListing'); ?>",
                "type": "post",
                "data": function (d) {
                    return $.extend({}, d, {
                        "<?= $this->security->get_csrf_token_name() ?>": '<?= $this->security->get_csrf_hash() ?>'
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
            "order": [[ 0, 'desc' ]],
            "stripeClasses": [ 'alpha-slate', 'even-row' ],
            "columns": [
                {
                    "data": "activity_log_id",
                    "width": "10%",
                    "render": function (data, type, row) {
                        return '<label><input type="checkbox" class="dt-checkbox styled" id="ids_' + row['activity_log_id'] + '" name="ids[]" value="' + row['activity_log_id'] + '"/></label>';
                    },
                    "sortable": false,
                    "searchable": false
                },
                {"data": "description"},
                {"data": "module"},
                {"data": "username"},
                {"data": "user_browser"},
                {"data": "user_platform"},
                {"data": "user_ip"},
                {"data": "date"}
            ],
            fnDrawCallback: function (oSettings) {
                // Switchery
                // Initialize multiple switches
                CheckboxKeyGen('checkAll');
                CustomToolTip();
                ScrollToTop();
            }
        });
        Select2Init();
    });

    function dataExport() {
        var table = $("#activityLogTable").dataTable();

        var allowFilter = $('#allowFilter').val();

        if(allowFilter == 1)
        {
            var data = table.fnServerParams;
            var postData = server_params;


            $.ajax({
                type: "post",
                url: "<?= site_url("ActivityLog/exportActivityLog")?>",
                dataType: "html",
                data : { data : postData },
                success:function(download_url_from_server){
                    document.location = download_url_from_server;
                }});
        }
        else
        {
            table.fnFilter('');
            var data = table.fnServerParams;
            var postData = server_params;


            $.ajax({
                type: "post",
                url: "<?= site_url("ActivityLog/exportActivityLog")?>",
                dataType: "html",
                data : { data : postData },
                success:function(download_url_from_server){
                    document.location = download_url_from_server;
                }});

        }
    }

</script>

<script>

    //Delete Time Cancel button click to remove checked value
    $(document).on('click', '.cancel', function () {
        $('#activityLogTable input[class="dt-checkbox styled"]').prop('checked', false);
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
            $('#activityLogTable tbody tr').find('td:first :checkbox').each(function () {
                $(this).prop('checked', checkedStatus);
            });
            CheckboxKeyGen();
        });

        $('#date').val('');

        $("#staff_id").select2({
            containerCssClass: 'border-primary',
            ajax: {
                url: "<?= site_url('Auth/getUsersDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '', // search term
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data.result,
                        pagination: {
                            more: (data.page * 10) < data.totalRows
                        }
                    };
                }
            },

            escapeMarkup: function (markup) {
                return markup;
            }

        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });

    });

    //Delete function
    function DeleteRecord(activityLogId) {
        $('#activityLogTable tbody input[class="dt-checkbox styled"]').prop('checked', false);
        $('#ids_' + activityLogId).prop('checked', true);
        $('.deleteRecord').click();
        CheckboxKeyGen();
    }

    //Delete Record
    $(document).on('click', '.deleteRecord', function () {
        var deleteElement = $('#activityLogTable tbody input[class="dt-checkbox styled"]:checked');
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
                        url: "<?= base_url("ActivityLog/delete")?>",
                        dataType: "json",
                        data: {deleteId: deleteId},
                        success: function (data) {
                            if (data['success']) {
                                swal({
                                    title: "<?= ucwords(lang('success'))?>",
                                    text: data['msg'],
                                    type: "<?= lang('success')?>",
                                    confirmButtonColor: "<?= BTN_SUCCESS; ?>",
                                },function () {
                                    dt_DataTable.ajax.reload();
                                    $('#checkAll').prop('checked', false);
                                    CheckboxKeyGen();
                                });
                            } else {
                                swal({
                                    title: "<?= ucwords(lang('error')); ?>",
                                    text: data['msg'],
                                    type: "<?= lang('error'); ?>",
                                    confirmButtonColor: "<?= BTN_ERROR; ?>"
                                },function (){
                                    dt_DataTable.ajax.reload();
                                });
                            }
                        }
                    });
                });
        }
    });
</script>