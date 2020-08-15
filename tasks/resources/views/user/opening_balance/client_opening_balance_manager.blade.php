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

      <div class="col-md-12" style="margin-top:40px">
        <div class="col-md-5">
          <h4 style="font-weight:700">Opening Balance:</h4>
        </div>
        <div class="col-md-7">
          <input type="text" name="opeing_balance" class="form-control opening_balance" value="">
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
          <input type="text" name="opeing_balance_date" class="form-control opeing_balance_date" value="">
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
        <tbody>
          <?php
          $get_invoices = DB::table('invoice_system')->where('client_id',$client_id)->get();
          if(count($get_invoices))
          {
            foreach($get_invoices as $invoice)
            {
              ?>
              <tr>
                <td><?php echo $invoice->invoice_number; ?></td>
                <td><?php echo date("d-M-Y", strtotime($invoice->invoice_date)); ?></td>
                <td style="text-align: right"><?php echo $invoice->gross; ?></td>
                <td style="text-align: right"><?php echo '-'; ?></td>
              </tr>
              <?php
            }
          }
          else{
            echo '<tr><td colspan="4">No Invoice Found</td></tr>';
          }
          ?>

          <tr>
            <td colspan="3" style="font-weight:700">Total Balance Remaining</td>
            <td style="background: #ddd;text-align: right"></td>
          </tr>
          <tr>
            <td colspan="3" style="font-weight:700">Unallocated Balance</td>
            <td style="background: #ddd;text-align: right"></td>
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
});
</script>
@stop