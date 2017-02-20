<div class="row">
	<div class="col-xs-12 col-sm-1 col-md-4 col-md-offset-4">
		<div class="panel panel-default">
			<div class="panel-body">
				<?php echo form_open('login', '', @$hidden); ?>
					<fieldset>
						<legend><?=__('auth.login.heading', null, 'Login')?></legend>
						<div class="form-group<?=form_error('login') ? ' has-error': ''?>"><?php echo form_input(array(
							'type' => 'text',
							'name' => 'login',
							'id' => 'login',
							'class' => 'form-control',
							'value' => set_value('login'),
							'placeholder' => __('ui.username_or_email', null, 'Username or email address'),
						)); ?><small class="help-block"><?=form_error('login')?></small></div><!--/.form-group-->
						<div class="form-group<?=form_error('password') ? ' has-error': ''?>"><?php echo form_input(array(
							'type' => 'password',
							'name' => 'password',
							'id' => 'password',
							'class' => 'form-control',
							'value' => set_value('password'),
							'placeholder' => __('ui.password', null, 'Password'),
						)); ?><small class="help-block"><?=form_error('password')?></small></div><!--/.form-group-->
						<div class="form-group">
							<div class="checkbox">
								<label><input type="checkbox" name="persist" value="1"> <?=__('ui.remember_me', null, 'Remember me')?></label>
							</div><!--/.checkbox-->
						</div><!--/.form-group-->
						<button class="btn btn-primary pull-right"><?=__('ui.signin', null, 'Sign in')?></button>
						<?=anchor('login/recover', __('ui.lost_password', null, 'Lost Password?'), 'class="btn btn-link"');?>
					</fieldset>
				<?php echo form_close(); ?>
			</div><!--/.panel-body-->
			<div class="panel-footer text-center">
				<?=anchor('register', __('ui.create_account', null, 'Create account'), 'class="btn btn-default btn-block"')?>
			</div><!--/.panel-footer-->
		</div><!--/.panel-->
	</div><!--/.col-md-4-->
</div><!--/.row-->
