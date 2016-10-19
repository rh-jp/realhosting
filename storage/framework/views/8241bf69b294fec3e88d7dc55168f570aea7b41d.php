<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo e(($page_title)?$appname.': '.strip_tags($page_title):"Admin Area"); ?></title>
	<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>" />
	<meta name='generator' content='CRUDBooster.com'/>
    <meta name='robots' content='noindex,nofollow'/>
    <link rel="shortcut icon" href="<?php echo e(get_setting('favicon')?asset(get_setting('favicon')):asset('vendor/crudbooster/assets/logo_crudbooster.png')); ?>">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    
    <?php echo $__env->make('crudbooster::admin_template_plugins', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    
    <!-- Theme style -->
    <link href="<?php echo e(asset("vendor/crudbooster/assets/adminlte/dist/css/AdminLTE.min.css")); ?>" rel="stylesheet" type="text/css" />    
    <link href="<?php echo e(asset("vendor/crudbooster/assets/adminlte/dist/css/skins/_all-skins.min.css")); ?>" rel="stylesheet" type="text/css" />      

    <!-- load js -->
    <script type="text/javascript">
      var site_url = "<?php echo e(url('/')); ?>" ;
      $(function() {
        $(document).ajaxStart(function() { Pace.restart(); });
      })
      <?php if($script_js): ?>
        <?php echo $script_js; ?>

      <?php endif; ?> 
    </script>
    <?php if($load_js): ?>
      <?php foreach($load_js as $js): ?>
        <script src="<?php echo e($js); ?>"></script>
      <?php endforeach; ?>
    <?php endif; ?>
    <style type="text/css">
        .dropdown-menu-action {left:-130%;}
        .btn-group-action .btn-action {cursor: default}
        #box-header-module {box-shadow:10px 10px 10px #dddddd;}
        .sub-module-tab li {background: #F9F9F9;cursor:pointer;}
        .sub-module-tab li.active {background: #ffffff;box-shadow: 0px -5px 10px #cccccc}
        .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {border:none;}
        .nav-tabs>li>a {border:none;}        
        .select2-container--default .select2-selection--single {border-radius: 0px !important}
    </style>
</head>
<body class="<?php echo (Session::get('theme_color'))?:'skin-blue'?>">
<div class='alert_floating' style='display:none'>
	<i class='fa fa-exclamation-triangle'></i> <span class='message'>Please wait...</span>
</div>
<div class="wrapper">

    <!-- Header -->
    <?php echo $__env->make('crudbooster::header', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <!-- Sidebar -->
    <?php echo $__env->make('crudbooster::sidebar', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
		

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
            	<i class='<?php echo @($priv)?$priv->icon:"fa fa-bars"?>'></i> 
            	<?php 
            		if(Request::get("parent_module")) {
            			$url = url(config('crudbooster.ADMIN_PATH').Request::get("parent_module"));
            			echo "<a href='$url'>".Request::get("parent_name")."</a> &raquo; ";
            		}

            		$page_title = (Request::get('detail'))?str_replace(array("Add Data","Edit Data"),"Detail Data",$page_title):$page_title;
            	?>
                <?php echo isset($page_title) ? $page_title : "Page Title"; ?>

                <small><?php echo isset($page_description) ? $page_description : null; ?></small>
            </h1>     
            <ol class="breadcrumb">
                <li><a title='Main Dashboard' href="<?php echo e(url(config('crudbooster.ADMIN_PATH'))); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a title='<?php echo e($parent_module_name); ?>' href="<?php echo e($dashboard); ?>"><i class='<?php echo @($priv)?$priv->icon:"fa fa-bars"?>'></i> <?php echo e($parent_module_name?:'Dashboard'); ?></a></li>
                <?php if(Request::segment(3) == 'sub-module'): ?>                    
                    <li><a title='<?php echo e($data_sub_module->name); ?>' href='<?php echo e(mainpath()); ?>'><i class='<?php echo e($data_sub_module->icon); ?>'></i> <?php echo e($data_sub_module->name); ?> </a></li>
                <?php endif; ?>
                <li class="active">You Are Here</li>
            </ol>       
        </section>
		
		

        <!-- Main content -->
        <section id='content_section' class="content">

        	<?php if(@$alerts): ?>
        		<?php foreach(@$alerts as $alert): ?>
        			<div class='callout callout-<?php echo e($alert[type]); ?>'>        				
        					<?php echo $alert['message']; ?>

        			</div>
        		<?php endforeach; ?>
        	<?php endif; ?>


			<?php if(Session::get('message')!=''): ?>
			<div class='alert alert-<?php echo e(Session::get("message_type")); ?>'>
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h4><i class="icon fa fa-info"></i> <?php echo e(ucfirst(Session::get("message_type"))); ?></h4>
				<?php echo Session::get('message'); ?>

			</div>
			<?php endif; ?>
            <!-- Your Page Content Here -->
            <?php echo $__env->yieldContent('content'); ?>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!-- Footer -->
    <?php echo $__env->make('crudbooster::footer', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>

</div><!-- ./wrapper -->



<!-- Optionally, you can add Slimscroll and FastClick plugins.
      Both of these plugins are recommended to enhance the
      user experience -->
</body>
</html>