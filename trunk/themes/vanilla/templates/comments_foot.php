<?php
// Note: This file is included from the library/Vanilla.Control.CommentFoot.php class.

echo("<a class=\"PageJump Top\" href=\"".$_SERVER["REQUEST_URI"]."#pgtop\">".$this->Context->GetDefinition("TopOfPage")."</a>"
."<a class=\"PageNav\" href=\"".GetUrl($this->Context->Configuration, "index.php")."\">".$this->Context->GetDefinition("BackToDiscussions")."</a>"
."<a name=\"pgbottom\"></a>");
?>