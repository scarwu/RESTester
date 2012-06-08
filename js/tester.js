$(function() {
	'use strict';
	
	var host = 'http://localhost';
	var api = 'service';
	var method = 'GET';
	var tester_url = window.location.toString();
	
	var list;
	var usage;
	
	$(document).ready(function() {
		$('#main > .setting').hide();
		$('#main > .setting .host input').val(host);
		$('#main > .setting .api input').val(api);
		$('#main > .setting .method input').val(method);
		
		$('#main > .client #request .option .host').val(host);
		
		loadServiceList({
			"host": $('#main > .setting .host input').val(),
			"uri": $('#main > .setting .api input').val(),
			"method": $('#main > .setting .method input').val()
		});
	});

	$('#main > .client > .setting').click(function() {
		$('#main > .setting').show();
		$('#main > .client').hide();
	});
	
	$('#main > .setting > .client').click(function() {
		$('#main > .setting').hide();
		$('#main > .client').show();
	});
	
	function addRow(target) {
		var div = $('<div></div>');
		var key = '<input type="text" class="key add" />';
		var value = '<input type="text" class="value add" />';
		div.html(key+value);
		$(target).append(div);
	}
	
	$('#main > .client #request .header .add').live('focus', function() {
		$('#main > .client #request .header .key').removeClass('add');
		$('#main > .client #request .header .value').removeClass('add');
		addRow('#main > .client #request .header');
	});
	
	$('#main > .client #request .params .add').live('focus', function() {
		$('#main > .client #request .params .key').removeClass('add');
		$('#main > .client #request .params .value').removeClass('add');
		addRow('#main > .client #request .params');
	});

	function loadServiceList(json_string) {
		json_string = JSON.stringify(json_string);
		$.ajax({
			dataType: 'json',
			cache: false,
			type: method,
			url: tester_url + 'AjaxHandler.php?' + json_string,
			success: function(output) {
				list = output['json']['list'];
				usage = output['json']['usage'];
				
				$.each(list, function() {
					var option = $('<option></option>');
					option.html(this);
					$('#main > .client #request .option .api').append(option);
				});
				
				$.each(usage[list[0]], function(method, action) {
					$('.method').append('<option>' + method + '</option>');
				});
				
				$('#response .header').val(output['header']);
				$('#response .json').val(JSON.stringify(output['json']));
			}
		});
	}
	
	$('#main > .client #request .option .api').live('change', function() {
		var api = $('#main > .client #request .option .api').val();
		$('#main > .client #request .option .method').html('');
		$.each(usage[api], function(method, action) {
			$('#main > .client #request .option .method').append('<option>' + method + '</option>');
		});
	});
	
	$('#main > .client #request .option .submit').click(function() {
		
	});
});
