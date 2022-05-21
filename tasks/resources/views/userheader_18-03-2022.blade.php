- <html>

<head>

<meta charset="UTF-8">

<?php

    //header('Set-Cookie: fileDownload=true; path=/');

    header('Cache-Control: max-age=60, must-revalidate');

 ?>

 <?php

 $page_segment =  Request::segment(2);

 if($page_segment == "dashboard") { $title = 'Bubble - Dashboard'; }

 elseif($page_segment == "client_management") { $title = 'Bubble - Client Management System'; }

 elseif($page_segment == "invoice_management") { $title = 'Bubble - Invoice Management System'; }

 elseif($page_segment == "statement_list") { $title = 'Bubble - Client Statements'; }

 elseif($page_segment == "receipt_management") { $title = 'Bubble - Receipt Management'; }

  elseif($page_segment == "receipt_settings") { $title = 'Bubble - Receipt Management'; }

  elseif($page_segment == "payment_management") { $title = 'Bubble - Payment Management'; }

 elseif($page_segment == "time_management") { $title = 'Bubble - Time Management System'; }

 elseif($page_segment == "manage_week") { $title = 'Bubble - Weekly Payroll Management'; }

 elseif($page_segment == "week_manage") { $title = 'Bubble - Weekly Payroll Management'; }

 elseif($page_segment == "select_week") { $title = 'Bubble - Weekly Payroll Management'; }

 elseif($page_segment == "manage_month") { $title = 'Bubble - Monthly Payroll Management'; }

 elseif($page_segment == "month_manage") { $title = 'Bubble - Monthly Payroll Management'; }

 elseif($page_segment == "select_month") { $title = 'Bubble - Monthly Payroll Management'; }

 elseif($page_segment == "payroll_settings") { $title = 'Bubble - Payroll Settings'; }

 elseif($page_segment == "p30") { $title = 'Bubble - PAYE MRS'; }

 elseif($page_segment == "p30month_manage") { $title = 'Bubble - PAYE MRS'; }

 elseif($page_segment == "p30_select_month") { $title = 'Bubble - PAYE MRS'; }

 elseif($page_segment == "paye_p30_manage") { $title = 'Bubble - PAYE M.R.S'; }

 elseif($page_segment == "paye_p30_ros_liabilities") { $title = 'Bubble - PAYE M.R.S'; }

 elseif($page_segment == "paye_p30_email_distribution") { $title = 'PAYE M.R.S. Quick Email'; }

 elseif($page_segment == "vat_clients") { $title = 'Bubble - VAT Management System'; }

  elseif($page_segment == "vat_review") { $title = 'Bubble - VAT Management Review'; }

 elseif($page_segment == "vat_notifications") { $title = 'Bubble - VAT Notification'; }



 elseif($page_segment == "rct_system") { $title = 'Bubble - RCT Manager'; }

 elseif($page_segment == "rct_client_manager") { $title = 'Bubble - RCT Manager'; }

 elseif($page_segment == "rct_liability_assessment") { $title = 'Bubble - RCT Manager'; }



 elseif($page_segment == "gbs_p30") { $title = 'Bubble - P30 System'; }

 elseif($page_segment == "gbs_p30month_manage") { $title = 'Bubble - P30 System'; }

 elseif($page_segment == "gbs_p30_select_month") { $title = 'Bubble - P30 System'; }



 elseif($page_segment == "year_end_manager") { $title = 'Bubble - Year End Manager'; }

 elseif($page_segment == "yearend_setting") { $title = 'Bubble - Year End Manager'; }

 elseif($page_segment == "yearend_individualclient") { $title = 'Bubble - Year End Manager'; }

 elseif($page_segment == "yeadend_clients") { $title = 'Bubble - Year End Manager'; }



 elseif($page_segment == "time_track") { $title = 'Bubble - TimeMe Manager'; }

 elseif($page_segment == "time_me_overview") { $title = 'Bubble - TimeMe Overview - Active Jobs'; }

 elseif($page_segment == "time_me_joboftheday") { $title = 'Bubble - TimeMe Overview - Jobs of the day'; }

 elseif($page_segment == "time_me_client_review") { $title = 'Bubble - TimeMe Overview - Client Review'; }

 elseif($page_segment == "time_me_all_job") { $title = 'Bubble - TimeMe Overview - All Jobs'; }

 elseif($page_segment == "time_task") { $title = 'Bubble - TimeMe Tasks'; }



 elseif($page_segment == "in_file") { $title = 'Bubble - Infiles Module'; }

 elseif($page_segment == "in_file_advance") { $title = 'Bubble - Infiles Module'; }

 elseif($page_segment == "infile_search") { $title = 'Bubble - Infiles Module'; }

 elseif($page_segment == "aml_system") { $title = 'Bubble - AML System'; }

 elseif($page_segment == "client_request_system") { $title = 'Bubble - Client Request System'; }

 elseif($page_segment == "client_request_manager") { $title = 'Bubble - Client Request System'; }

 elseif($page_segment == "client_request_edit") { $title = 'Bubble - Client Request System'; }

 elseif($page_segment == "yeadend_liability") { $title = 'Bubble - YEAR END MANAGER Liability Assessment'; }



 elseif($page_segment == "ta_system") { $title = 'Bubble - T.A. System'; }

 elseif($page_segment == "ta_allocation") { $title = 'Bubble - T.A. System'; }

 elseif($page_segment == "ta_auto_allocation") { $title = 'Bubble - T.A. System'; }

 elseif($page_segment == "ta_overview") { $title = 'Bubble - T.A. System'; }



 elseif($page_segment == "task_manager") { $title = 'Bubble - Task Manager'; }

 elseif($page_segment == "park_task") { $title = 'Bubble - Task Manager'; }

 elseif($page_segment == "taskmanager_search") { $title = 'Bubble - Task Manager'; }

 elseif($page_segment == "task_administration") { $title = 'Bubble - Task Manager'; }

 elseif($page_segment == "task_overview") { $title = 'Bubble - Task Manager'; }



 elseif($page_segment == "directmessaging") { $title = 'Bubble - Message Us'; }

 elseif($page_segment == "directmessaging_page_two") { $title = 'Bubble - Message Us'; }

 elseif($page_segment == "directmessaging_page_three") { $title = 'Bubble - Message Us'; }

 elseif($page_segment == "messageus_groups") { $title = 'Bubble - Message Us'; }

 elseif($page_segment == "messageus_saved_messages") { $title = 'Bubble - Message Us'; }



 elseif($page_segment == "opening_balance_manager") { $title = 'Bubble - Opening Balance Manager'; }

 elseif($page_segment == "client_opening_balance_manager") { $title = 'Bubble - Opening Balance Manager'; }

 elseif($page_segment == "import_opening_balance_manager") { $title = 'Bubble - Opening Balance Manager'; }



 elseif($page_segment == "client_account_review") { $title = 'Bubble - Client Account Review'; }



 elseif($page_segment == "financials") { $title = 'Bubble - Financials'; }



 elseif($page_segment == "two_bill_manager") { $title = 'Bubble - 2Bill'; }

 elseif($page_segment == "manage_croard") { $title = 'Bubble - CRO ARD'; }



  elseif($page_segment == "supplier_management") { $title = 'Bubble - Supplier Management'; }

 elseif($page_segment == "supplier_invoice_management") { $title = 'Bubble - Supplier Invoice Management'; }

 elseif($page_segment == "purchase_invoice_to_process") { $title = 'Bubble - Purchase Invoices to Process'; }

  elseif($page_segment == "supplier_opening_balance") { $title = 'Bubble - Supplier - Opening Balance'; }

 

 else{ $title = 'Bubble'; }

 ?>

<title><?php echo $title; ?></title>

<link rel="icon" href="<?php echo URL::to('assets/images/favicon-b.png'); ?>" sizes="32x32" />

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/bootstrap.min.css')?>">

<script type="text/javascript" src="<?php echo URL::to('assets/js/jquery-1.11.2.min.js')?>"></script>

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/bootstrap-theme.min.css')?>" />

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/font-awesome-4.2.0/css/font-awesome.css')?>">

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/style.css?v='.time().'')?>">

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/style-responsive.css')?>">

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/stylesheet-image-based.css')?>">

<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">

<link rel="stylesheet" href="<?php echo URL::to('assets/css/datepicker/jquery-ui.css')?>">

<link rel="stylesheet" href="<?php echo URL::to('assets/lightbox/colorbox.css')?>">

<script src="<?php echo URL::to('assets/js/datepicker/jquery-1.12.4.js')?>"></script>

<script src="<?php echo URL::to('assets/js/jquery.validate.js')?>"></script>

<script src="<?php echo URL::to('assets/js/jquery.number.min.js')?>"></script>

<script src="<?php echo URL::to('assets/pagination/jquery.twbsPagination.js'); ?>" type="text/javascript"></script>

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/dropzone/dist/dropzone.css'); ?>" />

<script type="text/javascript" src="<?php echo URL::to('assets/dropzone/dist/dropzone.js'); ?>"></script>

<script src="<?php echo URL::to('assets/js/datepicker/jquery-ui.js')?>"></script>

<script type="text/javascript" src="<?php echo URL::to('assets/js/bootstrap.min.js')?>"></script>



<script src="<?php echo URL::to('assets/lightbox/jquery.colorbox.js'); ?>"></script>



<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/jscrollpane/style/jquery.jscrollpane.css') ?>">

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/ladda/dist/ladda-themeless.min.css') ?>">

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/select2/dist/css/select2.min.css') ?>">

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ?>">

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/summernote/dist/summernote.css') ?>">

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/datatables/media/css/dataTables.bootstrap4.min.css') ?>">





<!-- Vendors Scripts -->

<!-- v1.0.0 -->



<script src="<?php echo URL::to('assets/vendors/jquery-mousewheel/jquery.mousewheel.min.js') ?>"></script>

<script src="<?php echo URL::to('assets/vendors/jscrollpane/script/jquery.jscrollpane.min.js') ?>"></script>

<script src="<?php echo URL::to('assets/vendors/select2/dist/js/select2.full.min.js') ?>"></script>

<!-- <script src="<?php //echo URL::to('assets/vendors/html5-form-validation/dist/jquery.validation.min.js') ?>"></script> -->

<script src="<?php echo URL::to('assets/vendors/moment/min/moment.min.js') ?>"></script>

<script src="<?php echo URL::to('assets/vendors/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ?>"></script>

<script src="<?php echo URL::to('assets/vendors/summernote/dist/summernote.min.js') ?>"></script>

<script src="<?php echo URL::to('assets/vendors/datatables/media/js/jquery.dataTables.min.js') ?>"></script>

<script src="<?php echo URL::to('assets/vendors/datatables/media/js/dataTables.bootstrap4.min.js') ?>"></script>

<script src="<?php echo URL::to('assets/vendors/datatables-responsive/js/dataTables.responsive.js') ?>"></script>

<script src="<?php echo URL::to('assets/vendors/editable-table/mindmup-editabletable.js') ?>"></script>

<link href="<?php echo URL::to('assets/common/css/jquery-ui.css')?>" rel="stylesheet">

<!-- <script src="<?php //echo URL::to('assets/common/js/jquery-ui.js')?>"></script> -->

<link href="<?php echo URL::to('assets/common/css/jquery.ui.autocomplete.css')?>" rel="stylesheet">

<script src="<?php echo URL::to('assets/ckeditor/ckeditor.js'); ?>"></script>

<script src="<?php echo URL::to('assets/ckeditor/src/js/main.js'); ?>"></script>

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/new_style.css')?>">

<link rel="preconnect" href="https://fonts.gstatic.com">

<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">



<style>

<?php

 $segment1 =  Request::segment(2);  

  if($segment1 == 'manage_week' || $segment1 == 'week_manage' || $segment1 == 'select_week') { 

    if($segment1 == 'select_week') { ?>

      .body_bg{

        width:2030px !important;

      }

      .page_title{

        width: 100% !important;

          height: auto;

          float: left !important;

          position: fixed !important;

          top: 84px !important;

          font-weight: bold;

          text-align: left;

          padding: 5px 0px;

          margin-bottom: 20px;

          color: #000;

          font-size: 15px;

          text-transform: uppercase;

          left: 0px;

      }

      <?php } 

    else{ ?>

      .body_bg{

      }

      <?php

      } 

  } 

  elseif($segment1 == 'manage_month' || $segment1 == 'month_manage' || $segment1 == 'select_month') { 

    if($segment1 == 'select_month') { ?>

      .body_bg{

        width:2030px !important;

      }

      .page_title{

        width: 100% !important;

          height: auto;

          float: left !important;

          position: fixed !important;

          top: 84px !important;

        font-weight: bold;

        text-align: left;

        padding: 5px 0px;

        margin-bottom: 20px;

        color: #000;

        font-size: 15px;

        text-transform: uppercase;

        left: 0px;

    }

      <?php } 

    else{ ?>

      .body_bg{

      }

      <?php

    }

  } 

  elseif($segment1 == 'p30' || $segment1 == 'p30month_manage' || $segment1 == 'p30_select_month') { 

    if($segment1 == 'p30_select_month') { ?>

    .body_bg{

        background: #03d4b7;

        width:2030px !important;

      }

      .page_title{

        width: 100% !important;

          height: auto;

          float: left !important;

          position: fixed !important;

          top: 84px !important;

        font-weight: bold;

        text-align: left;

        padding: 5px 0px;

        margin-bottom: 20px;

        color: #000;

        font-size: 15px;

        text-transform: uppercase;

        left: 0px;

    }

      <?php } 

    else{ ?>

      .body_bg{

        background: #03d4b7;

        

      }

      <?php

    }

  }

  elseif($segment1 == 'gbs_p30' || $segment1 == 'gbs_p30month_manage' || $segment1 == 'gbs_p30_select_month') { 

    if($segment1 == 'gbs_p30_select_month') { ?>

    .body_bg{

        background: #03d4b7;

        width:2030px !important;

      }

      .page_title{

        width: 100% !important;

          height: auto;

          float: left !important;

          position: fixed !important;

          top: 84px !important;

        font-weight: bold;

        text-align: left;

        padding: 5px 0px;

        margin-bottom: 20px;

        color: #000;

        font-size: 15px;

        text-transform: uppercase;

        left: 0px;

    }

      <?php } 

    else{ ?>

      .body_bg{

        background: #03d4b7;

        

      }

      <?php

    }

  } 

  ?>

.dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover

{

      background-color: #000 !important;

      background-image:none !important;

      color:#fff !important;

}



.dropdown-submenu {

    position: relative;

}



.dropdown-submenu > a.dropdown-item:after {

    content: "\f054";

    float: right;

}

.dropdown-submenu > a.dropdown-item:after {

    content: ">";

    float: right;

    font-weight: 800;

    margin-right: -10px;

}

.dropdown-submenu > .dropdown-menu {

    top: 0;

    left: 100%;

    margin-top: 0px;

    margin-left: 0px;

}

.dropdown-submenu:hover > .dropdown-menu {

    display: block;

}

.dropdown-menu

{

  width:200px;

}

.dropdown-header{

  font-size: 16px;

}

.modal_load_apply, .modal_load, .modal_load_content, .modal_load_available, .modal_load_browse, .modal_load_review, .modal_load_import {z-index: 999999999999999999999999999999999999 !important;}

.modal{ margin-top:4%; }

.dropdown-toggle:hover, .dropdown-toggle:active, .dropdown-toggle:focus { text-decoration: underline !important; }



.navbar-default{background: #e7e7e7}



.ui-autocomplete{z-index:999999999999999999999999999999999999 !important; }

.modal_max_height{max-height: 700px; overflow:hidden; overflow-y: scroll; }
.modal_max_height_400{max-height: 400px; overflow:hidden; overflow-y: scroll; }
.modal_max_height_350{max-height: 350px; overflow:hidden; overflow-y: scroll; }

  .disabled_input{
    background: #dfdfdf !important;
  }
</style>

</head>

<body class="body_bg">

<div class="modal fade modal_outstanding_payment_receipt" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title modal_outstanding_payment_receipt_title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body" style="clear:both">
          <table class="table own_table_white">
            <tbody id="tbody_outstanding_payment_receipt">
            </tbody>
          </table>
          <div id="outstanding_payment_receipt_message"></div>
          <input type="hidden" id="tbody_outstanding_payment_receipt_type" readonly name="">
          <input type="hidden" id="tbody_outstanding_payment_receipt_id" readonly name="">
      </div>
      <div class="modal-footer" style="clear:both">
        <div class="row">
          <div class="col-lg-6">
            <a href="javascript:" class="common_black_button outstanding_payment_receipt_delete" style="float: left;">Yes</a>
            <a href="javascript:" class="common_black_button" data-dismiss="modal" aria-label="Close" style="float: left;">No</a>
            
          </div>
          <div class="col-lg-6">
            <a href="javascript:" class="common_black_button outstanding_payment_receipt_download" style="float: right;">Download PDF</a>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</div>



<div class="modal fade journal_source_viewer_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top:13%">

  <div class="modal-dialog modal-sm" role="document" style="width:30%">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">Journal Source</h4>

      </div>

      <div class="modal-body">

          <table class="table own_table_white">

            <thead class="header">

                <th>Code</th>

                <th>Journal Source</th>

            </thead>

            <tbody>

              <tr>

                <td>BOB</td>

                <td>BANK OPENING BALANCE</td>

              </tr>

              <tr>

                <td>CFA</td>

                <td>CLIENT FINANCE ACCOUNT</td>

              </tr>

              <tr>

                <td>GJ</td>

                <td>GENERAL JOURNAL</td>

              </tr>

              <tr>

                <td>SI</td>

                <td>SALES INVOICE</td>

              </tr>

              <tr>

                <td>PI</td>

                <td>PURCHASE INVOICE</td>

              </tr>

              <tr>

                <td>PAY</td>

                <td>PAYMENT SYSTEM</td>

              </tr>

              <tr>

                <td>RCPT</td>

                <td>RECEIPTS SYSTEM</td>

              </tr>
              <tr>

                <td>POB</td>

                <td style="text-transform: uppercase;">Purchase / Supplier Opening Balance</td>

              </tr>
              <tr>

                <td>SOB</td>

                <td style="text-transform: uppercase;">Sales / Customer Opening Balance</td>

              </tr>

            </tbody>

          </table>

      </div>

      <div class="modal-footer">

      </div>

    </div>

  </div>

</div>

<div class="modal fade question_mark_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top:13%">

  <div class="modal-dialog modal-sm" role="document" style="width:30%">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" id="myModalLabel">Process of Posting Initial Journals</h4>

      </div>

      <div class="modal-body">

          <p>A) Set Bank Opening Balance and Save</p>

          <p>B) Set Client Opening Balance and post</p>

          <p>C) Set Supplier Opening Balances and Post</p>

          <p>D) Set the Client Account Opening balances and Post</p>

          <p>E) Post Nominals for Invoices Imported into the System</p>

      </div>

      <div class="modal-footer">

      </div>

    </div>

  </div>

</div>

<div class="modal fade journal_viewer_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 9999999 !important;">

  <div class="modal-dialog modal-sm" role="document" style="width:70%;">

    <div class="modal-content">          

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" style="font-weight:700;font-size:20px">Journal Viewer</h4>

      </div>

      <div class="modal-body modal_max_height" style="min-height: 100px;">  

        <div class="col-md-12">

          <label style="float:left;margin-top:10px;margin-right:10px">Enter Journal ID:</label>

          <input type="text" name="journal_viewer_text_id" class="form-control journal_viewer_text_id" id="journal_viewer_text_id" placeholder="Enter Journal ID" value="" style="width:15%;float:left;">

          <input type="button" class="common_black_button journal_viewer_btn" value="Load">

          <input type="button" class="common_black_button load_journal_viewer_pdf" value="Download PDF" style="float:right">
          <input type="button" class="common_black_button class_general_journal" value="General Journal" style="float:right">


        </div>

        <table class="table own_table_white" style="margin-top:55px">

          <thead>

            <th>Journal ID</th>

            <th>Date</th>

            <th>Journal Description</th>

            <th>Journal Source</th>

            <th>Nominal Code</th>

            <th>Nominal Description</th>

            <th style="text-align: right;">Debit Value</th>

            <th style="text-align: right;">Credit Value</th>

          </thead>

          <tbody id="journal_viewer_tbody">



          </tbody>

          <tr>

            <td colspan="6" style="text-align: right;font-weight:800">Total:</td>

            <td class="journal_viewer_debit_total" style="text-align: right;font-weight:800"></td>

            <td class="journal_viewer_credit_total" style="text-align: right;font-weight:800"></td>

          </tr>

        </table>

      </div>

      <div class="modal-footer">

        <input type="button" class="common_black_button" data-dismiss="modal" aria-label="Close" value="Cancel">

      </div>

    </div>

  </div>

</div>

<div class="modal fade payroll_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 9999999 !important;">

  <div class="modal-dialog modal-lg" role="document" style="width:70%;">

    <div class="modal-content">          

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <h4 class="modal-title" style="font-weight:700;font-size:20px">Payroll Settings</h4>

      </div>

      <div class="modal-body" style="clear:both"> 



        <ul class="nav nav-tabs" id="myTab" role="tablist">

          <li class="nav-item active">

            <a class="nav-link active" id="setemail-tab" data-toggle="tab" href="#setemail" role="tab" aria-controls="setemail" aria-selected="true">Email Settings</a>

          </li>

          <li class="nav-item">

            <a class="nav-link" id="setnotify-tab" data-toggle="tab" href="#setnotify" role="tab" aria-controls="setnotify" aria-selected="false">Notification Message</a>

          </li>

        </ul>

        <div class="tab-content" id="myTabContent">

          <div class="tab-pane fade active in" id="setemail" role="tabpanel" aria-labelledby="setemail-tab">

            <div class="admin_content_section" style="margin-top:10px">  

              <div>

                <div class="table-responsive">

                  <div>

                    <?php

                      $admin_details = DB::table('admin')->first();

                    if(Session::has('message')) { ?>

                        <p class="alert alert-info"><?php echo Session::get('message'); ?></p>

                    <?php }

                    ?>

                  </div>

                  <div class="col-lg-12 text-left padding_00">

                    <form name="payroll_settings_form" id="payroll_settings_form" method="post" action="<?php echo URL::to('user/save_payroll_settings'); ?>">

                      <h4>Enter Email Signature:</h4>

                      <textarea name="message_editor" id="editor999"><?php echo $admin_details->payroll_signature; ?></textarea>

                      <h4>Enter CC Box:</h4>

                      <input type="text" name="payroll_cc_input" class="form-control payroll_cc_input" value="<?php echo $admin_details->payroll_cc_email; ?>">

                      

                      <div class="modal-footer">  

                          <input type="submit" name="submit_payroll_settings" class="common_black_button submit_payroll_settings" value="Submit">

                      </div>

                    </form>

                  </div>

                </div>

              </div>

            </div>

          </div>

          <div class="tab-pane fade" id="setnotify" role="tabpanel" aria-labelledby="setnotify-tab">

            <form action="<?php echo URL::to('user/update_user_notification'); ?>" method="post" id="update_user_form">

            <textarea class="form-control input-sm" id="editor_9999"  name="user_notification" style="height:100px"><?php echo $admin_details->notify_message; ?></textarea>

            <div class="row">

              <div class="col-md-12" style="text-align:center; margin-top:20px">

                  <input type="submit" name="notify_submit" id="notify_submit" class="btn common_black_button" value="Update">

              </div>

            </div>

          </form>

          </div>

        </div>

          <!-- Content Header (Page header) -->

          

      </div>

      <div class="modal-footer" style="clear:both">

        <input type="button" class="common_black_button" data-dismiss="modal" aria-label="Close" value="Cancel">

      </div>

    </div>

  </div>

</div>

<div class="modal fade receipt_payment_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 99999999;margin-top:15%">
  <div class="modal-dialog modal-lg" role="document" style="width: 80%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title receipt_payment_title" id="myModalLabel"></h4>
      </div>
      <div class="modal-body">
          <table class="table own_table_white" id="result_payment_receipt" style="background: #fff;"></table>
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>

<?php

$suppliers = DB::table('suppliers')->orderBy('supplier_name','asc')->get();

$nominal_codes = DB::table('nominal_codes')->orderBy('code','asc')->get();

$vat_rates = DB::table('supplier_vat_rates')->get();

?>



<div class="top_row" style="z-index: 999999999999999999999999999999999">

  <div class="col-lg-12 padding_00" style="height:84px">

    <nav class="navbar navbar-default">

      <div class="container-fluid">

        <!-- Brand and toggle get grouped for better mobile display -->

        <div class="navbar-header">

          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">

            <span class="sr-only">Toggle navigation</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

          </button>

          <a class="navbar-brand" href="<?php echo URL::to('user/dashboard')?>"><img src="<?php echo URL::to('assets/images/bubble_logo.png')?>" class="img-responsive" /></a>

        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->

        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

          <ul class="nav navbar-nav navbar-right menu" style="margin-right: 1%;">



            <li>

              <div style="float: left; margin-right: 10px; padding-top: 8px; margin-top: 5px; color:#777">Client Name:</div>

              <div style="float:left; width: 400px; margin-top: 5px;">

                <input type="text" class="form-control top_client_common_search ui-autocomplete-input" placeholder="Enter Client Name" name="">

                <input type="hidden" id="client_search_hidden_top_menu" name="">

              </div>

            </li>

            <li class="dropdown" style="margin-right: 10px;"><a href="javascript:" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" style="padding: 8px 13px; margin-top: 5px "><i style="font-size: 20px;" class="fa fa-caret-down" aria-hidden="true"></i>

</a>

              <ul class="dropdown-menu">

                

                <li class="<?php if(($segment1 == "client_management")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/client_management'); ?>">Client Mangement</a></li>

                <li class="<?php if(($segment1 == "client_account_review")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/client_account_review'); ?>">Client Account Review</a></li>

                <li class="<?php if(($segment1 == "in_file" || $segment1 == "in_file_advance" || $segment1 == "infile_search")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/in_file_advance')?>">InFiles</a></li>

                <li class="<?php if(($segment1 == "client_request_system" || $segment1 == "client_request_manager" || $segment1 == "client_request_view" || $segment1 == "client_request_edit")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/client_request_system'); ?>">Client Request System</a></li>

                <li class="<?php if(($segment1 == "ta_system" || $segment1 == "ta_allocation")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/ta_system')?>">TA System</a></li>

              </ul>

            </li>





            <li class="dropdown <?php if($segment1 == "client_management" || $segment1 == "invoice_management" || $segment1 == "statement_list" || $segment1 == "receipt_management" || $segment1 == "receipt_settings" || $segment1 == "time_management" || $segment1 == "aml_system" || $segment1 == "opening_balance_manager" || $segment1 == "client_opening_balance_manager" || $segment1 == "import_opening_balance_manager" || $segment1 == "two_bill_manager" || $segment1 == "client_account_review" || $segment1 == "financials" || $segment1 == "supplier_management" || $segment1 == "supplier_invoice_management" || $segment1 == "payment_management") { echo 'active'; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Practice Functions <i class="fa fa-caret-down" aria-hidden="true"></i>

</a>

                <ul class="dropdown-menu dropdown-menu-right text-right" style="width: 150%;">

                    <li class="dropdown-header">

                      Clients

                    </li>

                    <li role="separator" class="divider"></li>

                    <li class="<?php if(($segment1 == "client_management")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/client_management'); ?>">Client Mangement</a></li>

                    <li class="<?php if(($segment1 == "client_account_review")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/client_account_review'); ?>">Client Account Review</a></li>

                    <li class="<?php if(($segment1 == "aml_system")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/aml_system'); ?>">AML System</a></li>

                    



                    <li role="separator" class="divider"></li>

                    <li class="dropdown-header">

                      Accounts

                    </li>

                    <li role="separator" class="divider"></li>

                    <li class="<?php if(($segment1 == "invoice_management")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/invoice_management'); ?>">Invoice Management</a></li>

                    <li class="<?php if(($segment1 == "receipt_management" || $segment1 == "receipt_settings")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/receipt_management'); ?>">Receipt Management</a></li>

		    <li class="<?php if(($segment1 == "payment_management" || $segment1 == "payment_settings")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/payment_management'); ?>">Payment Management</a></li>

                    <li class="<?php if(($segment1 == "two_bill_manager")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/two_bill_manager'); ?>">2Bill Manager</a></li>

                    <li class="<?php if(($segment1 == "financials")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/financials'); ?>">Practice Financials</a></li>

                    <li class="<?php if(($segment1 == "statement_list")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/statement_list'); ?>">Client Statements</a></li>



                    <li role="separator" class="divider"></li>

                    <li class="dropdown-header">

                      Suppliers & Payables

                    </li>

                    <li role="separator" class="divider" style="height:2px"></li>

                    <li class="<?php if(($segment1 == "supplier_management")) { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/supplier_management'); ?>">Supplier Management</a></li>

                    <li class="<?php if($segment1 == "supplier_invoice_management") { echo "active"; } else { echo ""; } ?>" style="padding-left: 14px;"><a href="<?php echo URL::to('user/supplier_invoice_management'); ?>">Supplier Invoice Management</a></li>

                </ul>

            </li>

            <li class="dropdown <?php if($segment1 == "manage_week" || $segment1 == "week_manage" || $segment1 == "select_week" || $segment1 == "manage_month" || $segment1 == "month_manage" || $segment1 == "select_month" || $segment1 == "p30" || $segment1 == "p30month_manage" || $segment1 == "p30_select_month" || $segment1 == "paye_p30month_manage" || $segment1 == "paye_p30_select_month" || $segment1 == "paye_p30_manage" || $segment1 == "paye_p30_ros_liabilities" || $segment1 == "paye_p30_email_distribution") { echo 'active'; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Payroll Functions <i class="fa fa-caret-down" aria-hidden="true"></i>

</a>

<?php 

  $current_year = DB::table('year')->orderBy('year_id','desc')->first();

  $current_week = DB::table('week')->where('year',$current_year->year_id)->orderBy('week_id','desc')->first();

  $current_month = DB::table('month')->where('year',$current_year->year_id)->orderBy('month_id','desc')->first();



?>

                <ul class="dropdown-menu" style="width:249px">

                    <li class="dropdown-header">

                      Weekly payroll Section

                    </li>

                    <li role="separator" class="divider"></li>

                    <li class="<?php if(($segment1 == "manage_week") || ($segment1 == "week_manage") || ($segment1 == "select_week")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/manage_week')?>">Weekly Payroll Manager</a></li>

                    <li><a href="<?php echo URL::to('user/week_manage/'.base64_encode($current_year->year_id).'')?>">Current Year</a></li>

                    <li><a href="<?php echo URL::to('user/select_week/'.base64_encode($current_week->week_id).'')?>">Current Period</a></li>

                    <li role="separator" class="divider"></li>

                    <li class="dropdown-header">

                      Monthly payroll Section

                    </li>

                    <li role="separator" class="divider"></li>

                    <li class="<?php if(($segment1 == "manage_month") || ($segment1 == "month_manage") || ($segment1 == "select_month")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/manage_month')?>">Monthly Payroll</a></li>

                    <li><a href="<?php echo URL::to('user/month_manage/'.base64_encode($current_year->year_id).'')?>">Current Year</a></li>

                    <li><a href="<?php echo URL::to('user/select_month/'.base64_encode($current_month->month_id).'')?>">Current Period</a></li>

                    <li role="separator" class="divider"></li>

                    <li class="<?php if(($segment1 == "p30") || ($segment1 == "p30month_manage" || $segment1 == "paye_p30month_manage" || $segment1 == "paye_p30_select_month" || $segment1 == "paye_p30_manage" || $segment1 == "paye_p30_ros_liabilities" || $segment1 == "paye_p30_email_distribution") || ($segment1 == "p30_select_month")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/p30'); ?>">PAYE Modern Reporting System</a></li>

                    <li><a href="javascript:" class="payroll_settings_btn">Payroll Settings</a></li>

                </ul>

            </li>



            <li class="dropdown <?php if($segment1 == "vat_clients" || $segment1 == "vat_review" || $segment1 == "rct_system" || $segment1 == "rct_liability_assessment" || $segment1 == "rct_client_manager" || $segment1 == "gbs_p30" || $segment1 == "gbs_p30month_manage" || $segment1 == "gbs_p30_select_month" || $segment1 == "year_end_manager" || $segment1 == "yearend_setting" || $segment1 == "supplementary_manager" || $segment1 == "yeadend_clients" || $segment1 == "yearend_individualclient" || $segment1 == "supplementary_note_create" || $segment1 == "gbs_paye_p30month_manage" || $segment1 == "gbs_paye_p30_select_month" || $segment1 == "yeadend_liability" || $segment1 == "client_request_system" || $segment1 == "client_request_manager" || $segment1 == "client_request_edit" || $segment1 == "client_request_view" || $segment1 == "manage_croard" || $segment1 == "directmessaging" || $segment1 == "directmessaging_page_two" || $segment1 == "directmessaging_page_three" || $segment1 == "messageus_groups" || $segment1 == "messageus_saved_messages" || $segment1 == "rct_summary") { echo 'active'; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Client Functions <i class="fa fa-caret-down" aria-hidden="true"></i>

</a>

                <ul class="dropdown-menu">

                    <li class="<?php if(($segment1 == "year_end_manager"  || $segment1 == "yeadend_clients"  || $segment1 == "yearend_individualclient"  || $segment1 == "yeadend_liability")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/year_end_manager'); ?>">Year End Manager</a></li>

                    <li class="<?php if(($segment1 == "client_request_system" || $segment1 == "client_request_manager" || $segment1 == "client_request_view" || $segment1 == "client_request_edit")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/client_request_system'); ?>">Client Request System</a></li>

                    <li class="<?php if($segment1 == "directmessaging" || $segment1 == "directmessaging_page_two" || $segment1 == "directmessaging_page_three" || $segment1 == "messageus_groups" || $segment1 == "messageus_saved_messages") { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/directmessaging')?>">MessageUs System</a></li>

                    <li class="<?php if(($segment1 == "vat_clients")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/vat_clients')?>">VAT Management</a></li>

                    <li class="<?php if(($segment1 == "rct_system") || ($segment1 == "rct_liability_assessment") || ($segment1 == "rct_client_manager")  || ($segment1 == "rct_summary")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/rct_system')?>">RCT System</a></li>

                    <li class="<?php if(($segment1 == "manage_croard")) { echo "active"; } else { echo ""; } ?>"><a 

                      href="<?php echo URL::to('user/manage_croard'); ?>">CRO ARD</a></li>

                </ul>

            </li>

            <li class="<?php if(($segment1 == "time_me" || $segment1 == "time_task" || $segment1 == "time_me_overview" || $segment1 == "time_me_joboftheday" || $segment1 == "time_me_client_review" || $segment1 == "time_me_all_job" || $segment1 == "time_track" || $segment1 == "ta_system" || $segment1 == "ta_allocation")) { echo "active"; } else { echo ""; } ?>"><a href="javascript:" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Time Me <i class="fa fa-caret-down" aria-hidden="true"></i>

</a>

              <ul class="dropdown-menu">

                <li class="<?php if(($segment1 == "time_track")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/time_track')?>">TimeMe Manager</a></li>

                <li class="<?php if(($segment1 == "time_me_overview" || $segment1 == "time_me_joboftheday" || $segment1 == "time_me_client_review" || $segment1 == "time_me_all_job")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/time_me_overview')?>">TimeMe Overview</a></li>

                <li class="<?php if(($segment1 == "time_task")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/time_task')?>">Tasks</a></li>

                <li class="<?php if(($segment1 == "ta_system" || $segment1 == "ta_allocation")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/ta_system')?>">TA System</a></li>

              </ul>

            </li>

            <li class="<?php if($segment1 == "task_manager" || $segment1 == "taskmanager_search" || $segment1 == "task_administration" || $segment1 == "park_task") { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/task_manager')?>">Task Manager</a></li>

            <li class="<?php if(($segment1 == "in_file" || $segment1 == "in_file_advance" || $segment1 == "infile_search")) { echo "active"; } else { echo ""; } ?>"><a href="<?php echo URL::to('user/in_file_advance')?>">InFiles</a>

            </li>



            

            <?php

            $admindetails = DB::table('admin')->first();

            $username = $admindetails->username;

            $password = base64_decode($admindetails->pass_base);

            ?>

            <!-- <li class="<?php //if(($segment1 == "p30") || ($segment1 == "p30month_manage")) { echo "active"; } else { echo ""; } ?>"><a href="<?php //echo URL::to('user/p30')?>">P30 Task</a></li> -->

            <!-- <li><a href="<?php echo URL::to('admin')?>">Admin Login</a></li> -->

            <li><a href="<?php echo URL::to('admin/adminlogin?username='.$username.'&password='.$password.'')?>">Admin</a></li>

            <li><a href="<?php echo URL::to('user/logout')?>">Logout</a></li>

          </ul>

        </div><!-- /.navbar-collapse -->

      </div><!-- /.container-fluid -->

  </nav>

  </div>

</div>

@yield('content')

<select name="select_nominal_codes_dummy" class="form-control select_nominal_codes_dummy" style="display:none">
  <option value="">Select Nominal Code</option>
  <?php
  if(count($nominal_codes)) {
    foreach($nominal_codes as $code){
      echo '<option value="'.$code->id.'">'.$code->code.' - '.$code->description.'</option>';
    }
  }
  ?>
</select>
<select name="select_vat_rates_dummy" class="form-control select_vat_rates_dummy" style="display:none">
  <option value="">Select VAT Rate</option>
  <?php
  if(count($vat_rates)) {
    foreach($vat_rates as $rate){
      echo '<option value="'.$rate->vat_rate.'">'.$rate->vat_rate.' %</option>';
    }
  }
  ?>
</select>

<div class="modal fade add_purchase_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false"  style="z-index: 9999999999999999;">

  <div class="modal-dialog modal-lg" role="document" style="width:80%">

    <div class="modal-content">

      <form name="add_purchase_invoice_form" id="add_purchase_invoice_form" method="post" action="<?php echo URL::to('user/store_purchase_invoice'); ?>">

        <div class="modal-header">

          <button type="button" class="close close_purchase_invoice"><span class="close_purchase_invoice" aria-hidden="true">&times;</span></button>

          <h4 class="modal-title">Add Purchase Invoice</h4>

        </div>

        <div class="modal-body modal_max_height" style="clear:both">

          <div class="col-md-10">

            <table class="table" style="width:100%">

              <thead>

                <tr>

                  <td style="width:11%;font-weight:600;vertical-align: middle;">Select Supplier: </td> 

                  <td style="width:22%">

                    <select name="select_supplier" class="form-control select_supplier select_supplier_invoice" required>

                      <option value="">Select Supplier</option>

                      <?php

                      if(count($suppliers)){

                        foreach($suppliers as $supplier){

                          echo '<option value="'.$supplier->id.'">'.$supplier->supplier_name.'</option>';

                        }

                      }

                      ?>

                    </select>

                  </td>

                  <td style="width:11%">
                    <a href="javascript:" class="common_black_button add_supplier_invoice" style="font-size:14px;font-weight: bold; float: left; margin-top:-1px ">Add Supplier</a>
                  </td>

                  <td style="width:22%"></td>

                  <td style="width:11%"></td>

                  <td style="width:22%"></td>

                </tr>

              </thead>

              <tbody id="supplier_detail_tbody">

                <tr>

                  <td style="font-weight:600">Supplier Code: </td> 

                  <td class="supplier_code_td"> </td>

                  <td style="font-weight:600">Supplier Name: </td> 

                  <td class="supplier_name_td"></td>

                  <td style="font-weight:600">Web Url: </td> 

                  <td class="web_url_td"> </td>

                </tr>

                <tr>

                  <td style="font-weight:600">Phone No: </td> 

                  <td class="phone_no_td"> </td>

                  <td style="font-weight:600">Email: </td>

                  <td class="email_td"></td>

                  <td style="font-weight:600">IBAN: </td> 

                  <td class="iban_td"> </td>

                </tr>

                <tr>

                  <td style="font-weight:600">BIC: </td> 

                  <td class="bic_td"> </td>

                  <td style="font-weight:600">VAT No: </td> 

                  <td class="vat_no_td"> </td>

                  <td></td>

                  <td></td>

                </tr>

              </tbody>

            </table>

          </div>

          <div class="col-md-2 text-right">

            <a href="javascript:" class="common_black_button transaction_list">Load Transaction List</a>

          </div>

          <div class="col-md-12">

            <div class="col-md-12" style="background: #E0BBE4">

              <h4 style="font-weight:600">Purchase Invoice Header Information</h4>

              <input type="hidden" name="hidden_global_id" id="hidden_global_id" value="">

              <input type="hidden" name="hidden_sno" id="hidden_sno" value="">

              <table class="table">

                <thead>

                  <th style="width:10%">Invoice No</th>

                  <th style="width:15%">Invoice Date</th>

                  <th style="width:15%">Reference</th>

                  <th style="width:15%">Net Value</th>

                  <th style="width:15%"></th>

                  <th style="width:15%">VAT</th>

                  <th style="width:15%">Gross</th>

                </thead>

                <tbody id="global_invoice_tbody">

                  <tr>

                    <td><input type="text" name="inv_no_global" class="form-control inv_no_global" value="" placeholder="Enter Invoice No" required></td>

                    <td><input type="text" name="inv_date_global" class="form-control inv_date_global" value="" placeholder="Enter Invoice Date" required></td>

                    <td><input type="text" name="ref_global" class="form-control ref_global" value="" placeholder="Enter Reference" required></td>

                    <td><input type="text" name="net_global" class="form-control net_global" value="" placeholder="Enter Net Value" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" onpaste="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" required></td>

                    <td></td>

                    <td><input type="text" name="vat_global" class="form-control vat_global" value="" placeholder="Enter VAT Value" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" onpaste="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" required></td>

                    <td><input type="text" name="gross_global" class="form-control gross_global" value="" placeholder="Enter Gross" readonly required></td>

                  </tr>

                </tbody>

              </table>

              <p id="attachment_global_supplier_tbody">

                <spam class="global_file_upload"></spam>

                <input type="hidden" name="global_file_url" id="global_file_url" value="">

                <input type="hidden" name="global_file_name" id="global_file_name" value="">

                <a href="javascript:" class="fa fa-plus faplus_progress" style="padding:5px;background: #dfdfdf;" title="Add Attachment" data-element=""></a>

              </p>

            </div>

            <div class="col-md-12">

              <h4 style="font-weight:600">Invoice Line Details</h4>

              <table class="table">

                <thead>

                  <th style="width:10%">S.No</th>

                  <th style="width:15%">Description</th>

                  <th style="width:15%">Nominal Code</th>

                  <th style="width:15%">Net Value</th>

                  <th style="width:15%">VAT Rate</th>

                  <th style="width:15%">VAT</th>

                  <th style="width:10%">Gross</th>

                  <th style="width: 5%;">&nbsp;</th>

                </thead>

                <tbody id="detail_tbody">

                  <tr>

                    <td>1</td>

                    <td><input type="text" name="description_detail[]" class="form-control description_detail" value="" placeholder="Enter Description"></td>

                    <td>

                      <select name="select_nominal_codes[]" class="form-control select_nominal_codes">

                        <option value="">Select Nominal Code</option>

                        <?php

                        if(count($nominal_codes)) {

                          foreach($nominal_codes as $code){

                            echo '<option value="'.$code->id.'">'.$code->code.' - '.$code->description.'</option>';

                          }

                        }

                        ?>

                      </select>

                    </td>

                    <td><input type="text" name="net_detail[]" class="form-control net_detail" value="" placeholder="Enter Net Value" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');" onpaste="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');"></td>

                    <td>

                      <select name="select_vat_rates[]" class="form-control select_vat_rates">

                        <option value="">Select VAT Rate</option>

                        <?php

                        if(count($vat_rates)) {

                          foreach($vat_rates as $rate){

                            echo '<option value="'.$rate->vat_rate.'">'.$rate->vat_rate.' %</option>';

                          }

                        }

                        ?>

                      </select>

                    </td>

                    <td><input type="text" name="vat_detail[]" class="form-control vat_detail" value="" placeholder="Enter VAT Value" readonly></td>

                    <td><input type="text" name="gross_detail[]" class="form-control gross_detail" value="" placeholder="Enter Gross" readonly></td>

                    <td class="detail_last_td" style="vertical-align: middle;text-align: center">

                      <a href="javascript:" class="fa fa-plus add_detail_section" title="Add Row"></a>

                    </td>

                  </tr>

                </tbody>

                <tr>

                  <td colspan="3" style="text-align: right;font-weight:600;vertical-align: middle">Total:</td>

                  <td><input type="text" name="total_detail_net" class="form-control total_detail_net" value="" placeholder="Total Net Value" readonly></td>

                  <td></td>

                  <td><input type="text" name="total_detail_vat" class="form-control total_detail_vat" value="" placeholder="Total VAT Value" readonly></td>

                  <td><input type="text" name="total_detail_gross" class="form-control total_detail_gross" value="" placeholder="Total Gross Value" readonly></td>

                </tr>

              </table>

            </div>

          </div>

        </div>

        <div class="modal-footer" style="clear:both">

          <input type="button" name="submit_purchase_invoice" id="submit_purchase_invoice" class="common_black_button submit_purchase_invoice" value="Submit Purchase Invoice">

        </div>

      </form>

    </div>

  </div>

</div>

<div class="modal fade" id="add_supplier_invoice_modal" tabindex="-1" data-backdrop="static"  role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 99999999999999999999; background: rgb(0,0,0,0.8);">
  <div class="modal-dialog modal-lg" role="document" style="width:50%; z-index: 99999999999999999999999; ">
    <div class="modal-content">
      
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add Supplier</h4>
        </div>
        <div class="modal-body" style="clear:both;">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item active">
              <a class="nav-link active" id="first-tab__supplier_invoice" data-toggle="tab" href="#first_supplier_invoice" role="tab" aria-controls="first_supplier_invoice" aria-selected="true">Add Supplier</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="home-tab_supplier_invoice" data-toggle="tab" href="#home_supplier_invoice" role="tab" aria-controls="home_supplier_invoice" aria-selected="true">Supplier Opening Balance</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent_supplier_invoice" style="margin-top:20px;">
              <div class="tab-pane fade active in" id="first_supplier_invoice" role="tabpanel" aria-labelledby="first-tab__supplier_invoice">
                <?php
                $supplier_count_invoice = DB::table('suppliers')->orderBy('id','desc')->first(); 
                if(count($supplier_count_invoice))
                {
                  $count_invoice = substr($supplier_count_invoice->supplier_code,3,7);
                  $count_invoice = sprintf("%04d",$count_invoice + 1);
                }
                else{
                  $count_invoice = sprintf("%04d",1);
                }
                ?>
                  <div class="row" style="margin: 0px;">
                    <div class="col-md-4 col-lg-4">
                      <label>Supplier Code : </label>
                      <div class="form-group">            
                        <input class="form-control supplier_code_class" name="supplier_code" placeholder="Enter Supplier Code" type="text" value="GBS<?php echo $count_invoice; ?>" disabled>
                      </div>
                    </div>
                    <div class="col-md-4 col-lg-4">
                      <label>Supplier Name : </label>
                      <div class="form-group">            
                        <input class="form-control supplier_name_class" name="supplier_name" placeholder="Enter Supplier Name" type="text" required>

                        <label class="error error_supplier_name_class" style="display: none;">Enter Supplier Name</label>
                      </div>
                    </div>
                  </div>

                  <div class="row" style="margin: 0px;">
                    <div class="col-lg-9">
                      <div class="paragraph_title">
                        <div class="para_text">Contact Details</div>
                        <div class="row">
                          <div class="col-md-12 col-lg-12">
                            <label>Supplier Address : </label>
                            <div class="form-group">            
                              <input class="form-control supp_address_class" name="supp_address" placeholder="Enter Supplier Address" type="text">
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4">
                            <label>Web URL : </label>
                            <div class="form-group">            
                              <input class="form-control supplier_address_class" name="supplier_address" placeholder="Enter Web URL" type="text">
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4">
                            <label>Email Address : </label>
                            <div class="form-group">            
                              <input class="form-control supplier_email_class" name="supplier_email" placeholder="Enter Email Address" type="email">
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4">
                            <label>Phone Number : </label>
                            <div class="form-group">            
                              <input class="form-control phone_no_class" name="phone_no" placeholder="Enter Phone Number" type="text" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div class="paragraph_title">
                          <div class="para_text">Banking Details</div>
                          <div class="row">
                            <div class="col-md-12 col-lg-12">
                              <label>Bank Account IBAN : </label>
                              <div class="form-group">            
                                <input class="form-control supplier_iban_class" name="supplier_iban" placeholder="Enter Bank Account IBAN" type="text">
                              </div>
                            </div>
                            <div class="col-md-12 col-lg-12">
                              <label>Bank Account BIC : </label>
                              <div class="form-group">            
                                <input class="form-control supplier_bic_class" name="supplier_bic" placeholder="Enter Bank Account BIC" type="text">
                                <input type="hidden" name="supplier_count" class="supplier_count_class" value="GBS<?php echo $count_invoice; ?>">
                              </div>
                            </div>
                          </div>
                        </div>
                    </div>
                  </div>

                  <div class="row" style="margin: 0px;">
                    <div class="col-lg-9">
                      <div class="paragraph_title">
                        <div class="para_text">General</div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>VAT Number : </label>
                            <div class="form-group">            
                              <input class="form-control vat_number_class" name="vat_number" placeholder="Enter VAT Number" type="text">
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4">
                            <label>Currency : </label>
                            <div class="form-group">            
                              <input class="form-control currency_class" name="currency" placeholder="Enter Currency" type="text">
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4">
                            <label>Default Nominal :</label>
                            <div class="form-group">
                              <select class="form-control default_nominal_supplier_class" name="default_nominal">
                                <option value="">Default Nominal</option>
                                <?php
                                $nominal_codes = DB::table('nominal_codes')->orderBy('code','asc')->get();
                                if(count($nominal_codes)) {
                                  foreach($nominal_codes as $code){
                                    echo '<option value="'.$code->id.'">'.$code->code.' - '.$code->description.'</option>';
                                  }
                                }
                                ?>
                              </select> 
                            </div>
                          </div>                        
                        </div>
                        <div class="row">
                          <div class="col-md-4 col-lg-4">
                            <label>Account Username :</label>
                            <div class="form-group">
                              <input type="text" class="form-control ac_username_class" placeholder="Enter Username" name="ac_username">
                            </div>
                          </div>
                          <div class="col-md-4 col-lg-4">
                            <label>Account Password :</label>
                            <div class="form-group">
                              <input type="text" class="form-control ac_password_class"  placeholder="Enter Password" name="ac_password">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>

                  



                  
                  
              </div>
              <div class="tab-pane fade" id="home_supplier_invoice" role="tabpanel" aria-labelledby="home-tab_supplier_invoice">
                  <div class="col-md-12 col-lg-12">
                    <label>Opening Balance : </label>
                    <div class="form-group">            
                      <input class="form-control opening_balance_class" name="opening_balance" placeholder="Enter Opening Balance" type="text" value="" oninput="this.value = this.value.replace(/[^0-9.-]/g, '').replace(/(\..*)\./g, '$1');">
                    </div>
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer" style="clear:both">
          <input type="hidden" name="supplier_id" id="supplier_id_invoice" value="">
          <input type="submit" class="common_black_button submit_module_update_invoice" value="Submit">
        </div>
      
    </div>
  </div>
</div>

<div class="modal fade view_purchase_invoice_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel"  style="z-index: 9999999999999999;">

  <div class="modal-dialog modal-lg" role="document" style="width:80%">

    <div class="modal-content">

        <div class="modal-header">

         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

          <h4 class="modal-title">View Invoice</h4>

        </div>

        <div class="modal-body modal_max_height" style="clear:both">
          <div class="col-md-12">
            <div class="col-md-12">

              <h4 style="font-weight:600">Invoice Line Details</h4>

              <table class="table">

                <thead>

                  <th style="width:10%">S.No</th>

                  <th style="width:15%">Description</th>

                  <th style="width:15%">Nominal Code</th>

                  <th style="width:15%">Net Value</th>

                  <th style="width:15%">VAT Rate</th>

                  <th style="width:15%">VAT</th>

                  <th style="width:10%">Gross</th>

                  <th style="width: 5%;">&nbsp;</th>

                </thead>

                <tbody id="detail_tbody_view">

                </tbody>
              </table>

            </div>

          </div>

        </div>

        <div class="modal-footer" style="clear:both">

         

        </div>

      </form>

    </div>

  </div>

</div>

<div class="modal fade dropzone_global_supplier_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">

  <div class="modal-dialog modal-sm" role="document" style="width:30%">

        <div class="modal-content">

          <div class="modal-header">

            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Add Attachments</h4>

          </div>

          <div class="modal-body" style="min-height:280px">  

              <div class="img_div_supplier">

                 <div class="image_div_attachments_supplier">

                    <form action="<?php echo URL::to('user/supplier_upload_global_files'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUpload200" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">

                      <input type="hidden" name="hidden_global_inv_id" id="hidden_global_inv_id" value="">

                    </form>

                 </div>

              </div>

          </div>

          <div class="modal-footer">  

            <a href="javascript:" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>

          </div>

        </div>

  </div>

</div>

<div class="modal fade" id="transaction_list_modal_invoice" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="z-index: 99999999999;">

  <div class="modal-dialog modal-lg" role="document" style="width:70%">

    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>

        <a href="javascript:" name="export_transaction_list" class="common_black_button export_transaction_list" id="export_transaction_list" style="float: right;margin-right: 10px;">Export as CSV</a>

        <h4 class="modal-title">Transaction List</h4>

      </div>

      <div class="modal-body" id="supplier_info_tbody_invoice" style="clear:both; overflow: unset;">



      </div>

      <div class="modal-footer" style="clear:both">



      </div>

    </div>

  </div>

</div>

<div class="modal fade reconcile_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:70%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Reconcile</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div style="float: left; margin-right: 10px; margin-left: 15px; padding-top: 9px;">Select Account</div>
          <div class="col-lg-3" style="margin-bottom: 20px;">
            <select class="form-control select_reconcile_bank">
              
              <?php
              $banks = DB::table('financial_banks')->get();
              $output_bank='<option value="">Select Bank</option>';
              if(count($banks)){
                foreach($banks as $bank){

                  $output_bank.='<option value="'.base64_encode($bank->id).'">'.$bank->description.'</option>';
                }
              }
              else{
                $output_bank='<option value="">Select Bank</option>';                
              }

              echo $output_bank;
              ?>
            </select>
            <label class="error error_select_bank" style="display: none;">Please Select Bank</label>
          </div>    
          <div style="float: left; ">
            <a href="javascript:" class="common_black_button reconcile_load" style="float: right;width: 100%">Load</a>
          </div>
        </div>
        <div class="row table_bank_details">
          <div class="col-lg-12">
            <div class="table-responsive">
              <table class="table own_table_white ">
                <thead>
                  <tr>
                    <th>Bank Name</th>
                    <th>Account Name</th>
                    <th>Account Number</th>
                    <th>Description</th>
                    <th>Nominal Code</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="td_bank_name"></td>
                    <td class="tb_ac_name"></td>
                    <td align="right" class="td_ac_number"></td>
                    <td class="td_ac_description"></td>
                    <td class="td_nominal_code"></td>
                  </tr>
                </tbody>
              </table>
            </div>            
          </div>
                      
        </div>
        <div class="row transactions_section" style="display: none;">
          <div class="col-lg-12">
            <h4>TRANSACTIONS GRID:</h4>
            <input type="hidden" class="receipt_id" value="" readonly name="">
              <input type="hidden" class="payment_id" value="" readonly name="">

              <input type="hidden" value="" class="balance_tran_class" >
          </div>
        </div>
        <div class="row transactions_section modal_max_height_350" style="display: none;">
          <div class="col-lg-12">
            
            
            <div class="table-responsive">
              

              <table class="table own_table_white" id="transaction_table">
                <thead>
                  <tr>
                    <th style="display:none"></th>
                    <th style="min-width: 130px !important;">Date</th>
                    <th style="min-width: 400px !important;">Description</th>
                    <th style="min-width: 150px !important; ">Source</th>
                    <th style="text-align: right; min-width: 100px !important;">Value</th>
                    <th style="min-width: 150px !important; text-align: right;">Outstanding Value</th>
                    <th style="min-width: 100px !important; text-align: right;">Journal ID</th>
                    <th style="min-width: 120px !important;">Clearance Date</th>
                  </tr>
                </thead>
                <tbody class="tbody_transaction">
                  
                </tbody>
              </table>
              
            </div>
          </div>          
        </div>

        <div class="row reconcilation_section" style="margin-top: 20px; display: none;">          
          <div class="col-lg-12" style="margin-bottom: 20px;">
            <div class="table-responsive">
              <div style="float: right; width: 236px; font-weight: bold; text-align: right;">
                <a href="javascript:" class="common_black_button accept_all_button" style="float: right; width: 200px;">Accept All as Cleared</a>
              </div>
              <div style="width: 150px; max-width: 150px; float: right; text-align: right;">
                Total: <span class="class_total_outstanding"></span>
                        <input readonly type="hidden" value="" class="input_total_outstanding" >
              </div>
              
            </div>
          </div>


          <div class="col-lg-8">
            <h4>RECONCILATION SECTION:</h4>
            <div class="table-responsive">
              <table class="table own_table_white">                
                <tbody class="tbody_reconcilation">
                  
                </tbody>
              </table>
            </div>
          </div>


          <div class="col-lg-4 reconcilation_report">
            <div class="row">              
              
              <div class="col-lg-4">
                <h4 style="margin-top: 7px; float: right;">Get Report: </h4>
              </div>
              <div class="col-lg-4">
                <a href="javascript:" style="float: right; width: 100%" class="common_black_button reconciliation_pdf">PDF</a>
              </div>
              <div class="col-lg-4">
                <a href="javascript:" style="float: right; width: 100%" class="common_black_button reconciliation_csv">CSV</a>
              </div>

              <div class="col-md-12" style="margin-top: 22px;">
                Note: Rendering all the data in PDF format will take very long time than CSV. E.g. CSV Rendering will complete in few seconds whereas, PDF Rendering may take upto 5 mins based on the data.
              </div>
            </div>
          </div>
        </div>
        
      </div>
      <div class="modal-footer">
        <input type="hidden" name="request_id_email_client" id="request_id_email_client" value="">
      </div>
    </div>
  </div>
</div>
<select class="general_nominal_hidden_for_add_ajax" style="display: none;" required name="general_nominal[]">
  <option value="">Select Nominal Code</option>
  <?php
  if(count($nominal_codes)) {
    foreach($nominal_codes as $code){
      echo '<option value="'.$code->code.'" data-element="'.$code->id.'">'.$code->code.' - '.$code->description.'</option>';
    }
  }
  ?>
</select>

<div class="modal fade general_journal_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="z-index: 99999999 !important; background: rgba(0,0,0,0.3) ">
  <div class="modal-dialog" role="document" style="width:70%;">
    <div class="modal-content">
      <form method="post" action="<?php echo URL::to('user/financials_general_journal_save')?>" id="general_journal_id">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">General Journal</h4>
      </div>
      <div class="modal-body" style="clear:both">
          <div class="row">
            <div class="col-md-2">
              <input type="text" name="general_journal_date" required class="form-control general_journal_date" placeholder="Select Date" value="">
              <label class="error error-general_journal_date"></label>
            </div>
            <div class="col-lg-12">
              <table class="table excel_sheet_table" style="margin-bottom: 0px;">
                <thead>
                  <tr>
                    <th style="width: 300px;">Nominal Code</th>
                    <th>Journal Desription</th>
                    <th style="width: 200px;">Debit Value</th>
                    <th style="width: 200px;">Credit Value</th>
                    <th style="width: 100px;">Action</th>
                  </tr>
                </thead>
                <tbody id="general_journal_tboday">
                  <tr>
                    <td>
                      <select class="general_nominal" required name="general_nominal[]">
                        <option value="">Select Nominal Code</option>

                        <?php
                        if(count($nominal_codes)) {
                          foreach($nominal_codes as $code){
                            echo '<option value="'.$code->code.'" data-element="'.$code->id.'">'.$code->code.' - '.$code->description.'</option>';
                          }
                        }
                        ?>
                      </select>
                      <label class="error error-general-nominal" ></label>
                    </td>
                    <td>
                      <input type="type" class="general_journal_desription" required placeholder="Enter Journal Desription" name="general_journal_desription[]">
                      <label class="error error-general_journal_desription" ></label>
                    </td>
                    <td>
                      <input type="text" style="text-align: right;" class="general_debit" required value="0.00" placeholder="Enter Debit Value" name="general_debit[]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                      <label class="error error-general_debit" ></label>
                    </td>
                    <td>
                      <input type="text" style="text-align: right;" class="general_credit" required value="0.00" placeholder="Enter Credit Value" name="general_credit[]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                      <label class="error error-general_credit" ></label>
                    </td>
                    <td>
                      
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <select class="general_nominal" name="general_nominal[]">
                        <option value="">Select Nominal Code</option>
                        <?php
                        if(count($nominal_codes)) {
                          foreach($nominal_codes as $code){
                            echo '<option value="'.$code->code.'" data-element="'.$code->id.'">'.$code->code.' - '.$code->description.'</option>';
                          }
                        }
                        ?>
                      </select>
                      <label class="error error-general-nominal" ></label>
                    </td>
                    <td>
                      <input type="type" class="general_journal_desription" required placeholder="Enter Journal Desription" name="general_journal_desription[]">
                      <label class="error error-general_journal_desription" ></label>
                    </td>
                    <td>
                      <input type="text" style="text-align: right;" class="general_debit" value="0.00" required placeholder="Enter Debit Value" name="general_debit[]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                      <label class="error error-general_debit" ></label>
                    </td>
                    <td>
                      <input type="text" style="text-align: right;" class="general_credit" value="0.00" required placeholder="Enter Credit Value" name="general_credit[]" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
                      <label class="error error-general_credit" ></label>
                    </td>
                    <td style="text-align: center;">
                      <a href="javascript:" class="fa fa-plus add_general" title="Add" style="margin-top: 10px;"></a>
                      <a href="javascript:" class="fa fa-trash delete_general" style="margin-top: 10px; margin-left: 10px; display: none;"></a>
                    </td>
                  </tr>
                </tbody>
                <tr>
                    <td style="width: 300px; border-top:1px solid #a3a3a3;"></td>
                    <td align="right" style="border-top:1px solid #a3a3a3;">Total:</td>
                    <td style="width: 200px; border-top:1px solid #a3a3a3;">
                      <input type="text" class="general_debit_total" name="" readonly style="text-align: right">
                    </td>
                    <td style="width: 200px; border-top:1px solid #a3a3a3;">
                      <input type="text" class="general_credit_total" name="" readonly style="text-align: right">
                    </td>
                    <td style="width: 100px; border-top:1px solid #a3a3a3;"></td>
                  </tr>
              </table>
            </div>
          </div>
      </div>
      <div class="modal-footer" style="clear:both; border-top:0px;">        
        <input type="button" value="Save" name="" class="common_black_button save_general_journal_button">        
      </div>
      </form>
    </div>
  </div>
</div>
<script>
$.ajaxQueue = [];
var que = $.ajaxQueue;

$.ajaxSetup({
    beforeSend: function(){
        if (this.queue) {
            que.push(this);
        }
        else {
            return true;
        }
        if (que.length > 1) {
            return false;
        }
    },
    complete: function(){
        que.shift();
        var newReq = que[0];
        if (newReq) {
            // setup object creation should be automated 
            // and include all properties in queued AJAX request
            // this version is just a demonstration. 
            var setup = {
                url: newReq.url,
                success: newReq.success
            };
            $.ajax(setup);
        }
    }
});
 CKEDITOR.replace('editor999',

              {

                height: '150px',

                enterMode: CKEDITOR.ENTER_BR,

                  shiftEnterMode: CKEDITOR.ENTER_P,

                  autoParagraph: false,

                  entities: false,

              });

 CKEDITOR.replace('editor_9999',

              {

                height: '150px',

                enterMode: CKEDITOR.ENTER_BR,

                  shiftEnterMode: CKEDITOR.ENTER_P,

                  autoParagraph: false,

                  entities: false,

              });

           initSample(); 

          $("#update_admin_form" ).validate({



              rules: {

                  admin_username : {required:true,},



                  newadmin_password : {required: true,},



                  confirmadmin_password : {required: true, equalTo: newadmin_password},    



              },



              messages: {



                  admin_username : "Username is required",



                  newadmin_password : "New Password is required",



                  confirmadmin_password : {



                      required: "Confirm Password is required",



                      equalTo : "Does not Match the password",



                  },



              },



          });

          $("#update_user_form" ).validate({



              rules: {

                  user_username : {required:true,},



                  newuser_password : {required: true,},



                  confirmuser_password : {required: true, equalTo: newuser_password},    



              },



              messages: {



                  user_username : "Username is required",



                  newuser_password : "New Password is required",



                  confirmuser_password : {



                      required: "Confirm Password is required",



                      equalTo : "Does not Match the password",



                  },



              },



          });

function detectPopupBlocker() {

  var myTest = window.open("about:blank","","directories=no,height=100,width=100,menubar=no,resizable=no,scrollbars=no,status=no,titlebar=no,top=0,location=no");

  if (!myTest) {

    return 1;

  } else {

    myTest.close();

    return 0;

  }

}



function SaveToDisk(fileURL, fileName) {

  

	var idval = detectPopupBlocker();

	if(idval == 1)

	{

		alert("A popup blocker was detected. Please Allow the popups to download the file.");

	}

	else{

		// for non-IE

		if (!window.ActiveXObject) {

		  var save = document.createElement('a');

		  save.href = fileURL;

		  save.target = '_blank';

		  save.download = fileName || 'unknown';

		  var evt = new MouseEvent('click', {

		    'view': window,

		    'bubbles': true,

		    'cancelable': false

		  });

		  save.dispatchEvent(evt);

		  (window.URL || window.webkitURL).revokeObjectURL(save.href);

		}

		// for IE < 11

		else if ( !! window.ActiveXObject && document.execCommand)     {

		  var _window = window.open(fileURL, '_blank');

		  _window.document.close();

		  _window.document.execCommand('SaveAs', true, fileName || fileURL)

		  _window.close();

		}

	}

	$("body").removeClass("loading");

}

</script>

<?php 

if(($segment1 == "opening_balance_manager" || $segment1 == "client_opening_balance_manager" || $segment1 == "import_opening_balance_manager"))

{

	?>

	<div class="footer_row">

		<div class="col-md-4">

			<spam style="float: left;margin-left: 45px;font-size: 17px;font-weight: 700;margin-top:3px">Global Opening Balanace Date: </spam>

			<input type="text" name="global_opening_date" class="input global_opening_date" value="" placeholder="DD-MMM-YYYY" style="float: left;margin-left: 20px;height: 31px;outline: none;">

			<input type="button" class="common_black_button set_global_opening_bal_date_now" name="set_global_opening_bal_date_now" value="Set Now">

		</div>

		<div class="col-md-4">

			© Copyright <?php echo date('Y'); ?> All Rights Reserved Bubble

		</div>

		<div class="col-md-4">

			&nbsp;

		</div>

	</div>

	<?php

}

else{

	?>

	<div class="footer_row">

		© Copyright <?php echo date('Y'); ?> All Rights Reserved Bubble

	</div>

	<?php

}

?>

<script>

$(document).ready(function() {

    $("body").removeClass("loading");

    $(".global_opening_date").datetimepicker({     

       format: 'L',

       format: 'DD-MMM-YYYY',

       widgetPositioning: { horizontal: 'left', vertical: 'top'}

    });

})

// $(document).ajaxComplete(function (event, request, settings) {

//     var str = request.responseText.toLocaleLowerCase();

//     console.log(str);

//     if (str.includes("user login") === true) {

//        //window.location.replace("<?php echo URL::to('/'); ?>");

//     }

// });

$(window).change(function(e) {

  if($(e.target).hasClass('select_user_author')) {

    var value = $(e.target).val();

    if(value != "") {

      $("#author_email-error").hide();  

      $(".author_email").removeClass("error");  

    }

  }

})

$(window).click(function(e) {
if($(e.target).hasClass('taskmanager_rate_input')){
  e.stopPropagation();
    e.preventDefault();
    
  var value = $(e.target).val();
  var id = $(e.target).attr("data-element");
  $(e.target).parents(".taskmanager_rate").find("#hidden_star_rating_taskmanager").val(value);
  $(e.target).parents(".taskmanager_rate:first").find(".taskmanager_rate_input").removeClass('checked_input');
  
  if(value == "5"){
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(4).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(3).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(2).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(1).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(0).addClass("checked_input");
  }
  if(value == "4"){
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(4).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(3).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(2).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(1).addClass("checked_input");
  }
  if(value == "3"){
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(4).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(3).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(2).addClass("checked_input");
  }
  if(value == "2"){
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(4).addClass("checked_input");
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(3).addClass("checked_input");
  }
  if(value == "1"){
    $(e.target).parents(".taskmanager_rate").find(".taskmanager_rate_input").eq(4).addClass("checked_input");
  }

  if(id != ""){
    $.ajax({
      url:"<?php echo URL::to('user/store_user_rating_taskmanager'); ?>",
      type:"post",
      data:{value:value,id:id},
      success:function(result){
        return false;
      }
    })
  }


}
if($(e.target).hasClass('outstanding_payment_receipt_download')){
  var id = $("#tbody_outstanding_payment_receipt_id").val();
  var type = $("#tbody_outstanding_payment_receipt_type").val();
  $("body").addClass("loading");
  setTimeout(function() {
    $.ajax({
        url:"<?php echo URL::to('user/outstanding_payment_receipt_download'); ?>",
        type:"post",          
        data:{id:id, type:type },
        success:function(result){
          SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);          
          $("body").removeClass("loading");
        }
    })
  },500);
}

if($(e.target).hasClass('outstanding_payment_receipt_delete')){

  var id = $("#tbody_outstanding_payment_receipt_id").val();
  var type = $("#tbody_outstanding_payment_receipt_type").val();
  $("body").addClass("loading");
  setTimeout(function() {
    $.ajax({
        url:"<?php echo URL::to('user/outstanding_payment_receipt_delete'); ?>",
        type:"post",
        dataType:"json",
        data:{id:id, type:type },
        success:function(result){
          $(".modal_outstanding_payment_receipt").modal('hide');
          $(".receipt_payment_"+id).detach();
          /*alert(result['message']);*/
          $("body").removeClass("loading");
        }
    })
  },500);

}






if($(e.target).hasClass('delete_outstading')){
  var id = $(e.target).attr("data-element");
  var type = $(e.target).attr("type");

  $.ajax({
    url:"<?php echo URL::to('user/payment_receipdt_delete_outstading'); ?>",
    type:"post",
    dataType:"json",
    data:{id:id,type:type},
    success:function(result){
      $("#outstanding_payment_receipt_message").html(result['message']);
      $("#tbody_outstanding_payment_receipt").html(result['output']);
      $("#tbody_outstanding_payment_receipt_type").val(result['type']);
      $("#tbody_outstanding_payment_receipt_id").val(result['id']);
      $(".modal_outstanding_payment_receipt_title").html(result['title']);
      $(".modal_outstanding_payment_receipt").modal('show');
        
        
    }
  })

}


if($(e.target).hasClass('receipt_viewer_class')){
  var id = $(e.target).attr("data-element");

  setTimeout(function() {
  $.ajax({
      url:"<?php echo URL::to('user/load_single_client_receipt_payment'); ?>",
      type:"post",
      dataType:"json",
      data:{id:id, type:0},
      success: function(result)
      {
        $(".receipt_payment_title").html(result['page_title']);
        $("#result_payment_receipt").html(result['output']);

        $(".receipt_payment_modal").modal('show');        
        $("body").removeClass("loading");           
      }
    })
  },500);

}

if($(e.target).hasClass('payment_viewer_class')){
  var id = $(e.target).attr("data-element");

  setTimeout(function() {
  $.ajax({
      url:"<?php echo URL::to('user/load_single_client_receipt_payment'); ?>",
      type:"post",
      dataType:"json",
      data:{id:id, type:1},
      success: function(result)
      {
        $(".receipt_payment_title").html(result['page_title']);
        $("#result_payment_receipt").html(result['output']);

        $(".receipt_payment_modal").modal('show');        
        $("body").removeClass("loading");           
      }
    })
  },500);

}



  if($(e.target).hasClass('payroll_settings_btn'))

  {

    $(".payroll_settings_modal").modal("show");

  }

  if($(e.target).hasClass('load_journal_viewer_pdf'))

  {

    var journal_id = $(".journal_viewer_text_id").val();

    if(journal_id == "")

    {

      alert("Please Enter the Journal Reference id to Download the PDF");

    }

    else{

      $("body").addClass("loading");

      $.ajax({

        url:"<?php echo URL::to('user/download_journal_viewer_by_journal_id'); ?>",

        type:"post",

        data:{journal_id:journal_id},

        success:function(result)

        {

          SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);

          $("body").removeClass("loading");

        }

      })

    }

  }

  if($(e.target).hasClass('journal_id_viewer'))

  {

    $("body").addClass("loading");

    var journal_id = $(e.target).attr("data-element");

    $.ajax({

      url:"<?php echo URL::to('user/show_journal_viewer_by_journal_id'); ?>",

      type:"post",

      data:{journal_id:journal_id},

      dataType:"json",

      success:function(result)

      {

        var split_journal = journal_id.split(".");

        $("#journal_viewer_tbody").html(result['output']);

        $(".journal_viewer_debit_total").html(result['total_debit']);

        $(".journal_viewer_credit_total").html(result['total_credit']);

        $(".journal_viewer_text_id").val(split_journal[0]);

        $(".journal_viewer_modal").modal("show");

        $("body").removeClass("loading");

      }

    })

  }

  if($(e.target).hasClass('journal_viewer_btn'))

  {

    var journal_id = $(".journal_viewer_text_id").val();

    if(journal_id == "")

    {

      alert("Please Enter the Journal Reference id to Load");

    }

    else{

      $("body").addClass("loading");

      $.ajax({

        url:"<?php echo URL::to('user/show_journal_viewer_by_journal_id'); ?>",

        type:"post",

        data:{journal_id:journal_id},

        dataType:"json",

        success:function(result)

        {

          $("#journal_viewer_tbody").html(result['output']);

          $(".journal_viewer_debit_total").html(result['total_debit']);

          $(".journal_viewer_credit_total").html(result['total_credit']);

          $(".journal_viewer_text_id").val(journal_id);

          $(".journal_viewer_modal").modal("show");

          $("body").removeClass("loading");

        },

        error: function(data)

        {

          $("body").removeClass("loading");

          if(data.status == "500")

          {

            $.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">This Journal ID doesnt exists, please check the Journal ID and then click on the Load Button.</p>',fixed:true,width:"800px"});

          }

        }

      })

    }

  }

  if($(e.target).hasClass('journal_source_link'))

  {

    $(".journal_source_viewer_modal").modal("show");

  }

	if($(e.target).hasClass('set_global_opening_bal_date_now'))

	{

		var global_date = $(".global_opening_date").val();

		if(global_date == "")

		{

			alert("Please enter the Global Opening Date");

		}

		else{

			$.colorbox({html:'<p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green">You are about to set the Global Opening Balance Date to '+global_date+'. Are you sure you want to continue?</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:green"><a href="javascript:" data-element="'+global_date+'" class="common_black_button yes_set_global_date">Yes</a><a href="javascript:" class="common_black_button no_set_global_date">No</a></p>',fixed:true,width:"800px"});

		}

	}

	if($(e.target).hasClass('yes_set_global_date'))

	{

		var global_date = $(".global_opening_date").val();

		$.ajax({

			url:"<?php echo URL::to('user/set_global_opening_bal_date'); ?>",

			type:"post",

			data:{global_date:global_date},

			success: function(result)

			{

        $(".global_opening_date").val("");

        $(".opening_balance_date").val(global_date);

				$.colorbox.close();

        window.location.reload();

			}

		})

	}

  if($(e.target).hasClass('no_set_global_date'))

  {

    $(".global_opening_date").val("");

    $.colorbox.close();

  }

});

</script>

<script type="text/javascript">

$(".top_client_common_search").autocomplete({

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

  delay:1000,

  minLength: 1,

  select: function( event, ui ) {

    $("#client_search_hidden_top_menu").val(ui.item.id);

  }

  });

$(document).ready(function(){
  blurfunction_gj();
  $(".general_journal_date").datetimepicker({
     format: 'L',
     format: 'DD/MM/YYYY',
  });
});
function keypressonlynumber($this)
{
  var replaced_value = $this.value.replace(/[^0-9.-]/g, '').replace(/(\..*?)\..*/g, '$1');
  $($this).val(replaced_value);
}
function blurfunction_gj(){
  $(".general_debit").blur(function(){
      var value = $(this).val();
      if((value == '') || (value == 0)){
        $(this).val('0.00');
        $(this).parents("tr").find(".general_credit").attr("readonly", false).removeClass('disabled_input');
      }  
      else{
        $(this).parents("tr").find(".general_credit").attr("readonly", true).addClass('disabled_input');
        $(this).val(parseFloat(value).toFixed(2));
      }
      var total_debit = 0.00;
      var total_credit = 0.00;

      $('.general_debit').each(function() {
        var debit_val = $(this).val();
        var floatdebit = parseFloat(debit_val);
        total_debit = parseFloat(total_debit + floatdebit);
      });

      $('.general_credit').each(function() {
        var credit_val = $(this).val();
        var floatcredit = parseFloat(credit_val);
        total_credit = parseFloat(total_credit + floatcredit);
      });

      $(".general_debit_total").val(total_debit.toFixed(2));
      $(".general_credit_total").val(total_credit.toFixed(2));

      var debitval = total_debit.toFixed(2);
      var creditval = total_credit.toFixed(2);

      if(debitval == creditval){
        $(".save_general_journal_button").show();
      } else{
        $(".save_general_journal_button").hide();
      }
  });

  $(".general_credit").blur(function(){
    var value = $(this).val();
    if((value == '') || (value == 0)){
      $(this).val('0.00');
      $(this).parents("tr").find(".general_debit").attr("readonly", false).removeClass('disabled_input');
    }  
    else{
      $(this).parents("tr").find(".general_debit").attr("readonly", true).addClass('disabled_input');
      $(this).val(parseFloat(value).toFixed(2));
    }

    var total_debit = 0.00;
    var total_credit = 0.00;

    $('.general_debit').each(function() {
      var debit_val = $(this).val();
      var floatdebit = parseFloat(debit_val);
      total_debit = parseFloat(total_debit + floatdebit);
    });

    $('.general_credit').each(function() {
      var credit_val = $(this).val();
      var floatcredit = parseFloat(credit_val);
      total_credit = parseFloat(total_credit + floatcredit);
    });

    $(".general_debit_total").val(total_debit.toFixed(2));
    $(".general_credit_total").val(total_credit.toFixed(2));

    var debitval = total_debit.toFixed(2);
    var creditval = total_credit.toFixed(2);

    if(debitval == creditval){
      $(".save_general_journal_button").show();
    } else{
      $(".save_general_journal_button").hide();
    }
  });
}
$(window).change(function(e){
  if($(e.target).hasClass('general_nominal')){
  var code = $(e.target).val();

  

  if(code == '712'){
    $(e.target).parents("tr").find(".error-general-nominal").show();
    $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Debtors Control Account.');
    $(".save_general_journal_button").attr("disabled", true);

    $(".general_nominal").attr("disabled", true);
    $(e.target).not().attr("disabled", false);
  }
  else if(code == '813'){
    $(e.target).parents("tr").find(".error-general-nominal").show();
    $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Creditors Control Account.');
    $(".save_general_journal_button").attr("disabled", true);

    $(".general_nominal").attr("disabled", true);
    $(e.target).not().attr("disabled", false);
  }
  else if(code == '813A'){
    $(e.target).parents("tr").find(".error-general-nominal").show();
    $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Client holding account Account.');
    $(".save_general_journal_button").attr("disabled", true);

    $(".general_nominal").attr("disabled", true);
    $(e.target).not().attr("disabled", false);
  }
  else if((code >= '771') && (code < '772')){
    $(e.target).parents("tr").find(".error-general-nominal").show();
    $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Bank Nominal accounts Account.');
    $(".save_general_journal_button").attr("disabled", true);

    $(".general_nominal").attr("disabled", true);
    $(e.target).not().attr("disabled", false);
  }
  else{
    $(e.target).parents("tr").find(".error-general-nominal").hide();
    $(".general_nominal").attr("disabled", false);
    $(".save_general_journal_button").attr("disabled", false);
  }

}
});
$(window).click(function(e){

if($(e.target).hasClass('add_general')){
  $(e.target).parents("table").find(".general_credit").removeClass('general_credit_last');
  var general_nominal = $(".general_nominal_hidden_for_add_ajax").html();

  $("#general_journal_tboday").append('<tr><td><select class="general_nominal" name="general_nominal[]">'+general_nominal+'</select><label class="error error-general-nominal" ></label></td><td><input type="type" class="general_journal_desription" required placeholder="Enter Journal Desription" name="general_journal_desription[]"><label class="error error-general_journal_desription" ></label></td><td><input type="text" style="text-align: right;" class="general_debit" value="0.00" required placeholder="Enter Debit Value" name="general_debit[]" oninput="keypressonlynumber(this)"><label class="error error-general_debit" ></label></td><td><input type="text" style="text-align: right;" class="general_credit general_credit_last" value="0.00" required placeholder="Enter Credit Value" name="general_credit[]" oninput="keypressonlynumber(this)"><label class="error error-general_credit" ></label></td><td style="text-align: center;"><a href="javascript:" class="fa fa-plus add_general" style="margin-top: 10px;display:none"></a><a href="javascript:" class="fa fa-trash delete_general" style="margin-top: 10px; " title="Delete"></a></td></tr>');

    blurfunction_gj();
}

if($(e.target).hasClass('delete_general')){
  var r = confirm('Are you sure want delete this row?');

  if(r){
    $(e.target).parents("tr").remove();
    blurfunction_gj();
  }
}
if($(e.target).hasClass('save_general_journal_button')){  
    $(".errror").show();
    var errorcount = 1;
    $(".general_journal_date").each(function() {
      var value = $(this).val();
      if(value == "")
      {
        $(".error-general_journal_date").show();
        $(".error-general_journal_date").html("Please select date.");      
        errorcount++;
      }
      else{
          $(this).parent().find(".error-general_journal_date").html("");  
      }      
    });
    $(".general_nominal").each(function() {
      var value = $(this).val();
      if(value == "")
      {
        $(this).parents("tr").find(".error-general-nominal").show();
        $(this).parents("tr").find(".error-general-nominal").html("Please select Nominal Code.");      
        errorcount++;
      }
      else{
          $(this).parent().find(".error-general-nominal").html("");  
      }      
    });
    $(".general_journal_desription").each(function() {
      var value = $(this).val();
      if(value == "")
      {
        $(this).parents("tr").find(".error-general_journal_desription").show();
        $(this).parents("tr").find(".error-general_journal_desription").html("Please enter Journal Desription.");      
        errorcount++;
      }
      else{
          $(this).parent().find(".error-general_journal_desription").html("");  
      }      
    });

    if(errorcount == 1){
      var nominal_date = $(".general_journal_date").val();
      var nominal_codes = $(".general_nominal").serialize();
      var journal_desription = $(".general_journal_desription").serialize();
      var debitvalues = $(".general_debit").serialize();
      var creditvalues = $(".general_credit").serialize();

      $.ajax({
        url:"<?php echo URL::to('user/save_general_journals'); ?>",
        type:"post",
        data:{nominal_date:nominal_date,nominal_codes:nominal_codes,journal_desription:journal_desription,debitvalues:debitvalues,creditvalues:creditvalues},
        success:function(result){
          $(".general_journal_modal").modal("hide");
        }
      })
    }
}

if($(e.target).hasClass('class_general_journal')){
  $(".general_journal_modal").modal('show');
  $(".general_journal_date").val("");
  $(".general_debit_total").val("");
  $(".general_credit_total").val("");

  var general_nominal = $(".general_nominal_hidden_for_add_ajax").html();


  $("#general_journal_tboday").html('<tr><td><select class="general_nominal" required="" name="general_nominal[]">'+general_nominal+'</select><label class="error error-general-nominal"></label></td><td><input type="type" class="general_journal_desription" required="" placeholder="Enter Journal Desription" name="general_journal_desription[]"><label class="error error-general_journal_desription"></label></td><td><input type="text" style="text-align: right;" class="general_debit" required="" value="0.00" placeholder="Enter Debit Value" name="general_debit[]" oninput="keypressonlynumber(this)"><label class="error error-general_debit"></label></td><td><input type="text" style="text-align: right;" class="general_credit" required="" value="0.00" placeholder="Enter Credit Value" name="general_credit[]" oninput="keypressonlynumber(this)"><label class="error error-general_credit"></label></td><td></td></tr><tr><td><select class="general_nominal" name="general_nominal[]">'+general_nominal+'</select> <label class="error error-general-nominal"></label></td><td><input type="type" class="general_journal_desription" required="" placeholder="Enter Journal Desription" name="general_journal_desription[]"><label class="error error-general_journal_desription"></label></td><td><input type="text" style="text-align: right;" class="general_debit" value="0.00" required="" placeholder="Enter Debit Value" name="general_debit[]" oninput="keypressonlynumber(this)"><label class="error error-general_debit"></label></td><td><input type="text" style="text-align: right;" class="general_credit general_credit_last" value="0.00" required="" placeholder="Enter Credit Value" name="general_credit[]" oninput="keypressonlynumber(this)"><label class="error error-general_credit"></label></td><td style="text-align: center;"><a href="javascript:" class="fa fa-plus add_general" title="Add" style="margin-top: 10px;"></a><a href="javascript:" class="fa fa-trash delete_general" style="margin-top: 10px; margin-left: 10px; display: none;"></a></td></tr>');

    blurfunction_gj();
}
});
</script>

</body>

</html>