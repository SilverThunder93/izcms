<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>izCMS - <?php echo (isset($title)) ? $title : "My home page" ?></title>
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
	<div id="container">
		<div id="header" class="">
			<h1><a href="index.php">izCMS</a></h1>
			<p class="slogan">The iz Content Manament System</p>
		</div><!-- #header -->

		<div id="navigation">
			<ul>
				<li><a href="#" title="">Home</a></li>
				<li><a href="#" title="">About</a></li>
				<li><a href="#" title="">Services</a></li>
				<li><a href="contact.php" title="">Contact us</a></li>
			</ul>
			
			<p class="greeting">Xin chào bạn hiền </p>
		</div><!-- end #navigation -->