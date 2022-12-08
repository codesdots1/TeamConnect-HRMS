<div id="countryModal" class="modal fade">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
			<div class="modal-body" style="padding: 30px;">
				<?php
				$form_id = array(
						'id'=>'countryDetails',
						'method'=>'post',
						'class'=>'form-horizontal'
				);?>
				<?= form_open('',$form_id); ?>
				<input type="hidden" name="country_id" id="country_id">

				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title mb-3"><?= lang('country_form') ?></h3>
				<div class="form-group">
					<span><?= lang('country_name') ?><span class="text-danger">*</span></span>
					<input type="text" name="country_name" id="country_name" class="form-control"
						   placeholder="Enter <?= lang('country_name') ?>">
				</div>
				<div class="form-group">
					<span><?= lang('is_active') ?></span>
					<div class="checkbox checkbox-switchery switchery-xs">
						<label>
							<input type="checkbox" name="is_active" id="is_active" class="switchery">
						</label>
					</div>
				</div>
			</div>

            <div class="modal-footer">
                <button type="reset" name="cancel"
                       class="btn btn-danger text-white ctm-border-radius float-right ml-3 cancel"
                        data-dismiss="modal"><?= lang('cancel_btn') ?> <i class="icon-cross2 position-right"></i></button>

                <button type="submit"
                        class="btn btn-theme button-1 text-white ctm-border-radius float-right submit" ><span><?= lang('submit_btn') ?></span>
                    <i id="icon-hide" class="icon-arrow-right8 position-right"></i>
                </button>

                <?= form_close(); ?>
            </div>
        </div>
    </div>
</div>

<!--  add,update and delete model code here-->
<script>

    var laddaSubmitBtn = Ladda.create(document.querySelector('.submit'));

    $(document).on('hide.bs.modal', '#countryModal', function () {
        $('#countryTable input').prop('checked', false);
        CheckboxKeyGen();
    });

    //Delete Time Cancel button click to remove checked value
    $(document).on('click', '.cancel', function () {
        $('#countryTable input[class="dt-checkbox styled"]').prop('checked', false);
        CheckboxKeyGen();
    });

    $(document).ready(function () {
        CustomToolTip();
        CheckboxKeyGen('checkAll');

        //$('#checkAll').prop('checked', false);
        $('#checkAll').click(function () {
            var checkedStatus = this.checked;
            $('#countryTable tbody tr').find('td:first :checkbox').each(function () {
                $(this).prop('checked', checkedStatus);
            });
            CheckboxKeyGen();
        });


        $('#countryModal').on('shown.bs.modal', function () {
            $('#country_name').focus();
        });

        //country model open
        $('.addCountry').click(function () {
            DtFormClear('countryDetails');
            $("form#countryDetails input[name=country_id]").val('');
            $('#countryModal').modal('show');
        });

        //Country model validation
        var validator = $("#countryDetails").validate({
            ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
            errorClass: 'validation-error-label',
            successClass: 'validation-valid-label',
            highlight: function(element, errorClass) {
                $(element).removeClass(errorClass);
            },
            unhighlight: function(element, errorClass) {
                $(element).removeClass(errorClass);
            },

            // Different components require proper error label placement
            errorPlacement: function(error, element) {

                // Styled checkboxes, radios, bootstrap switch
                if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container') ) {
                    if(element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                        error.appendTo( element.parent().parent().parent().parent() );
                    }
                    else {
                        error.appendTo( element.parent().parent().parent().parent().parent() );
                    }
                }

                // Unstyled checkboxes, radios
                else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
                    error.appendTo( element.parent().parent().parent() );
                }

                // Input with icons and Select2
                else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
                    error.appendTo( element.parent() );
                }

                // Inline checkboxes, radios
                else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                    error.appendTo( element.parent().parent() );
                }

                // Input group, styled file input
                else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
                    error.appendTo( element.parent().parent() );
                }

                else {
                    error.insertAfter(element);
                }
            },
            validClass: "validation-valid-label",
            success: function(label) {
                label.addClass("validation-valid-label").text("Success.")
            },
            rules: {
                country_name: {
                    required: true,
                    remote: {
                        url: "<?php echo site_url( "Country/NameExist");?>",
                        type: "post",
                        data: {
                            column_name: function () {
                                return "country_name";
                            },
                            column_id: function () {
                                return $("#country_id").val();
                            },
                            table_name: function () {
                                return "tbl_country";
                            }
                        }
                    }
                },
            },
            messages: {
                country_name: {
                    required: "Please Enter <?= lang('country_name') ?>",
                    remote  : "<?= lang('country_name') ?> Already Exist",

                }
            },
            submitHandler: function (e) {
                $(e).ajaxSubmit({
                    url: '<?php echo site_url("Country/save");?>',
                    type: 'post',
                    beforeSubmit: function (formData, jqForm, options) {
                        laddaStart();
                    },
                    complete: function () {
                        laddaStop();
                    },
                    dataType: 'json',
                    clearForm: false,
                    success: function (resObj) {
                        if(resObj.success){
                            $('#countryModal').modal('hide');
                            swal({
                                    title: "<?= ucwords(lang('success')); ?>",
                                    text: resObj.msg,
                                    confirmButtonColor: "<?= BTN_SUCCESS; ?>",
                                    type: "<?= lang('success'); ?>"
                                },
                                function () {
                                    if(typeof dt_DataTable !== 'undefined' ) {
                                        dt_DataTable.ajax.reload();
                                    } else {
                                        var option = new Option(resObj.country_name, resObj.country_id, true, true);
                                        $('#CountryList').append(option).trigger('change');
                                    }
                            });
                        }else{
                            swal({
                                title: "<?= ucwords(lang('error')); ?>",
                                text: resObj.msg,
                                type: "<?= lang('error'); ?>",
                                confirmButtonColor: "<?= BTN_ERROR; ?>"
                            });


                        }
                    }
                });
            }
        });
    });

    /// Status change function
    $(document).on('click', '.isActive', function () {
        var countryId = $(this).attr('id');
        var isActive   = $(this).data("status");
        $.ajax({
            type: "post",
            url: "<?= site_url("Country/changeStatus")?>",
            dataType: "json",
            data: {countryId: countryId, status: isActive},
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

    //Delete function
    function DeleteRecord(countryId) {
        $('#countryTable tbody input[class="dt-checkbox styled"]').prop('checked', false);
        $('#ids_' + countryId).prop('checked', true);
        $('.deleteRecord').click();
        CheckboxKeyGen();
    }


    //Delete Record
    $(document).on('click', '.deleteRecord', function () {
        $('form#countryDetails #is_active').siblings().remove();
        var deleteElement = $('#countryTable tbody input[class="dt-checkbox styled"]:checked');
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
                        url: "<?= base_url("Country/delete")?>",
                        dataType: "json",
                        data: {deleteId: deleteId},
                        success: function (data) {
                            if (data['success']) {
                                swal({

                                    title: "<?= ucwords(lang('success'))?>",
                                    text: data['msg'],
                                    type: "<?= lang('success')?>",
                                    confirmButtonColor: "<?= BTN_SUCCESS; ?>",
                                },function(){
                                    dt_DataTable.ajax.reload();
                                   // $('#checkAll').prop('checked', false);
                                    CheckboxKeyGen('checkAll');
                                });
                            } else {
                                swal({
                                    title: "<?= ucwords(lang('error')); ?>",
                                    text: data['msg'],
                                    type: "<?= lang('error'); ?>",
                                    confirmButtonColor: "<?= BTN_ERROR; ?>"
                                },function(){
                                    dt_DataTable.ajax.reload();
                                    CheckboxKeyGen('checkAll');
                                });
                            }
                        }
                    });
                });
        }
    });


        //Edit function
         function EditRecord(countryId) {
            $('#countryTable  input[class="dt-checkbox styled"]').prop('checked', false);
            $('#ids_' + countryId).prop('checked', true);
            $('.editRecord').click();
        }




    $(document).on('click', '.editRecord', function () {
        $('#countryTable input[class="dt-checkbox styled"]').prop('checked', false);
        $("form#countryDetails .validation-error-label").html('');

        var id = $(this).val();
        $("#ids_" + id).prop("checked", true);
        var edit_val = $('.dt-checkbox:checked').val();

        var selected_tr = $('.dt-checkbox:checked');
        var element = $(selected_tr).closest('tr').get(0);
        var aData = dt_DataTable.row(element).data();

        DtFormFill('countryDetails', aData);

        $('#countryModal').modal('show');

    });

</script>
