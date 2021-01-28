@extends('userheader')
@section('content')
<style>
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    999999999999;
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
body{background:url('<?php echo URL::to('assets/images/weekly_bg.jpg')?>') no-repeat center top !important; 
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
    $current_week = DB::table('week')->orderBy('week_id','desc')->first();
    ?>
    
  </div>
    <div class="select_button">
        <ul>
            <?php
            if(count($yearlist)){
              foreach($yearlist as $year){
                if($year->year_status == 0){
            ?>
              <li><a href="<?php echo URL::to('user/week_manage/'.base64_encode($year->year_id))?>"><?php echo $year->year_name?></a></li>
            <?php
                }
              }
            }
            ?>            
        </ul>
        <p style="float:left;clear: both;margin-top:15px"><a href="<?php echo URL::to('user/select_week/'.base64_encode($current_week->week_id)); ?>" class="common_black_button">Open Current Week</a></p>
        <a href="javascript:" class="common_black_button current_payroll_list" style="clear: both;font-size: 16px;font-weight: 600;color: #fff; text-shadow: 0px 1px 2px #000; position: absolute;bottom:8%;text-align: left;left: 15px;z-index: 9999999999999;">Current Payroll List</a>
        <p style="clear: both;font-size: 18px;font-weight: 800;color: #fff; text-shadow: 0px 1px 2px #000; position: absolute;bottom:8%;text-align: center;width: 98%;">You are In Weekly Payroll Task Management</p>
    </div>
</div>
<div class="modal_load"></div>
<script>
  $(window).click(function(e) {
    if($(e.target).hasClass('current_payroll_list'))
    {
      $("body").addClass("loading");
        $.ajax({
          url:"<?php echo URL::to('user/current_payroll_list'); ?>",
          type:"post",
          success:function(result)
          {
            SaveToDisk("<?php echo URL::to('papers'); ?>/current_payroll_lists.csv",'current_payroll_lists.csv');
            $("body").removeClass("loading");
          }
        })
    }
  });
</script>
@stop