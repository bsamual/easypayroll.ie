@extends('userheader')
@section('content')
<script src="<?php echo URL::to('assets/ckeditor/src/js/main1.js'); ?>"></script>
<script src='<?php echo URL::to('assets/js/table-fixed-header_cm.js'); ?>'></script>
<style>
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
</style>
<script src="<?php echo URL::to('ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('ckeditor/samples/js/sample.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>">
<link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> ">
<?php 
  $admin_details = Db::table('admin')->first();
  $admin_cc = $admin_details->task_cc_email;
?> 
<div class="modal fade invoice_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog" role="document" style="width:50%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Select Invoice</h4>
          </div>
          <div class="modal-body">  
              <div class="row">
                <div class="col-md-6" id="invoice_tbody" style="max-height: 600px; overflow-y: scroll;">

                </div>
                <div class="col-md-6" id="print_invoice" style="height: 600px; font-size: 14px; overflow-y: scroll;">
                    <style type="text/css">
                      .account_table .account_row .account_row_td{font-size: 14px; line-height: 20px; float:left;}
                      .account_table .account_row .account_row_td.left{width:40%;}
                      .account_table .account_row .account_row_td.right{width:60%;}

                      .tax_table_div{width: 100%; height: auto; float: left;}
                      .tax_table{width: 80%; height: auto; float: left;margin-left: 10%;}
                      .tax_table .tax_row .tax_row_td.right{width:30%;text-align: right; padding-right: 10px; border-top:2px solid #000; }

                      .class_row{width: 100%; height: 20px;}

                      .details_table .class_row .class_row_td, .tax_table .tax_row .tax_row_td{ font-size: 14px; font-weight: 600;float:left;}
                      .details_table .class_row .class_row_td.left{width:70%;min-height:10px; text-align: left; float: left; height:20px;}
                      .details_table .class_row .class_row_td.left_corner{width:10%;text-align: right; float: left;height:20px;}
                      .details_table .class_row .class_row_td.right_start{width:10%;text-align: right; float: left;height:20px;}
                      .details_table .class_row .class_row_td.right{width:10%;text-align: right; padding-right: 10px; float: right;height:20px;}

                      .tax_table .tax_row .tax_row_td.left{width:70%;text-align: left; float: left;}
                      .tax_table .tax_row .tax_row_td.right{width:30%;text-align: right; padding-right: 10px; float: right;}

                      .details_table .class_row, .tax_table .tax_row{line-height: 30px; clear: both;}

                      .company_details_class{width: 100%; margin: 0px auto; height: auto;}

                      .company_details_div{width: 40%; height: auto; float: left; margin-top: 220px; margin-left: 10%}
                      .firstname_div{width: 100%; float: left; margin-top: 55px;}
                      .aib_account{ width: 200px; height: auto; float: right; line-height: 20px; color: #ccc; font-size: 12px; }
                      .account_details_div{width: 50%; height:auto; float: left; margin-top: 220px;}
                      .account_details_main_address_div{width: 100%; height: auto; float: right;}
                      .account_details_address_div{width: 100%; height: auto; float: left; }
                      .account_details_invoice_div{width: 200px; height: auto; float: right; clear: both; margin-top: 20px;}
                      .invoice_label{width: 100%; height: auto; float: left; margin: 20px 0px; font-size: 15px; font-weight: bold; text-align: center; letter-spacing: 10px;}
                      .tax_details_class_maindiv{width: 100%; min-height: 539px; float: left;}
                    </style>
                    <div id="letterpad_modal" style="display:none;width: 100%;height:1235px; float: left; background:url('<?php echo URL::to('assets/invoice_letterpad.jpg');?>') no-repeat">
                      <div class="company_details_class"></div>
                      <div class="tax_details_class_maindiv">
                        <div class="details_table" style="width: 80%; height: auto; margin: 0px 10%;">
                          <div class="class_row class_row1"></div>
                          <div class="class_row class_row2"></div>
                          <div class="class_row class_row3"></div>
                          <div class="class_row class_row4"></div>
                          <div class="class_row class_row5"></div>
                          <div class="class_row class_row6"></div>
                          <div class="class_row class_row7"></div>
                          <div class="class_row class_row8"></div>
                          <div class="class_row class_row9"></div>
                          <div class="class_row class_row10"></div>
                          <div class="class_row class_row11"></div>
                          <div class="class_row class_row12"></div>
                          <div class="class_row class_row13"></div>
                          <div class="class_row class_row14"></div>
                          <div class="class_row class_row15"></div>
                          <div class="class_row class_row16"></div>
                          <div class="class_row class_row17"></div>
                          <div class="class_row class_row18"></div>
                          <div class="class_row class_row19"></div>
                          <div class="class_row class_row20"></div>
                        </div>
                      </div>
                      <input type="hidden" name="invoice_number_pdf" id="invoice_number_pdf" value="">
                      <div class="tax_details_class"></div> 
                    </div>
                </div>
              </div>
          </div>
          <div class="modal-footer">  
            <input type="hidden" id="hidden_invoice_id" name="hidden_invoice_id" value="">
            <input type="hidden" id="hidden_task_id_invoice" name="hidden_task_id_invoice" value="">
            <input type="button" class="common_black_button" id="update_task_details" value="Allocate to Task">
          </div>
        </div>
  </div>
</div>
<div class="content_section" style="margin-bottom:200px">
  <div class="page_title" style="z-index:999">
    <div class="col-lg-12 padding_00" style="text-align:center;font-size:20px">
      2Bill Manager
      <a href="javascript:" class="show_billed_items common_black_button" style="float:right;font-size:14px">Show Billed Items Also</a>
      <input type="hidden" name="hidden_billed_items" id="hidden_billed_items" value="0"> 
    </div>
  </div>
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
    <div class="table-responsive" style="width: 100%; float: left;margin-top:10px">
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane active in" id="home" role="tabpanel" aria-labelledby="home-tab">
            <table class="table" id="table_administration" style="width:100%;">
                <thead style="background: #0a0a0a;color: #fff;">
                  <tr>
                    <td>Task ID</td>
                    <td>Client Name</td>
                    <td>Task Subject</td>
                    <td>PDF</td>
                    <td>Task Status</td>
                    <td>Billing Status</td>
                    <td>Invoice</td>
                    <td>Action</td>
                  </tr>
                </thead>
                <tbody id="tbody_show_tasks">
                  <?php
                  $outputtask = '';
                  if(count($taskslist))
                  {
                    foreach($taskslist as $task)
                    {
                      if($task->client_id == "")
                      {
                        $task_details = DB::table('time_task')->where('id', $task->task_type)->first();
                        if(count($task_details))
                        {
                          $title = $task_details->task_name;
                        }
                        else{
                          $title = '';
                        }
                      }
                      else{
                        $client_details = DB::table('cm_clients')->where('client_id', $task->client_id)->first();
                        if(count($client_details))
                        {
                          $title = $client_details->company.' ('.$task->client_id.')';
                        }
                        else{
                          $title = '';
                        }
                      }
                      if($task->status == "2") { $task_status = 'Parked'; }
                      else{
                        if($task->status == "1"){ $task_status = 'Complete'; }
                        else { $task_status = $task->progress.'%'; }
                      }

                      if($task->billing_status == "1") { 
                          $billing_status = 'Billed';
                          $color = 'color:green';
                          $class_billed = 'billed_tr';
                          if($task->invoice == "") { $invoice_no = 'Not Specified'; }
                          else{ $invoice_no = $task->invoice; }
                      }
                      else{ 
                          $billing_status = 'Unbilled';
                          $color = 'color:blue'; 
                          $class_billed = 'unbilled_tr';
                          if($task->invoice == "") { $invoice_no = '<a href="javascript:" class="select_invoice invoice_'.$task->id.'" data-element="'.$task->id.'" data-client="'.$task->client_id.'">Not Specified</a>'; }
                          else{ $invoice_no = '<a href="javascript:" class="select_invoice invoice_'.$task->id.'" data-element="'.$task->id.'" data-client="'.$task->client_id.'">'.$task->invoice.'</a>'; }
                      }

                      $outputtask.='<tr id="tr_task_'.$task->id.'" class="tr_task '.$class_billed.'">
                        <td class="taskid_td">'.$task->taskid.'</td>
                        <td class="taskid_td task_name_val">'.$title.'</td>
                        <td class="task_subject_val">'.$task->subject.'</td>
                        <td class="pdf_val"><a href="javascript:" class="download_pdf_task" data-element="'.$task->id.'" title="Download PDF">PDF</a></td>
                        <td class="task_status_val">'.$task_status.'</td>
                        <td class="billing_status_val" style="font-weight:800;'.$color.'">'.$billing_status.'</td>
                        <td class="invoice_val">'.$invoice_no.'</td>
                        <td>
                          <a href="javascript:" class="fa fa-trash remove_two_bill" data-element="'.$task->id.'" title="Remove 2Bill Status"></a>
                        </td>
                      </tr>';
                    }
                  }
                  else{
                    $outputtask.='<td colspan="10" style="text-align:center">No Tasks Found</td>';
                  }
                  echo $outputtask;
                  ?>
                </tbody>
            </table>
        </div>
      </div>
    </div>
</div>
<div class="modal_load"></div>
<script>
$(function(){
    $('#table_administration').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        aaSorting: [],
    });
});
$(document).ready(function() {
  // $(".creation_date_search_class").datetimepicker({     
  //    format: 'L',
  //    format: 'DD-MMM-YYYY',
  // });
  // $(".due_date_search_class").datetimepicker({
  //    format: 'L',
  //    format: 'DD-MMM-YYYY',
  // });
  $(".billed_tr").hide();
})
// $(window).keyup(function(e) {
//     var valueTimmer;                //timer identifier
//     var valueInterval = 500;  //time in ms, 5 second for example
//     if($(e.target).hasClass('search_by_task'))
//     {
//         var that = $(e.target);
//         var input_val = $(e.target).val();  
//         clearTimeout(valueTimmer);
//         valueTimmer = setTimeout(doneTyping, valueInterval,input_val,that);   
//     }
// });
// function doneTyping(value,targetval)
// {
//   var vv = value.toLowerCase();

//   $(".tr_task").hide();
//   $(".taskid_td").each(function() {
//     var task_id_td = $(this).html();
//     task_id_td = task_id_td.toLowerCase();
//     var n = task_id_td.indexOf(vv);
//     if(n >= 0)
//     {
//       $(this).parents(".tr_task").show();
//     }
//   });

//   $(".subject_td").each(function() {
//     var subject_td = $(this).html();
//     subject_td = subject_td.toLowerCase();
//     var n_subject = subject_td.indexOf(vv);
//     if(n_subject >= 0)
//     {
//       $(this).parents(".tr_task").show();
//     }
//   });
// }
$(window).click(function(e) {
  if($(e.target).hasClass('remove_two_bill'))
  {
    var taskid = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to remove 2Bill Status from this Task?");
    if(r)
    {
      $.ajax({
        url:"<?php echo URL::to('user/remove_2bill_status'); ?>",
        type:"post",
        data:{taskid:taskid},
        success:function(result)
        {
          $(e.target).parents("tr:first").detach();
        }
      })
    }
  }
  if($(e.target).hasClass('invoice_class')){
    $("body").addClass("loading");  
    var editid = $(e.target).attr("data-element");
    $.ajax({
          url: "<?php echo URL::to('user/invoices_print_view') ?>",
          data:{id:editid},
          dataType:'json',
          type:"post",
          success:function(result){  
            $("#invoice_tbody_tr").find("td").css("background","#fff");
            $(e.target).parents("tr:first").find("td").css("background","#dfdfdf");    
             $("#letterpad_modal").show();
             $("body").removeClass("loading");  
             $("#hidden_invoice_id").val(editid);
             $("#invoice_number_pdf").val(editid);
             $(".company_details_class").html(result['companyname']);
             $(".tax_details_class").html(result['taxdetails']);
             $(".class_row1").html(result['row1']);
             $(".class_row2").html(result['row2']);
             $(".class_row3").html(result['row3']);
             $(".class_row4").html(result['row4']);
             $(".class_row5").html(result['row5']);
             $(".class_row6").html(result['row6']);
             $(".class_row7").html(result['row7']);
             $(".class_row8").html(result['row8']);
             $(".class_row9").html(result['row9']);
             $(".class_row10").html(result['row10']);
             $(".class_row11").html(result['row11']);
             $(".class_row12").html(result['row12']);
             $(".class_row13").html(result['row13']);
             $(".class_row14").html(result['row14']);
             $(".class_row15").html(result['row15']);
             $(".class_row16").html(result['row16']);
             $(".class_row17").html(result['row17']);
             $(".class_row18").html(result['row18']);
             $(".class_row19").html(result['row19']);
             $(".class_row20").html(result['row20']);
      }
    });
  }
  if($(e.target).hasClass('select_invoice'))
  {
    var taskid = $(e.target).attr("data-element");
    var client_id = $(e.target).attr("data-client");
    $.ajax({
      url:"<?php echo URL::to('user/get_tasks_invoices'); ?>",
      type:"post",
      data:{taskid:taskid,client_id:client_id},
      success:function(result)
      {
        $("#invoice_tbody").html(result);
        $(".invoice_modal").modal("show");
        $("#letterpad_modal").hide();
        $("#hidden_task_id_invoice").val(taskid);
      }
    })
  }
  if(e.target.id == "update_task_details")
  {
    var taskid = $("#hidden_task_id_invoice").val();
    var invoiceno = $("#hidden_invoice_id").val();
    $.ajax({
      url:"<?php echo URL::to('user/update_invoice_for_task'); ?>",
      type:"post",
      data:{taskid:taskid,invoiceno:invoiceno},
      success:function(result)
      {
        $("#tr_task_"+taskid).find(".invoice_val").find("a").html(invoiceno);
        $(".invoice_modal").modal("hide");
      }
    })
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
  if($(e.target).hasClass('show_billed_items'))
  {
    var value = $("#hidden_billed_items").val();
    if(value == "0")
    {
      $(".billed_tr").show();
      $(e.target).text("Hide Billed Items");
      $("#hidden_billed_items").val("1");
    }
    else{
      $(".billed_tr").hide();
      $(e.target).text("Show Billed Items Also");
      $("#hidden_billed_items").val("0");
    }
  }  
  if($(e.target).hasClass('edit_task'))
  {
    var task_id = $(e.target).attr("data-element");
    var author = $(e.target).attr("data-author");
    var allocated = $(e.target).attr("data-allocated");
    var specifics = $(e.target).attr("data-specifics");
    var files = $(e.target).attr("data-files");
    var recurring = $(e.target).attr("data-recurring");

    var internal = $(e.target).attr("data-internal");
    var taskname = $(e.target).attr("data-taskname");
    var tasktype = $(e.target).attr("data-tasktype");

    $("#hidden_task_id_copy_task").val(task_id);
    $(".select_user_author").val(author);
    $(".allocate_user_add").val(allocated);

    if(specifics == "1")
    {
      $("#retain_specifics").prop("checked",true);
    }
    else{
      $("#retain_specifics").prop("checked",false);
    }

    if(files == "1")
    {
      $("#retain_files").prop("checked",true);
    }
    else{
      $("#retain_files").prop("checked",false);
    }

    if(recurring > "0")
    {
      $("#recurring_task").prop("checked",true);
      $("#recurring_checkbox"+recurring).prop("checked",true);
      $(".accept_recurring_div").show();
    }
    else{
      $("#recurring_task").prop("checked",false);
      $(".accept_recurring_div").hide();
      $(".recurring_checkbox").prop("checked",false);
    }

    if(internal == "yes")
    {
      $(".internal_tasks_grp").show();
      $(".task-choose_internal_change").html(taskname);
      $("#idtask_change").val(tasktype);
    }
    else{
      $(".internal_tasks_grp").hide();
      $(".task-choose_internal_change").html("Select Task");
      $("#idtask_change").val("0");
    }

    $(".edit_task_modal").modal("show");
  }
  if($(e.target).hasClass('tasks_li_internal_change'))
  {
    var taskid = $(e.target).attr('data-element');
    $("#idtask_change").val(taskid);
    $(".task-choose_internal_change:first-child").text($(e.target).text());
  }
  if(e.target.id == "update_task_details")
  {
    var task_id = $("#hidden_task_id_copy_task").val();
    var author = $(".select_user_author").val();
    var allocated = $(".allocate_user_add").val();
    var specifics = $("#hidden_retain_specifics").val();
    var files = $("#hidden_retain_files").val();
    var recurring = $("#hidden_recurring_task").val();
    var days = $(".recurring_checkbox:checked").val();
    var specific_recurring = $(".specific_recurring").val();
    var task_type = $("#idtask_change").val();

    if(author == "")
    {
      alert("Please Select the Author");
    }
    else if(days == "4" && specific_recurring == "")
    {
      alert("Please set number of days for recurring");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/update_taskmanager_details'); ?>",
        type:"post",
        data:{task_id:task_id,author:author,allocated:allocated,specifics:specifics,files:files,recurring:recurring,days:days,specific_recurring:specific_recurring,task_type:task_type},
        dataType:"json",
        success:function(result)
        {
          $("#tr_task_"+task_id).find(".author_td").html(result['author']);
          $("#tr_task_"+task_id).find(".allocated_td").html(result['allocated']);
          $("#tr_task_"+task_id).find(".retain_spec_td").html(result['retain_spec']);
          $("#tr_task_"+task_id).find(".retain_files_td").html(result['retain_files']);
          $("#tr_task_"+task_id).find(".recurring_days_td").html(result['recurring_days']);
          $("#tr_task_"+task_id).find(".recurring_task_td").html(result['recurring_task']);
          if(result['task_type'] == "0")
          {

          }
          else if(result['task_type'] == "")
          {

          }
          else{
            $("#tr_task_"+task_id).find(".task_name_val").html(result['task_name_val']);
            $(".edit_task_"+task_id).attr("data-taskname",result['task_name_val']);
            $(".edit_task_"+task_id).attr("data-tasktype",result['task_type']);
          }
          $(".edit_task_modal").modal("hide");
        }
      })
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
          url:"<?php echo URL::to('user/refresh_taskmanager'); ?>",
          type:"post",
          data:{user_id:user_id},
          success: function(result)
          {
            $("#task_body_open").html(result);
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
          $(".task_specifics").prop("required",true);
          $(".task_specifics_copy").hide();
          $(".task_specifics").val(result['task_specifics']);
          $(".task_specifics_copy_val").html("");
          $("#hidden_task_specifics").val(result['task_specifics']);
        }
        else{
          $(".task_specifics_add").hide();
          $(".task_specifics").prop("required",false);
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

        $(".accept_recurring").prop("checked",true);
        $(".accept_recurring_div").show();
        $("#recurring_checkbox1").prop("checked",true);

        $("#open_task").prop("checked",false);
        $(".allocate_user_add").removeClass("disable_user");

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
    var comments = $("#new_comment").val();
    var task_id = $("#hidden_task_id_task_specifics").val();
    $.ajax({
      url:"<?php echo URL::to('user/add_comment_specifics'); ?>",
      type:"post",
      data:{task_id:task_id,comments:comments},
      success:function(result)
      {
        $("#existing_comments").append('<strong style="width:100%;float:left;text-align:justify">'+result+'</strong>');
        $("#new_comment").val("");
      }
    })
  }
  if($(e.target).hasClass('link_to_task_specifics'))
  {
    var task_id = $(e.target).attr("data-element");
    $(".task_specifics_modal").modal("show");
    $.ajax({
      url:"<?php echo URL::to('user/show_existing_comments'); ?>",
      type:"post",
      data:{task_id:task_id},
      success:function(result)
      {
        $("#hidden_task_id_task_specifics").val(task_id);
        $("#existing_comments").html(result);
      }
    })
  }
  if($(e.target).hasClass('edit_allocate_user'))
  {
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
    $(".allocation_modal").modal("show");

    $.ajax({
      url:"<?php echo URL::to('user/show_all_allocations'); ?>",
      type:"post",
      data:{task_id:task_id},
      success:function(result)
      {
        $("#hidden_task_id_history").val(task_id);
        $("#history_body").html(result);
      }
    })
  }
  if(e.target.id == "allocate_now")
  {
    var task_id = $("#hidden_task_id_allocation").val();
    var new_allocation = $(".new_allocation").val();
    if(new_allocation == "")
    {
      alert("Please choose the user to allocate the task.");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/taskmanager_change_allocations'); ?>",
        type:"post",
        data:{task_id:task_id,new_allocation:new_allocation},
        dataType:"json",
        success:function(result)
        {
          var htmlval = $("#allocation_history_div_"+task_id).html();
          $("#allocation_history_div_"+task_id).html(result['pval']+htmlval);
          var htmlval2 = $("#history_body").find("tbody").html();
          $("#history_body").find("tbody").html(result['trval']+htmlval2);
          $(".edit_allocate_user_"+task_id).attr("data-allocated",new_allocation);
          var count = 1;
          $("#allocation_history_div_"+task_id).find("p").each(function() {
            if(count > 5)
            {
              $(this).detach();
            }
            count++;
          })
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
          $(".due_date_modal").modal("hide");
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
  if(e.target.id == "create_new_task")
  {
    $(".create_new_model").find(".job_title").html("New Task Creator");
    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
    var user_id = $(".select_user_home").val();
    $(".select_user_author").val(user_id);
    $(".create_new_model").modal("show");
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

    $("#action_type").val("1");
    $(".allocate_user_add").val("");
    $(".client_search_class").val("");
    $("#client_search").val("");
    $(".task-choose_internal").html("Select Task");
    $(".subject_class").val("");
    $(".task_specifics_add").show();
    $(".task_specifics_copy").hide();
    $(".task_specifics").val("");
    $(".task_specifics").prop("required",true);
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

    $(".accept_recurring").prop("checked",true);
    $(".accept_recurring_div").show();
    $("#recurring_checkbox1").prop("checked",true);

    $("#open_task").prop("checked",false);
    $(".allocate_user_add").removeClass("disable_user");
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
        $("#add_notepad_attachments_progress_div_"+task_id).append("<p><a href='"+result['download_url']+"' download>"+result['filename']+"</a> <a href='"+result['delete_url']+"' class='fa fa-trash delete_attachments'></a></p>");
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
        $("#add_notepad_attachments_completion_div_"+task_id).append("<p><a href='"+result['download_url']+"' download>"+result['filename']+"</a> <a href='"+result['delete_url']+"' class='fa fa-trash delete_attachments'></a></p>");
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
  else if($(e.target).hasClass('fa-plus'))
  {
    var pos = $(e.target).position();
    var leftposi = parseInt(pos.left) - 200;
    $(e.target).parent().find('.img_div').css({"position":"absolute","top":pos.top,"left":leftposi}).toggle();
    $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
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
$(window).change(function(e) {
  if($(e.target).hasClass('select_user_home'))
  {
    var value = $(e.target).val();
    $.ajax({
      url:"<?php echo URL::to('user/change_taskmanager_user'); ?>",
      type:"post",
      data:{user:value},
      success: function(result)
      {
        window.location.reload();
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
            $(".accepted_files_main").hide();
            $(".accepted_files").html(0);
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id;
            $("#add_files_attachments_progress_div_"+obj.task_id).append("<p><a href='"+obj.download_url+"' download>"+obj.filename+"</a> <a href='"+obj.delete_url+"' class='fa fa-trash delete_attachments'></a></p>");
            $(".img_div").each(function() {
              $(this).hide();
            });
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
            $(".accepted_files_main").hide();
            $(".accepted_files").html(0);
        });
        this.on("drop", function(event) {
            $("body").addClass("loading");        
        });
        this.on("success", function(file, response) {
            var obj = jQuery.parseJSON(response);
            file.serverId = obj.id;
            $("#add_files_attachments_completion_div_"+obj.task_id).append("<p><a href='"+obj.download_url+"' download>"+obj.filename+"</a> <a href='"+obj.delete_url+"' class='fa fa-trash delete_attachments'></a></p>");
            $(".img_div").each(function() {
              $(this).hide();
            });
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
</script>
@stop
