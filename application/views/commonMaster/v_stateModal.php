<!--state modal code here -->
<div id="stateModal" class="modal fade">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<?php
				$form_id = array(
						'id'=>'stateDetails',
						'method'=>'post',
						'class'=>'form-horizontal'
				);?>
				<?= form_open('',$form_id); ?>
				<input type="hidden" name="state_id" id="state_id">

				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title mb-3"><?= lang('state_form') ?></h3>

				<div class="form-group">
					<span><?= lang('country_name') ?><span class="text-danger">*</span></span>
					<select name="country_id" id="country_id" data-init="1" data-placeholder="Select <?= lang('country_name') ?>"
							class="select">
						<option value=""></option>
						<?php foreach ($countryData as $country) { ?>
							<option value="<?php echo $country['country_id'] ?>"><?php echo $country['country_name'] ?></option>
						<?php } ?>
					</select>
				</div>

				<div class="form-group">
					<span><?= lang('state_name') ?><span class="text-danger">*</span></span>
					<input type="text" name="state_name" id="state_name" class="form-control"
						   placeholder="Enter <?= lang('state_name') ?>">
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

<!--  add,update modal code here-->
<script>

    var laddaSubmitBtn = Ladda.create(document.querySelector('.submit'));

    $(document).on('hide.bs.modal', '#stateModal', function () {
        $('#stateTable input').prop('checked', false);
        CheckboxKeyGen();
    });

    //Delete Time Cancel button click to remove checked value
    $(document).on('click', '.cancel', function () {
        $('#stateTable input[class="dt-checkbox styled"]').prop('checked', false);
        CheckboxKeyGen();
    });

    $(document).ready(function () {

        // Select with search
        $('.select').select2();
        CustomToolTip();
        CheckboxKeyGen('checkAll');

        //$('#checkAll').prop('checked', false);
        $('#checkAll').click(function () {
            var checkedStatus = this.checked;
            $('#stateTable tbody tr').find('td:first :checkbox').each(function () {
                $(this).prop('checked', checkedStatus);
            });
            CheckboxKeyGen();
        });


        $('#stateModal').on('shown.bs.modal', function () {
            $('#country_id').focus();
        });

        //state modal open
        $('.addState').click(function () {
            DtFormClear('stateDetails');
            $("form#stateDetails input[name=state_id]").val('');
            //$("form#stateDetails select").val('').trigger('change');
            $('#stateModal').modal('show');
        });

        //State modal validation
        var validator = $("#stateDetails").validate({
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
                state_name: {
                    required: true,
//                    remote: {
//                        url: "<?php //echo site_url( "State/NameExist");?>//",
//                        type: "post",
//                        data: {
//                            column_name: function () {
//                                return "state_name";
//                            },
//                            column_id: function () {
//                                return $("#state_id").val();
//                            },
//                            table_name: function () {
//                                return "tbl_state";
//                            },
//                        }
//                    }
                },
                country_id: {
                    required: true,
                }
            },
            messages: {
                state_name: {
                    required: "Please Enter <?= lang('state_name') ?>",
//                    remote  : "<?//= lang('state_name') ?>// Already Exist",
                },
                country_id: {
                    required: "Please Select <?= lang('country_name') ?>"
                }
            },
            submitHandler: function (e) {
                $(e).ajaxSubmit({
                    url: '<?php echo site_url("State/save");?>',
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
                            $('#stateModal').modal('hide');
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
                                        var option = new Option(resObj.country_name, resObj.state_id, true, true);
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



        //Edit function
         function EditRecord(stateId) {
             $('#stateTable  input[class="dt-checkbox styled"]').prop('checked', false);
             $('#ids_' + stateId).prop('checked', true);
             $('.editRecord').click();
         }

    $(document).on('click', '.editRecord', function () {
        $('#stateTable input[class="dt-checkbox styled"]').prop('checked', false);
        $("form#stateDetails .validation-error-label").html('');

        var id = $(this).val();
        $("#ids_" + id).prop("checked", true);
        var edit_val = $('.dt-checkbox:checked').val();

        var selected_tr = $('.dt-checkbox:checked');
        var element = $(selected_tr).closest('tr').get(0);
        var aData = dt_DataTable.row(element).data();

        DtFormFill('stateDetails', aData);

        $('#stateModal').modal('show');

    });

</script>
