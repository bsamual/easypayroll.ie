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
  border-bottom:1px solid #000;
  font-weight: 600; font-size: 15px;
  color: #000 !important;
}
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

.paye_mars_ul{width: 50%; height: auto; float: left; border: 1px solid #000;}
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

<div class="content_section">
<div class="arrow_right" style="height: auto; padding: 15px; position: fixed; bottom: 10px; background:  #ff0; z-index: 9999999; right: 15px;font-size:34px;display:none">
  <a href="javascript:" class="arrow_right_scroll"><i class="fa fa-arrow-circle-o-up arrow_right_scroll" aria-hidden="true"></i></a>
</div>
  <div class="page_title" style="position:fixed;margin-top: -17px;">
    <div class="col-lg-7 padding_00" style="line-height: 30px; font-size: 20px">
      PAYE M.R.S <?php echo $year->year_name?>  
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
                        $selected_active_month.='<option value="'.$mn.'">'.$mon_mon.'</option>';
                      }
                      echo $selected_active_month;
                      ?>
                  </select>
                </div>
                <div class="col-lg-7 ">                
                    <a href="javascript:" class="common_black_button apply_task_to_ros" style="clear: both;float: left;">Apply Task Liability To ROS Liability</a>
                    <a href="javascript:" class="common_black_button export_csv_report" style="float: left;">Report CSV</a>
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
        <ul id="task_paye_ul">
        <li>
            <div class="sno" style="background:#000;color:#fff">S.No</div>
            <div class="clientname" style="background:#000;color:#fff">Task Name
                
            </div>
        </li>';
  $iii = 1;
  if(count($payelist)){
    foreach ($payelist as $keytask => $task) {
        $level_name = DB::table('p30_tasklevel')->where('id',$task->task_level)->first();
        if($task->task_level != 0){ $action = $level_name->name; }
        if($task->pay == 0){ $pay = 'No';}else{$pay = 'Yes';}
        if($task->email == 0){ $email = 'No';}else{$email = 'Yes';}
        if($keytask % 2 == 0) { $background = ''; }else{ $background = 'style="background:#e5f7fe"'; }
        if($task->disabled == 1) { $checked = 'checked'; $label_color = 'color:#f00'; $disbledtext = ' (DISABLED)'; } else { $checked = ''; $label_color = 'color:#000'; $disbledtext = ''; }
        $period1 = unserialize($task->month_liabilities_1);
        $period2 = unserialize($task->month_liabilities_2);
        $period3 = unserialize($task->month_liabilities_3);
        $period4 = unserialize($task->month_liabilities_4);
        $period5 = unserialize($task->month_liabilities_5);
        $period6 = unserialize($task->month_liabilities_6);
        $period7 = unserialize($task->month_liabilities_7);
        $period8 = unserialize($task->month_liabilities_8);
        $period9 = unserialize($task->month_liabilities_9);
        $period10 = unserialize($task->month_liabilities_10);
        $period11 = unserialize($task->month_liabilities_11);
        $period12 = unserialize($task->month_liabilities_12);
        
        $output.='<li class="main_li" '.$background.'>
                <div class="sno">'.$iii.'</div>
                 <div class="clientname"><input type="checkbox" name="disable_clients" class="disable_clients" id="disable_'.$task->id.'" value="'.$task->id.'" '.$checked.'> <label class="task_name_label task_name_label2" for="disable_'.$task->id.'" style="'.$label_color.'">'.$task->task_name.$disbledtext.'</label>
                    <div class="load_info_table">
                      <table class="table_bg table-fixed-header table_paye_p30" id="table_'.$task->id.'" style="margin-bottom:20px; width:100%; margin-top:40px">
                        <thead class="header">
                          <tr>
                              <th style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #000;width:50px" valign="top">S.No</th>                    
                              <th colspan="7" style="text-align:left;width:500px">
                                  Clients
                              </th>
                          </tr>
                        </thead>
                        <tbody>
                            <tr class="task_row_'.$task->id.'">
                              <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff;" valign="top">1</td>
                              <td colspan="3" style="border-bottom: 0px; text-align: left; height:110px;"> 
                                <div class="update_task_label_sample" style="width:400px; position:absolute; margin-top:-50px;">
                                  <b class="task_name_label" style="font-size:18px;'.$label_color.'">'.$task->task_name.'</b><br>
                                  Emp No. '.$task->task_enumber.'<br>
                                  Action: '.$action.'<br>
                                  PAY: '.$pay.'<br>
                                  Email: '.$email.'                   
                                </div>
                              </td> 
                              <td style="text-align: center;" valign="bottom">ROS Liability</td>
                              <td style="text-align: center;" valign="bottom">Task Liability</td>
                              <td valign="bottom">Diff</td>';
                          $output.='</tr>';
                          $total_ros_value = 0;
                          $total_task_value = 0;
                          $total_diff_value = 0;
                          $total_payment_value = 0;
                        for($i=1; $i<=12;$i++)
                        {
                          if($i == 1) { $month_name = 'Jan'; }
                          elseif($i == 2) { $month_name = 'Feb'; }
                          elseif($i == 3) { $month_name = 'Mar'; }
                          elseif($i == 4) { $month_name = 'Apr'; }
                          elseif($i == 5) { $month_name = 'May'; }
                          elseif($i == 6) { $month_name = 'Jun'; }
                          elseif($i == 7) { $month_name = 'Jul'; }
                          elseif($i == 8) { $month_name = 'Aug'; }
                          elseif($i == 9) { $month_name = 'Sep'; }
                          elseif($i == 10) { $month_name = 'Oct'; }
                          elseif($i == 11) { $month_name = 'Nov'; }
                          elseif($i == 12) { $month_name = 'Dec'; }

                          $output.='<tr class="month_row_'.$task->id.'-'.$i.'" data-element="'.$i.'" data-value="'.$task->id.'">
                              <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff; border-bottom:1px solid #000"></td>
                              <td colspan="2" style="width: 40px; text-align: right; border-bottom: 0px;">
                                  <input type="radio" name="month_name_'.$task->id.'" class="month_class month_class_'.$i.'" value="'.$i.'" data-element="'.$task->id.'"><label>&nbsp;</label>
                              </td>
                              <td style="width: 100px; border-bottom: 0px;">'.$month_name.'-'.$year->year_name.'</td>
                              <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control ros_class ros_class_'.$task->id.'-'.$i.'" data-element="'.$i.'" data-value="'.$task->id.'" value="'.number_format_invoice(${'period'.$i}['ros_liability']).'"></td>
                              <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control liability_class liability_class_'.$task->id.'-'.$i.'" value="'.number_format_invoice(${'period'.$i}['task_liability']).'" readonly=""></td>
                              <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;"><input class="form-control diff_class diff_class_'.$task->id.'-'.$i.'" value="'.number_format_invoice(${'period'.$i}['liability_diff']).'" readonly=""></td>
                          </tr>';

                          $total_ros_value = $total_ros_value + number_format_invoice_without_comma(${'period'.$i}['ros_liability']);
                          $total_task_value = $total_task_value + number_format_invoice_without_comma(${'period'.$i}['task_liability']);
                          $total_diff_value = $total_diff_value + number_format_invoice_without_comma(${'period'.$i}['liability_diff']);
                          $total_payment_value = $total_payment_value + number_format_invoice_without_comma(${'period'.$i}['payments']);
                        }
                        $output.='<tr class="task_total_row_'.$task->id.'">
                                <td style="border-right: 1px solid #000 !important; border-left: 1px solid #000 !important; border: 1px solid #fff; border-bottom:1px solid #000"></td>
                                
                                <td colspan="2" style="width: 40px; text-align: right; border-bottom: 0px;">
                                    
                                </td>
                                <td style="width: 100px; border-bottom: 0px;">Total </td>
                                <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;">
                                  <input class="form-control total_ros_class" value="'.number_format_invoice($total_ros_value).'"></td>
                                <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;">
                                  <input class="form-control total_liability_class" value="'.number_format_invoice($total_task_value).'" readonly=""></td>
                                <td style="width: 110px; border-bottom: 0px; border: 1px solid #000; border-top: 0px;">
                                  <input class="form-control total_diff_class" value="'.number_format_invoice($total_diff_value).'" readonly=""></td>
                            </tr></tbody>
                        </table>
                    </div>
                </div>
            </li>';
            $iii++;
    }
  }
  else{
    $output.='
      <li>
          <div class="sno"></div>
          <div class="clientname"> Empty
             
          </div>
      </li>';
  }
  $output.='</ul>
  </div>';
  echo $output;
  ?>

    </div>
  </div>
</div>
<input type="hidden" name="hidden_loading_status" id="hidden_loading_status" value="">
<div class="modal_load"></div>

<script type="text/javascript">
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

$(window).click(function(e) {
if($(e.target).hasClass('apply_task_to_ros'))
{
  var active = $(".active_month").val();
  if(active == "")
  {
    alert('Please select the active month and then click on the "Apply Task Liability To ROS Liability" Button');
  }
  else{
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/apply_task_to_ros'); ?>",
      type:"post",
      data:{active:active,year:"<?php echo $year->year_id; ?>"},
      success:function(result)
      {
        var lengthval = $(".month_class:checked").length - 1;
        $(".month_class:checked").each(function(index,value) {
          var ros = $(this).parents("tr").find(".ros_class").val();
          if(ros == "" || ros == "0.00" || ros == "0" || ros == "00.00" || ros == "0.0" || ros == "00.0")
          {
            var liability = $(this).parents("tr").find(".liability_class").val();
            $(this).parents("tr").find(".ros_class").val(liability);
            $(this).parents("tr").find(".diff_class").val("0.00");
          }
          if(index == lengthval)
          {
            $("body").removeClass("loading");
          }
        });
      }
    })
  }
}
if($(e.target).hasClass('export_csv_report'))
{
  var base_url = '<?php echo URL::to('papers'); ?>';
  var active = $(".active_month").val();
  if(active == "")
  {
    alert('Please select the active month and then click on the "Report CSV" Button');
  }
  else{
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/report_active_month_csv'); ?>",
      type:"post",
      data:{active:active,year:"<?php echo $year->year_id; ?>"},
      success:function(result)
      {
        SaveToDisk(base_url+'/'+result,result);
        $("body").removeClass("loading");
      }
    })
  }
}
if($(e.target).hasClass('export_csv'))
{
  var base_url = '<?php echo URL::to('papers'); ?>';
  var task_id = $(e.target).attr("data-element");
  $.ajax({
    url:"<?php echo URL::to('user/paye_p30_create_csv'); ?>",
    type:"post",
    data:{task_id:task_id},
    success: function(result)
    {
      SaveToDisk(base_url+'/'+result,result)
    }
  })
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
    var active = $(this).val();
    $(".active_month_class").val(active);
    $(".month_class_"+active).prop("checked", true);
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

