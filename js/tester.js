$(function() {
	'use strict';
	
	var host = 'http://localhost';
	var api = 'service';
	var method = 'GET';
	
	$(document).ready(function() {
		$('#main > .setting').hide();
		$('#main > .setting .host input').val(host);
		$('#main > .setting .api input').val(api);
		$('#main > .setting .method input').val(method);
	});

	$('#nav > .setting').click(function() {
		$('#main > .setting').show();
		$('#main > .client').hide();
	});
	
	$('#nav > .client').click(function() {
		$('#main > .setting').hide();
		$('#main > .client').show();
	});
});
