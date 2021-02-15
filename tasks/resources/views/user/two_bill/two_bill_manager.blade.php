@extends('userheader')
@section('content')
<script src="<?php echo URL::to('assets/ckeditor/src/js/main1.js'); ?>"></script>
<script src='<?php echo URL::to('assets/js/table-fixed-header_cm.js'); ?>'></script>
<style>
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
  <div class="modal-dialog" role="document" style="width:80%;">
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

                      if($task->invoice == "") {
                          $billing_status = 'Unbilled';
                          $color = 'color:blue'; 
                          $class_billed = 'unbilled_tr';
                          $invoice_no = '<a href="javascript:" class="select_invoice invoice_'.$task->id.'" data-element="'.$task->id.'" data-client="'.$task->client_id.'">Not Specified</a>'; 
                      }
                      else{
                          $billing_status = 'Billed';
                          $color = 'color:green';
                          $class_billed = 'billed_tr';
                          $invoice_no = '<a href="javascript:" class="select_invoice invoice_'.$task->id.'" data-element="'.$task->id.'" data-client="'.$task->client_id.'">'.$task->invoice.'</a>';
                      }

                      $outputtask.='<tr id="tr_task_'.$task->id.'" class="tr_task '.$class_billed.'">
                        <td class="taskid_td">'.$task->taskid.'</td>
                        <td class="taskid_td task_name_val">'.$title.'</td>
                        <td class="task_subject_val">'.$task->subject.'</td>
                        <td class="pdf_val"><a href="javascript:" class="download_pdf_task" data-element="'.$task->id.'" title="Download PDF"><img src="'.URL::to('assets/images/pdf.png').'" style="width:20px" class="download_pdf_task" data-element="'.$task->id.'"></a></td>
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
  if($(e.target).hasClass('change_billing_status'))
  {
  	var taskid = $(e.target).attr("data-element");
  	if($(e.target).hasClass('fa-check'))
  	{
  		$.ajax({
  			url:"<?php echo URL::to('user/change_billing_status'); ?>",
  			type:"post",
  			data:{taskid:taskid,status:1},
  			success:function(result)
  			{
  				$(e.target).addClass('fa-times');
  				$(e.target).removeClass('fa-check');
  				$(e.target).parents("tr:first").removeClass("unbilled_tr");
  				$(e.target).parents("tr:first").addClass("billed_tr");
  				$(e.target).parents("tr:first").find(".billing_status_val").html("Billed");
  				$(e.target).parents("tr:first").find(".billing_status_val").css("color","green");

  				var value = $("#hidden_billed_items").val();
			    if(value == "1")
			    {
			      $(".billed_tr").show();
			    }
			    else{
			      $(".billed_tr").hide();
			    }
  			}
  		});
  	}
  	else{
  		$.ajax({
  			url:"<?php echo URL::to('user/change_billing_status'); ?>",
  			type:"post",
  			data:{taskid:taskid,status:0},
  			success:function(result)
  			{
  				$(e.target).addClass('fa-check');
  				$(e.target).removeClass('fa-times');
  				$(e.target).parents("tr:first").addClass("unbilled_tr");
  				$(e.target).parents("tr:first").removeClass("billed_tr");
  				$(e.target).parents("tr:first").find(".billing_status_val").html("Unbilled");
  				$(e.target).parents("tr:first").find(".billing_status_val").css("color","blue");

  				var value = $("#hidden_billed_items").val();
			    if(value == "1")
			    {
			      $(".billed_tr").show();
			    }
			    else{
			      $(".billed_tr").hide();
			    }
  			}
  		});
  	}
  }
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
        $("#tr_task_"+taskid).removeClass("unbilled_tr");
        $("#tr_task_"+taskid).addClass("billed_tr");
        $("#tr_task_"+taskid).find(".billing_status_val").html("Billed");
        $("#tr_task_"+taskid).find(".billing_status_val").css("color","green");

        var value = $("#hidden_billed_items").val();
        if(value == "1")
        {
          $(".billed_tr").show();
        }
        else{
          $(".billed_tr").hide();
        }

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
})

</script>
@stop
