@extends('userheader')
@section('content')

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>

<style>
  .refresh_submitted_file{
    float: right;
font-weight: 800;
font-size: 20px;
  }
  .floatnone{
    float: none !important;
font-size: 16px;
margin-left: 10px;
  }
  .margintop20{
  margin-top:20px !important;
  margin-bottom: 0px !important;
}
  .start_group{clear:both;}
.ui-autocomplete{
  z-index:99999999999999999 !important;
}
.disable_user{
  pointer-events: none;
  background: #c7c7c7;
}
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
.submission_due_no{
  color:#ec9e0f;
  font-weight: 800;
  margin-left:15px;
}

.submission_os_no{
  color:#f00;
  font-weight: 800;
  margin-left:15px;
}

.submitted_no{
  color:green;
  font-weight: 800;
  margin-left:15px;
}
.file_attachments img {width:30px !important;}
.attachment_div p {width:18%; float:left;}
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
<div class="modal fade vat_clients_months_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:9999">
  <div class="modal-dialog modal-sm" role="document" style="width:75%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title client_review_title">Client Review</h4>
          </div>
          <div class="modal-body" id="client_review_body">
            <div class="col-md-6">
              <label style="float:left;margin-top:10px;margin-right:15px">Select Year: </label>
              <select name="client_review_select_year" class="form-control client_review_select_year" style="float:left;width:20%;margin-right:10px">
                <option value="">Select Year</option>
                <option value="2020">2020</option>
                <option value="2021">2021</option>
                <option value="2022">2022</option>
              </select>
              <input type="button" name="load_all_month_btn" class="common_black_button load_all_month_btn" value="Load" style="margin-right:10px">
              <input type="checkbox" name="hide_non_return" class="hide_non_return" id="hide_non_return" value=""><label for="hide_non_return">Hide Non-Returning Months</label>
            </div>
            <div class="col-md-6" style="text-align: right">
              
              <input type="button" class="download_selected_periods common_black_button" value="Download Selected Files">
            </div>
            <div class="col-md-12">
              <table class="table own_table_white">
                <thead>
                  <th>Month <i class="fa fa-sort cli_month_sort"></i></th>
                  <th>Status <i class="fa fa-sort cli_status_sort"></i></th>
                  <th style="width: 17%;">Period</th>
                  <th style="width: 170px;">Submitted Date <i class="fa fa-sort cli_date_sort"></i></th>
                  <th>Vat3 Attachment <i class="fa fa-sort cli_vat_sort"></i></th>
                  <th>Records Received <i class="fa fa-sort cli_record_sort"></i></th>
                </thead>
                <tbody id="client_review_tbody">
                  <tr>
                    <td colspan="6" style="text-align: center">No Datas Found</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal-footer" style="clear:both;">  
            <input type="hidden" name="hidden_vat_client_id_overlay" id="hidden_vat_client_id_overlay" value="">
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
<!-- <div class="modal fade create_new_task_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;overflow-y: scroll;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:45%">
    <form action="<?php echo URL::to('user/create_new_taskmanager_task_vat')?>" method="post" class="add_new_form" id="create_task_form">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">New Task Creator</h4>
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
            
            <div class="row margintop20" style="margin-top:7px">
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
            <div class="row margintop20" style="margin-top:14px">
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
                  <input  type="text" class="form-control client_search_class_task" name="client_name" placeholder="Enter Client Name / Client ID" value="" required>
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
                  </div>
                </div>
            </div>
            <div class="form-group start_group margintop20" style="margin-top:20px">
              <div class="row">
                <div class="col-lg-5">
                  <div class="row">
                    <div class="col-md-5">
                      <div class="form-title"><label style="margin-top:5px">Priority:</label></div>
                    </div>
                    <div class="col-md-7" style="padding-top: 5px">
                      <?php echo user_rating(); ?>
                    </div>
                  </div>
                </div>
                <div class="col-lg-7">
                  <div class="row">
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
            <div class="form-group start_group margintop20" style="margin-top:15px !important">
                <div class="row">
                  <div class="col-lg-2">
                    <div class="form-title"><label style="margin-top:5px">Subject:</label></div>
                  </div>
                  <div class="col-lg-10">
                    <input  type="text" class="form-control subject_class" name="subject_class" placeholder="Enter Subject">
                  </div>
                </div>

                
                
            </div>
            <div class="form-group start_group task_specifics_add margintop20">

                <div class="row">
                  <div class="col-lg-7">
                    <div class="form-title" style="float:none"><label style="margin-top:5px">Task Specifics:</label></div>
                  </div>
                  <div class="col-lg-5">
                    <div class="form-group date_group" style="float: right;">
                      <div class="form-title" style="font-weight:600;margin-left:-10px">
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
            <div class="form-group date_group margintop20">
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
            <div class="form-group start_group retreived_files_div margintop20">

            </div>

            <div class="row margintop20" style="clear: both; padding-top: 10px;">
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
            <input type="hidden" name="hidden_vat_client_id" id="hidden_vat_client_id" value="">
            <input type="hidden" name="hidden_vat_month" id="hidden_vat_month" value="">
            <input type="submit" class="common_black_button make_task_live" value="Make Task Live" style="width: 100%;">
          </div>
        </div>
    </form>
  </div>
</div> -->
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
          <div class="modal-footer" style="text-align: center">  
              <div class="vat3_div" style="display:none">
                <h4 style="text-align: left;font-weight: 600">Details</h4>
                <table class="table">
                    <tr>
                      <td style="font-weight: 600">Client Tax No: </td>
                      <td class="client_tax_no"></td>
                    </tr>
                    <tr>
                      <td style="font-weight: 600">Registration No: </td>
                      <td class="vat3_reg_no"></td>
                    </tr>
                    <tr>
                      <td style="font-weight: 600">T1: </td>
                      <td class="vat3_t1"></td>
                    </tr>
                    <tr>
                      <td style="font-weight: 600">T2: </td>
                      <td class="vat3_t2"></td>
                    </tr>
                </table>
              </div>
              <div class="vat3_error_div" style="display:none">
                <h4 class="vat3_error" style="font-weight: 600;color:#f00"></h4>
              </div>
              <input type="hidden" name="hidden_vat3_filename" id="hidden_vat3_filename" class="hidden_vat3_filename" value="">
              <input type="hidden" name="hidden_vat3_dir" id="hidden_vat3_dir" class="hidden_vat3_dir" value="">
              <input type="button" class="common_black_button vat3_upload_btn" value="Commit Upload File">
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
                <div class="col-md-12" style="max-height:400px;min-height:400px;overflow-y: scroll">
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
      <div class="modal-body modal_max_height">
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
              <th style="width:20%;text-align: left; "><a href="javascript:" class="fa fa-arrow-circle-left show_prev_month" title="Extend to Prev Month" data-element="<?php echo date('m-Y', strtotime('first day of previous month')); ?>"></a> &nbsp;&nbsp;<a href="javascript:" class="show_month_in_overlay" data-element="<?php echo date('m-Y', strtotime('first day of previous month')); ?>"><?php echo date('M-Y', strtotime('first day of previous month')); ?></a> <label class="submission_due_no">Due: <spam class="no_sub_due no_sub_due_<?php echo date('m-Y', strtotime('first day of previous month')); ?>">0</spam></label><label class="submission_os_no">OS: <spam class="no_sub_os no_sub_os_<?php echo date('m-Y', strtotime('first day of previous month')); ?>">0</spam></label><label class="submitted_no">Submitted: <spam class="no_sub no_sub_<?php echo date('m-Y', strtotime('first day of previous month')); ?>">0</spam></label></th>
              <th style="width:20%;text-align: left; "><a href="javascript:" class="show_month_in_overlay" data-element="<?php echo date('m-Y'); ?>"><?php echo date('M-Y'); ?></a> <label class="submission_due_no">Due: <spam class="no_sub_due no_sub_due_<?php echo date('m-Y'); ?>">0</spam></label><label class="submission_os_no">OS: <spam class="no_sub_os no_sub_os_<?php echo date('m-Y'); ?>">0</spam></label><label class="submitted_no">Submitted: <spam class="no_sub no_sub_<?php echo date('m-Y'); ?>">0</spam></label></th>
              <th style="width:20%;text-align: left; "><a href="javascript:" class="show_month_in_overlay" data-element="<?php echo date('m-Y', strtotime('first day of next month')); ?>"><?php echo date('M-Y', strtotime('first day of next month')); ?></a> &nbsp;&nbsp; <a href="javascript:" class="fa fa-arrow-circle-right show_next_month" title="Extend to Next Month" data-element="<?php echo date('m-Y', strtotime('first day of next month')); ?>"></a> <label class="submission_due_no">Due: <spam class="no_sub_due no_sub_due_<?php echo date('m-Y', strtotime('first day of next month')); ?>">0</spam></label><label class="submission_os_no">OS: <spam class="no_sub_os no_sub_os_<?php echo date('m-Y', strtotime('first day of next month')); ?>">0</spam></label><label class="submitted_no">Submitted: <spam class="no_sub no_sub_<?php echo date('m-Y', strtotime('first day of next month')); ?>">0</spam></label></th>
          </tr>
        </thead>
        <tbody id="task_body">
          
        </tbody>            
      </table>
    </div>
  </div>
</div>
</div>

<div>
    <!-- embed the pdftotext web app as an iframe -->
    <iframe id="processor" src="" style="display: none"></iframe>
    <!-- a container for the output -->
    <div id="output" style="display: none"><div id="intro">Extracting text from a PDF file using only Javascript.<br>Tested in Chrome 16 and Firefox 9.</div></div>
</div>
  
<div>
  <!-- the PDF file must be on the same domain as this page -->
  <iframe id="input" src="" style="display: none"></iframe>
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

<input type="hidden" name="cli_month_sortoptions" id="cli_month_sortoptions" value="asc">
<input type="hidden" name="cli_status_sortoptions" id="cli_status_sortoptions" value="asc">
<input type="hidden" name="cli_date_sortoptions" id="cli_date_sortoptions" value="asc">
<input type="hidden" name="cli_vat_sortoptions" id="cli_vat_sortoptions" value="asc">
<input type="hidden" name="cli_record_sortoptions" id="cli_record_sortoptions" value="asc">

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
<?php
if(Session::has('task_message')) { ?>
<script>
$(document).ready(function(){
  $(".load_all_vat_clients").trigger("click");
  $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>Task Created successfully. </p>",width:"40%",height:"15%",fixed:"true"});
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

        var prev_count = $("#task_body").find("td:nth-child(4)").find(".orange_import").length;
        var curr_count = $("#task_body").find("td:nth-child(5)").find(".orange_import").length;
        var next_count = $("#task_body").find("td:nth-child(6)").find(".orange_import").length;


        var red_prev_count = $("#task_body").find("td:nth-child(4)").find(".red_import").length;
        var red_curr_count = $("#task_body").find("td:nth-child(5)").find(".red_import").length;
        var red_next_count = $("#task_body").find("td:nth-child(6)").find(".red_import").length;


        var green_prev_count = $("#task_body").find("td:nth-child(4)").find(".green_import").length;
        var green_curr_count = $("#task_body").find("td:nth-child(5)").find(".green_import").length;
        var green_next_count = $("#task_body").find("td:nth-child(6)").find(".green_import").length;

        console.log(green_prev_count+'--'+green_curr_count+'--'+green_next_count);

        $('.submission_due_no').eq(0).find(".no_sub_due").html(prev_count);
        $('.submission_due_no').eq(1).find(".no_sub_due").html(curr_count);
        $('.submission_due_no').eq(2).find(".no_sub_due").html(next_count);

        $('.submission_os_no').eq(0).find(".no_sub_os").html(red_prev_count);
        $('.submission_os_no').eq(1).find(".no_sub_os").html(red_curr_count);
        $('.submission_os_no').eq(2).find(".no_sub_os").html(red_next_count);


        $('.submitted_no').eq(0).find(".no_sub").html(green_prev_count);
        $('.submitted_no').eq(1).find(".no_sub").html(green_curr_count);
        $('.submitted_no').eq(2).find(".no_sub").html(green_next_count);
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

        var prev_count = $("#task_body").find("td:nth-child(4)").find(".orange_import").length;
        var curr_count = $("#task_body").find("td:nth-child(5)").find(".orange_import").length;
        var next_count = $("#task_body").find("td:nth-child(6)").find(".orange_import").length;

        var red_prev_count = $("#task_body").find("td:nth-child(4)").find(".red_import").length;
        var red_curr_count = $("#task_body").find("td:nth-child(5)").find(".red_import").length;
        var red_next_count = $("#task_body").find("td:nth-child(6)").find(".red_import").length;


        var green_prev_count = $("#task_body").find("td:nth-child(4)").find(".green_import").length;
        var green_curr_count = $("#task_body").find("td:nth-child(5)").find(".green_import").length;
        var green_next_count = $("#task_body").find("td:nth-child(6)").find(".green_import").length;

        console.log(green_prev_count+'--'+green_curr_count+'--'+green_next_count);

        $('.submission_due_no').eq(0).find(".no_sub_due").html(prev_count);
        $('.submission_due_no').eq(1).find(".no_sub_due").html(curr_count);
        $('.submission_due_no').eq(2).find(".no_sub_due").html(next_count);

        $('.submission_os_no').eq(0).find(".no_sub_os").html(red_prev_count);
        $('.submission_os_no').eq(1).find(".no_sub_os").html(red_curr_count);
        $('.submission_os_no').eq(2).find(".no_sub_os").html(red_next_count);

        $('.submitted_no').eq(0).find(".no_sub").html(green_prev_count);
        $('.submitted_no').eq(1).find(".no_sub").html(green_curr_count);
        $('.submitted_no').eq(2).find(".no_sub").html(green_next_count);
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
  if($(e.target).hasClass('cli_month_sort'))
  {
    var sort = $("#cli_month_sortoptions").val();
    if(sort == 'asc')
    {
      $("#cli_month_sortoptions").val('desc');
      var sorted = $('#client_review_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.month_year_sort_val').val()) <
        convertToNumber($(b).find('.month_year_sort_val').val()))) ? 1 : -1;
      });
    }
    else{
      $("#cli_month_sortoptions").val('asc');
      var sorted = $('#client_review_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.month_year_sort_val').val()) <
        convertToNumber($(b).find('.month_year_sort_val').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_review_tbody').html(sorted);
  }
  if($(e.target).hasClass('cli_status_sort'))
  {
    var sort = $("#cli_status_sortoptions").val();
    if(sort == 'asc')
    {
      $("#cli_status_sortoptions").val('desc');
      var sorted = $('#client_review_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.import_icon_overlay').html()) <
        convertToNumber($(b).find('.import_icon_overlay').html()))) ? 1 : -1;
      });
    }
    else{
      $("#cli_status_sortoptions").val('asc');
      var sorted = $('#client_review_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.import_icon_overlay').html()) <
        convertToNumber($(b).find('.import_icon_overlay').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_review_tbody').html(sorted);
  }
  if($(e.target).hasClass('cli_date_sort'))
  {
    var sort = $("#cli_date_sortoptions").val();
    if(sort == 'asc')
    {
      $("#cli_date_sortoptions").val('desc');
      var sorted = $('#client_review_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.date_sort_val').val()) <
        convertToNumber($(b).find('.date_sort_val').val()))) ? 1 : -1;
      });
    }
    else{
      $("#cli_date_sortoptions").val('asc');
      var sorted = $('#client_review_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.date_sort_val').val()) <
        convertToNumber($(b).find('.date_sort_val').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_review_tbody').html(sorted);
  }
  if($(e.target).hasClass('cli_vat_sort'))
  {
    var sort = $("#cli_vat_sortoptions").val();
    if(sort == 'asc')
    {
      $("#cli_vat_sortoptions").val('desc');
      var sorted = $('#client_review_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.attachment_div_overlay').html()) <
        convertToNumber($(b).find('.attachment_div_overlay').html()))) ? 1 : -1;
      });
    }
    else{
      $("#cli_vat_sortoptions").val('asc');
      var sorted = $('#client_review_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.attachment_div_overlay').html()) <
        convertToNumber($(b).find('.attachment_div_overlay').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_review_tbody').html(sorted);
  }
  if($(e.target).hasClass('cli_record_sort'))
  {
    var sort = $("#cli_record_sortoptions").val();
    if(sort == 'asc')
    {
      $("#cli_record_sortoptions").val('desc');
      var sorted = $('#client_review_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.record_sort_val').val()) <
        convertToNumber($(b).find('.record_sort_val').val()))) ? 1 : -1;
      });
    }
    else{
      $("#cli_record_sortoptions").val('asc');
      var sorted = $('#client_review_tbody').find('.shown_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.record_sort_val').val()) <
        convertToNumber($(b).find('.record_sort_val').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_review_tbody').html(sorted);
  }
  if($(e.target).hasClass('refresh_submitted_file')){

    $("#input").show();
    $("#processor").show();
    $("#output").show();

    var download_url = $(e.target).attr("data-element");
    var client_id = $(e.target).attr("data-client");
    var month_year = $(e.target).attr("data-month");
    $("#input").attr("src",download_url);
    $("#processor").attr("src","<?php echo url('pdftotext/index.html'); ?>");
    
    var input = document.getElementById("input");
    var processor = document.getElementById("processor");
    var output = document.getElementById("output");

    $("body").addClass("loading"); 
    window.addEventListener("message", function refreshlisteners(event){ 
      if (event.source != processor.contentWindow) return;
      switch (event.data){
        case "ready":
          var xhr = new XMLHttpRequest;
          xhr.open('GET', input.getAttribute("src"), true);
          xhr.responseType = "arraybuffer";
          xhr.onload = function(event) {
            processor.contentWindow.postMessage(this.response, "*");
          };
          xhr.send();
        break;
        default:
          output.textContent = $.trim(event.data.replace(/[^a-z0-9.,:' ]+/gi, " "));
          var error = 0;
          console.log(output.textContent);
          if(output.textContent.indexOf('Amended VAT3 Return') !== -1){
            var t2_string = output.textContent.split('Amended VAT3 Return');
            var t2_array = $.trim(t2_string[0]).split(' ');
            var t2 = t2_array[t2_array.length-1];

            var t2_string_array = $.trim(t2_string[1]).split(' ');

            if($.inArray("T3",t2_string_array) !== -1){
              var t1_string = t2_string[1].split(' Offset Instructions ');
              var t1_array = $.trim(t1_string[1]).split(' ');
              var t3 = t1_array[2];
              var t1 = parseFloat(parseFloat(t3) + parseFloat(t2)).toFixed(2);

            }else if($.inArray("T4",t2_string_array) !== -1){
              var t1_string = t2_string[1].split(' Offset Instructions ');
              var t1_array = $.trim(t1_string[1]).split(' ');
              var t3 = t1_array[2];
              var t1 = parseFloat(parseFloat(t3) - parseFloat(t2)).toFixed(2);

              if(t1 < 0){
                t1 = (t1 * -1).toFixed(2);
              }
            }
            else{
              var error = 1;
            }

            var reg = t1_array[1];
          }
          else if(output.textContent.indexOf('VAT3 Return') !== -1){
            var t2_string = output.textContent.split('VAT3 Return');
            var t2_array = $.trim(t2_string[0]).split(' ');
            var t2 = t2_array[t2_array.length-1];

            var t2_string_array = $.trim(t2_string[1]).split(' ');

            if($.inArray("T3",t2_string_array) !== -1){
              var t1_string = t2_string[1].split(' Offset Instructions ');
              var t1_array = $.trim(t1_string[1]).split(' ');
              var t3 = t1_array[2];
              var t1 = parseFloat(parseFloat(t3) + parseFloat(t2)).toFixed(2);

            }else if($.inArray("T4",t2_string_array) !== -1){
              var t1_string = t2_string[1].split(' Offset Instructions ');
              var t1_array = $.trim(t1_string[1]).split(' ');
              var t3 = t1_array[2];
              var t1 = parseFloat(parseFloat(t3) - parseFloat(t2)).toFixed(2);

              if(t1 < 0){
                t1 = (t1 * -1).toFixed(2);
              }
            }
            else{
              var error = 1;
            }
            var reg = t1_array[1];
          }
          else{
            var error = 2;
          }

          if(error == 1){
            $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:#f00>Invalid VAT3 File.</p>",width:"30%",height:"15%"});
            $("body").removeClass("loading"); 
            $("#input").hide();
            $("#processor").hide();
            $("#output").hide();
            window.removeEventListener("message",refreshlisteners);
          }
          else if(error == 2){
            $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:#f00>Invalid VAT3 File.</p>",width:"30%",height:"15%"});
            $("body").removeClass("loading"); 
            $("#input").hide();
            $("#processor").hide();
            $("#output").hide();
            window.removeEventListener("message",refreshlisteners);
          }
          else if(error == 3){
            $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:#f00>File can not be Uploaded as the Tax Numbers do not match.</p>",width:"30%",height:"15%"});
            $("body").removeClass("loading"); 
            $("#input").hide();
            $("#processor").hide();
            $("#output").hide();
            window.removeEventListener("message",refreshlisteners);
          }
          else{
            $.ajax({
              url:"<?php echo URL::to('user/vat_refresh_upload_images'); ?>",
              type:"post",
              data:{client_id:client_id,month_year:month_year,t1:t1,t2:t2},
              success:function(result)
              {
                $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".t1_spam").parents("p").show();
                $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".t2_spam").parents("p").show();
                $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".t1_spam").html(t1);
                $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".t2_spam").html(t2);

                $(".shown_tr_"+client_id+"_"+month_year).find(".t1_spam_overlay").parents("p").show();
                $(".shown_tr_"+client_id+"_"+month_year).find(".t2_spam_overlay").parents("p").show();

                $(".shown_tr_"+client_id+"_"+month_year).find(".t1_spam_overlay").html(t1);
                $(".shown_tr_"+client_id+"_"+month_year).find(".t2_spam_overlay").html(t2);

                $(".shown_tr_"+client_id+"_"+month_year).find(".month_download_checkbox").prop("disabled",false);
                $(".dropzone_progress_modal").modal("hide");

                $("#processor").hide();
                $("#output").hide();
                $("#input").hide();

                $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green>T1 & T2 Values are updated Successfully.</p>",width:"30%",height:"15%"});
                $("body").removeClass("loading");  
                $("#input").hide();
                $("#processor").hide();
                $("#output").hide();
                window.removeEventListener("message",refreshlisteners);
              }
            })
          }
        break;
      }
    });
    
  }
  if($(e.target).hasClass('download_selected_periods'))
  {
    var checked_count = $(".month_download_checkbox:checked").length;
    if(checked_count > 0)
    {
      $("body").addClass("loading");
      var months = '';
      $(".month_download_checkbox:checked").each(function() {
        var month = $(this).attr("data-month");
        if(months == "")
        {
          months = month;
        }
        else{
          months = months+','+month;
        }
      });
      var client_id = $("#hidden_vat_client_id_overlay").val();
      $.ajax({
        url:"<?php echo URL::to('user/download_selected_periods_vat_attachments'); ?>",
        type:"post",
        data:{client_id:client_id,months:months},
        success:function(result)
        {
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
      })
    }
    else{
      alert("Please select the Periods to download the attachments");
    }
  }
  if($(e.target).hasClass('vat3_upload_btn')){
    var client_id = $("#hidden_client_id_vat").val();
    var month_year = $("#hidden_month_year_vat").val();
    var filename = $(".hidden_vat3_filename").val();
    var dir = $(".hidden_vat3_dir").val();
    var t1 = $(".vat3_t1").html();
    var t2 = $(".vat3_t2").html();
    var reg = $(".vat3_reg_no").html();
    var client_tax_no = $(".client_tax_no").html();

    if(client_tax_no != reg){
      alert("The Registration No and the client tax no is different.");
    }
    else{
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/vat_commit_upload_images'); ?>",
        type:"post",
        dataType:"json",
        data:{client_id:client_id,month_year:month_year,filename:filename,dir:dir,t1:t1,t2:t2,reg:reg},
        success:function(result)
        {

          $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".attachment_div").append("<p><a href='javascript:' data-element='"+result['download_url']+"' class='file_attachments' title='"+filename+"' download><img src='<?php echo URL::to('assets/images/pdf.png'); ?>' style='width:30px !important'></a> <a href='"+result['delete_url']+"' class='fa fa-trash delete_attachments'></a></p>");
          $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".records_receive_label").attr("class","records_receive_label submitted_td");

          $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".t1_spam").parents("p").show();
          $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".t2_spam").parents("p").show();
          $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".t1_spam").html(t1);
          $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".t2_spam").html(t2);

          $(".shown_tr_"+client_id+"_"+month_year).find(".attachment_div_overlay").append("<p><a href='javascript:' data-element='"+result['download_url']+"' class='file_attachments'>"+filename+"</a> <a href='"+result['delete_url']+"' class='fa fa-trash delete_attachments'></a></p>");
          $(".shown_tr_"+client_id+"_"+month_year).find(".records_receive_label_overlay").attr("class","records_receive_label_overlay submitted_td_overlay");

          $(".shown_tr_"+client_id+"_"+month_year).find(".t1_spam_overlay").parents("p").show();
          $(".shown_tr_"+client_id+"_"+month_year).find(".t2_spam_overlay").parents("p").show();

          $(".shown_tr_"+client_id+"_"+month_year).find(".t1_spam_overlay").html(t1);
          $(".shown_tr_"+client_id+"_"+month_year).find(".t2_spam_overlay").html(t2);

          $(".shown_tr_"+client_id+"_"+month_year).find(".month_download_checkbox").prop("disabled",false);
          $(".dropzone_progress_modal").modal("hide");

          $("#processor").hide();
          $("#output").hide();
          $("#input").hide();

          var submitted_date = $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".submitted_import").val();
          if((/\d/.test(submitted_date)) && submitted_date != "")
          {
            var r = confirm("Are you sure you want to update the submission date?");
            if(r)
            {
              $.ajax({
                url:"<?php echo URL::to('user/check_submitted_date_vat_reviews'); ?>",
                type:"post",
                data:{month:month_year,client:client_id},
                success:function(resultt)
                {
                  $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".submitted_import").val(resultt);
                  $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".delete_submitted").show();

                  $(".shown_tr_"+client_id+"_"+month_year).find(".submitted_import_overlay").val(resultt);
                  $(".shown_tr_"+client_id+"_"+month_year).find(".delete_submitted").show();
                  $(".shown_tr_"+client_id+"_"+month_year).find(".date_sort_val").val(resultt);

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
              data:{month:month_year,client:client_id},
              success:function(resultt)
              {
                $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".submitted_import").val(resultt);
                $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".import_icon").removeClass("orange_import").removeClass("red_import").removeClass("blue_import").removeClass("green_import").removeClass("white_import").addClass("green_import");
                $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".import_icon").html("Submitted");
                $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".delete_submitted").show();

                $(".shown_tr_"+client_id+"_"+month_year).find(".submitted_import_overlay").val(resultt);
                $(".shown_tr_"+client_id+"_"+month_year).find(".date_sort_val").val(resultt);
                $(".shown_tr_"+client_id+"_"+month_year).find(".import_icon_overlay").removeClass("orange_import_overlay").removeClass("red_import_overlay").removeClass("blue_import_overlay").removeClass("green_import_overlay").removeClass("white_import_overlay").addClass("green_import_overlay");
                $(".shown_tr_"+client_id+"_"+month_year).find(".import_icon_overlay").html("Submitted");
                $(".shown_tr_"+client_id+"_"+month_year).find(".delete_submitted_overlay").show();

                $("body").removeClass("loading");
              }
            })
          }
        }
      })
    }
  }
  if($(e.target).hasClass('vat_client_class'))
  {
    var client_id = $(e.target).attr("data-element");
    var code = $(e.target).attr("data-code");
    var client_name = $(e.target).attr("data-client");

    $("#hidden_vat_client_id_overlay").val(client_id);
    $(".vat_clients_months_modal").modal("show");
    $("#client_review_tbody").html('<tr><td colspan="6" style="text-align:center">No Datas Found</td></tr>');
    $("#hide_non_return").prop("checked",false);

    $(".client_review_title").html('Client Review - '+code+' '+client_name+'');

  }
  if($(e.target).hasClass('load_all_month_btn'))
  {
    var year = $(".client_review_select_year").val();
    var client_id = $("#hidden_vat_client_id_overlay").val();
    if(year != "")
    {
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/get_client_review_for_year'); ?>",
        type:"post",
        data:{year:year,client_id:client_id},
        success:function(result)
        {
          $("#client_review_tbody").html(result);
          ajax_functions();
          $("body").removeClass("loading");
        }
      })
    }
    else{
      alert("Please select the Year");
    }
  }
  if($(e.target).hasClass('hide_non_return'))
  {
    if($(e.target).is(":checked"))
    {
      $(".shownn_tr").show();
      $(".non_returning").hide();
    }
    else{
      $(".shownn_tr").show();
    }
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

        var prev_count = $("#task_body").find("td:nth-child(4)").find(".orange_import").length;
        var curr_count = $("#task_body").find("td:nth-child(5)").find(".orange_import").length;
        var next_count = $("#task_body").find("td:nth-child(6)").find(".orange_import").length;

        var red_prev_count = $("#task_body").find("td:nth-child(4)").find(".red_import").length;
        var red_curr_count = $("#task_body").find("td:nth-child(5)").find(".red_import").length;
        var red_next_count = $("#task_body").find("td:nth-child(6)").find(".red_import").length;

        var green_prev_count = $("#task_body").find("td:nth-child(4)").find(".green_import").length;
        var green_curr_count = $("#task_body").find("td:nth-child(5)").find(".green_import").length;
        var green_next_count = $("#task_body").find("td:nth-child(6)").find(".green_import").length;

        console.log(green_prev_count+'--'+green_curr_count+'--'+green_next_count);



        $('.submission_due_no').eq(0).find(".no_sub_due").html(prev_count);
        $('.submission_due_no').eq(1).find(".no_sub_due").html(curr_count);
        $('.submission_due_no').eq(2).find(".no_sub_due").html(next_count);

        $('.submission_os_no').eq(0).find(".no_sub_os").html(red_prev_count);
        $('.submission_os_no').eq(1).find(".no_sub_os").html(red_curr_count);
        $('.submission_os_no').eq(2).find(".no_sub_os").html(red_next_count);

        $('.submitted_no').eq(0).find(".no_sub").html(green_prev_count);
        $('.submitted_no').eq(1).find(".no_sub").html(green_curr_count);
        $('.submitted_no').eq(2).find(".no_sub").html(green_next_count);
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

        var prev_count = $("#task_body").find("td:nth-child(4)").find(".orange_import").length;
        var curr_count = $("#task_body").find("td:nth-child(5)").find(".orange_import").length;
        var next_count = $("#task_body").find("td:nth-child(6)").find(".orange_import").length;

        var red_prev_count = $("#task_body").find("td:nth-child(4)").find(".red_import").length;
        var red_curr_count = $("#task_body").find("td:nth-child(5)").find(".red_import").length;
        var red_next_count = $("#task_body").find("td:nth-child(6)").find(".red_import").length;


        var green_prev_count = $("#task_body").find("td:nth-child(4)").find(".green_import").length;
        var green_curr_count = $("#task_body").find("td:nth-child(5)").find(".green_import").length;
        var green_next_count = $("#task_body").find("td:nth-child(6)").find(".green_import").length;

        console.log(green_prev_count+'--'+green_curr_count+'--'+green_next_count);

        $('.submission_due_no').eq(0).find(".no_sub_due").html(prev_count);
        $('.submission_due_no').eq(1).find(".no_sub_due").html(curr_count);
        $('.submission_due_no').eq(2).find(".no_sub_due").html(next_count);

        $('.submission_os_no').eq(0).find(".no_sub_os").html(red_prev_count);
        $('.submission_os_no').eq(1).find(".no_sub_os").html(red_curr_count);
        $('.submission_os_no').eq(2).find(".no_sub_os").html(red_next_count);

        $('.submitted_no').eq(0).find(".no_sub").html(green_prev_count);
        $('.submitted_no').eq(1).find(".no_sub").html(green_curr_count);
        $('.submitted_no').eq(2).find(".no_sub").html(green_next_count);
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
  // if($(e.target).hasClass('file_attachments'))
  // {
  //   var src = $(e.target).attr("data-element");
  //   $(".attachment_pdf").attr("src",src);
  //   $("#show_pdf_viewer").modal("show");
  // }
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
      dataType:"json",
      success:function(result)
      {
        $("#load_all_clients_status").val("1");
        $("#task_body").html(result['output']);
        $("#vat_expand").show();
        var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
        $(".date_text").datetimepicker({
           defaultDate: "",
           format: 'L',
           format: 'DD/MM/YYYY',
        });    
        $('.submission_due_no').eq(0).find(".no_sub_due").html(result['prev_no_sub_due']);
        $('.submission_due_no').eq(1).find(".no_sub_due").html(result['curr_no_sub_due']);
        $('.submission_due_no').eq(2).find(".no_sub_due").html(result['next_no_sub_due']);

        $('.submission_os_no').eq(0).find(".no_sub_os").html(result['prev_no_sub_os']);
        $('.submission_os_no').eq(1).find(".no_sub_os").html(result['curr_no_sub_os']);
        $('.submission_os_no').eq(2).find(".no_sub_os").html(result['next_no_sub_os']);

        $('.submitted_no').eq(0).find(".no_sub").html(result['prev_no_sub']);
        $('.submitted_no').eq(1).find(".no_sub").html(result['curr_no_sub']);
        $('.submitted_no').eq(2).find(".no_sub").html(result['next_no_sub']);

        $(".show_month_in_overlay").eq(0).html(result['prev_month']);
        $(".show_month_in_overlay").eq(1).html(result['curr_month']);
        $(".show_month_in_overlay").eq(2).html(result['next_month']);


        <?php if(Session::has('vat_client_id')) { ?>
            $('html, body').animate({ scrollTop: $(".tasks_tr_<?php echo Session::get('vat_client_id'); ?>").offset().top - 300}, 1000);
        <?php } ?>

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

    $("#processor").show();
    $("#output").show();
    $("#input").show();

    Dropzone.forElement("#imageUpload").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
    $(".vat3_div").hide();
    $(".vat3_error_div").hide();
    $(".vat3_error").html("");
    $(".vat3_upload_btn").hide();
    $(".hidden_vat3_filename").val("");
            $(".hidden_vat3_dir").val("");
  }
  if($(e.target).hasClass('add_attachment_month_year_overlay'))
  {
    var month_year = $(e.target).attr("data-element");
    var client = $(e.target).attr("data-client");
    $("#hidden_month_year_vat").val(month_year);
    $("#hidden_client_id_vat").val(client);
    $(".dropzone_progress_modal").modal("show");

    $("#processor").show();
    $("#output").show();
    $("#input").show();

    Dropzone.forElement("#imageUpload").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
    $(".vat3_div").hide();
    $(".vat3_error_div").hide();
    $(".vat3_error").html("");
    $(".vat3_upload_btn").hide();
    $(".hidden_vat3_filename").val("");
            $(".hidden_vat3_dir").val("");
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

        var prev_count = $("#task_body").find("td:nth-child(4)").find(".orange_import").length;
        var curr_count = $("#task_body").find("td:nth-child(5)").find(".orange_import").length;
        var next_count = $("#task_body").find("td:nth-child(6)").find(".orange_import").length;

        var red_prev_count = $("#task_body").find("td:nth-child(4)").find(".red_import").length;
        var red_curr_count = $("#task_body").find("td:nth-child(5)").find(".red_import").length;
        var red_next_count = $("#task_body").find("td:nth-child(6)").find(".red_import").length;

        var green_prev_count = $("#task_body").find("td:nth-child(4)").find(".green_import").length;
        var green_curr_count = $("#task_body").find("td:nth-child(5)").find(".green_import").length;
        var green_next_count = $("#task_body").find("td:nth-child(6)").find(".green_import").length;

        console.log(green_prev_count+'--'+green_curr_count+'--'+green_next_count);

        $('.submission_due_no').eq(0).find(".no_sub_due").html(prev_count);
        $('.submission_due_no').eq(1).find(".no_sub_due").html(curr_count);
        $('.submission_due_no').eq(2).find(".no_sub_due").html(next_count);

        $('.submission_os_no').eq(0).find(".no_sub_os").html(red_prev_count);
        $('.submission_os_no').eq(1).find(".no_sub_os").html(red_curr_count);
        $('.submission_os_no').eq(2).find(".no_sub_os").html(red_next_count);


        $('.submitted_no').eq(0).find(".no_sub").html(green_prev_count);
        $('.submitted_no').eq(1).find(".no_sub").html(green_curr_count);
        $('.submitted_no').eq(2).find(".no_sub").html(green_next_count);

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
        var prev_count = $("#task_body").find("td:nth-child(4)").find(".orange_import").length;
        var curr_count = $("#task_body").find("td:nth-child(5)").find(".orange_import").length;
        var next_count = $("#task_body").find("td:nth-child(6)").find(".orange_import").length;

        var red_prev_count = $("#task_body").find("td:nth-child(4)").find(".red_import").length;
        var red_curr_count = $("#task_body").find("td:nth-child(5)").find(".red_import").length;
        var red_next_count = $("#task_body").find("td:nth-child(6)").find(".red_import").length;

        var green_prev_count = $("#task_body").find("td:nth-child(4)").find(".green_import").length;
        var green_curr_count = $("#task_body").find("td:nth-child(5)").find(".green_import").length;
        var green_next_count = $("#task_body").find("td:nth-child(6)").find(".green_import").length;

        console.log(green_prev_count+'--'+green_curr_count+'--'+green_next_count);

        $('.submission_due_no').eq(0).find(".no_sub_due").html(prev_count);
        $('.submission_due_no').eq(1).find(".no_sub_due").html(curr_count);
        $('.submission_due_no').eq(2).find(".no_sub_due").html(next_count);

        $('.submission_os_no').eq(0).find(".no_sub_os").html(red_prev_count);
        $('.submission_os_no').eq(1).find(".no_sub_os").html(red_curr_count);
        $('.submission_os_no').eq(2).find(".no_sub_os").html(red_next_count);


        $('.submitted_no').eq(0).find(".no_sub").html(green_prev_count);
        $('.submitted_no').eq(1).find(".no_sub").html(green_curr_count);
        $('.submitted_no').eq(2).find(".no_sub").html(green_next_count);
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

          $(".tasks_tr_"+client).find("#add_files_vat_client_"+month).find(".t1_spam").html("");
          $(".tasks_tr_"+client).find("#add_files_vat_client_"+month).find(".t2_spam").html("");

          $(".shown_tr_"+client+"_"+month).find(".t1_spam_overlay").html("");
          $(".shown_tr_"+client+"_"+month).find(".t2_spam_overlay").html("");
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
 //   var href = $(e.target).attr("data-element");
  // var printWin = window.open(href,'_blank','location=no,height=570,width=650,top=80, left=250,leftscrollbars=yes,status=yes');
  // if (printWin == null || typeof(printWin)=='undefined')
  // {
  //  alert('Please uncheck the option "Block Popup windows" to allow the popup window generated from our website.');
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
    var client = $(e.target).attr("data-client");
    var cmclient = $(e.target).attr("data-cmclient");
    var month = $(e.target).attr("data-month");
    var clientname = $(e.target).attr("data-element");

    $("#hidden_vat_client_id").val(client);
    $("#hidden_vat_month").val(month);
    $(".client_search_class_task").val(clientname);
    $("#client_search_task").val(cmclient);
    $(".client_search_class_task").prop("readonly",true);
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
    $(".subject_class").val("VAT System Task");
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
});
function draglistenerssub(event,input,processor,output,taxno)
{
  // if (event.source != processor.contentWindow) return;
  // switch (event.data){
  //   case "ready":
  //     var xhr = new XMLHttpRequest;
  //     xhr.open('GET', input.getAttribute("src"), true);
  //     xhr.responseType = "arraybuffer";
  //     xhr.onload = function(event) {
  //       processor.contentWindow.postMessage(this.response, "*");
  //     };
  //     xhr.send();
  //   break;
  //   default:
  //     output.textContent = $.trim(event.data.replace(/[^a-z0-9.,:' ]+/gi, " "));
      
  //     var error = 0;
  //     if(output.textContent.indexOf('Amended VAT3 Return') !== -1){
  //       var t2_string = output.textContent.split('Amended VAT3 Return');
  //       var t2_array = $.trim(t2_string[0]).split(' ');
  //       var t2 = t2_array[t2_array.length-1];

  //       var t2_string_array = $.trim(t2_string[1]).split(' ');

  //       if($.inArray("T3",t2_string_array) !== -1){
  //         var t1_string = t2_string[1].split(' Offset Instructions ');
  //         var t1_array = $.trim(t1_string[1]).split(' ');
  //         var t3 = t1_array[2];
  //         var t1 = parseFloat(parseFloat(t3) + parseFloat(t2)).toFixed(2);

  //       }else if($.inArray("T4",t2_string_array) !== -1){
  //         var t1_string = t2_string[1].split(' Offset Instructions ');
  //         var t1_array = $.trim(t1_string[1]).split(' ');
  //         var t3 = t1_array[2];
  //         var t1 = parseFloat(parseFloat(t3) - parseFloat(t2)).toFixed(2);
  //       }
  //       else{
  //         var error = 1;
  //       }

  //       var reg = t1_array[1];
        
  //       $(".vat3_reg_no").html(reg);
  //       $(".vat3_t1").html(t1);
  //       $(".vat3_t2").html(t2);
  //       $(".vat3_div").show();
  //       if(reg != taxno)
  //       {
  //         var error = 3;
  //       }
        
  //     }
  //     else if(output.textContent.indexOf('VAT3 Return') !== -1){
  //       var t2_string = output.textContent.split('VAT3 Return');
  //       var t2_array = $.trim(t2_string[0]).split(' ');
  //       var t2 = t2_array[t2_array.length-1];

  //       var t2_string_array = $.trim(t2_string[1]).split(' ');

  //       if($.inArray("T3",t2_string_array) !== -1){
  //         var t1_string = t2_string[1].split(' Offset Instructions ');
  //         var t1_array = $.trim(t1_string[1]).split(' ');
  //         var t3 = t1_array[2];
  //         var t1 = parseFloat(parseFloat(t3) + parseFloat(t2)).toFixed(2);

  //       }else if($.inArray("T4",t2_string_array) !== -1){
  //         var t1_string = t2_string[1].split(' Offset Instructions ');
  //         var t1_array = $.trim(t1_string[1]).split(' ');
  //         var t3 = t1_array[2];
  //         var t1 = parseFloat(parseFloat(t3) - parseFloat(t2)).toFixed(2);
  //       }
  //       else{
  //         var error = 1;
  //       }

  //       var reg = t1_array[1];
  //       console.log(t1+'--'+t2+'--'+reg);
  //       $(".vat3_reg_no").html(reg);
  //       $(".vat3_t1").html(t1);
  //       $(".vat3_t2").html(t2);
  //       $(".vat3_div").show();
  //       if(reg != taxno)
  //       {
  //         var error = 3;
  //       }
        
  //     }
  //     else{
  //       var error = 2;
  //     }

  //     if(error == 1){
  //       $(".vat3_error_div").show();
  //       $(".vat3_error").html("Invalid VAT3 File");
  //       $(".vat3_upload_btn").hide();
  //       $("body").removeClass("loading");       
  //     }
  //     else if(error == 2){
  //       $(".vat3_error_div").show();
  //       $(".vat3_error").html("Invalid VAT3 File");
  //       $(".vat3_upload_btn").hide();
  //       $("body").removeClass("loading");    
  //     }
  //     else if(error == 3){
  //       $(".vat3_error_div").show();
  //       $(".vat3_error").html("File can not be Uploaded as the Tax Numbers do not match");
  //       $(".vat3_upload_btn").hide();
  //       $("body").removeClass("loading");    
  //     }
  //     else{
  //       $(".vat3_error_div").hide();
  //       $(".vat3_error").html("");
  //       $(".vat3_upload_btn").show();
  //       $("body").removeClass("loading");    
  //     }
  //   break;
  // }

  // window.removeEventListener("message",draglistenerssub);
}
function refreshlistenerssub(event,input,processor,output,client_id,month_year)
{
  // if (event.source != processor.contentWindow) return;
  // switch (event.data){
  //   case "ready":
  //     var xhr = new XMLHttpRequest;
  //     xhr.open('GET', input.getAttribute("src"), true);
  //     xhr.responseType = "arraybuffer";
  //     xhr.onload = function(event) {
  //       processor.contentWindow.postMessage(this.response, "*");
  //     };
  //     xhr.send();
  //   break;
  //   default:
  //     output.textContent = $.trim(event.data.replace(/[^a-z0-9.,:' ]+/gi, " "));
  //     var error = 0;
  //     console.log(output.textContent);
  //     if(output.textContent.indexOf('Amended VAT3 Return') !== -1){
  //       var t2_string = output.textContent.split('Amended VAT3 Return');
  //       var t2_array = $.trim(t2_string[0]).split(' ');
  //       var t2 = t2_array[t2_array.length-1];

  //       var t2_string_array = $.trim(t2_string[1]).split(' ');

  //       if($.inArray("T3",t2_string_array) !== -1){
  //         var t1_string = t2_string[1].split(' Offset Instructions ');
  //         var t1_array = $.trim(t1_string[1]).split(' ');
  //         var t3 = t1_array[2];
  //         var t1 = parseFloat(parseFloat(t3) + parseFloat(t2)).toFixed(2);

  //       }else if($.inArray("T4",t2_string_array) !== -1){
  //         var t1_string = t2_string[1].split(' Offset Instructions ');
  //         var t1_array = $.trim(t1_string[1]).split(' ');
  //         var t3 = t1_array[2];
  //         var t1 = parseFloat(parseFloat(t3) - parseFloat(t2)).toFixed(2);
  //       }
  //       else{
  //         var error = 1;
  //       }

  //       var reg = t1_array[1];
  //     }
  //     else if(output.textContent.indexOf('VAT3 Return') !== -1){
  //       var t2_string = output.textContent.split('VAT3 Return');
  //       var t2_array = $.trim(t2_string[0]).split(' ');
  //       var t2 = t2_array[t2_array.length-1];

  //       var t2_string_array = $.trim(t2_string[1]).split(' ');

  //       if($.inArray("T3",t2_string_array) !== -1){
  //         var t1_string = t2_string[1].split(' Offset Instructions ');
  //         var t1_array = $.trim(t1_string[1]).split(' ');
  //         var t3 = t1_array[2];
  //         var t1 = parseFloat(parseFloat(t3) + parseFloat(t2)).toFixed(2);

  //       }else if($.inArray("T4",t2_string_array) !== -1){
  //         var t1_string = t2_string[1].split(' Offset Instructions ');
  //         var t1_array = $.trim(t1_string[1]).split(' ');
  //         var t3 = t1_array[2];
  //         var t1 = parseFloat(parseFloat(t3) - parseFloat(t2)).toFixed(2);
  //       }
  //       else{
  //         var error = 1;
  //       }
  //       var reg = t1_array[1];
  //     }
  //     else{
  //       var error = 2;
  //     }

  //     console.log(error);
  //     if(error == 1){
  //       $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:#f00>Invalid VAT3 File.</p>",height:"22%"});
  //       $("body").removeClass("loading");       
  //     }
  //     else if(error == 2){
  //       $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:#f00>Invalid VAT3 File.</p>",height:"22%"});
  //       $("body").removeClass("loading");    
  //     }
  //     else if(error == 3){
  //       $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:#f00>File can not be Uploaded as the Tax Numbers do not match.</p>",height:"22%"});
  //       $("body").removeClass("loading");    
  //     }
  //     else{
  //       $.ajax({
  //         url:"<?php echo URL::to('user/vat_refresh_upload_images'); ?>",
  //         type:"post",
  //         data:{client_id:client_id,month_year:month_year,t1:t1,t2:t2},
  //         success:function(result)
  //         {
  //           $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".t1_spam").parents("p").show();
  //           $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".t2_spam").parents("p").show();
  //           $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".t1_spam").html(t1);
  //           $(".tasks_tr_"+client_id).find("#add_files_vat_client_"+month_year).find(".t2_spam").html(t2);

  //           $(".shown_tr_"+client_id+"_"+month_year).find(".t1_spam_overlay").parents("p").show();
  //           $(".shown_tr_"+client_id+"_"+month_year).find(".t2_spam_overlay").parents("p").show();

  //           $(".shown_tr_"+client_id+"_"+month_year).find(".t1_spam_overlay").html(t1);
  //           $(".shown_tr_"+client_id+"_"+month_year).find(".t2_spam_overlay").html(t2);

  //           $(".shown_tr_"+client_id+"_"+month_year).find(".month_download_checkbox").prop("disabled",false);
  //           $(".dropzone_progress_modal").modal("hide");

  //           $("#processor").hide();
  //           $("#output").hide();
  //           $("#input").hide();

  //           $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:#000>Updated Successfully.</p>",height:"22%"});
  //         }
  //       })
  //       $("body").removeClass("loading");  
  //       $("#input").hide();
  //       $("#processor").hide();
  //       $("#output").hide();  
  //     }
  //   break;
  // }
  // window.removeEventListener("message",refreshlisteners);
}
fileList = new Array();
Dropzone.options.imageUpload = {
    maxFiles: 1,
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
            $(".client_tax_no").html(obj.tax_no);

            $(".hidden_vat3_filename").val(obj.filename);
            $(".hidden_vat3_dir").val(obj.upload_dir);

            $("#input").attr("src",obj.download_url);
            $("#processor").attr("src","<?php echo url('pdftotext/index.html'); ?>");
            var input = document.getElementById("input");
            var processor = document.getElementById("processor");
            var output = document.getElementById("output");
            var taxno = obj.tax_no;
            window.addEventListener("message", function draglisteners(event) {
              if (event.source != processor.contentWindow) return;
              switch (event.data){
                case "ready":
                  var xhr = new XMLHttpRequest;
                  xhr.open('GET', input.getAttribute("src"), true);
                  xhr.responseType = "arraybuffer";
                  xhr.onload = function(event) {
                    processor.contentWindow.postMessage(this.response, "*");
                  };
                  xhr.send();
                break;
                default:
                  output.textContent = $.trim(event.data.replace(/[^a-z0-9.,:' ]+/gi, " "));
                  var error = 0;
                  if(output.textContent.indexOf('Amended VAT3 Return') !== -1){
                    var t2_string = output.textContent.split('Amended VAT3 Return');
                    var t2_array = $.trim(t2_string[0]).split(' ');
                    var t2 = t2_array[t2_array.length-1];

                    var t2_string_array = $.trim(t2_string[1]).split(' ');

                    if($.inArray("T3",t2_string_array) !== -1){
                      var t1_string = t2_string[1].split(' Offset Instructions ');
                      var t1_array = $.trim(t1_string[1]).split(' ');
                      var t3 = t1_array[2];
                      var t1 = parseFloat(parseFloat(t3) + parseFloat(t2)).toFixed(2);

                    }else if($.inArray("T4",t2_string_array) !== -1){
                      var t1_string = t2_string[1].split(' Offset Instructions ');
                      var t1_array = $.trim(t1_string[1]).split(' ');
                      var t3 = t1_array[2];
                      var t1 = parseFloat(parseFloat(t3) - parseFloat(t2)).toFixed(2);

                      if(t1 < 0){
                        t1 = (t1 * -1).toFixed(2);
                      }
                    }
                    else{
                      var error = 1;
                    }

                    var reg = t1_array[1];
                    
                    $(".vat3_reg_no").html(reg);
                    $(".vat3_t1").html(t1);
                    $(".vat3_t2").html(t2);
                    $(".vat3_div").show();
                    if(reg != taxno)
                    {
                      var error = 3;
                    }
                    
                  }
                  else if(output.textContent.indexOf('VAT3 Return') !== -1){
                    var t2_string = output.textContent.split('VAT3 Return');
                    var t2_array = $.trim(t2_string[0]).split(' ');
                    var t2 = t2_array[t2_array.length-1];

                    var t2_string_array = $.trim(t2_string[1]).split(' ');

                    if($.inArray("T3",t2_string_array) !== -1){
                      var t1_string = t2_string[1].split(' Offset Instructions ');
                      var t1_array = $.trim(t1_string[1]).split(' ');
                      var t3 = t1_array[2];
                      var t1 = parseFloat(parseFloat(t3) + parseFloat(t2)).toFixed(2);

                    }else if($.inArray("T4",t2_string_array) !== -1){
                      var t1_string = t2_string[1].split(' Offset Instructions ');
                      var t1_array = $.trim(t1_string[1]).split(' ');
                      var t3 = t1_array[2];
                      var t1 = parseFloat(parseFloat(t3) - parseFloat(t2)).toFixed(2);

                      if(t1 < 0){
                        t1 = (t1 * -1).toFixed(2);
                      }
                    }
                    else{
                      var error = 1;
                    }

                    var reg = t1_array[1];
                    console.log(t1+'--'+t2+'--'+reg);
                    $(".vat3_reg_no").html(reg);
                    $(".vat3_t1").html(t1);
                    $(".vat3_t2").html(t2);
                    $(".vat3_div").show();
                    if(reg != taxno)
                    {
                      var error = 3;
                    }
                    
                  }
                  else{
                    var error = 2;
                  }

                  if(error == 1){
                    $(".vat3_error_div").show();
                    $(".vat3_error").html("Invalid VAT3 File");
                    $(".vat3_upload_btn").hide();
                    $("body").removeClass("loading");       
                  }
                  else if(error == 2){
                    $(".vat3_error_div").show();
                    $(".vat3_error").html("Invalid VAT3 File");
                    $(".vat3_upload_btn").hide();
                    $("body").removeClass("loading");    
                  }
                  else if(error == 3){
                    $(".vat3_error_div").show();
                    $(".vat3_error").html("File can not be Uploaded as the Tax Numbers do not match");
                    $(".vat3_upload_btn").hide();
                    $("body").removeClass("loading");    
                  }
                  else{
                    $(".vat3_error_div").hide();
                    $(".vat3_error").html("");
                    $(".vat3_upload_btn").show();
                    $("body").removeClass("loading");    
                  }

                  window.removeEventListener("message",draglisteners);
                break;
              }
            });
            
        });
        this.on("complete", function (file, response) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            //Dropzone.forElement("#imageUpload").removeAllFiles(true);
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