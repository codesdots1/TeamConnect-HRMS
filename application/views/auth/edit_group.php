<div class="panel panel-flat">
    <div class="panel-heading">

    </div>

    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <fieldset>
                    <legend class="text-semibold"><i class="icon-reading position-left"></i>Group Details</legend>

                    <?php
                    //create  form open tag
                    $form_id = array(
                        'id'=>'masterRecordForm',
                        'class'=>'form-horizontal'
                    );
                    echo form_open_multipart('',$form_id);
                    //group id
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


                    //User Group Name





                        echo "<div class=\"form-group\">";
                        echo form_label('Group Name', '', array('class' => 'col-lg-3 control-label'));
                        echo "<div class=\"col-lg-9\">";
                        $data_user_group_name = array(

                            'name' => 'group_name',
                            'id' => 'group_name',
                            'class' => 'form-control',
                            'placeholder' => 'Enter Group Name',
                            'value'=>@$group->name = (isset($group->name) && ($group->name != ''))?$group->name:''
                        );
                        echo form_input($data_user_group_name);
                        echo "</div>";
                        echo "</div>";


                    //              User Group Description
                    echo "<div class=\"form-group\">";
                    echo form_label('Group Description', '', array('class' => 'col-lg-3 control-label'));
                    echo "<div class=\"col-lg-9\">";
                    $data_user_group_description = array(

                        'name' => 'group_description',
                        'id' => 'group_description',
                        'class' => 'form-control',
                        'placeholder' => 'Enter Group Description',
                        'value'=>@$group->description = (isset($group->description) && ($group->description != ''))?$group->description:''
                    );
                    echo form_input($data_user_group_description);
                    echo "</div>";
                    echo "</div>";

                    ?>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">See All Data</label>
                        <div class="col-lg-9">
                            <input type="checkbox" name="see_all_data" value="1" id="see_all_data"
                                <?= isset($group->see_all_data) && $group->see_all_data == 1 ? "checked" : '' ?>
                            >
                        </div>
                    </div>

                </fieldset>
            </div>


                <div class="col-md-6">
                    <fieldset>
                        <legend class="text-semibold"><i class=""></i> Access Permissons</legend>

                        <div class="panel-body">
                            <table class="table">
                                <thead>
                                <td>Items</td>
                                <td>Permissions</td>
                                </thead>
                                <?php
                                $permArr = unserialize(@$group->permissions);
                                if(!is_array($permArr)){
                                    $permArr = array();
                                }
                                foreach($controllers_methods as $controller_name => $methods_arr){
                                    echo "<tr>";
                                    echo "<td>".$controller_name."</td>";
                                    echo "<td>";
                                    foreach($methods_arr as $method){
                                        $methodDisplay = "";
                                        $methodActual = "";
                                        if(is_array($method)){
                                            reset($method);
                                            $methodActual = key($method);
                                            $methodDisplay = $method[$methodActual];

                                        }else{
                                            $methodDisplay = $method;
                                            $methodActual = $method;
                                        }
                                        $inputName = $controller_name."|".$methodActual;
                                        $checked = "";
                                        if(in_array($inputName, $permArr)){
                                            $checked="checked";
                                        }
//                                        echo "<input type='checkbox' {$checked} name='permissions[]' value='{$inputName}'>".$methodDisplay."&nbsp;&nbsp;";
                                        echo "<div class='checkbox-custom checkbox-primary pull-xs-left'> <input  type='checkbox' {$checked} name='permissions[]' value='{$inputName}'> <label>{$methodDisplay}&nbsp;&nbsp;</label> </div>";
                                    }
                                    echo "</td></tr>";
                                }
                                ?>
                            </table>
                        </div>
                    </fieldset>
                </div>

        </div>

        <div class="text-right">
            <button type="button" class="btn btn-xs border-slate text-slate btn-flat cancel" data-dismiss="modal"
                    onclick="window.location.href='<?php echo site_url('Auth/manage_groups'); ?>'">Cancel <i class="icon-cross2 position-right"></i>
            </button>
<!--            --><?php //echo form_submit('submit', 'Submit', "class='btn btn-primary legitRipple'"); ?>
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
        CheckboxKeyGen();
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
                group_name: {
                    required: true,
                },

                group_description:{
                    required:true
                },

            },
            messages: {
                group_name: {
                    required: "Please enter  group name.",

                },
                group_description:{
                    required:"Please enter group description."
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
                                window.location.href = '<?php echo site_url('Auth/manage_groups');?>';
                            });
                        }else{
                            swal({title: "Error",text: resObj.msg,type: "error"});
                        }
                    }
                });
            }
        });
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



</script>