@extends('userheader')
@section('content')

<script src="<?php echo URL::to('assets/ckeditor/src/js/main1.js'); ?>"></script>
<script src='<?php echo URL::to('assets/js/table-fixed-header_cm.js'); ?>'></script>
<style>
#table_administration_wrapper{ width:98%; margin-top: 25px; }
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

.modal_load_export {
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
body.loading_export {
    overflow: hidden;   
}
body.loading_export .modal_load_export {
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
.admin_content_section{margin-top:20px;}
</style>
<script src="<?php echo URL::to('ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('ckeditor/samples/js/sample.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>">
<link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> ">

<div class="content_section" style="margin-bottom:200px">
  <div style="width:100%;background: #f5f5f5">
    <div class="page_title" style="z-index:999">
      <h4 class="col-lg-12 padding_00 new_main_title">Task Overview</h4>
    </div>
    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
        <a href="<?php echo URL::to('user/task_manager'); ?>" class="nav-link">
          <spam id="open_task_count">Your Open Tasks (<?php echo count($open_task_count); ?>)</spam>
          <spam id="authored_task_count" style="display:none">Your Authored Tasks (<?php echo count($authored_task_count); ?>)</spam>
        </a>
      </li>
      <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
        <a href="<?php echo URL::to('user/park_task'); ?>" class="nav-link" id="home-tab">
          <spam id="park_task_count">Park Tasks (<spam id="park_task_count_val"><?php echo count($park_task_count); ?></spam>)</spam>
        </a>
      </li>
      <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
        <a href="<?php echo URL::to('user/taskmanager_search'); ?>" class="nav-link">Task Search</a>
      </li>
      <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
        <a href="<?php echo URL::to('user/task_administration'); ?>" class="nav-link" style="border-bottom: 2px solid #fff">
          Task Administration</a>
      </li>
      <li class="nav-item waves-effect waves-light active" style="width:20%;text-align: center">
        <a href="#home" class="nav-link" id="home-tab">Task Overview</a>
      </li>
    </ul>
  </div>
  <div class="tab-content" id="myTabContent">
    <div class="tab-pane active in" id="home" role="tabpanel" aria-labelledby="home-tab">
        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
          <div class="modal-dialog modal-sm" role="document">
            <form action="<?php echo URL::to('user/add_user'); ?>" method="post" class="addsp">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add Employee</h4>
                  </div>
                  <div class="modal-body">
                    <table class="table">
                        <tr>
                            <td><input type="text" name="name" class="form-control" placeholder="Enter First Name" required></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="lname" class="form-control" placeholder="Enter Last Name" required></td>
                        </tr>
                        <tr>
                            <td><input type="email" name="email" class="form-control" placeholder="Enter Email ID" required></td>
                        </tr>
                        <tr style="display:none">
                            <td><input type="text" name="initial" class="form-control" placeholder="Enter Initial"></td>
                        </tr>

                    </table>
                  </div>
                  <div class="modal-footer">
                    <input type="submit" value="Submit" class="common_black_button float_right">
                  </div>
                </div>
            </form>

            <form action="<?php echo URL::to('user/update_user'); ?>" method="post" class="editsp">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Edit Employee</h4>
                  </div>
                  <div class="modal-body">
                    <table class="table">
                        <tr>
                            <td><input type="text" name="name" class="form-control name_class" placeholder="Enter First Name" required></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="lname" class="form-control lname_class" placeholder="Enter Last Name" required></td>
                        </tr>
                        <tr>
                            <td><input type="email" name="email" class="form-control email_class" placeholder="Enter Email ID" required>
                              <input type="hidden" name="id" class="form-control name_id"></td>
                        </tr>
                        <tr style="display:none">
                            <td><input type="text" name="initial" class="form-control initial_class" placeholder="Enter Initial">
                        </tr>
                    </table>
                  </div>
                  <div class="modal-footer">
                    <input type="submit" value="Update" class="btn common_black_button">
                  </div>
                </div>
            </form>
          </div>
        </div>



        <style type="text/css">
        .table2 tr td{border-top: 0px !important; padding-left: 0px !important; line-height: 30px !important;}
        .form-control[readonly]{background-color: #dcdcdc !important}
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
            <div class="col-lg-12 padding_00">
              <div class="col-lg-6 text-left padding_00">
                <div class="sub_title">Manage Employees<a href="javascript:" class="common_black_button load_emp_details" id="load_emp_details">Load Details</a></div>
              </div>
              <div class="col-lg-6 text-right">
                <a href="javascript:" class="addclass common_black_button float_right" data-toggle="modal" data-target=".bs-example-modal-sm">Add New Employee</a>
              </div>
            </div>
            <table class="table">
                <thead>
                  <tr>
                      <th width="5%" style="text-align: left;">S.No</th>
                      <th style="text-align: left;">First Name</th>
                      <th style="text-align: left;">Last Name</th>
                      <th style="text-align: left;">Email</th>
                      <th style="text-align: left;">No of Open Tasks</th>
                      <th style="text-align: left;">No of Parked Tasks</th>
                      
                  </tr>
                </thead>
                <tbody id="employee_tbody">
                  <?php
                    $i=1;
                    if(count($userlist)){              
                      foreach($userlist as $user){
                  ?>
                  <tr>            
                    <td style="text-align: left;"><?php echo $i;?></td>            
                    <td style="text-align: left;"><a href="javascript:" class="costing_class" data-element="<?php echo base64_encode($user->user_id) ?>"><?php echo $user->firstname; ?></a></td>
                    <td style="text-align: left;"><?php echo $user->lastname; ?></td>
                    <td style="text-align: left;"><?php echo $user->email; ?></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <?php
                      $i++;
                      }              
                    }
                    else{
                      echo'<tr><td colspan="6" align="center">Empty</td></tr>';
                    }
                  ?>
                  
                </tbody>
            </table>

            <div class="col-lg-12 padding_00" style="margin-top:30px;border-top:1px solid #ddd;padding-top:20px;padding-bottom:20px">
              <div class="col-lg-12 text-left padding_00">
                <div class="sub_title">Manage Clients<a href="javascript:" class="common_black_button load_client_details" id="load_client_details">Load Details</a></div>
              </div>
            </div>
            <table class="table">
                <thead>
                  <tr>
                      <th width="5%" style="text-align: left;">S.No</th>
                      <th style="text-align: left;">Client ID</th>
                      <th style="text-align: left;">Firstname</th>
                      <th style="text-align: left;">Surname</th>
                      <th style="text-align: left;">Company</th>
                      <th style="text-align: left;">Email</th>
                      <th style="text-align: left;">No of Open Tasks</th>
                      <th style="text-align: left;">No of Parked Tasks</th>
                      
                  </tr>
                </thead>
                <tbody id="client_tbody">
                  <?php
                    $i=1;
                    if(count($clientlist)){              
                      foreach($clientlist as $client){
                        if($client->active == 2) { $color = 'color:#F00 !important'; }
                        else { $color = 'color:#000 !important'; }
                  ?>
                  <tr>            
                    <td style="text-align: left;<?php echo $color; ?>"><?php echo $i;?></td>            
                    <td style="text-align: left;<?php echo $color; ?>"><?php echo $client->client_id; ?></td>
                    <td style="text-align: left;<?php echo $color; ?>"><?php echo $client->firstname; ?></td>
                    <td style="text-align: left;<?php echo $color; ?>"><?php echo $client->surname; ?></td>
                    <td style="text-align: left;<?php echo $color; ?>"><?php echo $client->company; ?></td>
                    <td style="text-align: left;<?php echo $color; ?>"><?php echo $client->email; ?></td>
                    <td></td>
                    <td></td>
                  </tr>
                  <?php
                      $i++;
                      }              
                    }
                    else{
                      echo'<tr><td colspan="8" align="center">Empty</td></tr>';
                    }
                  ?>
                  
                </tbody>
            </table>
          </div>
        </div>
        </div>
    </div>
  </div>
</div>
<div class="modal_load"></div>

<script>
$(".addclass").click( function(){
  $(".addsp").show();
  $(".editsp").hide();
});
$(window).click(function(e) {
  var ascending = false;
  if($(e.target).hasClass('apply_button'))
  {
    var user_id = $("#user_id").val();    
    var from_date = $("#from_date").val();
    var new_cost = $("#new_cost").val();

    if(from_date == "")
    {
      alert("Please select date.");
    }
    else if(new_cost == ""){
      alert("Please enter Cost.");
    }
    else{
          $.ajax({
          url: "<?php echo URL::to('user/manage_user_cost_add') ?>",
          dataType:"json",
          type:"post",
          data:{user_id:user_id,from_date:from_date,new_cost:new_cost},
          success:function(result){
            if(result['alert'] != "")
            {
              alert(result['alert']);
            }
            else{
              $(".staff_cost_details").html(result['output_cost']);
              $("#from_date").val("");
              $("#new_cost").val("");
            }
        }
      })
    }
  }
  if($(e.target).hasClass('load_emp_details'))
  {
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/load_employee_details_overview'); ?>",
      dataType:"json",
      type:"post",
      success:function(result)
      {
        var i = 0;
        $('#employee_tbody').find('tr').each(function(){
          $(this).find('td').eq(4).detach();
          $(this).find('td').eq(4).detach();
          $(this).append(result['tdval'][i]);
          i++;
        });
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('load_client_details'))
  {
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/load_client_details_overview'); ?>",
      dataType:"json",
      type:"post",
      success:function(result)
      {
        var i = 0;
        $('#client_tbody').find('tr').each(function(){
          $(this).find('td').eq(6).detach();
          $(this).find('td').eq(6).detach();
          $(this).append(result['tdval'][i]);
          i++;
        });
        $("body").removeClass("loading");
      }
    })
  }
});
</script>
@stop
