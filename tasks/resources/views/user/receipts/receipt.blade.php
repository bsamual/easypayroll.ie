@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<style>
body{
  background: #f5f5f5 !important;
}
.fa-sort{
  cursor:pointer;
  margin-left: 8px;
}
.code_td{cursor:pointer;}
.active_code_tr{background: #dfdfdf;}
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

.modal_load_apply1 {
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
body.loading_apply1 {
    overflow: hidden;   
}
body.loading_apply1 .modal_load_apply1 {
    display: block;
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
.form-control[readonly]{
      background-color: #fff !important
}
.formtable tr td{
  padding-left: 15px;
  padding-right: 15px;
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
body.loading {
    overflow: hidden;   
}
body.loading .modal_load {
    display: block;
}
.table thead th:focus{background: #ddd !important;}
.form-control{border-radius: 0px;}
.disabled{cursor :auto !important;pointer-events: auto !important}
.error{color: #f00; font-size: 12px;}
a:hover{text-decoration: underline;}
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
  z-index:999;
}
</style>
<?php
if(!empty($_GET['import_type_new']))
{
  if(!empty($_GET['round']))
  {
    
    $filename = $_GET['filename'];
    
    $height = $_GET['height'];
    $highestrow = $_GET['highestrow'];
    $round = $_GET['round'];
    ?>
    <div class="upload_img" style="width: 100%;height: 100%;z-index:1"><p class="upload_text">Uploading Please wait...</p><img src="<?php echo URL::to('assets/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">Finished Uploading <?php echo $height; ?> of <?php echo $highestrow; ?></p></div>
    <script>
      $(document).ready(function() {
        setTimeout( function() { $("body").removeClass('loading'); },3000);
        var base_url = "<?php echo URL::to('/'); ?>";
        window.location.replace(base_url+'/user/import_new_receipts_one?filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type_new=1');
      })
    </script>
    <?php
  }
}
$get_imported_receipts = DB::table('receipts')->where('imported',1)->get();
if(count($get_imported_receipts))
{
  DB::table('receipts')->where('imported',1)->delete();
}
?>
<div class="modal fade" id="receipt_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top:5%">
  <div class="modal-dialog" role="document" style="width:80%">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Receipt Settings</h4>
          </div>
          <div class="modal-body">
              <h4 style="text-align: center">
                Setup Nominals to Allow Receipts <spam style="font-weight:600;text-decoration: underline;font-style: italic;">TO</spam> under the Receipt System
              </h4>
              <div class="col-md-4 col-md-offset-1">
                <h5 style="text-align: center">Nominal List</h5>
                <div class="col-md-12" style="border:1px solid #dfdfdf;max-height: 500px;overflow-y: scroll">
                  <table class="table">
                    <thead>
                      <th>Code <i class="fa fa-sort nominal_code_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                      <th>Description <i class="fa fa-sort nominal_des_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                    </thead>
                    <tbody id="nominal_tbody">
                      <?php
                      if(count($nominal_codes))
                      {
                        foreach($nominal_codes as $code)
                        {
                          echo '<tr class="code_tr" id="code_tr_'.$code->id.'" data-element="'.$code->id.'">
                            <td class="code_td nominal_code_sort_val" data-element="'.$code->id.'">'.$code->code.'</td>
                            <td class="code_td nominal_des_sort_val" data-element="'.$code->id.'">'.$code->description.'</td>
                          </tr>';
                        }
                      }
                      else{
                        echo '<tr>
                          <td colspan="2">No Records Found</td>
                        </tr>';
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="col-md-2" style="text-align: center">
                <input type="button" name="add_to_allowable_list" class="common_black_button add_to_allowable_list" value="Add to Allowable List" style="margin-top:69%">
              </div>
              <div class="col-md-4">
                <h5 style="text-align: center">Allowable Receipts Nominals</h5>
                <div class="col-md-12" style="border:1px solid #dfdfdf;max-height: 500px;overflow-y: scroll">
                  <table class="table">
                    <thead>
                      <th>Code <i class="fa fa-sort allowable_code_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                      <th>Description <i class="fa fa-sort allowable_des_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                    </thead>
                    <tbody id="allowable_tbody">
                      <?php
                      if(count($receipt_nominal_codes))
                      {
                        foreach($receipt_nominal_codes as $code)
                        {
                          echo '<tr>
                            <td class="allowable_code_sort_val">'.$code->code.'</td>
                            <td class="allowable_des_sort_val">'.$code->description.'</td>
                          </tr>';
                        }
                      }
                      else{
                        echo '<tr class="no_records">
                          <td colspan="2">No Records Found</td>
                        </tr>';
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
              <input type="hidden" name="hidden_nominal_code_id" id="hidden_nominal_code_id" value="">
          </div>
          <div class="modal-footer" style="clear: both;">
          </div>
      </div>
  </div>
</div>
<div class="modal fade" id="import_receipts" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top:5%">
  <div class="modal-dialog modal-sm" role="document" style="width:60%">
      <div class="modal-content">
        <form id="import_form" action="<?php echo URL::to('user/import_new_receipts'); ?>" method="post" autocomplete="off" enctype="multipart/form-data">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Import Receipts</h4>
          </div>
          <div class="modal-body">
              <label style="float:left;margin-top: 9px;">Choose File : </label>
              <input type="file" name="new_file" id="new_file" class="form-control input-sm" accept=".csv" required style="width:40%;float:left;margin-left: 10px;"> 
              <input type="button" name="load_import" class="common_black_button load_import" value="LOAD FILE" style="margin-left: 10px;">
              <br/>
              <p style="float:left;clear: both;margin-top: 30px;">Note: The CSV file format to import the receipts should be in the format below and also please make sure that the TITLES are same as same as shown below,</p>
              <div class="col-md-12" style="max-height: 500px; overflow-y: scroll">
                <table class="table">
                  <thead>
                    <th>Date</th>
                    <th>Debit Nominal</th>
                    <th>Credit Nominal</th>
                    <th>Client Code</th>
                    <th>Credit Nominal Description</th>
                    <th>Comment</th>
                    <th>Amount</th>
                    <th>Error</th>
                  </thead>
                  <tbody id="check_tbody">
                  </tbody>
                </table>
              </div>
          </div>
          <div class="modal-footer" style="margin-top:20px;clear: both;">
              <input type="submit" class="common_black_button" id="import_new_file" value="Import">
          </div>
        </form>
      </div>
  </div>
</div>
<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
    <h4 class="col-lg-12 padding_00 new_main_title">Receipts Management System</h4>   
      
      <div class="col-lg-12"  style="padding: 0px;">
            <div class="col-lg-4 padding_00">
            </div>
            <div class="col-lg-8">
              <a href="javascript:" class="fa fa-cog common_black_button settings_btn" style="float:right" title="Receipt Settings"></a>
              <a href="javascript:" class="common_black_button receipt_import" style="float:right">Import</a>
              <a href="javascript:" class="common_black_button add_receipts_btn" style="float:right">Add Receipts</a>
            </div>
      </div>
      <div style="clear: both;">
        <?php
        if(Session::has('message')) { ?>
            <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
        
        <?php } ?>
        <?php
        if(Session::has('message_import')) { ?>
            <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message_import'); ?></p>
        
        <?php } ?>
      </div> 
      <div class="col-lg-12 add_receipt_div"  style="padding: 0px;display:none;margin-top: 12px;">
        <div class="col-lg-8 padding_00">
          <h4>Add Receipts</h4>
        </div>
        <div class="col-lg-4">
          <a href="javascript:" class="common_black_button add_receipt_export" style="float:right">Export</a>
        </div>
        <table class="table" id="receipt_add_expand" width="100%" style="margin-top: 45px;">
            <thead>
              <tr style="background: #fff;">
                  <th width="8%" style="text-align: left;">Date</th>
                  <th width="15%" style="text-align: left;">Debit Nominal & Description</th>
                  <th width="8%" style="text-align: left;">Credit Nominal</th>
                  <th width="15%" style="text-align: left;">Client Code</th>
                  <th width="15%" style="text-align: left;">Credit Nominal Description</th>
                  <th width="15%" style="text-align: left;">Comment</th>
                  <th width="8%" style="text-align: left;">Amount</th>
              </tr>
            </thead>                            
            <tbody id="add_tbody">
              <tr>
                <td><input type="text" name="date_add" class="form-control date_add" value=""></td>
                <td>
                  <select name="debit_nominal" class="form-control debit_nominal">
                    <option value="">Select Nominal</option>
                    <?php
                    $receipt_nominals = DB::table('receipt_nominal_codes')->get();
                    if(count($receipt_nominals))
                    {
                      foreach($receipt_nominals as $code)
                      {
                        echo '<option value="'.$code->code.'">'.$code->code.' - '.$code->description.'</option>';
                      }
                    }
                    ?>
                  </select>
                </td>
                <td>
                  <select name="credit_nominal" class="form-control credit_nominal">
                    <option value="">Select Nominal</option>
                    <?php
                    $nominals = DB::table('nominal_codes')->get();
                    if(count($nominals))
                    {
                      foreach($nominals as $code)
                      {
                        echo '<option value="'.$code->code.'">'.$code->code.' - '.$code->description.'</option>';
                      }
                    }
                    ?>
                  </select>
                </td>
                <td><input type="text" name="client_code_add" class="form-control client_code_add" placeholder="Enter Client Code" value="" disabled></td>
                <td><input type="text" name="credit_descripion_add" class="form-control credit_descripion_add" value="" readonly></td>
                <td><input type="text" name="comment_add" class="form-control comment_add" value=""></td>
                <td>
                  <input type="text" name="amount_add" class="form-control amount_add" value="">
                  <input type="hidden" name="hidden_receipt_id" class="hidden_receipt_id" value="">
                </td>
              </tr>
            </tbody>
        </table>
      </div>
      <div class="col-lg-12"  style="padding: 0px;">
        <h4>Receipts List</h4>
            <div class="col-lg-8 padding_00">
              <div class="col-md-4 padding_00">
                <label style="float:left;margin-top: 6px;">Load Options:</label>
                <select name="filter_receipts" class="form-control filter_receipts" style="width:70%;float:left;margin-left: 11px;">
                  <option value="">Select Filter Type</option>
                  <option value="7">Previous Year</option>
                  <option value="1">Current Year</option>
                  <option value="2">Specific Date Range</option>
                  <option value="3">Client</option>
                  <option value="4">Debit Nominal</option>
                  <option value="5">Credit Nominal</option>
                  <option value="6">Load Current</option>
                </select>
              </div>
              <div class="col-md-4 specific_date_div" style="display:none">
                <label style="float:left;margin-top: 6px;">From:</label>
                <input type="text" name="from_receipt" class="form-control from_receipt" style="width:33%;float:left;margin-left:10px">

                <label style="float:left;margin-top: 6px;margin-left: 10px;">To:</label>
                <input type="text" name="to_receipt" class="form-control to_receipt" style="width:33%;float:left;margin-left:10px">
              </div>
              <div class="col-md-4 client_div" style="display:none">
                <label style="float:left;margin-top: 6px;">Client:</label>
                <input type="text" name="client_receipt" class="form-control client_receipt" style="width:77%;float:left;margin-left:10px">
                <input type="hidden" name="hidden_client_id" id="hidden_client_id" value="">
              </div>
              <div class="col-md-4 debit_nominal_div" style="display:none">
                <label style="float:left;margin-top: 6px;">Debit Nominal:</label>
                <select name="debit_nominal" class="form-control debit_nominal_receipt" style="width:60%;float:left;margin-left:10px">
                    <option value="">Select Nominal</option>
                    <?php
                    $receipt_nominals = DB::table('receipt_nominal_codes')->get();
                    if(count($receipt_nominals))
                    {
                      foreach($receipt_nominals as $code)
                      {
                        echo '<option value="'.$code->code.'">'.$code->code.' - '.$code->description.'</option>';
                      }
                    }
                    ?>
                  </select>
              </div>
              <div class="col-md-4 credit_nominal_div" style="display:none">
                <label style="float:left;margin-top: 6px;">Credit Nominal:</label>
                <select name="credit_nominal" class="form-control credit_nominal_receipt" style="width:60%;float:left;margin-left:10px">
                    <option value="">Select Nominal</option>
                    <?php
                    $nominals = DB::table('nominal_codes')->get();
                    if(count($nominals))
                    {
                      foreach($nominals as $code)
                      {
                        echo '<option value="'.$code->code.'">'.$code->code.'</option>';
                      }
                    }
                    ?>
                  </select>
              </div>
              <div class="col-md-4 filter_btn_div" style="display:none">
                  <input type="button" name="filter_btn" class="common_black_button filter_btn" value="Load">
              </div>
            </div>
            <div class="col-lg-4">
              <a href="javascript:" class="common_black_button receipt_list_export" style="float:right">Export</a>
            </div>
      </div>
      <div class="col-lg-12"  style="padding: 0px;">
        <table class="table" id="client_expand" width="100%" style="margin-top: 10px;">
            <thead>
              <tr style="background: #fff;">
                  <th width="8%" style="text-align: left;">Date <i class="fa fa-sort date_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                  <th width="15%" style="text-align: left;">Debit Nominal & Description <i class="fa fa-sort debit_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                  <th width="8%" style="text-align: left;">Credit Nominal <i class="fa fa-sort credit_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                  <th width="15%" style="text-align: left;">Client Code <i class="fa fa-sort client_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                  <th width="15%" style="text-align: left;">Credit Nominal Description <i class="fa fa-sort des_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                  <th width="15%" style="text-align: left;">Comment <i class="fa fa-sort comment_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                  <th width="8%" style="text-align: right;">Amount <i class="fa fa-sort amount_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                  <th width="8%" style="text-align: right;">Journal ID <i class="fa fa-sort journal_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                  <th width="8%" style="text-align: left;">Status</th>
                  <th width="8%" style="text-align: left;">Action</th>
              </tr>
            </thead>                            
            <tbody id="receipt_list_tbody">
                <tr>
                  <td colspan="10" style="text-align: center">No Records Found</td>
                </tr>
            </tbody>
        </table>
      </div>
  </div>
    <!-- End  -->
  <div class="main-backdrop"><!-- --></div>
  <div class="modal_load"></div>
  <div class="modal_load_apply" style="text-align: center;">
    <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Receipts are Loaded.</p>
    <p style="font-size:18px;font-weight: 600;">Processing Receipts: <span id="apply_first"></span> of <span id="apply_last"></span></p>
  </div>
  <div class="modal_load_apply1" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until Reconcile Process to be Processed.</p>
  <p style="font-size:18px;font-weight: 600;">Processing : <span id="apply_first1"></span> of <span id="apply_last1"></span></p>
</div>
  <input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
  <input type="hidden" name="show_alert" id="show_alert" value="">
  <input type="hidden" name="pagination" id="pagination" value="1">

  <input type="hidden" name="nominal_code_sortoptions" id="nominal_code_sortoptions" value="asc">
  <input type="hidden" name="nominal_des_sortoptions" id="nominal_des_sortoptions" value="asc">

  <input type="hidden" name="allowable_code_sortoptions" id="allowable_code_sortoptions" value="asc">
  <input type="hidden" name="allowable_des_sortoptions" id="allowable_des_sortoptions" value="asc">

  <input type="hidden" name="date_sortoptions" id="date_sortoptions" value="asc">
  <input type="hidden" name="debit_sortoptions" id="debit_sortoptions" value="asc">
  <input type="hidden" name="credit_sortoptions" id="credit_sortoptions" value="asc">
  <input type="hidden" name="client_sortoptions" id="client_sortoptions" value="asc">
  <input type="hidden" name="des_sortoptions" id="des_sortoptions" value="asc">
  <input type="hidden" name="comment_sortoptions" id="comment_sortoptions" value="asc">
  <input type="hidden" name="amount_sortoptions" id="amount_sortoptions" value="asc">
</div>
<script>
$(window).keydown(function(e) {
  if($(e.target).hasClass('amount_add'))
  {
    if($(e.target).parents("tr").nextAll("tr").length == 0)
    {
      var keyCode = e.keyCode || e.which;
      if (keyCode == 9 || keyCode == 13) { 
        var date = $(e.target).parents("tr:first").find(".date_add").val();
        var debit = $(e.target).parents("tr:first").find(".debit_nominal").val();
        var credit = $(e.target).parents("tr:first").find(".credit_nominal").val();
        var client_code = $(e.target).parents("tr:first").find(".client_code_add").val();
        var des = $(e.target).parents("tr:first").find(".credit_descripion_add").val();
        var comment = $(e.target).parents("tr:first").find(".comment_add").val();
        var amount = $(e.target).val();
        var i = 0;
        if(date == "")
        {
          $(e.target).parents("tr:first").find(".date_add").css("border-color","#f00");
          i = i + 1;
        }
        if(debit == "")
        {
          $(e.target).parents("tr:first").find(".debit_nominal").css("border-color","#f00");
          i = i + 1;
        }
        if(credit == "")
        {
          $(e.target).parents("tr:first").find(".credit_nominal").css("border-color","#f00");
          i = i + 1;
        }
        if(des == "")
        {
          $(e.target).parents("tr:first").find(".credit_descripion_add").css("border-color","#f00");
          i = i + 1;
        }
        if(credit == "712" || credit == "813A")
        {
          if(client_code == "")
          {
            $(e.target).parents("tr:first").find(".client_code_add").css("border-color","#f00");
            i = i + 1;
          }
        }
        if(amount == "")
        {
          $(e.target).parents("tr:first").find(".amount_add").css("border-color","#f00");
          i = i + 1;
        }


        if(i == 0){
          $("body").addClass("loading");
          $(e.target).parents("tr:first").find(".amount_add").css("border-color","#ccc");
          var receipt_id = $(e.target).parents("tr:first").find(".hidden_receipt_id").val();
          if(receipt_id == "")
          {
            $.ajax({
              url:"<?php echo URL::to('user/save_receipt_details'); ?>",
              type:"post",
              data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount},
              success:function(result)
              {
                $(e.target).parents("tr:first").find(".hidden_receipt_id").val(result);
                var htmlval = $(e.target).parents("tbody").find("tr:last").html();
                $("#add_tbody").append("<tr>"+htmlval+"</tr>");
                $(e.target).parents("tbody").find("tr:last").find(".client_code_add").prop("disabled",true);
                $(e.target).parents("tbody").find("tr:last").find(".hidden_receipt_id").val("");
                $("body").removeClass("loading");
                ajax_function();
              }
            });
          }
          else{
            $("body").addClass("loading");
            $.ajax({
              url:"<?php echo URL::to('user/update_receipt_details'); ?>",
              type:"post",
              data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
              success:function(result)
              {
                var htmlval = $(e.target).parents("tbody").find("tr:last").html();
                $("#add_tbody").append("<tr>"+htmlval+"</tr>");
                $(e.target).parents("tbody").find("tr:last").find(".client_code_add").prop("disabled",true);
                $(e.target).parents("tbody").find("tr:last").find(".hidden_receipt_id").val("");
                $("body").removeClass("loading");
                ajax_function();
              }
            });
          }
        }
      }
    }
    else{
      var keyCode = e.keyCode || e.which;
      if (keyCode == 9 || keyCode == 13) { 
        var date = $(e.target).parents("tr:first").find(".date_add").val();
        var debit = $(e.target).parents("tr:first").find(".debit_nominal").val();
        var credit = $(e.target).parents("tr:first").find(".credit_nominal").val();
        var client_code = $(e.target).parents("tr:first").find(".client_code_add").val();
        var des = $(e.target).parents("tr:first").find(".credit_descripion_add").val();
        var comment = $(e.target).parents("tr:first").find(".comment_add").val();
        var amount = $(e.target).val();
        var i = 0;
        if(date == "")
        {
          $(e.target).parents("tr:first").find(".date_add").css("border-color","#f00");
          i = i + 1;
        }
        if(debit == "")
        {
          $(e.target).parents("tr:first").find(".debit_nominal").css("border-color","#f00");
          i = i + 1;
        }
        if(credit == "")
        {
          $(e.target).parents("tr:first").find(".credit_nominal").css("border-color","#f00");
          i = i + 1;
        }
        if(des == "")
        {
          $(e.target).parents("tr:first").find(".credit_descripion_add").css("border-color","#f00");
          i = i + 1;
        }
        if(credit == "712" || credit == "813A")
        {
          if(client_code == "")
          {
            $(e.target).parents("tr:first").find(".client_code_add").css("border-color","#f00");
            i = i + 1;
          }
        }
        if(amount == "")
        {
          $(e.target).parents("tr:first").find(".amount_add").css("border-color","#f00");
          i = i + 1;
        }


        if(i == 0){
          $(e.target).parents("tr:first").find(".amount_add").css("border-color","#ccc");
          var receipt_id = $(e.target).parents("tr:first").find(".hidden_receipt_id").val();
          if(receipt_id == "")
          {
            $.ajax({
              url:"<?php echo URL::to('user/save_receipt_details'); ?>",
              type:"post",
              data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount},
              success:function(result)
              {
                $(e.target).parents("tr:first").find(".hidden_receipt_id").val(result);
              }
            });
          }
          else{
            $.ajax({
              url:"<?php echo URL::to('user/update_receipt_details'); ?>",
              type:"post",
              data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
              success:function(result)
              {

              }
            });
          }
        }
        else{
          var receipt_id = $(e.target).parents("tr:first").find(".hidden_receipt_id").val();
          if(receipt_id != "")
          {
            $.ajax({
              url:"<?php echo URL::to('user/update_receipt_details'); ?>",
              type:"post",
              data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
              success:function(result)
              {

              }
            });
          }
        }
      }
    }
  }
});
function doneTyping (that) {
    var date = that.parents("tr:first").find(".date_add").val();
    var debit = that.parents("tr:first").find(".debit_nominal").val();
    var credit = that.parents("tr:first").find(".credit_nominal").val();
    var client_code = that.parents("tr:first").find(".client_code_add").val();
    var des = that.parents("tr:first").find(".credit_descripion_add").val();
    var comment = that.parents("tr:first").find(".comment_add").val();
    var amount = that.val();
    var i = 0;
    if(date == "")
    {
      i = i + 1;
    }
    if(debit == "")
    {
      i = i + 1;
    }
    if(credit == "")
    {
      i = i + 1;
    }
    if(des == "")
    {
      i = i + 1;
    }
    if(credit == "712" || credit == "813A")
    {
      if(client_code == "")
      {
        i = i + 1;
      }
    }
    if(amount == "")
    {
      i = i + 1;
    }


    if(i == 0){
      that.parents("tr:first").find(".amount_add").css("border-color","#ccc");
      var receipt_id = that.parents("tr:first").find(".hidden_receipt_id").val();
      if(receipt_id == "")
      {
        $.ajax({
          url:"<?php echo URL::to('user/save_receipt_details'); ?>",
          type:"post",
          data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount},
          success:function(result)
          {
            that.parents("tr:first").find(".hidden_receipt_id").val(result);
          }
        });
      }
      else{
        $.ajax({
          url:"<?php echo URL::to('user/update_receipt_details'); ?>",
          type:"post",
          data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
          success:function(result)
          {

          }
        });
      }
    }
    else{
      var receipt_id = that.parents("tr:first").find(".hidden_receipt_id").val();
      if(receipt_id != "")
      {
        $.ajax({
          url:"<?php echo URL::to('user/update_receipt_details'); ?>",
          type:"post",
          data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
          success:function(result)
          {

          }
        });
      }
    }
  }
function ajax_function()
{
  var typingTimer;                //timer identifier
  var doneTypingInterval = 1000;  //time in ms, 5 second for example
  var $input = $('.amount_add');

  $input.on('keyup', function () {
    var that = $(this);
    clearTimeout(typingTimer);
    typingTimer = setTimeout(doneTyping, doneTypingInterval,that);
  });
  //on keydown, clear the countdown 
  $input.on('keydown', function () {
    clearTimeout(typingTimer);
  });
  $('.amount_add').keypress(function (e) {    
      var charCode = (e.which) ? e.which : event.keyCode    
      if (String.fromCharCode(charCode).match(/[^0-9.,]/g))    
          return false;                        
  });   
  $('.amount_add').keypress(function (e) {    
      var charCode = (e.which) ? e.which : event.keyCode    
      if (String.fromCharCode(charCode).match(/[^0-9.,/]/g))    
          return false;                        
  });   
  $(".client_receipt").autocomplete({
    source: function(request, response) {        
      $.ajax({
        url:"<?php echo URL::to('user/receipt_common_client_search'); ?>",
        dataType: "json",
        data: {
            term : request.term
        },
        success: function(data) {
            response(data);
        }
      });
    },
    minLength: 1,
    select: function( event, ui ) {
      $("#hidden_client_id").val(ui.item.id);
    }
  });
  $(".client_code_add").autocomplete({
    source: function(request, response) {        
      $.ajax({
        url:"<?php echo URL::to('user/receipt_common_client_search'); ?>",
        dataType: "json",
        data: {
            term : request.term
        },
        success: function(data) {
            response(data);
        }
      });
    },
    minLength: 1,
    select: function( event, ui ) {
      $(this).parents("tr:first").find(".credit_descripion_add").val(ui.item.company);
      $(this).parents("tr:first").find(".client_code_add").css("border-color","#ccc");
      $(this).parents("tr:first").find(".credit_descripion_add").css("border-color","#ccc");
      var date = $(this).parents("tr:first").find(".date_add").val();
      var debit = $(this).parents("tr:first").find(".debit_nominal").val();
      var credit = $(this).parents("tr:first").find(".credit_nominal").val();
      var client_code = $(this).parents("tr:first").find(".client_code_add").val();
      var des = $(this).parents("tr:first").find(".credit_descripion_add").val();
      var comment = $(this).parents("tr:first").find(".comment_add").val();
      var amount = $(this).parents("tr:first").find(".amount_add").val();
      var i = 0;
      if(date == "")
      {
        i = i + 1;
      }
      if(debit == "")
      {
        i = i + 1;
      }
      if(credit == "")
      {
        i = i + 1;
      }
      if(des == "")
      {
        i = i + 1;
      }
      if(credit == "712" || credit == "813A")
      {
        if(client_code == "")
        {
          i = i + 1;
        }
      }
      if(amount == "")
      {
        i = i + 1;
      }


      if(i == 0){
        var receipt_id = $(this).parents("tr:first").find(".hidden_receipt_id").val();
        if(receipt_id == "")
        {
          $.ajax({
            url:"<?php echo URL::to('user/save_receipt_details'); ?>",
            type:"post",
            data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount},
            success:function(result)
            {
              $(this).parents("tr:first").find(".hidden_receipt_id").val(result);
            }
          });
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/update_receipt_details'); ?>",
            type:"post",
            data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
            success:function(result)
            {

            }
          });
        }
      }
      else{
          var receipt_id = $(this).parents("tr:first").find(".hidden_receipt_id").val();
          if(receipt_id != "")
          {
            $.ajax({
              url:"<?php echo URL::to('user/update_receipt_details'); ?>",
              type:"post",
              data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
              success:function(result)
              {

              }
            });
          }
      }
    }
  });
  $(".date_add").datetimepicker({
     defaultDate: "",       
     format: 'L',
     format: 'DD/MM/YYYY',
  });

  $(".from_receipt").datetimepicker({
     defaultDate: "",       
     format: 'L',
     format: 'DD/MM/YYYY',
  });
  $(".to_receipt").datetimepicker({
     defaultDate: "",       
     format: 'L',
     format: 'DD/MM/YYYY',
  });

  $(".date_add").on("dp.hide", function (e) {
      $(this).css("border-color","#ccc");

      var date = $(e.target).parents("tr:first").find(".date_add").val();
      var debit = $(e.target).parents("tr:first").find(".debit_nominal").val();
      var credit = $(e.target).parents("tr:first").find(".credit_nominal").val();
      var client_code = $(e.target).parents("tr:first").find(".client_code_add").val();
      var des = $(e.target).parents("tr:first").find(".credit_descripion_add").val();
      var comment = $(e.target).parents("tr:first").find(".comment_add").val();
      var amount = $(e.target).parents("tr:first").find(".amount_add").val();
      var i = 0;
      if(date == "")
      {
        i = i + 1;
      }
      if(debit == "")
      {
        i = i + 1;
      }
      if(credit == "")
      {
        i = i + 1;
      }
      if(des == "")
      {
        i = i + 1;
      }
      if(credit == "712" || credit == "813A")
      {
        if(client_code == "")
        {
          i = i + 1;
        }
      }
      if(amount == "")
      {
        i = i + 1;
      }


      if(i == 0){
        var receipt_id = $(e.target).parents("tr:first").find(".hidden_receipt_id").val();
        if(receipt_id == "")
        {
          $.ajax({
            url:"<?php echo URL::to('user/save_receipt_details'); ?>",
            type:"post",
            data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount},
            success:function(result)
            {
              $(e.target).parents("tr:first").find(".hidden_receipt_id").val(result);
            }
          });
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/update_receipt_details'); ?>",
            type:"post",
            data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
            success:function(result)
            {

            }
          });
        }
      }
      else{
        var receipt_id = $(e.target).parents("tr:first").find(".hidden_receipt_id").val();
        if(receipt_id != "")
        {
          $.ajax({
            url:"<?php echo URL::to('user/update_receipt_details'); ?>",
            type:"post",
            data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
            success:function(result)
            {

            }
          });
        }
      }
  });

  $(".comment_add").blur(function(e) {
      var date = $(e.target).parents("tr:first").find(".date_add").val();
      var debit = $(e.target).parents("tr:first").find(".debit_nominal").val();
      var credit = $(e.target).parents("tr:first").find(".credit_nominal").val();
      var client_code = $(e.target).parents("tr:first").find(".client_code_add").val();
      var des = $(e.target).parents("tr:first").find(".credit_descripion_add").val();
      var comment = $(e.target).parents("tr:first").find(".comment_add").val();
      var amount = $(e.target).parents("tr:first").find(".amount_add").val();
      var i = 0;
      if(date == "")
      {
        i = i + 1;
      }
      if(debit == "")
      {
        i = i + 1;
      }
      if(credit == "")
      {
        i = i + 1;
      }
      if(des == "")
      {
        i = i + 1;
      }
      if(credit == "712" || credit == "813A")
      {
        if(client_code == "")
        {
          i = i + 1;
        }
      }
      if(amount == "")
      {
        i = i + 1;
      }



      if(i == 0){
        var receipt_id = $(e.target).parents("tr:first").find(".hidden_receipt_id").val();

        if(receipt_id == "")
        {
          $.ajax({
            url:"<?php echo URL::to('user/save_receipt_details'); ?>",
            type:"post",
            data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount},
            success:function(result)
            {
              //alert(result);
              $(e.target).parents("tr:first").find(".hidden_receipt_id").val(result);
            }
          });
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/update_receipt_details'); ?>",
            type:"post",
            data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
            success:function(result)
            {

            }
          });
        }
      }
      else{
        var receipt_id = $(e.target).parents("tr:first").find(".hidden_receipt_id").val();
        if(receipt_id != "")
        {
          $.ajax({
            url:"<?php echo URL::to('user/update_receipt_details'); ?>",
            type:"post",
            data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
            success:function(result)
            {

            }
          });
        }
      }
  });
}
$(document).ready(function() {
    ajax_function();
});
function check_import_function(filename,height,round,highestrow)
{
  $.ajax({
      url: "<?php echo URL::to('user/check_import_csv_one'); ?>",
      type: 'get',
      dataType:"json",
      data: {filename:filename,height:height,round:round,highestrow:highestrow},
      success: function (data) {
        if(data['import_type_new'] == "1")
        {
          $("#apply_first").html(data['height']);
          $("#apply_last").html(data['highestrow']);
          $("#check_tbody").append(data['output']);
          check_import_function(data['filename'],data['height'],data['round'],data['highestrow']);
        }
        else{
          if(data['error_code'] == "1")
          {
            alert("Invalid file format");
            $("#new_file").prop("disabled",false);
            $(".load_import").prop("disabled",false);
            $("#import_new_file").prop("disabled",true);
            $("body").removeClass("loading_apply");
          }
          else if(data['error_code'] == "2")
          {
            alert("File not uploaded");
            $("#new_file").prop("disabled",false);
            $(".load_import").prop("disabled",false);
            $("#import_new_file").prop("disabled",true);
            $("body").removeClass("loading_apply");
          }
          else{
            $("#check_tbody").append(data['output']);
            var error_len = $(".error_tr").length;
            if(error_len > 0)
            {
              $("#new_file").prop("disabled",false);
              $(".load_import").prop("disabled",false);
              $("#import_new_file").prop("disabled",true);
              $("body").removeClass("loading_apply");
            }
            else{
              $("#new_file").prop("disabled",true);
              $(".load_import").prop("disabled",true);
              $("#import_new_file").prop("disabled",false);
              $("body").removeClass("loading_apply");
            }
          }
        }
      }
  });
}
var convertToNumber = function(value){
       return value.toLowerCase();
}
var parseconvertToNumber = function(value){
       return parseInt(value);
}
$(window).click(function(e) {
  var ascending = false;
  if($(e.target).hasClass('nominal_code_sort'))
  {
    var sort = $("#nominal_code_sortoptions").val();
    if(sort == 'asc')
    {
      $("#nominal_code_sortoptions").val('desc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.nominal_code_sort_val').html()) <
        convertToNumber($(b).find('.nominal_code_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#nominal_code_sortoptions").val('asc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.nominal_code_sort_val').html()) <
        convertToNumber($(b).find('.nominal_code_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#nominal_tbody').html(sorted);
  }
  if($(e.target).hasClass('allowable_code_sort'))
  {
    var sort = $("#allowable_code_sortoptions").val();
    if(sort == 'asc')
    {
      $("#allowable_code_sortoptions").val('desc');
      var sorted = $('#allowable_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.allowable_code_sort_val').html()) <
        convertToNumber($(b).find('.allowable_code_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#allowable_code_sortoptions").val('asc');
      var sorted = $('#allowable_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.allowable_code_sort_val').html()) <
        convertToNumber($(b).find('.allowable_code_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#allowable_tbody').html(sorted);
  }
  if($(e.target).hasClass('nominal_des_sort'))
  {
    var sort = $("#nominal_des_sortoptions").val();
    if(sort == 'asc')
    {
      $("#nominal_des_sortoptions").val('desc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.nominal_des_sort_val').html()) <
        convertToNumber($(b).find('.nominal_des_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#nominal_des_sortoptions").val('asc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.nominal_des_sort_val').html()) <
        convertToNumber($(b).find('.nominal_des_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#nominal_tbody').html(sorted);
  }
  if($(e.target).hasClass('allowable_des_sort'))
  {
    var sort = $("#allowable_des_sortoptions").val();
    if(sort == 'asc')
    {
      $("#allowable_des_sortoptions").val('desc');
      var sorted = $('#allowable_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.allowable_des_sort_val').html()) <
        convertToNumber($(b).find('.allowable_des_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#allowable_des_sortoptions").val('asc');
      var sorted = $('#allowable_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.allowable_des_sort_val').html()) <
        convertToNumber($(b).find('.allowable_des_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#allowable_tbody').html(sorted);
  }
  if($(e.target).hasClass('date_sort'))
  {
    var sort = $("#date_sortoptions").val();
    if(sort == 'asc')
    {
      $("#date_sortoptions").val('desc');
      var sorted = $('#receipt_list_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.date_sort_val').html()) <
        parseconvertToNumber($(b).find('.date_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#date_sortoptions").val('asc');
      var sorted = $('#receipt_list_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.date_sort_val').html()) <
        parseconvertToNumber($(b).find('.date_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_list_tbody').html(sorted);
  }
  if($(e.target).hasClass('debit_sort'))
  {
    var sort = $("#debit_sortoptions").val();
    if(sort == 'asc')
    {
      $("#debit_sortoptions").val('desc');
      var sorted = $('#receipt_list_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.debit_sort_val').html()) <
        convertToNumber($(b).find('.debit_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#debit_sortoptions").val('asc');
      var sorted = $('#receipt_list_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.debit_sort_val').html()) <
        convertToNumber($(b).find('.debit_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_list_tbody').html(sorted);
  }
  if($(e.target).hasClass('credit_sort'))
  {
    var sort = $("#credit_sortoptions").val();
    if(sort == 'asc')
    {
      $("#credit_sortoptions").val('desc');
      var sorted = $('#receipt_list_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.credit_sort_val').html()) <
        convertToNumber($(b).find('.credit_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#credit_sortoptions").val('asc');
      var sorted = $('#receipt_list_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.credit_sort_val').html()) <
        convertToNumber($(b).find('.credit_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_list_tbody').html(sorted);
  }
  if($(e.target).hasClass('client_sort'))
  {
    var sort = $("#client_sortoptions").val();
    if(sort == 'asc')
    {
      $("#client_sortoptions").val('desc');
      var sorted = $('#receipt_list_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_sort_val').html()) <
        convertToNumber($(b).find('.client_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#client_sortoptions").val('asc');
      var sorted = $('#receipt_list_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_sort_val').html()) <
        convertToNumber($(b).find('.client_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_list_tbody').html(sorted);
  }
  if($(e.target).hasClass('des_sort'))
  {
    var sort = $("#des_sortoptions").val();
    if(sort == 'asc')
    {
      $("#des_sortoptions").val('desc');
      var sorted = $('#receipt_list_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.des_sort_val').html()) <
        convertToNumber($(b).find('.des_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#des_sortoptions").val('asc');
      var sorted = $('#receipt_list_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.des_sort_val').html()) <
        convertToNumber($(b).find('.des_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_list_tbody').html(sorted);
  }
  if($(e.target).hasClass('comment_sort'))
  {
    var sort = $("#comment_sortoptions").val();
    if(sort == 'asc')
    {
      $("#comment_sortoptions").val('desc');
      var sorted = $('#receipt_list_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.comment_sort_val').html()) <
        convertToNumber($(b).find('.comment_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#comment_sortoptions").val('asc');
      var sorted = $('#receipt_list_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.comment_sort_val').html()) <
        convertToNumber($(b).find('.comment_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_list_tbody').html(sorted);
  }
  if($(e.target).hasClass('amount_sort'))
  {
    var sort = $("#amount_sortoptions").val();
    if(sort == 'asc')
    {
      $("#amount_sortoptions").val('desc');
      var sorted = $('#receipt_list_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.amount_sort_val').html()) <
        parseconvertToNumber($(b).find('.amount_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#amount_sortoptions").val('asc');
      var sorted = $('#receipt_list_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.amount_sort_val').html()) <
        parseconvertToNumber($(b).find('.amount_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_list_tbody').html(sorted);
  }
  if($(e.target).hasClass('load_import'))
  {
    var file = $("#new_file").val();
    if(file == "")
    {
      alert("Please select the CSV file to import");
    }
    else{
      $("body").addClass("loading");
      var formData = $("#import_form").submit(function (e) {
        return;
      });
      var formData = new FormData(formData[0]);
      $.ajax({
          url: "<?php echo URL::to('user/check_import_csv'); ?>",
          type: 'POST',
          dataType:"json",
          data: formData,
          success: function (data) {
            if(data['import_type_new'] == "1")
            {
              $("body").addClass("loading_apply");
              $("body").removeClass("loading");

              $("#apply_first").html(data['height']);
              $("#apply_last").html(data['highestrow']);
              $("#check_tbody").html(data['output']);
              check_import_function(data['filename'],data['height'],data['round'],data['highestrow']);
            }
            else{
              if(data['error_code'] == "1")
              {
                alert("Invalid file format");
                $("#new_file").prop("disabled",false);
                $(".load_import").prop("disabled",false);
                $("#import_new_file").prop("disabled",true);
                $("body").removeClass("loading");
              }
              else if(data['error_code'] == "2")
              {
                alert("File not uploaded");
                $("#new_file").prop("disabled",false);
                $(".load_import").prop("disabled",false);
                $("#import_new_file").prop("disabled",true);
                $("body").removeClass("loading");
              }
              else{
                $("#check_tbody").html(data['output']);
                var error_len = $(".error_tr").length;
                if(error_len > 0)
                {
                  $("#new_file").prop("disabled",false);
                  $(".load_import").prop("disabled",false);
                  $("#import_new_file").prop("disabled",true);
                  $("body").removeClass("loading");
                }
                else{
                  $("#new_file").prop("disabled",true);
                  $(".load_import").prop("disabled",true);
                  $("#import_new_file").prop("disabled",false);
                  $("body").removeClass("loading");
                }
              }
            }
          },
          cache: false,
          contentType: false,
          processData: false
      });
    }
  }
  if($(e.target).hasClass('settings_btn'))
  {
    $("#receipt_settings_modal").modal("show");
  }
  if($(e.target).hasClass("code_td"))
  {
    if($(e.target).parents(".code_tr").hasClass('active_code_tr'))
    {
      $(e.target).parents(".code_tr").removeClass("active_code_tr");
    }
    else{
      $(e.target).parents(".code_tr").addClass("active_code_tr");
      var code = $(e.target).attr("data-element");
    }
  }
  if($(e.target).hasClass('add_to_allowable_list'))
  {
    $("body").addClass("loading");
    var code_length = $(".active_code_tr").length;
    if(code_length == 0)
    {
      alert("Please select the Nominal code to move to the allowable list");
    }
    else{
      var code = '';
      $(".active_code_tr").each(function() {
        var codee = $(this).find(".code_td:first").attr("data-element");
        if(code == ""){
          code = codee;
        }
        else{
          code = code+','+codee;
        }
      });
      $.ajax({
        url:"<?php echo URL::to('user/move_to_allowable_list'); ?>",
        type:"post",
        data:{code:code},
        success:function(result)
        {
          var codes = code.split(",");
          $.each(codes, function(index,value){
            $("#code_tr_"+value).detach();
          });
          var allowable_length = $("#allowable_tbody").find(".no_records").length;
          if(allowable_length > 0)
          {
            $("#allowable_tbody").html(result);
            $("#hidden_nominal_code_id").val("");
            $("body").removeClass("loading");
          }
          else{
            $("#allowable_tbody").append(result);
            $("#hidden_nominal_code_id").val("");
            $("body").removeClass("loading");
          }
        }
      })
    }
  }
  if($(e.target).hasClass('add_receipt_export'))
  {
    var ids = '';
    $(".hidden_receipt_id").each(function() {
      var id = $(this).val();
      if(id != ''){
        if(ids == "")
        {
          ids = id;
        }
        else{
          ids = ids+','+id;
        }
      }
      
    });
    if(ids != "")
    {
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/add_receipt_export_csv'); ?>",
        type:"post",
        data:{ids:ids},
        success:function(result)
        {
          $("body").removeClass("loading");
          SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
        }
      });
    }
    else{
      alert("Please fill the receipt and then proceed with export option");
    }
  }
  if($(e.target).hasClass('receipt_list_export'))
  {
    $("body").addClass("loading");
    var filter = $(".filter_receipts").val();
    var from = $(".from_receipt").val();
    var to = $(".to_receipt").val();
    var client = $("#hidden_client_id").val();
    var debit = $(".debit_nominal_receipt").val();
    var credit = $(".credit_nominal_receipt").val();

    if(filter == "")
    {
      alert("Please Load the Receipt List and the Proceed with Export option");
      $("body").removeClass("loading");
      return false;
    }
    if(filter == "2")
    {
      if(from == "" || to == "")
      {
        alert("Please select the from and to date to export the receipt list");
        $("body").removeClass("loading");
        return false;
      }
    }
    if(filter == "3")
    {
      if(client == "")
      {
        alert("Please select the client to export the receipt list");
        $("body").removeClass("loading");
        return false;
      }
    }
    if(filter == "4")
    {
      if(debit == "")
      {
        alert("Please select the debit nominal to export the receipt list");
        $("body").removeClass("loading");
        return false;
      }
    }
    if(filter == "5")
    {
      if(credit == "")
      {
        alert("Please select the credit nominal to export the receipt list");
        $("body").removeClass("loading");
        return false;
      }
    }
    $.ajax({
      url:"<?php echo URL::to('user/export_load_receipt'); ?>",
      type:"post",
      data:{filter:filter,from:from,to:to,client:client,debit:debit,credit:credit},
      success:function(result)
      {
        $("body").removeClass("loading");
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
      }
    })
  }
  if($(e.target).hasClass('edit_receipt'))
  {
    alert("Feature Not Available");
  }
  if($(e.target).hasClass('delete_receipt'))
  {
    alert("Feature Not Available");
  }
  if($(e.target).hasClass('change_to_unhold'))
  {
    // var id = $(e.target).attr("data-element");
    // $.ajax({
    //   url:"<?php echo URL::to('user/change_to_unhold'); ?>",
    //   type:"post",
    //   data:{id:id},
    //   success:function(result)
    //   {
    //     $(e.target).addClass('unhold_receipt');
    //     $(e.target).removeClass('change_to_unhold');
    //     $(e.target).parents("tr").find("td").css("color","#000");
    //     $(e.target).parents("tr").find("td").css("font-weight","300");
    //     $(e.target).html("Unhold")
    //   }
    // })
    var nominal_code = $(e.target).attr("data-nominal");
    $.ajax({
      url:"<?php echo URL::to('user/check_bank_nominal_code'); ?>",
      type:"post",
      data:{nominal_code:nominal_code},
      success:function(result){
        if(result == 0) { 
          alert("There is no valid Bank Account created for this Debit Nominal code.");
        }else{
          var r = confirm("Transaction not yet cleared or Reconciled in the bank account. Reconcile now?");
          if(r){
            var value = btoa(result);
            $.ajax({
              url:"<?php echo URL::to('user/finance_get_bank_details'); ?>",
              type:"post",
              dataType:"json",
              data:{id:value},
              success:function(result){

                $(".select_reconcile_bank").val(value);

                $(".td_bank_name").html(result['bank_name']);
                $(".tb_ac_name").html(result['account_name']);
                $(".td_ac_number").html(result['account_number']);
                $(".td_ac_description").html(result['description']);
                $(".td_nominal_code").html(result['nominal_code']);

                $(".table_bank_details").show();
                $(".reconcilation_section").hide();
                $(".transactions_section").hide();
                $(".reconcile_modal").modal("show");
                
                
              }
            });
          }
        }
      }
    })
  }
  if($(e.target).hasClass('filter_btn'))
  {
    $("body").addClass("loading");
    var filter = $(".filter_receipts").val();
    var from = $(".from_receipt").val();
    var to = $(".to_receipt").val();
    var client = $("#hidden_client_id").val();
    var debit = $(".debit_nominal_receipt").val();
    var credit = $(".credit_nominal_receipt").val();

    if(filter == "2")
    {
      if(from == "" || to == "")
      {
        alert("Please select the from and to date to load the receipt");
        $("body").removeClass("loading");
        return false;
      }
    }
    if(filter == "3")
    {
      if(client == "")
      {
        alert("Please select the client to load the receipt");
        $("body").removeClass("loading");
        return false;
      }
    }
    if(filter == "4")
    {
      if(debit == "")
      {
        alert("Please select the debit nominal to load the receipt");
        $("body").removeClass("loading");
        return false;
      }
    }
    if(filter == "5")
    {
      if(credit == "")
      {
        alert("Please select the credit nominal to load the receipt");
        $("body").removeClass("loading");
        return false;
      }
    }
    
    $.ajax({
      url:"<?php echo URL::to('user/load_receipt'); ?>",
      type:"post",
      data:{filter:filter,from:from,to:to,client:client,debit:debit,credit:credit},
      success:function(result)
      {
        $("#receipt_list_tbody").html(result);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('add_receipts_btn'))
  {
    if($(e.target).hasClass('hide_add_receipt'))
    {
      $(".add_receipt_div").hide();
      $(e.target).html("Add Receipts");
      $(e.target).removeClass("hide_add_receipt");
    }
    else{
      $(".add_receipt_div").show();
      $(e.target).html("Hide Add Receipts");
      $(e.target).addClass("hide_add_receipt");

      var htmlval = $("#add_tbody").find("tr:last").html();
      $("#add_tbody").html("<tr>"+htmlval+"</tr>");
      $(".hidden_receipt_id").val("");
      $("#add_tbody").find("input").css("border-color","#ccc");
      $("#add_tbody").find("select").css("border-color","#ccc");
      $("#add_tbody").find("input").val("");
      $("#add_tbody").find("select").val("");
      ajax_function();
    }
    $(".alert-info").detach();
  }
  if($(e.target).hasClass('receipt_import'))
  {    
    $("#import_receipts").modal("show");

    $("#new_file").prop("disabled",false);
    $(".load_import").prop("disabled",false);
    $("#import_new_file").prop("disabled",true);
    $("#new_file").val("");
    $("#check_tbody").html("");
  }
});
$(window).change(function(e) {
  if($(e.target).hasClass('filter_receipts'))
  {
    var value = $(e.target).val();
    if(value == "1")
    {
      $(".specific_date_div").hide();
      $(".client_div").hide();
      $(".debit_nominal_div").hide();
      $(".credit_nominal_div").hide();
      $(".filter_btn_div").show();
    }
    if(value == "2")
    {
      $(".specific_date_div").show();
      $(".client_div").hide();
      $(".debit_nominal_div").hide();
      $(".credit_nominal_div").hide();
      $(".filter_btn_div").show();
    }
    if(value == "3")
    {
      $(".specific_date_div").hide();
      $(".client_div").show();
      $(".debit_nominal_div").hide();
      $(".credit_nominal_div").hide();
      $(".filter_btn_div").show();
    }
    if(value == "4")
    {
      $(".specific_date_div").hide();
      $(".client_div").hide();
      $(".debit_nominal_div").show();
      $(".credit_nominal_div").hide();
      $(".filter_btn_div").show();
    }
    if(value == "5")
    {
      $(".specific_date_div").hide();
      $(".client_div").hide();
      $(".debit_nominal_div").hide();
      $(".credit_nominal_div").show();
      $(".filter_btn_div").show();
    }
    if(value == "6")
    {
      $(".specific_date_div").hide();
      $(".client_div").hide();
      $(".debit_nominal_div").hide();
      $(".credit_nominal_div").hide();
      $(".filter_btn_div").show();
    }
    if(value == "7")
    {
      $(".specific_date_div").hide();
      $(".client_div").hide();
      $(".debit_nominal_div").hide();
      $(".credit_nominal_div").hide();
      $(".filter_btn_div").show();
    }
  }
  if($(e.target).hasClass('debit_nominal'))
  {
    var code = $(e.target).val();
    if(code == "")
    {
      alert("Please select the Debit Nominal Code");
      $(e.target).css("border-color","#f00");
    }
    else{
      $(e.target).css("border-color","#ccc");
    }

      var date = $(e.target).parents("tr:first").find(".date_add").val();
      var debit = $(e.target).parents("tr:first").find(".debit_nominal").val();
      var credit = $(e.target).parents("tr:first").find(".credit_nominal").val();
      var client_code = $(e.target).parents("tr:first").find(".client_code_add").val();
      var des = $(e.target).parents("tr:first").find(".credit_descripion_add").val();
      var comment = $(e.target).parents("tr:first").find(".comment_add").val();
      var amount = $(e.target).parents("tr:first").find(".amount_add").val();
      var i = 0;
      if(date == "")
      {
        i = i + 1;
      }
      if(debit == "")
      {
        i = i + 1;
      }
      if(credit == "")
      {
        i = i + 1;
      }
      if(des == "")
      {
        i = i + 1;
      }
      if(credit == "712" || credit == "813A")
      {
        if(client_code == "")
        {
          i = i + 1;
        }
      }
      if(amount == "")
      {
        i = i + 1;
      }


      if(i == 0){
        var receipt_id = $(e.target).parents("tr:first").find(".hidden_receipt_id").val();
        if(receipt_id == "")
        {
          $.ajax({
            url:"<?php echo URL::to('user/save_receipt_details'); ?>",
            type:"post",
            data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount},
            success:function(result)
            {
              $(e.target).parents("tr:first").find(".hidden_receipt_id").val(result);
            }
          });
        }
        else{
          $.ajax({
            url:"<?php echo URL::to('user/update_receipt_details'); ?>",
            type:"post",
            data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
            success:function(result)
            {

            }
          });
        }
      }
      else{
        var receipt_id = $(e.target).parents("tr:first").find(".hidden_receipt_id").val();
        if(receipt_id != "")
        {
          $.ajax({
            url:"<?php echo URL::to('user/update_receipt_details'); ?>",
            type:"post",
            data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
            success:function(result)
            {

            }
          });
        }
      }
  }
  if($(e.target).hasClass('credit_nominal'))
  {
      var code = $(e.target).val();
      if(code == "")
      {
        alert("Please select the Credit Nominal Code");
        $(e.target).css("border-color","#f00");
        $(e.target).parents("tr:first").find(".client_code_add").prop("disabled",true);
        $(e.target).parents("tr:first").find(".client_code_add").val("");
        $(e.target).parents("tr:first").find(".credit_descripion_add").val("");

        var date = $(e.target).parents("tr:first").find(".date_add").val();
        var debit = $(e.target).parents("tr:first").find(".debit_nominal").val();
        var credit = $(e.target).parents("tr:first").find(".credit_nominal").val();
        var client_code = $(e.target).parents("tr:first").find(".client_code_add").val();
        var des = $(e.target).parents("tr:first").find(".credit_descripion_add").val();
        var comment = $(e.target).parents("tr:first").find(".comment_add").val();
        var amount = $(e.target).parents("tr:first").find(".amount_add").val();
        var i = 0;
        if(date == "")
        {
          i = i + 1;
        }
        if(debit == "")
        {
          i = i + 1;
        }
        if(credit == "")
        {
          i = i + 1;
        }
        if(des == "")
        {
          i = i + 1;
        }
        if(credit == "712" || credit == "813A")
        {
          if(client_code == "")
          {
            i = i + 1;
          }
        }
        if(amount == "")
        {
          i = i + 1;
        }


        if(i == 0){
          var receipt_id = $(e.target).parents("tr:first").find(".hidden_receipt_id").val();
          if(receipt_id == "")
          {
            $.ajax({
              url:"<?php echo URL::to('user/save_receipt_details'); ?>",
              type:"post",
              data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount},
              success:function(resultt)
              {
                $(e.target).parents("tr:first").find(".hidden_receipt_id").val(resultt);
              }
            });
          }
          else{
            $.ajax({
              url:"<?php echo URL::to('user/update_receipt_details'); ?>",
              type:"post",
              data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
              success:function(resultt)
              {

              }
            });
          }
        }
        else{
          var receipt_id = $(e.target).parents("tr:first").find(".hidden_receipt_id").val();
          if(receipt_id != "")
          {
            $.ajax({
              url:"<?php echo URL::to('user/update_receipt_details'); ?>",
              type:"post",
              data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
              success:function(result)
              {

              }
            });
          }
        }
      }
      else{
        if(code == "712" || code == "813A")
        {
          $(e.target).parents("tr:first").find(".client_code_add").val("");
          $(e.target).parents("tr:first").find(".client_code_add").prop("disabled",false);
          $(e.target).parents("tr:first").find(".credit_descripion_add").val("");
          $(e.target).css("border-color","#ccc");

          var date = $(e.target).parents("tr:first").find(".date_add").val();
          var debit = $(e.target).parents("tr:first").find(".debit_nominal").val();
          var credit = $(e.target).parents("tr:first").find(".credit_nominal").val();
          var client_code = $(e.target).parents("tr:first").find(".client_code_add").val();
          var des = $(e.target).parents("tr:first").find(".credit_descripion_add").val();
          var comment = $(e.target).parents("tr:first").find(".comment_add").val();
          var amount = $(e.target).parents("tr:first").find(".amount_add").val();
          var i = 0;
          if(date == "")
          {
            i = i + 1;
          }
          if(debit == "")
          {
            i = i + 1;
          }
          if(credit == "")
          {
            i = i + 1;
          }
          if(des == "")
          {
            i = i + 1;
          }
          if(credit == "712" || credit == "813A")
          {
            if(client_code == "")
            {
              i = i + 1;
            }
          }
          if(amount == "")
          {
            i = i + 1;
          }


          if(i == 0){
            var receipt_id = $(e.target).parents("tr:first").find(".hidden_receipt_id").val();
            if(receipt_id == "")
            {
              $.ajax({
                url:"<?php echo URL::to('user/save_receipt_details'); ?>",
                type:"post",
                data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount},
                success:function(resultt)
                {
                  $(e.target).parents("tr:first").find(".hidden_receipt_id").val(resultt);
                }
              });
            }
            else{
              $.ajax({
                url:"<?php echo URL::to('user/update_receipt_details'); ?>",
                type:"post",
                data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
                success:function(resultt)
                {

                }
              });
            }
          }
          else{
            var receipt_id = $(e.target).parents("tr:first").find(".hidden_receipt_id").val();
            if(receipt_id != "")
            {
              $.ajax({
                url:"<?php echo URL::to('user/update_receipt_details'); ?>",
                type:"post",
                data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
                success:function(result)
                {

                }
              });
            }
          }
        }
        else
        {
          $(e.target).parents("tr:first").find(".client_code_add").val("");
          $(e.target).css("border-color","#ccc");
          $(e.target).parents("tr:first").find(".credit_descripion_add").css("border-color","#ccc");

          $(e.target).parents("tr:first").find(".client_code_add").prop("disabled",true);
          $.ajax({
            url:"<?php echo URL::to('user/get_nominal_code_description'); ?>",
            type:"post",
            data:{code:code},
            success:function(result)
            {
              $(e.target).parents("tr:first").find(".credit_descripion_add").val(result);

              var date = $(e.target).parents("tr:first").find(".date_add").val();
              var debit = $(e.target).parents("tr:first").find(".debit_nominal").val();
              var credit = $(e.target).parents("tr:first").find(".credit_nominal").val();
              var client_code = $(e.target).parents("tr:first").find(".client_code_add").val();
              var des = $(e.target).parents("tr:first").find(".credit_descripion_add").val();
              var comment = $(e.target).parents("tr:first").find(".comment_add").val();
              var amount = $(e.target).parents("tr:first").find(".amount_add").val();
              var i = 0;
              if(date == "")
              {
                i = i + 1;
              }
              if(debit == "")
              {
                i = i + 1;
              }
              if(credit == "")
              {
                i = i + 1;
              }
              if(des == "")
              {
                i = i + 1;
              }
              if(credit == "712" || credit == "813A")
              {
                if(client_code == "")
                {
                  i = i + 1;
                }
              }
              if(amount == "")
              {
                i = i + 1;
              }


              if(i == 0){
                var receipt_id = $(e.target).parents("tr:first").find(".hidden_receipt_id").val();
                if(receipt_id == "")
                {
                  $.ajax({
                    url:"<?php echo URL::to('user/save_receipt_details'); ?>",
                    type:"post",
                    data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount},
                    success:function(resultt)
                    {
                      $(e.target).parents("tr:first").find(".hidden_receipt_id").val(resultt);
                    }
                  });
                }
                else{
                  $.ajax({
                    url:"<?php echo URL::to('user/update_receipt_details'); ?>",
                    type:"post",
                    data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
                    success:function(resultt)
                    {

                    }
                  });
                }
              }
              else{
                var receipt_id = $(e.target).parents("tr:first").find(".hidden_receipt_id").val();
                if(receipt_id != "")
                {
                  $.ajax({
                    url:"<?php echo URL::to('user/update_receipt_details'); ?>",
                    type:"post",
                    data:{date:date,debit:debit,credit:credit,des:des,client_code:client_code,comment:comment,amount:amount,receipt_id:receipt_id},
                    success:function(result)
                    {

                    }
                  });
                }
              }
            }
          })
        }
      }
  }
})
$(window).change(function(e){

if($(e.target).hasClass('input_balance_bank')){
  var input_balance_bank = $(e.target).val();
  var input_total_outstanding = $(".refresh_input_outstanding").val();
  var input_bala_transaction = $(".balance_tran_class").val();

  $.ajax({
      url:"<?php echo URL::to('user/balance_per_bank'); ?>",
      type:"post",
      dataType:"json",
      data:{input_balance_bank:input_balance_bank, input_total_outstanding:input_total_outstanding, input_bala_transaction:input_bala_transaction},
      success:function(result){

        $(".input_close_balance").val(result['close_balance']);
        $(".class_close_balance").html(result['close_balance_span']);

        $(".input_difference").val(result['diffence']);
        $(".class_difference").html(result['diffence_span']);
        
        
      }
    }); 

  

}


})
$(window).dblclick(function(e){
if($(e.target).hasClass('single_accept')){
  var type = $(e.target).attr("type");
  var id = $(e.target).attr("data-element");
  var receipt_id = $(".receipt_id").val();
  var payment_id = $(".payment_id").val();

  $.ajax({
      url:"<?php echo URL::to('user/finance_bank_single_accept'); ?>",
      type:"post",
      dataType:"json",
      data:{id:id, type:type, receipt_id:receipt_id, payment_id:payment_id},
      success:function(result){
        if(type == 1){
          $("#receipt_out_"+id).html(result['outstanding']);
          $("#receipt_out_"+id).css({"color":"blue"});

          $("#receipt_clear_"+id).html(result['clearance_date']);
          $("#receipt_clear_"+id).css({"color":"orange", "font-weight":"bold"});
          $("#receipt_clear_"+id).addClass('process_journal');
        }
        else{
          $("#payment_out_"+id).html(result['outstanding']);
          $("#payment_out_"+id).css({"color":"blue"});

          $("#payment_clear_"+id).html(result['clearance_date']);
          $("#payment_clear_"+id).css({"color":"orange", "font-weight":"bold"});
          $("#payment_clear_"+id).addClass('process_journal');
        }
        $(".class_total_outstanding").css({"color":"orange", "font-weight":"bold"});
        $(".class_total_outstanding_refresh").addClass('orange_value_refresh');

        $(".class_total_outstanding").html(result['total_outstanding_html']);
        $(".input_total_outstanding").val(result['total_outstanding']);
      }
  })
}
})
function accept_reconciliation(count)
{
  var id = $(".process_journal").eq(0).attr("data-element");
  var bank_id = $(".select_reconcile_bank").val();
  if($(".process_journal").eq(0).hasClass('receipt_clear'))
  {
    var type = '1';
  }
  else{
    var type = '2';
  }
  $.ajax({
    url:"<?php echo URL::to('user/create_journal_reconciliation'); ?>",
    type:"post",
    data:{id:id,type:type,bank_id:bank_id},
    success:function(result){
        
        if(type == '1')
        {
          $("#receipt_clear_"+id).removeClass('process_journal');
          $("#receipt_clear_"+id).parents("tr").find(".journal_td").html('<a href="javascript:" class="journal_id_viewer" data-element="'+result+'">'+result+'</a>');
        }
        else{
          $("#payment_clear_"+id).removeClass('process_journal');
          $("#payment_clear_"+id).parents("tr").find(".journal_td").html('<a href="javascript:" class="journal_id_viewer" data-element="'+result+'">'+result+'</a>')
        }
        var countval = count + 1;
        if($(".process_journal").eq(0).length > 0)
        {
          accept_reconciliation(countval);
          $("#apply_first").html(countval);
        }
        else{
          $("body").removeClass('loading_apply')
          $("#apply_first").html('0');
        }
    }
  })
}
$(window).click(function(e){
if($(e.target).hasClass('accept_all_button')){
  var pop = confirm('You are about to set the Clearance Date of All Transactions (Payments/receipts and General Journals) to the Transaction Date.  This will lock the Bank Account and Value on the Payments and Receipts systems for these transactions and you will not be able to change them.  Do you Want to Continue? ');
  if(pop){
    var receipt_id = $(".receipt_id").val();
    var payment_id = $(".payment_id").val();
    var select_bank = $(".select_reconcile_bank").val();

    $.ajax({
      url:"<?php echo URL::to('user/finance_bank_all_accept'); ?>",
      type:"post",
      dataType:"json",
      data:{receipt_id:receipt_id, payment_id:payment_id, select_bank:select_bank},
      success:function(result){
        $(".tbody_transaction").html(result['transactions']);
        $(".class_total_outstanding").html(result['total_outstanding']);
        $(".input_total_outstanding").val(result['total_outstanding']);
        $(".class_total_outstanding").css({"color":"orange", "font-weight":"bold"});
        
      }
  })

  }
  else{
    console.log('false');
  }
}
if($(e.target).hasClass('reconcile_load')){
  var value = $(".select_reconcile_bank").val();
  $.ajax({
    url:"<?php echo URL::to('user/finance_reconcile_load'); ?>",
    type:"post",
    dataType:"json",
    data:{id:value},
    success:function(result){
      $(".receipt_id").val(result['receipt_ids']);
      $(".payment_id").val(result['payment_ids']);
      $(".balance_tran_class").val(result['balance_transaction']);
      $(".input_total_outstanding").val(result['outstanding']);

      $(".class_total_outstanding").html(result['outstanding_html']);
      $(".class_total_outstanding_html").html(result['outstanding_html']);


      $(".tbody_transaction").html(result['transactions']);
      $(".tbody_reconcilation").html(result['reconcilation']);

      $(".transactions_section").show();
      $(".reconcilation_section").show();

      $(".date_balance_bank").datetimepicker({
         defaultDate: "",
         format: 'L',
         format: 'DD/MM/YYYY',
      });

      
    }
  });    
}
if($(e.target).hasClass('refresh_button')){
  var input_total_outstanding = $(".input_total_outstanding").val();
  var input_balance_bank = $(".input_balance_bank").val();
  var input_bala_transaction = $(".balance_tran_class").val();

  $.ajax({
      url:"<?php echo URL::to('user/finance_bank_refresh'); ?>",
      type:"post",
      dataType:"json",
      data:{input_total_outstanding:input_total_outstanding, input_balance_bank:input_balance_bank,input_bala_transaction:input_bala_transaction},
      success:function(result){

        $(".input_close_balance").val(result['close_balance']);
        $(".class_close_balance").html(result['close_balance_span']);

        $(".input_difference").val(result['diffence']);
        $(".class_difference").html(result['diffence_span']);

        $(".refresh_input_outstanding").val(result['outstanding']);
        $(".class_total_outstanding_refresh").html(result['outstanding_span']);
        $(".class_total_outstanding_refresh").removeClass('orange_value_refresh');

      }
        
  })
}
if($(e.target).hasClass('accept_reconciliation')){
  if($(".class_total_outstanding_refresh").hasClass('orange_value_refresh'))
  {
    alert("You can not accept the Reconciliation while there are Differences Due to updated Cleared Items and the Bank Statement Balance is Selected");
    return false;
  }
  if(($(".input_balance_bank").val() == '') || ($(".input_balance_bank").val() == '0') || ($(".input_balance_bank").val() == '0.00')){
    alert("You can not accept the Reconciliation while there are Differences Due to updated Cleared Items and the Bank Statement Balance is Selected");
    return false;
  }

  var countval = $(".process_journal").length;
  if(countval > 0)
  {
    $("body").addClass('loading_apply1')
    $("#apply_last1").html(countval);
    accept_reconciliation(0);
  }
}
if($(e.target).hasClass('reconciliation_pdf'))
{
  
  var bank_id = atob($(".select_reconcile_bank").val());
  var input = $(".input_balance_bank").val();
  var date = $(".date_balance_bank").val();

  var tor = $(".refresh_input_outstanding").val();
  var cb = $(".class_close_balance").html();
  var cd = $(".class_difference").html();

  if(cb == ""){
    alert("The Closing Balance is Empty so you cant Generate the Pdf File.");
    return false;
  }
  if(cd == ""){
    alert("The Difference is Empty so you cant Generate the Pdf File.");
    return false;
  }

  var receipt_id = $(".receipt_id").val();
  var payment_id = $(".payment_id").val();

  $("body").addClass("loading");
  $.ajax({
    url:"<?php echo URL::to('user/generate_reconcile_pdf'); ?>",
    type:"post",
    data:{bank_id:bank_id,input:input,date:date,tor:tor,cb:cb,cd:cd,receipt_id:receipt_id,payment_id:payment_id},
    success:function(result){
      SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
        $("body").removeClass("loading");
    }
  })
}
if($(e.target).hasClass('reconciliation_csv'))
{
  
  var bank_id = atob($(".select_reconcile_bank").val());
  var input = $(".input_balance_bank").val();
  var date = $(".date_balance_bank").val();

  var tor = $(".refresh_input_outstanding").val();
  var cb = $(".class_close_balance ").html();
  var cd = $(".class_difference").html();

  if(cb == ""){
    alert("The Closing Balance is Empty so you cant Generate the Pdf File.");
    return false;
  }
  if(cd == ""){
    alert("The Difference is Empty so you cant Generate the Pdf File.");
    return false;
  }
  
  var receipt_id = $(".receipt_id").val();
  var payment_id = $(".payment_id").val();

  $("body").addClass("loading");
  $.ajax({
    url:"<?php echo URL::to('user/generate_reconcile_csv'); ?>",
    type:"post",
    data:{bank_id:bank_id,input:input,date:date,tor:tor,cb:cb,cd:cd,receipt_id:receipt_id,payment_id:payment_id},
    success:function(result){
      SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
        $("body").removeClass("loading");
    }
  })
}
})
</script>
@stop