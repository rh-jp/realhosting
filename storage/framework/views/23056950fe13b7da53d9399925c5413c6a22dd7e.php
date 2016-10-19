					<style type="text/css">
					.form-divider {
						padding:10px 0px 10px 0px;
						margin-bottom: 10px;
						border-bottom:1px solid #dddddd;
					}
					.header-title {
						cursor: pointer;
					}
					i.fa-calendar {cursor:pointer;}
					</style>
					<script src="<?php echo e(asset('vendor/laravel-filemanager/js/lfm.js')); ?>"></script>
					<script type="text/javascript">
						$(function() {
							if (typeof event_header_click === 'undefined') {	
								event_header_click = true;						    						
								$(document).on("click",".header-title",function() {
									console.log("header title click");
									var index = $(this).attr('id').replace("header","");
									var handel = $(this);
									var parent = $(this).parent('.box-body');
									var first_group = parent.find(".header-group-"+index+":first").is(":hidden");
									if(first_group) {
										parent.find(".header-group-"+index).slideDown(function() {
											handel.find(".icon i").attr('class','fa fa-minus-square-o');
											handel.attr("title","Click here to slide up");
										});										
									}else{
										parent.find(".header-group-"+index).slideUp(function() {
											handel.find(".icon i").attr('class','fa fa-plus-square-o');
											handel.attr("title","Click here to expand");
										});										
									}								
								})
								$(".header-title").each(function() {
									var data_collapsed = $(this).attr('data-collapsed');
									console.log("header title "+data_collapsed);
									if(data_collapsed == 'false') {
										console.log("collapsed false");
										$(this).click();
									}
								})
							}

							$('.form-datepicker i').click(function() {
								console.log('i datepicker');
								$(this).parent().parent().find('input').click();
							})
						})						
					</script>

					<?php if(count($forms)==0):?>
                			<div class='callout callout-danger'>
                					<h4>Oops Sorry !</h4>
                					<p>Sorry this modul there is no feature for add new data</p>
                			</div>
                	<?php endif;?>

                	<?php 
                		$header_group_class = "";
                		foreach($forms as $index=>$form):
                			
                			$name 		= $form['name'];
                			@$join 		= $form['join'];
                			@$value		= (isset($form['value']))?$form['value']:'';
                			@$value		= (isset($row->{$name}))?$row->{$name}:$value;

                			$old 		= old($name);
                			$value 		= (!empty($old))?$old:$value;
                			
                			$validation = array();
                			$validation_raw = isset($form['validation'])?explode('|',$form['validation']):array();
                			if($validation_raw) {
                				foreach($validation_raw as $vr) {
                					$vr_a = explode(':',$vr);
                					if($vr_a[1]) {
                						$key = $vr_a[0];
                						$validation[$key] = $vr_a[1];
                					}else{
                						$validation[$vr] = TRUE;
                					}
                				}
                			}

                			// if( ($parent_field && $name == $parent_field) && !isset($form['visible']) ) continue;
                			if($parent_field && $parent_field == $name && !$form['noparent']) {
                				echo "<input type='hidden' name='$name' value='$parent_id'/>";
                				continue;
                			}

                			if(isset($form['onlyfor'])) {
								if(is_array($form['onlyfor'])) {
									if(!in_array(Session::get('admin_privileges_name'), $form['onlyfor'])) {
										continue;
									}
								}else{
									if(Session::get('admin_privileges_name') != $form['onlyfor']) {
										continue;
									}
								}
							}

                			if(isset($form['callback_php'])) {
                				@eval("\$value = ".$form['callback_php'].";");
                			}

                			if(isset($form['default_value'])) {
                				@$value = $form['default_value'];
                			}

                			if($join && @$row) {
                				$join_arr = explode(',', $join);
                				array_walk($join_arr, 'trim');
                				$join_table = $join_arr[0];
                				$join_title = $join_arr[1];
                				$join_query_{$join_table} = DB::table($join_table)->select($join_title)->where("id",$row->{'id_'.$join_table})->first();
	                			$value = @$join_query_{$join_table}->{$join_title};	                				                				
                			}
                			$type 		= @$form['type'];
                			$required 	= (@$form['required'])?"required":"";
                			$readonly 	= (@$form['readonly'])?"readonly":"";
                			$disabled 	= (@$form['disabled'])?"disabled":"";
                			$jquery 	= @$form['jquery'];
                			$placeholder = (@$form['placeholder'])?"placeholder='".$form['placeholder']."'":"";

                			if(get_method() == 'getDetail') {
                				$disabled = 'disabled';                				
                			}

                			if(Request::segment(3) == 'sub-module') {
                				if(Request::segment(6) == 'detail') {
                					$disabled = 'disabled';
                				}
                			}

                			if(Request::segment(3)=='edit' && $priv->is_edit==0) {
                				$disabled = 'disabled';
                			}

                			if($type=='header') {
                				$header_group_class = "header-group-$index";
                			}else{
                				$header_group_class = ($header_group_class)?:"header-group-$index";	
                			}
                			
                	?>          

                		<?php if($type=='header' || $type=='heading'): ?>
                			<div id='header<?php echo e($index); ?>' data-collapsed="<?php echo e(($form['collapsed']===false)?'false':'true'); ?>" class='header-title form-divider'>                				
	            					<h4>
	            						<strong><i class='<?php echo e($form['icon']?:"fa fa-check-square-o"); ?>'></i> <?php echo e($form['label']); ?></strong>
	            						<span class='pull-right icon'><i class='fa fa-minus-square-o'></i></span>
	            					</h4>            					
            				</div>
                		<?php endif; ?>	


                		<?php if($form['googlemaps']==true): ?>
                		<style>

					      #map {
					        height: 300px;
					      }
					      .controls {
					        margin-top: 10px;
					        border: 1px solid transparent;
					        border-radius: 2px 0 0 2px;
					        box-sizing: border-box;
					        -moz-box-sizing: border-box;
					        height: 32px;
					        outline: none;
					        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
					      }

					      #pac-input {
					        background-color: #fff;
					        font-family: Roboto;
					        font-size: 15px;
					        font-weight: 300;
					        margin-left: 12px;
					        padding: 0 11px 0 13px;
					        text-overflow: ellipsis;
					        width: 300px;
					      }

					      #pac-input:focus {
					        border-color: #4d90fe;
					      }

					      .pac-container {
					        font-family: Roboto;
					      }

					      #type-selector {
					        color: #fff;
					        background-color: #4d90fe;
					        padding: 5px 11px 0px 11px;
					      }

					      #type-selector label {
					        font-family: Roboto;
					        font-size: 13px;
					        font-weight: 300;
					      }
					    </style>
                		<div class='form-group peta <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>'>
							<input id="pac-input" class="controls" autofocus type="text"
						        placeholder="Enter a location">
						    <div id="type-selector" class="controls">
						      <input type="radio" name="type" id="changetype-all" checked="checked">
						      <label for="changetype-all">All</label>

						      <input type="radio" name="type" id="changetype-establishment">
						      <label for="changetype-establishment">Establishments</label>

						      <input type="radio" name="type" id="changetype-address">
						      <label for="changetype-address">Addresses</label>

						      <input type="radio" name="type" id="changetype-geocode">
						      <label for="changetype-geocode">Geocodes</label>
						    </div>
						    <div id="map"></div>
						</div>
                		<script type="text/javascript">
                		  var geocoder;
					      function initMap() {
					      	geocoder = new google.maps.Geocoder();
					        var map = new google.maps.Map(document.getElementById('map'), {
					          <?php if($row->latitude && $row->longitude): ?>
					          center: {lat: <?php echo e($row->latitude); ?>, lng: <?php echo e($row->longitude); ?> },
					          <?php else: ?> 
					          center: {lat: -7.0157404, lng: 110.4171283},
					          <?php endif; ?>
					          zoom: 12
					        });

					        <?php if($row->latitude && $row->longitude): ?>
					        var marker = new google.maps.Marker({
					          position: {lat: <?php echo e($row->latitude); ?>, lng: <?php echo e($row->longitude); ?> },
					          map: map,
					          draggable:true,
					          title: 'Location Here !'
					        });
					        <?php endif; ?>

					        var input = /** @type    {!HTMLInputElement} */(
					            document.getElementById('pac-input'));

					        var types = document.getElementById('type-selector');
					        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
					        map.controls[google.maps.ControlPosition.TOP_LEFT].push(types);

					        var autocomplete = new google.maps.places.Autocomplete(input);
					        autocomplete.bindTo('bounds', map);

					        var infowindow = new google.maps.InfoWindow();
					        var marker = new google.maps.Marker({
					          map: map,
					          draggable:true,
					          anchorPoint: new google.maps.Point(0, -29)
					        });

					        google.maps.event.addListener(marker, 'dragend', function(marker){
					        	

					        	  geocoder.geocode({
								    latLng: marker.latLng
								  }, function(responses) {
								    if (responses && responses.length > 0) {
								      address = responses[0].formatted_address;
								    } else {
								      address = 'Cannot determine address at this location.';
								    }

								    <?php if($form['googlemaps_address']): ?>
								  		$("input[name=<?php echo e($form['googlemaps_address']); ?>]").val(address);
									<?php endif; ?>

									console.log(address);

								    infowindow.setContent(address);
								    // infowindow.open(map, marker);
								  });

						        var latLng = marker.latLng; 
						        latitude = latLng.lat();
						        longitude = latLng.lng();
						        						          
						        $("input[name=latitude]").val(latitude);
						        $("input[name=longitude]").val(longitude);								          						          	
						     });

					        autocomplete.addListener('place_changed', function() {
					          infowindow.close();
					          marker.setVisible(false);
					          var place = autocomplete.getPlace();
					          if (!place.geometry) {
					            window.alert("Autocomplete's returned place contains no geometry");
					            return;
					          }

					          // If the place has a geometry, then present it on a map.
					          if (place.geometry.viewport) {
					            map.fitBounds(place.geometry.viewport);
					          } else {
					            map.setCenter(place.geometry.location);
					            map.setZoom(17);  // Why 17? Because it looks good.
					          }
					          // marker.setIcon(/** @type    {google.maps.Icon} */({
					          //   url: 'http://maps.google.com/mapfiles/ms/icons/red.png',
					          //   size: new google.maps.Size(71, 71),
					          //   origin: new google.maps.Point(0, 0),
					          //   anchor: new google.maps.Point(17, 34),
					          //   scaledSize: new google.maps.Size(35, 35)
					          // }));
					          marker.setPosition(place.geometry.location);
					          marker.setVisible(true);

					          var address = '';
					          if (place.address_components) {
					            address = [
					              (place.address_components[0] && place.address_components[0].short_name || ''),
					              (place.address_components[1] && place.address_components[1].short_name || ''),
					              (place.address_components[2] && place.address_components[2].short_name || '')
					            ].join(' ');
					          }

					          var latitude = place.geometry.location.lat();
							  var longitude = place.geometry.location.lng(); 

							  <?php if($form['googlemaps_address']): ?>
							  	$("input[name=<?php echo e($form['googlemaps_address']); ?>]").val(address);
							  <?php endif; ?>
					          
					          $("input[name=latitude]").val(latitude);
					          $("input[name=longitude]").val(longitude);

					          infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
					          infowindow.open(map, marker);
					        });

					        function setupClickListener(id, types) {
					          var radioButton = document.getElementById(id);
					          radioButton.addEventListener('click', function() {
					            autocomplete.setTypes(types);
					          });
					        }

					        setupClickListener('changetype-all', []);
					        setupClickListener('changetype-address', ['address']);
					        setupClickListener('changetype-establishment', ['establishment']);
					        setupClickListener('changetype-geocode', ['geocode']);
					      }
					    </script>		        					    
					    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e($setting->google_api_key); ?>&libraries=places&callback=initMap"
			        async defer></script>
                		<?php endif; ?>

                		<?php if($jquery): ?>
                		<script>
                		$(function() {
                			<?php echo $jquery;?>
                		});
                		</script>
                		<?php endif; ?>

                		<?php if(@$type=='html'): ?>
                		<div class='form-group <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>'>
                			<?php echo $form['html']; ?>

                		</div>
                		<?php endif; ?>

                		<?php if(@$type=='qrcode'): ?>
                		<script type="text/javascript">
                			$(function() {
                				<?php if($form['text']): ?>
                					var text = $("#<?php echo e($form['text']); ?>").val();
                				<?php else: ?> 
                					var text = "<?php echo e(@$row->id); ?>";
                				<?php endif; ?>

                				if(text) {
                					$("#qrcode_<?php echo e($name); ?>").qrcode({
	                					"size":<?php echo e($form['size']); ?>,
	                					"color":"<?php echo e($form['color']); ?>",
	                					"text":text
	                				})	
                				}
                				
                			})
                		</script>
                		<div class='form-group <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>'>
                			<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label>
                			<div id='qrcode_<?php echo e($name); ?>'></div>
                		</div>                		
                		<?php endif; ?>

                		<?php if(@$type=='checkbox'): ?>
						<div class='form-group <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>' id='form-group-<?php echo e($name); ?>' style="<?php echo e(@$form['style']); ?>">
							<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label>							
							<?php 
								$value = explode(";",$value);
								array_walk($value, 'trim');
							?>
							<?php if(isset($form['dataenum'])): ?>
								<?php foreach($form['dataenum'] as $k=>$d): ?>
									<?php 
										if(strpos($d, '|')) {
											$val = substr($d, 0, strpos($d, '|'));
											$label = substr($d, strpos($d, '|')+1);
										}else{
											$val = $label = $d;
										}
										$checked = (in_array($val, $value))?"checked":"";									
									?>
									<div class="checkbox <?php echo e($disabled); ?>">
									  <label>
									    <input type="checkbox" <?php echo e($disabled); ?> <?php echo e($checked); ?> name="<?php echo e($name); ?>[]" value="<?php echo e($val); ?>"> <?php echo e($label); ?>								    
									  </label>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>

								<?php 
									if(@$form['datatable']):
										$datatable_array = explode(",",$form['datatable']);
										$datatable_tab = $datatable_array[0];
										$datatable_field = $datatable_array[1];

										$tables = explode('.',$datatable_tab);
										$selects_data = DB::table($tables[0])->select($tables[0].".id");	

										if(\Schema::hasColumn($tables[0],'deleted_at')) {
											$selects_data->where('deleted_at',NULL);
										}

										if(@$form['datatable_where']) {
											$selects_data->whereraw($form['datatable_where']);
										}

										if(count($tables)) {
											for($i=1;$i<=count($tables)-1;$i++) {
												$tab = $tables[$i];
												$selects_data->leftjoin($tab,$tab.'.id','=','id_'.$tab);
											}
										}																			

										$selects_data->addselect($datatable_field);

										if(Session::get('foreign_key')) {
											$columns = \Schema::getColumnListing($datatable_tab);	
											foreach(Session::get('foreign_key') as $k=>$v) {
												if(in_array($k, $columns)){
													$selects_data->where($datatable_tab.'.'.$k,$v);
												}
											}
										}

										$selects_data = $selects_data->orderby($datatable_field,"asc")->get();
										foreach($selects_data as $d) {											

											$val = $d->{$datatable_field};
											$checked = (in_array($val, $value))?"checked":"";											

											if(@$form['datatable_exception'] == $val || @$form['datatable_exception'] == $d->{$datatable_field}) continue;

											echo "<div class='checkbox $disabled'>
											  <label>
											    <input type='checkbox' $disabled $checked name='".$name."[]' value='".$d->{$datatable_field}."'> ".$d->{$datatable_field}."								    
											  </label>
											</div>";
										}
									endif
								?>
							<div class="text-danger"><?php echo $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):""; ?></div>
							<p class='help-block'><?php echo e(@$form['help']); ?></p>
						</div>
						<?php endif; ?>


                		<?php if(@$type=='text' || @!$type): ?>
						<div class='form-group <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>' id='form-group-<?php echo e($name); ?>' style="<?php echo e(@$form['style']); ?>">
							<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label>
							<input type='text' title="<?php echo e($form['label']); ?>" <?php echo e($required); ?> <?php echo e($readonly); ?> <?php echo $placeholder; ?> <?php echo e($disabled); ?> <?php echo e($validation['max']?"maxlength=$validation[max]":""); ?> class='form-control' name="<?php echo e($name); ?>" id="<?php echo e($name); ?>" value='<?php echo e($value); ?>'/>
							<div class="text-danger"><?php echo $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):""; ?></div>
							<p class='help-block'><?php echo e(@$form['help']); ?></p>
						</div>
						<?php endif; ?>

						<?php if(@$type=='number'): ?>
						<div class='form-group <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>' id='form-group-<?php echo e($name); ?>' style="<?php echo e(@$form['style']); ?>">
							<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label>
							<input type='number' title="<?php echo e($form['label']); ?>" <?php echo e($required); ?> <?php echo e($readonly); ?> <?php echo $placeholder; ?> <?php echo e($disabled); ?> <?php echo e($validation['min']?"min=$validation[min]":""); ?> <?php echo e($validation['max']?"max=$validation[max]":""); ?> class='form-control' name="<?php echo e($name); ?>" id="<?php echo e($name); ?>" value='<?php echo e($value); ?>'/>
							<div class="text-danger"><?php echo $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):""; ?></div>
							<p class='help-block'><?php echo e(@$form['help']); ?></p>
						</div>
						<?php endif; ?>

						<?php if(@$type=='email'): ?>
						<div class='form-group <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>' id='form-group-<?php echo e($name); ?>' style="<?php echo e(@$form['style']); ?>">
							<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label>
							<div class="input-group">
			                	<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
			                	<input type='email' title="<?php echo e($form['label']); ?>" <?php echo e($required); ?> <?php echo e($readonly); ?> <?php echo $placeholder; ?> <?php echo e($disabled); ?> <?php echo e($validation['max']?"maxlength=$validation[max]":""); ?> class='form-control' name="<?php echo e($name); ?>" id="<?php echo e($name); ?>" value='<?php echo e($value); ?>'/>
			              	</div>							
							<div class="text-danger"><?php echo $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):""; ?></div>
							<p class='help-block'><?php echo e(@$form['help']); ?></p>
						</div>
						<?php endif; ?>

						<?php if(@$type=='money'): ?>
						<div class='form-group <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>' id='form-group-<?php echo e($name); ?>' style="<?php echo e(@$form['style']); ?>">
							<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label>
							<input type='text' title="<?php echo e($form['label']); ?>" <?php echo e($required); ?> <?php echo e($readonly); ?> <?php echo $placeholder; ?> <?php echo e($disabled); ?> class='form-control inputMoney' name="<?php echo e($name); ?>" id="<?php echo e($name); ?>" value='<?php echo e($value); ?>'/>
							<div class="text-danger"><?php echo $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):""; ?></div>
							<p class='help-block'><?php echo e(@$form['help']); ?></p>
						</div>						
						<?php endif; ?>

						<?php if(@$type=='date' || @$type=='datepicker'): ?>						
						<div class='form-group form-datepicker <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>' id='form-group-<?php echo e($name); ?>' style="<?php echo e(@$form['style']); ?>">
							<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label>
							<div class="input-group">  								
								<span class="input-group-addon"><i class='fa fa-calendar'></i></span>
								<input type='text' title="<?php echo e($form['label']); ?>" readonly <?php echo e($required); ?> <?php echo e($readonly); ?> <?php echo $placeholder; ?> <?php echo e($disabled); ?> class='form-control notfocus datepicker' name="<?php echo e($name); ?>" id="<?php echo e($name); ?>" value='<?php echo e($value); ?>'/>						
							</div>
							<div class="text-danger"><?php echo $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):""; ?></div>
							<p class='help-block'><?php echo e(@$form['help']); ?></p>
						</div>
						<?php endif; ?>

						<?php if(@$type=='datetime' || @$type=='datetimepicker'): ?>
						<div class='form-group form-datepicker <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>' id='form-group-<?php echo e($name); ?>' style="<?php echo e(@$form['style']); ?>">
							<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label>
							<div class="input-group">  			
								<?php if(!$disabled): ?>					
								<span class="input-group-addon"><i class='fa fa-calendar'></i></span>
								<?php endif; ?>
								<input type='text' title="<?php echo e($form['label']); ?>" readonly <?php echo e($required); ?> <?php echo e($readonly); ?> <?php echo $placeholder; ?> <?php echo e($disabled); ?> class='form-control notfocus datetimepicker' name="<?php echo e($name); ?>" id="<?php echo e($name); ?>" value='<?php echo e($value); ?>'/>					
							</div>
							<div class="text-danger"><?php echo $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):""; ?></div>
							<p class='help-block'><?php echo e(@$form['help']); ?></p>
						</div>
						<?php endif; ?>

						<?php if(@$type=='time' || @$type=='timepicker'): ?>
						<div class='bootstrap-timepicker'>
							<div class='form-group <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>' id='form-group-<?php echo e($name); ?>' style="<?php echo e(@$form['style']); ?>">
								<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label>
								<div class="input-group">	 
									<?php if(!$disabled): ?> 								
									<span class="input-group-addon"><i class='fa fa-clock-o'></i></span>
									<?php endif; ?>
									<input type='text' title="<?php echo e($form['label']); ?>" <?php echo e($required); ?> <?php echo e($readonly); ?> <?php echo $placeholder; ?> <?php echo e($disabled); ?> class='form-control notfocus timepicker' name="<?php echo e($name); ?>" id="<?php echo e($name); ?>" readonly value='<?php echo e($value); ?>'/>									
								</div>
								<div class="text-danger"><?php echo $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):""; ?></div>
								<p class='help-block'><?php echo e(@$form['help']); ?></p>
							</div>
						</div>
						<?php endif; ?>


						<?php if(@$type=='hide' || @$type=='hidden'): ?>
						<input type='hidden' name="<?php echo e($name); ?>" value='<?php echo e($value); ?>'/>
						<?php endif; ?>
 
						<?php if(@$type=='textarea'): ?>
						<div class='form-group <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>' id='form-group-<?php echo e($name); ?>' style="<?php echo e(@$form['style']); ?>">
							<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label>							
							<textarea name="<?php echo e($form['name']); ?>" id="<?php echo e($name); ?>" <?php echo e($required); ?> <?php echo e($readonly); ?> <?php echo $placeholder; ?> <?php echo e($disabled); ?> <?php echo e($validation['max']?"maxlength=$validation[max]":""); ?> class='form-control' rows='5'><?php echo e($value); ?></textarea>
							<div class="text-danger"><?php echo $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):""; ?></div>
							<p class='help-block'><?php echo e(@$form['help']); ?></p>
						</div>
						<?php endif; ?>


						<?php if(@$type=='wysiwyg'): ?>
						<div class='form-group <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>' id='form-group-<?php echo e($name); ?>' style="<?php echo e(@$form['style']); ?>">
							<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label>	
							<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
							<textarea id='textarea_<?php echo e($name); ?>' name="<?php echo e($name); ?>" class="form-control my-editor"><?php echo old($name, $value); ?></textarea>
							<script>
							  var editor_config = {
							    path_absolute : "<?php echo e(asset('/')); ?>", 
							    selector: "#textarea_<?php echo e($name); ?>",
							    height:250,
							    <?php echo e(($disabled)?"readonly:1,":""); ?>

							    plugins: [
							      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
							      "searchreplace wordcount visualblocks visualchars code fullscreen",
							      "insertdatetime media nonbreaking save table contextmenu directionality",
							      "emoticons template paste textcolor colorpicker textpattern"
							    ],
							    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
							    relative_urls: false,
							    file_browser_callback : function(field_name, url, type, win) {
							      var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
							      var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

							      var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
							      if (type == 'image') {
							        cmsURL = cmsURL + "&type=Images";
							      } else {
							        cmsURL = cmsURL + "&type=Files";
							      }

							      tinyMCE.activeEditor.windowManager.open({
							        file : cmsURL,
							        title : 'Filemanager',
							        width : x * 0.8,
							        height : y * 0.8,
							        resizable : "yes",
							        close_previous : "no"
							      });
							    }
							  };

							  tinymce.init(editor_config);
							</script>
							
							<div class="text-danger"><?php echo $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):""; ?></div>
							<p class='help-block'><?php echo e(@$form['help']); ?></p>
						</div>
						<?php endif; ?>

						<?php if(@$type=='password'): ?>
						<div class='form-group <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>' id='form-group-<?php echo e($name); ?>' style="<?php echo e(@$form['style']); ?>">
							<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label>
							<input type='password' title="<?php echo e($form['label']); ?>" id="<?php echo e($name); ?>" <?php echo e($required); ?> <?php echo $placeholder; ?> <?php echo e($readonly); ?> <?php echo e($disabled); ?> <?php echo e($validation['max']?"maxlength=$validation[max]":""); ?> class='form-control' name="<?php echo e($name); ?>"/>							
							<div class="text-danger"><?php echo $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):""; ?></div>
							<p class='help-block'><?php echo e(@$form['help']); ?></p>
						</div>
						<?php endif; ?>

						<?php if(@$type=='select'): ?>
						<?php 
							if(isset($form['sub_select'])):
								$tab = str_replace("id_","",$form['sub_select']);
								$sub_label = '';
								foreach($forms as $f) {
									if($f['name'] == $form['sub_select']) {
										$sub_label = $f['label'];
										break;
									}
								}
						?>
								<script>
								var val;
								$(function() { 
									val = $('#<?php echo e($form['sub_select']); ?>').attr('data-value');
									
									$('#<?php echo e($name); ?>').change(function() {
										console.log('<?php echo e($name); ?> changed');
										$('#<?php echo e($form['sub_select']); ?>').html('<option value="">Please wait loading data...</option>');
										var id = $(this).val();
										$.get('<?php echo e(mainpath("find-data")); ?>?table1=<?php echo e($tab); ?>&fk=<?php echo e($name); ?>&fk_value='+id+'&limit=1000',function(resp) {								
											$('#<?php echo e($form['sub_select']); ?>').empty().html("<option value=''>** Please Select a <?php echo e($sub_label); ?></option>");
											$.each(resp.items,function(i,obj) {
												var selected = (val && val == obj.id)?'selected':'';
												$('#<?php echo e($form['sub_select']); ?>').append('<option '+selected+' value=\"'+obj.id+'\">'+obj.text+'</option>');
											})
										})
									});	

									if(val) {
										console.log('Value Detect :'+val);
										$('#<?php echo e($name); ?>').trigger('change');
									}								
								});
								</script>
						<?php 
							endif;
						?>
						<div class='form-group <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>' id='form-group-<?php echo e($name); ?>' style="<?php echo e(@$form['style']); ?>">
							<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label>												
							<select class='form-control' id="<?php echo e($name); ?>" data-value='<?php echo e($value); ?>' <?php echo e($required); ?> <?php echo $placeholder; ?> <?php echo e($readonly); ?> <?php echo e($disabled); ?> name="<?php echo e($name); ?>">
								<option value=''>** Please Select a <?php echo e($form['label']); ?></option>
								<?php 
									if(@$form['dataenum']):
										foreach($form['dataenum'] as $d) {

											$val = $lab = '';
											if(strpos($d,'|')!==FALSE) {
												$draw = explode("|",$d);
												$val = $draw[0];
												$lab = $draw[1];
											}else{
												$val = $lab = $d;
											}

											$select = ($value == $val)?"selected":"";

											if(Request::get("parent_field")==$name && Request::get("parent_id")==@$val) {
												$select = "selected";
											}

											echo "<option $select value='$val'>$lab</option>";
										}
									endif;

									if(@$form['datatable']):
										$raw = explode(",",$form['datatable']);
										$format = $form['datatable_format'];
										$table1 = $raw[0];
										$column1 = $raw[1];
										
										@$table2 = $raw[2];
										@$column2 = $raw[3];

										@$table3 = $raw[4];
										@$column3 = $raw[5];
										
										$selects_data = DB::table($table1)->select($table1.".id");	

										if(\Schema::hasColumn($table1,'deleted_at')) {
											$selects_data->where($table1.'.deleted_at',NULL);
										}

										if(@$form['datatable_where']) {
											$selects_data->whereraw($form['datatable_where']);
										}	

										if($table1 && $column1) {
											$orderby_table  = $table1;
											$orderby_column = $column1;
										}

										if($table2 && $column2) {
											$selects_data->join($table2,$table2.'.id','=',$table1.'.'.$column1);											
											$orderby_table  = $table2;
											$orderby_column = $column2;										
										}													

										if($table3 && $column3) {
											$selects_data->join($table3,$table3.'.id','=',$table2.'.'.$column2);											
											$orderby_table  = $table3;
											$orderby_column = $column3;
										}

										if($format) {				
											$format = str_replace('&#039;', "'", $format);						
											$selects_data->addselect(DB::raw("CONCAT($format) as label"));	
											$selects_data = $selects_data->orderby(DB::raw("CONCAT($format)"),"asc")->get();
										}else{
											$selects_data->addselect($orderby_table.'.'.$orderby_column.' as label');
											$selects_data = $selects_data->orderby($orderby_table.'.'.$orderby_column,"asc")->get();
										}										

										
										foreach($selects_data as $d) {											

											$val    = $d->id;
											$select = ($value == $val)?"selected":"";							

											if(@$form['datatable_exception'] == $val || @$form['datatable_exception'] == $d->label) continue;

											echo "<option $select value='$val'>".$d->label."</option>";
										}
									endif
								?>
							</select>
							<div class="text-danger"><?php echo $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):""; ?></div>
							<p class='help-block'><?php echo e(@$form['help']); ?></p>
						</div>
						<?php endif; ?>


						<?php if(@$type=='select2'): ?>
						<?php 							
							$datatable = @$form['datatable'];
							$where     = @$form['datatable_where'];
							$format    = @$form['datatable_format'];													

							$raw       = explode(',',$datatable);
							$url       = mainpath("find-data");

							$table1    = $raw[0];
							$column1   = $raw[1];
							
							@$table2   = $raw[2];
							@$column2  = $raw[3];
							
							@$table3   = $raw[4];
							@$column3  = $raw[5];
						?>
						<script>				
							$(function() {
								$('#<?php echo $name?>').select2({								  							  
								  placeholder: {
									    id: '-1', 
									    text: '** Please Select a <?php echo e($form['label']); ?>'
									},
								  allowClear: true,
								  ajax: {								  	
								    url: '<?php echo $url; ?>',								    
								    delay: 250,								   								    
								    data: function (params) {
								      var query = {
										q: params.term,
										format: "<?php echo e($format); ?>",
										table1: "<?php echo e($table1); ?>",
										column1: "<?php echo e($column1); ?>",
										table2: "<?php echo e($table2); ?>",
										column2: "<?php echo e($column2); ?>",
										table3: "<?php echo e($table3); ?>",
										column3: "<?php echo e($column3); ?>",
										where: "<?php echo e($where); ?>"
								      }
								      return query;
								    },
								    processResults: function (data) {
								      return {
								        results: data.items
								      };
								    }								    								    
								  },
								  escapeMarkup: function (markup) { return markup; }, 							        							    
								  minimumInputLength: 1,
							      <?php if($value): ?>
								  initSelection: function(element, callback) {
							            var id = $(element).val()?$(element).val():"<?php echo e($value); ?>";
							            if(id!=='') {
							                $.ajax('<?php echo e($url); ?>', {
							                    data: {
							                    	id: id, 
							                    	format: "<?php echo e($format); ?>",
							                    	table1: "<?php echo e($table1); ?>",
													column1: "<?php echo e($column1); ?>",
													table2: "<?php echo e($table2); ?>",
													column2: "<?php echo e($column2); ?>",
													table3: "<?php echo e($table3); ?>",
													column3: "<?php echo e($column3); ?>"
												},
							                    dataType: "json"
							                }).done(function(data) {							                	
							                    callback(data.items[0]);	
							                    $('#<?php echo $name?>').html("<option value='"+data.items[0].id+"' selected >"+data.items[0].text+"</option>");			                	
							                });
							            }
							      }
						
							      <?php endif; ?>							      
								});

							})
						</script>
						<div class='form-group <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>' id='form-group-<?php echo e($name); ?>' style="<?php echo e(@$form['style']); ?>">
							<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label><br/>									
							<select style='width:100%' class='form-control' id="<?php echo e($name); ?>" <?php echo e($required); ?> <?php echo e($readonly); ?> <?php echo $placeholder; ?> <?php echo e($disabled); ?> name="<?php echo e($name); ?>">	
								
							</select>
							<div class="text-danger"><?php echo $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):""; ?></div>
							<p class='help-block'><?php echo e(@$form['help']); ?></p>
						</div>
						
						<?php endif; ?>


						<?php if(@$type=='radio'): ?>
						<div class='form-group <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>' id='form-group-<?php echo e($name); ?>'  style="<?php echo e(@$form['style']); ?>">
							<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label><br/>
							<?php foreach($form['dataenum'] as $k=>$d):
								$val = $lab = '';
								if(strpos($d,'|')!==FALSE) {
									$draw = explode("|",$d);
									$val = $draw[0];
									$lab = $draw[1];
								}else{
									$val = $lab = $d;
								}
								$select = ($value == $val)?"checked":"";
							?>	
							<label class='radio-inline'>
								<input type='radio' <?php echo e($select); ?> name='<?php echo e($name); ?>' <?php echo e(($k==0)?$required:''); ?> <?php echo e($readonly); ?> <?php echo e($disabled); ?> value='<?php echo e($val); ?>'/> <?php echo $lab; ?> 
							</label>						

							<?php endforeach;?>																
							<div class="text-danger"><?php echo $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):""; ?></div>
							<p class='help-block'><?php echo e(@$form['help']); ?></p>
						</div>
						<?php endif; ?>

						<?php if(@$type=='upload'): ?>							
							<div class='form-group <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>' id='form-group-<?php echo e($name); ?>' style='<?php echo e(@$form["style"]); ?>'>
								<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label>

								<div class="input-group">
							      <span class="input-group-btn">
							        <a id="lfm-<?php echo e($name); ?>" data-input="thumbnail-<?php echo e($name); ?>" data-preview="holder-<?php echo e($name); ?>" class="btn btn-primary">
							          <?php if(@$form['upload_file']): ?>
							          	<i class="fa fa-file-o"></i> Choose a file
							          <?php else: ?>
							          	<i class='fa fa-picture-o'></i> Choose an image
							          <?php endif; ?>
							        </a>
							      </span>
							      <input id="thumbnail-<?php echo e($name); ?>" class="form-control" type="text" readonly value='<?php echo e($value); ?>' name="<?php echo e($name); ?>">
							    </div>

							    <?php if(@$form['upload_file']): ?>
						          	<?php if($value): ?> <div style='margin-top:15px'><a id='holder-<?php echo e($name); ?>' href='<?php echo e(asset($value)); ?>' target='_blank' title='Download File <?php echo e(basename($value)); ?>'><i class='fa fa-download'></i> Download <?php echo e(basename($value)); ?></a> 
						          	&nbsp;<a class='btn btn-danger btn-delete btn-xs' onclick='swal({   title: "Are you sure?",   text: "You will not be able to undo this action!",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, delete !",   closeOnConfirm: false }, function(){  location.href="<?php echo e(url($mainpath."/delete-filemanager?file=".$row->{$name}."&id=".$row->id."&column=".$name)); ?>" });' href='javascript:void(0)' title='Delete this file'><i class='fa fa-ban'></i></a>
						          	</div><?php endif; ?>
						        <?php else: ?>
						          	<img id='holder-<?php echo e($name); ?>' <?php echo e(($value)?'src='.asset($value):''); ?> style="margin-top:15px;max-height:100px;">
						        <?php endif; ?>
							    

								<div class='help-block'><?php echo e(@$form['help']); ?></div>
								<div class="text-danger"><?php echo $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):""; ?></div>
							</div>
							<?php if(@$form['upload_file']): ?>
							<script type="text/javascript">$('#lfm-<?php echo e($name); ?>').filemanager('file','<?php echo e(url("/")); ?>');</script>
							<?php else: ?>
							<script type="text/javascript">$('#lfm-<?php echo e($name); ?>').filemanager('images','<?php echo e(url("/")); ?>');</script>
							<?php endif; ?>
						<?php endif; ?>

						<?php if(@$type=='upload_standard'): ?>
						<div class='form-group <?php echo e($header_group_class); ?> <?php echo e(($errors->first($name))?"has-error":""); ?>' id='form-group-<?php echo e($name); ?>' style="<?php echo e(@$form['style']); ?>">
							<label><?php echo e($form['label']); ?> <?php echo ($required)?"<span class='text-danger' title='This field is required'>*</span>":""; ?></label>
							<?php if($value): ?>
								<?php 
									$file = str_replace('uploads/','',$row->{$name});
									if(Storage::exists($file)) {										
										$url         = asset($row->{$name});
										$ext 	     = explode('.',$url);
										$ext 		 = end($ext);
										$ext 		 = strtolower($ext);
										$images_type = array('jpg','png','gif','jpeg','bmp','tiff');									
										$filesize    = Storage::size($file);																						
									}
									
									if($filesize):
										if(in_array($ext, $images_type)):
										?>
											<p><img style='max-width:100%' title="Image For <?php echo e($form['label']); ?>, File Type : <?php echo e($ext); ?>" src='<?php echo e($url); ?>'/></p>
										<?php else:?>
											<p><a href='<?php echo e($url); ?>'>Download File (<?php echo e($ext); ?>)</a></p>
										<?php endif;
									else:
										echo "<p class='text-danger'><i class='fa fa-exclamation-triangle'></i> Oops looks like File ".$row->{$name}." was Broken !. Click Delete and Re-Upload.</p>";
									endif; 
								?>
								<?php if(!$readonly || !$disabled): ?>
								<p><a class='btn btn-danger btn-delete btn-sm' onclick="if(!confirm('Are you sure want to delete ? after delete you can upload other file.')) return false" href='<?php echo e(url($mainpath."/delete-image?image=".$row->{$name}."&id=".$row->id."&column=".$name)); ?>'><i class='fa fa-ban'></i> Delete </a></p>
								<?php endif; ?>
							<?php endif; ?>	
							<?php if(!$value): ?>
							<input type='file' id="<?php echo e($name); ?>" title="<?php echo e($form['label']); ?>" <?php echo e($required); ?> <?php echo e($readonly); ?> <?php echo e($disabled); ?> class='form-control' name="<?php echo e($name); ?>"/>							
							<p class='help-block'><?php echo e(@$form['help']); ?></p>
							<?php else: ?>
							<p class='text-muted'><em>* If you want to upload other file, please first delete the file.</em></p>
							<?php endif; ?>
							<div class="text-danger"><?php echo $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):""; ?></div>
							
						</div>
						<?php endif; ?>

					<?php endforeach;?>