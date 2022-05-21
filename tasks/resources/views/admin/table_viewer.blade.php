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
<script src="<?php echo URL::to('assets/js/waypoints/lib/jquery.waypoints.js'); ?>"></script>
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

<div class="content_section" style="margin-bottom:200px">
  <div class="page_title">
        <h4 class="col-lg-1" style="padding: 0px;">
                Table Viewer             
            </h4>
            <div class="col-lg-9" style="padding-right: 0px; line-height: 35px;">
                <label style="float:left;margin-top:7px">Select Table: </label>
                <select name="select_table" class="form-control select_table" style="float:left;width:20%;margin-left:20px;margin-top:7px">
                    <option value="">Select one table</option>
                    <?php
                    if(count($tablelist)){
                      foreach($tablelist as $key => $table){
                        echo '<option value="'.$table.'" data-element="'.$key.'">'.$table.'</option>';
                      }
                    }
                    ?>
                </select>
                <input type="button" class="common_black_button show_table_viewer" id="show_table_viewer" value="Load Table" style="margin-top:7px"> <br/>

                <textarea name="table_notes" class="form-control table_notes" value="" style="display:none"></textarea>
            </div>
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

<div class="table_viewer_content" style="margin-top: 20px;"></div>
    <!-- End  -->
<div class="main-backdrop"><!-- --></div>


<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">


<script>
$(window).change(function(e) {
    if($(e.target).hasClass('select_table'))
    {
      var value=$(e.target).val();
      if(value == ''){
        $(".table_notes").hide();
      }
      else{
        $.ajax({
          url:"<?php echo URL::to('admin/get_table_notes'); ?>",
          type:"post",
          data:{value:value},
          success:function(result){
            $(".table_notes").show();
            $(".table_notes").val(result);
          }
        });
      }
    }
});
// function isScrolledIntoView(elem)
// {
//     var docViewTop = $(window).scrollTop();
//     var docViewBottom = docViewTop + $(window).height();

//     var elemTop = elem.offset().top;
//     var elemBottom = elemTop + $(elem).height();

//     return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
// }




// $(window).scroll(function() {
  
//   // if (isScrolledIntoView($('.load_more_content')))
//   // {
//   //     
//   // }
// });
function waypointsval() {
  $("#load_more_content").hide();
  Waypoint.destroyAll();
  var table_name = $(".select_table").val();
  var page = $("#hidden_page").val();

  var nextpage = parseInt(page) + 1;
  $.ajax({
     url:"<?php echo URL::to('admin/show_table_viewer_append'); ?>",
     type:"post",
     dataType:"json",
     data:{table_name:table_name,page:nextpage},
     success:function(result){
       $("#table_viewer_tbody").append(result['output']);
       if(result['show_load_btn'] == 1){
         $(".common_btn").addClass('load_more_content');
         $(".common_btn").show();
         $("#hidden_page").val(nextpage);
         $('#load_more_content').waypoint(function() {
            waypointsval();
        }, {
            offset: '100%'
        });
       }
       else{
         $(".common_btn").removeClass('load_more_content');
         $(".common_btn").hide();
         $("#hidden_page").val(0);
       }
     }
  })
}


$(window).click(function(e) {
  if($(e.target).hasClass('show_table_viewer'))
  {
    var table_name = $(".select_table").val();
    if(table_name == ""){
      alert("Please select the table");
    }
    else{
      $.ajax({
        url:"<?php echo URL::to('admin/show_table_viewer'); ?>",
        type:"post",
        dataType:"json",
        data:{table_name:table_name,page:"1"},
        success:function(result){
          $(".table_viewer_content").html(result['output']);
          $('#load_more_content').waypoint(function() {
              waypointsval();
          }, {
              offset: '100%'
          });
        }
      })
    }
  }
});

$(".table_notes").on("blur", function() {
  var notes = $(this).val();
  var table = $(".select_table").val();

  $.ajax({
    url:"<?php echo URL::to('admin/update_table_notes'); ?>",
    type:"post",
    data:{notes:notes,table:table},
    success:function(result){
      
    }
  });
});
</script>
@stop