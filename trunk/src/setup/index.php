<?php
// Set application name and version
include('../appg/version.php');
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-ca">
	<head>
		<title><?php echo APPLICATION . ' ' . APPLICATION_VERSION; ?></title>
		<link rel="stylesheet" type="text/css" href="./style.css" />
		<script type="text/javascript" src="../js/jquery.js"></script>
		<script type="text/javascript" src="./functions.js"></script>
	</head>
	<body>
		<h1>
			<img src="../themes/vanilla modern/styles/default/logo.png" alt="logo" />
		</h1>
		<div class="Container">
			<div class="Content">
				<h2>Setup</h2>
				<p>Please choose from the following two options:</p><br style="clear:left;" />
				<div class="ButtonContainer"><a href="installer.php" class="Button">Click here to install a completely brand new forum</a></div><br style="clear:left;" />
				<div class="ButtonContainer"><a class="Button" id="ShowUpgrade">Click here to upgrade an existing installation</a></div>


				<div id="Upgrading">
					<div id="UpgradeFrom">Upgrade from Vanilla 1.x:</div>
					<a href="http://lussumo.com/upgrade/" style="color:#000;font-weight:bold;">Read the upgrade instructions online</a>.<br /><br />

					<div id="UpgradeFrom">Upgrade from Vanilla 0.9.2.x:</div>
					<ul>
						<li>
							<strong>Back up your Database</strong>
							<p style="margin-top:0;">The upgrader will be performing structural changes on your database, you should have a backup of your old database just to be safe.</p>
						</li>
						<li>
							<strong>Back up your old Vanilla files</strong>
							<p style="margin-top:0;">Download and save your old Vanilla files to your local machine. Specifically, we can use your old appg/settings.php file for importing old settings.</p>
						</li>
					</ul>

					<p>When you are finished backing everything up, upload all of the new Vanilla files to your server, open this file in your web browser and click the upgrade link below.</p>

					<div class="ButtonContainer"><a href="upgrader.php" class="Button">Click here to upgrade from Vanilla 0.9.2.x to <?php echo APPLICATION . ' ' . APPLICATION_VERSION; ?></a></div>
				</div>
			</div>
		</div>
	</body>
</html>