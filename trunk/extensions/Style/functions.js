function SetStyle(AjaxUrl, StyleID, CustomStyle) {
   var Parameters = "StyleID="+StyleID+"&Style="+escape(CustomStyle);
	var dm = new DataManager();
	dm.RequestFailedEvent = HandleFailure;
	dm.RequestCompleteEvent = RefreshPageWhenAjaxComplete;
	dm.LoadData(AjaxUrl+"?"+Parameters);		
}