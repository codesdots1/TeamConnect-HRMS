<div class="panel panel-flat  border-left-lg border-left-slate">
    <div class="panel-heading">
        <h5 class="panel-title"><?= lang('email_template_form') ?> </h5>
    </div>

    <div class="panel-body">
        <?php
        $templateType = isset($getEmailTemplateData['type']) ? $getEmailTemplateData['type'] : 'quotation';
        $availableMergeFields = getAvailableMergeFields();

        //create  form open tag
        $form_id = array(
            'id' => 'DocumentDetails',
            'method' => 'post',
            'autocomplete' => 'off'
        );
        echo form_open_multipart('', $form_id);

        ?>
        <input type="hidden" name="document_id"
               value="<?= isset($getDocumentData['document_id']) ? $getDocumentData['document_id'] : '' ?>"
               id="document_id">

        <!--  Email Template Details-->
        <fieldset class="content-group">
            <legend class="text-bold"> <?= lang('email_template_details'); ?>  </legend>

            <div class="form-group">

                <div class="row">
                    <div class="col-md-6">

                        <div class="form-group">
                            <label><?= lang('series') ?></label>
                            <?php if (isset($getDocumentData['document_id']) && $getDocumentData['document_id'] != '') { ?>
                                <input type="text" class="form-control" name="series" id="series"
                                       value="<?= $getDocumentData['prefix'] . $getDocumentData['series'] ?>"
                                       readonly>
                            <?php } else { ?>
                                <input type="text" class="form-control" name="series" id="series"
                                       value="<?= DOCUMENT_PREFIX . $getNextAutoIncrementId ?>" readonly>
                            <?php } ?>
                        </div>
                        <div class="form-group">
                            <label><?= lang('document_type') ?></label>
                            <select data-placeholder="Select Your <?= lang('document_type') ?>" name="document_type"
                                    id="document_type"
                                    class="select">
                                <option value=""></option>
                                <?= CreateOptionFromEnumValues(enumValues("tbl_document", "document_type"), isset($getDocumentData['document_type']) ? $getDocumentData['document_type'] : ''); ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?= lang('subject_name') ?></label>
                            <input type="text" class="form-control" name="subject_name" id="subject_name"
                                   value="<?= isset($getDocumentData['subject_name']) ? $getDocumentData['subject_name'] : '' ?>"
                                   placeholder="Please Enter <?= lang('subject_name') ?>">
                        </div>
                        <div class="form-group">
                            <label><?= lang('document_name') ?></label>
                            <input type="text" class="form-control" name="document_name" id="document_name"
                                   value="<?= isset($getDocumentData['document_name']) ? $getDocumentData['document_name'] : '' ?>"
                                   placeholder="Please Enter <?= lang('document_name') ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><?= lang('available_merge_fields') ?></label>
                            <div class="row">
                                <?php
                                foreach ($availableMergeFields as $key => $merge_fields) {
                                    $uniqueAvailable = array_unique(call_user_func_array('array_merge', array_map(function ($merge_field) {
                                        return $merge_field['available'];
                                    }, $merge_fields)));
                                    if (in_array($templateType, $uniqueAvailable)) {
                                        ?>
                                        <div class="<?= count($uniqueAvailable) > 1 ? 'col-md-12' : 'col-md-12' ?>">
                                            <table class="table" cellspacing="5" cellpadding="5">
                                                <tr>
                                                    <th class="pull-left text-capitalize text-bold"><?= $key ?></th>
                                                </tr>
                                                <?php
                                                foreach ($merge_fields as $merge_key => $merge_field) {
                                                    if (in_array($templateType, $merge_field['available'])) {
                                                        ?>
                                                        <tr>
                                                            <td><?= $merge_field['name'] ?> &nbsp;&nbsp;
                                                                <a href="javascript:;" class="merge_field"
                                                                   data-key="<?= $merge_field['key'] ?>">
                                                                    <?= $merge_field['key'] ?></a>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </table>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label><?= lang('email_message') ?></label>
                            <textarea name="email_message" id="email_message" class="ckeditor" rows="2" cols="2">
                                      <?= (isset($getDocumentData['email_message']) && ($getDocumentData['email_message'] != '')) ? $getDocumentData['email_message'] : ''; ?>
                                    </textarea>
                            <label id="email_message-error" class="validation-error-label" for="email_message"></label>

                        </div>
                    </div>
                </div>
            </div>
        </fieldset>
        <!--  End Email Template Details-->

        <!-- create reset button-->
        <div class="text-right">
            <button type="button" class="btn btn-xs border-slate text-slate btn-flat cancel"
                    onclick="window.location.href='<?php echo site_url('Document'); ?>'"><?= lang('cancel_btn') ?>
                <i class="icon-cross2 position-right"></i></button>

            <button type="submit" id="submit"
                    class="btn btn-xs border-blue text-blue btn-flat btn-ladda btn-ladda-progress submit"
                    data-spinner-color="<?= BTN_SPINNER_COLOR; ?>" data-style="fade">
                <span class="ladda-label"><?= lang('submit_btn') ?></span>
                <i id="icon-hide" class="icon-arrow-right8 position-right"></i>
            </button>

        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script>

    var laddaSubmitBtn = Ladda.create(document.querySelector('.submit'));

</script>
<script>
    $(document).ready(function () {
        CheckboxKeyGen();
        CKEDITOR.config.removePlugins = 'image,pwimage';
        ;

        var validator = $("#DocumentDetails").validate({
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
                document_name: {
                    required: true
                },
                subject_name: {
                    required: true
                },
                email_message: {
                    required: true
                }
            },
            messages: {
                document_name: {
                    required: "Please Enter <?= lang('document_name') ?>"
                },
                subject_name: {
                    required: "Please Enter <?= lang('subject_name') ?>"
                },
                email_message: {
                    required: "Please Enter <?= lang('email_message')?>"
                }
            },
            submitHandler: function (e) {
                $('textarea.ckeditor').each(function () {
                    var $textarea = $(this);
                    $textarea.val(CKEDITOR.instances[$textarea.attr('name')].getData());
                });
                $(e).ajaxSubmit({
                    url: '<?php echo site_url("Document/save");?>',
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
                                window.location.href = '<?php echo site_url('Document');?>';
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
        CustomToolTip();
        Select2Init();
    });
    $(".merge_field").on('click', function () {
        var key = $(this).data('key');

        var sel, range;
        if (window.getSelection) {
            sel = window.getSelection()
            if (sel.getRangeAt && sel.rangeCount) {
                range = sel.getRangeAt(0);
                range.deleteContents();
                var container = range.startContainer;
                var id = container.childNodes[3].getAttribute('id');
                if (id == 'subject_name') {
                    var value = $('#' + id).val() + key;
                    $('#' + id).val(value);
                    return false;
                }
            }
        }
        CKEDITOR.instances['email_message'].insertHtml(key);
        var $target = $('html,body');
        $target.animate({scrollTop: $target.height() - 100}, 1000);
    });
</script>