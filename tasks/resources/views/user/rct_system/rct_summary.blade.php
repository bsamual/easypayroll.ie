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
.form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control{background-color: #dddddd !important;}
.error{color: #f00;}

body.loading {
    overflow: hidden;   
}
body.loading .modal_load {
    display: block;
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


</style>

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
	</div>
	<div class="row" style="background: #fff">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" >
	      <ul class="nav nav-tabs" style="margin-top: 20px;">
	        <li class="nav-item">
	          <a class="nav-link" href="<?php echo URL::to('user/rct_system'); ?>">RCT Manager</a>
	        </li>
	        <li class="nav-item active">
	          <a class="nav-link" href="<?php echo URL::to('user/rct_summary'); ?>">RCT Summary</a>
	        </li>
	      </ul>
	    </div>
	</div>
	<div class="row" style="background: #fff">
		<div class="col-lg-2" style="padding-top: 20px; padding-bottom: 20px; width: 300px;">
			<div style="float: left; line-height: 35px; margin-right: 20px;">From:</div>
			<div style="float: left; width: 200px;">
				<select class="form-control from_class">
					<option value="">Please select From</option>

					<?php
		              $current_month = date('Y-m');

		            $prevdate = date("Y-m-05", strtotime("-1 months"));
		            //$prev_date2 = date('Y-m', strtotime($prevdate));
		              $active_drop='<option value="'.$current_month.'">'.date('M-Y', strtotime($current_month)).'</option>';
		              for($i=0;$i<=22;$i++)
		              {
		                $month = $i + 1;
		                $newdate = date("Y-m-05", strtotime("-".$month." months"));
		                $formatted_date = date('M-Y', strtotime($newdate));
		                $formatted_date2 = date('Y-m', strtotime($newdate));

		                //if($prev_date2 == $formatted_date2){ $checked = 'selected'; } else { $checked = ''; }
		                $active_drop.='<option value="'.$formatted_date2.'">'.$formatted_date.'</option>';
		              }
		              echo $active_drop;
		            ?>
				</select>
				<label class="error" id="from_error" style="display: none">Please select a month</label>
			</div>
			
		</div>
		<div class="col-lg-3" style="padding-top: 20px; padding-bottom: 20px;">
			<div style="float: left; line-height: 35px; margin-right: 20px;">To:</div>
			<div style="float: left; width: 200px;">
				<select class="form-control to_class" disabled>
					<option value="">Please select to</option>					
				</select>
				<label class="error" id="to_error" style="display: none">Please select a month</label>
			</div>
			<div style="float: left; padding-top: 0px;">
				<a href="javascript:" class="common_black_button load_button" style="width: 100px; float: left;">Load</a>
			</div>
		</div>

		<div class="col-lg-12">
			<div class="table-responsive" id="result_table" style="max-height: 850px;">
				
			</div>
		</div>
	</div>
</div>

<div class="modal_load"><h5 style="text-align: center;margin-top: 30%;font-weight: 800;font-size: 18px;">Please Wait...</h5></div>

<script>
$(window).change(function(e){

if($(e.target).hasClass('from_class')){
	var value = $(e.target).val();

	if(value == ''){
		$("#from_error").show();
		$(".to_class").prop("disabled", true);
	}
	else{
		$("#from_error").hide();
		$.ajax({
	      url:"<?php echo URL::to('user/rct_summary_filter'); ?>",
	      type:"post",
	      dataType:"json",
	      data:{from:value},
	      success: function(result)
	      {      	
	      	$(".to_class").html(result['to_month']);

	      	if(value == ''){
	      		$(".to_class").prop("disabled", true);
	      	}
	      	else{
	      		$(".to_class").prop("disabled", false);
	      	}
	        
	      }
	    })
	}	
}

if($(e.target).hasClass('to_class')){
	var value = $(e.target).val();
	if(value == ''){
		$("#to_error").show();
	}
	else{
		$("#to_error").hide();
	}

}



})

$(window).click(function(e){

if($(e.target).hasClass('load_button')){
	var from_date = $(".from_class").val();
	var to_date = $(".to_class").val();

	if(from_date == ''){
		$("#from_error").show();
	}
	else if(to_date == ''){
		$("#from_error").hide();
		$("#to_error").show();
	}
	else{
		$("#from_error").hide();
		$("#to_error").hide();
		$("body").addClass("loading");
		setTimeout(function() {
			$.ajax({
		      url:"<?php echo URL::to('user/rct_summary_result'); ?>",
		      type:"post",
		      dataType:"json",
		      data:{from_date:from_date, to_date:to_date},
		      success: function(result)
		      {
		      	$("#result_table").html(result['output']);
		      	$("body").removeClass("loading");		        
		      }
		    })
	    },500);
	}

	
}

})



</script>

@stop