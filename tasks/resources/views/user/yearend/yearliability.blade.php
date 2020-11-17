@extends('userheader')
@section('content')
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/jquery.dataTables.min.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/fixedHeader.dataTables.min.css'); ?>">

<script src="<?php echo URL::to('assets/js/jquery.dataTables.min.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/dataTables.fixedHeader.min.js'); ?>"></script>

<script src="<?php echo URL::to('assets/js/jquery.form.js'); ?>"></script>


<link rel="stylesheet" href="<?php echo URL::to('assets/js/lightbox/colorbox.css'); ?>">
<script src="<?php echo URL::to('assets/js/lightbox/jquery.colorbox.js'); ?>"></script>

<script src='<?php echo URL::to('assets/js/table-fixed-header_cm.js')?>'></script>
<style>

body{
  background: #f5f5f5 !important;
}
.fa-sort{
  cursor:pointer;
}
.show_incomplete_label{
  cursor:pointer;
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
    top: 55%;
    left:20%;
    padding: 15px;
    background: #ff0;
    z-index: 999999;
    text-align: left;
}
.selectall{padding:10px 15px; background-image:linear-gradient(to bottom,#f5f5f5 0,#e8e8e8 100%); background: #f5f5f5; border:1px solid #ddd; border-radius: 3px;  }

.report_ok_button{background: #000; text-align: center; padding: 6px 12px; color: #fff; float: left; border: 0px; font-size: 13px; }
.report_ok_button:hover{background: #5f5f5f; text-decoration: none; color: #fff}
.report_ok_button:focus{background: #000; text-decoration: none; color: #fff}

.form-title{width: 100%; height: auto; float: left; margin-bottom: 5px;}


.table tr td, tr th{font-size: 15px;}
.form-control[readonly]{background-color: #dcdcdc !important}

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

.blinking{
    animation:blinkingText 0.8s infinite;
}
@keyframes blinkingText{
    0%{     background: #f00;    }
    49%{    background: #000; }
    50%{    background: #f00; }
    99%{    background:#000;  }
    100%{   background: #f00;    }
}
</style>







<div class="content_section" style="margin-bottom:200px">
  <div class="page_title" style="margin-bottom: 2px;">
          <div class="col-lg-3" style="padding: 0px;">
              <?php echo $yearid; ?> YEAR END MANAGER - Liability Assessment
              <input type="hidden" value="<?php echo $yearid; ?>" id="year_id" name="">
            </div>
            <div class="col-lg-3" ></div>
            <div class="col-lg-2">
              <select class="form-control setting_class">
                <option value="">Select Settings</option>
                <?php echo $setting_list; ?>
              </select>
            </div>
            <div class="col-lg-4 text-right"  style="padding: 0px;" >
              <div class="select_button" style=" margin-left: 10px;">
                <ul style="float: right;">            
                         
                  <li><input type="checkbox" name="show_incomplete" id="show_incomplete" value="1" class="show_incomplete_label"><label for="show_incomplete" class="show_incomplete_label">Hide / Show Active Clients</label> </li>     
                  <li><a href="<?php echo URL::to('user/year_end_manager'); ?>" style="font-size: 13px; font-weight: 500;">Back to Select Year Screen</a></li>
                  <li><a href="javascript:" class="export_button" style="font-size: 13px; font-weight: 500;">Export CSV</a></li>
                  


                  
              </ul>
            </div>                        
  </div>

  <div style="clear: both;">
   <?php
    if(Session::has('message')) { ?>
        <p class="alert alert-info"><a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a><?php echo Session::get('message'); ?></p>

    
    <?php } ?>
    </div> 


</div>

<div class="table-responsive table_result">
      
<div class="col-lg-12 text-center"><b style="font-size: 20px; margin-top:30px; ">Please Select Document Type.</b></div>
   
</div>
    <!-- End  -->

<div class="main-backdrop"><!-- --></div>




<div class="modal_load"></div>
<input type="hidden" name="hidden_client_count" id="hidden_client_count" value="">
<input type="hidden" name="show_alert" id="show_alert" value="">
<input type="hidden" name="pagination" id="pagination" value="1">

<input type="hidden" name="sno_sortoptions" id="sno_sortoptions" value="asc">
<input type="hidden" name="clientid_sortoptions" id="clientid_sortoptions" value="asc">
<input type="hidden" name="firstname_sortoptions" id="firstname_sortoptions" value="asc">
<input type="hidden" name="lastname_sortoptions" id="lastname_sortoptions" value="asc">
<input type="hidden" name="company_sortoptions" id="company_sortoptions" value="asc">
<input type="hidden" name="status_sortoptions" id="status_sortoptions" value="asc">

<input type="hidden" name="review_clientid_sortoptions" id="review_clientid_sortoptions" value="asc">
<input type="hidden" name="review_company_sortoptions" id="review_company_sortoptions" value="asc">


<script>

$(document).ready(function() {
  $('.table-fixed-header').fixedHeader();
  $(".date_class").datetimepicker({     
     format: 'L',
     format: 'DD-MMM-YYYY',
  });
});
// $(".payment_class").blur(function() {
//   var that = $(this);
//   var input_val = $(this).val();
//   var setting_id = $(".setting_class").val();        
//   var client_id  = $(this).attr("data-element");
//   var year_id  = $("#year_id").val();
//   $.ajax({
//         url:"<?php echo URL::to('user/yearend_liability_payment')?>",
//         type:"post",
//         dataType:"json",
//         data:{setting_id:setting_id, value:payment_value, year_id:year_id, client_id:client_id},
//         headers: {
//               'X-CSRF-TOKEN': '{{ csrf_token() }}'
//           },
//         success: function(result) { 
//           $("#client_"+result['client_id']).find(".balance_class").html(result['balance']);
//           payment_value = payment_value.replace(",", "");
//           payment_value = payment_value.replace(",", "");
//           payment_value = payment_value.replace(",", "");
//           payment_value = payment_value.replace(",", "");
//           payment_value = payment_value.replace(",", "");
//           payment_value = payment_value.replace(",", "");
//           that.parents("td").find(".payment_spam_class").html(payment_value);
//           $("#liability_expand").DataTable().destroy();
//           var table = $('#liability_expand').DataTable({
//               fixedHeader: {
//                 headerOffset: 75
//               },
//               autoWidth: false,
//               scrollX: false,
//               fixedColumns: false,
//               searching: false,
//               paging: false,
//               info: false,
//               order: false,
//           });
//           ajaxdatecomplete();
//         } 
//   });        
// })
// $(".prelim_class").blur(function() {
//   var that = $(this);
//   var input_val = $(this).val();
//   var setting_id = $(".setting_class").val();        
//   var client_id  = $(this).attr("data-element");
//   var year_id  = $("#year_id").val();

//   $.ajax({
//         url:"<?php echo URL::to('user/yearend_liability_prelim')?>",
//         type:"post",
//         dataType:"json",
//         data:{setting_id:setting_id, value:prelim_value, year_id:year_id, client_id:client_id},
//         headers: {
//               'X-CSRF-TOKEN': '{{ csrf_token() }}'
//           },
//         success: function(result) { 
//           prelim_value = prelim_value.replace(",", "");
//           prelim_value = prelim_value.replace(",", "");
//           prelim_value = prelim_value.replace(",", "");
//           prelim_value = prelim_value.replace(",", "");
//           prelim_value = prelim_value.replace(",", "");
//           prelim_value = prelim_value.replace(",", "");
//           that.parents("td").find(".prelim_spam_class").html(prelim_value);
//           $("#liability_expand").DataTable().destroy();
//           var table = $('#liability_expand').DataTable({
//               fixedHeader: {
//                 headerOffset: 75
//               },
//               autoWidth: false,
//               scrollX: false,
//               fixedColumns: false,
//               searching: false,
//               paging: false,
//               info: false,
//               order: false,
//           }); 
//           ajaxdatecomplete();
          
//         } 
//   });     
// })
</script>


<script>
$(window).click(function(e) {
  if($(e.target).hasClass('date_selection'))
  {
    if($(e.target).is(":checked"))
    {
      $(e.target).parents("td").find(".date_class").prop("disabled",false);
      var status = "1";
    }
    else{
      $(e.target).parents("td").find(".date_class").prop("disabled",true);
      var status = "0";
    }
    var setting_id = $(".setting_class").val();        
    var client_id  = $(e.target).attr("data-element");
    var year_id  = $("#year_id").val();

    $.ajax({
      url:"<?php echo URL::to('user/save_yearend_date_status'); ?>",
      type:"post",
      headers: {
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      data:{status:status,client_id:client_id,setting_id:setting_id,year_id:year_id},
      success:function(result)
      {
        var datecls_val = $(e.target).parents("td").find(".date_class").val();
        if(datecls_val == "" && $(e.target).is(":checked"))
        {
          $(e.target).parents("td").find(".date_class").val(result)
        }
        ajaxdatecomplete();
      }
    });    
  }
  if($(e.target).hasClass('fileattachment'))
  {
      e.preventDefault();
      var element = $(e.target).attr('data-element');
      console.log(element);
      $('body').addClass('loading');
      setTimeout(function(){
        SaveToDisk(element,element.split('/').reverse()[0]);
        $('body').removeClass('loading');
        }, 3000);
      return false; 
  }
  if(e.target.id == 'show_incomplete')
  {
    if($(e.target).is(":checked"))
    {
      $(".client_2").hide();
    }
    else{
      $(".client_2").show();
    }
  }

if($(e.target).hasClass('export_button'))
  {    
    var setting_id = $(".setting_class").val();
    var year_id = $("#year_id").val();
    if(setting_id == "")
    {
      alert("Please select Setting.");
    }
    else{
          $.ajax({
          url: "<?php echo URL::to('user/yearend_liability_export')?>",
          type:"post",
          data:{setting_id:setting_id,year_id:year_id},
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          success:function(result){

             SaveToDisk("<?php echo URL::to('papers'); ?>/Export_Liability.csv",'Export_Liability.csv');
            ajaxdatecomplete();
        }
      })
    }
  }




})
function ajaxdatecomplete()
{
  $(".date_class").datetimepicker({     
     format: 'L',
     format: 'DD-MMM-YYYY',
  });

  $(".date_class").on("dp.hide", function (e) {
    var setting_id = $(".setting_class").val();        
    var client_id  = $(this).attr("data-element");
    var year_id  = $("#year_id").val();
    var dateval = $(this).val();

    $.ajax({
      url:"<?php echo URL::to('user/save_yearend_liability_date'); ?>",
      type:"post",
      data:{dateval:dateval,client_id:client_id,setting_id:setting_id,year_id:year_id},
      headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
      success:function(result)
      {
        $(this).parents("td").find(".date_spam_class").html(result);
      }
    })
  });
}
</script>
<script>
$(function(){
    $('#liability_expand').DataTable({
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
</script>
<script>
$(".setting_class").change(function(){
  $("body").addClass("loading");
  var id = $(this).val();
  var yearid = $("#year_id").val();
  $.ajax({
        url:"<?php echo URL::to('user/yearend_liability_setting_result')?>",
        type:"post",
        dataType:"json",
        data:{id:id, yearid:yearid},
        headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
        success: function(result) { 
          $(".table_result").html(result['output_result']);
          $('#liability_expand').DataTable({
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
          ajaxdatecomplete();
          $("body").removeClass("loading");
        } 
  });
});
</script>
<script>
$(window).keyup(function(e) {
    var valueTimmer;                //timer identifier
    var valueInterval = 500;  //time in ms, 5 second for example
    var $payment_value = $('.payment_class');
    var $prelim_value = $('.prelim_class');
    if($(e.target).hasClass('payment_class'))
    {        
        var that = $(e.target);
        var input_val = $(e.target).val();
        var setting_id = $(".setting_class").val();        
        var client_id  = $(e.target).attr("data-element");
        var year_id  = $("#year_id").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_payment, valueInterval,input_val, client_id, year_id, setting_id, that);   
    }
    if($(e.target).hasClass('prelim_class'))
    {        
        var that = $(e.target);
        var input_val = $(e.target).val();
        var setting_id = $(".setting_class").val();        
        var client_id  = $(e.target).attr("data-element");
        var year_id  = $("#year_id").val();
        clearTimeout(valueTimmer);
        valueTimmer = setTimeout(doneTyping_prelim, valueInterval,input_val, client_id, year_id, setting_id, that);   
    }
});
function doneTyping_payment (payment_value, client_id, year_id, setting_id, that) {
  $.ajax({
        url:"<?php echo URL::to('user/yearend_liability_payment')?>",
        type:"post",
        dataType:"json",
        data:{setting_id:setting_id, value:payment_value, year_id:year_id, client_id:client_id},
        headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
        success: function(result) { 
          $("#client_"+result['client_id']).find(".balance_class").html(result['balance']);
          var table = $('#liability_expand').DataTable();
          $('#liability_expand').find("tbody").find("td").each(function() {
            this.innerHTML = parseInt( this.innerHTML ) + 1;
            table.cell( this ).invalidate().draw();
          });
          payment_value = payment_value.replace(",", "");
          payment_value = payment_value.replace(",", "");
          payment_value = payment_value.replace(",", "");
          payment_value = payment_value.replace(",", "");
          payment_value = payment_value.replace(",", "");
          payment_value = payment_value.replace(",", "");
          that.parents("td").find(".payment_spam_class").html(payment_value);

          ajaxdatecomplete();
        } 
  });            
}
function doneTyping_prelim (prelim_value, client_id, year_id, setting_id, that) {
  $.ajax({
        url:"<?php echo URL::to('user/yearend_liability_prelim')?>",
        type:"post",
        dataType:"json",
        data:{setting_id:setting_id, value:prelim_value, year_id:year_id, client_id:client_id},
        headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
        success: function(result) { 
          prelim_value = prelim_value.replace(",", "");
          prelim_value = prelim_value.replace(",", "");
          prelim_value = prelim_value.replace(",", "");
          prelim_value = prelim_value.replace(",", "");
          prelim_value = prelim_value.replace(",", "");
          prelim_value = prelim_value.replace(",", "");
          that.parents("td").find(".prelim_spam_class").html(prelim_value);

          ajaxdatecomplete();
          
        } 
  });            
}
</script>

@stop