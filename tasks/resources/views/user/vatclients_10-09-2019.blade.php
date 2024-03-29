@extends('userheader')
@section('content')
<style>
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
.fa-sort{
  cursor: pointer;
}
.modal_load {
    display:    none;
    position:   fixed;
    z-index:    99999;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 ) 
                url(<?php echo URL::to('assets/loading.gif'); ?>) 
                50% 50% 
                no-repeat;
}
.error_ref{
  color: #f00;
    font-size: 9px;
    position: absolute;
    left: 55.5%;
}
body.loading {
    overflow: hidden;
}
body.loading .modal_load {
    display: block;
}
.select_button table tbody tr td a{
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}
.select_button table tbody tr td a:hover{
    background: #fff;
    text-align: center;
    padding: 8px 12px;
    color: #000;
    float: left;
    width: 100%;
}
.select_button table tbody tr td label{
    color:#fff !important;
    font-weight:800;
    margin-top:6px;
}

.btn{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}
.btn:hover{
    background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: left;
    width: 100%;
}

.btn_add
{
  background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: right;
}
.btn_add:hover
{
  background: #000;
    text-align: center;
    padding: 8px 12px;
    color: #fff;
    float: right;
}
.drop_down{
  width: 100%;
margin-top: 2px;
background: none !important;
color: #000 !important;
border-bottom: 1px solid #dedada;
}
.dropdown-menu{
  right: 0px;
left: 79%;
top: 85%;
}
.color_pallete_red{
  padding:18px 17px;
  background: #f00;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
}
.color_pallete_green{
  padding:18px 17px;
  background: green;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
}
.color_pallete_yellow{
  padding:18px 17px;
  background: yellow;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
}
.color_pallete_purple{
  padding:18px 17px;
  background: purple;
      border-radius: 6px;
    margin-left: 10px;
    float: right;
}
.popover-title
{
  font-weight:800;
}
.popover-content{
  display:none !important;
}
#alert_modal{
  z-index:9999999 !important;
}
#alert_modal_edit{
  z-index:9999999 !important;
}
</style>

<style>
.body_bg{
    background: #0e95db;
}
.ui-widget{z-index: 999999999}
.form-control[readonly]{background: #eaeaea !important}
</style>
<?php

if(!empty($_GET['import_type']))
{
  if(!empty($_GET['round']))
  {
    
    $filename = $_GET['filename'];
    $height = $_GET['height'];
    $highestrow = $_GET['highestrow'];
    $round = $_GET['round'];
    ?>
    <div class="upload_img" style="width: 100%;height: 100%;z-index:1"><p class="upload_text">Uploading Please wait...</p><img src="<?php echo URL::to('assets/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">Finished Uploading <?php echo $height; ?> of <?php echo $highestrow; ?></p></div>

    <script>
      $(document).ready(function() {
        var base_url = "<?php echo URL::to('/'); ?>";
        window.location.replace(base_url+'/user/import_form_one?filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&import_type=1');
      })
    </script>
    <?php

  }
}
if(!empty($_GET['compare_type']))
{
  if(!empty($_GET['round']))
  {
    
    $filename = $_GET['filename'];
    $height = $_GET['height'];
    $highestrow = $_GET['highestrow'];
    $round = $_GET['round'];
    ?>
    <div class="upload_img" style="width: 100%;height: 100%;z-index:1"><p class="upload_text">Uploading Please wait...</p><img src="<?php echo URL::to('assets/loading.gif'); ?>" width="100px" height="100px"><p class="upload_text">Finished Comparing <?php echo $height; ?> of <?php echo $highestrow; ?></p></div>

    <script>
      $(document).ready(function() {
        var base_url = "<?php echo URL::to('/'); ?>";
        window.location.replace(base_url+'/user/compare_form_one?filename=<?php echo $filename; ?>&height=<?php echo $height; ?>&round=<?php echo $round; ?>&highestrow=<?php echo $highestrow; ?>&compare_type=1');
      })
    </script>
    <?php

  }
}
if(Session::has('message_import')) { ?>
  <div class="modal fade import_excel_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Clients yet to update from Excel sheet</h4>
            <a href="<?php echo URL::to('user/import_sessions?round=1'); ?>" class="btn btn-primary" style="float:right;width: 15%;margin-top: -32px;margin-right: 36px;"> UPLOAD </a>
          </div>
          <div class="modal-body">
            <table class="table">
              <thead>
                <th style="text-align: left">S.No</th>
                <th style="text-align: left">Client Name</th>
                <th style="text-align: left">Tax No</th>
              </thead>
              <tbody>
                <?php
                  if(count(Session::get('insertrows')))
                  {
                    $i = 1;
                    foreach(Session::get('insertrows') as $rows)
                    {
                      ?>
                        <tr>
                          <td><?php echo $i; ?> </td>
                          <td><?php echo $rows['name']; ?></td>
                          <td><?php echo $rows['taxnumber']; ?></td>
                        </tr>
                      <?php
                      $i++;
                    }
                  }
                  else{
                    ?>
                      <tr>
                        <td colspan="8">No Data to Upload</td>
                      </tr>
                    <?php
                  }
                ?>
              </tbody>
            </table>
          </div>
      </div>
    </div>
  </div>
  <?php
}
?>
<div class="modal fade" id="alert_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="z-index: 999999">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Alert</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Primary Email Address?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="pemail_update" class="pemail_update" id="pemail_yes" value="1"><label for="pemail_yes">Yes</label>
            <input type="radio" name="pemail_update" class="pemail_update" id="pemail_no" value="0"><label for="pemail_no">No</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Secondary Email Address?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="semail_update" class="semail_update" id="semail_yes" value="1"><label for="semail_yes">Yes</label>
            <input type="radio" name="semail_update" class="semail_update" id="semail_no" value="0"><label for="semail_no">No</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Salutation?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="salutation_update" class="salutation_update" id="salutation_yes" value="1"><label for="salutation_yes">Yes</label>
            <input type="radio" name="salutation_update" class="salutation_update" id="salutation_no" value="0"><label for="salutation_no">No</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary common_black_button" id="alert_submit" value="Submit">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="alert_modal_edit" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog" role="document" style="z-index: 999999">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel">Alert</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Primary Email Address?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="pemail_update_edit" class="pemail_update_edit" id="pemail_yes_edit" value="1"><label for="pemail_yes_edit">Yes</label>
            <input type="radio" name="pemail_update_edit" class="pemail_update_edit" id="pemail_no_edit" value="0"><label for="pemail_no_edit">No</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Secondary Email Address?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="semail_update_edit" class="semail_update_edit" id="semail_yes_edit" value="1"><label for="semail_yes_edit">Yes</label>
            <input type="radio" name="semail_update_edit" class="semail_update_edit" id="semail_no_edit" value="0"><label for="semail_no_edit">No</label>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8">
            <label>Do you want to update the Salutation?</label>
          </div>
          <div class="col-md-4">
            <input type="radio" name="salutation_update_edit" class="salutation_update_edit" id="salutation_yes_edit" value="1"><label for="salutation_yes_edit">Yes</label>
            <input type="radio" name="salutation_update_edit" class="salutation_update_edit" id="salutation_no_edit" value="0"><label for="salutation_no_edit">No</label>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <input type="button" class="btn btn-primary common_black_button" id="alert_submit_edit" value="Submit">
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="vat_notifications_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="margin-top:6%;width:75%">
      <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Vat Notifications Screen</h4>
          </div>
          <div class="modal-body">
            <iframe src="<?php echo URL::to('user/vat_notifications'); ?>" id="iframe_reload" width="100%" height="500px" style="border:0px;"></iframe>
          </div>
      </div>
  </div>
</div>
<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <form id="form-validation-edit" action="<?php echo URL::to('user/update_vat_clients'); ?>" method="post" class="editsp">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Edit Clients</h4>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">      
                    <label>Company Name : </label>          
                      <input class="form-control client_search_class_edit"
                             name=""
                             placeholder="Choose Company Name"
                             type="text" required>
                      <input type="hidden" id="client_search_edit" name="cmclientid" />
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">      
                    <label>Client Name : </label>          
                      <input type="hidden" name="client_id" id="client_id" value="">
                      <input class="form-control name_class firstname_class_edit"
                             name="name"
                             placeholder="Enter Client Name"
                             type="text" required>
                  </div>
                  <label class="error_client_name"></label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">     
                  <label>Enter Taxnumber : </label>           
                      <input class="form-control taxnumber_class"
                             name="taxnumber"
                             placeholder="Enter Taxnumber"
                             type="text" readonly required>
                  </div>  
              </div>
              <div class="col-md-6">
                  <div class="form-group">  
                  <label>Enter Primary Email ID : </label>               
                      <input class="form-control pemail_class primaryemail_class_edit"
                             name="pemail"
                             placeholder="Enter Primary Email ID"
                             type="email" required>
                  </div>
                  <label class="error_pemail"></label>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">       
                  <label>Enter Secondary Email ID : </label>         
                      <input class="form-control semail_class_edit"
                             name="semail"
                             placeholder="Enter Secondary Email Id"
                             type="email">
                      
                  </div>
                  <label class="error_semail"></label>
              </div>
              <div class="col-md-6">
                  <div class="form-group">    
                  <label>Enter Salutation : </label>                 
                      <textarea class="form-control salutation_class_edit"
                             name="salutation"
                             placeholder="Enter Salutation" required></textarea>                
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                  <div class="form-group">     
                  <label>Self Manage : </label>                 
                            <input type="radio" name="self" value="yes" class="self_manage_class" id="self_manage_class_yes" required >
                            <label>Yes</label>
                        
                            <input type="radio" name="self" value="no" class="self_manage_class" id="self_manage_class_no" required>
                            <label>No</label>
                    
                  </div>
              </div>
              <div class="col-md-6">
                  <div class="form-group">     
                  <label>Always Nil : </label>                 
                            <input type="radio" name="always_nil" value="yes" class="always_nil_class" id="always_nil_class_yes" required>
                            <label>Yes</label>
                        
                            <input type="radio" name="always_nil" value="no" class="always_nil_class" id="always_nil_class_no" required>
                            <label>No</label>
                    
                  </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="hidden_client_id_edit" id="hidden_client_id_edit" value="">
            <input type="hidden" name="hidden_client_salutation_edit" id="hidden_client_salutation_edit" value="">
            <input type="hidden" value="" name="id" class="name_id">
            <input type="button" value="Update Clients" class="btn_add" id="edit_client_details">
          </div>
        </div>
    </form>
  </div>
</div>
<div class="modal fade addclass_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <form id="form-validation-add" action="<?php echo URL::to('user/add_vat_clients'); ?>" method="post">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Add Clients</h4>
          </div>
          <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">      
                  <label>Company Name : </label>          
                    <input class="form-control client_search_class"
                           name=""
                           placeholder="Choose Company Name"
                           type="text" required>
                    <input type="hidden" id="client_search" name="cmclientid" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">      
                  <label>Client Name : </label>          
                    <input class="form-control name_class_add firstname_class"
                           name="name"
                           placeholder="Enter Client Name"
                           type="text" required>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">     
                  <label>Enter Taxnumber : </label>           
                  <input class="form-control taxnumber_class_add tax_reg1class"
                       name="taxnumber"
                       placeholder="Enter Taxnumber"
                       type="text" required>
                </div>     
                <label class="error_taxnumber_add"></label>       
            </div>
            <div class="col-md-6">
                <div class="form-group">  
                <label>Enter Primary Email ID : </label>               
                    <input class="form-control pemail_class_add primaryemail_class"
                           name="pemail"
                           placeholder="Enter Primary Email ID"
                           type="email" required>
                </div>
                <label class="error_pemail_add"></label>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">       
                <label>Enter Secondary Email ID : </label>         
                    <input class="form-control semail_class_add"
                           name="semail"
                           placeholder="Enter Secondary Email Id"
                           type="email">
                    
                </div>
                <label class="error_semail_add"></label>     
            </div>
            <div class="col-md-6">
                <div class="form-group">    
                <label>Enter Salutation : </label>                 
                    <textarea class="form-control salutation_class_add"
                           name="salutation"
                           placeholder="Enter Salutation" required></textarea>                
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">     
                <label>Tax Type : </label>                 
                          <input class="form-control tax_class_add"
                           name="tax_type"
                           placeholder="Enter Tax Type"
                           type="text" required>
                </div>  
            </div>
            <div class="col-md-6">
                <div class="form-group">     
                    <label>Document Type : </label>                 
                    <input class="form-control document_class_add" name="document_type" placeholder="Enter Document Type" type="text" required>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">     
                    <label>Period : </label>                 
                    <input class="form-control period_class_add" name="period_add" placeholder="Enter Period" type="text" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">     
                    <label>Due Date : </label>                 
                    <input class="form-control due_class_add datepicker" name="due_add" placeholder="Enter Due Date" type="text" required>
                </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4">
                <div class="form-group">     
                <label>Self Manage : </label>                 
                          <input type="radio" name="self" value="yes" id="self_manage_class_add_yes" required>
                          <label>Yes</label>
                      
                          <input type="radio" name="self" value="no" id="self_manage_class_add_no" required>
                          <label>No</label>
                  
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">     
                <label>Always Nil : </label>                 
                          <input type="radio" name="always_nil" value="yes" id="always_nil_class_add_yes" required>
                          <label>Yes</label>
                      
                          <input type="radio" name="always_nil" value="no" id="always_nil_class_add_no" required>
                          <label>No</label>
                  
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">     
                <label>ROS filer : </label>                 
                          <input type="radio" name="ros_filer" value="yes" id="ros_filer_class_add_yes" required>
                          <label>Yes</label>
                      
                          <input type="radio" name="ros_filer" value="no" id="ros_filer_class_add_no" required>
                          <label>No</label>
                  
                </div>
            </div>
          </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="hidden_client_id" id="hidden_client_id" value="">
            <input type="hidden" name="hidden_client_salutation" id="hidden_client_salutation" value="">
            <input type="submit" value="Add Clients" class="btn_add" id="add_client_details">
          </div>
        </div>
    </form>
  </div>
</div>
<div class="modal fade import_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
    <form id="form-validation-import" action="<?php echo URL::to('user/import_form'); ?>" method="post" enctype="multipart/form-data">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Import Clients</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">                
                <input class="form-control import_file" name="import_file" type="file" required>
            </div>
          </div>
          <div class="modal-footer">
            <input type="submit" value="Import" class="btn">

            <p style="text-align: justify;margin-top:55px">Select a revenue extract file for VAT returns due, and import into the system.  This will update the list of client in the VAT system that have VAT returns due.  Once imported you need to link these clients, who will be highlighted with the broken red chain link to a client in the CM System</p>
          </div>
        </div>
    </form>
  </div>
</div>
<div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" id="client_email_sents_modal" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Email Sent Date and Time</h4>
      </div>
      <div class="modal-body">
          <table class="table">
            <thead>
              <th>S.No</th>
              <th>Date</th>
              <th>Time</th>
            </thead>
            <tbody id="client_email_sents">

            </tbody>
          </table>
      </div>
      <div class="modal-footer">
        <a href="javascript:" class="btn btn-primary saveaspdf" data-element="">Save as Pdf</a>
      </div>
    </div>
  </div>
</div>
<div class="modal fade compare_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" style="margin-top: 5%;">
  <div class="modal-dialog modal-sm" role="document" style="width:30%">
    <form id="form-validation-compare" action="<?php echo URL::to('user/compare_form'); ?>" method="post" enctype="multipart/form-data">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">Notify Client of VAT returns due From This CSV File</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">                
                <input class="form-control compare_file" name="compare_file" type="file" required>
            </div>
          </div>
          <div class="modal-footer">
            <input type="submit" value="Review VAT returns and Proceed to Notify" class="btn">
            <p style="text-align: justify; margin-top:55px">Select a Revenue Extract file and import it, to proceed to notify clients that their VAT returns are due</p>
          </div>
        </div>
    </form>
  </div>
</div>
<div class="content_section">
<?php
  if(Session::has('message')) { ?>
         <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
<?php } ?>
  <div class="message_edit">
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
  <div class="page_title">
    <h5 style="color:#fff;text-decoration: underline;text-decoration-color: #fff;">VAT Clients Imported in to VAT Management System</h5>
    <h3 style="color:#fff;margin-bottom:-10px">VAT Management System</h3>
  </div>
  </div>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
      <div class="select_button">
        <ul style="float: right;">
          <li><a href="javascript:" class="addclass">Add New Client</a></li>
          <li><a href="javascript:" class="import_button">Import</a></li>
          <li>
            <a href="javascript:" class="compare_button" type="button" >VAT Notification</button></a>
            
          </li>
        </ul>
      </div><br/>
      <div>
      <?php
        $red = DB::table('vat_clients')->where('status',1)->count();
        $green = DB::table('vat_clients')->where('status',0)->where('pemail','!=', '')->where('self_manage','no')->count();
        $yellow = DB::table('vat_clients')->where('status',0)->where('pemail', '')->where('self_manage','no')->count();
        $purple = DB::table('vat_clients')->where('status',0)->where('self_manage','yes')->count();
      ?>
        <a href="javascript:" class="color_pallete_purple thumbnail" data-toggle="popover" title="<?php echo $purple; ?> Clients With Self Manage" data-placement="left" data-trigger="focus"></a>
        <a href="javascript:" class="color_pallete_yellow thumbnail" data-toggle="popover" title="<?php echo $yellow; ?> Clients Without Primary Email Address" data-placement="left" data-trigger="focus"></a>
        <a href="javascript:" class="color_pallete_green thumbnail" data-toggle="popover" title="<?php echo $green; ?> Clients With Primary Email Address" data-placement="left" data-trigger="focus"></a>
        <a href="javascript:" class="color_pallete_red thumbnail" data-toggle="popover" title="<?php echo $red; ?> Clients Disabled" data-placement="left" data-trigger="focus"></a>
      </div>
  </div>
    <div class="select_button">
        <table class="table table_bg">
          <thead>
            <tr class="background_bg">
                <th width="5%" style="text-align: left;">S.No <i class="fa fa-sort sno_sort" aria-hidden="true"></th>
                <th style="text-align: left; border:1px solid #000;">Client Name <i class="fa fa-sort client_sort" aria-hidden="true"></th>
                <th style="text-align: left; border:1px solid #000">Tax Regn./Trader No <i class="fa fa-sort tax_sort" aria-hidden="true"></th>
                <th style="text-align: left; border:1px solid #000">Email <i class="fa fa-sort pemail_sort" aria-hidden="true"></th>
                <th style="text-align: left; border:1px solid #000">Secondary Email <i class="fa fa-sort semail_sort" aria-hidden="true"></th>                            
                <th style="text-align: left; border:1px solid #000;width:8%">Action</th>

            </tr>
          </thead>
          <tbody id="task_body">
            <?php
                $i=1;
                if(count($clientlist)){              
                  foreach($clientlist as $client){                
                  if($client->status == 1) { $fontcolor = 'red'; }
                  elseif($client->status == 0 && $client->pemail != '' && $client->self_manage == 'no') { $fontcolor = 'green'; }
                  elseif($client->status == 0 && $client->pemail == '' && $client->self_manage == 'no') { $fontcolor = 'yellow'; }
                  elseif($client->status == 0 && $client->self_manage == 'yes') { $fontcolor = 'purple'; }
                  else{$fontcolor = '#fff';}
              ?>
            <tr class="task_tr task_<?php echo $client->client_id; ?>" style="text-align:center">
                <td class="sno_sort_val" width="5%" style="text-align: left;"><label style="color:<?php echo $fontcolor; ?> !important;"><?php echo $i; ?></label></th>
                <td class="client_sort_val" style="text-align: left; border:1px solid #000;"><label style="color:<?php echo $fontcolor; ?> !important;"><?php echo $client->name; ?></label></td>
                <td class="tax_sort_val" style="text-align: left; border:1px solid #000""><label style="color:<?php echo $fontcolor; ?> !important;"><?php echo $client->taxnumber; ?></label></td>
                <td class="pemail_sort_val" style="text-align: left; border:1px solid #000""><label style="color:<?php echo $fontcolor; ?> !important;"><?php echo $client->pemail; ?></label></td>
                <td class="semail_sort_val" style="text-align: left; border:1px solid #000""><label style="color:<?php echo $fontcolor; ?> !important;"><?php echo $client->semail; ?></label></td>                            
                <td style="text-align: left; border:1px solid #000"">
                    <a href="javascript:" style="width:auto; float: none; padding: 5px;" id="<?php echo base64_encode($client->client_id); ?>" class="editclass" title="Edit Client"><i class="fa fa-pencil-square editclass" id="<?php echo base64_encode($client->client_id); ?>" aria-hidden="true"></i></a>

                              &nbsp; 
                    <a href="javascript:" style="width:auto; float: none; padding: 5px;" id="<?php echo base64_encode($client->client_id); ?>" class="email_sent" title="Email Sent Date & Time"><i class="fa fa-envelope email_sent" id="<?php echo base64_encode($client->client_id); ?>" aria-hidden="true"></i></a>

                              &nbsp; 
                    <?php
                    if($client->status ==0){
                      echo'<a href="'.URL::to('user/deactive_vat_clients',base64_encode($client->client_id)).'" style="width:auto; float: none; padding: 5px; " title="Disable Client"><i class="fa fa-check" aria-hidden="true"></i></a>';
                    }
                    else{
                      echo'<a href="'.URL::to('user/active_vat_clients',base64_encode($client->client_id)).'" style="width:auto; float: none; padding: 5px; " title="Enable Client"><i class="fa fa-times" aria-hidden="true"></i></a>';
                    }
                  ?>
                  <br/><br/>
                    <div class="icon_div" style="width: 100%; height: auto; text-align: center; float: left; font-size: 30px; color: <?php if($client->cm_client_id == '') { echo 'red'; } else{ echo 'blue'; } ?>">
                      <i class="fa <?php if($client->cm_client_id == '') { echo 'fa-chain-broken'; } else{ echo 'fa-link'; } ?>" data-toggle="tooltip" <?php if($client->cm_client_id == '') { echo 'title="This task is not linked"'; } else { echo 'title="This task is linked"'; }?>></i>
                    </div>


                </td>
            </tr>
            <?php
                $i++;                              
                }              
              }
              if($i == 1)
              {
                echo'<tr><td colspan="9" align="center">Empty</td></tr>';
              }
            ?>
            
          </tbody>            
        </table>
        
    </div>
</div>
<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<input type="hidden" name="client_sortoptions" id="client_sortoptions" value="asc">
<input type="hidden" name="tax_sortoptions" id="tax_sortoptions" value="asc">
<input type="hidden" name="pemail_sortoptions" id="pemail_sortoptions" value="asc">
<input type="hidden" name="semail_sortoptions" id="semail_sortoptions" value="asc">
<div class="modal_load"></div>

<?php
if(count(Session::get('comparerows')) > 0)
{
echo '<script>
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:#f00>Can not proceed, please import Customers and Set Emails first</p>",width:"40%",height:"22%"});
    </script>';
    Session::forget('comparerows');
}
elseif(Session::has('comparerows')){ ?>
  <script>
  $(document).ready(function(){
    var iframe = $("#iframe_reload").attr("src");
    $("#iframe_reload").attr("src",iframe);
    $("#vat_notifications_modal").modal("show");
  });
    </script>
    <?php
    Session::forget('comparerows');
}
if(Session::has('message_import')) { ?>
<script>
$(document).ready(function(){
  $(".import_excel_modal").modal("show");
});
</script>
<?php } 
if(Session::has('message_import_not_valid')) { ?>
<script>
    $.colorbox({html:"<p style=text-align:center;margin-top:26px;font-size:18px;font-weight:600;color:#f00><?php echo Session::get('message_import_not_valid'); ?></p>",width:"40%",height:"22%"});
    </script>
<?php }
?>

<script>
  $( function() {
    $(".datepicker" ).datepicker({ dateFormat: 'mm-dd-yy' });
  } );
  </script>
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
var convertToNumber = function(value){
       return value.toLowerCase();
}
var parseconvertToNumber = function(value){
       return parseInt(value);
}
$(window).click(function(e) {  
  var ascending = false;
  if($(e.target).hasClass('sno_sort'))
  {
    var sort = $("#sno_sortoptions").val();
    if(sort == 'asc')
    {
      $("#sno_sortoptions").val('desc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.sno_sort_val').text()) <
        parseconvertToNumber($(b).find('.sno_sort_val').text()))) ? 1 : -1;
      });
    }
    else{
      $("#sno_sortoptions").val('asc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (parseconvertToNumber($(a).find('.sno_sort_val').text()) <
        parseconvertToNumber($(b).find('.sno_sort_val').text()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body').html(sorted);
  }
  if($(e.target).hasClass('client_sort'))
  {
    var sort = $("#client_sortoptions").val();
    if(sort == 'asc')
    {
      $("#client_sortoptions").val('desc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_sort_val').html()) <
        convertToNumber($(b).find('.client_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#client_sortoptions").val('asc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.client_sort_val').html()) <
        convertToNumber($(b).find('.client_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body').html(sorted);
  }
  if($(e.target).hasClass('tax_sort'))
  {
    var sort = $("#tax_sortoptions").val();
    if(sort == 'asc')
    {
      $("#tax_sortoptions").val('desc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.tax_sort_val').html()) <
        convertToNumber($(b).find('.tax_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#tax_sortoptions").val('asc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.tax_sort_val').html()) <
        convertToNumber($(b).find('.tax_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body').html(sorted);
  }
  if($(e.target).hasClass('pemail_sort'))
  {
    var sort = $("#pemail_sortoptions").val();
    if(sort == 'asc')
    {
      $("#pemail_sortoptions").val('desc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.pemail_sort_val').html()) <
        convertToNumber($(b).find('.pemail_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#pemail_sortoptions").val('asc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.pemail_sort_val').html()) <
        convertToNumber($(b).find('.pemail_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body').html(sorted);
  }
  if($(e.target).hasClass('semail_sort'))
  {
    var sort = $("#semail_sortoptions").val();
    if(sort == 'asc')
    {
      $("#semail_sortoptions").val('desc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.semail_sort_val').html()) <
        convertToNumber($(b).find('.semail_sort_val').html()))) ? 1 : -1;
      });
    }
    else{
      $("#semail_sortoptions").val('asc');
      var sorted = $('#task_body').find('.task_tr').sort(function(a,b){
        return (ascending ==
             (convertToNumber($(a).find('.semail_sort_val').html()) <
        convertToNumber($(b).find('.semail_sort_val').html()))) ? -1 : 1;
      });
    }
    ascending = ascending ? false : true;
    $('#task_body').html(sorted);
  }
  if(e.target.id == "edit_client_details")
  {
      var name = $(".name_class").val();           
      var pemail = $(".pemail_class").val();
      var semail = $(".semail_class_edit").val();
      var salutation = $(".salutation_class_edit").val();
      var id = $(".name_id").val();
      var self_manage = $(".self_manage_class:checked").val();
      var always_nil = $(".always_nil_class:checked").val();
      var client_id = $("#hidden_client_id_edit").val();
      var hidden_salutation = $("#hidden_client_salutation_edit").val();
      if(pemail == "")
      {
        $(".error_pemail").text("Please Enter your Primary Email Address");
        return false;
      }
      if(name  == "")
      {
        $(".error_client_name").text("Please Enter Client Name");
        return false; 
      }
      $.ajax({
        url:"<?php echo URL::to('user/update_vat_clients'); ?>",
        type:"post",
        dataType:"json",
        data:{name:name,pemail:pemail,semail:semail,salutation:salutation,self:self_manage,always_nil:always_nil,id:id,client_id:client_id,hidden_salutation:hidden_salutation},
        success: function(result)
        {
          $(".bs-example-modal-sm").modal("hide");
          $(".task_"+id).find(".semail_sort_val").find("label").text(semail);
          $(".task_"+id).find(".pemail_sort_val").find("label").text(pemail);
          $(".task_"+id).find(".client_sort_val").find("label").text(name);
          if(result['cm_client_id'] != "")
          {
            $(".task_"+id).find(".icon_div").find(".fa").removeClass("fa-chain-broken").addClass("fa-link");            
            $(".task_"+id).find(".icon_div").css({"color":"blue"});
          }
          else{
            $(".task_"+id).find(".icon_div").find(".fa").removeClass("fa-link").addClass("fa-chain-broken");
            $(".task_"+id).find(".icon_div").css({"color":"red"});
          }
          
          $(".color_pallete_purple").attr("data-content",result['purple']+" Clients");
          $(".color_pallete_yellow").attr("data-content",result['yellow']+" Clients");
          $(".color_pallete_green").attr("data-content",result['green']+" Clients");
          $(".color_pallete_red").attr("data-content",result['red']+" Clients");

          if(result['status'] == "0")
          {
            if(pemail != "" && self_manage == "no")
            {
              $(".task_"+id).find(".client_sort_val").find("label").attr("style","color:green !important");
            }
            else if(pemail == "" && self_manage == "no")
            {
              $(".task_"+id).find(".client_sort_val").find("label").attr("style","color:yellow !important");
            }
            else if(self_manage == "yes")
            {
              $(".task_"+id).find(".client_sort_val").find("label").attr("style","color:purple !important");
            }
            else{
              $(".task_"+id).find(".client_sort_val").find("label").attr("style","color:#fff !important");
            }
          }
          $(".message_edit").html('<p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a> Clients Updated successfully</p>');

        }
      });
  }
  if(e.target.id == "add_client_details")
  {
    var taxnumber = $(".error_taxnumber_add").text();
    if(taxnumber != "")
    {
      return false;
    }
    else{
      
    }
  }
  if($(e.target).hasClass("email_sent"))
  {
    $("body").addClass("loading");
    var id = $(e.target).attr("id");
    $.ajax({
      url:"<?php echo URL::to('user/email_sents'); ?>",
      type:"get",
      data:{id:id},
      success: function(result) {
        $("#client_email_sents_modal").modal("show");
        $("#client_email_sents").html(result);
        $(".saveaspdf").attr("data-element",id);
        $("body").removeClass("loading");
      }
    })
  }
  if($(e.target).hasClass('saveaspdf'))
  {
    $("body").addClass("loading");
    var id = $(e.target).attr("data-element");
    $.ajax({
      url:"<?php echo URL::to('user/email_sents_save_pdf'); ?>",
      type:"get",
      data:{id:id},
      success: function(result) {
        $("#client_email_sents_modal").modal("hide");
        $("body").removeClass("loading");
        SaveToDisk("<?php echo URL::to('papers'); ?>/"+result,result);
          return false; //this is critical to stop the click event which will trigger a normal file download
      }
    })
  }
  if($(e.target).hasClass('import_button')) {
    $(".import_modal").modal("show");
  }
  if($(e.target).hasClass('compare_button')) {
    $(".compare_modal").modal("show");
  }
  
  if($(e.target).hasClass('editclass')) {
    var editid = $(e.target).attr("id");
    console.log(editid);
    $.ajax({
        url: "<?php echo URL::to('user/edit_vat_clients') ?>"+"/"+editid,
        dataType:"json",
        type:"post",
        success:function(result){
           $(".bs-example-modal-sm").modal("toggle");
           $(".modal-content").css({"top":"90px"});
           $(".editsp").show();           
           $("#hidden_client_id_edit").val(result['cm_client_id']);
           $(".name_class").val(result['name']);           
           $(".taxnumber_class").val(result['taxnumber']);
           $(".pemail_class").val(result['pemail']);
           $(".semail_class_edit").val(result['semail']);
           $(".salutation_class_edit").val(result['salutation']);
           $(".client_search_class_edit").val(result['companyname']);
           $(".firstname_class_edit").val(result['firstname']);

           $(".name_id").val(result['id']);

            if(result['self_manage'] == 'yes')
            {
              $("#self_manage_class_yes").prop("checked",true);
            }
            else if(result['self_manage'] == 'no')
            {
              $("#self_manage_class_no").prop("checked",true);
            }
            else
            {
              $("#self_manage_class_yes").prop("checked",false);
              $("#self_manage_class_no").prop("checked",false);
            }

            if(result['always_nil'] == 'yes')
            {
              $("#always_nil_class_yes").prop("checked",true);
            }
            else if(result['always_nil'] == 'no')
            {
              $("#always_nil_class_no").prop("checked",true);
            }
            else
            {
              $("#always_nil_class_yes").prop("checked",false);
              $("#always_nil_class_no").prop("checked",false);
            }
      }
    });
  }
  if($(e.target).hasClass('addclass')) {
           $(".addclass_modal").modal("toggle");
           $(".modal-content").css({"top":"90px"});
  }
  if(e.target.id == "alert_submit")
  {
    var pemail = $(".pemail_update:checked").val();
    var semail = $(".semail_update:checked").val();
    var salutation = $(".salutation_update:checked").val();

    if(pemail == "" || typeof pemail === "undefined" || semail == "" || typeof semail === "undefined" || salutation == "" || typeof salutation === "undefined")
    {
      alert("Please select yes/no for all the questions.");
    }
    else{
      var clientid = $("#hidden_client_id").val();
      
      if(pemail == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclientemail'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $(".primaryemail_class").val(result);
          }
        });
      }
      if(semail == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclientemail_secondary'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $(".semail_class_add").val(result);
          }
        });
      }
      if(salutation == 1)
      {
        $("#hidden_client_salutation").val(1);
      }
      else{
        $("#hidden_client_salutation").val(0);
      }
      $("#alert_modal").modal("hide");
    }
  }
  if(e.target.id == "alert_submit_edit")
  {
    var pemail = $(".pemail_update_edit:checked").val();
    var semail = $(".semail_update_edit:checked").val();
    var salutation = $(".salutation_update_edit:checked").val();

    if(pemail == "" || typeof pemail === "undefined" || semail == "" || typeof semail === "undefined" || salutation == "" || typeof salutation === "undefined")
    {
      alert("Please select yes/no for all the questions.");
    }
    else{
      var clientid = $("#hidden_client_id_edit").val();
      if(pemail == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclientemail'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $(".primaryemail_class_edit").val(result);
          }
        });
      }
      if(semail == 1)
      {
        $.ajax({
          url:"<?php echo URL::to('user/getclientemail_secondary'); ?>",
          type:"post",
          data:{clientid:clientid},
          success: function(result)
          {
            $(".semail_class_edit").val(result);
          }
        });
      }
      if(salutation == 1)
      {
        $("#hidden_client_salutation_edit").val(1);
      }
      else{
        $("#hidden_client_salutation_edit").val(0);
      }
      $("#alert_modal_edit").modal("hide");
    }
  }
});



//setup before functions
var taxtypingTimer_add;                //timer identifier
var taxdoneTypingInterval_add = 1000;  //time in ms, 5 second for example
var $taxinput_add = $('.taxnumber_class_add');

//on keyup, start the countdown
$taxinput_add.on('keyup', function () {
  var taxinput_val_add = $(this).val();

  clearTimeout(taxtypingTimer_add);
  taxtypingTimer_add = setTimeout(taxdoneTyping_add, taxdoneTypingInterval_add,taxinput_val_add);
});

//on keydown, clear the countdown 
$taxinput_add.on('keydown', function () {
  clearTimeout(taxtypingTimer_add);
});

//user is "finished typing," do something
function taxdoneTyping_add (input) {
  $.ajax({
        url:"<?php echo URL::to('user/check_client_taxnumber'); ?>",
        type:"get",
        data:{taxnumber:input},
        success: function(result) {
          if(result == 1)
          {
            $(".error_taxnumber_add").text('Taxnumber Already Exists');
          }
          else{
            $(".error_taxnumber_add").text('');
          }
        }
      });
}
</script>

<script>
$(document).ready(function() {    
     $(".client_search_class").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/vat_client_search'); ?>",
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data);
                   
                }
            });
        },
        minLength: 1,
        select: function( event, ui ) {
          $("#client_search").val(ui.item.id);          
          $.ajax({
            dataType: "json",
            url:"<?php echo URL::to('user/vat_client_search_select'); ?>",
            data:{value:ui.item.id},
            success: function(result){
              $(".tax_reg1class").val(result['taxreg']);
              $(".firstname_class").val(result['firstname']);
              $("#hidden_client_id").val(ui.item.id);
              $('#alert_modal').modal({backdrop: 'static', keyboard: false});
              $(".pemail_update_edit").prop("checked",false);
              $(".semail_update_edit").prop("checked",false);
              $(".salutation_update_edit").prop("checked",false);

              $(".pemail_update").prop("checked",false);
              $(".semail_update").prop("checked",false);
              $(".salutation_update").prop("checked",false);
            }
          })
        }
    });
     $(".client_search_class_edit").autocomplete({
        source: function(request, response) {
            $.ajax({
                url:"<?php echo URL::to('user/vat_client_search'); ?>",
                dataType: "json",
                data: {
                    term : request.term
                },
                success: function(data) {
                    response(data);
                   
                }
            });
        },
        minLength: 1,
        select: function( event, ui ) {
          $("#client_search_edit").val(ui.item.id);          
          $.ajax({
            dataType: "json",
            url:"<?php echo URL::to('user/vat_client_search_select'); ?>",
            data:{value:ui.item.id},
            success: function(result){
              
              $(".firstname_class_edit").val(result['firstname']);
              $("#hidden_client_id_edit").val(ui.item.id);
              $('#alert_modal_edit').modal({backdrop: 'static', keyboard: false});

              $(".pemail_update_edit").prop("checked",false);
              $(".semail_update_edit").prop("checked",false);
              $(".salutation_update_edit").prop("checked",false);

              $(".pemail_update").prop("checked",false);
              $(".semail_update").prop("checked",false);
              $(".salutation_update").prop("checked",false);
            }
          })
        }
    });
});
</script>


@stop