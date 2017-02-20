<div class="row">
	<div class="col-xs-12 col-sm-1 col-md-4 col-md-offset-4">
		<div class="panel panel-default">
			<div class="panel-body">
				<?php echo form_open('login/reset/'.@$key, '', @$hidden); ?>
					<fieldset>
						<legend><?=__('auth.reset.heading', null, 'Reset password')?></legend>

						<div class="form-group<?=form_error('password') ? ' has-error': ''?>"><?php echo form_input(array(
							'type' => 'password',
							'name' => 'password',
							'id' => 'password',
							'class' => 'form-control',
							'value' => set_value('password'),
							'placeholder' => __('ui.password', null, 'Password'),
						)); ?><small class="help-block"><?=form_error('password')?></small></div><!--/.form-group-->

						<div class="form-group<?=form_error('cpassword') ? ' has-error': ''?>"><?php echo form_input(array(
							'type' => 'password',
							'name' => 'cpassword',
							'id' => 'cpassword',
							'class' => 'form-control',
							'value' => set_value('cpassword'),
							'placeholder' => __('ui.confirm_password', null, 'Confirm password'),
						)); ?><small class="help-block"><?=form_error('cpassword')?></small></div><!--/.form-group-->

						<button class="btn btn-primary pull-right"><?=__('ui.reset', null, 'Reset')?></button>

						<?php echo anchor('login', __('ui.cancel', null, 'Cancel'), 'class="btn btn-default"'); ?>

					</fieldset>
				<?php echo form_close(); ?>
			</div><!--/.panel-body-->
		</div><!--/.panel-->
	</div><!--/.col-md-4-->
</div><!--/.row-->
