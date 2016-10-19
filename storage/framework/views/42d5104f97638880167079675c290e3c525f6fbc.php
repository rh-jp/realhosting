<?php $__env->startSection('content'); ?>   

<ul class="nav nav-tabs">
        <li class="active"><a href="<?php echo e(mainpath()); ?>"><i class='fa fa-file'></i> API Documentation</a></li>
        <li><a href="<?php echo e(mainpath('screet-key')); ?>"><i class='fa fa-key'></i> API Screet Key</a></li>
        <li><a href="<?php echo e(mainpath('generator')); ?>"><i class='fa fa-cog'></i> API Generator</a></li>        
      </ul>

      <div class='box'>
        
        <div class='box-body'>
      
            <style>
            .table-api tbody tr td a {
              color: #db0e00;
              font-family: arial;
            }
            </style> 
            
            <script>
            $(function() {
              $(".link_name_api").click(function() {
                  $(".detail_api").slideUp();
                  $(this).parent("td").find(".detail_api").slideDown();
              })
              $(".selected_text").each(function() {
                    var n = $(this).text();
                    if(n.indexOf('api_')==0) {
                        $(this).attr('class','selected_text text-danger');
                    }            
              })
            })
            function deleteApi(id) {
              var url = "<?php echo e(url(config('crudbooster.ADMIN_PATH').'/api_generator/delete-api')); ?>/"+id;
              swal({   title: "Are you sure?",   text: "You will not be able to recover this data!",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, delete it!",   closeOnConfirm: false }, function(){   
                  $.get(url,function(resp) {
                    if(resp.status == 1) {
                      swal("Deleted!", "The data has been deleted.", "success"); 
                      location.href = document.location.href;
                    }
                  })                  
              });
            }
            </script> 
               
          <div class='form-group'>
              <label>API BASE URL</label>
              <input type='text' readonly class='form-control' title='Hanya klik dan otomatis copy to clipboard (kecuali Safari)' onClick="this.setSelectionRange(0, this.value.length); document.execCommand('copy');" value='<?php echo e(url('api')); ?>'/>
          </div>
          <div class='form-group'>
              <label>How To Use</label><br/>
              SCREETKEY : ABCDEF123456 <br/>
              TIME : UNIX CURRENT TIME  <br/>                        
              <label>Header :</label><br/>                          
                X-Authorization-Token : md5( SCREETKEY + TIME + USER_AGENT )<br/>
                X-Authorization-Time  : TIME                          
          </div>
          <table class='table table-striped table-api table-bordered'>
              <thead>
                  <tr class='info'><th width='2%'>No</th><th>API Name
                    <span class='pull-right'>
                      <a class='btn btn-xs btn-warning' target="_blank" href='<?php echo e(mainpath("download-postman")); ?>'>Export For POSTMAN <sup>Beta</sup></a>
                    </span>
                  </th></tr>
              </thead> 
              <tbody>
                  <?php $no = 0;?>
                  <?php foreach($apis as $api): ?>
                  <?php 
                    $parameters = ($api->parameters)?unserialize($api->parameters):array();
                    $responses  = ($api->responses)?unserialize($api->responses):array();
                  ?>
                                <tr>
                                    <td><?= ++$no;?></td>
                                    <td> 
                                      <a href='javascript:void(0)' title='API <?php echo e($ac->nama); ?>' style='color:#009fe3' class='link_name_api'><?=$api->nama;?></a> &nbsp; 
                                      <sup>
                                        <a title='Delete this API' onclick="deleteApi(<?php echo e($api->id); ?>)" href="javascript:void(0)"><i class='fa fa-trash'></i></a>
                                        &nbsp; <a title='Edit This API' href="<?php echo e(url(config('crudbooster.ADMIN_PATH').'/api_generator/edit-api').'/'.$api->id); ?>"><i class='fa fa-pencil'></i></a> 
                                      </sup>
                                      <div class='detail_api' style='display:none'>
                                          <table class='table table-bordered'>
                                          <tr><td width='12%'><strong>URL</strong></td><td><input title='Click and copied !' type='text' class='form-control' readonly onClick="this.setSelectionRange(0, this.value.length); document.execCommand('copy');" value="/<?php echo e($api->permalink); ?>"/></td></tr>
                                          <tr><td><strong>METHOD</strong></td><td><?php echo e(strtoupper($api->method_type)); ?></td></tr>
                                          <tr><td><strong>PARAMETER</strong></td><td>
                                            <table class='table table-bordered table-hover'>
                                              <thead><tr class='active'><th width="3%">No</th><th width="5%">Type</th><th>Parameter Names</th><th>Description / Validate / Rule</th><th>Mandatory</th></tr></thead>
                                              <tbody>
                                                  <?php $i = 0;?>
                                                  <?php foreach($parameters as $param): ?>
                                                    <?php if($param['used']): ?>
                                                    <tr>
                                                      <td><?php echo e(++$i); ?></td>
                                                      <td width="5%"><em><?php echo e($param['type']); ?></em></td>
                                                      <td><?php echo e($param['name']); ?></td>    
                                                      <td><?php echo e($param['config']); ?></td> 
                                                      <td><?php echo ($param['required'])?"<span class='label label-primary'>REQUIRED</span>":"<span class='label label-default'>OPTIONAL</span>"; ?></td>                   
                                                    </tr>
                                                    <?php endif; ?>
                                                  <?php endforeach; ?> 
                                                  <?php if($i == 0): ?>
                                                  <tr><td colspan='4' align="center"><i class='fa fa-search'></i> There is no parameter</td></tr>
                                                  <?php endif; ?>                                                 
                                              </tbody>
                                            </table>
                                          </td></tr>
                                          <tr><td><strong>RESPONSE</strong></td><td>
                                            <table class='table table-bordered table-hover'>
                                              <thead><tr class='active'><th width="3%">No</th><th width="5%">Type</th><th>Response Names</th></tr></thead>
                                              <tbody>
                                                  <?php $i = 1;?>
                                                  <tr><td><?php echo e($i++); ?></td><td><em>integer</em></td><td>api_status</td></tr>
                                                  <tr><td><?php echo e($i++); ?></td><td><em>string</em></td><td>api_message</td></tr>
                                                  
                                                  <?php if($api->aksi == 'list'): ?>
                                                  <tr class='active'><td>#</td><td>Array</td><td><strong>data</strong></td></tr>
                                                  <?php endif; ?>

                                                  <?php if($api->aksi == 'list' || $api->aksi == 'detail'): ?>
                                                    <?php foreach($responses as $resp): ?>
                                                      <?php if($resp['used']): ?>
                                                      <tr>
                                                        <td><?php echo e($i++); ?></td>
                                                        <td width="5%"><em><?php echo e($resp['type']); ?></em></td>
                                                        <td><?php echo e(($api->aksi=='list')?'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- ':''); ?> <?php echo e($resp['name']); ?></td>                                                   
                                                      </tr>
                                                      <?php endif; ?>
                                                    <?php endforeach; ?>
                                                  <?php endif; ?>

                                                  <?php if($api->aksi == 'save_add'): ?>
                                                    <tr><td width="5%"><?php echo e($i++); ?></td><td><em>integer</em></td><td>id</td></tr>
                                                  <?php endif; ?>
                                              </tbody>
                                            </table>

                                          </td></tr>
                                          <tr><td><strong>DESCRIPTION</strong></td><td><em><?php echo $api->keterangan; ?></em></td></tr>
                                          </table>
                                      </div>
                                    </td>
                                </tr>
                <?php endforeach; ?>
                
              </tbody>
          </table>                



    </div><!--END BODY-->
</div><!--END BOX-->

<?php $__env->stopSection(); ?>
<?php echo $__env->make('crudbooster::admin_template', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>