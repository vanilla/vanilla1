/**
 * An easy way to create elements for testing
 */
fixture = {
	/**
	 * id of the container
	 * @param {string}
	 */
	selector: '#fixtures',

	/**
	 * Last set of html set. allow to easely reset the content while a test.
	 * @private
	 * @param {string}
	 */
	lastSet: '',

	/**
	 * Create the fixture
	 * @param {string} html code to create.
	 */
	set: function(html) {
		this.lastSet = html;
		$(this.selector).html(html);
	},

	/**
	 * Reset the fixture to the last set
	 */
	reset: function() {
		$(this.selector).html(this.lastSet);
	},

	/**
	 * empty the fixture container
	 */
	empty: function() {
		$(this.selector).empty();
	}
};

test('BlockSubmit()', function() {
	var evt, done, result;

	//We will check BlockSubmit return what we expect and that the handler is executed or not
	function handler() {
		done = true;
	}

	evt = { keyCode: 13};
	done = false;
	result = BlockSubmit(evt, handler);
	ok( ( result === false ) && ( done === true ), 'Standard event: BlockSubmit should block' );

	evt = { which: 13};
	done = false;
	result = BlockSubmit(evt, handler);
	ok( ( result === false ) && ( done === true ), 'ie event: BlockSubmit should block' );

	evt = {};
	done = false;
	result = BlockSubmit(evt, handler);
	ok( ( result === true ) && ( done === false ), 'no key event: BlockSubmit should not block' );

	evt = { keyCode: 18 };
	done = false;
	result = BlockSubmit(evt, handler);
	ok( ( result === true ) && ( done === false ), 'wrong key event: BlockSubmit should not block' );
});

test('CheckAll()', function(){
	var r1, r2, r3;

	fixture.set(
		'<form action="#">'
		+ '<input type="checkbox" name="testCheckBox" id="testCheckBox1"/>'
		+ '<input type="checkbox" name="testCheckBox" id="testCheckBox2"/>'
		+ '<input type="checkbox" name="testCheckBox" id="testCheckBox3"/>'
		+ '</form>'
	);

	//easy way to get the effect of CheckAll on our checkbox
	function getResult() {
		r1 = $('#testCheckBox1').attr('checked');
		r2 = $('#testCheckBox2').attr('checked');
		r3 = $('#testCheckBox3').attr('checked');
	}

	CheckAll('testCheckBox1');
	getResult();
	ok( !!r1 && !r2 && !r3, 'Only the first CheckBox should be checked');

	CheckAll('testCheckBox1,testCheckBox2');
	getResult();
	ok( !!r1 && !!r2 && !r3, 'first and second CheckBoxes should be checked');

	CheckAll('testCheckBox1,testCheckBox2,testCheckBox3');
	getResult();
	ok( !!r1 && !!r2 && !!r3, 'all CheckBoxes should be checked');

	fixture.empty();
});

test('CheckNone()', function(){
	var r1, r2, r3;

	fixture.set(
		'<form action="#">'
		+ '<input type="checkbox" name="testCheckBox" id="testCheckBox1" checked="checked"/>'
		+ '<input type="checkbox" name="testCheckBox" id="testCheckBox2" checked="checked"/>'
		+ '<input type="checkbox" name="testCheckBox" id="testCheckBox3" checked="checked"/>'
		+ '</form>'
	);

	//easy way to get the effect of CheckNone on our checkbox
	function getResult() {
		r1 = $('#testCheckBox1').attr('checked');
		r2 = $('#testCheckBox2').attr('checked');
		r3 = $('#testCheckBox3').attr('checked');
	}

	CheckNone('testCheckBox1');
	getResult();
	ok( !r1 && !!r2 && !!r3, 'the first checkbox should be unchecked');

	CheckNone('testCheckBox1,testCheckBox2');
	getResult();
	ok( !r1 && !r2 && !!r3, 'first and second checkbox should be unchecked');

	CheckNone('testCheckBox1,testCheckBox2,testCheckBox3');
	getResult();
	ok( !r1 && !r2 && !r3, 'all CheckBoxes should be unchecked');

	fixture.empty();
});

test('CheckSwitch()', function(){
	var result;

	fixture.set(
		'<form action="#"><input type="checkbox" name="testCheckBox" id="testCheckBox" checked="checked"/></form>'
	);

	ok( $('#testCheckBox').attr('checked') == true, 'fixture well set');

	CheckSwitch('testCheckBox', false);
	result = $('#testCheckBox').attr('checked');
	ok( !result, 'CheckBox should not be checked');

	CheckSwitch('testCheckBox', true);
	result = $('#testCheckBox').attr('checked');
	ok( !!result, 'CheckBox should be checked');

	fixture.empty();
});

test('ClearContents()', function(){
	var el;

	fixture.set('<p id="testP">test</p>');
	el = $('#testP');

	equals( el.html(), 'test', 'fixture well set');

	ClearContents(el.get()[0]);
	ok( !(el.html()), 'content cleared');

	fixture.empty();
});

test('CompletePreferenceSet()', function(){

	fixture.set('<p id="testP" class="test">test</p>');

	CompletePreferenceSet('testP');
	ok( $('#testP').attr('class') === 'PreferenceComplete', 'should have "PreferenceComplete" class');

	fixture.empty();
});

test('Explode()', function(){
	var a = Explode('a,b,c', ','), b = ['a','b','c'];

	ok( a.constructor === Array, 'test type of is array');
	equals( a[0], 'a', 'test array content' );
	equals( a[1], 'b', 'test array content' );
	equals( a[2], 'c', 'test array content' );
});

test('Focus()', function(){
	//How can I test that something is in focus
});

test('GetElements()', function(){
	var els;
	fixture.set('<p>not a test</p><p id="test_1">test 1</p><p id="test_2">test 2</p>');

	els = GetElements('p', 'test_');
	equals( els.length, 2, 'test length of array of element' );
	equals( els[0].id, 'test_1', 'test array content' );
	equals( els[1].id, 'test_2', 'test array content' );

	fixture.empty();
});

test('HideElement()', function(){
	var el;
	fixture.set('<p id="testP">test</p>');

	el = $('#testP');
	HideElement('testP');
	equals( el.css('display'), 'none', 'test display' );
	equals( el.html(), 'test', 'test the content is just hidden' );

	HideElement('testP', true);
	equals( el.css('display'), 'none', 'test display' );
	equals( el.html(), '', 'test the content is away' );

	fixture.empty();
});

test('PathFinder()', function(){
	var p, r;
	$('<link rel="home" href="http://' + document.domain + '/community/index.php" />').appendTo('head');
	p = new PathFinder();

	r = p.getRootPath('link', 'href', 'index.php');
	equals( r,'/community/', 'test with string' );

	r = p.getRootPath('link', 'href', /index\.php$/);
	equals( r, '/community/', 'test with regex' );
});

test('PopTermsOfService()', function(){});

test('PreferenceSet()', function(){
	fixture.set('<p id="testP">test</p>');

	expect(1);
	stop();

	PreferenceSet.call({Param: 'testP'});

	function check(){
		ok( $('#testP').is('.PreferenceComplete'), 'simple test' );
		start();
		fixture.empty();
	}
	setTimeout(check, 450);
});

test('RefreshPage()', function(){
	// ?? maybe using iFrame
});

test('RefreshPageWhenAjaxComplete()', function(){
	// ?? maybe using iFrame
});

test('SubmitForm()', function(){
	// ?? maybe using iFrame
});

test('SwitchElementClass()', function(){
	var ClassName, Comment;
	fixture.set('<p id="testA" class="classA">test</p><p id="testB">commentA</p>');

	// easy way to get effet of SwitchElementClass
	function getResult() {
		ClassName = $('#testA').attr('class');
		Comment = $('#testB').html();
	}

	SwitchElementClass('testA', 'testB', 'classA', 'classB', 'commentA', 'commentB');
	getResult();
	ok ( ClassName == 'classB' && Comment == 'commentB', 'switch to B' );

	SwitchElementClass('testA', 'testB', 'classA', 'classB', 'commentA', 'commentB');
	getResult();
	ok ( ClassName == 'classA' && Comment == 'commentA', 'switch to A' );

	fixture.empty();
});

test('SwitchExtension()', function(){
	fixture.set(
		'<form action="#"><div id="ajaxResult"><input type="checkbox" id="chkajaxResultID"/></form>'
	);
	expect(3);
	stop();

	// overwrite DatManager to check parameter (and not send anything)
	DataManager = function(){
		this.Param = '';
		this.RequestFailedEvent = '';
		this.RequestCompleteEvent= '';
		this.LoadData = function(url) {
			ok( $('#ajaxResult').is('.Processing'), 'Check item shows as Processing' );
			ok( this.Param == 'ajaxResult', 'check extension key' );
			ok( url == 'test.php?ExtensionKey=ajaxResult&PostBackKey=TestPostBackKey', 'check url' );
			start();
			fixture.empty();
		};
		return this;
	}

	SwitchExtension('test.php', 'ajaxResult', 'TestPostBackKey');

});

test('SwitchExtensionResult()', function(){
	fixture.set(
		'<form action="#"><div id="ajaxResult"><input type="checkbox" id="chkajaxResultID"/></form>'
	);

	expect(1);
	stop();
	SwitchExtensionResult({responseText: 'ajaxResult'});

	// SwitchExtensionResult will execute SwitchExtensionItemClass after 400 millisecond
	function CheckDisable(){
		ok( $('#ajaxResult').is('.Disabled'), 'Check item shows as Disabled' );
		start();
		fixture.empty();
	}

	setTimeout(CheckDisable, 450);
});

test('SwitchExtensionItemClass()', function(){
	fixture.set(
		'<form action="#"><div id="ajaxResult"><input type="checkbox" id="chkajaxResultID"/></form>'
	);
	SwitchExtensionItemClass('ajaxResult');
	ok( $('#ajaxResult').is('.Disabled'), 'Check item shows as Disabled' );

	$('#chkajaxResultID').attr('checked', 'checked');
	SwitchExtensionItemClass('ajaxResult');
	ok( $('#ajaxResult').is('.Enabled'), 'Check item shows as Enabled' );

	fixture.empty();
});

test('SwitchPreference()', function(){

	fixture.set(
		'<form action="#"><div id="ajaxResult"><input type="checkbox" id="ajaxResultID"/></form>'
	);

	expect(4);
	stop();

	// overwrite DatManager to check parameter (and not send anything)
	DataManager = function(){
		this.Param = '';
		this.RequestFailedEvent = '';
		this.RequestCompleteEvent = '';
		this.LoadData = function(url) {
			ok( $('#ajaxResult').is('.PreferenceProgress'), 'Check item shows as PreferenceProgress' );
			equals(this.Param, 'ajaxResult', 'check Preference Name' );
			equals(this.RequestCompleteEvent, PreferenceSet, 'Check success callback' );
			equals( url, 'test.php?Type=ajaxResult&PostBackKey=TestPostBackKey&Switch=false', 'check url' );
			start();
			fixture.empty();
		};
		return this;
	}

	SwitchPreference('test.php', 'ajaxResult', false, 'TestPostBackKey');
});

test('Trim()', function(){
	equals( Trim( '	 test ' ), 'test', 'Simple trim');
	equals( Trim( ' test	test ' ), 'test	test', 'Check preserve good white space');
	equals( Trim( 'test' ), 'test', 'preserve all');
});

test('UpdateCheck()', function(){
	expect(2);
	stop();

	// overwrite DatManager to check parameter (and not send anything)
	DataManager = function(){
		this.Param = '';
		this.RequestFailedEvent = '';
		this.RequestCompleteEvent = '';
		this.LoadData = function(url) {
			equals(this.Param, 'test.php', 'check Preference Name' );
			equals( url, 'test.php?RequestName=requestName&PostBackKey=TestPostBackKey', 'check url' );
			start();
		};
		return this;
	}

	UpdateCheck('test.php', 'requestName', 'TestPostBackKey');
});

test('UpdateCheckStatus()', function(){
	var BackUpUpdateCheck, count = 0;
	fixture.set(
		'<div id="Core"><div id="CoreDetails"></div></div>'
		+ '<div id="itemName"><div id="itemNameDetails"></div></div>'
		+ '<form action="#"><input name="FormPostBackKey" id="FormPostBackKey" value="TestPostBackKey"/></form>'
	);

	expect(32);
	stop();

	// UpdateCheck is called back by UpdateCheckStatus. We will check the parameter
	BackUpUpdateCheck = window.UpdateCheck
	window.UpdateCheck = function (url, itemName, PostBackKey){
		equals(url, 'test.php', 'check url send for' + itemName);
		equals(PostBackKey, 'TestPostBackKey', 'check PostBackKey send for' + itemName);
		count++
		if (count > 5) {
			window.UpdateCheck = BackUpUpdateCheck;
			start();
		}
	}

	// Test "COMPLETE" return message. it should not execute UpdateCheck
	UpdateCheckStatus.call({Param: 'test.php'}, {responseText: 'COMPLETE'});
	ok(!($('#Core').attr('class')), 'no changes' );
	ok(!($('#CoreDetails').html()), 'no changes' );
	ok(!($('#itemName').attr('class')), 'no changes' );
	ok(!($('#itemNameDetails').html()), 'no changes' );
	fixture.empty();

	// Test "ERROR" return messages. it should not execute UpdateCheck
	fixture.reset();
	UpdateCheckStatus.call({Param: 'test.php'}, {responseText: 'First|[ERROR]error message'});
	ok( $('#Core').is('.UpdateError'), 'Check core error class' );
	equals( $('#CoreDetails').html(), 'error message', 'Check error message' );

	fixture.reset();
	UpdateCheckStatus.call({Param: 'test.php'}, {responseText: 'itemName|[ERROR]error message'});
	ok( $('#itemName').is('.UpdateError'), 'Check extension error class' );
	equals( $('#itemNameDetails').html(), 'error message', 'Check error message' );

	// The other test will execute UpdateCheck
	fixture.reset();
	UpdateCheckStatus.call({Param: 'test.php'}, {responseText: 'First|[OLD]old message'});
	ok( $('#Core').is('.UpdateOld'), 'Check core old class' );
	equals( $('#CoreDetails').html(), 'old message', 'Check old installation message' );

	fixture.reset();
	UpdateCheckStatus.call({Param: 'test.php'}, {responseText: 'itemName|[OLD]old message'});
	ok( $('#itemName').is('.UpdateOld'), 'Check extension old class' );
	equals( $('#itemNameDetails').html(), 'old message', 'Check old extension message' );


	fixture.reset();
	UpdateCheckStatus.call({Param: 'test.php'}, {responseText: 'First|[UNKNOWN]unknown message'});
	ok( $('#Core').is('.UpdateUnknown'), 'Check core unknown class' );
	equals( $('#CoreDetails').html(), 'unknown message', 'Check unknown something message' );

	fixture.reset();
	UpdateCheckStatus.call({Param: 'test.php'}, {responseText: 'itemName|[UNKNOWN]unknown message'});
	ok( $('#itemName').is('.UpdateUnknown'), 'Check extension unknown class' );
	equals( $('#itemNameDetails').html(), 'unknown message', 'Check unknown extension message' );


	fixture.reset();
	UpdateCheckStatus.call({Param: 'test.php'}, {responseText: 'First|[GOOD]good message'});
	ok( $('#Core').is('.UpdateGood'), 'Check core good class' );
	equals( $('#CoreDetails').html(), 'good message', 'Check good version message' );

	fixture.reset();
	UpdateCheckStatus.call({Param: 'test.php'}, {responseText: 'itemName|[GOOD]good message'});
	ok( $('#itemName').is('.UpdateGood'), 'Check extendion good class ' );
	equals( $('#itemNameDetails').html(), 'good message', 'Check good extension message' );

});

// This test is imcomplete, it should check the form is submitted
test('Wait()', function(){
	fixture.set(
		'<form id="testForm" target="testIframe" action="http://getvanilla.com"><input type="submit" name="testInput" value="submit" id="testInput"/></form>'
		+ '<iframe name="testIframe" id="testIframe" width="200px" height="200px" src="http://www.google.com"/>'
	);

	Input = $('#testInput');
	Wait(Input.get()[0], 'Waiting...');
	ok(Input.attr('disabled'), 'Check submit button is disable');

	fixture.empty();
});

test('WriteEmail()', function(){
	fixture.set('<div id="testDiv">test<div id="testScript">test</div></p>');

	WriteEmail('example.com', 'example', 'test', 'testScript');
	equals( $('#testDiv a').get().length, 1, 'Check the link is created' );
	equals( $('#testDiv a').html(), 'test', 'Check link test' );
	equals( $('#testDiv a').attr('href'), 'mailto:example@example.com', 'check address link');

	fixture.empty();
});