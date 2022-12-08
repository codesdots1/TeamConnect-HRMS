<div class="panel panel-flat">
    <div class="panel-heading">
        <!--            <h5 class="panel-title">Add User<a class="heading-elements-toggle"><i class="icon-more"></i></a></h5>-->
        <!--            <div class="heading-elements">-->
        <!--                <ul class="icons-list">-->
        <!--                    <li><a data-action="collapse"></a></li>-->
        <!--                    <li><a data-action="reload"></a></li>-->
        <!--                    <li><a data-action="close"></a></li>-->
        <!--                </ul>-->
        <!--            </div>-->
    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <fieldset>
                    <legend class="text-semibold"><i class="icon-reading position-left"></i> User Details</legend>

                    <?php
                    //create  form open tag
                    $form_id = array(
                        'id'=>'masterRecordForm',
                        'class'=>'form-horizontal'
                    );
                    echo form_open_multipart('',$form_id);
                    //currency id
                    // echo form_hidden('id',$id = (isset($id) && ($id != ''))?$id:'');
                    if(isset($user->id)){
                        $data = array(
                            'type'  => 'hidden',
                            'name'  => 'id',
                            'id'    => 'id',
                            'value' => $user->id = (isset($user->id) && ($user->id != ''))?$user->id:''
                        );
                        echo form_input($data);
                    }


                    //User Email


                    if(!isset($user->id)) {

                        echo "<div class=\"form-group\">";
                        echo form_label('Email', '', array('class' => 'col-lg-3 control-label'));
                        echo "<div class=\"col-lg-9\">";
                        $data_user_email = array(
                            'type'=>'email',
                            'name' => 'email',
                            'id' => 'email',
                            'class' => 'form-control',
                            'placeholder' => 'Enter User Email',

                        );
                        echo form_input($data_user_email);
                        echo "</div>";
                        echo "</div>";
                    }

                    //              User Firstname
                    echo "<div class=\"form-group\">";
                    echo form_label('First Name', '', array('class' => 'col-lg-3 control-label'));
                    echo "<div class=\"col-lg-9\">";
                    $data_user_firstname = array(

                        'name' => 'first_name',
                        'id' => 'first_name',
                        'class' => 'form-control',
                        'placeholder' => 'Enter First Name',
                        'value'=>@$user->first_name = (isset($user->first_name) && ($user->first_name != ''))?$user->first_name:''
                    );
                    echo form_input($data_user_firstname);
                    echo "</div>";
                    echo "</div>";




                    //              User Lastname
                    echo "<div class=\"form-group\">";
                    echo form_label('Last Name', '', array('class' => 'col-lg-3 control-label'));
                    echo "<div class=\"col-lg-9\">";
                    $data_user_lastname = array(

                        'name' => 'last_name',
                        'id' => 'last_name',
                        'class' => 'form-control',
                        'placeholder' => 'Enter Last Name',
                        'value'=>@$user->last_name = (isset($user->last_name) && ($user->last_name != ''))?$user->last_name:''
                    );
                    echo form_input($data_user_lastname);
                    echo "</div>";
                    echo "</div>";


                    //              User Mobile No
                    echo "<div class=\"form-group\">";
                    echo form_label('Mobile', '', array('class' => 'col-lg-3 control-label'));
                    echo "<div class=\"col-lg-9\">";
                    $data_user_mobileno = array(
                        'type'=>'tel',
                        'name' => 'phone',
                        'id' => 'phone',
                        'class' => 'form-control',
                        'placeholder' => 'Enter Mobile No',
                        'value'=>@$user->phone = (isset($user->phone) && ($user->phone != ''))?$user->phone:''
                    );
                    echo form_input($data_user_mobileno);
                    echo "</div>";
                    echo "</div>";


                    //User Password

                    echo "<div class=\"form-group\">";
                    echo form_label('Password(if changing password)','',array('class'=>'col-lg-3 control-label'));
                    echo "<div class=\"col-lg-9\">";
                    $data_user_password = array(
                        'type'=>'password',
                        'name' => 'password',
                        'id' => 'password',
                        'class' => 'form-control',
                        'placeholder' => 'Enter Password',
                    );
                    echo form_input($data_user_password);
                    echo "</div>";
                    echo "</div>";


                    //   //User Confirm Password
                    echo "<div class=\"form-group\">";
                    echo form_label('Confirm Password','',array('class'=>'col-lg-3 control-label'));
                    echo "<div class=\"col-lg-9\">";
                    $data_user_confirmpassword = array(
                        'type'=>'password',
                        'name' => 'password_confirm',
                        'id' => 'password_confirm',
                        'class' => 'form-control',
                        'placeholder' => 'Enter Confrim Password',
                    );
                    echo form_input($data_user_confirmpassword);
                    echo "</div>";
                    echo "</div>";
                    ?>

                    <div class="form-group">
                        <label class="col-lg-3 control-label"><?= lang('samaj') ?></label>
                        <div class="col-lg-9">
                            <select name="samaj_id[]" id="samaj_id" data-init="1" data-placeholder="Select <?= lang('samaj') ?>" class="select" multiple>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>
                </fieldset>
            </div>

            <?php if ($this->ion_auth->is_admin()){ ?>
                <div class="col-md-6">
                    <fieldset>
                        <legend class="text-semibold"><i class=""></i> Assigned Groups</legend>

                        <div class="panel-body">
                            <?php foreach ($groups as $group){ ?>
                                <label class="checkbox">
                                    <?php
                                    $gID=$group['id'];
                                    $checked = null;
                                    $item = null;
                                    foreach($currentGroups as $grp) {
                                        if ($gID == $grp->id) {
                                            $checked= ' checked="checked"';
                                            break;
                                        }
                                    }
                                    ?>
                                    <div class='checkbox-custom checkbox-primary'> <input  type='checkbox' <?= $checked ?> name='groups[]' value='<?= $group['id'] ?>'> <label><?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>&nbsp;&nbsp;&nbsp;</label> </div>
                                </label>
                            <?php } ?>
                        </div>
                    </fieldset>
                </div>
            <?php } ?>
        </div>

        <div class="text-right">
            <button type="button" class="btn btn-xs border-slate text-slate btn-flat cancel" data-dismiss="modal"
                    onclick="window.location.href='<?php echo site_url('Auth/index'); ?>'">Cancel <i class="icon-cross2 position-right"></i>
            </button>
<!--            --><?php //echo form_submit('submit', 'Submit', "class='btn btn-xs border-blue text-blue btn-flat'"); ?><!-- <i id="icon-hide" class="icon-arrow-right8 position-right"></i>-->
            <button type="submit" name="submit" class="btn btn-xs border-blue text-blue btn-flat">
                       Submit
                <i id="icon-hide" class="icon-arrow-right8 position-right"></i>
            </button>
        </div>
    </div>
</div>
<?php echo form_close(); ?>


<script>
    $(document).ready(function() {

      //  CheckboxKeyGen();
        Select2Init();
        samajDD('','#samaj_id');

        // Initialize
        var validator = $("#masterRecordForm").validate({
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
                email: {
                    required: true,
                    email:true,
                    validEmail:true,
                },

                first_name:{
                    required:true
                },
                last_name:{
                    required:true
                },

                phone:{
                    required:true,
                    number:true,
                    maxlength:12,
                },
                "samaj_id[]":{
                    required:true,
                },
                <?php if(@$user->id==""){ ?>
                password:{
                    required:true,
                    maxlength:8,
                },
                <?php } else{ ?>
                password:{

                    maxlength:8
                },
                <?php }?>
                <?php if(@$user->id==""){ ?>
                password_confirm:{
                    required:true,
                    equalTo:"#password"
                }
                <?php } else { ?>
                password_confirm:{

                    equalTo:"#password"
                }
                <?php } ?>
            },
            messages: {
                email: {
                    required: "Please enter  email.",

                },
                first_name:{
                    required:"Please enter firstname."
                },
                last_name:{
                    required:"Please enter lastname."
                },
                phone:{
                    required:"Please enter Mobile no.",
                    maxlength:"Please enter no more than 12 number."


                },
                password:{
                    required:"Please enter password."
                },
                password_confirm:{
                    required:"Please enter confirmpassword.",
                    equalTo:"confirm password and password must be same."
                },
                "samaj_id[]":{
                    required:"Please select samaj",
                },

            },
            submitHandler: function (e) {
//                    var actionBtn = $("#action").val();
                $(e).ajaxSubmit({
                    url: '<?php echo current_url();?>',
                    type: 'post',
                    beforeSubmit: function (formData, jqForm, options) {
                        //$(e).find('button').hide();
                        $('#loader').show();
                    },
                    complete: function () {
                        $('#loader').hide();
                        //(e).find('button').show();
                    },
                    dataType: 'json',
                    clearForm: false,
                    success: function (resObj, statusText) {
                        if(resObj.success){

                            swal({title: "Success",text: resObj.msg,type: "success"}, function (){
                                window.location.href = '<?php echo site_url('Auth');?>';
                            });

                        }else{
                            swal({title: "Error",text: resObj.msg,type: "error"});
                        }
                    }
                });
            }
        });

        jQuery.validator.addMethod("validEmail", function(value, element, param) {
            var reg = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
            if(reg.test(value)){
                return true;
            }else{
                return false;
            }
        }, "Please enter a valid email address");

        // Switchery
        // ------------------------------

        // Initialize multiple switches
        if (Array.prototype.forEach) {
            var elems = Array.prototype.slice.call(document.querySelectorAll('.switchery'));
            elems.forEach(function(html) {
                var switchery = new Switchery(html);
            });
        }
        else {
            var elems = document.querySelectorAll('.switchery');
            for (var i = 0; i < elems.length; i++) {
                var switchery = new Switchery(elems[i]);
            }
        }
    });

    <?php
        foreach ($userSamaj as $userSamajKey => $userSamajValue){ ?>
            var option = new Option("<?= $userSamajValue['samaj_name']; ?>", "<?= $userSamajValue['samaj_id']; ?>", true, true);
            $('#samaj_id').append(option).trigger('change');
    <?php } ?>
</script>

<?php if (isset($select2)) { ?>
    <?= $select2 ?>
<?php } ?>
