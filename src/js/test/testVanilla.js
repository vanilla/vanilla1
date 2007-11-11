test( 'AddLabelValuePair()', function() {
	var container, fixture = new Fixture('AddLabelValuePair');

	fixture.set('<form action="#">' +
			'<div><input type="hidden" name="LabelValuePairCount" id="LabelValuePairCount" value="1"/></div>' +
			'<ul id="CustomInfo">' +
			'<li><input type="text" name="Label1" maxlength="20" class="LVLabelInput"/></li>' +
			'<li><input type="text" name="Value1" maxlength="200" class="LVValueInput"/></li>' +
			'</ul>' +
			'</form>');

	AddLabelValuePair();
	expect(13);

	equals($('#LabelValuePairCount').attr('value'), 2, 'test counter');

	$('#CustomInfo li input').each(function(i, el){

		function check(name, maxLength, className){
			equals(el.name, name, 'check name');
			equals(el.maxLength, maxLength, 'check max length');
			equals(el.className, className, 'check class name');
		}

		switch(i){
			case 0:
				check('Label1', 20, 'LVLabelInput');
				break;
			case 1:
				check('Value1', 200, 'LVValueInput');
				break;
			case 2:
				check('Label2', 20, 'LVLabelInput');
				break;
			case 3:
				check('Value2', 200, 'LVValueInput');
				break;
			default:
				ok(false, 'there should not have more than 4 element')
				break;
		}

	});

	fixture.empty();

});

test( 'DiscussionSwitch()', function() {
	var fixture = new Fixture('DiscussionSwitch');

	fixture.set('<p><a href="#" id="HideDiscussion">test</a></p>');

	setFauxDataManager(
		'ajax/switch.php?Type=Active&DiscussionID=SomeId&Switch=1&PostBackKey=TestPostBackKey',
		'', function(){
			equals(this.RequestCompleteEvent, RefreshPageWhenAjaxComplete, 'test complete event');
			equals(this.RequestFailedEvent, HandleFailure, 'test fail event');
			ok($('#HideDiscussion').hasClass('Progress'), 'test sender class');
			fixture.empty();
		});

	expect(4);
	DiscussionSwitch(
		'ajax/switch.php', 'Active', 'SomeId', '1', 'HideDiscussion', 'TestPostBackKey'
	);
});

test( 'HideComment()', function() {
	var fixture = new Fixture('DiscussionSwitch'), confirmBackUp;

	fixture.set('<p><a href="#" id="testHideComment">test</a></p>');

	confirmBackUp = window.confirm;
	window.confirm = function(text){
		equals(text, 'hide', 'confirme text');
		return true;
	}

	setFauxDataManager(
		'hide.php?Type=Comment&Switch=1&DiscussionID=SomeId&CommentID=OtherId&PostBackKey=TestPostBackKey',
		'',
		function(){
			equals(this.RequestCompleteEvent, RefreshPageWhenAjaxComplete, 'test complete event');
			equals(this.RequestFailedEvent, HandleFailure, 'test fail event');
			ok($('#testHideComment').hasClass('HideProgress'), 'test sender class');
			equals($('#HideDiscussion').text(), '', 'test sender content');
			fixture.empty();
			window.confirm = confirmBackUp;
		}
	);

	expect(6);
	HideComment('hide.php', '1', 'SomeId', 'OtherId', 'show', 'hide', 'testHideComment', 'TestPostBackKey');
});

test( 'SetBookmark()', function() {

});

test( 'ApplyBookmark()', function() {

});

test( 'BookmarkComplete()', function() {

});

test( 'BookmarkFailed()', function() {

});

test( 'ShowAdvancedSearch()', function() {

});

test( 'ShowSimpleSearch()', function() {

});

test( 'ToggleCategoryBlock()', function() {

});

test( 'ToggleCommentBox()', function() {

});

test( 'ToggleCommentBoxComplete()', function() {

});

test( 'WhisperBack()', function() {

});