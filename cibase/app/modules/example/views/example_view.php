<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><div class="row">
	<div class="col-xs-1 col-sm-12 col-md-6">
		<div class="panel panel-default">
			<div class="panel-body">
				<h3><?php _e('hello_name', $name); ?></h3>
				<hr>
				<p><?php _e('dummy_string', $random); ?></p>
			</div>
		</div>
	</div><!--/.col-md-6-->
	<div class="col-xs-1 col-sm-12 col-md-6">
		<div class="panel panel-default">
			<div class="panel-body">
				<p><?php _e('dummy_password', $password); ?></p>
				<p style="word-wrap: break-word;"><?php _e('dummmy_hashed', $hashed); ?></p>
			</div>
		</div>
	</div><!--/.col-md-6-->
</div><!--/.row-->
