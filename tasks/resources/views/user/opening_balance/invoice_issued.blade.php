@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<link rel="stylesheet" href="<?php echo URL::to('assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>
body{
  background: #f5f5f5 !important;
}
#colorbox{
      z-index: 99999999999 !important;
}
.invalid_tr td{
  color:#f00;
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
.table thead th:focus{background: #ddd !important;}
.form-control{border-radius: 0px;}
.error{color: #f00; font-size: 12px;}
a:hover{text-decoration: underline;}
</style>
<?php
        $user = DB::table('user_login')->first();
        $financial_date = $user->opening_balance_date;
        ?>
<div class="modal fade import_outstanding_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog" role="document" style="width:40%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Import Outstanding Invoice File Manager</h4>
          </div>
          <div class="modal-body">  
            <div class="row">
              <div class="col-md-12" style="border-right:1px solid #dfdfdf">
                <h4>Browse File:</h4>
                <div class="img_div_progress">
                   <div class="image_div_attachments_progress">
                      <form action="<?php echo URL::to('user/invoice_outstanding_upload_csv'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload1" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left;">
                      </form>
                   </div>
                </div>
                <input type="hidden" name="hidden_import_file_name" id="hidden_import_file_name" value="">
                <p><input type="button" name="load_invoice_issued" class="common_black_button load_invoice_issued" value="Load" style="width:100%"></p>
                <p style="color:#f00">Note: Import a CSV file that contains the Balances for each invoice which was not fully paid at the opening balance date of <?php echo date('d F Y', strtotime($financial_date)); ?></p>
                <div class="table-responsive" style="min-height:200px;max-height: 400px; overflow-y: scroll;">
                  <table class="table">
                    <thead>
                      <th>Invoice No</th>
                      <th>Balance</th>
                      <th>Valid</th>
                      <th>Note</th>
                    </thead>
                    <tbody id="import_file_tbody">
                    </tbody>
                  </table>
                </div>
                <p><input type="button" name="submit_invoice_issued" class="common_black_button submit_invoice_issued" id="submit_invoice_issued" value="Submit" style="width:100%;display:none"></p>
              </div>
            </div>
            
          </div>
        </div>
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
<div class="modal fade invoice_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-keyboard="false" data-backdrop="static" style="z-index: 9999999999999999999999999999">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Invoices</h4>
        </div>
        <div class="modal-body" style="height: 600px; font-size: 14px; overflow-y: scroll;">
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
          <div id="letterpad_modal" style="width: 100%;height:1235px; float: left; background:url('<?php echo URL::to('assets/invoice_letterpad.jpg');?>') no-repeat">
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
        <div class="modal-footer">
            <input type="button" class="common_black_button saveas_pdf" value="Save as PDF">
            <input type="button" class="common_black_button print_pdf" value="Print">
        </div>
      </div>
  </div>
</div>
<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
      <h4 class="col-lg-12 padding_00 new_main_title">
        
        Client Opening Balance Manager (Balances at <?php echo date('d F Y', strtotime($financial_date)); ?>)
      </h4>
      <div class="col-md-2 padding_00">
        
        <!-- <input type="checkbox" name="hide_active_clients" class="hide_active_clients" id="hide_active_clients" value="1"><label for="hide_active_clients">Hide Inactive Clients</label> -->
      </div>
      <!-- <div class="col-lg-10 padding_00">
        <div class="select_button">
          <ul>
            <li><a href="<?php echo URL::to('user/import_opening_balance_manager'); ?>" class="common_black_button" style="float:right">Import Opening Balance Manager</a></li>
          </ul>
        </div>
      </div> -->
      <div style="clear: both;">
        <?php
        if(Session::has('message')) { ?>
            <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
         <?php }   
        ?>
      </div> 
      <div class="col-lg-12 padding_00" style="margin-top: 19px;">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item waves-effect waves-light" style="width:20%;text-align: center">
            <a href="<?php echo URL::to('user/opening_balance_manager'); ?>" class="nav-link" id="profile-tab">Client Opening Balances</a>
            
          </li>
          <li class="nav-item waves-effect waves-light active" style="width:20%;text-align: center">
            <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="false">
              <spam id="open_task_count">Invoices Issued</spam>
            </a>
          </li>
        </ul>
        <table class="display nowrap fullviewtablelist own_table_white" id="client_expand" width="100%">
          <thead>
            <tr style="background: #fff;">
                <th style="text-align: left;">Invoice</th>
                <th style="text-align: left;">Client ID</th>
                <th style="text-align: left;width:20%">Client</th>
                <th style="text-align: left;">Invoice Date</th>
                <th style="text-align: left;">Gross</th>
                <th style="text-align: left;width:20%">Balance Outstanding <a href="javascript:" class="common_black_button import_invoice_issued" id="import_invoice_issued" style="position: absolute;right: 15px;top: 0px;">Import</a></th>
            </tr>
          </thead>                            
          <tbody id="clients_tbody">
            <?php
            if(count($invoice_issued))
            {
              foreach($invoice_issued as $invoice){
                $client_details = DB::table('cm_clients')->where('client_id',$invoice->client_id)->first();
                $clientname = '';
                if(count($client_details)){
                  $clientname =  ($client_details->company == "")?$client_details->firstname.' & '.$client_details->surname:$client_details->company;
                }
                
                echo '<tr>
                  <td><a href="javascript:" class="invoice_inside_class invoice_sort_val" data-element="'.$invoice->invoice_number.'">'.$invoice->invoice_number.'</a></td>
                  <td>'.$invoice->client_id.'</td>
                  <td>'.$clientname.'</td>
                  <td>'.date('d-M-Y', strtotime($invoice->invoice_date)).'</td>
                  <td>'.number_format_invoice($invoice->gross).'</td>
                  <td><input type="text" name="outstanding_invoice" class="form-control outstanding_invoice" value="'.$invoice->outstanding_balance.'" data-element="'.$invoice->invoice_number.'" style="width:50%"></td>
                </tr>';
              }
            }
            ?>
          </tbody>
        </table>
      </div>
  </div>
  <div class="modal_load"></div>
  <input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
  <input type="hidden" name="show_alert" id="show_alert" value="">
  <input type="hidden" name="pagination" id="pagination" value="1">
</div>
<script type="text/javascript">
$(function(){
    $('#client_expand').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false,
        columnDefs: [{
            targets: [4], /* column index */
            orderable: false, /* true or false */
        }]
    });
}); 
$(window).click(function(e) {
  if($(e.target).hasClass('invoice_inside_class')){
      var editid = $(e.target).attr("data-element");
      $.ajax({
            url: "<?php echo URL::to('user/invoices_print_view') ?>",
            data:{id:editid},
            dataType:'json',
            type:"post",
            success:function(result){      
               $(".invoice_modal").modal("show");
               $("body").removeClass("loading");  
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
  if($(e.target).hasClass('load_invoice_issued'))
  {
    var filename = $("#hidden_import_file_name").val();
    if(filename == ""){
      alert("Please choose or Drag and Drop the CSV File to import");
    }
    else{
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/check_invoice_issued_csv_file'); ?>",
        type:"post",
        data:{filename:filename},
        dataType:"json",
        success:function(result){
          if(result['error'] == "1"){
            $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">'+result['message']+'</p>',fixed:true,width:"800px"});

            $("#import_file_tbody").html('<tr><td colspan="4">No Records Found</td></tr>');
            $("#submit_invoice_issued").hide();
            $("body").removeClass("loading");
          }
          else{
            $("#import_file_tbody").html(result['output']);
            $("#submit_invoice_issued").show();
            $("body").removeClass("loading");
          }

        }
      })
    }
  }
  if(e.target.id == "submit_invoice_issued"){
    var filename = $("#hidden_import_file_name").val();
    if(filename == ""){
      alert("Please choose or Drag and Drop the CSV File to import");
    }
    else{
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/upload_invoice_issued_csv_file'); ?>",
        type:"post",
        data:{filename:filename},
        success:function(result){
          window.location.reload();
        }
      })
    }
  }
  if($(e.target).hasClass('import_invoice_issued'))
  {
    $("body").addClass("loading");
    $(".import_outstanding_modal").modal("show");
    $("#import_file_tbody").html('<tr><td colspan="4">No Records Found</td></tr>');
    $("#submit_invoice_issued").hide();
    $("#hidden_import_file_name").val("");
    Dropzone.forElement("#imageUpload1").removeAllFiles(true);
    $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");
    $("body").removeClass("loading");
  }
})
$(".outstanding_invoice").blur(function() {
  var invoice = $(this).attr("data-element");
  var value = $(this).val();
  $.ajax({
    url:"<?php echo URL::to('user/update_outstanding_invoice'); ?>",
    type:"post",
    data:{invoice:invoice,value:value},
    success:function(result){

    }
  })
});
var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input = $('.outstanding_invoice');

$input.on('keyup', function () {
  var value = $(this).val();
  var invoice = $(this).attr('data-element');
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval,value,invoice);
});

$input.on('keydown', function () {
  clearTimeout(typingTimer);
});

function doneTyping (value,invoice) {
    $.ajax({
      url:"<?php echo URL::to('user/update_outstanding_invoice'); ?>",
      type:"post",
      data:{invoice:invoice,value:value},
      success:function(result){

      }
    })
}

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