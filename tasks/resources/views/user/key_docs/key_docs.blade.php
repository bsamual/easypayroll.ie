@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>
<script src="http://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<link rel="stylesheet" href="<?php echo URL::to('assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>
.client_details_table > tbody > tr > td{
  border-top: 0px solid;
}
.opening_bal_h5 {
  float: left;
  width: 100%;
  font-size: 18px;
}
#client_account_tbody > tr > td {
    padding: 12px !important;
}
.opening_bal_transaction_h5 {
  float: left;
  width: 100%;
  font-size: 18px;
}
#transaction_tbody > tr > td {
    padding: 12px !important;
}
#receipt_tbody > tr > td{
  padding:12px !important;
}
.label_class{
  width:20%;
  float: left;
}
.plus_add{ padding: 3px ;background: #000; color: #fff; width: 30px; text-align: center; margin-top: 23px; font-size: 20px; float: right; }
.plus_add:hover{background: #5f5f5f; color: #fff}
.minus_remove{ padding: 3px ;background: #000; color: #fff; width: 30px; text-align: center; margin-top: 23px; font-size: 20px; float: right;margin-left: 4px; }
.minus_remove:hover{background: #5f5f5f; color: #fff}
body{
  background: #f5f5f5 !important;
}
.fa-sort{
  cursor:pointer;
  margin-left: 8px;
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
.ui-widget{z-index: 999999999}
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
.img_div_add{
        border: 1px solid #000;
    width: 300px;
    position: absolute !important;
    min-height: 118px;
    background: rgb(255, 255, 0);
    display:none;
}
.dropzone.dz-clickable{margin-bottom: 0px !important;}
.report_model_selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; margin-top: 20px; border-radius: 3px;  }
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


<!-- <div class="modal fade receipt_payment_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
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
</div> -->



<div class="modal fade letters_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Add Files</h4>
          </div>
          <div class="modal-body" style="min-height:280px">  
              <div class="row">
                <div class="col-lg-12">
                  <div class="img_div_progress" style="display: block;">
                     <div class="image_div_attachments_progress">
                        <form action="<?php echo URL::to('user/upload_key_docs_letter'); ?>" method="post" enctype="multipart/form-data" class="dropzone" id="imageUploadprogress" style="clear:both;min-height:250px;background: #949400;color:#000;border:0px solid; height:auto; width:100%; float:left">                            
                            <input name="hidden_client_id_letters" id="hidden_client_id_letters" type="hidden" value="">
                            <input type="hidden" name="hidden_type_key_docs" id="hidden_type_key_docs" value="">
                        </form>
                     </div>
                  </div>
                  
                </div>
              </div>
          </div>
          <div class="modal-footer">  
            <a href="javascript:" class="btn btn-sm btn-primary" align="left" style="margin-left:7px; float:left;    background: #000;margin-top:-10px;margin-bottom:10px">Submit</a>
          </div>
        </div>
  </div>
</div>


<div class="modal fade download_pdf_folder_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Download Folder Path:</h4>
      </div>
      <div class="modal-body">
          <h4>Folder Path:</h4>
          <input type="text" name="download_folder_path" class="form-control download_folder_path" placeholder="Copy & Paste Folder Path" value="">
          <h4>Create Folder(Optional):</h4>
          <input type="text" name="create_folder" class="form-control create_folder" placeholder="Folder Name" value="">
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary common_black_button save_pdfs_in_folder" value="Download Selected Pdf">
      </div>
    </div>
  </div>
</div>
<div class="modal fade sent_to_client" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="width:50%">
    <form action="<?php echo URL::to('user/keydocs_email_selected_pdf'); ?>" method="post" >
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Email Selected PDF</h4>
      </div>
      <div class="modal-body">
          <div class="row">
            <div class="col-md-3">
              <label>From:</label>
            </div>
            <div class="col-md-9">
               <select name="from_user_to_client" id="from_user_to_client" class="form-control input-sm" value="" required>
                  <option value="">Select User</option>
                  <?php
                    $users = DB::table('user')->where('user_status',0)->where('disabled',0)->where('email','!=', '')->orderBy('lastname','asc')->get();
                    if(count($users))
                    {
                      foreach($users as $user)
                      {
                          ?>
                            <option value="<?php echo $user->user_id; ?>"><?php echo $user->lastname.' '.$user->firstname; ?></option>
                          <?php
                      }
                    }
                  ?>
              </select>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>To:</label>
            </div>
            <div class="col-md-9">
              <input type="text" class="form-control client_email" placeholder="Enter Email Address" name="client_search" value="" autocomplete="off" required>
              <input type="hidden" class="hidden_client_id" id="hidden_client_id" value="" name="hidden_client_id">
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>CC:</label>
            </div>
            <div class="col-md-9">
            	<?php 
				  $admin_details = Db::table('admin')->first();
				  $admin_cc = $admin_details->keydocs_cc_email;
				?> 
              <input type="text" name="cc_approval_to_client" class="form-control input-sm" value="<?php echo $admin_cc; ?>" readonly>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-3">
              <label>Subject:</label>
            </div>
            <div class="col-md-9">
              <input type="text" name="subject_to_client" class="form-control input-sm subject_to_client" value="Invoice(s) Attached" required>
            </div>
          </div>
          <div class="row" style="margin-top:10px">
            <div class="col-md-12">
              <label>Message:</label>
            </div>
            <div class="col-md-12">
              <textarea name="message_editor_to_client" id="editor_1">
              </textarea>
            </div>
            <div id="client_attachments">
            	<h4>Attachment:</h4>
            	<img class="zip_image" src="<?php echo URL::to('assets/images/zip.png'); ?>" style="width:100px;height:80px">
              <img class="pdf_image" src="<?php echo URL::to('assets/images/pdf.png'); ?>" style="width:65px;height:80px">
            	<spam class="zip_name"></spam>
            	<input type="hidden" name="hidden_attachment" class="hidden_attachment" value="">
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="request_id_email_client" id="request_id_email_client" value="">
        <input type="submit" class="btn btn-primary common_black_button" value="Send Request to Client">
      </div>
    </div>
    </form>
  </div>
</div>
<div class="modal fade invoice_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-keyboard="false" data-backdrop="static" style="z-index: 9999999999999999999999999999">
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
<div class="modal fade keydocs_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index: 999999;">
  <div class="modal-dialog modal-sm" role="document" style="width:40%">
        <div class="modal-content">
          <form name="keydocs_settings_form" id="keydocs_settings_form" method="post" action="<?php echo URL::to('user/save_keydocs_settings'); ?>" enctype="multipart/form-data">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title job_title" >Key Docs Settings</h4>
            </div>
            <div class="modal-body">  
                <h4><h4>Default CC Email Address:</h4>
                <input type="text" name="keydocs_cc_input" class="form-control keydocs_cc_input" value="<?php echo $admin_details->keydocs_cc_email; ?>">

                <h4>Enter Signature:</h4>
                <textarea name="message_editor" id="editor1"><?php echo $admin_details->keydocs_signature; ?></textarea>
                
                <h4>Email Header Image:</h4>
                <input type="file" id="validation-email" class="form-control" name="header_image"> 
                
                <?php
                if($admin_details->keydocs_header_image != ""){
                  $exploded = explode('/',$admin_details->keydocs_header_image);
                  ?>
                  <p style="margin-top:20px">Attached Header Image:</p>
                  <a href="<?php echo URL::to($admin_details->keydocs_header_image); ?>" download><?php echo end($exploded); ?></a>
                  <?php
                }
                ?>
            </div>
            <div class="modal-footer">  
                <input type="submit" name="submit_keydocs_settings" class="common_black_button submit_keydocs_settings" value="Submit">
            </div>
          </form>
        </div>
  </div>
</div>
<div class="content_section" style="margin-bottom:200px">
  	<div class="page_title">
        <h4 class="col-lg-12 padding_00 new_main_title">
            Key Docs          
            <a href="javascript:" id="settings_keydocs" class="fa fa-cog common_black_button" title="Key Docs Settings" style="float:right"></a>
        </h4>
        <div class="col-lg-5" style="padding-right: 0px;height: 95px;">
        	<div class="col-lg-3 padding_00">
    				<h5 style="font-weight: 600">Enter Client Name: </h5>
    			</div>
    			<div class="col-lg-7" style="padding: 0px;">
    				<div class="form-group">
    				    <?php
    				    if(isset($_GET['client_id']))
    				    {
    				      $client_id = $_GET['client_id'];
    				      $companydetails_val = DB::table('cm_clients')->where('client_id',$client_id)->first();
    				      $companyname_val = $companydetails_val->company.'-'.$client_id;
    				      $hiddenval = $_GET['client_id'];
    				    }
    				    else{
    				      $companyname_val = '';
    				      $hiddenval = '';
    				    }
    				    ?>
    				    <input type="text" class="form-control client_common_search" placeholder="Enter Client Name" style="font-weight: 500;" value="<?php echo $companyname_val; ?>" required />                      
    				    <input type="hidden" class="client_search_common" id="client_search_hidden_infile" value="<?php echo $hiddenval; ?>" name="client_id">
    				</div>                  
    			</div>
    			<div class="col-md-1" style="padding: 0px">
    				<input type="button" name="load_client_review" class="common_black_button load_client_review" value="Load">
    			</div>
        </div>
        <div class="col-lg-7" style="padding:0px 40px">
          <div class="col-md-12 client_details_div" style="padding:0px">
            
          </div>
        	<!-- <h3>CRO Information: </h3>
            <table class="table">
                <tbody>
                  <tr>
                    <td style="width:15%;margin-top:6px"><label style="margin-top:6px">Type:</label></td>
                    <td><input type="text" name="cro_type" class="form-control cro_info cro_type" value="" readonly></td>
                    <td colspan="2"><input type="button" class="common_black_button refresh_cro" value="Refresh"></td>
                  </tr>
                  <tr>
                    <td style=""><label style="margin-top:6px">ARD:</label><input type="button" class="common_black_button update_ard" value="Update" style="display:none"></td>
                    <td><input type="text" name="cm_ard_date" class="form-control cro_info cm_ard_date" value="" readonly></td>
                    <td style=""><label style="margin-top:6px">CRO ARD:</label></td>
                    <td><input type="text" name="cro_ard_date" class="form-control cro_info cro_ard_date" value="" readonly></td>
                  </tr>
                  <tr>
                    <td style=""><label style="margin-top:6px">Company Name:</label></td>
                    <td><input type="text" name="company_name" class="form-control cro_info company_name" value="" readonly></td>
                    <td colspan="2"><input type="text" name="cro_number" class="form-control cro_info cro_number" value="" readonly style="display:none"></td>
                  </tr>
                  
                </tbody>
              </table> -->
        </div>
        <div class="col-md-12" style="padding:0px">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item active">
              <a class="nav-link active" id="invoice-tab" data-toggle="tab" href="#invoice" role="tab" aria-controls="invoice" aria-selected="true" aria-expanded="true">Invoice List</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="year-tab" data-toggle="tab" href="#year" role="tab" aria-controls="year" aria-selected="false" aria-expanded="false">Year End Docs</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="letters-tab" data-toggle="tab" href="#letters" role="tab" aria-controls="letters" aria-selected="false" aria-expanded="false">Letters</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="tax-tab" data-toggle="tab" href="#tax" role="tab" aria-controls="tax" aria-selected="false" aria-expanded="false">Tax Clearance</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active in" id="invoice" role="tabpanel" aria-labelledby="invoice-tab">
              <div class="col-md-5" style="margin-top:20px">
                <input type="radio" name="invoice_date_option" class="invoice_date_option" id="invoice_date_option_1" value="1"><label for="invoice_date_option_1">Year</label>
                <input type="radio" name="invoice_date_option" class="invoice_date_option" id="invoice_date_option_2" value="2"><label for="invoice_date_option_2">All Invoice</label>
                <input type="radio" name="invoice_date_option" class="invoice_date_option" id="invoice_date_option_3" value="3"><label for="invoice_date_option_3">Custom Date</label>
                <br/>
                <input type="button" name="download_selected_pdf" class="common_black_button download_selected_pdf" value="Download Selected PDF" style="display:none">
                <input type="button" name="email_selected_pdf" class="common_black_button email_selected_pdf" value="Email Selected PDF" style="display:none">
                <input type="button" name="selected_export_csv" class="common_black_button selected_export_csv" value="Export CSV" style="display:none">

                <div class="col-md-12 invoice_year_div padding_00" style="margin-top: 7px;display:none">
                  <h5 class="col-md-1 padding_00" style="font-weight: 600; width: 80px;">Select Year:</h5>
                  <div class="col-md-3">
                    <select name="invoice_select_year" class="invoice_select_year form-control">
                      <option value="">Select Year</option>
                    </select>
                  </div>
                  <div class="col-md-2 padding_00">
                    <input type="button" name="load_invoice_year" class="common_black_button load_all_cm_invoice" value="Load Invoice">
                  </div>
                </div>
                <div class="col-md-12 custom_date_div" style="margin-top: 7px;display:none">
                  <h5 class="col-md-1">From:</h5>
                  <div class="col-md-3">
                    <input type="text" name="from_invoice" class="form-control from_invoice" value="">
                  </div>
                  <h5 class="col-md-1">To:</h5>
                  <div class="col-md-3">
                    <input type="text" name="to_invoice" class="form-control to_invoice" value="">
                  </div>
                  <div class="col-md-2">
                    <input type="button" name="load_invoice_year" class="common_black_button load_all_cm_invoice" value="Load Invoice">
                  </div>
                </div>
                <div class="col-md-12 invoice_table_div padding_00" style="display: none; margin-top: 7px;">
                </div> 
              </div>
            </div>
            <div class="tab-pane fade" id="year" role="tabpanel" aria-labelledby="year-tab">
              <div class="col-md-5" style="margin-top:20px">
                <div style="margin-top: 10px;clear: both;float: left;">
                  <a href="javascript:" class="common_black_button load_year_end_docs" id="load_year_end_docs">Load Year End Docs</a>
                  <a href="javascript:" class="common_black_button download_selected_documents" id="download_selected_documents">Download Documents</a>
                  <a href="javascript:" class="common_black_button dummy_button" style="display:none">Create Loan Pack</a>
                </div>
                
                <div class="col-md-12 receipt_table_div padding_00" style="margin-top: 20px;">
                  
                </div> 
              </div>
            </div>
            <div class="tab-pane fade" id="letters" role="tabpanel" aria-labelledby="letters-tab">
              <div class="col-md-5" style="margin-top:20px">
                <div style="margin-top: 10px;clear: both;float: left;">
                  <a href="javascript:" class="common_black_button add_letter_files" id="add_letter_files">Add Files</a>
                  <a href="javascript:" class="common_black_button download_selected_letters" id="download_selected_letters">Download Letters</a>
                  <a href="javascript:" class="common_black_button dummy_button" style="display:none">Create Standard Letters</a>
                </div>
                <div class="col-md-12 transaction_table_div padding_00" style="display:none">
                  <table class="table" style="margin-top: 20px;">
                    <thead>
                      <th>S.no</th>
                      <th>Files</th>
                      <th>Notes</th>
                      <th>Action</th>
                    </thead>
                    <tbody id="letters_tbody">
                        
                    </tbody>
                  </table>
                </div> 
              </div>
            </div>
            <div class="tab-pane fade" id="tax" role="tabpanel" aria-labelledby="tax-tab">
              <div class="col-md-5" style="margin-top:20px">

                <div style="margin-top: 10px;clear: both;float: left;">
                  <a href="javascript:" class="common_black_button add_tax_files" id="add_tax_files">Add Files</a>
                  <a href="javascript:" class="common_black_button download_selected_tax" id="download_selected_tax">Download Selected Files</a>

                  <!-- <a href="javascript:" class="common_black_button add_current_tax_files" id="add_current_tax_files" style="position: absolute;right: 0px;top: 4%;">Current Tax Clearance file</a> -->
                </div>
                <div class="col-md-12 transaction_table_div padding_00" style="display:none">
                  <h4 style="margin-top:20px;font-weight:600;text-decoration: underline;">Current Tax Clearance File:</h4>
                  <table class="table" style="margin-top: 20px;">
                    <thead>
                      <th>Files</th>
                      <th>Date Stored</th>
                      <th>Action</th>
                    </thead>
                    <tbody id="current_tax_tbody">
                        
                    </tbody>
                  </table>
                  <h4 style="margin-top:20px;font-weight:600;text-decoration: underline;">Files Stored:</h4>
                  <table class="table" style="margin-top: 20px;">
                    <thead>
                      <th>S.no</th>
                      <th>Files</th>
                      <th>Date Stored</th>
                      <th>Action</th>
                    </thead>
                    <tbody id="tax_tbody">
                        
                    </tbody>
                  </table>

                  <a href="javascript:" class="common_black_button dummy_button" style="display:none">Email Tax Clearance</a>
                </div>
              </div>
            </div>
          </div>
        </div>
	</div>
    <!-- End  -->
	<div class="main-backdrop"><!-- --></div>
	<div id="print_image">
	    
	</div>
	<div class="modal_load"></div>
  <div class="modal_load_apply" style="text-align: center;">
    <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Invoices are get to be download to the selected folder.</p>
  </div>
	<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
	<input type="hidden" name="show_alert" id="show_alert" value="">
	<input type="hidden" name="pagination" id="pagination" value="1">
  <input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
  <input type="hidden" name="invoice_sortoptions" id="invoice_sortoptions" value="asc">
  <input type="hidden" name="date_sortoptions" id="date_sortoptions" value="asc">
  <input type="hidden" name="net_sortoptions" id="net_sortoptions" value="asc">
  <input type="hidden" name="vat_sortoptions" id="vat_sortoptions" value="asc">
  <input type="hidden" name="gross_sortoptions" id="gross_sortoptions" value="asc">
  <input type="hidden" name="receipt_sno_sortoptions" id="receipt_sno_sortoptions" value="asc">
  <input type="hidden" name="debit_sortoptions" id="debit_sortoptions" value="asc">
  <input type="hidden" name="credit_sortoptions" id="credit_sortoptions" value="asc">
  <input type="hidden" name="receipt_date_sortoptions" id="receipt_date_sortoptions" value="asc">
  <input type="hidden" name="amount_sortoptions" id="amount_sortoptions" value="asc">
  <input type="hidden" name="keydocs_signature" id="keydocs_signature" value="<?php echo $admin_details->keydocs_signature; ?>">
</div>
<script>
$(function () {     
  $('a[data-toggle="collapse"]').on('click',function(){
    var objectID=$(this).attr('href');
    if($(objectID).hasClass('in'))
    {
      $(objectID).collapse('hide');
    }
    else{
      $(objectID).collapse('show');
    }
  });
});
$(window).click(function(e) { 
  if(e.target.id == "settings_keydocs")
  {
    if (CKEDITOR.instances.editor1) CKEDITOR.instances.editor1.destroy();

    CKEDITOR.replace('editor1',
    {
      height: '150px',
      enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        autoParagraph: false,
        entities: false,
    });
    $(".keydocs_settings_modal").modal("show");
  }
  if($(e.target).hasClass('load_year_end_docs')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      $.ajax({
        url:"<?php echo URL::to('user/load_year_end_docs'); ?>",
        type:"post",
        data:{client_id:client_id},
        success:function(result){
          $(".receipt_table_div").html(result);
          $(".receipt_table_div").show();
        }
      });
    }else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_selected_documents')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".year_end_documents:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".year_end_documents:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/download_year_end_documents'); ?>",
          type:"post",
          data:{ids:ids},
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
            setTimeout(function() {
              $.ajax({
                url:"<?php echo URL::to('user/delete_file_link'); ?>",
                type:"post",
                data:{result:result},
                success: function(result)
                {
                  $("body").removeClass("loading");
                }
              });
            },3000);
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_selected_letters')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_letter:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_letter:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/download_key_docs_letters'); ?>",
          type:"post",
          data:{ids:ids},
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
            setTimeout(function() {
              $.ajax({
                url:"<?php echo URL::to('user/delete_file_link'); ?>",
                type:"post",
                data:{result:result},
                success: function(result)
                {
                  $("body").removeClass("loading");
                }
              });
            },3000);
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('download_selected_tax')){
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var attachment = $(".key_docs_tax:checked").length;
      if(attachment > 0){
        var ids = '';
        $(".key_docs_tax:checked").each(function() {
          var id = $(this).attr("data-element");
          if(ids == "")
          {
            ids = id;
          }
          else{
            ids = ids+','+id;
          }
        });

        $.ajax({
          url:"<?php echo URL::to('user/download_key_docs_tax'); ?>",
          type:"post",
          data:{ids:ids},
          success:function(result){
            SaveToDisk("<?php echo URL::to('public'); ?>/"+result,result);
            setTimeout(function() {
              $.ajax({
                url:"<?php echo URL::to('user/delete_file_link'); ?>",
                type:"post",
                data:{result:result},
                success: function(result)
                {
                  $("body").removeClass("loading");
                }
              });
            },3000);
          }
        })
      }
      else{
        alert("Please select atlease one file to download");
      }
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('add_letter_files'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var task_id = $(e.target).attr("data-element");
      $(".letters_modal").modal("show");
      $("#hidden_type_key_docs").val("1");
      // Dropzone.forElement("#imageUpload").removeAllFiles(true);
      // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('add_tax_files'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id != ""){
      var task_id = $(e.target).attr("data-element");
      $(".letters_modal").modal("show");
      $("#hidden_type_key_docs").val("2");
      // Dropzone.forElement("#imageUpload").removeAllFiles(true);
      // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
      $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
    }
    else{
      alert("Please load the Client Details");
    }
  }
  if($(e.target).hasClass('add_current_tax_files'))
  {
    var currlength = $(".delete_current_tax").length;
    if(currlength > 0){
      alert("Already you have an current tax clearence file. If you want to change the current file please delete it and add a new current file.")
    }
    else{
      var client_id = $("#client_search_hidden_infile").val();
      if(client_id != ""){
        var task_id = $(e.target).attr("data-element");
        $(".letters_modal").modal("show");
        $("#hidden_type_key_docs").val("3");
        // Dropzone.forElement("#imageUpload").removeAllFiles(true);
        // Dropzone.forElement("#imageUpload2").removeAllFiles(true);
        $(".dz-message").find("span").html("Click here to BROWSE the files OR just drop files here to upload");
      }
      else{
        alert("Please load the Client Details");
      }
    }
  }
  if($(e.target).hasClass('delete_letter')){
    var letter_id = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to delete?");
    if(r){
      $.ajax({
        url:"<?php echo URL::to('user/delete_letter'); ?>",
        type:"post",
        data:{letter_id:letter_id},
        success:function(result)
        {
          $(e.target).parents("tr").detach();
        }
      })
    }
  }
  if($(e.target).hasClass('delete_tax')){
    var tax_id = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to delete?");
    if(r){
      $.ajax({
        url:"<?php echo URL::to('user/delete_tax'); ?>",
        type:"post",
        data:{tax_id:tax_id},
        success:function(result)
        {
          $(e.target).parents("tr").detach();
        }
      })
    }
  }
  if($(e.target).hasClass('delete_current_tax')){
    var tax_id = $(e.target).attr("data-element");
    var r = confirm("Are you sure you want to delete?");
    if(r){
      $.ajax({
        url:"<?php echo URL::to('user/delete_current_tax'); ?>",
        type:"post",
        data:{tax_id:tax_id},
        success:function(result)
        {
          $(e.target).parents("tr").html("<td colspan='3'>No Current File Found</td>");
        }
      })
    }
  }
})
function ajaxcomplete() {
  $(".letter_notes").blur(function() {
  var letter_id = $(this).attr("data-element");
  var value = $(this).val();

  if(value != ""){
    $.ajax({
      url:"<?php echo URL::to('user/save_letter_notes'); ?>",
      type:"post",
      data:{letter_id:letter_id,value:value},
      success:function(result){

      }
    })
  }
});
}

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
		}
  	});
  	var fullDate = new Date().toLocaleString("en-US", {timeZone: "Europe/Dublin"});
  	$(".from_invoice").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD/MM/YYYY',
    });
    $(".to_invoice").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD/MM/YYYY',
    });
    $(".from_receipt").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD/MM/YYYY',
    });
    $(".to_receipt").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'DD/MM/YYYY',
    });
});
$(function(){
    $('#crm_expand').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: false,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false
    });
});
var convertToNumber = function(value){
       return value.toLowerCase();
}
var convertToNumeric = function(value){
      value = value.replace(',','');
      value = value.replace(',','');
      value = value.replace(',','');
      value = value.replace(',','');
       return parseInt(value.toLowerCase());
}
$(window).click(function(e) { 
  var ascending = false;
  if($(e.target).hasClass('sort_sno'))
  {
    var sort = $("#sno_sortoptions").val();
    if(sort == 'asc')
    {
      $("#sno_sortoptions").val('desc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_sort_val').html()) <
        convertToNumeric($(b).find('.sno_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#sno_sortoptions").val('asc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_sort_val').html()) <
        convertToNumeric($(b).find('.sno_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#invoice_tbody').html(sorted);
  }






  if($(e.target).hasClass('sort_sno_receipt'))
  {
    var sort = $("#receipt_sno_sortoptions").val();
    if(sort == 'asc')
    {
      $("#receipt_sno_sortoptions").val('desc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_sort_receipt_val').html()) <
        convertToNumeric($(b).find('.sno_sort_receipt_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#receipt_sno_sortoptions").val('asc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_sort_receipt_val').html()) <
        convertToNumeric($(b).find('.sno_sort_receipt_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_debit'))
  {
    var sort = $("#debit_sortoptions").val();
    if(sort == 'asc')
    {
      $("#debit_sortoptions").val('desc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.debit_sort_val').html()) <
        convertToNumeric($(b).find('.debit_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#debit_sortoptions").val('asc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.debit_sort_val').html()) <
        convertToNumeric($(b).find('.debit_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_credit'))
  {
    var sort = $("#credit_sortoptions").val();
    if(sort == 'asc')
    {
      $("#credit_sortoptions").val('desc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.credit_sort_val').html()) <
        convertToNumeric($(b).find('.credit_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#credit_sortoptions").val('asc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.credit_sort_val').html()) <
        convertToNumeric($(b).find('.credit_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_receipt_date'))
  {
    var sort = $("#receipt_date_sortoptions").val();
    if(sort == 'asc')
    {
      $("#receipt_date_sortoptions").val('desc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.receipt_date_sort_val').html()) <
        convertToNumeric($(b).find('.receipt_date_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#receipt_date_sortoptions").val('asc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.receipt_date_sort_val').html()) <
        convertToNumeric($(b).find('.receipt_date_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_amount'))
  {
    var sort = $("#amount_sortoptions").val();
    if(sort == 'asc')
    {
      $("#amount_sortoptions").val('desc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.amount_sort_val').html()) <
        convertToNumeric($(b).find('.amount_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#amount_sortoptions").val('asc');
      var sorted = $('#receipt_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.amount_sort_val').html()) <
        convertToNumeric($(b).find('.amount_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#receipt_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_invoice'))
  {
    var sort = $("#invoice_sortoptions").val();
    if(sort == 'asc')
    {
      $("#invoice_sortoptions").val('desc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.invoice_sort_val').html()) <
        convertToNumeric($(b).find('.invoice_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#invoice_sortoptions").val('asc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.invoice_sort_val').html()) <
        convertToNumeric($(b).find('.invoice_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#invoice_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_date'))
  {
    var sort = $("#date_sortoptions").val();
    if(sort == 'asc')
    {
      $("#date_sortoptions").val('desc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.date_sort_val').html()) <
        convertToNumeric($(b).find('.date_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#date_sortoptions").val('asc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.date_sort_val').html()) <
        convertToNumeric($(b).find('.date_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#invoice_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_net'))
  {
    var sort = $("#net_sortoptions").val();
    if(sort == 'asc')
    {
      $("#net_sortoptions").val('desc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.net_sort_val').html()) <
        convertToNumeric($(b).find('.net_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#net_sortoptions").val('asc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.net_sort_val').html()) <
        convertToNumeric($(b).find('.net_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#invoice_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_vat'))
  {
    var sort = $("#vat_sortoptions").val();
    if(sort == 'asc')
    {
      $("#vat_sortoptions").val('desc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.vat_sort_val').html()) <
        convertToNumeric($(b).find('.vat_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#vat_sortoptions").val('asc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.vat_sort_val').html()) <
        convertToNumeric($(b).find('.vat_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#invoice_tbody').html(sorted);
  }
  if($(e.target).hasClass('sort_gross'))
  {
    var sort = $("#gross_sortoptions").val();
    if(sort == 'asc')
    {
      $("#gross_sortoptions").val('desc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.gross_sort_val').html()) <
        convertToNumeric($(b).find('.gross_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#gross_sortoptions").val('asc');
      var sorted = $('#invoice_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.gross_sort_val').html()) <
        convertToNumeric($(b).find('.gross_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#invoice_tbody').html(sorted);
  }
  if($(e.target).hasClass('load_client_account_details'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id == "")
    {
      alert("Please select the Client");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/get_client_account_review_listing'); ?>",
        type:"post",
        dataType:"json",
        data:{client_id:client_id},
        success:function(result)
        {
          $(".opening_bal_h5").show();
          $(".client_account_table").show();
          $(".export_client_account_details").show();
          $('#opening_bal_client').html(result['opening_balance']);
          $("#client_account_tbody").html(result['output']);
        }
      });
    }
  }
  if($(e.target).hasClass('load_transaction_listing'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id == "")
    {
      alert("Please select the Client");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/get_transaction_review_listing'); ?>",
        type:"post",
        dataType:"json",
        data:{client_id:client_id},
        success:function(result)
        {

          $(".opening_bal_transaction_h5").show();
          $(".transaction_table").show();
          $(".export_transaction_details").show();
          $('#opening_bal_transaction').html(result['opening_balance']);
          $("#transaction_tbody").html(result['output']);
          $('[data-toggle="popover"]').popover();
        }
      });
    }
  }
  if($(e.target).hasClass('export_client_account_details'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id == "")
    {
      alert("Please select the Client");
    }
    else{
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/export_client_account_review_listing'); ?>",
        type:"post",
        data:{client_id:client_id},
        success:function(result)
        {
          $("body").removeClass("loading");
          SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
        }
      });
    }
  }
  if($(e.target).hasClass('export_transaction_details'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id == "")
    {
      alert("Please select the Client");
    }
    else{
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/export_transaction_review_listing'); ?>",
        type:"post",
        data:{client_id:client_id},
        success:function(result)
        {
          $("body").removeClass("loading");
          SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
        }
      });
    }
  }
  if($(e.target).hasClass('client_tab'))
  {
    $(".opening_bal_h5").hide();
    $(".client_account_table").hide();
    $(".export_client_account_details").hide();
    $('#opening_bal_client').html('');
    $("#client_account_tbody").html('');
  }
  if($(e.target).hasClass('transaction_tab'))
  {
    $(".opening_bal_transaction_h5").hide();
    $(".transaction_table").hide();
    $(".export_transaction_details").hide();
    $('#opening_bal_transaction').html('');
    $("#transaction_tbody").html('');
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
				$("body").addClass("loading");
			    setTimeout(function(){ 
			        var client_id = $("#client_search_hidden_infile").val();
			          $(".copy_clients").attr("data-element", client_id);
			          $(".print_clients").attr("data-element", client_id);
			          $(".download_clients").attr("data-element", client_id);
			          $.ajax({
			              url: "<?php echo URL::to('user/client_review_load_all_client_invoice') ?>",
			              data:{client_id:client_id,year:year,type:"1"},
			              dataType:"json",
			              type:"post",
			              success:function(result){
			                $(".invoice_table_div").html(result['invoiceoutput']);
			                $(".invoice_table_div").show();
			                 $(".download_selected_pdf").show();
			                $(".email_selected_pdf").show();
                      $(".selected_export_csv").show();
			                $("body").removeClass("loading");
			                $('#invoice_expand').DataTable({
			                    autoWidth: true,
			                    scrollX: false,
			                    fixedColumns: false,
			                    searching: false,
			                    paging: false,
			                    info: false,
			                    ordering: false
			                });
			               
			          }
			        });
			    }, 2000);
			}
			
		}
		else if(type == "3")
		{
			$("body").addClass("loading");
		    setTimeout(function(){ 
		        var client_id = $("#client_search_hidden_infile").val();
		        var from = $(".from_invoice").val();
		        var to = $(".to_invoice").val();
		          $(".copy_clients").attr("data-element", client_id);
		          $(".print_clients").attr("data-element", client_id);
		          $(".download_clients").attr("data-element", client_id);
		          $.ajax({
		              url: "<?php echo URL::to('user/client_review_load_all_client_invoice') ?>",
		              data:{client_id:client_id,from:from,to:to,type:"3"},
		              dataType:"json",
		              type:"post",
		              success:function(result){
		                $(".invoice_table_div").html(result['invoiceoutput']);
		                $(".invoice_table_div").show();
		                 $(".download_selected_pdf").show();
			                $(".email_selected_pdf").show();
                      $(".selected_export_csv").show();
		                $("body").removeClass("loading");
		                $('#invoice_expand').DataTable({
		                    autoWidth: true,
		                    scrollX: false,
		                    fixedColumns: false,
		                    searching: false,
		                    paging: false,
		                    info: false,
		                    ordering: false
		                });
		          }
		        });
		    }, 2000);
			
		}
	}
  if($(e.target).hasClass('load_all_cm_receipt')) {
    var type = $(".receipt_date_option:checked").val();
    if(type == "1")
    {
      var year = $(".receipt_select_year").val();
      if(year == "")
      {
        alert("Please select the year to review the Receipt");
      }
      else{
        $("body").addClass("loading");
          setTimeout(function(){ 
              var client_id = $("#client_search_hidden_infile").val();
                $.ajax({
                    url: "<?php echo URL::to('user/client_review_load_all_client_receipt') ?>",
                    data:{client_id:client_id,year:year,type:"1"},
                    dataType:"json",
                    type:"post",
                    success:function(result){
                      $(".receipt_table_div").html(result['receiptoutput']);
                      $(".receipt_table_div").show();
                      $("body").removeClass("loading");
                      $('#receipt_expand').DataTable({
                          autoWidth: true,
                          scrollX: false,
                          fixedColumns: false,
                          searching: false,
                          paging: false,
                          info: false,
                          ordering: false
                      });
                     
                }
              });
          }, 2000);
      }
      
    }
    else if(type == "3")
    {
      $("body").addClass("loading");
        setTimeout(function(){ 
            var client_id = $("#client_search_hidden_infile").val();
            var from = $(".from_receipt").val();
            var to = $(".to_receipt").val();
              $.ajax({
                  url: "<?php echo URL::to('user/client_review_load_all_client_receipt') ?>",
                  data:{client_id:client_id,from:from,to:to,type:"3"},
                  dataType:"json",
                  type:"post",
                  success:function(result){
                    $(".receipt_table_div").html(result['receiptoutput']);
                    $(".receipt_table_div").show();
                    $("body").removeClass("loading");
                    $('#receipt_expand').DataTable({
                        autoWidth: true,
                        scrollX: false,
                        fixedColumns: false,
                        searching: false,
                        paging: false,
                        info: false,
                        ordering: false
                    });
              }
            });
        }, 2000);
      
    }
  }
  if($(e.target).hasClass('select_all_invoice'))
  {
    if($(e.target).is(":checked"))
    {
      $(".invoice_check:visible").prop("checked",true);
    }
    else{
      $(".invoice_check").prop("checked",false);
    }
  }
  if($(e.target).hasClass('select_all_receipt'))
  {
    if($(e.target).is(":checked"))
    {
      $(".receipt_check:visible").prop("checked",true);
    }
    else{
      $(".receipt_check").prop("checked",false);
    }
  }
	if($(e.target).hasClass('invoice_inside_class')){
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
	if($(e.target).hasClass('invoice_date_option'))
	{
		var client_id = $("#client_search_hidden_infile").val();
		if(client_id == "")
		{
			alert("Please select the Client and click on the Load Button");
			$(".invoice_date_option").prop("checked",false);
		}
		else{
			var value = $(e.target).val();
			if(value == "1")
			{
				$(".invoice_year_div").show();
				$(".custom_date_div").hide();
				$(".invoice_table_div").html("");
				$(".download_selected_pdf").hide();
        $(".selected_export_csv").hide();
				$(".email_selected_pdf").hide();
			}
			else if(value == "2")
			{
				$(".invoice_year_div").hide();
				$(".custom_date_div").hide();
				$("body").addClass("loading");
			    setTimeout(function(){ 
			        var client_id = $("#client_search_hidden_infile").val();
			          $(".copy_clients").attr("data-element", client_id);
			          $(".print_clients").attr("data-element", client_id);
			          $(".download_clients").attr("data-element", client_id);
			          $.ajax({
			              url: "<?php echo URL::to('user/client_review_load_all_client_invoice') ?>",
			              data:{client_id:client_id,type:"2"},
			              dataType:"json",
			              type:"post",
			              success:function(result){
			                $(".invoice_table_div").html(result['invoiceoutput']);
			                $(".invoice_table_div").show();
			                $(".download_selected_pdf").show();
							$(".email_selected_pdf").show();
              $(".selected_export_csv").show();
			                $("body").removeClass("loading");
			                $('#invoice_expand').DataTable({
			                    autoWidth: true,
			                    scrollX: false,
			                    fixedColumns: false,
			                    searching: false,
			                    paging: false,
			                    info: false,
			                    ordering: false
			                });
			                
			          }
			        });
			    }, 2000);
			}
			else if(value == "3")
			{
				$(".invoice_year_div").hide();
				$(".custom_date_div").show();
				$(".invoice_table_div").html("");
				$(".download_selected_pdf").hide();
        $(".selected_export_csv").hide();
				$(".email_selected_pdf").hide();
			}
		}
	}
  if($(e.target).hasClass('receipt_date_option'))
  {
    var client_id = $("#client_search_hidden_infile").val();
    if(client_id == "")
    {
      alert("Please select the Client and click on the Load Button");
      $(".receipt_date_option").prop("checked",false);
    }
    else{
      var value = $(e.target).val();
      if(value == "1")
      {
        $(".receipt_year_div").show();
        $(".custom_date_div_receipt").hide();
        $(".receipt_table_div").html("");
      }
      else if(value == "2")
      {
        $(".receipt_year_div").hide();
        $(".custom_date_div_receipt").hide();
        $("body").addClass("loading");
          setTimeout(function(){ 
              var client_id = $("#client_search_hidden_infile").val();
                $.ajax({
                    url: "<?php echo URL::to('user/client_review_load_all_client_receipt') ?>",
                    data:{client_id:client_id,type:"2"},
                    dataType:"json",
                    type:"post",
                    success:function(result){
                      $(".receipt_table_div").html(result['receiptoutput']);
                      $(".receipt_table_div").show();
                      $("body").removeClass("loading");
                      $('#receipt_expand').DataTable({
                          autoWidth: true,
                          scrollX: false,
                          fixedColumns: false,
                          searching: false,
                          paging: false,
                          info: false,
                          ordering: false
                      });
                }
              });
          }, 2000);
      }
      else if(value == "3")
      {
        $(".receipt_year_div").hide();
        $(".custom_date_div_receipt").show();
        $(".receipt_table_div").html("");
      }
    }
  }
	if($(e.target).hasClass('load_client_review'))
	{
		var client_id = $("#client_search_hidden_infile").val();
    if(client_id == "")
    {
      alert("Please select the Client and click on the Load Button");
    }
    else{
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/key_docs_client_select'); ?>",
        type:"get",
        dataType:"json",
        data:{client_id:client_id},
        success:function(result)
        {
          $("#hidden_client_id_letters").val(client_id);
          $(".company_name").val(result['company']);
          $(".cro_number").val(result['cro']);
          $(".cm_ard_date").val(result['ard']);
          $(".cro_ard_date").val("");
          $(".cro_type").val(result['type']);
          $(".invoice_select_year").html(result['invoice_year']);
          $(".receipt_select_year").html(result['receipt_year']);
          $(".client_details_div").html(result['client_details']);

          $("#letters_tbody").html(result['letter_output']);
          $("#tax_tbody").html(result['tax_output']);
          $("#current_tax_tbody").html(result['current_tax_output']);
          $(".transaction_table_div").show();
          $(".client_email").val(result['client_email']);
          $(".cro_ard_date").css("color","#000 !important");
          $(".company_name").css("color","#000 !important");
          $(".update_ard").hide();
          $(".dummy_button").show();
          $(".invoice_year_div").hide();
          $(".custom_date_div").hide();
          $(".receipt_year_div").hide();
          $(".custom_date_div_receipt").hide();
          $(".receipt_year_div").hide();
          $(".custom_date_div_receipt").hide();
          $(".download_selected_pdf").hide();
          $(".email_selected_pdf").hide();
          $(".selected_export_csv").hide();
          $(".invoice_table_div").hide();
          $(".receipt_table_div").hide();
          /*$(".receipt_table_div").hide();*/
          $(".from_invoice").val("");
          $(".to_invoice").val("");
          $(".from_receipt").val("");
          $(".to_receipt").val("");
          $(".invoice_date_option").prop("checked",false);
          $(".receipt_date_option").prop("checked",false);
          $(".opening_bal_h5").hide();
          $(".client_account_table").hide();
          $(".export_client_account_details").hide();
          $('#opening_bal_client').html('');
          $("#client_account_tbody").html('');

          $(".opening_bal_transaction_h5").hide();
          $(".transaction_table").hide();
          $(".export_transaction_details").hide();
          $('#opening_bal_transaction').html('');
          $("#transaction_tbody").html('');
          
          ajaxcomplete();
          $("body").removeClass("loading");
        }
      })
    }
	}
	if($(e.target).hasClass('refresh_cro'))
	{
		var client_id = $("#client_search_hidden_infile").val();
		if(client_id == "")
		{
			alert("Please select the Client and click on the Load Button");
			$(".invoice_date_option").prop("checked",false);
		}
		else{
			$("body").addClass("loading");
			var cro = $(".cro_number").val();
			var client_id = $("#client_search_hidden_infile").val();
			$.ajax({
				url:"<?php echo URL::to('user/refresh_cro_ard'); ?>",
				type:"get",
				dataType:"json",
				data:{cro:cro,clientid:client_id},
				success:function(result)
				{
					if(result['ardstatus'] == "1")
					{
						$(".cro_ard_date").val(result['next_ard']);
						$(".cro_ard_date").css("color","#f00 !important");
						$(".update_ard").show();
					}
					else{
						$(".cro_ard_date").val(result['next_ard']);
						$(".update_ard").hide();
					}
					if(result['companystatus'] == "1")
					{
						$(".company_name").val(result['company_name']);
						$(".company_name").css("color","#f00 !important");
					}
					else{
						$(".company_name").val(result['company_name']);
					}
					$("body").removeClass("loading");
				}
			})
		}
	}
	if($(e.target).hasClass('update_ard'))
	{
		var client_id = $("#client_search_hidden_infile").val();
		var cro_ard = $(".cro_ard_date").val();
		$.ajax({
			url:"<?php echo URL::to('user/update_cro_ard_date'); ?>",
			type:"post",
			data:{client_id:client_id,cro_ard:cro_ard},
			success:function(result)
			{
				$(".cm_ard_date").val(cro_ard);
				$(".cro_ard_date").css("color","#000 !important");
				$(".update_ard").hide();
			}
		})
	}
	if($(e.target).hasClass('download_selected_pdf'))
	{
		var checked = $(".invoice_check:checked").length;
		if(checked > 0)
		{
      // $(".download_pdf_folder_modal").modal("show");
      $("body").addClass("loading");
      var ids = '';
      $(".invoice_check:checked").each(function() {
          if(ids == "")
          {
            ids = $(this).attr("data-element");
          }
          else{
            ids = ids+','+$(this).attr("data-element");
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/invoice_download_selected_pdfs'); ?>",
        type:"post",
        dataType:"json",
        data:{ids:ids},
        success:function(result)
        {
          SaveToDisk("<?php echo URL::to('papers'); ?>/"+result['zipfile'],result['zipfile']);
        }
      })
		}
		else{
			alert("Please select atleast one invoice to download");
		}
	}
  if($(e.target).hasClass('selected_export_csv'))
  {
    var checked = $(".invoice_check:checked").length;
    if(checked > 0)
    {
      // $(".download_pdf_folder_modal").modal("show");
      $("body").addClass("loading");
      var ids = '';
      $(".invoice_check:checked").each(function() {
          if(ids == "")
          {
            ids = $(this).attr("data-element");
          }
          else{
            ids = ids+','+$(this).attr("data-element");
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/invoice_export_selected_csvs'); ?>",
        type:"post",
        dataType:"json",
        data:{ids:ids},
        success:function(result)
        {
          SaveToDisk("<?php echo URL::to('papers'); ?>/"+result['timefolder']+'/'+result['filename'],result['filename']);
        }
      })
    }
    else{
      alert("Please select atleast one invoice to export as csv");
    }
  }
  if($(e.target).hasClass('save_pdfs_in_folder'))
  {
    var folder_path = $(".download_folder_path").val();
    var folder_name = $(".create_folder").val();
    if(folder_path == "")
    {
      alert("Please copy and paste the folder path to download the files");
    }
    else{
      $("body").addClass("loading_apply");
      var ids = '';
      $(".invoice_check:checked").each(function() {
          if(ids == "")
          {
            ids = $(this).attr("data-element");
          }
          else{
            ids = ids+','+$(this).attr("data-element");
          }
      });
      $.ajax({
        url:"<?php echo URL::to('user/invoice_download_selected_pdfs'); ?>",
        type:"post",
        data:{ids:ids,folder_path:folder_path,folder_name:folder_name},
        success:function(result)
        {
          $("body").removeClass("loading_apply");
          $(".download_pdf_folder_modal").modal("hide");
          $(".download_folder_path").val("");
          $(".create_folder").val("");
          //SaveToDisk("<?php echo URL::to('papers'); ?>/"+result['zipfile'],result['zipfile']);
        }
      })
    }
  }
	if($(e.target).hasClass('email_selected_pdf'))
	{
		var checked = $(".invoice_check:checked").length;
		if(checked > 0)
		{
			$("body").addClass("loading");
			var ids = '';
			$(".invoice_check:checked").each(function() {
				if(ids == "")
				{
					ids = $(this).attr("data-element");
				}
				else{
					ids = ids+','+$(this).attr("data-element");
				}
			});
			$.ajax({
				url:"<?php echo URL::to('user/invoice_email_selected_pdfs'); ?>",
				type:"post",
        dataType:"json",
				data:{ids:ids},
				success:function(result)
				{
          var signature = $("#keydocs_signature").val();
          if (CKEDITOR.instances.editor_1) CKEDITOR.instances.editor_1.destroy();
					CKEDITOR.replace('editor_1',
          {
            height: '300px',
          });
          CKEDITOR.instances['editor_1'].setData("<p>Hi,</p><p>We have attached a copy of the following Invoices</p>"+result['pdfs']+"<p>"+signature+"</p>");
					$(".sent_to_client").modal("show");
					$(".zip_name").html(result);
          if(checked == 1)
          {
            $(".zip_image").hide();
            $(".pdf_image").show();
          }
          else{
            $(".zip_image").show();
            $(".pdf_image").hide();
          }
					$(".hidden_attachment").html(result['zipfile']);
					$("body").removeClass("loading");
				}
			})
		}
		else{
			alert("Please select atleast one invoice to download");
		}
	}
})
$(window).keyup(function(e) {
    var valueTimmer;                //timer identifier
    var valueInterval = 500;  //time in ms, 5 second for example
    var $signature_value = $('.class_signature');    
    if($(e.target).hasClass('class_signature'))
    {        
        var input_val = $(e.target).val();  
        var id = $(e.target).attr("data-element");
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping, valueInterval,input_val, id);   
    }    
});
function doneTyping (signature_value,id) {
  $.ajax({
        url:"<?php echo URL::to('user/admin_request_signature')?>",
        type:"post",
        dataType:"json",
        data:{value:signature_value, id:id},
        success: function(result) {            
            
        }
      });
}

Dropzone.options.imageUploadprogress = {
    maxFiles: 5000,
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
            $(".letters_modal").modal("hide");
            if(obj.type == "1"){
              $("#letters_tbody").append(obj.output);
            }
            else if(obj.type == "2"){
              $("#tax_tbody").append(obj.output);
              $("#current_tax_tbody").html(obj.current_output);
            }
        });
        this.on("complete", function (file) {
          if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            $("body").removeClass("loading");
            Dropzone.forElement("#imageUploadprogress").removeAllFiles(true);
            $(".dz-message").find("span").html("Click here to BROWSE the files <br/>OR just drop files here to upload");

            ajaxcomplete();
          }
        });
        this.on("error", function (file) {
          //$(".add_progress_attachments").html("");
          $("body").removeClass("loading");
        });
        this.on("canceled", function (file) {
          //$(".add_progress_attachments").html("");
            $("body").removeClass("loading");
        });
        this.on("removedfile", function(file) {
            if (!file.serverId) { return; }
            //$.get("<?php echo URL::to('user/remove_property_images'); ?>"+"/"+file.serverId);
        });
    },
};
</script>
@stop