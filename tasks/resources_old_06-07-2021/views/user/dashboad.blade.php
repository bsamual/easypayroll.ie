@extends('userheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">


<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script>


<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>
<script src="http://html2canvas.hertzen.com/dist/html2canvas.js"></script>

<link rel="stylesheet" href="<?php echo URL::to('assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('assets/js/lightbox/jquery.colorbox.js'); ?>"></script>



<style>
  .dashboard .dashboard_signle .crm_content{
    width:100%;
  }
.dashboard .crm {
    background: #b373a5;
}
.dashboard .morecrm {
    background: #b53098;
}
body{

  background: #f5f5f5 !important;

}
.dashboard .yearend
{
  background: #2e6da4;
}
.dashboard .moreyearend
{
  background: #004b8c;
}
.lifirst:before
{
      content: none !important;
}
.tasks_drop{border-radius: 0px; background:none;}


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

.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover

{

  z-index: 0 !important;

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
                url(<?php echo URL::to('assets/images/loading.gif'); ?>) 50% 50% no-repeat;

}



.ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 13px; }
.ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff}
.ok_button:focus{background: #000; text-decoration: none; color: #fff}


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
.ui-widget{z-index: 999999999}
</style>










<div class="content_section">
  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>

    
    <?php } ?>
    <?php
    if(Session::has('error-message')) { ?>
        <p class="alert alert-danger"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('error-message'); ?></p>

    
    <?php } ?>
    </div>
  <div class="page_title">
    Dashboard
  </div>
    <div class="row">      
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle cmsystem">
            <div class="content">
              <div class="title">Cm System</div>
              <div class="ul_list">
                <ul>
                <?php 
                $total_clients = DB::table('cm_clients')->count();
                $active_cm_clients = DB::table('cm_clients')->where('active',1)->count();
                ?>
                  <li>Total  Clients : <?php echo $total_clients; ?></li>
                  <li>Active  Clients : <?php echo $active_cm_clients; ?></li>
                  <li><a href="<?php echo URL::to('user/client_account_review'); ?>" style="color:#fff">Client Account Review</a></li>
                </ul>
              </div>
            </div>
            <div class="icon"><img src="<?php echo URL::to('assets/images/icon_cm_system.jpg')?>"></div>            
          </div>
          <div class="more morecmsystem">
                <a href="<?php echo URL::to('user/client_management'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle week">
            <div class="content">
              <div class="title">Weekly Payroll</div>
              <div class="ul_list">
              <?php 
              $current_week = DB::table('week')->orderBy('week_id','desc')->first();
              $current_year = DB::table('year')->where('year_id',$current_week->year)->first();
              $no_of_tasks = DB::table('task')->where('task_week',$current_week->week_id)->count();
              $week_completed = DB::table('task')->where('task_week',$current_week->week_id)->where('task_status',1)->count();
              $week_donot_completed = DB::table('task')->where('task_week',$current_week->week_id)->where('task_status',0)->where('task_complete_period',1)->count();
              $week_incompleted = DB::table('task')->where('task_week',$current_week->week_id)->where('task_status',0)->where('task_complete_period',0)->count();
              ?>
                <div class="sub-title">Week #<?php echo $current_week->week; ?>, <?php echo $current_year->year_name; ?> - <?php echo $no_of_tasks; ?> Tasks</div>
                <ul>
                  <li>Completed Tasks : <?php echo $week_completed; ?></li>
                  <li>Donot Complete tasks : <?php echo $week_donot_completed; ?></li>
                  <li>Incomplete Tasks : <?php echo $week_incompleted; ?></li>
                </ul>
              </div>
            </div>
            <div class="icon"><img src="<?php echo URL::to('assets/images/icon_week_task.jpg')?>"></div>            
          </div>
          <div class="more moreweek">
                <a href="<?php echo URL::to('user/manage_week'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle month">
            <div class="content">
              <div class="title">Monthly Payroll</div>
              <?php 
              $current_month = DB::table('month')->orderBy('month_id','desc')->first();
              $current_year = DB::table('year')->where('year_id',$current_month->year)->first();
              $no_of_tasks_month = DB::table('task')->where('task_month',$current_month->month_id)->count();
              $week_completed_month = DB::table('task')->where('task_month',$current_month->month_id)->where('task_status',1)->count();
              $week_donot_completed_month = DB::table('task')->where('task_month',$current_month->month_id)->where('task_status',0)->where('task_complete_period',1)->count();
              $week_incompleted_month = DB::table('task')->where('task_month',$current_month->month_id)->where('task_status',0)->where('task_complete_period',0)->count();
              ?>
              <div class="ul_list">
                <div class="sub-title">Month #<?php echo $current_month->month; ?>, <?php echo $current_year->year_name; ?> - <?php echo $no_of_tasks_month; ?> Tasks</div>
                <ul>
                  <li>Completed Tasks : <?php echo $week_completed_month; ?></li>
                  <li>Donot Complete tasks : <?php echo $week_donot_completed_month; ?></li>
                  <li>Incomplete Tasks : <?php echo $week_incompleted_month; ?></li>
                </ul>
              </div>
            </div>
            <div class="icon"><img src="<?php echo URL::to('assets/images/icon_month_task.jpg')?>"></div>            
          </div>
          <div class="more moremonth">
                <a href="<?php echo URL::to('user/manage_month'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle p30">
            <div class="content">
              <div class="title">P30 system</div>
              <div class="ul_list">
              <?php 
              $current_p30_month = DB::table('p30_month')->orderBy('month_id','desc')->first();
              $current_year = DB::table('year')->where('year_id',$current_p30_month->year)->first();
              $no_of_tasks_p30_month = DB::table('p30_task')->where('task_month',$current_p30_month->month_id)->count();
              $week_completed_p30_month = DB::table('p30_task')->where('task_month',$current_p30_month->month_id)->where('task_status',1)->count();
              $week_incompleted_p30_month = DB::table('p30_task')->where('task_month',$current_p30_month->month_id)->where('task_status',0)->count();
              ?>
                <div class="sub-title">Month #<?php echo $current_p30_month->month; ?>, <?php echo $current_year->year_name; ?> - <?php echo $no_of_tasks_p30_month; ?> Tasks</div>
                <ul>
                  <li>Completed Tasks : <?php echo $week_completed_p30_month; ?></li>
                  <li>Incomplete Tasks : <?php echo $week_incompleted_p30_month; ?></li>
                </ul>
              </div>
            </div>
            <div class="icon"><img src="<?php echo URL::to('assets/images/icon_p30.jpg')?>"></div>            
          </div>
          <div class="more morep30">
                <a href="<?php echo URL::to('user/p30'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle vat">
            <div class="content">
              <div class="title">VAT system</div>
              <?php 
              $disabled_clients = DB::table('vat_clients')->where('status',1)->count();
              $clients_email = DB::table('vat_clients')->where('status',0)->where('pemail','!=', '')->where('self_manage','no')->count();
              $clients_without_email = DB::table('vat_clients')->where('status',0)->where('pemail', '')->where('self_manage','no')->count();
              $self_manage = DB::table('vat_clients')->where('status',0)->where('self_manage','yes')->count();
              ?>
              <div class="ul_list">                
                <ul>
                  <li>Disabled Clients : <?php echo $disabled_clients; ?></li>
                  <li>Clients With Email : <?php echo $clients_email; ?></li>
                  <li>Clients Without Email: <?php echo $clients_without_email; ?></li>
                  <li>Self Managed  : <?php echo $self_manage; ?></li>
                </ul>
              </div>
            </div>
            <div class="icon"><img src="<?php echo URL::to('assets/images/icon_vat.jpg')?>"></div>            
          </div>
          <div class="more morevat">
                <a href="<?php echo URL::to('user/vat_clients'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>

      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle infile">
            <div class="content">
              <div class="title">In Files System</div>
              <?php 
              //DB::enableQueryLog();
              $infile_client = DB::table('in_file')->select('id','client_id')->where('client_id','!=','')->get();
              $array_clientid = array();
              $file_count = 0;
              if(count($infile_client))
              {
              	foreach($infile_client as $infile)
              	{
              		if(!in_array($infile->client_id,$array_clientid))
              		{
              			$check_attachment = DB::table('in_file_attachment')->where('file_id',$infile->id)->count();
	              		if($check_attachment > 0)
	              		{
	              			$file_count++;
	              			array_push($array_clientid, $infile->client_id);
	              		}
              		}
              	}
              }
              //$laQuery = DB::getQueryLog();
             
              $infile_complete = DB::table('in_file')->where('status', 1)->count();
              $infile_incomplete = DB::table('in_file')->where('status', 0)->count();
              ?>
              <div class="ul_list">                
                <ul>
                  <li>No. of Clients with In Files : <?php echo $file_count; ?></li>
                  <li>No. of Complete In Files : <?php echo $infile_complete; ?></li>
                  <li>No. of InComplete In Files : <?php echo $infile_incomplete; ?></li>
                </ul>
              </div>
            </div>
            <div class="icon"><img src="<?php echo URL::to('assets/images/infile_icon.jpg')?>"></div>            
          </div>
          <div class="more moreinfile">
                <a href="<?php echo URL::to('user/in_file_advance'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>

      <div class="col-lg-5">
        <div class="dashboard">
          <div class="dashboard_signle yearend">
            <div class="content" style="width:100%">
              <div class="title">Yearend System</div>
                <div class="ul_list">      
                  <?php 
                  $yearend_year = DB::table('year_end_year')->orderBy('id','desc')->limit(3)->get();
                  if(count($yearend_year))
                  {
                    foreach($yearend_year as $year)
                    {
                      $started = DB::table('year_client')->where('year',$year->year)->where('status',0)->count();
                      $in_progress = DB::table('year_client')->where('year',$year->year)->where('status',1)->count();
                      $completed = DB::table('year_client')->where('year',$year->year)->where('status',2)->count();
                      ?>
                        <div class="col-md-4 col-lg-4">
                          <ul>
                          <li class="lifirst" style="text-decoration: underline;">Year : <?php echo $year->year; ?></li>
                          <li>Not Started :  <?php echo $started; ?> Clients</li>
                          <li>Inprogress : <?php echo $in_progress; ?> Clients</li>
                          <li>Completed : <?php echo $completed; ?> Clients</li>
                          </ul>
                        </div>
                      <?php
                    }
                    if(count($yearend_year) == 2)
                    {
                    ?>
                      <div class="col-md-4 col-lg-4">
                        <ul>
                          <li class="lifirst" style="text-decoration: underline;">Year : 2017</li>
                          <li>No Records found</li>
                        </ul>
                      </div>
                    <?php
                    }
                    elseif(count($yearend_year) == 1)
                    { ?>
                      <div class="col-md-4 col-lg-4">
                        <ul>
                          <li class="lifirst" style="text-decoration: underline;">Year : 2017</li>
                          <li>No Records found</li>
                        </ul>
                      </div>
                      <div class="col-md-4 col-lg-4">
                        <ul>
                          <li class="lifirst" style="text-decoration: underline;">Year : 2016</li>
                          <li>No Records found</li>
                        </ul>
                      </div>
                    <?php } 
                  }
                  ?>
                </div>
            </div>         
          </div>
          <div class="more moreyearend">
                <a href="<?php echo URL::to('user/year_end_manager'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle crm">
            <div class="content crm_content">
              <div class="title">Client Request Manager</div>
              <?php
                $i=1;
                $clientlist = DB::table('cm_clients')->select('client_id', 'firstname', 'surname', 'company', 'status', 'active', 'id')->orderBy('id','asc')->get();
                $countoutstanding = 0;
                $awaiting_request_count = 0;
                $request_count_count = 0;
                if(count($clientlist)){              
                  foreach($clientlist as $key => $client){
                    
                   /* $outstanding_count = DB::table('request_client')->where('client_id', $client->client_id)->where('status', 0)->count();*/
                    $awaiting_request = DB::table('request_client')->where('client_id', $client->client_id)->where('status', 0)->count();
                    $request_count = DB::table('request_client')->where('client_id', $client->client_id)->where('status', 1)->count();

                    $awaiting_request_count = $awaiting_request_count + $awaiting_request;
                    $request_count_count = $request_count_count + $request_count;

                    $get_req = DB::table('request_client')->where('client_id', $client->client_id)->where('status', 1)->get();
                    if(count($get_req))
                    {
                      foreach($get_req as $req)
                      {
                          $check_received_purchase = DB::table('request_purchase_invoice')->where('request_id',$req->request_id)->where('status',0)->count();
                          $check_received_purchase_attached = DB::table('request_purchase_attached')->where('request_id',$req->request_id)->where('status',0)->count(); 

                          $check_received_sales = DB::table('request_sales_invoice')->where('request_id',$req->request_id)->where('status',0)->count();
                          $check_received_sales_attached = DB::table('request_sales_attached')->where('request_id',$req->request_id)->where('status',0)->count();

                          $check_received_bank = DB::table('request_bank_statement')->where('request_id',$req->request_id)->where('status',0)->count();

                          $check_received_cheque = DB::table('request_cheque')->where('request_id',$req->request_id)->where('status',0)->count();
                          $check_received_cheque_attached = DB::table('request_cheque_attached')->where('request_id',$req->request_id)->where('status',0)->count();

                          $check_received_others = DB::table('request_others')->where('request_id',$req->request_id)->where('status',0)->count();

                          $check_purchase = DB::table('request_purchase_invoice')->where('request_id',$req->request_id)->count();
                          $check_purchase_attached = DB::table('request_purchase_attached')->where('request_id',$req->request_id)->count(); 

                          $check_sales = DB::table('request_sales_invoice')->where('request_id',$req->request_id)->count();
                          $check_sales_attached = DB::table('request_sales_attached')->where('request_id',$req->request_id)->count();

                          $check_bank = DB::table('request_bank_statement')->where('request_id',$req->request_id)->count();

                          $check_cheque = DB::table('request_cheque')->where('request_id',$req->request_id)->count();
                          $check_cheque_attached = DB::table('request_cheque_attached')->where('request_id',$req->request_id)->count();

                          $check_others = DB::table('request_others')->where('request_id',$req->request_id)->count();

                          $countval_not_received = $check_received_purchase + $check_received_purchase_attached + $check_received_sales + $check_received_sales_attached + $check_received_bank + $check_received_cheque + $check_received_cheque_attached + $check_received_others;

                          $countval = $check_purchase + $check_purchase_attached + $check_sales + $check_sales_attached + $check_bank + $check_cheque + $check_cheque_attached + $check_others;

                          if($countval_not_received != 0)
                          {
                            $countoutstanding++;
                          }
                      }
                    }
                  }
                }
              ?>
              <div class="ul_list">                
                <ul>
                  <li>Total Requests : <?php echo $request_count_count; ?></li>
                  <li>Total Outstanding Requests : <?php echo $countoutstanding; ?></li>
                  <li>Total Awaiting Approval : <?php echo $awaiting_request_count; ?></li>
                </ul>
              </div>
            </div>
                       
          </div>
          <div class="more morecrm">
                <a href="<?php echo URL::to('user/client_request_system'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>





    </div>
</div>


@stop