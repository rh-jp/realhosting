<?php $__env->startSection('content'); ?>


            <?php if($button_show_data || $button_reload_data || $button_new_data || $button_delete_data || $index_button || $columns): ?>
            <div id='box-actionmenu' class='box'>
              <div class='box-body'>
                 <?php echo $__env->make("crudbooster::default.actionmenu", array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
              </div>
            </div>
            <?php endif; ?>


            <?php if(Request::get('file') && Request::get('import')): ?>

            <ul class='nav nav-tabs'>
                    <li style="background:#eeeeee"><a style="color:#111" onclick="if(confirm('Are you sure want to leave ?')) location.href='<?php echo e(mainpath("import-data")); ?>'" href='javascript:;'><i class='fa fa-download'></i> Upload a File &raquo;</a></li>
                    <li style="background:#eeeeee" ><a style="color:#111" href='#'><i class='fa fa-cogs'></i> Adjustment &raquo;</a></li>
                    <li style="background:#ffffff"  class='active'><a style="color:#111" href='#'><i class='fa fa-cloud-download'></i> Importing &raquo;</a></li>
            </ul>

            <!-- Box -->
            <div id='box_main' class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Importing</h3>
                    <div class="box-tools">                      
                    </div>
                </div>
                    
                <div class="box-body">
                    
                    <p style='font-weight: bold' id='status-import'><i class='fa fa-spin fa-spinner'></i> Please wait importing...</p>
                    <div class="progress">
                      <div id='progress-import' class="progress-bar progress-bar-primary progress-bar-striped" role="progressbar" aria-valuenow="40" 
                      aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                        <span class="sr-only">40% Complete (success)</span>
                      </div>
                    </div>                    

                    <script type="text/javascript">
                      $(function() {
                        var total = <?php echo e(intval(Session::get('total_data_import'))); ?>;
                        
                        var int_prog = setInterval(function() {

                          $.post("<?php echo e(mainpath('do-import-chunk?file='.Request::get('file'))); ?>",{resume:1},function(resp) {                                       
                              console.log(resp.progress);
                              $('#progress-import').css('width',resp.progress+'%');
                              $('#status-import').html("<i class='fa fa-spin fa-spinner'></i> Please wait importing... ("+resp.progress+"%)");
                              $('#progress-import').attr('aria-valuenow',resp.progress);
                              if(resp.progress >= 100) {
                                $('#status-import').addClass('text-success').html("<i class='fa fa-check-square-o'></i> Import Data Completed !");
                                clearInterval(int_prog);
                              }
                          })
                          
                          
                        },2500);

                        $.post("<?php echo e(mainpath('do-import-chunk').'?file='.Request::get('file')); ?>",function(resp) {
                            if(resp.status==true) {
                              $('#progress-import').css('width','100%');
                              $('#progress-import').attr('aria-valuenow',100);
                              $('#status-import').addClass('text-success').html("<i class='fa fa-check-square-o'></i> Import Data Completed !");
                              clearInterval(int_prog);
                              $('#upload-footer').show();
                              console.log('Import Success');
                            }
                        })

                      })

                    </script>

                </div><!-- /.box-body -->
        
                <div class="box-footer" id='upload-footer' style="display:none">  
                  <div class='pull-right'>                            
                      <a href='<?php echo e(mainpath("import-data")); ?>' class='btn btn-default'><i class='fa fa-upload'></i> Upload Other File</a> 
                      <a href='<?php echo e(mainpath()); ?>' class='btn btn-success'>Finish</a>                                
                  </div>
                </div><!-- /.box-footer-->
                
            </div><!-- /.box -->
            <?php endif; ?>

            <?php if(Request::get('file') && !Request::get('import')): ?>

            <ul class='nav nav-tabs'>
                    <li style="background:#eeeeee"><a style="color:#111" onclick="if(confirm('Are you sure want to leave ?')) location.href='<?php echo e(mainpath("import-data")); ?>'" href='javascript:;'><i class='fa fa-download'></i> Upload a File &raquo;</a></li>
                    <li style="background:#ffffff"  class='active'><a style="color:#111" href='#'><i class='fa fa-cogs'></i> Adjustment &raquo;</a></li>
                    <li style="background:#eeeeee"><a style="color:#111" href='#'><i class='fa fa-cloud-download'></i> Importing &raquo;</a></li>
            </ul>

            <!-- Box -->
            <div id='box_main' class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Adjustment</h3>
                    <div class="box-tools">
                                          
                    </div>
                </div>
        
                  <?php           
                    if($data_sub_module) {
                      $action_path = Route($data_sub_module->controller."GetIndex");
                    }else{
                      $action_path = mainpath();
                    }            

                    $action = $action_path."/done-import?file=".Request::get('file').'&import=1';
                  ?>

                <form method='post' id="form" enctype="multipart/form-data" action='<?php echo e($action); ?>'>
                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">             
                        <div class="box-body table-responsive no-padding">
                              <div class='callout callout-info'>
                                  * Just ignoring the column where you are not sure the data is suit with the column or not.<br/>
                                  * Warning !, Unfortunately at this time, the system can't import column that contains image or photo url.
                              </div>
                              <style type="text/css">
                                th, td {
                                    white-space: nowrap;
                                }                                
                              </style>
                              <table class='table table-bordered' style="width:130%">
                                  <thead>
                                      <tr class='success'>
                                          <?php foreach($table_columns as $k=>$column): ?>
                                            <?php
                                            $help = ''; 
                                            if($column == 'id' || $column == 'created_at' || $column == 'updated_at' || $column == 'deleted_at') continue;
                                            if(substr($column,0,3) == 'id_') {
                                              $relational_table = substr($column, 3);
                                              $help = "<a href='#' title='This is foreign key, so the System will be inserting new data to table `$relational_table` if doesn`t exists'><strong>(?)</strong></a>";
                                            }
                                            ?>
                                            <th data-no-column='<?php echo e($k); ?>'><?php echo e($column); ?> <?php echo $help; ?></th>
                                          <?php endforeach; ?>
                                      </tr>                                      
                                  </thead>
                                  <tbody>
          
                                        <tr>
                                        <?php foreach($table_columns as $k=>$column): ?>
                                            <?php if($column == 'id' || $column == 'created_at' || $column == 'updated_at' || $column == 'deleted_at') continue;?>
                                            <td data-no-column='<?php echo e($k); ?>'>
                                                <select style='width:120px' class='form-control select_column' name='select_column[<?php echo e($k); ?>]'>
                                                    <option value=''>** Set Column for <?php echo e($column); ?></option>
                                                    <?php foreach($data_import_column as $import_column): ?>
                                                    <option value='<?php echo e($import_column); ?>'><?php echo e($import_column); ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                        <?php endforeach; ?>
                                        </tr>
                                  </tbody>
                              </table>


                        </div><!-- /.box-body -->

                        <script type="text/javascript">
                          $(function(){    
                              var total_selected_column = 0;                      
                              setInterval(function() {
                                  total_selected_column = 0;
                                  $('.select_column').each(function() {
                                      var n = $(this).val();
                                      if(n) total_selected_column = total_selected_column + 1;
                                  })
                              },200);                              
                          })
                          function check_selected_column() {
                              var total_selected_column = 0;
                              $('.select_column').each(function() {
                                  var n = $(this).val();
                                  if(n) total_selected_column = total_selected_column + 1;
                              })
                              if(total_selected_column == 0) {
                                swal("Oops...", "Please at least 1 column that should adjusted...", "error");
                                return false;
                              }else{
                                return true;
                              }
                          }
                        </script>
                
                        <div class="box-footer">  
                          <div class='pull-right'>                            
                              <a onclick="if(confirm('Are you sure want to leave ?')) location.href='<?php echo e(mainpath("import-data")); ?>'" href='javascript:;' class='btn btn-default'>Cancel</a>  
                              <input type='submit' class='btn btn-primary' name='submit' onclick='return check_selected_column()' value='Import Data'/>   
                          </div>
                        </div><!-- /.box-footer-->
                </form>
            </div><!-- /.box -->


            <?php endif; ?>

            <?php if(!Request::get('file')): ?>
            <ul class='nav nav-tabs'>
                    <li style="background:#ffffff" class='active'><a style="color:#111" onclick="if(confirm('Are you sure want to leave ?')) location.href='<?php echo e(mainpath("import-data")); ?>'" href='javascript:;'><i class='fa fa-download'></i> Upload a File &raquo;</a></li>
                    <li style="background:#eeeeee"><a style="color:#111" href='#'><i class='fa fa-cogs'></i> Adjustment &raquo;</a></li>
                    <li style="background:#eeeeee"><a style="color:#111" href='#'><i class='fa fa-cloud-download'></i> Importing &raquo;</a></li>
            </ul>

            <!-- Box -->
            <div id='box_main' class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Upload a File</h3>
                    <div class="box-tools">
                                          
                    </div>
                </div>
        
                  <?php           
                    if($data_sub_module) {
                      $action_path = Route($data_sub_module->controller."GetIndex");
                    }else{
                      $action_path = mainpath();
                    }            

                    $action = $action_path."/do-upload-import-data";
                  ?>

                <form method='post' id="form" enctype="multipart/form-data" action='<?php echo e($action); ?>'>
                        <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">             
                        <div class="box-body">

                            <div class='callout callout-success'>
                                  <h4>Welcome to Data Importer Tool</h4>
                                  Before doing upload a file, its better to read this bellow instructions : <br/>
                                  * File format should be : xls or xlsx or csv<br/>
                                  * If you have a big file data, we can't guarantee. So, please split those files into some parts of file (at least max 5 MB).<br/>
                                  * This tool is generate data automatically so, be carefull about your table xls structure. Please make sure correctly the table structure.<br/>
                                  * Table structure : Line 1 is heading column , and next is the data.  (For example, you can export any module you wish to XLS format)                                                                
                              </div>

                            <div class='form-group'>
                                <label>File XLS / CSV</label>
                                <input type='file' name='userfile' class='form-control' required />
                                <div class='help-block'>File type supported only : XLS, XLSX, CSV</div>
                            </div>
                        </div><!-- /.box-body -->
                
                        <div class="box-footer">  
                          <div class='pull-right'>                            
                              <a href='<?php echo e(mainpath()); ?>' class='btn btn-default'>Cancel</a>  
                              <input type='submit' class='btn btn-primary' name='submit' value='Upload'/>   
                          </div>
                        </div><!-- /.box-footer-->
                </form>
            </div><!-- /.box -->


             <?php endif; ?>
        </div><!-- /.col -->


    </div><!-- /.row -->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('crudbooster::admin_template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>