<!--<script src='https://www.google.com/recaptcha/api.js'></script>-->

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
        'id'=>'checkOTP',
        'method'=>'post',
        'class'=>'form-horizontal',
        'autocomplete' =>'off'
    );
    echo form_open("auth/otp_check",$form_id);?>

    <div class="text-center">
        <div class="icon-object border-warning text-warning"><i class="icon-exclamation"></i></div>
        <h5 class="content-group"><?= lang('otp_verification')?>  </h5>
    </div>

    <div class="form-group has-feedback">
        <input type="tel" name="otp" id="otp" minlength="6"  value="123456"  maxlength="6" class="form-control" placeholder="Please Enter <?= lang('otps') ?>">
        <div class="form-control-feedback">
            <i class="icon-key text-muted"></i>
        </div>
    </div>

    <div class="form-group  has-feedback">

        <select data-placeholder="Select Your <?= lang('company') ?>" name="company_id" id="company_id" class="select">
            <option value=""></option>
            <?php
            foreach ($companyId as $company){ ?>
                <option value="<?= $company['company_id'] ?>"> <?= $company['company_name'] ?></option>
           <?php } ?>
        </select>
    </div>

    <div class="form-group has-feedback has-feedback-left">
        <div class="g-recaptcha" class="form-control"
             data-sitekey="<?=$this->config->item("recaptcha_site_key") ?>"></div>
        <label id="g-recaptcha-response-error" class="validation-error-label" for="g-recaptcha-response"></label>
    </div>

    <div class="form-group has-feedback ">

        <div class="col-xs-6">
            <button type="button" id="resendOtp" class="btn btn-primary pull-left"><?= lang('reset_btn') ?>  </button>
        </div>

        <div class="col-xs-6">
            <button type="submit" class="btn bg-pink pull-right"> <?= lang('submit_btn') ?> </button>
        </div>
    </div>
</div>
</div>
<?php
echo form_close();
?>

<script>

    $(document).ready(function() {
        Select2Init();
        $("#otp").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                    // Allow: Ctrl+A, Command+A
                    (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                    // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });

        $("#resendOtp").click(function () {
            $.ajax({
                url: "<?php echo site_url("auth/reSendOtp")?>",
                type: 'POST',
                dataType: 'json',
                beforeSend: function (formData, jqForm, options) {

                },
                complete: function () {

                },
                success: function (data) {
                    if (data.success) {
                        swal({title: "Success", text: data.msg, type: "success",confirmButtonColor: "#4CAF50"});
                    } else {
                        swal({title: "Error", text: data.msg, type: "error",confirmButtonColor: "#F44336"});
                    }
                }
            });
        });

        var validator = $("#checkOTP").validate({

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
                otp: {
                    required: true,
                    number: true,
                    digits: true,
                    remote:{
                        url: "<?php echo site_url( "auth/checkOtpExist");?>",
                        type: "post",
                        data: {
                            opt: function () {
                                return $("#otp").val();
                            }
                        }
                    }
                },
                'g-recaptcha-response': {
                    required: true
                },
                company_id:{
                    required: true
                }

            },
            messages: {
                otp: {
                    required: "Please Enter <?= lang('otps')?>",
                    number:"Please Enter valid <?= lang('otps')?>",
                    digits:"Please Enter only digits",
                    remote:"Enter <?= lang('otps')?> Is Invalid"
                },
                'g-recaptcha-response': {
                    required: 'Please complete captcha challenge',
                },
                company_id:{
                    required:  'Please Select <?= lang('company')?>',
                }
            },
            submitHandler: function (form) {
                form.submit();

            }
        });



    });



</script>