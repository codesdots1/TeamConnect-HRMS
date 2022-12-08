<!--city modal code here -->
<div id="cityModal" class="modal fade">
	<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-body">
				<?php
				$form_id = array(
						'id'=>'cityDetails',
						'method'=>'post',
						'class'=>'form-horizontal'
				);?>
				<?= form_open('',$form_id); ?>
				<input type="hidden" name="city_id" id="city_id">


				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h3 class="modal-title mb-3"><?= lang('city_form') ?></h3>

				<div class="form-group">
					<span><?= lang('state_name') ?><span class="text-danger">*</span></span>
					<select name="state_id" id="state_id" data-init="1" data-placeholder="Select <?= lang('state_name') ?>"
							class="select">
						<option value=""></option>
						<?php foreach ($stateData as $state) { ?>
							<option value="<?php echo $state['state_id'] ?>"><?php echo $state['state_name'] ?></option>
						<?php } ?>
					</select>
				</div>

				<div class="form-group">
					<span><?= lang('city_name') ?><span class="text-danger">*</span></span>
					<input type="text" name="city_name" id="city_name" class="form-control"
						   placeholder="Enter <?= lang('city_name') ?>">
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
    $(document).on('hide.bs.modal', '#cityModal', function () {
        $('#cityTable input').prop('checked', false);
        CheckboxKeyGen();
    });

    //Delete Time Cancel button click to remove checked value
    $(document).on('click', '.cancel', function () {
        $('#cityTable input[class="dt-checkbox styled"]').prop('checked', false);
        CheckboxKeyGen();
    });

    $(document).ready(function () {

        // Select with search
        $('.select').select2();
        CustomToolTip();
        CheckboxKeyGen('checked');

        //$('#checkAll').prop('checked', false);
        $('#checkAll').click(function () {
            var checkedStatus = this.checked;
            $('#cityTable tbody tr').find('td:first :checkbox').each(function () {
                $(this).prop('checked', checkedStatus);
            });
            CheckboxKeyGen();
        });


        $('#cityModal').on('shown.bs.modal', function () {
            $('#state_id').focus();
        });

        //city modal open
        $('.addCity').click(function () {
            DtFormClear('cityDetails');
            $("form#cityDetails input[name=city_id]").val('');
            //$("#state_id").val('').trigger('change');
            $('#cityModal').modal('show');
        });

        //city modal validation
        var validator = $("#cityDetails").validate({
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
                city_name: {
                    required: true,
//                    remote: {
//                        url: "<?php //echo site_url( "City/NameExist");?>//",
//                        type: "post",
//                        data: {
//                            column_name: function () {
//                                return "city_name";
//                            },
//                            column_id: function () {
//                                return $("#city_id").val();
//                            },
//                            table_name: function () {
//                                return "tbl_city";
//                            },
//                        }
//                    }
                },
                state_id: {
                    required: true,
                }
            },
            messages: {
                city_name: {
                    required: "Please Enter <?= lang('city_name') ?>",
//                    remote  : "<?//= lang('city_name') ?>// Already Exist",
                },
                state_id: {
                    required: "Please Select <?= lang('state_name') ?>"
                }
            },
            submitHandler: function (e) {
                $(e).ajaxSubmit({
                    url: '<?php echo site_url("City/save");?>',
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
                            $('#cityModal').modal('hide');
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
                                        var option = new Option(resObj.country_name, resObj.city_id, true, true);
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
         function EditRecord(cityId) {
            $('#cityTable  input[class="dt-checkbox styled"]').prop('checked', false);
            $('#ids_' + cityId).prop('checked', true);
            $('.editRecord').click();
        }

    //Edit Record
    $(document).on('click', '.editRecord', function () {
        $('#cityTable input[class="dt-checkbox styled"]').prop('checked', false);
        $("form#cityDetails .validation-error-label").html('');

        var id = $(this).val();
        $("#ids_" + id).prop("checked", true);
        var edit_val = $('.dt-checkbox:checked').val();

        var selected_tr = $('.dt-checkbox:checked');
        var element = $(selected_tr).closest('tr').get(0);
        var aData = dt_DataTable.row(element).data();

        DtFormFill('cityDetails', aData);

        $('#cityModal').modal('show');

    });


</script>
