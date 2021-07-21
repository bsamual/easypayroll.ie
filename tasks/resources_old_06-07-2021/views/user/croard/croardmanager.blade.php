@extends('userheader')
@section('content')
<script src='<?php echo URL::to('assets/js/table-fixed-header_croard.js'); ?>'></script>
<script src="<?php echo URL::to('assets/ckeditor/src/js/main1.js'); ?>"></script>
<style>
.table>thead>tr>th { background: #fff !important; }
.fa-sort{ cursor:pointer; }
.company_td { font-weight:800; }
.form-control[disabled] { background-color:#ececec !important; cursor: pointer; }
.fa-check { color:green; }
.fa-times { color:#f00; }
.fa { font-size:20px; }
#table_administration_wrapper{ width:98%; }
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

.form_radio .text{}
.form_radio span{right: 0px; left: unset;}
</style>
<script src="<?php echo URL::to('ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('ckeditor/samples/js/sample.js'); ?>"></script>
<link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/css/samples.css'); ?>">
<link rel="stylesheet" href="<?php echo URL::to('ckeditor/samples/toolbarconfigurator/lib/codemirror/neo.css'); ?> ">
<?php 
  $admin_details = Db::table('admin')->first();
  $admin_cc = $admin_details->task_cc_email;
?> 
<div class="modal fade search_company_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog" role="document" style="width:50%">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">Search Company</h4>
          </div>
          <div class="modal-body">  
            <div class="row">
              <div class="col-md-3">
                <h5>Company number:</h5>
                <input type="text" name="company_number" class="form-control company_number" value="">
              </div>
              <div class="col-md-9">
                <h5>Company / Business indicator:</h5>
                <div class="col-md-4">
                  <input type="radio" name="indicator" class="indicator" id="indicator_1" value="C"><label for="indicator_1" style="margin-top:3px">Limited Company</label>
                  <!-- <div class="form-group">
                     <label class="form_radio">Limited Company
                      <input type="radio" value="" style="width:1px; height:1px" name="test"  required>
                      <span class="checkmark_radio"></span>
                    </label>
                  </div> -->
                </div>
                <div class="col-md-8">
                  <input type="radio" name="indicator" class="indicator" id="indicator_2" value="B"><label for="indicator_2" style="width: 56%;margin-top:3px">Registered Business</label>
                  <input type="button" class="common_black_button search_company_btn" id="search_company_btn" value="Call From CRO">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-10 col-md-offset-1 table_api" style="margin-top:10px;">
                  <table class="table">
                    <tbody>
                      <tr>
                        <td>Company Number:</td>
                        <td><input type="text" name="company_number" class="form-control company_number" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company / Business indicator:</td>
                        <td><input type="text" name="indicator_text" class="form-control indicator_text" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Name:</td>
                        <td><input type="text" name="company_name" class="form-control company_name" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Address:</td>
                        <td><textarea name="company_address" class="form-control company_address" disabled style="height:110px"></textarea></td>
                      </tr>
                      <tr>
                        <td>Company Registration Date:</td>
                        <td><input type="text" name="company_reg_date" class="form-control company_reg_date" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Status:</td>
                        <td><input type="text" name="company_status_desc" class="form-control company_status_desc" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Status Date:</td>
                        <td><input type="text" name="company_status_date" class="form-control company_status_date" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Next ARD:</td>
                        <td><input type="text" name="next_ar_date" class="form-control next_ar_date" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Last ARD:</td>
                        <td><input type="text" name="last_ar_date" class="form-control last_ar_date" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Accounts Upto:</td>
                        <td><input type="text" name="last_acc_date" class="form-control last_acc_date" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Type:</td>
                        <td><input type="text" name="comp_type_desc" class="form-control comp_type_desc" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Type Code:</td>
                        <td><input type="text" name="company_type_code" class="form-control company_type_code" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Company Status Code:</td>
                        <td><input type="text" name="company_status_code" class="form-control company_status_code" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Place of Business:</td>
                        <td><input type="text" name="place_of_business" class="form-control place_of_business" value="" disabled></td>
                      </tr>
                      <tr>
                        <td>Eircode:</td>
                        <td><input type="text" name="eircode" class="form-control eircode" value="" disabled></td>
                      </tr>
                    </tbody>
                  </table>
              </div>
            </div>
          </div>
        </div>
  </div>
</div>
<div class="content_section" style="margin-bottom:200px">
  	<div class="page_title" style="z-index:999">
	    <div class="col-lg-12 padding_00" style="text-align:center;font-size:20px">
	      CRO - ARD Manager
	    </div>
  	</div>
    <div class="table-responsive" style="width: 100%; float: left;margin-top:10px">
      <div class="col-md-12" style="float:right">
          <div class="col-md-5">&nbsp;
          </div>
          <div class="col-md-2" style="text-align: right">
            <label style="margin-top: 5px;">CRO Api Username:</label>
          </div>
          <div class="col-md-2" style="padding:0px">
            <input type="text" name="cro_username" class="form-control cro_username" id="cro_username" value="<?php echo $cro->username; ?>" disabled>
          </div>
          <div class="col-md-1" style="text-align: right">
            <label style="margin-top: 5px;">CRO Api Key:</label>
          </div>
          <div class="col-md-2" style="padding:0px">
            <input type="text" name="cro_api" class="form-control cro_api" id="cro_api" value="<?php echo $cro->api_key; ?>" disabled>
          </div>
      </div>
      <div class="col-md-12" style="margin-top:10px;">
        <div class="col-md-6">&nbsp;
        </div>
        <div class="col-md-2">
          <input type="checkbox" name="show_incomplete" id="show_incomplete" value="1"><label for="show_incomplete" style="margin-top: 9px;">Hide Deactivated Accounts</label>
        </div>
        <div class="col-md-4">
          <input type="button" name="check_company" class="common_black_button check_company" value="Check Company" data-toggle="modal" data-target=".search_company_modal">
          <input type="button" name="global_core_call" class="common_black_button global_core_call" value="Global Core Call"> 
          <input type="button" name="show_ltd" id="show_ltd" class="common_black_button show_ltd" value="Show Active Ltd Clients Only">
        </div>
      </div>
      	<table class="table table-fixed-header" style="width:100%;margin-top:92px">
	        <thead class="header">
	            <th style="width:3%;text-align: left;">S.No <i class="fa fa-sort sno_sort" aria-hidden="true" style="float: right;"></i></th>
	            <th style="width:6%;text-align: left;">Client Code <i class="fa fa-sort clientid_sort" aria-hidden="true" style="float: right;"></i></th>
	            <th style="width:25%;text-align: left;">Company Name <i class="fa fa-sort company_sort" aria-hidden="true" style="float: right;"></i></th>
	            <th style="width:7%;text-align: left;">CRO Number <i class="fa fa-sort cro_sort" aria-hidden="true" style="float: right;"></i></th>
	            <th style="width:10%;text-align: left;">ARD <i class="fa fa-sort ard_sort" aria-hidden="true" style="float: right;"></i></th>
	            <th style="width:10%;text-align: left;">Type <i class="fa fa-sort type_sort" aria-hidden="true" style="float: right;"></i></th>
	            <th style="width:10%;text-align: left;">CRO ARD <i class="fa fa-sort cro_ard_sort" aria-hidden="true" style="float: right;"></i></th>
              <th style="width:25%;text-align: left;">NOTES </th>
	            <th style="width:5%;text-align: left;">Action</th>
	        </thead>                            
        	<tbody id="clients_tbody">
	        <?php
	        $i=1;
	        if(count($clientlist)){              
		        foreach($clientlist as $key => $client){
	              $disabled='';
                $style="color:#000";
	              if($client->active != "")
	              {
	                if($client->active == 2)
	                {
	                  $disabled='disabled_tr';
                    $style="color:#f00";
	                }
	                $check_color = DB::table('cm_class')->where('id',$client->active)->first();
	              }

	              $cmp = '<spam class="company_td" style="font-style:italic;"></spam>';
	              $cr_ard_date = '';
	              $ard_color = '';
                $timestampcroard = '';
	              $cro_ard_details = DB::table('croard')->where('client_id',$client->client_id)->first();
                $notes = '';
	              if(count($cro_ard_details))
	              {
                  $notes = $cro_ard_details->notes;
	              	if(strtolower($client->company) == strtolower($cro_ard_details->company_name))
	              	{
	              		$cmp = '<spam class="company_td" style="color:green;font-style:italic">'.$cro_ard_details->company_name.'</spam>';
	              	}
	              	else{
	              		$cmp = '<spam class="company_td" style="color:blue;font-style:italic;font-weight:800">'.$cro_ard_details->company_name.'</spam>';
	              	}
	              	if($cro_ard_details->cro_ard != "")
	              	{
	              		$api_date_month = date('d/m',strtotime($cro_ard_details->cro_ard));
		              	$ard = explode("/",$client->ard);
		              	if(count($ard) > 1)
		              	{
		              		$ard_date_month = $ard[0].'/'.$ard[1];
		              	}
        						else{
        							$ard_date_month = '';
        						}
		              	if($ard_date_month == $api_date_month)
		              	{
		              		$cr_ard_date = $cro_ard_details->cro_ard;

                      if($cro_ard_details->cro_ard == "")
                      {
                        $timestampcroard = '';
                      }
                      else{
                        $expandcroard = explode('/',$cro_ard_details->cro_ard);
                        if(count($expandcroard) > 1)
                        {
                          $correctcroard = $expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0];
                            $timestampcroard = strtotime($expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0]);
                        }
                        else{
                          $timestampcroard = '';
                        }
                      }
		              		$ard_color = 'color:green';
		              	}
		              	else{
		              		$cr_ard_date = $cro_ard_details->cro_ard;

                      if($cro_ard_details->cro_ard == "")
                      {
                        $timestampcroard = '';
                      }
                      else{
                        $expandcroard = explode('/',$cro_ard_details->cro_ard);
                        if(count($expandcroard) > 1)
                        {
                          $correctcroard = $expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0];
                          $timestampcroard = strtotime($expandcroard[2].'-'.$expandcroard[1].'-'.$expandcroard[0]);
                        }
                        else{
                          $timestampcroard = '';
                        }
                      }
		              		$ard_color = 'color:red';
		              	}
	              	}
	              }

                if($client->ard == "")
                {
                  $timestampard = '';
                }
                else{
                  $expand = explode('/',$client->ard);
                  if(count($expand) > 1)
                  {
                    $correctard = $expand[2].'-'.$expand[1].'-'.$expand[0];
                      $timestampard = strtotime($expand[2].'-'.$expand[1].'-'.$expand[0]);
                  }
                  else{
                    $timestampard = '';
                  }
                  
                }
		          ?>
		            <tr class="edit_task <?php echo $disabled; ?>" style="<?php echo $style; ?>"  id="clientidtr_<?php echo $client->client_id; ?>">
		                <td style="<?php echo $style; ?>" class="sno_sort_val"><?php echo $i; ?></td>
		                <td style="<?php echo $style; ?>" class="clientid_sort_val" align="left"><?php echo $client->client_id; ?></td>
		                <td style="<?php echo $style; ?>" align="left"><spam class="company_sort_val"><?php echo ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company; ?></spam> <br/> <?php echo $cmp; ?></td>
		                <td style="<?php echo $style; ?>" class="cro_sort_val" align="left">
                          
                          <?php echo ($client->cro == "")?"-":'<a href="javascript:" class="check_cro" data-element="'.$client->cro.'">'.$client->cro.'</a>'; ?>
                    </td>
		                <td style="<?php echo $style; ?>" align="left"><spam class="ard_sort_val" style="display: none"><?php echo $timestampard; ?></spam><?php echo ($client->ard == "")?"-":$client->ard; ?></td>
		                <td style="<?php echo $style; ?>" class="type_sort_val" align="left"><?php echo ($client->tye == "")?"-":$client->tye; ?></td>
		                <td class="cro_ard_td" style="<?php echo $ard_color; ?>" align="left"><spam class="cro_ard_sort_val" style="display: none"><?php echo $timestampcroard; ?></spam><?php echo $cr_ard_date; ?></td>
                    <td align="left">
                      <textarea name="cro_notes" class="form-control cro_notes" data-element="<?php echo $client->client_id; ?>" style="height:50px"><?php echo $notes; ?></textarea>
                    </td>
		                <td align="left"><a href="javascript:" class="fa fa-refresh refresh_croard" data-element="<?php echo $client->client_id; ?>" data-cro="<?php echo trim($client->cro); ?>" data-type="<?php echo trim($client->tye); ?>" style="<?php echo $style; ?>"></a></td>
		            </tr>
	              <?php
	              $i++;
	            }              
            }
            if($i == 1)
            {
              echo'<tr><td colspan="11" align="center">Empty</td></tr>';
            }
          	?> 
        	</tbody>
        </table>
    </div>
</div>
<div class="modal_load"></div>
<div class="modal_load_content" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Clients are loaded.</p>
  <p style="font-size:18px;font-weight: 600;">Loading: <span id="count_first"></span> of <span id="count_last"></span></p>
</div>

<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<input type="hidden" name="clientid_sortoptions" id="clientid_sortoptions" value="asc">
<input type="hidden" name="company_sortoptions" id="company_sortoptions" value="asc">
<input type="hidden" name="cro_sortoptions" id="cro_sortoptions" value="asc">
<input type="hidden" name="ard_sortoptions" id="ard_sortoptions" value="asc">
<input type="hidden" name="type_sortoptions" id="type_sortoptions" value="asc">
<input type="hidden" name="cro_ard_sortoptions" id="cro_ard_sortoptions" value="asc">

<script>
$(document).ready(function() {
  $('.table-fixed-header').fixedHeader();
});
function refresh_all_function(ival)
{
	var ival = ival + 1;
	var countval = $(".refresh_croard").length;
	var clientid = $(".refresh_croard:eq("+ival+")").attr("data-element");
	var cro = $(".refresh_croard:eq("+ival+")").attr("data-cro");
	var type = $(".refresh_croard:eq("+ival+")").attr("data-type");

	$("#count_first").html(ival);

	if(cro == "")
	{
		if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
		else { refresh_all_function(ival); }
	}
	else
  	{
  		if(type == "Ltd" || type == "ltd" || type == "Limited" || type == "Limted" || type == "limited")
  		{
  			$.ajax({
  				url:"<?php echo URL::to('user/refresh_cro_ard'); ?>",
  				dataType:"json",
  				type:"get",
  				data:{clientid:clientid,cro:cro},
  				success:function(result)
  				{
  					if(result['companystatus'] == "0")
  					{
  						$("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
  						$("#clientidtr_"+clientid).find(".company_td").css({'color' : 'green', 'font-weight' : '500'});
  					}
  					else{
  						$("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
  						$("#clientidtr_"+clientid).find(".company_td").css({'color' : 'blue', 'font-weight' : '800'});
  					}

  					if(result['ardstatus'] == "0")
  					{
  						$("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
  						$("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'green'});
  					}
  					else{
  						$("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
  						$("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'red'});
  					}

  					if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
  					else { refresh_all_function(ival); }
  				}
  			});
  		}
  		else{
  			if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
  			else { refresh_all_function(ival); }
  		}
  	}
}
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
$(".cro_notes").blur(function() {
  var input_val = $(this).val();
  var clientid = $(this).attr('data-element');
  
    $.ajax({
        url:"<?php echo URL::to('user/update_cro_notes'); ?>",
        type:"post",
        data:{input_val:input_val,clientid:clientid},
        success: function(result) {

        }
    });
});
var typingTimer;                //timer identifier
var doneTypingInterval = 1000;  //time in ms, 5 second for example
var $input1 = $('.cro_notes');
$input1.on('keyup', function () {
  var input_val = $(this).val();
  var clientid = $(this).attr('data-element');
  var that = $(this);
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping_cro, doneTypingInterval,input_val,clientid,that);
});
//on keydown, clear the countdown 
$input1.on('keydown', function () {
  clearTimeout(typingTimer);
});

function doneTyping_cro (input,clientid,that) {
  $.ajax({
      url:"<?php echo URL::to('user/update_cro_notes'); ?>",
      type:"post",
      data:{input_val:input,clientid:clientid},
      success: function(result) {

      }
  });
}
$(window).click(function(e) {
  var ascending = false;
  if($(e.target).hasClass('sno_sort'))
  {
    var sort = $("#sno_sortoptions").val();
    if(sort == 'asc')
    {
      $("#sno_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_sort_val').html()) <
        convertToNumeric($(b).find('.sno_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#sno_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumeric($(a).find('.sno_sort_val').html()) <
        convertToNumeric($(b).find('.sno_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('clientid_sort'))
  {
    var sort = $("#clientid_sortoptions").val();
    if(sort == 'asc')
    {
      $("#clientid_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.clientid_sort_val').html()) <
        convertToNumber($(b).find('.clientid_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#clientid_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.clientid_sort_val').html()) <
        convertToNumber($(b).find('.clientid_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('company_sort'))
  {
    var sort = $("#company_sortoptions").val();
    if(sort == 'asc')
    {
      $("#company_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.company_sort_val').html()) <
        convertToNumber($(b).find('.company_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#company_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.company_sort_val').html()) <
        convertToNumber($(b).find('.company_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('cro_sort'))
  {
    var sort = $("#cro_sortoptions").val();
    if(sort == 'asc')
    {
      $("#cro_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.cro_sort_val').html()) <
        convertToNumber($(b).find('.cro_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#cro_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.cro_sort_val').html()) <
        convertToNumber($(b).find('.cro_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('ard_sort'))
  {
    var sort = $("#ard_sortoptions").val();
    if(sort == 'asc')
    {
      $("#ard_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.ard_sort_val').html()) <
        convertToNumber($(b).find('.ard_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#ard_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.ard_sort_val').html()) <
        convertToNumber($(b).find('.ard_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('type_sort'))
  {
    var sort = $("#type_sortoptions").val();
    if(sort == 'asc')
    {
      $("#type_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.type_sort_val').html()) <
        convertToNumber($(b).find('.type_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#type_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.type_sort_val').html()) <
        convertToNumber($(b).find('.type_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  if($(e.target).hasClass('cro_ard_sort'))
  {
    var sort = $("#cro_ard_sortoptions").val();
    if(sort == 'asc')
    {
      $("#cro_ard_sortoptions").val('desc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.cro_ard_sort_val').html()) <
        convertToNumber($(b).find('.cro_ard_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#cro_ard_sortoptions").val('asc');
      var sorted = $('#clients_tbody').find('tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.cro_ard_sort_val').html()) <
        convertToNumber($(b).find('.cro_ard_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#clients_tbody').html(sorted);
  }
  	if($(e.target).hasClass('show_ltd'))
  	{
  		if($(e.target).hasClass('show_all'))
  		{
  			$(".type_sort_val").parents("tr").show();
  			$(e.target).removeClass("show_all");
  			$(e.target).val("Show Active Ltd Clients Only");
  		}
  		else{
  			$(".type_sort_val").parents("tr").hide();
  			$(".type_sort_val:contains(Ltd)").parents("tr").show();
  			$(".type_sort_val").parents(".disabled_tr").hide();
  			$(e.target).addClass("show_all");
  			$(e.target).val("Show all Clients");
  		}
  	}
	if($(e.target).hasClass('global_core_call'))
	{
    $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Do you want to update the Client Manager with the ARD Date from the Companies Office</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_proceed">Yes</a><a href="javascript:" class="common_black_button no_proceed">No</a></p>',fixed:true,width:"800px"});
	}
  if($(e.target).hasClass('yes_proceed'))
  {
    $("body").addClass("loading_content");
    var ival = 1;
    var countval = $(".refresh_croard").length;
    var clientid = $(".refresh_croard:eq("+ival+")").attr("data-element");
    var cro = $(".refresh_croard:eq("+ival+")").attr("data-cro");
    var type = $(".refresh_croard:eq("+ival+")").attr("data-type");

    $("#count_last").html(countval);
    $("#count_first").html(ival);

    if(cro == "")
    {
      if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
      else { refresh_all_function(ival); }
    }
    else
    {
      if(type == "Ltd" || type == "ltd" || type == "Limited" || type == "Limted" || type == "limited")
      {
        $.ajax({
          url:"<?php echo URL::to('user/refresh_cro_ard'); ?>",
          dataType:"json",
          type:"get",
          data:{clientid:clientid,cro:cro},
          success:function(result)
          {
            if(result['companystatus'] == "0")
            {
              $("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
              $("#clientidtr_"+clientid).find(".company_td").css({'color' : 'green', 'font-weight' : '500'});
            }
            else{
              $("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
              $("#clientidtr_"+clientid).find(".company_td").css({'color' : 'blue', 'font-weight' : '800'});
            }

            if(result['ardstatus'] == "0")
            {
              $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
              $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'green'});
            }
            else{
              $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
              $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'red'});
            }

            if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
            else { refresh_all_function(ival); }
          }
        });
      }
      else{
        if(ival == countval) { $("body").removeClass("loading_content"); $.colorbox.close(); }
        else { refresh_all_function(ival); }
      }
    }
  }
  if($(e.target).hasClass('no_proceed'))
  {
    $.colorbox.close();
  }
  if($(e.target).hasClass('check_cro'))
  {
    $("body").addClass("loading");
    var cro = $(e.target).attr("data-element");
    $(".company_number").val(cro);
    $(".search_company_modal").modal("show");
    $("#indicator_1").prop("checked",true);
    setTimeout( function() { 
      $(".search_company_btn").trigger("click");
      
    },1000);
  }
	if(e.target.id == 'show_incomplete')
	{
	    if($(e.target).is(':checked'))
	    {
	      $(".edit_task").each(function() {
	          if($(this).hasClass('disabled_tr'))
	          {
	            $(this).hide();
	          }
	      });
	    }
	    else{
	      $(".edit_task").each(function() {
	          if($(this).hasClass('disabled_tr'))
	          {
	            $(this).show();
	          }
	      });
	    }
	}
	if($(e.target).hasClass('search_company_btn'))
	{
    var checked = $(".indicator:checked").length;
    var company_number = $(".company_number").val();
    var indicator = $(".indicator:checked").val();
    if(checked < 1)
    {
      alert("Please select the Company / Business indicator to search for a Company");
    }
    else if(company_number == "")
    {
      alert("Please enter the Company Number to search for a Company");
    }
    else{
      $("body").addClass("loading");
      $.ajax({
        url:"<?php echo URL::to('user/get_company_details_cro'); ?>",
        type:"post",
        data:{company_number:company_number,indicator:indicator},
        success:function(result)
        {
          $(".table_api").html(result);
          //$(".search_company_modal").modal("hide");
          $("body").removeClass("loading");
        }
      });
    }
	}
	if($(e.target).hasClass('refresh_croard'))
	{
		var clientid = $(e.target).attr("data-element");
		var cro = $(e.target).attr("data-cro");
		var type = $(e.target).attr("data-type");
		if(cro == "")
		{
			alert("Sorry you cant fetch the details from api because the CRO Number for this client is empty.")
		}
		else 
  	{	
  		if(type == "Ltd" || type == "ltd" || type == "Limited" || type == "Limted" || type == "limited")
  		{
        $.colorbox({html:'<p style="text-align:center;margin-top:10px;font-size:18px;font-weight:600;color:#000">Do you want to update the Client Manager with the ARD Date from the Companies Office</p> <p style="text-align:center;margin-top:26px;font-size:18px;font-weight:600;"><a href="javascript:" class="common_black_button yes_proceed_single" data-element="'+clientid+'" data-cro="'+cro+'">Yes</a><a href="javascript:" class="common_black_button no_proceed_single">No</a></p>',fixed:true,width:"800px"});
  		}
  		else{
  			alert("Sorry you cant fetch the details from api because the type should be 'Ltd' or 'Limited'.")
  		}
  	}
	}
  if($(e.target).hasClass('yes_proceed_single'))
  {
    $("body").addClass("loading");
    var clientid = $(e.target).attr("data-element");
    var cro = $(e.target).attr("data-cro");
    $.ajax({
      url:"<?php echo URL::to('user/refresh_cro_ard'); ?>",
      dataType:"json",
      type:"get",
      data:{clientid:clientid,cro:cro},
      success:function(result)
      {
        if(result['companystatus'] == "0")
        {
          $("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
          $("#clientidtr_"+clientid).find(".company_td").css({'color' : 'green', 'font-weight' : '500'});
        }
        else{
          $("#clientidtr_"+clientid).find(".company_td").html(result['company_name']);
          $("#clientidtr_"+clientid).find(".company_td").css({'color' : 'blue', 'font-weight' : '800'});
        }

        if(result['ardstatus'] == "0")
        {
          $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
          $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'green'});
        }
        else{
          $("#clientidtr_"+clientid).find(".cro_ard_td").html('<spam class="cro_ard_sort_val" style="display: none">'+result['corard_timestamp']+'</spam>'+result['next_ard']);
          $("#clientidtr_"+clientid).find(".cro_ard_td").css({'color' : 'red'});
        }
        $.colorbox.close();
        $("body").removeClass("loading");
      }
    });
  }
  if($(e.target).hasClass('no_proceed_single'))
  {
    $.colorbox.close();
  }
})

</script>
@stop
