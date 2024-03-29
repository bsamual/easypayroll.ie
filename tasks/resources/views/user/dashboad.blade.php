@extends('userheader')
@section('content')
<!-- <link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">


<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script> -->

<!-- 
<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>
<script src="http://html2canvas.hertzen.com/dist/html2canvas.js"></script> -->

<!-- <link rel="stylesheet" href="<?php echo URL::to('assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('assets/js/lightbox/jquery.colorbox.js'); ?>"></script> -->
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

  /*background: #f5f5f5 !important;*/
  background: url('<?php echo URL::to('assets/images/gbs-and-co-bubbles.jpg')?>')!important;

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
.fa-refresh{
  position: absolute;
  right: 28px;
  font-size: 25px;
  color: #fff;
  top: 7px;
}
</style>
<div class="modal fade email_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document" style="width:70%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title" style="font-weight:700;font-size:20px">Settings</h4>
          </div>
          <div class="modal-body" style="clear:both"> 
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item active">
                <a class="nav-link active" id="first-tab" data-toggle="tab" href="#first" role="tab" aria-controls="first" aria-selected="true">Practice</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Email Settings</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Manage Year</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Manage Users</a>
              </li>
            </ul>
            <?php
            $settings = DB::table('settings')->where('source','practice')->first();
            if(count($settings))
            {
              $settingsval = unserialize($settings->settings);
            }
            ?>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade active in" id="first" role="tabpanel" aria-labelledby="first-tab">
                <form id="form-validation-email" name="form-validation" method="POST" action="<?php echo URL::to('user/update_practice_setting'); ?>">
                  <div class="col-lg-12 text-left padding_00">
                    <div class="col-lg-12" style="padding: 25px;">
                      <div class="col-lg-6">
                        <div class="form-group">
                            <label>Practice Code:</label>
                             <input id="validation-email"
                                   class="form-control"
                                   placeholder="Enter Practice Code"
                                   value="GBS"
                                   name="practice_code"
                                   type="text"
                                   disabled>                                
                        </div> 
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                            <label>Practice Name:</label>
                            <input id="validation-practice_name"
                                   class="form-control"
                                   placeholder="Enter Practice Name"
                                   value="<?php echo (isset($settingsval['practice_name']))?$settingsval['practice_name']:''; ?>"
                                   name="practice_name"
                                   type="text"
                                   required>                                
                        </div> 
                      </div> 
                      <div class="col-lg-6">
                        <div class="form-group">
                            <label>Address 1:</label>
                            <input id="validation-address_1"
                                   class="form-control"
                                   placeholder="Enter Address 1"
                                   value="<?php echo (isset($settingsval['address_1']))?$settingsval['address_1']:''; ?>"
                                   name="address_1"
                                   type="text"
                                   required>                                
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                            <label>Address 2:</label>
                            <input id="validation-address_2"
                                   class="form-control"
                                   placeholder="Enter Address 2"
                                   value="<?php echo (isset($settingsval['address_2']))?$settingsval['address_2']:''; ?>"
                                   name="address_2"
                                   type="text">                                
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                            <label>Address 3:</label>
                            <input id="validation-address_3"
                                   class="form-control"
                                   placeholder="Enter Address 3"
                                   value="<?php echo (isset($settingsval['address_3']))?$settingsval['address_3']:''; ?>"
                                   name="address_3"
                                   type="text">                                
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                            <label>Address 4:</label>
                            <input id="validation-address_4"
                                   class="form-control"
                                   placeholder="Enter Address 4"
                                   value="<?php echo (isset($settingsval['address_4']))?$settingsval['address_4']:''; ?>"
                                   name="address_4"
                                   type="text">                                
                        </div> 
                      </div>
                      <div class="col-lg-6"> 
                        <div class="form-group">
                            <label>Link 1:</label>
                            <input id="validation-link_1"
                                   class="form-control"
                                   placeholder="Enter Link 1"
                                   value="<?php echo (isset($settingsval['link_1']))?$settingsval['link_1']:''; ?>"
                                   name="link_1"
                                   type="text"
                                   required>                                
                        </div>  
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                            <label>Link 2:</label>
                            <input id="validation-link_2"
                                   class="form-control"
                                   placeholder="Enter Link 2"
                                   value="<?php echo (isset($settingsval['link_2']))?$settingsval['link_2']:''; ?>"
                                   name="link_2"
                                   type="text">                                
                        </div>  
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                            <label>Link 3:</label>
                            <input id="validation-link_3"
                                   class="form-control"
                                   placeholder="Enter Link 3"
                                   value="<?php echo (isset($settingsval['link_3']))?$settingsval['link_3']:''; ?>"
                                   name="link_3"
                                   type="text">                                
                        </div> 
                      </div>
                      <div class="col-lg-6"> 
                        <div class="form-group">
                            <label>Phone Number:</label>
                            <input id="validation-phone_no"
                                   class="form-control"
                                   placeholder="Enter Phone Number"
                                   value="<?php echo (isset($settingsval['phone_no']))?$settingsval['phone_no']:''; ?>"
                                   name="phone_no"
                                   type="text"
                                   required>                                
                        </div>  
                      </div>
                    </div>
                    <div class="col-lg-12" style="padding: 25px;text-align:right">
                      <input type="button" class="common_black_button" data-dismiss="modal" aria-label="Close" value="Cancel">
                      <input type="submit" class="common_black_button" id="park_submit" value="Submit">
                    </div>
                  </div>
                </form>
              </div>
              <div class="tab-pane fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                <form id="form-validation-email" name="form-validation" method="POST" action="<?php echo URL::to('user/update_email_setting'); ?>">
                  <div class="col-lg-6 text-left padding_00">
                    <div class="col-lg-12" style="padding: 25px;">
                      <div>
                        <?php
                        if(Session::has('emailmessage')) { ?>
                            <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('emailmessage'); ?></p>
                        <?php }
                        ?>
                      </div>
                        <div class="form-group">
                            <label>Admin Email ID</label>
                             <input id="validation-email"
                                   class="form-control"
                                   placeholder="Enter Email"
                                   value="<?php echo $admin_details->email; ?>"
                                   name="email"
                                   type="text"
                                   required >                                
                        </div> 
                        <div class="form-group">
                            <label>Rct Item Delete Email ID</label>
                            <input id="validation-cc-email"
                                   class="form-control"
                                   placeholder="Enter Delete Email ID"
                                   value="<?php echo $admin_details->delete_email; ?>"
                                   name="deleteemail"
                                   type="text"
                                   required>                                
                        </div>  
                        <div class="form-group">
                            <label>RCT CC Email ID</label>
                            <input id="validation-cc-email"
                                   class="form-control"
                                   placeholder="Enter RCT CC Email ID"
                                   value="<?php echo $admin_details->cc_email; ?>"
                                   name="ccemail"
                                   type="text"
                                   required>                                 
                        </div>
                        <div class="form-group">
                            <label>Taskmanager CC Email ID</label>
                            <input id="validation-cc-email"
                                   class="form-control"
                                   placeholder="Enter Taskmanager CC Email ID"
                                   value="<?php echo $admin_details->task_cc_email; ?>"
                                   name="taskccemail"
                                   type="text"
                                   required>                                 
                        </div>
                        <div class="form-group">
                            <label>P30 CC Email ID</label>
                            <input id="validation-cc-email"
                                   class="form-control"
                                   placeholder="Enter Taskmanager CC Email ID"
                                   value="<?php echo $admin_details->p30_cc_email; ?>"
                                   name="p30ccemail"
                                   type="text"
                                   required>                                 
                        </div>
                        <div class="form-group">
                            <label>Client Management CC Email ID</label>
                            <input id="validation-cc-email"
                                   class="form-control"
                                   placeholder="Enter Taskmanager CC Email ID"
                                   value="<?php echo $admin_details->cm_cc_email; ?>"
                                   name="cmccemail"
                                   type="text"
                                   required>                                 
                        </div>
                        <div class="form-group">
                            <label>VAT CC Email ID</label>
                            <input id="validation-cc-email"
                                   class="form-control"
                                   placeholder="Enter VAT CC Email ID"
                                   value="<?php echo $admin_details->vat_cc_email; ?>"
                                   name="vatccemail"
                                   type="text"
                                   required>                                 
                        </div>
                    </div>
                    <div class="col-lg-12" style="padding: 25px;text-align:right">
                      <input type="button" class="common_black_button" data-dismiss="modal" aria-label="Close" value="Cancel">
                      <input type="submit" class="common_black_button" id="park_submit" value="Submit">
                    </div>
                  </div>
                </form>
              </div>
              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <iframe src="{{URL::to('user/manage_task')}}" style="width:100%;height:800px"></iframe>
              </div>
              <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">
                <iframe src="{{URL::to('user/manage_user')}}" style="width:100%;height:800px"></iframe>
              </div>
            </div> 
            
          </div>
          <div class="modal-footer" style="clear:both">
            
          </div>
          
        </div>
  </div>
</div>
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
    <h4 class="col-lg-12 padding_00 new_main_title">Dashboard</h4>
  </div>
    <div class="row" id="dashboard_tbody">      
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle cmsystem">
            <div class="content" id="cm_tiles">
              <div class="title">Cm System</div>
              <div class="ul_list">
                <ul>
                
                  <li>Total  Clients : </li>
                  <li>Active  Clients : </li>
                  <li><a href="<?php echo URL::to('user/client_account_review'); ?>" style="color:#fff">Client Account Review</a></li>
                </ul>
              </div>
            </div>
            <div class="icon">
              <a href="javascript:" class="fa fa-refresh load_system" data-element="cm" data-toggle="tooltip" data-placement="bottom" title="Load Data"></a> 
              <img src="<?php echo URL::to('assets/images/icon_cm_system.jpg')?>">
            </div>            
          </div>
          <div class="more morecmsystem">
                <a href="<?php echo URL::to('user/client_management'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle week">
            <div class="content" id="week_tiles">
              <div class="title">Weekly Payroll</div>
              <div class="ul_list">
              
                <div class="sub-title">Week #,</div>
                <ul>
                  <li>Completed Tasks : </li>
                  <li>Donot Complete tasks : </li>
                  <li>Incomplete Tasks : </li>
                </ul>
              </div>
            </div>
            <div class="icon">
              <a href="javascript:" class="fa fa-refresh load_system" data-element="week"  data-toggle="tooltip" data-placement="bottom" title="Load Data"></a> 
              <img src="<?php echo URL::to('assets/images/icon_week_task.jpg')?>">
            </div>            
          </div>
          <div class="more moreweek">
                <a href="<?php echo URL::to('user/manage_week'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle month">
            <div class="content" id="month_tiles">
              <div class="title">Monthly Payroll</div>
              <div class="ul_list">
                <div class="sub-title">Month #,</div>
                <ul>
                  <li>Completed Tasks :</li>
                  <li>Donot Complete tasks :</li>
                  <li>Incomplete Tasks :</li>
                </ul>
              </div>
            </div>
            <div class="icon">
              <a href="javascript:" class="fa fa-refresh load_system" data-element="month"  data-toggle="tooltip" data-placement="bottom" title="Load Data"></a>
              <img src="<?php echo URL::to('assets/images/icon_month_task.jpg')?>">
            </div>            
          </div>
          <div class="more moremonth">
                <a href="<?php echo URL::to('user/manage_month'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle p30">
            <div class="content" id="p30_tiles">
              <div class="title">P30 system</div>
              <div class="ul_list">
              
                <div class="sub-title">Month #,</div>
                <ul>
                  <li>Completed Tasks :</li>
                  <li>Incomplete Tasks :</li>
                </ul>
              </div>
            </div>
            <div class="icon">
              <a href="javascript:" class="fa fa-refresh load_system" data-element="p30"  data-toggle="tooltip" data-placement="bottom" title="Load Data"></a>
              <img src="<?php echo URL::to('assets/images/icon_p30.jpg')?>">
            </div>            
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
            <div class="content" id="vat_tiles">
              <div class="title">VAT system</div>
              
              <div class="ul_list">                
                <ul>
                  <li>Disabled Clients:</li>
                  <li>Clients With Email:</li>
                  <li>Clients Without Email:</li>
                  <li>Self Managed:</li>
                </ul>
              </div>
            </div>
            <div class="icon">
              <a href="javascript:" class="fa fa-refresh load_system" data-element="vat"  data-toggle="tooltip" data-placement="bottom" title="Load Data"></a>
              <img src="<?php echo URL::to('assets/images/icon_vat.jpg')?>">
            </div>            
          </div>
          <div class="more morevat">
                <a href="<?php echo URL::to('user/vat_clients'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>

      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle infile">
            <div class="content" id="infile_tiles">
              <div class="title">In Files System</div>
              
              <div class="ul_list">                
                <ul>
                  <li>No. of Clients with In Files :</li>
                  <li>No. of Complete In Files :</li>
                  <li>No. of InComplete In Files :</li>
                </ul>
              </div>
            </div>
            <div class="icon">
              <a href="javascript:" class="fa fa-refresh load_system" data-element="infile"  data-toggle="tooltip" data-placement="bottom" title="Load Data"></a>
              <img src="<?php echo URL::to('assets/images/infile_icon.jpg')?>">
            </div>            
          </div>
          <div class="more moreinfile">
                <a href="<?php echo URL::to('user/in_file_advance'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>

      <div class="col-lg-6">
        <div class="dashboard">
          <div class="dashboard_signle yearend">
            <div class="content" id="yearend_tiles" style="width:100%">
              <div class="title">Yearend System</div>
                <div class="ul_list">      
                  <div class="col-md-4 col-lg-4">
                    <ul>
                    <li class="lifirst" style="text-decoration: underline;">Year : ####</li>
                    <li>Not Started :</li>
                    <li>Inprogress :</li>
                    <li>Completed :</li>
                    </ul>
                  </div>
                </div>
            </div> 
            <a href="javascript:" class="fa fa-refresh load_system" data-element="yearend"  data-toggle="tooltip" data-placement="bottom" title="Load Data"></a>        
          </div>
          <div class="more moreyearend">
                <a href="<?php echo URL::to('user/year_end_manager'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
      <div class="col-lg-3">
        <div class="dashboard">
          <div class="dashboard_signle crm">
            <div class="content crm_content" id="crm_tiles">
              <div class="title">Client Request Manager</div>
              
              <div class="ul_list">                
                <ul>
                  <li>Total Requests :</li>
                  <li>Total Outstanding Requests :</li>
                  <li>Total Awaiting Approval :</li>
                </ul>
              </div>
            </div>
            <a href="javascript:" class="fa fa-refresh load_system" data-element="crm" data-toggle="tooltip" data-placement="bottom" title="Load Data"></a>
          </div>
          <div class="more morecrm">
                <a href="<?php echo URL::to('user/client_request_system'); ?>"><i class="fa fa-arrow-circle-right" aria-hidden="true"></i> GO</a>
            </div>
        </div>
      </div>
    </div>
    <input type="button" class="common_black_button load_all_dashboard_tiles" value="Load All System Data"  style="position: fixed;right:25px;bottom:74px;">
    <input type="button" class="common_black_button settings_email" value="Settings"  style="position: fixed;right:210px;bottom:74px;">
</div>
<div class="modal_load"></div>
<script>
$(window).click(function(e) {
  if($(e.target).hasClass('load_system'))
  {
    $("body").addClass("loading");
    var system = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/load_dashboard_tiles'); ?>",
      type:"post",
      data:{system:system},
      success:function(result)
      {
        $("#"+system+"_tiles").html(result);
        $("body").removeClass("loading");
      }
    }); 
  }
  if($(e.target).hasClass('settings_email'))
  {
    $(".email_settings_modal").modal("show");
  }
  if($(e.target).hasClass('load_all_dashboard_tiles'))
  {
    $("body").addClass("loading");
    $.ajax({
      url:"<?php echo URL::to('user/load_all_dashboard_tiles'); ?>",
      type:"post",
      success:function(result)
      {
        $("#dashboard_tbody").html(result);
        $("body").removeClass("loading");
      }
    });
  }
}); 

$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>

@stop