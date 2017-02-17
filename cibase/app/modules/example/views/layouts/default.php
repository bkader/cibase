<!-- Default layout overriden: <?php echo __FILE__; ?> -->
<?php echo @$header."\n"; ?>
<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-8">
			<?php echo @$content."\n"; ?>
		</div><!--/.col-md-8-->
		<div class="col-xs-12 col-sm-12 col-md-4">
			<?php echo @$sidebar."\n"; ?>
		</div><!--/.col-md-4-->
	</div><!--/.row-->
</div><!--/.container-->
<?php echo @$footer."\n"; ?>
