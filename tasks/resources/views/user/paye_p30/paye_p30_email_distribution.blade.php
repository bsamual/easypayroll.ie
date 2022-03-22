@extends('userheader')
@section('content')
<style>
.liability_class{
  cursor:pointer;
}
.update_row_class{
  padding-top:15px;
  padding-bottom:15px;
  border-bottom:1px solid #dfdfdf;
}
.text-design{
  font-size:14px;
}
.fa-sort{ cursor:pointer; }
.load_info{
  background: #000;
    color: #fff;
    padding: 5px 10px;
    font-size: 13px;
    font-weight: normal;
    text-transform: none;
    position: absolute;
    left:50%;
}
.export_csv{
  background: #000;
    color: #fff;
    padding: 5px 10px;
    font-size: 13px;
    font-weight: normal;
    text-transform: none;
    position: absolute;
    left:66%;
}
.update_task{
    background: #000;
    color: #fff;
    padding: 5px 10px;
    font-size: 13px;
    font-weight: normal;
    text-transform: none;
    position: absolute;
    left:58%;
}
.unload_info{
  background: #000;
    color: #fff;
    padding: 5px 10px;
    font-size: 13px;
    font-weight: normal;
    text-transform: none;
    position: absolute;
    left:50%;
}
.load_info:active{
    color: #fff !important;
}
.unload_info:active{
  color: #fff !important;
}
.load_info:hover{
    color: #fff !important;
}
.unload_info:hover{
  color: #fff !important;
}
.load_info:focus{
    color: #fff !important;
}
.unload_info:focus{
  color: #fff !important;
}
.load_info:visited{
    color: #fff !important;
}
.unload_info:visited{
  color: #fff !important;
}
.error{
  color:#f00;
}
.blueinfo{
    color:#240bf7 !important;
    padding:6px;
    margin-left:-3px;
}
.table_bg>thead>tr>th
{
    text-align:left;
}
.select_button table tbody tr td a{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}
.select_button table tbody tr td a:hover{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}
.select_button table tbody tr td label{
    color:#000 !important;
    font-weight:800;
    margin-top:6px;
}
.page_title{
  margin-bottom: 0px !important;
  padding: 10px !important;
  background: #fff; z-index: 99
}
.table_bg tbody tr td{
  padding:1px;
  border-bottom:1px solid #000 !important;
  font-weight: 600; font-size: 15px;
  color: #000 !important;
}
.table_paye_p30{ border:1px solid #000; }
.table_bg thead tr th{
  padding:8px;
}
.button_top_right ul li a{padding: 5px 10px; font-size: 12px; font-weight: 600; margin-bottom: 0px;min-height: 33px}
.form-control[readonly]{background-color: #e6e6e6 !important}
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
.modal_load_refresh {
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
body.loading_refresh {
    overflow: hidden;   
}
body.loading_refresh .modal_load_refresh {
    display: block;
}
.modal_load_apply {
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
body.loading_apply {
    overflow: hidden;   
}
body.loading_apply .modal_load_apply {
    display: block;
}
.left_menu{background: #ff0; left: 0px; position: fixed;}
.left_menu li{clear: both; width: 100%;}
.left_menu li a{padding: 10px 15px;}
.left_menu .dropdown-menu{left: 120px; top: 0px;}
.left_menu .dropdown-menu li a{padding: 3px 10px;}
.paye_mars_ul{width: 100%; height: auto; float: left;}
.paye_mars_ul ul{margin: 0px; padding: 0px;}
.paye_mars_ul ul li{width: 100%; height: auto; float: left; list-style: none; border-bottom: 1px solid #000; font-size: 18px; font-weight: 700}
.paye_mars_ul ul li .sno{width: 10%; height: auto; float: left; padding: 5px; }
.paye_mars_ul ul li .clientname{width: 90%; height: auto; float: left; padding: 5px; border-left: 1px solid #000;}
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
.error_message_xls{
  padding: 10px;
    background: #dfdfdf;
}
.fa-question{
  color: #f00 !important;
  font-size: 20px !important;
  margin-left: 5px;
  font-weight: 500 !important;
  margin-top: 5px;
  cursor:pointer;
  background: #fff !important;
  float: none !important;
  padding: 0px 0px !important;
}
.own_table_white tr:nth-child(2n) td:first-child{
  background: none !important;
}
</style>
<div id="alert_modal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="margin-top:100px;z-index:999999">
  <div class="modal-dialog" style="width:30%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Alert</h4>
      </div>
      <div class="modal-body" id="alert_content">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>
<div id="confirm_modal" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static" style="margin-top:100px">
  <div class="modal-dialog" style="width:30%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Alert</h4>
      </div>
      <div class="modal-body" id="confirm_content">
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary yes_hit">Yes</button>
        <button type="button" class="btn btn-primary no_hit">No</button>
      </div>
    </div>
  </div>
</div>
<div id="send_email_modal" class="modal fade send_email_modal" role="dialog" data-keyboard="false" data-backdrop="static" style="margin-top:100px">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Send Email</h4>
      </div>
      <div class="modal-body">
        <h4>Select From User:</h4>
        <select name="select_from_input" class="form-control select_from_input">
          <option value="">Select From User</option>
          <?php
            $users = DB::table('user')->where('user_status',0)->where('disabled',0)->where('email','!=', '')->orderBy('lastname','asc')->get();
            if(count($users))
            {
              foreach($users as $user)
              {
                  ?>
                    <option value="<?php echo trim($user->email); ?>"><?php echo $user->lastname.' '.$user->firstname; ?></option>
                  <?php
              }
            }
          ?>
        </select>
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary common_black_button email_sent_button" value="Send Email">
      </div>
    </div>
  </div>
</div>

<div class="content_section">
<div class="arrow_right" style="height: auto; padding: 15px; position: fixed; bottom: 10px; background:  #ff0; z-index: 9999999; right: 15px;font-size:34px;display:none">
  <a href="javascript:" class="arrow_right_scroll"><i class="fa fa-arrow-circle-o-up arrow_right_scroll" aria-hidden="true"></i></a>
</div>
  <div class="page_title" style="position:fixed;margin-top: -18px;">
    <div class="col-lg-7 padding_00" style="line-height: 30px; font-size: 20px">
      PAYE M.R.S. Quick Access Email Distribution <?php echo $year->year_name?>  
      <input type="hidden" value="<?php echo $year->year_id?>" class="year_id" name="">
    </div>
    <div class="col-lg-5 padding_00 button_top_right" style="float:left;">
          <ul style="float:none">
            
          </ul>
    </div>
    <div class="col-lg-12 padding_00">
        <div class="row">
          <div class="col-lg-9" style="padding: 10px; border:5px solid #000">
              <div class="col-lg-12 padding_00" style="margin-top: 10px;">
                <div class="col-lg-2" style="margin-top:7px;text-align: right;">Active Month:</div>
                <div class="col-lg-3">
                  <select class="form-control active_month" required>
                      <option value="">Select Month From</option>
                      <?php
                      $selected_active_month = '';
                      for($mn=1;$mn<=12;$mn++)
                      {
                        $current_month = date('m');

                        if($current_month == "01") { $current_month = "01"; }
                        else{ $current_month = $current_month - 1; }

                        if($current_month == $mn) { $selected = 'selected'; } else { $selected = ''; }

                        if($mn == "1") { $mon_mon = 'January'; }
                        elseif($mn == "2") { $mon_mon = 'February'; }
                        elseif($mn == "3") { $mon_mon = 'March'; }
                        elseif($mn == "4") { $mon_mon = 'April'; }
                        elseif($mn == "5") { $mon_mon = 'May'; }
                        elseif($mn == "6") { $mon_mon = 'June'; }
                        elseif($mn == "7") { $mon_mon = 'July'; }
                        elseif($mn == "8") { $mon_mon = 'August'; }
                        elseif($mn == "9") { $mon_mon = 'September'; }
                        elseif($mn == "10") { $mon_mon = 'October'; }
                        elseif($mn == "11") { $mon_mon = 'November'; }
                        elseif($mn == "12") { $mon_mon = 'December'; }
                        $selected_active_month.='<option value="'.$mn.'" '.$selected.'>'.$mon_mon.'</option>';
                      }
                      echo $selected_active_month;
                      ?>
                  </select>
                </div>
                <div class="col-lg-7 ">                
                    <a href="javascript:" class="common_black_button quick_email" style="clear: both;float: left;">Quick Email</a>
                </div>
              </div>
          </div>
        </div>
    </div>
  </div>
  <div class="col-lg-12" style="clear: both;  margin-top:90px">
    <div style="width:100%;float:left;">
        <?php
        if(isset($_GET['status']) && $_GET['status'] == "apply")
        {
          ?>
          <p class="alert alert-info" style="clear:both; ">Applied Successfully.
              <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
          </p>
          <?php
        }
        if(Session::has('message')) { ?>
            <p class="alert alert-info" style="clear:both; "><?php echo Session::get('message'); ?>
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            </p>
        <?php }
        if(Session::has('error')) { ?>
            <p class="alert alert-danger" style="clear:both;"><?php echo Session::get('error'); ?>
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
            </p>
        <?php }
        ?>
        </div>
  <?php
  $output='<div class="paye_mars_ul" style="margin-top:30px">
        <table class="table table-fixed-header own_table_white" style="width:150%;max-width:150%">
          <thead>
            <th>S.No <i class="fa fa-sort sno_sort" aria-hidden="true" style="float: right;"></i></th>
            <th>Task Name <i class="fa fa-sort task_sort" aria-hidden="true" style="float: right;"></i></th>
            <th>Emp No <i class="fa fa-sort empno_sort" aria-hidden="true" style="float: right;"></i></th>
            <th>Action <i class="fa fa-sort action_sort" aria-hidden="true" style="float: right;"></i></th>
            <th>Pay <i class="fa fa-sort pay_sort" aria-hidden="true" style="float: right;"></i></th>
            <th>Email <i class="fa fa-sort email_sort" aria-hidden="true" style="float: right;"></i></th>
            <th>ROS <br/>Liability <i class="fa fa-sort ros_sort" aria-hidden="true" style="float: right;"></i></th>
            <th>Task <br/>Liability <i class="fa fa-sort liability_sort" aria-hidden="true" style="float: right;"></i></th>
            <th>Diff <i class="fa fa-sort diff_sort" aria-hidden="true" style="float: right;"></i></th>
            <th>Auto<br/> Email<br/> Status <i class="fa fa-sort autoemail_sort" aria-hidden="true" style="float: right;"></i></th>
            
            <th>January</th>
            <th>February</th>
            <th>March</th>
            <th>April</th>
            <th>May</th>
            <th>June</th>
            <th>July</th>
            <th>August</th>
            <th>September</th>
            <th>October</th>
            <th>November</th>
            <th>December</th>
          </thead>
          <tbody id="paye_task_tbody">';
  $iii = 1;
  if(count($payelist)){
    foreach ($payelist as $keytask => $task) {
        $level_name = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();
        if($task->task_level != 0){ $action = $level_name->name; }
        if($task->pay == 0){ $pay = 'No';}else{$pay = 'Yes';}
        if($task->email == 0){ $email = 'No <i class="fa fa-envelope"  style="color:red"></i>';}else{$email = 'Yes <i class="fa fa-envelope"  style="color:green"></i>';}
        if($keytask % 2 == 0) { $background = ''; }else{ $background = 'style="background:#e5f7fe"'; }
        if($task->disabled == 1) { $checked = 'checked'; $label_color = 'color:#f00'; $disbledtext = ' (DISABLED)'; } else { $checked = ''; $label_color = 'color:#000'; $disbledtext = ''; }

        $current_month = date('m');
        $current_month = $current_month + 1;
        $last_email_sent_1 = '';
        $last_email_sent_2 = '';
        $last_email_sent_3 = '';
        $last_email_sent_4 = '';
        $last_email_sent_5 = '';
        $last_email_sent_6 = '';
        $last_email_sent_7 = '';
        $last_email_sent_8 = '';
        $last_email_sent_9 = '';
        $last_email_sent_10 = '';
        $last_email_sent_11 = '';
        $last_email_sent_12 = '';


        if($current_month == "01" || $current_month == "1") {  $periodval = unserialize($task->month_liabilities_1); }
        elseif($current_month == "02" || $current_month == "2") { $periodval = unserialize($task->month_liabilities_2); }
        elseif($current_month == "03" || $current_month == "3") { $periodval = unserialize($task->month_liabilities_3); }
        elseif($current_month == "04" || $current_month == "4") { $periodval = unserialize($task->month_liabilities_4); }
        elseif($current_month == "05" || $current_month == "5") { $periodval = unserialize($task->month_liabilities_5); }
        elseif($current_month == "06" || $current_month == "6") { $periodval = unserialize($task->month_liabilities_6); }
        elseif($current_month == "07" || $current_month == "7") { $periodval = unserialize($task->month_liabilities_7); }
        elseif($current_month == "08" || $current_month == "8") { $periodval = unserialize($task->month_liabilities_8); }
        elseif($current_month == "09" || $current_month == "9") { $periodval = unserialize($task->month_liabilities_9); }
        elseif($current_month == "10" || $current_month == "10") { $periodval = unserialize($task->month_liabilities_10); }
        elseif($current_month == "11" || $current_month == "11") { $periodval = unserialize($task->month_liabilities_11); }
        elseif($current_month == "12" || $current_month == "12") { $periodval = unserialize($task->month_liabilities_12); }

        $monthperiodval_1 = unserialize($task->month_liabilities_1); 
        $monthperiodval_2 = unserialize($task->month_liabilities_2); 
        $monthperiodval_3 = unserialize($task->month_liabilities_3); 
        $monthperiodval_4 = unserialize($task->month_liabilities_4); 
        $monthperiodval_5 = unserialize($task->month_liabilities_5); 
        $monthperiodval_6 = unserialize($task->month_liabilities_6); 
        $monthperiodval_7 = unserialize($task->month_liabilities_7); 
        $monthperiodval_8 = unserialize($task->month_liabilities_8); 
        $monthperiodval_9 = unserialize($task->month_liabilities_9); 
        $monthperiodval_10 = unserialize($task->month_liabilities_10); 
        $monthperiodval_11 = unserialize($task->month_liabilities_11); 
        $monthperiodval_12 = unserialize($task->month_liabilities_12); 

        if(isset($monthperiodval_1['last_email_sent_all'])) { 
          $last_email_sent_1 = dateformat_string($monthperiodval_1['last_email_sent_all']); 
        }
        elseif(isset($monthperiodval_1['last_email_sent'])) {
          $last_email_sent_1 = ($monthperiodval_1['last_email_sent'] != "")? date('d M Y @ H:i', strtotime($monthperiodval_1['last_email_sent'])):""; 
        }

        if(isset($monthperiodval_2['last_email_sent_all'])) { 
          $last_email_sent_2 = dateformat_string($monthperiodval_2['last_email_sent_all']); 
        }
        elseif(isset($monthperiodval_2['last_email_sent'])) {
          $last_email_sent_2 = ($monthperiodval_2['last_email_sent'] != "")? date('d M Y @ H:i', strtotime($monthperiodval_2['last_email_sent'])):""; 
        }

        if(isset($monthperiodval_3['last_email_sent_all'])) { 
          $last_email_sent_3 = dateformat_string($monthperiodval_3['last_email_sent_all']); 
        }
        elseif(isset($monthperiodval_3['last_email_sent'])) {
          $last_email_sent_3 = ($monthperiodval_3['last_email_sent'] != "")? date('d M Y @ H:i', strtotime($monthperiodval_3['last_email_sent'])):""; 
        }

        if(isset($monthperiodval_4['last_email_sent_all'])) { 
          $last_email_sent_4 = dateformat_string($monthperiodval_4['last_email_sent_all']); 
        }
        elseif(isset($monthperiodval_4['last_email_sent'])) {
          $last_email_sent_4 = ($monthperiodval_4['last_email_sent'] != "")? date('d M Y @ H:i', strtotime($monthperiodval_4['last_email_sent'])):""; 
        }

        if(isset($monthperiodval_5['last_email_sent_all'])) { 
          $last_email_sent_5 = dateformat_string($monthperiodval_5['last_email_sent_all']); 
        }
        elseif(isset($monthperiodval_5['last_email_sent'])) {
          $last_email_sent_5 = ($monthperiodval_5['last_email_sent'] != "")? date('d M Y @ H:i', strtotime($monthperiodval_5['last_email_sent'])):""; 
        }

        if(isset($monthperiodval_6['last_email_sent_all'])) { 
          $last_email_sent_6 = dateformat_string($monthperiodval_6['last_email_sent_all']); 
        }
        elseif(isset($monthperiodval_6['last_email_sent'])) {
          $last_email_sent_6 = ($monthperiodval_6['last_email_sent'] != "")? date('d M Y @ H:i', strtotime($monthperiodval_6['last_email_sent'])):""; 
        }

        if(isset($monthperiodval_7['last_email_sent_all'])) { 
          $last_email_sent_7 = dateformat_string($monthperiodval_7['last_email_sent_all']); 
        }
        elseif(isset($monthperiodval_7['last_email_sent'])) {
          $last_email_sent_7 = ($monthperiodval_7['last_email_sent'] != "")? date('d M Y @ H:i', strtotime($monthperiodval_7['last_email_sent'])):""; 
        }

        if(isset($monthperiodval_8['last_email_sent_all'])) { 
          $last_email_sent_8 = dateformat_string($monthperiodval_8['last_email_sent_all']); 
        }
        elseif(isset($monthperiodval_8['last_email_sent'])) {
          $last_email_sent_8 = ($monthperiodval_8['last_email_sent'] != "")? date('d M Y @ H:i', strtotime($monthperiodval_8['last_email_sent'])):""; 
        }

        if(isset($monthperiodval_9['last_email_sent_all'])) { 
          $last_email_sent_9 = dateformat_string($monthperiodval_9['last_email_sent_all']); 
        }
        elseif(isset($monthperiodval_9['last_email_sent'])) {
          $last_email_sent_9 = ($monthperiodval_9['last_email_sent'] != "")? date('d M Y @ H:i', strtotime($monthperiodval_9['last_email_sent'])):""; 
        }

        if(isset($monthperiodval_10['last_email_sent_all'])) { 
          $last_email_sent_10 = dateformat_string($monthperiodval_10['last_email_sent_all']); 
        }
        elseif(isset($monthperiodval_10['last_email_sent'])) {
          $last_email_sent_10 = ($monthperiodval_10['last_email_sent'] != "")? date('d M Y @ H:i', strtotime($monthperiodval_10['last_email_sent'])):""; 
        }

        if(isset($monthperiodval_11['last_email_sent_all'])) { 
          $last_email_sent_11 = dateformat_string($monthperiodval_11['last_email_sent_all']); 
        }
        elseif(isset($monthperiodval_11['last_email_sent'])) {
          $last_email_sent_11 = ($monthperiodval_11['last_email_sent'] != "")? date('d M Y @ H:i', strtotime($monthperiodval_11['last_email_sent'])):""; 
        }

        if(isset($monthperiodval_12['last_email_sent_all'])) { 
          $last_email_sent_12 = dateformat_string($monthperiodval_12['last_email_sent_all']); 
        }
        elseif(isset($monthperiodval_12['last_email_sent'])) {
          $last_email_sent_12 = ($monthperiodval_12['last_email_sent'] != "")? date('d M Y @ H:i', strtotime($monthperiodval_12['last_email_sent'])):""; 
        }

        
        if(($task->email == "1") && ($periodval['liability_diff'] < 1) && ($periodval['ros_liability'] != 0) && ($periodval['ros_liability'] != '0.00')) { 
          $auto_email = '<spam class="auto_yes autoemail_sort_val" data-element="'.$task->id.'" style="color:green">Yes</spam>';
        }
        else { 
          $auto_email = '<spam class="auto_no autoemail_sort_val" style="color:red">No</spam>';
        }

        $output.='<tr>
          <td class="sno_sort_val">'.$iii.'</td>
          <td class="task_sort_val" style="font-size:18px;'.$label_color.'">'.$task->task_name.'</td>
          <td class="empno_sort_val">'.$task->task_enumber.'</td>
          <td class="action_sort_val">'.$action.'</td>
          <td class="pay_sort_val">'.$pay.'</td>
          <td class="email_sort_val">'.$email.'</td>
          <td>'.number_format_invoice($periodval['ros_liability']).' <spam class="ros_sort_val" style="display:none">'.round($periodval['ros_liability']).'</spam></td>
          <td>'.number_format_invoice($periodval['task_liability']).' <spam class="liability_sort_val" style="display:none">'.round($periodval['task_liability']).'</spam></td>
          <td>'.number_format_invoice($periodval['liability_diff']).' <spam class="diff_sort_val" style="display:none">'.round($periodval['liability_diff']).'</spam></td>
          <td>'.$auto_email.'</td>

          <td>'.$last_email_sent_1.'</td>
          <td>'.$last_email_sent_2.'</td>
          <td>'.$last_email_sent_3.'</td>
          <td>'.$last_email_sent_4.'</td>
          <td>'.$last_email_sent_5.'</td>
          <td>'.$last_email_sent_6.'</td>
          <td>'.$last_email_sent_7.'</td>
          <td>'.$last_email_sent_8.'</td>
          <td>'.$last_email_sent_9.'</td>
          <td>'.$last_email_sent_10.'</td>
          <td>'.$last_email_sent_11.'</td>
          <td>'.$last_email_sent_12 .'</td>
        </tr>';
        $iii++;
    }
  }
  else{
   $output.='<tr>
          <td colspan="10">No Tasks Found</td>
        </tr>';
            $iii++;
  }
  $output.='
  </tbody>
  </table>
  </div>';
  echo $output;
  ?>
    </div>
  </div>
</div>
<input type="hidden" name="hidden_loading_status" id="hidden_loading_status" value="">
<div class="modal_load"></div>
<div class="modal_load_apply" style="text-align: center;">
  <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until the Emails sent to all Clients with Auto Email Status as Yes.</p>
  <p style="font-size:18px;font-weight: 600;">Mail Sent for Client: <span id="apply_first"></span> of <span id="apply_last"></span></p>
</div>
<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<input type="hidden" name="task_sortoptions" id="task_sortoptions" value="asc">
<input type="hidden" name="empno_sortoptions" id="empno_sortoptions" value="asc">
<input type="hidden" name="action_sortoptions" id="action_sortoptions" value="asc">
<input type="hidden" name="pay_sortoptions" id="pay_sortoptions" value="asc">
<input type="hidden" name="email_sortoptions" id="email_sortoptions" value="asc">
<input type="hidden" name="ros_sortoptions" id="ros_sortoptions" value="asc">
<input type="hidden" name="liability_sortoptions" id="liability_sortoptions" value="asc">
<input type="hidden" name="diff_sortoptions" id="diff_sortoptions" value="asc">
<input type="hidden" name="autoemail_sortoptions" id="autoemail_sortoptions" value="asc">

<script type="text/javascript">
$(document).ready(function() {
  var value = $(".active_month").val();
  $('.active_month').val(value).change();
});
$(window).dblclick(function(e) {
  if($(e.target).hasClass('liability_class'))
  {
    var value = $(e.target).val();
    var paye_task = $(e.target).parents("tr").find(".ros_class").attr("data-value");
    var paye_month = $(e.target).parents("tr").find(".ros_class").attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/update_ros_liability'); ?>",
      type:"post",
      data:{paye_task:paye_task,paye_month:paye_month,value:value},
      success: function(result)
      {
        $(e.target).parents("tr").find(".ros_class").val(value);
        $(e.target).parents("tr").find(".diff_class").val('0.00');
      }
    });
  }
});
function next_email_sent_div(count)
{
    var active_month = $(".active_month").val();
    var from = $(".select_from_input").val();
    var id = $(".auto_yes:eq("+count+")").attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/send_email_to_paye_client'); ?>",
      type:"post",
      data:{id:id,month:active_month,from:from},
      success:function(result)
      {
        setTimeout( function() {
          var countval = count + 1;
          if($(".auto_yes:eq("+countval+")").length > 0)
          {
            next_email_sent_div(countval);
            $("#apply_first").html(countval);
          }
          else{
            $(".send_email_modal").modal("hide");
            window.location.reload();
          }
      },200);
      }
    });
}
var convertToNumber = function(value){
       var lowercase = value.toLowerCase();
       return lowercase.trim();
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
if($(e.target).hasClass('sno_sort'))
{
  var sort = $("#sno_sortoptions").val();
  if(sort == 'asc')
  {
    $("#sno_sortoptions").val('desc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumeric($(a).find('.sno_sort_val').html()) <
      convertToNumeric($(b).find('.sno_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#sno_sortoptions").val('asc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumeric($(a).find('.sno_sort_val').html()) <
      convertToNumeric($(b).find('.sno_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#paye_task_tbody').html(sorted);
}
if($(e.target).hasClass('task_sort'))
{
  var sort = $("#task_sortoptions").val();
  if(sort == 'asc')
  {
    $("#task_sortoptions").val('desc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.task_sort_val').html()) <
      convertToNumber($(b).find('.task_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#task_sortoptions").val('asc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.task_sort_val').html()) <
      convertToNumber($(b).find('.task_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#paye_task_tbody').html(sorted);
}
if($(e.target).hasClass('empno_sort'))
{
  var sort = $("#empno_sortoptions").val();
  if(sort == 'asc')
  {
    $("#empno_sortoptions").val('desc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.empno_sort_val').html()) <
      convertToNumber($(b).find('.empno_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#empno_sortoptions").val('asc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.empno_sort_val').html()) <
      convertToNumber($(b).find('.empno_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#paye_task_tbody').html(sorted);
}
if($(e.target).hasClass('action_sort'))
{
  var sort = $("#action_sortoptions").val();
  if(sort == 'asc')
  {
    $("#action_sortoptions").val('desc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.action_sort_val').html()) <
      convertToNumber($(b).find('.action_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#action_sortoptions").val('asc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.action_sort_val').html()) <
      convertToNumber($(b).find('.action_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#paye_task_tbody').html(sorted);
}
if($(e.target).hasClass('pay_sort'))
{
  var sort = $("#pay_sortoptions").val();
  if(sort == 'asc')
  {
    $("#pay_sortoptions").val('desc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.pay_sort_val').html()) <
      convertToNumber($(b).find('.pay_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#pay_sortoptions").val('asc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.pay_sort_val').html()) <
      convertToNumber($(b).find('.pay_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#paye_task_tbody').html(sorted);
}
if($(e.target).hasClass('email_sort'))
{
  var sort = $("#email_sortoptions").val();
  if(sort == 'asc')
  {
    $("#email_sortoptions").val('desc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.email_sort_val').html()) <
      convertToNumber($(b).find('.email_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#email_sortoptions").val('asc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.email_sort_val').html()) <
      convertToNumber($(b).find('.email_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#paye_task_tbody').html(sorted);
}
if($(e.target).hasClass('ros_sort'))
{
  var sort = $("#ros_sortoptions").val();
  if(sort == 'asc')
  {
    $("#ros_sortoptions").val('desc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumeric($(a).find('.ros_sort_val').html()) <
      convertToNumeric($(b).find('.ros_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#ros_sortoptions").val('asc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumeric($(a).find('.ros_sort_val').html()) <
      convertToNumeric($(b).find('.ros_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#paye_task_tbody').html(sorted);
}
if($(e.target).hasClass('liability_sort'))
{
  var sort = $("#liability_sortoptions").val();
  if(sort == 'asc')
  {
    $("#liability_sortoptions").val('desc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumeric($(a).find('.liability_sort_val').html()) <
      convertToNumeric($(b).find('.liability_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#liability_sortoptions").val('asc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumeric($(a).find('.liability_sort_val').html()) <
      convertToNumeric($(b).find('.liability_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#paye_task_tbody').html(sorted);
}
if($(e.target).hasClass('diff_sort'))
{
  var sort = $("#diff_sortoptions").val();
  if(sort == 'asc')
  {
    $("#diff_sortoptions").val('desc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumeric($(a).find('.diff_sort_val').html()) <
      convertToNumeric($(b).find('.diff_sort_val').html()))) ? 1 : -1;
    });
  }
  else{
    $("#diff_sortoptions").val('asc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumeric($(a).find('.diff_sort_val').html()) <
      convertToNumeric($(b).find('.diff_sort_val').html()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#paye_task_tbody').html(sorted);
}
if($(e.target).hasClass('autoemail_sort'))
{
  var sort = $("#autoemail_sortoptions").val();
  if(sort == 'asc')
  {
    $("#autoemail_sortoptions").val('desc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.autoemail_sort_val').text()) <
      convertToNumber($(b).find('.autoemail_sort_val').text()))) ? 1 : -1;
    });
  }
  else{
    $("#autoemail_sortoptions").val('asc');
    var sorted = $('#paye_task_tbody').find('tr').sort(function(a,b){
      return (ascending ==
           (convertToNumber($(a).find('.autoemail_sort_val').text()) <
      convertToNumber($(b).find('.autoemail_sort_val').text()))) ? -1 : 1;
    });
  }
  ascending = ascending ? false : true;
  $('#paye_task_tbody').html(sorted);
}
if($(e.target).hasClass('email_sent_button'))
{
  var from = $(".select_from_input").val();
  var count_email = $(".auto_yes").length;
  var selected_month = $(".active_month").val();
  var current_month = "<?php echo date('m'); ?>";

  if(from == "")
  {
    alert("Please select the from user to send an email");
  }
  else if(count_email == 0)
  {
    alert("There are no clients with Auto Email Status as Yes");
  }
  else{
    if(selected_month == "1"){var month_name = 'January'; }
    if(selected_month == "2"){var month_name = 'February'; }
    if(selected_month == "3"){var month_name = 'March'; }
    if(selected_month == "4"){var month_name = 'April'; }
    if(selected_month == "5"){var month_name = 'May'; }
    if(selected_month == "6"){var month_name = 'June'; }
    if(selected_month == "7"){var month_name = 'july'; }
    if(selected_month == "8"){var month_name = 'August'; }
    if(selected_month == "9"){var month_name = 'September'; }
    if(selected_month == "10"){var month_name = 'October'; }
    if(selected_month == "11"){var month_name = 'NovemberN'; }
    if(selected_month == "12"){var month_name = 'December'; }

    if(parseInt(selected_month) <= parseInt(current_month))
    {
      var r = confirm("You are about to send an email to all clients for "+month_name);
    }
    else if (parseInt(selected_month) > parseInt(current_month))
    {
      var r = confirm("You are about to send a Global Email for a Month outside the Normal Selection Criteria. Are you sure you want to Continue?");
    }
    if(r){
      $("body").addClass("loading_apply");
      var active_month = $(".active_month").val();
      var from = $(".select_from_input").val();
      
      $("#apply_last").html(count_email);
      var id = $(".auto_yes:eq(0)").attr("data-element");
      $.ajax({
        url:"<?php echo URL::to('user/send_email_to_paye_client'); ?>",
        type:"post",
        data:{id:id,month:active_month,from:from},
        success:function(result)
        {
          setTimeout( function() {
            if($(".auto_yes:eq(1)").length > 0)
            {
              next_email_sent_div(1);
              $("#apply_first").html(1);
            }
            else{
              $(".send_email_modal").modal("hide");
              window.location.reload();
            }
          },200);
        }
      });
    }
  }
}
if($(e.target).hasClass('quick_email'))
{
  var active = $(".active_month").val();
  if(active == "")
  {
    alert('Please select the active month and then click on the "Quick Email" Button');
  }
  else{
    $("#send_email_modal").modal("show");
  }
}
if($(e.target).hasClass("arrow_right_scroll"))
{
  $('body,html').animate({
        scrollTop : 0                       // Scroll to top of body
    }, 500);
}
if($(e.target).hasClass("month_class")){
    $("body").addClass("loading");
    var month_id = $(e.target).val(); 
    var task_id = $(e.target).attr("data-element");
    $(".payetask_"+task_id).val(month_id);
    $.ajax({
        url:"<?php echo URL::to('user/paye_p30_single_month'); ?>",
        type:"post",
        dataType:"json",
        data:{month_id:month_id, task_id:task_id},
        success: function(result){
            $("body").removeClass("loading"); 
        }
    })
}
})
$(".active_month").change(function(){
    $("body").addClass("loading");
    var active = $(this).val();
    var year_id = '<?php echo $year->year_id; ?>';
    setTimeout(function() {
      $.ajax({
        url:"<?php echo URL::to('user/get_paye_email_distribution_table'); ?>",
        type:"post",
        data:{active:active,year_id:year_id},
        success:function(result)
        {
          $("#paye_task_tbody").html(result)
          $("body").removeClass("loading");
        }
      })
    },1000);
});
$(window).keyup(function(e) {
    var valueTimmer;                //timer identifier
    var valueInterval = 500;  //time in ms, 5 second for example
    var $ros_value = $('.ros_class');
    var $payment_value = $('.payment_class');
    if($(e.target).hasClass('ros_class'))
    {
        var that = $(e.target);
        var input_val = $(e.target).val();  
        var period_id = $(e.target).attr("data-element");
        var task_id = $(e.target).attr("data-value");
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping, valueInterval,input_val, period_id,task_id,that);   
    }
});
function doneTyping (ros_value,period_id,task_id,that) {
  $.ajax({
        url:"<?php echo URL::to('user/paye_p30_ros_update')?>",
        type:"post",
        dataType:"json",
        data:{value:ros_value, period_id:period_id,task_id:task_id},
        success: function(result) {            
            $(".month_row_"+task_id+'-'+period_id).find(".diff_class").val(result['different']);
            var ros_total = 0;
            var task_total = 0;
            var diff_total = 0;
            var payment_total = 0;
            that.parents('.table_paye_p30').find(".ros_class").each(function() {
                var str = $(this).val();
                var value = str.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                var rosvalue = parseFloat(value);
                if (!isNaN(rosvalue)) {
                    ros_total += rosvalue;
                }
            });
            that.parents('.table_paye_p30').find(".liability_class").each(function() {
                var str = $(this).val();
                var value = str.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                var taskvalue = parseFloat(value);
                if (!isNaN(taskvalue)) {
                    task_total += taskvalue;
                }
            });
            that.parents('.table_paye_p30').find(".diff_class").each(function() {
                var str = $(this).val();
                var value = str.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                var diffvalue = parseFloat(value);
                if (!isNaN(diffvalue)) {
                    diff_total += diffvalue;
                }
            });
            that.parents('.table_paye_p30').find(".payment_class").each(function() {
                var str = $(this).val();
                var value = str.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                value = value.replace(",","");
                var paymentvalue = parseFloat(value);
                if (!isNaN(paymentvalue)) {
                    payment_total += paymentvalue;
                }
            });
            
            that.parents('.table_paye_p30').find(".total_ros_class").val(ros_total.toFixed(2));
            that.parents('.table_paye_p30').find(".total_liability_class").val(task_total.toFixed(2));
            that.parents('.table_paye_p30').find(".total_diff_class").val(diff_total.toFixed(2));
            that.parents('.table_paye_p30').find(".total_payment_class").val(payment_total.toFixed(2));           
        }
      });
}
$(".ros_class").blur(function(){
    setTimeout(function() {
        var ros_value = $(this).val();  
        var period_id = $(this).attr("data-element");
        $.ajax({
            url:"<?php echo URL::to('user/paye_p30_ros_update')?>",
            type:"post",
            dataType:"json",
            data:{value:ros_value, id:period_id},
            success: function(result) {            
                $(".month_row_"+result['id']).find(".diff_class").val(result['different']);           
            }
        });   
    },1000);
});
</script>
@stop
