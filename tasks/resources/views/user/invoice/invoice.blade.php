@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<style>
body{
  background: #f5f5f5 !important;
}
.create_journal_for_invoice{
  padding:5px;
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
.label_class{
  float:left ;
  margin-top:15px;
  font-weight:700;
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
.report_div{
    position: absolute;
    top: 74%;
    left:248px;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
    text-align: left;
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
.report_model_selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; margin-top: 20px; border-radius: 3px;  }
.report_ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 15px; transition: 0.3s; opacity: 0.6; }
.report_ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff; opacity: 1;}
.report_ok_button:focus{background: #000; text-decoration: none; color: #fff; opacity: 1;}
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
    body #coupon {
      display: none;
    }
    @media print {
      body * {
        display: none;
      }
      body #coupon {
        display: block;
      }
    }

</style>
<script>
function popitup(url) {
    newwindow=window.open(url,'name','height=600,width=1500');
    if (window.focus) {newwindow.focus()}
    return false;
}
</script>
<style>
.error{color: #f00; font-size: 12px;}
a:hover{text-decoration: underline;}
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
    $out = $_GET['out'];
    ?>
    <div class="upload_img" style="width: 100%;height: 100%;z-index:1"><p class="upload_text">Uploading Please wait...</p><img src="<?php echo URL::to('assets/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">Finished Uploading <?php echo $height; ?> of <?php echo $highestrow; ?></p></div>
    <script>
      $(document).ready(function() {
        setTimeout( function() { $("body").removeClass('loading'); },3000);
        var base_url = "<?php echo URL::to('/'); ?>";
        window.location.replace(base_url+'/user/import_new_invoice_one?filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type_new=1&out=<?php echo $out; ?>');
      })
    </script>
    <?php
  }
}
?>
<img id="coupon" />
<div class="modal fade report_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Report Clients</h4>
            <div class="col-lg-12 report_model_selectall">
              <div class="col-lg-2" style="padding: 0px;"><input type="checkbox" class="select_all_class" id="select_all_class" value="1" style="padding-top: 20px;"><label for="select_all_class" style="font-size: 14px; font-weight: normal;">Select all</label></div>
              <div class="col-lg-10" id="date_filter_fields">
                <div style="float:left; line-height:30px;">From</div>
                <div class="col-lg-3"><input type="text" class="form-control datepicker" name="fromdate" id="fromdate" /></div>
                <div style="float:left; line-height:30px;">To</div>
                <div class="col-lg-3"><input type="text" class="form-control datepicker" name="todate" id="todate"  /></div>
                <div class="col-lg-3"><a href="javascript:" class="report_ok_button fillter_class" style="line-height:20px;">Submit</a></div>               
              </div>
            </div>
        </div>
        <div class="modal-body" style="height: 400px; overflow-y: scroll;">
            <table class="table">
              <thead>
              <tr style="background: #fff;">
                  <th width="5%" style="text-align: left;">S.No</th>
                  <th width="5%" style="text-align: left;"></th>
                  <th style="text-align: left;">Client ID</th>
                  <th style="text-align: left;">First Name</th>    
                  <th style="text-align: left;">Company Name</th>
                  <th width="15%" style="text-align: left;">Invoice Count</th>
              </tr>
              </thead>                            
              <tbody id="report_tbody">
              
              </tbody>
          </table>
        </div>
        <div class="modal-footer">
            <input type="button" class="common_black_button" id="save_as_csv" value="Save as CSV">
            <input type="button" class="common_black_button" id="save_as_pdf" value="Save as PDF">
        </div>
      </div>
  </div>
</div>
<div class="modal fade invoice_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
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
<div class="modal fade" id="import_invoice" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <form action="<?php echo URL::to('user/import_new_invoice'); ?>" method="post" autocomplete="off" enctype="multipart/form-data">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Import Invoice</h4>
          </div>
          <div class="modal-body">
              <label>Choose File : </label>
              <input type="file" name="new_file" id="new_file" class="form-control input-sm" accept=".xlsx" required> 
          </div>
          <div class="modal-footer">
              <input type="submit" class="common_black_button" id="import_new_file" value="Import">
          </div>
        </form>
      </div>
  </div>
</div>
<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
    <h4 class="col-lg-12 padding_00 new_main_title">
                Invoice Management              
            </h4>   
      
      <div class="col-lg-12"  style="padding: 0px;">
          <div class="select_button" style="width: 330px;">
            <ul>
              <li><a href="javascript:" class="common_black_button invoice_nominals">Invoice Nominals</a></li>
              <li><a href="javascript:" class="common_black_button invoice_import">Import</a></li>
              <li><a href="javascript:" class="common_black_button reportclassdiv">Report</a></li>
            </ul>
          </div>
          <div class="report_div" style="display: none">
              <label>Please select following report type</label><br>
              <input type="radio" name="report_invoice" id="allinvoice" class="class_invoice" value="1"><label for="allinvoice">Client Based Invoice</label>
              <br/>
              <input type="radio" name="report_invoice" id="datefilterinvoice" class="class_invoice" value="2"><label for="datefilterinvoice">Date Based Invoice</label>
              <br/>
              <input type="submit" name="invoce_report_but" class="report_ok_button ok_button" value="OK">
          </div>
          
            <div class="col-lg-4" style="width: 550px;">
              <label style="float:left;margin-top:8px;margin-right:10px">Search Client ID: </label>
              <input type="text" class="form-control client_common_search" placeholder="Enter Client Name" style="font-weight: 500;width:40%;margin-bottom: 13px;float:left" value="" disabled/>                      
              <input type="hidden" class="client_search_common" id="client_search_hidden_infile" value="" name="client_id">
              <input type="checkbox" name="all_clients" class="all_clients" id="all_clients" checked><label for="all_clients">All Clients</label>
            </div>
            <div class="col-lg-4 padding_00" style="width: 500px;">
              <spam>Invoice List: </spam>
              <input type="radio" name="invoice_date_option" class="invoice_date_option" id="invoice_date_option_1" value="1"><label for="invoice_date_option_1">Year</label>&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="radio" name="invoice_date_option" class="invoice_date_option" id="invoice_date_option_2" value="2"><label for="invoice_date_option_2">All Invoice</label>&nbsp;&nbsp;&nbsp;&nbsp;
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
            <div class="col-lg-4 padding_00" style="width: 500px">
              <div class="col-md-5 padding_00">
                <select class="form-control search_select_class" style="width:70%;float:left">
                  <option value="">Select Type</option>
                  <option value="invoice_number">Invoice Number</option>
                  <option value="invoice_date">Date (DD-MMM-YYYY)</option>
                  <option value="client_id">Client ID</option>
                  <option value="company_name">Company Name</option>
                  <option value="inv_net">Net</option>
                  <option value="vat_value">VAT</option>
                  <option value="gross">Gross</option>
                </select>
                <a href="" class="fa fa-refresh common_black_button" style="font-size: 13px; font-weight: 500;float:left"></a>
              </div>
              <div class="col-md-7 padding_00">
                <input type="text" name="" placeholder="Search" class="form-control search_input_class" style="width:70%;float:left">
                <input type="text" name="" placeholder="Search" class="form-control search_input_class_date" style="width:70%;float:left;display: none">
                <a href="javascript:" class="common_black_button" id="search_button" style="font-size: 13px; font-weight: 500;float:left">Search</a>
              </div>
            </div>
          
      </div>
       
      
      
      <div style="clear: both;">
        <?php
        if(Session::has('message')) { ?>
            <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
        
        <?php } ?>
      </div> 
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
      <table class="display fullviewtablelist own_table_white" id="client_expand" width="100%">
          <thead>
          <tr style="background: #fff;">
              <th width="2%" style="text-align: left;">S.No</th>
              <th style="text-align: left;">Invoice #</th>
              <th style="text-align: left;">Date</th>
              <th style="text-align: left;">Client ID</th>
              <th style="text-align: left;width:27%">Company Name</th>
              <th style="text-align: right;">Net</th>
              <th style="text-align: right;">VAT</th>
              <th style="text-align: right;">Gross</th>
              <th style="text-align: left;">Statement</th>
              <th style="text-align: left;">Journal ID</th>
          </tr>
          </thead>                            
          <tbody id="invoice_tbody">
          
          </tbody>
      </table>
  </div>
    <!-- End  -->
  <div class="main-backdrop"><!-- --></div>
  <div id="report_pdf_type_two" style="display:none">
    <style>
    .table_style {
        width: 100%;
        border-collapse:collapse;
        border:1px solid #c5c5c5;
    }
    </style>
    <h3 id="pdf_title_all_ivoice" style="width: 100%; text-align: center; margin: 15px 0px; float: left;">Report of Client Based Invoices</h3>
    <h3 id="pdf_title_date_filter" style="width: 100%; text-align: center; margin: 15px 0px; float: left;">Report of Invoices From <span id="pdffromdate"></span> to <span id="pdftodate"></span></h3>
    <table class="table_style">
      <thead>
        <tr>
        <th width="2%" style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">S.No</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Invoice ID</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Date</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Client ID</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px ">Company Name</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px ">Net</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">VAT</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Gross</th>
        <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px ">Journal Id</th>
        </tr>
      </thead>
      <tbody id="report_pdf_type_two_tbody">
      </tbody>
    </table>
  </div>
  <div class="modal_load"></div>
  <div class="modal_load_apply" style="text-align: center;">
    <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Invoices are Loaded.</p>
    <p style="font-size:18px;font-weight: 600;">Building Sales Invoice Journals: <span id="apply_first"></span> of <span id="apply_last"></span></p>
  </div>
  <input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
  <input type="hidden" name="show_alert" id="show_alert" value="">
  <input type="hidden" name="pagination" id="pagination" value="1">

  <input type="hidden" name="hidden_financial_opening_date" id="hidden_financial_opening_date" value="">

  
</div>
<script>
$(document).ready(function() {
  $(".client_common_search").autocomplete({
    source: function(request, response) {        
      $.ajax({
        url:"<?php echo URL::to('user/client_review_client_common_search'); ?>",
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
      $("#client_search_hidden_infile").val(ui.item.id);
      $("#hidden_client_id").val(ui.item.id);
      $.ajax({
        url:"<?php echo URL::to('user/get_loaded_client_inv_year'); ?>",
        type:"post",
        data:{client_id:ui.item.id},
        success:function(result)
        {
          $(".invoice_select_year").html(result);
          $(".invoice_date_option").prop("checked",false);
        }
      })
    }
    });
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
    $(".search_input_class_date").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD-MMM-YYYY',
    });
});
$( function() {
  $(".datepicker" ).datepicker({ dateFormat: 'mm-dd-yy' });
  $(".datepicker" ).datepicker();
});
function printPdf(url) {
  var iframe = this._printIframe;
  if (!this._printIframe) {
    iframe = this._printIframe = document.createElement('iframe');
    document.body.appendChild(iframe);
    iframe.style.display = 'none';
    iframe.onload = function() {
      setTimeout(function() {
        iframe.focus();
        iframe.contentWindow.print();
      }, 1);
    };
  }
  iframe.src = url;
}
function next_invoice_check(count)
{
  var inv_id = $(".include_tr:eq("+count+")").find(".invoice_class").html();
  $.ajax({
    url:"<?php echo URL::to('user/insert_update_invoice_nominals'); ?>",
    type:"post",
    data:{inv_id:inv_id},
    success:function(result)
    {
      $(".include_tr:eq("+count+")").find(".jids").html(result);
      setTimeout( function() {
        var countval = count + 1;
        if($(".include_tr:eq("+countval+")").length > 0)
        {
          next_invoice_check(countval);
          $("#apply_first").html(countval);
        }
        else{
          $("#hidden_financial_opening_date").val("");
          $("body").removeClass("loading_apply");
          $.colorbox.close();
        }
      },200);
    }
  });
}
function next_invoice_check_after(count)
{
  var inv_id = $(".after_tr:eq("+count+")").find(".invoice_class").html();
  $.ajax({
    url:"<?php echo URL::to('user/insert_update_invoice_nominals'); ?>",
    type:"post",
    data:{inv_id:inv_id},
    success:function(result)
    {
      $(".after_tr:eq("+count+")").find(".jids").html(result);
      setTimeout( function() {
        var countval = count + 1;
        if($(".after_tr:eq("+countval+")").length > 0)
        {
          next_invoice_check_after(countval);
          $("#apply_first").html(countval);
        }
        else{
          $("body").removeClass("loading_apply");
          $.colorbox.close();
        }
      },200);
    }
  });
}
$(window).click(function(e) {
  if($(e.target).hasClass('all_clients'))
  {
    if($(e.target).is(":checked"))
    {
      $(".client_common_search").prop("disabled",true);
      $(".client_common_search").val("");
      $(".client_search_common").val("");
      $(".invoice_date_option").prop("checked",false);
    }
    else{
      $(".client_common_search").prop("disabled",false);
      $(".client_common_search").val("");
      $(".client_search_common").val("");
      $(".invoice_date_option").prop("checked",false);
    }
  }
  if($(e.target).hasClass('invoice_date_option'))
  {
    var client_selected = "0";
    if($(".all_clients").is(":checked"))
    {
      var client_selected = 'all';
    }
    else{
      var client = $("#client_search_hidden_infile").val();
      if(client == "")
      {
        var client_selected = '0';
      }
      else{
        var client_selected = client;
      }
    }
    if(client_selected == "0")
    {
      $(".invoice_date_option").prop("checked",false);
      alert("Please Select the Client");
    }
    else{
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
              url: "<?php echo URL::to('user/load_all_client_invoice') ?>",
              data:{client_id:client_selected,type:"2"},
              type:"post",
              success:function(result){
                $("#invoice_tbody").html(result);  
                $('#client_expand').DataTable({
                    autoWidth: false,
                    scrollX: false,
                    fixedColumns: false,
                    searching: false,
                    paging: false,
                    info: false
                });     
                $(".search_select_class").val("");
                $(".search_input_class").val("");
                $(".search_input_class_date").val("");
                $(".search_input_class_date").hide();
                $(".search_input_class").show();
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
    var client_selected = "0";
    if($(".all_clients").is(":checked"))
    {
      var client_selected = 'all';
    }
    else{
      var client = $("#client_search_hidden_infile").val();
      if(client == "")
      {
        var client_selected = '0';
      }
      else{
        var client_selected = client;
      }
    }

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
                  url: "<?php echo URL::to('user/load_all_client_invoice') ?>",
                  data:{client_id:client_selected,year:year,type:"1"},
                  type:"post",
                  success:function(result){
                    $("#invoice_tbody").html(result);       
                    $('#client_expand').DataTable({
                        autoWidth: false,
                        scrollX: false,
                        fixedColumns: false,
                        searching: false,
                        paging: false,
                        info: false
                    });
                    $(".search_select_class").val("");
                    $(".search_input_class").val("");
                    $(".search_input_class_date").val("");
                    $(".search_input_class_date").hide();
                    $(".search_input_class").show();
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
                  url: "<?php echo URL::to('user/load_all_client_invoice') ?>",
                  data:{client_id:client_selected,from:from,to:to,type:"3"},
                  type:"post",
                  success:function(result){
                    $("#invoice_tbody").html(result);  
                    $('#client_expand').DataTable({
                        autoWidth: false,
                        scrollX: false,
                        fixedColumns: false,
                        searching: false,
                        paging: false,
                        info: false
                    });     

                    $(".search_select_class").val("");
                    $(".search_input_class").val("");
                    $(".search_input_class_date").val("");
                    $(".search_input_class_date").hide();
                    $(".search_input_class").show();
                    $(".invoice_year_div").hide();
                    $(".invoice_custom_div").hide();
                    $("body").removeClass("loading");
                  }
              });
        }, 2000);
      
    }
  }
  if($(e.target).hasClass("invoice_nominals"))
  {
    var len = $(".include_tr").length;
    if(len == 0)
    {
      alert("Please list the invoices and then please check wheather the invoice date is greather than the financial opening date and then proceed with 'Invoice Nominals' button to create the journals.");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/check_financial_opening_bal_date'); ?>",
        type:"post",
        dataType:"json",
        success:function(result)
        {
          if(result['opening_date'] == "")
          {
            $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#F00">You must set an Financial Opening Date on the Financial Module Setup Screen</p>',fixed:true,width:"800px"});
          }
          else{
            $("#hidden_financial_opening_date").val(result['opening_date']);
            $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">The Financial Opening Date is '+result['date_formatted']+', do you want to include this date in the nominal import system or only invoices after this date</p><p style="text-align:center"><a href="javascript:" class="common_black_button include_option">Include</a><a href="javascript:" class="common_black_button after_option">After</a></p>',fixed:true,width:"800px"});
          }
        }
      })
    }
  }
  if($(e.target).hasClass('include_option'))
  {
    $("body").addClass("loading_apply");
    var countinvoice = $(".include_tr").length;
    $("#apply_last").html(countinvoice);
    var inv_id = $(".include_tr:eq(0)").find(".invoice_class").html();
    $.ajax({
      url:"<?php echo URL::to('user/insert_update_invoice_nominals'); ?>",
      type:"post",
      data:{inv_id:inv_id},
      success:function(result)
      {
        $(".include_tr:eq(0)").find(".jids").html(result);
        setTimeout( function() {
          if($(".include_tr:eq(1)").length > 0)
          {
            next_invoice_check(1);
            $("#apply_first").html(1);
          }
          else{
            $("#hidden_financial_opening_date").val("");
            $("body").removeClass("loading_apply");
            $.colorbox.close();
          }
      },200);
      }
    });
  }
  if($(e.target).hasClass('after_option'))
  {
    $("body").addClass("loading_apply");
    var countinvoice = $(".after_tr").length;
    $("#apply_last").html(countinvoice);
    var inv_id = $(".after_tr:eq(0)").find(".invoice_class").html();
    $.ajax({
      url:"<?php echo URL::to('user/insert_update_invoice_nominals'); ?>",
      type:"post",
      data:{inv_id:inv_id},
      success:function(result)
      {
        $(".after_tr:eq(0)").find(".jids").html(result);
        setTimeout( function() {
          if($(".after_tr:eq(1)").length > 0)
          {
            next_invoice_check_after(1);
            $("#apply_first").html(1);
          }
          else{
            $("body").removeClass("loading_apply");
            $.colorbox.close();
          }
      },200);
      }
    });
  }
  if($(e.target).hasClass('create_journal_for_invoice'))
  {
    $("body").addClass("loading_apply");
    var inv_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/insert_update_invoice_nominals'); ?>",
      type:"post",
      data:{inv_id:inv_id},
      success:function(result)
      {
        $(e.target).parents("td").html(result);
        $("body").removeClass("loading_apply");
      }
    });
  }
  if($(e.target).hasClass('saveas_pdf'))
  {
    var htmlcontent = $("#letterpad_modal").html();
    var inv_no = $("#invoice_number_pdf").val();
    $.ajax({
      url:"<?php echo URL::to('user/invoice_saveas_pdf'); ?>",
      data:{htmlcontent:htmlcontent,inv_no:inv_no},
      type:"post",
      success: function(result)
      {
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
      }
    });
  }
  if($(e.target).hasClass('print_pdf'))
  {
    var htmlcontent = $("#letterpad_modal").html();
    $.ajax({
      url:"<?php echo URL::to('user/invoice_print_pdf'); ?>",
      data:{htmlcontent:htmlcontent},
      type:"post",
      success: function(result)
      {
        $("#pdfDocument").attr("src","<?php echo URL::to('papers/Invoice Report.pdf'); ?>");
        printPdf("<?php echo URL::to('papers/Invoice Report.pdf'); ?>");
      }
    });
  }
  if(e.target.id == "select_all_class")
  {
    if($(e.target).is(":checked"))
    {
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
  if(e.target.id == "search_button")
  {
    var select = $(".search_select_class").val();
    if(select == "invoice_date")
    {
      var input = $(".search_input_class_date").val();
    }
    else{
      var input = $(".search_input_class").val();
    }

    if(input == "" || select == "")
    {
      alert("Please Type the Input and choose the search type to search the Invoice.");
    }
    else{
      $('#client_expand').DataTable().destroy();
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/invoice_search'); ?>",
        type:"get",
        data:{input:input,select:select},
        success: function(result) {
          $("#invoice_tbody").html(result);
          $("#client_expand_info").hide(); 
          $('#client_expand').DataTable({
              autoWidth: false,
              scrollX: false,
              fixedColumns: false,
              searching: false,
              paging: false,
              info: false
          });
          $(".invoice_date_option").prop("checked",false);
          $(".client_common_search").val("");
          $(".client_search_common").val("");
          $("#client_search_hidden_infile").val("");
          $(".all_clients").prop("checked",true);
          $(".client_common_search").prop("disabled",true);
          $("body").removeClass("loading");
        }
      });
    }
  }
  if(e.target.id == 'show_statement')
  {
    if($(e.target).is(':checked'))
    {
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/show_statement'); ?>",
        type:"post",
        data:{value:1},
        success: function(result)
        {
          $("#invoice_tbody").html(result);
          $("body").removeClass("loading");
        }
      });
    }
    else{
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/show_statement'); ?>",
        type:"post",
        data:{value:0},
        success: function(result)
        {
          $("#invoice_tbody").html(result);
          $("body").removeClass("loading");
        }
      });
    }
  }
  if($(e.target).hasClass('reportclassdiv'))
  {
    $(".report_div").toggle();
  }
  if(e.target.id == "save_as_csv")
  {
    if ($('#date_filter_fields').is(':visible')) {
      var fromdate = $("#fromdate").val();
      var todate = $("#todate").val();
       $("body").addClass("loading");
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
            url:"<?php echo URL::to('user/invoice_report_csv'); ?>",
            type:"post",
            data:{value:checkedvalue,fromdate:fromdate,todate:todate},
            success: function(result)
            {
              SaveToDisk("<?php echo URL::to('papers'); ?>/CM_Report.csv",'Invoice_Report.csv');
            }
          });
        }
        else{
          $("body").removeClass("loading");
          alert("Please Choose atleast one invoice to continue.");
        }
    }
    else{
       $("body").addClass("loading");
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
            url:"<?php echo URL::to('user/invoice_report_csv'); ?>",
            type:"post",
            data:{value:checkedvalue},
            success: function(result)
            {
              SaveToDisk("<?php echo URL::to('papers'); ?>/CM_Report.csv",'Invoice_Report.csv');
            }
          });
        }
        else{
          $("body").removeClass("loading");
          alert("Please Choose atleast one invoice to continue.");
        }
    }
  }
  if(e.target.id == "save_as_pdf")
  {
    $("#report_pdf_type_two_tbody").html('');
    if ($('#date_filter_fields').is(':visible')) {
      var fromdate = $("#fromdate").val();
      var todate = $("#todate").val();
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
                    url:"<?php echo URL::to('user/invoice_report_pdf'); ?>",
                    type:"post",
                    data:{value:imp,fromdate:fromdate,todate:todate},
                    success: function(result)
                    {
                      $("#report_pdf_type_two_tbody").append(result);
                      
                      var last = index + parseInt(1);
                      if(arrayval.length == last)
                      {
                        var pdf_html = $("#report_pdf_type_two").html();
                        $.ajax({
                          url:"<?php echo URL::to('user/invoice_download_report_pdfs'); ?>",
                          type:"post",
                          data:{htmlval:pdf_html},
                          success: function(result)
                          {
                            SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
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
          alert("Please Choose atleast one invoice to continue.");
        }
    }
    else{
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
                    url:"<?php echo URL::to('user/invoice_report_pdf'); ?>",
                    type:"post",
                    data:{value:imp},
                    success: function(result)
                    {
                      $("#report_pdf_type_two_tbody").append(result);
                      
                      var last = index + parseInt(1);
                      if(arrayval.length == last)
                      {
                        var pdf_html = $("#report_pdf_type_two").html();
                        $.ajax({
                          url:"<?php echo URL::to('user/invoice_download_report_pdfs'); ?>",
                          type:"post",
                          data:{htmlval:pdf_html},
                          success: function(result)
                          {
                            SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
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
          alert("Please Choose atleast one invoice to continue.");
        }
    }
  }
  if($(e.target).hasClass('ok_button'))
  {
    var check_option = $(".class_invoice:checked").val();
    if(check_option === "" || typeof check_option === "undefined")
    {
      alert("Please select atleast one report type to move forward.");
    }
    else{
      var id = $('input[name="report_invoice"]:checked').val();
      $(".class_invoice").prop("checked", false);
      if(id == 1){
          $("body").addClass("loading");    
          $.ajax({
              url: "<?php echo URL::to('user/report_client_invoice') ?>",
              data:{id:0},
              type:"post",
              success:function(result){      
                 $(".report_modal").modal("show");
                 $("#report_tbody").html(result);
                 $("#report_tbody").show();               
                 $(".report_div").hide();
                 $("body").removeClass("loading");
                 $("#date_filter_fields").hide();
                 $("#pdf_title_all_ivoice").show();
                 $("#pdf_title_date_filter").hide();
                 
          }
        });
      }
      else{
        $("body").addClass("loading");    
          $.ajax({
              url: "<?php echo URL::to('user/report_client_invoice') ?>",
              data:{id:1},
              type:"post",
              success:function(result){      
                 $(".report_modal").modal("show");
                 $("#report_tbody").hide();
                 $(".report_div").hide();
                 $("body").removeClass("loading");
                 $("#date_filter_fields").show();
          }
        });
      }
    }
  }
   if($(e.target).hasClass('fillter_class')){
    var from = $("#fromdate").val();
    var to = $("#todate").val();
    if(from === "" || to === "")
    {
      if(from == "")
      {
        alert("Plaese choose the From Date to view the report");
      }
      else
      {
        alert("Plaese choose the TO Date to view the report");
      }
    } 
      else{
        $("body").addClass("loading");    
          $.ajax({
            url: "<?php echo URL::to('user/report_client_invoice_date_filter') ?>",
            data:{id:0, fdate:from, tdate:to},
            type:"post",
            success:function(result){      
               $(".report_modal").modal("show");
               $("#report_tbody").html(result);
               $("#report_tbody").show();
               $(".report_div").hide();
               $("body").removeClass("loading");
               $("#pdf_title_all_ivoice").hide();
               $("#pdf_title_date_filter").show();
            }
          });
      }
  }
  if($(e.target).hasClass('invoice_import'))
  {    
    $("#import_invoice").modal("show");
  }
  if($(e.target).hasClass('invoice_class')){
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
});
$(window).change(function(e) {
  if($(e.target).hasClass('search_select_class'))
  {
    var value = $(e.target).val();
    if(value == "invoice_date")
    {
        $(".search_input_class").hide();
        $(".search_input_class_date").show();
        $(".search_input_class_date").val("");
    }
    else{
      $(".search_input_class").show();
        $(".search_input_class_date").hide();
        $(".search_input_class_date").val("");
    }
  }
});
</script>
<!-- Page Scripts -->
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
});
</script>
<script>
$("#fromdate").change(function(){    
    var value = $(this).val();
    console.log(value);
    $.ajax({
      success:function(result){
        $("#pdffromdate").html(value);
      }
    });
});
$("#todate").change(function(){    
    var value = $(this).val();
    console.log(value);
    $.ajax({
      success:function(result){
        $("#pdftodate").html(value);       
      }
    });
});
</script>
@stop