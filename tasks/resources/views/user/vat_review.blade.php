@extends('userheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>
<style>
.modal {
  overflow-y:auto;
}
.add_attachment_month_year{
  margin-left: 10px;
}
.delete_submitted{
  float: right;
  margin-left: 10px;
  margin-top: 5px;
  color: #f00;
}
.submitted_import{
    width: 45%;
    outline: none;
}

.add_attachment_month_year_overlay{
  margin-left: 10px;
}
.delete_submitted_overlay{
  float: right;
  margin-left: 10px;
  margin-top: 5px;
  color: #f00;
}
.submitted_import_overlay{
    width: 80%;
    outline: none;
}

.dz-remove{
    color: #000;
    font-weight: 800;
    text-transform: uppercase;
}
#colorbox{ z-index: 999999999999; }
.fa-sort { margin-top:3px; }
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.table-fixed-header {
  text-align: left;
  position: relative;
  border-collapse: collapse; 
}
.table-fixed-header thead tr th {
  background: white;
  position: sticky;
  top: 84; /* Don't forget this, required for the stickiness */
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
}

.table-fixed-header_overlay {
  text-align: left;
  position: relative;
  border-collapse: collapse; 
}
.table-fixed-header_overlay thead tr th {
  background: white;
  position: sticky;
  top: 84; /* Don't forget this, required for the stickiness */
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
}

.orange_import{
    width: 76%;
    padding: 10px;
    border-radius: 40px;
    background: orange;
    color: #fff;
    font-weight: 600;
}
.red_import{
        width: 67%;
    padding: 10px;
    border-radius: 40px;
    background: #f00;
    color: #fff;
    font-weight: 600;
}
.green_import{
    width: 40%;
    padding: 10px;
    border-radius: 40px;
    background: green;
    color: #fff;
    font-weight: 600;
}
.blue_import{
    width: 66%;
    padding: 10px;
    border-radius: 40px;
    background: blue;
    color: #fff;
    font-weight: 600;
}
.white_import{
    width: 35%;
    padding: 10px;
    border-radius: 40px;
    background: #40E0D0;
    color: #000;
    font-weight: 600;
}

.orange_import_overlay{
    width: 100%;
    padding: 10px;
    border-radius: 40px;
    background: orange;
    color: #fff;
    font-weight: 600;
    text-align: center;
}
.red_import_overlay{
        width: 100%;
    padding: 10px;
    border-radius: 40px;
    background: #f00;
    color: #fff;
    font-weight: 600;
    text-align: center;
}
.green_import_overlay{
    width: 100%;
    padding: 10px;
    border-radius: 40px;
    background: green;
    color: #fff;
    font-weight: 600;
    text-align: center;
}
.blue_import_overlay{
    width: 100%;
    padding: 10px;
    border-radius: 40px;
    background: blue;
    color: #fff;
    font-weight: 600;
    text-align: center;
}
.white_import_overlay{
    width: 100%;
    padding: 10px;
    border-radius: 40px;
    background: #40E0D0;
    color: #000;
    font-weight: 600;
    text-align: center;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th
{
  background: #fff;
  padding:10px;
}
.table>thead>tr>th{ 
  background: #fff;
    border-left: 1px solid #f5f5f5;
    padding:10px;
}
/*.nav>li>a:focus, .nav>li>a:hover { background: #d6d6d6;
    color: #000;
    font-weight: 700; }
.nav-item .active { background: #d6d6d6;
    color: #000;
    font-weight: 700; }*/
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
.fa-sort{
  cursor: pointer;
}
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    99999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
.error_ref{
  color: #f00;
    font-size: 9px;
    position: absolute;
    left: 55.5%;
}
body.loading {
    overflow: hidden;
}
body.loading .modal_load {
    display: block;
}


.btn{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}
.btn:hover{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}

.btn_add
{
  background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: right;
}
.btn_add:hover
{
  background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: right;
}
.drop_down{
  width: 100%;
margin-top: 2px;
background: none !important;
color: #000 !important;
border-bottom: 1px solid #dedada;
}
.dropdown-menu{
  right: 0px;
left: 79%;
top: 85%;
}
.color_pallete_red{
  padding:18px 17px;
  background: #f00;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
}
.color_pallete_green{
  padding:18px 17px;
  background: green;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
}
.color_pallete_yellow{
  padding:18px 17px;
  background: yellow;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
}
.color_pallete_purple{
  padding:18px 17px;
  background: purple;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
}
.popover-title
{
  font-weight:800;
}
.popover-content{
  display:none !important;
}
#alert_modal{
  z-index:9999999 !important;
}
#alert_modal_edit{
  z-index:9999999 !important;
}
</style>

<style>
.body_bg{
    background: #f5f5f5;
}

.fullviewtablelist>tbody>tr>td{
  font-weight:800 !important;
  font-size:15px !important;
}
.fullviewtablelist>tbody>tr>td a{
  font-weight:800 !important;
  font-size:15px !important;
}

.ui-widget{z-index: 999999999}
.form-control[readonly]{background: #eaeaea !important}

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
.remove_dropzone_attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.remove_notepad_attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.remove__attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.img_div_add{
    border: 1px solid #000;
    width: 280px;
    position: absolute !important;
    min-height: 118px;
    background: rgb(255, 255, 0);
    display:none;
}
.records_receive_label{
  font-weight:400;
}
.due_td{
  color:#f00;
  font-weight:800;
}
.os_td{
  color:#f00;
  font-weight:800;
}
.checked{
  color:green !important;
  font-weight:800;
}

.records_receive_label_overlay{
  font-weight:400;
}
.due_td_overlay{
  color:#f00;
}
.os_td_overlay{
  color:#f00;
}
.checked_overlay{
  color:green !important;
}
.readonly .slider{
  background: #dfdfdf !important;
}
.readonly .slider:before{
  background: #000 !important;
}
input:checked + .slider{
      background-color: #2196F3 !important;
}
.switch {
  background: #fff !important;
  position: relative;
  display: inline-block;
  width: 47px;
  height: 24px;
  float:left !important;
  margin-top: 4px;
}
.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 20px;
  left: 0px;
  bottom: 3px;
  background-color: red;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>
<?php
if(!empty($_GET['import_type']))
{
  if(!empty($_GET['round']))
  {
    $filename = $_GET['filename'];
    $height = $_GET['height'];
    $highestrow = $_GET['highestrow'];
    $round = $_GET['round'];
    $load_all = $_GET['load_all'];
    ?>
    <div class="upload_img" style="width: 100%;height: 100%;z-index:1"><p class="upload_text">Processing the CSV File. Please wait...</p><img src="<?php echo URL::to('assets/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">Processed <?php echo $height; ?> of <?php echo $highestrow; ?></p></div>

    <script>
      $(document).ready(function() {
        var base_url = "<?php echo URL::to('/'); ?>";
        window.location.replace(base_url+'/user/process_vat_reviews_one?filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type=1&load_all=<?php echo $load_all; ?>');
      })
    </script>
    <?php

  }
}
?>
<div class="modal fade dropzone_progress_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" >Add Attachments</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
              <div class="img_div_progress">
                 <div class="image_div_attachments_progress">
                    <form action="<?php echo URL::to('user/vat_upload_images'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                        <input name="hidden_client_id_vat" id="hidden_client_id_vat" type="hidden" value="">
                        <input name="hidden_month_year_vat" id="hidden_month_year_vat" type="hidden" value="">
                    </form>
                 </div>
              </div>
          </div>
          <div class="modal-footer">  
            <a href="javascript:" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
          </div>
        </div>
  </div>
</div>
<div class="modal fade period_change_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Change Period</h4>
          </div>
          <div class="modal-body">  
              <h4>From:</h4>
              <input type="text" name="from_change_period" class="form-control from_change_period" value="">

              <h4>To:</h4>
              <input type="text" name="to_change_period" class="form-control to_change_period" value="">
          </div>
          <div class="modal-footer">  
            <input type="hidden" name="hidden_month_year_period" id="hidden_month_year_period" value="">
            <input type="hidden" name="hidden_client_id_period" id="hidden_client_id_period" value="">
            <a href="javascript:" class="btn btn-sm btn-primary change_period_submit" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
          </div>
        </div>
  </div>
</div>
<div class="modal fade load_ros_vat_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog" role="document" style="width:70%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Import ROS Outstanding VAT Returns File Manager</h4>
          </div>
          <div class="modal-body">  
            <div class="row">
              <div class="col-md-5" style="border-right:1px solid #dfdfdf">
                <h4>Browse File:</h4>
                <div class="img_div_progress">
                   <div class="image_div_attachments_progress">
                      <form action="<?php echo URL::to('user/vat_upload_csv'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload1" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left;padding-top: 12%;">
                      </form>
                   </div>
                </div>
                <input type="hidden" name="hidden_import_file_name" id="hidden_import_file_name" value="">
                <p><input type="button" name="load_vat_due" class="common_black_button load_vat_due" value="Load" style="width:100%"></p>
              </div>
              <div class="col-md-7">
                <h4>Valid File:</h4>
                <table class="table own_table_white">
                  <thead>
                    <th>Filename</th>
                    <th>Date</th>
                    <th>Time</th>
                  </thead>
                  <tbody id="import_file_tbody">
                    <tr>
                      <td colspan="3">No Records Found</td>
                    </tr>
                  </tbody>
                </table>
                <p><input type="button" class="process_import_file common_black_button" value="Process" disabled></p>
                <h4 style="margin-top:40px">Imported File List:</h4>
                <table class="table own_table_white">
                  <thead>
                    <th>ID</th>
                    <th>Filename</th>
                    <th>Date</th>
                    <th>Time</th>
                  </thead>
                  <tbody id="imported_file_tbody">
                    <?php
                    $imported_list = DB::table('vat_reviews_import_attachment')->where('status',1)->get();
                    if(count($imported_list))
                    {
                      foreach($imported_list as $list)
                      {
                        echo '<tr>
                          <td><a href="'.URL::to($list->url.'/'.$list->filename).'" download>'.$list->import_id.'</a></td>
                          <td><a href="'.URL::to($list->url.'/'.$list->filename).'" download>'.$list->uploaded_filename.'</a></td>
                          <td><a href="'.URL::to($list->url.'/'.$list->filename).'" download>'.$list->import_date.'</a></td>
                          <td><a href="'.URL::to($list->url.'/'.$list->filename).'" download>'.$list->import_time.'</a></td>
                        </tr>';
                      }
                    }
                    else{
                      echo '<tr>
                        <td colspan="4">No Records Found</td>
                      </tr>';
                    }
                    ?>
                  </tbody>
                </table>
              </div>
              <?php
              if(Session::has('message_import'))
              {
                echo '<div class="col-md-12" style="text-align:center">
                  <h3 style="color:#000;font-weight: 600;">'.Session::get('message_import').'</h3>
                </div>';
              }
              ?>
            </div>
            
          </div>
        </div>
  </div>
</div>

<div class="modal fade" id="show_pdf_viewer" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 9999999999999999;">
  <div class="modal-dialog" role="document" style="z-index: 9999999999999999;width:50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">View Attachment</h4>
      </div>
      <div class="modal-body">
        <iframe name="attachment_pdf" class="attachment_pdf" src="" style="width:100%;height: 900px;"></iframe>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<div class="modal fade show_month_modal" id="show_month_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="z-index: 99999;">
  <div class="modal-dialog" role="document" style="width:90%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">View Month Progress</h4>
      </div>
      <div class="modal-body">
        <div class="show_active_div" style="width:33%;margin-right: 0px;float: left;">
            <label style="margin-right: 10px; line-height: 30px;float: left;margin-left: 4%;margin-top: 3px;">Show All</label>
            <label class="switch" style="margin-right: 10px;">
              <input type="checkbox" class="show_active" value="1">
              <span class="slider round"></span>
            </label>
            <input type="hidden" id="hidden_show_active" value="1">
            <label style="margin-right: 10px; line-height: 30px;margin-top: 2px;" >Show Active</label>
        </div>
        <input type="button" class="common_black_button export_csv_month" name="export_csv_month" value="Export CSV" style="float:right">
        <input type="hidden" name="hidden_month_overlay" id="hidden_month_overlay" value="">
        <div class="col-md-12 view_month_progress_div" id="view_month_progress_div" style="margin-top:20px">
        </div>
      </div>
      <div class="modal-footer" style="clear: both">
      </div>
    </div>
  </div>
</div>

<div class="content_section">
  <div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 padding_00 new_main_title">
                VAT Management System               
            </h4>
    </div>
<div class="row">
<?php
  if(Session::has('message')) { ?>
         <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
<?php } ?>
  <div class="message_edit">
  </div>
  
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link" href="<?php echo URL::to('user/vat_clients'); ?>">VAT Clients Imported in to VAT Management System</a>
    </li>
    <li class="nav-item active">
      <a class="nav-link" href="<?php echo URL::to('user/vat_review'); ?>">VAT Management System VAT Review</a>
    </li>
  </ul>
  <div class="col-md-6">
  </div>
  <div class="select_button" style="background: #fff; padding-top: 20px; padding-bottom: 20px;">
    <div class="col-lg-3" style="width: 21%; padding-right: 0px;">
      <div class="select_button">
        <ul style="float: left;">
          <li><a href="javascript:" class="common_black_button load_all_vat_clients">Load All Clients</a></li> 
          <li><a href="javascript:" class="common_black_button load_ros_vat_due" style="float:right">Load ROS VAT due Extract</a></li>         
        </ul>
      </div>
    </div>
    
    <div class="col-lg-3 padding_00" style="width: 11%">
      <?php 
      $current_import_file = DB::table('vat_reviews_import_attachment')->where('status',1)->orderBy('id','desc')->first();
      if(count($current_import_file))
      {
        ?>
        <spam style="font-weight:600">Imported Date: </spam><spam><?php echo $current_import_file->import_date; ?></spam>
            <br/>
            <spam style="font-weight:600">Imported File ID: </spam><spam class="current_imported_id"><?php echo $current_import_file->import_id; ?></spam>
        
        <?php
      }
      ?>
    </div>
    
    <div class="col-lg-3" style="width: 52%">

      
      <?php 
      $current_import_file = DB::table('vat_reviews_import_attachment')->where('status',1)->orderBy('id','desc')->first();
      if(count($current_import_file))
      {
        ?>
        <spam style="font-weight:600">Current Imported File: </spam><spam><?php echo $current_import_file->uploaded_filename; ?></spam><br/>
            <spam style="font-weight:600">Imported Time: </spam><spam><?php echo $current_import_file->import_time; ?></spam>
        <?php
      }
      ?>
    </div>
    <div class="col-lg-2" style="line-height: 35px; width: 15%">
      <input type="checkbox" name="show_incomplete" class="show_incomplete" id="show_incomplete" value="1"><label for="show_incomplete">Hide/Show Deactivated client </label>
    </div>

    <div class="col-lg-12" style="padding: 0px; line-height: 35px;">

      <table class="table table-fixed-header own_table_white"  id="vat_expand" align="left" style="min-width:70%;margin-top:20px;display:none">
        <thead>
          <tr>
              <th style="width:10%;text-align: left;">Client Code <i class="fa fa-sort sno_sort" style="float:right" aria-hidden="true"></i></th>
              <th style="width:20%;text-align: left; ;">Client Name <i class="fa fa-sort client_sort" style="float:right" aria-hidden="true"></i></th>
              <th style="width:10%;text-align: left; ">Tax No <i class="fa fa-sort tax_sort" style="float:right" aria-hidden="true"></i></th>
              <th style="width:20%;text-align: left; "><a href="javascript:" class="fa fa-arrow-circle-left show_prev_month" title="Extend to Prev Month" data-element="<?php echo date('m-Y', strtotime('last month')); ?>"></a> &nbsp;&nbsp;<a href="javascript:" class="show_month_in_overlay" data-element="<?php echo date('m-Y', strtotime('last month')); ?>"><?php echo date('M-Y', strtotime('last month')); ?></a></th>
              <th style="width:20%;text-align: left; "><a href="javascript:" class="show_month_in_overlay" data-element="<?php echo date('m-Y'); ?>"><?php echo date('M-Y'); ?></a> </th>
              <th style="width:20%;text-align: left; "><a href="javascript:" class="show_month_in_overlay" data-element="<?php echo date('m-Y', strtotime('next month')); ?>"><?php echo date('M-Y', strtotime('next month')); ?></a> &nbsp;&nbsp; <a href="javascript:" class="fa fa-arrow-circle-right show_next_month" title="Extend to Next Month" data-element="<?php echo date('m-Y', strtotime('next month')); ?>"></a></th>
          </tr>
        </thead>
        <tbody id="task_body">
          
        </tbody>            
      </table>
    </div>
  </div>
</div>
</div>
<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<input type="hidden" name="client_sortoptions" id="client_sortoptions" value="asc">
<input type="hidden" name="tax_sortoptions" id="tax_sortoptions" value="asc">

<input type="hidden" name="code_sortoptions" id="code_sortoptions" value="asc">
<input type="hidden" name="client_overlay_sortoptions" id="client_overlay_sortoptions" value="asc">
<input type="hidden" name="status_sortoptions" id="status_sortoptions" value="asc">
<input type="hidden" name="id_sortoptions" id="id_sortoptions" value="asc">
<input type="hidden" name="record_sortoptions" id="record_sortoptions" value="asc">
<input type="hidden" name="date_sortoptions" id="date_sortoptions" value="asc">
<input type="hidden" name="attachment_sortoptions" id="attachment_sortoptions" value="asc">

<input type="hidden" name="load_all_clients_status" id="load_all_clients_status" value="">


<div class="modal_load"></div>

<?php
if(Session::has('load_all')) { ?>
<script>
$(document).ready(function(){
  $(".load_all_vat_clients").trigger("click");
  $(".load_ros_vat_due").trigger("click");
});
</script>
<?php } ?>
<?php
if(Session::has('message_import')) { ?>
<script>
$(document).ready(function(){
  $(".load_ros_vat_due").trigger("click");
});
</script>
<?php } ?>
<script>
  $( function() {
    $(".datepicker" ).datepicker({ dateFormat: 'mm-dd-yy' });
  } );
  </script>
<script>
$(".from_change_period").datetimepicker({
   format: 'L',
   format: 'MMM-YYYY',
   viewMode: "months",
});
$(".to_change_period").datetimepicker({
   format: 'L',
   format: 'MMM-YYYY',
   viewMode: "months",
});
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();
    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
    $(".date_text").datetimepicker({
       defaultDate: "",
       format: 'L',
       format: 'DD/MM/YYYY',
    });  

    $(".date_text_overlay").datetimepicker({
       defaultDate: "",
       format: 'L',
       format: 'DD/MM/YYYY',
    });   
});
var convertToNumber = function(value){
       return value.toLowerCase();
}
var parseconvertToNumber = function(value){
       return parseInt(value);
}
function ajax_functions()
{
  var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
  $(".date_text").datetimepicker({
     defaultDate: "",
     format: 'L',
     format: 'DD/MM/YYYY',
  });
  $(".date_text_overlay").datetimepicker({
     defaultDate: "",
     format: 'L',
     format: 'DD/MM/YYYY',
  });  
  $(".submitted_import").datetimepicker({
     defaultDate: "",
     format: 'L',
     format: 'DD/MM/YYYY',
  }); 
  $(".submitted_import_overlay").datetimepicker({
     defaultDate: "",
     format: 'L',
     format: 'DD/MM/YYYY',
  }); 
  $(".date_text").on("dp.hide", function (e) {
    var client = $(e.target).attr("data-client");
    var month_year = $(e.target).attr("data-element");
    var date = $(e.target).val();
    $.ajax({
      url:"<?php echo URL::to('user/save_vat_review_date'); ?>",
      type:"post",
      data:{client:client,month_year:month_year,date:date},
      success:function(result)
      {

      }
    })
  }); 
  $(".date_text_overlay").on("dp.hide", function (e) {
    var client = $(e.target).attr("data-client");
    var month_year = $(e.target).attr("data-element");
    var date = $(e.target).val();
    $.ajax({
      url:"<?php echo URL::to('user/save_vat_review_date'); ?>",
      type:"post",
      data:{client:client,month_year:month_year,date:date},
      success:function(result)
      {

      }
    })
  }); 
  $(".submitted_import").on("dp.hide", function (e) {
    var client = $(e.target).attr("data-client");
    var month_year = $(e.target).attr("data-element");
    var date = $(e.target).val();
    $.ajax({
      url:"<?php echo URL::to('user/save_vat_review_date'); ?>",
      type:"post",
      data:{client:client,month_year:month_year,date:date},
      success:function(result)
      {
        $(e.target).parents("td").find(".delete_submitted").show();
        $(e.target).parents("td").find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import").addClass("green_import");
        $(e.target).parents("td").find(".import_icon").html("Submitted");
        $(e.target).parents("td").find(".records_receive_label").attr("class","records_receive_label submitted_td");
      }
    })
  }); 
  $(".submitted_import_overlay").on("dp.hide", function (e) {
    var client = $(e.target).attr("data-client");
    var month_year = $(e.target).attr("data-element");
    var date = $(e.target).val();
    $.ajax({
      url:"<?php echo URL::to('user/save_vat_review_date'); ?>",
      type:"post",
      data:{client:client,month_year:month_year,date:date},
      success:function(result)
      {
        $(e.target).parents("tr").find(".delete_submitted_overlay").show();
        $(e.target).parents("tr").find(".import_icon_overlay").removeClass("orange_import_overlay").removeClass("red_import_overlay").removeClass("blue_import_overlay").removeClass("green_import_overlay").removeClass("white_import_overlay").addClass("green_import_overlay");
        $(e.target).parents("tr").find(".import_icon_overlay").html("Submitted");
        $(e.target).parents("tr").find(".records_receive_label_overlay").attr("class","records_receive_label_overlay submitted_td_overlay");
        $(e.target).parents("tr").find(".date_sort_val").val(date);

        $(".tasks_tr_"+client).find("#add_files_vat_client_"+month_year).find(".delete_submitted").show();
        $(".tasks_tr_"+client).find("#add_files_vat_client_"+month_year).find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import").addClass("green_import");
        $(".tasks_tr_"+client).find("#add_files_vat_client_"+month_year).find(".import_icon").html("Submitted");
        $(".tasks_tr_"+client).find("#add_files_vat_client_"+month_year).find(".submitted_import").val(date);
        $(".tasks_tr_"+client).find("#add_files_vat_client_"+month_year).find(".records_receive_label").attr("class","records_receive_label submitted_td");
      }
    })
  }); 

  $(".input_text_one").on("blur", function (e) {
    var client = $(e.target).attr("data-client");
    var month_year = $(e.target).attr("data-element");
    var textval = $(e.target).val();
    $.ajax({
      url:"<?php echo URL::to('user/save_textval_review'); ?>",
      type:"post",
      data:{client:client,month_year:month_year,textval:textval,type:"2"},
      success:function(result)
      {

      }
    })
  });

  $(".input_text_two").on("blur", function (e) {
    var client = $(e.target).attr("data-client");
    var month_year = $(e.target).attr("data-element");
    var textval = $(e.target).val();
    $.ajax({
      url:"<?php echo URL::to('user/save_textval_review'); ?>",
      type:"post",
      data:{client:client,month_year:month_year,textval:textval,type:"3"},
      success:function(result)
      {

      }
    })
  });
}
$(window).click(function(e) { 
  var ascending = false;
  if($(e.target).hasClass('sno_sort'))
  {
    var sort = $("#sno_sortoptions").val();
    if(sort == 'asc')
    {
      $("#sno_sortoptions").val('desc');
      var sorted = $('#task_body').find('.tasks_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.sno_sort_val').text()) <
        convertToNumber($(b).find('.sno_sort_val').text()))) ? 1 : -1;
      });
    }
    else{
      $("#sno_sortoptions").val('asc');
      var sorted = $('#task_body').find('.tasks_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.sno_sort_val').text()) <
        convertToNumber($(b).find('.sno_sort_val').text()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body').html(sorted);
  }
  if($(e.target).hasClass('code_sort'))
  {
    var sort = $("#code_sortoptions").val();
    if(sort == 'asc')
    {
      $("#code_sortoptions").val('desc');
      var sorted = $('#overlay_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.code_sort_val').text()) <
        convertToNumber($(b).find('.code_sort_val').text()))) ? 1 : -1;
      });
    }
    else{
      $("#code_sortoptions").val('asc');
      var sorted = $('#overlay_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.code_sort_val').text()) <
        convertToNumber($(b).find('.code_sort_val').text()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#overlay_tbody').html(sorted);
    ajax_functions();
  }
  if($(e.target).hasClass('client_overlay_sort'))
  {
    var sort = $("#client_overlay_sortoptions").val();
    if(sort == 'asc')
    {
      $("#client_overlay_sortoptions").val('desc');
      var sorted = $('#overlay_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_overlay_sort_val').text()) <
        convertToNumber($(b).find('.client_overlay_sort_val').text()))) ? 1 : -1;
      });
    }
    else{
      $("#client_overlay_sortoptions").val('asc');
      var sorted = $('#overlay_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_overlay_sort_val').text()) <
        convertToNumber($(b).find('.client_overlay_sort_val').text()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#overlay_tbody').html(sorted);
    ajax_functions();
  }
  if($(e.target).hasClass('status_sort'))
  {
    var sort = $("#status_sortoptions").val();
    if(sort == 'asc')
    {
      $("#status_sortoptions").val('desc');
      var sorted = $('#overlay_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.import_icon_overlay').text()) <
        convertToNumber($(b).find('.import_icon_overlay').text()))) ? 1 : -1;
      });
    }
    else{
      $("#status_sortoptions").val('asc');
      var sorted = $('#overlay_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.import_icon_overlay').text()) <
        convertToNumber($(b).find('.import_icon_overlay').text()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#overlay_tbody').html(sorted);
    ajax_functions();
  }
  if($(e.target).hasClass('id_sort'))
  {
    var sort = $("#id_sortoptions").val();
    if(sort == 'asc')
    {
      $("#id_sortoptions").val('desc');
      var sorted = $('#overlay_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.id_sort_val').text()) <
        convertToNumber($(b).find('.id_sort_val').text()))) ? 1 : -1;
      });
    }
    else{
      $("#id_sortoptions").val('asc');
      var sorted = $('#overlay_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.id_sort_val').text()) <
        convertToNumber($(b).find('.id_sort_val').text()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#overlay_tbody').html(sorted);
    ajax_functions();
  }
  if($(e.target).hasClass('record_sort'))
  {
    var sort = $("#record_sortoptions").val();
    if(sort == 'asc')
    {
      $("#record_sortoptions").val('desc');
      var sorted = $('#overlay_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.record_sort_val').val()) <
        convertToNumber($(b).find('.record_sort_val').val()))) ? 1 : -1;
      });
    }
    else{
      $("#record_sortoptions").val('asc');
      var sorted = $('#overlay_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.record_sort_val').val()) <
        convertToNumber($(b).find('.record_sort_val').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#overlay_tbody').html(sorted);
    ajax_functions();
  }
  if($(e.target).hasClass('date_sort'))
  {
    var sort = $("#date_sortoptions").val();
    if(sort == 'asc')
    {
      $("#date_sortoptions").val('desc');
      var sorted = $('#overlay_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.date_sort_val').val()) <
        convertToNumber($(b).find('.date_sort_val').val()))) ? 1 : -1;
      });
    }
    else{
      $("#date_sortoptions").val('asc');
      var sorted = $('#overlay_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.date_sort_val').val()) <
        convertToNumber($(b).find('.date_sort_val').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#overlay_tbody').html(sorted);
    ajax_functions();
  }
  if($(e.target).hasClass('client_sort'))
  {
    var sort = $("#client_sortoptions").val();
    if(sort == 'asc')
    {
      $("#client_sortoptions").val('desc');
      var sorted = $('#task_body').find('.tasks_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_sort_val').html()) <
        convertToNumber($(b).find('.client_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#client_sortoptions").val('asc');
      var sorted = $('#task_body').find('.tasks_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_sort_val').html()) <
        convertToNumber($(b).find('.client_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body').html(sorted);
  }
  if($(e.target).hasClass('tax_sort'))
  {
    var sort = $("#tax_sortoptions").val();
    if(sort == 'asc')
    {
      $("#tax_sortoptions").val('desc');
      var sorted = $('#task_body').find('.tasks_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.tax_sort_val').html()) <
        convertToNumber($(b).find('.tax_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#tax_sortoptions").val('asc');
      var sorted = $('#task_body').find('.tasks_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.tax_sort_val').html()) <
        convertToNumber($(b).find('.tax_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body').html(sorted);
  }
  if($(e.target).parents(".switch").length > 0)
  {
    if($(e.target).parents(".show_active_div").find(".show_active").is(":checked"))
    {
      $(e.target).parents(".show_active_div").find("#hidden_show_active").val("1");
    }
    else{
      $(e.target).parents(".show_active_div").find("#hidden_show_active").val("0");
    }
    var layout = $("#hidden_show_active").val();
    if(layout == "1")
    {
      $(".import_icon_overlay").parents("tr").hide();
      $(".green_import_overlay").parents("tr").show();
      $(".orange_import_overlay").parents("tr").show();
      $(".red_import_overlay").parents("tr").show();
      $(".blue_import_overlay").parents("tr").show();
    }
    else{
      $(".import_icon_overlay").parents("tr").show();
    }
  }
  if($(e.target).hasClass('export_csv_month'))
  {
    $("body").addClass("loading");
    var month = $("#hidden_month_overlay").val();
    $.ajax({
      url:"<?php echo URL::to('user/export_month_in_overlay'); ?>",
      type:"post",
      data:{month:month},
      success:function(result)
      {
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('show_month_in_overlay'))
  {
    $("body").addClass("loading");
    var month = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/show_month_in_overlay'); ?>",
      type:"post",
      data:{month:month},
      success:function(result)
      {
        $(".show_month_modal").modal("show");
        $("#view_month_progress_div").html(result);
        $("#hidden_month_overlay").val(month);
        $(".show_active").prop("checked",false);
        $("body").removeClass("loading");
        ajax_functions();
      }
    })
  }
  if($(e.target).hasClass('check_records_received'))
  {
    var client_id = $(e.target).attr("data-client");
    var month = $(e.target).attr("data-month");
    if($(e.target).is(":checked"))
    {
      $.ajax({
        url:"<?php echo URL::to('user/update_records_received'); ?>",
        type:"post",
        data:{client_id:client_id,month:month,type:"1"},
        success:function(result)
        {
          $(e.target).parents("td").find(".records_receive_label").addClass("checked");
        }
      })
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/update_records_received'); ?>",
        type:"post",
        data:{client_id:client_id,month:month,type:"2"},
        success:function(result)
        {
          $(e.target).parents("td").find(".records_receive_label").removeClass("checked");
        }
      })
    }
  }
  if($(e.target).hasClass('check_records_received_overlay'))
  {
    var client_id = $(e.target).attr("data-client");
    var month = $(e.target).attr("data-month");
    if($(e.target).is(":checked"))
    {
      $.ajax({
        url:"<?php echo URL::to('user/update_records_received'); ?>",
        type:"post",
        data:{client_id:client_id,month:month,type:"1"},
        success:function(result)
        {
          $(e.target).parents("tr").find(".records_receive_label_overlay").addClass("checked");
          $(e.target).parents("tr").find(".record_sort_val").val("checked");
          $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".records_receive_label").addClass("checked");
          $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".check_records_received").prop("checked",true);
        }
      })
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/update_records_received'); ?>",
        type:"post",
        data:{client_id:client_id,month:month,type:"2"},
        success:function(result)
        {
          $(e.target).parents("tr").find(".records_receive_label_overlay").removeClass("checked");
          $(e.target).parents("tr").find(".record_sort_val").val("");
          $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".records_receive_label").removeClass("checked");
          $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".check_records_received").prop("checked",false);
        }
      })
    }
  }
  if($(e.target).hasClass('delete_submitted'))
  {
    var month = $(e.target).attr("data-element");
    var client = $(e.target).attr("data-client");
    $.ajax({
      url:"<?php echo URL::to('user/delete_submitted_vat_review'); ?>",
      type:"post",
      data:{month:month,client:client},
      success:function(result)
      {
        $(e.target).parents("td").find(".submitted_import").val("");
        $(e.target).parents("td").find(".delete_submitted").hide();
        var imported_id = $(e.target).parents("td").find(".import_file_attachment_id").length;
        if(imported_id == 0)
        {
          $(e.target).parents("td").find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import");
          $(e.target).parents("td").find(".import_icon").html("");
          $(e.target).parents("td").find(".records_receive_label").removeClass("submitted_td").removeClass("due_td").removeClass("os_td").removeClass('ps_td').removeClass("not_due_td");
        }
        else{
          var id = $(e.target).parents("td").find(".import_file_attachment_id").html();
          var current_id = $(".current_imported_id").html();
          if(current_id == id)
          {
            var cu_d = new Date();
            var cu_m = cu_d.getMonth() + 1;
            var cu_y = cu_d.getFullYear();
            var mm = month.substr(0, 2);
            if(cu_m < 10)
            {
              cu_m = '0'+cu_m;
            }

            if(cu_m == mm)
            {
              $(e.target).parents("td").find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import").addClass("orange_import");
              $(e.target).parents("td").find(".import_icon").html("Submission Due");
              $(e.target).parents("td").find(".records_receive_label").addClass("due_td");
            }
            else if(mm > cu_m)
            {
              $(e.target).parents("td").find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import").addClass("white_import");
              $(e.target).parents("td").find(".import_icon").html("Not Due");
              $(e.target).parents("td").find(".records_receive_label").addClass("not_due_td");
            }
            else{
              $(e.target).parents("td").find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import").addClass("red_import");
              $(e.target).parents("td").find(".import_icon").html("Submission O/S");
              $(e.target).parents("td").find(".records_receive_label").addClass("os_td");
            }
          }
          else{
            $(e.target).parents("td").find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import").addClass("blue_import");
            $(e.target).parents("td").find(".import_icon").html("Potentially Submitted");
            $(e.target).parents("td").find(".records_receive_label").addClass("ps_td");
          }
        }
      }
    })
  }
  if($(e.target).hasClass('delete_submitted_overlay'))
  {
    var month = $(e.target).attr("data-element");
    var client = $(e.target).attr("data-client");
    $.ajax({
      url:"<?php echo URL::to('user/delete_submitted_vat_review'); ?>",
      type:"post",
      data:{month:month,client:client},
      success:function(result)
      {
        $(e.target).parents("tr").find(".submitted_import_overlay").val("");
        $(e.target).parents("tr").find(".delete_submitted_overlay").hide();
        $(e.target).parents("tr").find(".date_sort_val").val("");
        $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".submitted_import").val("");
        $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".delete_submitted").hide();

        var imported_id = $(e.target).parents("tr").find(".import_file_attachment_id_overlay").length;
        if(imported_id == 0)
        {
          $(e.target).parents("tr").find(".import_icon_overlay").removeClass("orange_import_overlay").removeClass("red_import_overlay").removeClass("blue_import_overlay").removeClass("green_import_overlay").removeClass("white_import_overlay");
          $(e.target).parents("tr").find(".import_icon_overlay").html("");
          $(e.target).parents("tr").find(".records_receive_label_overlay").removeClass("submitted_td_overlay").removeClass("due_td_overlay").removeClass("os_td_overlay").removeClass('ps_td_overlay').removeClass("not_due_td_overlay");

          $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import");
          $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".import_icon").html("");
          $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".records_receive_label").removeClass("submitted_td").removeClass("due_td").removeClass("os_td").removeClass('ps_td').removeClass("not_due_td");
        }
        else{
          var id = $(e.target).parents("tr").find(".import_file_attachment_id_overlay").html();
          var current_id = $(".current_imported_id").html();
          if(current_id == id)
          {
            var cu_d = new Date();
            var cu_m = cu_d.getMonth() + 1;
            var cu_y = cu_d.getFullYear();
            var mm = month.substr(0, 2);
            if(cu_m < 10)
            {
              cu_m = '0'+cu_m;
            }

            if(cu_m == mm)
            {
              $(e.target).parents("tr").find(".import_icon_overlay").removeClass("orange_import_overlay").removeClass("red_import_overlay").removeClass("blue_import_overlay").removeClass("green_import_overlay").removeClass("white_import_overlay").addClass("orange_import_overlay");
              $(e.target).parents("tr").find(".import_icon_overlay").html("Submission Due");
              $(e.target).parents("tr").find(".records_receive_label_overlay").addClass("due_td_overlay");

              $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import").addClass("orange_import");
              $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".import_icon").html("Submission Due");
              $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".records_receive_label").addClass("due_td");
            }
            else if(mm > cu_m)
            {
              $(e.target).parents("tr").find(".import_icon_overlay").removeClass("orange_import_overlay").removeClass("red_import_overlay").removeClass("blue_import_overlay").removeClass("green_import_overlay").removeClass("white_import_overlay").addClass("white_import_overlay");
              $(e.target).parents("tr").find(".import_icon_overlay").html("Not Due");
              $(e.target).parents("tr").find(".records_receive_label_overlay").addClass("not_due_td_overlay");

              $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import").addClass("white_import");
              $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".import_icon").html("Not Due");
              $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".records_receive_label").addClass("not_due_td");
            }
            else{
              $(e.target).parents("tr").find(".import_icon_overlay").removeClass("orange_import_overlay").removeClass("red_import_overlay").removeClass("blue_import_overlay").removeClass("green_import_overlay").removeClass("white_import_overlay").addClass("red_import_overlay");
              $(e.target).parents("tr").find(".import_icon_overlay").html("Submission O/S");
              $(e.target).parents("tr").find(".records_receive_label_overlay").addClass("os_td_overlay");

              $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import").addClass("red_import");
              $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".import_icon").html("Submission O/S");
              $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".records_receive_label").addClass("os_td");
            }
          }
          else{
            $(e.target).parents("tr").find(".import_icon_overlay").removeClass("orange_import_overlay").removeClass("red_import_overlay").removeClass("blue_import_overlay").removeClass("green_import_overlay").removeClass("white_import_overlay").addClass("blue_import_overlay");
            $(e.target).parents("tr").find(".import_icon_overlay").html("Potentially Submitted");
            $(e.target).parents("tr").find(".records_receive_label_overlay").addClass("ps_td_overlay");

            $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import").addClass("blue_import");
            $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".import_icon").html("Potentially Submitted");
            $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month).find(".records_receive_label").addClass("ps_td");
          }
        }
      }
    })
  }
  if($(e.target).hasClass('load_ros_vat_due'))
  {
    $(".load_ros_vat_modal").modal("show");
    $(".process_import_file").prop("disabled",true);
    $("#import_file_tbody").html('<tr><td colspan="3">No Records Found</td></tr>')
    $("#hidden_import_file_name").val("");
    Dropzone.forElement("#imageUpload1").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
  }
  if($(e.target).hasClass('file_attachments'))
  {
    var src = $(e.target).attr("data-element");
    $(".attachment_pdf").attr("src",src);
    $("#show_pdf_viewer").modal("show");
  }
  if($(e.target).hasClass('load_vat_due'))
  {
    var filename = $("#hidden_import_file_name").val();
    if(filename == "")
    {
      alert("Please browse or drag and drop the csv file to import");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/check_valid_ros_due'); ?>",
        type:"post",
        data:{filename:filename},
        success:function(result)
        {
          if(result != "")
          {
            $("#import_file_tbody").html(result);
            $(".process_import_file").prop("disabled",false);
          }
          else{
            $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:#f00>This is not a valid CSV file to Import.</p>",width:"40%",height:"22%"});
          }
        }
      })
    }
  }
  if($(e.target).hasClass('process_import_file'))
  {
    var filename = $("#hidden_import_file_name").val();
    var load_all_option = $("#load_all_clients_status").val();
    window.location.replace("<?php echo URL::to('user/process_vat_reviews?filename='); ?>"+filename+"&load_all="+load_all_option);
  }
  if($(e.target).hasClass('period_change'))
  {
    var month = $(e.target).attr("data-month");
    var client = $(e.target).attr("data-client");
    $("#hidden_month_year_period").val(month);
    $("#hidden_client_id_period").val(client);
    $(".period_change_modal").modal("show");
    $(".from_change_period").val("");
    $(".to_change_period").val("");
  }
  if($(e.target).hasClass('change_period_submit'))
  {
    var month = $("#hidden_month_year_period").val();
    var client = $("#hidden_client_id_period").val();
    var from = $(".from_change_period").val();
    var to = $(".to_change_period").val();

    $.ajax({
      url:"<?php echo URL::to('user/change_period_vat_reviews'); ?>",
      type:"post",
      data:{month:month,client:client,from:from,to:to},
      success:function(result)
      {
        $(".tasks_tr_"+client).find("#add_files_vat_client_"+month).find(".period_import").html(from+' to '+to);
        $(".period_change_modal").modal("hide");
      }
    })
  }
  if($(e.target).hasClass('load_all_vat_clients'))
  {
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/load_all_vat_clients'); ?>",
      type:"post",
      success:function(result)
      {
        $("#load_all_clients_status").val("1");
        $("#task_body").html(result);
        $("#vat_expand").show();
        var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
        $(".date_text").datetimepicker({
           defaultDate: "",
           format: 'L',
           format: 'DD/MM/YYYY',
        });    
        $("body").removeClass("loading");
        ajax_functions();
      }
    })
  }
  if($(e.target).hasClass('add_attachment_month_year'))
  {
    var month_year = $(e.target).attr("data-element");
    var client = $(e.target).attr("data-client");
    $("#hidden_month_year_vat").val(month_year);
    $("#hidden_client_id_vat").val(client);
    $(".dropzone_progress_modal").modal("show");
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
  }
  if($(e.target).hasClass('add_attachment_month_year_overlay'))
  {
    var month_year = $(e.target).attr("data-element");
    var client = $(e.target).attr("data-client");
    $("#hidden_month_year_vat").val(month_year);
    $("#hidden_client_id_vat").val(client);
    $(".dropzone_progress_modal").modal("show");
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
  }
  if($(e.target).hasClass('show_prev_month'))
  {
    $("body").addClass("loading");
    var month_year = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/show_prev_month'); ?>",
      type:"post",
      dataType:"json",
      data:{month_year:month_year},
      success:function(result)
      {
        var cu_d = new Date();
        var cu_m = cu_d.getMonth() + 1;
        var cu_y = cu_d.getFullYear();
        var mm = month_year.substr(0, 2);
        var yy = month_year.substr(3, 7);
        if(cu_m < 10)
        {
          cu_m = '0'+cu_m;
        }

        var i = 0;
        $('#vat_expand').find('tr').each(function(){
          if(i == 0)
          {
            $(this).find('th').eq(3).html(result['prev'][i]);
            $(this).find('th').eq(4).html(result['curr'][i]);
            $(this).find('th').eq(5).html(result['next'][i]);
          }
          else{
            $(this).find('td').eq(3).html(result['prev'][i]);
            $(this).find('td').eq(4).html(result['curr'][i]);
            $(this).find('td').eq(5).html(result['next'][i]);

            var get_month = $(this).find("td").eq(3).find(".add_attachment_month_year").attr("data-element");
            $(this).find('td').eq(3).attr("id","add_files_vat_client_"+get_month);

            var get_month = $(this).find("td").eq(4).find(".add_attachment_month_year").attr("data-element");
            $(this).find('td').eq(4).attr("id","add_files_vat_client_"+get_month);

            var get_month = $(this).find("td").eq(5).find(".add_attachment_month_year").attr("data-element");
            $(this).find('td').eq(5).attr("id","add_files_vat_client_"+get_month);
          }
          i++;
        });
        $("body").removeClass("loading");
        ajax_functions();   
      }
    })
  }
  if($(e.target).hasClass('show_next_month'))
  {
    $("body").addClass("loading");
    var month_year = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/show_next_month'); ?>",
      type:"post",
      dataType:"json",
      data:{month_year:month_year},
      success:function(result)
      {
        var cu_d = new Date();
        var cu_m = cu_d.getMonth() + 1;
        var cu_y = cu_d.getFullYear();
        var mm = month_year.substr(0, 2);
        var yy = month_year.substr(3, 7);
        if(cu_m < 10)
        {
          cu_m = '0'+cu_m;
        }

        var i = 0;
        $('#vat_expand').find('tr').each(function(){
          if(i == 0)
          {
            $(this).find('th').eq(3).html(result['prev'][i]);
            $(this).find('th').eq(4).html(result['curr'][i]);
            $(this).find('th').eq(5).html(result['next'][i]);
          }
          else{
            $(this).find('td').eq(3).html(result['prev'][i]);
            $(this).find('td').eq(4).html(result['curr'][i]);
            $(this).find('td').eq(5).html(result['next'][i]);

            var get_month = $(this).find("td").eq(3).find(".add_attachment_month_year").attr("data-element");
            $(this).find('td').eq(3).attr("id","add_files_vat_client_"+get_month);

            var get_month = $(this).find("td").eq(4).find(".add_attachment_month_year").attr("data-element");
            $(this).find('td').eq(4).attr("id","add_files_vat_client_"+get_month);

            var get_month = $(this).find("td").eq(5).find(".add_attachment_month_year").attr("data-element");
            $(this).find('td').eq(5).attr("id","add_files_vat_client_"+get_month);
          }
          i++;
        });
         $("body").removeClass("loading");
        ajax_functions();
      }
    })
  }
  if($(e.target).hasClass('delete_attachments'))
  {
    e.preventDefault();
    var hrefval = $(e.target).attr("href");
    var client = $(e.target).attr("data-client");
    var month = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to delete this file?");
    if(r)
    {
      $.ajax({
        url:hrefval,
        type:"post",
        success:function(result)
        {
          $(e.target).parents("p:first").detach();
          $(".tasks_tr_"+client).find("#add_files_vat_client_"+month).find(".attachment_div").find('.delete_attachments[href="'+hrefval+'"]').parents("p:first").detach();
        }
      })
    }
  }
  if($(e.target).hasClass('show_incomplete'))
  {
    if($(e.target).is(":checked"))
    {
      $(".deactivated_tr").hide();
    }
    else{
      $(".tasks_tr").show();
    }
  }
  if($(e.target).hasClass("dropzone"))
  {
    $(e.target).parents('td').find('.img_div').show();    
    $(e.target).parents('.modal-body').find('.img_div').show();    
  }
  if($(e.target).hasClass("remove_dropzone_attach"))
  {
    $(e.target).parents('td').find('.img_div').show();   
    $(e.target).parents('.modal-body').find('.img_div').show(); 
  }
  if($(e.target).parent().hasClass("dz-message"))
  {
    $(e.target).parents('td').find('.img_div').show();
    $(e.target).parents('.modal-body').find('.img_div').show(); 
  }
  if($(e.target).hasClass('remove_dropzone_attach'))

  {

    var attachment_id = $(e.target).attr("data-element");

    var file_id = $(e.target).attr("data-task");

    $.ajax({

      url:"<?php echo URL::to('user/infile_remove_dropzone_attachment'); ?>",

      type:"post",

      data:{attachment_id:attachment_id,file_id:file_id},

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
  
  if($(e.target).hasClass('import_button')) {
    $(".import_modal").modal("show");
  }
  if($(e.target).hasClass('compare_button')) {
    $(".compare_modal").modal("show");
  }
});
fileList = new Array();
Dropzone.options.imageUpload = {
    maxFiles: 200,
    acceptedFiles: ".pdf",
    maxFilesize:500000,
    timeout: 10000000,
    dataType: "HTML",
    parallelUploads: 1,
    maxfilesexceeded: function(file) {
        this.removeAllFiles();
        this.addFile(file);
    },
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading");
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id;
            $(".tasks_tr_"+obj.client_id).find("#add_files_vat_client_"+obj.month_year).find(".attachment_div").append("<p><a href='javascript:' data-element='"+obj.download_url+"' class='file_attachments'>"+obj.filename+"</a> <a href='"+obj.delete_url+"' class='fa fa-trash delete_attachments'></a></p>");
            $(".tasks_tr_"+obj.client_id).find("#add_files_vat_client_"+obj.month_year).find(".records_receive_label").attr("class","records_receive_label submitted_td");

            $(".shown_tr_"+obj.client_id+"_"+obj.month_year).find(".attachment_div_overlay").append("<p><a href='javascript:' data-element='"+obj.download_url+"' class='file_attachments'>"+obj.filename+"</a> <a href='"+obj.delete_url+"' class='fa fa-trash delete_attachments'></a></p>");
            $(".shown_tr_"+obj.client_id+"_"+obj.month_year).find(".records_receive_label_overlay").attr("class","records_receive_label_overlay submitted_td_overlay");

            $(".dropzone_progress_modal").modal("hide");

            if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
              var submitted_date = $(".tasks_tr_"+obj.client_id).find("#add_files_vat_client_"+obj.month_year).find(".submitted_import").val();
              if((/\d/.test(submitted_date)) && submitted_date != "")
              {
                var r = confirm("Are you sure you want to update the submission date?");
                if(r)
                {
                  $.ajax({
                    url:"<?php echo URL::to('user/check_submitted_date_vat_reviews'); ?>",
                    type:"post",
                    data:{month:obj.month_year,client:obj.client_id},
                    success:function(result)
                    {
                      $(".tasks_tr_"+obj.client_id).find("#add_files_vat_client_"+obj.month_year).find(".submitted_import").val(result);
                      $(".tasks_tr_"+obj.client_id).find("#add_files_vat_client_"+obj.month_year).find(".delete_submitted").show();

                      $(".shown_tr_"+obj.client_id+"_"+obj.month_year).find(".submitted_import_overlay").val(result);
                      $(".shown_tr_"+obj.client_id+"_"+obj.month_year).find(".delete_submitted").show();
                      $(".shown_tr_"+obj.client_id+"_"+obj.month_year).find(".date_sort_val").val(result);

                      $("body").removeClass("loading");
                    }
                  })
                }
                else{
                  $("body").removeClass("loading");
                }
              }
              else{
                $.ajax({
                  url:"<?php echo URL::to('user/check_submitted_date_vat_reviews'); ?>",
                  type:"post",
                  data:{month:obj.month_year,client:obj.client_id},
                  success:function(result)
                  {
                    $(".tasks_tr_"+obj.client_id).find("#add_files_vat_client_"+obj.month_year).find(".submitted_import").val(result);
                    $(".tasks_tr_"+obj.client_id).find("#add_files_vat_client_"+obj.month_year).find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import").addClass("green_import");
                    $(".tasks_tr_"+obj.client_id).find("#add_files_vat_client_"+obj.month_year).find(".import_icon").html("Submitted");
                    $(".tasks_tr_"+obj.client_id).find("#add_files_vat_client_"+obj.month_year).find(".delete_submitted").show();

                    $(".shown_tr_"+obj.client_id+"_"+obj.month_year).find(".submitted_import_overlay").val(result);
                    $(".shown_tr_"+obj.client_id+"_"+obj.month_year).find(".date_sort_val").val(result);
                    $(".shown_tr_"+obj.client_id+"_"+obj.month_year).find(".import_icon_overlay").removeClass("orange_import_overlay").removeClass("red_import_overlay").removeClass("blue_import_overlay").removeClass("green_import_overlay").removeClass("white_import_overlay").addClass("green_import_overlay");
                    $(".shown_tr_"+obj.client_id+"_"+obj.month_year).find(".import_icon_overlay").html("Submitted");
                    $(".shown_tr_"+obj.client_id+"_"+obj.month_year).find(".delete_submitted_overlay").show();

                    $("body").removeClass("loading");
                  }
                })
              }
            }
        });
        this.on("complete", function (file, response) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            Dropzone.forElement("#imageUpload").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
          }
          
        });
        this.on("error", function (file) {
            $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            //$.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};
Dropzone.options.imageUpload1 = {
    maxFiles: 1,
    acceptedFiles: ".csv",
    maxFilesize:500000,
    timeout: 10000000,
    dataType: "HTML",
    parallelUploads: 1,
    addRemoveLinks: true,
    maxfilesexceeded: function(file) {
        this.removeAllFiles();
        this.addFile(file);
    },
    init: function() {
        this.on('sending', function(file) {
            $("body").addClass("loading");
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id;
            $("body").removeClass("loading");
            $("#hidden_import_file_name").val(obj.filename);
        });
        this.on("complete", function (file, response) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            //Dropzone.forElement("#imageUpload1").removeAllFiles(true);
            //$(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
          }
          
        });
        this.on("error", function (file) {
            $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
            $("body").removeClass("loading");
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            $.get("<?php echo URL::to('user/remove_vat_csv'); ?>"+"/"+file.serverId);
        });
    },
};
</script>

@stop