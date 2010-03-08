function SwitchTextMode(AjaxUrl, Switch, PostBackKey) {
   var dm = new DataManager();
   dm.Param = 'HtmlOn';
   dm.RequestFailedEvent = HandleFailure;
   dm.RequestCompleteEvent = RefreshPageWhenAjaxComplete;
   dm.LoadData(AjaxUrl+"?Type=HtmlOn&PostBackKey="+PostBackKey+"&Switch="+Switch);
}