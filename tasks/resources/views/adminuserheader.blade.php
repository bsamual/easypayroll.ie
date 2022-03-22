 <html>
<head>
<meta charset="UTF-8">
<?php
    //header('Set-Cookie: fileDownload=true; path=/');
    header('Cache-Control: max-age=60, must-revalidate');
 ?>
 
<title></title>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/bootstrap.min.css')?>">
<script type="text/javascript" src="<?php echo URL::to('assets/js/jquery-1.11.2.min.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/bootstrap-theme.min.css')?>" />
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/font-awesome-4.2.0/css/font-awesome.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/style.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/style-responsive.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/stylesheet-image-based.css')?>">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
<link rel="stylesheet" href="<?php echo URL::to('assets/css/datepicker/jquery-ui.css')?>">
<link rel="stylesheet" href="<?php echo URL::to('assets/lightbox/colorbox.css')?>">
<script src="<?php echo URL::to('assets/js/datepicker/jquery-1.12.4.js')?>"></script>
<script src="<?php echo URL::to('assets/js/jquery.validate.js')?>"></script>
<script src="<?php echo URL::to('assets/js/jquery.number.min.js')?>"></script>
<script src="<?php echo URL::to('assets/pagination/jquery.twbsPagination.js'); ?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/dropzone/dist/dropzone.css'); ?>" />
<script type="text/javascript" src="<?php echo URL::to('assets/dropzone/dist/dropzone.js'); ?>"></script>
<script src="<?php echo URL::to('assets/js/datepicker/jquery-ui.js')?>"></script>
<script type="text/javascript" src="<?php echo URL::to('assets/js/bootstrap.min.js')?>"></script>

<script src="<?php echo URL::to('assets/lightbox/jquery.colorbox.js'); ?>"></script>

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/jscrollpane/style/jquery.jscrollpane.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/ladda/dist/ladda-themeless.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/select2/dist/css/select2.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/summernote/dist/summernote.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/datatables/media/css/dataTables.bootstrap4.min.css') ?>">


<!-- Vendors Scripts -->
<!-- v1.0.0 -->

<script src="<?php echo URL::to('assets/vendors/jquery-mousewheel/jquery.mousewheel.min.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/jscrollpane/script/jquery.jscrollpane.min.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/select2/dist/js/select2.full.min.js') ?>"></script>
<!-- <script src="<?php //echo URL::to('assets/vendors/html5-form-validation/dist/jquery.validation.min.js') ?>"></script> -->
<script src="<?php echo URL::to('assets/vendors/moment/min/moment.min.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/summernote/dist/summernote.min.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/datatables/media/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/datatables-responsive/js/dataTables.responsive.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/editable-table/mindmup-editabletable.js') ?>"></script>
<link href="<?php echo URL::to('assets/common/css/jquery-ui.css')?>" rel="stylesheet">
<!-- <script src="<?php //echo URL::to('assets/common/js/jquery-ui.js')?>"></script> -->
<link href="<?php echo URL::to('assets/common/css/jquery.ui.autocomplete.css')?>" rel="stylesheet">
<script src="<?php echo URL::to('assets/ckeditor/ckeditor.js'); ?>"></script>
<script src="<?php echo URL::to('assets/ckeditor/src/js/main.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/new_style.css')?>">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

<style>
<?php
 $segment1 =  Request::segment(2);  
  if($segment1 == 'manage_week' || $segment1 == 'week_manage' || $segment1 == 'select_week') { 
    if($segment1 == 'select_week') { ?>
      .body_bg{
        width:2030px !important;
      }
      .page_title{
        width: 100% !important;
          height: auto;
          float: left !important;
          position: fixed !important;
          top: 84px !important;
          font-weight: bold;
          text-align: left;
          padding: 5px 0px;
          margin-bottom: 20px;
          color: #000;
          font-size: 15px;
          text-transform: uppercase;
          left: 0px;
      }
      <?php } 
    else{ ?>
      .body_bg{
      }
      <?php
      } 
  } 
  elseif($segment1 == 'manage_month' || $segment1 == 'month_manage' || $segment1 == 'select_month') { 
    if($segment1 == 'select_month') { ?>
      .body_bg{
        width:2030px !important;
      }
      .page_title{
        width: 100% !important;
          height: auto;
          float: left !important;
          position: fixed !important;
          top: 84px !important;
        font-weight: bold;
        text-align: left;
        padding: 5px 0px;
        margin-bottom: 20px;
        color: #000;
        font-size: 15px;
        text-transform: uppercase;
        left: 0px;
    }
      <?php } 
    else{ ?>
      .body_bg{
      }
      <?php
    }
  } 
  elseif($segment1 == 'p30' || $segment1 == 'p30month_manage' || $segment1 == 'p30_select_month') { 
    if($segment1 == 'p30_select_month') { ?>
    .body_bg{
        background: #03d4b7;
        width:2030px !important;
      }
      .page_title{
        width: 100% !important;
          height: auto;
          float: left !important;
          position: fixed !important;
          top: 84px !important;
        font-weight: bold;
        text-align: left;
        padding: 5px 0px;
        margin-bottom: 20px;
        color: #000;
        font-size: 15px;
        text-transform: uppercase;
        left: 0px;
    }
      <?php } 
    else{ ?>
      .body_bg{
        background: #03d4b7;
        
      }
      <?php
    }
  }
  elseif($segment1 == 'gbs_p30' || $segment1 == 'gbs_p30month_manage' || $segment1 == 'gbs_p30_select_month') { 
    if($segment1 == 'gbs_p30_select_month') { ?>
    .body_bg{
        background: #03d4b7;
        width:2030px !important;
      }
      .page_title{
        width: 100% !important;
          height: auto;
          float: left !important;
          position: fixed !important;
          top: 84px !important;
        font-weight: bold;
        text-align: left;
        padding: 5px 0px;
        margin-bottom: 20px;
        color: #000;
        font-size: 15px;
        text-transform: uppercase;
        left: 0px;
    }
      <?php } 
    else{ ?>
      .body_bg{
        background: #03d4b7;
        
      }
      <?php
    }
  } 
  ?>
.dropdown-menu>.active>a, .dropdown-menu>.active>a:focus, .dropdown-menu>.active>a:hover
{
      background-color: #000 !important;
      background-image:none !important;
      color:#fff !important;
}

.dropdown-submenu {
    position: relative;
}

.dropdown-submenu > a.dropdown-item:after {
    content: "\f054";
    float: right;
}
.dropdown-submenu > a.dropdown-item:after {
    content: ">";
    float: right;
    font-weight: 800;
    margin-right: -10px;
}
.dropdown-submenu > .dropdown-menu {
    top: 0;
    left: 100%;
    margin-top: 0px;
    margin-left: 0px;
}
.dropdown-submenu:hover > .dropdown-menu {
    display: block;
}
.dropdown-menu
{
  width:200px;
}
.dropdown-header{
  font-size: 16px;
}
.modal_load_apply, .modal_load, .modal_load_content, .modal_load_available, .modal_load_browse, .modal_load_review, .modal_load_import {z-index: 999999999999999999999999999999999999 !important;}
.modal{ margin-top:4%; }
.dropdown-toggle:hover, .dropdown-toggle:active, .dropdown-toggle:focus { text-decoration: underline !important; }
</style>
</head>
<body class="body_bg">
@yield('content')
<script>

          $("#update_admin_form" ).validate({

              rules: {
                  admin_username : {required:true,},

                  newadmin_password : {required: true,},

                  confirmadmin_password : {required: true, equalTo: newadmin_password},    

              },

              messages: {

                  admin_username : "Username is required",

                  newadmin_password : "New Password is required",

                  confirmadmin_password : {

                      required: "Confirm Password is required",

                      equalTo : "Does not Match the password",

                  },

              },

          });
          $("#update_user_form" ).validate({

              rules: {
                  user_username : {required:true,},

                  newuser_password : {required: true,},

                  confirmuser_password : {required: true, equalTo: newuser_password},    

              },

              messages: {

                  user_username : "Username is required",

                  newuser_password : "New Password is required",

                  confirmuser_password : {

                      required: "Confirm Password is required",

                      equalTo : "Does not Match the password",

                  },

              },

          });
function detectPopupBlocker() {
  var myTest = window.open("about:blank","","directories=no,height=100,width=100,menubar=no,resizable=no,scrollbars=no,status=no,titlebar=no,top=0,location=no");
  if (!myTest) {
    return 1;
  } else {
    myTest.close();
    return 0;
  }
}

function SaveToDisk(fileURL, fileName) {
  
	var idval = detectPopupBlocker();
	if(idval == 1)
	{
		alert("A popup blocker was detected. Please Allow the popups to download the file.");
	}
	else{
		// for non-IE
		if (!window.ActiveXObject) {
		  var save = document.createElement('a');
		  save.href = fileURL;
		  save.target = '_blank';
		  save.download = fileName || 'unknown';
		  var evt = new MouseEvent('click', {
		    'view': window,
		    'bubbles': true,
		    'cancelable': false
		  });
		  save.dispatchEvent(evt);
		  (window.URL || window.webkitURL).revokeObjectURL(save.href);
		}
		// for IE < 11
		else if ( !! window.ActiveXObject && document.execCommand)     {
		  var _window = window.open(fileURL, '_blank');
		  _window.document.close();
		  _window.document.execCommand('SaveAs', true, fileName || fileURL)
		  _window.close();
		}
	}
	$("body").removeClass("loading");
}
$(document).ready(function() {
    $("body").removeClass("loading");
    $(".global_opening_date").datetimepicker({     
       format: 'L',
       format: 'DD-MMM-YYYY',
       widgetPositioning: { horizontal: 'left', vertical: 'top'}
    });
})

</script>

</body>
</html>