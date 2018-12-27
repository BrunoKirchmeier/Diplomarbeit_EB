<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
		<title>Theater Sch&ouml;nenberg</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<link rel="stylesheet" href="<?= base_url()?>assets/css/styles.css">
		<link rel="stylesheet" href="<?= base_url()?>node_modules/bootstrap/dist/css/bootstrap.min.css">

		<!-- Javascript Globale Variablen initialisieren -->
		<script>
	  	var base_url = "<?= base_url(); ?>";
			var site_url = "<?= site_url(); ?>";
			var current_controller = "<?= $s_current_controller; ?>";
		</script>

</head>


<body>

	<div class="hamburger">
		<div class="bar1"></div>
		<div class="bar2"></div>
		<div class="bar3"></div>
		<div class="bar4"></div>
	</div>


	<div class="aside">

		<p class="aside-user"> <?= $s_active_user_name != '' ? 'Hallo ' . $s_active_user_name
		 																										 : ''; ?> </p>

		<div class="aside-link oben">
		  <a href="<?= site_url()?>Welcome"
				 id="Welcome">Willkommen</a>
		  <a href="<?= site_url()?>AktuellesStueck"
				 id="AktuellesStueck">Aktuelles Stück</a>
	<!-- 			<a href="<?= site_url()?>Auffuehrungen"
				 id="Auffuehrungen">Aufführungen</a> -->
		  <a href="<?= site_url()?>Ticket"
				 id="Ticket">Ticket Reservationen</a>
			<a href="<?= site_url()?>Auth"
				 id="Auth">Login</a>
		  <a href="<?= site_url()?>Auth/logout">Logout</a>
		</div>

		<div class="aside-link unten">
		</div>

	</div>

	<div class="main">
