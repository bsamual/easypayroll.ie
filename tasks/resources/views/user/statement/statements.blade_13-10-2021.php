@extends('userheader')
@section('content')
<script src="<?php echo URL::to('assets/ckeditor/src/js/main1.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>
<style>
.own_table_white tr td
{
  height:70px;
}
.own_table_white tr td{background: #fff !important}
.own_table_white tr:hover td{background: #fff !important}
.own_table_white tr:hover td:first-child {background: #fff !important}
.invoice_td{
  cursor: pointer;
}
.received_td{
  cursor: pointer;
}
.modal{
  z-index: 99999999;
}
#colorbox{
  z-index: 99999999999;
}
.attachment_div{
  margin-top: 10px;
  margin-left: 25px;
}
.add_attachment_month_year{
  float:left;
}
.email_unsent_label{
  margin-top: 10px;
margin-left: 25px;
}
.email_unsent{
  float:left;
}
.dz-remove{
    color: #000;
    font-weight: 800;
    text-transform: uppercase;
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
.status_icon{
padding: 10px;
width: 50%;
border-radius: 39px;
text-align: center;
}
.red_status{ background: #f00; color:#fff !important; }
.orange_status{ background: orange; color:#000 !important; }
.green_status{ background: green; color:#fff !important; }
.blue_status{ background: blue;color:#fff !important;  }
.yellow_status{ background: yellow !important; color:#000 !important;}
.table>thead>tr>th { background: #fff !important; }
.fa-sort{ cursor:pointer; }
.company_td { font-weight:800; }
.form-control[disabled] { background-color:#ececec !important; cursor: pointer; }
.fa-check { color:green; }
.fa-times { color:#f00; }
.fa { font-size:20px; }
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    999999999999999;
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
.modal_load_balance {
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
body.loading_balance {
    overflow: hidden;   
}
body.loading_balance .modal_load_balance {
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
.modal_load_content {
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
body.loading_content {
    overflow: hidden;   
}
body.loading_content .modal_load_content {
    display: block;
}
.disclose_label{ width:300px; }
.option_label{width:100%;}
table{
      border-collapse: separate !important;
}
.fa-plus,.fa-pencil-square{
  cursor:pointer;
}
.ui-widget{z-index: 999999999}
.ui-widget .ui-menu-item-wrapper{font-size: 14px; font-weight: bold;}
.ui-widget .ui-menu-item-wrapper:hover{font-size: 14px; font-weight: bold}
.file_attachment_div{width:100%;}
.remove_notepad_attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.remove__attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.delete_all_image, .delete_all_notes_only, .delete_all_notes, .download_all_image, .download_rename_all_image, .download_all_notes_only, .download_all_notes{cursor: pointer;}
.notepad_div {
    border: 1px solid #000;
    width: 400px;
    position: absolute;
    height: 250px;
    background: #dfdfdf;
    display: none;
}
.notepad_div textarea{
  height:212px;
}
.notepad_div_notes {
    border: 1px solid #000;
    width: 400px;
    position: absolute;
    height: 250px;
    background: #dfdfdf;
    display: none;
}
.notepad_div_notes textarea{
  height:212px;
}
.notepad_div_progress_notes,.notepad_div_completion_notes {
    border: 1px solid #000;
    width: 400px;
    position: absolute;
    height: 250px;
    background: #dfdfdf;
    display: none;
}
.notepad_div_progress_notes textarea, .notepad_div_completion_notes textarea{
  height:212px;
}
.img_div_add{
    border: 1px solid #000;
    width: 280px;
    position: absolute !important;
    min-height: 118px;
    background: rgb(255, 255, 0);
    display:none;
}
.notepad_div_notes_add {
    border: 1px solid #000;
    width: 280px;
    position: absolute;
    height: 250px;
    background: #dfdfdf;
    display: none;
}
.notepad_div_notes_add textarea{
  height:212px;
}
.edit_allocate_user{
  cursor: pointer;
  font-weight:600;
}
.disabled_icon{
  cursor:no-drop;
}
.disabled{
  pointer-events: none;
}
.disable_user{
  pointer-events: none;
  background: #c7c7c7;
}
.mark_as_incomplete{
  background: green;
}
.readonly .slider{
  background: #dfdfdf !important;
}
.readonly .slider:before{
  background: #000 !important;
}
input:checked + .slider{
      background-color: #2196F3 !important;
}
.switch {
  background: #fff !important;
  position: relative;
  display: inline-block;
  width: 47px;
  height: 24px;
  float:left !important;
  margin-top: 4px;
}
label{width:100%;}
.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}
.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 20px;
  left: 0px;
  bottom: 3px;
  background-color: red;
  -webkit-transition: .4s;
  transition: .4s;
}
input:checked + .slider {
  background-color: #2196F3;
}
input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}
input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}
/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}
.slider.round:before {
  border-radius: 50%;
}
.tr_closed{
  display:none;
}
.show_closed>.tr_closed{
  display:table-row !important;
}
/* Customize the label (the container) */
.form_checkbox {
  display: block;
  position: relative;
  padding-left: 35px;
  margin-bottom: 12px;
  cursor: pointer;
  
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
/* Hide the browser's default checkbox */
.form_checkbox input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
  height: 0;
  width: 0;
}
/* Create a custom checkbox */
.checkmark_checkbox {
  position: absolute;
  top: 0;
  left: 0;
  height: 20px;
  width: 20px;
  background-color: #fff;
  border:1px solid;
}
/* On mouse-over, add a grey background color */
.form_checkbox:hover input ~ .checkmark_checkbox {
  background-color: #fff;
  border:1px solid;
}
/* When the checkbox is checked, add a blue background */
.form_checkbox input:checked ~ .checkmark_checkbox {
  background-color: #fff;
}
/* Create the checkmark_checkbox/indicator (hidden when not checked) */
.checkmark_checkbox:after {
  content: "";
  position: absolute;
  display: none;
}
/* Show the checkmark_checkbox when checked */
.form_checkbox input:checked ~ .checkmark_checkbox:after {
  display: block;
}
/* Style the checkmark_checkbox/indicator */
.form_checkbox .checkmark_checkbox:after {
  left: 7px;
  top: 3px;
  width: 5px;
  height: 10px;
  border: solid #3a3a3a;
  border-width: 0 3px 3px 0;
  -webkit-transform: rotate(45deg);
  -ms-transform: rotate(45deg);
  transform: rotate(45deg);
}
.form_radio {
  display: block;
  position: relative;
  padding-right: 20px;
  margin-bottom: 12px;
  cursor: pointer;
  -webkit-user-select: none;
  -moz-user-select: none;
  -ms-user-select: none;
  user-select: none;
}
/* Hide the browser's default radio button */
.form_radio input {
  position: absolute;
  opacity: 0;
  cursor: pointer;
}
/* Create a custom radio button */
.checkmark_radio {
  position: absolute;
  top: 0;
  left: 0;
  height: 20px;
  width: 20px;
  background-color: #fff;
  border-radius: 50%;
  border:1px solid #3a3a3a;
}
/* On mouse-over, add a grey background color */
.form_radio:hover input ~ .checkmark_radio {
  background-color: #fff;
}
/* When the radio button is checked, add a blue background */
.form_radio input:checked ~ .checkmark_radio {
  background-color: #fff;
}
/* Create the indicator (the dot/circle - hidden when not checked) */
.checkmark_radio:after {
  content: "";
  position: absolute;
  display: none;
}
/* Show the indicator (dot/circle) when checked */
.form_radio input:checked ~ .checkmark_radio:after {
  display: block;
}
/* Style the indicator (dot/circle) */
.form_radio .checkmark_radio:after {
  top: 5px;
  left: 5px;
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background:green;
}
.dropzone .dz-preview.dz-image-preview {
    background: #949400 !important;
}
.dz-message span{text-transform: capitalize !important; font-weight: bold;}
.trash_imageadd{
  cursor:pointer;
}
.dropzone.dz-clickable .dz-message, .dropzone.dz-clickable .dz-message *{
      margin-top: 40px;
}
.dropzone .dz-preview {
  margin:0px !important;
  min-height:0px !important;
  width:100% !important;
  color:#000 !important;
  float: left;
  clear: both;
}
.dropzone .dz-preview p {
  font-size:12px !important;
}
.remove_dropzone_attach{
  color:#f00 !important;
  margin-left:10px;
}
.remove_dropzone_attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.remove_notepad_attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.remove__attach_add{
  color:#f00 !important;
  margin-left:10px;
}
.img_div_add{
    border: 1px solid #000;
    width: 280px;
    position: absolute !important;
    min-height: 118px;
    background: rgb(255, 255, 0);
    display:none;
}
.form_radio .text{}
.form_radio span{right: 0px; left: unset;}
input[type="checkbox"]:not(old) + label, input[type="radio"]:not(old) + label { margin-left:0px; }
</style>
<script src="<?php echo URL::to('ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('ckeditor/samples/js/sample.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>">
<link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> ">
<div class="modal fade settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog" role="document" style="width:20%">
        <div class="modal-content">
          <form id="statement_settings_form" method="post" action="<?php echo URL::to('user/save_statement_settings'); ?>" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Statement Settings</h4>
            </div>
            <div class="modal-body" style="clear:both">  
              <?php
              $settings = DB::table('settings')->where('source','statement')->first();
              if(count($settings))
              {
                $settingsval = unserialize($settings->settings);
              }
              ?>
              <h4>Enter Secondary Email CC Address:</h4>
              <input type="email" name="statement_secondary" class="form-control statement_secondary" value="<?php echo (isset($settingsval['secondary']))?$settingsval['secondary']:''; ?>">
              <h4>Enter Email Body Message:</h4>
              <textarea name="email_body" class="form-control email_body" id="editor_1"><?php echo (isset($settingsval['email_body']))?$settingsval['email_body']:''; ?></textarea>
              <h4>Choose Statement Background Image:</h4>
              <input type="file" name="bg_image" class="form-control bg_image" accept=".png" value="">
              <?php 
              if(isset($settingsval['filename'])) {
                if($settingsval['filename'] != "")
                {
                  echo '<h4>Attachment:</h4><a href="'.URL::to($settingsval['url'].'/'.$settingsval['filename']).'" download>'.$settingsval['filename'].'</a>';
                } 
              }
              ?>
              <h4>Enter Minimum Balance Level:</h4>
              <input type="text" name="minimum_bal" class="form-control minimum_bal" onkeypress="validate(event)" value="<?php echo (isset($settingsval['minimum_balance']))?$settingsval['minimum_balance']:''; ?>">
              <h4>Enter Payments to IBAN:</h4>
              <input type="text" name="payments_to_iban" class="form-control payments_to_iban" value="<?php echo (isset($settingsval['payments_to_iban']))?$settingsval['payments_to_iban']:''; ?>">
              <h4>Enter Payments to BIC:</h4>
              <input type="text" name="payments_to_bic" class="form-control payments_to_bic" value="<?php echo (isset($settingsval['payments_to_bic']))?$settingsval['payments_to_bic']:''; ?>">
            </div>
            <div class="modal-footer" style="clear:both">  
                <input type="submit" class="common_black_button" value="Submit">
            </div>
          </form>
        </div>
  </div>
</div>
<div class="modal fade invoice_list_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-lg" role="document" style="width:40%">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Invoice List</h4>
            </div>
            <div class="modal-body" style="clear:both">  
              <table class="table">
                <thead>
                  <th>Invoice Number</th>
                  <th>Invoice Date</th>
                  <th>Net</th>
                  <th>Vat</th>
                  <th>Gross</th>
                </thead>
                <tbody id="invoice_list_tbody">
                </tbody>
              </table>
            </div>
            <div class="modal-footer" style="clear:both">  
                
            </div>
        </div>
  </div>
</div>
<div class="modal fade receipt_list_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-lg" role="document" style="width:40%">
        <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Receipt List</h4>
            </div>
            <div class="modal-body" style="clear:both">  
              <table class="table">
                <thead>
                  <th>Receipt Date</th>
                  <th>Debit Nominal</th>
                  <th>Credit Nominal</th>
                  <th>Amount</th>
                </thead>
                <tbody id="receipt_list_tbody">
                </tbody>
              </table>
            </div>
            <div class="modal-footer" style="clear:both">  
                
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
<div class="content_section" style="margin-bottom:200px">
    <div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 padding_00 new_main_title">
                Client Statement
            </h4>
    </div>
    <div class="row">
      <div class="col-md-12">
          <a href="javascript:" id="load_clients" class="common_black_button load_clients" style="float:left">Load Clients</a>
          <a href="javascript:" id="load_values" class="common_black_button load_values" style="float:right;display:none">Load Values</a>
          <select name="select_month" class="select_month_values form-control" style="width:10%;margin-left:10px;float:right">
            <?php
            $current_month = date('M-Y');
            $current_monthh = date('m-Y');
            $curr_str_month = date('Y-m-01');
            $opening_month = DB::table('user_login')->first();
            $opening_bal_month = date('Y-m-01', strtotime($opening_month->opening_balance_date));
            $edate = strtotime($curr_str_month);
            $bdate = strtotime($opening_bal_month);
            $age = ((date('Y',$edate) - date('Y',$bdate)) * 12) + (date('m',$edate) - date('m',$bdate));
            echo '<option value="'.date('Y-m-01', strtotime($opening_bal_month)).'">'.date('M-Y', strtotime($opening_bal_month)).'</option>';
            for($i= 1; $i<=$age; $i++)
            {
              $dateval = date('m-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
              $datevall = date('M-Y', strtotime('first day of next month', strtotime($opening_bal_month)));
              $datevalll = date('Y-m-01', strtotime('first day of next month', strtotime($opening_bal_month)));
              if(date('m-Y') == $dateval) { $selected = 'selected'; } else { $selected = ''; }
              echo '<option value="'.$datevalll.'" '.$selected.'>'.$datevall.'</option>';
              $opening_bal_month = date('Y-m-d', strtotime('first day of next month', strtotime($opening_bal_month)));
            }
            ?>
          </select>
          <spam style="float:right">Current Month: </spam>
      </div>
    </div>
    <div class="row" style="margin-top:25px;">
      <div class="col-md-12" id="load_table_clients">
      </div>
      <a href="javascript:" class="fa fa-cog common_black_button statement_settings" style="float:right;margin-right:1%;"> Settings</a>
    </div>
</div>
<div class="modal_load"></div>
  <div class="modal_load_balance" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Loading Opening Balance</p> </div>
<div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Clients Opening Balance are Processed.</p>
  <p style="font-size:18px;font-weight: 600;">Loading Opening Balances: <span id="apply_first"></span> of <span id="apply_last"></span> Clients</p>
</div>
<div class="modal_load_content" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Clients Invoices, Receipts and Closing Balance are Processed.</p>
  <p style="font-size:18px;font-weight: 600;">Loading Values: <span id="count_first"></span> of <span id="count_last"></span> Clients</p>
</div>
<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<script>
/* @Note: not sure e.pageX will work in IE8 */
function scrolldiv()
{ 
  /* A full compatability script from MDN: */
  var supportPageOffset = window.pageXOffset !== undefined;
  var isCSS1Compat = ((document.compatMode || "") === "CSS1Compat");
 
  /* Set up some variables  */
  
  var demoItem3 = document.getElementById("demoItem3"); 
  /* Add an event to the window.onscroll event */
  window.addEventListener("scroll", function(e) {  
    
    /* A full compatability script from MDN for gathering the x and y values of scroll: */
    var x = supportPageOffset ? window.pageXOffset : isCSS1Compat ? document.documentElement.scrollLeft : document.body.scrollLeft;
var y = supportPageOffset ? window.pageYOffset : isCSS1Compat ? document.documentElement.scrollTop : document.body.scrollTop;
 
    
    demoItem3.style.top = -y + 230 + "px";
  });
  
};

function validate(evt) {
  var theEvent = evt || window.event;
  // Handle paste
  if (theEvent.type === 'paste') {
      key = event.clipboardData.getData('text/plain');
  } else {
  // Handle key press
      var key = theEvent.keyCode || theEvent.which;
      key = String.fromCharCode(key);
  }
  var regex = /[0-9,]|\./;
  if( !regex.test(key) ) {
    theEvent.returnValue = false;
    if(theEvent.preventDefault) theEvent.preventDefault();
  }
}
$(document).ready(function(){
  CKEDITOR.replace('editor_1',
 {
  height: '150px',
 }); 
});
function next_client_bal_check()
{
  $("body").addClass("loading_balance");
  $.ajax({
    url:"<?php echo URL::to('user/get_client_opening_balance'); ?>",
    type:"post",
    dataType:"json",
    success:function(result)
    {
      var i = 0;
      $('#statement_client_tbody').find('tr').each(function(){
        $(this).find('td').eq(6).html(result[i]);
        i++;
      });
      setTimeout(function() {
          $("body").removeClass("loading_balance");
          next_client_values_check(0);
      },1000);
    }
  });
}
function next_client_values_check(count)
{
  if(count == 0)
  {
    $("body").addClass("loading_content");
  }
  var current_month = $(".select_month_values").val();
  var countclient = $(".client_tr").length;
  $("#count_last").html(countclient);
  var client_id = $(".client_tr:eq("+count+")").attr("data-element");
  
  $.ajax({
    url:"<?php echo URL::to('user/get_client_statement_values'); ?>",
    type:"post",
    dataType:"json",
    data:{client_id:client_id,current_month:current_month},
    success:function(result)
    {
      $(".tr_header_1").find("th").detach();
      $(".tr_header_2").find("th").detach();
      $(".client_value_tr_"+client_id).find("td").detach();
      $(".tr_header_1").append(result['thval']);
      $(".tr_header_2").append(result['thval_divide']);
      $(".client_value_tr_"+client_id).append(result['tdval_divide']);
      setTimeout( function() {
        var countval = count + 1;
        if($(".client_tr:eq("+countval+")").length > 0)
        {
          next_client_values_check(countval);
          $("#count_first").html(countval);
        }
        else{
          scrolldiv();
          $("body").removeClass("loading_content");
        }
    },200);
    }
  });
}
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
$(window).click(function(e) {
  if($(e.target).hasClass('ok_to_send_statement'))
  {
    var client_id = $(e.target).attr("data-client");
    if($(e.target).is(":checked"))
    {
      var status = 1;
      $.ajax({
        url:"<?php echo URL::to('user/change_send_statement_status'); ?>",
        type:"post",
        data:{client_id:client_id,status:status},
        success:function(result)
        {

        }
      })
    }
    else{
      var status = 0;
      $.ajax({
        url:"<?php echo URL::to('user/change_send_statement_status'); ?>",
        type:"post",
        data:{client_id:client_id,status:status},
        success:function(result)
        {

        }
      })
    }
  }
  if($(e.target).hasClass('statement_settings'))
  {
    $(".settings_modal").modal("show");
  }
  if($(e.target).hasClass('load_clients'))
  {
    $("body").addClass("loading");
    var current_month = $(".select_month_values").val();
    $.ajax({
      url:"<?php echo URL::to('user/load_statement_clients'); ?>",
      type:"post",
      data:{current_month:current_month},
      success:function(result)
      {
        $("#load_table_clients").html(result);
        $(".load_values").show();
        $(".statement_settings").show();
        var countclient = $(".client_tr").length;
        $("#apply_last").html(countclient);
        scrolldiv();
        $("body").removeClass("loading");
        //next_client_bal_check(0);
      }
    })
  }
  if($(e.target).hasClass('load_values'))
  {
    var current_month = $(".select_month_values").val();
    if(current_month != "")
    {
      next_client_bal_check();
    }
  }
  if($(e.target).hasClass('invoice_td'))
  {
    $("body").addClass("loading");
    $(".invoice_list_modal").modal("show");
    var month = $(e.target).attr("data-month");
    var client = $(e.target).parents(".client_tr").attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/get_invoice_list_statement'); ?>",
      type:"post",
      data:{client:client,month:month},
      success:function(result)
      {
        $("#invoice_list_tbody").html(result);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('received_td'))
  {
    $("body").addClass("loading");
    $(".receipt_list_modal").modal("show");
    var month = $(e.target).attr("data-month");
    var client = $(e.target).parents(".client_tr").attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/get_receipt_list_statement'); ?>",
      type:"post",
      data:{client:client,month:month},
      success:function(result)
      {
        $("#receipt_list_tbody").html(result);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('invoice_class')){
    $("body").addClass("loading");  
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
   if($(e.target).hasClass('saveas_pdf'))
  {
    $("body").addClass("loading");  
    var htmlcontent = $("#letterpad_modal").html();
    var inv_no = $("#invoice_number_pdf").val();
    $.ajax({
      url:"<?php echo URL::to('user/invoice_saveas_pdf'); ?>",
      data:{htmlcontent:htmlcontent,inv_no:inv_no},
      type:"post",
      success: function(result)
      {
        $("body").removeClass("loading");  
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
      }
    });
  }
  if($(e.target).hasClass('print_pdf'))
  {
    $("body").addClass("loading");  
    var htmlcontent = $("#letterpad_modal").html();
    $.ajax({
      url:"<?php echo URL::to('user/invoice_print_pdf'); ?>",
      data:{htmlcontent:htmlcontent},
      type:"post",
      success: function(result)
      {
        $("body").removeClass("loading");  
        $("#pdfDocument").attr("src","<?php echo URL::to('papers/Invoice Report.pdf'); ?>");
        printPdf("<?php echo URL::to('papers/Invoice Report.pdf'); ?>");
      }
    });
  }
})

 
</script>
@stop
