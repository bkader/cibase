<div class="row">
	<div class="col-xs-12 col-sm-1 col-md-4 col-md-offset-4">
		<div class="panel panel-default">
			<div class="panel-body">
				<?php echo form_open('register', '', @$hidden); ?>
					<fieldset>
						<legend><?=__('auth.register.heading', null, 'Create Account')?></legend>

						<div class="form-group<?=form_error('email') ? ' has-error': ''?>"><?php echo form_input(array(
							'type' => 'email',
							'name' => 'email',
							'id' => 'email',
							'class' => 'form-control',
							'value' => set_value('email'),
							'placeholder' => __('ui.input.email', null, 'Email address'),
						)); ?><small class="help-block"><?=form_error('email')?></small></div><!--/.form-group-->

						<div class="form-group<?=form_error('username') ? ' has-error': ''?>"><?php echo form_input(array(
							'type' => 'text',
							'name' => 'username',
							'id' => 'username',
							'class' => 'form-control',
							'value' => set_value('username'),
							'placeholder' => __('ui.input.username', null, 'Username'),
						)); ?><small class="help-block"><?=form_error('username')?></small></div><!--/.form-group-->

						<div class="form-group<?=form_error('password') ? ' has-error': ''?>"><?php echo form_input(array(
							'type' => 'password',
							'name' => 'password',
							'id' => 'password',
							'class' => 'form-control',
							'value' => set_value('password'),
							'placeholder' => __('ui.input.password', null, 'Password'),
						)); ?><small class="help-block"><?=form_error('password')?></small></div><!--/.form-group-->

						<div class="form-group<?=form_error('cpassword') ? ' has-error': ''?>"><?php echo form_input(array(
							'type' => 'password',
							'name' => 'cpassword',
							'id' => 'cpassword',
							'class' => 'form-control',
							'value' => set_value('cpassword'),
							'placeholder' => __('ui.input.confirm_password', null, 'Confirm password'),
						)); ?><small class="help-block"><?=form_error('cpassword')?></small></div><!--/.form-group-->

						<?php echo anchor('login', __('ui.login', null, 'Login'), 'class="btn btn-default"'); ?>

						<button class="btn btn-primary pull-right"><?=__('ui.create_account', null, 'Create Account')?></button>

					</fieldset>
				<?php echo form_close(); ?>
			</div><!--/.panel-body-->
		</div><!--/.panel-->
	</div><!--/.col-md-4-->
</div><!--/.row-->
