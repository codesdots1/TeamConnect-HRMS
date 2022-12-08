
<body>


<!-- Register Content -->
<div class="bg-white">
  <div class="content content-boxed overflow-hidden">
    <div class="row">
      <div class="col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-4 col-lg-offset-4">
        <div class="push-30-t push-20 animated fadeIn">

          <h1><?php echo lang('deleteU_heading');?></h1>
          <p><?php echo sprintf(lang('deleteU_subheading'), $user->email);?></p>

          <?php echo form_open("auth/delete/".$user->id, array("class" => "js-validation-register form-horizontal push-50-t push-50"));?>

          <div class="form-group">
            <div class="col-xs-12">
              <div class="form-material form-material-success">
                <input class="form-control" type="radio" name="confirm" value="yes" checked="checked" />
                <label for="register-email">Yes</label>
              </div>
            </div>
          </div>

          <div class="form-group">
            <div class="col-xs-12">
              <div class="form-material form-material-success">
                <input class="form-control" type="radio" name="confirm" value="no" />
                <label for="register-email">No</label>
              </div>
            </div>
          </div>


          <?php echo form_hidden($csrf); ?>
          <?php echo form_hidden(array('id'=>$user->id)); ?>

<!--          <p>--><?php //echo form_submit('submit', lang('deactivate_submit_btn'));?><!--</p>-->

          <button class="btn btn-sm btn-block btn-success" type="submit">Submit</button>

          <?php echo form_close();?>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- END Register Content -->

<script>dashboard = false;</script>