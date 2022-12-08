<div class="accordion add-employee" id="accordion-details"> 
    <div class="card shadow-sm grow ctm-border-radius">
        <div class="card-header" id="headingTwo">
            <h4 class="cursor-pointer mb-0">
                <a class="coll-arrow d-block text-dark" href="javascript:void(0)" data-toggle="collapse" data-target="#Personal-Information">
                    Manage Permissions
                </a>
            </h4>
        </div>
        <div class="card-body p-0">
            <div id="Personal-Information" class="collapse show ctm-padding" aria-labelledby="basic1" data-parent="#accordion-details">
                <form name="recordlist" id="permissions"  method="post" action="">
                    <fieldset class="content-group">
                        <div class="datatable-scroll">
                            <table class="table datatable-basic admin-purmissions-list datatable">
                                <thead>
                                    <tr>
                                        <th style="width:20px;">
                                            <input type="checkbox" class="styled" id="SellectAll">
                                        </th>
                                        <th>All</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($all_admin_purmissions as $value): ?>
                                        <tr>
                                            <td><input  type="checkbox" class="styled mycheck" name="id_list[]" value="<?php echo $value['id'] ?>" <?php echo (in_array($value['id'], $user_purmissions)) ? 'checked' : ''; ?> ></td>
                                            <td><?php echo $value['name'] ?></td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <!-- create reset button-->
                            <div class="col-12">
                                <div class="submit-section text-center btn-add">
                                    <button   class="btn btn-theme button-1 text-white ctm-border-radius p-2 add-person ctm-btn-padding   legitRipple" data-spinner-color="#03A9F4" data-style="zoom-out" type="submit">Save <i class="icon-arrow-right14 position-right"></i></button> 
                                </div>
                            </div>
                        </div>
                        
                    </fieldset>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
// if ($('.admin-purmissions-list').length) {

    $('#SellectAll').click(function () {
        var checked = $(this).prop('checked');
        $('tbody').find('input:checkbox').prop('checked', checked);
        if (checked)
            $('tbody').find('input:checkbox').parent('span').addClass('checked');
        else
            $('tbody').find('input:checkbox').parent('span').removeClass('checked');
    });
    $("tbody .styled").change(function () {
        if (!$(this).prop("checked")) {
            $("#SellectAll").prop("checked", false);
            $('#SellectAll').parent('span').removeClass('checked');
        }
    });
// }
</script>

