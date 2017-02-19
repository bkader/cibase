<!DOCTYPE html>
<html class="no-js" lang="">
<head>
	<meta charset="<?=@$charset?>">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title><?=@$title?></title>
	<meta name="description" content="<?=@$description?>">
	<meta name="keywords" content="<?=@$keywords?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="apple-touch-icon" href="apple-touch-icon.png">
	<!-- Place favicon.ico in the root directory -->

	<?php echo css('bootstrap.min', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'); ?>
	<?php echo css('bootstrap-theme.min', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css'); ?>
	<?php echo css('font-awesome.min', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css'); ?>
	<?php echo css('style.min'); ?>
	<?php echo js('modernizr.min', 'https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js'); ?>

	<?php echo js('https://buttons.github.io/buttons.js', null, 'async defer'); ?>
</head>
<body>
	<?=@$layout."\n"?>
	<?php echo js('jquery.min', 'https://code.jquery.com/jquery-1.12.4.min.js'); ?>
	<?php echo js('bootstrap.min', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'); ?>
<?php if ( ! empty(config('google.analytics'))): ?>
	<!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
	<script>
		window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
		ga('create','<?=config('google.analytics')?>','auto');ga('send','pageview')
	</script>
	<script src="https://www.google-analytics.com/analytics.js" async defer></script>
<?php endif; ?>
</body>
</html>
