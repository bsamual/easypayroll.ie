@extends('userheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>
<script src="http://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<style>
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
.nav>li>a:focus, .nav>li>a:hover { background: #d6d6d6;
    color: #000;
    font-weight: 700; }
.nav-item .active { background: #d6d6d6;
    color: #000;
    font-weight: 700; }
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
<div class="modal fade dropzone_progress_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Add Attachments</h4>
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
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Import ROS Outstanding VAT Returns File Manager</h4>
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
                <table class="table">
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
                <table class="table">
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

<div class="content_section">
<?php
  if(Session::has('message')) { ?>
         <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
<?php } ?>
  <div class="message_edit">
  </div>
  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="page_title">
      <h4 class="col-lg-12 padding_00 new_main_title">VAT Management System</h4>
    </div>
  </div>
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link" href="<?php echo URL::to('user/vat_clients'); ?>">VAT Clients Imported in to VAT Management System</a>
    </li>
    <li class="nav-item">
      <a class="nav-link active" href="<?php echo URL::to('user/vat_review'); ?>">VAT Management System VAT Review</a>
    </li>
  </ul>
  <div class="col-md-6" style="margin-top: 20px;">
  </div>
  <div class="select_button">
    <div class="col-lg-2" style="padding: 0px; line-height: 35px;">
      <a href="javascript:" class="common_black_button load_all_vat_clients">Load All Clients</a>
    </div>
    <div class="col-lg-2" style="padding: 0px; line-height: 35px;">
      <input type="checkbox" name="show_incomplete" class="show_incomplete" id="show_incomplete" value="1"><label for="show_incomplete">Hide/Show Deactivated client </label>
    </div>
    <div class="col-lg-8">
      <a href="javascript:" class="common_black_button load_ros_vat_due" style="float:right">Load ROS VAT due Extract</a>
      <?php 
      $current_import_file = DB::table('vat_reviews_import_attachment')->where('status',1)->orderBy('id','desc')->first();
      if(count($current_import_file))
      {
        ?>
        <div style="float: right;margin-right: 45px;margin-top: -4px;">
          <spam style="font-weight:600">Imported Date: </spam><spam><?php echo $current_import_file->import_date; ?></spam><br/>
          <spam style="font-weight:600">Imported Time: </spam><spam><?php echo $current_import_file->import_time; ?></spam>
        </div>
        <div style="float: right;margin-right: 65px;margin-top: -4px;">
          <spam style="font-weight:600">Current Imported File: </spam><spam><?php echo $current_import_file->uploaded_filename; ?></spam><br/>
          <spam style="font-weight:600">Imported File ID: </spam><spam class="current_imported_id"><?php echo $current_import_file->import_id; ?></spam>
        </div>
        <?php
      }
      ?>
    </div>
    <div class="col-lg-12" style="padding: 0px; line-height: 35px;">

      <table class="table table-fixed-header" id="vat_expand" align="left" style="min-width:70%;margin-top:10px;display:none">
        <thead>
          <tr>
              <th style="width:10%;text-align: left;">Client Code <i class="fa fa-sort sno_sort" style="float:right" aria-hidden="true"></i></th>
              <th style="width:20%;text-align: left; ;">Client Name <i class="fa fa-sort client_sort" style="float:right" aria-hidden="true"></i></th>
              <th style="width:10%;text-align: left; ">Tax No <i class="fa fa-sort tax_sort" style="float:right" aria-hidden="true"></i></th>
              <th style="width:20%;text-align: left; "><a href="javascript:" class="fa fa-arrow-circle-left show_prev_month" title="Extend to Prev Month" data-element="<?php echo date('m-Y', strtotime('last month')); ?>"></a> &nbsp;&nbsp;<?php echo date('M-Y', strtotime('last month')); ?></th>
              <th style="width:20%;text-align: left; "><?php echo date('M-Y'); ?> </th>
              <th style="width:20%;text-align: left; "><?php echo date('M-Y', strtotime('next month')); ?> &nbsp;&nbsp; <a href="javascript:" class="fa fa-arrow-circle-right show_next_month" title="Extend to Next Month" data-element="<?php echo date('m-Y', strtotime('next month')); ?>"></a></th>
          </tr>
        </thead>
        <tbody id="task_body">
          
        </tbody>            
      </table>
    </div>
  </div>
</div>
<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<input type="hidden" name="client_sortoptions" id="client_sortoptions" value="asc">
<input type="hidden" name="tax_sortoptions" id="tax_sortoptions" value="asc">

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
  $(".submitted_import").datetimepicker({
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
            }
            else if(mm > cu_m)
            {
              $(e.target).parents("td").find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import").addClass("white_import");
              $(e.target).parents("td").find(".import_icon").html("Not Due");
            }
            else{
              $(e.target).parents("td").find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import").addClass("red_import");
              $(e.target).parents("td").find(".import_icon").html("Submission O/S");
            }
          }
          else{
            $(e.target).parents("td").find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import").addClass("blue_import");
            $(e.target).parents("td").find(".import_icon").html("Potentially Submitted");
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
    var r = confirm("Are you sure you want to delete this file?");
    if(r)
    {
      $.ajax({
        url:hrefval,
        type:"post",
        success:function(result)
        {
          $(e.target).parents("p:first").detach();
          
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
  if(e.target.id == "edit_client_details")
  {
      var name = $(".name_class").val();           
      var pemail = $(".pemail_class").val();
      var semail = $(".semail_class_edit").val();
      var salutation = $(".salutation_class_edit").val();
      var id = $(".name_id").val();
      var self_manage = $(".self_manage_class:checked").val();
      var always_nil = $(".always_nil_class:checked").val();
      var client_id = $("#hidden_client_id_edit").val();
      var hidden_salutation = $("#hidden_client_salutation_edit").val();
      if(pemail == "")
      {
        $(".error_pemail").text("Please Enter your Primary Email Address");
        return false;
      }
      if(name  == "")
      {
        $(".error_client_name").text("Please Enter Client Name");
        return false; 
      }
      $.ajax({
        url:"<?php echo URL::to('user/update_vat_clients'); ?>",
        type:"post",
        dataType:"json",
        data:{name:name,pemail:pemail,semail:semail,salutation:salutation,self:self_manage,always_nil:always_nil,id:id,client_id:client_id,hidden_salutation:hidden_salutation},
        success: function(result)
        {
          $(".bs-example-modal-sm").modal("hide");
          $(".task_"+id).find(".semail_sort_val").find("label").text(semail);
          $(".task_"+id).find(".pemail_sort_val").find("label").text(pemail);
          $(".task_"+id).find(".client_sort_val").find("label").text(name);
          if(result['cm_client_id'] != "")
          {
            $(".task_"+id).find(".icon_div").find(".fa").removeClass("fa-chain-broken").addClass("fa-link");            
            $(".task_"+id).find(".icon_div").css({"color":"blue"});
          }
          else{
            $(".task_"+id).find(".icon_div").find(".fa").removeClass("fa-link").addClass("fa-chain-broken");
            $(".task_"+id).find(".icon_div").css({"color":"red"});
          }
          
          $(".color_pallete_purple").attr("data-content",result['purple']+" Clients");
          $(".color_pallete_yellow").attr("data-content",result['yellow']+" Clients");
          $(".color_pallete_green").attr("data-content",result['green']+" Clients");
          $(".color_pallete_red").attr("data-content",result['red']+" Clients");

          if(result['status'] == "0")
          {
            if(pemail != "" && self_manage == "no")
            {
              $(".task_"+id).find(".client_sort_val").find("label").attr("style","color:green !important");
            }
            else if(pemail == "" && self_manage == "no")
            {
              $(".task_"+id).find(".client_sort_val").find("label").attr("style","color:#bd510a !important");
            }
            else if(self_manage == "yes")
            {
              $(".task_"+id).find(".client_sort_val").find("label").attr("style","color:purple !important");
            }
            else{
              $(".task_"+id).find(".client_sort_val").find("label").attr("style","color:#fff !important");
            }
          }
          $(".message_edit").html('<p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> Clients Updated successfully</p>');

        }
      });
  }
  if(e.target.id == "add_client_details")
  {
    var taxnumber = $(".error_taxnumber_add").text();
    if(taxnumber != "")
    {
      return false;
    }
    else{
      
    }
  }
  if($(e.target).hasClass("email_sent"))
  {
    $("body").addClass("loading");
    var id = $(e.target).attr("id");
    $.ajax({
      url:"<?php echo URL::to('user/email_sents'); ?>",
      type:"get",
      data:{id:id},
      success: function(result) {
        $("#client_email_sents_modal").modal("show");
        $("#client_email_sents").html(result);
        $(".saveaspdf").attr("data-element",id);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('saveaspdf'))
  {
    $("body").addClass("loading");
    var id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/email_sents_save_pdf'); ?>",
      type:"get",
      data:{id:id},
      success: function(result) {
        $("#client_email_sents_modal").modal("hide");
        $("body").removeClass("loading");
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
          return false; //this is critical to stop the click event which will trigger a normal file download
      }
    })
  }
  if($(e.target).hasClass('import_button')) {
    $(".import_modal").modal("show");
  }
  if($(e.target).hasClass('compare_button')) {
    $(".compare_modal").modal("show");
  }
  
  if($(e.target).hasClass('editclass')) {
    var editid = $(e.target).attr("id");
    console.log(editid);
    $.ajax({
        url: "<?php echo URL::to('user/edit_vat_clients') ?>"+"/"+editid,
        dataType:"json",
        type:"post",
        success:function(result){
           $(".bs-example-modal-sm").modal("toggle");
           $(".modal-content").css({"top":"90px"});
           $(".editsp").show();           
           $("#hidden_client_id_edit").val(result['cm_client_id']);
           $(".name_class").val(result['name']);           
           $(".taxnumber_class").val(result['taxnumber']);
           $(".pemail_class").val(result['pemail']);
           $(".semail_class_edit").val(result['semail']);
           $(".salutation_class_edit").val(result['salutation']);
           $(".client_search_class_edit").val(result['companyname']);
           $(".firstname_class_edit").val(result['firstname']);

           $(".name_id").val(result['id']);

            if(result['self_manage'] == 'yes')
            {
              $("#self_manage_class_yes").prop("checked",true);
            }
            else if(result['self_manage'] == 'no')
            {
              $("#self_manage_class_no").prop("checked",true);
            }
            else
            {
              $("#self_manage_class_yes").prop("checked",false);
              $("#self_manage_class_no").prop("checked",false);
            }

            if(result['always_nil'] == 'yes')
            {
              $("#always_nil_class_yes").prop("checked",true);
            }
            else if(result['always_nil'] == 'no')
            {
              $("#always_nil_class_no").prop("checked",true);
            }
            else
            {
              $("#always_nil_class_yes").prop("checked",false);
              $("#always_nil_class_no").prop("checked",false);
            }
      }
    });
  }
  if($(e.target).hasClass('addclass')) {
           $(".addclass_modal").modal("toggle");
           $(".modal-content").css({"top":"90px"});
  }
  if(e.target.id == "alert_submit")
  {
    var pemail = $(".pemail_update:checked").val();
    var semail = $(".semail_update:checked").val();
    var salutation = $(".salutation_update:checked").val();

    if(pemail == "" || typeof pemail === "undefined" || semail == "" || typeof semail === "undefined" || salutation == "" || typeof salutation === "undefined")
    {
      alert("Please select yes/no for all the questions.");
    }
    else{
      var clientid = $("#hidden_client_id").val();
      
      if(pemail == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclientemail'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $(".primaryemail_class").val(result);
          }
        });
      }
      if(semail == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclientemail_secondary'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $(".semail_class_add").val(result);
          }
        });
      }
      if(salutation == 1)
      {
        $("#hidden_client_salutation").val(1);
      }
      else{
        $("#hidden_client_salutation").val(0);
      }
      $("#alert_modal").modal("hide");
    }
  }
  if(e.target.id == "alert_submit_edit")
  {
    var pemail = $(".pemail_update_edit:checked").val();
    var semail = $(".semail_update_edit:checked").val();
    var salutation = $(".salutation_update_edit:checked").val();

    if(pemail == "" || typeof pemail === "undefined" || semail == "" || typeof semail === "undefined" || salutation == "" || typeof salutation === "undefined")
    {
      alert("Please select yes/no for all the questions.");
    }
    else{
      var clientid = $("#hidden_client_id_edit").val();
      if(pemail == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclientemail'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $(".primaryemail_class_edit").val(result);
          }
        });
      }
      if(semail == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclientemail_secondary'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $(".semail_class_edit").val(result);
          }
        });
      }
      if(salutation == 1)
      {
        $("#hidden_client_salutation_edit").val(1);
      }
      else{
        $("#hidden_client_salutation_edit").val(0);
      }
      $("#alert_modal_edit").modal("hide");
    }
  }
});



//setup before functions
var taxtypingTimer_add;                //timer identifier
var taxdoneTypingInterval_add = 1000;  //time in ms, 5 second for example
var $taxinput_add = $('.taxnumber_class_add');

//on keyup, start the countdown
$taxinput_add.on('keyup', function () {
  var taxinput_val_add = $(this).val();

  clearTimeout(taxtypingTimer_add);
  taxtypingTimer_add = setTimeout(taxdoneTyping_add, taxdoneTypingInterval_add,taxinput_val_add);
});

//on keydown, clear the countdown 
$taxinput_add.on('keydown', function () {
  clearTimeout(taxtypingTimer_add);
});

//user is "finished typing," do something
function taxdoneTyping_add (input) {
  $.ajax({
        url:"<?php echo URL::to('user/check_client_taxnumber'); ?>",
        type:"get",
        data:{taxnumber:input},
        success: function(result) {
          if(result == 1)
          {
            $(".error_taxnumber_add").text('Taxnumber Already Exists');
          }
          else{
            $(".error_taxnumber_add").text('');
          }
        }
      });
}
</script>

<script>
$(document).ready(function() {    
     $(".client_search_class").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/vat_client_search'); ?>",
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
            dataType: "json",
            url:"<?php echo URL::to('user/vat_client_search_select'); ?>",
            data:{value:ui.item.id},
            success: function(result){
              $(".tax_reg1class").val(result['taxreg']);
              $(".firstname_class").val(result['firstname']);
              $("#hidden_client_id").val(ui.item.id);
              $('#alert_modal').modal({backdrop: 'static', keyboard: false});
              $(".pemail_update_edit").prop("checked",false);
              $(".semail_update_edit").prop("checked",false);
              $(".salutation_update_edit").prop("checked",false);

              $(".pemail_update").prop("checked",false);
              $(".semail_update").prop("checked",false);
              $(".salutation_update").prop("checked",false);
            }
          })
        }
    });
     $(".client_search_class_edit").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/vat_client_search'); ?>",
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
          $("#client_search_edit").val(ui.item.id);          
          $.ajax({
            dataType: "json",
            url:"<?php echo URL::to('user/vat_client_search_select'); ?>",
            data:{value:ui.item.id},
            success: function(result){
              
              $(".firstname_class_edit").val(result['firstname']);
              $("#hidden_client_id_edit").val(ui.item.id);
              $('#alert_modal_edit').modal({backdrop: 'static', keyboard: false});

              $(".pemail_update_edit").prop("checked",false);
              $(".semail_update_edit").prop("checked",false);
              $(".salutation_update_edit").prop("checked",false);

              $(".pemail_update").prop("checked",false);
              $(".semail_update").prop("checked",false);
              $(".salutation_update").prop("checked",false);
            }
          })
        }
    });
});
</script>

<script>
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