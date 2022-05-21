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
#colorbox{
  z-index:99999999;
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
.modal_load_trial {
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
body.loading_trial {
    overflow: hidden;   
}
body.loading_trial .modal_load_trial {
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

.modal_load_receipt {
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
body.loading_receipts {
    overflow: hidden;   
}
body.loading_receipts .modal_load_receipt {
    display: block;
}
.modal_load_payment {
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
body.loading_payments {
    overflow: hidden;   
}
body.loading_payments .modal_load_payment {
    display: block;
}
.modal_load_calculation {
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
body.loading_calculations {
    overflow: hidden;   
}
body.loading_calculations .modal_load_calculation {
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
.table-fixed-header {
  text-align: left;
  position: relative;
  border-collapse: collapse; 
}
.table-fixed-header thead tr th {
  background: white;
  position: sticky;
  top: 0; /* Don't forget this, required for the stickiness */
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
}
.orange_value_refresh{
  color:orange;
  font-weight:600;
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

<div class="modal fade client_finance_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:80%">
    <div class="modal-content">
      <div class="modal-header" style="padding-bottom: 0px;border-bottom:0px">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          Client Account Balance Manager
          <div style="float:right;margin-right: 24px;font-size: 16px;margin-top: 5px;">
            <input type="button" name="export_csv_client_opening" class="common_black_button export_csv_client_opening" value="Export CSV">
            <input type="button" name="export_csv_summary" class="common_black_button export_csv_summary" value="Export CSV">
            <label>Client Account Opening Balance Date: </label> <spam class="opening_balance_date_spam"></spam>
          </div>
        </h4>
      </div>
      <div class="modal-body" style="min-height:700px;max-height: 700px;overflow-y:scroll;padding-top: 0px;">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
          <li class="nav-item active">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Opening Balance</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Summary</a>
          </li>
        </ul>
        <div class="tab-content" id="myTabContent">
          <div class="tab-pane fade active in" id="home" role="tabpanel" aria-labelledby="home-tab">
            <table class="table table-fixed table-fixed-header" id="client_financial">
              <thead>
                  <tr>
                    <th style="text-align: left;width:10%">Client Code <i class="fa fa-sort client_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:12%">Surname <i class="fa fa-sort surname_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:12%">Firstname <i class="fa fa-sort firstname_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:32%">Company Name <i class="fa fa-sort company_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:9%">Debit <i class="fa fa-sort debit_fin_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:9%">Credit <i class="fa fa-sort credit_fin_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:9%">Balance <i class="fa fa-sort balance_fin_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:9%">Commit</th>
                  </tr>
                </thead>
                <tbody id="client_tbody">
                <?php 
                  $clients = DB::table('cm_clients')->get();
                  if(count($clients))
                  {
                    foreach($clients as $client)
                    {
                      $finance_client = DB::table('finance_clients')->where('client_id',$client->client_id)->first();
                      $debit = '0.00';
                      $credit = '0.00';
                      $balance = '0.00';
                      $bal_style = '';
                      $owed_text = '';
                      $commit_style="display:none";
                      $commit_btn = '<input type="button" class="common_black_button commit_btn commit_btn_'.$client->client_id.'" value="Commit" data-element="'.$client->client_id.'" style="'.$commit_style.'">';
                      if(count($finance_client))
                      {
                        $debit = ($finance_client->debit != "")?$finance_client->debit:"0.00";
                        $credit = ($finance_client->credit != "")?$finance_client->credit:"0.00";
                        if($debit != "" && $debit != "0.00" && $debit != "0" && $credit != "" && $credit != "0.00" && $credit != "0")
                        {
                          $balance = 'ERROR';
                          $bal_style = 'color:#f00;font-weight:600';
                        }
                        else{
                          $balance = ($finance_client->balance != "")?number_format_invoice_empty($finance_client->balance):"0.00";
                          $bal_style = '';
                          if($balance != "0.00" && $balance != "" && $balance != "0")
                          {
                            if($finance_client->balance >= 0) { $owed_text = '<spam style="color:green;font-size:12px;font-weight:600">Client Owes Back</spam>'; }
                            else { $owed_text = '<spam style="color:#f00;font-size:12px;font-weight:600">Client Is Owed</spam>'; }

                            $commit_style = 'display:block'; 
                          }
                        }

                        if($finance_client->journal_id == "")
                        {
                          $commit_btn = '<input type="button" class="common_black_button commit_btn commit_btn_'.$client->client_id.'" value="Commit" data-element="'.$client->client_id.'" style="'.$commit_style.'">';
                        }
                        else{
                          $commit_btn = '<a href="javascript:" class="journal_id_viewer" data-element="'.$finance_client->journal_id.'">'.$finance_client->journal_id.'</a>';
                        }
                      }
                      echo '<tr class="client_tr_'.$client->client_id.'">
                          <td class="client_sort_val" style="width:8%">'.$client->client_id.'</td>
                          <td class="surname_sort_val" style="width:12%">'.$client->surname.'</td>
                          <td class="firstname_sort_val" style="width:12%">'.$client->firstname.'</td>
                          <td class="company_sort_val" style="width:32%">'.$client->company.'</td>
                          <td style="width:9%"><input type="text" class="form-control debit_fin_sort_val debit_fin_sort_val_'.$client->client_id.'" id="debit_fin_sort_val" value="'.number_format_invoice($debit).'" data-element="'.$client->client_id.'"></td>
                          <td style="width:9%"><input type="text" class="form-control credit_fin_sort_val credit_fin_sort_val_'.$client->client_id.'" id="credit_fin_sort_val" value="'.number_format_invoice($credit).'" data-element="'.$client->client_id.'"></td>
                          <td style="width:9%">
                            <input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'.$client->client_id.'" id="balance_fin_sort_val" value="'.$balance.'" style="'.$bal_style.'" disabled>
                            '.$owed_text.'
                          </td>
                          <td style="width:9%">
                              '.$commit_btn.'
                          </td>
                        </tr>';
                    }
                  }
                ?>
                </tbody>
              </table>
          </div>
          <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <a href="javascript:" class="common_black_button load_summary_clients" style="clear: both;float: left;margin-top: 10px;" title="Load Clients">Load Clients</a>
            <table class="table table-fixed" id="summary_financial" style="display:none">
              <thead>
                  <tr>
                    <th style="text-align: left;width:8%">Client Code <i class="fa fa-sort client_summary_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:10%">Surname <i class="fa fa-sort surname_summary_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:10%">Firstname <i class="fa fa-sort firstname_summary_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: left;width:26%">Company Name <i class="fa fa-sort company_summary_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: right;width:12%">Opening Balance <i class="fa fa-sort opening_bal_summary_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: right;width:12%">Client Money <br/>Received <i class="fa fa-sort receipt_summary_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: right;width:12%">Payments Made <i class="fa fa-sort payment_summary_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                    <th style="text-align: right;width:12%">Balance <i class="fa fa-sort balance_summary_sort" aria-hidden="true" style="margin-top: 4px;margin-left: 10px;"></i></th>
                  </tr>
                </thead>
                <tbody id="summary_tbody">

                </tbody>
                <tr>
                  <td colspan="4"></td>
                  <td class="total_opening_balance_summary" style="text-align: right"></td>
                  <td class="total_receipt_summary" style="text-align: right"></td>
                  <td class="total_payment_summary" style="text-align: right"></td>
                  <td class="total_balance_summary" style="text-align: right"></td>
                </tr>
              </table>
          </div>
        </div>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
<div class="modal fade open_client_review_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:95%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Client Account Review</h4>
      </div>
      <div class="modal-body" style="clear:both">
          <iframe src="" id="client_revew_iframe" class="client_revew_iframe" style="width:100%;height:900px;border:0px"></iframe>
      </div>
      <div class="modal-footer" style="clear:both">
        
      </div>
    </div>
  </div>
</div>
<div class="modal fade trial_balance_journal_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">View Journals</h4>
      </div>
      <div class="modal-body" id="trial_balance_journal_tbody" style="min-height:500px;max-height: 600px;overflow-y:scroll">
        
      </div>
      <div class="modal-footer">
        
      </div>
    </div>
  </div>
</div>
<div class="modal fade nominal_codes_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Financial Setup <input type="button" class="common_black_button add_nominal" value="Add a Nominal" style="float:right;margin-right:15px"></h4>
      </div>
      <div class="modal-body" style="min-height:500px;max-height: 600px;overflow-y:scroll">
        <table class="table own_table_white">
            <thead>
              <th style="text-align: left">Code <i class="fa fa-sort code_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
              <th style="text-align: left">Description <i class="fa fa-sort des_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
              <th style="text-align: left">Primary Group <i class="fa fa-sort primary_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
              <th style="text-align: left">Debit Group <i class="fa fa-sort debit_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
              <th style="text-align: left">Credit Group <i class="fa fa-sort credit_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></th>
            </thead>
            <tbody id="nominal_tbody">
            <?php 
              $nominal_codes = DB::table('nominal_codes')->get();
              if(count($nominal_codes))
              {
                foreach($nominal_codes as $codes)
                {
                  $des_code = $codes->description;
                  if($codes->type == 0)
                  {
                    echo '<tr class="des_tr_'.$codes->code.'">
                      <td class="code_sort_val">'.$codes->code.' <i class="fa fa-lock" title="Core Nominal"></i></td>
                      <td class="des_sort_val">'.$des_code.'</td>
                      <td class="primary_sort_val">'.$codes->primary_group.'</td>
                      <td class="debit_sort_val">'.$codes->debit_group.'</td>
                      <td class="credit_sort_val">'.$codes->credit_group.'</td>
                    </tr>';
                  }
                  else{
                    echo '<tr class="des_tr_'.$codes->code.' code_'.$codes->code.'">
                      <td><a href="javascript:" class="edit_nominal_code code_sort_val" data-element="'.$codes->code.'">'.$codes->code.'</a></td>
                      <td><a href="javascript:" class="edit_nominal_code des_sort_val" data-element="'.$codes->code.'">'.$des_code.'</a></td>
                      <td><a href="javascript:" class="edit_nominal_code primary_sort_val" data-element="'.$codes->code.'">'.$codes->primary_group.'</a></td>
                      <td><a href="javascript:" class="edit_nominal_code debit_sort_val" data-element="'.$codes->code.'">'.$codes->debit_group.'</a></td> 
                      <td><a href="javascript:" class="edit_nominal_code credit_sort_val" data-element="'.$codes->code.'">'.$codes->credit_group.'</a></td>
                    </tr>';
                  }
                }
              }
            ?>
            </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <label style="margin-top:5px;float:left">Opening Financial Date:</label>&nbsp;&nbsp;
        <?php 
        $date = DB::table('user_login')->where('id',1)->first();
        ?>
        <spam class="opening_date_spam" style="text-align: left;line-height: 32px;float:left"><?php echo date('d-M-Y', strtotime($date->opening_balance_date)); ?></spam><a href="javascript:" class="common_black_button edit_opening_balance_btn" style="float:left">...</a>
          <input type="text" name="opening_financial_date" class="opening_financial_date" value="<?php echo date('d-M-Y', strtotime($date->opening_balance_date)); ?>" style="display:none;width: 12%;padding: 7px;outline: none;float:left">
          <a href="javascript:" class="common_black_button save_opening_balance_btn" style="display:none;line-height: 32px;float:left">Save</a>
        <input type="hidden" name="request_id_email_client" id="request_id_email_client" value="">
        <input type="button" class="common_black_button bank_account_manager" value="Bank Account Manager" style="float:right">
        <a class="common_black_button" href="<?php echo URL::to('user/opening_balance_manager'); ?>" style="float:right">Opening Balances</a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade add_nominal_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add a Nominal</h4>
      </div>
      <div class="modal-body">
        <form name="add_nominal_form" id="add_nominal_form" method="post">
          <h4>Enter Nominal Code:</h4>
          <input type="text" name="nominal_code_add" class="form-control nominal_code_add" id="nominal_code_add" value="">
          <h4>Enter Description:</h4>
          <input type="text" name="description_add" class="form-control description_add" id="description_add" value="">
          <h4>Select Primary Group:</h4>
          <select name="primary_grp_add" class="form-control primary_grp_add" id="primary_grp_add">
              <option value="">Select Value</option>
              <option value="Profit & Loss">Profit & Loss</option>
              <option value="Balance Sheet">Balance Sheet</option>
          </select>
          <div class="debit_group_div" style="display:none">
            <h4>Select Debit Group:</h4>
            <select name="debit_grp_add" class="form-control debit_grp_add" id="debit_grp_add">
                <option value="">Select Value</option>
            </select>
          </div>
          <div class="credit_group_div" style="display:none">
            <h4>Select Credit Group:</h4>
            <select name="credit_grp_add" class="form-control credit_grp_add" id="credit_grp_add">
                <option value="">Select Value</option>
            </select>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-primary add_nominal_btn" value="Add Nominal">
      </div>
    </div>
  </div>
</div>
<div class="modal fade bank_account_manager_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:60%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Bank Account Manager</h4>
      </div>
      <div class="modal-body" style="min-height:500px;max-height: 500px;overflow-y:scroll">
        <h4 style="text-align: right"><a href="javascript:" class="common_black_button add_bank">Add a Bank</a></h4>
        <table class="table own_table_white" style="margin-top: 30px;">
          <thead>
            <th style="text-align: left">Bank Name</th>
            <th style="text-align: left">Account Name</th>
            <th style="text-align: left">Account Number</th>
            <th style="text-align: left">Description</th>
            <th style="text-align: left">Nominal Code</th>
            <th style="text-align: left">Opening Balance</th>
            <th style="text-align: left">Journal</th>
            <th style="text-align: left">Action</th>
          </thead>
          <tbody id="bank_tbody">
          <?php 
            $banks = DB::table('financial_banks')->get();
            if(count($banks))
            {
              foreach($banks as $bank)
              {
                if($bank->debit_balance != "")
                {
                  $baln = number_format_invoice($bank->debit_balance);
                }
                elseif($bank->credit_balance != "")
                {
                  $baln = '-'.number_format_invoice($bank->credit_balance);
                }
                else{
                  $baln = '';
                }

                if($bank->journal_id == 0)
                {
                  $journal_id = '';
                }
                else{
                  $journal_id = $bank->journal_id;
                }
                echo '<tr class="bank_'.$bank->id.'">
                    <td>'.$bank->bank_name.'</td>
                    <td>'.$bank->account_name.'</td>
                    <td>'.$bank->account_number.'</td>
                    <td>'.$bank->description.'</td>
                    <td>'.$bank->nominal_code.'</td>
                    <td>'.$baln.'</td>
                    <td><a href="javascript:" class="journal_id_viewer" data-element="'.$journal_id.'">'.$journal_id.'</a></td>
                    <td>
                      <a href="javascript:" class="fa fa-edit edit_bank_account" data-element="'.$bank->id.'" title="Edit Bank Description"></a>
                      <a href="javascript:" class="edit_opening_balance" title="Opening Balance" data-element="'.$bank->id.'"><img src="'.URL::to('assets/images/opening_balance.png').'" class="edit_opening_balance" data-element="'.$bank->id.'" style="width:30px"></a>&nbsp;&nbsp;
                      <a href="javascript:" title="Reconcile"><i class="fa fa-retweet reconcile_icon" data-element="'.base64_encode($bank->id).'"></i></a>
                    </td>
                </tr>';
              }
            }
            else{
              echo '<tr>
                <td colspan="4">No Bank Accounts Found</td>
              </tr>';
            }
          ?>
        </tbody>
      </table>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="request_id_email_client" id="request_id_email_client" value="">
      </div>
    </div>
  </div>
</div>

<div class="modal fade add_bank_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Add a Bank</h4>
      </div>
      <div class="modal-body">
        <form name="add_bank_form" id="add_bank_form" method="post">
          <h4>Enter Bank Name:</h4>
          <input type="text" name="bank_name_add" class="form-control bank_name_add" id="bank_name_add" value="">
          <h4>Enter Account Name:</h4>
          <input type="text" name="account_name_add" class="form-control account_name_add" id="account_name_add" value="">
          <h4>Enter Account No:</h4>
          <input type="text" name="account_no_add" class="form-control account_no_add" id="account_no_add" value="">
          <h4>Enter Nominal Description:</h4>
          <textarea name="nominal_description_add" class="form-control nominal_description_add" id="nominal_description_add"></textarea>
          <h4>Select Nominal Code:</h4>
          <select name="bank_code_add" class="form-control bank_code_add" id="bank_code_add">
              <option value="">Select Nominal Code</option>
          </select>
        </form>
      </div>
      <div class="modal-footer">
        <input type="submit" class="common_black_button add_bank_btn" value="Add Bank">
      </div>
    </div>
  </div>
</div>
<div class="modal fade edit_bank_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Update Bank</h4>
      </div>
      <div class="modal-body">
        <form name="edit_bank_form" id="edit_bank_form" method="post">
          <h4>Enter Bank Name:</h4>
          <input type="text" name="bank_name_edit" class="form-control bank_name_edit" id="bank_name_edit" value="">
          <h4>Enter Account Name:</h4>
          <input type="text" name="account_name_edit" class="form-control account_name_edit" id="account_name_edit" value="">
          <h4>Enter Account No:</h4>
          <input type="text" name="account_no_edit" class="form-control account_no_edit" id="account_no_edit" value="">
          <h4>Enter Nominal Description:</h4>
          <textarea name="nominal_description_edit" class="form-control nominal_description_edit" id="nominal_description_edit"></textarea>
          <input type="hidden" name="hidden_bank_id_update" id="hidden_bank_id_update" value="">
        </form>
      </div>
      <div class="modal-footer">
        <input type="submit" class="common_black_button update_bank_btn" value="Update Bank">
      </div>
    </div>
  </div>
</div>
<div class="modal fade opening_balance_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Opening Balance</h4>
      </div>
      <div class="modal-body">
          <h4 style="font-weight:600">Bank Name: </h4><p id="bank_name_des" style="font-weight:500"></p>
          <h4 style="font-weight:600">Account Description: </h4><p id="bank_acc_des" style="font-weight:500"></p>
          <h4 style="font-weight:600">Account Name: </h4><p id="acc_name" style="font-weight:500"></p>
          <h4 style="font-weight:600">Account Number: </h4><p id="acc_no" style="font-weight:500"></p>
          <h4 style="font-weight:600">Enter Debit Balance:</h4>
          <input type="text" name="debit_balance_add" class="form-control debit_balance_add" id="debit_balance_add" value="">
          <h4 style="font-weight:600">Enter Credit Balance:</h4>
          <input type="text" name="credit_balance_add" class="form-control credit_balance_add" id="credit_balance_add" value="">
          <h4 style="font-weight:600">Opening Financial Date:</h4>
          <input type="text" name="opening_financial_date_val" class="form-control opening_financial_date_val" id="opening_financial_date_val" value="" disabled>
      </div>
      <div class="modal-footer">
        <input type="hidden" name="hidden_bank_id" id="hidden_bank_id" value="">
        <input type="submit" class="common_black_button save_opening_balance" value="SAVE">
      </div>
    </div>
  </div>
</div>
<div class="content_section" style="">
    <div class="page_title">
      <h4 class="col-lg-12 padding_00 new_main_title">
                Financials               
            </h4>
        <?php
          $first_date = date('d/m/Y',strtotime('first day of this month'));
          $last_date = date('d/m/Y',strtotime('last day of this month'));
          $curr_year = date('Y');
          $prev_year = date('Y') - 1;
          ?>
        <div class="col-md-6 padding_00" style="">
          <h4>NOMINAL JOURNAL LISTING</h4>
          <div class="col-md-12" style="padding: 0px;margin-top:10px">
            <div class="col-md-8 padding_00">
              <input type="radio" name="date_selection" class="date_selection" id="curr_year" value="1" data-from="01/01/<?php echo $curr_year; ?>" data-to="31/12/<?php echo $curr_year; ?>"><label for="curr_year">Current Year</label>
              <input type="radio" name="date_selection" class="date_selection" id="prev_year" value="2" data-from="01/01/<?php echo $prev_year; ?>" data-to="31/12/<?php echo $prev_year; ?>"><label for="prev_year">Previous Year</label>
              <input type="radio" name="date_selection" class="date_selection" id="curr_month" value="3" data-from="<?php echo $first_date; ?>" data-to="<?php echo $last_date; ?>" checked><label for="curr_month">Current Month</label>
              <input type="radio" name="date_selection" class="date_selection" id="custom" value="4"><label for="custom">Custom</label>

            </div>
            <div class="col-md-4">
              <a href="javascript:" class="common_black_button load_journals" style="position: absolute;top: 10px;z-index: 9999;height: 74px;padding-top: 16px;width: 122px;">Load <br/> Journals</a>
              <a href="javascript:" class="class_general_journal common_black_button" style="position: absolute; top: 10px; width:122px; height: 74px; padding-top: 18px; left: 150px;">General<br/>Journal</a>
            </div>
          </div>
          <div class="col-md-12" style="padding: 0px; margin-top: 20px;" >
            <label class="col-md-1 padding_00" style="margin-top: 6px;text-align: left;">From:</label>
            <div class="col-md-3">
              <input type="text" name="from_custom_date" class="form-control from_custom_date" value="<?php echo $first_date; ?>" disabled>
            </div>

            <label class="col-md-1" style="margin-top: 6px;text-align: right;">To:</label>
            <div class="col-md-3">
              <input type="text" name="to_custom_date" class="form-control to_custom_date" value="<?php echo $last_date; ?>" disabled>
            </div>
          </div>
          <div class="col-md-12" style="margin-top:5px">
            
          </div>
          <div class="col-md-12 load_journal_div" style="margin-top:20px;background: #fff; height:650px;max-height: 650px;overflow-y: scroll">
            <table class="table own_table_white" id="journal_table">
              <thead>
                <tr>
                  <th style="text-align:left">Journal <br>ID <i class="fa fa-sort journal_id_sort" style="float: right"></i></th>
                  <th style="text-align:left">Journal <br>Date <i class="fa fa-sort journal_date_sort" style="float: right"></i></th>
                  <th style="text-align:left">Journal <br>Description <i class="fa fa-sort journal_des_sort" style="float: right"></i></th>
                  <th style="text-align:left">Nominal <br>Code <i class="fa fa-sort nominal_code_sort" style="float: right"></i></th>
                  <th style="text-align:left">Nominal Code <br>Description <i class="fa fa-sort nominal_des_sort" style="float: right"></i></th>
                  <th style="text-align:left">Journal <br>Source <i class="fa fa-sort source_sort" style="float: right"></i></th>
                  <th style="text-align:left">Debit <br>Value <i class="fa fa-sort debit_journal_sort" style="float: right"></i></th>
                  <th style="text-align:left">Credit <br>Value <i class="fa fa-sort credit_journal_sort" style="float: right"></i></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>
        <div class="col-md-6">
          <div class="row">
            <h4 class="col-lg-9 padding_00">TRIAL BALANCE</h4>
            <div class="col-lg-3" style="position: absolute; top: -13px; right: 0px;">
              <a href="javascript:" class="common_black_button bank_account_manager" style="float: right; width: 100%; margin-left: 0px; margin-bottom: 4px; padding: 7px 0px;">Bank Account Manager</a>

              <div class="row">
                <div class="col-lg-12">
                  <a href="javascript:" class="common_black_button question_mark_btn fa fa-question-circle" title="Process of Posting Initial Journals" style="width: 15%; padding: 7px 0px; float: left; margin-left: 0px;"></a> 
              <input type="button" class="common_black_button client_finance_account_btn" value="Client Finance Account" style="float:right;font-size: 14px; width: 82%; padding: 7px 0px;"> 
                </div>
                <div class="col-lg-12">
                  <input type="button" name="financial_setup" class="common_black_button financial_setup" value="Financial Setup" style="float: right; width: 100%; margin-top: 4px; padding: 7px 0px;">          
                </div>
                <div class="col-lg-12">
                  <input type="button" name="journal_source" class="common_black_button journal_source" value="Journal Source" style="float: right; width: 100%; margin-top: 4px; padding: 7px 0px;">
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-9">
              <div class="row">
                <div class="col-md-12" style="padding: 0px;margin-top:10px">
                  <div class="col-md-8 padding_00">
                    <input type="radio" name="date_selection_trial" class="date_selection_trial" id="curr_year_trial" value="1" data-from="01/01/<?php echo $curr_year; ?>" data-to="31/12/<?php echo $curr_year; ?>"><label for="curr_year_trial">Current Year</label>
                    <input type="radio" name="date_selection_trial" class="date_selection_trial" id="prev_year_trial" value="2" data-from="01/01/<?php echo $prev_year; ?>" data-to="31/12/<?php echo $prev_year; ?>"><label for="prev_year_trial">Previous Year</label>
                    <input type="radio" name="date_selection_trial" class="date_selection_trial" id="curr_month_trial" value="3" data-from="<?php echo $first_date; ?>" data-to="<?php echo $last_date; ?>" checked><label for="curr_month_trial">Current Month</label>
                    <input type="radio" name="date_selection_trial" class="date_selection_trial" id="custom_trial" value="4"><label for="custom_trial">Custom</label>
                  </div>
                  <div class="col-md-4">
                    <a href="javascript:" class="common_black_button load_balance" style="position: absolute;top: 10px;z-index: 9999;height: 74px;padding-top: 16px;width: 122px;">Load Trial<br/> Balance</a>
                    <a href="javascript:" class="common_black_button remove_nil_balances" style="position: absolute;top: 10px;z-index: 9999;height: 74px;padding-top: 16px;width: 122px;left:148px;display:none">Remove Nil <br/>Balance</a>
                  </div>
                  
                </div>
                <div class="col-md-12" style="padding: 0px; margin-top: 20px;" >
                  <label class="col-md-1 padding_00" style="margin-top: 6px;text-align: left;">From:</label>
                  <div class="col-md-3">
                    <input type="text" name="from_custom_date_trial" class="form-control from_custom_date_trial" value="<?php echo $first_date; ?>" disabled>
                  </div>

                  <label class="col-md-1" style="margin-top: 6px;text-align: right;">To:</label>
                  <div class="col-md-3">
                    <input type="text" name="to_custom_date_trial" class="form-control to_custom_date_trial" value="<?php echo $last_date; ?>" disabled>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              
            </div>
          </div>

          


          <div class="col-md-12" style="margin-top:5px">
            
          </div>
          <div class="col-md-12 load_balance_div" style="margin-top:20px;background: #fff; height:650px;max-height: 650px;overflow-y: scroll">
            <table class="table own_table_white">
              <thead>
                <th>Nominal <br/>Code <i class="fa fa-sort trial_code_sort" style="float: right"></i></th>
                <th>Nominal <br/>Description <i class="fa fa-sort trial_des_sort" style="float: right"></i></th>
                <th>Primary <br/>Group <i class="fa fa-sort trial_primary_sort" style="float: right"></i></th>
                <th>Debit <br/>Value <i class="fa fa-sort trial_debit_sort" style="float: right"></i></th>
                <th>Credit <br/>Value <i class="fa fa-sort trial_credit_sort" style="float: right"></i></th>
              </thead>
              <tbody id="trial_balance_tbody">
                <tr>
                  <td colspan="5" style="text-align:center">No Data Found</td>
                </tr>
              </tbody>
              <tr class="total_debit_credit_tr" style="display: none">
                <td colspan="3">Total</td>
                <td class="total_nominal_debit" style="text-align:right"></td>
                <td class="total_nominal_credit" style="text-align:right"></td>
            </table>
          </div>
        </div>
    </div>
    <!-- End  -->
  <div class="main-backdrop"><!-- --></div>
  <div class="modal_load"></div>
  <div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until Reconcile Process to be Processed.</p>
  <p style="font-size:18px;font-weight: 600;">Processing : <span id="apply_first"></span> of <span id="apply_last"></span></p>
</div>
  <div class="modal_load_balance" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Loading Opening Balance</p> </div>
  <div class="modal_load_trial" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Loading Nominal Details</p> </div>
  <div class="modal_load_receipt" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Calculating Client Money Received</p> </div>
  <div class="modal_load_payment" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Calculating Payments Made</p> </div>
  <div class="modal_load_calculation" style="text-align: center;"> <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Calculating the Sum of Balance.</p> </div>
  <input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
  <input type="hidden" name="show_alert" id="show_alert" value="">
  <input type="hidden" name="pagination" id="pagination" value="1">

  <input type="hidden" name="code_sortoptions" id="code_sortoptions" value="asc">
  <input type="hidden" name="des_sortoptions" id="des_sortoptions" value="asc">
  <input type="hidden" name="primary_sortoptions" id="primary_sortoptions" value="asc">
  <input type="hidden" name="debit_sortoptions" id="debit_sortoptions" value="asc">
  <input type="hidden" name="credit_sortoptions" id="credit_sortoptions" value="asc">

  <input type="hidden" name="journal_id_sortoptions" id="journal_id_sortoptions" value="asc">
  <input type="hidden" name="journal_date_sortoptions" id="journal_date_sortoptions" value="asc">
  <input type="hidden" name="journal_des_sortoptions" id="journal_des_sortoptions" value="asc">
  <input type="hidden" name="nominal_code_sortoptions" id="nominal_code_sortoptions" value="asc">
  <input type="hidden" name="nominal_des_sortoptions" id="nominal_des_sortoptions" value="asc">
  <input type="hidden" name="source_sortoptions" id="source_sortoptions" value="asc">
  <input type="hidden" name="debit_journal_sortoptions" id="debit_journal_sortoptions" value="asc">
  <input type="hidden" name="credit_journal_sortoptions" id="credit_journal_sortoptions" value="asc">

  <input type="hidden" name="trial_code_sortoptions" id="trial_code_sortoptions" value="asc">
  <input type="hidden" name="trial_des_sortoptions" id="trial_des_sortoptions" value="asc">
  <input type="hidden" name="trial_primary_sortoptions" id="trial_primary_sortoptions" value="asc">
  <input type="hidden" name="trial_debit_sortoptions" id="trial_debit_sortoptions" value="asc">
  <input type="hidden" name="trial_credit_sortoptions" id="trial_credit_sortoptions" value="asc">


  <input type="hidden" name="client_sortoptions" id="client_sortoptions" value="asc">
  <input type="hidden" name="surname_sortoptions" id="surname_sortoptions" value="asc">
  <input type="hidden" name="firstname_sortoptions" id="firstname_sortoptions" value="asc">
  <input type="hidden" name="company_sortoptions" id="company_sortoptions" value="asc">
  <input type="hidden" name="debit_fin_sortoptions" id="debit_fin_sortoptions" value="asc">
  <input type="hidden" name="credit_fin_sortoptions" id="credit_fin_sortoptions" value="asc">
  <input type="hidden" name="balance_fin_sortoptions" id="balance_fin_sortoptions" value="asc">

  <input type="hidden" name="client_summary_sortoptions" id="client_summary_sortoptions" value="asc">
  <input type="hidden" name="surname_summary_sortoptions" id="surname_summary_sortoptions" value="asc">
  <input type="hidden" name="firstname_summary_sortoptions" id="firstname_summary_sortoptions" value="asc">
  <input type="hidden" name="company_summary_sortoptions" id="company_summary_sortoptions" value="asc">

  <input type="hidden" name="opening_bal_summary_sortoptions" id="opening_bal_summary_sortoptions" value="asc">
  <input type="hidden" name="receipt_summary_sortoptions" id="receipt_summary_sortoptions" value="asc">
  <input type="hidden" name="payment_summary_sortoptions" id="payment_summary_sortoptions" value="asc">
  <input type="hidden" name="balance_summary_sortoptions" id="balance_summary_sortoptions" value="asc">

</div>

<script>

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
  var id = $(".process_journal").eq(count).attr("data-element");
  var bank_id = $(".select_reconcile_bank").val();
  if($(".process_journal").eq(count).hasClass('receipt_clear'))
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
        if($(".process_journal:eq("+countval+")").length > 0)
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
    $("body").addClass('loading_apply')
    $("#apply_last").html(countval);
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

$(document).ready(function() {
//   $('#client_financial').DataTable({
//     fixedHeader: {
//       header: true,
//       headerOffset: 500,
//     },
//     autoWidth: false,
//     scrollX: false,
//     searching: false,
//     paging: false,
//     info: false,
//     ordering: false,
// });
  $(".opening_financial_date").datetimepicker({     
     format: 'L',
     format: 'DD-MMM-YYYY',
  });

  $(".from_custom_date").datetimepicker({
     format: 'L',
     format: 'DD/MM/YYYY',
  });

  $(".to_custom_date").datetimepicker({
     format: 'L',
     format: 'DD/MM/YYYY',
  });
  $(".from_custom_date_trial").datetimepicker({
     format: 'L',
     format: 'DD/MM/YYYY',
  });

  $(".to_custom_date_trial").datetimepicker({
     format: 'L',
     format: 'DD/MM/YYYY',
  });

  $(".date_balance_bank").datetimepicker({
     defaultDate: "",
     format: 'L',
     format: 'DD/MM/YYYY',
  });
  


  // $(".opening_financial_date").on("dp.hide", function (e) {
  //     var opening_balance_date = $(".opening_financial_date").val();
  //     $.ajax({
  //       url:"<?php echo URL::to('user/save_opening_balance_date'); ?>",
  //       type:"post",
  //       data:{opening_balance_date:opening_balance_date},
  //       success:function(result)
  //       {

  //       }
  //     })
  // });
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
       format: 'YYYY-MM-DD',
    });
    $(".to_invoice").datetimepicker({
       defaultDate: fullDate,       
       format: 'L',
       format: 'YYYY-MM-DD',
    });
});


$(window).change(function(e){

// if($(e.target).hasClass('general_debit')){
//   var value = $(e.target).val();
//   if((value == '') || (value == 0)){
//     $(e.target).val('0.00');
//     $(e.target).parents("tr").find(".general_credit").attr("disabled", false);
//   }  
//   else{
//     $(e.target).parents("tr").find(".general_credit").attr("disabled", true);
//   }
//   var total_debit = 0.00;
//   var total_credit = 0.00;

//   console.log($('.general_debit').length);
//   $('.general_debit').each(function() {
//     var debit_val = $(this).val();
//     console.log(debit_val);
//     var floatdebit = parseFloat(debit_val).toFixed(2);
//     total_debit = parseFloat(total_debit + floatdebit).toFixed(2);
//   });

//   $('.general_credit').each(function() {
//     var credit_val = $(this).val();
//     var floatcredit = parseFloat(credit_val).toFixed(2);
//     total_credit = parseFloat(total_credit + floatcredit).toFixed(2);
//   });

//   $(".general_debit_total").val(total_debit);
//   $(".general_credit_total").val(total_credit);
// }

// if($(e.target).hasClass('general_credit')){
//   var value = $(e.target).val();
//   if((value == '') || (value == 0)){
//     $(e.target).val('0.00');
//     $(e.target).parents("tr").find(".general_debit").attr("disabled", false);
//   }  
//   else{
//     $(e.target).parents("tr").find(".general_debit").attr("disabled", true);
//   }

//   var total_debit = 0.00;
//   var total_credit = 0.00;

//   $('.general_debit').each(function() {
//     var debit_val = $(this).val();
//     var floatdebit = parseFloat(debit_val).toFixed(2);
//     total_debit = parseFloat(total_debit + floatdebit).toFixed(2);
//   });

//   $('.general_credit').each(function() {
//     var credit_val = $(this).val();
//     var floatcredit = parseFloat(credit_val).toFixed(2);
//     total_credit = parseFloat(total_credit + floatcredit).toFixed(2);
//   });

//   $(".general_debit_total").val(total_debit);
//   $(".general_credit_total").val(total_credit);
// }

if($(e.target).hasClass('general_nominal')){
  var code = $(e.target).val();

  

  if(code == '712'){
    $(e.target).parents("tr").find(".error-general-nominal").show();
    $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Debtors Control Account.');
    $(".save_general_journal_button").hide();

    $(".general_nominal").attr("disabled", true);
    $(e.target).not().attr("disabled", false);
  }
  else if(code == '813'){
    $(e.target).parents("tr").find(".error-general-nominal").show();
    $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Creditors Control Account.');
    $(".save_general_journal_button").hide();

    $(".general_nominal").attr("disabled", true);
    $(e.target).not().attr("disabled", false);
  }
  else if(code == '813A'){
    $(e.target).parents("tr").find(".error-general-nominal").show();
    $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Client holding account Account.');
    $(".save_general_journal_button").hide();

    $(".general_nominal").attr("disabled", true);
    $(e.target).not().attr("disabled", false);
  }
  else if((code >= '771') && (code < '772')){
    $(e.target).parents("tr").find(".error-general-nominal").show();
    $(e.target).parents("tr").find(".error-general-nominal").html('Can Not Journal Direct into the Bank Nominal accounts Account.');
    $(".save_general_journal_button").hide();

    $(".general_nominal").attr("disabled", true);
    $(e.target).not().attr("disabled", false);
  }
  else{
    $(e.target).parents("tr").find(".error-general-nominal").hide();
    $(".general_nominal").attr("disabled", false);
    $(".save_general_journal_button").show();
  }

}
  if($(e.target).hasClass('select_reconcile_bank')){
    var value = $(e.target).val();

    if(value == ''){
      $(".error_select_bank").show();
      $(".table_bank_details").hide();
    }
    else{
      $(".error_select_bank").hide();
      $.ajax({
        url:"<?php echo URL::to('user/finance_get_bank_details'); ?>",
        type:"post",
        dataType:"json",
        data:{id:value},
        success:function(result){
          $(".td_bank_name").html(result['bank_name']);
          $(".tb_ac_name").html(result['account_name']);
          $(".td_ac_number").html(result['account_number']);
          $(".td_ac_description").html(result['description']);
          $(".td_nominal_code").html(result['nominal_code']);
          $(".table_bank_details").show();
          
          
        }
      });
    }

  }



  if($(e.target).hasClass('primary_grp_add'))
  {
    var value = $(e.target).val();
    if(value == "Profit & Loss")
    {
      $(".debit_group_div").show();
      $(".credit_group_div").hide();

      $(".debit_grp_add").html('<option value="">Select Value</option><option value="Sales">Sales</option><option value="Other Income">Other Income</option><option value="Cost of Sales">Cost of Sales</option><option value="Administrative Expenses">Administrative Expenses</option><option value="Taxes">Taxes</option>');
      $(".credit_grp_add").html('<option value="">Select Value</option><option value="Sales">Sales</option><option value="Other Income">Other Income</option><option value="Cost of Sales">Cost of Sales</option><option value="Administrative Expenses">Administrative Expenses</option><option value="Taxes">Taxes</option>');
    }
    else{
      $(".debit_group_div").show();
      $(".credit_group_div").show();

      $(".debit_grp_add").html('<option value="">Select Value</option><option value="Fixed Assets">Fixed Assets</option><option value="Current Assets">Current Assets</option><option value="Current Liabilities">Current Liabilities</option><option value="Long Term Liabilities">Long Term Liabilities</option><option value="Capital Account">Capital Account</option>');
      $(".credit_grp_add").html('<option value="">Select Value</option><option value="Fixed Assets">Fixed Assets</option><option value="Current Assets">Current Assets</option><option value="Current Liabilities">Current Liabilities</option><option value="Long Term Liabilities">Long Term Liabilities</option><option value="Capital Account">Capital Account</option>');
    }
  }
  if($(e.target).hasClass('debit_grp_add'))
  {
    var primary = $(".primary_grp_add").val();
    var debit = $(".debit_grp_add").val();
    var credit = $(".credit_grp_add").val();

    if(primary == "Balance Sheet")
    {
      if(debit == credit)
      {
        alert("The Debit & Credit Selections should be Different if the Primary Group is Balance Sheet");
        $(".debit_grp_add").val("");
        return false;
      }
    }
  }
  if($(e.target).hasClass('credit_grp_add'))
  {
    var primary = $(".primary_grp_add").val();
    var debit = $(".debit_grp_add").val();
    var credit = $(".credit_grp_add").val();

    if(primary == "Balance Sheet")
    {
      if(debit == credit)
      {
        alert("The Debit & Credit Selections should be Different if the Primary Group is Balance Sheet");
        $(".credit_grp_add").val("");
        return false;
      }
    }
  }
});
var convertToNumber = function(value){
       return value.toLowerCase();
}
var parseconvertToNumber = function(value){
       return parseFloat(value);
}
$(window).keyup(function(e) {
  if($(e.target).hasClass('debit_balance_add'))
  {
    var debitvalue = $(e.target).val();
    if(debitvalue != "")
    {
      $(".credit_balance_add").prop("disabled",true);
      $(".credit_balance_add").val("");
    }
    else{
      $(".credit_balance_add").prop("disabled",false);
    }
  }
  if($(e.target).hasClass('credit_balance_add'))
  {
    var creditvalue = $(e.target).val();
    if(creditvalue != "")
    {
      $(".debit_balance_add").prop("disabled",true);
      $(".debit_balance_add").val("");
    }
    else{
      $(".debit_balance_add").prop("disabled",false);
    }
  }
});
function load_opening_balance()
{
  $("body").addClass("loading_balance");
  $.ajax({
    url:"<?php echo URL::to('user/summary_load_opening_balance'); ?>",
    type:"post",
    dataType:"json",
    success:function(result)
    {
      var i = 0;
      $('#summary_tbody').find('tr').each(function(){
        $(this).find('td').eq(4).html(result['output'][i]);
        i++;
      });
      $(".total_opening_balance_summary").html(result['total']);
      setTimeout(function() {
          $("body").removeClass("loading_balance");
          load_receipts();
      },1000);
      
    }
  });
}
function load_receipts()
{
  $("body").addClass("loading_receipts");
  $.ajax({
    url:"<?php echo URL::to('user/summary_load_receipts'); ?>",
    type:"post",
    dataType:"json",
    success:function(result)
    {
      var i = 0;
      $('#summary_tbody').find('tr').each(function(){
        $(this).find('td').eq(5).html(result['output'][i]);
        i++;
      });
      $(".total_receipt_summary").html(result['total']);
      setTimeout(function() {
          $("body").removeClass("loading_receipts");
          load_payments();
      },1000);
      
    }
  });
}
function load_payments()
{
  $("body").addClass("loading_payments");
  $.ajax({
    url:"<?php echo URL::to('user/summary_load_payments'); ?>",
    type:"post",
    dataType:"json",
    success:function(result)
    {
      var i = 0;
      $('#summary_tbody').find('tr').each(function(){
        $(this).find('td').eq(6).html(result['output'][i]);
        i++;
      });
      $(".total_payment_summary").html(result['total']);
      setTimeout(function() {
         $("body").removeClass("loading_payments");
          calculate_payments();
      },1000);
      
    }
  });
}
function calculate_payments()
{
  $("body").addClass("loading_calculations");
  $.ajax({
    url:"<?php echo URL::to('user/summary_calculations'); ?>",
    type:"post",
    dataType:"json",
    success:function(result)
    {
      var i = 0;
      $('#summary_tbody').find('tr').each(function(){
        $(this).find('td').eq(7).html(result['output'][i]);
        i++;
      });
      $(".total_balance_summary").html(result['total']);
      setTimeout(function() {
         $("body").removeClass("loading_calculations");
      },1000);
    }
  });
}
$(window).click(function(e) { 
  var ascending = false;
  if($(e.target).hasClass('journal_id_sort'))
  {
    var sort = $("#journal_id_sortoptions").val();
    if(sort == 'asc')
    {
      $("#journal_id_sortoptions").val('desc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.journal_id_sortval').html()) <
        parseconvertToNumber($(b).find('.journal_id_sortval').html()))) ? 1 : -1;
      });
    }
    else{
      $("#journal_id_sortoptions").val('asc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.journal_id_sortval').html()) <
        parseconvertToNumber($(b).find('.journal_id_sortval').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#load_journals_tbody').html(sorted);
  }

  if($(e.target).hasClass('journal_date_sort'))
  {
    var sort = $("#journal_date_sortoptions").val();
    if(sort == 'asc')
    {
      $("#journal_date_sortoptions").val('desc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.journal_date_sortval').html()) <
        parseconvertToNumber($(b).find('.journal_date_sortval').html()))) ? 1 : -1;
      });
    }
    else{
      $("#journal_date_sortoptions").val('asc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.journal_date_sortval').html()) <
        parseconvertToNumber($(b).find('.journal_date_sortval').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#load_journals_tbody').html(sorted);
  }

  if($(e.target).hasClass('journal_des_sort'))
  {
    var sort = $("#journal_des_sortoptions").val();
    if(sort == 'asc')
    {
      $("#journal_des_sortoptions").val('desc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.journal_des_sortval').html()) <
        convertToNumber($(b).find('.journal_des_sortval').html()))) ? 1 : -1;
      });
    }
    else{
      $("#journal_des_sortoptions").val('asc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.journal_des_sortval').html()) <
        convertToNumber($(b).find('.journal_des_sortval').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#load_journals_tbody').html(sorted);
  }

  if($(e.target).hasClass('nominal_code_sort'))
  {
    var sort = $("#nominal_code_sortoptions").val();
    if(sort == 'asc')
    {
      $("#nominal_code_sortoptions").val('desc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.nominal_code_sortval').html()) <
        parseconvertToNumber($(b).find('.nominal_code_sortval').html()))) ? 1 : -1;
      });
    }
    else{
      $("#nominal_code_sortoptions").val('asc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.nominal_code_sortval').html()) <
        parseconvertToNumber($(b).find('.nominal_code_sortval').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#load_journals_tbody').html(sorted);
  }

  if($(e.target).hasClass('nominal_des_sort'))
  {
    var sort = $("#nominal_des_sortoptions").val();
    if(sort == 'asc')
    {
      $("#nominal_des_sortoptions").val('desc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.nominal_des_sortval').html()) <
        convertToNumber($(b).find('.nominal_des_sortval').html()))) ? 1 : -1;
      });
    }
    else{
      $("#nominal_des_sortoptions").val('asc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.nominal_des_sortval').html()) <
        convertToNumber($(b).find('.nominal_des_sortval').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#load_journals_tbody').html(sorted);
  }

  if($(e.target).hasClass('source_sort'))
  {
    var sort = $("#source_sortoptions").val();
    if(sort == 'asc')
    {
      $("#source_sortoptions").val('desc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.source_sortval').html()) <
        convertToNumber($(b).find('.source_sortval').html()))) ? 1 : -1;
      });
    }
    else{
      $("#source_sortoptions").val('asc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.source_sortval').html()) <
        convertToNumber($(b).find('.source_sortval').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#load_journals_tbody').html(sorted);
  }

  if($(e.target).hasClass('debit_journal_sort'))
  {
    var sort = $("#debit_journal_sortoptions").val();
    if(sort == 'asc')
    {
      $("#debit_journal_sortoptions").val('desc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.debit_journal_sortval').html()) <
        parseconvertToNumber($(b).find('.debit_journal_sortval').html()))) ? 1 : -1;
      });
    }
    else{
      $("#debit_journal_sortoptions").val('asc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.debit_journal_sortval').html()) <
        parseconvertToNumber($(b).find('.debit_journal_sortval').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#load_journals_tbody').html(sorted);
  }

  if($(e.target).hasClass('credit_journal_sort'))
  {
    var sort = $("#credit_journal_sortoptions").val();
    if(sort == 'asc')
    {
      $("#credit_journal_sortoptions").val('desc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.credit_journal_sortval').html()) <
        parseconvertToNumber($(b).find('.credit_journal_sortval').html()))) ? 1 : -1;
      });
    }
    else{
      $("#credit_journal_sortoptions").val('asc');
      var sorted = $('#load_journals_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.credit_journal_sortval').html()) <
        parseconvertToNumber($(b).find('.credit_journal_sortval').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#load_journals_tbody').html(sorted);
  }


  if($(e.target).hasClass('trial_code_sort'))
  {
    var sort = $("#trial_code_sortoptions").val();
    if(sort == 'asc')
    {
      $("#trial_code_sortoptions").val('desc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.code_trial_sort_val').html()) <
        parseconvertToNumber($(b).find('.code_trial_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#trial_code_sortoptions").val('asc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.code_trial_sort_val').html()) <
        parseconvertToNumber($(b).find('.code_trial_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#trial_balance_tbody').html(sorted);
  }

  if($(e.target).hasClass('trial_des_sort'))
  {
    var sort = $("#trial_des_sortoptions").val();
    if(sort == 'asc')
    {
      $("#trial_des_sortoptions").val('desc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.des_trial_sort_val').html()) <
        convertToNumber($(b).find('.des_trial_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#trial_des_sortoptions").val('asc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.des_trial_sort_val').html()) <
        convertToNumber($(b).find('.des_trial_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#trial_balance_tbody').html(sorted);
  }

  if($(e.target).hasClass('trial_primary_sort'))
  {
    var sort = $("#trial_primary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#trial_primary_sortoptions").val('desc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.primary_trial_sort_val').html()) <
        convertToNumber($(b).find('.primary_trial_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#trial_primary_sortoptions").val('asc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.primary_trial_sort_val').html()) <
        convertToNumber($(b).find('.primary_trial_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#trial_balance_tbody').html(sorted);
  }

  if($(e.target).hasClass('trial_debit_sort'))
  {
    var sort = $("#trial_debit_sortoptions").val();
    if(sort == 'asc')
    {
      $("#trial_debit_sortoptions").val('desc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.debit_trial_sort_val').html()) <
        parseconvertToNumber($(b).find('.debit_trial_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#trial_debit_sortoptions").val('asc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.debit_trial_sort_val').html()) <
        parseconvertToNumber($(b).find('.debit_trial_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#trial_balance_tbody').html(sorted);
  }

  if($(e.target).hasClass('trial_credit_sort'))
  {
    var sort = $("#trial_credit_sortoptions").val();
    if(sort == 'asc')
    {
      $("#trial_credit_sortoptions").val('desc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.credit_trial_sort_val').html()) <
        parseconvertToNumber($(b).find('.credit_trial_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#trial_credit_sortoptions").val('asc');
      var sorted = $('#trial_balance_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.credit_trial_sort_val').html()) <
        parseconvertToNumber($(b).find('.credit_trial_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#trial_balance_tbody').html(sorted);
  }



  if($(e.target).hasClass('code_sort'))
  {
    var sort = $("#code_sortoptions").val();
    if(sort == 'asc')
    {
      $("#code_sortoptions").val('desc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.code_sort_val').html()) <
        parseconvertToNumber($(b).find('.code_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#code_sortoptions").val('asc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.code_sort_val').html()) <
        parseconvertToNumber($(b).find('.code_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#nominal_tbody').html(sorted);
  }
  if($(e.target).hasClass('des_sort'))
  {
    var sort = $("#des_sortoptions").val();
    if(sort == 'asc')
    {
      $("#des_sortoptions").val('desc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.des_sort_val').html()) <
        convertToNumber($(b).find('.des_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#des_sortoptions").val('asc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.des_sort_val').html()) <
        convertToNumber($(b).find('.des_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#nominal_tbody').html(sorted);
  }
  if($(e.target).hasClass('primary_sort'))
  {
    var sort = $("#primary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#primary_sortoptions").val('desc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.primary_sort_val').html()) <
        convertToNumber($(b).find('.primary_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#primary_sortoptions").val('asc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.primary_sort_val').html()) <
        convertToNumber($(b).find('.primary_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#nominal_tbody').html(sorted);
  }
  if($(e.target).hasClass('debit_sort'))
  {
    var sort = $("#debit_sortoptions").val();
    if(sort == 'asc')
    {
      $("#debit_sortoptions").val('desc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.debit_sort_val').html()) <
        convertToNumber($(b).find('.debit_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#debit_sortoptions").val('asc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.debit_sort_val').html()) <
        convertToNumber($(b).find('.debit_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#nominal_tbody').html(sorted);
  }
  if($(e.target).hasClass('credit_sort'))
  {
    var sort = $("#credit_sortoptions").val();
    if(sort == 'asc')
    {
      $("#credit_sortoptions").val('desc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.credit_sort_val').html()) <
        convertToNumber($(b).find('.credit_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#credit_sortoptions").val('asc');
      var sorted = $('#nominal_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.credit_sort_val').html()) <
        convertToNumber($(b).find('.credit_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#nominal_tbody').html(sorted);
  }

  if($(e.target).hasClass('client_sort'))
  {
    var sort = $("#client_sortoptions").val();
    if(sort == 'asc')
    {
      $("#client_sortoptions").val('desc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_sort_val').html()) <
        convertToNumber($(b).find('.client_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#client_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_sort_val').html()) <
        convertToNumber($(b).find('.client_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_tbody').html(sorted);
  }

  if($(e.target).hasClass('surname_sort'))
  {
    var sort = $("#surname_sortoptions").val();
    if(sort == 'asc')
    {
      $("#surname_sortoptions").val('desc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.surname_sort_val').html()) <
        convertToNumber($(b).find('.surname_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#surname_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.surname_sort_val').html()) <
        convertToNumber($(b).find('.surname_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_tbody').html(sorted);
  }

  if($(e.target).hasClass('firstname_sort'))
  {
    var sort = $("#firstname_sortoptions").val();
    if(sort == 'asc')
    {
      $("#firstname_sortoptions").val('desc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.firstname_sort_val').html()) <
        convertToNumber($(b).find('.firstname_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#firstname_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.firstname_sort_val').html()) <
        convertToNumber($(b).find('.firstname_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_tbody').html(sorted);
  }

  if($(e.target).hasClass('company_sort'))
  {
    var sort = $("#company_sortoptions").val();
    if(sort == 'asc')
    {
      $("#company_sortoptions").val('desc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.company_sort_val').html()) <
        convertToNumber($(b).find('.company_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#company_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.company_sort_val').html()) <
        convertToNumber($(b).find('.company_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_tbody').html(sorted);
  }

  if($(e.target).hasClass('client_summary_sort'))
  {
    var sort = $("#client_summary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#client_summary_sortoptions").val('desc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_summary_sort_val').find('a').html()) <
        convertToNumber($(b).find('.client_summary_sort_val').find('a').html()))) ? 1 : -1;
      });
    }
    else{
      $("#client_summary_sortoptions").val('asc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_summary_sort_val').find('a').html()) <
        convertToNumber($(b).find('.client_summary_sort_val').find('a').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#summary_tbody').html(sorted);
  }

  if($(e.target).hasClass('surname_summary_sort'))
  {
    var sort = $("#surname_summary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#surname_summary_sortoptions").val('desc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.surname_summary_sort_val').find('a').html()) <
        convertToNumber($(b).find('.surname_summary_sort_val').find('a').html()))) ? 1 : -1;
      });
    }
    else{
      $("#surname_summary_sortoptions").val('asc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.surname_summary_sort_val').find('a').html()) <
        convertToNumber($(b).find('.surname_summary_sort_val').find('a').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#summary_tbody').html(sorted);
  }

  if($(e.target).hasClass('firstname_summary_sort'))
  {
    var sort = $("#firstname_summary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#firstname_summary_sortoptions").val('desc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.firstname_summary_sort_val').find('a').html()) <
        convertToNumber($(b).find('.firstname_summary_sort_val').find('a').html()))) ? 1 : -1;
      });
    }
    else{
      $("#firstname_summary_sortoptions").val('asc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.firstname_summary_sort_val').find('a').html()) <
        convertToNumber($(b).find('.firstname_summary_sort_val').find('a').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#summary_tbody').html(sorted);
  }

  if($(e.target).hasClass('company_summary_sort'))
  {
    var sort = $("#company_summary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#company_summary_sortoptions").val('desc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.company_summary_sort_val').find('a').html()) <
        convertToNumber($(b).find('.company_summary_sort_val').find('a').html()))) ? 1 : -1;
      });
    }
    else{
      $("#company_summary_sortoptions").val('asc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.company_summary_sort_val').find('a').html()) <
        convertToNumber($(b).find('.company_summary_sort_val').find('a').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#summary_tbody').html(sorted);
  }

  if($(e.target).hasClass('debit_fin_sort'))
  {
    var sort = $("#debit_fin_sortoptions").val();
    if(sort == 'asc')
    {
      $("#debit_fin_sortoptions").val('desc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.debit_fin_sort_val').val();
        var bval = $(b).find('.debit_fin_sort_val').val();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? 1 : -1;
      });
    }
    else{
      $("#debit_fin_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.debit_fin_sort_val').val();
        var bval = $(b).find('.debit_fin_sort_val').val();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_tbody').html(sorted);
  }

  if($(e.target).hasClass('credit_fin_sort'))
  {
    var sort = $("#credit_fin_sortoptions").val();
    if(sort == 'asc')
    {
      $("#credit_fin_sortoptions").val('desc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.credit_fin_sort_val').val();
        var bval = $(b).find('.credit_fin_sort_val').val();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? 1 : -1;
      });
    }
    else{
      $("#credit_fin_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.credit_fin_sort_val').val();
        var bval = $(b).find('.credit_fin_sort_val').val();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_tbody').html(sorted);
  }

  if($(e.target).hasClass('balance_fin_sort'))
  {
    var sort = $("#balance_fin_sortoptions").val();
    if(sort == 'asc')
    {
      $("#balance_fin_sortoptions").val('desc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.balance_fin_sort_val').val();
        var bval = $(b).find('.balance_fin_sort_val').val();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? 1 : -1;
      });
    }
    else{
      $("#balance_fin_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.balance_fin_sort_val').val();
        var bval = $(b).find('.balance_fin_sort_val').val();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_tbody').html(sorted);
  }

  if($(e.target).hasClass('opening_bal_summary_sort'))
  {
    var sort = $("#opening_bal_summary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#opening_bal_summary_sortoptions").val('desc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.opening_bal_summary_sort_val').html();
        var bval = $(b).find('.opening_bal_summary_sort_val').html();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? 1 : -1;
      });
    }
    else{
      $("#opening_bal_summary_sortoptions").val('asc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.opening_bal_summary_sort_val').html();
        var bval = $(b).find('.opening_bal_summary_sort_val').html();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#summary_tbody').html(sorted);
  }
  if($(e.target).hasClass('receipt_summary_sort'))
  {
    var sort = $("#receipt_summary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#receipt_summary_sortoptions").val('desc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.receipt_summary_sort_val').html();
        var bval = $(b).find('.receipt_summary_sort_val').html();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? 1 : -1;
      });
    }
    else{
      $("#receipt_summary_sortoptions").val('asc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.receipt_summary_sort_val').html();
        var bval = $(b).find('.receipt_summary_sort_val').html();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#summary_tbody').html(sorted);
  }
  if($(e.target).hasClass('payment_summary_sort'))
  {
    var sort = $("#payment_summary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#payment_summary_sortoptions").val('desc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.payment_summary_sort_val').html();
        var bval = $(b).find('.payment_summary_sort_val').html();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? 1 : -1;
      });
    }
    else{
      $("#payment_summary_sortoptions").val('asc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.payment_summary_sort_val').html();
        var bval = $(b).find('.payment_summary_sort_val').html();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#summary_tbody').html(sorted);
  }
  if($(e.target).hasClass('balance_summary_sort'))
  {
    var sort = $("#balance_summary_sortoptions").val();
    if(sort == 'asc')
    {
      $("#balance_summary_sortoptions").val('desc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.balance_summary_sort_val').html();
        var bval = $(b).find('.balance_summary_sort_val').html();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? 1 : -1;
      });
    }
    else{
      $("#balance_summary_sortoptions").val('asc');
      var sorted = $('#summary_tbody').find('tr').sort(function(a,b){
        var aval = $(a).find('.balance_summary_sort_val').html();
        var bval = $(b).find('.balance_summary_sort_val').html();
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');
        aval = aval.replace(',','');

        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        bval = bval.replace(',','');
        return (ascending ==
             (parseconvertToNumber(aval) <
        parseconvertToNumber(bval))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#summary_tbody').html(sorted);
  }
  if($(e.target).hasClass('open_client_review'))
  {
    var client_id = $(e.target).attr("data-element");
    var src = "<?php echo URL::to('user/client_account_review_summary'); ?>?client_id="+client_id;
    $("#client_revew_iframe").attr("src", src);
    $(".open_client_review_modal").modal("show");
  }
  if($(e.target).hasClass('load_summary_clients'))
  {
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/summary_clients_list'); ?>",
      type:"post",
      success:function(result)
      {
        $("#summary_tbody").html(result);
        $("#summary_financial").show();
        setTimeout(function() {
          $("body").removeClass("loading");
          load_opening_balance();
        },1000);
      }
    });
  }
  $(e.target.id == "profile-tab")
  {
    //$("#summary_financial").hide();
    $(".export_csv_client_opening").hide();
    $(".export_csv_summary").show();
  }
  if(e.target.id == "home-tab")
  {
    $(".export_csv_client_opening").show();
    $(".export_csv_summary").hide();
  }
  if($(e.target).hasClass('commit_btn'))
  {
    var client_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/commit_client_account_opening_balance'); ?>",
      type:"post",
      data:{client_id:client_id},
      success:function(result)
      {
        $(e.target).parents("td:first").html('<a href="javascript:" class="journal_id_viewer" data-element="'+result+'">'+result+'</a>')
      }
    })
  }
  if($(e.target).hasClass('journal_source'))
  {
    $(".journal_source_viewer_modal").modal("show");
  }
  if($(e.target).hasClass('question_mark_btn'))
  {
    $(".question_mark_modal").modal("show");
  }
  if($(e.target).hasClass('export_csv_client_opening'))
  {
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/export_csv_client_opening'); ?>",
      type:"post",
      success:function(result)
      {
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('export_csv_summary'))
  {
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/summary_export_csv'); ?>",
      type:"post",
      success:function(result)
      {
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('client_finance_account_btn'))
  {
    var date = $(".opening_date_spam").html();
    $(".opening_balance_date_spam").html(date);
    $(".client_finance_modal").modal("show");
  }
  if($(e.target).hasClass('load_journals'))
  {
    $("body").addClass("loading");
    var selection = $(".date_selection:checked").val();
    var from = $(".from_custom_date").val();
    var to = $(".to_custom_date").val();

    $.ajax({
      url:"<?php echo URL::to('user/load_journals_financials'); ?>",
      type:"post",
      data:{selection:selection,from:from,to:to},
      success:function(result)
      {
        $(".load_journal_div").html(result);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('load_balance'))
  {
    $("body").addClass("loading_trial");
    var selection = $(".date_selection_trial:checked").val();
    var from = $(".from_custom_date_trial").val();
    var to = $(".to_custom_date_trial").val();

    $.ajax({
      url:"<?php echo URL::to('user/load_trial_balance_nominals'); ?>",
      type:"post",
      data:{selection:selection,from:from,to:to},
      dataType:"json",
      success:function(result)
      {
        $("#trial_balance_tbody").html(result['output']);
        $(".total_nominal_debit").html(result['total_nominal_debit']);
        $(".total_nominal_credit").html(result['total_nominal_credit']);
        $(".total_debit_credit_tr").show();
        $(".remove_nil_balances").show();
        $("body").removeClass("loading_trial");
      }
    })
  }
  if($(e.target).hasClass('remove_nil_balances'))
  {
    $("#trial_balance_tbody").find(".nil_balance_tr").detach();
  }
  if($(e.target).hasClass('get_nominal_code_journals'))
  {
    var code = $(e.target).attr("data-element");
    var debit = $(e.target).parents("tr").find(".debit_trial_sort_val").html();
    var credit = $(e.target).parents("tr").find(".credit_trial_sort_val").html();
    var opening = $(e.target).attr("data-opening");

    var selection = $(".date_selection_trial:checked").val();
    var from = $(".from_custom_date_trial").val();
    var to = $(".to_custom_date_trial").val();
    $("body").addClass('loading');
    if((debit == "0.00" || debit == "0" || debit == "")&& (credit == "0.00" || credit == "0" || credit == ""))
    {
      alert("There is no journals created for this Nominal Code");
      $("body").removeClass('loading');
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('user/load_trial_balance_journals_for_nominal'); ?>",
        type:"post",
        data:{selection:selection,from:from,to:to,code:code,opening:opening},
        success:function(result)
        {
          $(".trial_balance_journal_modal").modal("show");
          $("#trial_balance_journal_tbody").html(result);
          $("body").removeClass("loading");
        }
      })
    }
  }
  if($(e.target).hasClass('edit_opening_balance_btn'))
  {
    $(".opening_date_spam").hide();
    $(e.target).hide();
    $(".opening_financial_date").show();
    $(".save_opening_balance_btn").show();
  }
  if($(e.target).hasClass('save_opening_balance_btn'))
  {
    var opening_balance_date = $(".opening_financial_date").val();
    $.ajax({
      url:"<?php echo URL::to('user/save_opening_balance_date'); ?>",
      type:"post",
      data:{opening_balance_date:opening_balance_date},
      success:function(result)
      {
        $(".opening_date_spam").show();
        $(".edit_opening_balance_btn").show();
        $(".opening_financial_date").hide();
        $(".save_opening_balance_btn").hide();

        $(".opening_date_spam").html(opening_balance_date);
      }
    })
  }
  if($(e.target).hasClass('bank_account_manager'))
  {
    $(".bank_account_manager_modal").modal("show");
  }
  if($(e.target).hasClass('reconcile_icon'))
  {
    var value = $(e.target).attr("data-element");
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
  if($(e.target).hasClass('add_nominal'))
  {
    $(".add_nominal_modal").modal("show");
    $(".add_nominal_modal").find(".modal-title").html("Add Nominal");
    $(".add_nominal_btn").val("Add Nominal");
    $(".nominal_code_add").prop("disabled",false);
    $(".nominal_code_add").val('');
    $(".description_add").val('');
    $(".primary_grp_add").val('');
    $(".debit_grp_add").val('');
    $(".credit_grp_add").val('');
    $(".debit_group_div").hide();
    $(".credit_group_div").hide();
  }
  if($(e.target).hasClass('save_opening_balance'))
  {
    var debit_balance = $(".debit_balance_add").val();
    var credit_balance = $(".credit_balance_add").val();
    var id = $("#hidden_bank_id").val();
    $.ajax({
      url:"<?php echo URL::to('user/save_opening_balance_values'); ?>",
      type:"post",
      data:{id:id,debit_balance:debit_balance,credit_balance:credit_balance},
      success:function(result)
      {
        $(".opening_balance_modal").modal("hide");
        if(debit_balance != "")
        {
          $(".bank_"+id).find("td").eq(5).html(debit_balance);
        }
        else{
          $(".bank_"+id).find("td").eq(5).html('-'+credit_balance);
        }

        $(".bank_"+id).find("td").eq(6).html(result);
      }
    })
  }
  if($(e.target).hasClass('add_bank'))
  {
    $(".add_bank_modal").modal("show");
    $(".add_bank_modal").find(".modal-title").html('Add a Bank');
    $(".add_bank_btn").val("Add Bank");
    $(".bank_name_add").prop("disabled",false);
    $(".account_name_add").val('');
    $(".account_no_add").val('');
    $(".nominal_description_add").val('');
    $(".bank_code_add").val('');

    $.ajax({
      url:"<?php echo URL::to('user/get_nominal_codes_for_bank'); ?>",
      type:"post",
      success:function(result)
      {
        $(".bank_code_add").html(result);
      }
    })
  }

  if($(e.target).hasClass('financial_setup'))
  {
    $(".nominal_codes_modal").modal("show");
  }
  if($(e.target).hasClass('add_nominal_btn'))
  {
    if($("#add_nominal_form").valid())
    {
      var code = $(".nominal_code_add").val();
      var description = $(".description_add").val();
      var primary = $(".primary_grp_add").val();
      var debit = $(".debit_grp_add").val();
      var credit = $(".credit_grp_add").val();

      if(primary == "Profit & Loss")
      {
        if(debit == "")
        {
          alert("Please select the Debit Group");
          return false;
        }
      }
      else{
        if(debit == "")
        {
          alert("Please select the Debit Group");
          return false;
        }
        else if(credit == ""){
          alert("Please select the Credit Group");
          return false;
        }
      }

      $.ajax({
        url:"<?php echo URL::to('user/add_nominal_code_financial'); ?>",
        type:"post",
        dataType:"json",
        data:{code:code,description:description,primary:primary,debit:debit,credit:credit},
        success:function(result)
        {
          if(result['table_type'] == 0)
          {
            if(primary == "Profit & Loss")
            {
              $("#nominal_tbody").append('<tr class="code_'+code+'"><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+code+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+description+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+primary+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+debit+'</a></td> <td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+debit+'</a></td></tr>');  
              $(".add_nominal_modal").modal("hide");
            }
            else{
              $("#nominal_tbody").append('<tr class="code_'+code+'"><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+code+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+description+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+primary+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+debit+'</a></td> <td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+credit+'</a></td></tr>');  
              $(".add_nominal_modal").modal("hide");
            }
          }
          else{
            if(primary == "Profit & Loss")
            {
              $(".code_"+code).html('<td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+code+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+description+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+primary+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+debit+'</a></td> <td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+debit+'</a></td>');
              $(".add_nominal_modal").modal("hide");
            }
            else{
              $(".code_"+code).html('<td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+code+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+description+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+primary+'</a></td><td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+debit+'</a></td> <td><a href="javascript:" class="edit_nominal_code" data-element="'+code+'">'+credit+'</a></td>');
              $(".add_nominal_modal").modal("hide");
            }
          }
        }
      })
    }
  }
  if($(e.target).hasClass('add_bank_btn'))
  {
    if($("#add_bank_form").valid())
    {
      var bank_name = $(".bank_name_add").val();
      var account_name = $(".account_name_add").val();
      var account_no = $(".account_no_add").val();
      var description = $(".nominal_description_add").val();
      var code = $(".bank_code_add").val();

      $.ajax({
        url:"<?php echo URL::to('user/add_bank_financial'); ?>",
        type:"post",
        dataType:"json",
        data:{bank_name:bank_name,account_name:account_name,account_no:account_no,description:description,code:code},
        success:function(result)
        {
          if(result['bank_counts'] == 0)
          {
            $("#bank_tbody").html('<tr class="bank_'+result['id']+'"><td>'+bank_name+'</td><td>'+account_name+'</td><td>'+account_no+'</td><td>'+description+'</td><td>'+code+'</td><td><a href="javascript:" class="edit_opening_balance" title="Opening Balance" data-element="'+result['id']+'"><img src="<?php echo URL::to('assets/images/opening_balance.png'); ?>" class="edit_opening_balance" data-element="'+result['id']+'" style="width:30px"></a></td><td></td><td></td></tr>');  
              $(".add_bank_modal").modal("hide");
          }
          else{
            $("#bank_tbody").append('<tr class="bank_'+result['id']+'"><td>'+bank_name+'</td><td>'+account_name+'</td><td>'+account_no+'</td><td>'+description+'</td><td>'+code+'</td><td><a href="javascript:" class="edit_opening_balance" title="Opening Balance" data-element="'+result['id']+'"><img src="<?php echo URL::to('assets/images/opening_balance.png'); ?>" class="edit_opening_balance" data-element="'+result['id']+'"  style="width:30px"></a></td></tr>');  
              $(".add_bank_modal").modal("hide");
          }

          $(".des_tr_"+code).find("td").eq(1).html(description);
          
          // if(result['table_type'] == 0)
          // {
          //   $("#nominal_tbody").append('<tr class="bank_'+result['id']+'"><td>'+bank_name+'</td><td>'+account_name+'</td><td>'+account_no+'</td><td>'+code+'</td></tr>');  
          //     $(".add_bank_modal").modal("hide");
          // }
          // else{
          //    $(".bank_"+code).html('<td>'+bank_name+'</td><td>'+account_name+'</td><td>'+account_no+'</td><td>'+code+'</td>');
          //     $(".add_bank_modal").modal("hide");
          // }
        }
      })
    }
  }
  if($(e.target).hasClass('update_bank_btn'))
  {
    if($("#edit_bank_form").valid())
    {
      var bank_name = $(".bank_name_edit").val();
      var account_name = $(".account_name_edit").val();
      var account_no = $(".account_no_edit").val();
      var description = $(".nominal_description_edit").val();
      var bank_id = $("#hidden_bank_id_update").val();

      $.ajax({
        url:"<?php echo URL::to('user/update_bank_financial'); ?>",
        type:"post",
        dataType:"json",
        data:{bank_name:bank_name,account_name:account_name,account_no:account_no,description:description,bank_id:bank_id},
        success:function(result)
        {
          $(".bank_"+bank_id).find("td").eq(0).html(result['bank_name']);
          $(".bank_"+bank_id).find("td").eq(1).html(result['account_name']);
          $(".bank_"+bank_id).find("td").eq(2).html(result['account_no']);
          $(".bank_"+bank_id).find("td").eq(3).html(result['description']);
          $(".edit_bank_modal").modal("hide");
          $(".des_tr_"+result['code']).find("td").eq(1).html(result['description']);
        }
      })
    }
  }
  if($(e.target).hasClass('edit_opening_balance'))
  {
    var id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/financial_opening_balance_show'); ?>",
      type:"post",
      dataType:"json",
      data:{id:id},
      success:function(result)
      {
        $(".opening_balance_modal").modal("show");
        $("#bank_name_des").html(result['bank_name']);
        $("#bank_acc_des").html(result['description']);
        $("#acc_name").html(result['account_name']);
        $("#acc_no").html(result['account_no']);
        $(".debit_balance_add").val(result['debit_balance']);
        $(".credit_balance_add").val(result['credit_balance']);
        
        if(result['debit_balance'] == "" || result['debit_balance'] == "0.00" || result['debit_balance'] == "0") { 
          $(".debit_balance_add").prop("disabled",true); 
          $(".credit_balance_add").prop("disabled",false); 
        }

        if(result['credit_balance'] == "" || result['credit_balance'] == "0.00" || result['credit_balance'] == "0") { 
          $(".debit_balance_add").prop("disabled",false); 
          $(".credit_balance_add").prop("disabled",true); 
        }

        if(result['debit_balance'] == "" && result['credit_balance'] == ""){
          $(".debit_balance_add").prop("disabled",false); 
          $(".credit_balance_add").prop("disabled",false);
        }

        $(".opening_financial_date_val").val(result['opening_balance_date']);
        $("#hidden_bank_id").val(id);
      }
    })
  }
  if($(e.target).hasClass('edit_nominal_code'))
  {
    var code = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('user/edit_nominal_code_finance'); ?>",
        type:"post",
        dataType:"json",
        data:{code:code},
        success:function(result)
        {
          if(result['primary'] == "Profit & Loss")
          {
            $(".debit_grp_add").html('<option value="">Select Value</option><option value="Sales">Sales</option><option value="Other Income">Other Income</option><option value="Cost of Sales">Cost of Sales</option><option value="Administrative Expenses">Administrative Expenses</option><option value="Taxes">Taxes</option>');
            $(".credit_grp_add").html('<option value="">Select Value</option><option value="Sales">Sales</option><option value="Other Income">Other Income</option><option value="Cost of Sales">Cost of Sales</option><option value="Administrative Expenses">Administrative Expenses</option><option value="Taxes">Taxes</option>');

            $(".debit_group_div").show();
            $(".credit_group_div").hide();
          }
          else{
            $(".debit_grp_add").html('<option value="">Select Value</option><option value="Fixed Assets">Fixed Assets</option><option value="Current Assets">Current Assets</option><option value="Current Liabilities">Current Liabilities</option><option value="Long Term Liabilities">Long Term Liabilities</option><option value="Capital Account">Capital Account</option>');
            $(".credit_grp_add").html('<option value="">Select Value</option><option value="Fixed Assets">Fixed Assets</option><option value="Current Assets">Current Assets</option><option value="Current Liabilities">Current Liabilities</option><option value="Long Term Liabilities">Long Term Liabilities</option><option value="Capital Account">Capital Account</option>');

            $(".debit_group_div").show();
            $(".credit_group_div").show();
          }
          $(".add_nominal_modal").find(".modal-title").html("Update Nominal");
          $(".add_nominal_btn").val("Update Nominal");
          $(".nominal_code_add").prop("disabled",true);
          $(".add_nominal_modal").modal("show");
          $(".nominal_code_add").val(result['code']);
          $(".description_add").val(result['description']);
          $(".primary_grp_add").val(result['primary']);
          $(".debit_grp_add").val(result['debit']);
          $(".credit_grp_add").val(result['credit']);
        }
    });
  }
  if($(e.target).hasClass('edit_bank_account'))
  {
    var id = $(e.target).attr("data-element");
    $.ajax({
        url:"<?php echo URL::to('user/edit_bank_account_finance'); ?>",
        type:"post",
        dataType:"json",
        data:{id:id},
        success:function(result)
        {
          $(".edit_bank_modal").modal("show");
          $(".bank_name_edit").val(result['bank_name']);
          $(".account_name_edit").val(result['account_name']);
          $(".account_no_edit").val(result['account_no']);
          $(".nominal_description_edit").val(result['description']);
          $("#hidden_bank_id_update").val(id);
        }
    });
  }
  if($(e.target).hasClass('date_selection'))
  {
    var type = $(e.target).val();
    if(type == "4")
    {
      $(".from_custom_date").prop("disabled",false);
      $(".to_custom_date").prop("disabled",false);

      $(".from_custom_date").val("");
      $(".to_custom_date").val("");
    }
    else{
      $(".from_custom_date").prop("disabled",true);
      $(".to_custom_date").prop("disabled",true);

      var from = $(".date_selection:checked").attr("data-from");
      var to = $(".date_selection:checked").attr("data-to");

      $(".from_custom_date").val(from);
      $(".to_custom_date").val(to);
    }
  }
  if($(e.target).hasClass('date_selection_trial'))
  {
    var type = $(e.target).val();
    if(type == "4")
    {
      $(".from_custom_date_trial").prop("disabled",false);
      $(".to_custom_date_trial").prop("disabled",false);

      $(".from_custom_date_trial").val("");
      $(".to_custom_date_trial").val("");
    }
    else{
      $(".from_custom_date_trial").prop("disabled",true);
      $(".to_custom_date_trial").prop("disabled",true);

      var from = $(".date_selection_trial:checked").attr("data-from");
      var to = $(".date_selection_trial:checked").attr("data-to");

      $(".from_custom_date_trial").val(from);
      $(".to_custom_date_trial").val(to);
    }
  }
});
$('#add_nominal_form').validate({
    rules: {
        nominal_code_add : {required: true,remote:"<?php echo URL::to('user/check_nominal_code'); ?>"},
        description_add : {required: true,},
        primary_grp_add : {required:true,},
    },
    messages: {
        nominal_code_add : {
          required : "Nominal Code is Required",
          remote : "Nominal Code is Already created",
        },
        description_add : { 
          required : "Description is Required",
        },
        primary_grp_add : { 
          required : "Primary Group is Required",
        },
    },
});

$('#add_bank_form').validate({
    rules: {
        bank_name_add : {required: true,},
        account_name_add : {required: true,},
        account_no_add : {required:true,},
        nominal_description_add : {required:true,},
        bank_code_add : {required:true,},
    },
    messages: {
        bank_name_add : {
          required : "Bank Name is Required",
        },
        account_name_add : { 
          required : "Account Name is Required",
        },
        account_no_add : { 
          required : "Account No is Required",
        },
        nominal_description_add : {
          required : "Description is Required",
        },
        bank_code_add : {
          required : "Nominal Code is Required",
        }
    },
});
$('#edit_bank_form').validate({
    rules: {
        bank_name_edit : {required: true,},
        account_name_edit : {required: true,},
        account_no_edit : {required:true,},
        nominal_description_edit : {required:true,},
    },
    messages: {
        bank_name_edit : {
          required : "Bank Name is Required",
        },
        account_name_edit : { 
          required : "Account Name is Required",
        },
        account_no_edit : { 
          required : "Account No is Required",
        },
        nominal_description_edit : {
          required : "Description is Required",
        }
    },
});

$(document).ready(function () {    
    $('.debit_fin_sort_val').keypress(function (e) {    
        var charCode = (e.which) ? e.which : event.keyCode    
        if (String.fromCharCode(charCode).match(/[^0-9.,]/g))    
            return false;                        
    });   
     $('.credit_fin_sort_val').keypress(function (e) {    
        var charCode = (e.which) ? e.which : event.keyCode    
        if (String.fromCharCode(charCode).match(/[^0-9.,]/g))    
            return false;                        
    });    
}); 

$(".debit_fin_sort_val").blur(function() {
  var debit = $(this).val();
  var client_id = $(this).attr("data-element");
  var credit = $(".credit_fin_sort_val_"+client_id).val();
  $.ajax({
    url:"<?php echo URL::to('user/save_debit_credit_finance_client'); ?>",
    type:"post",
    dataType:"json",
    data:{debit:debit,credit:credit,client_id:client_id},
    success:function(result)
    {
      if(result['commit_status'] == "1") { $(".commit_btn_"+client_id).show(); } else { $(".commit_btn_"+client_id).hide(); }
      if(result['owed_text'] != "")
      {
        $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>'+result['owed_text']);
      }
      else{
         $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>');
      }
      if(result['bal_status'] == "1") { $(".balance_fin_sort_val_"+client_id).css({"color": "#f00", "font-weight": "600"}); }
      else{ $(".balance_fin_sort_val_"+client_id).css({"color": "#555", "font-weight": "500"}); }
    }
  })
});
$(".credit_fin_sort_val").blur(function() {
  var credit = $(this).val();
  var client_id = $(this).attr("data-element");
  var debit = $(".debit_fin_sort_val_"+client_id).val();
  $.ajax({
    url:"<?php echo URL::to('user/save_debit_credit_finance_client'); ?>",
    type:"post",
    dataType:"json",
    data:{debit:debit,credit:credit,client_id:client_id},
    success:function(result)
    {
      if(result['commit_status'] == "1") { $(".commit_btn_"+client_id).show(); } else { $(".commit_btn_"+client_id).hide(); }
      if(result['owed_text'] != "")
      {
        $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>'+result['owed_text']);
      }
      else{
         $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>');
      }
      if(result['bal_status'] == "1") { $(".balance_fin_sort_val_"+client_id).css({"color": "#f00", "font-weight": "600"}); }
      else{ $(".balance_fin_sort_val_"+client_id).css({"color": "#555", "font-weight": "500"}); }
    }
  })
});

$(window).keyup(function(e) {
    var valueTimmer;                //timer identifier
    var valueInterval = 500;  //time in ms, 5 second for example
    var valueInterval_client = 1000;  //time in ms, 5 second for example 
    if($(e.target).hasClass('bank_name_add'))
    {        
        var input_val = $(e.target).val();
        var account_no = $(".account_no_add").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping, valueInterval,input_val,account_no);   
    }    
    if($(e.target).hasClass('account_no_add'))
    {        
        var input_val = $(e.target).val();
        var bank_name = $(".bank_name_add").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping, valueInterval,bank_name,input_val);   
    }    
    if($(e.target).hasClass('bank_name_edit'))
    {        
        var input_val = $(e.target).val();
        var account_no = $(".account_no_edit").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_edit, valueInterval,input_val,account_no);   
    }    
    if($(e.target).hasClass('account_no_edit'))
    {        
        var input_val = $(e.target).val();
        var bank_name = $(".bank_name_edit").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_edit, valueInterval,bank_name,input_val);   
    }    
    if($(e.target).hasClass('debit_fin_sort_val'))
    {
      var input_val = $(e.target).val();
      var client_id = $(e.target).attr("data-element");
      var credit = $(".credit_fin_sort_val_"+client_id).val();

      clearTimeout(valueTimmer);
      valueTimmer = setTimeout(doneTyping_debit, valueInterval_client,input_val,credit,client_id);   
    }
    if($(e.target).hasClass('credit_fin_sort_val'))
    {
      var input_val = $(e.target).val();
      var client_id = $(e.target).attr("data-element");
      var debit = $(".debit_fin_sort_val_"+client_id).val();
      clearTimeout(valueTimmer);
      valueTimmer = setTimeout(doneTyping_debit, valueInterval_client,debit,input_val,client_id);   
    }
});
function doneTyping (bank_name,account_no) {
  $(".nominal_description_add").val(bank_name+' '+account_no);
}
function doneTyping_edit (bank_name,account_no) {
  $(".nominal_description_edit").val(bank_name+' '+account_no);
}
function doneTyping_debit (debit,credit,client_id) {
  $.ajax({
    url:"<?php echo URL::to('user/save_debit_credit_finance_client'); ?>",
    type:"post",
    dataType:"json",
    data:{debit:debit,credit:credit,client_id:client_id},
    success:function(result)
    {
      if(result['commit_status'] == "1") { $(".commit_btn_"+client_id).show(); } else { $(".commit_btn_"+client_id).hide(); }
      if(result['owed_text'] != "")
      {
        $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>'+result['owed_text']);
      }
      else{
         $(".balance_fin_sort_val_"+client_id).parents("td").html('<input type="text" class="form-control balance_fin_sort_val balance_fin_sort_val_'+client_id+'" id="balance_fin_sort_val" value="'+result['balance']+'" disabled>');
      }
      if(result['bal_status'] == "1") { $(".balance_fin_sort_val_"+client_id).css({"color": "#f00", "font-weight": "600"}); }
      else{ $(".balance_fin_sort_val_"+client_id).css({"color": "#555", "font-weight": "500"}); }
    }
  })
}


/*$(document).ready(function() {
    $(".reconcile_modal").modal('show');
});*/
</script>




@stop