<?php $__env->startSection('content'); ?>

  <style type="text/css">
  <?php if(get_method() == 'getDetail'): ?>
    .btn-form-save, .btn-delete, .btn-browse {
      display: none;
    }
  <?php endif; ?> 
  </style>

  <!-- JQUERY QRCODE-->
  <script src="<?php echo e(asset("vendor/crudbooster/assets/jquery-qrcode/jquery.qrcode-0.12.0.min.js")); ?>"></script>  

  <!--LOAD SELECT2--> 
  <link rel='stylesheet' href='<?php echo asset("vendor/crudbooster/assets/select2/dist/css/select2.min.css")?>'/>
  <script src='<?php echo asset("vendor/crudbooster/assets/select2/dist/js/select2.full.min.js")?>'></script>
  <style>.select2-container .select2-selection--single {height: 35px}</style>
    <div class='row'>
        <div class='col-md-12'>       

            <?php if($button_show_data || $button_reload_data || $button_new_data || $button_delete_data || $index_button || $columns): ?>
            <div id='box-actionmenu' class='box'>
              <div class='box-body'>
                 <?php echo $__env->make("crudbooster::default.actionmenu", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
              </div>
            </div>
            <?php endif; ?>

            <!-- Box -->
            <div id='box_main' class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo e((Request::get('detail'))?str_replace(array("Add Data","Edit Data"),"Detail Data",$page_title):$page_title); ?></h3>
                    <div class="box-tools">
                      <?php if($button_cancel): ?><a href='<?php echo e(mainpath("?".urldecode(http_build_query(@$_GET)))); ?>' class='btn btn-default'>Cancel</a><?php endif; ?>
                      
                    </div>
                </div>
        
        <?php           
          if($data_sub_module) {
            $action_path = Route($data_sub_module->controller."GetIndex");
          }else{
            $action_path = mainpath();
          }            

          if(@!$row) {
              $action = $action_path."/add-save";
          }else{
              $action = $action_path."/edit-save/$row->id";
          }
        ?>
        <form method='post' id="form" enctype="multipart/form-data" action='<?php echo e($action); ?>'>
        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">    
        <input type='hidden' name='return_url' value='<?php echo e(@$return_url); ?>'/>      
        <input type='hidden' name='ref_mainpath' value='<?php echo e(mainpath()); ?>'/>      
        <input type='hidden' name='ref_parameter' value='<?php echo e(urldecode(http_build_query(@$_GET))); ?>'/>
                <div class="box-body">

                  <?php if($command == 'detail'): ?>
                       <?php echo $__env->make("crudbooster::default.form_detail", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>  
                  <?php else: ?>
                     <?php echo $__env->make("crudbooster::default.form_body", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>         
                  <?php endif; ?>
                </div><!-- /.box-body -->
        
                <div class="box-footer">  
                  <div class='pull-right'>                            
                      <?php if($button_cancel): ?><a href='<?php echo e(mainpath("?".urldecode(http_build_query(@$_GET)))); ?>' class='btn btn-default'>Cancel</a><?php endif; ?>
                      <?php if( ($priv->is_create || $priv->is_edit) && count($forms)!=0): ?>

                         <?php if($priv->is_create && $button_addmore==TRUE): ?>                             
                            <input type='submit' name='submit' value='Save & Add More' class='btn btn-success'/>
                         <?php endif; ?>
                         <?php if($button_save): ?>
                            <input type='submit' name='submit' value='Save' class='btn btn-success'/>
                         <?php endif; ?>
                         
                      <?php endif; ?>
                  </div>
                </div><!-- /.box-footer-->

                </form>
        
            </div><!-- /.box -->
        </div><!-- /.col -->


    </div><!-- /.row -->

    <?php 
    if($form_add) {
      echo implode("\n",$form_add);
    }
  
  ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('crudbooster::admin_template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>