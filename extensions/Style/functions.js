function SetStyle(StyleID, CustomStyle) {
	ChangeLoaderText("Processing...");
	SwitchLoader(1);
   var Url = "./extensions/Style/setstyle.php";
   var Parameters = "StyleID="+StyleID+"&Style="+escape(CustomStyle);
   var DataManager = new Ajax.Request(
      Url,
      { method: 'get', parameters: Parameters, onComplete: SetStyleComplete }
   );
}

function SetStyleComplete(Request) {
	ChangeLoaderText("Refreshing...");
	setTimeout("document.location.reload();",600);
}