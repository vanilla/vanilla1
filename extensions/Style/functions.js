function SetStyle(AjaxUrl, StyleID, CustomStyle) {
	ChangeLoaderText("Processing...");
	SwitchLoader(1);
   var Parameters = "StyleID="+StyleID+"&Style="+escape(CustomStyle);
	var dm = new DataManager();
	dm.RequestFailedEvent = HandleFailure;
	dm.RequestCompleteEvent = RefreshPage;
	dm.LoadData(AjaxUrl+"?"+Parameters);		
}