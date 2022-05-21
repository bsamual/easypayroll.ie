@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>
<script src="http://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<link rel="stylesheet" href="<?php echo URL::to('assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>
body{
  background: #f5f5f5 !important;
}
.form-control[readonly]{
      background-color: #fff !important
}
.formtable tr td{
  padding-left: 15px;
  padding-right: 15px;
}
.fullviewtablelist>tbody>tr>td{
  font-weight:800 !important;
  font-size:15px !important;
}
.fullviewtablelist>tbody>tr>td a{
  font-weight:800 !important;
  font-size:15px !important;
}
.modal { overflow: auto !important;z-index: 999999;}
.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover
{
  z-index: 0 !important;
}
.ui-tooltip{
  margin-top:-50px !important;
}
.label_class{
  float:left ;
  margin-top:15px;
  font-weight:700;
}
.upload_img{
  position: absolute;
    top: 0px;
    z-index: 1;
    background: rgb(226, 226, 226);
    padding: 19% 0%;
    text-align: center;
    overflow: hidden;
}
.upload_text{
  font-size: 15px;
    font-weight: 800;
    color: #631500;
}
.field_check
{
  width:24%;
}
.import_div{
    position: absolute;
    top: 55%;
    left:30%;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
}
.selectall_div{
  position: absolute;
    top: 13%;
    left: 5%;
    border: 1px solid #000;
    padding: 12px;
    background: #ff0;
}
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
.ui-widget{z-index: 999999999}

.dropzone .dz-preview.dz-image-preview {

    background: #949400 !important;

}

.dz-message span{text-transform: capitalize !important; font-weight: bold;}

.trash_imageadd{

  cursor:pointer;

}

.dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message *{

      margin-top: 40px;

}

.dropzone .dz-preview {

  margin:0px !important;

  min-height:0px !important;

  width:100% !important;

  color:#000 !important;

    float: left;

  clear: both;

}

.dropzone .dz-preview p {

  font-size:12px !important;

}

.remove_dropzone_attach{

  color:#f00 !important;

  margin-left:10px;

}

.img_div_add{

        border: 1px solid #000;

    width: 300px;

    position: absolute !important;

    min-height: 118px;

    background: rgb(255, 255, 0);

    display:none;

}

.dropzone.dz-clickable{margin-bottom: 0px !important;}

.report_model_selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; margin-top: 20px; border-radius: 3px;  }


body.loading {
    overflow: hidden;   
}
body.loading .modal_load {
    display: block;
}
    .table thead th:focus{background: #ddd !important;}
    .form-control{border-radius: 0px;}
    .disabled{cursor :auto !important;pointer-events: auto !important}
    body #coupon {
      display: none;
    }
    @media print {
      body * {
        display: none;
      }
      body #coupon {
        display: block;
      }
    }
</style>
<script>
function popitup(url) {
    newwindow=window.open(url,'name','height=600,width=1500');
    if (window.focus) {newwindow.focus()}
    return false;
}

</script>

<style>
.error{color: #f00; font-size: 12px;}
a:hover{text-decoration: underline;}
</style>
<?php 
$admin_details = Db::table('admin')->first();
$admin_cc = $admin_details->task_cc_email;
if(!empty($_GET['import_type_new']))
{
  if(!empty($_GET['round']))
  {
    
    $filename = $_GET['filename'];

    
    $height = $_GET['height'];
    $highestrow = $_GET['highestrow'];
    $round = $_GET['round'];
    $out = $_GET['out'];

    ?>
    <div class="upload_img" style="width: 100%;height: 100%;z-index:1"><p class="upload_text">Uploading Please wait...</p><img src="<?php echo URL::to('assets/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">Finished Uploading <?php echo $height; ?> of <?php echo $highestrow; ?></p></div>

    <script>
      $(document).ready(function() {
        var base_url = "<?php echo URL::to('/'); ?>";
        window.location.replace(base_url+'/user/import_new_clients_one?filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type_new=1&out=<?php echo $out; ?>');
      })
    </script>
    <?php

  }
}
if(!empty($_GET['import_type_existing']))
{
  if(!empty($_GET['round']))
  {
    $filename = $_GET['filename'];
    $height = $_GET['height'];
    $highestrow = $_GET['highestrow'];
    $round = $_GET['round'];
    $out = $_GET['out'];
    $checkbox = $_GET['checkbox'];
    ?>
    <div class="upload_img" style="width: 100%;height: 100%;z-index:1"><p class="upload_text">Uploading Please wait...</p><img src="<?php echo URL::to('assets/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">Finished Uploading <?php echo $height; ?> of <?php echo $highestrow; ?></p></div>

    <script>
      $(document).ready(function() {
        var base_url = "<?php echo URL::to('/'); ?>";
        window.location.replace(base_url+'/user/import_existing_clients_one?filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type_existing=1&out=<?php echo $out; ?>&checkbox=<?php echo $checkbox; ?>');
      })
    </script>
    <?php

  }
}
?>
<img id="coupon" />
<div class="modal fade emailunsent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog" role="document">
    <form action="" method="post" id="emailunsent_form">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Paye MRS Email</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              <label>From</label>
            </div>
            <div class="col-md-9">
              <select name="from_user" id="from_user" class="form-control input-sm" value="" required>
                  <option value="">Select User</option>
                  <?php
                    $users = DB::table('user')->where('user_status',0)->where('disabled',0)->where('email','!=', '')->orderBy('lastname','asc')->get();
                    if(count($users))
                    {
                      foreach($users as $user)
                      {
                          ?>
                            <option value="<?php echo trim($user->email); ?>"><?php echo $user->lastname.' '.$user->firstname; ?></option>
                          <?php
                      }
                    }
                  ?>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>To</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="to_user" id="to_user" class="form-control input-sm" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>CC</label>
            </div>
            <div class="col-md-9">
              <?php 
                $admin_details = Db::table('admin')->first();
                $admin_cc = $admin_details->p30_cc_email;
              ?>
              <input type="text" name="cc_unsent" class="form-control input-sm" id="cc_unsent" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>Subject</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="subject_unsent" class="form-control input-sm subject_unsent" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Message</label>
            </div>
            <div class="col-md-12">
              <textarea name="message_editor" id="editor_2"></textarea>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hidden_email_task_id" id="hidden_email_task_id" value="">
        <input type="button" class="btn btn-primary common_black_button email_unsent_button" value="Send Email">
      </div>
    </div>
    </form>
  </div>
</div>
<div class="modal fade alert_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:999999999">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Alert</h5>
        </div>
        <div class="modal-body">
          You are about to rename all the Attachment Files, Do you wish to continue?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary common_black_button yes_hit">Yes</button>
            <button type="button" class="btn btn-primary common_black_button no_hit">No</button>
        </div>
      </div>
    </div>
</div>
<div class="modal fade other_client_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Other Clients</h4>
            <br/>
            
        </div>
        <div class="modal-body" style="height: 400px; overflow-y: scroll;">
          <div class="panel-group">
            <div class="col-lg-9">
              <div class="form-title">Choose Client</div>
              <input type="text" class="form-control client_search_class" placeholder="Enter Client Name" name="">
              <input type="hidden" id="client_search" name="" />
              <input type="hidden" id="select_type" name="" />
              <input type="hidden" id="current_client_id" name="" />
              
            </div>              
            <div class="col-lg-3" style="padding: 15px 0px 0px 0px; ">
              <input type="button" class="common_black_button other_submit"  value="Submit">
            </div>
              
            </div>
            <table class="table">
              <thead>
              <tr style="background: #fff;">
                   <th width="5%" style="text-align: left;">S.No</th>                   
                  <th style="text-align: left;">Client ID</th>
                  <th style="text-align: left;">First Name</th>    
                  <th style="text-align: left;">Company Name</th>                         
              </tr>
              </thead>                            
              <tbody>
                <?php
                $output='';
                $i=1;
                if(count($clientlist)){
                  foreach ($clientlist as $client) {
                    if($client->active == 1){
                      $color = 'style="color:#26BD67"';
                    }
                    elseif($client->active == 2){
                      $color = 'style="color:#FF0000"';
                    }
                    $output.='
                    <tr>
                      <td '.$color.'>'.$i.'</td>
                      <td '.$color.'>'.$client->client_id.'</td>
                      <td '.$color.'>'.$client->firstname.'</td>
                      <td '.$color.'>'.$client->company.'</td>
                    </tr>';
                    $i++;
                  }
                  
                }
                else{
                  $output='<tr><td colspan="4">Empty</td></tr>';
                }
                echo $output;
                ?>
              

              </tbody>
          </table>
        </div>
        <div class="modal-footer">            
            
        </div>
      </div>
  </div>
</div>

<div class="modal fade partner_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Personal Acquaintance of Partner</h4>           
            
        </div>
        <div class="modal-body" >
          <div class="panel-group">
            <div class="form-title">Select Partner</div>
              <select class="form-control" id="user_type">
                <option value="">Select Partner</option>
                <?php


                $resultuser='';
                if(count($userlist)){
                  foreach ($userlist as $user) {
                    $resultuser.='<option value='.$user->user_id.'>'.$user->lastname.' '.$user->firstname.'</option>';
                  }
                }
                echo $resultuser;
                ?>
              </select>
              
              <input type="hidden" id="select_type2" name="" />
              <input type="hidden" id="partner_current_client_id" name="" />
            
              
            </div>
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button partner_submit"  value="Submit">
        </div>
      </div>
  </div>
</div>

<div class="modal fade reply_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Reply to Advert / Walk in</h4>
                       
        </div>
        <div class="modal-body" >
          <div class="panel-group">
            <div class="form-title">Enter Notes</div>
              <textarea class="form-control" placeholder="Enter Notes" id="reply_note" style="height: 200px;"></textarea>
              
              <input type="hidden" id="select_type3" name="" />
              <input type="hidden" id="note_current_client_id" name="" />
            
              
            </div>
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button note_submit"  value="Submit">
        </div>
      </div>
  </div>
</div>

<div class="modal fade bank_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Bank</h4>
                        
        </div>
        <div class="modal-body" >
          <form id="bank_form">
          <div class="form-group">
            BanK Name
              <input type="text" name="bank_name" class="form-control"  id="bank_name" placeholder="Bank Name" required>
            </div>
            <div class="form-group">Bank Account Name
              <input type="text" name="account_name" class="form-control" id="account_name" placeholder="Bank Account Name" required>
            </div>
            <div class="form-group">Bank Account Number
              <input type="text" name="account_number" class="form-control" id="account_number" placeholder="Bank Account Number" required>
            </div>
            
            <input type="hidden" id="bank_current_client_id" name="" />
          </form>
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button bank_submit"  value="Add Bank">
        </div>
      </div>
  </div>
</div>
<div class="modal fade trade_details_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Trade Details</h4>
                        
        </div>
        <div class="modal-body" >
          <form id="trade_form">
          <div class="form-group">
            Products & Services
              <input type="text" name="products_services" class="form-control"  id="products_services" placeholder="Products & Services" required>
            </div>
            <div class="form-group">Transaction Type
              <input type="text" name="transaction_type" class="form-control" id="transaction_type" placeholder="Transaction Type" required>
            </div>
            <div class="form-group">Risk Factors
              <input type="text" name="risk_factors" class="form-control" id="risk_factors" placeholder="Risk factors" required>
            </div>
            <div class="form-group">Geo Area of Operation
              <input type="text" name="geo_area" class="form-control" id="geo_area" placeholder="Geo Area of Operation" required>
            </div>
            <input type="hidden" id="trade_current_client_id" name="" />
          </form>
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button trade_submit"  value="Add Trade details">
        </div>
      </div>
  </div>
</div>

<div class="modal fade bank_detail_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="width:60%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title bank_company_name">Add Bank</h4>           
            
        </div>
        <div class="modal-body">

          <table class="display nowrap fullviewtablelist"  id="bank_expand">
            <thead>
              <th>#</th>
              <th>Bank Name</th>
              <th>Account Name</th>
              <th>Account Number</th>
            </thead>
            <tbody id="bank_detail_body">
              
            </tbody>
          </table>
          
        </div>
        <div class="modal-footer">            
            
        </div>
      </div>
  </div>
</div>


<div class="modal fade review_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title bank_company_name">Review</h4>           
            
        </div>
        <div class="modal-body">
          <form id="review_form">
            <div class="form-group">
              Select Date
              <label class="input-group datepicker-only-init_date_received">
                  <input type="text" class="form-control review_date" id="review_date" placeholder="Select Date" name="review_date" style="font-weight: 500;" required />
                  <span class="input-group-addon">
                      <i class="glyphicon glyphicon-calendar"></i>
                  </span>
              </label>
            </div>
            <div class="form-group">
              Review By
              <textarea class="form-control" id="reivew_filed" name="reivew_filed" required></textarea>
            </div>
            <input type="hidden" id="review_current_client_id" name="reviewed_by">
          </form>
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button review_submit"  value="Review">
        </div>
      </div>
  </div>
</div>
<div class="modal fade notify_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="width:80%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel" style="float:left">Request/Manage ID Files</h4>
        <input type="button" name="clear_selection" class="btn btn-primary common_black_button" id="clear_selection" value="Clear Selection" style="float:right;margin-right:30px;">
        <input type="checkbox" name="notify_radio" id="identity_received"><label for="identity_received" style="float:right;margin-right:30px;">Hide Accounts with ID</label>
        <input type="checkbox" name="notify_radio" id="inactive_clients"><label for="inactive_clients" style="float:right;margin-right:60px;">Inactive Clients</label>
      </div>
      <div class="modal-body notify_place_div modal_max_height">
      </div>
      <div class="modal-footer">
        <input type="hidden" id="notify_type" value="">
        <input type="button" class="btn btn-primary common_black_button" id="email_notify" value="Email Notify Options">
      </div>
    </div>
  </div>
</div>
<div class="modal fade date_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title bank_company_name">Date Client Since</h4>           
            
        </div>
        <div class="modal-body">
          <div class="form-group">
            Select Date
            <label class="input-group datepicker-only-init_date_received">
                <input type="text" class="form-control client_date_since" placeholder="Select Date" name="received_date" style="font-weight: 500;" required />
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-calendar"></i>
                </span>
            </label>
          </div>
          <input type="hidden" id="date_since_current_client_id" name="">
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button date_submit"  value="Submit">
        </div>
      </div>
  </div>
</div>

<div class="modal fade edit_review_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title bank_company_name">Review</h4>           
            
        </div>
        <div class="modal-body">
          <div class="form-group">
            Select Date
            <label class="input-group datepicker-only-init_date_received">
                <input type="text" class="form-control review_date_edit" id="review_date_edit" placeholder="Select Date" name="received_date" style="font-weight: 500;" required />
                <span class="input-group-addon">
                    <i class="glyphicon glyphicon-calendar"></i>
                </span>
            </label>
          </div>
          <div class="form-group">
            Review By
            <textarea class="form-control" id="reivew_filed_edit"></textarea>
          </div>
          <input type="hidden" id="review_current_client_id_edit" name="">
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button review_edit_submit"  value="Review">
        </div>
      </div>
  </div>
</div>


<div class="modal fade report_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Report</h4>
            <div class="col-lg-12 report_model_selectall">

              <div class="col-lg-2" style="padding: 0px;"><input type="checkbox" class="select_all_class" id="select_all_class" value="1" style="padding-top: 20px;"><label for="select_all_class" style="font-size: 14px; font-weight: normal;">Select all</label></div>

            </div>
            
        </div>
        <div class="modal-body" style="height: 400px; overflow-y: scroll;">          
            <table class="table own_table_white">
              <thead>
              <tr style="background: #fff;">
                  <th width="5%" style="text-align: left;">S.No</th>                   
                  <th></th>
                  <th style="text-align: left;">Client ID</th>
                  <th style="text-align: left;">First Name</th>    
                  <th style="text-align: left;">Company Name</th>                         
              </tr>
              </thead>                            
              <tbody>
                <?php
                $output='';
                $i=1;
                if(count($clientlist)){
                  foreach ($clientlist as $client) {
                    if($client->active == 1){
                      $color = 'style="color:#26BD67"';
                    }
                    elseif($client->active == 2){
                      $color = 'style="color:#FF0000"';
                    }
                    $output.='
                    <tr>
                      <td '.$color.'>'.$i.'</td>
                      <td><input type="checkbox" name="report_client" class="select_client" data-element="'.$client->client_id.'" value="'.$client->client_id.'" /><label>&nbsp;</label></td>
                      <td '.$color.'>'.$client->client_id.'</td>
                      <td '.$color.'>'.$client->firstname.'</td>
                      <td '.$color.'>'.$client->company.'</td>
                    </tr>';
                    $i++;
                  }
                  
                }
                else{
                  $output='<tr><td colspan="4">Empty</td></tr>';
                }
                echo $output;
                ?>
              

              </tbody>
          </table>
        </div>
        <div class="modal-footer">            
            <input type="button" class="common_black_button" id="save_as_pdf" value="Save as PDF">
        </div>
      </div>
  </div>
</div>



<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
    <h4 class="col-lg-12 padding_00 new_main_title">
                AML System                  
            </h4>        
            <div class="col-lg-5 padding_00">
            
            <?php $check_incomplete = Db::table('user_login')->where('userid',1)->first(); if($check_incomplete->aml_incomplete == 1) { $inc_checked = 'checked'; } else { $inc_checked = ''; } ?>
                <input type="checkbox" name="show_incomplete" id="show_incomplete" value="1" <?php echo $inc_checked?> ><label for="show_incomplete">Hide Deactivated Accounts</label>
                  <div class="select_button" style=" margin-left: 10px;text-align: right;margin-top:10px;margin-bottom: 20px">
                    <ul>
                    <li><a class="standard_file_name_cls" href="javascript:" style="font-size: 13px; font-weight: 500;">Rename ID Attachment files</a></li>
                    <li><a href="javascript:" style="font-size: 13px; font-weight: 500;" data-toggle="modal" data-target=".report_modal">Report</a></li>
                    <li><a href="javascript:" class="notify_aml" id="notify_aml" style="font-size: 13px; font-weight: 500;">Request/Manage ID Files</a></li>
                  </ul>
                </div>
              </div>
          
  <div class="table-responsive" style="max-width: 100%; float: left;margin-bottom:5px; margin-top:55px">
  </div>
  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
     <?php }   
    ?>
    </div> 

<style type="text/css">
.refresh_icon{margin-left: 10px;}
.refresh_icon:hover{text-decoration: none;}
.datepicker-only-init_date_received, .auto_save_date{cursor: pointer;}
.bootstrap-datetimepicker-widget{width: 300px !important;}
.image_div_attachments P{width: 100%; height: auto; font-size: 13px; font-weight: normal; color: #000;}
</style>

<table class="display nowrap fullviewtablelist own_table_white " id="aml_expand" width="100%" style="max-width: 100%;">
                        <thead>
                        <tr style="background: #fff;">
                             <th width="2%" style="text-align: left;">S.No</th>
                            <th style="text-align: left;">Client ID</th>
                            <th style="text-align: left;">Company</th>
                            <th style="text-align: left;">First Name</th>
                            <th style="text-align: left;">Surname</th>
                            <th width="10%" style="text-align: left;">Client Source</th>
                            <th style="text-align: left;">Date Client Since</th>
                            <th style="text-align: left;">Client Identity</th> 
                            <th style="text-align: left;">Trade Details</th> 
                            <th style="text-align: left;">Email Unsent</th> 
                            <th style="text-align: left;">Bank Accounts</th>                             
                            <th style="text-align: left;">File review</th>
                            <th style="text-align: left;">Risk Category</th>                            
                        </tr>
                        </thead>                            
                        <tbody id="clients_tbody">
                        <?php
                            $i=1;
                            if(count($clientlist)){              
                              foreach($clientlist as $key => $client){

                                
                                
                                  $disabled='';
                                  if($client->active != "")
                                  {
                                    if($client->active == 2)
                                    {
                                      $disabled='disabled';
                                    }
                                    $check_color = DB::table('cm_class')->where('id',$client->active)->first();
                                    $style="color:#".$check_color->classcolor."";
                                  }
                                  else{
                                    $style="color:#000";
                                  }

                                  $aml_system = DB::table('aml_system')->where('client_id', $client->client_id)->first();

                                  if(count($aml_system)){
                                    $risk_select = $aml_system->risk_category;                                    
                                  }
                                  else{
                                    $risk_select = '';
                                  }   

                                  $aml_attachement = DB::table('aml_attachment')->where('client_id', $client->client_id)->get();

                                  $output_attached='';
                                  if(count($aml_attachement)){
                                    foreach ($aml_attachement as $attached) {
                                      if($attached->standard_name == "")
                                      {
                                        $output_attached.='
                                        <a href="'.URL::to('/'.$attached->url.'/'.$attached->attachment).'" download>'.$attached->attachment.'</a><i class="fa fa-trash delete_attached" style="cursor:pointer; margin-left:10px; color:#000;" data-element="'.$attached->id.'"></i><br/>';
                                      }
                                      else{
                                        $output_attached.='
                                        <a href="'.URL::to('/'.$attached->url.'/'.$attached->attachment).'" download="'.$attached->standard_name.'">'.$attached->standard_name.'</a><i class="fa fa-trash delete_attached" style="cursor:pointer; margin-left:10px; color:#000;" data-element="'.$attached->id.'"></i><br/>';
                                      }
                                    }
                                  }
                                  else{
                                    $output_attached.='';
                                  }

                                  if(count($aml_attachement) != '' ){
                                    $image_plus_sapce='margin-top:10px;';
                                  }
                                  else{
                                    $image_plus_sapce='margin-top:0px;';
                                  }


                                  if(count($aml_system)){
                                    if($aml_system->review == 1){
                                      $output_reveiw = 'Date:'.date('d-M-Y', strtotime($aml_system->review_date)).'</br/>Review By: '.$aml_system->file_review.'<br/><a href="javascript:"><i class="fa fa-pencil-square edit_review" data-element="'.$client->client_id.'"></i></a><a href="javascript:"  style="margin-left:10px;"><i class="fa fa-trash delete_review" data-element="'.$client->client_id.'"></i></a>';
                                    }
                                    else{
                                      $output_reveiw = '
                                      <div class="select_button" style=" margin-left: 10px;">
                                        <ul>                                    
                                        <li><a href="javascript:" class="review_by" data-element="'.$client->client_id.'" style="font-size: 13px; font-weight: 500;">Review By</a></li>
                                      </ul>
                                    </div>';
                                    }  
                                  }  
                                  else{
                                    $output_reveiw = '
                                    <div class="select_button" style=" margin-left: 10px;">
                                      <ul>                                    
                                      <li><a href="javascript:" class="review_by" data-element="'.$client->client_id.'" style="font-size: 13px; font-weight: 500;">Review By</a></li>
                                    </ul>
                                  </div>';
                                  }                           


                                  if(count($aml_system)){
                                    if($aml_system->client_source == 1){
                                      $client_details = DB::table('cm_clients')->where('client_id', $aml_system->client_source_detail)->first();
                                      $client_source ='<a href="javascript:" data-text="Other Client - '.$client_details->company.' - '.$client_details->firstname.' '.$client_details->surname.'" class="download_client_source"> Other Client - '.$client_details->company.' - '.$client_details->firstname.' '.$client_details->surname.'.txt</a>

                                      <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Edit Client Source"><i class="fa fa-edit refresh_client_source" data-element='.$aml_system->client_id.'></i></a>

                                      <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Delete Client Source"><i class="fa fa-trash refresh_client_source" data-element='.$aml_system->client_id.'></i></a>';
                                    }
                                    elseif($aml_system->client_source == 2){
                                      $user_details = DB::table('user')->where('user_id', $aml_system->client_source_detail)->first();                                      
                                      $client_source ='<a href="javascript:" data-text="Partner - '.$user_details->lastname.' '.$user_details->firstname.'" class="download_client_source">Partner - '.$user_details->lastname.' '.$user_details->firstname.'.txt</a>


                                      <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Edit Client Source"><i class="fa fa-edit refresh_client_source" data-element='.$aml_system->client_id.'></i></a>

                                      <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Delete Client Source"><i class="fa fa-trash refresh_client_source" data-element='.$aml_system->client_id.'></i></a>';
                                    }
                                    elseif($aml_system->client_source == 3){
                                      
                                      $client_source = '<a href="javascript:" data-text="Note - '.$aml_system->client_source_detail.'" class="download_client_source">Note - '.$aml_system->client_source_detail.'.txt</a>

                                      <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Edit Client Source"><i class="fa fa-edit refresh_client_source" data-element='.$aml_system->client_id.'></i></a>

                                      <a href="javascript:" class="refresh_icon" data-toggle="tooltip" data-original-title="Delete Client Source"><i class="fa fa-trash refresh_client_source" data-element='.$aml_system->client_id.'></i></a>';
                                    }
                                    else{
                                      $client_source='<input type="radio" name="client_source" class="other_client" value="1" id="other_client_'.$client->client_id.'" data-element="'.$client->client_id.'" data-toggle="modal" data-target=".other_client_modal" value="1"><label for="other_client_'.$client->client_id.'">Other Client</label><br/>
                                  <input type="radio" name="client_source" class="partner_class" data-toggle="modal" data-target=".partner_modal" value="2"  data-element="'.$client->client_id.'" id="personal_partner_'.$client->client_id.'"><label for="personal_partner_'.$client->client_id.'">Personal Acquaintance of Partner</label><br/>
                                  <input type="radio" name="client_source"  class="reply_class" data-toggle="modal" data-target=".reply_modal"  value="3" data-element="'.$client->client_id.'" id="reply_note_'.$client->client_id.'"><label for="reply_note_'.$client->client_id.'">Reply to Advert / Walk in</label>';
                                    }
                                  }
                                  else{

                                    $client_source='<input type="radio" name="client_source" class="other_client" value="1" id="other_client_'.$client->client_id.'" data-element="'.$client->client_id.'" data-toggle="modal" data-target=".other_client_modal" value="1"><label for="other_client_'.$client->client_id.'">Other Client</label><br/>
                                  <input type="radio" name="client_source" class="partner_class" data-toggle="modal" data-target=".partner_modal" value="2"  data-element="'.$client->client_id.'" id="personal_partner_'.$client->client_id.'"><label for="personal_partner_'.$client->client_id.'">Personal Acquaintance of Partner</label><br/>
                                  <input type="radio" name="client_source"  class="reply_class" data-toggle="modal" data-target=".reply_modal"  value="3" data-element="'.$client->client_id.'" id="reply_note_'.$client->client_id.'"><label for="reply_note_'.$client->client_id.'">Reply to Advert / Walk in</label>';


                                  }


                                  $aml_bank = DB::table('aml_bank')->where('client_id', $client->client_id)->first();
                                  $aml_count = DB::table('aml_bank')->where('client_id', $client->client_id)->count();

                                  if(count($aml_bank)){
                                    $bank_output='<a href="javascript:" class="bank_detail_class" data-element="'.$client->client_id.'">'.$aml_count.'</a>
                                    <a href="javascript:" class="bank_class" data-toggle="modal" data-target=".bank_modal" ><i class="fa fa-plus add_bank" title="Add Bank" data-element="'.$client->client_id.'" style="margin-left:10px;"></i></a>
                                    ';
                                  }
                                  else{
                                    $bank_output='
                                    <a href="javascript:" class="bank_class" data-toggle="modal" data-target=".bank_modal" ><i class="fa fa-plus add_bank" title="Add Bank" data-element="'.$client->client_id.'"></i></a>
                                    ';
                                  }

                                  $cli_det = DB::table('cm_clients')->where('client_id', $client->client_id)->first();
                                  if(count($cli_det))
                                  {
                                    if($cli_det->client_added == "")
                                    {
                                      $output_client_since = 'No Date Set';
                                    }
                                    else{
                                      $explode_date = explode("/",$cli_det->client_added);
                                      $explode_hyphen_date = explode("-",$cli_det->client_added);
                                      if(count($explode_date) > 1)
                                      {
                                        $client_added  = DateTime::createFromFormat('d/m/Y', $cli_det->client_added);
                                        $client_added_since = $client_added->format('d-M-Y');
                                      }
                                      elseif(count($explode_hyphen_date) > 1)
                                      {
                                        $client_added  = DateTime::createFromFormat('d-m-Y', $cli_det->client_added);
                                        $client_added_since = $client_added->format('d-M-Y');
                                      }
                                      else{
                                        $client_added_since  = $cli_det->client_added;
                                      }

                                      $output_client_since = $client_added_since;
                                    }
                                  }
                                  else{
                                    $output_client_since = 'No Date Set';
                                  }
                                  
                          ?>
                            <tr class="edit_task <?php echo $disabled; ?>" style="<?php echo $style; ?>"  id="clientidtr_<?php echo $client->id; ?>">
                                <td style="<?php echo $style; ?>"><?php echo $i; ?></td>
                                <td align="left"><a href="javascript:" id="<?php echo base64_encode($client->id); ?>" class="invoice_class" style="<?php echo $style; ?>"><?php echo $client->client_id; ?></a></td>
                                <td align="left"><a href="javascript:" id="<?php echo base64_encode($client->id); ?>" class="invoice_class" style="<?php echo $style; ?>"><?php echo ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company; ?></a></td>
                                <td align="left"><a href="javascript:" id="<?php echo base64_encode($client->id); ?>" class="invoice_class" style="<?php echo $style; ?>"><?php echo $client->firstname; ?></a></td>
                                <td align="left"><a href="javascript:" id="<?php echo base64_encode($client->id); ?>" class="invoice_class" style="<?php echo $style; ?>"><?php echo $client->surname; ?></a></td>
                                
                                <td align="left" style="<?php echo $style; ?>" id="client_source_<?php echo $client->client_id?>">
                                  <?php echo $client_source?>
                                </td>
                                <td align="left" id="client_since_<?php echo $client->client_id?>" style="<?php echo $style; ?>">
                                  <?php echo $output_client_since?>
                                </td>
                                <td align="left" >
                                  
                                  <div id="client_identity_<?php echo $client->client_id?>"><?php echo $output_attached?></div>
                                  
                                    <i class="fa fa-plus fa-plus-add" style="cursor: pointer; color: #000; <?php echo $image_plus_sapce?>" aria-hidden="true" title="Add Attachment" data-element="<?php echo $client->client_id?>"></i> 

                 <p id="attachments_text" style="display:none; font-weight: bold;">"Files Attached:</p>

                    <div id="add_attachments_div">

                    

                    </div>

                                    

                <div class="img_div img_div_add" id="img_div_<?php echo $client->client_id?>" style="z-index:9999999; margin-left: -120px; min-height: 275px">
                <form name="image_form" style="margin-bottom: 0px !important;" id="image_form" action="" method="post" enctype="multipart/form-data" style="text-align: left;">                 

                </form>                

                <div class="image_div_attachments">
                  <p>You can only upload maximum 300 files <br/>at a time. If you drop more than 300 <br/>files then the files uploading process<br/> will be crashed. </p>
                  <form action="<?php echo URL::to('user/aml_upload_images_add?client_id='.$client->client_id); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload1" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">

                      <input name="_token" type="hidden" value="<?php echo $client->client_id?>">                  

                  </form>                

                </div>
                <div class="select_button" style=" margin-top: 10px;">
                    <ul>                                    
                    <li><a href="javascript:" class="image_submit" data-element="<?php echo $client->client_id?>" style="font-size: 13px; font-weight: 500;">Submit</a></li>
                  </ul>
                </div>

               </div>
                                </td>
                                <td id="client_trade_<?php echo $client->client_id?>" style="text-align:center; width: 180px; ">
                                  <?php 
                                  if(count($aml_system)){
                                  if($aml_system->trade_details != "")
                                  {
                                    ?>
                                    <a href="<?php echo URL::to('uploads/aml_trade_details').'/'.$client->client_id.'/'.$aml_system->trade_details; ?>" download><?php echo $aml_system->trade_details; ?></a><br/>
                                    <a href="javascript:" class="fa fa-edit trade_details_edit trade_details_<?php echo $client->client_id; ?>" data-element="<?php echo $client->client_id; ?>"></a>

                                    
                                    <?php
                                  }
                                  else{
                                    ?>
                                     <a href="javascript:" class="fa fa-plus trade_details trade_details_<?php echo $client->client_id; ?>" data-element="<?php echo $client->client_id; ?>"></a>
                                    <?php
                                  }
                                }
                                  else{
                                    ?>
                                     <a href="javascript:" class="fa fa-plus trade_details trade_details_<?php echo $client->client_id; ?>" data-element="<?php echo $client->client_id; ?>"></a>
                                    <?php
                                  }
                                  ?>
                                </td>
                                <?php
                                if(count($aml_system))
                                {
                                  if($aml_system->last_email_sent == "0000-00-00 00:00:00") { $email_sent = ''; }
                                  else{ $email_sent = date('d M Y @ H:i', strtotime($aml_system->last_email_sent)); }
                                }
                                else{
                                  $email_sent = '';
                                }
                                ?>
                                <td style="text-align:center; width: 180px; "><a href="javascript:" class="fa fa-envelope email_unsent email_unsent_<?php echo $client->client_id; ?>" data-element="<?php echo $client->client_id; ?>"></a><br><?php echo $email_sent; ?><br></td>
<!-- 
                                <td align="center"><a href="javascript:" id="<?php echo base64_encode($client->client_id); ?>" class="payroll_class" style="<?php echo $style; ?>">Payroll Tasks</a></td> -->

                                <td style="<?php echo $style; ?>" align="left"  id="client_bank_<?php echo $client->client_id?>">
                                  <?php echo $bank_output ?>
                                </td>
                                <td style="<?php echo $style; ?>" align="left"   id="review_<?php echo $client->client_id?>">
                                  <?php echo $output_reveiw?>
                                  
                                </td>
                                <td style="<?php echo $style; ?>" align="left">
                                  <select class="form-control risk_class" data-element="<?php echo $client->client_id?>" >
                                    <option value="1" <?php if($risk_select == 1){echo 'selected';}else{echo'';}?>>Green</option>
                                    <option value="2" <?php if($risk_select == 2){echo 'selected';}else{echo'';}?>>Yellow</option>
                                    <option value="3" <?php if($risk_select == 3){echo 'selected';}else{echo'';}?>>Red</option>
                                  </select>
                                  
                                </td>
                            </tr>
                            <?php
                              $i++;
                              }              
                            }
                            if($i == 1)
                            {
                              echo'<tr><td colspan="11" align="center">Empty</td></tr>';
                            }
                          ?> 
                        </tbody>
                    </table>
</div>
    <!-- End  -->
<div class="main-backdrop"><!-- --></div>
<div id="print_image">
    
</div>
<div id="report_pdf_type_two" style="display:none">
  <style>
  .table_style {
      width: 100%;
      border-collapse:collapse;
      border:1px solid #c5c5c5;
  }
  </style>
  <table class="table_style">
    <thead>
      <tr>
      <td style="text-align: left;border:1px solid #000;">#</td>
      <td style="text-align: left;border:1px solid #000;">Client Id</td>
      <td style="text-align: left;border:1px solid #000;">Company</td>
      <td style="text-align: left;border:1px solid #000;">First Name</td>
      <td style="text-align: left;border:1px solid #000;">Surname</td>
      <td style="text-align: left;border:1px solid #000;">Client Source</td>
      <td style="text-align: left;border:1px solid #000;">Date Client Since</td>
      <td style="text-align: left;border:1px solid #000;">Client Identity</td>      
      <td style="text-align: left;border:1px solid #000;">Bank Account</td>
      <td style="text-align: left;border:1px solid #000;">File Review</td>
      <td style="text-align: left;border:1px solid #000;">Risk Category</td>
      </tr>
    </thead>
    <tbody id="report_pdf_type_two_tbody">

    </tbody>
  </table>
</div>



<div id="report_pdf_type_two_invoice" style="display:none">
  <style>
  .table_style {
      width: 100%;
      border-collapse:collapse;
      border:1px solid #c5c5c5;
  }
  </style>

  <h3 id="pdf_title_inivoice" style="width: 100%; text-align: center; margin: 15px 0px; float: left;">List of Invoices issued to <span class="invoice_filename"></span></h3>  

  <table class="table_style">
    <thead>
      <tr>
      <th width="2%" style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">S.No</th>
      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Invoice ID</th>
      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Date</th>
      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Client ID</th>
      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px ">Company Name</th>
      <th style="text-align: right; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px ">Net</th>
      <th style="text-align: right; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">VAT</th>
      <th style="text-align: right; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Gross</th>
      </tr>
    </thead>
    <tbody id="report_pdf_type_two_tbody_invoice">

    </tbody>
  </table>
</div>





<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">

<script type="text/javascript">

$(".image_file").change(function(){
  var lengthval = $(this.files).length;
  var htmlcontent = '<label class="attachments_label">Attachments : </label>';
  for(var i=0; i<= lengthval - 1; i++)
  {
    var sno = i + 1;
    if(htmlcontent == "")
    {
      htmlcontent = '<p class="attachment_p">'+sno+'. '+this.files[i].name+'</p>';
    }
    else{
      htmlcontent = htmlcontent+'<p class="attachment_p">'+sno+'. '+this.files[i].name+'</p>';
    }
  }
  $(this).parent().find(".image_div_attachments").html(htmlcontent);
});
$(".image_file_add").change(function(){
  var lengthval = $(this.files).length;
  var htmlcontent = '';
  var attachments = $('#add_attachments_div').html();
  for(var i=0; i<= lengthval - 1; i++)
  {
    var sno = i + 1;
    if(htmlcontent == "")
    {
      htmlcontent = '<p class="attachment_p">'+this.files[i].name+'</p>';
    }
    else{
      htmlcontent = htmlcontent+'<p class="attachment_p">'+this.files[i].name+'</p>';
    }
  }
  $('#add_attachments_div').html(attachments+' '+htmlcontent);
  $("#attachments_text").show();
  $(".img_div").hide();
});


fileList = new Array();
Dropzone.options.imageUpload1 = {
    acceptedFiles: null,
    maxFilesize:50000,
    timeout: 10000000,
    dataType: "HTML",
    parallelUploads: 1,
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading");
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"'>Remove</a></p>";
            }
            else{
              $("#attachments_text").show();
              $("#add_attachments_div").append("<p>"+obj.filename+" </p>");
              $(".img_div").each(function() {
                $(this).hide();
              });
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            $("body").removeClass("loading");
          }
         });
        this.on("error", function (file) {
            console.log(file);
            $("body").removeClass("loading");
      });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
      });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};
Dropzone.options.imageUpload2 = {
    acceptedFiles: null,
    maxFilesize:50000,
    timeout: 10000000,
    dataType: "HTML",
    parallelUploads: 1,
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading");
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"'>Remove</a></p>";
            }
            else{
              $("#attachments_text").show();
              $("#add_attachments_div").append("<p>"+obj.filename+" </p>");
              $(".img_div").each(function() {
                $(this).hide();
              });
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            $("body").removeClass("loading");
          }
         });
        this.on("error", function (file) {
            console.log(file);
            $("body").removeClass("loading");
      });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
      });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};

<?php

if(!empty($_GET['divid']))
{
  $divid = $_GET['divid'];
  ?>
  $(function() {
    $("body").addClass("loading");
    setTimeout(function(){ 
      if($("#<?php echo $divid; ?>").length > 0)
      {
        $(document).scrollTop( $("#<?php echo $divid; ?>").offset().top - parseInt(150) ); 
        <?php if(Session::get('edit_message')){ ?>
          $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:green><?php echo Session::get('edit_message'); ?></p>",width:"30%",fixed:true});
        <?php } else if(Session::get('edit_error')) { ?>
          $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:#f00><?php echo Session::get('edit_error'); ?></p>",width:"30%",fixed:true});
        <?php } else if(isset($_GET['activate'])) { ?>
            $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:green>Client Activated Successfully.</p>",width:"30%",fixed:true});
        <?php } else if(isset($_GET['deactivate'])) { ?>
            $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:#f00>Client Deactivated Successfully.</p>",width:"30%",fixed:true});
        <?php } else if(isset($_GET['status_pin_invalid'])) { ?>
            $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:#f00>Crypt Pin You have entered is Incorrect.</p>",width:"30%",fixed:true});
        <?php } ?>
      }
      $("body").removeClass("loading"); 
       window.history.replaceState(null, null, "<?php echo URL::to('user/client_management'); ?>");
    }, 5000); 
  });
  <?php
} ?>
// $(window).scroll(function(e){
//   var len = $(".load_more").length;
//   if(len > 0)
//   {
//     var off = $(".load_more").offset();
//     var scroll = $(window).scrollTop();
//     var h = screen.height - parseInt(220);
//     var screen_height = $(document).height();

//     var final_scroll = parseInt(scroll) + parseInt(h);
//     if(off.top <= final_scroll)
//     {
//       $("body").addClass("loading");
//       doSomething();
//     }
//   }
// });
// function doSomething() { 
//     var paginate = $("#pagination").val();
//     var count = parseInt(paginate) + parseInt(1);
//     var base_url = "<?php echo URL::to('user/clientmanagement_paginate'); ?>";

//     $("#pagination").val(count);
//     $.ajax({
//       url: base_url,
//       data: {page:count},
//       type: "get",
//       success:function(result){
//         $("body").removeClass("loading");
//         $("body").find(".load_more").removeClass("load_more");
//         $("#clients_tbody").append(result);
//         var table = $('#client_expand').DataTable();
 
//         table.fixedHeader.adjust();
//       }
//     });
//   }
$(document).ready(function(){
    $('#images').on('change',function(){
      $("body").addClass("loading");
      setTimeout(function(){ 
          $('#multiple_upload_form').ajaxForm({
              //display the uploaded images
              target:'#images_preview',
              beforeSubmit:function(e){
                  $(".attachments").html(e);
                   $("body").removeClass("loading");
              },
              success:function(e){
                  $(".attachments").html(e);
                   $("body").removeClass("loading");
              },
              error:function(e){
                  $(".attachments").html(e);
                   $("body").removeClass("loading");
              }
          }).submit();
      }, 2000);
      
  });
});
$(document).ready(function() {
  $('[data-toggle="tooltip"]').tooltip(); 
  if($("#show_incomplete").is(':checked'))
  {
    $(".edit_task").each(function() {
        if($(this).hasClass('disabled'))
        {
          $(this).hide();
        }
    });
  }
  else{
    $(".edit_task").each(function() {
        if($(this).hasClass('disabled'))
        {
          $(this).show();
        }
    });
  }
});
$(".image_file").change(function(){

  var lengthval = $(this.files).length;

  var htmlcontent = '<label class="attachments_label">Attachments : </label>';

  for(var i=0; i<= lengthval - 1; i++)

  {

    var sno = i + 1;

    if(htmlcontent == "")

    {

      htmlcontent = '<p class="attachment_p">'+sno+'. '+this.files[i].name+'</p>';

    }

    else{

      htmlcontent = htmlcontent+'<p class="attachment_p">'+sno+'. '+this.files[i].name+'</p>';

    }

  }

  $(this).parent().find(".image_div_attachments").html(htmlcontent);

});
$(window).click(function(e) {
  if($(e.target).hasClass('notify_all_clients')){
    if($(e.target).is(":checked"))
    {
      $(".notify_option:visible").prop("checked",true);
    }
    else{
      $(".notify_option:visible").prop("checked",false);
    }
  }
  if($(e.target).hasClass('standard_file_name_cls'))
  {
    $(".alert_modal").modal("show");
  }
  if($(e.target).hasClass('yes_hit'))
  {
    $("body").addClass("loading");
    window.location.replace("<?php echo URL::to('user/standard_file_name'); ?>");
  }
  if($(e.target).hasClass('no_hit'))
  {
    $(".alert_modal").modal("hide");
  }
  if($(e.target).hasClass('download_client_source'))
  {
    var text = $(e.target).attr("data-text");
    $.ajax({
      url:"<?php echo URL::to('user/generate_aml_text_file'); ?>",
      type:"get",
      data:{text:text},
      success: function(result)
      {
        SaveToDisk("<?php echo URL::to('papers/aml_client_source'); ?>/"+result,result);
      }
    });
  }
  if(e.target.id == "inactive_clients")
  {
    $(".notify_place_div").find("tr").show();

    if($("#identity_received").is(":checked")){
      $(".notify_place_div").find(".identity_received").hide();
    }
    else{
      $(".notify_place_div").find(".identity_received").show();
    }

    if($(e.target).is(":checked")){
      $(".notify_place_div").find(".inactive").hide();
    }
    else{
      $(".notify_place_div").find(".inactive").show();
    }
  }
  if(e.target.id == "identity_received")
  {
    $(".notify_place_div").find("tr").show();
    
    if($("#inactive_clients").is(":checked")){
      $(".notify_place_div").find(".inactive").hide();
    }
    else{
      $(".notify_place_div").find(".inactive").show();
    }
    
    if($(e.target).is(":checked")){
      $(".notify_place_div").find(".identity_received").hide();
    }
    else{
      $(".notify_place_div").find(".identity_received").show();
    }
  }
  if(e.target.id == "clear_selection")
  {
    $(e.target).hide();
    $("#inactive_clients").prop("checked",false);
    $("#identity_received").prop("checked",false);
    $(".notify_place_div").find("tr").show();
  }
  // if($(e.target).hasClass('aml_notify'))
  // {
  //   if($('#inactive_clients').is(':checked') && $('#identity_received').is(':checked'))
  //   {
  //     $(".inactive").find("td:first").css({"color": "#f00 !important", "font-weight": "600"});
  //     $(".identity_received").find("td:first").css({"color": "#1000ff !important", "font-weight": "600"});
  //   }
  //   else if($('#inactive_clients').is(':checked') && !($('#identity_received').is(':checked')))
  //   {
  //     $(".identity_received").find("td:first").css({"color": "#000 !important"});
  //     $(".inactive").find("td:first").css({"color": "#f00 !important", "font-weight": "600"});
  //   }
  //   else if($('#identity_received').is(':checked') && !($('#inactive_clients').is(':checked')))
  //   {
  //     $(".inactive").find("td:first").css({"color": "#000 !important"});
  //     $(".identity_received").find("td:first").css({"color": "#1000ff !important", "font-weight": "600"});
  //   }
  //   else if(!($('#identity_received').is(':checked')) && !($('#inactive_clients').is(':checked')))
  //   {
  //     $(".inactive").find("td:first").css({"color": "#000 !important"});
  //     $(".identity_received").find("td:first").css({"color": "#000 !important"});
  //   }
  // }
  if(e.target.id == 'notify_aml')
  {
    if (Dropzone.instances.imageUpload2) Dropzone.forElement("#imageUpload2").destroy();
    if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
    $.ajax({
        url:"<?php echo URL::to('user/notify_tasks_aml'); ?>",
        type:"get",
        success: function(result) {
          $(".notify_modal").modal('show');
          $(".notify_place_div").html(result);
          setTimeout(function(){  
             CKEDITOR.replace('editor_1',
             {
              height: '150px',
             }); 

             $(".dropzone").dropzone();
          },1000);
        }
    });
  }
  if(e.target.id == 'email_notify')
  {
    var countval = $(".notify_option:checked").length;
    if(countval > 0)
    {
      $(".notify_modal").modal('hide');
      var message = CKEDITOR.instances['editor_1'].getData();
      $("body").addClass("loading");
      var emails = [];
      var toemails = '';
      var timeval = "<?php echo time(); ?>";
      $(".notify_option").each(function(i, el) {
        var id = $(el).attr('data-element');
          if($(el).is(':checked'))
          {
            var user_email = $(el).parents('tr').find(".notify_primary_email").val();
            var secondary_email = $(el).parents('tr').find(".notify_secondary_email").val();
            if(user_email != '' && typeof user_email !== 'undefined')
            {
              if($.inArray(user_email, emails) == -1)
              {
                emails.push(user_email);
                if(toemails == '')
                {
                  toemails= user_email;
                }
                else{
                  toemails = toemails+', '+user_email;
                }
              }
            }
            if(secondary_email != '' && typeof secondary_email !== 'undefined')
            {
              if($.inArray(secondary_email, emails) == -1)
              {
                emails.push(secondary_email);
                if(toemails == '')
                {
                  toemails= secondary_email;
                }
                else{
                  toemails = toemails+', '+secondary_email;
                }
              }
            }
          }
      });
      toemails = toemails+', <?php echo $admin_cc; ?>';
      var option_length = emails.length;
      $.each( emails, function( i, value ) {
          setTimeout(function(){
            $.ajax({
              url:"<?php echo URL::to('user/email_notify_aml'); ?>",
              type:"get",
              data:{email:value,message:message,toemails:toemails,timeval:timeval},
              success: function(result) {
                var keyi = parseInt(i) + parseInt(1);
                if(option_length == keyi)
                {
                  $("body").removeClass("loading");
                }
              }
            });
          },2000 + ( i * 2000 ));
      });
    }
    else{
      alert("Please choose the clients to send the email.");
    }
  }
  if($(e.target).hasClass('email_unsent'))
  {
    $("body").addClass("loading");
    if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
      CKEDITOR.replace('editor_2',
               {
                height: '150px',
               }); 
    setTimeout(function() {
            
            var client_id = $(e.target).attr('data-element');
            $.ajax({
              url:'<?php echo URL::to('user/aml_edit_email_unsent_files'); ?>',
              type:'get',
              data:{client_id:client_id},
              dataType:"json",
              success: function(result)
              {
                  $(".subject_unsent").val(result['subject']);
                  $("#to_user").val(result['to']);
                  $(".emailunsent").modal('show');
                  $("#hidden_email_task_id").val(client_id);
                  CKEDITOR.instances['editor_2'].setData(result['html']);
              }
            })
        $("body").removeClass("loading");
    },7000);
  }
  if($(e.target).hasClass('email_unsent_button'))
  {
    if($("#emailunsent_form").valid())
    {
      $("body").addClass("loading");
      var content = CKEDITOR.instances['editor_2'].getData();
      var to = $("#to_user").val();
      var from = $("#from_user").val();
      var subject = $(".subject_unsent").val();
      var client_id = $("#hidden_email_task_id").val();
      var cc = $("#cc_unsent").val();

      $.ajax({
        url:"<?php echo URL::to('user/aml_email_unsent_files'); ?>",
        type:"post",
        data:{client_id:client_id,from:from,to:to,subject:subject,content:content,cc:cc},
        success: function(result)
        {
          $(".emailunsent").modal('hide');
          if(result == "0")
          {
            $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email Field is empty so email is not sent</p>", fixed:true});
          }
          else{
            $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email sent Successfully</p>", fixed:true});
            $(".email_unsent_"+client_id).parents("td").html("<a href='javascript:' class='fa fa-envelope email_unsent email_unsent_"+client_id+"' data-element='"+client_id+"'></a><br>"+result);
          }
          $("body").removeClass("loading");
        }
      });
    }
  }
  if($(e.target).hasClass('remove_dropzone_attach'))
  {
    var attachment_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/aml_remove_dropzone_attachment'); ?>",
      type:"post",
      data:{attachment_id:attachment_id},
      success: function(result)
      {
        var countval = $(e.target).parents(".dropzone").find(".dz-preview").length;
        if(countval == 1)
        {
          $(e.target).parents(".dropzone").removeClass("dz-started");
        }
        $(e.target).parents(".dz-preview").detach();
      }
    })
  }
  if(e.target.id == 'show_incomplete')
  {
    if($(e.target).is(':checked'))
    {
      $(".edit_task").each(function() {
          if($(this).hasClass('disabled'))
          {
            $(this).hide();
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/update_aml_incomplete_status'); ?>",
        type:"post",
        data:{value:1},
        success: function(result)
        {
        }
      });
    }
    else{
      $(".edit_task").each(function() {
          if($(this).hasClass('disabled'))
          {
            $(this).show();
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/update_aml_incomplete_status'); ?>",
        type:"post",
        data:{value:0},
        success: function(result)
        {
        }
      });
    }
  }

if($(e.target).hasClass('refresh_client_source')){
  $('body').addClass('loading');
  var client_id = $(e.target).attr('data-element');
  
  $.ajax({
    url:"<?php echo URL::to('user/aml_system_client_source_refresh'); ?>",
    type:"post",
    data:{id:client_id},
    dataType:"json",
    success: function(result)
    {
      $("#client_source_"+result['id']).html(result['output']);
      $('body').removeClass('loading');
    }
  });

}



if($(e.target).hasClass('risk_class')){
  var client_id = $(e.target).attr('data-element');
  var value = $(e.target).val();
  
  $.ajax({
    url:"<?php echo URL::to('user/aml_system_risk_update'); ?>",
    type:"post",
    data:{id:client_id,value:value},
    dataType:"json",
    success: function(result)
    {
      
    }
  });


}

if($(e.target).hasClass('other_client')){
  var type = $(e.target).val();
  var current_client = $(e.target).attr('data-element');

  $("#select_type").val(type);
  $("#current_client_id").val(current_client);  
  $("#client_search").val('');
  $(".client_search_class").val('');
}

if($(e.target).hasClass('other_submit')){
  var client_search = $("#client_search").val();
  if(client_search == "")
  {
    alert('Please choose the client to proceed.');
  }
  else{
    $('body').addClass('loading');
    var client_id = $("#client_search").val();  
    var type = $("#select_type").val();
    var current_client_id = $("#current_client_id").val();

    $.ajax({
      url:"<?php echo URL::to('user/aml_system_other_client'); ?>",
      type:"post",
      data:{client_id:client_id,type:type,current_client_id:current_client_id},
      dataType:"json",
      success: function(result)
      {
        $(".other_client_modal").modal('hide');
        $("#client_source_"+result['id']).html(result['output']);
        $('body').removeClass('loading');
        $('[data-toggle="tooltip"]').tooltip();
        
      }
    });
  }
}

if($(e.target).hasClass('partner_class')){

  var type = $(e.target).val();
  var current_client = $(e.target).attr('data-element');
  $("#user_type").val('');

  $("#select_type2").val(type);
  $("#partner_current_client_id").val(current_client);  

}


if($(e.target).hasClass('partner_submit')){
  var user_type = $("#user_type").val();
  if(user_type == "")
  {
    alert("Please Choose the user from the dropdown to proceed.")
  }
  else{
    $('body').addClass('loading');  
    var type = $("#select_type2").val();
    var current_client_id = $("#partner_current_client_id").val();
    

    $.ajax({
      url:"<?php echo URL::to('user/aml_system_partner'); ?>",
      type:"post",
      data:{type:type,current_client_id:current_client_id,user_type:user_type},
      dataType:"json",
      success: function(result)
      {
        $(".partner_modal").modal('hide');
        $("#client_source_"+result['id']).html(result['output']);
        $('body').removeClass('loading');
        $('[data-toggle="tooltip"]').tooltip();
        
      }
    });
  }
}

if($(e.target).hasClass('reply_class')){

  var type = $(e.target).val();
  var current_client = $(e.target).attr('data-element');
  $("#reply_note").val('');

  $("#select_type3").val(type);
  $("#note_current_client_id").val(current_client);  

}

if($(e.target).hasClass('note_submit')){
  var reply_note = $("#reply_note").val();
  if(reply_note == "")
  {
    alert("Notes textarea is empty. Please Fill the Notes to proceed")
  }
  else{
    $('body').addClass('loading');  
    var type = $("#select_type3").val();
    var current_client_id = $("#note_current_client_id").val();
    

    $.ajax({
      url:"<?php echo URL::to('user/aml_system_note'); ?>",
      type:"post",
      data:{type:type,current_client_id:current_client_id,reply_note:reply_note},
      dataType:"json",
      success: function(result)
      {
        $(".reply_modal").modal('hide');
        $("#client_source_"+result['id']).html(result['output']);
        $('body').removeClass('loading');
        $('[data-toggle="tooltip"]').tooltip();
        
      }
    });
  }
}


if($(e.target).hasClass('add_bank')){

  var current_client = $(e.target).attr('data-element');
  $("#bank_name").val('');
  $("#account_name").val('');
  $("#account_number").val('');
  
  $("#bank_current_client_id").val(current_client);  

}
if($(e.target).hasClass('trade_details'))
{
  var current_client = $(e.target).attr('data-element');
  $("#products_services").val('');
  $("#transaction_type").val('');
  $("#risk_factors").val('');
  $("#geo_area").val('');

  $("#trade_current_client_id").val(current_client);  
  $(".trade_details_modal").modal("show");
}

if($(e.target).hasClass('trade_details_edit'))
{
  var current_client = $(e.target).attr('data-element');
  $.ajax({
    url:"<?php echo URL::to('user/get_trade_details'); ?>",
    type:"get",
    data:{current_client:current_client},
    dataType:"json",
    success:function(result)
    {
      $("#products_services").val(result['products_services']);
      $("#transaction_type").val(result['transaction_type']);
      $("#risk_factors").val(result['risk_factors']);
      $("#geo_area").val(result['geo_area']);
      $("#trade_current_client_id").val(current_client);  
      $(".trade_details_modal").modal("show");
    }
  });
}

if($(e.target).hasClass('bank_submit')){
  if($("#bank_form").valid())
  {
    //$('body').addClass('loading');    
    var current_client_id = $("#bank_current_client_id").val();
    var bank_name = $("#bank_name").val();
    var account_name = $("#account_name").val();
    var account_number = $("#account_number").val();

    $.ajax({
      url:"<?php echo URL::to('user/aml_system_add_bank'); ?>",
      type:"post",
      data:{current_client_id:current_client_id,bank_name:bank_name,account_name:account_name,account_number:account_number},
      dataType:"json",
      success: function(result)
      {
        $(".bank_modal").modal('hide');
        $("#client_bank_"+result['id']).html(result['output']);
        $('body').removeClass('loading');
        $('[data-toggle="tooltip"]').tooltip();
        
      }
    });
  }
}
if($(e.target).hasClass('trade_submit')){
  if($("#trade_form").valid())
  {
    //$('body').addClass('loading');    
    var current_client_id = $("#trade_current_client_id").val();
    var products_services = $("#products_services").val();
    var transaction_type = $("#transaction_type").val();
    var risk_factors = $("#risk_factors").val();
    var geo_area = $("#geo_area").val();

    $.ajax({
      url:"<?php echo URL::to('user/aml_system_add_trade'); ?>",
      type:"post",
      data:{current_client_id:current_client_id,products_services:products_services,transaction_type:transaction_type,risk_factors:risk_factors,geo_area:geo_area},
      dataType:"json",
      success: function(result)
      {
        $(".trade_details_modal").modal('hide');
        $("#client_trade_"+result['id']).html(result['output']);
        $('body').removeClass('loading');
        $('[data-toggle="tooltip"]').tooltip();
        
      }
    });
  }
}

if($(e.target).hasClass('bank_detail_class')){
  
  $("#bank_expand").dataTable().fnDestroy();
  $('body').addClass('loading');  
   var client_id = $(e.target).attr('data-element');

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_bank_details'); ?>",
    type:"post",
    data:{client_id:client_id},
    dataType:"json",
    success: function(result)
    {
      $(".bank_detail_model").modal('show');
      $("#bank_detail_body").html(result['output']);
      $(".bank_company_name").html(result['company_name']);
      $('#bank_expand').DataTable({
          fixedHeader: {
            headerOffset: 75
          },
          autoWidth: true,
          scrollX: false,
          fixedColumns: false,
          searching: false,
          paging: false,
          info: false
      });     
      $('body').removeClass('loading'); 
    }
  });
}

if($(e.target).hasClass('review_by')){

  var current_client = $(e.target).attr('data-element');
  $("#review_current_client_id").val(current_client);
  $("#review_date").val('');
  $("#reivew_filed").val('');
  $(".review_model").modal('show');


}

if($(e.target).hasClass('review_submit')){
  if($("#review_form").valid())
  {
    $('body').addClass('loading');    
    var current_client_id = $("#review_current_client_id").val();
    var review_date = $("#review_date").val();
    var reivew_filed = $("#reivew_filed").val();

    $.ajax({
      url:"<?php echo URL::to('user/aml_system_review'); ?>",
      type:"post",
      data:{current_client_id:current_client_id,review_date:review_date,reivew_filed:reivew_filed},
      dataType:"json",
      success: function(result)
      {
        $(".review_model").modal('hide');
        $("#review_"+result['id']).html(result['output']);
        $('body').removeClass('loading'); 
        
      }
    });
  }
}
if($(e.target).hasClass('edit_review')){
  var current_client = $(e.target).attr('data-element');
  $("#review_current_client_id_edit").val(current_client);
  

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_review_edit'); ?>",
    type:"post",
    data:{current_client:current_client},
    dataType:"json",
    success: function(result)
    {
      $(".edit_review_model").modal('show');
      $("#review_date_edit").val(result['date']);
      $("#reivew_filed_edit").val(result['output']);
      $(".review_date_edit").datetimepicker({   
         format: 'L',
         format: 'DD-MMM-YYYY'
      });
      
    }
  });
}
if($(e.target).hasClass('review_edit_submit')){
  $('body').addClass('loading');    
  var current_client_id = $("#review_current_client_id_edit").val();
  var review_date = $("#review_date_edit").val();
  var reivew_filed = $("#reivew_filed_edit").val();

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_review_edit_update'); ?>",
    type:"post",
    data:{current_client_id:current_client_id,review_date:review_date,reivew_filed:reivew_filed},
    dataType:"json",
    success: function(result)
    {
      $(".edit_review_model").modal('hide');
      $("#review_"+result['id']).html(result['output']);
      $('body').removeClass('loading');
      
    }
  });
}


if($(e.target).hasClass('delete_review')){
  $('body').addClass('loading');    
  var current_client = $(e.target).attr('data-element');;

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_review_delete'); ?>",
    type:"post",
    data:{current_client:current_client},
    dataType:"json",
    success: function(result)
    {      
      $("#review_"+result['id']).html(result['output']);
      $('body').removeClass('loading');
      
    }
  });
}




if($(e.target).hasClass('image_submit')){
  $('body').addClass('loading');    
  var current_client = $(e.target).attr('data-element');

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_image_upload'); ?>",
    type:"post",
    data:{current_client:current_client},
    dataType:"json",
    success: function(result)
    {
      $(".img_div_add").hide();
      $("#client_identity_"+result['id']).html(result['output']);
      $('body').removeClass('loading');
      
    }
  });
}

if($(e.target).hasClass('delete_attached')){
  $('body').addClass('loading');    
  var id = $(e.target).attr('data-element');

  $.ajax({
    url:"<?php echo URL::to('user/aml_system_delete_attached'); ?>",
    type:"post",
    data:{id:id},
    dataType:"json",
    success: function(result)
    {
      $(".img_div_add").hide();
      $("#client_identity_"+result['id']).html(result['output']);
      $('body').removeClass('loading');
      
    }
  });
}

if($(e.target).hasClass('client_since_class')){  
  var current_client = $(e.target).attr('data-element');
  $("#date_since_current_client_id").val(current_client);  
  $(".date_model").modal('show');
  $(".client_date_since").val('');
}

if($(e.target).hasClass('date_submit')){
  var date = $(".client_date_since").val();
  if(date == "")
  {
    alert("Please fill the date.");
  }
  else{
    $('body').addClass('loading');    
    var current_client = $("#date_since_current_client_id").val();

    $.ajax({
      url:"<?php echo URL::to('user/aml_system_client_since'); ?>",
      type:"post",
      data:{date:date,current_client:current_client},
      dataType:"json",
      success: function(result)
      {      
        $("#client_since_"+result['id']).html(result['output']);
        $(".date_model").modal('hide');
        $('body').removeClass('loading');
      }
    });
  }
}


if(e.target.id == "save_as_pdf")
{
  if($(".select_client:checked").length)
    {
      $("body").addClass("loading");
        var checkedvalue = '';
        var size = 100;
        $(".select_client:checked").each(function() {
          var value = $(this).val();
          if(checkedvalue == "")
          {
            checkedvalue = value;
          }
          else{
            checkedvalue = checkedvalue+","+value;
          }
        });
        var exp = checkedvalue.split(',');
        var arrayval = [];
        for (var i=0; i<exp.length; i+=size) {
            var smallarray = exp.slice(i,i+size);
            arrayval.push(smallarray);
        }
        $.each(arrayval, function( index, value ) {
            setTimeout(function(){ 
              var imp = value.join(',');
              $.ajax({
                url:"<?php echo URL::to('user/aml_report_pdf'); ?>",
                type:"post",
                data:{value:imp},
                success: function(result)
                {
                  $("#report_pdf_type_two_tbody").append(result);
                  var last = index + parseInt(1);
                  if(arrayval.length == last)
                  {
                    var pdf_html = $("#report_pdf_type_two").html();
                    $.ajax({
                      url:"<?php echo URL::to('user/aml_download_report_pdfs'); ?>",
                      type:"post",
                      data:{htmlval:pdf_html},
                      success: function(result)
                      {
                        //var pdffile = result.split('||');
                        //SaveToDisk("<?php echo URL::to('papers'); ?>/"+pdffile[0]+"/"+pdffile[1],pdffile[1]);

                        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
                        window.location.reload();
                      }
                    });
                  }
                }
              });
            }, 3000);
        });
    }
    else{
      $("body").removeClass("loading");
      alert("Please Choose atleast one invoice to continue.");
    }
}



if(e.target.id == "select_all_class")
{
  if($(e.target).is(":checked"))
  {
    $(".select_client").each(function() {
      $(this).prop("checked",true);
    });
  }
  else{
    $(".select_client").each(function() {
      $(this).prop("checked",false);
    });
  }
}
if($(e.target).hasClass('fa-plus-add'))
  {
    $(".img_div").hide();
   var current_client = $(e.target).attr('data-element');

    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left);
    $(e.target).parent().find('.img_div_add').toggle();
    Dropzone.forElement("#imageUpload1").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE<br/>the files OR just drop<br/>files here to upload");

    
    //$("#img_div_"+current_client).css({"position":"absolute","top":toppos,"left":leftposi, "z-index":"9999"}).toggle();
  }
  else if($(e.target).hasClass('fa-plus'))
  {
    $(".img_div").hide();
    var current_client = $(e.target).attr('data-element');

    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left) - 200;
    $(e.target).parent().find('.img_div').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
    $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
    
    //$("#img_div_"+current_client).css({"position":"absolute","top":toppos,"left":leftposi, "z-index":"9999"}).toggle();
  }
  
  
  if($(e.target).hasClass('image_file_add'))
  {
    $(e.target).parents('.modal-body').find('.img_div').toggle();
  }
  
});
$.ajaxSetup({async:false});
$('#form-validation').validate({
    rules: {
        name : {required: true,},
        lname : {required: true,},
        taxnumber : {required: true,remote:"<?php echo URL::to('user/rctclient_checktax'); ?>"},
        email : {required: true,email:true,remote:"<?php echo URL::to('user/rctclient_checkemail'); ?>"},
    },
    messages: {
        name : "Client Name is Required",
        lname : "Salutation is Required",
        taxnumber : {
          required : "Tax Number is Required",
          remote : "Tax Number is already exists",
        },
        email : {
          required : "Email Id is Required",
          email : "Please Enter a valid Email Address",
          remote : "Email Id is already exists",
        },
    },
});
$('#bank_form').validate({
    rules: {
        bank_name : {required: true,},
        account_name : {required: true,},
        account_number : {required: true,},
    },
    messages: {
        bank_name : "Bank Name is Required",
        account_name : "Account Name is Required",
        account_number : "Account Number is Required",
    },
});
$('#review_form').validate({
    rules: {
        review_date : {required: true,},
        reivew_filed : {required: true,},
    },
    messages: {
        review_date : "Review Date is Required",
        reivew_filed : "Reviewed By is Required",
    },
});


</script>

<!-- Page Scripts -->
<script>
$(function(){
    $('#aml_expand').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false
    });
});

$(".client_date_since").datetimepicker({
   //defaultDate: fullDate,       
   format: 'L',
   format: 'DD-MMM-YYYY',
   //maxDate: fullDate,
});
$(".review_date").datetimepicker({   
   format: 'L',
   format: 'DD-MMM-YYYY'
});



</script>

<script>
$(document).ready(function() {    
  $(".client_search_class").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/aml_client_search'); ?>",
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 1,
        select: function( event, ui ) {
          $("#client_search").val(ui.item.id);          
          $.ajax({
            url:"<?php echo URL::to('user/aml_client_search_select'); ?>",
            data:{value:ui.item.id},
            success: function(result){
              
              
            }
          })
        }
  });
});
</script>





@stop