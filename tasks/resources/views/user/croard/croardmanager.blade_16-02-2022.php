@extends('userheader')
@section('content')
<script src='<?php echo URL::to('assets/js/table-fixed-header_croard.js'); ?>'></script>
<script src="<?php echo URL::to('assets/ckeditor/src/js/main1.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>
<style>
  .start_group{clear:both;}
.modal{
  z-index: 99999999;
}
#colorbox{
  z-index: 99999999999;
}
.attachment_div{
  margin-top: 10px;
  margin-left: 25px;
}
.add_attachment_month_year{
  float:left;
}
.email_unsent_label{
  margin-top: 10px;
margin-left: 25px;
}
.email_unsent{
  float:left;
}
.dz-remove{
    color: #000;
    font-weight: 800;
    text-transform: uppercase;
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
.status_icon{
padding: 10px;
width: 50%;
border-radius: 39px;
text-align: center;
}
.red_status{ background: #f00; color:#fff !important; }
.orange_status{ background: orange; color:#000 !important; }
.green_status{ background: green; color:#fff !important; }
.blue_status{ background: blue;color:#fff !important;  }
.yellow_status{ background: yellow !important; color:#000 !important;}

.table>thead>tr>th { background: #fff !important; }
.fa-sort{ cursor:pointer; }
.company_td { font-weight:800; }
.form-control[disabled] { background-color:#ececec !important; cursor: pointer; }
.fa-check { color:green; }
.fa-times { color:#f00; }
.fa { font-size:20px; }
#table_administration_wrapper{ width:98%; }
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    999999999999999;
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
.modal_load_content {
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
body.loading_content {
    overflow: hidden;   
}
body.loading_content .modal_load_content {
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


.ui-widget{z-index: 999999999}
.ui-widget .ui-menu-item-wrapper{font-size: 14px; font-weight: bold;}
.ui-widget .ui-menu-item-wrapper:hover{font-size: 14px; font-weight: bold}
.file_attachment_div{width:100%;}

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
label{width:100%;}

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
.tr_closed{
  display:none;
}
.show_closed>.tr_closed{
  display:table-row !important;
}
/* Customize the label (the container) */
.form_checkbox {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default checkbox */
.form_checkbox input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}

/* Create a custom checkbox */
.checkmark_checkbox {
  position: absolute;
  top: 0;
  left: 0;
  height: 20px;
  width: 20px;
  background-color: #fff;
  border:1px solid;
}

/* On mouse-over, add a grey background color */
.form_checkbox:hover input ~ .checkmark_checkbox {
  background-color: #fff;
  border:1px solid;
}

/* When the checkbox is checked, add a blue background */
.form_checkbox input:checked ~ .checkmark_checkbox {
  background-color: #fff;

}

/* Create the checkmark_checkbox/indicator (hidden when not checked) */
.checkmark_checkbox:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the checkmark_checkbox when checked */
.form_checkbox input:checked ~ .checkmark_checkbox:after {
  display: block;
}

/* Style the checkmark_checkbox/indicator */
.form_checkbox .checkmark_checkbox:after {
  left: 7px;
  top: 3px;
  width: 5px;
  height: 10px;
  border: solid #3a3a3a;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}

.form_radio {
  display: block;
  position: relative;
  padding-right: 20px;
  margin-bottom: 12px;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}

/* Hide the browser's default radio button */
.form_radio input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}

/* Create a custom radio button */
.checkmark_radio {
  position: absolute;
  top: 0;
  left: 0;
  height: 20px;
  width: 20px;
  background-color: #fff;
  border-radius: 50%;
  border:1px solid #3a3a3a;
}

/* On mouse-over, add a grey background color */
.form_radio:hover input ~ .checkmark_radio {
  background-color: #fff;
}

/* When the radio button is checked, add a blue background */
.form_radio input:checked ~ .checkmark_radio {
  background-color: #fff;
}

/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark_radio:after {
  content: "";
  position: absolute;
  display: none;
}

/* Show the indicator (dot/circle) when checked */
.form_radio input:checked ~ .checkmark_radio:after {
  display: block;
}

/* Style the indicator (dot/circle) */
.form_radio .checkmark_radio:after {
  top: 5px;
  left: 5px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background:green;
}
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
.form_radio .text{}
.form_radio span{right: 0px; left: unset;}
input[type="checkbox"]:not(old) + label, input[type="radio"]:not(old) + label { margin-left:0px; }
</style>
<script src="<?php echo URL::to('ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('ckeditor/samples/js/sample.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>">
<link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> ">
<?php 
  $admin_details = Db::table('admin')->first();
  $admin_cc = $admin_details->task_cc_email;
?> 
<div class="modal fade create_new_task_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;overflow-y: scroll;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:45%">
    <form action="<?php echo URL::to('user/create_new_taskmanager_task_croard')?>" method="post" class="add_new_form" id="create_task_form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">New Task Creator</h4>
          </div>
          <div class="modal-body modal_max_height">            
            <div class="row"> 
                <div class="col-md-3">
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
                <div class="col-md-2">
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
              <div class="col-md-3 client_group">
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
                <div class="col-md-7 client_group">
                  <input  type="text" class="form-control client_search_class_task" name="client_name" placeholder="Enter Client Name / Client ID" value="" readonly required>
                  <input type="hidden" id="client_search_task" name="clientid" value=""/>
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
              <div class="col-md-3 padding_00">
                <div class="form-title"><label style="margin-top:5px">Priority:</label></div>
              </div>
              <div class="col-md-9 padding_00">
                <?php echo user_rating(); ?>
              </div>
            </div>
            <div class="form-group start_group" style="margin-top:10px">
                <div class="form-title"><label style="margin-top:5px">Subject:</label></div>
                <input  type="text" class="form-control subject_class" name="subject_class" placeholder="Enter Subject">
            </div>
            <div class="form-group start_group task_specifics_add">
                <div class="form-title" style="float:none"><label style="margin-top:5px">Task Specifics:</label></div>
                <textarea class="form-control task_specifics" id="editor_2" name="task_specifics" placeholder="Enter Task Specifics" style="height:400px"></textarea>
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
            <div class="form-group date_group">
                <div class="form-title" style="font-weight:600;margin-left:-10px">
                  <input type='checkbox' name="2_bill_task" class="2_bill_task" id="2_bill_task0" value="1"/> 
                  <label for="2_bill_task0" style="color:green">This task is a 2Bill Task!</label>
                  <img src="<?php echo URL::to('assets/2bill.png')?>" style="width:40px;margin-left:8px">
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
                    <form action="<?php echo URL::to('user/croard_upload_images'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">
                        <input name="hidden_client_id_croard" id="hidden_client_id_croard" type="hidden" value="">
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

<div class="modal fade automatic_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-sm" role="document" style="width:70%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" >Updating - Awaiting CRO ARD</h4>
          </div>
          <div class="modal-body automatic_tbody" style="min-height:280px">  
              
          </div>
          <div class="modal-footer">  
            
          </div>
        </div>
  </div>
</div>
<div class="modal fade name_verify_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-sm" role="document" style="width:60%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Company name discrepancy</h4>
          </div>
          <div class="modal-body automatic_tbody" style="min-height:280px">  
              <a href="javascript:" class="common_black_button refresh_blue_client" style="float:right;margin-bottom: 10px">Refresh The Client From CRO Details</a>
              <table class="table">
                <thead>
                  <th>Company Number</th>
                  <th>CM System Name</th>
                  <th>Type</th>
                  <th>CRO Name</th>
                  <th>CRO Number</th>
                  <th>CRO ARD</th>
                </thead>
                <tbody id="name_verify_tbody">
                </tbody>
              </table>
          </div>
          <div class="modal-footer">  
            
          </div>
        </div>
  </div>
</div>
<div class="modal fade croard_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-sm" role="document" style="width:40%">
        <div class="modal-content">
          <form name="croard_settings_form" id="croard_settings_form" method="post" action="<?php echo URL::to('user/save_croard_settings'); ?>">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title job_title" >CRO-ARD Settings</h4>
            </div>
            <div class="modal-body">  
                <h4>Enter Email Signature:</h4>
                <textarea name="message_editor" id="editor1"><?php echo $admin_details->croard_signature; ?></textarea>
                <h4>Enter CC Box:</h4>
                <input type="text" name="croard_cc_input" class="form-control croard_cc_input" value="<?php echo $admin_details->croard_cc_email; ?>">
                <h4>Submission Days Allowed After ARD Date:</h4>
                <input type="text" name="croard_days_input" class="form-control croard_days_input" value="<?php echo $admin_details->croard_submission_days; ?>" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                <?php $cro = DB::table('cro_credentials')->first(); ?>
                <h4>Username:</h4>
                <input id="validation-email" class="form-control" placeholder="Enter Username" value="<?php echo $cro->username; ?>" name="username" type="text" required > 
                <label>API Key</label>
                <input id="validation-cc-email" class="form-control" placeholder="Enter API Key" value="<?php echo $cro->api_key; ?>" name="api_key" type="text" required> 

            </div>
            <div class="modal-footer">  
                <input type="submit" name="submit_croard_settings" class="common_black_button submit_croard_settings" value="Submit">
            </div>
          </form>
        </div>
  </div>
</div>
<div class="modal fade rbo_review_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-lg" role="document" style="width:80%">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">RBO REVIEW</h4>
            </div>
            <div class="modal-body" style="clear:both">  
              <input type="button" name="show_ltd_rbo" id="show_ltd_rbo" class="common_black_button show_ltd_rbo" value="Show Active Ltd Clients Only" style="float:right">
              <input type="button" name="report_csv_rbo" id="report_csv_rbo" class="common_black_button report_csv_rbo" value="Report CSV" style="float:right">

              <div class="col-md-12" id="rbo_review_tbody">
              </div>
            </div>
            <div class="modal-footer" style="clear:both">  
                
            </div>
        </div>
  </div>
</div>
<div class="modal fade emailunsent" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document">
    <form id="email_unsent_form" action="<?php echo URL::to('user/email_unsent_files_croard'); ?>" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Send Email</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              <label>From</label>
            </div>
            <?php
              $userlist = DB::table('user')->where('user_status', 0)->where('disabled',0)->orderBy('lastname','asc')->get();
              $uname = '<option value="">Select Username</option>';
              if(count($userlist)){
                foreach ($userlist as $singleuser) {
                    if($uname == '')
                    {
                      $uname = '<option value="'.$singleuser->user_id.'">'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                    }
                    else{
                      $uname = $uname.'<option value="'.$singleuser->user_id.'">'.$singleuser->lastname.' '.$singleuser->firstname.'</option>';
                    }
                  }
              }
            ?>
            <div class="col-md-9">
              <select name="select_user" id="select_user" class="form-control" title="Select the User" required>
                <?php echo $uname; ?>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>To</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="to_user" id="to_user" class="form-control" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>CC</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="cc_unsent" class="form-control" value="<?php echo $admin_details->croard_cc_email; ?>" readonly required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>Subject</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="subject_unsent" class="form-control subject_unsent" value="" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Message</label>
            </div>
            <div class="col-md-12">
              <textarea name="message_editor" id="editor">
              </textarea>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Attachment</label>
            </div>
            <div class="col-md-12" id="email_attachments">
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hidden_client_id_email_croard" id="hidden_client_id_email_croard" value="">
        <input type="button" class="btn btn-primary common_black_button email_unsent_files_btn" value="Send Email">
      </div>
    </div>
    </form>
  </div>
</div>
<div class="modal fade search_company_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog" role="document" style="width:50%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Search Company</h4>
          </div>
          <div class="modal-body modal_max_height">  
            <div class="row">
              <div class="col-md-3">
                <h5>Company number:</h5>
                <input type="text" name="company_number" class="form-control company_number" value="">
              </div>
              <div class="col-md-9">
                <h5>Company / Business indicator:</h5>
                <div class="col-md-4">
                  <input type="radio" name="indicator" class="indicator" id="indicator_1" value="C"><label for="indicator_1" style="margin-top:3px">Limited Company</label>
                  <!-- <div class="form-group">
                     <label class="form_radio">Limited Company
                      <input type="radio" value="" style="width:1px; height:1px" name="test"  required>
                      <span class="checkmark_radio"></span>
                    </label>
                  </div> -->
                </div>
                <div class="col-md-8">
                  <input type="radio" name="indicator" class="indicator" id="indicator_2" value="B"><label for="indicator_2" style="width: 56%;margin-top:3px">Registered Business</label>
                  <input type="button" class="common_black_button search_company_btn" id="search_company_btn" value="Call From CRO">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-10 col-md-offset-1 table_api" style="margin-top:10px;">
                  <table class="table">
                    <tbody>
                      <tr>
                        <td>Company Number:</td>
                        <td><input type="text" name="company_number" class="form-control company_number" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company / Business indicator:</td>
                        <td><input type="text" name="indicator_text" class="form-control indicator_text" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Name:</td>
                        <td><input type="text" name="company_name" class="form-control company_name" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Address:</td>
                        <td><textarea name="company_address" class="form-control company_address" disabled style="height:110px"></textarea></td>
                      </tr>
                      <tr>
                        <td>Company Registration Date:</td>
                        <td><input type="text" name="company_reg_date" class="form-control company_reg_date" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Status:</td>
                        <td><input type="text" name="company_status_desc" class="form-control company_status_desc" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Status Date:</td>
                        <td><input type="text" name="company_status_date" class="form-control company_status_date" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Next ARD:</td>
                        <td><input type="text" name="next_ar_date" class="form-control next_ar_date" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Last ARD:</td>
                        <td><input type="text" name="last_ar_date" class="form-control last_ar_date" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Accounts Upto:</td>
                        <td><input type="text" name="last_acc_date" class="form-control last_acc_date" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Type:</td>
                        <td><input type="text" name="comp_type_desc" class="form-control comp_type_desc" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Type Code:</td>
                        <td><input type="text" name="company_type_code" class="form-control company_type_code" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Status Code:</td>
                        <td><input type="text" name="company_status_code" class="form-control company_status_code" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Place of Business:</td>
                        <td><input type="text" name="place_of_business" class="form-control place_of_business" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Eircode:</td>
                        <td><input type="text" name="eircode" class="form-control eircode" value="" disabled></td>
                      </tr>
                    </tbody>
                  </table>
              </div>
            </div>
          </div>
        </div>
  </div>
</div>
<div class="content_section" style="margin-bottom:200px">

<div class="page_title" style="z-index:999;">
  <h4 class="col-lg-12 padding_00 new_main_title">
            CRO - ARD Manager               
        </h4>
</div>
<div class="row">
  <div class="col-lg-12" style="padding-right: 0px;">
<div class="col-md-2 padding_00">
  <input type="checkbox" name="show_incomplete" id="show_incomplete" value="1"><label for="show_incomplete">Hide Deactivated Accounts</label>
</div>

      <div class="col-lg-2" style="text-align: left; width: 9%;">
        <label style="margin-top: 5px;">CRO Api Username:</label>
      </div>
      <div class="col-lg-1" style="padding:0px">
        <input type="text" name="cro_username" class="form-control cro_username" id="cro_username" value="<?php echo $cro->username; ?>" disabled>
      </div>
      <div class="col-lg-1" style="text-align: left; width: 7%;">
        <label style="margin-top: 5px;">CRO Api Key:</label>
      </div>
      <div class="col-lg-2" style="padding:0px">
        <input type="text" name="cro_api" class="form-control cro_api" id="cro_api" value="<?php echo $cro->api_key; ?>" disabled>
      </div>
      <div class="col-md-5 text-right padding_00">
          <input type="button" name="check_company" class="common_black_button check_company" value="Check Company" data-toggle="modal" data-target=".search_company_modal">
          <input type="button" name="global_core_call" class="common_black_button global_core_call" value="Global Core Call"> 
          <input type="button" name="show_ltd" id="show_ltd" class="common_black_button show_ltd" value="Show Active Ltd Clients Only">
          <input type="button" name="rbo_review_btn" id="rbo_review_btn" class="common_black_button rbo_review_btn" value="RBO Review">
          <a href="javascript:" id="settings_croard" class="fa fa-cog common_black_button"></a>
        </div>
  </div>
</div>
    <div class="table-responsive" style="width: 100%; float: left;margin-top:10px">
      
      <div class="col-md-12" style="margin-top:0px;">
        <div class="col-md-6">&nbsp;
        </div>
        
        
      </div>
      	<table class="table table-fixed-header own_table_white" style="width:100%;margin-top:0px; background: #fff">
	        <thead class="header">
	            <th style="width:3.5%;text-align: left;">S.No <i class="fa fa-sort sno_sort" aria-hidden="true" style="float: right;"></i></th>
	            <th style="width:6%;text-align: left;">Client Code <i class="fa fa-sort clientid_sort" aria-hidden="true" style="float: right;"></i></th>
	            <th style="width:25%;text-align: left;">Company Name <i class="fa fa-sort company_sort" aria-hidden="true" style="float: right;"></i></th>
	            <th style="width:7%;text-align: left;">CRO Number <i class="fa fa-sort cro_sort" aria-hidden="true" style="float: right;"></i></th>
	            <th style="width:10%;text-align: left;">Type <i class="fa fa-sort type_sort" aria-hidden="true" style="float: right;"></i></th>
	            <th style="width:10%;text-align: left;">CRO ARD <i class="fa fa-sort cro_ard_sort" aria-hidden="true" style="float: right;"></i></th>
              <th style="width:25%;text-align: left;">NOTES </th>
	            <th style="width:5%;text-align: left;">Action</th>
	        </thead>                            
        	<tbody id="clients_tbody">
	        <?php
	        $ivall=1;
          $admin_details = DB::table('admin')->first();
          $submission_days = $admin_details->croard_submission_days;

	        if(count($clientlist)){              
		        foreach($clientlist as $key => $client){
	              $disabled='';
                $style="color:#000";
	              if($client->active != "")
	              {
	                if($client->active == 2)
	                {
	                  $disabled='disabled_tr';
                    $style="color:#f00";
	                }
	                $check_color = DB::table('cm_class')->where('id',$client->active)->first();
	              }
                $last_submission = '';
	              $cmp = '<spam class="company_td" style="font-style:italic;"></spam>';
	              $cr_ard_date = '';
	              $ard_color = '';
                $timestampcroard = '';
	              $cro_ard_details = DB::table('croard')->where('client_id',$client->client_id)->first();
                $notes = '';
                $color_status = '';
                $status_label = '';
                $attachment = '';
                $last_email_sent = '';
                $signature_checked = '';
                $yellow_status = '';
                $yellow_label = '';
                $signature_file_date = '';
                $rbo_submission = '';
	              if(count($cro_ard_details))
	              {
                  if($cro_ard_details->filename != "")
                  {
                    $attachment = '<a class="attachment_link" href="'.URL::to($cro_ard_details->url.'/'.$cro_ard_details->filename).'" download>'.$cro_ard_details->filename.'</a>';
                  }
                  if($cro_ard_details->signature == 1)
                  {
                    $signature_checked = 'checked';
                    $yellow_status = 'yellow_status';
                    $yellow_label = '';
                    
                  }
	              	if($cro_ard_details->signature_date != "")
                  {
                    $signature_file_date = date('d/m/Y', strtotime($cro_ard_details->signature_date));
                  }
                  if($cro_ard_details->last_email_sent != "")
                  {
                    $last_email_sent = date('d F Y @ H : i', strtotime($cro_ard_details->last_email_sent));
                  }
                  $notes = $cro_ard_details->notes;

                  $clientname_company = preg_replace('/[[:^print:]]/', '', strtolower($client->company));
                  $croname_company = preg_replace('/[[:^print:]]/', '', strtolower($cro_ard_details->company_name));
                  

	              	if($cro_ard_details->cro_ard != "")
	              	{
                    $exp_api_date_month = explode("/",$cro_ard_details->cro_ard);
                    $api_date_month = '';
                    if(count($exp_api_date_month))
                    {
                      $api_date_month = $exp_api_date_month[0].'/'.$exp_api_date_month[1];
                    }
		              	$ard = explode("/",$client->ard);
		              	if(count($ard) > 1)
		              	{
		              		$ard_date_month = $ard[0].'/'.$ard[1];
		              	}
        						else{
        							$ard_date_month = '';
        						}

		              	if($ard_date_month == $api_date_month)
		              	{
		              		$cr_ard_date = $cro_ard_details->cro_ard;

                      if($cro_ard_details->cro_ard == "")
                      {
                        $timestampcroard = '';
                      }
                      else{
                        $expandcroard = explode('/',$cro_ard_details->cro_ard);
                        if(count($expandcroard) > 1)
                        {
                          $correctcroard = $expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0];
                          $timestampcroard = strtotime($expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0]);
                          $dd = date('d/m/Y', strtotime($expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0]. ' + '.$submission_days.' days'));

                          $current_date = date('Y-m-d');
                          $current_year = date('Y');
                          $croard_year = $expandcroard[2];

                          if($croard_year > $current_year)
                          {
                            $color_status = 'blue_status';
                            $status_label = 'Current Year OK';
                            $ard_color = 'color:blue';
                          }
                          else{
                            $firstdate = strtotime($correctcroard);
                            $seconddate = strtotime($current_date);
                            $diff = ceil(($firstdate - $seconddate)/60/60/24);
                            if($diff < 0 || $diff == 0)
                            {
                              $color_status = 'red_status';
                              $status_label = 'Submission Late';
                              $ard_color = 'color:red';

                              $last_submission = '<strong>Last Submission Date: <spam>'.$dd.'</spam></strong>';
                            }
                            elseif($diff <= 30)
                            {
                              $color_status = 'orange_status';
                              $status_label = 'Submission Pending';
                              $ard_color = 'color:orange';
                            }
                            elseif($diff > 30)
                            {
                              $color_status = 'green_status';
                              $status_label = 'Future Submission';
                              $ard_color = 'color:green';
                            }
                          }
                        }
                        else{
                          $timestampcroard = '';
                        }
                      }
		              	}
		              	else{
		              		$cr_ard_date = $cro_ard_details->cro_ard;

                      if($cro_ard_details->cro_ard == "")
                      {
                        $timestampcroard = '';
                      }
                      else{
                        $expandcroard = explode('/',$cro_ard_details->cro_ard);
                        if(count($expandcroard) > 1)
                        {
                          $correctcroard = $expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0];
                          $timestampcroard = strtotime($expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0]);
                          $dd = date('d/m/Y', strtotime($expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0]. ' + '.$submission_days.' days'));

                          $current_date = date('Y-m-d');
                          $current_year = date('Y');
                          $croard_year = $expandcroard[2];

                          if($croard_year > $current_year)
                          {
                            $color_status = 'blue_status';
                            $status_label = 'Current Year OK';
                            $ard_color = 'color:blue';
                          }
                          else{
                            $firstdate = strtotime($correctcroard);
                            $seconddate = strtotime($current_date);

                            $diff = ceil(($firstdate - $seconddate)/60/60/24);
                            if($diff < 0 || $diff == 0)
                            {
                              $color_status = 'red_status';
                              $status_label = 'Submission Late';
                              $ard_color = 'color:red';
                              $last_submission = '<strong>Last Submission Date: <spam>'.$dd.'</spam></strong>';
                            }
                            elseif($diff <= 30)
                            {
                              $color_status = 'orange_status';
                              $status_label = 'Submission Pending';
                              $ard_color = 'color:orange';
                            }
                            elseif($diff > 30)
                            {
                              $color_status = 'green_status';
                              $status_label = 'Future Submission';
                              $ard_color = 'color:green';
                            }
                          }
                        }
                        else{
                          $timestampcroard = '';
                        }
                      }
		              	}
	              	}

                  if($clientname_company == $croname_company)
                  {
                    $cmp = '<spam class="company_td" style="color:green;font-style:italic">'.$cro_ard_details->company_name.'</spam>';
                  }
                  else{
                    $cmname = ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company;
                    $cmp = '<spam class="company_td company_blue" data-crono="'.$client->client_id.'" data-cmname="'.$cmname.'" data-croname="'.$cro_ard_details->company_name.'" data-croard="'.$cr_ard_date.'" data-cronumber="'.$client->cro.'" data-type="'.$client->tye.'" style="color:blue;font-style:italic;font-weight:800">'.$cro_ard_details->company_name.'</spam>';
                  }
                  $rbo_submission = $cro_ard_details->rbo_submission;
	              }

                if($client->ard == "")
                {
                  $timestampard = '';
                }
                else{
                  $expand = explode('/',$client->ard);
                  if(count($expand) > 1)
                  {
                    $correctard = $expand[2].'-'.$expand[1].'-'.$expand[0];
                      $timestampard = strtotime($expand[2].'-'.$expand[1].'-'.$expand[0]);
                  }
                  else{
                    $timestampard = '';
                  }
                }

                
		          ?>
		            <tr class="edit_task <?php echo $disabled; ?>" style="<?php echo $style; ?>"  id="clientidtr_<?php echo $client->client_id; ?>">
		                <td style="<?php echo $style; ?>" class="sno_sort_vall"><?php echo $ivall; ?></td>
		                <td style="<?php echo $style; ?>" class="clientid_sort_val" align="left"><?php echo $client->client_id; ?></td>
		                <td style="<?php echo $style; ?>" align="left"><spam class="company_sort_val"><?php echo ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company; ?></spam> <br/> <?php echo $cmp; ?></td>
		                <td style="<?php echo $style; ?>" class="cro_sort_val" align="left">
                          
                          <?php echo ($client->cro == "")?"-":'<a href="javascript:" class="check_cro" data-element="'.$client->cro.'">'.$client->cro.'</a>'; ?>
                    </td>
		                <td style="<?php echo $style; ?>" class="type_sort_val" align="left"><?php echo ($client->tye == "")?"-":$client->tye; ?></td>
		                <td class="cro_ard_td" style="<?php echo $ard_color; ?>" align="left"><spam class="cro_ard_sort_val" style="display: none"><?php echo $timestampcroard; ?></spam><spam class="cro_ard_val"><?php echo $cr_ard_date; ?></spam></td>
                    <td align="left" style="color:#000">
                      <textarea name="cro_notes" class="form-control cro_notes" data-element="<?php echo $client->client_id; ?>" style="height:50px"><?php echo $notes; ?></textarea>
                      <div class="col-md-12" style="padding-left:0px;padding-right:0px;padding-top:10px;padding-bottom:10px;border-top:1px solid #ccc;border-bottom:1px solid #ccc;margin-top:10px">
                        <div class="col-md-12 padding_00"><span style="float:left;font-size: 18px;margin-right: 10px;margin-top: 5px;">RBO Submisison Reference:</span> <input type="text" name="rbo_submission_text" class="form-control rbo_submission_text" value="<?php echo $rbo_submission; ?>" data-element="<?php echo $client->client_id; ?>" maxlength="10" style="float:left;width:30%"></div>
                      </div>
                      <div class="col-md-12 padding_00" style="margin-bottom: 10px">
                        <div class="col-md-5 padding_00"><h4>Active submissions:</h4></div>
                        <div class="col-md-3 padding_00" style="margin-top:10px">
                          <a href="javascript:" class="common_black_button create_task_manager" data-client="<?php echo $client->client_id; ?>" data-clientname="<?php echo ($client->company == "")?$client->firstname.' & '.$client->surname.' - '.$client->client_id:$client->company.' - '.$client->client_id; ?>" style="padding: 6px 10px;";>Create Task</a>
                        </div>
                      </div>
                       
                        
                      <label class="status_icon <?php echo $color_status; ?> <?php echo $yellow_status; ?>">
                        <spam class="status_label"><?php echo $status_label; ?> </spam>
                        <spam class="yellow_label" style="display:none">Awaiting CRO Update</spam>
                      </label> &nbsp;&nbsp; <?php echo $last_submission; ?><br/>
                      <div class="col-md-12 padding_00">
                        <a href="javascript:" class="fa fa-plus add_attachment_month_year" data-client="<?php echo $client->client_id; ?>" style="margin-top:10px;" aria-hidden="true" title="Add a File"></a> 
                        <div class="attachment_div"><?php echo $attachment; ?></div>
                      </div>
                      <div class="col-md-12 padding_00" style="margin-top:15px">
                        <a href="javascript:" class="fa fa-envelope email_unsent" data-client="<?php echo $client->client_id; ?>" style="margin-top:10px;" aria-hidden="true" title="Send Email"></a>
                        <div class="email_unsent_label"><?php echo $last_email_sent; ?></div>
                      </div>
                      <?php
                      $tasks_det = DB::table('taskmanager_croard')->where('client_id',$client->client_id)->get();
                      if(count($tasks_det))
                      {
                        echo '<div class="col-md-12 padding_00" style="margin-top:15px"><h5>Linked Tasks</h5>';
                        $i = 1;
                        foreach($tasks_det as $task_det)
                        {
                          $task_details = DB::table('taskmanager')->where('id',$task_det->task_id)->first();
                          echo '<p style="float: left;margin-top: 10px;font-weight: 600;">'.$i.'. Task : '.$task_details->taskid.' - '.$task_details->subject.'</p>';
                          $i++;
                        }
                        echo '</div>';
                      }
                      ?>
                      <div class="col-md-12 padding_00" style="margin-top:15px">
                        <input type="checkbox" name="signature_file_check" id="signature_file_check<?php echo $client->client_id; ?>" class="signature_file_check" data-element="<?php echo $client->client_id; ?>" value="" <?php echo $signature_checked; ?>><label for="signature_file_check<?php echo $client->client_id; ?>" 
                          style="width: fit-content;float: left;margin-right: 10px;">Signature file Submitted</label>
                        <input type="text" class="form-control signature_file_date" id="signature_file_date" name="signature_file_date" value="<?php echo $signature_file_date; ?>" data-element="<?php echo $client->client_id; ?>" readonly style="width: 30%;margin-top: -20px;background: #dfdfdf">
                      </div>
                    </td>
		                <td align="left"><a href="javascript:" class="fa fa-refresh refresh_croard" data-element="<?php echo $client->client_id; ?>" data-cro="<?php echo trim($client->cro); ?>" data-type="<?php echo trim($client->tye); ?>" style="<?php echo $style; ?>"></a></td>
		            </tr>
	              <?php
	              $ivall++;
	            }              
            }
            if($ivall == 1)
            {
              echo'<tr><td colspan="11" align="center">Empty</td></tr>';
            }
          	?> 
        	</tbody>
        </table>
    </div>
</div>
<div class="modal_load"></div>
<div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Clients CRO are Checked.</p>
  <p style="font-size:18px;font-weight: 600;">Checking CRO: <span id="apply_first"></span> of <span id="apply_last"></span></p>
</div>
<div class="modal_load_content" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Clients are loaded.</p>
  <p style="font-size:18px;font-weight: 600;">Loading: <span id="count_first"></span> of <span id="count_last"></span></p>
</div>

<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<input type="hidden" name="clientid_sortoptions" id="clientid_sortoptions" value="asc">
<input type="hidden" name="company_sortoptions" id="company_sortoptions" value="asc">
<input type="hidden" name="cro_sortoptions" id="cro_sortoptions" value="asc">
<input type="hidden" name="ard_sortoptions" id="ard_sortoptions" value="asc">
<input type="hidden" name="type_sortoptions" id="type_sortoptions" value="asc">
<input type="hidden" name="cro_ard_sortoptions" id="cro_ard_sortoptions" value="asc">

<input type="hidden" name="sno_rbo_sortoptions" id="sno_rbo_sortoptions" value="asc">
<input type="hidden" name="clientid_rbo_sortoptions" id="clientid_rbo_sortoptions" value="asc">
<input type="hidden" name="company_rbo_sortoptions" id="company_rbo_sortoptions" value="asc">
<input type="hidden" name="cro_rbo_sortoptions" id="cro_rbo_sortoptions" value="asc">
<input type="hidden" name="type_rbo_sortoptions" id="type_rbo_sortoptions" value="asc">
<input type="hidden" name="rbo_ref_sortoptions" id="rbo_ref_sortoptions" value="asc">

<script>
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
$(document).ready(function() {
  <?php if(Session::has('message_client_id')) { ?>
    $(document).scrollTop( $("#clientidtr_<?php echo Session::get('message_client_id'); ?>").offset().top - parseInt(150) );  
    $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:green">Task Created Successfully. </p>',fixed:true,width:"800px"});
  <?php } elseif(Session::has('message_settings')) { ?>
    $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:green">Croard Settings Saved successfully. </p>',fixed:true,width:"800px"});

  <?php } else{  ?>
    var yellow_status = $(".signature_file_check:checked").length;
    var blue_status = $(".company_blue").length;
    if(yellow_status > 0)
    {
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/croard_get_yellow_status_clients'); ?>",
        type:"post",
        success:function(result)
        {
          $(".automatic_tbody").html(result);
          $(".automatic_modal").modal("show");
          $("body").removeClass("loading");


          $("body").addClass("loading_apply");
          var countcro = $(".overlay_cro").length;
          $("#apply_last").html(countcro);
          var cro = $(".overlay_cro:eq(0)").html();
          var client = $(".overlay_cro:eq(0)").attr("data-element");

          $.ajax({
            url:"<?php echo URL::to('user/check_cro_in_api'); ?>",
            type:"post",
            dataType:"json",
            data:{cro:cro,client:client},
            success:function(result)
            {
              if(result['updated'] == 1)
              {
                $(".overlay_tr_"+client).find(".overlay_updated_croard").html(result['croard']);
                $("#clientidtr_"+client).find(".cro_ard_val").html(result['croard']);
                $("#clientidtr_"+client).find(".signature_file_date").val('');

                $("#clientidtr_"+client).find(".status_icon").removeClass('green_status').removeClass('red_status').removeClass('orange_status').removeClass('blue_status').removeClass('yellow_status');
                $("#clientidtr_"+client).find(".status_icon").addClass(result['color_status']);
                $("#clientidtr_"+client).find(".status_label").html(result['status_label']);
                $("#clientidtr_"+client).find(".signature_file_check").prop("checked",false);
                $("#clientidtr_"+client).find(".cro_ard_val").parents("td:first").css("color",result['ard_color']);
                $("#clientidtr_"+client).find(".attachment_div").html("");
              }
              else{
                $("#clientidtr_"+client).find(".signature_file_date").val('');
                $("#clientidtr_"+client).find(".status_icon").removeClass('green_status').removeClass('red_status').removeClass('orange_status').removeClass('blue_status').removeClass('yellow_status');
                $("#clientidtr_"+client).find(".status_icon").addClass(result['color_status']);
                $("#clientidtr_"+client).find(".status_label").html(result['status_label']);
                $("#clientidtr_"+client).find(".signature_file_check").prop("checked",false);
                $("#clientidtr_"+client).find(".cro_ard_val").parents("td:first").css("color",result['ard_color']);
                $("#clientidtr_"+client).find(".attachment_div").html("");
              }
              setTimeout( function() {
                if($(".overlay_cro:eq(1)").length > 0)
                {
                  next_cro_check(1);
                  $("#apply_first").html(1);
                }
                else{
                  $("body").removeClass("loading_apply");
                }
            },200);
            }
          });
        }
      })
    }
    else{
      if(blue_status > 0)
      {
        $("body").addClass('loading');
        var html_output = '';
        $(".company_blue").each(function() {
          var company_number = $(this).attr("data-crono");
          var cm_name = $(this).attr("data-cmname");
          var cro_name = $(this).attr("data-croname");
          var cro_number = $(this).attr("data-cronumber");
          var cro_ard = $(this).attr("data-croard");
          var type = $(this).attr("data-type");
          if(type == "Ltd" || type == "ltd")
          {
            html_output+='<tr><td class="refresh_blue_croard" data-element="'+company_number+'" data-cro="'+cro_number+'" data-type="'+type+'">'+company_number+'</td><td>'+cm_name+'</td><td>'+type+'</td><td>'+cro_name+'</td><td>'+cro_number+'</td><td>'+cro_ard+'</td></tr>';
          }
          
        })
        $("#name_verify_tbody").html(html_output);
        $(".name_verify_modal").modal("show");
        $("body").removeClass('loading');
      }
    }
  <?php } ?>
  $('.table-fixed-header').fixedHeader();
  $(".signature_file_check:checked").parents("td").find(".status_label").hide();
  $(".signature_file_check:checked").parents("td").find(".yellow_label").show();
  $(".signature_file_date").datetimepicker({
     defaultDate: "",
       format: 'L',
       format: 'DD/MM/YYYY',
  });

  $(".signature_file_date").on("dp.hide", function (e) {
    var client = $(e.target).attr("data-element");
    var date = $(e.target).val();
    $.ajax({
      url:"<?php echo URL::to('user/save_croard_signature_date'); ?>",
      type:"post",
      data:{client:client,date:date},
      success:function(result)
      {

      }
    })
  }); 

    
});

function next_cro_check(count)
{
  var cro = $(".overlay_cro:eq("+count+")").html();
  var client = $(".overlay_cro:eq("+count+")").attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/check_cro_in_api'); ?>",
    type:"post",
    dataType:"json",
    data:{cro:cro,client:client},
    success:function(result)
    {
      if(result['updated'] == 1)
      {
        $(".overlay_tr_"+client).find(".overlay_updated_croard").html(result['croard']);
        $("#clientidtr_"+client).find(".cro_ard_val").html(result['croard']);
        $("#clientidtr_"+client).find(".signature_file_date").val('');

        $("#clientidtr_"+client).find(".status_icon").removeClass('green_status').removeClass('red_status').removeClass('orange_status').removeClass('blue_status').removeClass('yellow_status');
        $("#clientidtr_"+client).find(".status_icon").addClass(result['color_status']);
        $("#clientidtr_"+client).find(".status_label").html(result['status_label']);
        $("#clientidtr_"+client).find(".signature_file_check").prop("checked",false);
        $("#clientidtr_"+client).find(".cro_ard_val").parents("td:first").css("color",result['ard_color']);
        $("#clientidtr_"+client).find(".attachment_div").html("");
      }
      else{
        $("#clientidtr_"+client).find(".signature_file_date").val('');
        $("#clientidtr_"+client).find(".status_icon").removeClass('green_status').removeClass('red_status').removeClass('orange_status').removeClass('blue_status').removeClass('yellow_status');
        $("#clientidtr_"+client).find(".status_icon").addClass(result['color_status']);
        $("#clientidtr_"+client).find(".status_label").html(result['status_label']);
        $("#clientidtr_"+client).find(".signature_file_check").prop("checked",false);
        $("#clientidtr_"+client).find(".cro_ard_val").parents("td:first").css("color",result['ard_color']);
        $("#clientidtr_"+client).find(".attachment_div").html("");
      }
      setTimeout( function() {
        var countval = count + 1;
        if($(".overlay_cro:eq("+countval+")").length > 0)
        {
          next_cro_check(countval);
          $("#apply_first").html(countval);
        }
        else{
          $("body").removeClass("loading_apply");
          $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">CRO ARD Date Updated Successfully. </p>',fixed:true,width:"800px"});
        }
      },200);
    }
  });
}
function refresh_all_function(ival)
{
	var ival = ival + 1;
	var countval = $(".refresh_croard").length;
	var clientid = $(".refresh_croard:eq("+ival+")").attr("data-element");
	var cro = $(".refresh_croard:eq("+ival+")").attr("data-cro");
	var type = $(".refresh_croard:eq("+ival+")").attr("data-type");

	$("#count_first").html(ival);
  if(cro == "")
  {
    $.ajax({
        url:"<?php echo URL::to('user/remove_croard_refresh'); ?>",
        type:"post",
        data:{clientid:clientid},
        success:function(result)
        {
          $("#clientidtr_"+clientid).find(".cro_ard_td").html('');
          $("#clientidtr_"+clientid).find(".company_blue").html('').removeClass('company_blue');
          if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
          else { 
            setTimeout(function() {
              refresh_all_function(ival); 
            },500);
          }
        }
    });
  }
  else {
    if(type == "Ltd" || type == "ltd" || type == "Limited" || type == "Limted" || type == "limited")
    {
      $.ajax({
        url:"<?php echo URL::to('user/refresh_cro_ard'); ?>",
        dataType:"json",
        type:"get",
        data:{clientid:clientid,cro:cro},
        success:function(result)
        {
          if(result['companystatus'] == "0")
          {
            $("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
            $("#clientidtr_"+clientid).find(".company_td").css({'color' : 'green', 'font-weight' : '500'});
          }
          else{
            $("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
            $("#clientidtr_"+clientid).find(".company_td").css({'color' : 'blue', 'font-weight' : '800'});
          }
          $("#clientidtr_"+clientid).find(".cro_ard_td").html('');
          if(result['ardstatus'] == "0")
          {
           $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
           $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'green'});
          }
          else{
           $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
           $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'red'});
          }

          if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
          else { 
            setTimeout(function() {
              refresh_all_function(ival); 
            },500); 
          }
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/remove_croard_refresh'); ?>",
        type:"post",
        data:{clientid:clientid},
        success:function(result)
        {
          $("#clientidtr_"+clientid).find(".cro_ard_td").html('');
          $("#clientidtr_"+clientid).find(".company_blue").html('').removeClass('company_blue');
          if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
          else { 
            setTimeout(function() {
              refresh_all_function(ival); 
            },500);
          }
        }
      });
    }
  }
}
function refresh_blue_function(ival)
{
  var ival = ival + 1;
  var countval = $(".refresh_blue_croard").length;
  var clientid = $(".refresh_blue_croard:eq("+ival+")").attr("data-element");
  var cro = $(".refresh_blue_croard:eq("+ival+")").attr("data-cro");
  var type = $(".refresh_blue_croard:eq("+ival+")").attr("data-type");

  $("#count_first").html(ival);
  $("#count_last").html(countval);
  console.log(countval);
  if(cro == "")
  {
    $.ajax({
        url:"<?php echo URL::to('user/remove_blue_croard_refresh'); ?>",
        type:"post",
        data:{clientid:clientid},
        success:function(result)
        {
          $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(2).html('');
          $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(3).html('');

          if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
          else { 
            setTimeout(function() {
              refresh_blue_function(ival); 
            },500);
          }
        }
    });
  }
  else {
    if(type == "Ltd" || type == "ltd" || type == "Limited" || type == "Limted" || type == "limited")
    {
      $.ajax({
        url:"<?php echo URL::to('user/refresh_blue_cro_ard'); ?>",
        dataType:"json",
        type:"get",
        data:{clientid:clientid,cro:cro},
        success:function(result)
        {
          $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(2).html(result['company_name']);
            $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(3).html(result['next_ard']);

          if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
          else { 
            setTimeout(function() {
              refresh_blue_function(ival); 
            },500); 
          }
        }
      });
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/remove_blue_croard_refresh'); ?>",
        type:"post",
        data:{clientid:clientid},
        success:function(result)
        {
          $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(2).html('');
            $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(3).html('');
          if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
          else { 
            setTimeout(function() {
              refresh_blue_function(ival); 
            },500);
          }
        }
      });
    }
  }
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
  var ascending = false;
  if($(e.target).hasClass('sno_sort'))
  {
    var sort = $("#sno_sortoptions").val();
    if(sort == 'asc')
    {
      $("#sno_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_sort_vall').html()) <
        convertToNumeric($(b).find('.sno_sort_vall').html()))) ? 1 : -1;
      });
    }
    else{
      $("#sno_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_sort_vall').html()) <
        convertToNumeric($(b).find('.sno_sort_vall').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('clientid_sort'))
  {
    var sort = $("#clientid_sortoptions").val();
    if(sort == 'asc')
    {
      $("#clientid_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.clientid_sort_val').html()) <
        convertToNumber($(b).find('.clientid_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#clientid_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.clientid_sort_val').html()) <
        convertToNumber($(b).find('.clientid_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('company_sort'))
  {
    var sort = $("#company_sortoptions").val();
    if(sort == 'asc')
    {
      $("#company_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($.trim($(a).find('.company_sort_val').html())) <
        convertToNumber($.trim($(b).find('.company_sort_val').html())))) ? 1 : -1;
      });
    }
    else{
      $("#company_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($.trim($(a).find('.company_sort_val').html())) <
        convertToNumber($.trim($(b).find('.company_sort_val').html())))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('cro_sort'))
  {
    var sort = $("#cro_sortoptions").val();
    if(sort == 'asc')
    {
      $("#cro_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.cro_sort_val').html()) <
        convertToNumber($(b).find('.cro_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#cro_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.cro_sort_val').html()) <
        convertToNumber($(b).find('.cro_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('ard_sort'))
  {
    var sort = $("#ard_sortoptions").val();
    if(sort == 'asc')
    {
      $("#ard_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.ard_sort_val').html()) <
        convertToNumber($(b).find('.ard_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#ard_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.ard_sort_val').html()) <
        convertToNumber($(b).find('.ard_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('type_sort'))
  {
    var sort = $("#type_sortoptions").val();
    if(sort == 'asc')
    {
      $("#type_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.type_sort_val').html()) <
        convertToNumber($(b).find('.type_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#type_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.type_sort_val').html()) <
        convertToNumber($(b).find('.type_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('cro_ard_sort'))
  {
    var sort = $("#cro_ard_sortoptions").val();
    if(sort == 'asc')
    {
      $("#cro_ard_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.cro_ard_sort_val').html()) <
        convertToNumber($(b).find('.cro_ard_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#cro_ard_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.cro_ard_sort_val').html()) <
        convertToNumber($(b).find('.cro_ard_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('sno_rbo_sort'))
  {
    var sort = $("#sno_rbo_sortoptions").val();
    if(sort == 'asc')
    {
      $("#sno_rbo_sortoptions").val('desc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_rbo_sort_val').html()) <
        convertToNumeric($(b).find('.sno_rbo_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#sno_rbo_sortoptions").val('asc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_rbo_sort_val').html()) <
        convertToNumeric($(b).find('.sno_rbo_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_rbo_tbody').html(sorted);
  }
  if($(e.target).hasClass('clientid_rbo_sort'))
  {
    var sort = $("#clientid_rbo_sortoptions").val();
    if(sort == 'asc')
    {
      $("#clientid_rbo_sortoptions").val('desc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.clientid_rbo_sort_val').html()) <
        convertToNumber($(b).find('.clientid_rbo_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#clientid_rbo_sortoptions").val('asc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.clientid_rbo_sort_val').html()) <
        convertToNumber($(b).find('.clientid_rbo_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_rbo_tbody').html(sorted);
  }
  if($(e.target).hasClass('company_rbo_sort'))
  {
    var sort = $("#company_rbo_sortoptions").val();
    if(sort == 'asc')
    {
      $("#company_rbo_sortoptions").val('desc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($.trim($(a).find('.company_rbo_sort_val').html())) <
        convertToNumber($.trim($(b).find('.company_rbo_sort_val').html())))) ? 1 : -1;
      });
    }
    else{
      $("#company_rbo_sortoptions").val('asc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($.trim($(a).find('.company_rbo_sort_val').html())) <
        convertToNumber($.trim($(b).find('.company_rbo_sort_val').html())))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_rbo_tbody').html(sorted);
  }
  if($(e.target).hasClass('cro_rbo_sort'))
  {
    var sort = $("#cro_rbo_sortoptions").val();
    if(sort == 'asc')
    {
      $("#cro_rbo_sortoptions").val('desc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.cro_rbo_sort_val').html()) <
        convertToNumber($(b).find('.cro_rbo_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#cro_rbo_sortoptions").val('asc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.cro_rbo_sort_val').html()) <
        convertToNumber($(b).find('.cro_rbo_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_rbo_tbody').html(sorted);
  }
  if($(e.target).hasClass('type_rbo_sort'))
  {
    var sort = $("#type_rbo_sortoptions").val();
    if(sort == 'asc')
    {
      $("#type_rbo_sortoptions").val('desc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.type_rbo_sort_val').html()) <
        convertToNumber($(b).find('.type_rbo_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#type_rbo_sortoptions").val('asc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.type_rbo_sort_val').html()) <
        convertToNumber($(b).find('.type_rbo_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_rbo_tbody').html(sorted);
  }
  if($(e.target).hasClass('rbo_ref_sort'))
  {
    var sort = $("#rbo_ref_sortoptions").val();
    if(sort == 'asc')
    {
      $("#rbo_ref_sortoptions").val('desc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.rbo_ref_sort_val').html()) <
        convertToNumber($(b).find('.rbo_ref_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#rbo_ref_sortoptions").val('asc');
      var sorted = $('#clients_rbo_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.rbo_ref_sort_val').html()) <
        convertToNumber($(b).find('.rbo_ref_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_rbo_tbody').html(sorted);
  }
  if($(e.target).hasClass('rbo_review_btn'))
  {
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/rbo_review_list'); ?>",
      type:"post",
      success:function(result)
      {
        $("#rbo_review_tbody").html(result);
        $(".show_ltd_rbo").removeClass("show_all_rbo");
        $(".show_ltd_rbo").val("Show Active Ltd Clients Only");
        $(".rbo_review_modal").modal("show");
        $("body").removeClass("loading");
      }
    });
  }
  if($(e.target).hasClass('report_csv_rbo'))
  {
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/report_csv_rbo'); ?>",
      type:"post",
      success:function(result)
      {
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    });
  }
  if(e.target.id == "settings_croard")
  {
    if (CKEDITOR.instances.editor1) CKEDITOR.instances.editor1.destroy();

    CKEDITOR.replace('editor1',
    {
      height: '150px',
      enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        autoParagraph: false,
        entities: false,
    });
    $(".croard_settings_modal").modal("show");
  }
  if($(e.target).hasClass('show_ltd'))
  {
    if($(e.target).hasClass('show_all'))
    {
      $(".type_sort_val").parents("tr").show();
      $(e.target).removeClass("show_all");
      $(e.target).val("Show Active Ltd Clients Only");
    }
    else{
      $(".type_sort_val").parents("tr").hide();
      $(".type_sort_val:contains(Ltd):not(:contains('UK Ltd'))").parents("tr").show();
      $(".type_sort_val").parents(".disabled_tr").hide();
      $(e.target).addClass("show_all");
      $(e.target).val("Show all Clients");
    }
  }
  if($(e.target).hasClass('show_ltd_rbo'))
  {
    if($(e.target).hasClass('show_all_rbo'))
    {
      $(".type_rbo_sort_val").parents("tr").show();
      $(e.target).removeClass("show_all_rbo");
      $(e.target).val("Show Active Ltd Clients Only");
    }
    else{
      $(".type_rbo_sort_val").parents("tr").hide();
      $(".type_rbo_sort_val:contains(Ltd):not(:contains('UK Ltd'))").parents("tr").show();
      $(".type_rbo_sort_val").parents(".disabled_rbo_tr").hide();
      $(e.target).addClass("show_all_rbo");
      $(e.target).val("Show all Clients");
    }
  }
  if($(e.target).hasClass('signature_file_check'))
  {
    if($(e.target).is(":checked"))
    {
      $(e.target).parents("td:first").find(".status_label").hide();
      $(e.target).parents("td:first").find(".yellow_label").show();
      $(e.target).parents("td:first").find(".status_icon").addClass("yellow_status");
      var status = 1;
    }
    else{
      $(e.target).parents("td:first").find(".status_label").show();
      $(e.target).parents("td:first").find(".yellow_label").hide();
      $(e.target).parents("td:first").find(".status_icon").removeClass("yellow_status");
      var status = 0;
    }
    var client_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/change_yellow_status_croard'); ?>",
      type:"post",
      data:{client_id:client_id,status:status},
      success:function(result)
      {
        if(status == 1)
        {
          $(e.target).parents("td:first").find(".signature_file_date").val(result);
        }
        else{
          $(e.target).parents("td:first").find(".signature_file_date").val("");
        }
      }
    })
  }
  if($(e.target).hasClass('email_unsent'))
  {
    if (CKEDITOR.instances.editor) CKEDITOR.instances.editor.destroy();
    var attach_len = $(e.target).parents("td:first").find(".attachment_link").length;
    if(attach_len > 0)
    {
      CKEDITOR.replace('editor',
         {
          height: '150px',
         });
      var client_id = $(e.target).attr('data-client');
      $.ajax({
        url:'<?php echo URL::to('user/edit_email_unsent_files_croard'); ?>',
        type:'get',
        data:{client_id:client_id},
        dataType:"json",
        success: function(result)
        {
          CKEDITOR.instances['editor'].setData(result['html']);
          $("#email_attachments").html(result['files']);
          $(".subject_unsent").val(result['subject']);
          $("#to_user").val(result['to']);
          $("#hidden_client_id_email_croard").val(client_id);
          $(".emailunsent").modal('show');
        }
      });
    }
    else{
      $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000"> Email option will be enabled only after you add a signature file.</p>',fixed:true,width:"800px"});
    }
  }
  if($(e.target).hasClass('email_unsent_files_btn'))
  {
    for (instance in CKEDITOR.instances) 
    {
        CKEDITOR.instances['editor'].updateElement();
    }
    if($("#email_unsent_form").valid())
    {
      $("body").addClass("loading");
      $('#email_unsent_form').ajaxForm({
          success:function(result){
            var date = result.split("||");
            $("#clientidtr_"+date[1]).find(".email_unsent_label").html("<p>"+date[0]+"</p>");
            $("body").removeClass("loading");
            $(".emailunsent").modal("hide");
            $("body").removeClass("loading");
          }
      }).submit();
    }
  }
  if($(e.target).hasClass('global_core_call'))
  {
    $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Do you want to update the Client Manager with the ARD Date from the Companies Office</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_proceed">Yes</a><a href="javascript:" class="common_black_button no_proceed">No</a></p>',fixed:true,width:"800px"});
  }
  if($(e.target).hasClass('refresh_blue_client'))
  {
    $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Do you want to update the Client Manager with the ARD Date from the Companies Office</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_blue_proceed">Yes</a><a href="javascript:" class="common_black_button no_proceed">No</a></p>',fixed:true,width:"800px"});
  }
  if($(e.target).hasClass('add_attachment_month_year'))
  {
  	var client = $(e.target).attr("data-client");
  	var attach_len = $(e.target).parents("td:first").find(".attachment_link").length;
  	if(attach_len > 0)
  	{
  		$.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000"> A B1 file is already listed, do you want to over write it</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_attach" data-element="'+client+'">Yes</a><a href="javascript:" class="common_black_button no_attach">No</a></p>',fixed:true,width:"800px"});
  	}
    else{
    	$("#hidden_client_id_croard").val(client);
	    $(".dropzone_progress_modal").modal("show");
	    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    }
  }
  if($(e.target).hasClass('yes_attach'))
  {
  	$.colorbox.close();
  	var client = $(e.target).attr("data-element");
  	$("#hidden_client_id_croard").val(client);
    $(".dropzone_progress_modal").modal("show");
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
  }
  if($(e.target).hasClass('no_attach'))
  {
  	$.colorbox.close();
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
    var client = $(e.target).attr("data-client");
    var clientname = $(e.target).attr("data-clientname");

    $(".client_search_class_task").val(clientname);
    $("#client_search_task").val(client);
    
    $(".create_new_task_model").find(".job_title").html("New Task Creator");
    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
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
    $(".subject_class").val("CRO ARD System Task");
    $(".task_specifics_add").show();
    CKEDITOR.instances['editor_2'].setData("Do the Annual Return");
    
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
      success: function(result)
      {
        $("#add_notepad_attachments_div").html('');
        $("#add_attachments_div").html('');
        $("body").removeClass("loading");
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
  if($(e.target).hasClass('fa-plus-task'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left);
    $(e.target).parent().find('.img_div_task').toggle();
    Dropzone.forElement("#imageUpload5").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");    
  }
  if(e.target.id == "open_task")
  {
    if($(e.target).is(":checked"))
    {
      $(".allocate_user_add").val("");
      $(".allocate_user_add").addClass("disable_user");
      $(".allocate_email").addClass("disable_user");
    }
    else{
      $(".allocate_user_add").val("");
      $(".allocate_user_add").removeClass("disable_user");
      $(".allocate_email").removeClass("disable_user");
    }
  }
  if($(e.target).hasClass('yes_proceed'))
  {
    $("body").addClass("loading_content");
    $.colorbox.close();
    var ival = 1;
    var countval = $(".refresh_croard").length;
    var clientid = $(".refresh_croard:eq("+ival+")").attr("data-element");
    var cro = $(".refresh_croard:eq("+ival+")").attr("data-cro");
    var type = $(".refresh_croard:eq("+ival+")").attr("data-type");
    setTimeout(function() {
      $("#count_last").html(countval);
      $("#count_first").html(ival);
      if(cro == "")
      {
        $.ajax({
          url:"<?php echo URL::to('user/remove_croard_refresh'); ?>",
          type:"post",
          data:{clientid:clientid},
          success:function(result)
          {
            $("#clientidtr_"+clientid).find(".cro_ard_td").html('');
            $("#clientidtr_"+clientid).find(".company_blue").html('').removeClass('company_blue');
            if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
            else { 
              setTimeout( function() {
                refresh_all_function(ival); 
              },500);
            }
          }
        });
      }
      else{
        if(type == "Ltd" || type == "ltd" || type == "Limited" || type == "Limted" || type == "limited") {
          $.ajax({
            url:"<?php echo URL::to('user/refresh_cro_ard'); ?>",
            dataType:"json",
            type:"get",
            data:{clientid:clientid,cro:cro},
            success:function(result)
            {
              if(result['companystatus'] == "0")
              {
                $("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
                $("#clientidtr_"+clientid).find(".company_td").css({'color' : 'green', 'font-weight' : '500'});
              }
              else{
                $("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
                $("#clientidtr_"+clientid).find(".company_td").css({'color' : 'blue', 'font-weight' : '800'});
              }
              $("#clientidtr_"+clientid).find(".cro_ard_td").html('');
              if(result['ardstatus'] == "0")
              {
                $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
                $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'green'});
              }
              else{
                $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
                $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'red'});
              }

              if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
              else { 
                setTimeout( function() {
                  refresh_all_function(ival); 
                },500);
              }
            }
          });
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/remove_croard_refresh'); ?>",
            type:"post",
            data:{clientid:clientid},
            success:function(result)
            {
              $("#clientidtr_"+clientid).find(".cro_ard_td").html('');
              $("#clientidtr_"+clientid).find(".company_blue").html('').removeClass('company_blue');
              if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
              else { 
                setTimeout( function() {
                  refresh_all_function(ival); 
                },500);
              }
            }
          });
        }
      }
    },1000);
  }
  if($(e.target).hasClass('yes_blue_proceed'))
  {
    $("body").addClass("loading_content");
    $.colorbox.close();
    var ival = 1;
    var countval = $(".refresh_blue_croard").length;
    var clientid = $(".refresh_blue_croard:eq("+ival+")").attr("data-element");
    var cro = $(".refresh_blue_croard:eq("+ival+")").attr("data-cro");
    var type = $(".refresh_blue_croard:eq("+ival+")").attr("data-type");
    setTimeout(function() {
      $("#count_last").html(countval);
      $("#count_first").html(ival);
      if(cro == "")
      {
        $.ajax({
          url:"<?php echo URL::to('user/remove_blue_croard_refresh'); ?>",
          type:"post",
          data:{clientid:clientid},
          success:function(result)
          {
            $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(2).html('');
            $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(3).html('');
            if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
            else { 
              setTimeout( function() {
                refresh_blue_function(ival); 
              },500);
            }
          }
        });
      }
      else{
        if(type == "Ltd" || type == "ltd" || type == "Limited" || type == "Limted" || type == "limited") {
          $.ajax({
            url:"<?php echo URL::to('user/refresh_blue_cro_ard'); ?>",
            dataType:"json",
            type:"get",
            data:{clientid:clientid,cro:cro},
            success:function(result)
            {
              $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(2).html(result['company_name']);
              $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(3).html(result['next_ard']);

              if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
              else { 
                setTimeout( function() {
                  refresh_all_function(ival); 
                },500);
              }
            }
          });
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/remove_blue_croard_refresh'); ?>",
            type:"post",
            data:{clientid:clientid},
            success:function(result)
            {
              $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(2).html('');
              $(".refresh_blue_croard:eq("+ival+")").parents("tr").find("td").eq(3).html('');
              if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
              else { 
                setTimeout( function() {
                  refresh_all_function(ival); 
                },500);
              }
            }
          });
        }
      }
    },1000);
  }
  if($(e.target).hasClass('no_proceed'))
  {
    $.colorbox.close();
  }
  if($(e.target).hasClass('check_cro'))
  {
    $("body").addClass("loading");
    var cro = $(e.target).attr("data-element");
    $(".company_number").val(cro);
    $(".search_company_modal").modal("show");
    $("#indicator_1").prop("checked",true);
    setTimeout( function() { 
      $(".search_company_btn").trigger("click");
      
    },1000);
  }
  if(e.target.id == 'show_incomplete')
  {
      if($(e.target).is(':checked'))
      {
        $(".edit_task").each(function() {
            if($(this).hasClass('disabled_tr'))
            {
              $(this).hide();
            }
        });
      }
      else{
        $(".edit_task").each(function() {
            if($(this).hasClass('disabled_tr'))
            {
              $(this).show();
            }
        });
      }
  }
  if($(e.target).hasClass('search_company_btn'))
  {
    var checked = $(".indicator:checked").length;
    var company_number = $(".company_number").val();
    var indicator = $(".indicator:checked").val();
    if(checked < 1)
    {
      alert("Please select the Company / Business indicator to search for a Company");
    }
    else if(company_number == "")
    {
      alert("Please enter the Company Number to search for a Company");
    }
    else{
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/get_company_details_cro'); ?>",
        type:"post",
        data:{company_number:company_number,indicator:indicator},
        success:function(result)
        {
          $(".table_api").html(result);
          //$(".search_company_modal").modal("hide");
          $("body").removeClass("loading");
        }
      });
    }
  }
  if($(e.target).hasClass('refresh_croard'))
  {
    var clientid = $(e.target).attr("data-element");
    var cro = $(e.target).attr("data-cro");
    var type = $(e.target).attr("data-type");

    $.ajax({
      url:"<?php echo URL::to('user/remove_croard_refresh'); ?>",
      type:"post",
      data:{clientid:clientid},
      success:function(result)
      {
        $("#clientidtr_"+clientid).find(".cro_ard_td").html('');
        $("#clientidtr_"+clientid).find(".company_blue").html('').removeClass('company_blue');
        if(cro == "")
        {
          alert("Sorry you cant fetch the details from api because the CRO Number for this client is empty.");
        }

        if(type == "Ltd" || type == "ltd" || type == "Limited" || type == "Limted" || type == "limited")
        {
          $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Do you want to update the Client Manager with the ARD Date from the Companies Office</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_proceed_single" data-element="'+clientid+'" data-cro="'+cro+'">Yes</a><a href="javascript:" class="common_black_button no_proceed_single">No</a></p>',fixed:true,width:"800px"});
        }
        else{
          alert("Sorry you cant fetch the details from api because the type should be 'Ltd' or 'Limited'.")
        }
      }
    })
  }
  if($(e.target).hasClass('yes_proceed_single'))
  {
    $("body").addClass("loading");
    var clientid = $(e.target).attr("data-element");
    var cro = $(e.target).attr("data-cro");
    $.ajax({
      url:"<?php echo URL::to('user/refresh_cro_ard'); ?>",
      dataType:"json",
      type:"get",
      data:{clientid:clientid,cro:cro},
      success:function(result)
      {
        if(result['companystatus'] == "0")
        {
          $("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
          $("#clientidtr_"+clientid).find(".company_td").css({'color' : 'green', 'font-weight' : '500'});
        }
        else{
          $("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
          $("#clientidtr_"+clientid).find(".company_td").css({'color' : 'blue', 'font-weight' : '800'});
        }
        $("#clientidtr_"+clientid).find(".cro_ard_td").html('');
        if(result['ardstatus'] == "0")
        {
          $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
          $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'green'});
        }
        else{
          $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
          $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'red'});
        }
        $.colorbox.close();
        $("body").removeClass("loading");
      }
    });
  }
  if($(e.target).hasClass('no_proceed_single'))
  {
    $.colorbox.close();
  }

  $(".cro_notes").blur(function() {
    var input_val = $(this).val();
    var clientid = $(this).attr('data-element');
    
      $.ajax({
          url:"<?php echo URL::to('user/update_cro_notes'); ?>",
          type:"post",
          data:{input_val:input_val,clientid:clientid},
          success: function(result) {

          }
      });
  });
  $(".rbo_submission_text").blur(function() {
    var input_val = $(this).val();
    var clientid = $(this).attr('data-element');
    
      $.ajax({
          url:"<?php echo URL::to('user/update_rbo_submission'); ?>",
          type:"post",
          data:{input_val:input_val,clientid:clientid},
          success: function(result) {

          }
      });
  });

  var typingTimer;                //timer identifier
  var doneTypingInterval = 1000;  //time in ms, 5 second for example
  var $input1 = $('.cro_notes');
  var $input2 = $('.rbo_submission_text');
  $input1.on('keyup', function () {
    var input_val = $(this).val();
    var clientid = $(this).attr('data-element');
    var that = $(this);
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping_cro, doneTypingInterval,input_val,clientid,that);
  });
  $input2.on('keyup', function () {
    var input_val = $(this).val();
    var clientid = $(this).attr('data-element');
    var that = $(this);
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping_rbo, doneTypingInterval,input_val,clientid,that);
  });
  //on keydown, clear the countdown 
  $input1.on('keydown', function () {
    clearTimeout(typingTimer);
  });
  $input2.on('keydown', function () {
    clearTimeout(typingTimer);
  });
})
var convertToNumber = function(value){
       var lowercase = value.toLowerCase();
       return lowercase.trim();
}
var convertToNumeric = function(value){
      value = value.replace(',','');
      value = value.replace(',','');
      value = value.replace(',','');
      value = value.replace(',','');

       return parseInt(value.toLowerCase());
}


function doneTyping_cro (input,clientid,that) {
  $.ajax({
      url:"<?php echo URL::to('user/update_cro_notes'); ?>",
      type:"post",
      data:{input_val:input,clientid:clientid},
      success: function(result) {

      }
  });
}

function doneTyping_rbo (input,clientid,that) {
  $.ajax({
      url:"<?php echo URL::to('user/update_rbo_submission'); ?>",
      type:"post",
      data:{input_val:input,clientid:clientid},
      success: function(result) {

      }
  });
}

$.ajaxSetup({async:false});
$('#email_unsent_form').validate({
    rules: {
        select_user : {required: true,},
        to_user : {required: true,},
        cc_unsent : {required: true,},
    },
    messages: {
        select_user : "Please select a From User",
        to_user : "Please Enter the Email ID to send a mail",
        cc_unsent : "Please create a CC Mail ID in CROARD Settings Overlay",
    },
});


fileList = new Array();
Dropzone.options.imageUpload = {
    maxFiles: 1,
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
            $(".dropzone_progress_modal").modal("hide");
            var croard_date = $("#clientidtr_"+obj.client_id).find(".cro_ard_val").html();
            var cro_number =  $("#clientidtr_"+obj.client_id).find(".check_cro").html();
            $("#clientidtr_"+obj.client_id).find(".attachment_div").html('<a class="attachment_link" href="'+obj.download_url+'" download>'+obj.filename+'</a>');
            $.ajax({
              url:"<?php echo URL::to('user/get_company_details_next_crd'); ?>",
              type:"post",
              dataType:"json",
              data:{company_number:cro_number,indicator:'C',client_id:obj.client_id},
              success:function(result)
              {
                $("#clientidtr_"+obj.client_id).find(".cro_ard_val").html(result['croard']);
                $("#clientidtr_"+obj.client_id).find(".signature_file_date").val(result['croard']);
                if(result['updated'] == 1)
                {
                  $("#clientidtr_"+obj.client_id).find(".signature_file_check").prop("checked",false);
                  $("#clientidtr_"+obj.client_id).find(".status_icon").removeClass('green_status').removeClass('red_status').removeClass('orange_status').removeClass('blue_status').removeClass('yellow_status');
                }
                else{
                  $("#clientidtr_"+obj.client_id).find(".status_icon").removeClass('green_status').removeClass('red_status').removeClass('orange_status').removeClass('blue_status');
                }
                
                $("#clientidtr_"+obj.client_id).find(".status_icon").addClass(result['color_status']);
                $("#clientidtr_"+obj.client_id).find(".status_icon").html(result['status_label']);
                var cro_ard = $("#clientidtr_"+obj.client_id).find(".cro_ard_val").html();
                var ard = $("#clientidtr_"+obj.client_id).find(".ard_val").html();
                if(cro_ard == ard)
                {
                  $("#clientidtr_"+obj.client_id).find(".cro_ard_val").parents("td:first").css("color","green");
                }
                else{
                  $("#clientidtr_"+obj.client_id).find(".cro_ard_val").parents("td:first").css("color","#f00");
                }
                $("body").removeClass("loading");
              }
            });
        });
        this.on("complete", function (file, response) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            Dropzone.forElement("#imageUpload").removeAllFiles(true);
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
</script>
@stop
