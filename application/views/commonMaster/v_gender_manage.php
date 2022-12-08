<div class="panel panel-flat border-left-lg border-left-slate">
    <div class="panel-heading ">
        <h5 class="panel-title"><?= lang('gender_heading') ?><a class="heading-elements-toggle"><i class="icon-more"></i></a>
        </h5>
        <div class="heading-elements">
            <ul class="icons-list">
                <li><a data-action="collapse"></a></li>
            </ul>
        </div>
    </div>

    <div class="panel-body">
        <?php
        //create  form open tag
        $form_id = array(
            'id'=>'genderDetails',
            'method'=>'post',
            'class'=>'form-horizontal'
        );
        echo form_open_multipart('', $form_id);
        $genderId = (isset($getGenderDesData['gender_id']) && ($getGenderDesData['gender_id'] != '')) ? $getGenderDesData['gender_id'] : '';
        ?>
        <input type="hidden" name="gender_id" value="<?= $genderId ?>" id="gender_id">

        <div class="tabbable">

            <ul class="nav nav-tabs">

                <?php
                if(is_array($languages) && count($languages) > 0){
                    foreach ($languages as $languageCount => $languageData) {
                        ?>
                        <li role="presentation" class = "<?= ($languageCount == 0) ? "active" : ""; ?>">
                            <a aria-expanded="true" href="#tab_<?= $languageData['language_id']; ?>" data-toggle="tab">
                                <?=  $languageData['language_name'];?>
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>

            </ul>

            <div class="tab-content">
                <?php if(is_array($languages) && count($languages) > 0) {
                    foreach ($languages as $languageCount => $languageData) {
                        ?>

                        <div role="tabpanel" class="<?= ($languageCount == 0) ? "tab-pane active" : "tab-pane"; ?>"
                             id="tab_<?= $languageData['language_id']; ?>">

                            <input type="hidden" name="language_id[]"
                                   value="<?= (isset($languageData['language_id']) && ($languageData['language_id'] != '')) ? $languageData['language_id'] : '' ?>"
                                   id="">
                            <input type="hidden" name="gender_description_id[]"
                                   value="<?= (isset($getGenderDescription[$languageData['language_id']]['gender_description_id']) && ($getGenderDescription[$languageData['language_id']]['gender_description_id'] != '')) ? $getGenderDescription[$languageData['language_id']]['gender_description_id'] : ''; ?>"
                                   id="gender_description_id">


                            <div class="form-group">
                                <label class="col-lg-3 control-label"><?= lang('gender') ?><span class="text-danger"> * </span></label>
                                <div class="col-lg-9">
                                    <input type="text" name="gender[]" id="gender" class="form-control"
                                           value="<?= (isset($getGenderDescription[$languageData['language_id']]['gender_name']) && ($getGenderDescription[$languageData['language_id']]['gender_name'] != '')) ? $getGenderDescription[$languageData['language_id']]['gender_name'] : ''; ?>"
                                           placeholder="Enter <?= lang('gender') ?>">
                                </div>
                            </div>



                        </div>
                    <?php }
                }?>

            </div>
        </div>

        <!-- create reset button-->
        <div class="text-right">
            <button type="button" class="btn btn-xs border-slate text-slate btn-flat cancel"
                    onclick="window.location.href='<?php echo site_url('Gender'); ?>'"><?= lang('cancel_btn') ?> <i class="icon-cross2 position-right"></i> </button>

            <button type="submit"
                    class="btn btn-xs border-blue text-blue btn-flat btn-ladda btn-ladda-progress submit"
                    data-spinner-color="#03A9F4" data-style="fade"><span
                    class="ladda-label"><?= lang('submit_btn') ?></span>
                <i id="icon-hide" class="icon-arrow-right8 position-right"></i>
            </button>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>
<script>
    var laddaSubmitBtn = Ladda.create(document.querySelector('.submit'));

    $(document).ready(function () {
        // Full featured editor
//        CKEDITOR.replace( 'long_description', {
//            height: '400px',
//            extraPlugins: 'forms'
//        });
        ImageLoad();
        numberInit();
        Select2Init();
        SwitcheryKeyGen();
        FileKeyGen();

        // Initialize
        jQuery.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-z ]+$/i.test(value);
        }, "Only Letters are allowed");
        var validator = $("#genderDetails").validate({
            ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
            errorClass: 'validation-error-label',
            successClass: 'validation-valid-label',
            highlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },
            unhighlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },

            // Different components require proper error label placement
            errorPlacement: function (error, element) {

                // Styled checkboxes, radios, bootstrap switch
                if (element.parents('div').hasClass("checker") || element.parents('div').hasClass("choice") || element.parent().hasClass('bootstrap-switch-container')) {
                    if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                        error.appendTo(element.parent().parent().parent().parent());
                    }
                    else {
                        error.appendTo(element.parent().parent().parent().parent().parent());
                    }
                }

                // Unstyled checkboxes, radios
                else if (element.parents('div').hasClass('checkbox') || element.parents('div').hasClass('radio')) {
                    error.appendTo(element.parent().parent().parent());
                }

                // Input with icons and Select2
                else if (element.parents('div').hasClass('has-feedback') || element.hasClass('select2-hidden-accessible')) {
                    error.appendTo(element.parent());
                }

                // Inline checkboxes, radios
                else if (element.parents('label').hasClass('checkbox-inline') || element.parents('label').hasClass('radio-inline')) {
                    error.appendTo(element.parent().parent());
                }

                // Input group, styled file input
                else if (element.parent().hasClass('uploader') || element.parents().hasClass('input-group')) {
                    error.appendTo(element.parent().parent());
                }

                else {
                    error.insertAfter(element);
                }
            },
            validClass: "validation-valid-label",
            success: function (label) {
                label.addClass("validation-valid-label").text("Success.")
            },
            rules: {
                'gender_name[]': {
                    required: true,
                }
            },
            messages: {
                'gender_name[]': {
                    required: "Please Select <?= lang('gender') ?>",
                }
            },submitHandler: function (e) {
                $(e).ajaxSubmit({
                    url: '<?php echo site_url("Gender/save");?>',
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
                        if (resObj.success) {
                            swal({
                                title: "<?= ucwords(lang('success')); ?>",
                                text: resObj.msg,
                                confirmButtonColor: "<?= BTN_SUCCESS; ?>",
                                type: "<?= lang('success'); ?>"
                            }, function () {
                                window.location.href = '<?php echo site_url('Gender');?>';
                            });
                        } else {
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


    function ImageLoad() {
        var memberId = $('#member_id').val();
        $.ajax({
            type: "post",
            url: "<?php echo site_url('Member/imageLoad');?>",
            dataType: "json",
            async: false,
            data: {member_id: memberId},
            beforeSend: function (formData, jqForm, options) {
//                var dialog = bootbox.dialog({
//                    message: 'Please have patience, images are loading',
//                });
            },
            complete: function () {
                // bootbox.hideAll();
            },
            success: function (resObj) {
                $('#imageListing').html(resObj);
            }
        });
    }



</script>
