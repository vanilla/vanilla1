<?php
// Ajax method that removes a search and returns the id of the search that was removed upon success

include_once("../../appg/settings.php");
include_once("../../conf/settings.php");
include_once("../../appg/init_ajax.php");

$SearchID = ForceIncomingInt("SearchID", 0);
if ($SearchID > 0) {
   $sm = $Context->ObjectFactory->NewContextObject($Context, "SearchManager");
   $sm->DeleteSearch($SearchID);
}
echo($SearchID);
?>