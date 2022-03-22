<html>
<head>
<title>Bubble Accounting</title>
<link rel="icon" href="<?php echo URL::to('assets/images/favicon-b.png'); ?>" sizes="32x32" />
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/bootstrap.min.css'); ?>">
<script type="text/javascript" src="<?php echo URL::to('assets/js/jquery-1.11.2.min.js'); ?>"></script>
<link href="<?php echo URL::to('assets/css/bootstrap-theme.min.css'); ?>" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/font-awesome-4.2.0/css/font-awesome.css'); ?>">

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/style_bubble.css'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/style_bubble-responsive.css'); ?>">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">

<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>

<body>

<style>
.error, .error_captcha{
  color:#f00;
}
</style>

<div class="modal fade register_modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="margin-top: 10%;">
    <div class="modal-content">
      <form id="reg_form" action="<?php echo URL::to('user/user_registration'); ?>" method="post" enctype="multipart/form-data">
      <div class="modal-header">
        <h4 class="modal-title" id="exampleModalLabel" style="float: left;">Create an Account</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div style="clear: both">
          <?php
          if(Session::has('message')) { ?>
              <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
          <?php } ?>
        </div>
          <div class="row">
            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
              <div class="form-group">
                <label for="recipient-name" class="col-form-label">Enter Practice Name:</label>
                <input type="text" name="practice_name" id="practice_name" class="form-control" placeholder="Enter Practice Name" required>
              </div>
              <div class="form-group">
                <label for="message-text" class="col-form-label">Enter Address:</label>
                <input type="text" name="address1" id="address1" class="form-control" placeholder="Enter Address" required>
              </div>
              <div class="form-group">            
                <input type="text" name="address2" id="address2" class="form-control" placeholder="Enter Address">
              </div>
              <div class="form-group">    
                <input type="text" name="address3" id="address3" class="form-control" placeholder="Enter Address">
              </div>
              <div class="form-group">   
                <input type="text" name="address4" id="address4" class="form-control" placeholder="Enter Address">
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
              <div class="form-group">
                <label for="message-text" class="col-form-label">Practice Logo:</label>
                <input type="file" name="practice_logo" id="practice_logo" class="form-control" placeholder="Choose Practice Logo" required style="background: #fff;">
              </div>
              <div class="form-group">
                <label for="message-text" class="col-form-label">Enter Telephone Number:</label>
                <input type="text" name="telephone" id="telephone" class="form-control" placeholder="Enter Telephone Number" required>
              </div>
              <div class="form-group">
                <label for="message-text" class="col-form-label">Enter Administration User:</label>
                <input type="text" name="admin_user" id="admin_user" class="form-control" placeholder="Enter Administration User" required>
              </div>
            </div>
            <!-- <div class="col-md-8">
              <div class="not-robot">
                <script src='https://www.google.com/recaptcha/api.js'></script>
                <div class="g-recaptcha" data-sitekey="6Ld5rXAUAAAAACzAVEc4dhZv5iNZj1YizfJfirdO"></div>
                <div style="margin-top: -3%;color: #f00;font-size: 13px;"></div>
                <p class="error_captcha"></p>
              </div>
            </div> -->
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary common_button_gray" data-dismiss="modal">CLOSE</button>
        <button type="submit" class="btn btn-primary common_button">REGISTER</button>
      </div>
       </form>
    </div>
  </div>
</div>


<div class="top_black_row"></div>
<div class="top_white_row float_left width_100">
  <div class="container top_container">
    <div class="row">
      <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <div class="bubble_logo">
          <img src="<?php echo URL::to('assets/images/bubble_logo.png'); ?>" class="width_100">
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        <div class="desktop_menu">
          <ul>
            <li>
              <a href="javascript:">About Us</a>
            </li>
            <li>
              <a href="javascript:">Bubble Accounting Modules <i class="fa fa-angle-down" aria-hidden="true"></i></a>
              <div class="drop_down_own">
                <div class="row">
                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="menuul">
                      <ul>
                        <li><a href="<?php echo URL::to('client-invoice-management')?>">Client & Client Invoice Management</a></li>
                        <li><a href="<?php echo URL::to('the-2-bill-manager')?>">The 2 Bill Manager</a></li>
                        <li><a href="<?php echo URL::to('practice-financials')?>">Practice Financials</a></li>
                        <li><a href="<?php echo URL::to('the-payroll-management-system')?>">The Payroll Management System</a></li>
                        <li><a href="<?php echo URL::to('the-payroll-modern-reporting-system')?>">The Payroll Modern Reporting System</a></li>
                        <li><a href="<?php echo URL::to('the-year-end-manager')?>">The Year End Manager</a></li>
                        <li><a href="<?php echo URL::to('the-client-request-system')?>">The Client Request System</a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <div class="menuul">
                      <ul>                        
                        <li><a href="<?php echo URL::to('the-vat-management-system')?>">The VAT Management System</a></li>
                        <li><a href="<?php echo URL::to('the-rct-System')?>">The RCT System</a></li>                        
                        <li><a href="<?php echo URL::to('the-cro-ard-system')?>">The CRO ARD System</a></li>
                        <li><a href="<?php echo URL::to('time-management-tools')?>">Time Management Tools</a></li>
                        <li><a href="<?php echo URL::to('the-infiles-system')?>">The Infiles System</a></li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>
            </li>
            <li><a href="javascript:">Contact Us</a></li>
          </ul>
        </div>
        
      </div>
      <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
        <div class="float_right" style="position: relative; margin-top: 24px;">
          <a href="javascript:" class="common_button login_buton">LOG IN</a>
          <a href="javascript:" class="common_button" data-toggle="modal" data-target=".register_modal">REGISTER (for free)</a>

          <div class="login_section" style="display: none;">
            <div class="row">
              <div class="col-lg-12">
                <h3 style="margin-top: 0px;">LOG IN</h3>
                <div style="clear: both">
                <?php
                if(Session::has('message')) { ?>
                    <p class="alert alert-info"><?php echo Session::get('message'); ?></p>
                <?php }
                ?>
                </div> 
              </div>
              <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <form action="<?php echo URL::to('user/login'); ?>" method="post">
                  <div class="form-group">
                    <label>Enter Username</label>
                    <input type="text" name="userid"  class="form-control" placeholder="Enter User Name" required>
                  </div>
                  <div class="form-group">
                    <label>Enter Passowrd</label>
                    <input type="Password" name="password" class="form-control" placeholder="Enter Password" required>
                  </div>
                  <div class="form-group">
                    <label>Practice Code</label>
                    <input type="text" class="form-control" placeholder="Enter Practice Code" name="practice_code" value="GBSUser" disabled="">
                  </div>
                  <div class="form-group text-right">
                    <a href="<?php echo URL::to('index2')?>" style="font-size: 13px; ">Forgot Password</a>
                  </div>
                  <div class="form-group">
                    <input type="submit" class="common_button float_right" value="LOG IN" style="font-weight: 700">
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
  </div>
</div>

<div class="banner_section float_left width_100">
  <div class="banner_section_image">
    <img src="<?php echo URL::to('assets/images/bubbleback.jpg'); ?>" >
  </div>
  <div class="content_section">
    <h1>BubbleAccounting</h1>
    <h3>Leading Practice Management Software, used by top Accounting to manage, Staff, Tasks, Clients and how they interact with each other.</h3>
    <div class="more_button">
      <a href="<?php echo URL::to('bubble-accounting')?>" class="common_button">More</a>
    </div>
  </div>
</div>
<div class="width_100" style="display: none;">
  <div class="container">
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
      <div class="module_single module_single_color1">
        <div class="title width_100 text-center">Client & Client<br/>Invoice Management</div>        
        <div class="content"></div>
        <div class="width_100 margin_top_20 text-center">
          <a href="<?php echo URL::to('index2')?>" class="common_button" style="float: none;">View More</a>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
      <div class="module_single module_single_color2">
        <div class="title width_100 text-center">The 2 Bill<br/>Manager</div>        
        <div class="content"></div>
        <div class="width_100 margin_top_20 text-center">
          <a href="<?php echo URL::to('index2')?>" class="common_button" style="float: none;">View More</a>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
      <div class="module_single module_single_color3">
        <div class="title width_100 text-center">Practice<br/>Financials</div>        
        <div class="content"></div>
        <div class="width_100 margin_top_20 text-center">
          <a href="<?php echo URL::to('index2')?>" class="common_button" style="float: none;">View More</a>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
      <div class="module_single module_single_color4">
        <div class="title width_100 text-center">Supplier & Purchase<br/>Management</div>        
        <div class="content"></div>
        <div class="width_100 margin_top_20 text-center">
          <a href="<?php echo URL::to('index2')?>" class="common_button" style="float: none;">View More</a>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
      <div class="module_single module_single_color5">
        <div class="title width_100 text-center">The Payroll<br/>Management System</div>        
        <div class="content"></div>
        <div class="width_100 margin_top_20 text-center">
          <a href="<?php echo URL::to('index2')?>" class="common_button" style="float: none;">View More</a>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
      <div class="module_single module_single_color6">
        <div class="title width_100 text-center">The Year End<br/>Manager</div>        
        <div class="content"></div>
        <div class="width_100 margin_top_20 text-center">
          <a href="<?php echo URL::to('index2')?>" class="common_button" style="float: none;">View More</a>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
      <div class="module_single module_single_color2">
        <div class="title width_100 text-center">The Client<br/>Request System</div>        
        <div class="content"></div>
        <div class="width_100 margin_top_20 text-center">
          <a href="<?php echo URL::to('index2')?>" class="common_button" style="float: none;">View More</a>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
      <div class="module_single module_single_color3">
        <div class="title width_100 text-center">The VAT<br/>Management System</div>        
        <div class="content"></div>
        <div class="width_100 margin_top_20 text-center">
          <a href="<?php echo URL::to('index2')?>" class="common_button" style="float: none;">View More</a>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
      <div class="module_single module_single_color1">
        <div class="title width_100 text-center">The RCT<br/>System</div>        
        <div class="content"></div>
        <div class="width_100 margin_top_20 text-center">
          <a href="<?php echo URL::to('index2')?>" class="common_button" style="float: none;">View More</a>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
      <div class="module_single module_single_color5">
        <div class="title width_100 text-center">The CRO<br/>ARD System</div>        
        <div class="content"></div>
        <div class="width_100 margin_top_20 text-center">
          <a href="<?php echo URL::to('index2')?>" class="common_button" style="float: none;">View More</a>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
      <div class="module_single module_single_color6">
        <div class="title width_100 text-center">Time<br/>Management Tools</div>        
        <div class="content"></div>
        <div class="width_100 margin_top_20 text-center">
          <a href="<?php echo URL::to('index2')?>" class="common_button" style="float: none;">View More</a>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12 col-xs-12">
      <div class="module_single module_single_color4">
        <div class="title width_100 text-center">The Infiles<br/>System</div>        
        <div class="content"></div>
        <div class="width_100 margin_top_20 text-center">
          <a href="<?php echo URL::to('index2')?>" class="common_button" style="float: none;">View More</a>
        </div>
      </div>
    </div>
    
  </div>
</div>

<div class="footer width_100 float_left" style="display: none;">
  <div class="container">
    <div class="footer_content width_100 float_left">
      
      <div class="row">
        
        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-12">
          <img src="<?php echo URL::to('assets/images/bubble_logo_white.png'); ?>" class="float_left" style="padding-top: 80px; width: 300px">
        </div>
        <div class="col-lg-8 col-md-8 col-xs-12 col-sm-12">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <h4 style="color: #fff">Bubble Accounting Modules</h4>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
              <div class="ul_footer">
                <ul>
                  <li><a href="<?php echo URL::to('index2')?>">Client & Client Invoice Management</a></li>
                  <li><a href="<?php echo URL::to('index2')?>">The 2 Bill Manager</a></li>
                  <li><a href="<?php echo URL::to('index2')?>">Practice Financials</a></li>
                  <li><a href="<?php echo URL::to('index2')?>">Supplier & Purchase Management</a></li>
                  <li><a href="<?php echo URL::to('index2')?>">The Payroll Management System</a></li>
                  <li><a href="<?php echo URL::to('index2')?>">The Payroll Modern Reporting System</a></li>
                  <li><a href="<?php echo URL::to('index2')?>">The Year End Manager</a></li>
                </ul>
              </div>
            </div>
            <div class="col-lg-6 col-md-6 col-xs-12 col-sm-12">
              <div class="ul_footer">
                <ul>
                  <li><a href="<?php echo URL::to('index2')?>">The Client Request System</a></li>
                  <li><a href="<?php echo URL::to('index2')?>">The VAT Management System</a></li>
                  <li><a href="<?php echo URL::to('index2')?>">The RCT System</a></li>
                  <li><a href="<?php echo URL::to('index2')?>">The CRO ARD System</a></li>
                  <li><a href="<?php echo URL::to('index2')?>">Time Management Tools</a></li>
                  <li><a href="<?php echo URL::to('index2')?>">The Infiles System</a></li>
                </ul>
              </div>
            </div>
          </div>
          
        </div>
        
      </div>
    </div>
  </div>
</div>

<div class="footer_second width_100 float_left">
  <div class="container">
    <div class="copy_right_ul float_left">
      <ul>
        <li><a href="<?php echo URL::to('index2')?>">Terms of Service</a></li>
        <li><a href="<?php echo URL::to('index2')?>">Privacy Policy</a></li>
        <li><a href="<?php echo URL::to('index2')?>">Anti-Spam Policy</a></li>
        <li><a href="<?php echo URL::to('index2')?>">Trademark</a></li>
        <li><a href="<?php echo URL::to('index2')?>">Cookie Preferences</a></li>
      </ul>
    </div>
    <div class="copy_right float_left">
        &copy; 2022 Bubble Accounting, All rights reserved.
    </div>
  </div>
</div>
<script type="text/javascript">
$(".login_buton").click(function(){
  $(".login_section").slideToggle();
});
</script>

<script src="<?php echo URL::to('assets/js/jquery.validate.js'); ?>"></script>
<script type="text/javascript" src="<?php echo URL::to('assets/js/bootstrap.min.js'); ?>"></script>

<script>
$.ajaxSetup({async:false});
    $( "#reg_form" ).validate({
      rules:
      {
        practice_name : {required: true},
        address1 : {required: true},
        practice_logo : {required: true},
        telephone : {required: true},
        admin_user : {required: true},
      },
      messages: {
        practice_name : { required : "Please Enter Practice Name", },
        address1 : { required : "Please Enter Address", },
        practice_logo : { required : "Please Choose the Practice Logo", },
        telephone : { required : "Please Enter Telephone Number", },
        admin_user : { required : "Please Enter Administration User", },
      }
      // submitHandler: function(form) {
      //      if (grecaptcha.getResponse()) {
      //          form.submit();
      //      } else {
      //          $(".error_captcha").html("Please confirm google captcha to proceed");
      //          return false;
      //      }
      //  },
    });
</script>
</body>
</html>



