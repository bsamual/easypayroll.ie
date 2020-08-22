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
      <h4 style="padding: 0px;font-weight:700;text-align: center">Client Opening Balance Manager</h4>
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
      <div class="col-md-12">
        <div class="col-md-5">
          <h4 style="font-weight:700">Client ID:</h4>
        </div>
        <div class="col-md-7">
          <h4><?php echo $client_details->client_id; ?></h4>
        </div>
      </div>
      <div class="col-md-12">
        <div class="col-md-5">
          <h4 style="font-weight:700">Company:</h4>
        </div>
        <div class="col-md-7">
          <h4><?php echo $client_details->company; ?></h4>
        </div>
      </div>
      <div class="col-md-12">
        <div class="col-md-5">
          <h4 style="font-weight:700">Name:</h4>
        </div>
        <div class="col-md-7">
          <h4><?php echo $client_details->firstname.' & '.$client_details->surname; ?></h4>
        </div>
      </div>
      <div class="col-md-12">
        <div class="col-md-5">
          <h4 style="font-weight:700">Primary Email:</h4>
        </div>
        <div class="col-md-7">
          <h4><?php echo $client_details->email; ?></h4>
        </div>
      </div>
      <div class="col-md-12">
        <div class="col-md-5">
          <h4 style="font-weight:700">Secondary Email:</h4>
        </div>
        <div class="col-md-7">
          <h4><?php echo ($client_details->email2 == "")?"-":$client_details->email2; ?></h4>
        </div>
      </div>
      <?php
      $opening_balance = DB::table('opening_balance')->where('client_id',$client_id)->first();
      if(count($opening_balance))
      {
        $balance = $opening_balance->opening_balance;
        if($opening_balance->opening_date == "0000-00-00")
        {
          $date = "";
        }
        else{
          $date = date('d-M-Y', strtotime($opening_balance->opening_date));
        }
      }
      else{
        $balance = "";
        $date = "";
      }

      ?>
      <div class="col-md-12" style="margin-top:40px">
        <div class="col-md-5">
          <h4 style="font-weight:700">Opening Balance:</h4>
        </div>
        <div class="col-md-7">
          <input type="number" name="opening_balance" class="form-control opening_balance" value="<?php echo $balance; ?>">
        </div>
      </div>
      <div class="col-md-12">
        <div class="col-md-5">
          &nbsp;
        </div>
        <div class="col-md-7">
          <h4 style="line-height: 27px;">Note: Negative Balance on the opening Balance shows clients who have an overpaid account.</h4>
        </div>
      </div>
      <div class="col-md-12" style="margin-top:10px">
        <div class="col-md-5">
          <h4 style="font-weight:700">Opening Balance Date:</h4>
        </div>
        <div class="col-md-7">
          <input type="text" name="opening_balance_date" class="form-control opening_balance_date" value="<?php echo $date; ?>">
        </div>
      </div>
    </div>
    <div class="col-md-5">
      <table class="table" style="border:2px solid #ddd">
        <thead>
          <tr>
            <th colspan="4">Balance Break Down</th>
          </tr>
          <tr>
            <th style="text-align: left">Invoice No</th>
            <th style="text-align: left">Invoice Date</th>
            <th style="text-align: right">Invoice Gross</th>
            <th style="text-align: right">Balance Remaining</th>
          </tr>
        </thead>
        <tbody id="invoice_tbody_list">
          <?php
          $get_invoices = DB::table('invoice_system')->where('client_id',$client_id)->orderBy('invoice_date','desc')->get();
          $total_remaining = 0;
          if(count($get_invoices))
          {
            foreach($get_invoices as $invoice)
            {
              if($invoice->balance_remaining != "") { $balance_remaining = $invoice->balance_remaining; } else { $balance_remaining = '-'; }
              ?>
              <tr>
                <td><?php echo $invoice->invoice_number; ?></td>
                <td><?php echo date("d-M-Y", strtotime($invoice->invoice_date)); ?></td>
                <td style="text-align: right"><?php echo $invoice->gross; ?></td>
                <td style="text-align: right"><?php echo $balance_remaining; ?></td>
              </tr>
              <?php
              $total_remaining = $total_remaining + $balance_remaining;
            }
          }
          else{
            echo '<tr><td colspan="4">No Invoice Found</td></tr>';
          }
          $unallocated = $balance - $total_remaining;
          ?>

          <tr>
            <td colspan="3" style="font-weight:700">Total Balance Remaining</td>
            <td style="background: #ddd;text-align: right"><?php echo $total_remaining; ?></td>
          </tr>
          <tr>
            <td colspan="3" style="font-weight:700">Unallocated Balance</td>
            <td style="background: #ddd;text-align: right"><?php echo $unallocated; ?></td>
          </tr>
        </tbody>
      </table>
      <input type="button" class="common_black_button auto_allocate" id="auto_allocate" value="Auto Allocate" style="width:100%">
      <input type="button" class="common_black_button lock_opening" id="lock_opening" value="Lock Opening Balance" style="width:40%; margin-top:20px">
    </div>
    <div class="col-md-1">&nbsp;</div>
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
        info: false
    });
    $(".opening_balance_date").datetimepicker({     
       format: 'L',
       format: 'DD-MMM-YYYY',
    });
});

var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input = $('.opening_balance');
//on keyup, start the countdown
$input.on('keyup', function () {
  var input_val = $(this).val();
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_balance, doneTypingInterval,input_val);
});
//on keydown, clear the countdown 
$input.on('keydown', function () {
  clearTimeout(typingTimer);
  var input_val = $(this).val();
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_balance, doneTypingInterval,input_val);
});
//user is "finished typing," do something
function doneTyping_balance (input) {
  $.ajax({
        url:"<?php echo URL::to('user/change_opening_balance'); ?>",
        type:"post",
        data:{client_id:"<?php echo $client_id; ?>",balance:input},
        success: function(result) {

        }
      });
}

$(".opening_balance_date").on("dp.hide", function (e) {
    var input = $(".opening_balance_date").val();
    $.ajax({
        url:"<?php echo URL::to('user/change_opening_balance_date'); ?>",
        type:"post",
        data:{client_id:"<?php echo $client_id; ?>",dateval:input},
        success: function(result) {

        }
      });
});

$(window).click(function(e){
  if($(e.target).hasClass('auto_allocate'))
  {
    $("body").addClass("loading");
    var opening_balance = $(".opening_balance").val();
    var opening_balance_date = $(".opening_balance_date").val();
    if(opening_balance_date == "")
    {
      alert("Please Enter the Opening Balance Date and then click on to auto allocation button.");
    }
    else{
      var client_id = "<?php echo $client_id; ?>";
      $.ajax({
        url:"<?php echo URL::to('user/auto_allocate_opening_balance'); ?>",
        type:"post",
        data:{client_id:client_id,opening_balance:opening_balance},
        success: function(result)
        {
          $("#invoice_tbody_list").html(result);
          $("body").removeClass("loading");
        }
      });
    }
  }
});
</script>
@stop