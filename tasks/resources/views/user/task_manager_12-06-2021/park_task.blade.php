@extends('userheader')
@section('content')

<script src="<?php echo URL::to('assets/ckeditor/src/js/main1.js'); ?>"></script>
<script src='<?php echo URL::to('assets/js/table-fixed-header_cm.js'); ?>'></script>
<style>
.tasks_drop {text-align: left !important; }
.existing_comments > p { margin-bottom: 0px !important; }
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.fa-sort{ cursor:pointer; }
.error{
  color:#f00;
}
/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
.file_attachments{
	color: #002bff;
    text-decoration: underline;
    font-weight: 700;
}
.link_infile{
	color: #002bff;
  font-weight: 700;
}
.link_infile_p{
  color: #002bff;
  text-decoration: underline;
}
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    999999999999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
body.loading {
    overflow: hidden;   
}
body.loading .modal_load {
    display: block;
}
.disclose_label{ width:300px; }
.option_label{width:100%;}
table{
      border-collapse: separate !important;
}
.fa-plus,.fa-pencil-square{
  cursor:pointer;
}
.table_bg>tbody>tr>td, .table_bg>tbody>tr>th, .table_bg>tfoot>tr>td, .table_bg>tfoot>tr>th, .table_bg>thead>tr>td
{
  border-top: 0px solid;
  color:#000 !important;
  text-align: left;
  font-weight:600;
  padding: 6px 10px;
  background: #dcdcdc;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td
{
  border-top: 0px solid;
  color:#000 !important;
  text-align: left;
  font-weight:600;
  padding: 6px 10px;
  background: #dcdcdc;
  font-size:15px;
}
.ui-widget{z-index: 999999999}
.ui-widget .ui-menu-item-wrapper{font-size: 14px; font-weight: bold;}
.ui-widget .ui-menu-item-wrapper:hover{font-size: 14px; font-weight: bold}
.file_attachment_div{width:100%;}
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
.delete_all_image, .delete_all_notes_only, .delete_all_notes, .download_all_image, .download_rename_all_image, .download_all_notes_only, .download_all_notes{cursor: pointer;}
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

.notepad_div_progress_notes,.notepad_div_completion_notes {
    border: 1px solid #000;
    width: 400px;
    position: absolute;
    height: 250px;
    background: #dfdfdf;
    display: none;
}
.notepad_div_progress_notes textarea, .notepad_div_completion_notes textarea{
  height:212px;
}
.img_div_add{
    border: 1px solid #000;
    width: 280px;
    position: absolute !important;
    min-height: 118px;
    background: rgb(255, 255, 0);
    display:none;
}
.notepad_div_notes_add {
    border: 1px solid #000;
    width: 280px;
    position: absolute;
    height: 250px;
    background: #dfdfdf;
    display: none;
}
.notepad_div_notes_add textarea{
  height:212px;
}
.edit_allocate_user{
  cursor: pointer;
  font-weight:600;
}
.disabled_icon{
  cursor:no-drop;
}
.disabled{
  pointer-events: none;
}
.disable_user{
  pointer-events: none;
  background: #c7c7c7;
}
.mark_as_incomplete{
  background: green;
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
.avoid_email{ color:green; font-size:18px;}
.retain_email { color: #f00 !important; }
.hidden_tasks_tr>td{
	cursor:pointer;
}
#colorbox, #cboxWrapper { z-index:99999999999; }
</style>
<script src="<?php echo URL::to('ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('ckeditor/samples/js/sample.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>">
<link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> ">
<?php 
  $admin_details = Db::table('admin')->first();
  $admin_cc = $admin_details->task_cc_email;
?>
<div class="modal fade change_taskname_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Edit Task Name</h4>
          </div>
          <div class="modal-body" style="min-height: 95px;">  
            <div class="row">
              <div class="col-md-12 internal_tasks_group">
                <label style="margin-top:5px">Select Task:</label>
              </div>
              <div class="col-md-12 internal_tasks_group_change">
                <div class="dropdown" style="width: 100%">
                  <a class="btn btn-default dropdown-toggle tasks_drop_change" data-target="#" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="width: 100%">
                    <span class="task-choose_internal_change">Select Task</span>  <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu internal_task_details_change" role="menu"  aria-labelledby="dropdownMenu" style="width: 100%">
                    <li><a tabindex="-1" href="javascript:" class="tasks_li_internal_change">Select Task</a></li>
                      <?php
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
                        <li><a tabindex="-1" href="javascript:" class="tasks_li_internal_change" data-element="<?php echo $single_task->id?>"><?php echo $icon.$single_task->task_name?></a></li>
                      <?php
                        }
                      }
                      ?>
                  </ul>
                  <input type="hidden" name="idtask_change" id="idtask_change" value="">
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">  
            <input type="hidden" class="hidden_task_id_change_task" name="hidden_task_id_change_task" value="">
            <input type="button" class="common_black_button" id="change_taskname_button" value="Submit">
          </div>
        </div>
  </div>
</div> 
<div class="modal fade question_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Alert</h4>
          </div>
          <div class="modal-body" style="min-height: 200px;">  
            <div class="col-md-12">
              <label>Do you want to carry over ALL of the task specifics from the original task or just the Original Task Specifics. Select Yes for the all the task specifics or Select No for just the Original Task Specifics. </label>
            </div>
            <div class="col-md-12">
              <input type="radio" name="copy_task_specifics" class="copy_task_specifics" id="copy_task_specifics_yes" value="1"><label for="copy_task_specifics_yes">Yes</label>
              <input type="radio" name="copy_task_specifics" class="copy_task_specifics" id="copy_task_specifics_no" value="2" checked><label for="copy_task_specifics_no">No</label>
            </div>
            <div class="col-md-12">
              <label>Do you want attach some or all of the files to this Task? </label>
            </div>
            <div class="col-md-12">
              <input type="radio" name="copy_task_files" class="copy_task_files" id="copy_task_files_yes" value="1"><label for="copy_task_files_yes">Yes</label>
              <input type="radio" name="copy_task_files" class="copy_task_files" id="copy_task_files_no" value="2" checked><label for="copy_task_files_no">No</label>
            </div>

            <div class="hide_taskmanager_files">
            </div>
          </div>
          <div class="modal-footer">  
            <input type="hidden" class="hidden_task_id_copy_task" name="hidden_task_id_copy_task" value="">
            <input type="button" class="common_black_button" id="question_submit" value="Submit">
          </div>
        </div>
  </div>
</div>
<div class="modal fade infiles_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:45%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Link Infiles</h4>
          </div>
          <div class="modal-body" id="infiles_body">  

          </div>
          <div class="modal-footer">  
            <input type="button" class="common_black_button" id="link_infile_button" value="Submit">
          </div>
        </div>
  </div>
</div>

<div class="modal fade due_date_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="font-weight:700;font-size:20px">Edit Due Date</h4>
          </div>
          <div class="modal-body" style="min-height: 193px;">  
            <p class="col-md-12">You are changing the due date of the task</p>
            <label class="col-md-4">Subject:</label>
            <label class="col-md-8 subject_due_date"></label>
            <br/>
            <label class="col-md-4">Current Due Date:</label>
            <div class="col-md-8">
              <input type="text" name="current_due_date" class="form-control current_due_date" value="" readonly style="font-weight:700">
            </div>
            <br/>
            <br/>
            <label class="col-md-4" style="margin-top:15px">New Due Date:</label>
            <div class="col-md-8" style="margin-top:15px">
              <input type="text" name="new_due_date" class="form-control new_due_date due_date_edit" value="20-Mar-20">
            </div>
            <p class="col-md-12" style="color:#f00">WARNING:  The Author of the task will be notified of the change.</p>
          </div>
          <div class="modal-footer">  
            <input type="hidden" name="hidden_task_id_due_date" id="hidden_task_id_due_date" value="">
            <input type="button" class="common_black_button" id="due_date_change_button" value="Apply New Due Date">
          </div>
        </div>
  </div>
</div>
<div class="modal fade allocation_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:35%;">
        <div class="modal-content">
          <div class="modal-header allocation_body">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="font-weight:700;font-size:20px">Task Allocation</h4>
          </div>
          <div class="modal-body allocation_body" style="min-height: 195px;">
            <label class="col-md-3" style="padding:0px">Task Subject:</label>
            <label class="col-md-9 subject_allocation"></label>
            <br/>
            <label class="col-md-3" style="margin-top:15px;padding:0px">Current Allocation:</label>
            <div class="col-md-9" style="margin-top:15px">
              <select name="current_allocation" class="form-control current_allocation" disabled>
                <option value="">Select User</option>        
                  <?php
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
            <br/>
            <br/>
            <label class="col-md-3" style="margin-top:15px;padding:0px">Allocate this task to:</label>
            <div class="col-md-9" style="margin-top:15px">
              <select name="new_allocation" class="form-control new_allocation">
                <option value="">Select User</option>        
                  <?php
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
          </div>
          <div class="modal-footer allocation_body">  
            <input type="hidden" name="hidden_task_id_allocation" id="hidden_task_id_allocation" value="">
            <input type="hidden" name="hidden_task_id_auto_close" id="hidden_task_id_auto_close" value="">
            <input type="button" class="common_black_button" id="allocate_now" value="Allocate Now">
          </div>
          <div class="modal-header history_body">
          	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="text-align: center">Task Allocation History</h4>
          </div>
          <div class="modal-body history_body" id="history_body">
          </div>
          <div class="modal-footer history_body">  
            <input type="hidden" name="hidden_task_id_history" id="hidden_task_id_history" value="">
            <input type="button" class="common_black_button export_csv_history" id="export_csv_history" value="Export CSV">
            <input type="button" class="common_black_button export_pdf_history" id="export_pdf_history" value="Export PDF">
          </div>
        </div>
  </div>
</div>

<div class="modal fade task_specifics_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:40%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" style="font-weight:700;font-size:20px">Task Specifics</h4>
            <h5 class="title_task_details" style="font-size:18px;font-weight:600"></h5>
          </div>
          <div class="modal-body" style="min-height: 193px;padding: 5px;">
            <label class="col-md-12" style="padding: 0px;">
              <label style="margin-top:10px">Existing Task Specific Comments:</label>
              <a href="javascript:" class="common_black_button download_pdf_spec" style="float: right;">Download as PDF</a> 
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
              

              <div class="col-md-12" style="float:right;margin-top:10px;padding:0px">
                  <input type='checkbox' name="auto_close_task_comment" class="auto_close_task_comment" id="auto_close_task_comment" value="1"/> <label for="auto_close_task_comment" style="margin-top: 10px;">Make this task is an Auto Close Task</label>
              </div>
            </div>
          </div>
        </div>
  </div>
</div>

<div class="modal fade infiles_progress_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:45%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Link Infiles</h4>
          </div>
          <div class="modal-body" id="infiles_progress_body">  

          </div>
          <div class="modal-footer">
            <input type="hidden" name="hidden_progress_infiles_task_id" id="hidden_progress_infiles_task_id" class="hidden_progress_infiles_task_id" value="">
            <input type="button" class="common_black_button" id="link_infile_progress_button" value="Submit">
          </div>
        </div>
  </div>
</div>

<div class="modal fade infiles_completion_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:45%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Link Infiles</h4>
          </div>
          <div class="modal-body" id="infiles_completion_body">  

          </div>
          <div class="modal-footer">  
            <input type="hidden" name="hidden_completion_infiles_task_id" id="hidden_completion_infiles_task_id" class="hidden_completion_infiles_task_id" value="">
            <input type="button" class="common_black_button" id="link_infile_completion_button" value="Submit">
          </div>
        </div>
  </div>
</div>
<div class="modal fade yearend_completion_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:45%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Link Infiles</h4>
          </div>
          <div class="modal-body" id="yearend_completion_body">  

          </div>
          <div class="modal-footer">  
            <input type="hidden" name="hidden_completion_yearend_task_id" id="hidden_completion_yearend_task_id" class="hidden_completion_infiles_task_id" value="">
            <input type="button" class="common_black_button" id="link_yearend_completion_button" value="Submit">
          </div>
        </div>
  </div>
</div>
<div class="modal fade create_new_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;overflow-y: scroll">
  <div class="modal-dialog modal-sm" role="document" style="width:45%">
    <form action="<?php echo URL::to('user/create_new_taskmanager_task')?>" method="post" class="add_new_form" id="create_job_form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">New Task Creator</h4>
          </div>
          <div class="modal-body">            
            <div class="row"> 
                <div class="col-md-3">
                	<label style="margin-top:5px">Author:</label>
                </div>
                <div class="col-md-9">
                	<select name="select_user" class="form-control select_user_author" required>
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
            </div>
            <div class="row" style="margin-top:10px">
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
            <div class="row" style="margin-top:7px">
            	<div class="col-md-3">
                	<label style="margin-top:5px">Allocate To:</label>
                </div>
                <div class="col-md-7">
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
                <div class="col-md-2" style="padding:0px">
                  <div style="margin-top:5px">
                  	<input type='checkbox' name="open_task" id="open_task" value="1"/>
                  	<label for="open_task">OpenTask</label>
                  </div>
                </div>
            </div>
            <div class="row" style="margin-top:14px">
            	<div class="col-md-3 client_group">
                	<label style="margin-top:5px">Client:</label>
                </div>
                <div class="col-md-7 client_group">
                	<input  type="text" class="form-control client_search_class" name="client_name" placeholder="Enter Client Name / Client ID" required>
                	<input type="hidden" id="client_search" name="clientid" />
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
		                      <li><a tabindex="-1" href="javascript:" class="tasks_li_internal" data-element="<?php echo $single_task->id?>"><?php echo $icon.$single_task->task_name?></a></li>
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
                	  <input type='checkbox' name="internal_checkbox" id="internal_checkbox" value="1"/>
                  	<label for="internal_checkbox">Internal</label>
                  </div>
                </div>
            </div>
            <div class="form-group start_group" style="margin-top:10px">
                <div class="form-title"><label style="margin-top:5px">Subject:</label></div>
                <input  type="text" class="form-control subject_class" name="subject_class" placeholder="Enter Subject">
            </div>
            <div class="form-group start_group task_specifics_add">
                <div class="form-title"><label style="margin-top:5px">Task Specifics:</label></div>
                <textarea class="form-control task_specifics" id="editor_2" name="task_specifics" placeholder="Enter Task Specifics" style="height:400px"></textarea>
            </div>
            <div class="form-group start_group task_specifics_copy">
                <div class="form-title"><label style="margin-top:5px">Task Specifics:</label></div>
                <div class="task_specifics_copy_val" style="width:100%;height:400px;background: #e2e2e2;min-height: 400px;overflow-y: scroll;padding: 7px;"></div>
                
                <input type="hidden" name="hidden_task_specifics" id="hidden_task_specifics" value="">
            </div>
            <div class="form-group date_group">
                <div class="col-md-2" style="padding:0px">
                	<label style="margin-top:5px">DueDate:</label>
                </div>
                <div class="col-md-10">
                	<label class="input-group datepicker-only-init_date_received">
	                    <input type="text" class="form-control due_date" placeholder="Select Due Date" name="due_date" style="font-weight: 500;" required />
	                    <span class="input-group-addon">
	                        <i class="glyphicon glyphicon-calendar"></i>
	                    </span>
	                </label>
                </div>
            </div>
            <div class="form-group start_group retreived_files_div">

            </div>
            <div class="form-group start_group">
              <label>Task Files: </label>
              <a href="javascript:" class="fa fa-plus fa-plus-add" style="margin-top:10px;" aria-hidden="true" title="Add Attachment"></a> 
              <a href="javascript:" class="fa fa-pencil-square fanotepadadd" style="margin-top:10px; margin-left: 10px;" aria-hidden="true" title="Add Completion Notes"></a>
              <a href="javascript:" class="infiles_link" style="margin-top:10px; margin-left: 10px;">Infiles</a>
              <input type="hidden" name="hidden_infiles_id" id="hidden_infiles_id" value="">
              <div class="img_div img_div_add" style="z-index:9999999; min-height: 275px">
                <form name="image_form" id="image_form" action="" method="post" enctype="multipart/form-data" style="text-align: left;">
                </form>
                <div class="image_div_attachments">
                  <p>You can only upload maximum 300 files at a time. If you drop more than 300 files then the files uploading process will be crashed. </p>
                  <form action="<?php echo URL::to('user/infile_upload_images_taskmanager_add'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload1" style="clear:both;min-height:80px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                      <input name="_token" type="hidden" value="">
                  </form>              
                </div>
               </div>
               <div class="notepad_div_notes_add" style="z-index:9999; position:absolute;display:none">
                  <textarea name="notepad_contents_add" class="form-control notepad_contents_add" placeholder="Enter Contents"></textarea>
                  <input type="button" name="notepad_submit_add" class="btn btn-sm btn-primary notepad_submit_add" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                  <spam class="error_files_notepad_add"></spam>
              </div>
            </div>
            
            <p id="attachments_text" style="display:none; font-weight: bold;">Files Attached:</p>
            <div id="add_attachments_div">
            </div>
            <div id="add_notepad_attachments_div">
            </div>
            <p id="attachments_infiles" style="display:none; font-weight: bold;">Linked Infiles:</p>
            <div id="add_infiles_attachments_div">
            </div>
            <div class="form-group date_group">
                <div class="form-title" style="font-weight:600;margin-left:-10px"><input type='checkbox' name="auto_close_task" class="auto_close_task" id="auto_close_task0" value="1"/> <label for="auto_close_task0">This task is an Auto Close Task</label></div>
            </div>
            <div class="form-group date_group">
                <div class="form-title" style="font-weight:600;margin-left:-10px"><input type='checkbox' name="accept_recurring" class="accept_recurring" id="recurring_checkbox0" value="1" checked/> <label for="recurring_checkbox0">Recurring Task</label></div>
                <div class="accept_recurring_div">
                  <p>This Task is repeated:</p>
                  <div class="form-title">
                    <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox1" value="1" checked/>
                    <label for="recurring_checkbox1">Monthly</label>
                  </div>
                  <div class="form-title">
                    <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox2" value="2"/>
                    <label for="recurring_checkbox2">Weekly</label>
                  </div>
                  <div class="form-title">
                    <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox3" value="3"/>
                    <label for="recurring_checkbox3">Daily</label>
                  </div>
                  <div class="form-title">
                    <input type='radio' name="recurring_checkbox" class="recurring_checkbox" id="recurring_checkbox4" value="4"/>
                    <label for="recurring_checkbox4">Specific Number of Days</label>
                    <input type="number" name="specific_recurring" class="specific_recurring" value="" style="width: 29%;height: 25px;">
                  </div>
                </div>
            </div>
            <div class="form-group date_group">
                <div class="form-title" style="font-weight:600;margin-left:-10px"><input type='checkbox' name="2_bill_task" class="2_bill_task" id="2_bill_task0" value="1"/> <label for="2_bill_task0" style="color:green">This task is a 2Bill Task!</label></div>
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
<div class="modal fade dropzone_progress_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Add Progress Files Attachments</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
              <div class="img_div_progress">
                 <div class="image_div_attachments_progress">
                    <form action="<?php echo URL::to('user/infile_upload_images_taskmanager_progress'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                        <input name="hidden_task_id_progress" id="hidden_task_id_progress" type="hidden" value="">
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

<div class="modal fade dropzone_completion_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Add Completion Files Attachments</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
              <div class="img_div_completion">
                 <div class="image_div_attachments_completion">
                    <form action="<?php echo URL::to('user/infile_upload_images_taskmanager_completion'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload2" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                        <input name="hidden_task_id_completion" id="hidden_task_id_completion" type="hidden" value="">
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
<div class="content_section">
	<div id="fixed-header" style="width:100%;position: fixed;background: #fff;margin-top: -16px;">
	  <div class="page_title" style="z-index:999;margin-top:20px">
	  	<div class="row">
		    <div class="col-md-5 padding_00">
		      <label class="col-md-1" style="text-align: right">User:</label>
		      <div class="col-md-5">
		        <select name="select_user" class="form-control select_user_home">
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
		      </div>
		      <label class="col-md-1"><a href="javascript:" class="fa fa-refresh refresh_task" title="Refresh Tasks for this user" style="padding:10px;background: #dfdfdf"></a></label>
		    </div>
		    <div class="col-md-2 padding_00" style="text-align: center">
		      <label style="margin-left: 1%; text-align:center;font-size:20px">Task Manager</label>
		    </div>
		    <div class="col-md-5 padding_00">
		    	<div class="compressed_layout_div" style="float:right;width:31%;margin-right: 80px;">
			      <label >Compressed Layout:</label>
	              <label class="switch" style="margin-right: 10px; float:right !important">
	                <input type="checkbox" class="compressed_layout" value="1" checked>
	                <span class="slider round"></span>
	              </label>
	              <input type="hidden" id="hidden_compressed_layout" value="1">
          		</div>
		    </div>
		</div>
		<div class="row" style="display:none">
		    <div class="col-md-5 padding_00">
		      <label class="col-md-1" style="text-align: right">View:</label>
          <div class="col-md-5">
            <select name="select_view" class="form-control select_view">
              <option value="1">All Tasks Allocated</option>
              <option value="2">RedLine Tasks Allocated</option>
              <option value="3">Authored by All Tasks</option>
            </select>
          </div>
          <!-- <label class="col-md-1">&nbsp;</label>
		      <div class="col-md-11">
		          <input type="checkbox" name="select_check" class="show_authored_task_only" id="show_authored_task_only"><label for="show_authored_task_only">Show authored tasks only</label>
		      </div> -->
		    </div>
		    <div class="col-md-2 padding_00">
		      &nbsp;
		    </div>
		    <div class="col-md-5 padding_00">
		      <a href="javascript:" class="common_black_button" id="create_new_task" style="width:30%;float:right;margin-right:80px">New task</a>
		    </div>
		</div>
	  </div>
      <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
          <a href="<?php echo URL::to('user/task_manager'); ?>" class="nav-link" id="home-tab">
            <spam id="park_task_count">Your Open Tasks (<spam id="park_task_count_val"><?php echo count($open_task_count); ?></spam>)</spam>
          </a>
        </li>
        <li class="nav-item waves-effect waves-light active" style="width:20%;text-align: center">
          <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">
            <spam id="open_task_count">Park Tasks (<spam id="open_task_count_val"><?php if($park_task_count == 0) { echo 0; } else { echo count($park_task_count); } ?></spam>)</spam>
            <spam id="redline_task_count" style="display:none">Redline Tasks (<spam id="redline_task_count_val">0</spam>)</spam>
            <spam id="authored_task_count" style="display:none">Your Authored Tasks (<spam id="authored_task_count_val"><?php if($authored_task_count == 0) { echo 0; } else { echo count($authored_task_count); } ?></spam>)</spam>
          </a>
        </li>
        <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
          <a href="<?php echo URL::to('user/taskmanager_search'); ?>" class="nav-link" id="profile-tab">Task Search</a>
        </li>
        <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
          <a href="<?php echo URL::to('user/task_administration'); ?>" class="nav-link" id="profile-tab">Task Administration</a>
        </li>
      </ul>
    </div>
      <div class="table-responsive" style="width: 100%; float: left;margin-top:120px;">
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane active in" id="home" role="tabpanel" aria-labelledby="home-tab">
          <div style="width:100%;float:left; margin-top: 20px;">
		  <?php
		  if(Session::has('message')) { ?>
		      <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
		  <?php }
		  if(Session::has('error')) { ?>
		      <p class="alert alert-danger"><?php echo Session::get('error'); ?></p>
		  <?php }
		  ?>
		  </div>
<style type="text/css">
.open_layout_div{width:99%;margin: 0px auto;}
.open_layout_div_change::before{width: 100%; height: 30px; content: ""; position: fixed; background: #fff; margin-top: 300px; z-index: 99}
</style>
		    <div class="open_layout_div">
	          <table class="table_bg table-fixed-header open_layout" style="width:100%;margin: 0px auto;">
	            <tbody id="task_body_open">
	              <?php
	              $layout = '';
	              if(count($user_tasks))
	              {
	                foreach($user_tasks as $keytaskid => $task)
	                {
	                  if($task->status == 1){ $disabled = 'disabled'; $disabled_icon = 'disabled_icon'; }
	                  else{ $disabled = ''; $disabled_icon = ''; }

	                  $taskfiles = DB::table('taskmanager_files')->where('task_id',$task->id)->get();
	                  $tasknotepad = DB::table('taskmanager_notepad')->where('task_id',$task->id)->get();
	                  $taskinfiles = DB::table('taskmanager_infiles')->where('task_id',$task->id)->get();
                    $taskyearend = DB::table('taskmanager_yearend')->where('task_id',$task->id)->get();
                    $two_bill_icon = '';
                    if($task->two_bill == "1")
                    {
                      $two_bill_icon = '<img src="'.URL::to('assets/2bill.png').'" style="width:32px;margin-left:10px" title="this is a 2Bill Task">';
                    }
	                  if($task->client_id == "")
	                  {
	                    $title_lable = 'Task Name:';
	                    $task_details = DB::table('time_task')->where('id', $task->task_type)->first();
	                    if(count($task_details))
	                    {
	                      $title = '<spam class="task_name_'.$task->id.'">'.$task_details->task_name.'</spam>'.$two_bill_icon;
	                    }
	                    else{
	                      $title = '<spam class="task_name_'.$task->id.'"></spam>'.$two_bill_icon;
	                    }
	                  }
	                  else{
	                    $title_lable = 'Client:';
	                    $client_details = DB::table('cm_clients')->where('client_id', $task->client_id)->first();
	                    if(count($client_details))
	                    {
	                      $title = $client_details->company.' ('.$task->client_id.')'.$two_bill_icon;
	                    }
	                    else{
	                      $title = ''.$two_bill_icon;
	                    }
	                  }
	                  $author = DB::table('user')->where('user_id',$task->author)->first();
	                  
	                  $task_specifics_val = strip_tags($task->task_specifics);

	                  if($task->subject == "") { $subject = substr($task_specifics_val,0,30); }
	                  else{ $subject = $task->subject; }

	                  if($task->allocated_to == 0) { $allocated_to = 'Open Task'; }
	                  else{ $allocated = DB::table('user')->where('user_id',$task->allocated_to)->first(); $allocated_to = $allocated->lastname.' '.$allocated->firstname; }

	                  if(Session::has('taskmanager_user'))
	                  {
	                    $author_cls = 'allocated_tr'; $hidden_author_cls = 'hidden_allocated_tr'; 
	                  }
	                  else{
	                    $author_cls = '';
	                    $hidden_author_cls = '';
	                  }

                    if($task->auto_close == 1)
                    {
                      $close_task = 'auto_close_task_complete';
                    }
                    else{
                      $close_task = '';
                    }
	                  ?>
	                  <tr class="tasks_tr <?php echo $author_cls; ?>" id="task_tr_<?php echo $task->id; ?>">
	                    <td style="vertical-align: baseline;background: #2fd9ff;width:35%;padding:0px">
                        <?php
                          $statusi = 0;
                          if(Session::has('taskmanager_user'))
                          {
                            if(Session::get('taskmanager_user') == $task->author) { 
                              if($task->author_spec_status == "1")
                              {
                                echo '<p class="redlight_indication redline_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;"></p>';
                                $statusi++;
                              }
                            }
                            else{
                              if($task->allocated_spec_status == "1")
                              {
                                echo '<p class="redlight_indication redline_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;"></p>';
                                $statusi++;
                              }
                            }
                          }
                          if($statusi == 0)
                          {
                            echo '<p class="redlight_indication redlight_indication_'.$task->id.'" style="border: 4px solid #f00;margin-top:0px;background: #f00;display:none"></p>';
                          }
                          ?>
	                      <table class="table">
	                        <tr>
	                          <td style="width:25%;background: #2fd9ff;font-weight:700;text-decoration: underline;"><?php echo $title_lable; ?></td>
	                          <td style="width:75%;background: #2fd9ff"><?php echo $title; ?> 
	                          <?php
	                          if($task->recurring_task > 0)
	                          {
	                            ?>
	                            <img src="<?php echo URL::to('assets/images/recurring.png'); ?>" style="width:30px;" title="This is a Recurring Task">
	                            <?php
	                          }
	                          ?>
	                          </td>
	                        </tr>
	                        <tr>
	                          <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Subject:</td>
	                          <td style="background: #2fd9ff"><?php echo $subject; ?></td>
	                        </tr>
	                        <tr>
	                          <?php 
	                            $date1=date_create(date('Y-m-d'));
	                            $date2=date_create($task->due_date);
	                            $diff=date_diff($date1,$date2);
	                            $diffdays = $diff->format("%R%a");

	                            if($diffdays == 0 || $diffdays== 1) { $due_color = '#e89701'; }
	                            elseif($diffdays < 0) { $due_color = '#f00'; }
	                            elseif($diffdays > 7) { $due_color = '#000'; }
	                            elseif($diffdays <= 7) { $due_color = '#00a91d'; }
	                            else{ $due_color = '#000'; }
	                          ?>
	                          <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Due Date:</td>

	                          <td style="background: #2fd9ff" class="<?php echo $disabled_icon; ?>">
	                            <spam style="color:<?php echo $due_color; ?> !important;font-weight:800" id="due_date_task_<?php echo $task->id; ?>"><?php echo date('d-M-Y', strtotime($task->due_date)); ?></spam>
	                            <a href="javascript:" data-element="<?php echo $task->id?>" data-subject="<?php echo $subject; ?>" data-value="<?php echo date('d-M-Y', strtotime($task->due_date)); ?>" data-duedate="<?php echo $task->due_date; ?>" data-color="<?php echo $due_color; ?>" class="fa fa-edit edit_due_date edit_due_date_<?php echo $task->id; ?> <?php echo $disabled; ?>" style="font-weight:800"></a>
	                          </td>
	                        </tr>
                          <tr>
                            <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Date Created:</td>
                            <td style="background: #2fd9ff">
                              <spam><?php echo date('d-M-Y', strtotime($task->creation_date)); ?></spam>
                            </td>
                          </tr>
	                        <tr>
	                          <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Task Specifics:</td>
	                          <td style="background: #2fd9ff"><a href="javascript:" class="link_to_task_specifics" data-element="<?php echo $task->id; ?>"><?php echo substr($task_specifics_val,0,30); ?>...</a></td>
	                        </tr>
	                        <tr>
	                          <td style="background: #2fd9ff;font-weight:700;text-decoration: underline;">Task files:</td>
	                          <td style="background: #2fd9ff">
	                            <?php
	                            $fileoutput = '';
	                            if(count($taskfiles))
	                            {
	                              foreach($taskfiles as $file)
	                              {
	                                if($file->status == 0)
	                                {
	                                  $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                                }
	                              }
	                            }
	                            if(count($tasknotepad))
	                            {
	                              foreach($tasknotepad as $note)
	                              {
	                                if($note->status == 0)
	                                {
	                                  $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                                }
	                              }
	                            }
	                            if(count($taskinfiles))
	                            {
	                              $i=1;
	                              foreach($taskinfiles as $infile)
	                              {
	                                if($infile->status == 0)
	                                {
	                                  if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                                  $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
	                                  $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                                  $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
	                                  <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
	                                  <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

	                                  <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
	                                  </p>';
	                                  $i++;
	                                }
	                              }
	                            }
	                            echo $fileoutput;
	                            ?>

	                          </td>
	                        </tr>
	                      </table>
	                    </td>
	                    <td style="vertical-align: baseline;background: #dcdcdc;width:30%">
	                      <table class="table">
	                        <tr>
	                          <td style="width:25%;font-weight:700;text-decoration: underline;">Author:</td>
	                          <td style="width:75%"><?php echo $author->lastname.' '.$author->firstname; ?> 
	                          <?php
	                          if($task->avoid_email == 0) {
	                            ?>
	                            <a href="javascript:" class="fa fa-envelope avoid_email" data-element="<?php echo $task->id; ?>" title="Avoid Emails for this task"></a>
	                            <?php
	                          }
	                          else{
	                            ?>
	                            <a href="javascript:" class="fa fa-envelope avoid_email retain_email" data-element="<?php echo $task->id; ?>" title="Avoid Emails for this task"></a>
	                            <?php
	                          }
	                          ?>
	                          </td>
	                        </tr>
	                        <tr>
	                          <td style=";font-weight:700;text-decoration: underline;">Allocated to:</td>
	                          <td id="allocated_to_name_<?php echo $task->id; ?>"><?php echo $allocated_to; ?></td>
	                        </tr>
	                        <tr>
	                          <td colspan="2">
	                            <spam style="font-weight:700;text-decoration: underline;">Allocations: </spam> &nbsp;
	                            <a href="javascript:" data-element="<?php echo $task->id?>" data-subject="<?php echo $subject; ?>" data-author="<?php echo $task->author; ?>" data-allocated="<?php echo $task->allocated_to; ?>"  class="fa fa-sitemap edit_allocate_user edit_allocate_user_<?php echo $task->id; ?> <?php echo $close_task; ?> <?php echo $disabled; ?>" title="Allocate User" style="font-weight:800"></a>
	                            &nbsp;
	                            <a href="javascript:" data-element="<?php echo $task->id?>" data-subject="<?php echo $subject; ?>" data-author="<?php echo $task->author; ?>" data-allocated="<?php echo $task->allocated_to; ?>"  class="fa fa-history show_task_allocation_history show_task_allocation_history_<?php echo $task->id; ?>" title="Allocation History" style="font-weight:800"></a>
	                            &nbsp;
	                            <a href="javascript:" data-element="<?php echo $task->id?>" data-author="<?php echo $task->author; ?>" data-allocated="<?php echo $task->allocated_to; ?>"  class="request_update request_update_<?php echo $task->id; ?>" title="Request Update" style="font-weight:800">
	                            	<img src="<?php echo URL::to('assets/images/request.png'); ?>" data-element="<?php echo $task->id?>" data-author="<?php echo $task->author; ?>" data-allocated="<?php echo $task->allocated_to; ?>"  class="request_update request_update_<?php echo $task->id; ?>" style="width:16px;">
	                            </a>
	                          </td>
	                        </tr>
	                        <tr>
	                          <td colspan="2" id="allocation_history_div_<?php echo $task->id; ?>" class="<?php echo $disabled_icon; ?>">
	                            <?php
	                            $allocations = DB::table('taskmanager_specifics')->where('task_id',$task->id)->where('to_user','!=','')->where('status','<',3)->limit(5)->orderBy('id','desc')->get();
	                            $output = '';
	                            if(count($allocations))
	                            {
	                              foreach($allocations as $allocate)
	                              {
	                                $output.='<p data-element="'.$task->id.'" data-subject="'.$subject.'" data-author="'.$task->author.'" data-allocated="'.$task->allocated_to.'"  class="edit_allocate_user edit_allocate_user_'.$task->id.' '.$close_task.' '.$disabled.'" title="Allocate User">';
	                                  $fromuser = DB::table('user')->where('user_id',$allocate->from_user)->first();
	                                  $touser = DB::table('user')->where('user_id',$allocate->to_user)->first();
	                                  $output.=$fromuser->lastname.' '.$fromuser->firstname.' -> '.$touser->lastname.' '.$touser->firstname.' ('.date('d-M-Y H:i', strtotime($allocate->allocated_date)).')';
	                                $output.='</p>';
	                              }
	                            }
	                            echo $output;
	                            ?>
	                          </td>
	                        </tr>
	                      </table>
	                    </td>
	                    <td style="vertical-align: baseline;background: #dcdcdc;width:20%">
	                      <table class="table">
	                        <tr>
	                          <td style="font-weight:700;text-decoration: underline;">Progress Files:</td>
	                          <td></td>
	                        </tr>
	                        <tr>
	                          <td class="<?php echo $disabled_icon; ?>">
	                            <a href="javascript:" class="fa fa-plus faplus_progress <?php echo $disabled; ?>" data-element="<?php echo $task->id; ?>" style="padding:5px;background: #dfdfdf;"></a>
	                            <a href="javascript:" class="fa fa-edit fanotepad_progress <?php echo $disabled; ?>" style="padding:5px;background: #dfdfdf;"></a>
	                            <?php
	                            if($task->client_id != "")
	                            {
	                              ?>
	                              <a href="javascript:" class="infiles_link_progress <?php echo $disabled; ?>" data-element="<?php echo $task->id; ?>">Infiles</a>
	                              <?php
	                            }
	                            ?>
	                            <input type="hidden" name="hidden_progress_client_id" id="hidden_progress_client_id_<?php echo $task->id; ?>" value="<?php echo $task->client_id; ?>">
	                            <input type="hidden" name="hidden_infiles_progress_id" id="hidden_infiles_progress_id_<?php echo $task->id; ?>" value="">
	                            
	                            <div class="notepad_div_progress_notes" style="z-index:9999; position:absolute">
	                              <textarea name="notepad_contents_progress" class="form-control notepad_contents_progress" placeholder="Enter Contents"></textarea>
	                              <input type="hidden" name="hidden_task_id_progress_notepad" id="hidden_task_id_progress_notepad" value="<?php echo $task->id; ?>">
	                              <input type="button" name="notepad_progress_submit" class="btn btn-sm btn-primary notepad_progress_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
	                              <spam class="error_files_notepad"></spam>
	                            </div>
	                          </td>
	                          <td></td>
	                        </tr>
	                        <tr>
	                          <td colspan="2" class="<?php echo $disabled_icon; ?>">
	                            <?php
	                            $fileoutput ='<div id="add_files_attachments_progress_div_'.$task->id.'">';
	                            if(count($taskfiles))
	                            {
	                              foreach($taskfiles as $file)
	                              {
	                                if($file->status == 1)
	                                {
	                                  $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                                }
	                              }
	                            }
	                            $fileoutput.='</div>';
	                            $fileoutput.='<div id="add_notepad_attachments_progress_div_'.$task->id.'">';
	                            if(count($tasknotepad))
	                            {
	                              foreach($tasknotepad as $note)
	                              {
	                                if($note->status == 1)
	                                {
	                                  $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
	                                }
	                              }
	                            }
	                            $fileoutput.='</div>';
	                            $fileoutput.='<div id="add_infiles_attachments_progress_div_'.$task->id.'">';
	                            if(count($taskinfiles))
	                            {
	                              $i=1;
	                                foreach($taskinfiles as $infile)
	                                {
	                                  if($infile->status == 1)
	                                  {
	                                    if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
	                                    $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
	                                    $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
	                                    $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
	                                    <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
	                                    <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

	                                    <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
	                                    </p>';
	                                    $i++;
	                                  }
	                                }
	                            }
	                            $fileoutput.='</div>';
	                            echo $fileoutput;
	                            ?>
	                          </td>
	                        </tr>
	                      </table>
	                    </td>
	                    <td style="vertical-align: baseline;background: #2fd9ff;width:15%">
	                      <table class="table" style="margin-bottom: 105px;">
                          <tr>
                            <td style="background:#2fd9ff" class="<?php echo $disabled_icon; ?>">
                              <spam style="font-weight:700;text-decoration: underline;font-size: 16px;">Task ID:</spam> <spam style="font-size: 16px;"><?php echo $task->taskid; ?></spam>
                              <a href="javascript:" class="fa fa-files-o copy_task" data-element="<?php echo $task->id; ?>" title="Copy this Task" style="padding:5px;font-size:20px;font-weight: 800;float: right"></a>
                              <a href="javascript:" class="fa fa-file-pdf-o download_pdf_task" data-element="<?php echo $task->id; ?>" title="Download PDF" style="padding:5px;font-size:20px;font-weight: 800;float: right">
                                </a> 
                              </td>
                          </tr>
                          <tr>
                            <td style="background:#2fd9ff">
                              <spam style="font-weight:700;text-decoration: underline;float:left">Progress:</spam> 
                              <a href="javascript:" class="fa fa-sliders" title="Set progress" data-placement="bottom" data-popover-content="#a1_<?php echo $task->id; ?>" data-toggle="popover" data-trigger="click" tabindex="0" data-original-title="Set Progress"  style="padding:5px;font-weight:700;float:left"></a>

                              <!-- Content for Popover #1 -->
                              <div class="hidden" id="a1_<?php echo $task->id; ?>">
                                <div class="popover-heading">
                                  Set Progress Percentage
                                </div>
                                <div class="popover-body">
                                  <input type="number" class="form-control input-sm progress_value" id="progress_value_<?php echo $task->id; ?>" value="" style="width:60%;float:left">
                                  <a href="javascript:" class="common_black_button set_progress" data-element="<?php echo $task->id; ?>" style="font-size: 11px;line-height: 29px;">Set</a>
                                </div>
                              </div>
                              <div class="progress progress_<?php echo $task->id; ?>" style="width:60%;margin-bottom:5px">
                                <div class="progress-bar" role="progressbar" aria-valuenow="<?php echo $task->progress; ?>" aria-valuemin="0" aria-valuemax="100" style="width:<?php echo $task->progress; ?>%">
                                  <?php echo $task->progress; ?>%
                                </div>
                              </div>
                            </td>
                          </tr>
                          <tr>
                            <td style="background:#2fd9ff">
                              <?php
                              if($task->status == 1)
                              {
                                ?>
                                <a href="javascript:" class="common_black_button mark_as_incomplete" data-element="<?php echo $task->id; ?>" style="font-size:12px">Completed</a>
                                <?php
                              }
                              elseif($task->status == 2)
                              {
                                if(Session::has('taskmanager_user'))
                                {
                                  $allocated_person = Session::get('taskmanager_user');
                                  if($task->author == $allocated_person)
                                  {
                                    $complete_button = 'mark_as_complete_author';
                                  }
                                  else{
                                    $complete_button = 'mark_as_complete';
                                  }
                                }
                                else{
                                  $complete_button = 'mark_as_complete';
                                }
                                ?>
                                <a href="javascript:" class="common_black_button <?php echo $complete_button; ?> <?php echo $close_task; ?>" data-element="<?php echo $task->id; ?>" style="font-size:12px">Mark Complete</a>
                                <a href="javascript:" class="common_black_button activate_task_button" data-element="<?php echo $task->id; ?>" style="font-size:12px">Activate</a>
                                <?php
                              }
                              else{
                                if(Session::has('taskmanager_user'))
                                {
                                  $allocated_person = Session::get('taskmanager_user');
                                  if($task->author == $allocated_person)
                                  {
                                    $complete_button = 'mark_as_complete_author';
                                  }
                                  else{
                                    $complete_button = 'mark_as_complete';
                                  }
                                }
                                else{
                                  $complete_button = 'mark_as_complete';
                                }
                                
                                ?>
                                <a href="javascript:" class="common_black_button <?php echo $complete_button; ?> <?php echo $close_task; ?>" data-element="<?php echo $task->id; ?>" style="font-size:12px">Mark Complete</a>
                                <a href="javascript:" class="common_black_button park_task_button" data-element="<?php echo $task->id; ?>" style="font-size:12px">Park Task</a>
                                <?php
                              }
                              ?>
                              
                              
                            </td>
                          </tr>
                          <tr>
                            <td style="background:#2fd9ff">
                              <spam style="font-weight:700;text-decoration: underline;">Completion Files:</spam><br/>
                              <a href="javascript:" class="fa fa-plus faplus_completion <?php echo $disabled; ?>" data-element="<?php echo $task->id; ?>" style="padding:5px"></a>
                              <a href="javascript:" class="fa fa-edit fanotepad_completion <?php echo $disabled; ?>" style="padding:5px;"></a>
                              <?php
                              if($task->client_id != "")
                              {
                                ?>
                                <a href="javascript:" class="infiles_link_completion <?php echo $disabled; ?>" data-element="<?php echo $task->id; ?>">Infiles</a>
                                <a href="javascript:" class="yearend_link_completion <?php echo $disabled; ?>" data-element="<?php echo $task->id; ?>">Yearend</a>
                                <?php
                              }
                              $get_infiles = DB::table('taskmanager_infiles')->where('task_id',$task->id)->get();
                              $get_yearend = DB::table('taskmanager_yearend')->where('task_id',$task->id)->get();
                              $idsval = '';
                              $idsval_yearend = '';
                              if(count($get_infiles))
                              {
                                foreach($get_infiles as $set_infile)
                                {
                                  if($idsval == "")
                                  {
                                    $idsval = $set_infile->infile_id;
                                  }
                                  else{
                                    $idsval = $idsval.','.$set_infile->infile_id;
                                  }
                                }
                              }
                              if(count($get_yearend))
                              {
                                foreach($get_yearend as $set_yearend)
                                {
                                  if($idsval_yearend == "")
                                  {
                                    $idsval_yearend = $set_yearend->setting_id;
                                  }
                                  else{
                                    $idsval_yearend = $idsval_yearend.','.$set_yearend->setting_id;
                                  }
                                }
                              }
                              ?>
                              
                              <input type="hidden" name="hidden_completion_client_id" id="hidden_completion_client_id_<?php echo $task->id; ?>" value="<?php echo $task->client_id; ?>">
                              <input type="hidden" name="hidden_infiles_completion_id" id="hidden_infiles_completion_id_<?php echo $task->id; ?>" value="<?php echo $idsval; ?>">
                              <input type="hidden" name="hidden_yearend_completion_id" id="hidden_yearend_completion_id_<?php echo $task->id; ?>" value="<?php echo $idsval_yearend; ?>">

                              
                              <div class="notepad_div_completion_notes" style="z-index:9999; position:absolute">
                                <textarea name="notepad_contents_completion" class="form-control notepad_contents_completion" placeholder="Enter Contents"></textarea>
                                <input type="hidden" name="hidden_task_id_completion_notepad" id="hidden_task_id_completion_notepad" value="<?php echo $task->id; ?>">
                                <input type="button" name="notepad_completion_submit" class="btn btn-sm btn-primary notepad_completion_submit" align="left" value="Upload" style="margin-left:7px;    background: #000;margin-top:4px">
                                <spam class="error_files_notepad"></spam>
                              </div>


                              <?php
                              $fileoutput ='<div id="add_files_attachments_completion_div_'.$task->id.'">';
                              if(count($taskfiles))
                              {
                                foreach($taskfiles as $file)
                                {
                                  if($file->status == 2)
                                  {
                                    $fileoutput.='<p><a href="'.URL::to($file->url).'/'.$file->filename.'" class="file_attachments" download>'.$file->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_files?file_id='.$file->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
                                  }
                                }
                              }
                              $fileoutput.='</div>';
                              $fileoutput.='<div id="add_notepad_attachments_completion_div_'.$task->id.'">';
                              if(count($tasknotepad))
                              {
                                foreach($tasknotepad as $note)
                                {
                                  if($note->status == 2)
                                  {
                                    $fileoutput.='<p><a href="'.URL::to($note->url).'/'.$note->filename.'" class="file_attachments" download>'.$note->filename.'</a> <a href="'.URL::to('user/delete_taskmanager_notepad?file_id='.$note->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a></p>';
                                  }
                                }
                              }
                              $fileoutput.='</div>';
                              $fileoutput.='<div id="add_infiles_attachments_completion_div_'.$task->id.'">';
                              if(count($taskinfiles))
                              {
                                $i=1;
                                  foreach($taskinfiles as $infile)
                                  {
                                    if($infile->status == 2)
                                    {
                                      if($i == 1) { $fileoutput.='Linked Infiles:<br/>'; }
                                      $file = DB::table('in_file')->where('id',$infile->infile_id)->first();
                                      $ele = URL::to('user/infile_search?client_id='.$task->client_id.'&fileid='.$file->id.'');
                                      $fileoutput.='<p class="link_infile_p"><a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$i.'</a>
                                      <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.date('d-M-Y', strtotime($file->data_received)).'</a>
                                      <a href="javascript:" class="link_infile" data-element="'.$ele.'">'.$file->description.'</a>

                                      <a href="'.URL::to('user/delete_taskmanager_infiles?file_id='.$infile->id.'').'" class="fa fa-trash delete_attachments '.$disabled.'"></a>
                                      </p>';
                                      $i++;
                                    }
                                  }
                              }
                              $fileoutput.='</div>';
                              $fileoutput.='<div id="add_yearend_attachments_completion_div_'.$task->id.'">';
                              if(count($taskyearend))
                              {
                                $i=1;
                                  foreach($taskyearend as $yearend)
                                  {
                                    if($yearend->status == 2)
                                    {
                                      if($i == 1) { $fileoutput.='Linked Yearend:<br/>'; }
                                      $file = DB::table('year_setting')->where('id',$yearend->setting_id)->first();
                                      $get_client_id = DB::table('taskmanager')->where('id',$task->id)->first();
                                      $year_client_id = $get_client_id->client_id;
                                      $yearend_id = DB::table('year_client')->where('client_id',$year_client_id)->orderBy('id','desc')->first();

                                      $ele = URL::to('user/yearend_individualclient/'.base64_encode($yearend_id->id).'');
                                      $fileoutput.='<p class="link_yearend_p"><a href="'.$ele.'" target="_blank">'.$i.'</a>
                                      <a href="'.$ele.'" target="_blank">'.$file->document.'</a>
                                      <a href="'.URL::to('user/delete_taskmanager_yearend?file_id='.$yearend->id.'').'" class="fa fa-trash delete_attachments"></a>
                                      </p>';
                                      $i++;
                                    }
                                  }
                              }
                              $fileoutput.='</div>';
                              echo $fileoutput;
                              ?>
                            </td>
                          </tr>
                          <tr>
                            <td style="background:#2fd9ff">
                            </td>
                          </tr>
                        </table>
	                    </td>
	                  </tr>
	                  <tr class="empty_tr" style="background: #fff;height:30px">
	                    <td style="padding:0px;background: #fff;">
	                      
	                    </td>
	                    <td colspan="3" style="background: #fff;height:30px"></td>
	                  </tr>
	                  
	                  <?php
	                  $layout.= '<tr class="hidden_tasks_tr '.$hidden_author_cls.'" id="hidden_tasks_tr_'.$task->id.'" data-element="'.$task->id.'" style="display:none">
	                    <td style="background: #dcdcdc;padding:0px;">
	                    	<table style="width:100%">
		                    	<tr>';
		                    	$statusi = 0;
				                  if(Session::has('taskmanager_user'))
				                  {
				                    if(Session::get('taskmanager_user') == $task->author) { 
				                      if($task->author_spec_status == "1")
				                      {
				                        $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redline_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800"></spam>';
				                        $redlight_value = 1;
				                        $statusi++;
				                      }
				                    }
				                    else{
				                      if($task->allocated_spec_status == "1")
				                      {
				                        $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redline_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800"></spam>';
				                        $redlight_value = 1;
				                        $statusi++;
				                      }
				                    }
				                  }
				                  if($statusi == 0)
				                  {
				                    $redlight_indication_layout ='<spam class="fa fa-star redlight_indication_layout redlight_indication_layout_'.$task->id.'" style="color:#f00;font-weight:800;display:none"></spam>';
				                    $redlight_value = 0;
				                  }
		                    		$layout.= '
		                    		<td style="width:5%;padding:10px; font-size:14px; font-weight:800;" class="redlight_sort_val">
		                    		<spam class="hidden_redlight_value" style="display:none">'.$redlight_value.'</spam>
		                    		'.$redlight_indication_layout.'
		                    		</td>
		                    		<td style="width:45%;padding:10px; font-size:14px; font-weight:800;" class="taskname_sort_val">'.$title.'</td>
                            <td style="width:5%;padding:10px; font-size:14px; font-weight:800;" class="2bill_sort_val">
                            '.$two_bill_icon.'
                            </td>
		                    		<td style="width:45%;padding:10px; font-size:14px; font-weight:800" class="subject_sort_val">'.$subject.'</td>
		                    	</tr>
		                    </table>
	                    </td>
	                    <td style="background: #dcdcdc;padding:0px;">
	                    	<table style="width:100%">
		                    	<tr>
		                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800;" class="author_sort_val">'.$author->lastname.' '.$author->firstname.'</td>
		                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="allocated_sort_val">'.$allocated_to.'</td>
		                    	</tr>
	                    	</table>
	                    </td>
	                    <td style="background: #dcdcdc;padding:0px;">
	                    	<table style="width:100%">
		                    	<tr>
		                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800;" class="duedate_sort_val">
		                    			<spam class="hidden_due_date_layout" style="display:none">'.strtotime($task->due_date).'</spam>
		                    			<spam class="layout_due_date_task" style="color:'.$due_color.' !important;font-weight:800" id="layout_due_date_task_'.$task->id.'">'.date('d-M-Y', strtotime($task->due_date)).'</spam>
		                    		</td>
                            <td style="width:50%;padding:10px; font-size:14px; font-weight:800;">
                              '.date('d-M-Y', strtotime($task->park_date)).'
                            </td>
                            <td style="width:50%;padding:10px; font-size:14px; font-weight:800;">';
                              if(strtotime(date('Y-m-d')) >= strtotime($task->park_date))
                              {
                                $layout.='Will Park';
                              }
                              else{
                                $layout.='Not Park';
                              }
                            $layout.='</td>
		                    		<td style="width:50%;padding:10px; font-size:14px; font-weight:800" class="createddate_sort_val">
		                    		<spam class="hidden_created_date_layout" style="display:none">'.strtotime($task->creation_date).'</spam>
		                    		'.date('d-M-Y', strtotime($task->creation_date)).'
		                    		</td>
		                    	</tr>
		                    </table>
	                    </td>
	                    <td style="background: #dcdcdc;padding:0px;">
	                    	<table style="width:100%">
		                    	<tr>
		                    		<td style="width:40%;padding:10px; font-size:14px; font-weight:800;" class="taskid_sort_val">'.$task->taskid.'</td>
		                    		<td class="layout_progress_'.$task->id.'" style="width:40%;padding:10px; font-size:14px; font-weight:800;"><spam class="progress_sort_val" style="display:none">'.$task->progress.'</spam>'.$task->progress.'%</td>
		                    		<td style="width:20%;padding:10px; font-size:14px; font-weight:800">
		                    			<a href="javascript:" class="fa fa-file-pdf-o download_pdf_task" data-element="'.$task->id.'" title="Download PDF" style="padding:5px;font-size:20px;font-weight: 800">
	                              		</a> 
		                    		</td>
		                    	</tr>
		                    </table>
	                    </td>
	                  </tr>';
	                }
	              }
	              else{
	                ?>
	                <td colspan="4" style="text-align: center;padding:20px">No Tasks Found</td>
	                <?php
                  $layout.='<tr><td colspan="4" style="text-align: center;padding:20px">No Tasks Found</td></tr>';
	              }
	              ?>
	            </tbody>
	          </table>
	      </div>
          <?php 
          if($open_task_count == 0 && $authored_task_count == 0) {  } else { ?>
          <table class="table_bg table-fixed-header table_layout" style="width:100%;float:left;display:none">
          	<thead>
          		<tr class="hidden_tasks_th" id="menulist">
                    <td style="background: #000;padding:0px;border:1px solid #868686;border-right: 0px solid">
                    	<table style="width:100%">
	                    	<tr>
	                    		<td style="color:#fff;width:5%;padding:10px; font-size:14px; font-weight:800;border-right: 1px solid #868686"><i class="fa fa-sort redlight_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
	                    		<td style="color:#fff;width:45%;padding:10px; font-size:14px; font-weight:800;border-right: 1px solid #868686">Client/Task Name<i class="fa fa-sort taskname_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
                            <td style="color:#fff;width:5%;padding:10px; font-size:14px; font-weight:800;border-right: 1px solid #868686"></td>
	                    		<td style="color:#fff;width:45%;padding:10px; font-size:14px; font-weight:800">Subject<i class="fa fa-sort subject_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
	                    	</tr>
	                    </table>
                    </td>
                    <td style="background: #000;padding:0px;border:1px solid #868686;border-right: 0px solid">
                    	<table style="width:100%">
	                    	<tr>
	                    		<td style="color:#fff;width:50%;padding:10px; font-size:14px; font-weight:800;border-right: 1px solid #868686">Author Name<i class="fa fa-sort author_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
	                    		<td style="color:#fff;width:50%;padding:10px; font-size:14px; font-weight:800">Allocated Name<i class="fa fa-sort allocated_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
	                    	</tr>
                    	</table>
                    </td>
                    <td style="background: #000;padding:0px;border:1px solid #868686;border-right: 0px solid">
                    	<table style="width:100%">
	                    	<tr>
	                    		<td style="color:#fff;width:50%;padding:10px; font-size:14px; font-weight:800;border-right: 1px solid #868686">Due Date<i class="fa fa-sort duedate_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
                          <td style="color:#fff;width:50%;padding:10px; font-size:14px; font-weight:800;border-right: 1px solid #868686">Parked until<i class="fa fa-sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
                          <td style="color:#fff;width:50%;padding:10px; font-size:14px; font-weight:800;border-right: 1px solid #868686">Status<i class="fa fa-sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
	                    		<td style="color:#fff;width:50%;padding:10px; font-size:14px; font-weight:800">Created Date<i class="fa fa-sort createddate_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
	                    	</tr>
	                    </table>
                    </td>
                    <td style="background: #000;padding:0px;border:1px solid #868686">
                    	<table style="width:100%">
	                    	<tr>
	                    		<td style="color:#fff;width:40%;padding:10px; font-size:14px; font-weight:800;border-right: 1px solid #868686">Task ID<i class="fa fa-sort taskid_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
	                    		<td style="color:#fff;width:40%;padding:10px; font-size:14px; font-weight:800;border-right: 1px solid #868686">Progress<i class="fa fa-sort progress_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></td>
	                    		<td style="color:#fff;width:20%;padding:10px; font-size:14px; font-weight:800">
	                    			Action
	                    		</td>
	                    	</tr>
	                    </table>
                    </td>
                </tr>
          	</thead>
          	<tbody id="task_body_layout">
          		<?php 
          		echo $layout;
          		?>
          	</tbody>
          </table>
      		<?php } ?>
        </div>
      </div>
    </div>
</div>
<input type="hidden" name="taskname_sortoptions" id="taskname_sortoptions" value="asc">
<input type="hidden" name="subject_sortoptions" id="subject_sortoptions" value="asc">
<input type="hidden" name="author_sortoptions" id="author_sortoptions" value="asc">
<input type="hidden" name="allocated_sortoptions" id="allocated_sortoptions" value="asc">
<input type="hidden" name="duedate_sortoptions" id="duedate_sortoptions" value="asc">
<input type="hidden" name="createddate_sortoptions" id="createddate_sortoptions" value="asc">
<input type="hidden" name="taskid_sortoptions" id="taskid_sortoptions" value="asc">
<input type="hidden" name="progress_sortoptions" id="progress_sortoptions" value="asc">
<input type="hidden" name="redlight_sortoptions" id="redlight_sortoptions" value="asc">
<div class="modal_load"></div>
<script>
<?php
if(!empty($_GET['tr_task_id']))
{
  $divid = $_GET['tr_task_id'];
  ?>
  $(function() {
    $(document).scrollTop( $("#task_tr_<?php echo $divid; ?>").offset().top - parseInt(150) );
  });
  <?php
}
?>
$(function(){
    $("[data-toggle=popover]").popover({
        html : true,
        content: function() {
          var content = $(this).attr("data-popover-content");
          return $(content).children(".popover-body").html();
        },
        title: function() {
          var title = $(this).attr("data-popover-content");
          return $(title).children(".popover-heading").html();
        }
    });

    var layout = $("#hidden_compressed_layout").val();
    $(".tasks_tr").hide();
	$(".tasks_tr").next().hide();
	$(".hidden_tasks_tr").hide();
  var view = $(".select_view").val();
    if(view == "3")
    {
      if(layout == "1")
   	  {
   	  	$(".author_tr:first").show();
      	$(".author_tr:first").next().show();
      	$(".table_layout").show();
      	$(".table_layout").find(".hidden_author_tr").show();
      	$(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#2fd9ff");
   	  }
   	  else{
   	  	$(".author_tr").show();
      	$(".author_tr").next().show();
      	$(".table_layout").hide();
      	$(".table_layout").find(".hidden_author_tr").hide();
   	  }

      var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
      var opentask = $(".hidden_allocated_tr").length;
      var authored = $(".hidden_author_tr").length;
      $("#redline_task_count_val").html(redline);
      $("#open_task_count_val").html(opentask);
      $("#authored_task_count_val").html(authored);
    }
    else if(view == "2"){
      $("#open_task_count").hide();
      $("#redline_task_count").show();
      $("#authored_task_count").hide();
      if(layout == "1")
      {
        var i = 1;
        $(".redline_indication").each(function() {
          if(i == 1)
          {
            if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
            {
              $(this).parents(".allocated_tr").show();
              $(this).parents(".allocated_tr").next().show();
              i++;
            }
          }
        });
        $(".table_layout").show();
        $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
        
        var j = 1;
        $(".redline_indication_layout").each(function() {
          if(j == 1)
          {
            if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
            {
              $(this).parents(".hidden_allocated_tr").find("td").css("background","#2fd9ff");
              j++;
            }
          }
        });
      }
      else{
        $(".redline_indication").parents(".allocated_tr").show();
        $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
        $(".table_layout").hide();
        $(".table_layout").find(".hidden_allocated_tr").hide();
      }

      var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
      var opentask = $(".hidden_allocated_tr").length;
      var authored = $(".hidden_author_tr").length;
      $("#redline_task_count_val").html(redline);
      $("#open_task_count_val").html(opentask);
      $("#authored_task_count_val").html(authored);
    }
    else if(view == "1"){
      if(layout == "1")
   	  {
   	  	$(".allocated_tr:first").show();
      	$(".allocated_tr:first").next().show();
      	$(".table_layout").show();
      	$(".table_layout").find(".hidden_allocated_tr").show();
      	$(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#2fd9ff");
   	  }
   	  else{
   	  	$(".allocated_tr").show();
      	$(".allocated_tr").next().show();
      	$(".table_layout").hide();
      	$(".table_layout").find(".hidden_allocated_tr").hide();
   	  }

      var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
      var opentask = $(".hidden_allocated_tr").length;
      var authored = $(".hidden_author_tr").length;
      $("#redline_task_count_val").html(redline);
      $("#open_task_count_val").html(opentask);
      $("#authored_task_count_val").html(authored);
    }

    if(layout == "1")
    {
      $(".open_layout_div").addClass("open_layout_div_change");
    	var open_tasks_height = $(".open_layout_div").height();
    	var margintop = parseInt(open_tasks_height);
    	$(".open_layout_div").css("position","fixed");
    	$(".open_layout_div").css("height","312px");
    	if(open_tasks_height > 312)
    	{
    		$(".open_layout_div").css("overflow-y","scroll");
    	}
    	if(open_tasks_height < 50)
    	{
    		$(".table_layout").css("margin-top","20px");
    	}
        else{
        	$(".table_layout").css("margin-top","335px");
        }
    }
    else{
      $(".open_layout_div").removeClass("open_layout_div_change");
    	$(".open_layout_div").css("position","unset");
    	$(".open_layout_div").css("height","auto");
    	$(".open_layout_div").css("overflow-y","unset");
        $(".table_layout").css("margin-top","0px");
    }
    $(".taskname_sort_val").find("img").detach();
});
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
          }
        })
      }
  });
 $(".copy_client_search_class").autocomplete({
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
            $("#copy_client_search").val(ui.item.id);
          }
        })
      }
  });
$('body').on('hidden.bs.popover', function (e) {
    $(e.target).data("bs.popover").inState = { click: false, hover: false, focus: false }
});
$('html').on('click', function(e) {
  if (typeof $(e.target).data('original-title') == 'undefined' && !$(e.target).parents().is('.popover.in')) {
    $('[data-original-title]').popover('hide');
  }
});
var convertToNumber = function(value){
       return value.toLowerCase();
}
var parseconvertToNumber = function(value){
       return parseInt(value);
}
$(window).click(function(e) {
	var ascending = false;
	if($(e.target).hasClass('taskname_sort'))
	{
		var sort = $("#taskname_sortoptions").val();
		if(sort == 'asc')
		{
		  $("#taskname_sortoptions").val('desc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (convertToNumber($(a).find('.taskname_sort_val').text()) <
		    convertToNumber($(b).find('.taskname_sort_val').text()))) ? 1 : -1;
		  });
		}
		else{
		  $("#taskname_sortoptions").val('asc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (convertToNumber($(a).find('.taskname_sort_val').text()) <
		    convertToNumber($(b).find('.taskname_sort_val').text()))) ? -1 : 1;
		  });
		}
		ascending = ascending ? false : true;
		$('#task_body_layout').html(sorted);
	}
	if($(e.target).hasClass('redlight_sort'))
	{
		var sort = $("#redlight_sortoptions").val();
		if(sort == 'asc')
		{
		  $("#redlight_sortoptions").val('desc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (convertToNumber($(a).find('.hidden_redlight_value').text()) <
		    convertToNumber($(b).find('.hidden_redlight_value').text()))) ? 1 : -1;
		  });
		}
		else{
		  $("#redlight_sortoptions").val('asc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (convertToNumber($(a).find('.hidden_redlight_value').text()) <
		    convertToNumber($(b).find('.hidden_redlight_value').text()))) ? -1 : 1;
		  });
		}
		ascending = ascending ? false : true;
		$('#task_body_layout').html(sorted);
	}
	if($(e.target).hasClass('subject_sort'))
	{
		var sort = $("#subject_sortoptions").val();
		if(sort == 'asc')
		{
		  $("#subject_sortoptions").val('desc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (convertToNumber($(a).find('.subject_sort_val').text()) <
		    convertToNumber($(b).find('.subject_sort_val').text()))) ? 1 : -1;
		  });
		}
		else{
		  $("#subject_sortoptions").val('asc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (convertToNumber($(a).find('.subject_sort_val').text()) <
		    convertToNumber($(b).find('.subject_sort_val').text()))) ? -1 : 1;
		  });
		}
		ascending = ascending ? false : true;
		$('#task_body_layout').html(sorted);
	}
	if($(e.target).hasClass('author_sort'))
	{
		var sort = $("#author_sortoptions").val();
		if(sort == 'asc')
		{
		  $("#author_sortoptions").val('desc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (convertToNumber($(a).find('.author_sort_val').text()) <
		    convertToNumber($(b).find('.author_sort_val').text()))) ? 1 : -1;
		  });
		}
		else{
		  $("#author_sortoptions").val('asc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (convertToNumber($(a).find('.author_sort_val').text()) <
		    convertToNumber($(b).find('.author_sort_val').text()))) ? -1 : 1;
		  });
		}
		ascending = ascending ? false : true;
		$('#task_body_layout').html(sorted);
	}
	if($(e.target).hasClass('allocated_sort'))
	{
		var sort = $("#allocated_sortoptions").val();
		if(sort == 'asc')
		{
		  $("#allocated_sortoptions").val('desc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (convertToNumber($(a).find('.allocated_sort_val').text()) <
		    convertToNumber($(b).find('.allocated_sort_val').text()))) ? 1 : -1;
		  });
		}
		else{
		  $("#allocated_sortoptions").val('asc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (convertToNumber($(a).find('.allocated_sort_val').text()) <
		    convertToNumber($(b).find('.allocated_sort_val').text()))) ? -1 : 1;
		  });
		}
		ascending = ascending ? false : true;
		$('#task_body_layout').html(sorted);
	}
	if($(e.target).hasClass('duedate_sort'))
	{
		var sort = $("#duedate_sortoptions").val();
		if(sort == 'asc')
		{
		  $("#duedate_sortoptions").val('desc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (parseconvertToNumber($(a).find('.hidden_due_date_layout').text()) <
		    parseconvertToNumber($(b).find('.hidden_due_date_layout').text()))) ? 1 : -1;
		  });
		}
		else{
		  $("#duedate_sortoptions").val('asc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (parseconvertToNumber($(a).find('.hidden_due_date_layout').text()) <
		    parseconvertToNumber($(b).find('.hidden_due_date_layout').text()))) ? -1 : 1;
		  });
		}
		ascending = ascending ? false : true;
		$('#task_body_layout').html(sorted);
	}
	if($(e.target).hasClass('createddate_sort'))
	{
		var sort = $("#createddate_sortoptions").val();
		if(sort == 'asc')
		{
		  $("#createddate_sortoptions").val('desc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (parseconvertToNumber($(a).find('.hidden_created_date_layout').text()) <
		    parseconvertToNumber($(b).find('.hidden_created_date_layout').text()))) ? 1 : -1;
		  });
		}
		else{
		  $("#createddate_sortoptions").val('asc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (parseconvertToNumber($(a).find('.hidden_created_date_layout').text()) <
		    parseconvertToNumber($(b).find('.hidden_created_date_layout').text()))) ? -1 : 1;
		  });
		}
		ascending = ascending ? false : true;
		$('#task_body_layout').html(sorted);
	}
	if($(e.target).hasClass('taskid_sort'))
	{
		var sort = $("#taskid_sortoptions").val();
		if(sort == 'asc')
		{
		  $("#taskid_sortoptions").val('desc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (convertToNumber($(a).find('.taskid_sort_val').text()) <
		    convertToNumber($(b).find('.taskid_sort_val').text()))) ? 1 : -1;
		  });
		}
		else{
		  $("#taskid_sortoptions").val('asc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (convertToNumber($(a).find('.taskid_sort_val').text()) <
		    convertToNumber($(b).find('.taskid_sort_val').text()))) ? -1 : 1;
		  });
		}
		ascending = ascending ? false : true;
		$('#task_body_layout').html(sorted);
	}
	if($(e.target).hasClass('progress_sort'))
	{
		var sort = $("#progress_sortoptions").val();
		if(sort == 'asc')
		{
		  $("#progress_sortoptions").val('desc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (convertToNumber($(a).find('.progress_sort_val').text()) <
		    parseconvertToNumber($(b).find('.progress_sort_val').text()))) ? 1 : -1;
		  });
		}
		else{
		  $("#progress_sortoptions").val('asc');
		  var sorted = $('#task_body_layout').find('.hidden_tasks_tr:visible').sort(function(a,b){
		    return (ascending ==
		         (convertToNumber($(a).find('.progress_sort_val').text()) <
		    parseconvertToNumber($(b).find('.progress_sort_val').text()))) ? -1 : 1;
		  });
		}
		ascending = ascending ? false : true;
		$('#task_body_layout').html(sorted);
	}
  if($(e.target).hasClass('activate_task_button'))
  {
    var r = confirm("Do you want to make this task live now?");
    if(r)
    {
      $("body").addClass("loading");
      var task_id = $(e.target).attr("data-element");
      var nexttask_id = $(e.target).parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
      var prevtask_id = $(e.target).parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
      if (typeof nexttask_id !== "undefined") {
        var taskidval = nexttask_id;
      }
      else if (typeof prevtask_id !== "undefined") {
        var taskidval = prevtask_id;
      }
      else{
        var taskidval = '';
      }
      $.ajax({
        url:"<?php echo URL::to('user/park_task_incomplete'); ?>",
        type:"post",
        data:{task_id:task_id},
        success:function(resultval)
        {
          var layout = $("#hidden_compressed_layout").val();
          var view = $(".select_view").val();
          if(layout == "1")
          {
            var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
            var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
            if (typeof nexttask_id !== "undefined") {
              var taskidval = nexttask_id;
            }
            else if (typeof prevtask_id !== "undefined") {
              var taskidval = prevtask_id;
            }
            else{
              var taskidval = '';
            }

            $("#task_tr_"+task_id).next().detach();
            $("#task_tr_"+task_id).detach();
            $("#hidden_tasks_tr_"+task_id).detach();

            $("#task_tr_"+taskidval).show();
            $("#task_tr_"+taskidval).next().show();
            $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#2fd9ff");

            var opentask = $("#open_task_count_val").html();
            var parktask = $("#park_task_count_val").html();
            var countopen = parseInt(opentask) - 1;
            var countpark = parseInt(parktask) + 1;
            $("#open_task_count_val").html(countopen);
            $("#park_task_count_val").html(countpark);
            $("body").removeClass("loading");
          }
          else{
            setTimeout(function() {
              var user_id = $(".select_user_home").val();
              $.ajax({
                url:"<?php echo URL::to('user/refresh_parktask'); ?>",
                type:"post",
                data:{user_id:user_id},
                dataType:"json",
                success: function(result)
                {
                  $("#task_body_open").html(result['open_tasks']);
                  $("#task_body_layout").html(result['layout']);
                  $(".taskname_sort_val").find("img").detach();
                  var layout = $("#hidden_compressed_layout").val();
                  $(".tasks_tr").hide();
                  $(".tasks_tr").next().hide();
                  $(".hidden_tasks_tr").hide();
                  var view = $(".select_view").val();
                  if(view == "3")
                  {
                    if(layout == "1")
                    {
                      $(".author_tr:first").show();
                      $(".author_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_author_tr").show();
                      $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#2fd9ff");
                    }
                    else{
                      $(".author_tr").show();
                      $(".author_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_author_tr").hide();
                    }

                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;

                    var parktask = $("#park_task_count_val").html();
                    var countpark = parseInt(parktask) + 1;
                    $("#park_task_count_val").html(countpark);
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  else if(view == "2"){
                    $("#open_task_count").hide();
                    $("#redline_task_count").show();
                    $("#authored_task_count").hide();
                    if(layout == "1")
                    {
                      var i = 1;
                      $(".redline_indication").each(function() {
                        if(i == 1)
                        {
                          if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                          {
                            $(this).parents(".allocated_tr").show();
                            $(this).parents(".allocated_tr").next().show();
                            i++;
                          }
                        }
                      });
                      $(".table_layout").show();
                      $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                      
                      var j = 1;
                      $(".redline_indication_layout").each(function() {
                        if(j == 1)
                        {
                          if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                          {
                            $(this).parents(".hidden_allocated_tr").find("td").css("background","#2fd9ff");
                            j++;
                          }
                        }
                      });
                    }
                    else{
                      $(".redline_indication").parents(".allocated_tr").show();
                      $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }

                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;

                    var parktask = $("#park_task_count_val").html();
                    var countpark = parseInt(parktask) + 1;
                    $("#park_task_count_val").html(countpark);
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  else if(view == "1"){
                    if(layout == "1")
                    {
                      $(".allocated_tr:first").show();
                      $(".allocated_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_allocated_tr").show();
                      $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#2fd9ff");
                    }
                    else{
                      $(".allocated_tr").show();
                      $(".allocated_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }

                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;

                    var parktask = $("#park_task_count_val").html();
                    var countpark = parseInt(parktask) + 1;
                    $("#park_task_count_val").html(countpark);
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  $("[data-toggle=popover]").popover({
                      html : true,
                      content: function() {
                        var content = $(this).attr("data-popover-content");
                        return $(content).children(".popover-body").html();
                      },
                      title: function() {
                        var title = $(this).attr("data-popover-content");
                        return $(title).children(".popover-heading").html();
                      }
                  });
                  if(layout == "0")
                  {
                    if(taskidval != "")
                    {
                      // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                    }
                  }
                  else{
                    $("#"+taskidval).show();
                    $("#"+taskidval).next().show();
                    var hidden_tr = taskidval.substr(8);
                    $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#2fd9ff");
                  }
                  if(layout == "1")
                  {
                    $(".open_layout_div").addClass("open_layout_div_change");
                    var open_tasks_height = $(".open_layout_div").height();
                    var margintop = parseInt(open_tasks_height);
                    $(".open_layout_div").css("position","fixed");
                    $(".open_layout_div").css("height","312px");
                    if(open_tasks_height > 312)
                    {
                      $(".open_layout_div").css("overflow-y","scroll");
                    }
                    if(open_tasks_height < 50)
                    {
                      $(".table_layout").css("margin-top","20px");
                    }
                      else{
                        $(".table_layout").css("margin-top","335px");
                      }
                  }
                  else{
                    $(".open_layout_div").removeClass("open_layout_div_change");
                    $(".open_layout_div").css("position","unset");
                    $(".open_layout_div").css("height","auto");
                    $(".open_layout_div").css("overflow-y","unset");
                      $(".table_layout").css("margin-top","0px");
                  }
                  $("body").removeClass("loading");
                }
              })
            },2000);
          }
        }
      })
    }
  }
  if($(e.target).hasClass('edit_task_name'))
  {
    var taskid = $(e.target).attr("data-element");
    var taskname = $(e.target).attr("data-value");
    $(".task-choose_internal_change").html(taskname);
    $(".hidden_task_id_change_task").val(taskid);
    $(".change_taskname_modal").modal("show");
  }
	if($(e.target).hasClass('request_update'))
	{
		var r = confirm("An email is sent to the person who the task is currently allocated to. Are you sure you want to continue?");
		if(r)
		{
			$("body").addClass("loading");
			setTimeout(function() {
				var task_id = $(e.target).attr("data-element");
				var author = $(e.target).attr("data-author");
				var allocated_to = $(e.target).attr("data-allocated");

				$.ajax({
					url:"<?php echo URL::to('user/request_update'); ?>",
					type:"post",
					data:{task_id:task_id,author:author,allocated_to:allocated_to},
					success:function(result)
					{
						$("body").removeClass("loading");
						if(result == "1")
						{
							$.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Email sent Successfully</p>", fixed:true});
						}
						else{
							$.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:#f00>The Task you requested for update is an open task. Email will be sent only if any of the user is been allocated to this task.</p>", fixed:true});
						}
					}
				});
			},1000);
		}
	}
	if($(e.target).parents(".switch").length > 0)
	{
		if($(e.target).parents(".compressed_layout_div").find(".compressed_layout").is(":checked"))
		{
		  $(e.target).parents(".compressed_layout_div").find("#hidden_compressed_layout").val("1");
		}
		else{
		  $(e.target).parents(".compressed_layout_div").find("#hidden_compressed_layout").val("0");
		}

		var layout = $("#hidden_compressed_layout").val();
	    $(".tasks_tr").hide();
		$(".tasks_tr").next().hide();
		$(".hidden_tasks_tr").hide();
    var view = $(".select_view").val();
	    if(view == "3")
	    {
	      if(layout == "1")
	   	  {
	   	  	$(".author_tr:first").show();
	      	$(".author_tr:first").next().show();
	      	$(".table_layout").show();
	      	$(".table_layout").find(".hidden_author_tr").show();
	      	$(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#2fd9ff");
	   	  }
	   	  else{
	   	  	$(".author_tr").show();
	      	$(".author_tr").next().show();
	      	$(".table_layout").hide();
	      	$(".table_layout").find(".hidden_author_tr").hide();
	   	  }

        var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
        var opentask = $(".hidden_allocated_tr").length;
        var authored = $(".hidden_author_tr").length;

        var parktask = $("#park_task_count_val").html();
        var countpark = parseInt(parktask) + 1;
        $("#park_task_count_val").html(countpark);

        $("#redline_task_count_val").html(redline);
        $("#open_task_count_val").html(opentask);
        $("#authored_task_count_val").html(authored);
	    }
      else if(view == "2"){
        $("#open_task_count").hide();
        $("#redline_task_count").show();
        $("#authored_task_count").hide();
        if(layout == "1")
        {
          var i = 1;
          $(".redline_indication").each(function() {
            if(i == 1)
            {
              if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
              {
                $(this).parents(".allocated_tr").show();
                $(this).parents(".allocated_tr").next().show();
                i++;
              }
            }
          });
          $(".table_layout").show();
          $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
          
          var j = 1;
          $(".redline_indication_layout").each(function() {
            if(j == 1)
            {
              if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
              {
                $(this).parents(".hidden_allocated_tr").find("td").css("background","#2fd9ff");
                j++;
              }
            }
          });
        }
        else{
          $(".redline_indication").parents(".allocated_tr").show();
          $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
          $(".table_layout").hide();
          $(".table_layout").find(".hidden_allocated_tr").hide();
        }

        var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
        var opentask = $(".hidden_allocated_tr").length;
        var authored = $(".hidden_author_tr").length;

        var parktask = $("#park_task_count_val").html();
                    var countpark = parseInt(parktask) + 1;
                    $("#park_task_count_val").html(countpark);

        $("#redline_task_count_val").html(redline);
        $("#open_task_count_val").html(opentask);
        $("#authored_task_count_val").html(authored);
      }
	    else if(view == "1"){
	      if(layout == "1")
	   	  {
	   	  	$(".allocated_tr:first").show();
	      	$(".allocated_tr:first").next().show();
	      	$(".table_layout").show();
	      	$(".table_layout").find(".hidden_allocated_tr").show();
	      	$(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#2fd9ff");
	   	  }
	   	  else{
	   	  	$(".allocated_tr").show();
	      	$(".allocated_tr").next().show();
	      	$(".table_layout").hide();
	      	$(".table_layout").find(".hidden_allocated_tr").hide();
	   	  }

        var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
        var opentask = $(".hidden_allocated_tr").length;
        var authored = $(".hidden_author_tr").length;

        var parktask = $("#park_task_count_val").html();
                    var countpark = parseInt(parktask) + 1;
                    $("#park_task_count_val").html(countpark);
        $("#redline_task_count_val").html(redline);
        $("#open_task_count_val").html(opentask);
        $("#authored_task_count_val").html(authored);
	    }

	    if(layout == "1")
      {
        $(".open_layout_div").addClass("open_layout_div_change");
        var open_tasks_height = $(".open_layout_div").height();
        var margintop = parseInt(open_tasks_height);
        $(".open_layout_div").css("position","fixed");
        $(".open_layout_div").css("height","312px");
        if(open_tasks_height > 312)
        {
          $(".open_layout_div").css("overflow-y","scroll");
        }
        if(open_tasks_height < 50)
        {
          $(".table_layout").css("margin-top","20px");
        }
          else{
            $(".table_layout").css("margin-top","335px");
          }
      }
      else{
        $(".open_layout_div").removeClass("open_layout_div_change");
        $(".open_layout_div").css("position","unset");
        $(".open_layout_div").css("height","auto");
        $(".open_layout_div").css("overflow-y","unset");
          $(".table_layout").css("margin-top","0px");
      }
	}
  if($(e.target).hasClass('make_task_live'))
  {
    e.preventDefault();
    if($( "#create_job_form" ).valid())
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
        var clientid = $("#client_search").val();
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
            $("#create_job_form").submit();
          }
          else{
            $.colorbox({html:'<p style="text-align:center;margin-top:26px;"><img src="<?php echo URL::to('assets/2bill.png'); ?>" style="width: 100px;"></p><p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Is this Task a 2Bill Task?  If this is a Non-Standard task for this Client you may want to set the 2Bill Status</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_make_task_live">Yes</a><a href="javascript:" class="common_black_button no_make_task_live">No</a></p>',fixed:true,width:"800px"});
          }
        }
      }
      else{
        if($(".2_bill_task").is(":checked"))
        {
          $("#create_job_form").submit();
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
    $("#create_job_form").submit();
  }
  if($(e.target).hasClass('no_make_task_live'))
  {
    $(".2_bill_task").prop("checked",false);
    $("#create_job_form").submit();
  }
  if($(e.target).hasClass('avoid_email'))
  {
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-element");
    if($(e.target).hasClass('retain_email'))
    {
      $.ajax({
        url:"<?php echo URL::to('user/set_avoid_email_taskmanager'); ?>",
        type:"post",
        data:{task_id:task_id,status:0},
        success:function(result)
        {
          $(e.target).removeClass("retain_email");
          $("body").removeClass("loading");
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/set_avoid_email_taskmanager'); ?>",
        type:"post",
        data:{task_id:task_id,status:1},
        success:function(result)
        {
          $(e.target).addClass("retain_email");
          $("body").removeClass("loading");
        }
      });
    }
  }
  if($(e.target).hasClass('set_progress'))
  {
    var task_id = $(e.target).attr("data-element");
    var value = $(e.target).parents(".popover-content").find(".progress_value").val();
    if(value == "")
    {
      $(".progress_"+task_id).find(".progress-bar").attr("area-valuenow",0);
      $(".progress_"+task_id).find(".progress-bar").css("width","0px");
      $(".layout_progress_"+task_id).html("0%");
    }
    else{
      $(".progress_"+task_id).find(".progress-bar").attr("aria-valuenow",value);
      $(".progress_"+task_id).find(".progress-bar").css("width",value+"%");
      $(".progress_"+task_id).find(".progress-bar").html(value+"%");

      $(".layout_progress_"+task_id).html(value+"%");
    }
    $(".progress_value").val("");
    $('[data-toggle="popover"]').popover('hide')
    $.ajax({
      url:"<?php echo URL::to('user/set_progress_value'); ?>",
      type:"post",
      data:{task_id:task_id,value:value},
      success:function(result)
      {

      }
    })
  }
  
  if($(e.target).parents(".hidden_tasks_tr").length > 0)
  {
  	$(".hidden_tasks_tr").find("td").css("background","#dcdcdc");
  	$(e.target).parents(".hidden_tasks_tr").find("td").css("background","#2fd9ff");

  	var taskid = $(e.target).parents(".hidden_tasks_tr").attr("data-element");

  	$(".tasks_tr").hide();
    	$(".tasks_tr").next().hide();

    	$("#task_tr_"+taskid).show();
    	$("#task_tr_"+taskid).next().show();
    	var layout = $("#hidden_compressed_layout").val();
	   if(layout == "1")
    {
      $(".open_layout_div").addClass("open_layout_div_change");
      var open_tasks_height = $(".open_layout_div").height();
      var margintop = parseInt(open_tasks_height);
      $(".open_layout_div").css("position","fixed");
      $(".open_layout_div").css("height","312px");
      if(open_tasks_height > 312)
      {
        $(".open_layout_div").css("overflow-y","scroll");
      }
      if(open_tasks_height < 50)
      {
        $(".table_layout").css("margin-top","20px");
      }
        else{
          $(".table_layout").css("margin-top","335px");
        }
    }
    else{
      $(".open_layout_div").removeClass("open_layout_div_change");
      $(".open_layout_div").css("position","unset");
      $(".open_layout_div").css("height","auto");
      $(".open_layout_div").css("overflow-y","unset");
        $(".table_layout").css("margin-top","0px");
    }
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
  if(e.target.id == "open_task")
  {
    if($(e.target).is(":checked"))
    {
      $(".allocate_user_add").val("");
      $(".allocate_user_add").addClass("disable_user");
    }
    else{
      $(".allocate_user_add").val("");
      $(".allocate_user_add").removeClass("disable_user");
    }
  }
  if($(e.target).hasClass('mark_as_complete'))
  {
    var task_id = $(e.target).attr("data-element");
    var nexttask_id = $(e.target).parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
    var prevtask_id = $(e.target).parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
    if($(e.target).hasClass('auto_close_task_complete'))
    {
      $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">This Task is an AUTOCLOSE task, by allocating are you happy that this taks is complete with no further action required by the author?  If you select YES this task will be marked COMPLETE and will not be brought to the attention of the Author, If you select NO the task will be place back in the Authors Open Tasks for review</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:" data-task="'+task_id+'" data-next="'+nexttask_id+'" data-prev="'+prevtask_id+'" class="common_black_button yes_mark_complete">Yes</a><a href="javascript:" class="common_black_button no_mark_complete" data-task="'+task_id+'" data-next="'+nexttask_id+'" data-prev="'+prevtask_id+'">No</a></p>',fixed:true,width:"800px"});
    }
    else{
      $("body").addClass("loading");
      if (typeof nexttask_id !== "undefined") {
        var taskidval = nexttask_id;
      }
      else if (typeof prevtask_id !== "undefined") {
        var taskidval = prevtask_id;
      }
      else{
        var taskidval = '';
      }
      $.ajax({
        url:"<?php echo URL::to('user/taskmanager_mark_complete'); ?>",
        type:"post",
        data:{task_id:task_id,type:"0"},
        success:function(resultval)
        {
          var layout = $("#hidden_compressed_layout").val();
          var view = $(".select_view").val();
          if(layout == "1")
          {
            var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
            var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
            if (typeof nexttask_id !== "undefined") {
              var taskidval = nexttask_id;
            }
            else if (typeof prevtask_id !== "undefined") {
              var taskidval = prevtask_id;
            }
            else{
              var taskidval = '';
            }

            $("#task_tr_"+task_id).next().detach();
            $("#task_tr_"+task_id).detach();
            $("#hidden_tasks_tr_"+task_id).detach();

            $("#task_tr_"+taskidval).show();
            $("#task_tr_"+taskidval).next().show();
            $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#2fd9ff");

            var opentask = $("#open_task_count_val").html();
            var countopen = parseInt(opentask) - 1;
            $("#open_task_count_val").html(countopen);
            $("body").removeClass("loading");
          }
          else{
            setTimeout(function() {
              var user_id = $(".select_user_home").val();
              $.ajax({
                url:"<?php echo URL::to('user/refresh_parktask'); ?>",
                type:"post",
                data:{user_id:user_id},
                dataType:"json",
                success: function(result)
                {
                  $("#task_body_open").html(result['open_tasks']);
                  $("#task_body_layout").html(result['layout']);
                  $(".taskname_sort_val").find("img").detach();
                  var layout = $("#hidden_compressed_layout").val();
                  $(".tasks_tr").hide();
                  $(".tasks_tr").next().hide();
                  $(".hidden_tasks_tr").hide();
                  var view = $(".select_view").val();
                  if(view == "3")
                  {
                    if(layout == "1")
                    {
                      $(".author_tr:first").show();
                      $(".author_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_author_tr").show();
                      $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#2fd9ff");
                    }
                    else{
                      $(".author_tr").show();
                      $(".author_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_author_tr").hide();
                    }

                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  else if(view == "2"){
                    $("#open_task_count").hide();
                    $("#redline_task_count").show();
                    $("#authored_task_count").hide();
                    if(layout == "1")
                    {
                      var i = 1;
                      $(".redline_indication").each(function() {
                        if(i == 1)
                        {
                          if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                          {
                            $(this).parents(".allocated_tr").show();
                            $(this).parents(".allocated_tr").next().show();
                            i++;
                          }
                        }
                      });
                      $(".table_layout").show();
                      $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                      
                      var j = 1;
                      $(".redline_indication_layout").each(function() {
                        if(j == 1)
                        {
                          if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                          {
                            $(this).parents(".hidden_allocated_tr").find("td").css("background","#2fd9ff");
                            j++;
                          }
                        }
                      });
                    }
                    else{
                      $(".redline_indication").parents(".allocated_tr").show();
                      $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }

                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  else if(view == "1"){
                    if(layout == "1")
                    {
                      $(".allocated_tr:first").show();
                      $(".allocated_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_allocated_tr").show();
                      $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#2fd9ff");
                    }
                    else{
                      $(".allocated_tr").show();
                      $(".allocated_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }

                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  $("[data-toggle=popover]").popover({
                      html : true,
                      content: function() {
                        var content = $(this).attr("data-popover-content");
                        return $(content).children(".popover-body").html();
                      },
                      title: function() {
                        var title = $(this).attr("data-popover-content");
                        return $(title).children(".popover-heading").html();
                      }
                  });
                  if(layout == "0")
                  {
                    if(taskidval != "")
                    {
                      // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                    }
                  }
                  else{
                    $("#"+taskidval).show();
                    $("#"+taskidval).next().show();
                    var hidden_tr = taskidval.substr(8);
                    $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#2fd9ff");
                  }
                  if(layout == "1")
                  {
                    $(".open_layout_div").addClass("open_layout_div_change");
                    var open_tasks_height = $(".open_layout_div").height();
                    var margintop = parseInt(open_tasks_height);
                    $(".open_layout_div").css("position","fixed");
                    $(".open_layout_div").css("height","312px");
                    if(open_tasks_height > 312)
                    {
                      $(".open_layout_div").css("overflow-y","scroll");
                    }
                    if(open_tasks_height < 50)
                    {
                      $(".table_layout").css("margin-top","20px");
                    }
                      else{
                        $(".table_layout").css("margin-top","335px");
                      }
                  }
                  else{
                    $(".open_layout_div").removeClass("open_layout_div_change");
                    $(".open_layout_div").css("position","unset");
                    $(".open_layout_div").css("height","auto");
                    $(".open_layout_div").css("overflow-y","unset");
                      $(".table_layout").css("margin-top","0px");
                  }
                  $("body").removeClass("loading");
                }
              })
            },2000);
          }
        }
      });
    }
  }
  if($(e.target).hasClass('yes_mark_complete'))
  {
    var task_id = $(e.target).attr("data-task");
    var nexttask_id = $(e.target).attr("data-next");
    var prevtask_id = $(e.target).attr("data-prev");

    $("body").addClass("loading");
    if (typeof nexttask_id !== "undefined") {
      var taskidval = nexttask_id;
    }
    else if (typeof prevtask_id !== "undefined") {
      var taskidval = prevtask_id;
    }
    else{
      var taskidval = '';
    }
    $.ajax({
      url:"<?php echo URL::to('user/taskmanager_mark_complete'); ?>",
      type:"post",
      data:{task_id:task_id,type:"1"},
      success:function(resultval)
      {
        var layout = $("#hidden_compressed_layout").val();
        var view = $(".select_view").val();
        if(layout == "1")
        {
          var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
          var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
          if (typeof nexttask_id !== "undefined") {
            var taskidval = nexttask_id;
          }
          else if (typeof prevtask_id !== "undefined") {
            var taskidval = prevtask_id;
          }
          else{
            var taskidval = '';
          }

          $("#task_tr_"+task_id).next().detach();
          $("#task_tr_"+task_id).detach();
          $("#hidden_tasks_tr_"+task_id).detach();

          $("#task_tr_"+taskidval).show();
          $("#task_tr_"+taskidval).next().show();
          $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#2fd9ff");

          var opentask = $("#open_task_count_val").html();
          var countopen = parseInt(opentask) - 1;
          $("#open_task_count_val").html(countopen);
          $("body").removeClass("loading");
        }
        else{
          setTimeout(function() {
            var user_id = $(".select_user_home").val();
            $.ajax({
              url:"<?php echo URL::to('user/refresh_parktask'); ?>",
              type:"post",
              data:{user_id:user_id},
              dataType:"json",
              success: function(result)
              {
                $("#task_body_open").html(result['open_tasks']);
                $("#task_body_layout").html(result['layout']);
                $(".taskname_sort_val").find("img").detach();
                var layout = $("#hidden_compressed_layout").val();
                $(".tasks_tr").hide();
                $(".tasks_tr").next().hide();
                $(".hidden_tasks_tr").hide();
                var view = $(".select_view").val();
                if(view == "3")
                {
                  if(layout == "1")
                  {
                    $(".author_tr:first").show();
                    $(".author_tr:first").next().show();
                    $(".table_layout").show();
                    $(".table_layout").find(".hidden_author_tr").show();
                    $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#2fd9ff");
                  }
                  else{
                    $(".author_tr").show();
                    $(".author_tr").next().show();
                    $(".table_layout").hide();
                    $(".table_layout").find(".hidden_author_tr").hide();
                  }

                  var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                  var opentask = $(".hidden_allocated_tr").length;
                  var authored = $(".hidden_author_tr").length;
                  $("#redline_task_count_val").html(redline);
                  $("#open_task_count_val").html(opentask);
                  $("#authored_task_count_val").html(authored);
                }
                else if(view == "2"){
                  $("#open_task_count").hide();
                  $("#redline_task_count").show();
                  $("#authored_task_count").hide();
                  if(layout == "1")
                  {
                    var i = 1;
                    $(".redline_indication").each(function() {
                      if(i == 1)
                      {
                        if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                        {
                          $(this).parents(".allocated_tr").show();
                          $(this).parents(".allocated_tr").next().show();
                          i++;
                        }
                      }
                    });
                    $(".table_layout").show();
                    $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                    
                    var j = 1;
                    $(".redline_indication_layout").each(function() {
                      if(j == 1)
                      {
                        if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                        {
                          $(this).parents(".hidden_allocated_tr").find("td").css("background","#2fd9ff");
                          j++;
                        }
                      }
                    });
                  }
                  else{
                    $(".redline_indication").parents(".allocated_tr").show();
                    $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                    $(".table_layout").hide();
                    $(".table_layout").find(".hidden_allocated_tr").hide();
                  }

                  var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                  var opentask = $(".hidden_allocated_tr").length;
                  var authored = $(".hidden_author_tr").length;
                  $("#redline_task_count_val").html(redline);
                  $("#open_task_count_val").html(opentask);
                  $("#authored_task_count_val").html(authored);
                }
                else if(view == "1"){
                  if(layout == "1")
                  {
                    $(".allocated_tr:first").show();
                    $(".allocated_tr:first").next().show();
                    $(".table_layout").show();
                    $(".table_layout").find(".hidden_allocated_tr").show();
                    $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#2fd9ff");
                  }
                  else{
                    $(".allocated_tr").show();
                    $(".allocated_tr").next().show();
                    $(".table_layout").hide();
                    $(".table_layout").find(".hidden_allocated_tr").hide();
                  }

                  var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                  var opentask = $(".hidden_allocated_tr").length;
                  var authored = $(".hidden_author_tr").length;
                  $("#redline_task_count_val").html(redline);
                  $("#open_task_count_val").html(opentask);
                  $("#authored_task_count_val").html(authored);
                }
                $("[data-toggle=popover]").popover({
                    html : true,
                    content: function() {
                      var content = $(this).attr("data-popover-content");
                      return $(content).children(".popover-body").html();
                    },
                    title: function() {
                      var title = $(this).attr("data-popover-content");
                      return $(title).children(".popover-heading").html();
                    }
                });
                if(layout == "0")
                {
                  if(taskidval != "")
                  {
                    // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                  }
                }
                else{
                  $("#"+taskidval).show();
                  $("#"+taskidval).next().show();
                  var hidden_tr = taskidval.substr(8);
                  $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#2fd9ff");
                }
                if(layout == "1")
                {
                  $(".open_layout_div").addClass("open_layout_div_change");
                  var open_tasks_height = $(".open_layout_div").height();
                  var margintop = parseInt(open_tasks_height);
                  $(".open_layout_div").css("position","fixed");
                  $(".open_layout_div").css("height","312px");
                  if(open_tasks_height > 312)
                  {
                    $(".open_layout_div").css("overflow-y","scroll");
                  }
                  if(open_tasks_height < 50)
                  {
                    $(".table_layout").css("margin-top","20px");
                  }
                    else{
                      $(".table_layout").css("margin-top","335px");
                    }
                }
                else{
                  $(".open_layout_div").removeClass("open_layout_div_change");
                  $(".open_layout_div").css("position","unset");
                  $(".open_layout_div").css("height","auto");
                  $(".open_layout_div").css("overflow-y","unset");
                    $(".table_layout").css("margin-top","0px");
                }
                $("body").removeClass("loading");
              }
            })
          },2000);
        }
        $.colorbox.close();
      }
    });
  }
  if($(e.target).hasClass('no_mark_complete'))
  {
    var task_id = $(e.target).attr("data-task");
    var nexttask_id = $(e.target).attr("data-next");
    var prevtask_id = $(e.target).attr("data-prev");
    
    $("body").addClass("loading");
    if (typeof nexttask_id !== "undefined") {
      var taskidval = nexttask_id;
    }
    else if (typeof prevtask_id !== "undefined") {
      var taskidval = prevtask_id;
    }
    else{
      var taskidval = '';
    }
    $.ajax({
      url:"<?php echo URL::to('user/taskmanager_mark_complete'); ?>",
      type:"post",
      data:{task_id:task_id,type:"0"},
      success:function(resultval)
      {
        var layout = $("#hidden_compressed_layout").val();
        var view = $(".select_view").val();
        if(layout == "1")
        {
          var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
          var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
          if (typeof nexttask_id !== "undefined") {
            var taskidval = nexttask_id;
          }
          else if (typeof prevtask_id !== "undefined") {
            var taskidval = prevtask_id;
          }
          else{
            var taskidval = '';
          }

          $("#task_tr_"+task_id).next().detach();
          $("#task_tr_"+task_id).detach();
          $("#hidden_tasks_tr_"+task_id).detach();

          $("#task_tr_"+taskidval).show();
          $("#task_tr_"+taskidval).next().show();
          $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#2fd9ff");

          var opentask = $("#open_task_count_val").html();
          var countopen = parseInt(opentask) - 1;
          $("#open_task_count_val").html(countopen);
          $("body").removeClass("loading");
        }
        else{
          setTimeout(function() {
            var user_id = $(".select_user_home").val();
            $.ajax({
              url:"<?php echo URL::to('user/refresh_parktask'); ?>",
              type:"post",
              data:{user_id:user_id},
              dataType:"json",
              success: function(result)
              {
                $("#task_body_open").html(result['open_tasks']);
                $("#task_body_layout").html(result['layout']);
                $(".taskname_sort_val").find("img").detach();
                var layout = $("#hidden_compressed_layout").val();
                $(".tasks_tr").hide();
                $(".tasks_tr").next().hide();
                $(".hidden_tasks_tr").hide();
                var view = $(".select_view").val();
                if(view == "3")
                {
                  if(layout == "1")
                  {
                    $(".author_tr:first").show();
                    $(".author_tr:first").next().show();
                    $(".table_layout").show();
                    $(".table_layout").find(".hidden_author_tr").show();
                    $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#2fd9ff");
                  }
                  else{
                    $(".author_tr").show();
                    $(".author_tr").next().show();
                    $(".table_layout").hide();
                    $(".table_layout").find(".hidden_author_tr").hide();
                  }

                  var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                  var opentask = $(".hidden_allocated_tr").length;
                  var authored = $(".hidden_author_tr").length;
                  $("#redline_task_count_val").html(redline);
                  $("#open_task_count_val").html(opentask);
                  $("#authored_task_count_val").html(authored);
                }
                else if(view == "2"){
                  $("#open_task_count").hide();
                  $("#redline_task_count").show();
                  $("#authored_task_count").hide();
                  if(layout == "1")
                  {
                    var i = 1;
                    $(".redline_indication").each(function() {
                      if(i == 1)
                      {
                        if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                        {
                          $(this).parents(".allocated_tr").show();
                          $(this).parents(".allocated_tr").next().show();
                          i++;
                        }
                      }
                    });
                    $(".table_layout").show();
                    $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                    
                    var j = 1;
                    $(".redline_indication_layout").each(function() {
                      if(j == 1)
                      {
                        if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                        {
                          $(this).parents(".hidden_allocated_tr").find("td").css("background","#2fd9ff");
                          j++;
                        }
                      }
                    });
                  }
                  else{
                    $(".redline_indication").parents(".allocated_tr").show();
                    $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                    $(".table_layout").hide();
                    $(".table_layout").find(".hidden_allocated_tr").hide();
                  }

                  var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                  var opentask = $(".hidden_allocated_tr").length;
                  var authored = $(".hidden_author_tr").length;
                  $("#redline_task_count_val").html(redline);
                  $("#open_task_count_val").html(opentask);
                  $("#authored_task_count_val").html(authored);
                }
                else if(view == "1"){
                  if(layout == "1")
                  {
                    $(".allocated_tr:first").show();
                    $(".allocated_tr:first").next().show();
                    $(".table_layout").show();
                    $(".table_layout").find(".hidden_allocated_tr").show();
                    $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#2fd9ff");
                  }
                  else{
                    $(".allocated_tr").show();
                    $(".allocated_tr").next().show();
                    $(".table_layout").hide();
                    $(".table_layout").find(".hidden_allocated_tr").hide();
                  }

                  var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                  var opentask = $(".hidden_allocated_tr").length;
                  var authored = $(".hidden_author_tr").length;
                  $("#redline_task_count_val").html(redline);
                  $("#open_task_count_val").html(opentask);
                  $("#authored_task_count_val").html(authored);
                }
                $("[data-toggle=popover]").popover({
                    html : true,
                    content: function() {
                      var content = $(this).attr("data-popover-content");
                      return $(content).children(".popover-body").html();
                    },
                    title: function() {
                      var title = $(this).attr("data-popover-content");
                      return $(title).children(".popover-heading").html();
                    }
                });
                if(layout == "0")
                {
                  if(taskidval != "")
                  {
                    // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                  }
                }
                else{
                  $("#"+taskidval).show();
                  $("#"+taskidval).next().show();
                  var hidden_tr = taskidval.substr(8);
                  $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#2fd9ff");
                }
                if(layout == "1")
                {
                  $(".open_layout_div").addClass("open_layout_div_change");
                  var open_tasks_height = $(".open_layout_div").height();
                  var margintop = parseInt(open_tasks_height);
                  $(".open_layout_div").css("position","fixed");
                  $(".open_layout_div").css("height","312px");
                  if(open_tasks_height > 312)
                  {
                    $(".open_layout_div").css("overflow-y","scroll");
                  }
                  if(open_tasks_height < 50)
                  {
                    $(".table_layout").css("margin-top","20px");
                  }
                    else{
                      $(".table_layout").css("margin-top","335px");
                    }
                }
                else{
                  $(".open_layout_div").removeClass("open_layout_div_change");
                  $(".open_layout_div").css("position","unset");
                  $(".open_layout_div").css("height","auto");
                  $(".open_layout_div").css("overflow-y","unset");
                    $(".table_layout").css("margin-top","0px");
                }
                $("body").removeClass("loading");
              }
            })
          },2000);
        }
        $.colorbox.close();
      }
    });
  }
  if($(e.target).hasClass('mark_as_complete_author'))
  {
    var r = confirm("You are about to mark this task as Complete are you sure you want to continue?");
    if(r)
    {
      $("body").addClass("loading");
      var task_id = $(e.target).attr("data-element");

      var nexttask_id = $(e.target).parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
      var prevtask_id = $(e.target).parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
      if (typeof nexttask_id !== "undefined") {
        var taskidval = nexttask_id;
      }
      else if (typeof prevtask_id !== "undefined") {
        var taskidval = prevtask_id;
      }
      else{
        var taskidval = '';
      }
      
      $.ajax({
        url:"<?php echo URL::to('user/taskmanager_mark_complete'); ?>",
        type:"post",
        data:{task_id:task_id},
        success:function(resultval)
        {
          var layout = $("#hidden_compressed_layout").val();
          if(layout == "1")
          {
            var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
            var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
            if (typeof nexttask_id !== "undefined") {
              var taskidval = nexttask_id;
            }
            else if (typeof prevtask_id !== "undefined") {
              var taskidval = prevtask_id;
            }
            else{
              var taskidval = '';
            }

            $("#task_tr_"+task_id).next().detach();
            $("#task_tr_"+task_id).detach();
            $("#hidden_tasks_tr_"+task_id).detach();

            $("#task_tr_"+taskidval).show();
            $("#task_tr_"+taskidval).next().show();
            $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#2fd9ff");

            var opentask = $("#authored_task_count_val").html();
            var countopen = parseInt(opentask) - 1;
            $("#authored_task_count_val").html(countopen);
            $("body").removeClass("loading");
          }
          else{
            setTimeout(function() {
              var user_id = $(".select_user_home").val();
              $.ajax({
                url:"<?php echo URL::to('user/refresh_parktask'); ?>",
                type:"post",
                data:{user_id:user_id},
                dataType:"json",
                success: function(result)
                {
                  $("#task_body_open").html(result['open_tasks']);
                  $("#task_body_layout").html(result['layout']);
                  $(".taskname_sort_val").find("img").detach();
                  var layout = $("#hidden_compressed_layout").val();
  			          $(".tasks_tr").hide();
          				$(".tasks_tr").next().hide();
          				$(".hidden_tasks_tr").hide();
                  var view = $(".select_view").val();
        			    if(view == "3")
        			    {
        			      if(layout == "1")
        			   	  {
        			   	  	$(".author_tr:first").show();
        			      	$(".author_tr:first").next().show();
        			      	$(".table_layout").show();
        			      	$(".table_layout").find(".hidden_author_tr").show();
        			      	$(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#2fd9ff");
        			   	  }
        			   	  else{
        			   	  	$(".author_tr").show();
        			      	$(".author_tr").next().show();
        			      	$(".table_layout").hide();
        			      	$(".table_layout").find(".hidden_author_tr").hide();
        			   	  }

                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;

                    var parktask = $("#park_task_count_val").html();
                    var countpark = parseInt(parktask) + 1;
                    $("#park_task_count_val").html(countpark);

                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
        			    }
                  else if(view == "2"){
                    $("#open_task_count").hide();
                    $("#redline_task_count").show();
                    $("#authored_task_count").hide();
                    if(layout == "1")
                    {
                      var i = 1;
                      $(".redline_indication").each(function() {
                        if(i == 1)
                        {
                          if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                          {
                            $(this).parents(".allocated_tr").show();
                            $(this).parents(".allocated_tr").next().show();
                            i++;
                          }
                        }
                      });
                      $(".table_layout").show();
                      $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                      
                      var j = 1;
                      $(".redline_indication_layout").each(function() {
                        if(j == 1)
                        {
                          if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                          {
                            $(this).parents(".hidden_allocated_tr").find("td").css("background","#2fd9ff");
                            j++;
                          }
                        }
                      });
                    }
                    else{
                      $(".redline_indication").parents(".allocated_tr").show();
                      $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }

                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;

                    var parktask = $("#park_task_count_val").html();
                    var countpark = parseInt(parktask) + 1;
                    $("#park_task_count_val").html(countpark);

                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
        			    else if(view == "1"){
        			      if(layout == "1")
        			   	  {
        			   	  	$(".allocated_tr:first").show();
        			      	$(".allocated_tr:first").next().show();
        			      	$(".table_layout").show();
        			      	$(".table_layout").find(".hidden_allocated_tr").show();
        			      	$(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#2fd9ff");
        			   	  }
        			   	  else{
        			   	  	$(".allocated_tr").show();
        			      	$(".allocated_tr").next().show();
        			      	$(".table_layout").hide();
        			      	$(".table_layout").find(".hidden_allocated_tr").hide();
        			   	  }
                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;

                    var parktask = $("#park_task_count_val").html();
                    var countpark = parseInt(parktask) + 1;
                    $("#park_task_count_val").html(countpark);

                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
        			    }

                  $("[data-toggle=popover]").popover({
                      html : true,
                      content: function() {
                        var content = $(this).attr("data-popover-content");
                        return $(content).children(".popover-body").html();
                      },
                      title: function() {
                        var title = $(this).attr("data-popover-content");
                        return $(title).children(".popover-heading").html();
                      }
                  });
                  if(layout == "0")
        			   	{
        			   		if(taskidval != "")
  	                {
  	                  // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
  	                }
        			   	}
                  else{
                  	$("#"+taskidval).show();
      			      	$("#"+taskidval).next().show();
      			      	var hidden_tr = taskidval.substr(8);
                		$("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#2fd9ff");
  			           }

        			    if(layout == "1")
                  {
                    $(".open_layout_div").addClass("open_layout_div_change");
                    var open_tasks_height = $(".open_layout_div").height();
                    var margintop = parseInt(open_tasks_height);
                    $(".open_layout_div").css("position","fixed");
                    $(".open_layout_div").css("height","312px");
                    if(open_tasks_height > 312)
                    {
                      $(".open_layout_div").css("overflow-y","scroll");
                    }
                    if(open_tasks_height < 50)
                    {
                      $(".table_layout").css("margin-top","20px");
                    }
                      else{
                        $(".table_layout").css("margin-top","335px");
                      }
                  }
                  else{
                    $(".open_layout_div").removeClass("open_layout_div_change");
                    $(".open_layout_div").css("position","unset");
                    $(".open_layout_div").css("height","auto");
                    $(".open_layout_div").css("overflow-y","unset");
                      $(".table_layout").css("margin-top","0px");
                  }
  			           $("body").removeClass("loading");
                }
              })
            },2000);
          }
        }
      })
    }
  }
  if($(e.target).hasClass('mark_as_incomplete'))
  {
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/taskmanager_mark_incomplete'); ?>",
      type:"post",
      data:{task_id:task_id},
      success:function(result)
      {
        window.location.replace("<?php echo URL::to('user/task_manager?tr_task_id='); ?>"+task_id);
      }
    })
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
  if($(e.target).hasClass('refresh_task'))
  {
    $("body").addClass("loading");
    setTimeout(function() {
      var user_id = $(".select_user_home").val();
      if(user_id == "")
      {
        alert("Please Select the user and then click on the refresh button.");
      }
      else{
        $.ajax({
          url:"<?php echo URL::to('user/refresh_parktask'); ?>",
          type:"post",
          data:{user_id:user_id},
          dataType:"json",
          success: function(result)
          {
            $("#task_body_open").html(result['open_tasks']);
            $("#task_body_layout").html(result['layout']);
            $(".taskname_sort_val").find("img").detach();
            var layout = $("#hidden_compressed_layout").val();
			    $(".tasks_tr").hide();
				$(".tasks_tr").next().hide();
				$(".hidden_tasks_tr").hide();
          var view = $(".select_view").val();
			    if(view == "3")
			    {
			      if(layout == "1")
			   	  {
			   	  	$(".author_tr:first").show();
			      	$(".author_tr:first").next().show();
			      	$(".table_layout").show();
			      	$(".table_layout").find(".hidden_author_tr").show();
			      	$(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#2fd9ff");
			   	  }
			   	  else{
			   	  	$(".author_tr").show();
			      	$(".author_tr").next().show();
			      	$(".table_layout").hide();
			      	$(".table_layout").find(".hidden_author_tr").hide();
			   	  }
            var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
            var opentask = $(".hidden_allocated_tr").length;
            var authored = $(".hidden_author_tr").length;

            var parktask = $("#park_task_count_val").html();
                    var countpark = parseInt(parktask) + 1;
                    $("#park_task_count_val").html(countpark);

            $("#redline_task_count_val").html(redline);
            $("#open_task_count_val").html(opentask);
            $("#authored_task_count_val").html(authored);
			    }
          else if(view == "2"){
            $("#open_task_count").hide();
            $("#redline_task_count").show();
            $("#authored_task_count").hide();
            if(layout == "1")
            {
              var i = 1;
              $(".redline_indication").each(function() {
                if(i == 1)
                {
                  if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                  {
                    $(this).parents(".allocated_tr").show();
                    $(this).parents(".allocated_tr").next().show();
                    i++;
                  }
                }
              });
              $(".table_layout").show();
              $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
              
              var j = 1;
              $(".redline_indication_layout").each(function() {
                if(j == 1)
                {
                  if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                  {
                    $(this).parents(".hidden_allocated_tr").find("td").css("background","#2fd9ff");
                    j++;
                  }
                }
              });
            }
            else{
              $(".redline_indication").parents(".allocated_tr").show();
              $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
              $(".table_layout").hide();
              $(".table_layout").find(".hidden_allocated_tr").hide();
            }

            var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
            var opentask = $(".hidden_allocated_tr").length;
            var authored = $(".hidden_author_tr").length;

            var parktask = $("#park_task_count_val").html();
                    var countpark = parseInt(parktask) + 1;
                    $("#park_task_count_val").html(countpark);

            $("#redline_task_count_val").html(redline);
            $("#open_task_count_val").html(opentask);
            $("#authored_task_count_val").html(authored);
          }
			    else if(view == "1"){
			      if(layout == "1")
			   	  {
			   	  	$(".allocated_tr:first").show();
			      	$(".allocated_tr:first").next().show();
			      	$(".table_layout").show();
			      	$(".table_layout").find(".hidden_allocated_tr").show();
			      	$(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#2fd9ff");
			   	  }
			   	  else{
			   	  	$(".allocated_tr").show();
			      	$(".allocated_tr").next().show();
			      	$(".table_layout").hide();
			      	$(".table_layout").find(".hidden_allocated_tr").hide();
			   	  }
            var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
            var opentask = $(".hidden_allocated_tr").length;
            var authored = $(".hidden_author_tr").length;

            var parktask = $("#park_task_count_val").html();
                    var countpark = parseInt(parktask) + 1;
                    $("#park_task_count_val").html(countpark);
            $("#redline_task_count_val").html(redline);
            $("#open_task_count_val").html(opentask);
            $("#authored_task_count_val").html(authored);
			    }

			    if(layout == "1")
          {
            $(".open_layout_div").addClass("open_layout_div_change");
            var open_tasks_height = $(".open_layout_div").height();
            var margintop = parseInt(open_tasks_height);
            $(".open_layout_div").css("position","fixed");
            $(".open_layout_div").css("height","312px");
            if(open_tasks_height > 312)
            {
              $(".open_layout_div").css("overflow-y","scroll");
            }
            if(open_tasks_height < 50)
            {
              $(".table_layout").css("margin-top","20px");
            }
              else{
                $(".table_layout").css("margin-top","335px");
              }
          }
          else{
            $(".open_layout_div").removeClass("open_layout_div_change");
            $(".open_layout_div").css("position","unset");
            $(".open_layout_div").css("height","auto");
            $(".open_layout_div").css("overflow-y","unset");
              $(".table_layout").css("margin-top","0px");
          }

            $("[data-toggle=popover]").popover({
                html : true,
                content: function() {
                  var content = $(this).attr("data-popover-content");
                  return $(content).children(".popover-body").html();
                },
                title: function() {
                  var title = $(this).attr("data-popover-content");
                  return $(title).children(".popover-heading").html();
                }
            });
            $("body").removeClass("loading");
          }
        })
      }
    },2000);
  }
  if($(e.target).hasClass('copy_task'))
  {
    $("#hidden_copied_files").val("");
    $("#hidden_copied_notes").val("");
    $("#hidden_copied_infiles").val("");
    var task_id = $(e.target).attr("data-element");
    $(".hide_taskmanager_files").hide();
    $(".question_modal").modal("show");
    $(".hidden_task_id_copy_task").val(task_id);

    $("#copy_task_specifics_no").prop("checked",true);
    $("#copy_task_files_no").prop("checked",true);
  }
  if(e.target.id == "question_submit")
  {
    $(".create_new_model").find(".job_title").html("Copy Task");
    var task_specifics = $(".copy_task_specifics:checked").val();
    var task_files = $(".copy_task_files:checked").val();
    var task_id = $(".hidden_task_id_copy_task").val();
    $(".question_modal").modal("hide");
    $.ajax({
      url:"<?php echo URL::to('user/copy_task_details'); ?>",
      type:"post",
      data:{task_id:task_id,task_specifics:task_specifics,task_files:task_files},
      dataType:"json",
      success: function(result)
      {
        $(".create_new_model").modal("show");
        if (CKEDITOR.instances.editor_2) CKEDITOR.instances.editor_2.destroy();
        
        $(".select_user_author").val("");
        $(".create_new_model").modal("show");
        $(".created_date").datetimepicker({
           defaultDate: fullDate,       
           format: 'L',
           format: 'DD-MMM-YYYY',
        });
        $(".due_date").datetimepicker({
           defaultDate: fullDate,
           format: 'L',
           format: 'DD-MMM-YYYY',
        });
        $(".created_date").val(result['creation_date']);
        $(".due_date").val("");
        $("#action_type").val("2");
        $(".allocate_user_add").val(result['allocated_to']);

        if(result['internal'] == "1")
        {
          $(".task-choose_internal").html(result['task_name']);
          $("#idtask").val(result['task_type']);
          $(".internal_tasks_group").show();
          $("#internal_checkbox").prop("checked",true);
          $(".client_group").hide();
          $(".client_search_class").prop("required",false);
          $(".client_search_class").val("");
          $("#client_search").val("");
        }
        else{
          $(".task-choose_internal").html("Select Task");
          $("#idtask").val("");
          $(".internal_tasks_group").hide();
          $("#internal_checkbox").prop("checked",false);

          $(".client_group").show();
          $(".client_search_class").prop("required",true);
          $(".client_search_class").val(result['client_name']);
          $("#client_search").val(result['client_id']);
        }

        $(".subject_class").val(result['subject']);

        $("#hidden_specific_type").val(result['task_specifics_type']);
        $("#hidden_attachment_type").val(result['task_attachment_type']);

        if(result['task_specifics_type'] == "2")
        {
          $(".task_specifics_add").show();
          
          CKEDITOR.replace('editor_2',
           {
            height: '150px',
            enterMode: CKEDITOR.ENTER_BR, 
            shiftEnterMode: CKEDITOR.ENTER_P,
            autoParagraph: false,
            entities: false,
            contentsCss: "body {font-size: 16px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif}",
           });
          CKEDITOR.instances['editor_2'].setData(result['task_specifics']);
          $(".task_specifics_copy").hide();
          $(".task_specifics_copy_val").html("");
          $("#hidden_task_specifics").val(result['task_specifics']);
        }
        else{
          $(".task_specifics_add").hide();
          $(".task_specifics_copy").show();
          
          $(".task_specifics").val("");
          $(".task_specifics_copy_val").html(result['task_specifics']);
          $("#hidden_task_specifics").val(result['task_specifics']);
        }
        
        if(result['task_attachment_type'] == "2")
        {
          $(".retreived_files_div").hide();
          $(".retreived_files_div").html("");
        }
        else{
          $(".retreived_files_div").show();
          $(".retreived_files_div").html(result['attached_files']);
        }
        $(".specific_recurring").val("");        
        
        $(".infiles_link").show();
        $("#attachments_text").hide();
        $("#hidden_infiles_id").val("");
        $("#add_infiles_attachments_div").html("");
        $("#attachments_infiles").hide();

        $(".auto_close_task").prop("checked",false);
        $(".accept_recurring").prop("checked",true);
        $(".accept_recurring_div").show();
        $("#recurring_checkbox1").prop("checked",true);

        $("#open_task").prop("checked",false);
        $(".allocate_user_add").removeClass("disable_user");

        $.ajax({
          url:"<?php echo URL::to('user/clear_session_task_attachments'); ?>",
          type:"post",
          data:{fileid:"0"},
          success: function(result)
          {
            $("#add_notepad_attachments_div").html('');
            $("#add_attachments_div").html('');
            $("body").removeClass("loading");
          }
        })
      }
    })
    
  }
  if($(e.target).hasClass('export_pdf_history'))
  {
    $("body").addClass("loading");
    var task_id = $("#hidden_task_id_history").val();
    $.ajax({
      url:"<?php echo URL::to('user/download_pdf_history'); ?>",
      type:"post",
      data:{task_id:task_id},
      success:function(result)
      {
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('export_csv_history'))
  {
    $("body").addClass("loading");
    var task_id = $("#hidden_task_id_history").val();
    $.ajax({
      url:"<?php echo URL::to('user/download_csv_history'); ?>",
      type:"post",
      data:{task_id:task_id},
      success:function(result)
      {
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    })
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
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">This Task is an AUTOCLOSE task, by allocating are you happy that this taks is complete with no further action required by the author?  If you select YES this task will be marked COMPLETE and will not be brought to the attention of the Author, If you select NO the task will be place back in the Authors Open Tasks for review</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:" data-task="'+task_id+'" class="common_black_button yes_allocate_back">Yes</a><a href="javascript:" class="common_black_button no_allocate_back" data-task="'+task_id+'">No</a></p>',fixed:true,width:"800px"});
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
                  $("body").removeClass("loading");
                }
                else{
                  $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
                  $("#editor_1").val("");
                  CKEDITOR.instances['editor_1'].setData("");
                  $(".task_specifics_modal").modal("hide");
                  $.ajax({
                    url:"<?php echo URL::to('user/taskmanager_change_allocations'); ?>",
                    type:"post",
                    data:{task_id:task_id,new_allocation:new_allocation,type:"0"},
                    dataType:"json",
                    success:function(result)
                    {
                      var htmlval = $("#allocation_history_div_"+task_id).html();
                      $("#allocation_history_div_"+task_id).html(result['pval']+htmlval);
                      var htmlval2 = $("#history_body").find("tbody").html();
                      $("#history_body").find("tbody").html(result['trval']+htmlval2);
                      $(".edit_allocate_user_"+task_id).attr("data-allocated",new_allocation);
                      $("#allocated_to_name_"+task_id).html(result['to']);
                      $("#hidden_tasks_tr_"+task_id).find(".allocated_sort_val").html(result['to']);
                      var count = 1;
                      $("#allocation_history_div_"+task_id).find("p").each(function() {
                        if(count > 5)
                        {
                          $(this).detach();
                        }
                        count++;
                      })
                      $(".allocation_modal").modal("hide");
                      var layout = $("#hidden_compressed_layout").val();
                      if(layout == "1")
                      {
                        if($("#task_tr_"+task_id).hasClass('author_tr'))
                        {
                        }
                        else{
                          var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
                          var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
                          if (typeof nexttask_id !== "undefined") {
                            var taskidval = nexttask_id;
                          }
                          else if (typeof prevtask_id !== "undefined") {
                            var taskidval = prevtask_id;
                          }
                          else{
                            var taskidval = '';
                          }

                          $("#task_tr_"+task_id).next().detach();
                          $("#task_tr_"+task_id).detach();
                          $("#hidden_tasks_tr_"+task_id).detach();

                          $("#task_tr_"+taskidval).show();
                          $("#task_tr_"+taskidval).next().show();
                          $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#2fd9ff");

                          var opentask = $("#open_task_count_val").html();
                          var countopen = parseInt(opentask) - 1;
                          $("#open_task_count_val").html(countopen);
                        }
                        $("body").removeClass("loading");
                      }
                      else{
                        var user_id = $(".select_user_home").val();
                        $.ajax({
                          url:"<?php echo URL::to('user/refresh_parktask'); ?>",
                          type:"post",
                          data:{user_id:user_id},
                              dataType:"json",
                          success: function(result)
                          {
                            $("#task_body_open").html(result['open_tasks']);
                            $("#task_body_layout").html(result['layout']);
                            $(".taskname_sort_val").find("img").detach();
                            var layout = $("#hidden_compressed_layout").val();
                            var view = $(".select_view").val();
                            $(".tasks_tr").hide();
                            $(".tasks_tr").next().hide();
                            $(".hidden_tasks_tr").hide();
                            if(view == "3")
                            {
                              if(layout == "1")
                              {
                                $(".author_tr:first").show();
                                $(".author_tr:first").next().show();
                                $(".table_layout").show();
                                $(".table_layout").find(".hidden_author_tr").show();
                                $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#2fd9ff");
                              }
                              else{
                                $(".author_tr").show();
                                $(".author_tr").next().show();
                                $(".table_layout").hide();
                                $(".table_layout").find(".hidden_author_tr").hide();
                              }
                              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                              var opentask = $(".hidden_allocated_tr").length;
                              var authored = $(".hidden_author_tr").length;
                              $("#redline_task_count_val").html(redline);
                              $("#open_task_count_val").html(opentask);
                              $("#authored_task_count_val").html(authored);
                            }
                            else if(view == "2"){
                              $("#open_task_count").hide();
                              $("#redline_task_count").show();
                              $("#authored_task_count").hide();
                              if(layout == "1")
                              {
                                var i = 1;
                                $(".redline_indication").each(function() {
                                  if(i == 1)
                                  {
                                    if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                                    {
                                      $(this).parents(".allocated_tr").show();
                                      $(this).parents(".allocated_tr").next().show();
                                      i++;
                                    }
                                  }
                                });
                                $(".table_layout").show();
                                $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                                
                                var j = 1;
                                $(".redline_indication_layout").each(function() {
                                  if(j == 1)
                                  {
                                    if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                                    {
                                      $(this).parents(".hidden_allocated_tr").find("td").css("background","#2fd9ff");
                                      j++;
                                    }
                                  }
                                });
                              }
                              else{
                                $(".redline_indication").parents(".allocated_tr").show();
                                $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                                $(".table_layout").hide();
                                $(".table_layout").find(".hidden_allocated_tr").hide();
                              }

                              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                              var opentask = $(".hidden_allocated_tr").length;
                              var authored = $(".hidden_author_tr").length;
                              $("#redline_task_count_val").html(redline);
                              $("#open_task_count_val").html(opentask);
                              $("#authored_task_count_val").html(authored);
                            }
                            else if(view == "1"){
                              if(layout == "1")
                              {
                                $(".allocated_tr:first").show();
                                $(".allocated_tr:first").next().show();
                                $(".table_layout").show();
                                $(".table_layout").find(".hidden_allocated_tr").show();
                                $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#2fd9ff");
                              }
                              else{
                                $(".allocated_tr").show();
                                $(".allocated_tr").next().show();
                                $(".table_layout").hide();
                                $(".table_layout").find(".hidden_allocated_tr").hide();
                              }
                              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                              var opentask = $(".hidden_allocated_tr").length;
                              var authored = $(".hidden_author_tr").length;
                              $("#redline_task_count_val").html(redline);
                              $("#open_task_count_val").html(opentask);
                              $("#authored_task_count_val").html(authored);
                            }
                            $("[data-toggle=popover]").popover({
                                html : true,
                                content: function() {
                                  var content = $(this).attr("data-popover-content");
                                  return $(content).children(".popover-body").html();
                                },
                                title: function() {
                                  var title = $(this).attr("data-popover-content");
                                  return $(title).children(".popover-heading").html();
                                }
                            });

                            if(layout == "0")
                            {
                                if(taskidval != "")
                                {
                                  //$(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                                }
                            }
                            else{
                              $("#"+taskidval).show();
                              $("#"+taskidval).next().show();
                            
                              var hidden_tr = taskidval.substr(8);
                              $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#2fd9ff");
                            }

                            if(layout == "1")
                            {
                              $(".open_layout_div").addClass("open_layout_div_change");
                              var open_tasks_height = $(".open_layout_div").height();
                              var margintop = parseInt(open_tasks_height);
                              $(".open_layout_div").css("position","fixed");
                              $(".open_layout_div").css("height","312px");
                              if(open_tasks_height > 312)
                              {
                                $(".open_layout_div").css("overflow-y","scroll");
                              }
                              if(open_tasks_height < 50)
                              {
                                $(".table_layout").css("margin-top","20px");
                              }
                                else{
                                  $(".table_layout").css("margin-top","335px");
                                }
                            }
                            else{
                              $(".open_layout_div").removeClass("open_layout_div_change");
                              $(".open_layout_div").css("position","unset");
                              $(".open_layout_div").css("height","auto");
                              $(".open_layout_div").css("overflow-y","unset");
                                $(".table_layout").css("margin-top","0px");
                            }
                            $("body").removeClass("loading");
                          }
                        })
                      }
                    }
                  })
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
              $("body").removeClass("loading");
            }
            else{
              $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
              $("#editor_1").val("");
              CKEDITOR.instances['editor_1'].setData("");
              $(".task_specifics_modal").modal("hide");
              $.ajax({
                url:"<?php echo URL::to('user/taskmanager_change_allocations'); ?>",
                type:"post",
                data:{task_id:task_id,new_allocation:new_allocation,type:"1"},
                dataType:"json",
                success:function(result)
                {
                  var htmlval = $("#allocation_history_div_"+task_id).html();
                  $("#allocation_history_div_"+task_id).html(result['pval']+htmlval);
                  var htmlval2 = $("#history_body").find("tbody").html();
                  $("#history_body").find("tbody").html(result['trval']+htmlval2);
                  $(".edit_allocate_user_"+task_id).attr("data-allocated",new_allocation);
                  $("#allocated_to_name_"+task_id).html(result['to']);
                  $("#hidden_tasks_tr_"+task_id).find(".allocated_sort_val").html(result['to']);
                  var count = 1;
                  $("#allocation_history_div_"+task_id).find("p").each(function() {
                    if(count > 5)
                    {
                      $(this).detach();
                    }
                    count++;
                  })
                  $(".allocation_modal").modal("hide");
                  var layout = $("#hidden_compressed_layout").val();
                  if(layout == "1")
                  {
                    if($("#task_tr_"+task_id).hasClass('author_tr'))
                    {
                    }
                    else{
                      var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
                      var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
                      if (typeof nexttask_id !== "undefined") {
                        var taskidval = nexttask_id;
                      }
                      else if (typeof prevtask_id !== "undefined") {
                        var taskidval = prevtask_id;
                      }
                      else{
                        var taskidval = '';
                      }

                      $("#task_tr_"+task_id).next().detach();
                      $("#task_tr_"+task_id).detach();
                      $("#hidden_tasks_tr_"+task_id).detach();

                      $("#task_tr_"+taskidval).show();
                      $("#task_tr_"+taskidval).next().show();
                      $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#2fd9ff");

                      var opentask = $("#open_task_count_val").html();
                      var countopen = parseInt(opentask) - 1;
                      $("#open_task_count_val").html(countopen);
                    }
                    $("body").removeClass("loading");
                  }
                  else{
                    var user_id = $(".select_user_home").val();
                    $.ajax({
                      url:"<?php echo URL::to('user/refresh_parktask'); ?>",
                      type:"post",
                      data:{user_id:user_id},
                          dataType:"json",
                      success: function(result)
                      {
                        $("#task_body_open").html(result['open_tasks']);
                        $("#task_body_layout").html(result['layout']);
                        $(".taskname_sort_val").find("img").detach();
                        var layout = $("#hidden_compressed_layout").val();
                        var view = $(".select_view").val();
                        $(".tasks_tr").hide();
                        $(".tasks_tr").next().hide();
                        $(".hidden_tasks_tr").hide();
                        if(view == "3")
                        {
                          if(layout == "1")
                          {
                            $(".author_tr:first").show();
                            $(".author_tr:first").next().show();
                            $(".table_layout").show();
                            $(".table_layout").find(".hidden_author_tr").show();
                            $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#2fd9ff");
                          }
                          else{
                            $(".author_tr").show();
                            $(".author_tr").next().show();
                            $(".table_layout").hide();
                            $(".table_layout").find(".hidden_author_tr").hide();
                          }
                          var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                          var opentask = $(".hidden_allocated_tr").length;
                          var authored = $(".hidden_author_tr").length;
                          $("#redline_task_count_val").html(redline);
                          $("#open_task_count_val").html(opentask);
                          $("#authored_task_count_val").html(authored);
                        }
                        else if(view == "2"){
                          $("#open_task_count").hide();
                          $("#redline_task_count").show();
                          $("#authored_task_count").hide();
                          if(layout == "1")
                          {
                            var i = 1;
                            $(".redline_indication").each(function() {
                              if(i == 1)
                              {
                                if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                                {
                                  $(this).parents(".allocated_tr").show();
                                  $(this).parents(".allocated_tr").next().show();
                                  i++;
                                }
                              }
                            });
                            $(".table_layout").show();
                            $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                            
                            var j = 1;
                            $(".redline_indication_layout").each(function() {
                              if(j == 1)
                              {
                                if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                                {
                                  $(this).parents(".hidden_allocated_tr").find("td").css("background","#2fd9ff");
                                  j++;
                                }
                              }
                            });
                          }
                          else{
                            $(".redline_indication").parents(".allocated_tr").show();
                            $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                            $(".table_layout").hide();
                            $(".table_layout").find(".hidden_allocated_tr").hide();
                          }

                          var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                          var opentask = $(".hidden_allocated_tr").length;
                          var authored = $(".hidden_author_tr").length;
                          $("#redline_task_count_val").html(redline);
                          $("#open_task_count_val").html(opentask);
                          $("#authored_task_count_val").html(authored);
                        }
                        else if(view == "1"){
                          if(layout == "1")
                          {
                            $(".allocated_tr:first").show();
                            $(".allocated_tr:first").next().show();
                            $(".table_layout").show();
                            $(".table_layout").find(".hidden_allocated_tr").show();
                            $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#2fd9ff");
                          }
                          else{
                            $(".allocated_tr").show();
                            $(".allocated_tr").next().show();
                            $(".table_layout").hide();
                            $(".table_layout").find(".hidden_allocated_tr").hide();
                          }
                          var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                          var opentask = $(".hidden_allocated_tr").length;
                          var authored = $(".hidden_author_tr").length;
                          $("#redline_task_count_val").html(redline);
                          $("#open_task_count_val").html(opentask);
                          $("#authored_task_count_val").html(authored);
                        }
                        $("[data-toggle=popover]").popover({
                            html : true,
                            content: function() {
                              var content = $(this).attr("data-popover-content");
                              return $(content).children(".popover-body").html();
                            },
                            title: function() {
                              var title = $(this).attr("data-popover-content");
                              return $(title).children(".popover-heading").html();
                            }
                        });

                        if(layout == "0")
                        {
                            if(taskidval != "")
                            {
                              //$(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                            }
                        }
                        else{
                          $("#"+taskidval).show();
                          $("#"+taskidval).next().show();
                        
                          var hidden_tr = taskidval.substr(8);
                          $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#2fd9ff");
                        }

                        if(layout == "1")
                        {
                          $(".open_layout_div").addClass("open_layout_div_change");
                          var open_tasks_height = $(".open_layout_div").height();
                          var margintop = parseInt(open_tasks_height);
                          $(".open_layout_div").css("position","fixed");
                          $(".open_layout_div").css("height","312px");
                          if(open_tasks_height > 312)
                          {
                            $(".open_layout_div").css("overflow-y","scroll");
                          }
                          if(open_tasks_height < 50)
                          {
                            $(".table_layout").css("margin-top","20px");
                          }
                            else{
                              $(".table_layout").css("margin-top","335px");
                            }
                        }
                        else{
                          $(".open_layout_div").removeClass("open_layout_div_change");
                          $(".open_layout_div").css("position","unset");
                          $(".open_layout_div").css("height","auto");
                          $(".open_layout_div").css("overflow-y","unset");
                            $(".table_layout").css("margin-top","0px");
                        }
                        $("body").removeClass("loading");
                      }
                    })
                  }
                  $.colorbox.close();
                }
              })
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
              $("body").removeClass("loading");
            }
            else{
              $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
              $("#editor_1").val("");
              CKEDITOR.instances['editor_1'].setData("");
              $(".task_specifics_modal").modal("hide");
              $.ajax({
                url:"<?php echo URL::to('user/taskmanager_change_allocations'); ?>",
                type:"post",
                data:{task_id:task_id,new_allocation:new_allocation,type:"0"},
                dataType:"json",
                success:function(result)
                {
                  var htmlval = $("#allocation_history_div_"+task_id).html();
                  $("#allocation_history_div_"+task_id).html(result['pval']+htmlval);
                  var htmlval2 = $("#history_body").find("tbody").html();
                  $("#history_body").find("tbody").html(result['trval']+htmlval2);
                  $(".edit_allocate_user_"+task_id).attr("data-allocated",new_allocation);
                  $("#allocated_to_name_"+task_id).html(result['to']);
                  $("#hidden_tasks_tr_"+task_id).find(".allocated_sort_val").html(result['to']);
                  var count = 1;
                  $("#allocation_history_div_"+task_id).find("p").each(function() {
                    if(count > 5)
                    {
                      $(this).detach();
                    }
                    count++;
                  })
                  $(".allocation_modal").modal("hide");
                  var layout = $("#hidden_compressed_layout").val();
                  if(layout == "1")
                  {
                    if($("#task_tr_"+task_id).hasClass('author_tr'))
                    {
                    }
                    else{
                      var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
                      var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
                      if (typeof nexttask_id !== "undefined") {
                        var taskidval = nexttask_id;
                      }
                      else if (typeof prevtask_id !== "undefined") {
                        var taskidval = prevtask_id;
                      }
                      else{
                        var taskidval = '';
                      }

                      $("#task_tr_"+task_id).next().detach();
                      $("#task_tr_"+task_id).detach();
                      $("#hidden_tasks_tr_"+task_id).detach();

                      $("#task_tr_"+taskidval).show();
                      $("#task_tr_"+taskidval).next().show();
                      $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#2fd9ff");

                      var opentask = $("#open_task_count_val").html();
                      var countopen = parseInt(opentask) - 1;
                      $("#open_task_count_val").html(countopen);
                    }
                    $("body").removeClass("loading");
                  }
                  else{
                    var user_id = $(".select_user_home").val();
                    $.ajax({
                      url:"<?php echo URL::to('user/refresh_parktask'); ?>",
                      type:"post",
                      data:{user_id:user_id},
                          dataType:"json",
                      success: function(result)
                      {
                        $("#task_body_open").html(result['open_tasks']);
                        $("#task_body_layout").html(result['layout']);
                        $(".taskname_sort_val").find("img").detach();
                        var layout = $("#hidden_compressed_layout").val();
                        var view = $(".select_view").val();
                        $(".tasks_tr").hide();
                        $(".tasks_tr").next().hide();
                        $(".hidden_tasks_tr").hide();
                        if(view == "3")
                        {
                          if(layout == "1")
                          {
                            $(".author_tr:first").show();
                            $(".author_tr:first").next().show();
                            $(".table_layout").show();
                            $(".table_layout").find(".hidden_author_tr").show();
                            $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#2fd9ff");
                          }
                          else{
                            $(".author_tr").show();
                            $(".author_tr").next().show();
                            $(".table_layout").hide();
                            $(".table_layout").find(".hidden_author_tr").hide();
                          }
                          var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                          var opentask = $(".hidden_allocated_tr").length;
                          var authored = $(".hidden_author_tr").length;
                          $("#redline_task_count_val").html(redline);
                          $("#open_task_count_val").html(opentask);
                          $("#authored_task_count_val").html(authored);
                        }
                        else if(view == "2"){
                          $("#open_task_count").hide();
                          $("#redline_task_count").show();
                          $("#authored_task_count").hide();
                          if(layout == "1")
                          {
                            var i = 1;
                            $(".redline_indication").each(function() {
                              if(i == 1)
                              {
                                if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                                {
                                  $(this).parents(".allocated_tr").show();
                                  $(this).parents(".allocated_tr").next().show();
                                  i++;
                                }
                              }
                            });
                            $(".table_layout").show();
                            $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                            
                            var j = 1;
                            $(".redline_indication_layout").each(function() {
                              if(j == 1)
                              {
                                if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                                {
                                  $(this).parents(".hidden_allocated_tr").find("td").css("background","#2fd9ff");
                                  j++;
                                }
                              }
                            });
                          }
                          else{
                            $(".redline_indication").parents(".allocated_tr").show();
                            $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                            $(".table_layout").hide();
                            $(".table_layout").find(".hidden_allocated_tr").hide();
                          }

                          var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                          var opentask = $(".hidden_allocated_tr").length;
                          var authored = $(".hidden_author_tr").length;
                          $("#redline_task_count_val").html(redline);
                          $("#open_task_count_val").html(opentask);
                          $("#authored_task_count_val").html(authored);
                        }
                        else if(view == "1"){
                          if(layout == "1")
                          {
                            $(".allocated_tr:first").show();
                            $(".allocated_tr:first").next().show();
                            $(".table_layout").show();
                            $(".table_layout").find(".hidden_allocated_tr").show();
                            $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#2fd9ff");
                          }
                          else{
                            $(".allocated_tr").show();
                            $(".allocated_tr").next().show();
                            $(".table_layout").hide();
                            $(".table_layout").find(".hidden_allocated_tr").hide();
                          }
                          var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                          var opentask = $(".hidden_allocated_tr").length;
                          var authored = $(".hidden_author_tr").length;
                          $("#redline_task_count_val").html(redline);
                          $("#open_task_count_val").html(opentask);
                          $("#authored_task_count_val").html(authored);
                        }
                        $("[data-toggle=popover]").popover({
                            html : true,
                            content: function() {
                              var content = $(this).attr("data-popover-content");
                              return $(content).children(".popover-body").html();
                            },
                            title: function() {
                              var title = $(this).attr("data-popover-content");
                              return $(title).children(".popover-heading").html();
                            }
                        });

                        if(layout == "0")
                        {
                            if(taskidval != "")
                            {
                              //$(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                            }
                        }
                        else{
                          $("#"+taskidval).show();
                          $("#"+taskidval).next().show();
                        
                          var hidden_tr = taskidval.substr(8);
                          $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#2fd9ff");
                        }

                        if(layout == "1")
                        {
                          $(".open_layout_div").addClass("open_layout_div_change");
                          var open_tasks_height = $(".open_layout_div").height();
                          var margintop = parseInt(open_tasks_height);
                          $(".open_layout_div").css("position","fixed");
                          $(".open_layout_div").css("height","312px");
                          if(open_tasks_height > 312)
                          {
                            $(".open_layout_div").css("overflow-y","scroll");
                          }
                          if(open_tasks_height < 50)
                          {
                            $(".table_layout").css("margin-top","20px");
                          }
                            else{
                              $(".table_layout").css("margin-top","335px");
                            }
                        }
                        else{
                          $(".open_layout_div").removeClass("open_layout_div_change");
                          $(".open_layout_div").css("position","unset");
                          $(".open_layout_div").css("height","auto");
                          $(".open_layout_div").css("overflow-y","unset");
                            $(".table_layout").css("margin-top","0px");
                        }
                        $("body").removeClass("loading");
                      }
                    })
                  }
                  $.colorbox.close();
                }
              })
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
                  $("body").removeClass("loading");
                }
                else{
                  $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify;font-weight:400">'+result+'</strong>');
                  $("#editor_1").val("");
                  CKEDITOR.instances['editor_1'].setData("");
                  $(".task_specifics_modal").modal("hide");
                  $.ajax({
                    url:"<?php echo URL::to('user/taskmanager_change_allocations'); ?>",
                    type:"post",
                    data:{task_id:task_id,new_allocation:new_allocation,type:"0"},
                    dataType:"json",
                    success:function(result)
                    {
                      var htmlval = $("#allocation_history_div_"+task_id).html();
                      $("#allocation_history_div_"+task_id).html(result['pval']+htmlval);
                      var htmlval2 = $("#history_body").find("tbody").html();
                      $("#history_body").find("tbody").html(result['trval']+htmlval2);
                      $(".edit_allocate_user_"+task_id).attr("data-allocated",new_allocation);
                      $("#allocated_to_name_"+task_id).html(result['to']);
                      $("#hidden_tasks_tr_"+task_id).find(".allocated_sort_val").html(result['to']);
                      var count = 1;
                      $("#allocation_history_div_"+task_id).find("p").each(function() {
                        if(count > 5)
                        {
                          $(this).detach();
                        }
                        count++;
                      })
                      $(".allocation_modal").modal("hide");
                      var layout = $("#hidden_compressed_layout").val();
                      if(layout == "1")
                      {
                        if($("#task_tr_"+task_id).hasClass('author_tr'))
                        {
                        }
                        else{
                          var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
                          var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
                          if (typeof nexttask_id !== "undefined") {
                            var taskidval = nexttask_id;
                          }
                          else if (typeof prevtask_id !== "undefined") {
                            var taskidval = prevtask_id;
                          }
                          else{
                            var taskidval = '';
                          }

                          $("#task_tr_"+task_id).next().detach();
                          $("#task_tr_"+task_id).detach();
                          $("#hidden_tasks_tr_"+task_id).detach();

                          $("#task_tr_"+taskidval).show();
                          $("#task_tr_"+taskidval).next().show();
                          $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#2fd9ff");

                          var opentask = $("#open_task_count_val").html();
                          var countopen = parseInt(opentask) - 1;
                          $("#open_task_count_val").html(countopen);
                        }
                        $("body").removeClass("loading");
                      }
                      else{
                        var user_id = $(".select_user_home").val();
                        $.ajax({
                          url:"<?php echo URL::to('user/refresh_parktask'); ?>",
                          type:"post",
                          data:{user_id:user_id},
                              dataType:"json",
                          success: function(result)
                          {
                            $("#task_body_open").html(result['open_tasks']);
                            $("#task_body_layout").html(result['layout']);
                            $(".taskname_sort_val").find("img").detach();
                            var layout = $("#hidden_compressed_layout").val();
                            var view = $(".select_view").val();
                            $(".tasks_tr").hide();
                            $(".tasks_tr").next().hide();
                            $(".hidden_tasks_tr").hide();
                            if(view == "3")
                            {
                              if(layout == "1")
                              {
                                $(".author_tr:first").show();
                                $(".author_tr:first").next().show();
                                $(".table_layout").show();
                                $(".table_layout").find(".hidden_author_tr").show();
                                $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#2fd9ff");
                              }
                              else{
                                $(".author_tr").show();
                                $(".author_tr").next().show();
                                $(".table_layout").hide();
                                $(".table_layout").find(".hidden_author_tr").hide();
                              }
                              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                              var opentask = $(".hidden_allocated_tr").length;
                              var authored = $(".hidden_author_tr").length;
                              $("#redline_task_count_val").html(redline);
                              $("#open_task_count_val").html(opentask);
                              $("#authored_task_count_val").html(authored);
                            }
                            else if(view == "2"){
                              $("#open_task_count").hide();
                              $("#redline_task_count").show();
                              $("#authored_task_count").hide();
                              if(layout == "1")
                              {
                                var i = 1;
                                $(".redline_indication").each(function() {
                                  if(i == 1)
                                  {
                                    if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                                    {
                                      $(this).parents(".allocated_tr").show();
                                      $(this).parents(".allocated_tr").next().show();
                                      i++;
                                    }
                                  }
                                });
                                $(".table_layout").show();
                                $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                                
                                var j = 1;
                                $(".redline_indication_layout").each(function() {
                                  if(j == 1)
                                  {
                                    if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                                    {
                                      $(this).parents(".hidden_allocated_tr").find("td").css("background","#2fd9ff");
                                      j++;
                                    }
                                  }
                                });
                              }
                              else{
                                $(".redline_indication").parents(".allocated_tr").show();
                                $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                                $(".table_layout").hide();
                                $(".table_layout").find(".hidden_allocated_tr").hide();
                              }

                              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                              var opentask = $(".hidden_allocated_tr").length;
                              var authored = $(".hidden_author_tr").length;
                              $("#redline_task_count_val").html(redline);
                              $("#open_task_count_val").html(opentask);
                              $("#authored_task_count_val").html(authored);
                            }
                            else if(view == "1"){
                              if(layout == "1")
                              {
                                $(".allocated_tr:first").show();
                                $(".allocated_tr:first").next().show();
                                $(".table_layout").show();
                                $(".table_layout").find(".hidden_allocated_tr").show();
                                $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#2fd9ff");
                              }
                              else{
                                $(".allocated_tr").show();
                                $(".allocated_tr").next().show();
                                $(".table_layout").hide();
                                $(".table_layout").find(".hidden_allocated_tr").hide();
                              }
                              var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                              var opentask = $(".hidden_allocated_tr").length;
                              var authored = $(".hidden_author_tr").length;
                              $("#redline_task_count_val").html(redline);
                              $("#open_task_count_val").html(opentask);
                              $("#authored_task_count_val").html(authored);
                            }
                            $("[data-toggle=popover]").popover({
                                html : true,
                                content: function() {
                                  var content = $(this).attr("data-popover-content");
                                  return $(content).children(".popover-body").html();
                                },
                                title: function() {
                                  var title = $(this).attr("data-popover-content");
                                  return $(title).children(".popover-heading").html();
                                }
                            });

                            if(layout == "0")
                            {
                                if(taskidval != "")
                                {
                                  //$(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                                }
                            }
                            else{
                              $("#"+taskidval).show();
                              $("#"+taskidval).next().show();
                            
                              var hidden_tr = taskidval.substr(8);
                              $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#2fd9ff");
                            }

                            if(layout == "1")
                            {
                              $(".open_layout_div").addClass("open_layout_div_change");
                              var open_tasks_height = $(".open_layout_div").height();
                              var margintop = parseInt(open_tasks_height);
                              $(".open_layout_div").css("position","fixed");
                              $(".open_layout_div").css("height","312px");
                              if(open_tasks_height > 312)
                              {
                                $(".open_layout_div").css("overflow-y","scroll");
                              }
                              if(open_tasks_height < 50)
                              {
                                $(".table_layout").css("margin-top","20px");
                              }
                                else{
                                  $(".table_layout").css("margin-top","335px");
                                }
                            }
                            else{
                              $(".open_layout_div").removeClass("open_layout_div_change");
                              $(".open_layout_div").css("position","unset");
                              $(".open_layout_div").css("height","auto");
                              $(".open_layout_div").css("overflow-y","unset");
                                $(".table_layout").css("margin-top","0px");
                            }
                            $("body").removeClass("loading");
                          }
                        })
                      }
                    }
                  })
                }
              }
            })
          },1000);
      }
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
          $("#existing_comments").html(result['output']);
          $(".title_task_details").html(result['title']);
          $(".task_specifics_modal").modal("show");
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
  if($(e.target).hasClass('edit_allocate_user'))
  {
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-element");
    var subject = $(e.target).attr("data-subject");
    var author = $(e.target).attr("data-author");
    var allocated = $(e.target).attr("data-allocated");
    $(".new_allocation").val("");
    $(".new_allocation").find("option").show();
    if(allocated == "0" || allocated == "")
    {
      $(".current_allocation").val(author);
      $(".new_allocation").find("option[value='"+author+"']").hide();
    }
    else{
      $(".current_allocation").val(allocated);
      $(".new_allocation").find("option[value='"+allocated+"']").hide();
    }
    $(".subject_allocation").html(subject);

    $("#hidden_task_id_allocation").val(task_id);
    $(".history_body").hide();
    $(".allocation_body").show();
    

    $.ajax({
      url:"<?php echo URL::to('user/show_all_allocations'); ?>",
      type:"post",
      data:{task_id:task_id},
      success:function(result)
      {
      	$(".allocation_modal").modal("show");
        $("#hidden_task_id_history").val(task_id);
        $("#history_body").html(result);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('show_task_allocation_history'))
  {
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-element");
    var subject = $(e.target).attr("data-subject");
    var author = $(e.target).attr("data-author");
    var allocated = $(e.target).attr("data-allocated");

    $(".new_allocation").val("");
    $(".new_allocation").find("option").show();
    if(allocated == "0" || allocated == "")
    {
      $(".current_allocation").val(author);
      $(".new_allocation").find("option[value='"+author+"']").hide();
    }
    else{
      $(".current_allocation").val(allocated);
      $(".new_allocation").find("option[value='"+allocated+"']").hide();
    }
    $(".subject_allocation").html(subject);

    $("#hidden_task_id_allocation").val(task_id);
    $(".history_body").show();
    $(".allocation_body").hide();

    $.ajax({
      url:"<?php echo URL::to('user/show_all_allocations'); ?>",
      type:"post",
      data:{task_id:task_id},
      success:function(result)
      {
        $("#hidden_task_id_history").val(task_id);
        $("#history_body").html(result);
        $(".allocation_modal").modal("show");
        $("body").removeClass("loading");
      }
    })
  }
  if(e.target.id == "allocate_now")
  {
    $("body").addClass("loading");
    var task_id = $("#hidden_task_id_allocation").val();
    var auto_close = $("#hidden_task_id_auto_close").val();
    var new_allocation = $(".new_allocation").val();
    var author = $("#hidden_task_id_author");
    var selected_user = $(".select_user_home").val();

    if(auto_close == "1")
    {
      if(selected_user != author)
      {
        if(author == new_allocation)
        {
          $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">This Task is an AUTOCLOSE task, by allocating are you happy that this taks is complete with no further action required by the author?  If you select YES this task will be marked COMPLETE and will not be brought to the attention of the Author, If you select NO the task will be place back in the Authors Open Tasks for review</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:" data-task="'+task_id+'" data-new="'+new_allocation+'" data-author="'+author+'" class="common_black_button yes_allocate_now">Yes</a><a href="javascript:" class="common_black_button no_allocate_now" data-task="'+task_id+'" data-new="'+new_allocation+'" data-author="'+author+'">No</a></p>',fixed:true,width:"800px"});
            $("body").removeClass("loading");
            return false;
        }
      }
    }

    var nexttask_id = $(e.target).parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
    var prevtask_id = $(e.target).parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
    if (typeof nexttask_id !== "undefined") {
      var taskidval = nexttask_id;
    }
    else if (typeof prevtask_id !== "undefined") {
      var taskidval = prevtask_id;
    }
    else{
      var taskidval = '';
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
          var htmlval = $("#allocation_history_div_"+task_id).html();
          $("#allocation_history_div_"+task_id).html(result['pval']+htmlval);
          var htmlval2 = $("#history_body").find("tbody").html();
          $("#history_body").find("tbody").html(result['trval']+htmlval2);
          $(".edit_allocate_user_"+task_id).attr("data-allocated",new_allocation);
          $("#allocated_to_name_"+task_id).html(result['to']);
          $("#hidden_tasks_tr_"+task_id).find(".allocated_sort_val").html(result['to']);
          var count = 1;
          $("#allocation_history_div_"+task_id).find("p").each(function() {
            if(count > 5)
            {
              $(this).detach();
            }
            count++;
          })
          $(".allocation_modal").modal("hide");
          var layout = $("#hidden_compressed_layout").val();
          if(layout == "1")
          {
            if($("#task_tr_"+task_id).hasClass('author_tr'))
            {

            }
            else{
              var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
              var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
              if (typeof nexttask_id !== "undefined") {
                var taskidval = nexttask_id;
              }
              else if (typeof prevtask_id !== "undefined") {
                var taskidval = prevtask_id;
              }
              else{
                var taskidval = '';
              }

              $("#task_tr_"+task_id).next().detach();
              $("#task_tr_"+task_id).detach();
              $("#hidden_tasks_tr_"+task_id).detach();

              $("#task_tr_"+taskidval).show();
              $("#task_tr_"+taskidval).next().show();
              $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#2fd9ff");

              var opentask = $("#open_task_count_val").html();
              var countopen = parseInt(opentask) - 1;
              $("#open_task_count_val").html(countopen);
            }
            $("body").removeClass("loading");
          }
          else{
             setTimeout(function() {
                var user_id = $(".select_user_home").val();
                $.ajax({
                  url:"<?php echo URL::to('user/refresh_parktask'); ?>",
                  type:"post",
                  data:{user_id:user_id},
                      dataType:"json",
                  success: function(result)
                  {
                    $("#task_body_open").html(result['open_tasks']);
                    $("#task_body_layout").html(result['layout']);
                    $(".taskname_sort_val").find("img").detach();
                      var layout = $("#hidden_compressed_layout").val();
                      var view = $(".select_view").val();
                  $(".tasks_tr").hide();
                $(".tasks_tr").next().hide();
                $(".hidden_tasks_tr").hide();
                  if(view == "3")
                  {
                    if(layout == "1")
                    {
                      $(".author_tr:first").show();
                      $(".author_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_author_tr").show();
                      $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#2fd9ff");
                    }
                    else{
                      $(".author_tr").show();
                      $(".author_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_author_tr").hide();
                    }
                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  else if(view == "2"){
                    $("#open_task_count").hide();
                    $("#redline_task_count").show();
                    $("#authored_task_count").hide();
                    if(layout == "1")
                    {
                      var i = 1;
                      $(".redline_indication").each(function() {
                        if(i == 1)
                        {
                          if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                          {
                            $(this).parents(".allocated_tr").show();
                            $(this).parents(".allocated_tr").next().show();
                            i++;
                          }
                        }
                      });
                      $(".table_layout").show();
                      $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                      
                      var j = 1;
                      $(".redline_indication_layout").each(function() {
                        if(j == 1)
                        {
                          if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                          {
                            $(this).parents(".hidden_allocated_tr").find("td").css("background","#2fd9ff");
                            j++;
                          }
                        }
                      });
                    }
                    else{
                      $(".redline_indication").parents(".allocated_tr").show();
                      $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }

                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  else if(view == "1"){
                    if(layout == "1")
                    {
                      $(".allocated_tr:first").show();
                      $(".allocated_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_allocated_tr").show();
                      $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#2fd9ff");
                    }
                    else{
                      $(".allocated_tr").show();
                      $(".allocated_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }
                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }

                      $("[data-toggle=popover]").popover({
                          html : true,
                          content: function() {
                            var content = $(this).attr("data-popover-content");
                            return $(content).children(".popover-body").html();
                          },
                          title: function() {
                            var title = $(this).attr("data-popover-content");
                            return $(title).children(".popover-heading").html();
                          }
                      });

                      if(layout == "0")
                     {
                          if(taskidval != "")
                          {
                            // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                          }
                      }
                      else{
                        $("#"+taskidval).show();
                        $("#"+taskidval).next().show();
                      
                        var hidden_tr = taskidval.substr(8);
                        $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#2fd9ff");
                      }

                      if(layout == "1")
                      {
                        $(".open_layout_div").addClass("open_layout_div_change");
                        var open_tasks_height = $(".open_layout_div").height();
                        var margintop = parseInt(open_tasks_height);
                        $(".open_layout_div").css("position","fixed");
                        $(".open_layout_div").css("height","312px");
                        if(open_tasks_height > 312)
                        {
                          $(".open_layout_div").css("overflow-y","scroll");
                        }
                        if(open_tasks_height < 50)
                        {
                          $(".table_layout").css("margin-top","20px");
                        }
                          else{
                            $(".table_layout").css("margin-top","335px");
                          }
                      }
                      else{
                        $(".open_layout_div").removeClass("open_layout_div_change");
                        $(".open_layout_div").css("position","unset");
                        $(".open_layout_div").css("height","auto");
                        $(".open_layout_div").css("overflow-y","unset");
                          $(".table_layout").css("margin-top","0px");
                      }
                    $("body").removeClass("loading");
                  }
                })
             },2000);
          }
        }
      })
    }
  }
  if($(e.target).hasClass('yes_allocate_now'))
  {
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-task");
    var new_allocation = $(e.target).attr("data-new");
    var author = $(e.target).attr("data-author");

    var nexttask_id = $(e.target).parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
    var prevtask_id = $(e.target).parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
    if (typeof nexttask_id !== "undefined") {
      var taskidval = nexttask_id;
    }
    else if (typeof prevtask_id !== "undefined") {
      var taskidval = prevtask_id;
    }
    else{
      var taskidval = '';
    }

    if(new_allocation == "")
    {
      alert("Please choose the user to allocate the task.");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/taskmanager_change_allocations'); ?>",
        type:"post",
        data:{task_id:task_id,new_allocation:new_allocation,type:"1"},
        dataType:"json",
        success:function(result)
        {
          var htmlval = $("#allocation_history_div_"+task_id).html();
          $("#allocation_history_div_"+task_id).html(result['pval']+htmlval);
          var htmlval2 = $("#history_body").find("tbody").html();
          $("#history_body").find("tbody").html(result['trval']+htmlval2);
          $(".edit_allocate_user_"+task_id).attr("data-allocated",new_allocation);
          $("#allocated_to_name_"+task_id).html(result['to']);
          $("#hidden_tasks_tr_"+task_id).find(".allocated_sort_val").html(result['to']);
          var count = 1;
          $("#allocation_history_div_"+task_id).find("p").each(function() {
            if(count > 5)
            {
              $(this).detach();
            }
            count++;
          })
          $(".allocation_modal").modal("hide");
          var layout = $("#hidden_compressed_layout").val();
          if(layout == "1")
          {
            if($("#task_tr_"+task_id).hasClass('author_tr'))
            {

            }
            else{
              var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
              var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
              if (typeof nexttask_id !== "undefined") {
                var taskidval = nexttask_id;
              }
              else if (typeof prevtask_id !== "undefined") {
                var taskidval = prevtask_id;
              }
              else{
                var taskidval = '';
              }

              $("#task_tr_"+task_id).next().detach();
              $("#task_tr_"+task_id).detach();
              $("#hidden_tasks_tr_"+task_id).detach();

              $("#task_tr_"+taskidval).show();
              $("#task_tr_"+taskidval).next().show();
              $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#2fd9ff");

              var opentask = $("#open_task_count_val").html();
              var countopen = parseInt(opentask) - 1;
              $("#open_task_count_val").html(countopen);
            }
            $("body").removeClass("loading");
          }
          else{
             setTimeout(function() {
                var user_id = $(".select_user_home").val();
                $.ajax({
                  url:"<?php echo URL::to('user/refresh_parktask'); ?>",
                  type:"post",
                  data:{user_id:user_id},
                      dataType:"json",
                  success: function(result)
                  {
                    $("#task_body_open").html(result['open_tasks']);
                    $("#task_body_layout").html(result['layout']);
                    $(".taskname_sort_val").find("img").detach();
                      var layout = $("#hidden_compressed_layout").val();
                      var view = $(".select_view").val();
                  $(".tasks_tr").hide();
                $(".tasks_tr").next().hide();
                $(".hidden_tasks_tr").hide();
                  if(view == "3")
                  {
                    if(layout == "1")
                    {
                      $(".author_tr:first").show();
                      $(".author_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_author_tr").show();
                      $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#2fd9ff");
                    }
                    else{
                      $(".author_tr").show();
                      $(".author_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_author_tr").hide();
                    }
                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  else if(view == "2"){
                    $("#open_task_count").hide();
                    $("#redline_task_count").show();
                    $("#authored_task_count").hide();
                    if(layout == "1")
                    {
                      var i = 1;
                      $(".redline_indication").each(function() {
                        if(i == 1)
                        {
                          if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                          {
                            $(this).parents(".allocated_tr").show();
                            $(this).parents(".allocated_tr").next().show();
                            i++;
                          }
                        }
                      });
                      $(".table_layout").show();
                      $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                      
                      var j = 1;
                      $(".redline_indication_layout").each(function() {
                        if(j == 1)
                        {
                          if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                          {
                            $(this).parents(".hidden_allocated_tr").find("td").css("background","#2fd9ff");
                            j++;
                          }
                        }
                      });
                    }
                    else{
                      $(".redline_indication").parents(".allocated_tr").show();
                      $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }

                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  else if(view == "1"){
                    if(layout == "1")
                    {
                      $(".allocated_tr:first").show();
                      $(".allocated_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_allocated_tr").show();
                      $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#2fd9ff");
                    }
                    else{
                      $(".allocated_tr").show();
                      $(".allocated_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }
                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }

                      $("[data-toggle=popover]").popover({
                          html : true,
                          content: function() {
                            var content = $(this).attr("data-popover-content");
                            return $(content).children(".popover-body").html();
                          },
                          title: function() {
                            var title = $(this).attr("data-popover-content");
                            return $(title).children(".popover-heading").html();
                          }
                      });

                      if(layout == "0")
                     {
                          if(taskidval != "")
                          {
                            // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                          }
                      }
                      else{
                        $("#"+taskidval).show();
                        $("#"+taskidval).next().show();
                      
                        var hidden_tr = taskidval.substr(8);
                        $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#2fd9ff");
                      }

                      if(layout == "1")
                      {
                        $(".open_layout_div").addClass("open_layout_div_change");
                        var open_tasks_height = $(".open_layout_div").height();
                        var margintop = parseInt(open_tasks_height);
                        $(".open_layout_div").css("position","fixed");
                        $(".open_layout_div").css("height","312px");
                        if(open_tasks_height > 312)
                        {
                          $(".open_layout_div").css("overflow-y","scroll");
                        }
                        if(open_tasks_height < 50)
                        {
                          $(".table_layout").css("margin-top","20px");
                        }
                          else{
                            $(".table_layout").css("margin-top","335px");
                          }
                      }
                      else{
                        $(".open_layout_div").removeClass("open_layout_div_change");
                        $(".open_layout_div").css("position","unset");
                        $(".open_layout_div").css("height","auto");
                        $(".open_layout_div").css("overflow-y","unset");
                          $(".table_layout").css("margin-top","0px");
                      }
                    $("body").removeClass("loading");
                  }
                })
             },2000);
          }
          $.colorbox.close();
        }
      })
    }
  }
  if($(e.target).hasClass('no_allocate_now'))
  {
    $("body").addClass("loading");
    var task_id = $(e.target).attr("data-task");
    var new_allocation = $(e.target).attr("data-new");
    var author = $(e.target).attr("data-author");

    var nexttask_id = $(e.target).parents(".tasks_tr").nextAll('.tasks_tr:visible').first().attr("id");
    var prevtask_id = $(e.target).parents(".tasks_tr").prevAll('.tasks_tr:visible').first().attr("id");
    if (typeof nexttask_id !== "undefined") {
      var taskidval = nexttask_id;
    }
    else if (typeof prevtask_id !== "undefined") {
      var taskidval = prevtask_id;
    }
    else{
      var taskidval = '';
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
          var htmlval = $("#allocation_history_div_"+task_id).html();
          $("#allocation_history_div_"+task_id).html(result['pval']+htmlval);
          var htmlval2 = $("#history_body").find("tbody").html();
          $("#history_body").find("tbody").html(result['trval']+htmlval2);
          $(".edit_allocate_user_"+task_id).attr("data-allocated",new_allocation);
          $("#allocated_to_name_"+task_id).html(result['to']);
          $("#hidden_tasks_tr_"+task_id).find(".allocated_sort_val").html(result['to']);
          var count = 1;
          $("#allocation_history_div_"+task_id).find("p").each(function() {
            if(count > 5)
            {
              $(this).detach();
            }
            count++;
          })
          $(".allocation_modal").modal("hide");
          var layout = $("#hidden_compressed_layout").val();
          if(layout == "1")
          {
            if($("#task_tr_"+task_id).hasClass('author_tr'))
            {

            }
            else{
              var nexttask_id = $("#hidden_tasks_tr_"+task_id).nextAll('.hidden_tasks_tr:visible').first().attr("data-element");
              var prevtask_id = $("#hidden_tasks_tr_"+task_id).prevAll('.hidden_tasks_tr:visible').first().attr("data-element");
              if (typeof nexttask_id !== "undefined") {
                var taskidval = nexttask_id;
              }
              else if (typeof prevtask_id !== "undefined") {
                var taskidval = prevtask_id;
              }
              else{
                var taskidval = '';
              }

              $("#task_tr_"+task_id).next().detach();
              $("#task_tr_"+task_id).detach();
              $("#hidden_tasks_tr_"+task_id).detach();

              $("#task_tr_"+taskidval).show();
              $("#task_tr_"+taskidval).next().show();
              $("#hidden_tasks_tr_"+taskidval).find("td").css("background","#2fd9ff");

              var opentask = $("#open_task_count_val").html();
              var countopen = parseInt(opentask) - 1;
              $("#open_task_count_val").html(countopen);
            }
            $("body").removeClass("loading");
          }
          else{
             setTimeout(function() {
                var user_id = $(".select_user_home").val();
                $.ajax({
                  url:"<?php echo URL::to('user/refresh_parktask'); ?>",
                  type:"post",
                  data:{user_id:user_id},
                      dataType:"json",
                  success: function(result)
                  {
                    $("#task_body_open").html(result['open_tasks']);
                    $("#task_body_layout").html(result['layout']);
                    $(".taskname_sort_val").find("img").detach();
                      var layout = $("#hidden_compressed_layout").val();
                      var view = $(".select_view").val();
                  $(".tasks_tr").hide();
                $(".tasks_tr").next().hide();
                $(".hidden_tasks_tr").hide();
                  if(view == "3")
                  {
                    if(layout == "1")
                    {
                      $(".author_tr:first").show();
                      $(".author_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_author_tr").show();
                      $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#2fd9ff");
                    }
                    else{
                      $(".author_tr").show();
                      $(".author_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_author_tr").hide();
                    }
                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  else if(view == "2"){
                    $("#open_task_count").hide();
                    $("#redline_task_count").show();
                    $("#authored_task_count").hide();
                    if(layout == "1")
                    {
                      var i = 1;
                      $(".redline_indication").each(function() {
                        if(i == 1)
                        {
                          if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
                          {
                            $(this).parents(".allocated_tr").show();
                            $(this).parents(".allocated_tr").next().show();
                            i++;
                          }
                        }
                      });
                      $(".table_layout").show();
                      $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
                      
                      var j = 1;
                      $(".redline_indication_layout").each(function() {
                        if(j == 1)
                        {
                          if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
                          {
                            $(this).parents(".hidden_allocated_tr").find("td").css("background","#2fd9ff");
                            j++;
                          }
                        }
                      });
                    }
                    else{
                      $(".redline_indication").parents(".allocated_tr").show();
                      $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }

                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }
                  else if(view == "1"){
                    if(layout == "1")
                    {
                      $(".allocated_tr:first").show();
                      $(".allocated_tr:first").next().show();
                      $(".table_layout").show();
                      $(".table_layout").find(".hidden_allocated_tr").show();
                      $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#2fd9ff");
                    }
                    else{
                      $(".allocated_tr").show();
                      $(".allocated_tr").next().show();
                      $(".table_layout").hide();
                      $(".table_layout").find(".hidden_allocated_tr").hide();
                    }
                    var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
                    var opentask = $(".hidden_allocated_tr").length;
                    var authored = $(".hidden_author_tr").length;
                    $("#redline_task_count_val").html(redline);
                    $("#open_task_count_val").html(opentask);
                    $("#authored_task_count_val").html(authored);
                  }

                      $("[data-toggle=popover]").popover({
                          html : true,
                          content: function() {
                            var content = $(this).attr("data-popover-content");
                            return $(content).children(".popover-body").html();
                          },
                          title: function() {
                            var title = $(this).attr("data-popover-content");
                            return $(title).children(".popover-heading").html();
                          }
                      });

                      if(layout == "0")
                     {
                          if(taskidval != "")
                          {
                            // $(document).scrollTop( $("#"+taskidval).offset().top - parseInt(250) );
                          }
                      }
                      else{
                        $("#"+taskidval).show();
                        $("#"+taskidval).next().show();
                      
                        var hidden_tr = taskidval.substr(8);
                        $("#hidden_tasks_tr_"+hidden_tr).find("td").css("background","#2fd9ff");
                      }

                      if(layout == "1")
                      {
                        $(".open_layout_div").addClass("open_layout_div_change");
                        var open_tasks_height = $(".open_layout_div").height();
                        var margintop = parseInt(open_tasks_height);
                        $(".open_layout_div").css("position","fixed");
                        $(".open_layout_div").css("height","312px");
                        if(open_tasks_height > 312)
                        {
                          $(".open_layout_div").css("overflow-y","scroll");
                        }
                        if(open_tasks_height < 50)
                        {
                          $(".table_layout").css("margin-top","20px");
                        }
                          else{
                            $(".table_layout").css("margin-top","335px");
                          }
                      }
                      else{
                        $(".open_layout_div").removeClass("open_layout_div_change");
                        $(".open_layout_div").css("position","unset");
                        $(".open_layout_div").css("height","auto");
                        $(".open_layout_div").css("overflow-y","unset");
                          $(".table_layout").css("margin-top","0px");
                      }
                    $("body").removeClass("loading");
                  }
                })
             },2000);
          }
          $.colorbox.close();
        }
      })
    }
  }
  if($(e.target).hasClass('edit_due_date'))
  {
    var subject = $(e.target).attr("data-subject");
    var due_date = $(e.target).attr("data-value");
    var task_id = $(e.target).attr("data-element");
    var color = $(e.target).attr("data-color");
    var correct_date = $(e.target).attr("data-duedate");
    $(".new_due_date").val("");

    $(".subject_due_date").html(subject);
    $(".current_due_date").val(due_date);
    $(".current_due_date").css("background",color);
    $("#hidden_task_id_due_date").val(task_id);

    $(".due_date_modal").modal("show");
    $(".due_date_edit").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD-MMM-YYYY',
       minDate: correct_date,
    });
  }
  if(e.target.id == "due_date_change_button")
  {
    $("body").addClass("loading");
    var task_id = $("#hidden_task_id_due_date").val();
    var new_date = $(".new_due_date").val();
    if(new_date == "")
    {
      alert("Please choose the Due Date to apply a new due date");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/taskmanager_change_due_date'); ?>",
        type:"post",
        data:{task_id:task_id,new_date:new_date},
        dataType:"json",
        success:function(result)
        {
          $(".edit_due_date_"+task_id).attr("data-duedate",result['new_change_date']);
          $(".edit_due_date_"+task_id).attr("data-value",result['new_date']);
          $(".edit_due_date_"+task_id).attr("data-color",result['color']);

          $("#due_date_task_"+task_id).html(result['new_date']);
          $("#due_date_task_"+task_id).css("color",result['color']);

          $("#layout_due_date_task_"+task_id).html(result['new_date']);
          $("#layout_due_date_task_"+task_id).css("color",result['color']);


          $(".due_date_modal").modal("hide");
          $("body").removeClass("loading");
        }
      })
    }
  }
  if($(e.target).hasClass('delete_attachments'))
  {
    e.preventDefault();
    var hrefval = $(e.target).attr("href");
    var r = confirm("Are you sure you want to delete this attachment?");
    if(r)
    {
      $.ajax({
        url:hrefval,
        type:"get",
        success:function(result)
        {
          $(e.target).parents("p").detach();
        }
      })
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
  if(e.target.id == "link_infile_progress_button")
  {
    var checkcount = $(".infile_progress_check:checked").length;
    var task_id = $("#hidden_progress_infiles_task_id").val();
    if(checkcount > 0)
    {
      var ids = '';
      $(".infile_progress_check:checked").each(function() {
        if(ids == "")
        {
          ids = $(this).val();
        }
        else{
          ids = ids+','+$(this).val();
        }
      });

      $("#hidden_infiles_progress_id_"+task_id).val(ids);
      $(".infiles_progress_modal").modal("hide");
      $.ajax({
        url:"<?php echo URL::to('user/show_linked_progress_infiles'); ?>",
        type:"post",
        data:{ids:ids,task_id:task_id},
        success:function(result)
        {
          $("#add_infiles_attachments_progress_div_"+task_id).html(result);
        }
      })
    }
  }
  if(e.target.id == "link_infile_completion_button")
  {
    var checkcount = $(".infile_completion_check:checked").length;
    var task_id = $("#hidden_completion_infiles_task_id").val();
    if(checkcount > 0)
    {
      var ids = '';
      $(".infile_completion_check:checked").each(function() {
        if(ids == "")
        {
          ids = $(this).val();
        }
        else{
          ids = ids+','+$(this).val();
        }
      });

      $("#hidden_infiles_completion_id_"+task_id).val(ids);
      $(".infiles_completion_modal").modal("hide");
      $.ajax({
        url:"<?php echo URL::to('user/show_linked_completion_infiles'); ?>",
        type:"post",
        data:{ids:ids,task_id:task_id},
        success:function(result)
        {
          $("#add_infiles_attachments_completion_div_"+task_id).html(result);
        }
      })
    }
  }
  if(e.target.id == "link_yearend_completion_button")
  {
    var checkcount = $(".yearend_completion_check:checked").length;
    var task_id = $("#hidden_completion_yearend_task_id").val();
    if(checkcount > 0)
    {
      var ids = '';
      $(".yearend_completion_check:checked").each(function() {
        if(ids == "")
        {
          ids = $(this).val();
        }
        else{
          ids = ids+','+$(this).val();
        }
      });

      $("#hidden_yearend_completion_id_"+task_id).val(ids);
      $(".yearend_completion_modal").modal("hide");
      $.ajax({
        url:"<?php echo URL::to('user/show_linked_completion_yearend'); ?>",
        type:"post",
        data:{ids:ids,task_id:task_id},
        success:function(result)
        {
          $("#add_yearend_attachments_completion_div_"+task_id).html(result);
        }
      })
    }
  }
  if(e.target.id == "show_incomplete_files")
  {
    if($(e.target).is(":checked"))
    {
      $(".tr_incomplete").hide();
    }
    else{
      $(".tr_incomplete").show();
    }
  }
  if($(e.target).hasClass('link_infile'))
  {
  	var href = $(e.target).attr("data-element");
	var printWin = window.open(href,'_blank','location=no,height=570,width=650,top=80, left=250,leftscrollbars=yes,status=yes');
	if (printWin == null || typeof(printWin)=='undefined')
	{
		alert('Please uncheck the option "Block Popup windows" to allow the popup window generated from our website.');
	}
  }
  if($(e.target).hasClass('link_yearend'))
  {
    var href = $(e.target).attr("data-element");
  var printWin = window.open(href,'_blank','location=no,height=570,width=650,top=80, left=250,leftscrollbars=yes,status=yes');
  if (printWin == null || typeof(printWin)=='undefined')
  {
    alert('Please uncheck the option "Block Popup windows" to allow the popup window generated from our website.');
  }
  }
  if($(e.target).hasClass('infiles_link'))
  {
  	var client_id = $("#client_search").val();
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
  if($(e.target).hasClass('infiles_link_progress'))
  {
    var task_id = $(e.target).attr("data-element");
    var client_id = $("#hidden_progress_client_id_"+task_id).val();
    var ids = $("#hidden_infiles_progress_id_"+task_id).val();

    if(client_id == "")
    {
      alert("Please select the client and then choose infiles");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/show_progress_infiles'); ?>",
        type:"post",
        data:{client_id:client_id,ids:ids},
        success: function(result)
        {
          $("#hidden_progress_infiles_task_id").val(task_id);
          $(".infiles_progress_modal").modal("show");
          $("#infiles_progress_body").html(result);
        }
      })
    }
  }
  if($(e.target).hasClass('infiles_link_completion'))
  {
    var task_id = $(e.target).attr("data-element");
    var client_id = $("#hidden_completion_client_id_"+task_id).val();
    var ids = $("#hidden_infiles_completion_id_"+task_id).val();

    if(client_id == "")
    {
      alert("Please select the client and then choose infiles");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/show_completion_infiles'); ?>",
        type:"post",
        data:{client_id:client_id,ids:ids},
        success: function(result)
        {
          $("#hidden_completion_infiles_task_id").val(task_id);
          $(".infiles_completion_modal").modal("show");
          $("#infiles_completion_body").html(result);
        }
      })
    }
  }
  if($(e.target).hasClass('yearend_link_completion'))
  {
    var task_id = $(e.target).attr("data-element");
    var client_id = $("#hidden_completion_client_id_"+task_id).val();
    var ids = $("#hidden_yearend_completion_id_"+task_id).val();

    if(client_id == "")
    {
      alert("Please select the client and then choose infiles");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/show_completion_yearend'); ?>",
        type:"post",
        data:{client_id:client_id,ids:ids},
        success: function(result)
        {
          $("#hidden_completion_yearend_task_id").val(task_id);
          $(".yearend_completion_modal").modal("show");
          $("#yearend_completion_body").html(result);
        }
      })
    }
  }
  if(e.target.id == "create_new_task")
  {
    $(".create_new_model").find(".job_title").html("New Task Creator");
    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
    var user_id = $(".select_user_home").val();
    $(".select_user_author").val(user_id);
    $(".create_new_model").modal("show");
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
            contentsCss: "body {font-size: 16px;font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif}",
   });

    $("#action_type").val("1");
    $(".allocate_user_add").val("");
    $(".client_search_class").val("");
    $("#client_search").val("");
    $(".task-choose_internal").html("Select Task");
    $(".subject_class").val("");
    $(".task_specifics_add").show();
    $(".task_specifics_copy").hide();
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
    $("#hidden_infiles_id").val("");
    $("#add_infiles_attachments_div").html("");
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
    $.ajax({
      url:"<?php echo URL::to('user/clear_session_task_attachments'); ?>",
      type:"post",
      data:{fileid:"0"},
      success: function(result)
      {
        $("#add_notepad_attachments_div").html('');
        $("#add_attachments_div").html('');
        $("body").removeClass("loading");
      }
    })
  }
  if(e.target.id == "internal_checkbox")
  {
    $("#client_search").val("");
    $("#idtask").val("");
    $(".task-choose_internal").html("Select Task");
    $(".client_search_class").val("");

    if($(e.target).is(":checked"))
    {
      $(".client_group").hide();
      $(".client_search_class").prop("required",false);
      $(".internal_tasks_group").show();
      $(".infiles_link").hide();
    }
    else{
      $(".client_group").show();
      $(".client_search_class").prop("required",true);
      $(".internal_tasks_group").hide();
      $(".infiles_link").show();
    }
  }
  if($(e.target).hasClass('tasks_li'))

  {

    var taskid = $(e.target).attr('data-element');

    $("#idtask").val(taskid);
    $("#edit_idtask").val(taskid);

    $(".task-choose:first-child").text($(e.target).text());

  }

  if($(e.target).hasClass('tasks_li_internal'))
  {
    var taskid = $(e.target).attr('data-element');
    $("#idtask").val(taskid);
    $("#edit_idtask").val(taskid);
    $(".task-choose_internal:first-child").text($(e.target).text());
  }
  if($(e.target).hasClass('tasks_li_internal_change'))
  {
    var taskid = $(e.target).attr('data-element');
    $("#idtask_change").val(taskid);
    $(".task-choose_internal_change:first-child").text($(e.target).text());
  }
  if(e.target.id == "change_taskname_button")
  {
    var taskid = $(".hidden_task_id_change_task").val();
    var tasktype = $("#idtask_change").val();
    $.ajax({
      url:"<?php echo URL::to('user/change_task_name_taskmanager'); ?>",
      type:"post",
      data:{taskid:taskid,tasktype:tasktype},
      success:function(result)
      {
        $(".task_name_"+taskid).html(result);
        $(".change_taskname_modal").modal("hide");
      }
    })
  }
  if($(e.target).hasClass('tasks_li_internal_copy'))
  {
    var taskid = $(e.target).attr('data-element');
    $("#idtask_copy").val(taskid);
    $("#edit_idtask").val(taskid);
    $(".task-choose_internal_copy:first-child").text($(e.target).text());
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

  if($(e.target).hasClass('notepad_progress_submit'))
  { 
    var contents = $(e.target).parent().find('.notepad_contents_progress').val();
    if(contents == '' || typeof contents === 'undefined')
    {
      $(e.target).parent().find(".error_files_notepad").text("Please Enter the contents for the notepad to save.");
      return false;
    }
    else{
      $(e.target).parents('td').find('.notepad_div_progress_notes').toggle();
    }
  }
  else{
    $(".notepad_div_progress_notes").each(function() {
      $(this).hide();
    });
  }

  if($(e.target).hasClass('notepad_completion_submit'))
  { 
    var contents = $(e.target).parent().find('.notepad_contents_completion').val();
    if(contents == '' || typeof contents === 'undefined')
    {
      $(e.target).parent().find(".error_files_notepad").text("Please Enter the contents for the notepad to save.");
      return false;
    }
    else{
      $(e.target).parents('td').find('.notepad_div_completion_notes').toggle();
    }
  }
  else{
    $(".notepad_div_completion_notes").each(function() {
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
  if($(e.target).hasClass('notepad_contents_progress'))
  {
    $(e.target).parents('.notepad_div_progress_notes').toggle();
  }
  if($(e.target).hasClass('notepad_contents_completion'))
  {
    $(e.target).parents('.notepad_div_completion_notes').toggle();
  }
  if($(e.target).hasClass('notepad_submit_add'))
  {
    var contents = $(".notepad_contents_add").val();
    $.ajax({
      url:"<?php echo URL::to('user/add_taskmanager_notepad_contents'); ?>",
      type:"post",
      data:{contents:contents},
      dataType:"json",
      success: function(result)
      {
        $("#attachments_text").show();
        $("#add_notepad_attachments_div").append("<p>"+result['filename']+" <a href='javascript:' class='remove_notepad_attach_add' data-task='"+result['file_id']+"'>Remove</a></p>");
        $(".notepad_div_notes_add").hide();
      }
    });
  }
  if($(e.target).hasClass('notepad_progress_submit'))
  {
    var contents = $(e.target).parents(".notepad_div_progress_notes").find(".notepad_contents_progress").val();
    var task_id = $(e.target).parents(".notepad_div_progress_notes").find("#hidden_task_id_progress_notepad").val();
    $.ajax({
      url:"<?php echo URL::to('user/taskmanager_notepad_contents_progress'); ?>",
      type:"post",
      data:{contents:contents,task_id:task_id},
      dataType:"json",
      success: function(result)
      {
        $("#add_notepad_attachments_progress_div_"+task_id).append("<p><a href='"+result['download_url']+"' class='file_attachments' download>"+result['filename']+"</a> <a href='"+result['delete_url']+"' class='fa fa-trash delete_attachments'></a></p>");
        $(".notepad_div_progress_notes").hide();
      }
    });
  }
  if($(e.target).hasClass('notepad_completion_submit'))
  {
    var contents = $(e.target).parents(".notepad_div_completion_notes").find(".notepad_contents_completion").val();
    var task_id = $(e.target).parents(".notepad_div_completion_notes").find("#hidden_task_id_completion_notepad").val();
    $.ajax({
      url:"<?php echo URL::to('user/taskmanager_notepad_contents_completion'); ?>",
      type:"post",
      data:{contents:contents,task_id:task_id},
      dataType:"json",
      success: function(result)
      {
        $("#add_notepad_attachments_completion_div_"+task_id).append("<p><a href='"+result['download_url']+"' class='file_attachments' download>"+result['filename']+"</a> <a href='"+result['delete_url']+"' class='fa fa-trash delete_attachments'></a></p>");
        $(".notepad_div_completion_notes").hide();
      }
    });
  }
  if($(e.target).hasClass('trash_imageadd'))
  {
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/clear_session_task_attachments'); ?>",
      type:"post",
      data:{fileid:"0"},
      success: function(result)
      {
        $("#add_notepad_attachments_div").html('');
        $("#add_attachments_div").html('');
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('fa-plus-add'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left);
    $(e.target).parent().find('.img_div_add').toggle();
    Dropzone.forElement("#imageUpload1").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
  }
  if($(e.target).hasClass('faplus_progress'))
  {
    var task_id = $(e.target).attr("data-element");
    $("#hidden_task_id_progress").val(task_id);
    $(".dropzone_progress_modal").modal("show");
    // Dropzone.forElement("#imageUpload").removeAllFiles(true);
    // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
  }
  if($(e.target).hasClass('faplus_completion'))
  {
    var task_id = $(e.target).attr("data-element");
    $("#hidden_task_id_completion").val(task_id);
    $(".dropzone_completion_modal").modal("show");
    // Dropzone.forElement("#imageUpload").removeAllFiles(true);
    // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
  }
  if($(e.target).hasClass('fileattachment'))

  {

    e.preventDefault();

    var element = $(e.target).attr('data-element');

    $('body').addClass('loading');

    setTimeout(function(){

      SaveToDisk(element,element.split('/').reverse()[0]);

      $('body').removeClass('loading');

      }, 3000);

    return false; 

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

  if($(e.target).hasClass('remove_dropzone_attach_add'))
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
  if($(e.target).hasClass('remove_notepad_attach_add'))
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

            window.location.reload();

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
        $("body").addClass("loading");

        var id = $(e.target).attr('data-element');

        $.ajax({

            url:"<?php echo URL::to('user/infile_download_bpso_all_image'); ?>",

            type:"get",

            data:{type:"p",id:id},

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
  if($(e.target).hasClass('download_s_all_image')){
      var lenval = $(e.target).parents("table").find(".s_check:checked").length;
      if(lenval > 0)
      {
        $("body").addClass("loading");

        var id = $(e.target).attr('data-element');

        $.ajax({

            url:"<?php echo URL::to('user/infile_download_bpso_all_image'); ?>",

            type:"get",

            data:{type:"s",id:id},

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
          dataType: "json",       
          success:function(result){
            $("#bspo_id_"+result['id']).html(result['table_content']);
            $("body").removeClass("loading");
            $('[data-toggle="tooltip"]').tooltip();
                           
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

    var leftposi = parseInt(pos.left) - 200;

    $(e.target).parent().find('.notepad_div').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();



  }



  if($(e.target).hasClass('fanotepad_progress')){
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left) - 200;
    $(e.target).parent().find('.notepad_div_progress_notes').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
  }
  if($(e.target).hasClass('fanotepad_completion')){
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left) - 200;
    $(e.target).parent().find('.notepad_div_completion_notes').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
  }
   if($(e.target).hasClass('fanotepadadd')){
    var clientid = $("#client_search").val();
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left) - 20;
    $(e.target).parent().find('.notepad_div_notes_add').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
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
    $(".report_div").toggle();
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
    console.log(status);
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
})

$(window).click(function(e) {
  $.ajax({ 
      url:"<?php echo URL::to('user/get_task_redline_notification'); ?>",
      type:"post",
      success:function(result)
      {
        var ids = result.split(",");
        $.each(ids, function(index,value) {
          $(".redlight_indication_"+value).show();
          $(".redlight_indication_layout_"+value).show();

          $(".redlight_indication_"+value).addClass('redline_indication');
          $(".redlight_indication_layout_"+value).addClass('redline_indication_layout');
        })
      }
  })
});
$(window).change(function(e) {
  if($(e.target).hasClass('select_view'))
  {
    var view = $(e.target).val();
    var layout = $("#hidden_compressed_layout").val();
    $(".tasks_tr").hide();
    $(".tasks_tr").next().hide();
    $(".hidden_tasks_tr").hide();
    $(".table_layout").find(".hidden_tasks_tr").find("td").css("background","#dcdcdc");
    if(view == "3")
    {
      $("#open_task_count").hide();
      $("#redline_task_count").hide();
      $("#authored_task_count").show();
      if(layout == "1")
      {
        $(".author_tr:first").show();
        $(".author_tr:first").next().show();
        $(".table_layout").show();
        $(".table_layout").find(".hidden_author_tr").show();
        $(".table_layout").find(".hidden_author_tr:first").find("td").css("background","#2fd9ff");
      }
      else{
        $(".author_tr").show();
        $(".author_tr").next().show();
        $(".table_layout").hide();
        $(".table_layout").find(".hidden_tasks_tr").hide();
      }
      var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
      var opentask = $(".hidden_allocated_tr").length;
      var authored = $(".hidden_author_tr").length;

      var parktask = $("#park_task_count_val").html();
                    var countpark = parseInt(parktask) + 1;
                    $("#park_task_count_val").html(countpark);

      $("#redline_task_count_val").html(redline);
      $("#open_task_count_val").html(opentask);
      $("#authored_task_count_val").html(authored);
    }
    else if(view == "2")
    {
      $("#open_task_count").hide();
      $("#redline_task_count").show();
      $("#authored_task_count").hide();
      if(layout == "1")
      {
        var i = 1;
        $(".redline_indication").each(function() {
          if(i == 1)
          {
            if($(this).parents(".tasks_tr").hasClass('allocated_tr'))
            {
              $(this).parents(".allocated_tr").show();
              $(this).parents(".allocated_tr").next().show();
              i++;
            }
          }
        });
        $(".table_layout").show();
        $(".redline_indication_layout").parents(".hidden_allocated_tr").show();
        
        var j = 1;
        $(".redline_indication_layout").each(function() {
          if(j == 1)
          {
            if($(this).parents(".hidden_tasks_tr").hasClass('hidden_allocated_tr'))
            {
              $(this).parents(".hidden_allocated_tr").find("td").css("background","#2fd9ff");
              j++;
            }
          }
        });
      }
      else{
        $(".redline_indication").parents(".allocated_tr").show();
        $(".redline_indication").parents(".tasksallocated_tr_tr").next().show();
        $(".table_layout").hide();
        $(".table_layout").find(".hidden_allocated_tr").hide();
      }

      var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
      var opentask = $(".hidden_allocated_tr").length;
      var authored = $(".hidden_author_tr").length;

      var parktask = $("#park_task_count_val").html();
                    var countpark = parseInt(parktask) + 1;
                    $("#park_task_count_val").html(countpark);

      $("#redline_task_count_val").html(redline);
      $("#open_task_count_val").html(opentask);
      $("#authored_task_count_val").html(authored);

    }
    else if(view == "1")
    {
      $("#open_task_count").show();
      $("#redline_task_count").hide();
      $("#authored_task_count").hide();
      if(layout == "1")
      {
        $(".allocated_tr:first").show();
        $(".allocated_tr:first").next().show();
        $(".table_layout").show();
        $(".table_layout").find(".hidden_allocated_tr").show();
        $(".table_layout").find(".hidden_allocated_tr:first").find("td").css("background","#2fd9ff");
      }
      else{
        $(".allocated_tr").show();
        $(".allocated_tr").next().show();
        $(".table_layout").hide();
        $(".table_layout").find(".hidden_tasks_tr").hide();
      }
      var redline = $(".redline_indication_layout").parents(".hidden_allocated_tr").length;
      var opentask = $(".hidden_allocated_tr").length;
      var authored = $(".hidden_author_tr").length;

      var parktask = $("#park_task_count_val").html();
                    var countpark = parseInt(parktask) + 1;
                    $("#park_task_count_val").html(countpark);
                    
      $("#redline_task_count_val").html(redline);
      $("#open_task_count_val").html(opentask);
      $("#authored_task_count_val").html(authored);
    }

    if(layout == "1")
    {
      $(".open_layout_div").addClass("open_layout_div_change");
      var open_tasks_height = $(".open_layout_div").height();
      var margintop = parseInt(open_tasks_height);
      $(".open_layout_div").css("position","fixed");
      $(".open_layout_div").css("height","312px");
      if(open_tasks_height > 312)
      {
        $(".open_layout_div").css("overflow-y","scroll");
      }
      if(open_tasks_height < 50)
      {
        $(".table_layout").css("margin-top","20px");
      }
        else{
          $(".table_layout").css("margin-top","335px");
        }
    }
    else{
      $(".open_layout_div").removeClass("open_layout_div_change");
      $(".open_layout_div").css("position","unset");
      $(".open_layout_div").css("height","auto");
      $(".open_layout_div").css("overflow-y","unset");
        $(".table_layout").css("margin-top","0px");
    }
  }
  if($(e.target).hasClass('select_user_home'))
  {
    var value = $(e.target).val();
    $.ajax({
      url:"<?php echo URL::to('user/change_taskmanager_user'); ?>",
      type:"post",
      data:{user:value},
      success: function(result)
      {
        window.location.replace("<?php echo URL::to('user/task_manager'); ?>");
      }
    });
  }
})
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

Dropzone.options.imageUpload = {
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
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
            Dropzone.forElement("#imageUpload").removeAllFiles(true);
            Dropzone.forElement("#imageUpload2").removeAllFiles(true);
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
            $.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};

Dropzone.options.imageUpload2 = {
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
            $("#add_files_attachments_completion_div_"+obj.task_id).append("<p><a href='"+obj.download_url+"' class='file_attachments' download>"+obj.filename+"</a> <a href='"+obj.delete_url+"' class='fa fa-trash delete_attachments'></a></p>");
            $(".dropzone_progress_modal").modal("hide");
            $(".dropzone_completion_modal").modal("hide");
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");

            Dropzone.forElement("#imageUpload").removeAllFiles(true);
            Dropzone.forElement("#imageUpload2").removeAllFiles(true);
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
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id; // Getting the new upload ID from the server via a JSON response
            if(obj.id != 0)
            {
              file.previewElement.innerHTML = "<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach_add' data-task='"+obj.task_id+"'>Remove</a></p>";
            }
            else{
              $("#attachments_text").show();
              $("#add_attachments_div").append("<p>"+obj.filename+" <a href='javascript:' class='remove_dropzone_attach_add' data-task='"+obj.file_id+"'>Remove</a></p>");
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

$.ajaxSetup({async:false});

$( "#create_job_form" ).validate({

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
</script>
@stop
