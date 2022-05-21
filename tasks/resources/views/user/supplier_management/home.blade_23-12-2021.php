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
  <div style="clear: both;">
    <?php
    if(Session::has('message')) { ?>
      <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
    <?php }
    if(Session::has('error-message')) { ?>
      <p class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('error-message'); ?></p>
    <?php } ?>
  </div>
  <div class="col-lg-12 text-right">
    <a href="javascript:" class="common_black_button export_suppliers" style="font-size:14px;font-weight: bold;">Export Suppliers</a>
    <a href="javascript:" class="common_black_button add_supplier" style="font-size:14px;font-weight: bold;">Add Supplier</a>
    <a href="javascript:" class="common_black_button fa fa-refresh refresh_all_suppliers" style="font-size:14px;font-weight: bold;" title="Refresh Balance and Invoice Count"></a>
    <!-- <a href="javascript:" class="fa fa-refresh common_black_button" style="font-size:14px;font-weight: bold;"></a> -->
  </div> 
  <div class="col-lg-12" style="margin-top: 10px;">
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
    <table class="table own_white_table" id="client_expand" width="100%">
      <thead>
        <tr>
          <th>Supplier Code</th>
          <th>Supplier Name</th>
          <th>Email Address</th>
          <th>Web URL</th>
          <th>Invoice Count</th>
          <th style="text-align: right">Balance Count</th>
          <th style="text-align: center">Action</th>
        </tr>
      </thead>
      <tbody id="supplier_tbody">
        <?php
        if(count($suppliers)) {
          foreach($suppliers as $supplier) {
            $invoice_count = DB::table('supplier_global_invoice')->select(DB::raw('count(*) as invoice_count, sum(gross) as gross_sum'))->where('supplier_id',$supplier->id)->first();
            $payment_sum = DB::table('payments')->select(DB::raw('sum(amount) as payment_sum'))->where('imported',0)->where('debit_nominal','813')->where('supplier_code',$supplier->id)->first();
            $balance = ($supplier->opening_balance + $invoice_count->gross_sum) - $payment_sum->payment_sum
            ?>
            <tr>
              <td><?php echo $supplier->supplier_code; ?></td>
              <td><?php echo $supplier->supplier_name; ?></td>
              <td><a href="mailto:<?php echo $supplier->email; ?>"><?php echo $supplier->email; ?></a></td>
              <?php
              $http = '';
              if($supplier->web_url != ""){
                if ((strpos('http', $supplier->web_url) === false) && (strpos('https', $supplier->web_url) === false))
                {
                  $http = 'http://';
                }
              }
              ?>
              <td><a href="<?php echo $http.$supplier->web_url; ?>" target="_blank"><?php echo $supplier->web_url; ?></a></td>
              <td class="invoice_count_td"><spam class="invoice_count_value" style="display:none"><?php echo $invoice_count->invoice_count; ?></spam> </td>
              <td class="balance_count_td" style="text-align: right"><spam class="balance_value" style="display:none"><?php echo number_format_invoice($balance); ?></spam></td>
              <td style="text-align: center">
                <!-- <a href="javascript:" class="fa fa-refresh refresh_supplier" data-element="<?php echo $supplier->id; ?>" title="Refresh Counts"></a>&nbsp;&nbsp;&nbsp; -->
                <a href="javascript:" class="fa fa-edit edit_supplier" data-element="<?php echo $supplier->id; ?>" title="Edit Supplier"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="javascript:" class="fa fa-refresh refresh_count" data-element="<?php echo $supplier->id; ?>" title="Refresh Supplier"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="javascript:" class="fa fa-money transaction_list" data-element="<?php echo $supplier->id; ?>" title="Transactions List"></a>
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
  $(document).ready(function() {
    var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
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
      }
    }
  });
  $(window).click(function(e) {

if($(e.target).hasClass('purchase_class')){
  var id = $(e.target).attr("data-element");

  setTimeout(function() {
  $.ajax({
      url:"<?php echo URL::to('user/load_single_invoice_payment'); ?>",
      type:"post",
      dataType:"json",
      data:{id:id, type:0},
      success: function(result)
      {
        $(".invoice_payment_title").html(result['page_title']);
        $("#result_payment_invoice").html(result['output']);

        $(".invoice_payment_modal").modal('show');        
        $("body").removeClass("loading");           
      }
    })
  },500);

}

if($(e.target).hasClass('payment_class')){
  var id = $(e.target).attr("data-element");

  setTimeout(function() {
  $.ajax({
      url:"<?php echo URL::to('user/load_single_invoice_payment'); ?>",
      type:"post",
      dataType:"json",
      data:{id:id, type:1},
      success: function(result)
      {
        $(".invoice_payment_title").html(result['page_title']);
        $("#result_payment_invoice").html(result['output']);

        $(".invoice_payment_modal").modal('show');        
        $("body").removeClass("loading");           
      }
    })
  },500);

}




    if($(e.target).hasClass('export_suppliers'))
    {
      $("body").addClass('loading');
      $.ajax({
        url:"<?php echo URL::to('user/export_suppliers_list'); ?>",
        type:"post",
        success:function(result){
          SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
          $("body").removeClass("loading");
        }
      })
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
          }
        })
      }
    }
    if($(e.target).hasClass('refresh_count'))
    {
      $("body").addClass('loading');
      var id = $(e.target).attr("data-element");
      setTimeout(function() {
        $.ajax({
          url:"<?php echo URL::to('user/refresh_supplier_counts'); ?>",
          type:"post",
          data:{id:id},
          dataType:"json",
          success:function(result)
          {
            $(e.target).parents("tr").find(".invoice_count_td").html('<spam class="invoice_count_value">'+result['invoice_count']+'</spam>');
            $(e.target).parents("tr").find(".balance_count_td").html('<spam class="balance_value">'+result['balance']+'</spam>');
            $("body").removeClass('loading');
          }
        })
      },1000);
    }
    if($(e.target).hasClass('refresh_all_suppliers'))
    {
      $("body").addClass("loading");
      setTimeout(function() {
        $(".invoice_count_value").show();
        $(".balance_value").show();
        $("body").removeClass("loading");
      },10000)
      
    }
    if($(e.target).hasClass('add_supplier'))
    {
      $("#add_supplier_modal").modal("show");
      $("#supplier_id").val("");
      $("#hidden_supplier_id").val("");
      var code = $("#supplier_count").val();
      $("#supplier_code").val(code);
      $("#add_supplier_modal").find(".modal-title").html("Add Supplier");
      $("#supplier_name").val('');
      $("#supplier_address").val('');
      $("#phone_no").val('');
      $("#supplier_email").val('');
      $("#supplier_iban").val('');
      $("#supplier_bic").val('');
      $("#vat_number").val('');
      $("#currency").val('');
      $("#opening_balance").val('');
    }
    if($(e.target).hasClass('edit_supplier'))
    {
      var id = $(e.target).attr("data-element");
      $("#supplier_id").val(id);
      $("#hidden_supplier_id").val(id);
      $.ajax({
        url:"<?php echo URL::to('user/edit_supplier'); ?>",
        type:"post",
        dataType:"json",
        data:{id:id},
        success:function(result)
        {
          $("#add_supplier_modal").modal("show");
          $("#supplier_code").val(result['supplier_code']);
          $("#supplier_name").val(result['supplier_name']);
          $("#supplier_address").val(result['web_url']);

          $("#supp_address").val(result['supplier_address']);

          $("#phone_no").val(result['phone_no']);
          $("#supplier_email").val(result['email']);
          $("#supplier_iban").val(result['iban']);
          $("#supplier_bic").val(result['bic']);
          $("#vat_number").val(result['vat_no']);
          $("#currency").val(result['currency']);
          $("#opening_balance").val(result['opening_balance']);

          $("#add_supplier_modal").find(".modal-title").html("Edit Supplier");
        }
      })
    }
    if($(e.target).hasClass('transaction_list'))
    {
      var id = $(e.target).attr("data-element");
      $("#transaction_table").dataTable().fnDestroy();
      $.ajax({
        url:"<?php echo URL::to('user/get_supplier_transaction_list'); ?>",
        type:"post",
        data:{id:id, type:0},
        dataType:"json",
        success:function(result)
        {
          $("#transaction_list_modal").modal("show");
          $(".transaction_list_title").html(result['modal_title']);
          $("#supplier_info_tbody").html(result['output']);
          $('#transaction_table').DataTable({
              fixedHeader: true,
                autoWidth: true,
                scrollX: false,
                fixedColumns: false,
                searching: false,
                paging: false,
                info: false
          });

          
        }
      })
    }

    if($(e.target).hasClass('add_purchase_invoice'))
    {
      $(".select_supplier").parents("tr").hide();
      $(".transaction_list").hide();
      var supplier_id = $(e.target).attr("data-element"); 
      var supplier_name = $(e.target).attr("data-supplier"); 
      $(".select_supplier").val(supplier_id);
      $(".add_purchase_invoice_modal").modal("show");
      $(".add_purchase_invoice_modal").find(".modal-title").html("Add Purchase Invoice for Supplier - "+supplier_name);
      $(".select_supplier").trigger("click");

      $("#global_invoice_tbody").find(".form-control").val("");
      $("#detail_tbody").find(".form-control").val("");
      $("#hidden_global_id").val("");
      $("#hidden_sno").val("");
      $("#attachment_global_supplier_tbody").html('<spam class="global_file_upload"></spam><input type="hidden" name="global_file_url" id="global_file_url" value=""><input type="hidden" name="global_file_name" id="global_file_name" value=""><a href="javascript:" class="fa fa-plus faplus_progress" style="padding:5px;background: #dfdfdf;" title="Add Attachment"></a>');

    }
    if($(e.target).hasClass('close_purchase_invoice'))
    {
      window.location.reload();
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
          var vat_global = $(".net_global").val();
          var gross_global = $(".gross_global").val();

          net_global = parseFloat(net_global).toFixed(2);
          gross_global = parseFloat(gross_global).toFixed(2);

          var total_net = 0.00;
          $(".net_detail").each(function() {
            var value = $(this).val();
            total_net = (parseFloat(total_net) + parseFloat(value)).toFixed(2);
          })

          var total_gross = 0.00;
          $(".gross_detail").each(function() {
            var value = $(this).val();
            total_gross = (parseFloat(total_gross) + parseFloat(value)).toFixed(2);
          })

          //console.log(net_global+'----'+total_net+'----'+gross_global+'----'+total_gross)

          if((net_global == total_net) && (gross_global == total_gross)) {
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
    if($(e.target).hasClass('faplus_progress'))
    {
      $("#hidden_global_inv_id").val("");
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