$(function() {
	'use strict';
	
	// Server Setting
	var host = 'http://localhost';
	var api = 'service';
	var method = 'GET';
	
	// Client Setting
	var tester_url = window.location.toString();
	
	// Service API data
	var list;
	var usage;
	var statuscode;
	var params;
	
	$(document).ready(function() {
		// Hidden unused page
		$('#nav .client').siblings().addClass('non_active');
		$('#main > .client').siblings('div').hide();
		
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

	$('#nav h2').click(function() {
		$(this).removeClass('non_active').siblings().addClass('non_active');
		var name = $(this).attr('class');
		$('#main > .' + name).show().siblings('div').hide();
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
		bindPrams();
	});

	function loadServiceList(json) {
		$.ajax({
			dataType: 'json',
			cache: false,
			type: method,
			url: tester_url + 'client/AjaxHandler.php?' + JSON.stringify(json),
			success: function(output) {
				list = output['json']['list'];
				usage = output['json']['usage'];
				statuscode = output['json']['statuscode'];
				
				$.each(list, function() {
					var option = $('<option></option>');
					option.html(this);
					$('#main > .client #request .option .api').append(option);
				});
				
				loadAPIUsage(usage[list[0]]);
				genUsagePramas();
				
				$('#response .header').val(output['header']);
				$('#response .json').val(JSON.stringify(output['json']));
			}
		});
	}
	
	function loadAPIUsage(api) {
		$.each(api, function(method, action) {
			$('.method').append('<option>' + method + '</option>');
		});
	}
	
	function bindPrams() {
		$('#main > .client #request .params .key').autocomplete(params, {matchContains: true, width: 173});
	} 
	
	function genUsagePramas() {
		params = Array();
		
		$.each(usage, function() {
			$.each(this, function() {
				$.each(this, function() {
					$.each(this['input'], function() {
						if(jQuery.inArray(this[0], params) == -1)
							params.push(this[0]);
					});
				});
			});
		});
		bindPrams();
	}
	
	$('#main > .client #request .option .api').live('change', function() {
		var api = $('#main > .client #request .option .api').val();
		$('#main > .client #request .option .method').html('');
		$.each(usage[api], function(method, action) {
			$('#main > .client #request .option .method').append('<option>' + method + '</option>');
		});
	});
	
	function callService(json) {
		$.ajax({
			dataType: 'json',
			cache: false,
			type: method,
			url: tester_url + 'client/AjaxHandler.php?' + JSON.stringify(json),
			success: function(output) {
				$('#response .header').val(output['header']);
				if(output['json'] != undefined)
					$('#response .json').val(JSON.stringify(output['json']));
				else
					$('#response .json').val('');
			}
		});
	}
	
	$('#main > .client #request .option .submit').click(function() {
		var json = {};
		var request = '#main > .client #request ';
		
		// Host
		json['host'] = $(request + '.option .host').val();
		
		// Uri
		json['uri'] = $(request + '.option .api').val();
		if($(request + '.option .segments').val() != "")
			 json['uri'] += '/' + $(request + '.option .segments').val();
		
		// Method
		json['method'] = $(request + '.option .method').val();

		// File
		if($(request + '.file_path').val() != "")
			json['file'] = '/tmp/RESTester/' + $(request + '.file_path')[0].files.item(0).name;
		
		// Header
		$.each($(request + '.header div'), function() {
			if($(this).children('.key').val() != "" && $(this).children('.value').val() != "") {
				if(json['header'] == undefined)
					json['header'] = {};
				json['header'][$(this).children('.key').val()] = $(this).children('.value').val();
			}
		});
		
		// Params
		$.each($(request + '.params div'), function() {
			if($(this).children('.key').val() != "" && $(this).children('.value').val() != "") {
				if(json['params'] == undefined)
					json['params'] = {};
				json['params'][$(this).children('.key').val()] = $(this).children('.value').val();
			}
		});
		
		console.log(JSON.stringify(json));

		callService(json);
	});
});
