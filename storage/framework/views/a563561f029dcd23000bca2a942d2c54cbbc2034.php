<?php $__env->startSection('content'); ?>

    <?php if($index_additional_view && ($index_additional_view['position']=='top' || !$index_additional_view['position'])): ?>
        <?php echo $__env->make($index_additional_view['view'],$index_additional_view['data'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>
    
    <div class='row'>
        <div class='col-md-12'> 

          <div  id='box_main' >

            <?php if($index_statistic): ?>
              <div id='box-statistic' class='row'>
              <?php foreach($index_statistic as $stat): ?>
                  <div  class="col-md-<?php echo e($stat['width']); ?>">
                      <div class="small-box bg-<?php echo e($stat['color']); ?>">
                        <div class="inner">
                          <h3><?php echo e($stat['count']); ?></h3>
                          <p><?php echo e($stat['label']); ?></p>
                        </div>
                        <div class="icon">
                          <i class="<?php echo e($stat['icon']); ?>"></i>
                        </div>                    
                      </div>
                  </div>
              <?php endforeach; ?>
              </div>
            <?php endif; ?>

            <?php if(Request::segment(3) == 'sub-module'): ?>
            <?php 
                $parent_module = DB::table('cms_moduls')->where('path',Request::segment(2))->first();
                
                $parent_module_class = '\crocodicstudio\crudbooster\controllers\\' . $parent_module->controller;                
                if(!class_exists($parent_module_class)) {
                  $parent_module_class = '\App\Http\Controllers\\'.$parent_module->controller;
                }
                $parent_module_class = new $parent_module_class;

                $parent_module_class->index_array = TRUE;
                $parent_module_class->show_addaction = FALSE;
                $parent_module_class->index_only_id = intval(Request::segment(4));    
                $parent_module_class->constructor();            
                $index_single = $parent_module_class->getIndex();
            ?>
            <div class='box box-primary' id='box-header-module'>
              <div class="box-header with-border">
                <h3 class="box-title"><?php echo e($parent_module->name); ?></h3>

                <div class="box-tools pull-right">
                  <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                  </button>
                </div>
                <!-- /.box-tools -->
              </div>
              <div class='box-body'>
                  <table class='table table-striped'>
                    <thead>
                    <tr>                                            
                      <?php                       
                        $parent_columns = $parent_module_class->columns_table();

                        foreach($parent_columns as $col) {                            
                            if($col['visible']===FALSE) continue;                            
                            $sort_column = Request::get('filter_column');
                            $colname = $col['label'];
                            $name = $col['name'];
                            $field = $col['field_with'];
                            $width = ($col['width'])?:"auto";
                            $mainpath = trim(mainpath(),'/').$build_query;
                            echo "<th width='$width'>$colname</th>";                            
                        }
                      ?>   

                      <?php if($priv->is_edit!=0 || $priv->is_delete!=0 || $priv->is_read!=0): ?>                      
                      <th width='100px'>Action</th>
                      <?php endif; ?>                                                               
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($index_single as $column): ?>
                        <tr>
                            <?php foreach($column as $k=>$col): ?>
                              <?php if($k==0) continue;?>
                              <td><?php echo $col; ?></td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                  </table>
              </div>
            </div>


            <ul class="nav nav-tabs sub-module-tab"> 
                <?php 
                  $subs = $parent_module_class->sub_module();
                  $parent_path = mainpath('../../..');
                  foreach($subs as $sub):
                    $active = (Request::segment(5) == $sub['path'])?"active":"";
                ?>
                  <li role="presentation" class='<?php echo e($active); ?>' title="<?php echo e($sub['label']); ?>">
                      <a href='<?php echo e($parent_path."/sub-module/".Request::segment(4)."/".$sub["path"]); ?>'><i class='<?php echo e($sub["icon"]); ?>'></i> <?php echo e($sub['label']); ?></a>
                  </li>
                <?php endforeach;?>
            </ul>

            <?php endif; ?>



            <div class='box'>
              <div class='box-body'>
                 <?php echo $__env->make("crudbooster::default.actionmenu", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
              </div>
            </div>
    
            <!-- Box -->
            <?php echo $__env->make("crudbooster::default.table", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>


          </div><!--END BOX MAIN-->

        </div><!-- /.col -->


    </div><!-- /.row -->  


    <?php if($index_additional_view && $index_additional_view['position']=='bottom'): ?>
        <?php echo $__env->make($index_additional_view['view'],$index_additional_view['data'], array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <?php endif; ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('crudbooster::admin_template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>