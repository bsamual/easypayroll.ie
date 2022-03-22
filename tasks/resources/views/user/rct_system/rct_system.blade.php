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
/*body{
  background: #f5f5f5 !important;
}*/
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

<img id="coupon" />


<div class="content_section" style="margin-bottom:200px">
  <div class="page_title" style="z-index:999;">
      <h4 class="col-lg-12 padding_00 new_main_title">
                RCT Manager               
            </h4>
    </div>
  <div class="row">
        <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
     <?php }   
    ?>
    </div> 

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
      <ul class="nav nav-tabs" style="margin-top: 20px;">
        <li class="nav-item active">
          <a class="nav-link" href="<?php echo URL::to('user/rct_system'); ?>">RCT Manager</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="<?php echo URL::to('user/rct_summary'); ?>">RCT Summary</a>
        </li>
      </ul>
    </div>

<div class="row" style="background: #fff; padding-bottom: 20px;">
            <div class="col-lg-1" style="padding-left: 40px; line-height: 35px; width: 160px; margin-top: 18px; ">
                Active Month
            </div>
            <div class="col-lg-2 padding_00" style="margin-top: 20px;">
              <select class="form-control select_active_month_rct">
                <?php
                  $current_month = date('Y-m');

                $prevdate = date("Y-m-05", strtotime("-1 months"));
                $prev_date2 = date('Y-m', strtotime($prevdate));
                  $active_drop='<option value="'.$current_month.'">'.date('M-Y', strtotime($current_month)).'</option>';
                  for($i=0;$i<=22;$i++)
                  {
                    $month = $i + 1;
                    $newdate = date("Y-m-05", strtotime("-".$month." months"));
                    $formatted_date = date('M-Y', strtotime($newdate));
                    $formatted_date2 = date('Y-m', strtotime($newdate));

                    if($prev_date2 == $formatted_date2){ $checked = 'selected'; } else { $checked = ''; }
                    $active_drop.='<option value="'.$formatted_date2.'" '.$checked.'>'.$formatted_date.'</option>';
                  }
                  echo $active_drop;
                ?>
              </select>
            </div>
            <div class="col-lg-2" style="float:right;text-align: right;margin-right: 12px; margin-top: 30px; background: #fff">
              <a href="javascript:" class="common_black_button rct_settings">Settings</a>
            </div>
            </div>
</div>

<div class="col-lg-12 padding_00">
  <div class="table-responsive" style="max-width: 100%; float: left;margin-bottom:30px;">
  
  

<style type="text/css">
.refresh_icon{margin-left: 10px;}
.refresh_icon:hover{text-decoration: none;}
.datepicker-only-init_date_received, .auto_save_date{cursor: pointer;}
.bootstrap-datetimepicker-widget{width: 300px !important;}
.image_div_attachments P{width: 100%; height: auto; font-size: 13px; font-weight: normal; color: #000;}
</style>
<div class="modal fade rct_settings_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-lg" role="document" style="width:60%;">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title job_title">RCT Settings</h4>
          </div>
          <div class="modal-body" style="clear:both">  
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item active">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Email Salutation</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Letter Pad Background</a>
              </li>
            </ul>
            <div class="tab-content" id="myTabContent">
              <div class="tab-pane fade active in" id="home" role="tabpanel" aria-labelledby="home-tab">
                
                <style>
                .cursor_sort{cursor: pointer;}
                </style>
                  <div class="col-lg-12 padding_00" style="margin-top: 20px;">
                      <div class="col-lg-12 padding_00">                    
                          <div class="margin-bottom-50">
                              <table class="table" >
                                  <thead class="table-inverse">
                                  <tr style="background: #fff;">
                                      <th width="5%" style="text-align: left;">S.No</th>
                                      <th>Name</th>
                                      <th style="text-align: center;">Description</th>
                                      <th style="text-align: center;" width="15%">Action</th>
                                  </tr>
                                  </thead>                            
                                  <tbody>
                                    <?php
                                      $i=1;
                                      if(count($userlist)){              
                                        foreach($userlist as $user){
                                    ?>
                                    <tr>            
                                      <td><?php echo $i;?></td>            
                                      <td align="left"><?php echo $user->name; ?></td>
                                      <td align="left"><?php echo $user->description; ?></td>
                                      <td align="center">
                                          <a href="#" id="<?php echo base64_encode($user->id); ?>" class="editclass"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                                      </td>
                                    </tr>
                                    <?php
                                        $i++;
                                        }              
                                      }
                                      else{
                                        echo'<tr><td colspan="5" align="center">Empty</td></tr>';
                                      }
                                    ?>                                                       
                                  </tbody>
                              </table>
                          </div>
                      </div>
                  </div> 

                <input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
                <input type="hidden" name="first_sortoptions" id="first_sortoptions" value="asc">
                <input type="hidden" name="last_sortoptions" id="last_sortoptions" value="asc">
                <input type="hidden" name="tax_sortoptions" id="tax_sortoptions" value="asc">
                <input type="hidden" name="email_sortoptions" id="email_sortoptions" value="asc">
                <input type="hidden" name="seconday_sortoptions" id="seconday_sortoptions" value="asc">
              </div>
              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="col-lg-12 padding_00">
                        <div class="col-lg-12 padding_00">                    
                            <div class="margin-bottom-50">
                                <table class="table">
                                    <thead class="table-inverse">
                                    <tr style="background: #fff;">
                                        <th width="5%" style="text-align: left;">S.No</th>
                                        <th>Name</th>
                                        <th>Email Salution</th>
                                        <th style="text-align: center;">image</th>
                                        <th style="text-align: center;" width="15%">Action</th>
                                    </tr>
                                    </thead>                            
                                    <tbody id="sort_maintable">
                                      <?php
                                        $i=1;
                                        if(count($letterpad)){              
                                          foreach($letterpad as $user){
                                      ?>
                                      <tr>            
                                        <td><?php echo $i;?></td>            
                                        <td align="left"><?php echo $user->name; ?></td>
                                        <td align="left"><?php echo $user->salution; ?></td>
                                        <td align="left">
                                          <img width="150px;" src="<?php echo URL::to('uploads/letterpad/'.$user->image); ?>" >                                                                    
                                        </td>
                                        <td align="center">
                                            <a href="#" id="<?php echo base64_encode($user->id); ?>" class="editclass_letter"><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
                                        </td>
                                      </tr>
                                      <?php
                                          $i++;
                                          }              
                                        }
                                        else{
                                          echo'<tr><td colspan="5" align="center">Empty</td></tr>';
                                        }
                                      ?>                                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> 
                    <input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
                    <input type="hidden" name="first_sortoptions" id="first_sortoptions" value="asc">
                    <input type="hidden" name="last_sortoptions" id="last_sortoptions" value="asc">
                    <input type="hidden" name="tax_sortoptions" id="tax_sortoptions" value="asc">
                    <input type="hidden" name="email_sortoptions" id="email_sortoptions" value="asc">
                    <input type="hidden" name="seconday_sortoptions" id="seconday_sortoptions" value="asc">
              </div>
            </div>
          </div>
          <div class="modal-footer" style="clear:both">  
            <input type="button" class="common_black_button" id="link_infile_button" value="Submit">
          </div>
        </div>
  </div>
</div>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;z-index:99999999999">
    <div class="modal-dialog modal-sm" role="document">
      <form id="form-validation-edit" action="<?php echo URL::to('user/update_rctsalution'); ?>" method="post" class="editsp">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Edit Salution</h4>
            </div>
            <div class="modal-body">
              <div class="form-group">
                  <input type="text"
                    class="form-control name_class" readonly>
              </div>

              <div class="form-group">
                  <textarea class="form-control desc_class"
                         name="description"
                         placeholder="Enter Salution"
                         type="text"
                         data-validation="[NOTEMPTY]"
                         data-validation-message="Enter Salution" style="height: 150px;"></textarea>
                  <input type="hidden" class="name_id" name="id">
              </div>            
            </div>
            <div class="modal-footer">
              <input type="submit" value="Update" class="common_black_button">
            </div>
          </div>
      </form>
    </div>
  </div>
<div class="modal fade bs-example-modal-sm_letter" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;z-index:99999999999">
  <div class="modal-dialog modal-sm" role="document">
    <form id="form-validation-edit" action="<?php echo URL::to('user/update_rctletterpad'); ?>" method="post" enctype="multipart/form-data" class="editsp_letter">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Letter Background</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
                <input type="text"
                  class="form-control name_class_letter" readonly>
            </div>

            <div class="form-group">
              <img src="<?php echo URL::to('uploads/letterpad/no-image.jpg'); ?>" id="img_id" width="100%">
            </div>
            <div class="form-group">
                <textarea class="form-control salution_class" style="height: 150px;" name="salution" data-validation="[NOTEMPTY]" data-validation-message="Enter Salution">></textarea>
            </div>

            <div class="form-group">
                <input class="form-control image_class"
                       name="letterpadimage"                       
                       type="file"
                       data-validation="[NOTEMPTY]"
                       data-validation-message="Enter Image">
                <input type="hidden" class="name_id_letter" name="id">
            </div>            
          </div>
          <div class="modal-footer">
            <input type="submit" value="Update" class="common_black_button">
          </div>
        </div>
    </form>
  </div>
</div>
<table class="display nowrap fullviewtablelist own_table_white" id="ta_expand" width="100%" style="max-width: 100%; margin-top:0px !important; ">
                        <thead>
                        <tr style="background: #fff;">
                             <th width="2%" style="text-align: left;">S.No</th>
                            <th style="text-align: left;">Client ID</th>
                            <th style="text-align: left;">Company</th>
                            <th style="text-align: left;">First Name</th>
                            <th style="text-align: left;">Surname</th>
                            <th style="text-align: left;">Month</th>
                            <th style="text-align: left;">Deduction</th>
                            <th style="text-align: left;">Gross</th>
                            <th style="text-align: left;">Net</th>
                            <th style="text-align: left;">Count</th>
                            <!-- <th style="text-align: left;">Email</th> -->
                            <th style="text-align: center;">Action</th>
                        </tr>
                        </thead>                            
                        <tbody id="clients_tbody">
                        <?php
                            $ival=1;
                            if(count($clientlist)){              
                              foreach($clientlist as $client){
                                if($client->active == 2)
                                {
                                  $color='color:#f00;';
                                }
                                else{
                                  $color="";
                                }
                          ?>
                            <tr class="edit_task tr_client_td_<?php echo $client->client_id; ?>">
                                <td style="<?php echo $color; ?>"><?php echo $ival; ?></td>
                                <td align="left"><a href="javascript:" data-element="<?php echo URL::to('user/rct_client_manager/'.$client->client_id)?>" class="view_rct_manager" style="<?php echo $color; ?>"><?php echo $client->client_id; ?></a></td>
                                <td align="left"><a href="javascript:" data-element="<?php echo URL::to('user/rct_client_manager/'.$client->client_id)?>" class="view_rct_manager" style="<?php echo $color; ?>"><?php echo ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company; ?></a></td>
                                <td align="left"><a href="javascript:" data-element="<?php echo URL::to('user/rct_client_manager/'.$client->client_id)?>" class="view_rct_manager" style="<?php echo $color; ?>"><?php echo $client->firstname; ?></a></td>
                                <td align="left"><a href="javascript:" data-element="<?php echo URL::to('user/rct_client_manager/'.$client->client_id)?>" class="view_rct_manager" style="<?php echo $color; ?>"><?php echo $client->surname; ?></a></td>
                                <td align="left">
                                    <select class="form-control select_active_month" data-element="<?php echo $client->client_id; ?>" style="<?php echo $color; ?>">
                                      <option value="">Select Month</option>
                                      <?php
                                        $current_month = date('Y-m');

                                        $prevdate = date("Y-m-05", strtotime("-1 months"));
                                        $prev_date2 = date('Y-m', strtotime($prevdate));
                                        $active_drop='<option value="'.$current_month.'">'.date('M-Y', strtotime($current_month)).'</option>';
                                        for($i=0;$i<=22;$i++)
                                        {
                                          $month = $i + 1;
                                          $newdate = date("Y-m-05", strtotime("-".$month." months"));
                                          $formatted_date = date('M-Y', strtotime($newdate));
                                          $formatted_date2 = date('Y-m', strtotime($newdate));

                                          if($prev_date2 == $formatted_date2){ $checked = 'selected'; } else { $checked = ''; }
                                          $active_drop.='<option value="'.$formatted_date2.'" '.$checked.'>'.$formatted_date.'</option>';
                                        }
                                        echo $active_drop;
                                      ?>
                                    </select>
                                </td>
                                <td align="left" class="deduction_clientid" style="<?php echo $color; ?>">
                                  <?php
                                    $deductionsum = 0;
                                    $grosssum = 0;
                                    $netsum = 0;
                                    $icount = 0;

                                    $rct_output = '';
                                    $rctsubmission = DB::table('rct_submission')->where('client_id', $client->client_id)->first();
                                    if(count($rctsubmission)){
                                      $start_date = unserialize($rctsubmission->start_date);
                                      $grossval = unserialize($rctsubmission->value_gross);
                                      $netval = unserialize($rctsubmission->value_net);
                                      $deductionval = unserialize($rctsubmission->deduction);

                                      $prevdate = date("Y-m-05", strtotime("-1 months"));
                                      $prev_date2 = date('Y-m', strtotime($prevdate));
                                      $data = array();
                                      if(count($start_date))
                                      {
                                        foreach($start_date as $key => $start)
                                        {
                                          $date = substr($start,0,7);
                                          if($date == $prev_date2)
                                          {
                                            if(isset($data[$date]))
                                            {
                                              $implodeval = implode(",",$data[$date]);                  
                                              $combineval = $implodeval.','.$key;
                                              $data[$date] = explode(',',$combineval);

                                            }
                                            else{
                                              $data[$date] = array($key);
                                            }
                                          }
                                        }
                                      }
                                      krsort($data);
                                      if(count($data))
                                      {
                                        foreach($data as $key_date => $dataval)
                                        {
                                          $grosssum = 0;
                                          $netsum = 0;
                                          $deductionsum = 0;
                                          $icount = 0;
                                          if(count($dataval))
                                          {
                                            foreach($dataval as $sumvalue)
                                            {
                                              if(isset($grossval[$sumvalue]))
                                              {
                                                $grosssum = $grosssum + $grossval[$sumvalue];
                                              }
                                              else{
                                                $grosssum = $grosssum + 0;
                                              }

                                              if(isset($netval[$sumvalue]))
                                              {
                                                $netsum = $netsum + $netval[$sumvalue];
                                              }
                                              else{
                                                $netsum = $netsum + 0;
                                              }

                                              if(isset($deductionval[$sumvalue]))
                                              {
                                                $deductionsum = $deductionsum + $deductionval[$sumvalue];
                                              }
                                              else{
                                                $deductionsum = $deductionsum + 0;
                                              }
                                              $icount++;
                                            }
                                          }
                                        }
                                      }
                                    }
                                    echo ($deductionsum)?number_format_invoice_without_decimal($deductionsum):'-';
                                    ?>
                                </td>
                                <td align="left" class="gross_clientid" style="<?php echo $color; ?>">
                                  <?php echo ($grosssum)?number_format_invoice_without_decimal($grosssum):'-'; ?>
                                </td>
                                <td align="left" class="net_clientid" style="<?php echo $color; ?>"><?php echo ($netsum)?number_format_invoice_without_decimal($netsum):'-'; ?></td>
                                <td align="left" class="count_clientid" style="<?php echo $color; ?>"><?php echo ($icount)?$icount:'-'; ?></td>
                                <!-- <td align="left" class="emails_clientid">
                                  <?php
                                  $emails = DB::table('rct_submission_email')->where('client_id',$client->client_id)->where('start_date',$prev_date2)->get();
                                  if(count($emails))
                                  {
                                    foreach($emails as $email)
                                    {
                                      echo '<p style="'.$color.'">'.date('F d, Y', strtotime($email->email_sent)).'</p>';
                                    }
                                  }
                                  ?>
                                </td> -->
                                <td align="center"><a href="<?php echo URL::to('user/rct_liability_assessment/'.$client->client_id)?>" class="fa fa-eye view_liability_assessment" style="<?php echo $color; ?>"></a></td>
                            </tr>
                            <?php
                              $ival++;
                              }              
                            }
                            if($ival == 1)
                            {
                              echo'<tr>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td align="center">Empty</td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  </tr>';
                            }
                          ?> 
                        </tbody>
                    </table>
</div>
</div>
</div>
    <!-- End  -->
<div class="main-backdrop"><!-- --></div>
<div id="print_image">
    
</div>
<div id="report_pdf_type_two" style="display:none">
  <style>
  .table_style {
      width: 100%;
      border-collapse:collapse;
      border:1px solid #c5c5c5;
  }
  </style>
  <table class="table_style">
    <thead>
      <tr>
      <td style="text-align: left;border:1px solid #000;">#</td>
      <td style="text-align: left;border:1px solid #000;">Client Id</td>
      <td style="text-align: left;border:1px solid #000;">Company</td>
      <td style="text-align: left;border:1px solid #000;">First Name</td>
      <td style="text-align: left;border:1px solid #000;">Surname</td>
      <td style="text-align: left;border:1px solid #000;">Client Source</td>
      <td style="text-align: left;border:1px solid #000;">Date Client Since</td>
      <td style="text-align: left;border:1px solid #000;">Client Identity</td>      
      <td style="text-align: left;border:1px solid #000;">Bank Account</td>
      <td style="text-align: left;border:1px solid #000;">File Review</td>
      <td style="text-align: left;border:1px solid #000;">Risk Category</td>
      </tr>
    </thead>
    <tbody id="report_pdf_type_two_tbody">

    </tbody>
  </table>
</div>



<div id="report_pdf_type_two_invoice" style="display:none">
  <style>
  .table_style {
      width: 100%;
      border-collapse:collapse;
      border:1px solid #c5c5c5;
  }
  </style>

  <h3 id="pdf_title_inivoice" style="width: 100%; text-align: center; margin: 15px 0px; float: left;">List of Invoices issued to <span class="invoice_filename"></span></h3>  

  <table class="table_style">
    <thead>
      <tr>
      <th width="2%" style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">S.No</th>
      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Invoice ID</th>
      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Date</th>
      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Client ID</th>
      <th style="text-align: left; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px ">Company Name</th>
      <th style="text-align: right; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px ">Net</th>
      <th style="text-align: right; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">VAT</th>
      <th style="text-align: right; border-bottom:1px solid #ccc; border-right:1px solid #ccc; padding-left:5px">Gross</th>
      </tr>
    </thead>
    <tbody id="report_pdf_type_two_tbody_invoice">

    </tbody>
  </table>
</div>





<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">



<!-- Page Scripts -->
<script>
$(".editclass_letter").click( function(){
  var editid = $(this).attr("id");
  console.log(editid);
  $.ajax({
      url: "<?php echo URL::to('user/edit_rctletterpad') ?>"+"/"+editid,
      dataType:"json",
      type:"post",
      success:function(result){
         $(".bs-example-modal-sm_letter").modal("toggle");
         $(".editsp_letter").show();
         $(".name_class_letter").val(result['name']);
         $(".salution_class").val(result['salution']);
         $(".name_id_letter").val(result['id']);         
         $("#img_id").attr("src",result['image']);

    }
  })
});





$(".addclass").click( function(){
  $(".addsp").show();
  $(".editsp").hide();
});
$(".editclass").click( function(){
  var editid = $(this).attr("id");
  console.log(editid);
  $.ajax({
      url: "<?php echo URL::to('user/edit_rctsalution') ?>"+"/"+editid,
      dataType:"json",
      type:"post",
      success:function(result){
         $(".bs-example-modal-sm").modal("toggle");
         $(".editsp").show();
         $(".addsp").hide();
         $(".name_class").val(result['name']);
         $(".desc_class").val(result['description']);         
         $(".name_id").val(result['id']);
    }
  })
});
$(window).click(function(e) {
  var ascending = false;
  if($(e.target).hasClass('delete_user'))
  {
    var r = confirm("Are You Sure want to delete this Subcontractor?");
    if (r == true) {
       
    } else {
        return false;
    }
  }
});
$('#form-validation').validate({
    submit: {
        settings: {
            inputContainer: '.form-group',
            errorListClass: 'form-control-error',
            errorClass: 'has-danger'
        }
    }
});
$('#form-validation-edit').validate({
    submit: {
        settings: {
            inputContainer: '.form-group',
            errorListClass: 'form-control-error',
            errorClass: 'has-danger'
        }
    }
});
$(function(){
    $('#ta_expand').DataTable({
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
$(window).change(function(e){
  if($(e.target).hasClass('select_active_month_rct'))
  {
    $("body").addClass("loading");
    var value = $(e.target).val();
    $("#ta_expand").dataTable().fnDestroy();
    $.ajax({
      url:"<?php echo URL::to('user/set_rct_active_month'); ?>",
      type:"post",
      data:{date:value},
      success: function(result)
      {
        $("#clients_tbody").html(result);
        $('#ta_expand').DataTable({
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
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('select_active_month'))
  {
    $("body").addClass("loading");
    var value = $(e.target).val();
    var client_id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/set_rct_active_month_individual'); ?>",
      type:"post",
      data:{date:value,client_id:client_id},
      dataType:"json",
      success: function(result)
      {
        $(".tr_client_td_"+client_id).find(".deduction_clientid").html(result['deduction']);
        $(".tr_client_td_"+client_id).find(".gross_clientid").html(result['gross']);
        $(".tr_client_td_"+client_id).find(".net_clientid").html(result['net']);
        $(".tr_client_td_"+client_id).find(".count_clientid").html(result['count']);
        $(".tr_client_td_"+client_id).find(".emails_clientid").html(result['email_text']);

        $("body").removeClass("loading");
      }
    })
  }
})
$(window).click(function(e) {
  if($(e.target).hasClass('rct_settings'))
  {
    $(".rct_settings_modal").modal("show");
  }
  if($(e.target).hasClass('view_liability_assessment'))
  {
    e.preventDefault();
    var href= $(e.target).attr("href");
    var active_month = $(e.target).parents("tr").find(".select_active_month").val();
    window.location.replace(href+'?active_month='+active_month);
  }
  if($(e.target).hasClass('view_rct_manager'))
  {
    e.preventDefault();
    var href= $(e.target).attr("data-element");
    var active_month = $(e.target).parents("tr").find(".select_active_month").val();
    window.location.replace(href+'?active_month='+active_month);
  }
})
</script>


@stop