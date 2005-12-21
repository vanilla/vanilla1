<?php
function GetFeedUriForRSS2($Parameters) {
   $Parameters->Remove("DiscussionID");
   $Uri = FormatStringForDisplay($_SERVER["REQUEST_URI"]);
   $Uri = explode("?", $Uri);
   $Uri = $Uri[0];
   return $Uri."?".$Parameters->GetQueryString();   
}

?>