@extends('userheader')
@section('content')
<style>
body{background:url('<?php echo URL::to('assets/images/monthly_bg.jpg')?>') no-repeat center top !important; 
  -webkit-background-size: cover !important;
  -moz-background-size: cover !important;
  -o-background-size: cover !important;
  background-size: cover !important;}
</style>
<style>
.page_title{color:#fff; text-shadow: 0px 1px 2px #000}
</style>
<div class="content_section">
  <div class="page_title">
    SELECT YEAR
    <?php
    $current_month = DB::table('month')->orderBy('month_id','desc')->first();
    ?>
    <a href="<?php echo URL::to('user/select_month/'.base64_encode($current_month->month_id)); ?>" class="common_black_button" style="float:right">Open Current Month</a>
  </div>
    <div class="select_button">
        <ul>
            <?php
            if(count($yearlist)){
              foreach($yearlist as $year){
                if($year->year_status == 0){
            ?>
              <li><a href="<?php echo URL::to('user/month_manage/'.base64_encode($year->year_id))?>"><?php echo $year->year_name?></a></li>
            <?php
                }
              }
            }
            ?>            
        </ul>
        <p style="clear: both;font-size: 18px;font-weight: 800;color: #fff; text-shadow: 0px 1px 2px #000; position: absolute;bottom:8%;text-align: center;
    width: 98%;">You are in Monthly Payroll Task Management</p>
    </div>
</div>
@stop