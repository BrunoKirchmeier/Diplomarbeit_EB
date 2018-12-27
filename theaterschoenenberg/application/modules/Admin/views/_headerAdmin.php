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

		<!-- tinymcs laden -->
		<script type="text/javascript"
		        src="<?= base_url()?>assets/js/tinymce/tinymce.min.js">
		</script>

		<!-- Javascript Globale Variablen initialisieren -->
		<script>
	  	var base_url = "<?= base_url(); ?>";
			var site_url = "<?= site_url(); ?>";
			var current_controller = "<?= $s_current_controller; ?>";
		</script>

</head>


<body>



	<div class="asideAdmin">

		<p class="aside-user"> <?= $s_active_user_name != '' ? 'Hallo ' . $s_active_user_name
																												 : ''; ?> </p>

		<div class="asideAdmin-link oben">
		 <!-- <a href="<?= site_url()?>Welcome"
	  				 id="Welcome">Willkommen</a> -->
		  <a href="<?= site_url()?>Admin/Backend_AktuellesStueck"
				 id="Backend_AktuellesStueck">Aktuelles Stück</a>
	<!--		<a href="<?= site_url()?>Admin/Backend_AktuellesStueck"
				 id="Backend_Auffuehrungen">Aufführungen</a> -->
		  <a href="<?= site_url()?>Admin/Backend_Tickets"
				 id="Backend_Tickets">Ticket Reservationen</a>
			<a href="<?= site_url()?>Admin/Backend_Auth/logout">Logout</a>
		</div>


		<div class="asideAdmin-link unten">
		</div>

	</div>

	<div class="mainAdmin">
