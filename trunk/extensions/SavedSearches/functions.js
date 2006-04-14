function RemoveSearch(AjaxUrl, SearchID) {
	var SearchToRemove = document.getElementById("SavedSearch_"+SearchID);
	var SavedSearchCount = document.getElementById("SavedSearchCount");
	var SearchSavingHelp = document.getElementById("SearchSavingHelp");
	if (SavedSearchCount && SearchSavingHelp && SearchToRemove) {
		if (confirm("Are you sure you want to remove this search?")) {
         var Parameters = "SearchID="+SearchID;
			var dm = new DataManager();
			dm.RequestFailedEvent = HandleFailure;
			dm.RequestCompleteEvent = RefreshPage;
			dm.LoadData(AjaxUrl+"?"+Parameters);		
		}
	}
}

function HideSearchInPage (originalRequest) {
	var RemovedSearchID = originalRequest.responseText;
	var SearchToRemove = document.getElementById("SavedSearch_"+RemovedSearchID);
	var SavedSearchCount = document.getElementById("SavedSearchCount");
	var SearchSavingHelp = document.getElementById("SearchSavingHelp");
	if (SavedSearchCount && SearchSavingHelp && SearchToRemove) {
   	SearchToRemove.style.display = "none";
		SavedSearchCount.value = SavedSearchCount.value - 1;
		if (SavedSearchCount.value <= 0) SearchSavingHelp.style.display = "block";			
	}
}