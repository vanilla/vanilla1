<?php
/*
Extension Name: Expire
Extension Url: http://code.google.com/p/vanilla-friends/
Description: Automatically close or sink old discussions.
Version: 1.0.1
Author: squirrel
Author Url: http://digitalsquirrel.com/
*/


// Set up dictionary definitions.
$Context->SetDefinition('Expire.Error.DaysOldNaN', '"//1" must be a number.');

// Expire old discussions.
function Expire_Old_Discussions(&$Context)
{
	// Force proper config values.
	$DaysOld        = (integer)  @$Context->Configuration['Expire.DaysOld'];
	$Action         = strtoupper(@$Context->Configuration['Expire.Action']);
	$IgnoreWhispers = (boolean)  @$Context->Configuration['Expire.IgnoreWhispers'];

	$Now = time();
	$ExpireDate = $Now - ($DaysOld * 86400);
	if ($ExpireDate != $Now) {
		$SqlBuilder = $Context->ObjectFactory->NewContextObject($Context, 'SqlBuilder');
		$SqlBuilder->SetMainTable('Discussion', 'd');
		switch ($Action) {
			case 'SINK':
				$SqlBuilder->AddFieldNameValue('Sink', '1');
				break;
			case 'HIDE':
				$SqlBuilder->AddFieldNameValue('Active', '0');
				break;
			case 'CLOSE':
			default:
				$SqlBuilder->AddFieldNameValue('Closed', '1');
				break;
		}
		$SqlBuilder->AddWhere('d', 'Sticky', '', 0, '=');
		$SqlBuilder->AddWhere('d', 'DateLastActive', '', MySqlDateTime($ExpireDate), '<=');
		if (!$IgnoreWhispers) {
			$SqlBuilder->AddWhere('d', 'DateLastWhisper', '', MySqlDateTime($ExpireDate), '<=');
		}
		$Context->Database->Update($SqlBuilder, 'Expire', 'Expire_Old_Discussions', 'An error occurred while expiring old dicussions.');
	}

	// Update the last look time.
	$Context->Configuration['Expire.LastLook'] = 0;
	$SettingsFile = $Context->Configuration['APPLICATION_PATH'].'conf/settings.php';
	$SettingsManager = $Context->ObjectFactory->NewContextObject($Context, 'ConfigurationManager');
	$SettingsManager->DefineSetting('Expire.LastLook', $Now, 1);
	$SettingsManager->SaveSettingsToFile($SettingsFile);
	$Context->Configuration['Expire.LastLook'] = $Now;
}

// Should we go looking for expired discussions?
if (time() > (@$Configuration['Expire.LastLook'] + 86400)) {
	Expire_Old_Discussions($Context);
}


// Settings form initialization.
if ( 'settings.php' == $Context->SelfUrl ) {
	function SetList_Init_Expire(&$SetList)
	{
		$elements = &$SetList->DelegateParameters['Form']['elements'];

		// Replace '//1' with the time since the last expire check.
		$elements['ExpireOptions']['description'] = str_replace(
			'//1',
			TimeDiff($SetList->Context, $SetList->Context->Configuration['Expire.LastLook']),
			$elements['ExpireOptions']['description']
		);
	}
	$Context->AddToDelegate('SetList', 'Init_Expire', 'SetList_Init_Expire');

	function SetList_Process_Expire(&$SetList)
	{
		$elements = &$SetList->DelegateParameters['Form']['elements'];

		// Throw an error if DaysOld is not a number.
		if (!is_numeric($elements['DaysOld']['value'])) {
			$Error = str_replace(
				'//1',
				$elements['DaysOld']['label'],
				$SetList->Context->GetDefinition('Expire.Error.DaysOldNaN')
			);
			$SetList->Context->WarningCollector->Add($Error);
		}

		// Check for expired discussions whenever settings are saved.
		if ($SetList->Context->WarningCollector->Iif()) {
			$SetList->Context->Configuration['Expire.DaysOld']        = $elements['DaysOld']['value'];
			$SetList->Context->Configuration['Expire.Action']         = $elements['Action']['value'];
			$SetList->Context->Configuration['Expire.IgnoreWhispers'] = $elements['IgnoreWhispers']['value'];
			Expire_Old_Discussions($SetList->Context);
			$elements['ExpireOptions']['description'] = str_replace(
				'//1',
				TimeDiff($SetList->Context, 1, 1),
				$SetList->Context->GetDefinition('Expire.ExpireOptions.description')
			);
		}
	}
	$Context->AddToDelegate('SetList', 'Process_Expire', 'SetList_Process_Expire');
}


// muscles in a mess like a mess of spaghetti
// hack through the mess with a greased up machete

?>
