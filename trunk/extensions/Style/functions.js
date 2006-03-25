function SetStyle(StyleID, CustomStyle) {
	ChangeLoaderText("Processing...");
	SwitchLoader(1);
   var Url = "./extensions/Style/setstyle.php";
   var Parameters = "StyleID="+StyleID+"&Style="+escape(CustomStyle);
	var dm = new DataManager();
	dm.RequestFailedEvent = HandleFailure;
	dm.RequestCompleteEvent = RefreshPage;
	dm.LoadData(Url+"?"+Parameters);		
}