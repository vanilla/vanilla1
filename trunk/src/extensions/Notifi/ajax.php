<?php
	include('../../appg/settings.php');
	include('../../conf/settings.php');
	include('../../appg/init_vanilla.php');

	$PostBackAction = ForceIncomingString('PostBackAction','');
	$Type           = ForceIncomingString('Type', '');
	$ElementID      = ForceIncomingInt('ElementID', 0);
	$Value          = ForceIncomingInt('Value',0);

	if ($PostBackAction == 'ChangeNotifi') {
		if ($Type != 'OWN') {
			if ($Type != 'KEEPEMAILING') {
				ChangeNotifi($Context,$Type,$ElementID,$Value);
			}
			if ($Type == 'ALL') {
				notifiSwitch($Context,$Value,$Context->Session->UserID,'SubscribedEntireForum');
			} elseif ($Type == 'KEEPEMAILING') {
				notifiSwitch($Context,$Value,$Context->Session->UserID,'KeepEmailing');
			} elseif ($Type == 'COMMENT') {
				notifiSwitch($Context,$Value,$Context->Session->UserID,'SubscribeComment');
			}
		} else {
			notifiSwitch($Context,$Value,$Context->Session->UserID,'SubscribeOwn');
		}
		echo 'Complete';
	}

	$Context->Unload();
?>