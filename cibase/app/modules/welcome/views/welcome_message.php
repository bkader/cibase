<div class="row">
	<div class="col-xs-12">
	<h1>Welcome to CodeIgniter!</h1>

	<div id="body">
		<p>The page you are looking at is being generated dynamically by CodeIgniter.</p>

		<p>If you would like to edit this page you'll find it located at:</p>
		<code><?php echo __FILE__; ?></code>

		<p>The corresponding controller for this page is found at:</p>
		<code><?php echo $this->load->module_path.'controllers'.DS.'Welcome.php'; ?></code>

		<p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="user_guide/">User Guide</a>.</p>
	</div>

	<p>Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo  (ENVIRONMENT === 'development') ?  'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
	</div><!--/.col-xs-12-->
</div><!--/.row-->