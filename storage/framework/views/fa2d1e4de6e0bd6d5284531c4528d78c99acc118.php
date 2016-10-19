<!-- Bootstrap 3.3.2 -->
    <link href="<?php echo e(asset("vendor/crudbooster/assets/adminlte/bootstrap/css/bootstrap.min.css")); ?>" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="<?php echo e(asset("vendor/crudbooster/assets/adminlte/font-awesome/css")); ?>/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="http://code.ionicframework.com/ionicons/2.0.0/css/ionicons.min.css" rel="stylesheet" type="text/css" />
      	    
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script> 
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
	
	<!-- REQUIRED JS SCRIPTS -->

	<!-- jQuery 2.1.3 -->
	<script src="<?php echo e(asset ('vendor/crudbooster/assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js')); ?>"></script>
	
	<!-- Bootstrap 3.3.2 JS -->
	<script src="<?php echo e(asset ('vendor/crudbooster/assets/adminlte/bootstrap/js/bootstrap.min.js')); ?>" type="text/javascript"></script>
	<!-- AdminLTE App -->
	<script src="<?php echo e(asset ('vendor/crudbooster/assets/adminlte/dist/js/app.min.js')); ?>" type="text/javascript"></script>
	
	<link href="<?php echo e(asset("vendor/crudbooster/assets/adminlte/plugins/iCheck/all.css")); ?>" rel="stylesheet" type="text/css" />
	<!-- iCheck 1.0.1 -->
    <script src="<?php echo e(asset("vendor/crudbooster/assets/adminlte/plugins/iCheck/icheck.min.js")); ?>" type="text/javascript"></script>
    <script src="<?php echo e(asset("vendor/crudbooster/assets/js/dateformat.js")); ?>" type="text/javascript"></script>
	<!-- ChartJS 1.0.1 -->
    <script src="<?php echo e(asset('vendor/crudbooster/assets/adminlte/plugins/chartjs/Chart.min.js')); ?>"></script>		

	<!--BOOTSTRAP DATEPICKER-->	
	<script src="<?php echo e(asset ('vendor/crudbooster/assets/adminlte/plugins/datepicker/bootstrap-datepicker.js')); ?>"></script>
	<link rel="stylesheet" href="<?php echo e(asset ('vendor/crudbooster/assets/adminlte/plugins/datepicker/datepicker3.css')); ?>">

	<!--BOOTSTRAP DATERANGEPICKER-->
	<script src="<?php echo e(asset ('vendor/crudbooster/assets/adminlte/plugins/daterangepicker/moment.min.js')); ?>"></script>
	<script src="<?php echo e(asset ('vendor/crudbooster/assets/adminlte/plugins/daterangepicker/daterangepicker.js')); ?>"></script>
	<link rel="stylesheet" href="<?php echo e(asset ('vendor/crudbooster/assets/adminlte/plugins/daterangepicker/daterangepicker-bs3.css')); ?>">

	<!-- Bootstrap time Picker -->
  	<link rel="stylesheet" href="<?php echo e(asset ('vendor/crudbooster/assets/adminlte/plugins/timepicker/bootstrap-timepicker.min.css')); ?>">  	  	
	<script src="<?php echo e(asset ('vendor/crudbooster/assets/adminlte/plugins/timepicker/bootstrap-timepicker.min.js')); ?>"></script>

	<link rel='stylesheet' href='<?php echo e(asset("vendor/crudbooster/assets/fancy//source/jquery.fancybox.css")); ?>'/>
	<script src="<?php echo e(asset('vendor/crudbooster/assets/fancy/source/jquery.fancybox.pack.js')); ?>"></script> 	

	<!--SWEET ALERT-->
	<script src="<?php echo e(asset('vendor/crudbooster/assets/sweetalert/dist/sweetalert.min.js')); ?>"></script> 
	<link rel="stylesheet" type="text/css" href="<?php echo e(asset('vendor/crudbooster/assets/sweetalert/dist/sweetalert.css')); ?>">

	<!--MONEY FORMAT-->
	<script src="<?php echo e(asset('vendor/crudbooster/jquery.price_format.2.0.min.js')); ?>"></script>

	<script>			
		var ASSET_URL = "<?php echo e(asset('/')); ?>";
		var APP_NAME = "<?php echo e($appname); ?>";
		var CURRENT_MODULE_PATH = "<?php echo e(Session::get('current_mainpath')); ?>";
		var SUB_MODULE = "<?php echo e(Request::get('submodul')); ?>";
		var ADMIN_PATH = '<?php echo e(url(config("crudbooster.ADMIN_PATH"))); ?>';
		var NOTIFICATION_JSON = "<?php echo e(route('NotificationsControllerGetLatestJson')); ?>";
		var NOTIFICATION_INDEX = "<?php echo e(route('NotificationsControllerGetIndex')); ?>";
		var LOCK_SCREEN_TIME = <?php echo $setting->app_lockscreen_timeout?:30?>;
		var LOCK_SCREEN_URL = "<?php echo e(route('getLockScreen')); ?>";
	</script>
	<script src="<?php echo e(asset('vendor/crudbooster/assets/js/main.js')); ?>"></script>
	<link rel='stylesheet' href='<?php echo e(asset("vendor/crudbooster/assets/css/main.css")); ?>'/>

    <!-- Pace style -->
    <link rel="stylesheet" href="<?php echo e(asset ('vendor/crudbooster/assets/adminlte/plugins/pace/pace.min.css')); ?>">    
    <script src="<?php echo e(asset ('vendor/crudbooster/assets/adminlte/plugins/pace/pace.min.js')); ?>"></script>