<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Login Panel : <?php echo e($appname); ?></title>
    <meta name='generator' content='CRUDBooster.com'/>
    <meta name='robots' content='noindex,nofollow'/>
    <link rel="shortcut icon" href="<?php echo e(get_setting('favicon')?asset(get_setting('favicon')):asset('vendor/crudbooster/assets/logo_crudbooster.png')); ?>">

    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.2 -->
    <link href="<?php echo e(asset('vendor/crudbooster/assets/adminlte/bootstrap/css/bootstrap.min.css')); ?>" rel="stylesheet" type="text/css" />
    <!-- Font Awesome Icons -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Theme style -->
    <link href="<?php echo e(asset('vendor/crudbooster/assets/adminlte/dist/css/AdminLTE.min.css')); ?>" rel="stylesheet" type="text/css" />

    
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <link rel='stylesheet' href='<?php echo e(asset("vendor/crudbooster/assets/css/main.css")); ?>'/>
    <style type="text/css">
      .login-page, .register-page {
          background: <?php echo e(get_setting("login_background_color")?:'#dddddd'); ?> url('<?php echo e(get_setting("login_background_image")?asset(get_setting("login_background_image")):asset('vendor/crudbooster/assets/realhosting.jpg')); ?>');
          color: <?php echo e(get_setting("login_font_color")?:'#ffffff'); ?> !important;
          background-repeat: no-repeat;
          background-position: center;
          background-size: cover;
      }
      .login-box, .register-box {
        margin: 2% auto;
      }
      .login-box-body {
        box-shadow: 0px 0px 100px rgba(0,0,0,0.8);              
        background: none;
        color: <?php echo e(get_setting("login_font_color")?:'#ffffff'); ?> !important;
      }
      html,body {
        overflow: hidden;
      }
    </style>
  </head>
  <body class="login-page">

    <div class="login-box">
      <div class="login-logo">
        <a href="<?php echo e(url('/')); ?>">
            <img title='<?php echo ($appname == 'CRUDBooster')?"<b>Realhosting</b>Booster":$appname; ?>' src='<?php echo e(get_setting("logo")?asset(get_setting('logo')):asset('vendor/crudbooster/assets/realhosting.png')); ?>' style='max-width: 100%;max-height:170px'/>
        </a>
      </div><!-- /.login-logo -->      
      <div class="login-box-body">
	  
    		<?php if( Session::get('message') != '' ): ?>
        		<div class='alert alert-warning'>
        			<?php echo e(Session::get('message')); ?>

        		</div>	
    		<?php endif; ?> 
		
        <p class='login-box-msg'>Login om je sessie te starten</p>
        <form autocomplete='off' action="<?php echo e(route('postLogin')); ?>" method="post">
		  <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>" />
          <div class="form-group has-feedback">
            <input autocomplete='off'  type="text" class="form-control" name='email' required placeholder="Email"/>
            <span class="glyphicon glyphicon-user form-control-feedback"></span>
          </div>
          <div class="form-group has-feedback">
            <input autocomplete='off'  type="password" class="form-control" name='password' required placeholder="Password"/>
            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          </div>
          <div style="margin-bottom:10px" class='row'>
            <div class='col-xs-12'>
                <button type="submit" class="btn btn-success btn-block btn-flat"><i class='fa fa-lock'></i> Inloggen</button>                
            </div>
          </div>       

          <?php if(count($privileges_register)): ?>
          <div class="row">
            <div class="col-xs-12">                  
              <a class='btn btn-primary btn-block btn-flat' href='<?php echo e(route("getRegister")); ?>'><i class='fa fa-user'></i> Registreer</a>          
            </div><!-- /.col -->
          </div>
          <?php endif; ?>
          
          <div class='row'>
            <div class='col-xs-12' align="center"><p style="padding:10px 0px 10px 0px">Wachtwoord vergeten ? <a href='<?php echo e(route("getForgot")); ?>'>Klik hier</a>   </p></div>
          </div>
        </form>
        

		<br/>
        <!--a href="#">I forgot my password</a-->

      </div><!-- /.login-box-body -->

    </div><!-- /.login-box -->



    <!-- jQuery 2.1.3 -->
    <script src="<?php echo e(asset('vendor/crudbooster/assets/adminlte/plugins/jQuery/jQuery-2.1.4.min.js')); ?>"></script>
    <!-- Bootstrap 3.3.2 JS -->
    <script src="<?php echo e(asset('vendor/crudbooster/assets/adminlte/bootstrap/js/bootstrap.min.js')); ?>" type="text/javascript"></script>
  </body>
</html>