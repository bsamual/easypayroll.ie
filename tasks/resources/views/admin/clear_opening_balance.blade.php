@extends('adminheader')
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
<?php
DB::table('request_others')->where('other_content','')->where('status',0)->delete();
DB::table('request_purchase_attached')->where('attachment','')->where('url','')->where('status',0)->delete();
DB::table('request_purchase_invoice')->where('specific_invoice','')->where('status',0)->delete();
DB::table('request_sales_attached')->where('attachment','')->where('url','')->where('status',0)->delete();
DB::table('request_sales_invoice')->where('specific_invoice','')->where('sales_invoices','')->where('status',0)->delete();
DB::table('request_bank_statement')->where('bank_id','')->where('statment_number','')->where('status',0)->delete();
DB::table('request_cheque')->where('bank_id','')->where('specific_number','')->where('status',0)->delete();
DB::table('request_cheque_attached')->where('attachment','')->where('url','')->where('status',0)->delete();
?>
<style>
.label_class{
  width:20%;
  float: left;
}
.plus_add{ padding: 3px ;background: #000; color: #fff; width: 30px; text-align: center; margin-top: 23px; font-size: 20px; float: right; }
.plus_add:hover{background: #5f5f5f; color: #fff}
.minus_remove{ padding: 3px ;background: #000; color: #fff; width: 30px; text-align: center; margin-top: 23px; font-size: 20px; float: right;margin-left: 4px; }
.minus_remove:hover{background: #5f5f5f; color: #fff}
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
<!-- Modal -->
<div class="modal fade add_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="z-index: 999999999999999;">
  <div class="modal-dialog modal-md" role="document" style="z-index: 999999">
    <div class="modal-content">
      <form method="post" action="<?php echo URL::to('user/admin_request_add')?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Add Request Category</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Enter Category Name</label>
            <input type="text" required class="form-control" name="category" placeholder="Enter Category" name="">
          </div> 
          <div class="col-lg-12 padding_00" style="height: 1px; background: #ccc;"></div>
          <div class="col-lg-12 padding_00" style="padding-top: 10px; margin-bottom: 8px;" >
            <b style="font-size: 15px;">Request Item</b>
          </div>
          <div id="add_items_div">
            <div class="row single_item_div">
              <div class="col-lg-10">
                <div class="form-group">
                  <label>Enter Item Name</label>
                  <input type="text" required class="form-control" name="request_item[]" placeholder="Enter Item Name">
                </div>      
              </div>
              <div class="col-lg-2">
                <a href="javascript:" class="plus_add">+</a>
              </div>
          </div>
          </div>
          
          

          
        </div>
        <div class="modal-footer">
          <input type="submit" class="btn btn-primary common_black_button" value="Add New Category">
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade edit_model" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" data-backdrop="static" data-keyboard="false" style="z-index: 999999999999999;">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <form method="post" action="<?php echo URL::to('user/admin_request_edit_form')?>">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="myModalLabel">Edit Request Category</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Enter Category Name</label>
            <input type="text" required class="form-control category_edit" name="category_edit" placeholder="Enter Category" name="">
          </div> 
          <div class="col-lg-12 padding_00" style="height: 1px; background: #ccc;"></div>
          <div class="col-lg-12 padding_00" style="padding-top: 10px; margin-bottom: 8px;" >
            <b style="font-size: 15px;">Request Item</b>
          </div>
          <div id="edit_items_div">
            
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="category_id_edit" id="category_id_edit" value="">
          <input type="submit" class="btn btn-primary common_black_button" value="Update Category">
        </div>
      </form>
    </div>
  </div>
</div>
<div class="modal fade" id="setup_request_modal" tabindex="-1" role="dialog" aria-labelledby="setup_request_modal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" role="document" style="width:50%">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <a href="javascript:" class="addclass common_black_button float_right" data-toggle="modal" data-target=".add_model" style="margin-right: 20px;">Add Setup Category</a>
         <h5 class="modal-title" id="exampleModalLabel">Setup Request Categories</h5>
      </div>
      <div class="modal-body">
        <?php $requestlist = DB::table('request_category')->get(); ?>
        <table class="table">
          <thead>
            <tr>
                <th width="5%" style="text-align: left;">S.No</th>
                <th style="text-align: left;">Category Name</th>
                <th>Signature</th>
                <th width="15%">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $output='';
            $i=1;
            if(count($requestlist)){
              foreach ($requestlist as $request) {
                $sub_category=DB::table('request_sub_category')->where('category_id', $request->category_id)->get();
                if(count($sub_category)){
                  $outputsub='';
                  foreach ($sub_category as $sub) {
                    $outputsub.=$sub->sub_category_name.'<br/>';
                  }              
                }
                else{
                  $outputsub='';
                }
                if($request->status == 0){
                  $status = '<a href="'.URL::to('user/admin_deactive_request/'.base64_encode($request->category_id)).'"><i class="fa fa-check deactive_class" style="color:#00b348;"></i></a>';
                }
                else{
                  $status = '<a href="'.URL::to('user/admin_active_request/'.base64_encode($request->category_id)).'"><i class="fa fa-times active_class" style="color:#f00;"></i></a>';
                }
                $output.='<tr>
                            <td>'.$i.'</td>
                            <td><b>'.$request->category_name.'</b><br/>'.$outputsub.'</td>
                            <td width="300px;">
                            <textarea placeholder="Enter Signature" data-element="'.base64_encode($request->category_id).'" class="form-control class_signature" style="height:100px;">'.$request->signature.'</textarea>
                            </td>
                            <td style="font-size:15px; text-align:center">
                            '.$status.'&nbsp;&nbsp;
                            <a href="javascript:"><i class="fa fa-pencil-square edit_icon" data-element="'.base64_encode($request->category_id).'"></i></a>&nbsp;&nbsp;
                            <a href="'.URL::to('user/admin_delete_request/'.base64_encode($request->category_id)).'"><i class="fa fa-trash"></i></a>
                            
                            </td>
                          </tr>';
                          $i++;
              }
            }
            else{
              $output='<tr><td colspan="4" align="center">Empty</td></tr>';
            }
            echo $output;
            ?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
        <h4 class="col-lg-3" style="padding: 0px;">
                Clear Opening Balance             
            </h4>
            <div class="col-lg-9 text-right" style="padding-right: 0px; line-height: 35px;text-align: right">
                <a href="javascript:" class="common_black_button clear_all_opening_balance" title="Clear all Opening Balance for all Clients">Clear Opening Balance for all Clients</a> 
            </div>
  <div class="table-responsive" style="max-width: 100%; float: left;margin-bottom:30px; margin-top:55px">
  </div>
  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
     <?php }   
    ?>
    </div> 

<style type="text/css">
.refresh_icon{margin-left: 10px;}
.refresh_icon:hover{text-decoration: none;}
.datepicker-only-init_date_received, .auto_save_date{cursor: pointer;}
.bootstrap-datetimepicker-widget{width: 300px !important;}
.image_div_attachments P{width: 100%; height: auto; font-size: 13px; font-weight: normal; color: #000;}
</style>

<table class="display nowrap fullviewtablelist" id="crm_expand" width="100%" style="max-width: 100%;">
                        <thead>
                        <tr style="background: #fff;">
                             <th width="2%" style="text-align: left;">S.No</th>
                            <th style="text-align: left;">Client ID</th>
                            <th style="text-align: left;">Company</th>
                            <th style="text-align: left;">Action</th>
                        </tr>
                        </thead>                            
                        <tbody id="clients_tbody">
                        <?php
                            $i=1;
                            if(count($clientlist)){              
                              foreach($clientlist as $key => $client){
                          ?>
                            <tr class="edit_task " id="clientidtr_<?php echo $client->id; ?>">
                                <td style=""><?php echo $i; ?></td>
                                <td align="left"><a href="<?php echo URL::to('user/client_request_manager/'.base64_encode($client->client_id))?>" id="<?php echo base64_encode($client->id); ?>" class="invoice_class" style=""><?php echo $client->client_id; ?></a></td>
                                <td align="left"><a href="<?php echo URL::to('user/client_request_manager/'.base64_encode($client->client_id))?>" id="<?php echo base64_encode($client->id); ?>" class="invoice_class" style=""><?php echo ($client->company == "")?$client->firstname.' & '.$client->surname:$client->company; ?></a></td>
                                <td align="left"><a href="javascript:" class="clear_opening_balance" title="Clear Opening Balance for this Client" data-element="<?php echo $client->client_id; ?>">Clear Balance</a></td>
                            </tr>
                            <?php
                              $i++;
                              }              
                            }
                            if($i == 1)
                            {
                              echo'<tr><td colspan="8" align="center">Empty</td></tr>';
                            }
                          ?> 
                        </tbody>
                    </table>
</div>
    <!-- End  -->
<div class="main-backdrop"><!-- --></div>


<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">


<script>
$(window).click(function(e) {
  if($(e.target).hasClass('clear_all_opening_balance'))
  {
    var r = confirm("Are you sure you want to delete all the Balance for all the Clients");
    if(r)
    {
      $.ajax({
        url:"<?php echo URL::to('admin/clear_all_opening_balance'); ?>",
        type:"post",
        success:function(result)
        {
          window.location.replace("<?php echo URL::to('admin/clear_opening_balance?cleared=1'); ?>");
        }
      })
    }
  }
  if($(e.target).hasClass('clear_opening_balance'))
  {
    var r = confirm("Are you sure you want to delete all the Balance for the selected Client");
    if(r)
    {
      var client_id = $(e.target).attr("data-element");
      $.ajax({
        url:"<?php echo URL::to('admin/clear_opening_balance_for_client'); ?>",
        type:"post",
        data:{client_id:client_id},
        success:function(result)
        {
          //window.location.replace("<?php echo URL::to('admin/clear_opening_balance?cleared=1'); ?>");
        }
      })
    }
  }
});
$(function(){
    $('#crm_expand').DataTable({
        fixedHeader: {
          headerOffset: 75
        },
        autoWidth: false,
        scrollX: false,
        fixedColumns: false,
        searching: false,
        paging: false,
        info: false
    });
});

$(window).click(function(e) { 

})
</script>
@stop