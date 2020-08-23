@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>

<link rel="stylesheet" href="<?php echo URL::to('assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>
/* Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

/* Firefox */
input[type=number] {
  -moz-appearance: textfield;
}
body{
  background: #f5f5f5 !important;
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

<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
      <h4 style="padding: 0px;font-weight:700;text-align: center">Opening Balance Manager - Import Balances</h4>
      <div style="clear: both;">
        <?php
        if(Session::has('message')) { ?>
            <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
         <?php }   
        ?>
      </div>
  </div>
  <div class="row">
    <div class="col-md-1">&nbsp;</div>
    <div class="col-md-5">
      <form id="import_balance_form" action="<?php echo URL::to('user/import_opening_balance'); ?>" method="post" enctype="multipart/form-data">
        <div class="col-md-12">
          <div class="col-md-12">
            <input type="radio" name="import_balance" class="import_balance" id="import_balance_1" value="1" checked><label for="import_balance_1" style="font-size:18px;font-weight:700">Import Balances Only</label>
          </div>
          <div class="col-md-12" style="margin-top:10px">
            <input type="radio" name="import_balance" class="import_balance" id="import_balance_2" value="2"><label for="import_balance_2" style="font-size:18px;font-weight:700">Import Balances from Outstanding Invoices</label>
          </div>
        </div>
        <div class="col-md-12">
          <div class="col-md-12">
            <h4 style="line-height: 27px;" id="import_note">Import Balances Only – Select a CSV file that has Client ID and Balance.  Balances will be imported for each client and will not be locked on import</h4>
          </div>
        </div>
        <div class="col-md-12" style="margin-top:10px">
          <div class="col-md-5">
            <h4 style="font-weight:700">Import File:</h4>
          </div>
          <div class="col-md-7">
            <input type="file" name="balance_file" class="form-control balance_file" value="">
          </div>
        </div>
        <div class="col-md-12" style="margin-top:10px">
          <div class="col-md-12">
            <input type="button" class="common_black_button activate_file" id="activate_file" value="Activate File" style="width:100%">
          </div>
        </div>
      </form>
    </div>
    <div class="col-md-5">
      <div class="col-md-12 import_table" style="margin-top:10px;display:none">
        <div class="col-md-5">
          <h4 style="font-weight:700">Opening Balance Date:</h4>
        </div>
        <div class="col-md-7">
          <input type="text" name="opening_balance_date" class="form-control opening_balance_date" value="">
        </div>
      </div>
      <div class="col-md-12" style="margin-bottom: 20px">
        <input type="button" class="common_black_button start_import" id="activate_file" value="Start Import" style="width:100%;display:none">
      </div>
      <table class="table import_table" style="border:2px solid #ddd;display:none">
        <thead>
          <tr>
            <th colspan="6">File Content</th>
          </tr>
          <tr>
            <th style="text-align: left">Client ID</th>
            <th style="text-align: left">Inv No</th>
            <th style="text-align: right">Balance</th>
            <th style="text-align: left">ID Check</th>
            <th style="text-align: left">Value Check</th>
            <th style="text-align: left">Invoice No Check</th>
          </tr>
        </thead>
        <tbody id="import_tbody_list">
          
        </tbody>
      </table>
    </div>
    <div class="col-md-1">&nbsp;</div>
  </div>
  <div class="modal_load"></div>
  <input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
  <input type="hidden" name="show_alert" id="show_alert" value="">
  <input type="hidden" name="pagination" id="pagination" value="1">
  <input type="hidden" name="hidden_filename" id="hidden_filename" value="">
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
        info: false
    });
    $(".opening_balance_date").datetimepicker({     
       format: 'L',
       format: 'DD-MMM-YYYY',
    });
});


$(window).click(function(e){
  if(e.target.id == "import_balance_1")
  {
    $("#import_note").html("Import Balances Only – Select a CSV file that has Client ID and Balance.  Balances will be imported for each client and will not be locked on import");
  }
  if(e.target.id == "import_balance_2")
  {
    $("#import_note").html("Import Balances From Outstanding Invoices – Select a CSV file that has an invoice number and an unpaid value for that invoice");
  }
  if($(e.target).hasClass('activate_file'))
  {
    var file = $(".balance_file").val();
    if(file == "")
    {
      if($("#import_balance_1").is(":checked"))
      {
        alert("Select a CSV file that has Client ID and Balance.  Balances will be imported for each client and will not be locked on import");
      }
      else{
        alert("Please Select a CSV file that has Invoice ID and Balance.  Balances will be imported for each invoices and will not be locked on import");
      }
    }
    else{
      $("body").addClass("loading");
      setTimeout(function(){ 
          $('#import_balance_form').ajaxForm({
              dataType:"json",
              success:function(e){
                $(".opening_balance_date").val("");
                if(e['error'] == 0)
                {
                  $(".import_table").show();
                  $(".start_import").show();
                  $("#import_tbody_list").html(e['output']);
                  $("#hidden_filename").val(e['upload_dir']);
                  $("body").removeClass("loading");
                }
                else{
                  $(".import_table").hide();
                  $(".start_import").hide();
                  $("body").removeClass("loading");
                  $.colorbox({html:"<p style=text-align:center;font-size:18px;font-weight:600;color:green>"+e['message']+"</p>",width:"30%",fixed:true});
                }
              }
          }).submit();
      }, 2000);
    }
  }
  if($(e.target).hasClass('start_import'))
  {
    var errors = $(".error_import").length;
    var bal_date = $(".opening_balance_date").val();
    if(errors > 0)
    {
      alert("Itseems some of the Data failed in the csv file you are trying to import. Please check the failed data then replace the csv file and start importing.");
    }
    else if(bal_date == "")
    {
      alert("Please Enter the Opening Balance Date and then click on to Start Import button.")
    }
    else{
      var import_type = $(".import_balance:checked").val();
      var filename = $("#hidden_filename").val();
      $.ajax({
        url:"<?php echo URL::to('user/import_opening_balance_to_clients'); ?>",
        type:"post",
        data:{filename:filename,bal_date:bal_date,import_type:import_type},
        success: function(result)
        {
          window.location.replace("<?php echo URL::to('user/opening_balance_manager?imported=1'); ?>");
        }
      });
    }
  }
});
</script>
@stop