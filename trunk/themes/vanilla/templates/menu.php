<?php
// Note: This file is included from the library/Vanilla.Control.Menu.php class.

echo('<div class="SiteContainer">');
	$this->CallDelegate('PostSiteContainerRender');
	echo('<a name="pgtop"></a>
   <div id="LoadStatus" style="display: none;">'.$this->Context->GetDefinition('Loading').'</div>
   <div class="Session">');
   if ($this->Context->Session->UserID > 0) {
      echo(str_replace('//1',
         $this->Context->Session->User->Name,
         $this->Context->GetDefinition('SignedInAsX')).' (<a href="'.$this->Context->Configuration['SIGNOUT_URL'].'">'.$this->Context->GetDefinition('SignOut').'</a>)');
   } else {
      echo($this->Context->GetDefinition('NotSignedIn').' (<a href="'.$this->Context->Configuration['SIGNIN_URL'].'?ReturnUrl='.GetRequestUri().'">'.$this->Context->GetDefinition('SignIn').'</a>)');
   }
   echo('</div>');
	$this->CallDelegate('PreHeadRender');	
   echo('<div class="Head">
      <div class="Logo">'.$this->Context->Configuration['BANNER_TITLE'].'</div>
      <ul id="MenuForum">');
      while (list($Key, $Tab) = each($this->Tabs)) {
   		echo('<li><a class="'.$this->TabClass($this->CurrentTab, $Tab['Value']).' '.$Tab['CssClass'].'" href="'.$Tab['Url'].'" '.$Tab['Attributes'].'>'.$Tab['Text'].'</a></li>');
      }
      echo('</ul>
   </div>');
	$this->CallDelegate('PreBodyRender');	
   echo('<div class="Body">');
?>