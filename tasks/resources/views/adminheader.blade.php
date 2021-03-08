<html>
<head>
<title>Easypayroll - TaskAdmin</title>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/bootstrap.min.css')?>">
<script type="text/javascript" src="<?php echo URL::to('assets/js/jquery-1.11.2.min.js')?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/bootstrap-theme.min.css')?>" />
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/font-awesome-4.2.0/css/font-awesome.css')?>">

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/style.css')?>">
<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/style-responsive.css')?>">

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/css/stylesheet-image-based.css')?>">

<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">

<link rel="stylesheet" href="<?php echo URL::to('assets/css/datepicker/jquery-ui.css')?>">


<script src="<?php echo URL::to('assets/js/datepicker/jquery-1.12.4.js')?>"></script>
<script src="<?php echo URL::to('assets/js/datepicker/jquery-ui.js')?>"></script>
<script src="<?php echo URL::to('assets/js/jquery.validate.js')?>"></script>
 <script src="<?php echo URL::to('assets/ckeditor/ckeditor.js'); ?>"></script>
  <script src="<?php echo URL::to('assets/ckeditor/src/js/main.js'); ?>"></script>

<link rel="stylesheet" type="text/css" href="<?php echo URL::to('assets/vendors/datatables/media/css/dataTables.bootstrap4.min.css') ?>">
<script src="<?php echo URL::to('assets/vendors/datatables/media/js/jquery.dataTables.min.js') ?>"></script>
<script src="<?php echo URL::to('assets/vendors/datatables/media/js/dataTables.bootstrap4.min.js') ?>"></script>
    
<script src="<?php echo URL::to('assets/vendors/datatables-responsive/js/dataTables.responsive.js') ?>"></script>


<script src="<?php echo URL::to('assets/js/jscolor/jscolor.js') ?>"></script>

<style type="text/css">
  .table thead th:focus{background: #ddd !important;}
</style>
</head>
<body>

<div class="top_row">
  <div class="col-lg-12 padding_00">
    <nav class="navbar navbar-default">
      <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><img src="<?php echo URL::to('assets/images/easy_payroll_logo.png')?>" class="img-responsive" /></a>
        </div>
<?php $segment1 =  Request::segment(2); ?>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right menu">
            <!-- <li class="{{ Request::is( 'admin/manage_year') ? 'active' : '' }}"><a href="<?php //echo URL::to('admin/manage_year')?>">Manage Year</a></li> -->
            <li class="{{ Request::is( 'admin/manage_task') ? 'active' : '' }}"><a href="<?php echo URL::to('admin/manage_task')?>">Manage Year</a></li>
            <li class="{{ Request::is( 'admin/manage_user') ? 'active' : '' }}"><a href="<?php echo URL::to('admin/manage_user')?>">Manage Users</a></li>

            <li class="{{ Request::is( 'admin/central_locations') ? 'active' : '' }}"><a href="<?php echo URL::to('admin/central_locations')?>">Central Location</a></li>
            
            <li class="dropdown <?php if($segment1 == "p30_task_leval" || $segment1 == "p30_due_date" || $segment1 == "p30_period") { echo 'active'; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manage P30</a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo URL::to('admin/p30_task_leval')?>">Manage Task Level</a></li>
                    <li><a href="<?php echo URL::to('admin/p30_due_date')?>">Manage Due Date</a></li>
                    <li><a href="<?php echo URL::to('admin/p30_period')?>">Manage Period</a></li>
                </ul>
            </li>
            <!-- <li class="dropdown <?php if($segment1 == "gbs_p30_task_leval" || $segment1 == "gbs_p30_due_date" || $segment1 == "gbs_p30_period") { echo 'active'; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manage Gbsco P30</a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo URL::to('admin/gbs_p30_task_leval')?>">Manage Task Level</a></li>
                    <li><a href="<?php echo URL::to('admin/gbs_p30_due_date')?>">Manage Due Date</a></li>
                    <li><a href="<?php echo URL::to('admin/gbs_p30_period')?>">Manage Period</a></li>
                </ul>
            </li> -->
            <li class="dropdown <?php if($segment1 == "manage_rctclients" || $segment1 == "manage_rctemail_salution" || $segment1 == "manage_rctbackground" || $segment1 == "rct_profile") { echo 'active'; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manage RCT</a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo URL::to('admin/manage_rctclients')?>">Manage Clients</a></li>
                    <li><a href="<?php echo URL::to('admin/manage_rctemail_salution')?>">Email Salution</a></li>
                    <li><a href="<?php echo URL::to('admin/manage_rctbackground')?>">Letterpad Background</a></li>
                    
                </ul>
            </li>
            <li class="dropdown <?php if($segment1 == "manage_cm_class" || $segment1 == "manage_cm_paper" || $segment1 == "manage_cm_fields" || $segment1 == "cm_clients_list" || $segment1 == "cm_profile") { echo 'active'; } ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Manage CM System</a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo URL::to('admin/cm_clients_list')?>">Edit Client's Class</a></li>
                    <li><a href="<?php echo URL::to('admin/manage_cm_fields')?>">Manage Column</a></li>
                    <li><a href="<?php echo URL::to('admin/manage_cm_class')?>">Add/Remove Class</a></li>
                    <li><a href="<?php echo URL::to('admin/manage_cm_paper')?>">Manage Print Label</a></li>     
                    <li><a href="<?php echo URL::to('admin/cm_profile')?>">CM system Crypt Pin</a></li>               
                </ul>
            </li>
            <!-- <li class="<?php if($segment1 == "setup_request_category") { echo 'active'; } ?>""><a href="<?php echo URL::to('admin/setup_request_category')?>">Setup Request Categories</a></li> -->
            <li class="<?php if($segment1 == "clear_opening_balance") { echo 'active'; } ?>"><a href="<?php echo URL::to('admin/clear_opening_balance')?>">Clear Opening Balance</a></li>
            <li class="dropdown <?php if($segment1 == "profile" || $segment1 == "vat_profile" || $segment1 == "manage_cro") { echo 'active'; } ?>"><a href="javascript:" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Profile</a>
                <ul class="dropdown-menu">
                    <li><a href="<?php echo URL::to('admin/profile')?>">Manage Admin/User Login</a></li>
                    <li><a href="<?php echo URL::to('admin/manage_cro')?>">Manage CRO</a></li>
                    <li><a href="<?php echo URL::to('admin/vat_profile')?>">VAT Email Signature</a></li>
                    <li><a href="<?php echo URL::to('admin/email_settings')?>">Email Settings</a></li>
                </ul>
            </li>
            <li><a href="<?php echo URL::to('admin/logout')?>">Logout</a></li>
            
            
          </ul>
          
          
        </div><!-- /.navbar-collapse -->
      </div><!-- /.container-fluid -->
  </nav>
  </div>
   
</div>
@yield('content')
<div class="footer_row">© Copyright <?php echo date('Y'); ?> All Rights Reserved EasyPayroll</div>

<script type="text/javascript" src="<?php echo URL::to('assets/js/bootstrap.min.js')?>"></script>
</body>
</html>