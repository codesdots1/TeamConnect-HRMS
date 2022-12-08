<div class="content">
    <?php
    if ($message != '') { ?>
        <div class="alert alert-warning alert-bordered">
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span><span
                        class="sr-only">Close</span></button>
            <span class="text-semibold"><?= $message ?>
        </div>
    <?php } ?>

<div class="panel panel-body login-form">

    <?php
    //create  form open tag
    $form_id = array(
        'id' => 'changePassword',
        'method' => 'post',
        'class' => 'form-horizontal',
        'action' => 'auth/reset_password/' . $code,

    );
    echo form_open_multipart('', $form_id); ?>


    <div class="text-center">
        <div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
        <h5 class="content-group">Change Password</h5>
    </div>

    <div class="form-group has-feedback">
        <input type="password" name="new" value="" id="new" class="form-control" placeholder="Enter New Password" pattern="<?php echo '^.{'.$this->data['min_password_length'].'}.*$' ?>">
    </div>

    <div class="form-group has-feedback">
        <input type="password" name="new_confirm" value="" id="new_confirm" class="form-control" placeholder="Enter Confirm New Password" pattern="<?php echo '^.{'.$this->data['min_password_length'].'}.*$' ?>">
    </div>

    <input type="hidden" name="user_id" value="<?= $user_id ?>" id="user_id" class="form-control">

    <?php echo form_hidden($csrf); ?>

    <button type="submit" class="btn bg-pink-400 btn-block">Change password</button>
</div>
</div>
<?php

echo form_close();
?>
<script>
    $(document).ready(function () {

        var validator = $("#changePassword").validate({

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
                new: {
                    required: true,
                    minlength: 8,
                    maxlength: 20
                },
                new_confirm: {
                    required: true,
                    equalTo: "#new",
                    minlength: 8,
                    maxlength: 20
                }
            },
            messages: {
                new: {
                    required: "Please enter new password",
                    minlength: "Please enter at least 8 characters",
                    maxlength: "Please enter no more than 20 characters"
                },
                new_confirm: {
                    required: "Please enter confirm new password",
                    equalTo:  "New password and Confirm New Password must be same",
                    minlength: "Please enter at least 8 characters",
                    maxlength: "Please enter no more than 20 characters"
                }
            },
            submitHandler: function (form) {
                form.submit();
            }
        });
    });

</script>