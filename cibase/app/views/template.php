<!doctype html>
<html class="no-js" lang="">
    <head>
        <meta charset="<?php echo @$charset; ?>">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title><?php echo @$title; ?></title>
        <meta name="description" content="<?php echo @$description; ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <!-- Place favicon.ico in the root directory -->

		<?php echo css('bootstrap.min'); ?>
		<?php echo css('bootstrap-theme.min'); ?>
		<?php echo css('font-awesome.min'); ?>
		<?php echo css('style.min'); ?>
		<?php echo js('modernizr.min'); ?>
    </head>
    <body>
		<?php echo @$layout."\n"; ?>
		<?php echo js('jquery.min'); ?>
		<?php echo js('bootstrap.min'); ?>
<?php if ( ! empty(config('google.analytics'))): ?>
        <!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
        <script>
            window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;
            ga('create','<?php echo config('google.analytics'); ?>','auto');ga('send','pageview')
        </script>
        <script src="https://www.google-analytics.com/analytics.js" async defer></script>
<?php endif; ?>
    </body>
</html>
