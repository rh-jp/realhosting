<div id='wrapper' style='padding:20px 50px 20px 50px;background:#eeeeee'>
		<div style='padding:20px;background:#3379A1;border-bottom:1px solid #eee;color:#ffffff;font-size:25px;font-weight:bold'>
			<div align='center'>
				<?php 
					$appname = DB::table('cms_settings')->where('name','appname')->first()->content;
					echo $appname;
				?>
			</div> 
		</div>
		<div style='padding:20px 20px 60px 20px;background:#ffffff;'>
