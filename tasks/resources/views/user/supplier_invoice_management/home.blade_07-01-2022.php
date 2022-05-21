@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>

<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo URL::to('assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>
  .invoice_year_div{
    position: absolute;
    top: 93%;
    left:20%;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
    text-align: left;
    width: 250px;
}
.invoice_custom_div{
    position: absolute;
    top: 93%;
    left:266px;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
    text-align: left;
}
.all_clients, .invoice_date_option{margin-top: 12px !important;}
  .disabled_ard{
    background: #dfdfdf !important;
    pointer-events: none; 
  }
  body{
    background: #f5f5f5 !important;
  }
  .form-control[readonly]{
    background-color: #fff !important
  }
  .ard_class[readonly]{
    background-color: #dfdfdf !important
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
  .pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover
  {
    z-index: 0 !important;
  }
  .ui-tooltip{
    margin-top:-50px !important;
  }
  .label_class{
    float:left ;
    margin-top:15px;
    font-weight:700;
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
  .field_check
  {
    width:24%;
  }
  .import_div{
    position: absolute;
    top: 55%;
    left:30%;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
  }
  .selectall_div{
    position: absolute;
    top: 13%;
    left: 5%;
    border: 1px solid #000;
    padding: 12px;
    background: #ff0;
  }
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
  .table thead th:focus{background: #ddd !important;}
  .form-control{border-radius: 0px;}
  .disabled{cursor :auto !important;pointer-events: auto !important}
  body #coupon {
    display: none;
  }
  .error{color: #f00; font-size: 12px;}
  a:hover{text-decoration: underline;}
</style>


<div class="modal fade vat_rate_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 99999999999;">
  <div class="modal-dialog modal-lg" role="document" style="width:30%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Settings</h4>
      </div>
      <div class="modal-body" style="clear:both">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item active">
            <a class="nav-link active" id="first-tab" data-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="true">Vat Rate</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Rounding</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Journals</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent" style="margin-top: 20px;">
          <div class="tab-pane fade active in" id="first" role="tabpanel" aria-labelledby="first-tab">
            <label>Enter VAT Rate: </label>
            <input type="text" name="vat_rate_input" class="form-control vat_rate_input" value="" placeholder="Enter VAT Rate" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
            <input type="button" name="submit_vat_rate" class="common_black_button submit_vat_rate" value="Submit" style="margin-top:10px">

            <table class="table" style="margin-top:20px">
              <thead>
                <tr>
                  <th>S.No</th>
                  <th>VAT Rate</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="vat_rate_tbody">
                  <?php
                  $vat_rates = DB::table('supplier_vat_rates')->get();
                  $i =1;
                  if(count($vat_rates))
                  {
                    foreach($vat_rates as $rate)
                    {
                      ?>
                      <tr class="vat_tr">
                        <td><?php echo $i; ?></td>
                        <td><?php echo $rate->vat_rate; ?> %</td>
                        <td>
                          <?php
                          if($rate->status == 1){
                            echo '<a href="javascript:" class="fa fa-times change_vat_status" data-element="'.$rate->id.'" data-status="0" title="Enable Rate" style="color:#f00"></a>';
                          }
                          else {
                            echo '<a href="javascript:" class="fa fa-check change_vat_status" data-element="'.$rate->id.'" data-status="1" title="Disable Rate" style="color:green"></a>';
                          }
                          ?>
                        </td>
                      </tr>
                      <?php
                      $i++;
                    }
                  }
                  ?>
              </tbody>
            </table>
          </div>
          <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
            <label>Allowable Rounding: </label>
            <select name="allowable_rounding" class="form-control allowable_rounding" id="allowable_rounding">
              <option value="">Select Value</option>
              <?php
              for($i=0.00; $i<=1.01;){
                ?>
                <option value="<?php echo number_format_invoice($i); ?>"><?php echo number_format_invoice($i); ?></option>
                <?php
                $i = $i + 0.01;
              }
              ?>
            </select>
            <input type="button" name="submit_rounding" class="common_black_button submit_rounding" value="Submit" style="margin-top:10px">
            <p style="margin-top:10px">Note: The Rounding value is the allowable variance between a Supplier invoice header and the total of the Supplier invoice detail</p>
          </div>
          <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <input type="button" name="repost_journals" class="common_black_button repost_journals" value="Repost all Purchase Invoice Journals" style="margin-top:10px">
          </div>
        </div>
      </div>
      <div class="modal-footer" style="clear:both">

      </div>
    </div>
  </div>
</div>
<div class="modal fade report_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 99999999999;">
  <div class="modal-dialog modal-md" role="document" style="width: 60%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Purchase Ledger Reports</h4>
      </div>
      <div class="modal-body" style="clear:both">
        <div class="row">
          <div class="col-lg-9">
            <div class="row">
              <div class="col-lg-4">
                <input type="radio" value="1" id="report_purchas" class="report_radio" name="report_radio">
                <label for="report_purchas">Purchase Summary Report</label>
              </div>
              <div class="col-lg-4">
                <input type="radio" value="2" id="report_details" class="report_radio" name="report_radio">
                <label for="report_details">Detailed Purchase Analysis</label>
              </div>
              <div class="col-lg-4">
                <input type="radio" value="3" id="report_ledger" class="report_radio" name="report_radio">
                <label for="report_ledger">Purchase Posting Summary Report</label>
              </div>
              <input type="hidden" class="report_type_input" name="" readonly>
              <label class="error error_report_type" style="margin-left:15px"></label>
            </div>
            <div class="row" style="margin-top: 5px;">
              <div class="col-lg-6">
                <input type="text" name="from_custom_date" class="form-control report_from_date" value="" placeholder="Choose From Date">
                <label class="error error_from_date"></label>
              </div>
              <div class="col-lg-6">
                <input type="text" name="from_custom_date" class="form-control report_to_date" value="" placeholder="Choose To Date">
                <label class="error error_to_date"></label>
              </div>
            </div>
            

          </div>
          <div class="col-lg-3">
              <a href="javascript:" class="common_black_button run_report_invoice" style="width: 100%; font-size: 17px !important; float: left; padding: 17px;">Run<br/>Report</a>
          </div>
        </div>
        
      </div>
      <div class="modal-footer" style="clear:both">

      </div>
    </div>
  </div>
</div>

<div class="content_section" style="margin-bottom:200px">
  <div class="page_title" style="z-index:999;">
    <h4 class="col-lg-12 padding_00 new_main_title">
      Supplier Invoice Management System             
    </h4>
  </div>
  <ul class="nav nav-tabs" id="myTab" role="tablist">
    <li class="nav-item active">
      <a class="nav-link active" id="first-tab" data-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="true">Supplier Invoice Management</a>
    </li>
    <li class="nav-item">
      <a href="<?php echo URL::to('user/purchase_invoice_to_process'); ?>" class="nav-link">Purchase Invoices to Process</a>
    </li>
  </ul>
  <div style="clear: both;">
    <?php
    if(Session::has('message')) { ?>
      <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
    <?php }
    if(Session::has('error-message')) { ?>
      <p class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('error-message'); ?></p>
    <?php } ?>
  </div>
  <div class="col-lg-12"  style="padding: 0px;">
    <div class="col-lg-4">
      <spam>Invoice List: </spam>
      <input type="radio" name="invoice_date_option" class="invoice_date_option" id="invoice_date_option_1" value="1"><label for="invoice_date_option_1">Year</label>&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="radio" name="invoice_date_option" class="invoice_date_option" id="invoice_date_option_2" value="2" checked><label for="invoice_date_option_2">All Invoice</label>&nbsp;&nbsp;&nbsp;&nbsp;
      <input type="radio" name="invoice_date_option" class="invoice_date_option" id="invoice_date_option_3" value="3"><label for="invoice_date_option_3">Custom Date</label>&nbsp;&nbsp;&nbsp;&nbsp;

      <div class="invoice_year_div" style="display: none">
        <h5 class="col-md-12">Select Year:</h5>
        <div class="col-md-12">
          <select name="invoice_select_year" class="invoice_select_year form-control">
            <?php
            $invoice_year = DB::select('SELECT *,SUBSTR(`invoice_date`, 1, 4) as `invoice_year` from `invoice_system` GROUP BY SUBSTR(`invoice_date`, 1, 4) ORDER BY SUBSTR(`invoice_date`, 1, 4) ASC');
            $output_year = '<option value="">Select Year</option>';
            if(count($invoice_year))
            {
              foreach($invoice_year as $year)
              {
                $output_year.='<option value="'.$year->invoice_year.'">'.$year->invoice_year.'</option>';
              }
            }
            echo $output_year;
            ?>
          </select>
        </div>
        <div class="col-md-12" style="margin-top:10px">
          <input type="button" name="load_invoice_year" class="common_black_button load_all_cm_invoice" value="Load Invoice">
        </div>
      </div>

      <div class="invoice_custom_div" style="display: none">
        <h5 class="col-md-12">From:</h5>
        <div class="col-md-12">
          <input type="text" name="from_invoice" class="form-control from_invoice" value="">
        </div>
        <h5 class="col-md-12">To:</h5>
        <div class="col-md-12">
          <input type="text" name="to_invoice" class="form-control to_invoice" value="">
        </div>
        <div class="col-md-12" style="margin-top:10px">
          <input type="button" name="load_invoice_year" class="common_black_button load_all_cm_invoice" value="Load Invoice">
        </div>
      </div>
    </div>
    <div class="col-lg-8 text-right">
      <!-- export_purchase_invoice -->
      <a href="javascript:" class="common_black_button report_button">Report</a>
      <!-- <a href="javascript:" class="common_black_button export_purchase_invoice">Export</a> -->
      <a href="javascript:" class="common_black_button add_purchase_invoice">Add Purchase Invoice</a>
      <a href="javascript:" class="common_black_button fa fa-cogs settings_btn"></a>
    </div>
  </div>
  <div class="col-lg-12">
    <style>
      #client_expand {
        text-align: left;
        position: relative;
        border-collapse: collapse; 
      }
      #client_expand thead tr th {
        background: white;
        position: sticky;
        top: 84; /* Don't forget this, required for the stickiness */
        box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
      }
      </style>
    <table class="table own_white_table" id="client_expand"  width="100%">
      <thead>
        <tr>
          <th>S.No</th>
          <th style="max-width: 82px !important;">Supplier Code</th>
          <th>Supplier Name</th>
          <th>Date</th>
          <th>Invoice No</th>
          <th>Reference</th>
          <th style="text-align: right">Net</th>
          <th style="text-align: right">VAT</th>
          <th style="text-align: right">Gross</th>
          <th style="max-width: 53px;">Journal ID</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody id="invoice_tbody">
        <?php
        $i = 1;
        if(count($global_invoices)) {
          foreach($global_invoices as $global) {
            $supplier_details = DB::table('suppliers')->where('id',$global->supplier_id)->first();
            $supplier_code = '';
            $supplier_name = '';
            $balance = 0.00;
            if(count($supplier_details))
            {
              $supplier_code = $supplier_details->supplier_code;
              $supplier_name = $supplier_details->supplier_name;
              $balance = $supplier_details->opening_balance;
            }
            ?>
            <tr>
              <td><?php echo $global->id; ?></td>
              <td><?php echo $supplier_code; ?></td>
              <td><?php echo $supplier_name; ?></td>
              <td><?php echo date('d-M-Y', strtotime($global->invoice_date)); ?></td>
              <td><?php echo $global->invoice_no; ?></td>
              <td><?php echo $global->reference; ?></td>
              <td style="text-align: right"><?php echo number_format_invoice($global->net); ?></td>
              <td style="text-align: right"><?php echo number_format_invoice($global->vat); ?></td>
              <td style="text-align: right"><?php echo number_format_invoice($global->gross); ?></td>
              <td><a href="javascript:" class="journal_id_viewer" data-element="<?php echo $global->journal_id; ?>"><?php echo $global->journal_id; ?></a></td>
              <td>
                <a href="javascript:" class="fa fa-eye view_purchase_invoice" data-element="<?php echo $global->id; ?>" title="View Purchase Invoice"></a>&nbsp;&nbsp;
                <a href="javascript:" class="fa fa-edit edit_purchase_invoice" data-element="<?php echo $global->id; ?>" data-sno="<?php echo $i; ?>" title="Edit Purchase Invoice"></a>
                <?php
                if($global->url != ""){
                  ?>
                  &nbsp;&nbsp;<a href="<?php echo URL::to($global->url.'/'.$global->filename); ?>" class="fa fa-download"  title="Download Invoice" download></a>
                  <?php
                }
                ?>
              </td>
            </tr>
            <?php
            $i++;
          }
        }
        ?>
      </tbody>
    </table>
  </div>
  <div class="modal_load"></div>
  <div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Supplier Invoices Journals are Reposted.</p>
  <p style="font-size:18px;font-weight: 600;">Processing Invoice of <span id="apply_first"></span> of <span id="apply_last"></span></p>
</div>
  <input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
  <input type="hidden" name="show_alert" id="show_alert" value="">
  <input type="hidden" name="pagination" id="pagination" value="1">
  
</div>
<script>
  $(function(){
      $('#client_expand').DataTable({
          autoWidth: false,
          scrollX: false,
          fixedColumns: false,
          searching: false,
          paging: false,
          info: false,
          order: []
      });
      blurfunction();
  });
  function blurfunction() {
    $(".net_detail").on('blur', function() {
      var net_value = $(this).val();
      var value = $(this).parents("tr").find(".select_vat_rates").val();
      if(net_value != "" && value != "")
      {
        var vat_value = (parseFloat(net_value) * (parseFloat(value) / 100)).toFixed(2);
        var gross_value = (parseFloat(net_value) + parseFloat(vat_value)).toFixed(2);
        $(this).parents("tr").find(".vat_detail").val(vat_value);
        $(this).parents("tr").find(".gross_detail").val(gross_value);

        var total_net = '0.00';
        $(".net_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_net = (parseFloat(total_net) + parseFloat(value)).toFixed(2);
          }
        });


        var total_vat = '0.00';
        $(".vat_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_vat = (parseFloat(total_vat) + parseFloat(value)).toFixed(2);
          }
        });

        var total_gross = '0.00';
        $(".gross_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_gross = (parseFloat(total_gross) + parseFloat(value)).toFixed(2);
          }
        });
        $(".total_detail_net").val(total_net);
        $(".total_detail_vat").val(total_vat);
        $(".total_detail_gross").val(total_gross);

        var net_global = parseFloat($(".net_global").val()).toFixed(2);
        var vat_global = parseFloat($(".vat_global").val()).toFixed(2);
        var gross_global = parseFloat($(".gross_global").val()).toFixed(2);

        if(net_global == total_net) { $(".total_detail_net").css("color","green"); }
        else { $(".total_detail_net").css("color","red"); }

        if(vat_global == total_vat) { $(".total_detail_vat").css("color","green"); }
        else { $(".total_detail_vat").css("color","red"); }

        if(gross_global == total_gross) { $(".total_detail_gross").css("color","green"); }
        else { $(".total_detail_gross").css("color","red"); }
      }
    });


    $(".net_global").on('blur', function() {
      var net_value = $(this).val();
      var vat_value = $(this).parents("tr").find(".vat_global").val();
      if(net_value != "" && vat_value != "")
      {
        var gross_value = (parseFloat(net_value) + parseFloat(vat_value)).toFixed(2);
        $(this).parents("tr").find(".gross_global").val(gross_value);

        var total_net = '0.00';
        $(".net_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_net = (parseFloat(total_net) + parseFloat(value)).toFixed(2);
          }
        });


        var total_vat = '0.00';
        $(".vat_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_vat = (parseFloat(total_vat) + parseFloat(value)).toFixed(2);
          }
        });

        var total_gross = '0.00';
        $(".gross_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_gross = (parseFloat(total_gross) + parseFloat(value)).toFixed(2);
          }
        });
        $(".total_detail_net").val(total_net);
        $(".total_detail_vat").val(total_vat);
        $(".total_detail_gross").val(total_gross);

        var net_global = parseFloat($(".net_global").val()).toFixed(2);
        var vat_global = parseFloat($(".vat_global").val()).toFixed(2);
        var gross_global = parseFloat($(".gross_global").val()).toFixed(2);

        if(net_global == total_net) { $(".total_detail_net").css("color","green"); }
        else { $(".total_detail_net").css("color","red"); }

        if(vat_global == total_vat) { $(".total_detail_vat").css("color","green"); }
        else { $(".total_detail_vat").css("color","red"); }

        if(gross_global == total_gross) { $(".total_detail_gross").css("color","green"); }
        else { $(".total_detail_gross").css("color","red"); }
      }
    });

    $(".vat_global").on('blur', function() {
      var net_value = $(this).parents("tr").find(".net_global").val();
      var vat_value = $(this).val(); 
      if(net_value != "" && vat_value != "")
      {
        var gross_value = (parseFloat(net_value) + parseFloat(vat_value)).toFixed(2);
        $(this).parents("tr").find(".gross_global").val(gross_value);

        var total_net = '0.00';
        $(".net_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_net = (parseFloat(total_net) + parseFloat(value)).toFixed(2);
          }
        });


        var total_vat = '0.00';
        $(".vat_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_vat = (parseFloat(total_vat) + parseFloat(value)).toFixed(2);
          }
        });

        var total_gross = '0.00';
        $(".gross_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_gross = (parseFloat(total_gross) + parseFloat(value)).toFixed(2);
          }
        });
        $(".total_detail_net").val(total_net);
        $(".total_detail_vat").val(total_vat);
        $(".total_detail_gross").val(total_gross);

        var net_global = parseFloat($(".net_global").val()).toFixed(2);
        var vat_global = parseFloat($(".vat_global").val()).toFixed(2);
        var gross_global = parseFloat($(".gross_global").val()).toFixed(2);

        if(net_global == total_net) { $(".total_detail_net").css("color","green"); }
        else { $(".total_detail_net").css("color","red"); }

        if(vat_global == total_vat) { $(".total_detail_vat").css("color","green"); }
        else { $(".total_detail_vat").css("color","red"); }

        if(gross_global == total_gross) { $(".total_detail_gross").css("color","green"); }
        else { $(".total_detail_gross").css("color","red"); }
      }
    });


    var total_net = '0.00';
    $(".net_detail").each(function() {
      var value = $(this).val();
      if(value != "")
      {
        total_net = (parseFloat(total_net) + parseFloat(value)).toFixed(2);
      }
    });


    var total_vat = '0.00';
    $(".vat_detail").each(function() {
      var value = $(this).val();
      if(value != "")
      {
        total_vat = (parseFloat(total_vat) + parseFloat(value)).toFixed(2);
      }
    });

    var total_gross = '0.00';
    $(".gross_detail").each(function() {
      var value = $(this).val();
      if(value != "")
      {
        total_gross = (parseFloat(total_gross) + parseFloat(value)).toFixed(2);
      }
    });
    $(".total_detail_net").val(total_net);
    $(".total_detail_vat").val(total_vat);
    $(".total_detail_gross").val(total_gross);

    var net_global = parseFloat($(".net_global").val()).toFixed(2);
        var vat_global = parseFloat($(".vat_global").val()).toFixed(2);
        var gross_global = parseFloat($(".gross_global").val()).toFixed(2);

    if(net_global == total_net) { $(".total_detail_net").css("color","green"); }
    else { $(".total_detail_net").css("color","red"); }

    if(vat_global == total_vat) { $(".total_detail_vat").css("color","green"); }
    else { $(".total_detail_vat").css("color","red"); }

    if(gross_global == total_gross) { $(".total_detail_gross").css("color","green"); }
    else { $(".total_detail_gross").css("color","red"); }

    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
    $(".from_invoice").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD-MMM-YYYY',
    });
    $(".to_invoice").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD-MMM-YYYY',
    });
    $(".inv_date_global").datetimepicker({     
       format: 'L',
       format: 'DD-MM-YYYY',
    });

    $(".report_from_date").datetimepicker({       
       format: 'L',
       format: 'DD-MMM-YYYY',
    });
    $(".report_to_date").datetimepicker({       
       format: 'L',
       format: 'DD-MMM-YYYY',
    });
  }
  $(document).ready(function() {
    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
    $(".from_invoice").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD-MMM-YYYY',
    });
    $(".to_invoice").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD-MMM-YYYY',
    });
    $(".inv_date_global").datetimepicker({     
       format: 'L',
       format: 'DD-MM-YYYY',
    });
  });
  $(window).change(function(e) {
    if($(e.target).hasClass('select_vat_rates'))
    {
      var value = $(e.target).val();
      var net_value = $(e.target).parents("tr").find(".net_detail").val();
      if(net_value != "" && value != "")
      {
        var vat_value = (parseFloat(net_value) * (parseFloat(value) / 100)).toFixed(2);
        var gross_value = (parseFloat(net_value) + parseFloat(vat_value)).toFixed(2);
        $(e.target).parents("tr").find(".vat_detail").val(vat_value);
        $(e.target).parents("tr").find(".gross_detail").val(gross_value);


        var total_net = '0.00';
        $(".net_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_net = (parseFloat(total_net) + parseFloat(value)).toFixed(2);
          }
        });


        var total_vat = '0.00';
        $(".vat_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_vat = (parseFloat(total_vat) + parseFloat(value)).toFixed(2);
          }
        });

        var total_gross = '0.00';
        $(".gross_detail").each(function() {
          var value = $(this).val();
          if(value != "")
          {
            total_gross = (parseFloat(total_gross) + parseFloat(value)).toFixed(2);
          }
        });
        $(".total_detail_net").val(total_net);
        $(".total_detail_vat").val(total_vat);
        $(".total_detail_gross").val(total_gross);

        var net_global = parseFloat($(".net_global").val()).toFixed(2);
        var vat_global = parseFloat($(".vat_global").val()).toFixed(2);
        var gross_global = parseFloat($(".gross_global").val()).toFixed(2);

        if(net_global == total_net) { $(".total_detail_net").css("color","green"); }
        else { $(".total_detail_net").css("color","red"); }

        if(vat_global == total_vat) { $(".total_detail_vat").css("color","green"); }
        else { $(".total_detail_vat").css("color","red"); }

        if(gross_global == total_gross) { $(".total_detail_gross").css("color","green"); }
        else { $(".total_detail_gross").css("color","red"); }
      }
    }
    if($(e.target).hasClass('select_supplier'))
    {
      var id = $(e.target).val();
      if(id != "")
      {
        $.ajax({
          url:"<?php echo URL::to('user/get_supplier_info_details'); ?>",
          type:"post",
          dataType:"json",
          data:{id:id},
          success:function(result)
          {
            $(".supplier_code_td").html(result['supplier_code']);
            $(".supplier_name_td").html(result['supplier_name']);
            $(".web_url_td").html(result['web_url']);
            $(".phone_no_td").html(result['phone_no']);
            $(".email_td").html(result['email']);
            $(".iban_td").html(result['iban']);
            $(".bic_td").html(result['bic']);
            $(".vat_no_td").html(result['vat_no']);
            $(".select_nominal_codes").val(result['default_nominal']);
          }
        })
      }
    }
  });
function next_journal_check(count)
{
  var invid = $(".edit_purchase_invoice:eq("+count+")").attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/check_supplier_journal_repost'); ?>",
    type:"post",
    data:{invid:invid},
    success:function(result)
    {
      setTimeout( function() {
        var countval = count + 1;
        if($(".edit_purchase_invoice:eq("+countval+")").length > 0)
        {
          next_journal_check(countval);
          $("#apply_first").html(countval);
        }
        else{
          $("body").removeClass("loading_apply");
          $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Journals Reposted Successfully.</p>',fixed:true,width:"800px"});
        }
      },200);
    }
  });
}
  $(window).click(function(e) {

if($(e.target).hasClass('report_radio')){
  var type = $(e.target).val();
  $(".report_type_input").val(type);
}

if($(e.target).hasClass('report_button')){
  $(".report_modal").modal('show');
  /*$(".report_radio").val('');
  $(".report_type_input").val('');
  $(".report_from_date").val('');
  $(".report_to_date").val('');*/
}


if($(e.target).hasClass('run_report_invoice')){
  var type = $(".report_type_input").val();
  var from = $(".report_from_date").val();
  var to = $(".report_to_date").val();

  if(type == ''){
    $(".error_report_type").html('Please select type');
  }
  else if(from == ''){
    $(".error_report_type").html('');
    $(".error_from_date").html('Please choose from date');
  }
  else if(to == ''){
    $(".error_from_date").html('');
    $(".error_to_date").html('Please choose to date');
  }
  else{
    $(".error_to_date").html('');
    $("body").addClass("loading");
      setTimeout(function(){ 
        $.ajax({
          url: "<?php echo URL::to('user/supplier_invoice_report_download') ?>",
          data:{type:type,from:from,to:to},
          type:"post",
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
            $("body").removeClass("loading");
          }
        });
      }, 500);
    }

  


}

    if($(e.target).hasClass('add_supplier_invoice')){

  $(".supplier_name_class").val('');
  $(".supp_address_class").val('');
  $(".supplier_address_class").val('');
  $(".phone_no_class").val('');
  $(".supplier_email_class").val('');
  $(".supplier_iban_class").val('');
  $(".supplier_bic_class").val('');
  $(".supplier_count").val('');
  $(".vat_number_class").val('');
  $(".currency_class").val('');
  $(".opening_balance_class").val('');


  $("#add_supplier_invoice_modal").modal('show');
  $("#add_supplier_invoice_modal").css({"z-index":"999999999999999998 !important"})
}

if($(e.target).hasClass('submit_module_update_invoice')){
  var supplier_code = $(".supplier_code_class").val();
  var supplier_name = $(".supplier_name_class").val();
  var supp_address = $(".supp_address_class").val();
  var supplier_address = $(".supplier_address_class").val();
  var phone_no = $(".phone_no_class").val();
  var supplier_email = $(".supplier_email_class").val();
  var supplier_iban = $(".supplier_iban_class").val();
  var supplier_bic = $(".supplier_bic_class").val();
  var supplier_count = $(".supplier_count_class").val();
  var vat_number = $(".vat_number_class").val();
  var currency = $(".currency_class").val();
  var opening_balance = $(".opening_balance_class").val();
  var supplier_id = $("#supplier_id_invoice").val();

  if(supplier_name == ''){
    $(".error_supplier_name_class").show();
  }
  else{
    $(".error_supplier_name_class").hide();
    setTimeout(function() {      
      $.ajax({
          url:"<?php echo URL::to('user/store_supplier_invoice'); ?>",
          type:"post",
          dataType:"json",
          data:{supplier_id:supplier_id, supplier_code:supplier_code, supplier_name:supplier_name, supp_address:supp_address, supplier_address:supplier_address, phone_no:phone_no, supplier_email:supplier_email, supplier_iban:supplier_iban, supplier_bic:supplier_bic, vat_number:vat_number, currency:currency, opening_balance:opening_balance, supplier_count:supplier_count},
          success: function(result)
          {
            $(".supplier_code_class").val(result['code']);
            $(".select_supplier_invoice").html(result['drop_supplier']);
            $(".supplier_invoice_message").html(result['message']);

            $(".supplier_code_td").html(result['supplier_code']);
            $(".supplier_name_td").html(result['supplier_name']);
            $(".web_url_td").html(result['web_url']);
            $(".phone_no_td").html(result['phone_no']);
            $(".email_td").html(result['email']);
            $(".iban_td").html(result['iban']);
            $(".bic_td").html(result['bic']);
            $(".vat_no_td").html(result['vat_no']);


            $("#add_supplier_invoice_modal").modal('hide');
            
          }
        })
      },500);
  }

}
    if($(e.target).hasClass('repost_journals')){
      $("body").addClass("loading_apply");
      var lengthval = $(".edit_purchase_invoice").length;
      $("#apply_first").html(0);
      $("#apply_last").html(lengthval);
      next_journal_check(0);
    }
    if($(e.target).hasClass('delete_global_attachments'))
    {
      var global_id = $("#hidden_global_id").val();
      var r= confirm("Do you want to delete the file?");
      if(r){
        $("#global_file_url").val("");
        $("#global_file_name").val("");
        $(".global_file_upload").html("");
      }

      if(global_id != ""){
        $.ajax({
          url:"<?php echo URL::to('user/delete_supplier_global_attachment'); ?>",
          type:"post",
          data:{global_id:global_id},
          success:function(result){
            
          }
        })
      }
    }
    if($(e.target).hasClass('export_transaction_list'))
    {
      $("body").addClass("loading");
      var supplier_id = $("#hidden_trans_supplier_id").val();
      $.ajax({
        url:"<?php echo URL::to('user/export_supplier_transaction_list'); ?>",
        type:"post",
        data:{supplier_id:supplier_id},
        success:function(result){
          SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
          $("body").removeClass("loading");
        }
      })
    }
    if($(e.target).hasClass('transaction_list'))
    {
      var id = $(".select_supplier").val();
      if(id == "")
      {
        alert("Please select the Supplier and then load the Transaction lists");
      }
      else{
        $.ajax({
          url:"<?php echo URL::to('user/get_supplier_transaction_list'); ?>",
          type:"post",
          data:{id:id, type:1},
          dataType:"json",
          success:function(result)
          {
            $("#transaction_list_modal_invoice").modal("show");
            $("#supplier_info_tbody_invoice").html(result['output']);
            $("#supplier_info_tbody_invoice").find(".supplier_info_div").hide();
          }
        });
      }
    }
    if($(e.target).hasClass('close_purchase_invoice'))
    {
      var r = confirm('Are you sure you want to close the Purchase Invoice Overlay? If you have any unsaved data then please click on "Submit Purchase Invoice" button, so you do not loose any data.');
      if(r){
        $(".add_purchase_invoice_modal").modal("hide");
        $(".total_detail_net").val("");
        $(".total_detail_vat").val("");
        $(".total_detail_gross").val("");
      }
    }
    if($(e.target).hasClass('submit_purchase_invoice')) {
      if($("#add_purchase_invoice_form").valid())
      {
        var des = $("#detail_tbody").find("tr").last().find(".description_detail").val();
        var code = $("#detail_tbody").find("tr").last().find(".select_nominal_codes").val();
        var net = $("#detail_tbody").find("tr").last().find(".net_detail").val(); 
        var vat_rate = $("#detail_tbody").find("tr").last().find(".select_vat_rates").val(); 
        var vat_value = $("#detail_tbody").find("tr").last().find(".vat_detail").val(); 
        var gross = $("#detail_tbody").find("tr").last().find(".gross_detail").val(); 

        if(des == "" || code == "" || net == "" || vat_rate == "" || vat_value == "" || gross == "")
        {
          alert("Please fill all the fields and then proceed with next invoice");
        }
        else{
          var supplier = $(".select_supplier").val();
          var net_global = $(".net_global").val();
          var vat_global = $(".vat_global").val();
          var gross_global = $(".gross_global").val();

          net_global = parseFloat(net_global).toFixed(2);
          vat_global = parseFloat(vat_global).toFixed(2);
          gross_global = parseFloat(gross_global).toFixed(2);

          var total_net = 0.00;
          $(".net_detail").each(function() {
            var value = $(this).val();
            total_net = (parseFloat(total_net) + parseFloat(value)).toFixed(2);
          })

          var total_vat = 0.00;
          $(".vat_detail").each(function() {
            var value = $(this).val();
            total_vat = (parseFloat(total_vat) + parseFloat(value)).toFixed(2);
          })

          var total_gross = 0.00;
          $(".gross_detail").each(function() {
            var value = $(this).val();
            total_gross = (parseFloat(total_gross) + parseFloat(value)).toFixed(2);
          })

          //console.log(net_global+'----'+total_net+'----'+gross_global+'----'+total_gross)

          if((net_global == total_net) && (vat_global == total_vat) && (gross_global == total_gross)) {
            $("#add_purchase_invoice_form").submit();
          }
          else{
            alert("The Global value and the Total of Detail value are different. Please check the Net and Gross Value are same in both Global and Detail Section")
          }
        }
      }
    }
    if($(e.target).hasClass('add_detail_section'))
    {
      if($(e.target).hasClass('remove_detail_section'))
      {
        var r = confirm("Are you sure you want to delete this invoice?");
        if(r)
        {
          $(e.target).parents("tr").detach();
          var ival = 1;
          $("#detail_tbody").find("tr").each(function() {
            $(this).find("td").eq(0).html(ival);
            $(this).find("td").eq(8).html('<a href="javascript:" class="fa fa-trash remove_detail_section"></a>');
            ival++;
          });

          $("#detail_tbody").find("tr").last().find("td").eq(8).html('<a href="javascript:" class="fa fa-trash remove_detail_section"></a>&nbsp;&nbsp;<a href="javascript:" class="fa fa-plus add_detail_section"></a>');
        }
      }
      else{
        var des = $(e.target).parents("tr").find(".description_detail").val();
        var code = $(e.target).parents("tr").find(".select_nominal_codes").val();
        var net = $(e.target).parents("tr").find(".net_detail").val(); 
        var vat_rate = $(e.target).parents("tr").find(".select_vat_rates").val(); 
        var vat_value = $(e.target).parents("tr").find(".vat_detail").val(); 
        var gross = $(e.target).parents("tr").find(".gross_detail").val(); 

        if(des == "" || code == "" || net == "" || vat_rate == "" || vat_value == "" || gross == "")
        {
          alert("Please fill all the fields and then proceed with next invoice");
        }
        else{
          var html = $(e.target).parents("tr").html();
          $("#detail_tbody").append('<tr>'+html+'</tr>');
          $("#detail_tbody").find("tr").last().find("input").val("");
          $("#detail_tbody").find("tr").last().find("select").val("");
          $("#detail_tbody").find("tr").last().find(".select_vat_rates").val(vat_rate);

          var ival = 1;
          $("#detail_tbody").find("tr").each(function() {
            $(this).find("td").eq(0).html(ival);
            $(this).find("td").eq(8).html('<a href="javascript:" class="fa fa-trash remove_detail_section"></a>');
            ival++;
          });

          $("#detail_tbody").find("tr").last().find("td").eq(8).html('<a href="javascript:" class="fa fa-trash remove_detail_section"></a>&nbsp;&nbsp;<a href="javascript:" class="fa fa-plus add_detail_section"></a>');
        }
      }
      blurfunction();
    }
    else if($(e.target).hasClass('remove_detail_section'))
    {
      var r = confirm("Are you sure you want to delete this invoice?");
      if(r)
      {
        $(e.target).parents("tr").detach();
        var ival = 1;
        $("#detail_tbody").find("tr").each(function() {
          $(this).find("td").eq(0).html(ival);
          $(this).find("td").eq(8).html('<a href="javascript:" class="fa fa-trash remove_detail_section"></a>');
          ival++;
        });

        $("#detail_tbody").find("tr").last().find("td").eq(8).html('<a href="javascript:" class="fa fa-trash remove_detail_section"></a>&nbsp;&nbsp;<a href="javascript:" class="fa fa-plus add_detail_section"></a>');
        blurfunction();
      }
    }

    if($(e.target).hasClass('change_vat_status'))
    {
      var id = $(e.target).attr("data-element");
      var status = $(e.target).attr("data-status");
      $.ajax({
        url:"<?php echo URL::to('user/change_supplier_vat_status'); ?>",
        type:"post",
        data:{id:id,status:status},
        success:function(result)
        {
          if(status == 1)
          {
            $(e.target).attr("class","fa fa-times change_vat_status");
            $(e.target).css("color","#f00");
            $(e.target).attr("data-status",0);
          }
          else{
            $(e.target).attr("class","fa fa-check change_vat_status");
            $(e.target).css("color","green");
            $(e.target).attr("data-status",1);
          }
        }
      });
    }
    if($(e.target).hasClass('submit_vat_rate'))
    {
      var value = $(".vat_rate_input").val();
      if(value == ""){
        alert("Please enter the vat rate");
      }
      else{
        var tr_length = $(".vat_tr").length;
        $.ajax({
          url:"<?php echo URL::to('user/store_supplier_vat_rate'); ?>",
          type:"post",
          data:{value:value,tr_length:tr_length},
          success:function(result)
          {
            $("#vat_rate_tbody").append(result);
            $(".vat_rate_input").val("");
          }
        })
      }
    }
    if($(e.target).hasClass('invoice_date_option'))
    {
      var value = $(e.target).val();
      if(value == "1")
      {
        $(".invoice_year_div").show();
        $(".invoice_custom_div").hide();
        $(".invoice_select_year").val("");
        var myVal = $('.invoice_select_year option:last').val();
        $('.invoice_select_year').val(myVal);
      }
      else if(value == "2")
      {
        $(".invoice_year_div").hide();
        $(".custom_date_div").hide();
        $('#client_expand').DataTable().destroy();
        $("body").addClass("loading");
          setTimeout(function(){ 
            $.ajax({
              url: "<?php echo URL::to('user/load_all_global_invoice') ?>",
              data:{type:"2"},
              type:"post",
              success:function(result){
                $("#invoice_tbody").html(result);
                $('#client_expand').DataTable({
                    autoWidth: false,
                    scrollX: false,
                    fixedColumns: false,
                    searching: false,
                    paging: false,
                    info: false,
                    order: []
                });
                $("body").removeClass("loading");
              }
            });
          }, 2000);
      }
      else if(value == "3")
      {
        $(".invoice_year_div").hide();
        $(".invoice_custom_div").show();
        $(".from_invoice").val("");
        $(".to_invoice").val("");
      }
    }
    else if($(e.target).parents(".invoice_custom_div").length > 0)
    {
      $(".invoice_year_div").hide();
      $(".invoice_custom_div").show();
    }
    else if($(e.target).parents(".invoice_year_div").length > 0)
    {
      $(".invoice_year_div").show();
      $(".invoice_custom_div").hide();
    }
    else{
        $(".invoice_year_div").hide();
        $(".invoice_custom_div").hide();
    }

    if($(e.target).hasClass('load_all_cm_invoice')) {
      var type = $(".invoice_date_option:checked").val();
      if(type == "1")
      {
        var year = $(".invoice_select_year").val();
        if(year == "")
        {
          alert("Please select the year to review the invoice");
        }
        else{
          $('#client_expand').DataTable().destroy();
          $("body").addClass("loading");
            setTimeout(function(){ 
                  $.ajax({
                    url: "<?php echo URL::to('user/load_all_global_invoice') ?>",
                    data:{year:year,type:"1"},
                    type:"post",
                    success:function(result){
                      $("#invoice_tbody").html(result);       
                      $('#client_expand').DataTable({
                          autoWidth: false,
                          scrollX: false,
                          fixedColumns: false,
                          searching: false,
                          paging: false,
                          info: false,
                          order: []
                      });
                      $(".invoice_year_div").hide();
                      $(".invoice_custom_div").hide();
                      $("body").removeClass("loading");
                      }
                  });
            }, 2000);
        }
        
      }
      else if(type == "3")
      {
        $('#client_expand').DataTable().destroy();
        $("body").addClass("loading");
          setTimeout(function(){ 
              var from = $(".from_invoice").val();
              var to = $(".to_invoice").val();
                $.ajax({
                    url: "<?php echo URL::to('user/load_all_global_invoice') ?>",
                    data:{from:from,to:to,type:"3"},
                    type:"post",
                    success:function(result){
                      $("#invoice_tbody").html(result);  
                      $('#client_expand').DataTable({
                          autoWidth: false,
                          scrollX: false,
                          fixedColumns: false,
                          searching: false,
                          paging: false,
                          info: false,
                          order: []
                      });     
                      $(".invoice_year_div").hide();
                      $(".invoice_custom_div").hide();
                      $("body").removeClass("loading");
                    }
                });
          }, 2000);
        
      }
    }
    if($(e.target).hasClass('export_purchase_invoice')) {
      var type = $(".invoice_date_option:checked").val();
      if(type == "1")
      {
        var year = $(".invoice_select_year").val();
        if(year == "")
        {
          alert("Please select the year to review the invoice");
        }
        else{
          $("body").addClass("loading");
          setTimeout(function(){ 
            $.ajax({
              url: "<?php echo URL::to('user/export_all_global_invoice') ?>",
              data:{year:year,type:"1"},
              type:"post",
              success:function(result){
                SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
                $("body").removeClass("loading");
              }
            });
          }, 2000);
        }
      }
      else if(type == "2")
      {
        $("body").addClass("loading");
        setTimeout(function(){ 
          $.ajax({
            url: "<?php echo URL::to('user/export_all_global_invoice') ?>",
            data:{type:"1"},
            type:"post",
            success:function(result){
              SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
              $("body").removeClass("loading");
            }
          });
        }, 2000);
      }
      else if(type == "3")
      {
        $("body").addClass("loading");
        setTimeout(function(){ 
          var from = $(".from_invoice").val();
          var to = $(".to_invoice").val();
          $.ajax({
              url: "<?php echo URL::to('user/export_all_global_invoice') ?>",
              data:{from:from,to:to,type:"3"},
              type:"post",
              success:function(result){
                SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
                $("body").removeClass("loading");
              }
          });
        }, 2000);
      }
    }

    if($(e.target).hasClass('settings_btn'))
    {
      $(".vat_rate_settings_modal").modal("show");
    }
    ////////////////////////Add Purchase Invoice///////////////////////////
    if($(e.target).hasClass('add_purchase_invoice'))
    {
      $(".add_purchase_invoice_modal").modal("show");
      $(".add_purchase_invoice_modal").find(".modal-title").html("Add Purchase Invoice");
      $("#global_invoice_tbody").find(".form-control").val("");
      $("#detail_tbody").find(".form-control").val("");
      $("#hidden_global_id").val("");
      $("#hidden_sno").val("");
      $(".select_supplier").val("");
      $("#attachment_global_supplier_tbody").html('<spam class="global_file_upload"></spam><input type="hidden" name="global_file_url" id="global_file_url" value=""><input type="hidden" name="global_file_name" id="global_file_name" value=""><a href="javascript:" class="fa fa-plus faplus_progress" style="padding:5px;background: #dfdfdf;" title="Add Attachment"></a>');

      $(".total_detail_net").val("");
      $(".total_detail_vat").val("");
      $(".total_detail_gross").val("");

      $(".supplier_code_td").html("");
      $(".supplier_name_td").html("");
      $(".web_url_td").html("");
      $(".phone_no_td").html("");
      $(".email_td").html("");
      $(".iban_td").html("");
      $(".bic_td").html("");
      $(".vat_no_td").html("");

      var nominal_codes = $(".select_nominal_codes_dummy").html();
      var vat_rates = $(".select_vat_rates_dummy").html();
      $("#detail_tbody").html('<tr><td>1</td><td><input type="text" name="description_detail[]" class="form-control description_detail" value="" placeholder="Enter Description"></td><td><select name="select_nominal_codes[]" class="form-control select_nominal_codes">'+nominal_codes+'</select></td><td><input type="text" name="net_detail[]" class="form-control net_detail" value="" placeholder="Enter Net Value" oninput="keypressonlynumber(this)" onpaste="keypressonlynumber(this)"></td><td><select name="select_vat_rates[]" class="form-control select_vat_rates">'+vat_rates+'</select></td><td><input type="text" name="vat_detail[]" class="form-control vat_detail" value="" placeholder="Enter VAT Value" readonly=""></td><td><input type="text" name="gross_detail[]" class="form-control gross_detail" value="" placeholder="Enter Gross" readonly=""></td><td class="detail_last_td" style="vertical-align: middle;text-align: center"><a href="javascript:" class="fa fa-plus add_detail_section" title="Add Row"></a></td></tr>');
    }
    if($(e.target).hasClass('edit_purchase_invoice'))
    {
      var id = $(e.target).attr("data-element");
      var sno = $(e.target).attr("data-sno");
      $.ajax({
        url:"<?php echo URL::to('user/edit_purchase_invoice_supplier'); ?>",
        type:"post",
        data:{id:id},
        dataType:"json",
        success:function(result)
        {
          $("#supplier_detail_tbody").html(result['supplier_output']);
          $("#global_invoice_tbody").html(result['global_output']);
          $("#detail_tbody").html(result['detail_output']);
          $("#attachment_global_supplier_tbody").html(result['attachment_output']);
          $(".select_supplier").val(result['supplier_id']);
          $("#hidden_global_id").val(id);
          $("#hidden_sno").val(sno);
          $(".add_purchase_invoice_modal").modal("show");
          $(".add_purchase_invoice_modal").find(".modal-title").html("Edit Purchase Invoice");

          blurfunction();
        }
      })
    }
    if($(e.target).hasClass('view_purchase_invoice'))
    {
      var id = $(e.target).attr("data-element");
      $.ajax({
        url:"<?php echo URL::to('user/view_purchase_invoice_supplier'); ?>",
        type:"post",
        data:{id:id},
        dataType:"json",
        success:function(result)
        {
          $("#detail_tbody_view").html(result['detail_output']);

          $(".view_purchase_invoice_modal").modal("show");
          blurfunction();
        }
      })
    }

    //////////////////////////////////////////////////////////////////////

    if($(e.target).hasClass('faplus_progress'))
    {
      var global_id = $(e.target).attr("data-element");
      if(global_id != ""){
        $("#hidden_global_inv_id").val(global_id);
      } else {
        $("#hidden_global_inv_id").val("");
      }
      
      $(".dropzone_global_supplier_modal").modal("show");
      // Dropzone.forElement("#imageUpload").removeAllFiles(true);
      // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
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
  });
Dropzone.options.imageUpload200 = {
    maxFiles: 1,
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
            $(".global_file_upload").html("<a href='"+obj.download_url+"' class='file_attachments' download>"+obj.filename+"</a> <a href='javascript:' class='fa fa-trash delete_global_attachments'></a>");
            $("#global_file_url").val(obj.url);
            $("#global_file_name").val(obj.filename);
            $(".dropzone_global_supplier_modal").modal("hide");
            $(".dropzone_completion_modal").modal("hide");
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            var acceptedcount= this.getAcceptedFiles().length;
            var rejectedcount= this.getRejectedFiles().length;
            var totalcount = acceptedcount + rejectedcount;
            $("#total_count_files").val(totalcount);
            $("body").removeClass("loading");
            Dropzone.forElement("#imageUpload200").removeAllFiles(true);
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
</script>
@stop