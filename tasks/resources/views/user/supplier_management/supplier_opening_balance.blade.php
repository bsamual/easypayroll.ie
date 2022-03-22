@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo URL::to('assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>
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
  .table thead th:focus{background: #ddd !important;}
  .form-control{border-radius: 0px;}
  .disabled{cursor :auto !important;pointer-events: auto !important}
  body #coupon {
    display: none;
  }
  .error{color: #f00; font-size: 12px;}
  a:hover{text-decoration: underline;}
</style>




<div class="modal fade" id="transaction_list_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 99999999999;">
  <div class="modal-dialog modal-lg" role="document" style="width:70%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
        <h4 class="modal-title">Transaction List - <span class="transaction_list_title"></span></h4>
      </div>
      <div class="modal-body" id="supplier_info_tbody" style="clear:both; overflow: unset;">

      </div>
      <div class="modal-footer" style="clear:both">

      </div>
    </div>
  </div>
</div>
<div class="modal fade invoice_payment_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="z-index: 99999999999999; background: rgb(0,0,0,0.8);">
  <div class="modal-dialog modal-lg" role="document" style="width: 80%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title invoice_payment_title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
          <table class="table own_table_white" id="result_payment_invoice" style="background: #fff;"></table>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="add_supplier_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 99999999999;">
  <div class="modal-dialog modal-lg" role="document" style="width:30%">
    <div class="modal-content">
      <form name="add_supplier_form" method="post" action="<?php echo URL::to('user/store_supplier'); ?>" id="add_supplier_form">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add Supplier</h4>
        </div>
        <div class="modal-body" style="clear:both;">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item active">
              <a class="nav-link active" id="first-tab" data-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="true">Add Supplier</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Supplier Opening Balance</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent" style="margin-top:20px;min-height:500px;max-height:500px;overflow-y: scroll">
              <div class="tab-pane fade active in" id="first" role="tabpanel" aria-labelledby="first-tab">
                <?php
                $supplier_count = DB::table('suppliers')->orderBy('id','desc')->first(); 
                if(count($supplier_count))
                {
                  $count = substr($supplier_count->supplier_code,3,7);
                  $count = sprintf("%04d",$count + 1);
                }
                else{
                  $count = sprintf("%04d",1);
                }
                ?>
                
                  <div class="col-md-12 col-lg-12">
                    <label>Supplier Code : </label>
                    <div class="form-group">            
                      <input class="form-control" name="supplier_code" id="supplier_code" placeholder="Enter Supplier Code" type="text" value="GBS<?php echo $count; ?>" disabled>
                    </div>
                  </div>
                  <div class="col-md-12 col-lg-12">
                    <label>Supplier Name : </label>
                    <div class="form-group">            
                      <input class="form-control" name="supplier_name" id="supplier_name" placeholder="Enter Supplier Name" type="text" required>
                    </div>
                  </div>
                  <div class="col-md-12 col-lg-12">
                    <label>Supplier Address : </label>
                    <div class="form-group">            
                      <input class="form-control" name="supp_address" id="supp_address" placeholder="Enter Supplier Address" type="text">
                    </div>
                  </div>
                  <div class="col-md-12 col-lg-12">
                    <label>Web URL : </label>
                    <div class="form-group">            
                      <input class="form-control" name="supplier_address" id="supplier_address" placeholder="Enter Web URL" type="text">
                    </div>
                  </div>
                  <div class="col-md-12 col-lg-12">
                    <label>Phone Number : </label>
                    <div class="form-group">            
                      <input class="form-control" name="phone_no" id="phone_no" placeholder="Enter Phone Number" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                    </div>
                  </div>
                  <div class="col-md-12 col-lg-12">
                    <label>Email Address : </label>
                    <div class="form-group">            
                      <input class="form-control" name="supplier_email" id="supplier_email" placeholder="Enter Email Address" type="email">
                    </div>
                  </div>
                  <div class="col-md-12 col-lg-12">
                    <label>Bank Account IBAN : </label>
                    <div class="form-group">            
                      <input class="form-control" name="supplier_iban" id="supplier_iban" placeholder="Enter Bank Account IBAN" type="text">
                    </div>
                  </div>
                  <div class="col-md-12 col-lg-12">
                    <label>Bank Account BIC : </label>
                    <div class="form-group">            
                      <input class="form-control" name="supplier_bic" id="supplier_bic" placeholder="Enter Bank Account BIC" type="text">
                      <input type="hidden" name="supplier_count" id="supplier_count" value="GBS<?php echo $count; ?>">
                    </div>
                  </div>
                  <div class="col-md-12 col-lg-12">
                    <label>VAT Number : </label>
                    <div class="form-group">            
                      <input class="form-control" name="vat_number" id="vat_number" placeholder="Enter VAT Number" type="text">
                    </div>
                  </div>
                  <div class="col-md-12 col-lg-12">
                    <label>Currency : </label>
                    <div class="form-group">            
                      <input class="form-control" name="currency" id="currency" placeholder="Enter Currency" type="text" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');">
                    </div>
                  </div>
              </div>
              <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                  <div class="col-md-12 col-lg-12">
                    <label>Opening Balance : </label>
                    <div class="form-group">            
                      <input class="form-control" name="opening_balance" id="opening_balance" placeholder="Enter Opening Balance" type="text" value="" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');">
                    </div>
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer" style="clear:both">
          <input type="hidden" name="supplier_id" id="supplier_id" value="">
          <input type="submit" class="common_black_button" id="submit_module_update" value="Submit">
        </div>
      </form>
    </div>
  </div>
</div>

<div class="content_section" style="margin-bottom:200px">
  <div class="page_title" style="z-index:999;">
    <h4 class="col-lg-12 padding_00 new_main_title">
      Supplier Management System             
    </h4>
  </div>  
  <div class="col-lg-12"></div>
  <div style="clear: both;">
    <?php
    if(Session::has('message')) { ?>
      <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
    <?php }
    if(Session::has('error-message')) { ?>
      <p class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('error-message'); ?></p>
    <?php } ?>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URL::to('user/supplier_management'); ?>">Supplier Management</a>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo URL::to('user/supplier_opening_balance'); ?>">Opening Balance</a>
        </li>
      </ul>
    </div>
    
  </div>

  <div class="col-lg-12 text-right" style="padding-top: 20px; padding-bottom: 20px; background: #fff; display: none;">
    <a href="javascript:" class="common_black_button export_suppliers" style="font-size:14px;font-weight: bold;">Export Suppliers</a>
    <a href="javascript:" class="common_black_button add_supplier" style="font-size:14px;font-weight: bold;">Add Supplier</a>
    <a href="javascript:" class="common_black_button fa fa-refresh refresh_all_suppliers" style="font-size:14px;font-weight: bold;" title="Refresh Balance and Invoice Count"></a>
    <!-- <a href="javascript:" class="fa fa-refresh common_black_button" style="font-size:14px;font-weight: bold;"></a> -->
  </div> 
  <div class="col-lg-12 padding_00">
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
    <table class="table own_white_table" id="client_expand" width="100%" style="margin-top: 0px !important;">
      <thead>
        <tr>
          <th>Supplier Code</th>
          <th>Supplier Name</th>
          
          <th style="text-align: right">Opening Balance</th>
          
          <th style="text-align: center">Action</th>
        </tr>
      </thead>
      <tbody id="supplier_tbody">
        <?php
        if(count($suppliers)) {
          foreach($suppliers as $supplier) {
            /*$invoice_count = DB::table('supplier_global_invoice')->select(DB::raw('count(*) as invoice_count, sum(gross) as gross_sum'))->where('supplier_id',$supplier->id)->first();
            $payment_sum = DB::table('payments')->select(DB::raw('sum(amount) as payment_sum'))->where('imported',0)->where('debit_nominal','813')->where('supplier_code',$supplier->id)->first();
            $balance = ($supplier->opening_balance + $invoice_count->gross_sum) - $payment_sum->payment_sum;*/

            $opening_balance = number_format_invoice($supplier->opening_balance);
            /*$invoice_gross = number_format_invoice($invoice_count->gross_sum);
            $payment = number_format_invoice($payment_sum->payment_sum);*/

            if($supplier->opening_balance == '' || $supplier->opening_balance == 0 || $supplier->opening_balance == 0.00){
              $journal_button = '';
            }
            elseif($supplier->journal_id != ''){
              $journal_button = '<a href="javascript:" class="journal_id_viewer" data-element="'.$supplier->journal_id.'">'.$supplier->journal_id.'</a>';
            }
            else{
              $journal_button = '<a href="javascript:" class="common_black_button journal_button" data-element="'.base64_encode($supplier->id).'">Journal</a>';
            }
            ?>
            <tr class="supp_tr_<?php echo $supplier->id; ?>">
              <td><?php echo $supplier->supplier_code; ?></td>
              <td><?php echo $supplier->supplier_name; ?></td>
              
              <?php
              $http = '';
              if($supplier->web_url != ""){
                if ((strpos('http', $supplier->web_url) === false) && (strpos('https', $supplier->web_url) === false))
                {
                  $http = 'http://';
                }
              }
              ?>
              <td class="class_opening_td" align="right">
                <spam class="class_opening_spam"><?php echo $opening_balance?></spam>
              </td>
              
              <td style="text-align: center">
                <?php echo $journal_button ?>
              </td>
            </tr>
            <?php
          }
        }
        ?>
      </tbody>
    </table>
  </div>
  <div class="modal_load"></div>
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
          info: false
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
      }
    });

    $(".net_global").on('blur', function() {
      var net_value = $(this).val();
      var vat_value = $(this).parents("tr").find(".vat_global").val();
      if(net_value != "" && vat_value != "")
      {
        var gross_value = (parseFloat(net_value) + parseFloat(vat_value)).toFixed(2);
        $(this).parents("tr").find(".gross_global").val(gross_value);
      }
    });

    $(".vat_global").on('blur', function() {
      var net_value = $(this).parents("tr").find(".net_global").val();
      var vat_value = $(this).val(); 
      if(net_value != "" && vat_value != "")
      {
        var gross_value = (parseFloat(net_value) + parseFloat(vat_value)).toFixed(2);
        $(this).parents("tr").find(".gross_global").val(gross_value);
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

    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
    $(".inv_date_global").datetimepicker({     
       format: 'L',
       format: 'DD-MM-YYYY',
    });
  }

$(window).click(function(e){

if($(e.target).hasClass('journal_button')){
  var value = $(e.target).attr("data-element");

  $("body").addClass("loading");
  setTimeout(function() {
    $.ajax({
        url:"<?php echo URL::to('user/supplier_journal_create'); ?>",
        type:"post",
        dataType:"json",
        data:{id:value},
        success:function(result){
          $(e.target).parent("td").html(result['id']);
          $("body").removeClass("loading");
          
        }
    })
  },200);



}


})

</script>
@stop