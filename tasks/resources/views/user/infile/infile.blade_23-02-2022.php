@extends('userheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>
<script src="http://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<link rel="stylesheet" href="<?php echo URL::to('assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/slideupdown.js'); ?>"></script>
<style>
  .start_group{clear:both;}
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
.imported_date{
  margin-left: 10px;
    padding: 5px;
    width: 30%;
    outline: none;
    margin-top: 10px;
}
/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.content_check{
  word-wrap:break-word; /*old browsers*/
  overflow-wrap:break-word;
}
.overflow-wrap-hack{
  max-width:1px;
}
.modal_load_apply {
    display:    none;
    position:   fixed;
    z-index:    9999999999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
body.loading_apply {
    overflow: hidden;   
}
body.loading_apply .modal_load_apply {
    display: block;
}
.modal_load_number {
    display:    none;
    position:   fixed;
    z-index:    9999999999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
body.loading_number {
    overflow: hidden;   
}
body.loading_number .modal_load_number {
    display: block;
}
.modal_load_attachments {
    display:    none;
    position:   fixed;
    z-index:    9999999999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
body.loading_attachments {
    overflow: hidden;   
}
body.loading_attachments .modal_load_attachments {
    display: block;
}
.modal_load_available {
    display:    none;
    position:   fixed;
    z-index:    9999999999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
body.loading_available {
    overflow: hidden;   
}
body.loading_available .modal_load_available {
    display: block;
}

.modal_load_browse {
    display:    none;
    position:   fixed;
    z-index:    9999999999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
body.loading_browse {
    overflow: hidden;   
}
body.loading_browse .modal_load_browse {
    display: block;
}

.modal_load_review {
    display:    none;
    position:   fixed;
    z-index:    9999999999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
body.loading_review {
    overflow: hidden;   
}
body.loading_review .modal_load_review {
    display: block;
}


.modal_load_import {
    display:    none;
    position:   fixed;
    z-index:    9999999999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
body.loading_import {
    overflow: hidden;   
}
body.loading_import .modal_load_import {
    display: block;
}
.flag_gray{ color:gray; cursor: pointer }
.flag_orange{ color:orange; cursor: pointer }
.flag_red{ color:red; cursor: pointer }

.flag_gray_sub{ color:gray; cursor: pointer }
.flag_orange_sub{ color:orange; cursor: pointer }
.flag_red_sub{ color:red; cursor: pointer }

#colorbox { z-index:99999999999999999999 !important; }
.pdf_multipage {
float:right;
color: #f00;
font-size: 18px;
}
.disabled_prev { pointer-events: none; background: #dfdfdf !important; }
.fa-circle { color:green; }
.td_supplier { cursor: pointer; }
.tasks_drop {text-align: left !important; }
.error_files_notepad_add{color:#f00;}
.download_b_all_image{ cursor : pointer; }
.download_p_all_image{ cursor : pointer; }
.download_s_all_image{ cursor : pointer; }
.download_o_all_image{ cursor : pointer; }
.file_attachment_div{width:100%;}
.add_text{width:80px;}
.user_td_class{
  word-wrap: break-word; white-space:normal; min-width:150px; max-width: 150px;
}
.datepicker-only-init table tr th{border-top: 0px !important;}
.datepicker-only-init table tr td{border-top: 0px !important;}
.auto_save_date table tr th{border-top: 0px !important;}
.auto_save_date table tr td{border-top: 0px !important;}
.form-control[disabled]{background-color: #ccc !important;}
.fa-plus,.fa-plus-task, .fa-pencil-square{cursor: pointer;}
.dropzone .dz-preview.dz-image-preview {
    background: #949400 !important;
}
.remove_dropzone_attach_task{
  color:#f00 !important;
  margin-left:10px;
}
.remove_notepad_attach_task{
  color:#f00 !important;
  margin-left:10px;
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

.delete_all_image, .delete_all_notes_only, .delete_all_notes, .download_all_image, .download_rename_all_image, .download_all_notes_only, .download_all_notes, .summary_infile_attachments{cursor: pointer;}

.notepad_div {

    border: 1px solid #000;

    width: 400px;

    position: absolute;

    height: 250px;

    background: #dfdfdf;

    display: none;

}

.notepad_div textarea{

  height:212px;

}



.notepad_div_notes {

    border: 1px solid #000;

    width: 400px;

    position: absolute;

    height: 250px;

    background: #dfdfdf;

    display: none;

}

.notepad_div_notes textarea{

  height:212px;

}

.img_div_add{

        border: 1px solid #000;

    width: 280px;

    position: inherit !important;

    min-height: 118px;

    background: rgb(255, 255, 0);

    display:none;

}
table.dataTable.row-border tbody tr:first-child th, table.dataTable.row-border tbody tr:first-child td, table.dataTable.display tbody tr:first-child th, table.dataTable.display tbody tr:first-child td{ z-index:9; }
.notepad_div_notes_add,.notepad_div_notes_task {
    border: 1px solid #000;
    width: 280px;
    position: absolute;
    height: 250px;
    background: #dfdfdf;
    display: none;
}

.notepad_div_notes_add textarea,.notepad_div_notes_task textarea{

  height:212px;

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



.label_class{

  float:left ;

  margin-top:15px;

  font-weight:700;

}





.modal_load {

    display:    none;

    position:   fixed;

    z-index:    99999999999;

    top:        0;

    left:       0;

    height:     100%;

    width:      100%;

    background: rgba( 255, 255, 255, .8 ) 

                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 

                50% 50% 

                no-repeat;

}



.report_div{

    position: absolute;

    top: 35px; right: 15px;

    padding: 15px;

    background: #ff0;

    z-index: 999999;

    text-align: left;

}

.selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; border-radius: 3px;  }



.ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 13px; line-height: 20px; font-weight: 500 }

.ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff}

.ok_button:focus{background: #000; text-decoration: none; color: #fff}

.report_csv, .report_pdf{opacity: 1 !important}



.datepicker-only-init_date_received, .auto_save_date{cursor: pointer;}



.ui-widget{z-index: 999999999}

.ui-widget .ui-menu-item-wrapper{font-size: 14px; font-weight: bold;}

.ui-widget .ui-menu-item-wrapper:hover{font-size: 14px; font-weight: bold}



body.loading {

    overflow: hidden;   

}

body.loading .modal_load {

    display: block;

}

    .table thead th:focus{background: #ddd !important;}

    .form-control{border-radius: 0px;}

    .disabled{cursor :auto !important;pointer-events: auto !important}

    .disable_class{cursor :auto !important;pointer-events: none !important}

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

<?php
if(isset($_GET['client_id']))
{
  $client_id = $_GET['client_id'];
  $companydetails_val = DB::table('cm_clients')->where('client_id',$client_id)->first();
  $companyname_val = $companydetails_val->company.'-'.$client_id;
  $hiddenval = $_GET['client_id'];
}
else{
  $companyname_val = '';
  $hiddenval = '';
}
?>

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

.form-title{width: 100%; height: auto; float: left; margin-bottom: 5px;}



.submit_button{background: #000; text-align: center; padding: 8px 12px; color: #fff; float: left; border: none; font-size: 14px; font-weight: normal; transition: 0.3s; opacity: 0.6;}

.submit_button:hover{background: #000; opacity: 1;}

</style>
<?php 
  $admin_details = Db::table('admin')->first();
  $admin_cc = $admin_details->task_cc_email;
if(Session::has('countupdated')) 
{
  $countupdated = Session::get('countupdated');
  $total_count = Session::get('total_count');
  $message = Session::get('message');

  ?>
  <script>
    $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><?php echo $message; ?> Files Uploaded <?php echo $countupdated; ?> of <?php echo $total_count; ?> Files successfully</p>'});
  </script>
  <?php
}
elseif(Session::has('countupdated'))
{
  $message = Session::get('message');
  ?>
  <script>
    $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><?php echo $message; ?></p>'});
  </script>
  <?php
}
?>
<!--*************************************************************************-->
<div class="modal fade task_specifics_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:40%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close close_task_specifics" data-dismiss="modal" aria-label="Close"><span class="close_task_specifics" aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="font-weight:700;font-size:20px">Task Specifics: <spam class="task_title_spec"></spam></h4>
            <h5 class="title_task_details" style="font-size:18px;font-weight:600"></h5>
            <div class="user_ratings_div"></div>
          </div>
          <div class="modal-body" style="min-height: 193px;padding: 5px;">
            <label class="col-md-12" style="padding: 0px;">
              <label style="margin-top:10px">Existing Task Specific Comments:</label>

              <a href="javascript:" class="common_black_button download_pdf_spec" style="float: right;">Download as PDF</a> 
              <img src="<?php echo URL::to('assets/2bill.png'); ?>" class="2bill_image 2bill_image_comments" style="width:32px;margin-left:10px;float:right;margin-top: 4px;display:none" title="this is a 2Bill Task">
            </label>
            <div class="col-md-12" style="padding: 0px;">
              <div class="existing_comments" id="existing_comments" style="width:100%;background: #c7c7c7;padding:10px;min-height:300px;height:300px;overflow-y: scroll;font-size: 16px"></div>
            </div>

            <label class="col-md-12" style="margin-top:15px;padding: 0px">New Comment:</label>
            <div class="col-md-12" style="padding: 0px">
              <textarea name="new_comment" class="form-control new_comment" id="editor_1" style="height:150px"></textarea>
            </div>
          </div>
          <div class="modal-footer" style="padding: 18px 5px;">  
            <input type="hidden" name="hidden_task_id_task_specifics" id="hidden_task_id_task_specifics" value="">
            <input type="hidden" name="show_auto_close_msg" id="show_auto_close_msg" value="">
            
            <div class="col-md-12" style="padding:0px;margin-top:10px">
              <input type="button" class="common_black_button add_comment_allocate_to_btn" value="Add Comment and Allocate To" style="float: left;font-size:12px">
              <select name="add_comment_allocate_to" class="form-control add_comment_allocate_to" style="float: left;width:20%;font-size:12px">
                <option value="">Select User</option>
                <?php
                  if(count($userlist)){
                    foreach ($userlist as $user) {
                  ?>
                    <option value="<?php echo $user->user_id ?>"><?php echo $user->lastname.'&nbsp;'.$user->firstname; ?></option>
                  <?php
                    }
                  }
                ?>
              </select>

              <input type="button" class="common_black_button add_task_specifics" id="add_task_specifics" value="Add Comment Now" style="float: right;font-size:12px">
              <input type="button" class="common_black_button add_comment_and_allocate" id="add_comment_and_allocate" value="Add Comment and Allocate Back" style="float: right;font-size:12px">
              
              <div class="col-md-6" style="float:left;margin-top:10px;padding:0px">
                <input type="button" class="common_black_button add_progress_files_from_task_specifics" id="add_progress_files_from_task_specifics" value="Add Progress Files" data-element="" style="float: left;font-size:12px">
                <spam class="progress_spam" style="font-weight:600;color:green;margin-top:10px"></spam>
              </div>
              <div class="col-md-6" style="float:right;margin-top:10px;padding:0px">
                  <input type='checkbox' name="auto_close_task_comment" class="auto_close_task_comment" id="auto_close_task_comment" value="1"/> <label for="auto_close_task_comment" style="margin-top: 10px;">Make this task is an Auto Close Task</label>
              </div>
            </div>
          </div>
        </div>
  </div>
</div>
<div class="modal fade dropzone_progress_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Add Progress Files Attachments</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label>Select User</label>
                    <select class="form-control files_user_drop" required>
                      <option value="">Select User</option>
                      <?php
                      $selected = '';                      
                      if(count($userlist)){
                        foreach ($userlist as $user) {
                          if(Session::has('taskmanager_user'))
                          {
                            if($user->user_id == Session::get('taskmanager_user')) { $selected = 'selected'; }
                            else{ $selected = ''; }
                          }

                      ?>
                        <option value="<?php echo $user->user_id ?>" <?php echo $selected; ?>><?php echo $user->lastname.'&nbsp;'.$user->firstname; ?></option>
                      <?php
                        }
                      }
                      ?>
                    </select>
                    <spam class="error_files_user_drop"></spam>
                  </div>
                  
                </div>
                <div class="col-lg-12">
                  <div class="img_div_progress" style="display: block;">
                     <div class="image_div_attachments_progress">

                      <?php
                      if(Session::has('taskmanager_user'))
                          {
                            $session_user_id = Session::get('taskmanager_user');
                          }
                          else{
                            $session_user_id = '';
                          }
                      ?>
                      
                        <form action="<?php echo URL::to('user/infile_upload_images_taskmanager_progress'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUploadprogress" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">                            
                            <input name="hidden_task_id_progress" id="hidden_task_id_progress" type="hidden" value="">
                            <input type="hidden" value="<?php echo $session_user_id?>" id="files_user_hidden" name="user_id">
                        </form>

                        <div class="add_progress_attachments" style="display:none">

                        </div>
                     </div>
                  </div>
                  
                </div>
              </div>
          </div>
          <div class="modal-footer">  
            <a href="javascript:" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
          </div>
        </div>
  </div>
</div>
<div class="modal fade infiles_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:45%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Link Infiles</h4>
          </div>
          <div class="modal-body" id="infiles_body">  

          </div>
          <div class="modal-footer">  
            <input type="button" class="common_black_button" id="link_infile_button" value="Submit">
          </div>
        </div>
  </div>
</div>

<div class="modal fade summary_infile_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:45%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Summary</h4>
          </div>
          <div class="modal-body" id="summary_infile_tbody" style="clear:both">  

          </div>
          <div class="modal-footer" style="clear:both">  
          </div>
        </div>
  </div>
</div>
<div class="modal fade edit_description_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Edit Description</h4>
          </div>
          <div class="modal-body">  
            <h5>Description Text:</h5>
            <textarea name="description_edit_text" class="description_edit_text form-control" id="description_edit_text"> </textarea>
          </div>
          <div class="modal-footer">  
            <input type="hidden" name="edit_description_infile_id" class="edit_description_infile_id" value="">
            <input type="button" class="common_black_button edit_description_btn" id="edit_description_btn" value="Submit">
          </div>
        </div>
  </div>
</div>
<div class="modal fade integrity_check_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:70%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Integrity Check</h4>
            <input type="button" class="check_now common_black_button" value="Check Now" style="margin-top:-32px;margin-right:28px;float:right">
            <a href="javascript:" class="export_integrity_filename_a common_black_button" download style="margin-top:-32px;margin-right:28px;float:right">Export </a>
          </div>
          <div class="modal-body modal_max_height">  
            <div>
              <table class="table">
                <thead>
                    <th style="text-align:left;width:20%">Client</th>
                    <th style="text-align:left;width:10%">Client ID</th>
                    <th style="text-align:left;width:30%">Filename</th>
                    <th style="text-align:left;width:5%">Status</th>
                    <th style="text-align:left;width:5%">Action</th>
                    <th style="text-align:left;width:20%">Description</th>
                    <th style="text-align:left;width:10%">Date Added</th>
                </thead>
                <tbody id="integrity_check_body">

                </tbody>
              </table>
            </div>
            
            <div class="available_import_div">
              <div class="col-md-12">
                <h5>Summary: </h5>
                <p>Number of Files:<spam class="number_of_files"></spam></p>
                <p>Number of OK Files:<spam class="number_of_ok_files"></spam></p>
                <p>Number of Missing Files:<spam class="number_of_missing_files"></spam></p>
              </div>
              <form name="availability_form" id="availability_form" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-2">
                      <h5 style="font-weight:700;font-weight:18px">Location Path:</h5>
                    </div>
                    <div class="col-md-6">
                      <input type="file" name="location_path" class="form-control location_path" id="location_path" webkitdirectory directory multiple/>

                    </div>
                    <div class="col-md-4">
                      <input type="button" name="review_location" class="review_location common_black_button" value="Review Location"> 
                    </div>

                    <div class="col-md-2">&nbsp;</div>
                    <div class="col-md-6">
                      <input type="button" name="import_available_files" class="import_available_files common_black_button" value="Import Available Files"> 
                    </div>
                </div>
              </form>
            </div>
          </div>
          <div class="modal-footer" style="text-align:left">
             <div class="export_integrity_filename" id="export_integrity_filename">
              <h5>Download</h5>

             </div>
          </div>
        </div>
  </div>
</div>
<div class="modal fade download_option_p_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">What do you want to download?</h4>
          </div>
          <div class="modal-body">  
          	<p><input type="radio" name="download_p_files" class="download_p_files" id="p_files_only" value="1"> <label for="p_files_only">Files Only</label></p>
      		<p><input type="radio" name="download_p_files" class="download_p_files" id="p_s_only" value="2"> <label for="p_s_only">P/S Data</label></p>
      		<p><input type="radio" name="download_p_files" class="download_p_files" id="p_both" value="3"> <label for="p_both">Both</label></p>
          </div>
          <div class="modal-footer">  
            <input type="button" class="common_black_button" id="download_p_all_button" value="Submit">
          </div>
        </div>
  </div>
</div>
<div class="modal fade download_option_s_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">What do you want to download?</h4>
          </div>
          <div class="modal-body">  
      		<p><input type="radio" name="download_s_files" class="download_s_files" id="s_files_only" value="1"> <label for="s_files_only">Files Only</label></p>
      		<p><input type="radio" name="download_s_files" class="download_s_files" id="s_p_only" value="2"> <label for="s_p_only">P/S Data</label></p>
      		<p><input type="radio" name="download_s_files" class="download_s_files" id="s_both" value="3"> <label for="s_both">Both</label></p>
          </div>
          <div class="modal-footer">  
            <input type="button" class="common_black_button" id="download_s_all_button" value="Submit">
          </div>
        </div>
  </div>
</div>
<div class="modal fade supplier_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Supplier/Customer <input type="button" name="build_supplier" class="common_black_button build_supplier" value="Build" style="float:right;margin-right: 15px;"></h4>

          </div>
          <div class="modal-body">  
          	<textarea name="supplier_text" class="form-control supplier_text" style="height:150px"></textarea>
          	<p style="color:#f00;margin-top: 10px; font-weight: 600;">Note: Please add a Supplier/Customer values in comma seperated text.</p>
          </div>
          <div class="modal-footer">  
            <input type="button" class="common_black_button supplier_button" id="supplier_button" value="Submit">
            <input type="hidden" name="hidden_supplier_file_id" id="hidden_supplier_file_id" value="">
          </div>
        </div>
  </div>
</div>
<div class="modal fade create_new_task_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;overflow-y: scroll">
  <div class="modal-dialog modal-sm" role="document" style="width:45%">
    <form action="<?php echo URL::to('user/create_new_taskmanager_task')?>" method="post" class="add_new_form" id="create_task_form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight: 700; font-size: 20px;">New Task Creator</h4>
          </div>
          <div class="modal-body">            
            <div class="row"> 
                <div class="col-md-2">
                	<label style="margin-top:5px">Author:</label>
                </div>
                <div class="col-md-3">
                	<select name="select_user" class="form-control select_user_author" required>
	                  <option value="">Select User</option>        
	                    <?php
	                    $userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
	                    $selected = '';
	                    if(count($userlist)){
	                      foreach ($userlist as $user) {
	                    ?>
	                      <option value="<?php echo $user->user_id ?>"><?php echo $user->lastname.'&nbsp;'.$user->firstname; ?></option>
	                    <?php
	                      }
	                    }
	                    ?>
	                </select>
                </div>
                <div class="col-md-2">
                  <label style="margin-top:5px">Author Email:</label>
                </div>
                <div class="col-md-4">
                  <input  type="email" class="form-control author_email" name="author_email" placeholder="Enter Author's Email" required>
                </div>
            </div>
            
            <div class="row" style="margin-top:7px">
                <div class="col-md-2">
                  <label style="margin-top:5px">Allocate To:</label>
                </div>
                <div class="col-md-3">
                  <select name="allocate_user" class="form-control allocate_user_add">
                    <option value="">Select User</option>        
                      <?php
                      $selected = '';
                      if(count($userlist)){
                        foreach ($userlist as $user) {
                          if(Session::has('task_manager_user'))
                          {
                            if($user->user_id == Session::get('task_manager_user')) { $selected = 'selected'; }
                            else{ $selected = ''; }
                          }
                      ?>
                        <option value="<?php echo $user->user_id ?>" <?php echo $selected; ?>><?php echo $user->lastname.'&nbsp;'.$user->firstname; ?></option>
                      <?php
                        }
                      }
                      ?>
                  </select>
                </div>

                <div class="col-md-2">
                  <label style="margin-top:5px">Allocate To Email:</label>
                </div>
                <div class="col-md-3">
                  <input  type="email" class="form-control allocate_email" name="allocate_email" placeholder="Enter Allocate's Email" required>
                </div>
                <div class="col-md-2" style="padding:0px">
                  <div style="margin-top:5px">
                    <input type='checkbox' name="open_task" id="open_task" value="1"/>
                    <label for="open_task">OpenTask</label>
                  </div>
                </div>
            </div>
            <div class="row" style="margin-top:14px">
            	<div class="col-md-2 client_group">
                	<label style="margin-top:5px">Client:</label>
                </div>
                <?php
                if(isset($_GET['client_id']))
                {
                	$client_id = $_GET['client_id'];
                	$client_details = DB::table('cm_clients')->where('client_id',$client_id)->first();
                	$company = $client_details->company.'-'.$client_id;
                }
                else{
                	$client_id = '';
                	$company = '';
                }
                ?>
                <div class="col-md-8 client_group">
                	<input  type="text" class="form-control client_search_class_task" name="client_name" placeholder="Enter Client Name / Client ID" value="<?php echo $company; ?>" required disabled>
                	<input type="hidden" id="client_search_task" name="clientid" value="<?php echo $client_id; ?>"/>
                </div>

                <div class="col-md-3 internal_tasks_group" style="display: none;">
                	<label style="margin-top:5px">Select Task:</label>
                </div>
                <div class="col-md-7 internal_tasks_group" style="display: none;">
                	<div class="dropdown" style="width: 100%">
		                <a class="btn btn-default dropdown-toggle tasks_drop" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%">
		                  <span class="task-choose_internal">Select Task</span>  <span class="caret"></span>                          
		                </a>
		                <ul class="dropdown-menu internal_task_details" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">
		                  <li><a tabindex="-1" href="javascript:" class="tasks_li_internal">Select Task</a></li>
		                    <?php
		                    $taskslist = DB::table('time_task')->where('task_type', 0)->orderBy('task_name', 'asc')->get();
		                    if(count($taskslist)){
		                      foreach ($taskslist as $single_task) {
		                        if($single_task->task_type == 0){
		                          $icon = '<i class="fa fa-desktop" style="margin-right:10px;"></i>';
		                        }
		                        else if($single_task->task_type == 1){
		                          $icon = '<i class="fa fa-users" style="margin-right:10px;"></i>';
		                        }
		                        else{
		                          $icon = '<i class="fa fa-globe" style="margin-right:10px;"></i>';
		                        }
		                    ?>
		                      <li><a tabindex="-1" href="javascript:" class="tasks_li_internal" data-element="<?php echo $single_task->id?>" data-project="<?php echo $single_task->project_id; ?>"><?php echo $icon.$single_task->task_name?></a></li>
		                    <?php
		                      }
		                    }
		                    ?>
		                </ul>
		                <input type="hidden" name="idtask" id="idtask" value="">
		              </div>
                </div>
                <div class="col-md-2" style="padding:0px">
                  <div style="margin-top:5px">
                	  <!-- <input type='checkbox' name="internal_checkbox" id="internal_checkbox" value="1" disabled />
                  	<label for="internal_checkbox">Internal</label> -->
                  </div>
                </div>
            </div>
            <div class="form-group start_group" style="margin-top:20px">

              <div class="row">
                <div class="col-lg-5">
                  <div class="row">
                    <div class="col-md-5">
                      <div class="form-title"><label style="margin-top:5px">Priority:</label></div>
                    </div>
                    <div class="col-md-7" style="padding-top: 5px;">
                      <?php echo user_rating(); ?>
                    </div>
                  </div>
                  
                </div>
                <div class="col-lg-7">
                  <div class="row" style="margin-top:0px">
                    <div class="col-md-3">
                        <label style="margin-top:5px">Creation Date:</label>
                      </div>
                      <div class="col-md-9">
                        <label class="input-group datepicker-only-init_date_received">
                            <input type="text" class="form-control created_date" placeholder="Select Creation Date" name="created_date" style="font-weight: 500;" required />
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-calendar"></i>
                            </span>
                        </label>
                      </div>
                  </div>
                </div>
              </div>


              
            </div>
            <div class="form-group start_group" style="margin-top:10px">
                <div class="row">
                  <div class="col-lg-2">
                    <div class="form-title"><label style="margin-top:5px">Subject:</label></div>
                  </div>
                  <div class="col-lg-10">
                    <input  type="text" class="form-control subject_class" name="subject_class" placeholder="Enter Subject">
                  </div>
                </div>

                
                
            </div>
            <div class="form-group start_group task_specifics_add">

                <div class="row">
                  <div class="col-lg-7">
                    <div class="form-title" style="float:none"><label style="margin-top:5px">Task Specifics:</label></div>
                  </div>
                  <div class="col-lg-5">
                    <div class="form-group date_group" style="float: right;">
                      <div class="form-title" style="font-weight:600;">
                        <input type='checkbox' name="2_bill_task" class="2_bill_task" id="2_bill_task0" value="1"/> 
                        <label for="2_bill_task0" style="color:green">This task is a 2Bill Task!</label>
                        <img src="<?php echo URL::to('assets/2bill.png')?>" style="width:40px;margin-left:8px">
                      </div>
                  </div>

                  </div>
                  <div class="col-lg-12">
                    <textarea class="form-control task_specifics" id="editor_2" name="task_specifics" placeholder="Enter Task Specifics" style="height:400px"></textarea>
                  </div>
                </div>


                
                
            </div>
            <div class="form-group date_group">
                <div class="col-md-1" style="padding:0px">
                  <label style="margin-top:5px">DueDate:</label>
                </div>
                <div class="col-md-3">
                  <label class="input-group datepicker-only-init_date_received">
                      <input type="text" class="form-control due_date" placeholder="Select Due Date" name="due_date" style="font-weight: 500;" required />
                      <span class="input-group-addon">
                          <i class="glyphicon glyphicon-calendar"></i>
                      </span>
                  </label>
                </div>
                <div class="col-md-1" style="padding:0px">
                  <label style="margin-top:5px">Project:</label>
                </div>
                <div class="col-md-3">
                    <select name="select_project" class="form-control select_project">
                      <option value="">Select Project</option>
                      <?php
                          $projects = DB::table('projects')->get();
                          if(count($projects)){
                            foreach($projects as $project){
                              ?>
                              <option value="<?php echo $project->project_id; ?>"><?php echo $project->project_name; ?></option>
                              <?php
                            }
                          }
                      ?>
                    </select>
                </div>
                <div class="col-md-2" style="padding:0px">
                  <label style="margin-top:5px">Project Time:</label>
                </div>
                <div class="col-md-1" style="padding:0px">
                    <select name="project_hours" class="form-control project_hours">
                      <option value="">HH</option>
                      <?php
                      for($i = 0; $i <= 23; $i++)
                      {
                        if($i < 10) { $i = '0'.$i; }
                        ?>
                        <option value="{{$i}}">{{$i}}</option>
                        <?php
                      }
                      ?>
                    </select>
                </div>
                <div class="col-md-1" style="padding:0px">
                    <select name="project_mins" class="form-control project_mins">
                      <option value="">MM</option>
                      <?php
                      for($i = 0; $i <= 59; $i++)
                      {
                         if($i < 10) { $i = '0'.$i; }
                        ?>
                        <option value="{{$i}}">{{$i}}</option>
                        <?php
                      }
                      ?>
                    </select>
                </div>
            </div>
            <div class="form-group start_group retreived_files_div">

            </div>

            <div class="row" style="padding-top: 10px; clear: both;">
              <div class="col-lg-8">
                <div class="form-group start_group">
                  <label>Task Files: </label>
                  <a href="javascript:" class="fa fa-plus fa-plus-task" style="margin-top:10px; margin-left: 10px;" aria-hidden="true" title="Add Attachment"></a> 
                  <a href="javascript:" class="fa fa-pencil-square fanotepadtask" style="margin-top:10px; margin-left: 10px;" aria-hidden="true" title="Add Completion Notes"></a>
                  <a href="javascript:" class="infiles_link" style="margin-top:10px; margin-left: 10px;">Infiles</a>
                  <input type="hidden" name="hidden_infiles_id" id="hidden_infiles_id" value="">
                  <div class="img_div img_div_task" style="z-index:9999999; min-height: 275px">
                    <form name="image_form" id="image_form" action="" method="post" enctype="multipart/form-data" style="text-align: left;">
                    </form>
                    <div class="image_div_attachments">
                      <p>You can only upload maximum 300 files at a time. If you drop more than 300 files then the files uploading process will be crashed. </p>
                      <form action="<?php echo URL::to('user/infile_upload_images_taskmanager_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload5" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                          <input name="_token" type="hidden" value="">
                      </form>              
                    </div>
                   </div>
                   <div class="notepad_div_notes_task" style="z-index:9999; position:absolute;display:none">
                      <textarea name="notepad_contents_task" class="form-control notepad_contents_task" placeholder="Enter Contents"></textarea>
                      <input type="button" name="notepad_submit_task" class="btn btn-sm btn-primary notepad_submit_task" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                      <spam class="error_files_notepad_add"></spam>
                  </div>
                </div>
                
                <p id="attachments_text_task" style="display:none; font-weight: bold;">Files Attached:</p>
                <div id="add_attachments_div_task">
                </div>
                <div id="add_notepad_attachments_div_task">
                </div>
                <p id="attachments_infiles" style="display:none; font-weight: bold;">Linked Infiles:</p>
                <div id="add_infiles_attachments_div">
                </div>
              </div>
              <div class="col-lg-4">
                <div class="form-group date_group">
                    <div class="form-title" style="font-weight:600;margin-left:-10px"><input type='checkbox' name="auto_close_task" class="auto_close_task" id="auto_close_task0" value="1"/> <label for="auto_close_task0">This task is an Auto Close Task</label></div>
                </div>
                <div class="form-group date_group">
                    <div class="form-title" style="font-weight:600;margin-left:-10px;float:none"><input type='checkbox' name="accept_recurring" class="accept_recurring" id="recurring_checkbox0" value="1" checked/> <label for="recurring_checkbox0">Recurring Task</label></div>
                    <div class="accept_recurring_div">
                      <p>This Task is repeated:</p>
                      <div class="form-title" style="float:none">
                        <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox1" value="1" checked/>
                        <label for="recurring_checkbox1">Monthly</label>
                      </div>
                      <div class="form-title" style="float:none">
                        <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox2" value="2"/>
                        <label for="recurring_checkbox2">Weekly</label>
                      </div>
                      <div class="form-title" style="float:none">
                        <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox3" value="3"/>
                        <label for="recurring_checkbox3">Daily</label>
                      </div>
                      <div class="form-title" style="float:none">
                        <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox4" value="4"/>
                        <label for="recurring_checkbox4">Specific Number of Days</label>
                        <input type="number" name="specific_recurring" class="specific_recurring" value="" style="width: 29%;height: 25px;">
                      </div>
                    </div>
                </div>
              </div>
            </div>


            
            
            
          </div>
          <div class="modal-footer">     
            <input type="hidden" name="action_type" id="action_type" value="">
            <input type="hidden" name="hidden_specific_type" id="hidden_specific_type" value="">
            <input type="hidden" name="hidden_attachment_type" id="hidden_attachment_type" value="">
            <input type="hidden" name="hidden_task_id_copy_task" class="hidden_task_id_copy_task" value="">
            <input type="hidden" name="total_count_files" id="total_count_files" value="">
            <input type="submit" class="common_black_button make_task_live" value="Make Task Live" style="width: 100%;">
          </div>
        </div>
    </form>
  </div>
</div>
<div class="modal fade model_notify" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="width:25%">
    <form action="" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title notify_title" id="myModalLabel">Notify</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-lg-12">
            </div>
              <input type="checkbox" name="" id="notity_selectall"><label for="notity_selectall">Select All</label>
              <table id="dtBasicExample" class="table">
                <thead>
                  <tr>
                    <th scope="col" style="text-align: left">S.No</th>
                    <th scope="col" style="text-align: left">Name</th>
                  </tr>
                </thead>
                <tbody>
              <?php
              $userlist_notify = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
              $i = 1;
              if(count($userlist_notify)){
                foreach ($userlist_notify as $user) {
                ?>
                  <tr>
                    <td scope="row"><?php echo $i; ?> <input type="checkbox" class="notify_id_class" name="username" id="user_<?php echo $user->user_id?>" data-element="<?php echo $user->email; ?>" data-value="<?php echo $user->user_id; ?>"><label>&nbsp;</label></td>
                    <td><label for="user_<?php echo $user->user_id?>"><?php echo $user->lastname.' '.$user->firstname; ?></label></td>
                  </tr>
                <?php
                $i++;
                }
              }
              ?>
              </tbody>
              </table>
          </div>
      </div>
      <div class="modal-footer">
          <input type="hidden" class="notify_file_id" value="" name="">
          <input type="button" class="btn btn-primary common_black_button notify_all_clients_tasks" value="Send Email">
      </div>
    </div>
    </form>
  </div>
</div>


<div class="modal fade report_infile_model" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Infile Report</h4>
        </div>

        <div class="modal-body" style="height: 400px; overflow-y: scroll;">

          <div class="col-md-2 select_all" style="padding: 0px;"><input type="checkbox" class="select_all_class" id="select_all_class" value="1" style="padding-top: 20px;"><label for="select_all_class" style="font-size: 14px; font-weight: normal;">Select all</label>
          </div>
          <div class="col-md-6">
            <input type="checkbox" id="show_incomplete_report" checked=""><label for="show_incomplete_report" style="font-size: 14px; font-weight: normal;">Show Incomplete Files</label>

            <input type="hidden" class="report_show_incomplete">

          </div>


            <table class="table">
              <thead>
              <tr style="background: #fff;">
                  <th width="5%" style="text-align: left;">S.No</th>
                  <th width="5%" style="text-align: left;"></th>
                  <th style="text-align: left;">Client ID</th>
                  <th style="text-align: left;">First Name</th>    
                  <th style="text-align: left;">Company Name</th>                  
              </tr>
              </thead>
              <tbody id="report_tbody">

              </tbody>
          </table>
        </div>
        <div class="modal-footer">
            <div class="single_client_button" style="display: none">
              <input type="button" class="common_black_button" id="single_save_as_csv" value="Save as CSV">
              <input type="button" class="common_black_button" id="single_save_as_pdf" value="Save as PDF">
            </div>
            <div class="all_client_button" style="display: none">
              <input type="button" class="common_black_button" id="save_as_csv" value="Save as CSV">
              <input type="button" class="common_black_button" id="save_as_pdf" value="Save as PDF">
            </div>
        </div>

      </div>

  </div>

</div>







<div class="modal fade create_new_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;">

  <div class="modal-dialog modal-sm" role="document">

    <form action="<?php echo URL::to('user/create_new_file')?>" method="post" class="add_new_form" id="create_job_form">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title job_title">File Received Manager</h4>

          </div>

          <div class="modal-body">            

            <div class="form-group client_group"> 

                <div class="form-title">Select a Client <span><i class="fa fa-info-circle" style="font-size: 13px; cursor: pointer;" data-toggle="tooltip" title="Please make sure that you select a client from the auto-complete result shown below as you type, only then Create New button will be enabled.

"></i></span></div>

                <input  type="text" class="form-control client_search_class" name="client_name" placeholder="Enter Client Name / Client ID" required value="<?php echo $companydetails_val->company.'-'.$client_id?>">

                <input type="hidden" value="<?php echo $client_id?>" id="client_search" name="clientid" />

            </div>



            <div class="form-group date_group">

                <div class="form-title">Recevied Date</div>

                <label class="input-group datepicker-only-init_date_received">

                    <input type="text" class="form-control date_received" placeholder="Select Received Date" name="received_date" style="font-weight: 500;" required />

                    <span class="input-group-addon">

                        <i class="glyphicon glyphicon-calendar"></i>

                    </span>

                </label>

            </div>



            <div class="form-group start_group">

                <div class="form-title">Added Date</div>

                <div class='input-group datepicker-only-init'>

                    <input type='text' class="form-control date_added" placeholder="Select Added Date" name="added_date" style="font-weight: 500;" required />

                    <span class="input-group-addon">

                        <i class="glyphicon glyphicon-calendar"></i>

                    </span>

                </div>

            </div>



            <div class="form-group start_group">

                <div class="form-title">Description</div>

                <textarea type='text' class="form-control" placeholder="Enter Description" name="description" style="font-weight: 500; height: 100px" required /></textarea>

            </div>



            <div class="form-group start_group">

                <div class='input-group'>

                    <input type='checkbox' name="hard_files_checkbox" id="hard_files_checkbox" value="1"/>

                    <label for="hard_files_checkbox">Hard Files</label>

                </div>

            </div>



            <p id="attachments_text" style="display:none; font-weight: bold;">"Files Attached:</p>

            <div id="add_attachments_div">

            

            </div>

            <div id="add_notepad_attachments_div">



            </div>

            

            <div class="form-group start_group">

              <i class="fa fa-plus fa-plus-add" style="margin-top:10px;" aria-hidden="true" title="Add Attachment"></i> 

              <i class="fa fa-pencil-square fanotepadadd" style="margin-top:10px; margin-left: 10px;" aria-hidden="true" title="Add Completion Notes"></i>

              <i class="fa fa-trash trash_imageadd" data-element="" style="margin-left: 10px;" aria-hidden="true"></i>



              <div class="img_div img_div_add" style="z-index:9999999; min-height: 275px">

                <form name="image_form" id="image_form" action="" method="post" enctype="multipart/form-data" style="text-align: left;">

                  

                </form>

                

                <div class="image_div_attachments">
                  <p>You can only upload maximum 300 files at a time. If you drop more than 300 files then the files uploading process will be crashed. </p>
                  <form action="<?php echo URL::to('user/infile_upload_images_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload1" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">

                      <input name="_token" type="hidden" value="'.$file->id.'">

                   

                  </form>                

                </div>

               </div>



               <div class="notepad_div_notes_add" style="z-index:9999; position:absolute">

                  <textarea name="notepad_contents_add" class="form-control notepad_contents_add" placeholder="Enter Contents"></textarea>

                  <input type="button" name="notepad_submit_add" class="btn btn-sm btn-primary notepad_submit_add" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">

                  <spam class="error_files_notepad_add"></spam>

              </div>

            </div>

          </div>

          <div class="modal-footer">           
            <p class="accepted_files_main" style="display:none"><strong> </strong><span class="accepted_files">0</span> Files are Ready to Upload.</p> 
            <input type="hidden" name="total_count_files" id="total_count_files" value="">
            <input type="submit" class="common_black_button job_button_name create_new_class"  value="Create New">

          </div>

        </div>

    </form>

  </div>

</div>

<!--*************************************************************************-->

<div class="content_section" style="margin-bottom:200px">

  <div class="page_title">
        
        <h4 class="col-lg-12 padding_00 new_main_title">Soft File Management System for <?php echo $companydetails_val->company.'('.$client_id.')'?></h4>
          
            <div class="col-lg-3 text-right" style="padding-right: 0px;">

                <form action="<?php echo URL::to('user/infile_search')?>" method="get">

                  <div class="col-lg-6" style="padding: 0px;">

                    <div class="form-group">
                        
                        <input type="text" class="form-control client_common_search" placeholder="Enter Client Name" style="font-weight: 500;" value="<?php echo $companyname_val; ?>" required />                      

                      <input type="hidden" class="client_search_common" id="client_search_hidden_infile" value="<?php echo $hiddenval; ?>" name="client_id">                                          

                    </div>                  

                  </div>

                  <div class="col-lg-6" style="padding: 0px;">

                    <div class="select_button" style=" margin-left: 10px; width: 150px;">

                        <ul>

                        <li><input type="submit" value="Load" class="submit_button" id="client_search_infile"  name=""></li>

                      </ul>

                    </div>

                  </div>

                </form>

              

            </div>

            <div class="col-lg-9" style="padding:0px 0px 0px 0px; ">

              <div class="row">
                
                <div class="col-lg-12">

            <div class="dropdown" style="float: right;">
                <button class="btn btn-default dropdown-toggle own_drop_down" style="float: right;" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  <i class="fa fa-bars" aria-hidden="true"></i>
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" style="right: 0px; left: unset; width: 250px;" aria-labelledby="dropdownMenu1" style="width: 250px;">
                  <li><a href="javascript:" class="create_new">Add New File Batch</a></li>
                  <li><a href="javascript:" class="reportclassdiv">Report</a></li>
                  <li><a href="javascript:" class="integrity_check_for_all">Integrity Check</a></li>
                  <li><a href="javascript:" class="expand_all_infile_items">Expand All Infile Items</a></li>
                  <li><a href="<?php echo URL::to('user/in_file_advance'); ?>" >Return to Infiles Main Screen</a></li>
                </ul>
              </div>
              <div style="float: right; margin-top: 13px; margin-right: 20px;">
                  <?php
                  $user_details = DB::table('user_login')->where('id',1)->first();
                  ?>

                <input type="checkbox" id="show_incomplete" <?php if($user_details->infile_incomplete == 1) { echo 'checked'; } else{echo '';}?> ><label for="show_incomplete">Show Incomplete Files</label>
                </div>
              <div class="report_div" style="display: none">
                      <label>Please select following report type</label><br>
                      <input type="radio" name="report_infile" id="singleclient" class="class_invoice" value="1"><label for="singleclient">Individual Client</label>
                      <br/>
                      <input type="radio" name="report_infile" id="allclient" class="class_invoice" value="2"><label for="allclient">All Clients</label>
                      <br/>
                      <input type="hidden" class="report_type">
                      <input type="submit" name="invoce_report_but" class="report_ok_button ok_button" value="OK">
                  </div>
                  
                </div>
                
              </div>

             
              

              
            </div>
  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
    <?php } ?>
    </div>
</div>
<div class="row">
  <div class="col-lg-12">
      <table class="display nowrap fullviewtablelist" id="in_file" width="100%">
        <thead>
            <tr style="background: #fff;">
	            <th width="2%" style="text-align: left;">S.No</th>
	            <th style="text-align: left;width:400px">Client Name</th>
	            <th style="text-align: left;width:200px">Date Received / Added</th>
	            <th style="text-align: left;" width="20%">Files & Notes </th>
	            <th style="text-align: left;width:150px; max-width: 150px;"></th>
	            <th style="text-align: left;"></th>
	            <th style="text-align: left;"></th>
	            <th style="text-align: left;"></th>
	            <th style="text-align: left;"></th>
	        </tr>
        </thead>
        <tbody id="in_file_tbody">
          <?php
          $i=1;
          $output='';
          if(count($infiles)){
            foreach ($infiles as $file) {
              if($file->status == 0){
                $staus = 'fa-check edit_status'; 
                $statustooltip = 'Complete Infile';
                $disable = '';
                $disable_class = '';
                $color='';
                $color_last='style="border-top:0px solid;border-bottom:1px solid #6a6a6a"';
              }
              else{
                $staus = 'fa-times edit_status incomplete_status';
                $statustooltip = 'InComplete Infile';
                $disable = 'disabled';
                $disable_class = 'disable_class';
                $color = 'style="color:#f00;"';
                $color_last = 'style="color:#f00;border-top:0px solid;border-bottom:1px solid #6a6a6a"';
              }
              $companydetails = DB::table('cm_clients')->where('client_id', $file->client_id)->first();
              if(count($companydetails) != ''){
                $companyname = $companydetails->company;
              }
              else{
                $companyname = 'N/A';
              }
              $notes_attachments = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',0)->where('notes_type', 1)->get();
              $download_notes='';
              if(count($notes_attachments)){                        

                foreach($notes_attachments as $attachment){
                   if($attachment->check_file == 1) { $checked = 'checked'; } else { $checked = ''; }
                    $download_notes.= '<input type="checkbox" name="fileattachment_checkbox" class="fileattachment_checkbox '.$disable_class.'" id="fileattach_'.$attachment->id.'" value="'.$attachment->id.'" '.$checked.' '.$disable.'><label for="fileattach_'.$attachment->id.'">&nbsp;</label><a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'" '.$color.'>'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'"><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';

                }

              }

              else{

                $download_notes ='';

              }



              if(count($notes_attachments)){

                $delete_notes_all = '<i class="fa fa-minus-square delete_all_notes '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>

                <i class="fa fa-download download_all_notes" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>';

              }

              else{

                $delete_notes_all = '';

              }





              $attach_notes_only = DB::table('in_file_attachment')->where('file_id',$file->id)->where('status',1)->get();



              $notes_only='';



              if(count($attach_notes_only)){                        

                foreach($attach_notes_only as $attachment){



                    $notes_only.= '<a href="javascript:" class="fileattachment" data-element="'.URL::to('/').'/'.$attachment->url.'/'.$attachment->attachment.'" data-src="'.$attachment->url.'/'.$attachment->attachment.'">'.$attachment->attachment.'</a><a href="javascript:" class="trash_icon '.$disable_class.'" '.$color.'><i class="fa fa-trash trash_image" data-element="'.$attachment->id.'" aria-hidden="true"></i></a><br/>';

                }

              }

              else{

                $notes_only ='';

              }



              if(count($attach_notes_only)){

                $delete_notes_only = '<i class="fa fa-minus-square delete_all_notes_only '.$disable_class.'" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Delete All Attachments"></i>

                <i class="fa fa-download download_all_notes_only" data-element="'.$file->id.'" style="margin-top:10px; margin-left:10px;" aria-hidden="true" title="Download All Attachments"></i>';

              }

              else{

                $delete_notes_only = '';

              }









              $userdrop='';



              if(count($userlist)){

                foreach ($userlist as $user) {

                  if($user->user_id == $file->complete_by ){ $selected = 'selected';} else{$selected = '';}

                  $userdrop.='<option value="'.$user->user_id.'" '.$selected.'>'.$user->lastname.' '.$user->firstname.'</option>';

                }

              }



              $complete_date = ($file->complete_date != '0000-00-00')?date('d-M-Y', strtotime($file->complete_date)):'';



              $hard_files = ($file->hard_files != 0)?'YES':'NO';



              if($file->internal == 1){

                $internal_checkbox = 'checked';

              }

              else{

                $internal_checkbox = '';

              }

              if(isset($_GET['client_id']))
              {
                $url_upload = URL::to('user/infile_search?client_id='.$_GET['client_id'].'&infile_item='.$file->id);
              }
              else{
                $url_upload = URL::to('user/in_file?infile_item='.$file->id);
              }
              if($file->integrity_check == 1)
              {
                $integrity_check_color = 'green';
                $integrity_check_date = '<spam class="check_date_time" style="font-weight: 600;color: #000;margin-left: 10px;font-size: 14px;">'.date('d-m-Y H:i:s', strtotime($file->check_date_time)).'</spam>';
              }
              elseif($file->integrity_check == 2)
              {
                $integrity_check_color = 'orange';
                $integrity_check_date = '<spam class="check_date_time" style="font-weight: 600;color: #000;margin-left: 10px;font-size: 14px;">'.date('d-m-Y H:i:s', strtotime($file->check_date_time)).'</spam>';
              }
              else{
                $integrity_check_color = '#f00';
                $integrity_check_date = '<spam class="check_date_time" style="font-weight: 600;color: #000;margin-left: 10px;font-size: 14px;"></spam>';
              }

          $output.='<tr class="infile_tr_body infile_tr_body_'.$file->id.'" id="infile_'.$file->id.'" data-element="'.$file->id.'">
				<td '.$color.' valign="top" >'.$i.'</td>
				<td '.$color.' valign="top">'.$companyname.'</td>
				<td '.$color.' valign="top">Received:'.date('d-M-Y', strtotime($file->data_received)).'<br/>Added: '.date('d-M-Y', strtotime($file->date_added)).'</td>
				<td '.$color.'>
					<span style="color: #0300c1;">Description: </span>         
          <span style="color: #0300c1;" class="des_'.$file->id.'">'.$file->description.'</span>
          <span style="color: #0300c1;"><a href="javascript:" class="fa fa-pencil edit_description" title="Edit Description" data-element="'.$file->id.'"></a></span>
					<div style="clear:both"></div>
              	</td>
              	<td '.$color.' colspan="2">';
              		$output.='<input type="button" class="common_black_button show_previous show_previous_'.$file->id.'" value="Load Previously Entered P/S" data-element="'.$file->id.'" style="display:none"> 
              	</td>
                <td '.$color.' colspan="3">';
                  $output.='<input type="button" class="common_black_button integrity_check" value="Integrity Check" data-element="'.$file->id.'" style="background:'.$integrity_check_color.'">
                  <input type="button" class="common_black_button show_attachments" value="Show Attachments" data-element="'.$file->id.'"><br/>
                  '.$integrity_check_date.'
                </td>
            </tr>
            <tr class="infile_tr_body infile_tr_body_'.$file->id.'" data-element="'.$file->id.'">
            	<td colspan="9" class="show_attachments_td" id="show_attachments_td_'.$file->id.'">
            		
            	</td>
            </tr>
            <tr class="infile_tr_body infile_tr_body_'.$file->id.' infile_tr_body_last_'.$file->id.'" data-element="'.$file->id.'">
            	<td colspan="3" style="border-top: 0px solid; border-bottom:1px solid #6a6a6a">
            		<div class="col-md-9 col-lg-9">
						'.$download_notes.'
						<i class="fa fa-pencil-square fanotepad '.$disable_class.'" style="margin-top:10px;" aria-hidden="true" title="Add Completion Notes"></i>
							'.$delete_notes_all.'';
						if($file->task_notify == 1){
						$output.='<br/><a href="javascript:" class="single_notify '.$disable_class.'" data-element="'.$file->id.'" '.$color.'>Notify</a> &nbsp &nbsp <a href="javascript:"  class="all_notify '.$disable_class.'" data-element="'.$file->id.'" '.$color.'>Notify all</a> &nbsp &nbsp <a href="javascript:"  class="create_task_manager" data-element="'.$file->id.'" '.$color.'>Create Task</a>';

						}
            if($file->imported_status == 1) { $status_checked = 'checked'; $status_shown = 'display:initial'; } else{ $status_checked = ''; $status_shown = 'display:none'; }
            if($file->imported_date == "") { $imported_date_val = date('d-M-Y'); } else{ $imported_date_val = $file->imported_date; }
            $output.='<br/><input type="checkbox" name="imported_workings" class="imported_workings" data-element="'.$file->id.'" id="imported_workings_'.$file->id.'" '.$status_checked.'><label for="imported_workings_'.$file->id.'" style="margin-top: 16px;">Imported into Workings</label>
            <input type="text" name="imported_date" class="imported_date imported_date_'.$file->id.'" value="'.$imported_date_val.'" data-element="'.$file->id.'" placeholder="Choose Date" style="'.$status_shown.'">
            ';
					$output.='</div>
					<div class="col-md-3 col-lg-3">
						<h4>Linked to Tasks:</h4>';
						$get_tasks = DB::table('taskmanager_infiles')->where('infile_id',$file->id)->get();
						if(count($get_tasks))
						{
						  foreach($get_tasks as $taskval)
						  {
						    $task_name = DB::table('taskmanager')->where('id',$taskval->task_id)->first();
						    $ii = 1;
						    $output.='<p>
                  <a href="javascript:" class="link_to_task_specifics download_pdf_task" data-element="'.$taskval->task_id.'" title="Download PDF" style="color:#f00">'.$ii.'. '.$task_name->taskid.' - '.$task_name->subject.'</a>
                </p>';
						    $ii++;
						  }
						}
						else{
						  $output.='<p style="color:#f00">No Task Linked to this Infile Item.</p>';
						}
					$output.='</div>';
					$output.='<div class="img_div" style="z-index:9999999">
						<form name="image_form" id="image_form" action="'.URL::to('user/infile_image_upload').'" method="post" enctype="multipart/form-data" style="text-align: left;">
						<input type="file" name="image_file[]" required class="form-control image_file" value="" multiple>
						<input type="hidden" name="hidden_id" value="'.$file->id.'">
						<input type="submit" name="image_submit" class="btn btn-sm btn-primary image_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
						<spam class="error_files"></spam>
						</form>
 						<div style="width:100%;text-align:center;margin-top:-10px;margin-bottom:10px;color:#000"><label style="font-weight:800;">OR</label></div>
						<div class="image_div_attachments">
							<form action="'.URL::to('user/infile_upload_images?file_id='.$file->id).'" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
							   <input name="_token" type="hidden" value="'.$file->id.'">
							</form>
							<a href="'.$url_upload.'" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
						</div>
  					</div>
  					<div class="notepad_div" style="z-index:9999; position:absolute">
					    <form name="notepad_form" id="notepad_form" action="'.URL::to('user/infile_notepad_upload').'" method="post" enctype="multipart/form-data" style="text-align: left;">
					      <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
					      <input type="hidden" name="hidden_id" value="'.$file->id.'">
					      <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
					      <spam class="error_files_notepad"></spam>
					    </form>
					</div>
            	</td>
            	<td style="border-top: 0px solid;border-bottom:1px solid #6a6a6a"></td>
            	<td style="border-top: 0px solid;border-bottom:1px solid #6a6a6a" class="user_td_class" '.$color.' valign="top">
            		<h5>Complete By</h5>
					<select class="form-control user_select '.$disable_class.'" data-element="'.$file->id.'" '.$disable.'>
					<option value="">Select User</option>'.$userdrop.'</select>
				</td>
				<td '.$color_last.' valign="top">
				  <h5>Complete Date</h5>
				  <label class="input-group auto_save_date">
				      <input type="text" class="form-control complete_date '.$disable_class.'" '.$disable.' value="'.$complete_date.'" data-element="'.$file->id.'" placeholder="Select Date" name="date" style="font-weight: 500; z-index:0" required="" autocomplete="off">
				      <span class="input-group-addon">
				          <i class="glyphicon glyphicon-calendar"></i>
				      </span>
				  </label>
				</td>
				<td '.$color_last.' valign="top">
					<h5>Completion Notes</h5>
					'.$notes_only.'
					<i class="fa fa-pencil-square fanotepad_notes '.$disable_class.'" style="margin-top:10px;" aria-hidden="true" title="Add Completion Notes"></i>
					'.$delete_notes_only.'
					<div class="notepad_div_notes" style="z-index:9999; position:absolute">
						<form name="notepad_form_notes" id="notepad_form_notes" action="'.URL::to('user/infile_notepad_upload_notes').'" method="post" enctype="multipart/form-data" style="text-align: left;">
						  <textarea name="notepad_contents" class="form-control notepad_contents" placeholder="Enter Contents"></textarea>
						  <input type="hidden" name="hidden_id" value="'.$file->id.'">
						  <input type="submit" name="notepad_submit" class="btn btn-sm btn-primary notepad_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
						  <spam class="error_files_notepad"></spam>
						</form>
					</div>
				</td>
				<td '.$color_last.' align="center" valign="top"><h5>Hard Files</h5>'.$hard_files.'</td>
				<td '.$color_last.' align="center" valign="top"><h5>Action</h5><a href="javascript:"><i class="fa '.$staus.'" data-element="'.$file->id.'" title="'.$statustooltip.'"></i></a></td>
            </tr>';          

            $i++;

            }

          }

          else{

            $output.='<tr>

                    <td>1</td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td align="center">Empty</td>

                    <td></td>

                    <td></td>

                    <td></td>

                    <td></td>

                    </tr>';

          }

          echo $output;

          

          ?>

          

        </tbody>

      </table>





  </div>

</div>

</div>



    <!-- End  -->

<div class="main-backdrop"><!-- --></div>

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
      <td style="text-align: left;border:1px solid #000;">S.No</td>
      <td style="text-align: left;border:1px solid #000;">Client Name</td>
      <td style="text-align: left;border:1px solid #000;">Date Received</td>
      <td style="text-align: left;border:1px solid #000;">Date Added</td>
      <td style="text-align: left;border:1px solid #000;">No. of Files</td>
      </tr>
    </thead>
    <tbody id="report_pdf_type_two_tbody">

    </tbody>
  </table>
</div>


<div id="report_pdf_type_two_single" style="display:none">

  <div id="report_pdf_type_two_tbody_single">
  </div>
</div>





<div class="modal_load"></div>
<div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Infile Items are Checked.</p>
  <p style="font-size:18px;font-weight: 600;">Checking File: <span id="apply_first"></span> of <span id="apply_last"></span></p>
</div>
<div class="modal_load_attachments" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Infile Items are Processed.</p>
  <p style="font-size:18px;font-weight: 600;">Expanding: <span id="apply_first_count"></span> of <span id="apply_last_count"></span></p>
</div>

<div class="modal_load_import" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait while we import all the missing files from this folder.</p>
  <p style="font-size:18px;font-weight: 600;">Importing: <span id="import_first"></span> of <span id="import_last"></span></p>
</div>

<div class="modal_load_browse" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait while we load the selected folder for scanning. <br/>This may take upto 3 minutes but it may take longer depending on the number of files in this folder.</p>
</div>

<div class="modal_load_number" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please Wait While Auto Numbering is Applied to this Infile Item.</p>
</div>

<div class="modal_load_available" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait while we import the missing file from this folder.</p>
</div>

<div class="modal_load_review" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait while we scan this entire folder to find if any missing files are available in this folder. <br/>This may take upto 3 minutes but it may take longer depending on the number of files in this folder.</p>
</div>

<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">
<input type="hidden" name="hidden_file_id" id="hidden_file_id" value="">
<input type="hidden" name="hidden_file_id_supplier" id="hidden_file_id_supplier" value="">
<input type="hidden" name="hidden_attachment_id_supplier" id="hidden_attachment_id_supplier" value="">

<input type="hidden" name="hidden_p_all_download" id="hidden_p_all_download" value="">
<input type="hidden" name="hidden_s_all_download" id="hidden_s_all_download" value="">

<input type="hidden" name="hidden_re_no_file_id" id="hidden_re_no_file_id" value="">
<input type="hidden" name="hidden_re_no_value" id="hidden_re_no_value" value="">
<input type="hidden" name="hidden_re_no_inc" id="hidden_re_no_inc" value="">
<input type="hidden" name="hidden_re_no_radio" id="hidden_re_no_radio" value="">

<input type="hidden" name="hidden_no_file_id" id="hidden_no_file_id" value="">
<input type="hidden" name="hidden_no_value" id="hidden_no_value" value="">
<input type="hidden" name="hidden_no_inc" id="hidden_no_inc" value="">
<input type="hidden" name="hidden_no_radio" id="hidden_no_radio" value="">


<script>
//on keyup, start the countdown
var typingTimer;                //timer identifier
var doneTypingInterval = 2500;  //time in ms, 5 second for example
var $input = $('.add_text');
var $input1 = $('.supplier');
var $input2 = $('.percent_one_value');
var $input3 = $('.percent_two_value');
var $input4 = $('.percent_three_value');
var $input5 = $('.percent_four_value');
var $input10 = $('.percent_five_value');
var $input6 = $('.date_attachment');
var $input7 = $('.code_attachment');

var $input8 = $('.currency_value');
var $input9 = $('.value_value');

$input.on('keyup', function () {
  var input_val = $(this).val();
  var id = $(this).attr('data-element');
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval,input_val,id);
});
//on keydown, clear the countdown 
$input.on('keydown', function () {
  clearTimeout(typingTimer);
});

// $input1.on('keyup', function () {
//   var input_val = $(this).val();
//   var attachmentid = $(this).attr('data-element');
//   var fileid = $(this).attr('data-file');
//   var that = $(this);
//   clearTimeout(typingTimer);
//   typingTimer = setTimeout(doneTyping_supplier, doneTypingInterval,input_val,attachmentid,fileid,that);
// });
// //on keydown, clear the countdown 
// $input1.on('keydown', function () {
//   clearTimeout(typingTimer);
// });

$input2.on('keyup', function () {
  var input_val = $(this).val();
  var attachmentid = $(this).attr('data-element');
  var fileid = $(this).attr('data-file');
  var that = $(this);
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_percent_one, doneTypingInterval,input_val,attachmentid,fileid,that);
});
//on keydown, clear the countdown 
$input2.on('keydown', function () {
  clearTimeout(typingTimer);
});

$input3.on('keyup', function () {
  var input_val = $(this).val();
  var attachmentid = $(this).attr('data-element');
  var fileid = $(this).attr('data-file');
  var that = $(this);
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_percent_two, doneTypingInterval,input_val,attachmentid,fileid,that);
});
//on keydown, clear the countdown 
$input3.on('keydown', function () {
  clearTimeout(typingTimer);
});

$input4.on('keyup', function () {
  var input_val = $(this).val();
  var attachmentid = $(this).attr('data-element');
  var fileid = $(this).attr('data-file');
  var that = $(this);
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_percent_three, doneTypingInterval,input_val,attachmentid,fileid,that);
});
//on keydown, clear the countdown 
$input4.on('keydown', function () {
  clearTimeout(typingTimer);
});

$input5.on('keyup', function () {
  var input_val = $(this).val();
  var attachmentid = $(this).attr('data-element');
  var fileid = $(this).attr('data-file');
  var that = $(this);
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_percent_four, doneTypingInterval,input_val,attachmentid,fileid,that);
});
//on keydown, clear the countdown 
$input5.on('keydown', function () {
  clearTimeout(typingTimer);
});

$input10.on('keyup', function () {
  var input_val = $(this).val();
  var attachmentid = $(this).attr('data-element');
  var fileid = $(this).attr('data-file');
  var that = $(this);
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_percent_five, doneTypingInterval,input_val,attachmentid,fileid,that);
});
//on keydown, clear the countdown 
$input10.on('keydown', function () {
  clearTimeout(typingTimer);
});

$input6.on('keyup', function () {
  var input_val = $(this).val();
  var attachmentid = $(this).attr('data-element');
  var fileid = $(this).attr('data-file');
  var that = $(this);
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_date_attachment, doneTypingInterval,input_val,attachmentid,fileid,that);
});
//on keydown, clear the countdown 
$input6.on('keydown', function () {
  clearTimeout(typingTimer);
});

$input7.on('keyup', function () {
  var input_val = $(this).val();
  var attachmentid = $(this).attr('data-element');
  var fileid = $(this).attr('data-file');
  var that = $(this);
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_code_attachment, doneTypingInterval,input_val,attachmentid,fileid,that);
});
//on keydown, clear the countdown 
$input7.on('keydown', function () {
  clearTimeout(typingTimer);
});

$input8.on('keyup', function () {
  var input_val = $(this).val();
  var attachmentid = $(this).attr('data-element');
  var fileid = $(this).attr('data-file');
  var that = $(this);
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_currency_attachment, doneTypingInterval,input_val,attachmentid,fileid,that);
});
//on keydown, clear the countdown 
$input8.on('keydown', function () {
  clearTimeout(typingTimer);
});

$input9.on('keyup', function () {
  var input_val = $(this).val();
  var attachmentid = $(this).attr('data-element');
  var fileid = $(this).attr('data-file');
  var that = $(this);
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_value_attachment, doneTypingInterval,input_val,attachmentid,fileid,that);
});
//on keydown, clear the countdown 
$input9.on('keydown', function () {
  clearTimeout(typingTimer);
});

//user is "finished typing," do something
function doneTyping (input,id) {
  	$.ajax({
        url:"<?php echo URL::to('user/update_fileattachment_textval'); ?>",
        type:"get",
        data:{input:input,id:id},
        success: function(result) {

        }
    });
}
// function doneTyping_supplier (input,attachmentid,fileid,that) {
//   if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
//   {
//   	$.ajax({
//         url:"<?php echo URL::to('user/update_supplier_infile_attachment'); ?>",
//         type:"get",
//         data:{input:input,attachmentid:attachmentid,fileid:fileid,type:"0"},
//         success: function(result) {
//           that.attr("data-value",input);
//         }
//     });
//   }
// }
function doneTyping_percent_one (input,attachmentid,fileid,that) {
  if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
  {
  	$.ajax({
        url:"<?php echo URL::to('user/update_percent_one_infile_attachment'); ?>",
        type:"get",
        dataType:"json",
        data:{input:input,attachmentid:attachmentid,fileid:fileid},
        success: function(result) {
        	$(".net_value_"+attachmentid).val(result['net']);
        	$(".vat_value_"+attachmentid).val(result['vat']);
        	$(".gross_value_"+attachmentid).val(result['gross']);

          $(".net_value_"+attachmentid).attr("data-value",result['net']);
          $(".vat_value_"+attachmentid).attr("data-value",result['vat']);
          $(".gross_value_"+attachmentid).attr("data-value",result['gross']);
          that.attr("data-value",input);
        }
    });
  }
}
function doneTyping_percent_two (input,attachmentid,fileid,that) {
  if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
  {
  	$.ajax({
        url:"<?php echo URL::to('user/update_percent_two_infile_attachment'); ?>",
        type:"get",
        dataType:"json",
        data:{input:input,attachmentid:attachmentid,fileid:fileid},
        success: function(result) {
        	$(".net_value_"+attachmentid).val(result['net']);
        	$(".vat_value_"+attachmentid).val(result['vat']);
        	$(".gross_value_"+attachmentid).val(result['gross']);

          $(".net_value_"+attachmentid).attr("data-value",result['net']);
          $(".vat_value_"+attachmentid).attr("data-value",result['vat']);
          $(".gross_value_"+attachmentid).attr("data-value",result['gross']);
          that.attr("data-value",input);
        }
    });
  }
}
function doneTyping_percent_three (input,attachmentid,fileid,that) {
  if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
  {
  	$.ajax({
        url:"<?php echo URL::to('user/update_percent_three_infile_attachment'); ?>",
        type:"get",
        dataType:"json",
        data:{input:input,attachmentid:attachmentid,fileid:fileid},
        success: function(result) {
        	$(".net_value_"+attachmentid).val(result['net']);
        	$(".vat_value_"+attachmentid).val(result['vat']);
        	$(".gross_value_"+attachmentid).val(result['gross']);

          $(".net_value_"+attachmentid).attr("data-value",result['net']);
          $(".vat_value_"+attachmentid).attr("data-value",result['vat']);
          $(".gross_value_"+attachmentid).attr("data-value",result['gross']);
          that.attr("data-value",input);
        }
    });
  }
}
function doneTyping_percent_four (input,attachmentid,fileid,that) {
  if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
  {
  	$.ajax({
        url:"<?php echo URL::to('user/update_percent_four_infile_attachment'); ?>",
        type:"get",
        dataType:"json",
        data:{input:input,attachmentid:attachmentid,fileid:fileid},
        success: function(result) {
        	$(".net_value_"+attachmentid).val(result['net']);
        	$(".vat_value_"+attachmentid).val(result['vat']);
        	$(".gross_value_"+attachmentid).val(result['gross']);

          $(".net_value_"+attachmentid).attr("data-value",result['net']);
          $(".vat_value_"+attachmentid).attr("data-value",result['vat']);
          $(".gross_value_"+attachmentid).attr("data-value",result['gross']);
          that.attr("data-value",input);
        }
    });
  }
}
function doneTyping_percent_five (input,attachmentid,fileid,that) {
  if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
  {
    $.ajax({
        url:"<?php echo URL::to('user/update_percent_five_infile_attachment'); ?>",
        type:"get",
        dataType:"json",
        data:{input:input,attachmentid:attachmentid,fileid:fileid},
        success: function(result) {
          $(".net_value_"+attachmentid).val(result['net']);
          $(".vat_value_"+attachmentid).val(result['vat']);
          $(".gross_value_"+attachmentid).val(result['gross']);

          $(".net_value_"+attachmentid).attr("data-value",result['net']);
          $(".vat_value_"+attachmentid).attr("data-value",result['vat']);
          $(".gross_value_"+attachmentid).attr("data-value",result['gross']);
          that.attr("data-value",input);
        }
    });
  }
}
function doneTyping_date_attachment (input,attachmentid,fileid,that) {
  if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
  {
  	$.ajax({
        url:"<?php echo URL::to('user/infile_attachment_date_filled'); ?>",
        type:"post",
        data:{id:attachmentid,dateval:input},
        success: function(result) {
          that.attr("data-value",input);
        }
    });
  }
}
function doneTyping_code_attachment (input,attachmentid,fileid,that) {
  if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
  {
    $.ajax({
        url:"<?php echo URL::to('user/infile_attachment_code_filled'); ?>",
        type:"post",
        data:{id:attachmentid,code:input},
        success: function(result) {
          that.attr("data-value",input);
        }
    });
  }
}

function doneTyping_currency_attachment (input,attachmentid,fileid,that) {
  if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
  {
    $.ajax({
        url:"<?php echo URL::to('user/infile_attachment_currency_filled'); ?>",
        type:"post",
        data:{id:attachmentid,currency:input},
        success: function(result) {
          that.attr("data-value",input);
        }
    });
  }
}

function doneTyping_value_attachment (input,attachmentid,fileid,that) {
  if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
  {
    $.ajax({
        url:"<?php echo URL::to('user/infile_attachment_value_filled'); ?>",
        type:"post",
        data:{id:attachmentid,value:input},
        success: function(result) {
          that.attr("data-value",input);
        }
    });
  }
}
$(".add_text").blur(function() {
  var input = $(this).val();
  var id = $(this).attr('data-element');
  $.ajax({
        url:"<?php echo URL::to('user/update_fileattachment_textval'); ?>",
        type:"get",
        data:{input:input,id:id},
        success: function(result) {

        }
  });
});
$(".supplier").blur(function() {
  $(this).delay(150).queue(function(){
  	var input_val = $(this).val();
  	var attachmentid = $(this).attr('data-element');
  	var fileid = $(this).attr('data-file');
  	if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
    {
      $.ajax({
          url:"<?php echo URL::to('user/update_supplier_infile_attachment'); ?>",
          type:"get",
          data:{input:input_val,attachmentid:attachmentid,fileid:fileid,type:"1"},
          success: function(result) {
            $(this).attr("data-value",input_val);
          }
      });
    }
  });
});
$(".percent_one_value").blur(function() {
	var input_val = $(this).val();
	var attachmentid = $(this).attr('data-element');
	var fileid = $(this).attr('data-file');
	var that = $(this);
  if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
  {
    $(this).attr("data-value",input_val);
  	$.ajax({
        url:"<?php echo URL::to('user/update_percent_one_infile_attachment'); ?>",
        type:"get",
        dataType:"json",
        data:{input:input_val,attachmentid:attachmentid,fileid:fileid},
        success: function(result) {
        	$(".net_value_"+attachmentid).val(result['net']);
        	$(".vat_value_"+attachmentid).val(result['vat']);
        	$(".gross_value_"+attachmentid).val(result['gross']);

          $(".net_value_"+attachmentid).attr("data-value",result['net']);
          $(".vat_value_"+attachmentid).attr("data-value",result['vat']);
          $(".gross_value_"+attachmentid).attr("data-value",result['gross']);
        	that.val(result['value']);
          $(this).attr("data-value",input_val);
        }
    });
  }
});
$(".percent_two_value").blur(function() {
	var input_val = $(this).val();
	var attachmentid = $(this).attr('data-element');
	var fileid = $(this).attr('data-file');
	var that = $(this);
  if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
  {
    $(this).attr("data-value",input_val);
	   $.ajax({
        url:"<?php echo URL::to('user/update_percent_two_infile_attachment'); ?>",
        type:"get",
        dataType:"json",
        data:{input:input_val,attachmentid:attachmentid,fileid:fileid},
        success: function(result) {
        	$(".net_value_"+attachmentid).val(result['net']);
        	$(".vat_value_"+attachmentid).val(result['vat']);
        	$(".gross_value_"+attachmentid).val(result['gross']);

          $(".net_value_"+attachmentid).attr("data-value",result['net']);
          $(".vat_value_"+attachmentid).attr("data-value",result['vat']);
          $(".gross_value_"+attachmentid).attr("data-value",result['gross']);
        	that.val(result['value']);
          $(this).attr("data-value",input_val);
        }
      });
   }
});
$(".percent_three_value").blur(function() {
	var input_val = $(this).val();
	var attachmentid = $(this).attr('data-element');
	var fileid = $(this).attr('data-file');
	var that = $(this);
  if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
  {
    $(this).attr("data-value",input_val);
	   $.ajax({
        url:"<?php echo URL::to('user/update_percent_three_infile_attachment'); ?>",
        type:"get",
        dataType:"json",
        data:{input:input_val,attachmentid:attachmentid,fileid:fileid},
        success: function(result) {
        	$(".net_value_"+attachmentid).val(result['net']);
        	$(".vat_value_"+attachmentid).val(result['vat']);
        	$(".gross_value_"+attachmentid).val(result['gross']);

          $(".net_value_"+attachmentid).attr("data-value",result['net']);
          $(".vat_value_"+attachmentid).attr("data-value",result['vat']);
          $(".gross_value_"+attachmentid).attr("data-value",result['gross']);
        	that.val(result['value']);
          $(this).attr("data-value",input_val);
        }
      });
   }
});
$(".percent_four_value").blur(function() {
	var input_val = $(this).val();
	var attachmentid = $(this).attr('data-element');
	var fileid = $(this).attr('data-file');
	var that = $(this);
  if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
  {
    $(this).attr("data-value",input_val);
	   $.ajax({
        url:"<?php echo URL::to('user/update_percent_four_infile_attachment'); ?>",
        type:"get",
        dataType:"json",
        data:{input:input_val,attachmentid:attachmentid,fileid:fileid},
        success: function(result) {
        	$(".net_value_"+attachmentid).val(result['net']);
        	$(".vat_value_"+attachmentid).val(result['vat']);
        	$(".gross_value_"+attachmentid).val(result['gross']);

          $(".net_value_"+attachmentid).attr("data-value",result['net']);
          $(".vat_value_"+attachmentid).attr("data-value",result['vat']);
          $(".gross_value_"+attachmentid).attr("data-value",result['gross']);
        	that.val(result['value']);
          $(this).attr("data-value",input_val);
        }
      });
   }
});
$(".percent_five_value").blur(function() {
  var input_val = $(this).val();
  var attachmentid = $(this).attr('data-element');
  var fileid = $(this).attr('data-file');
  var that = $(this);
  if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
  {
    $(this).attr("data-value",input_val);
     $.ajax({
        url:"<?php echo URL::to('user/update_percent_five_infile_attachment'); ?>",
        type:"get",
        dataType:"json",
        data:{input:input_val,attachmentid:attachmentid,fileid:fileid},
        success: function(result) {
          $(".net_value_"+attachmentid).val(result['net']);
          $(".vat_value_"+attachmentid).val(result['vat']);
          $(".gross_value_"+attachmentid).val(result['gross']);

          $(".net_value_"+attachmentid).attr("data-value",result['net']);
          $(".vat_value_"+attachmentid).attr("data-value",result['vat']);
          $(".gross_value_"+attachmentid).attr("data-value",result['gross']);
          that.val(result['value']);
          $(this).attr("data-value",input_val);
        }
      });
   }
});

function add_secondary_function()
{
  $('.date_attachment').datetimepicker({
        widgetPositioning: {
            horizontal: 'left'
        },
        icons: {
            time: "fa fa-clock-o",
            date: "fa fa-calendar",
            up: "fa fa-arrow-up",
            down: "fa fa-arrow-down"
        },
        format: 'L',
        format: 'DD/MM/YYYY',
        defaultDate:'',   
    });
  
  //on keyup, start the countdown
  var typingTimer;                //timer identifier
  var doneTypingInterval = 2500;  //time in ms, 5 second for example
  var $input = $('.add_text');
  var $input1 = $('.supplier');
  var $input2 = $('.percent_one_value');
  var $input3 = $('.percent_two_value');
  var $input4 = $('.percent_three_value');
  var $input5 = $('.percent_four_value');
  var $input10 = $('.percent_five_value');
  var $input6 = $('.date_attachment');
  var $input7 = $('.code_attachment');

  var $input8 = $('.currency_value');
  var $input9 = $('.value_value');

  $input.on('keyup', function () {
    var input_val = $(this).val();
    var id = $(this).attr('data-element');
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping, doneTypingInterval,input_val,id);
  });
  //on keydown, clear the countdown 
  $input.on('keydown', function () {
    clearTimeout(typingTimer);
  });

  // $input1.on('keyup', function () {
  //   var input_val = $(this).val();
  //   var attachmentid = $(this).attr('data-element');
  //   var fileid = $(this).attr('data-file');
  //   var that = $(this);
  //   clearTimeout(typingTimer);
  //   typingTimer = setTimeout(doneTyping_supplier, doneTypingInterval,input_val,attachmentid,fileid,that);
  // });
  // //on keydown, clear the countdown 
  // $input1.on('keydown', function () {
  //   clearTimeout(typingTimer);
  // });

  $input2.on('keyup', function () {
    var input_val = $(this).val();
    var attachmentid = $(this).attr('data-element');
    var fileid = $(this).attr('data-file');
    var that = $(this);
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping_percent_one, doneTypingInterval,input_val,attachmentid,fileid,that);
  });
  //on keydown, clear the countdown 
  $input2.on('keydown', function () {
    clearTimeout(typingTimer);
  });

  $input3.on('keyup', function () {
    var input_val = $(this).val();
    var attachmentid = $(this).attr('data-element');
    var fileid = $(this).attr('data-file');
    var that = $(this);
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping_percent_two, doneTypingInterval,input_val,attachmentid,fileid,that);
  });
  //on keydown, clear the countdown 
  $input3.on('keydown', function () {
    clearTimeout(typingTimer);
  });

  $input4.on('keyup', function () {
    var input_val = $(this).val();
    var attachmentid = $(this).attr('data-element');
    var fileid = $(this).attr('data-file');
    var that = $(this);
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping_percent_three, doneTypingInterval,input_val,attachmentid,fileid,that);
  });
  //on keydown, clear the countdown 
  $input4.on('keydown', function () {
    clearTimeout(typingTimer);
  });

  $input5.on('keyup', function () {
    var input_val = $(this).val();
    var attachmentid = $(this).attr('data-element');
    var fileid = $(this).attr('data-file');
    var that = $(this);
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping_percent_four, doneTypingInterval,input_val,attachmentid,fileid,that);
  });
  //on keydown, clear the countdown 
  $input5.on('keydown', function () {
    clearTimeout(typingTimer);
  });

  $input10.on('keyup', function () {
    var input_val = $(this).val();
    var attachmentid = $(this).attr('data-element');
    var fileid = $(this).attr('data-file');
    var that = $(this);
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping_percent_five, doneTypingInterval,input_val,attachmentid,fileid,that);
  });
  //on keydown, clear the countdown 
  $input10.on('keydown', function () {
    clearTimeout(typingTimer);
  });

  $input6.on('keyup', function () {
    var input_val = $(this).val();
    var attachmentid = $(this).attr('data-element');
    var fileid = $(this).attr('data-file');
    var that = $(this);
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping_date_attachment, doneTypingInterval,input_val,attachmentid,fileid,that);
  });
  //on keydown, clear the countdown 
  $input6.on('keydown', function () {
    clearTimeout(typingTimer);
  });

  $input7.on('keyup', function () {
    var input_val = $(this).val();
    var attachmentid = $(this).attr('data-element');
    var fileid = $(this).attr('data-file');
    var that = $(this);
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping_code_attachment, doneTypingInterval,input_val,attachmentid,fileid,that);
  });
  //on keydown, clear the countdown 
  $input7.on('keydown', function () {
    clearTimeout(typingTimer);
  });

  $input8.on('keyup', function () {
    var input_val = $(this).val();
    var attachmentid = $(this).attr('data-element');
    var fileid = $(this).attr('data-file');
    var that = $(this);
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping_currency_attachment, doneTypingInterval,input_val,attachmentid,fileid,that);
  });
  //on keydown, clear the countdown 
  $input8.on('keydown', function () {
    clearTimeout(typingTimer);
  });

  $input9.on('keyup', function () {
    var input_val = $(this).val();
    var attachmentid = $(this).attr('data-element');
    var fileid = $(this).attr('data-file');
    var that = $(this);
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping_value_attachment, doneTypingInterval,input_val,attachmentid,fileid,that);
  });
  //on keydown, clear the countdown 
  $input9.on('keydown', function () {
    clearTimeout(typingTimer);
  });

  $(".supplier").autocomplete({
      source: function(request, response) {
          $.ajax({
              url:"<?php echo URL::to('user/infile_supplier_search'); ?>",
              dataType: "json",
              data: {
                  term : request.term,
                  fileid : $("#hidden_file_id_supplier").val(),
              },
              success: function(data) {
                  response(data);
              }
          });
      },
      delay:1000,
      minLength: 1,
      select: function( event, ui ) {
        $.ajax({
          url:"<?php echo URL::to('user/infile_supplier_search_select'); ?>",
          type:"post",
          data:{value:ui.item.value,fileid:ui.item.id,attachment_id:$("#hidden_attachment_id_supplier").val()},
          success: function(result){

          }
        })
      }
  });
  $(".add_text").blur(function() {
    var input = $(this).val();
    var id = $(this).attr('data-element');
    $.ajax({
          url:"<?php echo URL::to('user/update_fileattachment_textval'); ?>",
          type:"get",
          data:{input:input,id:id},
          success: function(result) {

          }
    });
  });
  $(".supplier").blur(function() {
    $(this).delay(150).queue(function(){
      var input_val = $(this).val();
      var attachmentid = $(this).attr('data-element');
      var fileid = $(this).attr('data-file');
      if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
      {
        $.ajax({
            url:"<?php echo URL::to('user/update_supplier_infile_attachment'); ?>",
            type:"get",
            data:{input:input_val,attachmentid:attachmentid,fileid:fileid,type:"1"},
            success: function(result) {
              $(this).attr("data-value",input_val);
            }
        });
      }
    });
  });
  $(".percent_one_value").blur(function() {
    var input_val = $(this).val();
    var attachmentid = $(this).attr('data-element');
    var fileid = $(this).attr('data-file');
    var that = $(this);
    if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
    {
      $(this).attr("data-value",input_val);
      $.ajax({
          url:"<?php echo URL::to('user/update_percent_one_infile_attachment'); ?>",
          type:"get",
          dataType:"json",
          data:{input:input_val,attachmentid:attachmentid,fileid:fileid},
          success: function(result) {
            $(".net_value_"+attachmentid).val(result['net']);
            $(".vat_value_"+attachmentid).val(result['vat']);
            $(".gross_value_"+attachmentid).val(result['gross']);

            $(".net_value_"+attachmentid).attr("data-value",result['net']);
            $(".vat_value_"+attachmentid).attr("data-value",result['vat']);
            $(".gross_value_"+attachmentid).attr("data-value",result['gross']);
            that.val(result['value']);
            $(this).attr("data-value",input_val);
          }
      });
    }
  });
  $(".percent_two_value").blur(function() {
    var input_val = $(this).val();
    var attachmentid = $(this).attr('data-element');
    var fileid = $(this).attr('data-file');
    var that = $(this);
    if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
    {
      $(this).attr("data-value",input_val);
       $.ajax({
          url:"<?php echo URL::to('user/update_percent_two_infile_attachment'); ?>",
          type:"get",
          dataType:"json",
          data:{input:input_val,attachmentid:attachmentid,fileid:fileid},
          success: function(result) {
            $(".net_value_"+attachmentid).val(result['net']);
            $(".vat_value_"+attachmentid).val(result['vat']);
            $(".gross_value_"+attachmentid).val(result['gross']);

            $(".net_value_"+attachmentid).attr("data-value",result['net']);
            $(".vat_value_"+attachmentid).attr("data-value",result['vat']);
            $(".gross_value_"+attachmentid).attr("data-value",result['gross']);
            that.val(result['value']);
            $(this).attr("data-value",input_val);
          }
        });
     }
  });
  $(".percent_three_value").blur(function() {
    var input_val = $(this).val();
    var attachmentid = $(this).attr('data-element');
    var fileid = $(this).attr('data-file');
    var that = $(this);
    if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
    {
      $(this).attr("data-value",input_val);
       $.ajax({
          url:"<?php echo URL::to('user/update_percent_three_infile_attachment'); ?>",
          type:"get",
          dataType:"json",
          data:{input:input_val,attachmentid:attachmentid,fileid:fileid},
          success: function(result) {
            $(".net_value_"+attachmentid).val(result['net']);
            $(".vat_value_"+attachmentid).val(result['vat']);
            $(".gross_value_"+attachmentid).val(result['gross']);

            $(".net_value_"+attachmentid).attr("data-value",result['net']);
            $(".vat_value_"+attachmentid).attr("data-value",result['vat']);
            $(".gross_value_"+attachmentid).attr("data-value",result['gross']);
            that.val(result['value']);
            $(this).attr("data-value",input_val);
          }
        });
     }
  });
  $(".percent_four_value").blur(function() {
    var input_val = $(this).val();
    var attachmentid = $(this).attr('data-element');
    var fileid = $(this).attr('data-file');
    var that = $(this);
    if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
    {
      $(this).attr("data-value",input_val);
       $.ajax({
          url:"<?php echo URL::to('user/update_percent_four_infile_attachment'); ?>",
          type:"get",
          dataType:"json",
          data:{input:input_val,attachmentid:attachmentid,fileid:fileid},
          success: function(result) {
            $(".net_value_"+attachmentid).val(result['net']);
            $(".vat_value_"+attachmentid).val(result['vat']);
            $(".gross_value_"+attachmentid).val(result['gross']);

            $(".net_value_"+attachmentid).attr("data-value",result['net']);
            $(".vat_value_"+attachmentid).attr("data-value",result['vat']);
            $(".gross_value_"+attachmentid).attr("data-value",result['gross']);
            that.val(result['value']);
            $(this).attr("data-value",input_val);
          }
        });
     }
  });
  $(".percent_five_value").blur(function() {
    var input_val = $(this).val();
    var attachmentid = $(this).attr('data-element');
    var fileid = $(this).attr('data-file');
    var that = $(this);
    if($(".infile_tr_body_"+fileid).find(".show_previous").hasClass('disabled_prev_btn'))
    {
      $(this).attr("data-value",input_val);
       $.ajax({
          url:"<?php echo URL::to('user/update_percent_five_infile_attachment'); ?>",
          type:"get",
          dataType:"json",
          data:{input:input_val,attachmentid:attachmentid,fileid:fileid},
          success: function(result) {
            $(".net_value_"+attachmentid).val(result['net']);
            $(".vat_value_"+attachmentid).val(result['vat']);
            $(".gross_value_"+attachmentid).val(result['gross']);

            $(".net_value_"+attachmentid).attr("data-value",result['net']);
            $(".vat_value_"+attachmentid).attr("data-value",result['vat']);
            $(".gross_value_"+attachmentid).attr("data-value",result['gross']);
            that.val(result['value']);
            $(this).attr("data-value",input_val);
          }
        });
     }
  });
  $(".date_attachment").on("dp.hide", function (e) {
    var attachment_id = $(this).attr("data-element");
    var file_id = $(this).attr("data-file");
    if($(".infile_tr_body_"+file_id).find(".show_previous").hasClass('disabled_prev_btn'))
    {
      var dateval = $(this).val();
      $.ajax({
        url:"<?php echo URL::to('user/infile_attachment_date_filled'); ?>",
        type:"post",
        data:{id:attachment_id,dateval:dateval},
        success: function(result)
        {
          $(this).attr("data-value",dateval);
        }
      })
    }
  });
  $(".code_attachment").blur(function(){
    var attachment_id = $(this).attr("data-element");
    var file_id = $(this).attr("data-file");
    if($(".infile_tr_body_"+file_id).find(".show_previous").hasClass('disabled_prev_btn'))
    {
      var code = $(this).val();
      $.ajax({
        url:"<?php echo URL::to('user/infile_attachment_code_filled'); ?>",
        type:"post",
        data:{id:attachment_id,code:code},
        success: function(result)
        {

        }
      })
    }
  });

  $(".currency_value").blur(function(){
    var attachment_id = $(this).attr("data-element");
    var file_id = $(this).attr("data-file");
    if($(".infile_tr_body_"+file_id).find(".show_previous").hasClass('disabled_prev_btn'))
    {
      var code = $(this).val();
      $.ajax({
        url:"<?php echo URL::to('user/infile_attachment_currency_filled'); ?>",
        type:"post",
        data:{id:attachment_id,currency:code},
        success: function(result)
        {

        }
      })
    }
  });

  $(".value_value").blur(function(){
    var attachment_id = $(this).attr("data-element");
    var file_id = $(this).attr("data-file");
    if($(".infile_tr_body_"+file_id).find(".show_previous").hasClass('disabled_prev_btn'))
    {
      var code = $(this).val();
      $.ajax({
        url:"<?php echo URL::to('user/infile_attachment_value_filled'); ?>",
        type:"post",
        data:{id:attachment_id,value:code},
        success: function(result)
        {

        }
      })
    }
  });
}
// Basic example
$(document).ready(function () {
  $(".imported_date").datetimepicker({
       format: 'L',
       format: 'DD-MMM-YYYY',
    });
	$('.complete_date').datetimepicker({

          widgetPositioning: {

              horizontal: 'left'

          },

          icons: {

              time: "fa fa-clock-o",

              date: "fa fa-calendar",

              up: "fa fa-arrow-up",

              down: "fa fa-arrow-down"

          },

          format: 'L',

          format: 'DD-MMM-YYYY',

      });
	$('.date_attachment').datetimepicker({
          widgetPositioning: {
              horizontal: 'left'
          },
          icons: {
              time: "fa fa-clock-o",
              date: "fa fa-calendar",
              up: "fa fa-arrow-up",
              down: "fa fa-arrow-down"
          },
          format: 'L',
          format: 'DD/MM/YYYY',
          defaultDate:'',   
      });
	$('[data-toggle="tooltip"]').tooltip(); 
  

  if($("#show_incomplete").is(':checked'))
  {
    $(".user_select").each(function() {
        if($(this).hasClass('disable_class'))
        {
          var fileid = $(this).parents('tr').attr("data-element");
          $(".infile_tr_body_"+fileid).hide();
        }
    });
  }
  else{
    $(".user_select").each(function() {
        if($(this).hasClass('disable_class'))
        {
          var fileid = $(this).parents('tr').attr("data-element");
          $(".infile_tr_body_"+fileid).show();
        }
    });
  }
  	<?php
	if(!empty($_GET['infile_item']))
	{
	  $divid = $_GET['infile_item'];
	  ?>
	  $(document).scrollTop($("#infile_<?php echo $divid; ?>").offset().top - 50);
    $("#infile_<?php echo $divid; ?>").find(".show_attachments").trigger("click");
	  <?php
	}
	?>

});



</script>



<script type="text/javascript">

  $(function () {        

  	var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});

      $('.datepicker-only-init').datetimepicker({

          widgetPositioning: {

              horizontal: 'left'

          },

          icons: {

              time: "fa fa-clock-o",

              date: "fa fa-calendar",

              up: "fa fa-arrow-up",

              down: "fa fa-arrow-down"

          },

          format: 'L',

          format: 'DD-MMM-YYYY',

      });

      $('.datepicker-only-init_date_received').datetimepicker({

          widgetPositioning: {

              horizontal: 'left'

          },

          icons: {

              time: "fa fa-clock-o",

              date: "fa fa-calendar",

              up: "fa fa-arrow-up",

              down: "fa fa-arrow-down"

          },          

          format: 'L',

          maxDate: fullDate,

          format: 'DD-MMM-YYYY',

      });

      

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

$(window).change(function(e) {

  if($(e.target).hasClass('location_path'))
  {
    $("body").removeClass("loading_browse");
  }

  if($(e.target).hasClass('user_select'))

  {

    var input_val = $(e.target).val();

    var id = $(e.target).attr('data-element');

    $.ajax({

        url:"<?php echo URL::to('user/infile_user_update'); ?>",

        type:"get",

        data:{users:input_val,id:id},

        success: function(result) {

          

        }

      });

  }  

  // if($(e.target).hasClass('complete_date'))

  // {

  //   var input_val = $(e.target).val();

  //   var id = $(e.target).attr('data-element');

  //   $.ajax({

  //       url:"<?php echo URL::to('user/infile_complete_date'); ?>",

  //       type:"get",

  //       data:{date:input_val,id:id},

  //       success: function(result) {

          

  //       }

  //     });

  // }

});


jQuery(document).ready(function($) {
  $('[data-toggle="tooltip"]').tooltip();
    var max = 10;
    $('.add_text').keypress(function(e) {
    	
        if (e.which < 0x20) {
            // e.which < 0x20, then it's not a printable character
            // e.which === 0 - Not a character
            return;     // Do nothing
        }
        if (this.value.length == max) {
        	$(this).val($.trim(this.value));
            e.preventDefault();
        } else if (this.value.length > max) {
        	$(this).val($.trim(this.value));
            // Maximum exceeded
            this.value = this.value.substring(0, max);
        }
    });
}); //end if ready(fn)
$(".client_search_class_task").autocomplete({
      source: function(request, response) {
          $.ajax({
              url:"<?php echo URL::to('user/task_client_search'); ?>",
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
        $("#client_search_task").val(ui.item.id);
        $.ajax({
          dataType: "json",
          url:"<?php echo URL::to('user/task_client_search_select'); ?>",
          data:{value:ui.item.id},
          success: function(result){         
            $("#client_search_task").val(ui.item.id);
          }
        })
      }
  });
$(".supplier").autocomplete({
      source: function(request, response) {
          $.ajax({
              url:"<?php echo URL::to('user/infile_supplier_search'); ?>",
              dataType: "json",
              data: {
                  term : request.term,
                  fileid : $("#hidden_file_id_supplier").val(),
              },
              success: function(data) {
                  response(data);
              }
          });
      },
      delay:1000,
      minLength: 1,
      select: function( event, ui ) {
        $.ajax({
          url:"<?php echo URL::to('user/infile_supplier_search_select'); ?>",
          type:"post",
          data:{value:ui.item.value,fileid:ui.item.id,attachment_id:$("#hidden_attachment_id_supplier").val()},
          success: function(result){

          }
        })
      }
  });
$(window).dblclick(function(e) {
	if($(e.target).parents('.td_percent_one').length > 0)
	{
    if(!$(e.target).hasClass('change_percent_one'))
    {
      $(e.target).parents('.td_percent_one').find(".percent_one_div").show();
      var value = $(e.target).parents('.td_percent_one').find(".percent_one_text").html();
      $(e.target).parents('.td_percent_one').find(".percent_one_div").find(".change_percent_one").val(value);
    }
	}
	if($(e.target).parents('.td_percent_two').length > 0)
	{
    if(!$(e.target).hasClass('change_percent_two'))
    {
  		$(e.target).parents('.td_percent_two').find(".percent_two_div").show();
      var value = $(e.target).parents('.td_percent_two').find(".percent_two_text").html();
      $(e.target).parents('.td_percent_two').find(".percent_two_div").find(".change_percent_two").val(value);
    }
	}
	if($(e.target).parents('.td_percent_three').length > 0)
	{
    if(!$(e.target).hasClass('change_percent_three'))
    {
  		$(e.target).parents('.td_percent_three').find(".percent_three_div").show();
      var value = $(e.target).parents('.td_percent_three').find(".percent_three_text").html();
      $(e.target).parents('.td_percent_three').find(".percent_three_div").find(".change_percent_three").val(value);
    }
	}
	if($(e.target).parents('.td_percent_four').length > 0)
	{
    if(!$(e.target).hasClass('change_percent_four'))
    {
  		$(e.target).parents('.td_percent_four').find(".percent_four_div").show();
      var value = $(e.target).parents('.td_percent_four').find(".percent_four_text").html();
      $(e.target).parents('.td_percent_four').find(".percent_four_div").find(".change_percent_four").val(value);
    }
	}
  if($(e.target).parents('.td_percent_five').length > 0)
  {
    if(!$(e.target).hasClass('change_percent_five'))
    {
      $(e.target).parents('.td_percent_five').find(".percent_five_div").show();
      var value = $(e.target).parents('.td_percent_five').find(".percent_five_text").html();
      $(e.target).parents('.td_percent_five').find(".percent_five_div").find(".change_percent_five").val(value);
    }
  }
  if($(e.target).hasClass('supplier'))
  {
    var value = $(e.target).val();
    if(value == "")
    {
      if($(e.target).parents("tr").hasClass('attachment_tr_main'))
      {

      }
      else{
        var input_val = $(e.target).parents("tr").prev().find(".supplier").val();
        var attachment_id = $(e.target).attr("data-element");
        var fileid = $(this).attr('data-file');

        $.ajax({
            url:"<?php echo URL::to('user/update_supplier_infile_attachment'); ?>",
            type:"get",
            data:{input:input_val,attachmentid:attachment_id,fileid:fileid,type:"0"},
            success: function(result) {
              $(e.target).val(input_val);
              $(e.target).attr("data-value",input_val);
            }
        });

      }
    }
  }
});

$('body').on('click', function (e) {
    $('[data-toggle=popover]').each(function () {
        // hide any open popovers when the anywhere else in the body is clicked
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
});

function next_integrity_check(count,filepath,filename)
{
  var fileid = $(".integrity_attachment:eq("+count+")").attr("data-element");
  var keyval = $(".integrity_attachment:eq("+count+")").attr("data-key");
  $.ajax({
    url:"<?php echo URL::to('user/check_files_in_files'); ?>",
    type:"post",
    dataType:"json",
    data:{fileid:fileid,filepath:filepath,filename:filename,type:"1",round:"1"},
    success:function(result)
    {
    	setTimeout( function() {
	      $(".integrity_status_"+fileid).html(result['status']);
	      var countval = count + 1;
	      if($(".integrity_attachment:eq("+countval+")").length > 0)
	      {
	        next_integrity_check(countval,filepath,filename);
	        $("#apply_first").html(countval);
	      }
	      else{
          $(".export_integrity_filename").html('<h5>Download</h5><p><a href="'+result['url']+'" download="">'+result['filename']+'</a></p>');
          $(".export_integrity_filename_a").attr("href",result['url']);
          $(".available_import_div").show();
          var total_files = $(".files_spam").length;
          var ok_files = $(".ok_spam").length;
          var missing_files = $(".missing_spam").length;
          $(".number_of_files").html(total_files)
          $(".number_of_ok_files").html(ok_files)
          $(".number_of_missing_files").html(missing_files)
          if(missing_files > 0){
            $("#availability_form").show();
          }
          else{
            $("#availability_form").hide();
          }
	        $("body").removeClass("loading_apply");
	        $("#export_integrity_filename").show();

          $("#infile_"+result['fileitem_id']).find(".integrity_check").css("background","green");
          $("#infile_"+result['fileitem_id']).find(".integrity_check").parents("td").find(".check_date_time").html(result['check_datetime']);
	      }
	  	},200);
    }
  });
}
function next_integrity_check_div(count,filepath,filename)
{
    var fileid = $(".integrity_check:eq("+count+")").attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/check_integrity_files'); ?>",
      type:"post",
      dataType:"json",
      data:{fileid:fileid,filepath:filepath,filename:filename,type:"1",},
      success:function(result)
      {
      	setTimeout( function() {
          $(".integrity_check:eq("+count+")").css("background","green");
          $(".integrity_check:eq("+count+")").parents("td").find(".check_date_time").html(result['check_datetime']);

	        $("#integrity_check_body").append(result['output']);
	        var countval = count + 1;
	        if($(".integrity_check:eq("+countval+")").length > 0)
	        {
	          next_integrity_check_div(countval,filepath,filename);
	          $("#apply_first").html(countval);
	        }
	        else{
	          $(".export_integrity_filename").html('<h5>Download</h5><p><a href="'+result['url']+'" download="">'+result['filename']+'</a></p>');
            $(".export_integrity_filename_a").attr("href",result['url']);
            $("#export_integrity_filename").show();
	          $("body").removeClass("loading_apply");
	        }
	    },200);
      }
    });
}
function next_attachments_check_div(count)
{
    var fileid = $(".show_attachments:eq("+count+")").attr("data-element");
    if($(".show_attachments:eq("+count+")").hasClass('remove_attachments'))
    {
      setTimeout( function() {
        var countval = count + 1;
        if($(".show_attachments:eq("+countval+")").length > 0)
        {
          next_attachments_check_div(countval);
          $("#apply_first_count").html(countval);
        }
        else{
          $('[data-toggle="tooltip"]').tooltip();
          $("[data-toggle=popover]").each(function(i, obj) {

            $(this).popover({
              html: true,
              content: function() {
                var id = $(this).attr('id')
                return $('#popover-content-' + id).html();
              }
            });

          });
          add_secondary_function();
          $("body").removeClass("loading_attachments");
        }
      },200);
    }
    else{
        $.ajax({
          url:"<?php echo URL::to('user/show_attachments_infile'); ?>",
          type:"post",
          dataType:"json",
          data:{fileid:fileid},
          success:function(result)
          {
            $('.show_attachments:eq('+count+')').addClass("remove_attachments");
            $("#show_attachments_td_"+fileid).html(result['output']);
            $(".show_previous_"+fileid).show();
            $(".show_previous_"+fileid).parents("td:first").append(result['ps_data_btn']);
            $('.show_attachments:eq('+count+')').val("Hide Attachments");

            setTimeout( function() {
              var countval = count + 1;
              if($(".show_attachments:eq("+countval+")").length > 0)
              {
                next_attachments_check_div(countval);
                $("#apply_first_count").html(countval);
              }
              else{
                $('[data-toggle="tooltip"]').tooltip();
                $("[data-toggle=popover]").each(function(i, obj) {

                  $(this).popover({
                    html: true,
                    content: function() {
                      var id = $(this).attr('id')
                      return $('#popover-content-' + id).html();
                    }
                  });

                });
                add_secondary_function();
                $("body").removeClass("loading_attachments");
              }
            },200);
          }
        });
    }
}
function next_available_check_div(count)
{ 
    var url = $(".available_files:eq(0)").parents("tr:first").find(".hidden_file_missing").val();
    var fileid = $(".available_files:eq(0)").parents("tr:first").find(".integrity_attachment").attr("data-file");
    var filename = $(".available_files:eq(0)").attr("data-filename");

    $.ajax({
      url:"<?php echo URL::to('user/import_available_files'); ?>",
      type:"post",
      data:{fileval:url,fileid:fileid,filename:filename},
      success:function(result)
      {
        if(result == "0")
        {
          $(".available_files:eq(0)").parents("tr:first").find(".files_spam").removeClass("missing_spam");
          $(".available_files:eq(0)").parents("tr:first").find(".files_spam").addClass("ok_spam");
          $(".available_files:eq(0)").parents("tr:first").find(".files_spam").css("color","green");
          $(".available_files:eq(0)").parents("tr:first").find(".files_spam").html("OK");
          $(".available_files:eq(0)").parents("tr:first").find(".action_status").html("File Imported");

          var okfiles = $(".number_of_ok_files").html();
          var missingfiles = $(".number_of_missing_files").html();

          okfiles = parseInt(okfiles) + 1;
          missingfiles = parseInt(missingfiles) - 1;
          $(".number_of_ok_files").html(okfiles);
          $(".number_of_missing_files").html(missingfiles);
        }

        setTimeout( function() {
          var countval = count + 1;
          if($(".available_files:eq(0)").length > 0)
          {
            next_available_check_div(countval);
            $("#import_first").html(countval);
          }
          else{
            $("body").removeClass("loading_import");
          }
        },1000);
      }
    });
}
$(window).change(function(e) {
  if($(e.target).hasClass('select_user_author'))
  {
    var value = $(e.target).val();
    if(value == "")
    {
      $(".author_email").val("");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/get_author_email_for_taskmanager'); ?>",
        type:"post",
        data:{value:value},
        success:function(result)
        {
          $(".author_email").val(result);
        }
      })
    }
  }
  if($(e.target).hasClass('allocate_user_add')){
    var value = $(e.target).val();
    if(value == "")
    {
      $(".allocate_email").val("");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/get_author_email_for_taskmanager'); ?>",
        type:"post",
        data:{value:value},
        success:function(result)
        {
          $(".allocate_email").val(result);
        }
      })
    }
  }
});
$(window).click(function(e) {
  if($(e.target).hasClass('summary_infile_attachments')){
    $("body").addClass('loading');
    var file_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/get_summary_infile_attachments'); ?>",
      type:"post",
      data:{file_id:file_id},
      success:function(result){
        $(".summary_infile_modal").modal("show");
        $("#summary_infile_tbody").html(result);
        $("body").removeClass('loading');
      }
    })
  }
  if($(e.target).hasClass('add_progress_files_from_task_specifics'))
  {
    var task_id = $(e.target).attr("data-element");
    $("#hidden_task_id_progress").val(task_id);
    $(".dropzone_progress_modal").modal("show");
    // Dropzone.forElement("#imageUpload").removeAllFiles(true);
    // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
  }
  if($(e.target).hasClass('link_to_task_specifics'))
  {
    if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
    $("#editor_1").val("");
    $("body").addClass("loading");
    setTimeout(function() {
      var task_id = $(e.target).attr("data-element");
      $.ajax({
        url:"<?php echo URL::to('user/show_existing_comments'); ?>",
        type:"post",
        dataType:"json",
        data:{task_id:task_id},
        success:function(result)
        {
            CKEDITOR.replace('editor_1',
             {
              height: '150px',
              enterMode: CKEDITOR.ENTER_BR,
            shiftEnterMode: CKEDITOR.ENTER_P,
            autoParagraph: false,
            entities: false,
            contentsCss: "body {font-size: 16px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif}",
             });
          $("#hidden_task_id_task_specifics").val(task_id);
          $("#add_progress_files_from_task_specifics").attr("data-element",task_id);
          $(".progress_spam").html("");
          $("#existing_comments").html(result['output']);
          $(".title_task_details").html(result['title']);
          $(".user_ratings_div").html(result['user_ratings']);
          $(".task_specifics_modal").modal("show");
          if(result['two_bill'] == "1"){
            $(".2bill_image_comments").show();
          }
          else{
            $(".2bill_image_comments").hide();
          }
          $(".task_title_spec").html(result['task_specifics_name']);
          $(".redlight_indication_"+task_id).hide();
          $(".redlight_indication_layout_"+task_id).hide();
          $(".redlight_indication_layout_"+task_id).removeClass('redline_indication_layout');
          $(".redlight_indication_"+task_id).removeClass('redline_indication');
          if(result['auto_close'] == "1")
          {
            $(".auto_close_task_comment").prop("checked",true);
          }
          else{
            $(".auto_close_task_comment").prop("checked",false);
          }
          $("#show_auto_close_msg").val(result['show_auto_close_msg']);
          
          $("body").removeClass("loading");
        }
      })
    },500);
  }
  if(e.target.id == "allocate_now")
  {
    //$("body").addClass("loading");

    var task_id = $("#hidden_task_id_allocation").val();
    var auto_close = $("#hidden_task_id_auto_close").val();
    var new_allocation = $(".new_allocation").val();
    var author = $("#hidden_task_id_author").val();
    var selected_user = $(".select_user_home").val();

    if(auto_close == "1")
    {
      if(selected_user != author)
      {
        if(author == new_allocation)
        {
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">This Task is an AUTOCLOSE task, by allocating are you happy that this taks is complete with no further action required by the author?  If you select YES this task will be marked COMPLETE and will not be brought to the attention of the Author, If you select NO the task will be place back in the Authors Open Tasks for review</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:" data-task="'+task_id+'" data-new="'+new_allocation+'" data-author="'+author+'" class="common_black_button yes_allocate_now">Yes</a><a href="javascript:" data-task="'+task_id+'" data-new="'+new_allocation+'" data-author="'+author+'" class="common_black_button no_allocate_now">No</a></p>',fixed:true,width:"800px"});
            $("body").removeClass("loading");
            return false;
        }
      }
    }

    if(new_allocation == "")
    {
      alert("Please choose the user to allocate the task.");
    }
    else{

      $.ajax({
        url:"<?php echo URL::to('user/taskmanager_change_allocations'); ?>",
        type:"post",
        data:{task_id:task_id,new_allocation:new_allocation,type:"0"},
        dataType:"json",
        success:function(result)
        {
          $("body").removeClass("loading");
          $(".allocation_modal").modal("hide");
          var layout = $("#hidden_compressed_layout").val();
          
        }
      })
    }
  }
  if($(e.target).hasClass('download_pdf_spec'))
  {
    $("body").addClass("loading");
    var task_id = $("#hidden_task_id_task_specifics").val();
    $.ajax({
      url:"<?php echo URL::to('user/download_pdf_specifics'); ?>",
      type:"post",
      data:{task_id:task_id},
      success:function(result)
      {
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('close_task_specifics')){
    if($(".add_progress_attachments").find("p").length > 0){
      var obj = {};
      obj.message = []; 
      obj.task_id = []; 
      obj.user_id = []; 
      $(".add_progress_attachments").find('p').each(function(index,value) {
        var message = $(this).html();
        var task_id = $(this).attr("data-element");
        var user_id = $(this).attr("data-user");

        obj.message.push([message]);
        obj.task_id.push([task_id]);
        obj.user_id.push([user_id]);
      });

      var messages = JSON.stringify(obj);

      $.ajax({
        url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
        type:"post",
        data:{messages:messages},
        success:function(result){
          $(".add_progress_attachments").html("");
          $("#existing_comments").append(result);
          $("body").removeClass("loading");
        }
      });
    }
  }
  if($(e.target).hasClass('add_task_specifics'))
  {
    var comments = CKEDITOR.instances['editor_1'].getData();
    var task_id = $("#hidden_task_id_task_specifics").val();
    if(comments == "")
    {
      alert("Please enter new comments and then click on the Add New Comment Button");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/add_comment_specifics'); ?>",
        type:"post",
        data:{task_id:task_id,comments:comments},
        success:function(result)
        {
          $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
          $("#editor_1").val("");

          if($(".add_progress_attachments").find("p").length > 0){
            var obj = {};
            obj.message = []; 
            obj.task_id = []; 
            obj.user_id = []; 
            $(".add_progress_attachments").find('p').each(function(index,value) {
              var message = $(this).html();
              var task_id = $(this).attr("data-element");
              var user_id = $(this).attr("data-user");

              obj.message.push([message]);
              obj.task_id.push([task_id]);
              obj.user_id.push([user_id]);
            });

            var messages = JSON.stringify(obj);

            $.ajax({
              url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
              type:"post",
              data:{messages:messages},
              success:function(result){
                $(".add_progress_attachments").html("");
                $("#existing_comments").append(result);
                $("body").removeClass("loading");
              }
            });
          }
          CKEDITOR.instances['editor_1'].setData("");
        }
      })
    }
  }
  if($(e.target).hasClass('auto_close_task_comment'))
  {
    var task_id = $("#hidden_task_id_task_specifics").val();
    if($(e.target).is(":checked"))
    {
      var status = 1;
    }
    else{
      var status = 0;
    }
    $.ajax({
        url:"<?php echo URL::to('user/change_auto_close_status'); ?>",
        type:"post",
        data:{task_id:task_id,status:status},
        success:function(result)
        {
          $("#show_auto_close_msg").val(result);
          if($(e.target).is(":checked"))
          {
            $("#task_tr_"+task_id).find(".mark_as_complete").addClass("auto_close_task_complete");
            $("#task_tr_"+task_id).find(".edit_allocate_user").addClass("auto_close_task_complete");
          }
          else{
            $("#task_tr_"+task_id).find(".mark_as_complete").removeClass("auto_close_task_complete");
            $("#task_tr_"+task_id).find(".edit_allocate_user").removeClass("auto_close_task_complete");
          }
        }
    });
  }
  if($(e.target).hasClass('add_comment_and_allocate'))
  {
      var comments = CKEDITOR.instances['editor_1'].getData();
      var task_id = $("#hidden_task_id_task_specifics").val();
      var show_auto_close = $("#show_auto_close_msg").val();
      if(comments == "")
      {
        alert("Please enter new comments and then click on the Add New Comment Button");
      }
      else{
        if(show_auto_close == "1")
        {
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">This Task is an AUTOCLOSE task, by allocating are you happy that this taks is complete with no further action required by the author?  If you select YES this task will be marked COMPLETE and will not be brought to the attention of the Author, If you select NO the task will be place back in the Authors Open Tasks for review</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:" data-task="'+task_id+'" class="common_black_button yes_allocate_back">Yes</a><a href="javascript:" data-task="'+task_id+'" class="common_black_button no_allocate_back">No</a></p>',fixed:true,width:"800px"});
            $("body").removeClass("loading");
            return false;
        }
        else{
          $("body").addClass("loading");
          setTimeout(function() {
            $.ajax({
              url:"<?php echo URL::to('user/add_comment_and_allocate'); ?>",
              type:"post",
              data:{task_id:task_id,comments:comments},
              success:function(result)
              {
                var new_allocation = result;
                if(new_allocation == "0")
                {
                  $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
                  $("#editor_1").val("");
                  CKEDITOR.instances['editor_1'].setData("");
                  if($(".add_progress_attachments").find("p").length > 0){
                    var obj = {};
                    obj.message = []; 
                    obj.task_id = []; 
                    obj.user_id = []; 
                    $(".add_progress_attachments").find('p').each(function(index,value) {
                      var message = $(this).html();
                      var task_id = $(this).attr("data-element");
                      var user_id = $(this).attr("data-user");

                      obj.message.push([message]);
                      obj.task_id.push([task_id]);
                      obj.user_id.push([user_id]);
                    });

                    var messages = JSON.stringify(obj);

                    $.ajax({
                      url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
                      type:"post",
                      data:{messages:messages},
                      success:function(result){
                        $(".add_progress_attachments").html("");
                        $("#existing_comments").append(result);
                        $("body").removeClass("loading");
                      }
                    });
                  }
                  $("body").removeClass("loading");
                }
                else{
                  $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
                  $("#editor_1").val("");
                  CKEDITOR.instances['editor_1'].setData("");

                  if($(".add_progress_attachments").find("p").length > 0){
                    var obj = {};
                    obj.message = []; 
                    obj.task_id = []; 
                    obj.user_id = []; 
                    $(".add_progress_attachments").find('p').each(function(index,value) {
                      var message = $(this).html();
                      var task_id = $(this).attr("data-element");
                      var user_id = $(this).attr("data-user");

                      obj.message.push([message]);
                      obj.task_id.push([task_id]);
                      obj.user_id.push([user_id]);
                    });

                    var messages = JSON.stringify(obj);

                    $.ajax({
                      url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
                      type:"post",
                      data:{messages:messages},
                      success:function(result){
                        $(".add_progress_attachments").html("");
                        $("#existing_comments").append(result);
                        $("body").removeClass("loading");
                      }
                    });
                  }

                  $(".task_specifics_modal").modal("hide");
                  $.ajax({
                    url:"<?php echo URL::to('user/taskmanager_change_allocations'); ?>",
                    type:"post",
                    data:{task_id:task_id,new_allocation:new_allocation,type:"0"},
                    dataType:"json",
                    success:function(result)
                    {
                      $("body").removeClass("loading");
                    }
                  });
                }
              }
            })
          },1000);
        }
      }
  }
  if($(e.target).hasClass('yes_allocate_back'))
  {
      var comments = CKEDITOR.instances['editor_1'].getData();
      var task_id = $(e.target).attr("data-task");

      $("body").addClass("loading");
      setTimeout(function() {
        $.ajax({
          url:"<?php echo URL::to('user/add_comment_and_allocate'); ?>",
          type:"post",
          data:{task_id:task_id,comments:comments},
          success:function(result)
          {
            var new_allocation = result;
            if(new_allocation == "0")
            {
              $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
              $("#editor_1").val("");
              CKEDITOR.instances['editor_1'].setData("");

              if($(".add_progress_attachments").find("p").length > 0){
                var obj = {};
                obj.message = []; 
                obj.task_id = []; 
                obj.user_id = []; 
                $(".add_progress_attachments").find('p').each(function(index,value) {
                  var message = $(this).html();
                  var task_id = $(this).attr("data-element");
                  var user_id = $(this).attr("data-user");

                  obj.message.push([message]);
                  obj.task_id.push([task_id]);
                  obj.user_id.push([user_id]);
                });

                var messages = JSON.stringify(obj);

                $.ajax({
                  url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
                  type:"post",
                  data:{messages:messages},
                  success:function(result){
                    $(".add_progress_attachments").html("");
                    $("#existing_comments").append(result);
                    $("body").removeClass("loading");
                  }
                });
              }
              $("body").removeClass("loading");
            }
            else{
              $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
              $("#editor_1").val("");
              CKEDITOR.instances['editor_1'].setData("");
              if($(".add_progress_attachments").find("p").length > 0){
                var obj = {};
                obj.message = []; 
                obj.task_id = []; 
                obj.user_id = []; 
                $(".add_progress_attachments").find('p').each(function(index,value) {
                  var message = $(this).html();
                  var task_id = $(this).attr("data-element");
                  var user_id = $(this).attr("data-user");

                  obj.message.push([message]);
                  obj.task_id.push([task_id]);
                  obj.user_id.push([user_id]);
                });

                var messages = JSON.stringify(obj);

                $.ajax({
                  url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
                  type:"post",
                  data:{messages:messages},
                  success:function(result){
                    $(".add_progress_attachments").html("");
                    $("#existing_comments").append(result);
                    $("body").removeClass("loading");
                  }
                });
              }
              $(".task_specifics_modal").modal("hide");
              $.ajax({
                url:"<?php echo URL::to('user/taskmanager_change_allocations'); ?>",
                type:"post",
                data:{task_id:task_id,new_allocation:new_allocation,type:"1"},
                dataType:"json",
                success:function(result)
                {
                  $("body").removeClass("loading");

                  $.colorbox.close();
                }
              });
            }
          }
        })
      },1000);
  }
  if($(e.target).hasClass('no_allocate_back'))
  {
      var comments = CKEDITOR.instances['editor_1'].getData();
      var task_id = $(e.target).attr("data-task");
      
      $("body").addClass("loading");
      setTimeout(function() {
        $.ajax({
          url:"<?php echo URL::to('user/add_comment_and_allocate'); ?>",
          type:"post",
          data:{task_id:task_id,comments:comments},
          success:function(result)
          {
            var new_allocation = result;
            if(new_allocation == "0")
            {
              $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
              $("#editor_1").val("");
              CKEDITOR.instances['editor_1'].setData("");

              if($(".add_progress_attachments").find("p").length > 0){
                var obj = {};
                obj.message = []; 
                obj.task_id = []; 
                obj.user_id = []; 
                $(".add_progress_attachments").find('p').each(function(index,value) {
                  var message = $(this).html();
                  var task_id = $(this).attr("data-element");
                  var user_id = $(this).attr("data-user");

                  obj.message.push([message]);
                  obj.task_id.push([task_id]);
                  obj.user_id.push([user_id]);
                });

                var messages = JSON.stringify(obj);

                $.ajax({
                  url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
                  type:"post",
                  data:{messages:messages},
                  success:function(result){
                    $(".add_progress_attachments").html("");
                    $("#existing_comments").append(result);
                    $("body").removeClass("loading");
                  }
                });
              }
              $("body").removeClass("loading");
            }
            else{
              $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
              $("#editor_1").val("");
              CKEDITOR.instances['editor_1'].setData("");
              if($(".add_progress_attachments").find("p").length > 0){
                var obj = {};
                obj.message = []; 
                obj.task_id = []; 
                obj.user_id = []; 
                $(".add_progress_attachments").find('p').each(function(index,value) {
                  var message = $(this).html();
                  var task_id = $(this).attr("data-element");
                  var user_id = $(this).attr("data-user");

                  obj.message.push([message]);
                  obj.task_id.push([task_id]);
                  obj.user_id.push([user_id]);
                });

                var messages = JSON.stringify(obj);

                $.ajax({
                  url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
                  type:"post",
                  data:{messages:messages},
                  success:function(result){
                    $(".add_progress_attachments").html("");
                    $("#existing_comments").append(result);
                    $("body").removeClass("loading");
                  }
                });
              }
              $(".task_specifics_modal").modal("hide");
              $.ajax({
                url:"<?php echo URL::to('user/taskmanager_change_allocations'); ?>",
                type:"post",
                data:{task_id:task_id,new_allocation:new_allocation,type:"0"},
                dataType:"json",
                success:function(result)
                {
                  $("body").removeClass("loading");
                  $.colorbox.close();
                }
              });
            }
          }
        })
      },1000);
  }
  if($(e.target).hasClass('add_comment_allocate_to_btn'))
  {
      var comments = CKEDITOR.instances['editor_1'].getData();
      var task_id = $("#hidden_task_id_task_specifics").val();
      var allocate_to = $(".add_comment_allocate_to").val();
      if(comments == "")
      {
        alert("Please enter new comments and then click on the Add New Comment Button");
      }
      else if(allocate_to == "")
      {
        alert("Please select the user and then proceed with submit button.");
      }
      else{
          $("body").addClass("loading");
          setTimeout(function() {
            $.ajax({
              url:"<?php echo URL::to('user/add_comment_and_allocate_to'); ?>",
              type:"post",
              data:{task_id:task_id,comments:comments,allocate_to:allocate_to},
              success:function(result)
              {
                var new_allocation = result;
                if(new_allocation == "0")
                {
                  $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
                  $("#editor_1").val("");
                  CKEDITOR.instances['editor_1'].setData("");
                  if($(".add_progress_attachments").find("p").length > 0){
                    var obj = {};
                    obj.message = []; 
                    obj.task_id = []; 
                    obj.user_id = []; 
                    $(".add_progress_attachments").find('p').each(function(index,value) {
                      var message = $(this).html();
                      var task_id = $(this).attr("data-element");
                      var user_id = $(this).attr("data-user");

                      obj.message.push([message]);
                      obj.task_id.push([task_id]);
                      obj.user_id.push([user_id]);
                    });

                    var messages = JSON.stringify(obj);

                    $.ajax({
                      url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
                      type:"post",
                      data:{messages:messages},
                      success:function(result){
                        $(".add_progress_attachments").html("");
                        $("#existing_comments").append(result);
                        $("body").removeClass("loading");
                      }
                    });
                  }
                }
                else{
                  $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
                  $("#editor_1").val("");
                  CKEDITOR.instances['editor_1'].setData("");
                  if($(".add_progress_attachments").find("p").length > 0){
                    var obj = {};
                    obj.message = []; 
                    obj.task_id = []; 
                    obj.user_id = []; 
                    $(".add_progress_attachments").find('p').each(function(index,value) {
                      var message = $(this).html();
                      var task_id = $(this).attr("data-element");
                      var user_id = $(this).attr("data-user");

                      obj.message.push([message]);
                      obj.task_id.push([task_id]);
                      obj.user_id.push([user_id]);
                    });

                    var messages = JSON.stringify(obj);

                    $.ajax({
                      url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
                      type:"post",
                      data:{messages:messages},
                      success:function(result){
                        $(".add_progress_attachments").html("");
                        $("#existing_comments").append(result);
                        $("body").removeClass("loading");
                      }
                    });
                  }
                  $(".task_specifics_modal").modal("hide");
                  $.ajax({
                    url:"<?php echo URL::to('user/taskmanager_change_allocations'); ?>",
                    type:"post",
                    data:{task_id:task_id,new_allocation:new_allocation,type:"0"},
                    dataType:"json",
                    success:function(result)
                    {
                      $("body").removeClass("loading");
                    }
                  });
                }
              }
            })
          },1000);
      }
  }
  if($(e.target).hasClass('expand_all_infile_items'))
  {
    $("body").addClass("loading_attachments");
    var countitems = $(".show_attachments").length;
    $("#apply_last_count").html(countitems);
    var fileid = $(".show_attachments:eq(0)").attr("data-element");
    if($(".show_attachments:eq(0)").hasClass('remove_attachments'))
    {
      setTimeout( function() {
        if($(".show_attachments:eq(1)").length > 0)
        {
          next_attachments_check_div(1);
          $("#apply_first_count").html(1);
        }
        else{
          $('[data-toggle="tooltip"]').tooltip();
          $("[data-toggle=popover]").each(function(i, obj) {

            $(this).popover({
              html: true,
              content: function() {
                var id = $(this).attr('id')
                return $('#popover-content-' + id).html();
              }
            });

          });
          add_secondary_function();
          $("body").removeClass("loading_attachments");
        }
      },200);
    }
    else{
        $.ajax({
          url:"<?php echo URL::to('user/show_attachments_infile'); ?>",
          type:"post",
          dataType:"json",
          data:{fileid:fileid},
          success:function(result)
          {
            $('.show_attachments:eq(0)').addClass("remove_attachments");
            $("#show_attachments_td_"+fileid).html(result['output']);
            $(".show_previous_"+fileid).show();
            $(".show_previous_"+fileid).parents("td:first").append(result['ps_data_btn']);
            $('.show_attachments:eq(0)').val("Hide Attachments");
            setTimeout( function() {
              if($(".show_attachments:eq(1)").length > 0)
              {
                next_attachments_check_div(1);
                $("#apply_first_count").html(1);
              }
              else{
                $('[data-toggle="tooltip"]').tooltip();
                $("[data-toggle=popover]").each(function(i, obj) {

                  $(this).popover({
                    html: true,
                    content: function() {
                      var id = $(this).attr('id')
                      return $('#popover-content-' + id).html();
                    }
                  });

                });
                add_secondary_function();
                $("body").removeClass("loading_attachments");
              }
            },200);
          }
        });
    }
  }
  if($(e.target).hasClass('edit_description'))
  {
    var fileid = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/infile_edit_description'); ?>",
      type:"post",
      data:{fileid:fileid},
      success:function(result)
      {
        $(".edit_description_modal").modal("show");
        $(".edit_description_infile_id").val(fileid);
        $(".description_edit_text").val(result);
      }
    })
  }
  if($(e.target).hasClass('edit_description_btn'))
  {
    var fileid = $(".edit_description_infile_id").val();
    var value = $(".description_edit_text").val();
    if(value == "")
    {
      alert("Please enter the description and then proceed with submit button.");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/update_edit_description'); ?>",
        type:"post",
        data:{fileid:fileid,value:value},
        success:function(result)
        {
          $(".edit_description_modal").modal("hide");
          $(".des_"+fileid).html(value);
        }
      });
    }
  }
  if($(e.target).hasClass('auto_increment'))
  {
    $(e.target).popover("toggle");
  }
  if($(e.target).hasClass('imported_workings'))
  {
    var file_id = $(e.target).attr("data-element");
    if($(e.target).is(":checked"))
    {
      var status = 1;
      $(".imported_date_"+file_id).css("display","initial");
    }
    else{
      var status = 0;
      $(".imported_date_"+file_id).css("display","none");
    }

    $.ajax({
        url:"<?php echo URL::to('user/save_imported_status'); ?>",
        type:"post",
        data:{file_id:file_id,status:status},
        success:function(result)
        {
          
        }
    });
  }
  if($(e.target).hasClass('submit_auto_value'))
  {
    var file_id = $(e.target).attr("data-element");
    var value = $(e.target).parents(".form-data").find(".auto_number_value_"+file_id).val();
    var inc = $(e.target).parents(".form-data").find(".inc_number_value_"+file_id).val();
    var radio = $(e.target).parents(".popover-content").find(".item_auto_num_"+file_id+":checked").val();
    var pchecked = $("#bspo_id_"+file_id).find(".p_check:checked").length;
    var schecked = $("#bspo_id_"+file_id).find(".s_check:checked").length;

    if(value == "")
    {
      alert("Please Enter the Value");
    }
    else if(inc == "")
    {
      alert("Please Enter the Increment Value");
    }
    else if(typeof radio === "undefined" || radio == "")
    {
      alert("Please Select the Item");
    }
    else if(radio == "1" && pchecked == 0)
    {
      alert("Sorry, can't number the infile items as none of the items are specified as Purchase.")
    }
    else if(radio == "2" && schecked == 0)
    {
      alert("Sorry, can't number the infile items as none of the items are specified as Sales.")
    }
    else{
      $("body").addClass("loading_number");
      setTimeout(function(){
        $.ajax({
          url:"<?php echo URL::to('user/check_secondary_line_has_value'); ?>",
          type:"post",
          data:{file_id:file_id,radio:radio},
          success:function(result)
          {
            if(result == 0)
            {
              $.ajax({
                url:"<?php echo URL::to('user/update_infile_textvalue_item'); ?>",
                type:"post",
                data:{file_id:file_id,value:value,inc:inc,radio:radio},
                success:function(result)
                {
                  $("[data-toggle='popover']").popover('hide');
                  var place = inc.split(".");
                  var get_place_length = place.length;
                  if(get_place_length > 1)
                  {
                    var places = place[1].length;
                  }
                  else{
                    var places = 0;
                  }
                  var alert_value = 1;
                  if(radio == "1")
                  {
                    $("#bspo_id_"+file_id).find(".p_check:checked").each(function() {
                       var textval = $(this).parents(".attachment_tr:first").find(".add_text").val();
                       if(textval == "")
                       {
                        $(this).parents(".attachment_tr:first").find(".add_text").val(value);
                        var attachment_id = $(this).parents(".attachment_tr:first").find(".add_text").attr("data-element");
                        var subvalue = value;
                        $(".attachment_tr_"+attachment_id).each(function() {
                          subvalue = parseFloat(parseFloat(subvalue) + .001).toFixed(3);
                          $(this).find(".add_text").val(subvalue);
                        });
                        value = parseFloat(parseFloat(value) + parseFloat(inc)).toFixed(places);
                        alert_value = alert_value + 1;
                       }
                    });
                  }
                  else
                  {
                    $("#bspo_id_"+file_id).find(".s_check:checked").each(function() {
                       var textval = $(this).parents(".attachment_tr:first").find(".add_text").val();
                       if(textval == "")
                       {
                        $(this).parents(".attachment_tr:first").find(".add_text").val(value);
                        var attachment_id = $(this).parents(".attachment_tr:first").find(".add_text").attr("data-element");
                        var subvalue = value;
                        $(".attachment_tr_"+attachment_id).each(function() {
                          subvalue = parseFloat(parseFloat(subvalue) + .001).toFixed(3);
                          $(this).find(".add_text").val(subvalue);
                        });
                        value = parseFloat(parseFloat(value) + parseFloat(inc)).toFixed(places);
                        alert_value = alert_value + 1;
                       }
                    });
                  }
                  if(alert_value == 1)
                  {
                    alert("All Infile items are already numbered. No changes made.");
                  }
                  $("body").removeClass("loading_number");
                }
              })
            }
            else{
              $("#hidden_no_file_id").val(file_id);
              $("#hidden_no_value").val(value);
              $("#hidden_no_inc").val(inc);
              $("#hidden_no_radio").val(radio);
              $("body").removeClass("loading_number");
              $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">We find that some of the Secondary lines are already numbered. Hence, we are about to Remove the Current Numbering, and Re-number based on your Entry above. </p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><input type="button" class="common_black_button yes_number" value="Yes"> <input type="button" class="common_black_button no_number" value="No"></p>',fixed:true,width:"800px"});
            }
          }
        });
      },1000);
    }
  }
  if($(e.target).hasClass('yes_number'))
  {
    $("body").addClass("loading_number");
    setTimeout(function() {
      var file_id = $("#hidden_no_file_id").val();
      var value = $("#hidden_no_value").val();
      var inc = $("#hidden_no_inc").val();
      var radio = $("#hidden_no_radio").val();

      $.ajax({
        url:"<?php echo URL::to('user/update_infile_textvalue_item'); ?>",
        type:"post",
        data:{file_id:file_id,value:value,inc:inc,radio:radio},
        success:function(result)
        {
          $("[data-toggle='popover']").popover('hide');
          var place = inc.split(".");
          var get_place_length = place.length;
          if(get_place_length > 1)
          {
            var places = place[1].length;
          }
          else{
            var places = 0;
          }
          var alert_value = 1;
          if(radio == "1")
          {
            $("#bspo_id_"+file_id).find(".p_check:checked").each(function() {
               var textval = $(this).parents(".attachment_tr:first").find(".add_text").val();
               if(textval == "")
               {
                $(this).parents(".attachment_tr:first").find(".add_text").val(value);
                var attachment_id = $(this).parents(".attachment_tr:first").find(".add_text").attr("data-element");
                var subvalue = value;
                $(".attachment_tr_"+attachment_id).each(function() {
                  subvalue = parseFloat(parseFloat(subvalue) + .001).toFixed(3);
                  $(this).find(".add_text").val(subvalue);
                });
                value = parseFloat(parseFloat(value) + parseFloat(inc)).toFixed(places);
                alert_value = alert_value + 1;
               }
            });
          }
          else
          {
            $("#bspo_id_"+file_id).find(".s_check:checked").each(function() {
               var textval = $(this).parents(".attachment_tr:first").find(".add_text").val();
               if(textval == "")
               {
                $(this).parents(".attachment_tr:first").find(".add_text").val(value);
                var attachment_id = $(this).parents(".attachment_tr:first").find(".add_text").attr("data-element");
                var subvalue = value;
                $(".attachment_tr_"+attachment_id).each(function() {
                  subvalue = parseFloat(parseFloat(subvalue) + .001).toFixed(3);
                  $(this).find(".add_text").val(subvalue);
                });
                value = parseFloat(parseFloat(value) + parseFloat(inc)).toFixed(places);
                alert_value = alert_value + 1;
               }
            });
          }
          if(alert_value == 1)
          {
            alert("All Infile items are already numbered. No changes made.");
          }
          $("body").removeClass("loading_number");
          $("#hidden_no_file_id").val("");
          $("#hidden_no_value").val("");
          $("#hidden_no_inc").val("");
          $("#hidden_no_radio").val("");
          $.colorbox.close();
        }
      })
    },1000);
  }
  if($(e.target).hasClass('no_number'))
  {
    $("#hidden_no_file_id").val("");
    $("#hidden_no_value").val("");
    $("#hidden_no_inc").val("");
    $("#hidden_no_radio").val("");
    $.colorbox.close();
  }
  if($(e.target).hasClass('submit_re_number'))
  {
    var file_id = $(e.target).attr("data-element");
    var value = $(e.target).parents(".form-data").find(".auto_number_value_"+file_id).val();
    var inc = $(e.target).parents(".form-data").find(".inc_number_value_"+file_id).val();
    var radio = $(e.target).parents(".popover-content").find(".item_auto_num_"+file_id+":checked").val();
    var pchecked = $("#bspo_id_"+file_id).find(".p_check:checked").length;
    var schecked = $("#bspo_id_"+file_id).find(".s_check:checked").length;

    if(value == "")
    {
      alert("Please Enter the Value");
    }
    else if(inc == "")
    {
      alert("Please Enter the Increment Value");
    }
    else if(typeof radio === "undefined" || radio == "")
    {
      alert("Please Select the Item");
    }
    else if(radio == "1" && pchecked == 0)
    {
      alert("Sorry, can't number the infile items as none of the items are specified as Purchase.")
    }
    else if(radio == "2" && schecked == 0)
    {
      alert("Sorry, can't number the infile items as none of the items are specified as Sales.")
    }
    else{
      $("#hidden_re_no_file_id").val(file_id);
      $("#hidden_re_no_value").val(value);
      $("#hidden_re_no_inc").val(inc);
      $("#hidden_re_no_radio").val(radio);

      $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">We are about to Remove the Current Numbering, and ReNumber based on your Entry above</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><input type="button" class="common_black_button yes_renumber" value="Yes"> <input type="button" class="common_black_button no_renumber" value="No"></p>',fixed:true,width:"800px"});
    }
  }
  if($(e.target).hasClass('yes_renumber'))
  {
    $("body").addClass("loading_number");
    setTimeout(function() {
      var file_id = $("#hidden_re_no_file_id").val();
      var value = $("#hidden_re_no_value").val();
      var inc = $("#hidden_re_no_inc").val();
      var radio = $("#hidden_re_no_radio").val();

      $.ajax({
        url:"<?php echo URL::to('user/renumber_infile_textvalue_item'); ?>",
        type:"post",
        data:{file_id:file_id,value:value,inc:inc,radio:radio},
        success:function(result)
        {
          $("[data-toggle='popover']").popover('hide');
          var place = inc.split(".");
          var get_place_length = place.length;
          if(get_place_length > 1)
          {
            var places = place[1].length;
          }
          else{
            var places = 0;
          }
          if(radio == "1")
          {
            $("#bspo_id_"+file_id).find(".p_check:checked").each(function() {
                $(this).parents(".attachment_tr:first").find(".add_text").val(value);
                var attachment_id = $(this).parents(".attachment_tr:first").find(".add_text").attr("data-element");
                var subvalue = value;
                $(".attachment_tr_"+attachment_id).each(function() {
                  subvalue = parseFloat(parseFloat(subvalue) + .001).toFixed(3);
                  $(this).find(".add_text").val(subvalue);
                });

                value = parseFloat(parseFloat(value) + parseFloat(inc)).toFixed(places);
            });
          }
          else
          {
            $("#bspo_id_"+file_id).find(".s_check:checked").each(function() {
                $(this).parents(".attachment_tr:first").find(".add_text").val(value);
                var attachment_id = $(this).parents(".attachment_tr:first").find(".add_text").attr("data-element");
                var subvalue = value;
                $(".attachment_tr_"+attachment_id).each(function() {
                  subvalue = parseFloat(parseFloat(subvalue) + .001).toFixed(3);
                  $(this).find(".add_text").val(subvalue);
                });

                value = parseFloat(parseFloat(value) + parseFloat(inc)).toFixed(places);
            });
          }
          $("#hidden_re_no_file_id").val("");
          $("#hidden_re_no_value").val("");
          $("#hidden_re_no_inc").val("");
          $("#hidden_re_no_radio").val("");
          $("body").removeClass("loading_number");
          $.colorbox.close();
        }
      })
    },1000);
  }
  if($(e.target).hasClass('no_renumber'))
  {
    $("#hidden_re_no_file_id").val("");
    $("#hidden_re_no_value").val("");
    $("#hidden_re_no_inc").val("");
    $("#hidden_re_no_radio").val("");
    $.colorbox.close();
  }
  if($(e.target).hasClass('location_path'))
  {
    $("body").addClass("loading_browse");
  }
  if($(e.target).hasClass('available_files'))
  {
    $("body").addClass("loading_available");
    setTimeout(function() {
        var url = $(e.target).parents("tr:first").find(".hidden_file_missing").val();
        var fileid = $(e.target).parents("tr:first").find(".integrity_attachment").attr("data-file");
        var filename = $(e.target).attr("data-filename");

        $.ajax({
          url:"<?php echo URL::to('user/import_available_files'); ?>",
          type:"post",
          data:{fileval:url,fileid:fileid,filename:filename},
          success:function(result)
          {
            if(result == "0")
            {
              $(e.target).parents("tr:first").find(".files_spam").removeClass("missing_spam");
              $(e.target).parents("tr:first").find(".files_spam").addClass("ok_spam");
              $(e.target).parents("tr:first").find(".files_spam").css("color","green");
              $(e.target).parents("tr:first").find(".files_spam").html("OK");
              $(e.target).parents("tr:first").find(".action_status").html("File Imported");

              var okfiles = $(".number_of_ok_files").html();
              var missingfiles = $(".number_of_missing_files").html();

              okfiles = parseInt(okfiles) + 1;
              missingfiles = parseInt(missingfiles) - 1;
              $(".number_of_ok_files").html(okfiles);
              $(".number_of_missing_files").html(missingfiles);
            }
            $("body").removeClass("loading_available");
          }
        })
    },1000);
  }
  if($(e.target).hasClass('import_available_files'))
  {
    $("body").addClass("loading_import");
    var countavailable = $(".available_files").length;
    $("#import_last").html(countavailable);
    var url = $(".available_files:eq(0)").parents("tr:first").find(".hidden_file_missing").val();
    var fileid = $(".available_files:eq(0)").parents("tr:first").find(".integrity_attachment").attr("data-file");
    var filename = $(".available_files:eq(0)").attr("data-filename");

    $.ajax({
      url:"<?php echo URL::to('user/import_available_files'); ?>",
      type:"post",
      data:{fileval:url,fileid:fileid,filename:filename},
      success:function(result)
      {
        if(result == "0")
        {
          $(".available_files:eq(0)").parents("tr:first").find(".files_spam").removeClass("missing_spam");
          $(".available_files:eq(0)").parents("tr:first").find(".files_spam").addClass("ok_spam");
          $(".available_files:eq(0)").parents("tr:first").find(".files_spam").css("color","green");
          $(".available_files:eq(0)").parents("tr:first").find(".files_spam").html("OK");
          $(".available_files:eq(0)").parents("tr:first").find(".action_status").html("File Imported");

          var okfiles = $(".number_of_ok_files").html();
          var missingfiles = $(".number_of_missing_files").html();

          okfiles = parseInt(okfiles) + 1;
          missingfiles = parseInt(missingfiles) - 1;
          $(".number_of_ok_files").html(okfiles);
          $(".number_of_missing_files").html(missingfiles);
        }

        setTimeout( function() {
          if($(".available_files:eq(1)").length > 0)
          {
            next_available_check_div(1);
            $("#import_first").html(1);
          }
          else{
            $("body").removeClass("loading_import");
          }
        },3000);
      }
    });
  }
  if($(e.target).hasClass('show_attachments'))
  {
    if($(e.target).hasClass('remove_attachments'))
    {
      var fileid = $(e.target).attr("data-element");
      $("#show_attachments_td_"+fileid).html("");
      $(".show_previous_"+fileid).parents("td:first").find(".fa-dot-circle-o").detach();
      $(e.target).removeClass("remove_attachments");
      $(e.target).val("Show Attachments");
      $(".show_previous_"+fileid).hide();
    }
    else{
      $("body").addClass("loading");
      setTimeout(function() {
        var fileid = $(e.target).attr("data-element");
        $.ajax({
          url:"<?php echo URL::to('user/show_attachments_infile'); ?>",
          type:"post",
          dataType:"json",
          data:{fileid:fileid},
          success:function(result)
          {
            $(e.target).addClass("remove_attachments");
            $("#show_attachments_td_"+fileid).html(result['output']);
            $(".show_previous_"+fileid).show();
            $(".show_previous_"+fileid).parents("td:first").append(result['ps_data_btn']);
            $(e.target).val("Hide Attachments");
            $('[data-toggle="tooltip"]').tooltip();
            $("[data-toggle=popover]").each(function(i, obj) {

              $(this).popover({
                html: true,
                content: function() {
                  var id = $(this).attr('id')
                  return $('#popover-content-' + id).html();
                }
              });

            });


            add_secondary_function();
            $("body").removeClass("loading");
          }
        });
      },1000);
    }
  }
  if($(e.target).hasClass('integrity_check_for_all'))
  {
    $("body").addClass("loading_apply");
    $("#integrity_check_body").html("");
    var countintegrity = $(".integrity_check").length;
    $("#apply_last").html(countintegrity);
    var fileid = $(".integrity_check:eq(0)").attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/check_integrity_files'); ?>",
      type:"post",
      dataType:"json",
      data:{fileid:fileid,filepath:"",filename:"",type:"0"},
      success:function(result)
      {
        $(".integrity_check:eq(0)").css("background","green");
          $(".integrity_check:eq(0)").parents("td").find(".check_date_time").html(result['check_datetime']);

        $(".available_import_div").hide();
        $(".integrity_check_modal").modal("show");
        setTimeout( function() {
        	$("#integrity_check_body").append(result['output']);
	        if($(".integrity_check:eq(1)").length > 0)
	        {
	          next_integrity_check_div(1,result['filepath'],result['filename']);
	          $("#apply_first").html(1);
	        }
	        else{
	          
	          $(".export_integrity_filename").html('<h5>Download</h5><p><a href="'+result['url']+'" download="">'+result['filename']+'</a></p>');
            $(".export_integrity_filename_a").attr("href",result['url']);
            $("#export_integrity_filename").show();
	          $("body").removeClass("loading_apply");
	        }
        },200);
      }
    });
  }
  if($(e.target).hasClass('integrity_check'))
  {
    $("body").addClass("loading");
    var fileid = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/check_integrity_files'); ?>",
      type:"post",
      dataType:"json",
      data:{fileid:fileid,filepath:"",filename:"",type:"0"},
      success:function(result)
      {
        $(".available_import_div").hide();
        $("#integrity_check_body").html(result['output']);
        $(".integrity_check_modal").modal("show");
        $(".export_integrity_filename").html(result['files']);
        $(".export_integrity_filename_a").attr("href",result['url']);
        $("#export_integrity_filename").show();
        $("body").removeClass("loading");
        // $.ajax({
        //   url:"<?php echo URL::to('user/get_infile_check_reports'); ?>",
        //   type:"post",
        //   data:{fileid:fileid},
        //   success:function(result)
        //   {
        //     //$("#export_integrity_filename").html(result);
        //     $("body").removeClass("loading");
        //   }
        // })
      }
    });
  }
  if($(e.target).hasClass("check_now"))
  {
    $("body").addClass("loading_apply");
    var countintegrity = $(".integrity_attachment").length;
    $("#apply_last").html(countintegrity);
    var fileid = $(".integrity_attachment:eq(0)").attr("data-element");
    var keyval = $(".integrity_attachment:eq(0)").attr("data-key");
    $.ajax({
      url:"<?php echo URL::to('user/check_files_in_files'); ?>",
      type:"post",
      dataType:"json",
      data:{fileid:fileid,filepath:"",filename:"",type:"0",round:"0"},
      success:function(result)
      {
      	setTimeout( function() {
	        $(".integrity_status_"+fileid).html(result['status']);
	        if($(".integrity_attachment:eq(1)").length > 0)
	        {
	          next_integrity_check(1,result['filepath'],result['filename']);
	          $("#apply_first").html(1);
	        }
	        else{
            $(".export_integrity_filename").append('<p><a href="'+result['url']+'" download="">'+result['filename']+'</a></p>');
            $(".export_integrity_filename_a").attr("href",result['url']);
            $(".available_import_div").show();
            var total_files = $(".files_spam").length;
            var ok_files = $(".ok_spam").length;
            var missing_files = $(".missing_spam").length;
            $(".number_of_files").html(total_files);
            $(".number_of_ok_files").html(ok_files);
            $(".number_of_missing_files").html(missing_files);
            if(missing_files > 0){
              $("#availability_form").show();
            }
            else{
              $("#availability_form").hide();
            }
            $("body").removeClass("loading_apply");
            $("#export_integrity_filename").show();

            $("#infile_"+result['fileitem_id']).find(".integrity_check").css("background","green");
            $("#infile_"+result['fileitem_id']).find(".integrity_check").parents("td").find(".check_date_time").html(result['check_datetime']);
	        }
	    },200);
      }
    });
  }
  if($(e.target).hasClass('review_location'))
  {
    var path = $('.location_path')[0].files;
    var countmissing = $(".missing_spam").length;
    var fileslength = path.length;
    if(path.length < 1)
    {
      alert("Please Select the Directory to Review the Missing Files");
    }
    else if(countmissing == 0)
    {
      alert("There is no Missing files to check availability.");
    }
    else{
      $("body").addClass("loading_review");
      setTimeout(function() {
        var missing_files = '';
        var ser = '';
        $(".missing_spam").each(function() {
          var attachment = $(this).find(".hide_attach").html();
          if(missing_files == "")
          {
            missing_files = attachment;
          }
          else{
            missing_files = missing_files+'||'+attachment;
          }
        });
        var missing = missing_files.split("||");
        var countval = 0;
        $.each(path,function(index,value) {
          var name = value.name;
          var nameval = value.name;
          name = name.toLowerCase();
          if(jQuery.inArray(name, missing) !== -1)
          {
            var reader = new FileReader();
            reader.readAsDataURL(value); 
            reader.onloadend = function(e) {
                //console.log(e.target.fileName);
                 var base64data = reader.result;
                 $(".hide_attach:contains("+name+")").parents("tr:first").find(".action_status").html('<a href="javascript:" class="available_files" data-filename="'+nameval+'">Available</a>');
                 $(".hide_attach:contains("+name+")").parents("tr:first").find(".hidden_file_missing").val(base64data);

                  countval = countval + 1;
                  if(countval == fileslength)
                  {
                    $("#location_path").val("");
                    $("body").removeClass("loading_review");
                  }
            }
          }
          else{
            countval = countval + 1;
            if(countval == fileslength)
            {
              $("body").removeClass("loading_review");
            }
          }
        });
      },1000);
    }
  }
  if($(e.target).hasClass('add_secondary'))
  {
    var attach_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/add_new_secondary_line'); ?>",
      type:"post",
      data:{attach_id:attach_id},
      success:function(result)
      {
        if($(e.target).parents("tr").nextAll(".attachment_tr_main").length == 0)
        {
          $(e.target).parents("tbody:first").find("tr:last").before(result);
          $('.date_attachment').datetimepicker({
              widgetPositioning: {
                  horizontal: 'left'
              },
              icons: {
                  time: "fa fa-clock-o",
                  date: "fa fa-calendar",
                  up: "fa fa-arrow-up",
                  down: "fa fa-arrow-down"
              },
              format: 'L',
              format: 'DD/MM/YYYY',
              defaultDate:'',   
          });
          add_secondary_function();
        }
        else{
          $(e.target).parents("tr").nextUntil().each(function() {
            if($(this).hasClass('attachment_tr_main'))
            {
              $(this).prev().before(result);
              $('.date_attachment').datetimepicker({
                  widgetPositioning: {
                      horizontal: 'left'
                  },
                  icons: {
                      time: "fa fa-clock-o",
                      date: "fa fa-calendar",
                      up: "fa fa-arrow-up",
                      down: "fa fa-arrow-down"
                  },
                  format: 'L',
                  format: 'DD/MM/YYYY',
                  defaultDate:'',   
              });
              add_secondary_function();
              return false;
            }
          });
        }
      }
    })
  }
  if($(e.target).hasClass('ps_data'))
  {
    var item_id = $(e.target).attr("data-file");
    var valcount = 0;
    $("#bspo_id_"+item_id).find(".ps_data").each(function() {
      var datavalue = $(this).attr("data-value");
      if(datavalue != "")
      {
        valcount++;
      }
    });
    if(valcount > 0)
    {
      if($(".infile_tr_body_"+item_id).find(".show_previous").hasClass('disabled_prev_btn'))
      {

      }
      else{
        $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">We noticed that data already exist in this Infile item so please click on Load Previously Entered P/S before you modify any value.</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><input type="button" class="common_black_button show_previous" value="Load Previously Entered P/S" data-element="'+item_id+'"></p>',fixed:true,width:"800px"});
      }
    }
    else{
      $(".infile_tr_body_"+item_id).find(".show_previous").addClass('disabled_prev_btn')
    }
  }
	if($(e.target).hasClass('show_iframe_next'))
	{
    $(e.target).parents(".show_iframe").slideRow("up",1000);
		var html = $(e.target).parents(".show_iframe").nextAll(".show_iframe:first").prevAll(".attachment_tr_main:first").find(".fileattachment").eq(1).trigger("click");
    $('html, body').animate({
       scrollTop: ($(e.target).parents(".show_iframe").nextAll(".show_iframe:first").offset().top - 135)
     }, 2000);
	}
	if($(e.target).hasClass('show_iframe_prev'))
	{
		$(e.target).parents(".show_iframe").slideRow("up",1000);
    var html = $(e.target).parents(".show_iframe").prevAll(".show_iframe:first").prevAll(".attachment_tr_main:first").find(".fileattachment").eq(1).trigger("click");
    $('html, body').animate({
       scrollTop: ($(e.target).parents(".show_iframe").prevAll(".show_iframe:first").offset().top - 135)
     }, 2000);
	}
  if($(e.target).hasClass('show_iframe_hide'))
  {
    $(".show_iframe").slideRow("up",1000);
  }
	if($(e.target).hasClass('show_previous'))
	{
		$("body").addClass("loading");
		setTimeout(function(result)
		{
			var fileid = $(e.target).attr("data-element");
			$(".infile_tr_body_"+fileid).find(".ps_data").each(function(){
				var value = $(this).attr("data-value");
				$(this).val(value);
			});
			$(".infile_tr_body_"+fileid).find(".show_previous").addClass("disabled_prev");
      $(".infile_tr_body_"+fileid).find(".show_previous").addClass("disabled_prev_btn");
      $.colorbox.close();
			$("body").removeClass("loading");
		},2000);
	}
	if($(e.target).hasClass('supplier'))
	{
		var fileid = $(e.target).attr("data-file");
		var attachmentid = $(e.target).attr("data-element");
		$("#hidden_file_id_supplier").val(fileid);
		$("#hidden_attachment_id_supplier").val(attachmentid);
	}
	if($(e.target).parents('.percent_one_div').length > 0)
	{
		$(e.target).parents(".percent_one_div").show();
	}
	else{
		$(".percent_one_div").hide();
	}
	if($(e.target).parents('.percent_two_div').length > 0)
	{
		$(e.target).parents(".percent_two_div").show();
	}
	else{
		$(".percent_two_div").hide();
	}
	if($(e.target).parents('.percent_three_div').length > 0)
	{
		$(e.target).parents(".percent_three_div").show();
	}
	else{
		$(".percent_three_div").hide();
	}
	if($(e.target).parents('.percent_four_div').length > 0)
	{
		$(e.target).parents(".percent_four_div").show();
	}
	else{
		$(".percent_four_div").hide();
	}
  if($(e.target).parents('.percent_five_div').length > 0)
  {
    $(e.target).parents(".percent_five_div").show();
  }
  else{
    $(".percent_five_div").hide();
  }
	if($(e.target).hasClass('submit_percent_one'))
	{
		var value = $(e.target).parents(".percent_one_div").find(".change_percent_one").val();
		var fileid = $(e.target).attr("data-element");
		if(value == "")
		{
			alert("Please enter the Value and then press submit button.");
		}
		else{
			var ival = 0;
			$(".percent_one_value_"+fileid).each(function() {
				var value = $(this).attr("data-value");
				if(value != "") { ival++; }
			})
			if(ival == 0)
			{
				$.ajax({
					url:"<?php echo URL::to('user/change_percent_value'); ?>",
					type:"post",
					data:{fileid:fileid,value:value,type:"one"},
					success: function(result)
					{
						$(e.target).parents(".td_percent_one").find(".percent_one_text").html(result);
						$(".percent_one_div").hide();
					}
				})
			}
			else{
				alert("Sorry you cannot make a change if there is an entry for the infiles batch for that given column.");
			}
		}
	}
	if($(e.target).hasClass('submit_percent_two'))
	{
		var value = $(e.target).parents(".percent_two_div").find(".change_percent_two").val();
		var fileid = $(e.target).attr("data-element");
		if(value == "")
		{
			alert("Please enter the Value and then press submit button.");
		}
		else{
			var ival = 0;
			$(".percent_two_value_"+fileid).each(function() {
				var value = $(this).attr("data-value");
				if(value != "") { ival++; }
			});
			if(ival == 0)
			{
				$.ajax({
					url:"<?php echo URL::to('user/change_percent_value'); ?>",
					type:"post",
					data:{fileid:fileid,value:value,type:"two"},
					success: function(result)
					{
						$(e.target).parents(".td_percent_two").find(".percent_two_text").html(result);
						$(".percent_two_div").hide();
					}
				})
			}
			else{
				alert("Sorry you cannot make a change if there is an entry for the infiles batch for that given column.");
			}
		}
	}
	if($(e.target).hasClass('submit_percent_three'))
	{
		var value = $(e.target).parents(".percent_three_div").find(".change_percent_three").val();
		var fileid = $(e.target).attr("data-element");
		if(value == "")
		{
			alert("Please enter the Value and then press submit button.");
		}
		else{
			var ival = 0;
			$(".percent_three_value_"+fileid).each(function() {
				var value = $(this).attr("data-value");
				if(value != "") { ival++; }
			})
			if(ival == 0)
			{
				$.ajax({
					url:"<?php echo URL::to('user/change_percent_value'); ?>",
					type:"post",
					data:{fileid:fileid,value:value,type:"three"},
					success: function(result)
					{
						$(e.target).parents(".td_percent_three").find(".percent_three_text").html(result);
						$(".percent_three_div").hide();
					}
				})
			}
			else{
				alert("Sorry you cannot make a change if there is an entry for the infiles batch for that given column.");
			}
		}
	}
	if($(e.target).hasClass('submit_percent_four'))
	{
		var value = $(e.target).parents(".percent_four_div").find(".change_percent_four").val();
		var fileid = $(e.target).attr("data-element");
		if(value == "")
		{
			alert("Please enter the Value and then press submit button.");
		}
		else{
			var ival = 0;
			$(".percent_four_value_"+fileid).each(function() {
				var value = $(this).attr("data-value");
				if(value != "") { ival++; }
			})
			if(ival == 0)
			{
				$.ajax({
					url:"<?php echo URL::to('user/change_percent_value'); ?>",
					type:"post",
					data:{fileid:fileid,value:value,type:"four"},
					success: function(result)
					{
						$(e.target).parents(".td_percent_four").find(".percent_four_text").html(result);
						$(".percent_four_div").hide();
					}
				})
			}
			else{
				alert("Sorry you cannot make a change if there is an entry for the infiles batch for that given column.");
			}
		}
	}
  if($(e.target).hasClass('submit_percent_five'))
  {
    var value = $(e.target).parents(".percent_five_div").find(".change_percent_five").val();
    var fileid = $(e.target).attr("data-element");
    if(value == "")
    {
      alert("Please enter the Value and then press submit button.");
    }
    else{
      var ival = 0;
      $(".percent_five_value_"+fileid).each(function() {
        var value = $(this).attr("data-value");
        if(value != "") { ival++; }
      })
      if(ival == 0)
      {
        $.ajax({
          url:"<?php echo URL::to('user/change_percent_value'); ?>",
          type:"post",
          data:{fileid:fileid,value:value,type:"five"},
          success: function(result)
          {
            $(e.target).parents(".td_percent_five").find(".percent_five_text").html(result);
            $(".percent_five_div").hide();
          }
        })
      }
      else{
        alert("Sorry you cannot make a change if there is an entry for the infiles batch for that given column.");
      }
    }
  }
	if($(e.target).hasClass('td_supplier'))
	{
		var fileid = $(e.target).attr("data-element");
		$("#hidden_supplier_file_id").val(fileid);
		$.ajax({
			url:"<?php echo URL::to('user/get_supplier_names_from_infile'); ?>",
			type:"post",
			data:{fileid:fileid},
			success:function(result)
			{
				$(".supplier_text").val(result);
				$(".supplier_modal").modal("show");
			}
		})
	}
  if($(e.target).hasClass('build_supplier'))
  {
    $("body").addClass("loading");

    var client_id = $("#client_search_task").val();
    $.ajax({
      url:"<?php echo URL::to('user/build_supplier_names_for_client_id'); ?>",
      type:"post",
      data:{client_id:client_id},
      success:function(result)
      {
        setTimeout(function() {
          $(".supplier_text").val(result);
          $(".supplier_modal").modal("hide");
          $("body").removeClass("loading");
        },1000);
        
      }
    })
  }
  // if($(e.target).hasClass('td_supplier'))
  // {
  //   var client_id = $("#client_search_task").val();
  //   $.ajax({
  //     url:"<?php echo URL::to('user/get_supplier_names_for_client_id'); ?>",
  //     type:"post",
  //     data:{client_id:client_id},
  //     success:function(result)
  //     {
  //       $(".supplier_text").val(result);
  //       $(".supplier_modal").modal("show");
  //     }
  //   })
  // }
    if($(e.target).hasClass('supplier_button'))
	{
		var supplier = $(".supplier_text").val();
		var fileid = $("#hidden_supplier_file_id").val();
		$.ajax({
			url:"<?php echo URL::to('user/set_supplier_names_from_infile'); ?>",
			type:"post",
			data:{fileid:fileid,supplier:supplier},
			success: function(result)
			{
				$(".supplier_modal").modal("hide");
			}
		})
	}
  if($(e.target).hasClass('notepad_contents_task'))
  {
    $(e.target).parents('.modal-body').find('.notepad_div_notes_task').show();
  }
  if($(e.target).hasClass('download_pdf_task'))
  {
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/download_taskmanager_task_pdf'); ?>",
      type:"post",
      data:{task_id:task_id},
      success:function(result)
      {
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('make_task_live'))
  {
    e.preventDefault();
    if($("#create_task_form").valid())
    {
      if($("#internal_checkbox").is(":checked"))
      {
          var taskvalue = $("#idtask").val();
          if(taskvalue == "")
          {
            alert("Please select the Task Name and then make the task as live");
            return false;
          }
      }
      else{
        var clientid = $("#client_search_task").val();
        if(clientid == "")
        {
          alert("Please select the Client and then make the task as live");
          return false;
        }
      }
      if (CKEDITOR.instances.editor_2)
      {
        var comments = CKEDITOR.instances['editor_2'].getData();
        if(comments == "")
        {
          alert("Please Enter Task Specifics and then make the task as Live.");
          return false;
        }
        else{
          if($(".2_bill_task").is(":checked"))
          {
            $("#create_task_form").submit();
          }
          else{
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;"><img src="<?php echo URL::to('assets/2bill.png'); ?>" style="width: 100px;"></p><p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Is this Task a 2Bill Task?  If this is a Non-Standard task for this Client you may want to set the 2Bill Status</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_make_task_live">Yes</a><a href="javascript:" class="common_black_button no_make_task_live">No</a></p>',fixed:true,width:"800px"});
          }
        }
      }
      else{
        if($(".2_bill_task").is(":checked"))
        {
          $("#create_task_form").submit();
        }
        else{
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;"><img src="<?php echo URL::to('assets/2bill.png'); ?>" style="width: 100px;"></p><p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Is this Task a 2Bill Task?  If this is a Non-Standard task for this Client you may want to set the 2Bill Status</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_make_task_live">Yes</a><a href="javascript:" class="common_black_button no_make_task_live">No</a></p>',fixed:true,width:"800px"});
        }
      }
    }
  }
  if($(e.target).hasClass('yes_make_task_live'))
  {
    $(".2_bill_task").prop("checked",true);
      $("#create_task_form").submit();
  }
  if($(e.target).hasClass('no_make_task_live'))
  {
    $(".2_bill_task").prop("checked",false);
      $("#create_task_form").submit();
  }
  if($(e.target).hasClass('accept_recurring'))
  {
    if($(e.target).is(":checked"))
    {
      $(".accept_recurring_div").show();
      $("#recurring_checkbox1").prop("checked",true);
    }
    else{
      $(".accept_recurring_div").hide();
      $(".recurring_checkbox").prop("checked",false);
    }
  }
  if($(e.target).hasClass('remove_infile_link_add'))
  {
    var file_id = $(e.target).attr("data-element");
    var ids = $("#hidden_infiles_id").val();
    var idval = ids.split(",");
    var nextids = '';
    $.each(idval, function( index, value ) {
      if(value != file_id)
      {
        if(nextids == "")
        {
          nextids = value;
        }
        else{
          nextids = nextids+','+value;
        }
      }
    });
    $("#hidden_infiles_id").val(nextids);
    $(e.target).parents("tr").detach();
  }
  if(e.target.id == "link_infile_button")
  {
    var checkcount = $(".infile_check:checked").length;
    if(checkcount > 0)
    {
      var ids = '';
      $(".infile_check:checked").each(function() {
        if(ids == "")
        {
          ids = $(this).val();
        }
        else{
          ids = ids+','+$(this).val();
        }
      });

      $("#hidden_infiles_id").val(ids);
      $(".infiles_modal").modal("hide");
      $.ajax({
        url:"<?php echo URL::to('user/show_linked_infiles'); ?>",
        type:"post",
        data:{ids:ids},
        success:function(result)
        {
          $("#attachments_infiles").show();
          $("#add_infiles_attachments_div").show();
          $("#add_infiles_attachments_div").html(result);
        }
      })
    }
  }
 //  if($(e.target).hasClass('link_infile'))
 //  {
 //  	var href = $(e.target).attr("data-element");
	// var printWin = window.open(href,'_blank','location=no,height=570,width=650,top=80, left=250,leftscrollbars=yes,status=yes');
	// if (printWin == null || typeof(printWin)=='undefined')
	// {
	// 	alert('Please uncheck the option "Block Popup windows" to allow the popup window generated from our website.');
	// }
 //  }
  if($(e.target).hasClass('infiles_link'))
  {
  	var client_id = $("#client_search_task").val();
    var ids = $("#hidden_infiles_id").val();

  	if(client_id == "")
  	{
  		alert("Please select the client and then choose infiles");
  	}
  	else{
  		$.ajax({
  			url:"<?php echo URL::to('user/show_infiles'); ?>",
  			type:"post",
  			data:{client_id:client_id,ids:ids},
  			success: function(result)
  			{
  				$(".infiles_modal").modal("show");
  				$("#infiles_body").html(result);
  			}
  		})
  	}
  }
  if($(e.target).hasClass('notepad_submit_task'))
  { 
    var contents = $(e.target).parent().find('.notepad_contents_task').val();
    if(contents == '' || typeof contents === 'undefined')
    {
      $(e.target).parent().find(".error_files_notepad_add").text("Please Enter the contents for the notepad to save.");
      return false;
    }
    else{
      $(e.target).parents('td').find('.notepad_div_notes_task').toggle();
    }
  }
  else{
    $(".notepad_div_notes_task").each(function() {
      $(this).hide();
    });
  }
  if($(e.target).hasClass('notepad_contents_task'))
  {
    $(e.target).parents('.modal-body').find('.notepad_div_notes_task').show();
  }
  if($(e.target).hasClass('notepad_submit_task'))
  {
    var contents = $(".notepad_contents_task").val();
    $.ajax({
      url:"<?php echo URL::to('user/add_taskmanager_notepad_contents'); ?>",
      type:"post",
      data:{contents:contents},
      dataType:"json",
      success: function(result)
      {
        $("#attachments_text").show();
        $("#add_notepad_attachments_div_task").append("<p>"+result['filename']+" <a href='javascript:' class='remove_notepad_attach_task' data-task='"+result['file_id']+"'>Remove</a></p>");
        $(".notepad_div_notes_task").hide();
      }
    });
  }
  if($(e.target).hasClass("create_task_manager"))
  {
  	var fileid = $(e.target).attr("data-element");
    var description = $(".des_"+fileid).html();
  	$("#hidden_infiles_id").val(fileid);
  	
    $(".create_new_task_model").find(".job_title").html("New Task Creator");
    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
    var user_id = $(".select_user_home").val();
    $(".select_user_author").val("");
    $(".create_new_task_model").modal("show");
    if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
    $(".created_date").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD-MMM-YYYY',
       maxDate: fullDate,
    });
    $(".due_date").datetimepicker({
       defaultDate: fullDate,
       format: 'L',
       format: 'DD-MMM-YYYY',
       minDate: fullDate,
    });
    CKEDITOR.replace('editor_2',
    {
    	height: '150px',
    	enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        autoParagraph: false,
        entities: false,
   });

    $("#action_type").val("1");
    $(".allocate_user_add").val("");
    $(".task-choose_internal").html("Select Task");
    $(".subject_class").val(description);
    $(".task_specifics_add").show();
    CKEDITOR.instances['editor_2'].setData("");
    
    $(".retreived_files_div").hide();
    $(".retreived_files_div").html("");
    $(".recurring_checkbox").prop("checked", false);
    $(".specific_recurring").val("");
    $(".task_specifics_copy_val").html("");
    $("#hidden_task_specifics").val("");

    $("#hidden_specific_type").val("");
    $("#hidden_attachment_type").val("");

    $(".created_date").prop("readonly", true);
    $(".client_group").show();
    $(".client_search_class").prop("required",true);
    $(".internal_tasks_group").hide();
    $("#internal_checkbox").prop("checked",false);
    $(".infiles_link").show();
    $("#attachments_text").hide();
    
    $("#attachments_infiles").hide();
    $("#idtask").val("");

    $("#hidden_copied_files").val("");
    $("#hidden_copied_notes").val("");
    $("#hidden_copied_infiles").val("");

    $(".auto_close_task").prop("checked",false);
    $(".accept_recurring").prop("checked",false);
    $(".accept_recurring_div").hide();
    $("#recurring_checkbox1").prop("checked",false);

    $("#open_task").prop("checked",false);
    $(".allocate_user_add").removeClass("disable_user");
    $(".allocate_email").removeClass("disable_user");
    $.ajax({
      url:"<?php echo URL::to('user/clear_session_task_attachments'); ?>",
      type:"post",
      data:{fileid:fileid},
      success: function(result)
      {
        $("#add_notepad_attachments_div").html('');
        $("#add_attachments_div").html('');
        $("body").removeClass("loading");
        $("#attachments_infiles").show();
        $("#add_infiles_attachments_div").html(result);
        window.history.replaceState(null, null, "<?php echo URL::to('user/infile_search?client_id='.$_GET['client_id'].'&infile_item='); ?>"+fileid);
      }
    })
  }
  
  if($(e.target).hasClass('fanotepadtask')){
    var clientid = $("#client_search_task").val();
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left) - 20;
    $(e.target).parent().find('.notepad_div_notes_task').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
  }
  if($(e.target).hasClass('remove_dropzone_attach_task'))
  {
    var file_id = $(e.target).attr("data-task");
    $.ajax({
      url:"<?php echo URL::to('user/tasks_remove_dropzone_attachment'); ?>",
      type:"post",
      data:{file_id:file_id},
      success: function(result)
      {
        $(e.target).parents("p").detach();
      }
    })
  }
  if($(e.target).hasClass('remove_notepad_attach_task'))
  {
    var file_id = $(e.target).attr("data-task");
    $.ajax({
      url:"<?php echo URL::to('user/tasks_remove_notepad_attachment'); ?>",
      type:"post",
      data:{file_id:file_id},
      success: function(result)
      {
        $(e.target).parents("p").detach();
      }
    })
  }
  if($(e.target).hasClass('b_check'))
  {
    var attachment_id = $(e.target).val();
    if($(e.target).is(":checked"))
    {
      var b_status = 1;
    }
    else{
      var b_status = 0;
    }
    $.ajax({
      url:"<?php echo URL::to('user/change_attachment_bpso_status'); ?>",
      type:"post",
      data:{type:"1",status:b_status,attachment_id:attachment_id},
      success: function(result)
      {
      	$(e.target).parents("tr:first").find(".supplier").prop("disabled",true);
      	$(e.target).parents("tr:first").find(".date_attachment").prop("disabled",true);
        $(e.target).parents("tr:first").find(".code_attachment").prop("disabled",true);

        $(e.target).parents("tr:first").find(".currency_value").prop("disabled",true);
        $(e.target).parents("tr:first").find(".value_value").prop("disabled",true);

      	$(e.target).parents("tr:first").find(".percent_one_value").prop("disabled",true);
      	$(e.target).parents("tr:first").find(".percent_two_value").prop("disabled",true);
      	$(e.target).parents("tr:first").find(".percent_three_value").prop("disabled",true);
      	$(e.target).parents("tr:first").find(".percent_four_value").prop("disabled",true);
        $(e.target).parents("tr:first").find(".percent_five_value").prop("disabled",true);

        $(".attachment_tr_"+attachment_id).find(".supplier").prop("disabled",true);
        $(".attachment_tr_"+attachment_id).find(".date_attachment").prop("disabled",true);
        $(".attachment_tr_"+attachment_id).find(".code_attachment").prop("disabled",true);

        $(".attachment_tr_"+attachment_id).find(".currency_value").prop("disabled",true);
        $(".attachment_tr_"+attachment_id).find(".value_value").prop("disabled",true);

        $(".attachment_tr_"+attachment_id).find(".percent_one_value").prop("disabled",true);
        $(".attachment_tr_"+attachment_id).find(".percent_two_value").prop("disabled",true);
        $(".attachment_tr_"+attachment_id).find(".percent_three_value").prop("disabled",true);
        $(".attachment_tr_"+attachment_id).find(".percent_four_value").prop("disabled",true);
        $(".attachment_tr_"+attachment_id).find(".percent_five_value").prop("disabled",true);


      }
    });
  }
  if($(e.target).hasClass('p_check'))
  {
    var attachment_id = $(e.target).val();
    if($(e.target).is(":checked"))
    {
      var p_status = 1;
    }
    else{
      var p_status = 0;
    }
    $.ajax({
      url:"<?php echo URL::to('user/change_attachment_bpso_status'); ?>",
      type:"post",
      data:{type:"2",status:p_status,attachment_id:attachment_id},
      success: function(result)
      {
      	$(e.target).parents("tr:first").find(".supplier").prop("disabled",false);
      	$(e.target).parents("tr:first").find(".date_attachment").prop("disabled",false);
        $(e.target).parents("tr:first").find(".code_attachment").prop("disabled",false);

        $(e.target).parents("tr:first").find(".currency_value").prop("disabled",false);
        $(e.target).parents("tr:first").find(".value_value").prop("disabled",false);

      	$(e.target).parents("tr:first").find(".percent_one_value").prop("disabled",false);
      	$(e.target).parents("tr:first").find(".percent_two_value").prop("disabled",false);
      	$(e.target).parents("tr:first").find(".percent_three_value").prop("disabled",false);
      	$(e.target).parents("tr:first").find(".percent_four_value").prop("disabled",false);
        $(e.target).parents("tr:first").find(".percent_five_value").prop("disabled",false);

        $(".attachment_tr_"+attachment_id).find(".supplier").prop("disabled",false);
        $(".attachment_tr_"+attachment_id).find(".date_attachment").prop("disabled",false);
        $(".attachment_tr_"+attachment_id).find(".code_attachment").prop("disabled",false);

        $(".attachment_tr_"+attachment_id).find(".currency_value").prop("disabled",false);
        $(".attachment_tr_"+attachment_id).find(".value_value").prop("disabled",false);

        $(".attachment_tr_"+attachment_id).find(".percent_one_value").prop("disabled",false);
        $(".attachment_tr_"+attachment_id).find(".percent_two_value").prop("disabled",false);
        $(".attachment_tr_"+attachment_id).find(".percent_three_value").prop("disabled",false);
        $(".attachment_tr_"+attachment_id).find(".percent_four_value").prop("disabled",false);
        $(".attachment_tr_"+attachment_id).find(".percent_five_value").prop("disabled",false);
      }
    });
  }
  if($(e.target).hasClass('s_check'))
  {
    var attachment_id = $(e.target).val();
    if($(e.target).is(":checked"))
    {
      var s_status = 1;
    }
    else{
      var s_status = 0;
    }
    $.ajax({
      url:"<?php echo URL::to('user/change_attachment_bpso_status'); ?>",
      type:"post",
      data:{type:"3",status:s_status,attachment_id:attachment_id},
      success: function(result)
      {
      	$(e.target).parents("tr:first").find(".supplier").prop("disabled",false);
      	$(e.target).parents("tr:first").find(".date_attachment").prop("disabled",false);
        $(e.target).parents("tr:first").find(".code_attachment").prop("disabled",false);

        $(e.target).parents("tr:first").find(".currency_value").prop("disabled",false);
        $(e.target).parents("tr:first").find(".value_value").prop("disabled",false);

      	$(e.target).parents("tr:first").find(".percent_one_value").prop("disabled",false);
      	$(e.target).parents("tr:first").find(".percent_two_value").prop("disabled",false);
      	$(e.target).parents("tr:first").find(".percent_three_value").prop("disabled",false);
      	$(e.target).parents("tr:first").find(".percent_four_value").prop("disabled",false);
        $(e.target).parents("tr:first").find(".percent_five_value").prop("disabled",false);

        $(".attachment_tr_"+attachment_id).find(".supplier").prop("disabled",false);
        $(".attachment_tr_"+attachment_id).find(".date_attachment").prop("disabled",false);
        $(".attachment_tr_"+attachment_id).find(".code_attachment").prop("disabled",false);

        $(".attachment_tr_"+attachment_id).find(".currency_value").prop("disabled",false);
        $(".attachment_tr_"+attachment_id).find(".value_value").prop("disabled",false);

        $(".attachment_tr_"+attachment_id).find(".percent_one_value").prop("disabled",false);
        $(".attachment_tr_"+attachment_id).find(".percent_two_value").prop("disabled",false);
        $(".attachment_tr_"+attachment_id).find(".percent_three_value").prop("disabled",false);
        $(".attachment_tr_"+attachment_id).find(".percent_four_value").prop("disabled",false);
        $(".attachment_tr_"+attachment_id).find(".percent_five_value").prop("disabled",false);
      }
    });
  }
  if($(e.target).hasClass('o_check'))
  {
    var attachment_id = $(e.target).val();
    if($(e.target).is(":checked"))
    {
      var o_status = 1;
    }
    else{
      var o_status = 0;
    }
    $.ajax({
      url:"<?php echo URL::to('user/change_attachment_bpso_status'); ?>",
      type:"post",
      data:{type:"4",status:o_status,attachment_id:attachment_id},
      success: function(result)
      {
      	$(e.target).parents("tr:first").find(".supplier").prop("disabled",true);
      	$(e.target).parents("tr:first").find(".date_attachment").prop("disabled",true);
        $(e.target).parents("tr:first").find(".code_attachment").prop("disabled",true);

        $(e.target).parents("tr:first").find(".currency_value").prop("disabled",true);
        $(e.target).parents("tr:first").find(".value_value").prop("disabled",true);

      	$(e.target).parents("tr:first").find(".percent_one_value").prop("disabled",true);
      	$(e.target).parents("tr:first").find(".percent_two_value").prop("disabled",true);
      	$(e.target).parents("tr:first").find(".percent_three_value").prop("disabled",true);
      	$(e.target).parents("tr:first").find(".percent_four_value").prop("disabled",true);
        $(e.target).parents("tr:first").find(".percent_five_value").prop("disabled",true);

        $(".attachment_tr_"+attachment_id).find(".supplier").prop("disabled",true);
        $(".attachment_tr_"+attachment_id).find(".date_attachment").prop("disabled",true);
        $(".attachment_tr_"+attachment_id).find(".code_attachment").prop("disabled",true);

        $(".attachment_tr_"+attachment_id).find(".currency_value").prop("disabled",true);
        $(".attachment_tr_"+attachment_id).find(".value_value").prop("disabled",true);

        $(".attachment_tr_"+attachment_id).find(".percent_one_value").prop("disabled",true);
        $(".attachment_tr_"+attachment_id).find(".percent_two_value").prop("disabled",true);
        $(".attachment_tr_"+attachment_id).find(".percent_three_value").prop("disabled",true);
        $(".attachment_tr_"+attachment_id).find(".percent_four_value").prop("disabled",true);
        $(".attachment_tr_"+attachment_id).find(".percent_five_value").prop("disabled",true);
      }
    });
  }
	if($(e.target).hasClass('download_rename'))
  	{
	    e.preventDefault();
	    var element = $(e.target).attr('data-src');
	    var id = $(e.target).attr('data-element');

	    $('body').addClass('loading');

	    $.ajax({
	    	url:"<?php echo URL::to('user/get_attachment_details'); ?>",
	    	type:"get",
	    	data:{id:id,element:element},
	    	success: function(result)
	    	{
	    		if(result == "")
	    		{
	    			SaveToDisk(element,element.split('/').reverse()[0]);
	    		}
	    		else{
	    			SaveToDisk(element,result);
	    		}
	    		
	    	}
	    });
  	}
	if($(e.target).hasClass('add_text_image'))
	{
		$("body").addClass("loading");
		var id = $(e.target).attr("data-element");
		$.ajax({
			url:"<?php echo URL::to('user/change_attachment_text_status'); ?>",
			type:"get",
			data:{id:id},
			success: function(result)
			{
				if($(e.target).parent().find(".fileattachment_checkbox").is(":checked"))
				{
					$(e.target).parent().find(".add_text").prop("disabled",true);
				}
				else{
					$(e.target).parent().find(".add_text").prop("disabled",false);
				}
				$(e.target).parent().find(".add_text").show();
				$(e.target).parent().find(".add_text").val("");
				$(e.target).parent().find(".remove_text_image").show();
				$(e.target).parent().find(".download_rename").show();
				$(e.target).hide();
				$("body").removeClass("loading");
			}
		})
	}
	if($(e.target).hasClass('remove_text_image'))
	{
		$("body").addClass("loading");
		var id = $(e.target).attr("data-element");
		$.ajax({
			url:"<?php echo URL::to('user/remove_attachment_text_status'); ?>",
			type:"get",
			data:{id:id},
			success: function(result)
			{
				$(e.target).parent().find(".add_text_image").show();
				$(e.target).parent().find(".add_text").hide();
				$(e.target).parent().find(".download_rename").hide();
				$(e.target).hide();
				$("body").removeClass("loading");
			}
		})
	}
  if($(e.target).hasClass('single_notify')){

    var taskid = $(e.target).attr("data-element");

    $(".model_notify").modal("show");

    $(".notify_title").html('Send Notification to Selected Staffs');

    $(".notify_file_id").val(taskid);

    $(".notify_id_class").prop("checked", false);

    $(".notify_id_class").prop("disabled", false);



    $("#notity_selectall").prop("checked", false);

    $("#notity_selectall").prop("disabled", false);



  }



  if($(e.target).hasClass('all_notify')){

    var taskid = $(e.target).attr("data-element");

    $(".model_notify").modal("show");

    $(".notify_title").html('Send Notification to All Staffs');

    $(".notify_file_id").val(taskid);

    $(".notify_id_class").prop("checked", true);

    $(".notify_id_class").attr("disabled", true);



    $("#notity_selectall").prop("checked", true);

    $("#notity_selectall").prop("disabled", true);



    //$("#notity_selectall").parent().hide();

  }



  if(e.target.id == "notity_selectall"){

    if($(e.target).is(":checked"))

    {

      $(".notify_id_class").each(function() {

        $(this).prop("checked",true);

      });

    }



    else{

      $(".notify_id_class").each(function() {

        $(this).prop("checked",false);

      });

    }

  }
  if($(e.target).hasClass("notify_all_clients_tasks"))

  {

    $("body").addClass("loading");

    $(".model_notify").modal("hide");

    var emails = [];

    var clientids = [];

    var toemails = '';

    var timeval = '<?php echo time(); ?>';


    var file_id = $(".notify_file_id").val();

    $(".notify_id_class").each(function(i, el) {

        if($(el).is(':checked'))

        {

          var user_email = $(el).attr('data-element');

          var user_id = $(el).attr('data-value');

          

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

          if(user_id != '' && typeof user_id !== 'undefined')

          {

            if($.inArray(user_id, clientids) == -1)

            {

              clientids.push(user_id);

            }

          }

        }

    });

    toemails = toemails+', <?php echo $admin_cc; ?>';

    var option_length = emails.length;

    $.each( emails, function( i, value ) {

        setTimeout(function(){

          $.ajax({

            url:"<?php echo URL::to('user/infile_email_notify_tasks_pdf'); ?>",

            type:"get",

            data:{email:value,clientid:clientids[i],toemails:toemails,file_id:file_id,timeval:timeval},

            success: function(result) {

              var keyi = parseInt(i) + parseInt(1);

              if(option_length == keyi)

              {

                $("body").removeClass("loading");

                $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email sent Successfully</p>", fixed:true});

              }

            }

          });

        },2000 + ( i * 2000 ));

    }); 

  }
  if(e.target.id == "client_search_infile")
  {
    var clientid = $("#client_search_hidden_infile").val();
    if(clientid == "" || typeof clientid === "undefined")
    {
      alert("Please select a client from the autocomplete list and then click submit.");
      return false;
    }
  }
  if($(e.target).hasClass('fileattachment_checkbox'))
  {
    var value = $(e.target).val();
    $("body").addClass("loading");
    if($(e.target).is(":checked"))
    {
      $.ajax({
        url:"<?php echo URL::to('user/fileattachment_status'); ?>",
        type:"post",
        data:{id:value,status:1},
        success: function(result)
        {
        	$(e.target).parent().find(".add_text").prop("disabled",true);
        	$("body").removeClass("loading");
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/fileattachment_status'); ?>",
        type:"post",
        data:{id:value,status:0},
        success: function(result)
        {
        	$(e.target).parent().find(".add_text").prop("disabled",false);
        	$("body").removeClass("loading");
        }
      });
    }
  }
  if($(e.target).parents(".auto_save_date").length > 0)

  {

    var file_id = $(e.target).parents(".auto_save_date").find(".complete_date").attr("data-element");

    $("#hidden_file_id").val(file_id);

  }

  if($(e.target).hasClass('image_submit'))

  {

    var files = $(e.target).parent().find('.image_file').val();

    if(files == '' || typeof files === 'undefines')

    {

      $(e.target).parent().find(".error_files").text("Please Choose the files to proceed");

      return false;

    }

    else{

      $(e.target).parents('td').find('.img_div').toggle();

    }

  }

  else{

    $(".img_div").each(function() {

      $(this).hide();

    });

  }

  if($(e.target).hasClass('image_submit_add'))

  {

    var files = $(e.target).parent().find('.image_file_add').val();

    if(files == '' || typeof files === 'undefines')

    {

      $(e.target).parent().find(".error_files").text("Please Choose the files to proceed");

      return false;

    }

    else{

      $(e.target).parents('.modal-body').find('.img_div').toggle();

    }

  }

  else{

    $(".img_div").each(function() {

      $(this).hide();

    });

  }

  if($(e.target).hasClass('notepad_submit'))

  { 

    var contents = $(e.target).parent().find('.notepad_contents').val();

    if(contents == '' || typeof contents === 'undefined')

    {

      $(e.target).parent().find(".error_files_notepad").text("Please Enter the contents for the notepad to save.");

      return false;

    }

    else{

      $(e.target).parents('td').find('.notepad_div').toggle();

      $(e.target).parents('td').find('.notepad_div_notes').toggle();

    }

  }

  else{

    $(".notepad_div").each(function() {

      $(this).hide();

    });

    $(".notepad_div_notes").each(function() {

      $(this).hide();

    });

  }

  if($(e.target).hasClass('notepad_submit_add'))

  { 

    var contents = $(e.target).parent().find('.notepad_contents_add').val();

    if(contents == '' || typeof contents === 'undefined')

    {

      $(e.target).parent().find(".error_files_notepad_add").text("Please Enter the contents for the notepad to save.");

      return false;

    }

    else{

      $(e.target).parents('td').find('.notepad_div_notes_add').toggle();

    }

  }

  else{

    $(".notepad_div_notes_add").each(function() {

      $(this).hide();

    });

  }

  if($(e.target).hasClass('image_file'))

  {

    $(e.target).parents('td').find('.img_div').toggle();

    $(e.target).parents('.modal-body').find('.img_div').toggle();

  }

  if($(e.target).hasClass('image_file_add'))

  {

    $(e.target).parents('.modal-body').find('.img_div').toggle();

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

  if($(e.target).hasClass('notepad_contents'))

  {

    $(e.target).parents('td').find('.notepad_div').toggle();

    $(e.target).parents('td').find('.notepad_div_notes').toggle();

  }

  if($(e.target).hasClass('notepad_contents_add'))

  {

    $(e.target).parents('.modal-body').find('.notepad_div_notes_add').toggle();

  }

  if($(e.target).hasClass('notepad_submit_add'))

  {

    var contents = $(".notepad_contents_add").val();

    var clientid = $("#client_search").val();

    $.ajax({

      url:"<?php echo URL::to('user/add_notepad_contents'); ?>",

      type:"post",

      data:{contents:contents,clientid:clientid},

      success: function(result)

      {

        $("#attachments_text").show();

        $("#add_notepad_attachments_div").append('<p>'+result+'</p>');

        $(".notepad_div_notes_add").hide();

      }

    });

  }

  if($(e.target).hasClass('trash_imageadd'))

  {

    $("body").addClass("loading");

    $.ajax({

      url:"<?php echo URL::to('user/clear_session_attachments'); ?>",

      type:"post",

      success: function(result)

      {

        $("#add_notepad_attachments_div").html('');

        $("#add_attachments_div").html('');

        $("body").removeClass("loading");

      }

    })

  }

  if($(e.target).hasClass('edit_status')){
    if($(e.target).hasClass('incomplete_status'))
    {
      var r = confirm("Are you sure you want to mark this Infile as Incomplete?");
      if (r == true) {
          $("body").addClass("loading");
          var id = $(e.target).attr("data-element");
          $.ajax({
                url:"<?php echo URL::to('user/in_file_status_update'); ?>",
                data:{status:0,id:id},
                success: function(result) {
                  $("body").removeClass("loading");
                  $(".infile_tr_body_"+id).find("td").css({"color" : "#000"});
                  $(".infile_tr_body_"+id).find("a").css({"color" : "#000"});
                  $(".infile_tr_body_"+id).find("i").css({"color" : "#000"});
                  $(".infile_tr_body_"+id).find(".fa-plus").removeClass("disable_class");
                  $(".infile_tr_body_"+id).find(".fa-minus-square").removeClass("disable_class");
                  $(".infile_tr_body_"+id).find(".fa-pencil-square").removeClass("disable_class");
                  $(".infile_tr_body_"+id).find(".user_select").removeClass("disable_class");
                  $(".infile_tr_body_"+id).find(".complete_date").removeClass("disable_class");
                  $(".infile_tr_body_"+id).find("select").prop("disabled",false);
                  $(".infile_tr_body_"+id).find(".fa-times").addClass('fa-check');
                  $(".infile_tr_body_"+id).find(".fa-times").removeClass('incomplete_status');
                  $(".infile_tr_body_"+id).find(".fa-times").removeClass('fa-times');
                  $(".infile_tr_body_"+id).find(".fileattachment_checkbox").each(function() {
                    if($(this).is(":checked"))
                    {
                      $(this).parent().find(".add_text").prop("disabled",true);
                    }
                  });
                  if($("#show_incomplete").is(':checked'))
                    {
                    $(".user_select").each(function() {
                        if($(this).hasClass('disable_class'))
                        {
                          var fileid = $(this).parents('tr').attr("data-element");
      					  $(".infile_tr_body_"+fileid).hide();
                        }
                    });
                  }
                  else{
                    $(".user_select").each(function() {
                        if($(this).hasClass('disable_class'))
                        {
                          var fileid = $(this).parents('tr').attr("data-element");
          				  $(".infile_tr_body_"+fileid).show();
                        }
                    });
                  }           
                }
          });
      }
      else{
        return false
      }
    }
    else{
      var username = $(e.target).parents("tr").find(".user_select").val();
      var complete_date = $(e.target).parents("tr").find(".complete_date").val();
      if(username == "" || typeof username === "undefined")
      {
        alert("Please choose a Username to mark this file as completed");
      }
      else if(complete_date == "" || typeof complete_date === "undefined")
      {
        alert("Please select the Complete Date to mark this file as completed");
      }
      else{
          var r = confirm("Are you sure you want to Complete this file?");
          if (r == true) {
              $("body").addClass("loading");
              var id = $(e.target).attr("data-element");
              $.ajax({
                    url:"<?php echo URL::to('user/in_file_status_update'); ?>",
                    data:{status:1,id:id},
                    success: function(result) {
                      $("body").removeClass("loading");
                      $(".infile_tr_body_"+id).find("td").css({"color" : "#f00"});
                      $(".infile_tr_body_"+id).find("a").css({"color" : "#f00"});
                      $(".infile_tr_body_"+id).find("i").css({"color" : "#f00"});
                      $(".infile_tr_body_"+id).find(".fa-plus").addClass("disable_class");
                      $(".infile_tr_body_"+id).find(".fa-minus-square").addClass("disable_class");
                      $(".infile_tr_body_"+id).find(".fa-pencil-square").addClass("disable_class");
                      $(".infile_tr_body_"+id).find(".fa-check").css({"color" : "#000"});
                      $(".infile_tr_body_"+id).find(".user_select").addClass("disable_class");
                      $(".infile_tr_body_"+id).find(".complete_date").addClass("disable_class");
                      $(".infile_tr_body_"+id).find("select").prop("disabled",true);
                      $(".infile_tr_body_"+id).find(".fa-check").addClass('fa-times');
                      $(".infile_tr_body_"+id).find(".fa-check").addClass('incomplete_status');
                      $(".infile_tr_body_"+id).find(".fa-check").removeClass('fa-check');
                      if($("#show_incomplete").is(':checked'))
                        {
                        $(".user_select").each(function() {
                            if($(this).hasClass('disable_class'))
                            {
                              var fileid = $(this).parents('tr').attr("data-element");
          						$(".infile_tr_body_"+fileid).hide();
                            }
                        });
                      }
                      else{
                        $(".user_select").each(function() {
                            if($(this).hasClass('disable_class'))
                            {
                              var fileid = $(this).parents('tr').attr("data-element");
          						$(".infile_tr_body_"+fileid).show();
                            }
                        });
                      }
                    }
              });
          }
          else{
            return false
          }
      }
    }
  }


  if($(e.target).hasClass('fa-times')){

    

  }



  /*

  if(e.target.id == 'show_incomplete'){

    Dropzone.autoDiscover = false;

    $("body").addClass("loading");   

    $("#in_file").dataTable().fnDestroy();

    if($(e.target).is(':checked'))

    {

       

      $.ajax({

        url:"<?php echo URL::to('user/in_file_show_incomplete'); ?>",

        type:"post",

        data:{value:1},

        success: function(result)

        {

          $("body").removeClass("loading");

          $("#in_file_tbody").html(result);

          $('#in_file').DataTable({            

              fixedHeader: {

                headerOffset: 75

              },

              autoWidth: true,

              scrollX: false,

              fixedColumns: false,

              searching: false,

              paging: false,

              info: false,            

          });

        }

      });

    }

    else{

      $.ajax({

        url:"<?php echo URL::to('user/in_file_show_incomplete'); ?>",

        type:"post",

        data:{value:0},

        success: function(result)

        {

          $("body").removeClass("loading");

          $("#in_file_tbody").html(result);

          $('#in_file').DataTable({            

              fixedHeader: {

                headerOffset: 75

              },

              autoWidth: true,

              scrollX: false,

              fixedColumns: false,

              searching: false,

              paging: false,

              info: false,            

          });

        }

      });

    }

  }*/



  if(e.target.id == 'show_incomplete'){



    if($("#show_incomplete").is(':checked'))

                {

      $(".user_select").each(function() {

          if($(this).hasClass('disable_class'))

          {

            var fileid = $(this).parents('tr').attr("data-element");
          $(".infile_tr_body_"+fileid).hide();

          }

      });
      var status = 1;
    }

    else{

      $(".user_select").each(function() {

          if($(this).hasClass('disable_class'))

          {

            var fileid = $(this).parents('tr').attr("data-element");
          $(".infile_tr_body_"+fileid).show();

          }

      });
      var status = 0;

    }

    $.ajax({
      url:"<?php echo URL::to('user/infile_incomplete_status'); ?>",
      type:"post",
      data:{status:status},
      success: function(result)
      {

      }
    });

  }





 





  if($(e.target).hasClass('create_new')) {

    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});

    $(".create_new_model").modal("show");

    $(".date_received").datetimepicker({

       defaultDate: fullDate,       

       format: 'L',

       format: 'DD-MMM-YYYY',

       maxDate: fullDate,

    });

    $(".date_added").datetimepicker({

       defaultDate: fullDate,

       format: 'L',

       format: 'DD-MMM-YYYY',

    });

    $(".date_added").prop("readonly", true);

    $("#attachments_text").hide();

  }

  

  if($(e.target).hasClass('fa-plus-add'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left);
    $(e.target).parent().find('.img_div_add').toggle();
    Dropzone.forElement("#imageUpload1").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");    
  }
  else if($(e.target).hasClass('fa-plus-task'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left);
    $(e.target).parent().find('.img_div_task').toggle();
    Dropzone.forElement("#imageUpload5").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");    
  }
  else if($(e.target).hasClass('fa-plus'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left);
    var id= $(e.target).parents("tr:first").attr("data-element");
    $(".infile_tr_body_last_"+id).find('.img_div').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
    $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
  }

  if($(e.target).hasClass('fileattachment'))
  {
  	$(".pdf_multipage").hide();
  	if($(e.target).hasClass('file_attach_bpso'))
  	{
      e.preventDefault();
      var fileid = $(e.target).parents(".file_attachment_div").find(".fileattachment_checkbox").val();
      var element = $(e.target).attr('data-element');
      var src = $(e.target).attr('data-src');
      var srcc = $(e.target).attr('data-src');
      $('body').addClass('loading');
      var attach_type = $(e.target).attr("data-original-title");
      var ext = attach_type.split(".");
      var exttype = ext[ext.length-1];
      $(".show_iframe").hide();
      $(".fa-circle").hide();
      $('[data-toggle="tooltip"]').tooltip("hide");
      if(exttype == "pdf")
      {
        src = src.replace("uploads/","");
        src = src.replace("=","@");
        src = src.replace("=","@");
        src = src.replace("=","@");
        src = src.replace("=","@");
        src = "<?php echo URL::to('uploads/index.html?file='); ?>"+src;
        $.ajax({
          url:"<?php echo URL::to("user/check_pdf_pages"); ?>",
          type:"post",
          data:{src:srcc},
          success:function(result)
          {
            if(result > 1)
            {
              $(".pdf_multipage").show();
            }
            else{
              $(".pdf_multipage").hide();
            }
            $(".show_iframe_"+fileid).find(".attachment_pdf").attr("src",src);
            $(".show_iframe_"+fileid).find(".show_iframe_download").attr("href",element);
            $(".show_iframe_"+fileid).slideRow('down', 500);

            $('html, body').animate({
               scrollTop: ($(e.target).offset().top - 100)
            }, 2000);
            setTimeout(function() {
              $('body').removeClass('loading');
            },2000);
          }
        });
      }
      else if(exttype == "jpg" || exttype == "jpeg" || exttype == "png" || exttype == "tif" || exttype == "tiff" || exttype == "gif")
      {
        $(".show_iframe_"+fileid).find(".attachment_pdf").attr("src",element);
        $(".show_iframe_"+fileid).find(".show_iframe_download").attr("href",element);
        $(".show_iframe_"+fileid).slideRow('down', 500);
        $('html, body').animate({
               scrollTop: ($(e.target).offset().top - 100)
            }, 2000);
        setTimeout(function() {
          $('body').removeClass('loading');
        },2000);
      }
      else{
        $(".show_iframe_"+fileid).find(".attachment_pdf").attr("src","<?php echo URL::to('user/file_not_supported'); ?>");
        $(".show_iframe_"+fileid).find(".show_iframe_download").attr("href",element);
        $(".show_iframe_"+fileid).slideRow('down', 500);
        $('html, body').animate({
               scrollTop: ($(e.target).offset().top - 100)
            }, 2000);
        $('body').removeClass('loading');
      }
      $(e.target).parents("tr:first").find(".fa-circle").show();
      return false;
  	}
    else if($(e.target).hasClass('flag_gray'))
    {
      var fileid = $(e.target).parents(".file_attachment_div").find(".fileattachment_checkbox").val();
      var flag_status = '1';
      $.ajax({
        url:"<?php echo URL::to('user/change_flag_status'); ?>",
        type:"post",
        data:{fileid:fileid,flag_status:flag_status},
        success:function(result)
        {
          $(e.target).removeClass('flag_gray');
          $(e.target).addClass('flag_orange');
        }
      });
    }
    else if($(e.target).hasClass('flag_orange'))
    {
      var fileid = $(e.target).parents(".file_attachment_div").find(".fileattachment_checkbox").val();
      var flag_status = '2';
      $.ajax({
        url:"<?php echo URL::to('user/change_flag_status'); ?>",
        type:"post",
        data:{fileid:fileid,flag_status:flag_status},
        success:function(result)
        {
          $(e.target).removeClass('flag_orange');
          $(e.target).addClass('flag_red');
        }
      });
    }
    else if($(e.target).hasClass('flag_red'))
    {
      var fileid = $(e.target).parents(".file_attachment_div").find(".fileattachment_checkbox").val();
      var flag_status = '0';
      $.ajax({
        url:"<?php echo URL::to('user/change_flag_status'); ?>",
        type:"post",
        data:{fileid:fileid,flag_status:flag_status},
        success:function(result)
        {
          $(e.target).removeClass('flag_red');
          $(e.target).addClass('flag_gray');
        }
      });
    }
    else if($(e.target).hasClass('flag_gray_sub'))
    {
      var fileid = $(e.target).parents(".sub_attachment").attr("data-value");
      var flag_status = '1';
      $.ajax({
        url:"<?php echo URL::to('user/change_flag_status'); ?>",
        type:"post",
        data:{fileid:fileid,flag_status:flag_status},
        success:function(result)
        {
          $(e.target).removeClass('flag_gray_sub');
          $(e.target).addClass('flag_orange_sub');
        }
      });
    }
    else if($(e.target).hasClass('flag_orange_sub'))
    {
      var fileid = $(e.target).parents(".sub_attachment").attr("data-value");
      var flag_status = '2';
      $.ajax({
        url:"<?php echo URL::to('user/change_flag_status'); ?>",
        type:"post",
        data:{fileid:fileid,flag_status:flag_status},
        success:function(result)
        {
          $(e.target).removeClass('flag_orange_sub');
          $(e.target).addClass('flag_red_sub');
        }
      });
    }
    else if($(e.target).hasClass('flag_red_sub'))
    {
      var fileid = $(e.target).parents(".sub_attachment").attr("data-value");
      var flag_status = '0';
      $.ajax({
        url:"<?php echo URL::to('user/change_flag_status'); ?>",
        type:"post",
        data:{fileid:fileid,flag_status:flag_status},
        success:function(result)
        {
          $(e.target).removeClass('flag_red_sub');
          $(e.target).addClass('flag_gray_sub');
        }
      });
    }
  	else{
  		e.preventDefault();
	    var element = $(e.target).attr('data-element');
	    $('body').addClass('loading');
	    setTimeout(function(){
	      SaveToDisk(element,element.split('/').reverse()[0]);
	      $('body').removeClass('loading');
	      }, 3000);
	    return false;
  	}
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



  if($(e.target).hasClass('trash_image'))

  {



    var r = confirm("Are You sure you want to delete");

    if (r == true) {

      var imgid = $(e.target).attr('data-element');

      $.ajax({

          url:"<?php echo URL::to('user/infile_delete_image'); ?>",

          type:"get",

          data:{imgid:imgid},

          success: function(result) {
            if(result == "")
            {
              window.location.reload();
            }
            else{
              window.location.replace(result);
            }
          }

      });

    }

  }



  if($(e.target).hasClass('delete_all_image')){



    var r = confirm("Are You sure you want to delete all the attachments?");

    if (r == true) {

      var id = $(e.target).attr('data-element');

      $.ajax({

          url:"<?php echo URL::to('user/infile_delete_all_image'); ?>",

          type:"get",

          data:{id:id},

          success: function(result) {

            window.location.reload();

          }

      });

    }

  }

  if($(e.target).hasClass('download_all_image')){

      $("body").addClass("loading");

      var id = $(e.target).attr('data-element');

      $.ajax({

          url:"<?php echo URL::to('user/infile_download_all_image'); ?>",

          type:"get",

          data:{id:id},

          success: function(result) {

              SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

              setTimeout(function() {

                $.ajax({

                  url:"<?php echo URL::to('user/delete_file_link'); ?>",

                  type:"post",

                  data:{result:result},

                  success: function(result)

                  {

                    $("body").removeClass("loading");

                  }

                });

              },3000);

          }

      });

  }

  if($(e.target).hasClass('download_rename_all_image')){

      $("body").addClass("loading");

      var id = $(e.target).attr('data-element');

      $.ajax({

          url:"<?php echo URL::to('user/infile_download_rename_all_image'); ?>",

          type:"get",

          data:{id:id},

          success: function(result) {

              SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

              setTimeout(function() {

                $.ajax({

                  url:"<?php echo URL::to('user/delete_file_link'); ?>",

                  type:"post",

                  data:{result:result},

                  success: function(result)

                  {

                    $("body").removeClass("loading");

                  }

                });

              },3000);

          }

      });

  }
  if($(e.target).hasClass('download_b_all_image')){

      var lenval = $(e.target).parents("table").find(".b_check:checked").length;
      if(lenval > 0)
      {
          $("body").addClass("loading");

          var id = $(e.target).attr('data-element');

          $.ajax({

              url:"<?php echo URL::to('user/infile_download_bpso_all_image'); ?>",

              type:"get",

              data:{type:"b",id:id},

              success: function(result) {

                  SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

                  setTimeout(function() {

                    $.ajax({

                      url:"<?php echo URL::to('user/delete_file_link'); ?>",

                      type:"post",

                      data:{result:result},

                      success: function(result)

                      {

                        $("body").removeClass("loading");

                      }

                    });

                  },3000);

              }

          });
      }
      else{
        alert("None of the checkbox is checked to download the files");
      }
  }
  if($(e.target).hasClass('download_p_all_image')){
      var lenval = $(e.target).parents("table").find(".p_check:checked").length;
      if(lenval > 0)
      {
        $(".download_option_p_modal").modal("show");
        $("#p_files_only").prop("checked",true);
        var file_id = $(e.target).attr("data-element");
        $("#hidden_p_all_download").val(file_id);
      }
      else{
        alert("None of the checkbox is checked to download the files");
      }
  }
  if($(e.target).hasClass('download_s_all_image')){
      var lenval = $(e.target).parents("table").find(".s_check:checked").length;
      if(lenval > 0)
      {
        $(".download_option_s_modal").modal("show");
        $("#s_files_only").prop("checked",true);
        var file_id = $(e.target).attr("data-element");
        $("#hidden_s_all_download").val(file_id);
      }
      else{
        alert("None of the checkbox is checked to download the files");
      }
  }
  if(e.target.id == "download_p_all_button")
  {
  	var value = $(".download_p_files:checked").val();
  	if(value == "1")
  	{
  		$("body").addClass("loading");
        var id = $("#hidden_p_all_download").val();
        $.ajax({
            url:"<?php echo URL::to('user/infile_download_bpso_all_image'); ?>",
            type:"get",
            data:{type:"p",id:id},
            success: function(result) {
            	$(".download_option_p_modal").modal("hide");
                SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
                setTimeout(function() {
                  $.ajax({
                    url:"<?php echo URL::to('user/delete_file_link'); ?>",
                    type:"post",
                    data:{result:result},
                    success: function(result)
                    {

                      $("body").removeClass("loading");
                    }
                  });
                },3000);
            }

        });
  	}
  	else if(value == "2")
  	{
  		$("body").addClass("loading");
        var id = $("#hidden_p_all_download").val();
        $.ajax({
            url:"<?php echo URL::to('user/infile_download_bpso_all_image_csv'); ?>",
            type:"get",
            data:{type:"p",id:id},
            success: function(result) {
            	$(".download_option_p_modal").modal("hide");
                SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
                setTimeout(function() {
                  $.ajax({
                    url:"<?php echo URL::to('user/delete_file_link'); ?>",
                    type:"post",
                    data:{result:result},
                    success: function(result)
                    {

                      $("body").removeClass("loading");
                    }
                  });
                },3000);
            }

        });
  	}
  	else{
  		$("body").addClass("loading");
        var id = $("#hidden_p_all_download").val();
        $.ajax({
            url:"<?php echo URL::to('user/infile_download_bpso_all_image_both'); ?>",
            type:"get",
            data:{type:"p",id:id},
            success: function(result) {
            	$(".download_option_p_modal").modal("hide");
                SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
                setTimeout(function() {
                  $.ajax({
                    url:"<?php echo URL::to('user/delete_file_link'); ?>",
                    type:"post",
                    data:{result:result},
                    success: function(result)
                    {

                      $("body").removeClass("loading");
                    }
                  });
                },3000);
            }

        });
  	}
  }
  if(e.target.id == "download_s_all_button")
  {
  	var value = $(".download_s_files:checked").val();
  	if(value == "1")
  	{
  		$("body").addClass("loading");
        var id = $("#hidden_s_all_download").val();
        $.ajax({
            url:"<?php echo URL::to('user/infile_download_bpso_all_image'); ?>",
            type:"get",
            data:{type:"s",id:id},
            success: function(result) {
            	$(".download_option_s_modal").modal("hide");
                SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
                setTimeout(function() {
                  $.ajax({
                    url:"<?php echo URL::to('user/delete_file_link'); ?>",
                    type:"post",
                    data:{result:result},
                    success: function(result)
                    {

                      $("body").removeClass("loading");
                    }
                  });
                },3000);
            }

        });
  	}
  	else if(value == "2")
  	{
  		$("body").addClass("loading");
        var id = $("#hidden_s_all_download").val();
        $.ajax({
            url:"<?php echo URL::to('user/infile_download_bpso_all_image_csv'); ?>",
            type:"get",
            data:{type:"s",id:id},
            success: function(result) {
            	$(".download_option_s_modal").modal("hide");
                SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
                setTimeout(function() {
                  $.ajax({
                    url:"<?php echo URL::to('user/delete_file_link'); ?>",
                    type:"post",
                    data:{result:result},
                    success: function(result)
                    {

                      $("body").removeClass("loading");
                    }
                  });
                },3000);
            }

        });
  	}
  	else{
  		$("body").addClass("loading");
        var id = $("#hidden_s_all_download").val();
        $.ajax({
            url:"<?php echo URL::to('user/infile_download_bpso_all_image_both'); ?>",
            type:"get",
            data:{type:"s",id:id},
            success: function(result) {
            	$(".download_option_s_modal").modal("hide");
                SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
                setTimeout(function() {
                  $.ajax({
                    url:"<?php echo URL::to('user/delete_file_link'); ?>",
                    type:"post",
                    data:{result:result},
                    success: function(result)
                    {

                      $("body").removeClass("loading");
                    }
                  });
                },3000);
            }

        });
  	}
  }
  if($(e.target).hasClass('download_o_all_image')){
      var lenval = $(e.target).parents("table").find(".o_check:checked").length;
      if(lenval > 0)
      {
        $("body").addClass("loading");

        var id = $(e.target).attr('data-element');

        $.ajax({

            url:"<?php echo URL::to('user/infile_download_bpso_all_image'); ?>",

            type:"get",

            data:{type:"o",id:id},

            success: function(result) {

                SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

                setTimeout(function() {

                  $.ajax({

                    url:"<?php echo URL::to('user/delete_file_link'); ?>",

                    type:"post",

                    data:{result:result},

                    success: function(result)

                    {

                      $("body").removeClass("loading");

                    }

                  });

                },3000);

            }

        });
      }
      else{
        alert("None of the checkbox is checked to download the files");
      }
  }

  if($(e.target).hasClass('delete_all_notes_only')){



    var r = confirm("Are You sure you want to delete all the attachments?");

    if (r == true) {

      var id = $(e.target).attr('data-element');

      $.ajax({

          url:"<?php echo URL::to('user/infile_delete_all_notes_only'); ?>",

          type:"get",

          data:{id:id},

          success: function(result) {

            window.location.reload();

          }

      });

    }

  }



  if($(e.target).hasClass('download_all_notes_only')){

    $("body").addClass("loading");

      var id = $(e.target).attr('data-element');

      $.ajax({

          url:"<?php echo URL::to('user/infile_download_all_notes_only'); ?>",

          type:"get",

          data:{id:id},

          success: function(result) {

            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

              

              setTimeout(function() {

                $.ajax({

                  url:"<?php echo URL::to('user/delete_file_link'); ?>",

                  type:"post",

                  data:{result:result},

                  success: function(result)

                  {

                    $("body").removeClass("loading");

                  }

                });

              },3000);

          }

      });

  }  
if($(e.target).hasClass('bpso_all_check')){
  $("body").addClass("loading");
  var id = $(e.target).attr('id');
  var type = $(e.target).attr('data-element');
  $.ajax({
        url: "<?php echo URL::to('user/bpso_all_check') ?>",
        type:"post",        
        data:{id:id, type:type},   
        success:function(result){
          $(e.target).parents(".infile_inner_table_row").html(result);
          $("body").removeClass("loading");
          
          $('[data-toggle="tooltip"]').tooltip();
          $("[data-toggle=popover]").each(function(i, obj) {
            $(this).popover({
              html: true,
              content: function() {
                var id = $(this).attr('id')
                return $('#popover-content-' + id).html();
              }
            });
          });
          add_secondary_function();         
    }
  });

}




  if($(e.target).hasClass('delete_all_notes')){



    var r = confirm("Are You sure you want to delete all the attachments?");

    if (r == true) {

      var id = $(e.target).attr('data-element');

      $.ajax({

          url:"<?php echo URL::to('user/infile_delete_all_notes'); ?>",

          type:"get",

          data:{id:id},

          success: function(result) {

            window.location.reload();

          }

      });

    }

  }

  if($(e.target).hasClass('download_all_notes')){

    $("body").addClass("loading");

      var id = $(e.target).attr('data-element');

      $.ajax({

          url:"<?php echo URL::to('user/infile_download_all_notes'); ?>",

          type:"get",

          data:{id:id},

          success: function(result) {

            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);

              

              setTimeout(function() {

                $.ajax({

                  url:"<?php echo URL::to('user/delete_file_link'); ?>",

                  type:"post",

                  data:{result:result},

                  success: function(result)

                  {

                    $("body").removeClass("loading");

                  }

                });

              },3000);

          }

      });

  }



  if($(e.target).hasClass('fa-pencil-square')){
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left);
    var topposi = parseInt(pos.top) + 500;
    var id = $(e.target).parents("tr").attr("data-element");
    $(".infile_tr_body_last_"+id).find('.notepad_div').css({"position":"absolute","top":topposi,"left":leftposi}).toggle();
  }



  if($(e.target).hasClass('fanotepad_notes')){



    var pos = $(e.target).position();

    var leftposi = parseInt(pos.left) - 200;

    $(e.target).parent().find('.notepad_div_notes').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();



  }



   if($(e.target).hasClass('fanotepadadd')){

    var clientid = $("#client_search").val();

    if(clientid == "")

    {

      alert("Please Choose the client id to create the attachments");

    }

    else{

      var pos = $(e.target).position();

      var leftposi = parseInt(pos.left) - 20;

      $(e.target).parent().find('.notepad_div_notes_add').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();

    }

  }





  if($(e.target).hasClass('internal_checkbox')){

    var id = $(e.target).attr('data-element');

    if($(e.target).is(':checked')){

      $.ajax({

        url:"<?php echo URL::to('user/infile_internal'); ?>",

        type:"get",

        data:{internal:1,id:id},

        success: function(result) {

          //$(e.target).parents('tr').find('.task_label').css({'color':'#89ff00','font-weight':'800'});

        }

      });

    }

    else{

      $.ajax({

        url:"<?php echo URL::to('user/infile_internal'); ?>",

        type:"get",

        data:{internal:0,id:id},

        success: function(result) {

          //$(e.target).parents('tr').find('.task_label').css({'color':'#fff','font-weight':'600'});

        }



      });

    }

  }

  if($(e.target).hasClass('reportclassdiv'))
  {
    $(".report_div").show();
  }
  else if($(e.target).hasClass('report_div'))
  {
    $(".report_div").show();
  }
  else if($(e.target).parents(".report_div").length > 0)
  {
    $(".report_div").show();
  }
  else{
      $(".report_div").hide();
  }
  if($(e.target).hasClass('ok_button'))
  {
    var check_option = $(".class_invoice:checked").val();
    $("#show_incomplete_report").prop("checked", true);
    $(".report_show_incomplete").val(1);

    if(check_option === "" || typeof check_option === "undefined")
    {
      alert("Please select atleast one report type to move forward.");
    }
    else{
      $(".report_type").val(1);
      var id = $('input[name="report_infile"]:checked').val();
      $(".class_invoice").prop("checked", false);
      if(id == 1){
          $("#report_tbody").html('');
          $("body").addClass("loading");
          $.ajax({
              url: "<?php echo URL::to('user/report_infile') ?>",
              data:{id:0},
              type:"post",
              success:function(result){
                 $(".report_infile_model").modal("show");                 
                 $(".report_div").hide();
                 $("body").removeClass("loading");
                 $("#report_tbody").html(result);
                 $(".select_all").hide();
                 $(".single_client_button").show();
                 $(".all_client_button").hide();                 
          }
        });
      }
      else{
        $(".report_type").val(2);
        $("#report_tbody").html('');
        $("body").addClass("loading");
          $.ajax({
              url: "<?php echo URL::to('user/report_infile') ?>",
              data:{id:1},
              type:"post",
              success:function(result){  
                $(".report_infile_model").modal("show");
                $(".report_div").hide();
                $("body").removeClass("loading");      
                $("#report_tbody").html(result);
                $(".select_all").show(); 
                $(".single_client_button").hide();
                $(".all_client_button").show();
          }
        });
      }
    }
  }


  if(e.target.id == "select_all_class") {
    if($(e.target).is(":checked")){
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


  if(e.target.id == "save_as_pdf")
  {
    $("#report_pdf_type_two_tbody").html('');
    var status = $(".report_show_incomplete").val();
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
                url:"<?php echo URL::to('user/infile_report_pdf'); ?>",
                type:"post",
                data:{value:imp, status:status},
                success: function(result)
                {
                  $("#report_pdf_type_two_tbody").append(result);
                  
                  var last = index + parseInt(1);
                  if(arrayval.length == last)
                  {
                    var pdf_html = $("#report_pdf_type_two").html();
                    $.ajax({
                      url:"<?php echo URL::to('user/download_infile_report_pdf'); ?>",
                      type:"post",
                      data:{htmlval:pdf_html},
                      success: function(result)
                      {
                        SaveToDisk("<?php echo URL::to('infile_report'); ?>/"+result,result);
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
      alert("Please Choose atleast one client to continue.");
    }
  }
  if(e.target.id == "save_as_csv")
  {
    $("body").addClass("loading");
    var status = $(".report_show_incomplete").val();
    if($(".select_client:checked").length)
    {
      var checkedvalue = '';
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
      $.ajax({
        url:"<?php echo URL::to('user/infile_report_csv'); ?>",
        type:"post",
        data:{value:checkedvalue, status:status},
        success: function(result)
        {
          SaveToDisk("<?php echo URL::to('infile_report'); ?>/Infile_Report.csv",'Infile_Report.csv');
        }
      });
    }
    else{
      $("body").removeClass("loading");
      alert("Please Choose atleast one client to continue.");
    }
  }

  if(e.target.id == "single_save_as_csv")
  {
    $("body").addClass("loading");
    var status = $(".report_show_incomplete").val();
    if($(".select_client:checked").length)
    {
      var checkedvalue = '';
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
      $.ajax({
        url:"<?php echo URL::to('user/infile_report_csv_single'); ?>",
        type:"post",
        data:{value:checkedvalue, status:status},
        success: function(result)
        {
          SaveToDisk("<?php echo URL::to('infile_report'); ?>/Infile_Report.csv",'Infile_Report.csv');
        }
      });
    }
    else{
      $("body").removeClass("loading");
      alert("Please Choose atleast one client to continue.");
    }
  }


  if(e.target.id == "single_save_as_pdf")
  {
    $("#report_pdf_type_two_tbody_single").html('');
    var status = $(".report_show_incomplete").val();
    //console.log(status);
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
                url:"<?php echo URL::to('user/infile_report_pdf_single'); ?>",
                type:"post",
                data:{value:imp, status:status},
                success: function(result)
                {
                  $("#report_pdf_type_two_tbody_single").append(result);
                  
                  var last = index + parseInt(1);
                  if(arrayval.length == last)
                  {
                    var pdf_html = $("#report_pdf_type_two_single").html();
                    $.ajax({
                      url:"<?php echo URL::to('user/download_infile_report_pdf_single'); ?>",
                      type:"post",
                      data:{htmlval:pdf_html},
                      success: function(result)
                      {
                        SaveToDisk("<?php echo URL::to('infile_report'); ?>/"+result,result);
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
      alert("Please Choose atleast one client to continue.");
    }
  }

  if(e.target.id == 'show_incomplete_report'){
    var type = $(".report_type").val();
    $("body").addClass("loading");
    if($(e.target).is(':checked'))
    {
      $.ajax({
        url:"<?php echo URL::to('user/infile_report_incomplete'); ?>",
        type:"post",
        data:{id:0, type:type},
        success: function(result)
        {
          $("#report_tbody").html(result);
          $(".report_show_incomplete").val(1);
          $("body").removeClass("loading");
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/infile_report_incomplete'); ?>",
        type:"post",
        data:{id:1, type:type},
        success: function(result)
        {
          $("#report_tbody").html(result);
          $(".report_show_incomplete").val(2);
          $("body").removeClass("loading");
        }
      });
    }
  }
});
$(function () {
  $(".complete_date").on("dp.hide", function (e) {
    var file_id = $(this).attr("data-element");
    var dateval = $(this).val();
    $.ajax({
    	url:"<?php echo URL::to('user/infile_complete_date'); ?>",
    	type:"get",
    	data:{id:file_id,dateval:dateval},
    	success: function(result)
    	{

    	}
    })
  });
  $(".imported_date").on("dp.hide", function (e) {
    var file_id = $(this).attr("data-element");
    var dateval = $(this).val();
    $.ajax({
      url:"<?php echo URL::to('user/save_imported_date'); ?>",
      type:"post",
      data:{file_id:file_id,date:dateval},
      success: function(result)
      {

      }
    })
  });
  $(".date_attachment").on("dp.hide", function (e) {
    var attachment_id = $(this).attr("data-element");
    var file_id = $(this).attr("data-file");
    if($(".infile_tr_body_"+file_id).find(".show_previous").hasClass('disabled_prev_btn'))
    {
      var dateval = $(this).val();
      $.ajax({
      	url:"<?php echo URL::to('user/infile_attachment_date_filled'); ?>",
      	type:"post",
      	data:{id:attachment_id,dateval:dateval},
      	success: function(result)
      	{
          $(this).attr("data-value",dateval);
      	}
      })
    }
  });

});

$(document).ready(function() {

if($("#show_incomplete").is(':checked'))

                {

  $(".user_select").each(function() {

      if($(this).hasClass('disable_class'))

      {

        var fileid = $(this).parents('tr').attr("data-element");
          $(".infile_tr_body_"+fileid).hide();

      }

  });

}

else{

  $(".user_select").each(function() {

      if($(this).hasClass('disable_class'))

      {

        var fileid = $(this).parents('tr').attr("data-element");
          $(".infile_tr_body_"+fileid).show();

      }

  });

}



 $('[data-toggle="tooltip"]').tooltip(); 
  $(".client_search_class").autocomplete({
      source: function(request, response) {
          $.ajax({
              url:"<?php echo URL::to('user/task_client_search'); ?>",
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

          url:"<?php echo URL::to('user/task_client_search_select'); ?>",

          data:{value:ui.item.id},

          success: function(result){         

            $("#client_search").val(ui.item.id);     

            $(".create_new_class").show();

          }

        })

      }

  });

  $(".client_common_search").autocomplete({
      source: function(request, response) {        
          $.ajax({
              url:"<?php echo URL::to('user/task_client_common_search'); ?>",
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
        $(".client_search_common").val(ui.item.id); 
      }
  });
});





</script>



<script>

fileList = new Array();

Dropzone.options.imageUpload = {

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

              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"' data-task='"+obj.task_id+"'>Remove</a></p>";

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

            //console.log(file);

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

Dropzone.options.imageUpload1 = {

    maxFiles: 2000,

    acceptedFiles: null,

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
            $(".accepted_files_main").hide();

            $(".accepted_files").html(0);

        });

        this.on("drop", function(event) {

            $("body").addClass("loading");        

        });

        this.on("success", function(file, response) {

            var obj = jQuery.parseJSON(response);

            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response

            if(obj.id != 0)

            {

              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach' data-element='"+obj.id+"' data-task='"+obj.task_id+"'>Remove</a></p>";

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
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            $(".accepted_files_main").show();

            $(".accepted_files").html(acceptedcount);
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");

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

            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);

        });

    },

};
Dropzone.options.imageUpload5 = {
    maxFiles: 2000,
    acceptedFiles: null,
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
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach_task' data-task='"+obj.task_id+"'>Remove</a></p>";
            }
            else{
              $("#attachments_text").show();
              $("#add_attachments_div_task").append("<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach_task' data-task='"+obj.file_id+"'>Remove</a></p>");
              $(".img_div").each(function() {
                $(this).hide();
              });
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
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
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};

Dropzone.options.imageUploadprogress = {
    maxFiles: 2000,
    acceptedFiles: null,
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
            $("#add_files_attachments_progress_div_"+obj.task_id).append("<p><a href='"+obj.download_url+"' class='file_attachments' download>"+obj.filename+"</a> <a href='"+obj.delete_url+"' class='fa fa-trash delete_attachments'></a></p>");
            $(".dropzone_progress_modal").modal("hide");
            $(".dropzone_completion_modal").modal("hide");

            $(".add_progress_attachments").append('<p class="pending_attachment" data-element="'+obj.task_id+'" data-user="'+obj.from_user+'">'+obj.message+'</p>')
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
            $(".progress_spam").html("Progress files added Successfully");
            Dropzone.forElement("#imageUploadprogress").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");

            if($(".task_specifics_modal").hasClass('in')){

            }
            else{
              var obj = {};
              obj.message = []; 
              obj.task_id = []; 
              obj.user_id = []; 
              $(".add_progress_attachments").find('p').each(function(index,value) {
                var message = $(this).html();
                var task_id = $(this).attr("data-element");
                var user_id = $(this).attr("data-user");

                obj.message.push([message]);
                obj.task_id.push([task_id]);
                obj.user_id.push([user_id]);
              });

              var messages = JSON.stringify(obj);

              $.ajax({
                url:"<?php echo URL::to('user/save_attachments_messages'); ?>",
                type:"post",
                data:{messages:messages},
                success:function(result){
                  $(".add_progress_attachments").html("");
                }
              });
            }
          }
        });
        this.on("error", function (file) {
          //$(".add_progress_attachments").html("");
          $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
          //$(".add_progress_attachments").html("");
            $("body").removeClass("loading");
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            //$.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};

$.ajaxSetup({async:false});

$( "#create_task_form" ).validate({

    rules: {
        select_user : {required: true},
        created_date : { required: true},   
        client_name : { required: true},   
        due_date : { required: true},   
    },
    messages: {
        select_user : {
          required : "Please select the Author",
        },
        created_date : {
            required : "Creation Date is required",
        },
        client_name : {
            required : "Client Name is required",
        },
        due_date : {
            required : "Due Date is required",
        },
    },
});



$(function(){
    $('#in_file').DataTable({
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
    $('#in_file').css('white-space','word-wrap');
});
</script>



@stop