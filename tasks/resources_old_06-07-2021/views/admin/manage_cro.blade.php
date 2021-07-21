@extends('adminheader')
@section('content')
<style>
.sub_title{
    font-size: 18px;
    margin-bottom: 20px;

}
.border{
    padding: 10px;
    line-height: 3;
}
.error{
      color: #f00;
    line-height: 1;
}
.top_row{
  z-index:99999;
}
</style>
<!-- Content Header (Page header) -->
<div class="admin_content_section">  
  <div>
  <div class="table-responsive">
    <div>
      <?php
      if(Session::has('message')) { ?>
          <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
      <?php }
      ?>
    </div>      
      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 col-lg-offset-4 col-md-offset-4 padding_00 border">
        <div class="col-lg-12 text-left padding_00">
          <div class="sub_title" style="margin-bottom: 0px; ">CRO Settings :</div>
        </div>
        <div class="col-lg-8 text-left padding_00">
          <form id="form-validation-email" name="form-validation" method="POST" action="<?php echo URL::to('admin/update_cro_setting'); ?>">
                        <div class="col-lg-12" style="padding: 25px;">
                          <div>
                            <?php
                            if(Session::has('emailmessage')) { ?>
                                <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('emailmessage'); ?></p>
                            <?php }
                            ?>
                          </div>
                            <div class="form-group">
                                <label>Username</label>
                                 <input id="validation-email"
                                       class="form-control"
                                       placeholder="Enter Username"
                                       value="<?php echo $cro->username; ?>"
                                       name="username"
                                       type="text"
                                       required >                                
                            </div> 
                            <div class="form-group">
                                <label>API Key</label>
                                <input id="validation-cc-email"
                                       class="form-control"
                                       placeholder="Enter API Key"
                                       value="<?php echo $cro->api_key; ?>"
                                       name="api_key"
                                       type="text"
                                       required>                                
                            </div>
                            <div class="form-group">
                                <input type="submit" class="common_black_button" value="Update">
                            </div>
                        </div>
                      </form>
        </div>
      </div>
  </div>
</div>
</div>
@stop