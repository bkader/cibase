<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="<?php echo @$site_url; ?>"><?php echo @$site_name; ?></a>
		</div><!--/.navbar-header-->

		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse navbar-ex1-collapse">
			<ul class="nav navbar-nav">
				<li><a href="https://goo.gl/fZ5P94" target="_blank">GitHub</a></li>
				<li><a href="http://www.codeigniter.com/" target="_blank">CodeIgniter</a></li>
				<li><?php echo anchor('example', 'Example'); ?></li>
			</ul><!--/.nav-->

			<form action="<?php echo site_url('example'); ?>" class="navbar-form navbar-left" role="search" method="get">
				<div class="form-group">
					<input type="text" name="name" class="form-control" placeholder="Search">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form><!--/.navbar-form-->

			<ul class="nav navbar-nav navbar-right">
				<li><a href="https://goo.gl/wGXHO9" target="_blank">@kader</a></li>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">My Other Repos <b class="caret"></b></a>
					<ul class="dropdown-menu">
						<li><a href="https://goo.gl/RzSsE9" target="_blank">CodeIgniter Gettext Hook</a></li>
						<li><a href="https://goo.gl/gIMfYw" target="_blank">CodeIgniter Theme Library</a></li>
						<li><a href="https://goo.gl/dHupYv" target="_blank">Dinakit Framework</a></li>
						<li class="divider"></li>
						<li><a href="https://github.com/bkader?tab=repositories" target="_blank">View all</a></li>
					</ul>
				</li>
			</ul><!--/.nav-->
		</div><!-- /.navbar-collapse -->
	</div><!--/.container-->
</nav><!--/.navbar-->
