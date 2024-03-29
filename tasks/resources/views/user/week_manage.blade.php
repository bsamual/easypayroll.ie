@extends('userheader')
@section('content')
<style>
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
    color:#fff !important;
    font-weight:800;
    margin-top:6px;
}
</style>
<style>
body{background:url('<?php echo URL::to('assets/images/bg_weekly.jpg')?>') no-repeat center top !important; 
  -webkit-background-size: cover !important;
  -moz-background-size: cover !important;
  -o-background-size: cover !important;
  background-size: cover !important;}

.table_style tr td{border:0px solid #fff; text-align: center; color:#fff !important; text-shadow: 0px 1px 2px #000; border-top: 0px !important; padding: 8px 0px !important}

.table_style tr td .btn{background: #fff; color:#000; text-shadow: none; font-weight: bold; border-radius: 0px;}
.table_style{border: 0px solid #fff;}
.page_title{color:#fff; text-shadow: 0px 1px 2px #000}
.background_bg{background: #fff; color: #000;}

</style>
<div class="content_section">
  <div class="page_title">
    SELECT WEEK
    <?php
    $current_week = DB::table('week')->orderBy('week_id','desc')->first();
    ?>
    <a href="<?php echo URL::to('user/select_week/'.base64_encode($current_week->week_id)); ?>" class="common_black_button" style="float:right">Open Current Week</a>
  </div>
    <div class="select_button">
        <table class="table table_bg table_style">
          <thead>
            <tr class="background_bg">
                <th style="border-bottom: 0px !important;">YEAR</th>
                <th style="border-bottom: 0px !important;">NO OF TASKS</th>
                <th style="border-bottom: 0px !important;">NO OF TASKS COMPLETED</th>
                <th style="border-bottom: 0px !important;">NO OF DONOT COMPLETE TASKS</th>
                <th style="border-bottom: 0px !important;">NO OF TASKS INCOMPLETE</th>
                <th style="border-bottom: 0px !important;">DATES (DD-MMMM-YYYY)</th>

            </tr>
          </thead>
          <tbody>
            <?php
            $end_date = $year->end_date;
            $arraydate = array();
            if(count($weeklist)){
              foreach($weeklist as $week){
                $explode = explode('-',$end_date);
                $start_date = $explode[1].'-'.$explode[2].'-'.$explode[0];
                array_push($arraydate,$end_date);
                $end_date = date('Y-m-d', strtotime("+7 days", strtotime($end_date)));
              }
            }
            if(count($weeklist)){
                $count = count($arraydate) - 1;
              foreach($weeklist as $key => $week){
                $task_count = DB::table('task')->where('task_week',$week->week_id)->count();
                $task_completed = DB::table('task')->where('task_week',$week->week_id)->where('task_status',1)->count();
                $task_donot_completed = DB::table('task')->where('task_week',$week->week_id)->where('task_status',0)->where('task_complete_period',1)->count();
                $task_incomplete = DB::table('task')->where('task_week',$week->week_id)->where('task_status',0)->where('task_complete_period',0)->count();
            ?>
            <tr>
                <td><a href="<?php echo URL::to('user/select_week/'.base64_encode($week->week_id))?>" class="btn">Week <?php echo $week->week?></a></td>
                <td><label><?php echo $task_count; ?></label></td>
                <td><label><?php echo $task_completed; ?></label></td>
                <td><label><?php echo $task_donot_completed; ?></label></td>
                <td><label><?php echo $task_incomplete; ?></label></td>
                <td><label><?php echo date('d-F-Y', strtotime($arraydate[$count])); ?></label></td>
            </tr>
            <?php 
              $count = $count - 1;
            } } ?>
          </tbody>            
        </table>
        
    </div>
</div>
@stop