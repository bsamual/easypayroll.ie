@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<link rel="stylesheet" href="<?php echo URL::to('assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('assets/js/lightbox/jquery.colorbox.js'); ?>"></script>
<style>
body{
  background: #f5f5f5 !important;
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
.error{color: #f00; font-size: 12px;}
a:hover{text-decoration: underline;}
</style>

<div class="modal fade alert_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" style="z-index:999999999">
  <div class="modal-dialog modal-md">
    <div class="modal-content">
      <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Alert</h5>
        </div>
        <div class="modal-body">
          You are about to rename all the Attachment Files, Do you wish to continue?
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary common_black_button yes_hit">Yes</button>
            <button type="button" class="btn btn-primary common_black_button no_hit">No</button>
        </div>
      </div>
    </div>
</div>

<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
      <h4 class="col-md-6" style="padding: 0px;font-weight:700;">Opening Balance Manager</h4>
      <div class="col-md-6">
        <a href="<?php echo URL::to('user/import_opening_balance_manager'); ?>" class="common_black_button" style="float:right">Import Opening Balance Manager</a>
        <input type="checkbox" name="hide_active_clients" class="hide_active_clients" id="hide_active_clients" value="1"><label for="hide_active_clients" style="float:right;margin-right: 25px;margin-top: 10px;">Hide Inactive Clients</label>
      </div>
      <div style="clear: both;">
        <?php
        if(Session::has('message')) { ?>
            <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
         <?php }   
        ?>
      </div> 
      <table class="display nowrap fullviewtablelist" id="client_expand" width="100%">
        <thead>
          <tr style="background: #fff;">
              <th width="2%" style="text-align: left;">S.No</th>
              <th style="text-align: left;">Client ID</th>
              <th style="text-align: left;">Company</th>
              <th style="text-align: left;">Name</th>
              <th style="text-align: left;">Balance</th>
              <th width="10%" style="text-align: left;">Action</th>
          </tr>
        </thead>                            
        <tbody id="clients_tbody">
            <?php
              $i=1;
              if(count($clientlist)){              
                foreach($clientlist as $key => $client){
                    $disabled='';
                    if($client->active == "2")
                    {
                      $check_color = DB::table('cm_class')->where('id',$client->active)->first();
                      $style="color:#f00";
                      $active_cli = 'inactive_clients';
                    }
                    else{
                      $style="color:#000";
                      $active_cli = 'active_clients';
                    }
                    $balance_check = DB::table('opening_balance')->where('client_id',$client->client_id)->first();
                    if(count($balance_check))
                    {
                      if($balance_check->opening_balance == "")
                      {
                        $balance = '<spam style="color:#000">-</spam>';
                      }
                      elseif($balance_check->opening_balance == 0)
                      {
                        $balance = '<spam style="color:#000">0.00</spam>';
                      }
                      elseif($balance_check->opening_balance < 0)
                      {
                        $balance = '<spam style="color:#f00">'.number_format_invoice($balance_check->opening_balance).'</spam>';
                      }
                      else{
                        $balance = '<spam style="color:blue">'.number_format_invoice($balance_check->opening_balance).'</spam>';
                      }

                      if($balance_check->locked == 0)
                      {
                        $action = '<a href="'.URL::to('user/lock_client_opening_balance?client_id='.$client->client_id.'&locked=1').'" class="fa fa-unlock" style="color:green;font-size:18px !important"></a>';
                      }
                      else{
                        $action = '<a href="'.URL::to('user/lock_client_opening_balance?client_id='.$client->client_id.'&locked=0').'" class="fa fa-lock" style="color:#f00;font-size:18px !important"></a>';
                      }
                    }
                    else{
                      $balance = '<spam style="color:#000">-</spam>';
                      $action = '<a href="'.URL::to('user/lock_client_opening_balance?client_id='.$client->client_id.'&locked=1').'" class="fa fa-unlock" style="color:green;font-size:18px !important"></a>';
                    }
                    ?>
                    <tr class="edit_task <?php echo $active_cli; ?> <?php echo $disabled; ?>" style="<?php echo $style; ?>"  id="clientidtr_<?php echo $client->id; ?>">
                      <td><a href="<?php echo URL::to('user/client_opening_balance_manager?client_id='.$client->client_id.''); ?>" style="<?php echo $style; ?>"><?php echo $i; ?></a></td>
                      <td align="left"><a href="<?php echo URL::to('user/client_opening_balance_manager?client_id='.$client->client_id.''); ?>" style="<?php echo $style; ?>"><?php echo $client->client_id; ?></a></td>
                      <td align="left"><a href="<?php echo URL::to('user/client_opening_balance_manager?client_id='.$client->client_id.''); ?>" style="<?php echo $style; ?>"><?php echo ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company; ?></a></td>
                      <td align="left"><a href="<?php echo URL::to('user/client_opening_balance_manager?client_id='.$client->client_id.''); ?>" style="<?php echo $style; ?>"><?php echo $client->firstname.' & '.$client->surname; ?></a></td>
                      
                      <td align="left">
                        <?php echo $balance; ?>
                      </td>
                      <td align="left" style="<?php echo $style; ?>">
                        <?php echo $action; ?>
                      </td>
                    </tr>
                    <?php
                      $i++;
                }              
              }
              else
              {
                echo'<tr><td colspan="11" align="center">Empty</td></tr>';
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
<script type="text/javascript">
$(function(){
    $('#client_expand').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: true,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false
    });
});
$(window).click(function(e) {
	if($(e.target).hasClass('hide_active_clients'))
	{
		if($(e.target).is(":checked"))
		{
			$(".inactive_clients").hide();
		}
		else{
			$(".inactive_clients").show();
		}
	}
});
</script>
@stop