<div class="row">
	<div class="col-xs-12 col-sm-1 col-md-4 col-md-offset-4">
		<div class="panel panel-default">
			<div class="panel-body">
				<?php echo form_open('login/recover', '', @$hidden); ?>
					<fieldset>
						<legend><?=__('auth.recover.heading', null, 'Lost password')?></legend>

						<div class="form-group<?=form_error('login') ? ' has-error': ''?>"><?php echo form_input(array(
							'type' => 'text',
							'name' => 'login',
							'id' => 'login',
							'class' => 'form-control',
							'value' => set_value('login'),
							'placeholder' => __('ui.username_or_email', null, 'Username or email address'),
						)); ?><small class="help-block"><?=form_error('login')?></small></div><!--/.form-group-->

						<button class="btn btn-primary pull-right"><?=__('ui.recover', null, 'Recover')?></button>

						<?php echo anchor('login', __('ui.cancel', null, 'Cancel'), 'class="btn btn-default"'); ?>

					</fieldset>
				<?php echo form_close(); ?>
			</div><!--/.panel-body-->
		</div><!--/.panel-->
	</div><!--/.col-md-4-->
</div><!--/.row-->
