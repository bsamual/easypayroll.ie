@extends('userheader')
@section('content')
<?php require_once(app_path('Http/helpers.php')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<style>
.code_td{cursor:pointer;}
.active_code_tr{background: #dfdfdf;}
body{
  background: #f5f5f5 !important;
}
.modal_load_apply {
    display:    none;
    position:   fixed;
    z-index:    9999999999999;
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
.label_class{
  float:left ;
  margin-top:15px;
  font-weight:700;
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
.report_div{
    position: absolute;
    top: 74%;
    left:248px;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
    text-align: left;
}
.invoice_year_div{
    position: absolute;
    top: 93%;
    left:20%;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
    text-align: left;
    width: 250px;
}
.invoice_custom_div{
    position: absolute;
    top: 93%;
    left:266px;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
    text-align: left;
}
.report_model_selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; margin-top: 20px; border-radius: 3px;  }
.report_ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 15px; transition: 0.3s; opacity: 0.6; }
.report_ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff; opacity: 1;}
.report_ok_button:focus{background: #000; text-decoration: none; color: #fff; opacity: 1;}
.all_clients, .invoice_date_option{margin-top: 12px !important;}
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
.error{color: #f00; font-size: 12px;}
a:hover{text-decoration: underline;}
</style>

<div class="modal fade" id="import_invoice" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <form action="<?php echo URL::to('user/import_new_invoice'); ?>" method="post" autocomplete="off" enctype="multipart/form-data">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Import Invoice</h4>
          </div>
          <div class="modal-body">
              <label>Choose File : </label>
              <input type="file" name="new_file" id="new_file" class="form-control input-sm" accept=".xlsx" required> 
          </div>
          <div class="modal-footer">
              <input type="submit" class="common_black_button" id="import_new_file" value="Import">
          </div>
        </form>
      </div>
  </div>
</div>
<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
    <h4 class="col-lg-12 padding_00 new_main_title">Receipts Management System</h4>     
      <div style="clear: both;">
        <?php
        if(Session::has('message')) { ?>
            <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>
        
        <?php } ?>
      </div>
      <h4 style="text-align: center">
        Setup Nominals to Allow Receipts to under the Receipt System
        <a href="<?php echo URL::to('user/receipt_management'); ?>" class="common_black_button" style="float: right;">Back to Receipts</a>
      </h4>
      <div class="col-md-3 col-md-offset-2">
        <h5 style="text-align: center">Nominal List</h5>
        <div class="col-md-12" style="border:1px solid #dfdfdf;max-height: 500px;overflow-y: scroll">
          <table class="table">
            <thead>
              <th>Code</th>
              <th>Description</th>
            </thead>
            <tbody>
              <?php
              if(count($nominal_codes))
              {
                foreach($nominal_codes as $code)
                {
                  echo '<tr class="code_tr" id="code_tr_'.$code->id.'" data-element="'.$code->id.'">
                    <td class="code_td" data-element="'.$code->id.'">'.$code->code.'</td>
                    <td class="code_td" data-element="'.$code->id.'">'.$code->description.'</td>
                  </tr>';
                }
              }
              else{
                echo '<tr>
                  <td colspan="2">No Records Found</td>
                </tr>';
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="col-md-2" style="text-align: center">
        <input type="button" name="add_to_allowable_list" class="common_black_button add_to_allowable_list" value="Add to Allowable List" style="margin-top:69%">
      </div>
      <div class="col-md-3">
        <h5 style="text-align: center">Allowable Receipts Nominals</h5>
        <div class="col-md-12" style="border:1px solid #dfdfdf;max-height: 500px;overflow-y: scroll">
          <table class="table">
            <thead>
              <th>Code</th>
              <th>Description</th>
            </thead>
            <tbody id="allowable_tbody">
              <?php
              if(count($receipt_nominal_codes))
              {
                foreach($receipt_nominal_codes as $code)
                {
                  echo '<tr>
                    <td>'.$code->code.'</td>
                    <td>'.$code->description.'</td>
                  </tr>';
                }
              }
              else{
                echo '<tr class="no_records">
                  <td colspan="2">No Records Found</td>
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
  <div class="modal_load"></div>
  <div class="modal_load_apply" style="text-align: center;">
    <p style="font-size:18px;font-weight: 600;margin-top: 27%;">Please wait until all the Invoices are Loaded.</p>
    <p style="font-size:18px;font-weight: 600;">Building Sales Invoice Journals: <span id="apply_first"></span> of <span id="apply_last"></span></p>
  </div>
  
  <input type="hidden" name="hidden_nominal_code_id" id="hidden_nominal_code_id" value="">

</div>
<script>
$(window).click(function(e) {
  if($(e.target).hasClass("code_td"))
  {
    if($(e.target).parents(".code_tr").hasClass('active_code_tr'))
    {
      $(e.target).parents(".code_tr").removeClass("active_code_tr");
    }
    else{
      $(e.target).parents(".code_tr").addClass("active_code_tr");
      var code = $(e.target).attr("data-element");
    }
  }
  if($(e.target).hasClass('add_to_allowable_list'))
  {
    $("body").addClass("loading");
    var code_length = $(".active_code_tr").length;
    if(code_length == 0)
    {
      alert("Please select the Nominal code to move to the allowable list");
    }
    else{
      var code = '';
      $(".active_code_tr").each(function() {
        var codee = $(this).find(".code_td:first").attr("data-element");
        if(code == ""){
          code = codee;
        }
        else{
          code = code+','+codee;
        }
      });
      $.ajax({
        url:"<?php echo URL::to('user/move_to_allowable_list'); ?>",
        type:"post",
        data:{code:code},
        success:function(result)
        {
          var codes = code.split(",");
          $.each(codes, function(index,value){
            $("#code_tr_"+value).detach();
          });
          var allowable_length = $("#allowable_tbody").find(".no_records").length;
          if(allowable_length > 0)
          {
            $("#allowable_tbody").html(result);
            $("#hidden_nominal_code_id").val("");
            $("body").removeClass("loading");
          }
          else{
            $("#allowable_tbody").append(result);
            $("#hidden_nominal_code_id").val("");
            $("body").removeClass("loading");
          }
        }
      })
    }
  }
});
</script>
@stop