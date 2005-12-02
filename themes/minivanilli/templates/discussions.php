<?php
// Note: This file is included from the controls/index.php file
// in the DiscussionGrid class.

					
					
echo("<table border=\"0\" width=\"100%\">
	<tr>
		<td>".$PageList."</td>
		<td align=\"right\">");
			if ($this->Context->Session->UserID > 0) {
				$CategoryID = ForceIncomingInt("CategoryID", 0);
				echo("<a class=\"StartDiscussionButton\" href=\"post.php".($CategoryID > 0?"?CategoryID=".$CategoryID:"")."\">".$this->Context->GetDefinition("StartANewDiscussion")."</a>");
			}
		echo("</td>
	</tr>
</table>		
<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"DataTable\">
   <tr>
      <td class=\"DataTableHead\">".$this->Context->GetDefinition("DiscussionTopic")."</td>
      <td class=\"DataTableHead MetaHead\">");
		if ($this->Context->Configuration["USE_CATEGORIES"]) {
			echo($this->Context->GetDefinition("Category")."</td>
         <td class=\"DataTableHead MetaHead\">");
      }
      echo($this->Context->GetDefinition("Active")."</td>
      <td class=\"DataTableHead MetaHead\">".$this->Context->GetDefinition("Replies")."</td>
   </tr>");
	
$Discussion = $this->Context->ObjectFactory->NewObject($this->Context, "Discussion");
      
while ($Row = $this->Context->Database->GetRow($this->DiscussionData)) {
   $Discussion->Clear();
   $Discussion->GetPropertiesFromDataSet($Row, $this->Context->Configuration);
   $Discussion->FormatPropertiesForDisplay();
   $Discussion->ForceNameSpaces($this->Context->Configuration);
	// Prefix the discussion name with the whispered-to username if this is a whisper
   if ($Discussion->WhisperUserID > 0) {
		$Discussion->Name = @$Discussion->WhisperUsername.": ".$Discussion->Name;
	}
	$UnreadQS = GetUnreadQuerystring($Discussion, $this->Context->Configuration);
	$LastQS = GetLastCommentQuerystring($Discussion, $this->Context->Configuration);
   
	echo("<tr class=\"Discussion".$Discussion->Status.($Discussion->CountComments == 1?" NoReplies":"").($this->Context->Configuration["USE_CATEGORIES"] ? " Category_".$Discussion->CategoryID:"")."\">
      <td class=\"DataTableItem\">".DiscussionPrefix($this->Context->Configuration, $Discussion)."<a href=\"comments.php?DiscussionID=".$Discussion->DiscussionID.($this->Context->Session->User->Preference("JumpToLastReadComment")?$UnreadQS:"")."\">".$Discussion->Name."</a></dd>");
		if ($this->Context->Configuration["USE_CATEGORIES"]) {
			echo("
         <td class=\"DataTableItem MetaItem\"><a href=\"./?CategoryID=".$Discussion->CategoryID."\">".$Discussion->Category."</a></td>
			");
		}
		echo("<td class=\"DataTableItem MetaItem\">".TimeDiff($this->Context, $Discussion->DateLastActive,mktime())."</td>
      <td class=\"DataTableItem MetaItem\">".$Discussion->CountComments);
		if ($this->Context->Session->UserID > 0 && $Discussion->NewComments > 0) {
			echo(" [".$Discussion->NewComments."]");
		}      
      echo("</td>
   </tr>");
}
echo("</table>
<table border=\"0\" width=\"100%\">
	<tr>
		<td class=\"PageListBottom\">".(($this->DiscussionDataCount > 0) ? $PageList : "")."</td>
		<td class=\"PageDetails\">".$pl->GetPageDetails($this->Context)."</td>
	</tr>
</table>");
?>