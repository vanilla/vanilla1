<?php
// Note: This file is included from the library/Vanilla/Vanilla.Control.DiscussionGrid.php class.

echo($this->PageJump
.'<div class="Title">'.$this->Context->PageTitle.'</div>'
.$PageList
.'<div class="PageDetails">'.($PageDetails == 0 ? $this->Context->GetDefinition('NoDiscussionsFound') : $PageDetails).'</div>');
$Discussion = $this->Context->ObjectFactory->NewObject($this->Context, 'Discussion');
$FirstRow = 1;
$CurrentUserJumpToLastCommentPref = $this->Context->Session->User->Preference('JumpToLastReadComment');
$DiscussionList = '';
$ThemeFilePath = ThemeFilePath($this->Context->Configuration, 'discussion.php');
while ($Row = $this->Context->Database->GetRow($this->DiscussionData)) {
   $Discussion->Clear();
   $Discussion->GetPropertiesFromDataSet($Row, $this->Context->Configuration);
   $Discussion->FormatPropertiesForDisplay();
	// Prefix the discussion name with the whispered-to username if this is a whisper
   if ($Discussion->WhisperUserID > 0) {
		$Discussion->Name = @$Discussion->WhisperUsername.': '.$Discussion->Name;
	}

	// Discussion search results are identical to regular discussion listings, so include the discussion search results template here.
	include($ThemeFilePath);
	
   $FirstRow = 0;
}
echo($DiscussionList);
if ($this->DiscussionDataCount > 0) {
   echo($PageList
	.'<div class="PageDetails">'.$pl->GetPageDetails($this->Context).'</div>'
	.'<a class="PageJump Top" href="'.GetRequestUri().'#pgtop">'.$this->Context->GetDefinition('TopOfPage').'</a>');
}

?>