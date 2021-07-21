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
<div class="modal fade client_finance_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:80%">
    <div class="modal-content">
      <div class="modal-header" style="padding-bottom: 0px;border-bottom:0px">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">
          Client Account Opening Balance Manager
          <div style="float:right;margin-right: 24px;font-size: 16px;margin-top: 5px;">
            <input type="button" name="export_csv_client_opening" class="common_black_button export_csv_client_opening" value="Export Csv">
            <label>Client Account Opening Balance Date: </label> <spam class="opening_balance_date_spam"></spam>
          </div>
        </h4>

         <table class="table own_table_white" style="margin-bottom: 0px;margin-top:40px">
            <thead>
              <tr>
                <th style="text-align: left;width:8%">Client Code <i class="fa fa-sort client_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                <th style="text-align: left;width:12%">Surname <i class="fa fa-sort surname_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                <th style="text-align: left;width:12%">Firstname <i class="fa fa-sort firstname_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                <th style="text-align: left;width:32%">Company Name <i class="fa fa-sort company_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                <th style="text-align: left;width:9%">Debit <i class="fa fa-sort debit_fin_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                <th style="text-align: left;width:9%">Credit <i class="fa fa-sort credit_fin_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                <th style="text-align: left;width:9%">Balance <i class="fa fa-sort balance_fin_sort" aria-hidden="true" style="float: right;margin-top: 4px;"></i></th>
                <th style="text-align: left;width:9%">Commit</th>
              </tr>
            </thead>
          </table>
      </div>
      <div class="modal-body" style="min-height:700px;max-height: 700px;overflow-y:scroll;padding-top: 0px;">

        <table class="table table-fixed" id="client_financial">
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
                      <td style="width:9%"><input type="button" class="common_black_button commit_btn commit_btn_'.$client->client_id.'" value="Commit" style="'.$commit_style.'"></td>
                    </tr>';
                }
              }
            ?>
            </tbody>
          </table>
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
                  if($codes->type == 0)
                  {
                    echo '<tr>
                      <td class="code_sort_val">'.$codes->code.' <i class="fa fa-lock" title="Core Nominal"></i></td>
                      <td class="des_sort_val">'.$codes->description.'</td>
                      <td class="primary_sort_val">'.$codes->primary_group.'</td>
                      <td class="debit_sort_val">'.$codes->debit_group.'</td>
                      <td class="credit_sort_val">'.$codes->credit_group.'</td>
                    </tr>';
                  }
                  else{
                    echo '<tr class="code_'.$codes->code.'">
                      <td><a href="javascript:" class="edit_nominal_code code_sort_val" data-element="'.$codes->code.'">'.$codes->code.'</a></td>
                      <td><a href="javascript:" class="edit_nominal_code des_sort_val" data-element="'.$codes->code.'">'.$codes->description.'</a></td>
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
  <div class="modal-dialog" role="document" style="width:45%">
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
            <th style="text-align: left">Action</th>
          </thead>
          <tbody id="bank_tbody">
          <?php 
            $banks = DB::table('financial_banks')->get();
            if(count($banks))
            {
              foreach($banks as $bank)
              {
                echo '<tr class="bank_'.$bank->id.'">
                    <td>'.$bank->bank_name.'</td>
                    <td>'.$bank->account_name.'</td>
                    <td>'.$bank->account_number.'</td>
                    <td>'.$bank->description.'</td>
                    <td>'.$bank->nominal_code.'</td>
                    <td><a href="javascript:" class="edit_opening_balance" title="Opening Balance" data-element="'.$bank->id.'"><img src="'.URL::to('assets/images/opening_balance.png').'" class="edit_opening_balance" data-element="'.$bank->id.'" style="width:30px"></a></td>
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
<div class="content_section" style="margin-bottom:200px">
    <div class="page_title">
      <h4 class="col-lg-12 padding_00 new_main_title">
                Financials               
            </h4>
        
        <div class="col-md-6 padding_00">
          <div class="col-md-12" style="padding: 0px">
            <div class="col-md-8 padding_00">
              <input type="radio" name="date_selection" class="date_selection" id="curr_year" value="1"><label for="curr_year">Current Year</label>
              <input type="radio" name="date_selection" class="date_selection" id="prev_year" value="2"><label for="prev_year">Previous Year</label>
              <input type="radio" name="date_selection" class="date_selection" id="curr_month" value="3" checked><label for="curr_month">Current Month</label>
              <input type="radio" name="date_selection" class="date_selection" id="custom" value="4"><label for="custom">Custom</label>
            </div>
            <div class="col-md-4">
              <a href="javascript:" class="common_black_button load_journals" style="position: absolute;top: 10px;z-index: 9999;height: 74px;padding-top: 16px;width: 122px;">Load <br/> Journals</a>
            </div>
          </div>
          <div class="col-md-12" style="padding: 0px; margin-top: 20px;" >
            <label class="col-md-1 padding_00" style="margin-top: 6px;text-align: left;">From:</label>
            <div class="col-md-3">
              <input type="text" name="from_custom_date" class="form-control from_custom_date" value="" disabled>
            </div>

            <label class="col-md-1" style="margin-top: 6px;text-align: right;">To:</label>
            <div class="col-md-3">
              <input type="text" name="to_custom_date" class="form-control to_custom_date" value="" disabled>
            </div>
          </div>
          <div class="col-md-12" style="margin-top:5px">
            
          </div>
          <div class="col-md-12 load_journal_div" style="margin-top:30px;background: #fff; height:800px;max-height: 800px;overflow-y: scroll">

          </div>
        </div>
        <div class="col-md-6" style="text-align: right">
            
              <input type="button" name="financial_setup" class="common_black_button financial_setup" value="Financial Setup">
              <input type="button" class="common_black_button client_finance_account_btn" value="Client Finance Account" style="float:right;font-size: 14px;margin-bottom: 10px;">
        </div>
    </div>
    <!-- End  -->
  <div class="main-backdrop"><!-- --></div>
  <div class="modal_load"></div>
  <input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
  <input type="hidden" name="show_alert" id="show_alert" value="">
  <input type="hidden" name="pagination" id="pagination" value="1">

  <input type="hidden" name="code_sortoptions" id="code_sortoptions" value="asc">
  <input type="hidden" name="des_sortoptions" id="des_sortoptions" value="asc">
  <input type="hidden" name="primary_sortoptions" id="primary_sortoptions" value="asc">
  <input type="hidden" name="debit_sortoptions" id="debit_sortoptions" value="asc">
  <input type="hidden" name="credit_sortoptions" id="credit_sortoptions" value="asc">


  <input type="hidden" name="client_sortoptions" id="client_sortoptions" value="asc">
  <input type="hidden" name="surname_sortoptions" id="surname_sortoptions" value="asc">
  <input type="hidden" name="firstname_sortoptions" id="firstname_sortoptions" value="asc">
  <input type="hidden" name="company_sortoptions" id="company_sortoptions" value="asc">
  <input type="hidden" name="debit_fin_sortoptions" id="debit_fin_sortoptions" value="asc">
  <input type="hidden" name="credit_fin_sortoptions" id="credit_fin_sortoptions" value="asc">
  <input type="hidden" name="balance_fin_sortoptions" id="balance_fin_sortoptions" value="asc">
</div>

<script>

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
     format: 'DD-MMM-YYYY',
  });

  $(".to_custom_date").datetimepicker({
     format: 'L',
     format: 'DD-MMM-YYYY',
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
       return parseInt(value);
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
$(window).click(function(e) { 
  var ascending = false;
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

  if($(e.target).hasClass('debit_fin_sort'))
  {
    var sort = $("#debit_fin_sortoptions").val();
    if(sort == 'asc')
    {
      $("#debit_fin_sortoptions").val('desc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.debit_fin_sort_val').val()) <
        convertToNumber($(b).find('.debit_fin_sort_val').val()))) ? 1 : -1;
      });
    }
    else{
      $("#debit_fin_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.debit_fin_sort_val').val()) <
        convertToNumber($(b).find('.debit_fin_sort_val').val()))) ? -1 : 1;
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
        return (ascending ==
             (convertToNumber($(a).find('.credit_fin_sort_val').val()) <
        convertToNumber($(b).find('.credit_fin_sort_val').val()))) ? 1 : -1;
      });
    }
    else{
      $("#credit_fin_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.credit_fin_sort_val').val()) <
        convertToNumber($(b).find('.credit_fin_sort_val').val()))) ? -1 : 1;
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
        return (ascending ==
             (convertToNumber($(a).find('.balance_fin_sort_val').val()) <
        convertToNumber($(b).find('.balance_fin_sort_val').val()))) ? 1 : -1;
      });
    }
    else{
      $("#balance_fin_sortoptions").val('asc');
      var sorted = $('#client_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.balance_fin_sort_val').val()) <
        convertToNumber($(b).find('.balance_fin_sort_val').val()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#client_tbody').html(sorted);
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
      }
    })
  }
  if($(e.target).hasClass('add_bank'))
  {
    $(".add_bank_modal").modal("show");

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
            $("#bank_tbody").html('<tr class="bank_'+result['id']+'"><td>'+bank_name+'</td><td>'+account_name+'</td><td>'+account_no+'</td><td>'+description+'</td><td>'+code+'</td><td><a href="javascript:" class="edit_opening_balance" title="Opening Balance" data-element="'+result['id']+'"><img src="<?php echo URL::to('assets/images/opening_balance.png'); ?>" class="edit_opening_balance" data-element="'+result['id']+'" style="width:30px"></a></td></tr>');  
              $(".add_bank_modal").modal("hide");
          }
          else{
            $("#bank_tbody").append('<tr class="bank_'+result['id']+'"><td>'+bank_name+'</td><td>'+account_name+'</td><td>'+account_no+'</td><td>'+description+'</td><td>'+code+'</td><td><a href="javascript:" class="edit_opening_balance" title="Opening Balance" data-element="'+result['id']+'"><img src="<?php echo URL::to('assets/images/opening_balance.png'); ?>" class="edit_opening_balance" data-element="'+result['id']+'"  style="width:30px"></a></td></tr>');  
              $(".add_bank_modal").modal("hide");
          }
          
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
  // if($(e.target).hasClass('edit_bank_account'))
  // {
  //   var id = $(e.target).attr("data-element");
  //   $.ajax({
  //       url:"<?php echo URL::to('user/edit_bank_account_finance'); ?>",
  //       type:"post",
  //       dataType:"json",
  //       data:{id:id},
  //       success:function(result)
  //       {
  //         $(".add_bank_btn").val("Update Bank");
  //         $(".add_bank_modal").modal("show");
  //         $(".bank_name_add").val(result['bank_name']);
  //         $(".account_name_add").val(result['account_name']);
  //         $(".account_no_add").val(result['account_no']);
  //         $(".bank_code_add").val(result['code']);
  //       }
  //   });
  // }
  if($(e.target).hasClass('date_selection'))
  {
    var type = $(e.target).val();
    if(type == "4")
    {
      $(".from_custom_date").prop("disabled",false);
      $(".to_custom_date").prop("disabled",false);
    }
    else{
      $(".from_custom_date").prop("disabled",true);
      $(".to_custom_date").prop("disabled",true);

      $(".from_custom_date").val("");
      $(".to_custom_date").val("");
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
</script>




@stop