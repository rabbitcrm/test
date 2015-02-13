/********************************************************************************+
 * Thze contents of this file are subject to the Skyzon CRM License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  Skyzon CRM
 * The Initial Developer of the Original Code is Skyzon Technologies.
 * Portions created by Skyzon Technologies are Copyright (C) skyzon technologies.
 * All Rights Reserved.
 +********************************************************************************/

$(function() {

	// Utility functions
	var bczUtils = {
		dataTableCallback: function(oSettings) {
console.log('handle hidden columns for DT...' + $('table.bcz-data-table').length);			
    	// Hide a set of data for mobile views
	
			$('table.bcz-data-table tbody tr td').each(function() {
				var parentTable = $(this).parents('table.bcz-data-table');
				if (parentTable.find('thead tr th:eq('+$(this).index()+')').hasClass('hidden-xs')) {
					$(this).addClass('hidden-xs');
				}
				if (parentTable.find('thead tr th:eq('+$(this).index()+')').hasClass('bcz-row-actions')) {
					$(this).addClass('text-center bcz-row-actions');
				}
			});
    }
	};
	
	
	/* Redoirect button click handler */
	$('button.bcz-btn-redirect').on('click', function() {
		if ($(this).data('redirect-url'))	location.href = $(this).data('redirect-url');
	});

	// Auto-complete handler
	if ($('input.bcz-auto-complete').length) {
		$.get(appBaseUrl+$('input.bcz-auto-complete').data('sourcepath'), function(data, status, jqXHR) {
			if (data.length) {
				$('input.bcz-auto-complete').autocomplete({
				  //url: appBaseUrl + $('input.bcz-auto-complete').data('sourcepath'),
				  minChars: 3,
				  maxItemsToShow: 10,
				  data: data,
				  appendTo: '#acresults',
				  onItemSelect: function(item) {
				  	var selectFlag = $('input.bcz-auto-complete').data('selectFlag');
						if (!selectFlag) {
							$('input.bcz-auto-complete').val('');
						} else {
							
							for (var i = data.length - 1; i >= 0; i--) {
								if (item.value == data[i]['value']){ if (confirm("This account is already created. Do you want to take me to the Account detail page?")) {
        location.href = appBaseUrl + 'companies/details/' + data[i]['key'];
		formmodified=3;
    }
    return false;
	
								}
						
					 
								 
							};
							
	
							//$('input[name=company_exists]').val(item.value);
						}
						return false;
				  },
				  response: function(event, ui) {
					  
					  
					  
					
					}
				});
				
			}
		});
	}

	/* Handling the clicks for items which needs confirmation */
	$('body').on('click', '.bcz-confirm-operation', function() {
		if($(this).hasClass('bcz-confirm-active')) return;

		$('#bcz_confirmation_modal').modal('show');
		$(this).addClass('bcz-confirm-active');
	});
	$('#bcz_confirmation_modal .modal-footer button').on('click', function() {
		if ($(this).hasClass('btn-primary')) {
			$('.bcz-confirm-operation.bcz-confirm-active').data('bcz-confirm', true);
			$('.bcz-confirm-operation.bcz-confirm-active').trigger('click');
			$('#bcz_confirmation_modal').modal('hide');
		}
		$('.bcz-confirm-operation.bcz-confirm-active').removeClass('bcz-confirm-active');
	});

	// Handling compose email modal
	if ($('#compose_email_modal').length) {
		// $(".bcz-btn-email-modal").on('click', function() {
		// 	$('#compose_email_modal').modal('show');
		// 	return false;
		// });

		$('#send_email').on('click', function() {
			var sendEmailBtn = $(this);
			var sendEmailForm = $(this).closest('form');
			sendEmailForm.find('.alert').addClass('hide');

			if (sendEmailForm.parsley('validate')) sendEmailBtn.text('Sending...').addClass('disabled');
			sendEmailForm.ajaxForm(function(data, status, jqXHR) {
				if (data.success) {
					sendEmailForm.resetForm();
					$('#compose_email_modal').modal('hide');

				} else if (data) {
					var emailsPanel = $('.bcz-btn-email-modal').parents('.panel').find('.panel-body');
					emailsPanel.html(data);

					// Reconstruct the user table
					emailsPanel.find('table.bcz-data-table').dataTable({
						
						"bProcessing": true,
						"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
						"sPaginationType": "full_numbers",
				    "bServerSide": true,
				    "sAjaxSource": emailsPanel.find('table.bcz-data-table').data('source'),
				    "fnDrawCallback": bczUtils.dataTableCallback
					});

					sendEmailForm.resetForm();
					$('#compose_email_modal').modal('hide');

					// Open content panel
					emailsPanel.parent().removeClass('collapse').addClass('in').css({'height' : 'auto'});
					$('.bcz-add-item-entity.bcz-active-btn').button('reset');
					$('.bcz-add-item-entity.bcz-active-btn').removeClass('bcz-active-btn');
				} else {
					sendEmailForm.find('.bcz-status-msg').text('Something went wrong while sending this email.');
					sendEmailForm.find('.alert').removeClass('hide');
				}

				sendEmailBtn.text('Send').removeClass('disabled');
			});
			
			
		});
	}


  if($('.Home').length) {
 

  if ($('.home_comy').length) {
	   
					 // Reconstruct the user table
				$('table.home_comy').dataTable({
					"bProcessing": true,
					
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"aaSorting": [],
					"aoColumns": [{ "bSortable": false },{ "bSortable": false }, { "bSortable": false }, { "bSortable": false }],
					 "sPaginationType": "bootstrap",
			    "bServerSide": true,
			    "sAjaxSource": $('.home_comy').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				});
					  
				} 
 
   if ($('.home_lead').length) {

					 // Reconstruct the user table
				$('table.home_lead').dataTable({
					"bProcessing": true,
					
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"aaSorting": [],
					"aoColumns": [{ "bSortable": false },{ "bSortable": false }, { "bSortable": false }, { "bSortable": false }],
					 "sPaginationType": "bootstrap",
			    "bServerSide": true,
			    "sAjaxSource": $('.home_lead').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				});
				} 
				
				
   if ($('#New_Opportunities table.home_opp').length) {
	  
				 if ($('table.home_opp').length) {
					 // Reconstruct the user table
				$('table.home_opp').dataTable({
					"bProcessing": true,
					
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"aaSorting": [],
					"aoColumns": [{ "bSortable": false },{ "bSortable": false }, { "bSortable": false }, { "bSortable": false }],
					 "sPaginationType": "bootstrap",
			    "bServerSide": true,
			    "sAjaxSource": $('table.home_opp').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				});
				}

 }
 
 
  if ($('.top_opp').length) {

					 // Reconstruct the user table
				$('.top_opp').dataTable({
					"bProcessing": true,
					
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"aaSorting": [],
					"aoColumns": [{ "bSortable": false },{ "bSortable": false }],
					  "sPaginationType": "bootstrap",
					
			    "bServerSide": true,
			    "sAjaxSource": $('.top_opp').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				});
				} 
 
 
				 if ($('.latest_opp').length) {
	   
					 // Reconstruct the user table
				$('table.latest_opp').dataTable({
					"bProcessing": true,
					
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"aaSorting": [],
					"aoColumns": [{ "bSortable": false },{ "bSortable": false }],
					  "sPaginationType": "bootstrap",
			    "bServerSide": true,
			    "sAjaxSource": $('.latest_opp').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				});
				} 
				
				
			 }	
	// Handling search in the header bar 
  $("#bcz_search").on('keyup', function () {
	  
	  // Remove all the previous results
	  
	  if($(this).val().length>=2)
	  {
	
		$('.bcz-search-form .dropdown-menu li').remove();

		// Return with status message if no query specified to search
		var searchString = $(this).val().trim();
		
		
	

		// Get call to search for the specified query string
		$.get(appBaseUrl+'search/matchesJson?query='+searchString, function(data, status, jqXHR) {
			
			var resultsHtml = "";
			if (data.success) {
				var cid;
				var jk=0;
				for (var i = 0; i < data.results.length; i++) {
					if(data.results[i].type!="")
					{
						jk++;
					switch (data.results[i].type) {
						case 'Opportunity':
						  	labelType = 'success';
						  	break;
						case 'lead':
						  	labelType = 'info';
						  	break;
						case 'contact':
						  	labelType = 'warning';
						  	break;
						case 'Account':
						  	labelType = 'primary';
						  	break;
						case 'Ticket':
						  	labelType = 'danger';
						  	break;
					}
					
					if(data.results[i].type=="contact")
					{
						if(cid!=data.results[i].id)
						{
							cid=data.results[i].id;
						var itemUrl = appBaseUrl + data.results[i].urlPrefix + '/' + data.results[i].id;
					resultsHtml += "<li><a title='"+data.results[i].name+"' href='"+itemUrl+"'><span class='search-item bcz-text-ellipsis'>"+data.results[i].name+"</span> <span class='label label-"+labelType+" '>"+data.results[i].type+"</span></a></li>";
					if (i < (data.results.length - 1)) resultsHtml += "<li class='divider'></li>";
					
						}
					}
					else
					{

					var itemUrl = appBaseUrl + data.results[i].urlPrefix + '/' + data.results[i].id;
					resultsHtml += "<li><a href='"+itemUrl+"'><span class='search-item bcz-text-ellipsis'>"+data.results[i].name+"</span> <span class='label label-"+labelType+" '>"+data.results[i].type+"</span></a></li>";
					if (i < (data.results.length - 1)) resultsHtml += "<li class='divider'></li>";
					}
				}
				if(jk==0)
				{
					resultsHtml = '<li class="padder-l-mini text-center">No results found</li>';
				}
			}

			} else {
				resultsHtml = '<li class="padder-l-mini text-center">No results found</li>';
			}

			if (status == 'success') $('.bcz-search-form .dropdown-menu').html(resultsHtml).removeClass('hide');
		});
	  }
	  else
	  {
		   $('.bcz-search-form .dropdown-menu').html('').addClass('hide');
	  }
	  
		});
  $("#bcz_search").on('focus', function () {
  	$('.bcz-search-form .dropdown-menu').addClass('hide');	// Hide the search results dropdown
  });

	/* Datatable construction handler */
	if ($('table.bcz-data-table').length) {
		$('table.bcz-data-table').each(function() {
			if ($(this).data('source')) {
				
				
					if ($('.Opportunities').length) {
				$(this).dataTable({
					 
					"bProcessing": true,
					 "aaSorting": [[6, 'desc']],
					
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $(this).data('source'),
				
			    "fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if ($('.campaign').length) {
				$(this).dataTable({
					 
					"bProcessing": true,
					 "aaSorting": [[6, 'desc']],
					
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $(this).data('source'),
				
			    "fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if ($('.tasks').length) {
				$(this).dataTable({
					 
					"bProcessing": true,
					 "aaSorting": [[6, 'desc']],
					
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $(this).data('source'),
				
			    "fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if ($('.leads').length) {
				$(this).dataTable({
					 
					"bProcessing": true,
					 "aaSorting": [[5, 'desc']],
					
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $(this).data('source'),
				
			    "fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if ($('.quotes').length) {
				$(this).dataTable({
					 
					"bProcessing": true,
					 "aaSorting": [[5, 'desc']],
					
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $(this).data('source'),
				
			    "fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if ($('.orders').length) {
				$(this).dataTable({
					 
					"bProcessing": true,
					 "aaSorting": [[5, 'desc']],
					
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $(this).data('source'),
				
			    "fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if ($('.cases').length) {
				$(this).dataTable({
					 
					"bProcessing": true,
					 "aaSorting": [[6, 'desc']],
					
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $(this).data('source'),
				
			    "fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				

				else
				{
					
					$(this).dataTable({
					 
					"bProcessing": true,
					 "aaSorting": [[6, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $(this).data('source'),
				
			    "fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				
				
				
			} else {
				
				$(this).dataTable({
					"bProcessing": true,
					 "aaSorting": [[6, 'desc']],
					"sPaginationType": "full_numbers",
			    "fnDrawCallback": bczUtils.dataTableCallback
				});				
			}
		});
	}

	/* Filters change handler */
	if ($('.bcz-filters').length) {
			
		$('.bcz-filters .select2-option').on('change', function(e) {
			var params = {};
			$('.bcz-filters select').each(function() {
				params[$(this).attr('name')] = $(this).val();
				
				
			});

			$.post($(this).parents('.bcz-filters').data('filter-action'), {'filters': params}, function(data, status, jqXHR) {
				$('.bcz-filters-content').html(data);
					$('.export').val('1');

			
				// Construct datatable
				
					if ($('.Opportunities').length) {
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[6, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if($('.deal-details').length)
				
				 {
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[5, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback

				});
				}
				
				else if ($('.campaign').length) {
				$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[6, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				
				else if ($('.tasks').length) {
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[6, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if ($('.leads').length) {
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[5, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if ($('.quotes').length) {
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[5, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if ($('.orders').length) {
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[5, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if ($('.cases').length) {
			
				$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[6, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				
				else
				{
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
					
				}
				
			});
		});
		
		
		var val=0;
		if($('.1').val()!=""){val=1;}else if($('.2').val()!=""){val=1;}else if($('.3').val()!=""){val=1;}else if($('.4').val()!=""){val=1;}else{val=0;}
		
		if(val!="0")
		{
			var params = {};
			$('.bcz-filters select').each(function() {
				params[$(this).attr('name')] = $(this).val();
				
			});

			$.post($(this).parents('.bcz-filters').data('filter-action'), {'filters': params}, function(data, status, jqXHR) {
				$('.bcz-filters-content').html(data);
			

				// Construct datatable
				
					if ($('.Opportunities').length) {
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[6, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if($('.deal-details').length)
				
				 {
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[5, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if ($('.tasks').length) {
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[6, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if ($('.leads').length) {
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[5, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if ($('.quotes').length) {
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[5, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if ($('.orders').length) {
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[5, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				else if ($('.cases').length) {
			
				$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[6, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				}
				
				else
				{
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
					
				}
				
			});
	}
	}

	/* Select boxes handling */ 
	if ($.fn.select2) {
	    $(".select2-option").select2();
	}
	
	
	
	$('.campaign_search').on('keyup', function(e) {

		  var first_name=$('.campaign_search').val();
		  var params = {};
				params['campaign_name'] = first_name;
				
				if(first_name.length>=1)
				{
					$('.export').val('1');
					
				}
				else
				{
					$('.export').val('0');
				}
				
			
			$.post($(this).parents('.bcz-filters').data('filter-action'), {'filters': params}, function(data, status, jqXHR) {
				
				  
				$('.bcz-filters-content').html(data);
				// Construct datatable
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[5, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				});
	 
		  
	  
	});
	
	
	
	if ($('.leads').length) {
	$('.lead_search').on('keyup', function(e) {

		  var first_name=$('.lead_search').val();
		  var params = {};
				params['first_name'] = first_name;
				
				if(first_name.length>=1)
				{
					$('.export').val('1');
					
				}
				else
				{
					$('.export').val('0');
				}
				
			
			$.post($(this).parents('.bcz-filters').data('filter-action'), {'filters': params}, function(data, status, jqXHR) {
				
				  
				$('.bcz-filters-content').html(data);
				// Construct datatable
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[5, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				});
	 
		  
	  
	});
	}
	
		if ($('.Opportunities').length) {
	$('.Opportunities_search').on('keyup', function(e) {

		  var Opportunities_search=$('.Opportunities_search').val();
		  var params = {};
				params['first'] = Opportunities_search;
					if(Opportunities_search.length>=1)
				{
					$('.export').val('1');
					
				}
				else
				{
					$('.export').val('0');
				}
			$.post($(this).parents('.bcz-filters').data('filter-action'), {'filters': params}, function(data, status, jqXHR) {
				$('.bcz-filters-content').html(data);
				// Construct datatable
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[6, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				
			});
	 
		  
	  
	});
	}
	
			if ($('.tasks').length) {
	$('.tasks_search').on('keyup', function(e) {

		  var tasks_search=$('.tasks_search').val();
		  var params = {};
				params['first'] = tasks_search;
				if(tasks_search.length>=1)
				{
					$('.export').val('1');
					
				}
				else
				{
					$('.export').val('0');
				}
			$.post($(this).parents('.bcz-filters').data('filter-action'), {'filters': params}, function(data, status, jqXHR) {
				$('.bcz-filters-content').html(data);
				// Construct datatable
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[6, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				
			});
	 
		  
	  
	});
	}
	
	
				
	
	if ($('.contacts').length) {
	$('.contacts_search').on('keyup', function(e) {
		  var contacts_search=$('.contacts_search').val();
		  var params = {};
				params['first'] = contacts_search;
				
				if(contacts_search.length>=1)
				{
					$('.export').val('1');
					
				}
				else
				{
					$('.export').val('0');
				}
			$.post($(this).parents('.bcz-filters').data('filter-action'), {'filters': params}, function(data, status, jqXHR) {
				$('.bcz-filters-content').html(data);
				// Construct datatable
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[0, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
			});
	 
		  
	  
	});
	}
	
	if ($('.Accounts').length) {
	$('.accounts_search').on('keyup', function(e) {

		  var accounts_search=$('.accounts_search').val();
		  var params = {};
				params['first'] = accounts_search;
				if(accounts_search.length>=1)
				{
					$('.export').val('1');
					
				}
				else
				{
					$('.export').val('0');
				}
			$.post($(this).parents('.bcz-filters').data('filter-action'), {'filters': params}, function(data, status, jqXHR) {
				$('.bcz-filters-content').html(data);
				// Construct datatable
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[0, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				
			});
	 
		  
	  
	});
	}
	
		if ($('.products').length) {
	$('.products_search').on('keyup', function(e) {

		  var products_search=$('.products_search').val();
		  var params = {};
				params['first'] = products_search;
				if(products_search.length>=1)
				{
					$('.export').val('1');
					
				}
				else
				{
					$('.export').val('0');
				}
			$.post($(this).parents('.bcz-filters').data('filter-action'), {'filters': params}, function(data, status, jqXHR) {
				
				$('.bcz-filters-content').html(data);
				// Construct datatable
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[0, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				
			});
	 
		  
	  
	});
	}
	
			if ($('.quotes').length) {
	$('.quotes_search').on('keyup', function(e) {
		
		

		  var quotes_search=$('.quotes_search').val();
		  var params = {};
				params['first'] = quotes_search;
				if(quotes_search.length>=1)
				{
					$('.export').val('1');
					
				}
				else
				{
					$('.export').val('0');
				}
			$.post($(this).parents('.bcz-filters').data('filter-action'), {'filters': params}, function(data, status, jqXHR) {
				$('.bcz-filters-content').html(data);
				// Construct datatable
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[5, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				
			});
	 
		  
	  
	});
	}
				if ($('.orders').length) {
	$('.orders_search').on('keyup', function(e) {

		  var orders_search=$('.orders_search').val();
		  var params = {};
				params['first'] = orders_search;
				
				if(orders_search.length>=1)
				{
					$('.export').val('1');
					
				}
				else
				{
					$('.export').val('0');
				}
			$.post($(this).parents('.bcz-filters').data('filter-action'), {'filters': params}, function(data, status, jqXHR) {
				$('.bcz-filters-content').html(data);
				// Construct datatable
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[5, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				
			});
	 
		  
	  
	});
	}
	
			if ($('.cases').length) {
	$('.cases_search').on('keyup', function(e) {

		  var cases_search=$('.cases_search').val();
		  var params = {};
				params['first'] = cases_search;
				if(cases_search.length>=1)
				{
					$('.export').val('1');
					
				}
				else
				{
					$('.export').val('0');
				}
			$.post($(this).parents('.bcz-filters').data('filter-action'), {'filters': params}, function(data, status, jqXHR) {
				$('.bcz-filters-content').html(data);
				// Construct datatable
					$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					 "aaSorting": [[6, 'desc']],
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
				
			});
	 
		  
	  
	});
	}
	
	

	// Scripts to handle note creation flow
	if ($('#add_note').length) {	
		$('button#add_note').on('click', function() {
			var addNoteBtn = $(this);

			// Note field value checkup
			var note = addNoteBtn.parents('.media').find('input[name=note]').val();
			if (note == '')	return false;

			// Add note through AJAX
			var formData = addNoteBtn.data();
			$.post(formData.action, {id: formData.id, type: formData.type, note: note}, function(data, status, jqXHR) {
				var notesPanelBody = addNoteBtn.closest('.panel-body');
				
				// Remove no notes message
				if (notesPanelBody.find('.bcz-no-data-msg').length) {
					notesPanelBody.find('.bcz-no-data-msg').remove();	// If no notes exist previously
				}

				// Add separator to note
				var separator = (notesPanelBody.find('article.media').length > 1) ? '<div class="line pull-in"></div>' : '';
				data += separator;
				addNoteBtn.closest('article.media').after(data);

				// Reset form fields
				addNoteBtn.parents('.media').find('input[name=note]').val('');
				addNoteBtn.text('ADD').removeClass('disabled').attr('disabled', false);
			});
		});
	}

	// Scripts to handle entity doc upload and deletion flows
	if ($('.bcz-docs-container').length) {
		$('.bcz-docs-container').on('click', 'button.delete-entity-doc', function() {
			if (!$(this).data('bcz-confirm')) return;

			var deleteDocBtn = $(this);
			var formData = deleteDocBtn.data();
console.log(formData);
			var parentPanel = $(this).parents('.panel-body');
			$.get(formData.action, {id: formData.id, name: formData.name}, function(data, status, jqXHR) {
				if (data.success) {
					if (deleteDocBtn.parents('article.media').next('div.line.pull-in').length) deleteDocBtn.parents('article.media').next('div.line.pull-in').remove();
					else if (deleteDocBtn.parents('article.media').prev('div.line.pull-in').length) deleteDocBtn.parents('article.media').prev('div.line.pull-in').remove()

					deleteDocBtn.parents('article.media').remove();

					if (!parentPanel.find('article.media').length) {
						parentPanel.find('form.bcz-file-upload-form').after('<p class="bcz-no-data-msg h5 m-t-large">No documents uploaded yet.</p>');
					}
				}
			});
		});
	}

	// File upload flow
	if ($('form.bcz-file-upload-form').length) {
		
	
		// Submission handler
		$('form.bcz-file-upload-form input[type=submit]').on('click', function() {
			
				
				
			var uploadDocBtn = $(this);
			var uploadDocForm = $(this).closest('form');
			uploadDocForm.find('.upload-indicator').removeClass('hide');
			uploadDocBtn.closest('.panel-body').find('div.text-danger').text('');

			uploadDocForm.ajaxForm(function(data, status, jqXHR) {
				var docsPanelBody = uploadDocBtn.closest('.panel-body');

				if (data.message) {
					docsPanelBody.find('div.text-danger').html(data.message);
				} else {
					// Remove no docs message
					if (docsPanelBody.find('.bcz-no-data-msg').length) {
						docsPanelBody.find('.bcz-no-data-msg').remove();	// If no notes exist previously
					}

					// Add separator to doc
					var separator = docsPanelBody.find('article.media').length ? '<div class="line pull-in"></div>' : '';
					data += separator;
					uploadDocForm.after(data);
				}

				// Reset form fields
				uploadDocForm.resetForm();
				uploadDocForm.find('.file-input-name').html('');
				uploadDocForm.find('.upload-indicator').addClass('hide');
			});
		});

		// File selection handler
		
		$('.fileschg').on('change', function() {
			if (!$(this).val()) return false;
			$(this).parents('form.bcz-file-upload-form').find('input[type=submit]').trigger('click');
		});
	}




	
	
	/* OLD FLOW with MODAL 
	if ($('#create_note_modal').length) {	
		$('button#create_note').on('click', function() {
			var addNoteBtn = $(this);
			var addNoteForm = $(this).closest('form');
			addNoteForm.find('.alert').addClass('hide');

			$.post(addNoteForm.attr('action'), addNoteForm.serialize(), function(data, status, jqXHR) {
				if (data) {
					var notesPanelId = $('#new_note').parent().attr('href');
					var notesPanelBody = $(notesPanelId + ' .panel-body');
					if ($(notesPanelId + ' .bcz-no-data-msg').length) {
						notesPanelBody.html(data);	// If no notes exist previously
					} else {
						notesPanelBody.prepend(data + '<div class="line pull-in"></div>');	// If notes exist already
					}
					$('#new_note').parent().trigger('click');
					$('#create_note_modal').modal('hide');

				} else {
					addNoteForm.find('.bcz-status-msg').text('Something went wrong while adding your note, please try again after sometime.');
					addNoteForm.find('.alert').removeClass('hide');
				}

				addNoteBtn.text('Save').removeClass('disabled').attr('disabled', false);
			});
		});
	}
	*/
	
	
	
	/* Campaign details page */
	if($('body.campaign-details').length) {
		// Action button click handler
		$('.Campaign-actions a').on('click', function(e) {
			if ($(this).data('redirect-url'))	location.href = $(this).data('redirect-url');
			else $($(this).attr('href')).modal('show');

			if ($(this).hasClass('bcz-confirm-active') && $(this).data('bcz-confirm')) {
			
				$.post(appBaseUrl+'campaign/delete', {'campaign_id': $(this).parents('.page-actions').data('pageid')}, function(data, status, jqXHR) {
					if (data.success && data.redirectUrl) {
						
						location.href = data.redirectUrl;
					} else {
						
						// TODO: handle error response
					}
				});
			}
		});

		// Reassign form submission handler
		$('button#reassign_campaign').on('click', function() {
			var reassignBtn = $(this);
			var reassignForm = $(this).closest('form');
			reassignForm.find('.alert').addClass('hide');

			$.post(reassignForm.attr('action'), reassignForm.serialize(), function(data, status, jqXHR) {
				if (data.success) {
					if (data.redirectUrl) location.href = data.redirectUrl;
					else location.reload();
				}
			});
		});

		

		// Create button click handlers for addind a new note / task / doc
		$('.panel-group .panel-heading a > button').on('click', function(e) {
			$($(this).data('modal-id')).modal('show');
		});
	}

	

	/* Lead details page */
	if($('body.lead-details').length) {
		// Action button click handler
		$('.lead-actions a').on('click', function(e) {
			if ($(this).data('redirect-url'))	location.href = $(this).data('redirect-url');
			else $($(this).attr('href')).modal('show');

			if ($(this).hasClass('bcz-confirm-active') && $(this).data('bcz-confirm')) {
				$.post(appBaseUrl+'leads/delete', {'lead_id': $(this).parents('.page-actions').data('pageid')}, function(data, status, jqXHR) {
					if (data.success && data.redirectUrl) {
						location.href = data.redirectUrl;
					} else {
						// TODO: handle error response
					}
				});
			}
		});

		// Reassign form submission handler
		$('button#reassign_lead').on('click', function() {
			var reassignBtn = $(this);
			var reassignForm = $(this).closest('form');
			reassignForm.find('.alert').addClass('hide');

			$.post(reassignForm.attr('action'), reassignForm.serialize(), function(data, status, jqXHR) {
				if (data.success) {
					if (data.redirectUrl) location.href = data.redirectUrl;
					else location.reload();
				}
			});
		});

		// Convert form submission handler
		$('button#convert_lead').on('click', function() {
			var convertBtn = $(this);
			var convertForm = $(this).closest('form');
			convertForm.find('.alert').addClass('hide');

			convertForm.ajaxForm(function(data, status, jqXHR) {
				if (data.success) {
					if (data.redirectUrl) location.href = data.redirectUrl;
					else location.reload();
				} else {
					convertForm.find('.bcz-status-msg').text(data.message);
					convertForm.find('.alert').removeClass('hide');
					convertBtn.text('Convert').removeClass('disabled').attr('disabled', false);
				}
      });
		});

		// Create button click handlers for addind a new note / task / doc
		$('.panel-group .panel-heading a > button').on('click', function(e) {
			$($(this).data('modal-id')).modal('show');
		});
	}

	/* Deal details page */
	
	
	
	if($('body.deal-details').length) {
		
		$('.wizard .actions').on('click', 'button', function(e) {
			var params = {};
			params.next = $(this).hasClass('next-btn');
			params.prev = !params.next;
			params.id = $(this).parent().data('id');
			var activeStage = $(this).parents('.wizard').find('ul.steps li.active');
			params.stage = params.next ? activeStage.next().data('stage') : activeStage.prev().data('stage');
			$.post(appBaseUrl+'deals/changestage', params, function(data, status, jqXHR) {
				if (data.success) {
					/*alert('no no');
					
						$.ajax({
								type: "POST",
								url: appBaseUrl+'deals/changestagemenu',
								data: params, 
								dataType:'json',
								success: function(datas){
									if(datas)
									{
										 var items = [];
										 var j=0
 										 $.each( datas, function( key, val ) {
											 j++;
   										 items.push( "<li data-stage='"+j+"'><span class='badge'>"+j+"</span>"+val+"</li>" );
										});
											
	
									$('.wizard').html(items);
									alert(items);
									}
									else
									{
										alert(items);
									}
								}
						});*/
					
					$('.wizard .steps li').removeClass('active');
					$('.wizard .steps li span.badge').removeClass('badge-info');
					if (params.next) {
						activeStage.next().addClass('active').find('span.badge').addClass('badge-info');
					} else {
						activeStage.prev().addClass('active').find('span.badge').addClass('badge-info');
					}

					// Show or hide the stage change buttons
					$('.wizard .actions button').removeClass('hide');
					if (!$('.wizard .steps li.active').prev('li').length) $('.wizard .actions button.prev-btn').addClass('hide');
					if (!$('.wizard .steps li.active').next('li').length) $('.wizard .actions button.next-btn').addClass('hide');
				}
			});
		});

		// Action button click handler
		$('.deal-actions a').on('click', function(e) {
			if ($(this).data('redirect-url'))	location.href = $(this).data('redirect-url');
			else $($(this).attr('href')).modal('show');

			if ($(this).hasClass('bcz-confirm-active') && $(this).data('bcz-confirm')) {
				$.post(appBaseUrl+'deals/delete', {'deal_id': $(this).parents('.page-actions').data('pageid')}, function(data, status, jqXHR) {
					if (data.success && data.redirectUrl) {
						location.href = data.redirectUrl;
					} else {
						// TODO: handle error response
					}
				});
			}
		});

		// Reassign form submission handler
		$('button#reassign_deal').on('click', function() {
			var reassignBtn = $(this);
			var reassignForm = $(this).closest('form');
			reassignForm.find('.alert').addClass('hide');

			$.post(reassignForm.attr('action'), reassignForm.serialize(), function(data, status, jqXHR) {
				if (data.success) {
					location.reload();
				} else {
					reassignForm.find('.bcz-status-msg').text(data.message);
					reassignForm.find('.alert').removeClass('hide');
					reassignBtn.text('Reassign').removeClass('disabled').attr('disabled', false);
				}
			});
		});

		// Create button click handlers for addind a new note / task / doc
		$('.panel-group .panel-heading a > button').on('click', function(e) {
			$($(this).data('modal-id')).modal('show');
		});
	}

	/* Create deal page */
	if ($('body.create-deal').length) {
		$('form button[type=submit]').on('click', function() {
			var dealElem = $('input[name=deal_amount]');
			var dealAmount = $.trim(dealElem.val());
			// $('body.create-deal form').parsley('validate');
			if (dealAmount && !$.isNumeric(dealAmount)) {
				dealElem.addClass('parsley-error');
				if (dealElem.next('ul').find('li').length)	dealElem.next('ul').find('li').html('Opportunities amount should be numeric.');
				else if (dealElem.next('p').length) dealElem.next('p').text('Opportunities amount should be numeric.');
				else dealElem.parent().append('<p class="m-t-mini">Opportunities amount should be numeric.</p>');
				return false;
			}
		});
	}

	/* Settings page */
	if($('body.settings').length) {
		
		
		
				$('.btn-group label.active').click(function()
			{
				var addUserForm = $(this).closest('form');
			addUserForm.ajaxForm(function(data, status, jqXHR) {
				if (data.message && !data.success) {
					addUserForm.find('.bcz-status-msg').text(data.message);
					addUserForm.find('.alert').removeClass('hide');
				} else if (data) {
					$('#users_container').html(data);

					// Reconstruct the user table
					$('table.bcz-data-table').dataTable({
						"bProcessing": true,
						"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
						"sPaginationType": "full_numbers",
				    "bServerSide": true,
				    "sAjaxSource": $('.btn-group label.active').data('source'),
    				"fnDrawCallback": bczUtils.dataTableCallback
					});

					addUserForm.resetForm();
					$('#add_user_modal').modal('hide');

					// Update user count value
					var userCount = parseInt($('.bcz-btn-add-user').parents('.panel').data('org-user-count'));
					$('.bcz-btn-add-user').parents('.panel').data('org-user-count', (userCount + 1));
				}
				addUserBtn.text('Save').removeClass('disabled');
      });
		
						
						
						});
						
						$('.btn-group label.btn-danger').click(function()
			{
			$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('.btn-group label.active').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				});
			
						});



		// Users organization chart
		if ($('#users_org_chart').length) $('#users_org_chart_source').orgChart({container: $('#users_org_chart')});

		// Users spacetree view
		if ($('#spacetree').length) $('#spacetree').spacetree('#tree').hide();

		// Users section actions handler
		$('.bcz-btn-add-user').on('click', function() {
			//if ($(this).parents('.panel').data('org-user-count') < 3) {
				$('#add_user_modal').modal('show');
			//} else {
			//	$('#upgrade_message_modal').modal('show');
			//}

			//return false;
		});

		// Designation selection handler
		$('#add_user_modal select[name=user_designation]').on('change', function() {
			var userDesignation = $(this).val();
			var designationNos = {Admin: 1, Manager: 2, Executive: 3};
			$("#add_user_modal select[name=report_to_id] option").addClass('hide');

			$("#add_user_modal select[name=report_to_id] option").each(function() {
				currRole = $(this).data('role');
				if (currRole == undefined || (designationNos[currRole] < designationNos[userDesignation])) {
					$(this).removeClass('hide');
				}
			});

			$("#add_user_modal select[name=report_to_id]").prop('selectedIndex', 0);
			$("#add_user_modal select.select2-option").select2();
		});

		$('#add_user').on('click', function() {
			var addUserBtn = $(this);
			var addUserForm = $(this).closest('form');
			addUserForm.find('.alert').addClass('hide');

			if (addUserForm.parsley('validate')) addUserBtn.text('Saving...').addClass('disabled');
			addUserForm.ajaxForm(function(data, status, jqXHR) {
				if (data.message && !data.success) {
					addUserForm.find('.bcz-status-msg').text(data.message);
					addUserForm.find('.alert').removeClass('hide');
				} else if (data) {
					$('#users_container').html(data);

				// Reconstruct the user table
				//alert($('.bcz-data-table').data('source'));
				$('#users_container .table1 .bcz-data-table').dataTable({
					"bProcessing": true,
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('#users_container .table1 .bcz-data-table').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				});


					addUserForm.resetForm();
					$('#add_user_modal').modal('hide');

					// Update user count value
					var userCount = parseInt($('.bcz-btn-add-user').parents('.panel').data('org-user-count'));
					$('.bcz-btn-add-user').parents('.panel').data('org-user-count', (userCount + 1));
				}
				addUserBtn.text('Save').removeClass('disabled');
      });
		});
		$('#users_container').on('click', '.bcz-row-actions a', function(e) {
		
			if ($(this).data('bczajax-modal')) {
			  	$.get($(this).data('href'), function(data) {
						$('#edit_user_modal').html(data).modal('show');
			    	$(".select2-option").select2();
					
			  	});

			}
			else if ($(this).data('action')=='delete') {
				
				$('.modal-dialog p').html('Do you want to delete the user "'+$(this).data('username')+'" ?');
				var userId = $(this).parents('tr').data('rowid');
				if (!userId) userId = $(this).data('id');
				$($(this).attr('href')+' input[name=user_id]').val(userId);
				$($(this).attr('href')).modal('show');
				
			}
			
			
			else if ($(this).data('action')=='delete1') {
				
			$('.modal-dialog p').html('Do you want to delete the user "'+$(this).data('username')+'" ?');
				var userId = $(this).parents('tr').data('rowid');
				if (!userId) userId = $(this).data('id');
				$($(this).attr('href')+' input[name=user_id]').val(userId);
				$($(this).attr('href')).modal('show');
			}
			else if ($(this).data('action')=='deactivate') {
				
			$('.modal-dialog p').html('Do you want to Deactivate the user "'+$(this).data('username')+'" ?');
				var userId = $(this).parents('tr').data('rowid');
				if (!userId) userId = $(this).data('id');
				$($(this).attr('href')+' input[name=user_id]').val(userId);
				$($(this).attr('href')).modal('show');
			}
			else if ($(this).data('action')=='activate') {
				
			$('.modal-dialog p').html('Do you want to Activate the user "'+$(this).data('username')+'" ?');
				var userId = $(this).parents('tr').data('rowid');
				if (!userId) userId = $(this).data('id');
				$($(this).attr('href')+' input[name=user_id]').val(userId);
				$($(this).attr('href')).modal('show');
			}
			else if ($(this).data('action')=='reset') {
				
			$('.modal-dialog p').html("Do you want to reset this user's password?");
				var userId = $(this).parents('tr').data('rowid');
				if (!userId) userId = $(this).data('id');
				$($(this).attr('href')+' input[name=user_id]').val(userId);
				$($(this).attr('href')).modal('show');
			}
			
			else {
			
				var userId = $(this).parents('tr').data('rowid');
				if (!userId) userId = $(this).data('id');
				$($(this).attr('href')+' input[name=user_id]').val(userId);
				$($(this).attr('href')).modal('show');
				
			}
			return false;			
		});
		
		
		$('#users_container1').on('click', '.bcz-row-actions a', function(e) {
	
			if ($(this).data('bczajax-modal')) {
				
			  	$.get($(this).data('href'), function(data) {
						$('#edit_user_modal').html(data).modal('show');
			    	$(".select2-option").select2();
					
			  	});

			}
			else if ($(this).data('action')=='delete') {
				
				$('.modal-dialog p').html('Do you want to delete the user "'+$(this).data('username')+'" ?');
				var userId = $(this).parents('tr').data('rowid');
				if (!userId) userId = $(this).data('id');
				$($(this).attr('href')+' input[name=user_id]').val(userId);
				$($(this).attr('href')).modal('show');
				
			}
			
			
			
			
			else if ($(this).data('action')=='delete1') {
				
			$('.modal-dialog p').html('Do you want to delete the user "'+$(this).data('username')+'" ?');
			
				var userId = $(this).parents('tr').data('rowid');
				if (!userId) userId = $(this).data('id');
				$($(this).attr('href')+' input[name=user_id]').val(userId);
				$($(this).attr('href')).modal('show');
			}
			else if ($(this).data('action')=='deactivate') {
				
			$('.modal-dialog p').html('Do you want to Deactivate the use "'+$(this).data('username')+'" ?');
				var userId = $(this).parents('tr').data('rowid');
				if (!userId) userId = $(this).data('id');
				$($(this).attr('href')+' input[name=user_id]').val(userId);
				$($(this).attr('href')).modal('show');
			}
			else if ($(this).data('action')=='activate') {
				
			$('.modal-dialog p').html('Do you want to Activate the user "'+$(this).data('username')+'" ?');
				var userId = $(this).parents('tr').data('rowid');
				if (!userId) userId = $(this).data('id');
				$($(this).attr('href')+' input[name=user_id]').val(userId);
				$($(this).attr('href')).modal('show');
			}
			else if ($(this).data('action')=='reset') {
				
			$('.modal-dialog p').html("Do you want to reset this user's password?");
				var userId = $(this).parents('tr').data('rowid');
				if (!userId) userId = $(this).data('id');
				$($(this).attr('href')+' input[name=user_id]').val(userId);
				$($(this).attr('href')).modal('show');
			}
			
			else {
			
				var userId = $(this).parents('tr').data('rowid');
				if (!userId) userId = $(this).data('id');
				$($(this).attr('href')+' input[name=user_id]').val(userId);
				$($(this).attr('href')).modal('show');
				
			}

			return false;			
		});
		
		
	
		
		
		$('#edit_user_modal').on('click', '#update_user', function() {
			var updateUserForm = $(this).closest('form');
			updateUserForm.find('.alert').addClass('hide');

			updateUserForm.ajaxForm(function(data, status, jqXHR) {
				$('#users_container').html(data);

				// Reconstruct the user table
				$('#users_container .table1 .bcz-data-table1').dataTable({
					"bProcessing": true,
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('#users_container .table1 .bcz-data-table1').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				});
				

				$('#edit_user_modal').modal('hide');

				// Update user count value
				var userCount = parseInt($('.bcz-btn-add-user').parents('.panel').data('org-user-count'));
				$('.bcz-btn-add-user').parents('.panel').data('org-user-count', (userCount - 1));
      });
		});
		
		
		$('#edit_user_modal').on('click', '#update_user1', function() {
		 
			var updateUserForm = $(this).closest('form');
			updateUserForm.find('.alert').addClass('hide');

			updateUserForm.ajaxForm(function(data, status, jqXHR) {
				$('#users_container1').html(data);

				// Reconstruct the user table
				$('#users_container1 .table1 .bcz-data-table').dataTable({
					"bProcessing": true,
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('#users_container1 .table1 .bcz-data-table').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				});
				

				$('#edit_user_modal').modal('hide');

				// Update user count value
				var userCount = parseInt($('.bcz-btn-add-user').parents('.panel').data('org-user-count'));
				$('.bcz-btn-add-user').parents('.panel').data('org-user-count', (userCount - 1));
      });
		});
		
		
		$('#delete_user').on('click', function() {
			var deleteUserForm = $(this).closest('form');
			deleteUserForm.find('.alert').addClass('hide');

			deleteUserForm.ajaxForm(function(data, status, jqXHR) {
				$('#users_container').html(data);

				// Reconstruct the user table
				$('#users_container .table1 .bcz-data-table').dataTable({
					"bProcessing": true,
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('#users_container .table1 .bcz-data-table').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				});

				$('#delete_user_confirmation_modal').modal('hide');

				// Update user count value
				var userCount = parseInt($('.bcz-btn-add-user').parents('.panel').data('org-user-count'));
				$('.bcz-btn-add-user').parents('.panel').data('org-user-count', (userCount - 1));
      });
		});
		
		$('#delete_user1').on('click', function() {
			var deleteUserForm = $(this).closest('form');
			deleteUserForm.find('.alert').addClass('hide');

			deleteUserForm.ajaxForm(function(data, status, jqXHR) {
				$('#users_container1').html(data);

				// Reconstruct the user table
				$('#users_container1 .table1 .bcz-data-table').dataTable({
					"bProcessing": true,
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('#users_container1 .table1 .bcz-data-table').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				});

				$('#delete_user_confirmation_modal1').modal('hide');

				// Update user count value
				var userCount = parseInt($('.bcz-btn-add-user').parents('.panel').data('org-user-count'));
				$('.bcz-btn-add-user').parents('.panel').data('org-user-count', (userCount - 1));
      });
		});
		
		
		$('#delete_modal').on('click', function() {
			var deleteUserForm = $(this).closest('form');
			deleteUserForm.find('.alert').addClass('hide');

			deleteUserForm.ajaxForm(function(data, status, jqXHR) {
				$('#users_container').html(data);

				// Reconstruct the user table
				$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				});

				$('#delete_modal').modal('hide');

				// Update user count value
				var userCount = parseInt($('.bcz-btn-add-user').parents('.panel').data('org-user-count'));
				$('.bcz-btn-add-user').parents('.panel').data('org-user-count', (userCount - 1));
      });
		});
		
		
		$('#reset_user').on('click', function() {
			var resetUserForm = $(this).closest('form');
			resetUserForm.find('.alert').addClass('hide');

			resetUserForm.ajaxForm(function(data, status, jqXHR) {
		
					$('#users_container').html(data);

				// Reconstruct the user table
				$('#users_container .table1 .bcz-data-table').dataTable({
					"bProcessing": true,
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('#users_container .table1 .bcz-data-table').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				});
				$('#reset_user_confirmation_modal').modal('hide');
      });
		});
		
		
		 
		
		
		
		
		
		
		
		$('#deactivate_user').on('click', function() {
			
			var deactivateUserForm = $(this).closest('form');
			deactivateUserForm.find('.alert').addClass('hide');

			deactivateUserForm.ajaxForm(function(data, status, jqXHR) {
				$('#users_container').html(data);

				// Reconstruct the user table
				$('#users_container .table1 .bcz-data-table1').dataTable({
					"bProcessing": true,
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('#users_container .table1 .bcz-data-table1').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				});
				

				$('#deactivate_user_confirmation_modal').modal('hide');

				// Update user count value
				var userCount = parseInt($('.bcz-btn-add-user').parents('.panel').data('org-user-count'));
				$('.bcz-btn-add-user').parents('.panel').data('org-user-count', (userCount - 1));
      });
	     });	
		
		
		
		
		$('#activate_user').on('click', function() {
			
			var activateUserForm = $(this).closest('form');
			activateUserForm.find('.alert').addClass('hide');

			activateUserForm.ajaxForm(function(data, status, jqXHR) {
				$('#users_container1').html(data);

				// Reconstruct the user table
				//alert($('.bcz-data-table').data('source'));
				$('#users_container1 .table1 .bcz-data-table').dataTable({
					"bProcessing": true,
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('#users_container1 .table1 .bcz-data-table').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				});

				$('#activate_user_confirmation_modal').modal('hide');

				// Update user count value
				var userCount = parseInt($('.bcz-btn-add-user').parents('.panel').data('org-user-count'));
				$('.bcz-btn-add-user').parents('.panel').data('org-user-count', (userCount - 1));
      });
	     });	
		/*
		$('a').on('click', function() {
			
			var pageNum = $(this).attr("href");
			if(pageNum=="#collapse32")
			{
				$('#users_container .table .bcz-data-table1').dataTable({
					"bProcessing": true,
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('#users_container .table .bcz-data-table1').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				
				});
				
				var userCount = parseInt($('.bcz-btn-add-user').parents('.panel').data('org-user-count'));
				$('#users_container .bcz-btn-add-user').parents('.panel').data('org-user-count', (userCount - 1));
			}
			else if(pageNum=="#collapse33")
			{
				$('#users_container1 .table .bcz-data-table').dataTable({
					"bProcessing": true,
					"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('#users_container1 .table .bcz-data-table').data('source'),
  				"fnDrawCallback": bczUtils.dataTableCallback
				});
				
				var userCount = parseInt($('.bcz-btn-add-user').parents('.panel').data('org-user-count'));
				$('#users_container1 .bcz-btn-add-user').parents('.panel').data('org-user-count', (userCount - 1));
			}
		});*/
		

		// Organization data submission handler
		$('.panel-collapse').on('click', 'button#save_org', function() {
			var saveOrgBtn = $(this);
			var set_currency = $("#set_currency").val();
			var saveOrgForm = $(this).closest('form');

			saveOrgForm.ajaxForm(function(data) {
				saveOrgForm.parents('.panel-collapse .panel-body').html(data);
				//$("header#header .navbar-logo-wrapper img").attr('src', $('#collapseTwo .media img').attr('src'));
				$(".select2-option").select2();
				var set_currency=$('#set_currency').val();
				
				$.fn.BootstrapFileInput();
				if(set_currency!="")
				{
				$('#currency_div').html('<input type="text" name="set_currency" data-required="true" class="form-control" value="'+set_currency+'" readonly="readonly">');
				}
      });
		});

		// Admin setting block actions handler
		

		// Setting form submission handler
	
		// Setting form cancel button handler
		$('button.btn-cancel-setting').on('click', function() {
			$(".bcz-block.active-block .list-group-item.active-setting-item").removeClass('active-setting-item');
			$(".bcz-block.active-block").removeClass('active-block');
		});

		// Import form submission handler
		$('#bcz_import').on('click', function() {
			$('.import-status-msg').addClass('hide');

			// Validation checks
			var impTable = $('select[name=import_table]').val();
			var impFile = $('input[name=import_file]').val();
			if (!impTable || !impFile) return false;

			var importBtn = $(this);
			var importForm = $(this).closest('form');

			importForm.ajaxForm(function(data, status, jqXHR) {
				if (data.success) {
					$('.import-status-msg').text(data.message).addClass('alert-success');
					$("select[name=import_table]").prop('selectedIndex', 0);
					$("select[name=import_table]").select2();
					$('select[name=import_table]').val(0);
					$('.file-input-name').text('');
				} else {
					$('.import-status-msg').text(data.message).addClass('alert-danger');
				}
				$('.import-status-msg').removeClass('hide');
				importForm.resetForm();
      });
		});
	}



/* Settings page */
	if($('.advancedsettings').length) {
		
		


		// Admin setting block actions handler
		$('.bcz-block').on('click', 'a', function() {
			var blockElem = $(this).parents('.bcz-block');
			var modalElemId = $(this).attr('href');
			$(modalElemId + ' .setting-type').html(blockElem.find('header strong').html());
			$(modalElemId + ' input[name=setting_type]').val(blockElem.data('type'));
			blockElem.addClass('active-block');
			$(modalElemId + ' input[name=probability]').addClass('hide');
			if (blockElem.data('type') == 'stage')	$(modalElemId + ' input[name=probability]').removeClass('hide');

			var elemData = $(this).data();
			var settingElem = $(this).parents('.list-group-item');
			$(modalElemId + ' input[name=setting_val]').val('');
			
			if (elemData.action == 'edit') {
				
				var listElem = $(this).parents('.list-group-item');
				$(modalElemId + ' input[name=setting_id]').val(listElem.data('id'));
				if(listElem.data('id')=='24')
				{
					$(modalElemId + ' input[name=probability]').val('100');
					$(modalElemId + ' input[name=probability]').attr('readonly', true);
					
				}
				else if(listElem.data('id')=='25')
				{
					$(modalElemId + ' input[name=probability]').val('0');
					$(modalElemId + ' input[name=probability]').attr('readonly', true);
					
				}


				else
				{
					$(modalElemId + ' input[name=probability]').attr('readonly', false);
				}
				
				$(modalElemId + ' input[name=setting_val]').val(listElem.find('.bcz-data').text());
				if (blockElem.data('type') == 'stage') $(modalElemId + ' input[name=probability]').val(listElem.data('probability'));

				listElem.addClass('active-setting-item');

			}
			 else if (elemData.action == 'delete') {
				var listElem = $(this).parents('.list-group-item');
				$(modalElemId + ' input[name=setting_id]').val(listElem.data('id'));
				$(modalElemId + ' input[name=setting_val]').val(listElem.find('.bcz-data').text());
				listElem.addClass('active-setting-item');

			}
			
			
			
			
			
			
			else if (elemData.action == 'moveup') {
				var currSettingInfo = settingElem.data();

				// Get prev setting data
				blockElem.find('.list-group-item').each(function() {
					var listItemData = $(this).data();
					if (listItemData.order == (currSettingInfo.order - 1)) {
						prevSettingInfo = listItemData;
					}
				});

				$.get(appBaseUrl + 'advancedsettings/moveSetting', {move: 'up', column: blockElem.data('type'), id: currSettingInfo.id, order: currSettingInfo.order, otherId: prevSettingInfo.id, otherOrder: prevSettingInfo.order}, function(data, status, jqXHR) {
					if (data.success) {
						var currElem = blockElem.find('.list-group-item:eq('+settingElem.index()+')');
						var prevElem = blockElem.find('.list-group-item:eq('+(settingElem.index() - 1)+')');
						currElem.data('order', (currElem.data('order') - 1));
						prevElem.data('order', (prevElem.data('order') + 1));
						prevElem.before(currElem.detach());
					}
				});

				$(".bcz-block.active-block .list-group-item.active-setting-item").removeClass('active-setting-item');
				$(".bcz-block.active-block").removeClass('active-block');

			} else if (elemData.action == 'movedown') {
				var currSettingInfo = settingElem.data();

				// Get next setting data
				blockElem.find('.list-group-item').each(function() {
					var listItemData = $(this).data();
					if (listItemData.order == (currSettingInfo.order + 1)) {
						nextSettingInfo = listItemData;
					}
				});

				$.get(appBaseUrl + 'advancedsettings/moveSetting', {type: 'down', column: blockElem.data('type'), id: currSettingInfo.id, order: currSettingInfo.order, otherId: nextSettingInfo.id, otherOrder: nextSettingInfo.order}, function(data, status, jqXHR) {
					if (data.success) {
						var currElem = blockElem.find('.list-group-item:eq('+settingElem.index()+')');
						var nextElem = blockElem.find('.list-group-item:eq('+(settingElem.index() + 1)+')');
						currElem.data('order', (currElem.data('order') + 1));
						nextElem.data('order', (nextElem.data('order') - 1));
						nextElem.after(currElem.detach());
					}
				});

				$(".bcz-block.active-block .list-group-item.active-setting-item").removeClass('active-setting-item');
				$(".bcz-block.active-block").removeClass('active-block');
			}
		});

		// Setting form submission handler
		$('button.btn-submit-setting').on('click', function() {
			var saveSettingBtn = $(this);
			var saveSettingForm = $(this).closest('form');
			saveSettingForm.find('.alert').addClass('hide');

			saveSettingForm.ajaxForm(function(data, status, jqXHR) {
				var activeBlockElem = $(".bcz-block.active-block");
				var operationDone = false;
				if (data.action == 'edit') {
					var activeListElem = activeBlockElem.find('.list-group-item.active-setting-item');
					activeListElem.find('.bcz-data').text(data.setting);
					if (data.setting_type == 'stage') activeListElem.data('probability', data.probability);
					$('#edit_setting_modal').modal('hide');
					operationDone = true;

				} else if (data.action == 'not_delete') {
					var activeListElem = activeBlockElem.find('.list-group-item.active-setting-item');
					activeListElem.remove();
					$('#not_delete_setting_confirmation_modal').modal('hide');
					operationDone = true;

				}
				 else if (data.action == 'delete') {
					var activeListElem = activeBlockElem.find('.list-group-item.active-setting-item');
					activeListElem.remove();
					$('#delete_setting_confirmation_modal').modal('hide');
					operationDone = true;

				

				}
				 else if (data.message && !data.success) {
					saveSettingForm.find('.bcz-status-msg').text(data.message);
					saveSettingForm.find('.alert').removeClass('hide');

				} else if (data) {
					activeBlockElem.find('.list-group').append(data);
					$('#add_setting_modal').modal('hide');
					operationDone = true;

				} else {
					saveSettingForm.find('.bcz-status-msg').text(data.message);
					saveSettingForm.find('.alert').removeClass('hide');
				}

				if (operationDone) {
					$(".bcz-block.active-block .list-group-item.active-setting-item").removeClass('active-setting-item');
					$(".bcz-block.active-block").removeClass('active-block');
				}
	        });
		});

		// Setting form cancel button handler
		$('button.btn-cancel-setting').on('click', function() {
			$(".bcz-block.active-block .list-group-item.active-setting-item").removeClass('active-setting-item');
			$(".bcz-block.active-block").removeClass('active-block');
		});

		// Import form submission handler
		$('#bcz_import').on('click', function() {
			$('.import-status-msg').addClass('hide');

			// Validation checks
			var impTable = $('select[name=import_table]').val();
			var impFile = $('input[name=import_file]').val();
			if (!impTable || !impFile) return false;

			var importBtn = $(this);
			var importForm = $(this).closest('form');

			importForm.ajaxForm(function(data, status, jqXHR) {
				if (data.success) {
					$('.import-status-msg').text(data.message).addClass('alert-success');
					$("select[name=import_table]").prop('selectedIndex', 0);
					$("select[name=import_table]").select2();
					$('select[name=import_table]').val(0);
					$('.file-input-name').text('');
				} else {
					$('.import-status-msg').text(data.message).addClass('alert-danger');
				}
				$('.import-status-msg').removeClass('hide');
				importForm.resetForm();
      });
		});
	}
	
	
	
	/* Quote creation page */
	if($('body.create-quote').length || $('body.edit-quote').length || $('body.edit-order').length) {
		// Add quote item HTML block
		$('#add_quote_item').on('click', function() {			
			$(this).closest('.row').before($('#bcz_quote_dummy_item').html());
			var addedElem = $(this).closest('.row').prev().prev();
			addedElem.find('.item-index').text(addedElem.index() - (addedElem.index() / 2) + 0.5);
			addedElem.find(".bcz-select2-option").select2();
			if ($('.row.bcz-quote-item').length >= 13)	$('#add_quote_item').hide();
			
			return false;
		});

		// Remove quote item HTML block		
		$('#collapseTwo').on('click', 'a.bcz-btn-delete-quote-item', function() {
			var currQuoteItem = $(this).parents('.row.bcz-quote-item');
			currQuoteItem.prev().remove();
			currQuoteItem.remove();
			if ($('.row.bcz-quote-item').length < 13)	$('#add_quote_item').show();
			return false;
		});

		// Copy billing address to shipping address
		$('#copy_billing_addr').on('click', function() {
			$('.billing-address-fields .form-group').each(function() {
				var inputField = $(this).find('input');
				var inputFieldVal = inputField.val();
				var inputFieldName = inputField.attr('name');
				if (inputFieldName) {
					// var inputFieldName = inputField.attr('name');
					$('.shipping-address-fields .form-group input[name='+inputFieldName.replace('bill', 'ship')+']').val(inputFieldVal);
				}

				if ($(this).find('select').val()) {
					$(".shipping-address-fields .select2-option").select2("val", $(this).find('select').val());
				}
			});
		});

		// Currency selection handling flow
		/*$('input.bcz-currency').on('change', function() {
			$(".select_currency_msg").addClass('hide');
			// Reset all items info, TODO: better to show a confirmation modal here
			$('#collapseTwo select.bcz-quote-product').each(function() {
				if ($(this).val()) {
					$(this).val('').trigger('change');
					$('input[name=frieght], input[name=install], input[name=total]').val('');
				}
			});
		});*/
		
		$('select.bcz-currency').on('change', function() {
			$('.bcz-quote-product1').attr('data-block', $(this).val());
			
			$(".select_currency_msg").addClass('hide');
			// Reset all items info, TODO: better to show a confirmation modal here
			$('#collapseTwo select.bcz-quote-product').each(function() {
				if ($(this).val()) {
					$(this).val('').trigger('change');
					$('input[name=frieght], input[name=install], input[name=total]').val('');
				}
			});
		});



		/*// Quote item/product selection handling flow
		$('#collapseTwo').on('change', '.bcz-quote-product', function(e) {
console.log('select change...');
			$(".select_currency_msg").addClass('hide');
			if ($(this).val() && !$('input.bcz-currency').val()) {
				$(this).val('');
				$(this).select2("val", "");
				$(".select_currency_msg").removeClass('hide');
				return false;
			}

			var currItemRow = $(this).parents('.row.bcz-quote-item');

			// Clear previous product data
			var currItemAmount = quotesUtility.toPlainNumber(currItemRow.find('input.bcz-quote-amount').val());
			currItemRow.find('input.bcz-quote-item-field').val('');
			currItemRow.find('select.bcz-quote-vat').select2("val", "0");
			currItemRow.next().find('textarea.bcz-quote-item-field').val('');

			// Update total value
			var total = quotesUtility.toPlainNumber($('.bcz-quote-items-total').val());
			if (total) {
				total = parseFloat(total);
				total -= currItemAmount ? parseFloat(currItemAmount) : 0;
				$('.bcz-quote-items-total').val(quotesUtility.toLocalNotation(total));
			}

			// New product addition
			if ($(this).val() == 'skz_add_new_prod') {
				$('#create_product_modal').modal();
				return false;
			}

			// Set the selected product data
			if ($(this).val()) {
				var quoteCurrency = $('select[name='+($('body.edit-order').length ? 'so_currency' : 'quote_currency')+']').val();
				var prodData = $(this).find('option[value='+$(this).val()+']').data();
				var prodPrice = (quoteCurrency == 'USD') ? prodData.priceUsd : prodData.price;
				currItemRow.find('input.bcz-quote-qty').val(1);
				currItemRow.find('input.bcz-quote-price').val(prodPrice);
				currItemRow.find('input.bcz-quote-amount').val(prodPrice);

				// Update total quote value
				quotesUtility.updateQuoteTotal();
			}
		});*/
		
		
		// Quote item/product selection handling flow
		$('#collapseTwo').on('change', '.bcz-quote-product', function(e) {
console.log('select change...');
			$(".select_currency_msg").addClass('hide');
			if ($(this).val() && !$('select.bcz-currency').val()) {
				$(this).val('');
				$(this).select2("val", "");
				$(".select_currency_msg").removeClass('hide');
				return false;
			}

			var currItemRow = $(this).parents('.row.bcz-quote-item');

			// Clear previous product data
			var currItemAmount = quotesUtility.toPlainNumber(currItemRow.find('input.bcz-quote-amount').val());
			currItemRow.find('input.bcz-quote-item-field').val('');
			currItemRow.find('select.bcz-quote-vat').select2("val", "0");
			currItemRow.next().find('textarea.bcz-quote-item-field').val('');

			// Update total value
			var total = quotesUtility.toPlainNumber($('.bcz-quote-items-total').val());
			if (total) {
				total = parseFloat(total);
				total -= currItemAmount ? parseFloat(currItemAmount) : 0;
				$('.bcz-quote-items-total').val(quotesUtility.toLocalNotation(total));
			}

			// New product addition
			if ($(this).val() == 'skz_add_new_prod') {
				$('#create_product_modal').modal();
				return false;
			}

			// Set the selected product data
			if ($(this).val()) {
				var quoteCurrency = $('select[name='+($('body.edit-order').length ? 'so_currency' : 'quote_currency')+']').val();
				var prodData = $(this).find('option[value='+$(this).val()+']').data();
				var prodPrice = (quoteCurrency == 'USD') ? prodData.priceUsd : prodData.price;
				var id = $(this).children(":selected").attr("id");
				var base_currency=$('#base_currency').val();
				/*if(base_currency!=quoteCurrency)
				{
					alert('no no');
				}
				else
				{
					alert('Yes Yes');
				}
				alert(id);
			
				if(prodPrice=="")
				{
					alert('no');
					
				}
				else
				{
					alert('Yes');
					
				}*/
				
				currItemRow.find('input.bcz-quote-qty').val(1);
					prodPrice=Math.round(prodPrice);
				currItemRow.find('input.bcz-quote-price').val(prodPrice);
			
				currItemRow.find('input.bcz-quote-amount').val(prodPrice);

				// Update total quote value
				quotesUtility.updateQuoteTotal();
			}
		});

		// Quote item field selection handling flow
		$('#collapseTwo').on('change', 'input.bcz-quote-item-field, select.bcz-quote-vat', function() {
			var currItemRow = $(this).parents('.row.bcz-quote-item');
			
			// Discount field updates
			var currency = $('select[name='+($('body.edit-order').length ? 'so_currency' : 'quote_currency')+']').val();
			var price = currItemRow.find('input.bcz-quote-price').val();
			var qty1 = currItemRow.find('input.bcz-quote-qty').val();
		
			
			price1=Number(price)*Number(qty1);
			
			price = parseFloat(quotesUtility.toPlainNumber(price));
			if ($(this).hasClass('bcz-quote-discount')) {
				var discPer = quotesUtility.toPlainNumber($(this).val());
				
				discPer = (parseFloat(discPer) / price1) * 100;
				var tot=Number(discPer.toFixed(3))*Number(qty1);
				currItemRow.find('input.bcz-quote-discount1').val(tot);
			} else if ($(this).hasClass('bcz-quote-discount1')) {
				var discVal = quotesUtility.toPlainNumber($(this).val());
				discVal = (parseFloat(discVal) * Number(price)) / 100;
				currItemRow.find('input.bcz-quote-discount').val(quotesUtility.toLocalNotation(discVal));
			}

			// Get tax specific data
			var qty = quotesUtility.toPlainNumber(currItemRow.find('input.bcz-quote-qty').val());
			var discount = quotesUtility.toPlainNumber(currItemRow.find('input.bcz-quote-discount').val());
			discount =Number(discount)*Number(qty);
			var vat = quotesUtility.toPlainNumber(currItemRow.find('select.bcz-quote-vat').val());

			// Update item amounts
			qty = qty ? parseFloat(qty) : 0;
			var amount = (qty * price);
			if (amount && discount) amount -= parseFloat(discount);
			if (amount && vat) amount += ((amount * parseFloat(vat)) / 100);
			
			currItemRow.find('input.bcz-quote-amount').val(quotesUtility.toLocalNotation(amount));

			// Update total quote value
			quotesUtility.updateQuoteTotal();
		});

		// Frieght and Install field change handler
		$('input.bcz-quote-items-frieght, input.bcz-quote-items-install').on('change', function() {
			quotesUtility.updateQuoteTotal();	// Update total quote value
		});

		// Utility for quotes pages
		var quotesUtility = {
			updateQuoteTotal: function() {
				var total = 0, itemAmount = 0;
				$('#collapseTwo .row.bcz-quote-item input.bcz-quote-amount').each(function() {
					itemAmount = $(this).val();
					if (itemAmount) total += parseFloat(quotesUtility.toPlainNumber(itemAmount));
				});

				var frieght = $('input.bcz-quote-items-frieght').val();
				var install = $('input.bcz-quote-items-install').val();
				if (frieght) total += parseFloat(quotesUtility.toPlainNumber(frieght));
				if (install) total += parseFloat(quotesUtility.toPlainNumber(install));
				$('input.bcz-quote-items-total').val(quotesUtility.toLocalNotation(total));
			},
			toPlainNumber: function (val) {
				return val.toString().replace(",", "");
			},
			toLocalNotation: function (val) {
				return val.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
			}
		};

		// Add product modal submission
		$('#create_product_modal #add_product').on('click', function() {
			var formElem = $(this).closest('form');
			formElem.find('.alert').addClass('hide');

			formElem.ajaxForm(function(data, status, jqXHR) {
				if (status == 'success') {
					// Add this product to select options
					$('select.bcz-quote-product.select2-option').append(data.option);
					$('select.bcz-quote-product.select2-option').select2('val', data.product_id);
					$('select.bcz-quote-product.select2-option').trigger('change');

					// Reset the modal form and close modal
					formElem.resetForm();
					formElem.parent().modal('hide');
				}
      });
		});
	}

	/* Quote details page */
	if($('body.quote-details').length) {
		$('.wizard .actions').on('click', 'button', function(e) {
			var params = {};
			params.next = $(this).hasClass('next-btn');
			params.prev = !params.next;
			params.id = $(this).parent().data('id');
			var activeStage = $(this).parents('.wizard').find('ul.steps li.active');
			params.stage = params.next ? activeStage.next().data('stage') : activeStage.prev().data('stage');
			$.post(appBaseUrl+'quotes/changestage', params, function(data, status, jqXHR) {
				if (data.success) {
					$('.wizard .steps li').removeClass('active');
					$('.wizard .steps li span.badge').removeClass('badge-info');
					if (params.next) {
						activeStage.next().addClass('active').find('span.badge').addClass('badge-info');
					} else {
						activeStage.prev().addClass('active').find('span.badge').addClass('badge-info');
					}

					// Show or hide the stage change buttons
					$('.wizard .actions button').removeClass('hide');
					if (!$('.wizard .steps li.active').prev('li').length) $('.wizard .actions button.prev-btn').addClass('hide');
					if (!$('.wizard .steps li.active').next('li').length) $('.wizard .actions button.next-btn').addClass('hide');
				}
			});
		});		

		// Action button click handler
		$('.quote-actions a').on('click', function(e) {
			if ($(this).data('redirect-url')) {
				if ($(this).attr('target') == '_blank') {
					var pdfUrl = $(this).data('redirect-url');
					var pdfWin = window.open(pdfUrl, '_blank');
					pdfWin.focus();
				} else {
					location.href = $(this).data('redirect-url');
				}

			} else {
				$($(this).attr('href')).modal('show');
			}
		});

		// Generate SO form submission handler
		$('button#generate_so').on('click', function() {
			var generateBtn = $(this);
			var generateForm = $(this).closest('form');
			generateForm.find('.alert').addClass('hide');

			generateForm.ajaxForm(function(data, status, jqXHR) {
				if (data.success) {
					location.href = data.redirectUrl;
				} else {
					generateForm.find('.bcz-status-msg').text(data.message);
					generateForm.find('.alert').removeClass('hide');
					generateBtn.text('Generate').removeClass('disabled').attr('disabled', false);
				}
	        });
		});
	}

	// Documents scripts
	if ($('body.docs').length) {
		//$('#docs_tree').treeview();

		// List item directory clicks handler
		$(".list-item.directory").on('click', function() {
			$(this).next().toggleClass('hidden');
		});

		// Action button click handler
		$('.doc-actions a').on('click', function(e) {
			if ($(this).data('redirect-url')) {
				var pdfUrl = $(this).data('redirect-url');
				var pdfWin = window.open(pdfUrl, '_blank');
				pdfWin.focus();

			} else {
				$($(this).attr('href')).modal('show');
			}
		});

		// Create folder form submission handler
		$('button#create_folder').on('click', function() {
			var folderBtn = $(this);
			var folderForm = $(this).closest('form');
			folderForm.find('.alert').addClass('hide');

			folderForm.ajaxForm(function(data, status, jqXHR) {
				if (data.success) {
					location.reload();
				} else {
					folderForm.find('.bcz-status-msg').text(data.message);
					folderForm.find('.alert').removeClass('hide');
					folderBtn.removeClass('disabled').attr('disabled', false);
				}
	        });
		});

		// Upload document form submission handler
		$('button#upload_doc').on('click', function() {
			var uploadDocBtn = $(this);
			var uploadDocForm = $(this).closest('form');
			uploadDocForm.find('.alert').addClass('hide');

			uploadDocForm.ajaxForm(function(data, status, jqXHR) {
				if (data.success) {
					location.reload();
				} else {
					uploadDocForm.find('.bcz-status-msg').text(data.message);
					uploadDocForm.find('.alert').removeClass('hide');
					uploadDocBtn.removeClass('disabled').attr('disabled', false);
				}
	        });
		});

		// Delete document handler
		$('.bcz-delete-doc-btn').on('click', function() {
			if (!$(this).data('bcz-confirm')) return;
			
			

			$.get($(this).data('url'), {}, function(data, status, jqXHR) {
				if (data.success) {
					location.reload();
				}
			});
		});
	}

	/* Order details page */
	if($('body.order-details').length) {
		$('.wizard .actions').on('click', 'button', function(e) {
			var params = {};
			params.next = $(this).hasClass('next-btn');
			params.prev = !params.next;
			params.id = $(this).parent().data('id');
			var activeStage = $(this).parents('.wizard').find('ul.steps li.active');
			params.stage = params.next ? activeStage.next().data('stage') : activeStage.prev().data('stage');
			$.post(appBaseUrl+'orders/changestage', params, function(data, status, jqXHR) {
				if (data.success) {
					$('.wizard .steps li').removeClass('active');
					$('.wizard .steps li span.badge').removeClass('badge-info');
					if (params.next) {
						activeStage.next().addClass('active').find('span.badge').addClass('badge-info');
					} else {
						activeStage.prev().addClass('active').find('span.badge').addClass('badge-info');
					}

					// Show or hide the stage change buttons
					$('.wizard .actions button').removeClass('hide');
					if (!$('.wizard .steps li.active').prev('li').length) $('.wizard .actions button.prev-btn').addClass('hide');
					if (!$('.wizard .steps li.active').next('li').length) $('.wizard .actions button.next-btn').addClass('hide');
				}
			});
		});

		// Action button click handler
		$('.order-actions a').on('click', function(e) {
			if ($(this).data('redirect-url')) {
				if ($(this).attr('target') == '_blank') {
					var pdfUrl = $(this).data('redirect-url');
					var pdfWin = window.open(pdfUrl, '_blank');
					pdfWin.focus();
				} else {
					location.href = $(this).data('redirect-url');
				}

			} else {
				$($(this).attr('href')).modal('show');
			}
		});
	}


	/* Company creation page */
	if($('body.create-company').length || $('body.edit-company').length) {
		// Copy billing address to shipping address
		$('#copy_billing_addr').on('click', function() {
			//$(this).find("input[type=radio]").attr('checked', 'checked');
			$('.billing-address-fields .form-group').each(function() {
				var inputField = $(this).find('input');
				var inputFieldVal = inputField.val();
				var inputFieldName = inputField.attr('name');
				if (inputFieldName) {
					// var inputFieldName = inputField.attr('name');
					$('.shipping-address-fields .form-group input[name='+inputFieldName.replace('bill', 'ship')+']').val(inputFieldVal);
				}

				if ($(this).find('select').val()) {
					$(".shipping-address-fields .select2-option").select2("val", $(this).find('select').val());
				}
			});
		});
	}

	/* Case / Ticket creation page */
	if($('body.create-case').length) {
		$('select[name=company_id]').on('change', function() {
			// Clear contacts and products
			//$('select[name=no_product_id]').removeClass('hide');
			//$('select[name=case_product_id]').addClass('hide');
			$('select[name=contact_id] option[value!=""]').remove();
			$("select[name=contact_id]").select2();

			if (!$(this).val()) return;

			$.get(appBaseUrl + 'cases/getCompanyContactsJSON', {company_id: $(this).val()}, function(data, status, jqXHR) {
				if (data.length) {
					// Update contacts list
					for (var i = data.length - 1; i >= 0; i--) {
						$('select[name=contact_id]').append('<option value="'+data[i].contact_id+'">'+data[i].contact_name+'</option>');
					};
					$("select[name=contact_id]").select2();

					// Toggle products list
					//$('select[name=no_product_id]').addClass('hide');
					//$('select[name=case_product_id]').removeClass('hide');
				}
			});
		});
	}
	
	
	if($('body.edit-case').length) {
		$('select[name=company_id]').on('change', function() {
			// Clear contacts and products
			//$('select[name=no_product_id]').removeClass('hide');
			//$('select[name=case_product_id]').addClass('hide');
			$('select[name=contact_id] option[value!=""]').remove();
			$("select[name=contact_id]").select2();

			if (!$(this).val()) return;

			$.get(appBaseUrl + 'cases/getCompanyContactsJSON', {company_id: $(this).val()}, function(data, status, jqXHR) {
				if (data.length) {
					// Update contacts list
					for (var i = data.length - 1; i >= 0; i--) {
						$('select[name=contact_id]').append('<option value="'+data[i].contact_id+'">'+data[i].contact_name+'</option>');
					};
					$("select[name=contact_id]").select2();

					// Toggle products list
					//$('select[name=no_product_id]').addClass('hide');
					//$('select[name=case_product_id]').removeClass('hide');
				}
			});
		});
	}
	
	
	/* Deal / Ticket creation page */
	if($('body.create-deal').length) {
					
		
			// Add product modal submission
			
		$('#create_account_deal_modal #add_account').on('click', function() {
			var formElem = $(this).closest('form');
			formElem.find('.alert').addClass('hide');
		
			
			formElem.ajaxForm(function(data, status, jqXHR) {
			
			
		
				if (status == 'success') {
					
				
						$('#opp_company_name').val(data.opp_company_name);
							$('#deal_company_id').val(data.deal_company_id);
					
				$('#con_company_name').val(data.opp_company_name);
							$('#con_company_id').val(data.deal_company_id);
					// Reset the modal form and close modal
					formElem.resetForm();
					formElem.parent().modal('hide');
				}
				
			
      });
		
		});
		
		
		
		
		
			$('#create_contact_deal_modal #add_contact_deal_modal').on('click', function() {
			
			var formElem = $(this).closest('form');
			formElem.find('.alert').addClass('hide');

			formElem.ajaxForm(function(data, status, jqXHR) {
				if (status == 'success') {
					
					
					$('#opp_contact_name').val(data.names);
					$('#deal_contact_id').val(data.contactId);
					
				
					// Reset the modal form and close modal
					formElem.resetForm();
					formElem.parent().modal('hide');
				}
				
			
      });
		
		});
		
		
		
		$('select[name=deal_company_id]').on('change', function() {
			// Clear contacts and products
			//$('select[name=no_product_id]').removeClass('hide');
			//$('select[name=case_product_id]').addClass('hide');
			$('select[name=deal_contact_id] option[value!=""]').remove();
			$("select[name=deal_contact_id]").select2();

			if (!$(this).val()) return;

			$.get(appBaseUrl + 'cases/getCompanyContactsJSON', {company_id: $(this).val()}, function(data, status, jqXHR) {
				if (data.length) {
					// Update contacts list
					for (var i = data.length - 1; i >= 0; i--) {
						$('select[name=deal_contact_id]').append('<option value="'+data[i].contact_id+'">'+data[i].contact_name+'</option>');
					};
					$("select[name=deal_contact_id]").select2();

					// Toggle products list
					//$('select[name=no_product_id]').addClass('hide');
					//$('select[name=case_product_id]').removeClass('hide');
				}
			});
		});
	}

	/* Dashboard  page */
	if($('body.dashboard').length) {
		// Task item click handler
		$('#your_tasks li.list-group-item a').on('click', function(e) {
			location.href = $(this).attr('href');
		});










/*		// Sales pipeline chart
		$.get(appBaseUrl+'dashboard/getpipelineinfo', function(stageDeals, status, jqXHR) {
			if (stageDeals.length) {
		    $("#sales_pipeline").igFunnelChart({
		        width: "100%",
		        height: "250px",
		        leftMargin: 20,
		        dataSource: stageDeals,
//		        dataSourceUrl: appBaseUrl+'dashboard/getpipelineinfo',
		        outlineThickness: 3,
		        bottomEdgeWidth: 0.25,
		        outerLabelAlignment: "right",
		        brushes: ["#13c4a5", "#233445", "#3fcf7f", "#5191d1", "#ff5f5f", "#f4c414"],
		        outlines: [ "#13c4a5", "#233445", "#3fcf7f", "#5191d1", "#ff5f5f", "#f4c414" ],
		        valueMemberPath: "Budget",
		        innerLabelMemberPath: "Budget",
		        innerLabelVisibility: "visible",
		        outerLabelMemberPath: "Department",
		        outerLabelVisibility: "visible",
		        funnelSliceDisplay: "weighted"
		    });
		  } else {
				$("#sales_pipeline").html('<p class="padder-v m-l-small">No data found.</p>');
		  }
		});*/

    // Leads by source campaigh horizontal bar chart
		$.get(appBaseUrl+'dashboard/getsourceleads', function(sourceLeads, status, jqXHR) {
			if (sourceLeads.length) {
				//var vdata = [ [10, "Jan"], [8, "Feb"], [4, "Mar"], [13, "Apr"], [17, "May"], [9, "Jun"] ];
			  $.plot($("#leads_source"), [sourceLeads], {
		      yaxis: {
						mode: "categories",
						tickLength: 0
		      },
		      grid: {
		      		backgroundColor: '#fff',
		          hoverable: true,
		          clickable: false,
		          borderWidth: 0
		      },
		      series: {
							bars: {
								show: true,
								fillColor: '#13c4a5',
								barWidth: .75,
								align: "center",
								horizontal: true
							},
		          shadowSize: 0,
		          highlightColor: '#13c4a5'
		      },
		      colors: ['#13c4a5']
			  });

			} else {
				$("#leads_source").html('<p class="padder-v m-l-small">No data found.</p>');
			}
		});

    // Cases by priority for vertical bar chart
		$.get(appBaseUrl+'dashboard/getprioritycases', function(priorityCases, status, jqXHR) {
			if (priorityCases.length) {
				//var hdata = [ ["Jan", 10], ["Feb", 8], ["Mar", 4] ];
			  $.plot($("#cases_priority"), [priorityCases], {
		      xaxis: {
						mode: "categories",
						tickLength: 0
		      },
		      grid: {
		      		backgroundColor: '#fff',
		          hoverable: true,
		          clickable: false,
		          borderWidth: 0
		      },
		      legend: {
		          labelBoxBorderColor: "none",
		          position: "left"
		      },
		      series: {
							bars: {
								show: true,
								fillColor: '#f4c414',
								barWidth: .3,
								align: "center"
							},
		          shadowSize: 0,
		          highlightColor: '#f4c414'
		      },
		      tooltip: true,
		      colors: ['#f4c414']
			  });

			} else {
				$("#cases_priority").html('<p class="padder-v m-l-small">No data found.</p>');
			}
		});
	}

	/* Detail pages entities creation flow - using ADD buttons appearing at the right side of panel header bars */
	if ($('.bcz-add-item-entity').length) {
		$('.bcz-add-item-entity').on('click', function() {
			// Handle panel collapse
			$('.bcz-add-item-entity.bcz-active-btn').removeClass('bcz-active-btn');
			$(this).addClass('bcz-active-btn');
			var dataContainer = $($(this).parents('.accordion-toggle').attr('href'));
			if (dataContainer.hasClass('collapse')) dataContainer.removeClass('collapse').addClass('in');
			else if (dataContainer.hasClass('in')) dataContainer.removeClass('in').addClass('collapse');

			var buttonData = $(this).data();
			if (buttonData.redirectUrl) {
				location.href = buttonData.redirectUrl;
			} else if (buttonData.href) {
				$(buttonData.href).find('input[name=associate_to]').val(buttonData.associateTo);
				$(buttonData.href).find('input[name=associate_id]').val(buttonData.associateId);
				$(buttonData.href).modal('show');
			}
		});
	}
	if ($('form.bcz-add-item-entity-form').length) {
		$('form.bcz-add-item-entity-form button[type=submit]').on('click', function() {
			var formElem = $(this).closest('form.bcz-add-item-entity-form');
			formElem.find('.alert').addClass('hide');

			formElem.ajaxForm(function(data, status, jqXHR) {
				if (status == 'success') {
					// Update the content
					var listContainerId = $('.bcz-add-item-entity.bcz-active-btn').parents('.accordion-toggle').attr('href');
					$(listContainerId+' .panel-body').html(data);

					// Reconstruct the data table
					if ($(listContainerId+' table.bcz-data-table').length) {
						$(listContainerId+' table.bcz-data-table').dataTable({
							"bProcessing": true,
							"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
							"sPaginationType": "full_numbers",
					    "bServerSide": true,
					    "sAjaxSource": $(listContainerId + ' table.bcz-data-table').data('source'),
	    				"fnDrawCallback": bczUtils.dataTableCallback
						});
					}

					// Open content panel
					$(listContainerId).removeClass('collapse').addClass('in').css({'height' : 'auto'});
					$('.bcz-add-item-entity.bcz-active-btn').button('reset');
					$('.bcz-add-item-entity.bcz-active-btn').removeClass('bcz-active-btn');

					// Reset the modal form and close modal
					formElem.resetForm();
					formElem.parent().modal('hide');
				}
      });
		});
	}
	
	
	
	
	/* Detail pages entities creation flow - using ADD buttons appearing at the right side of panel header bars */
	if ($('.bcz-add-item-entity').length) {
		$('.bcz-add-item-entity').on('click', function() {
			// Handle panel collapse
			$('.bcz-add-item-entity.bcz-active-btn').removeClass('bcz-active-btn');
			$(this).addClass('bcz-active-btn');
			var dataContainer = $($(this).parents('.accordion-toggle').attr('href'));
			if (dataContainer.hasClass('collapse')) dataContainer.removeClass('collapse').addClass('in');
			else if (dataContainer.hasClass('in')) dataContainer.removeClass('in').addClass('collapse');

			var buttonData = $(this).data();
			if (buttonData.redirectUrl) {
				location.href = buttonData.redirectUrl;
			} else if (buttonData.href) {
				$(buttonData.href).find('input[name=associate_to]').val(buttonData.associateTo);
				$(buttonData.href).find('input[name=associate_id]').val(buttonData.associateId);
				$(buttonData.href).modal('show');
			}
		});
	}
	if ($('form.bcz-add-item-entity-form').length) {
		$('form.bcz-add-item-entity-form button[type=submit]').on('click', function() {
			var formElem = $(this).closest('form.bcz-add-item-entity-form');
			formElem.find('.alert').addClass('hide');

			formElem.ajaxForm(function(data, status, jqXHR) {
				if (status == 'success') {
					// Update the content
					var listContainerId = $('.bcz-add-item-entity.bcz-active-btn').parents('.accordion-toggle').attr('href');
					$(listContainerId+' .panel-body').html(data);

					// Reconstruct the data table
					if ($(listContainerId+' table.bcz-data-table').length) {
						$(listContainerId+' table.bcz-data-table').dataTable({
							"bProcessing": true,
							"sDom": "<'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
							"sPaginationType": "full_numbers",
					    "bServerSide": true,
					    "sAjaxSource": $(listContainerId + ' table.bcz-data-table').data('source'),
	    				"fnDrawCallback": bczUtils.dataTableCallback
						});
					}

					// Open content panel
					$(listContainerId).removeClass('collapse').addClass('in').css({'height' : 'auto'});
					$('.bcz-add-item-entity.bcz-active-btn').button('reset');
					$('.bcz-add-item-entity.bcz-active-btn').removeClass('bcz-active-btn');

					// Reset the modal form and close modal
					formElem.resetForm();
					formElem.parent().modal('hide');
				}
      });
		});
	}
	
	

	/* Case details page */
	if($('body.case-details').length) {
		// Action button click handler
		$('.case-actions a').on('click', function(e) {
			if ($(this).data('redirect-url'))	location.href = $(this).data('redirect-url');
			else $($(this).attr('href')).modal('show');

			if ($(this).hasClass('bcz-confirm-active') && $(this).data('bcz-confirm')) {
				$.post(appBaseUrl+'cases/delete', {'case_id': $(this).parents('.page-actions').data('pageid')}, function(data, status, jqXHR) {
					if (data.success && data.redirectUrl) {
						location.href = data.redirectUrl;
					} else {
						// TODO: handle error response
					}
				});
			}
		});

		// Reassign form submission handler
		$('button#reassign_case').on('click', function() {
			var reassignBtn = $(this);
			var reassignForm = $(this).closest('form');
			reassignForm.find('.alert').addClass('hide');

			$.post(reassignForm.attr('action'), reassignForm.serialize(), function(data, status, jqXHR) {
				if (data.success) {
					if (data.redirectUrl) location.href = data.redirectUrl;
					else location.reload();
				}
			});
		});

		// Status change handler
		$('.wizard .actions').on('click', 'button', function(e) {
			var params = {};
			params.next = $(this).hasClass('next-btn');
			params.prev = !params.next;
			params.id = $(this).parent().data('id');
			var activeStage = $(this).parents('.wizard').find('ul.steps li.active');
			params.status = params.next ? activeStage.next().data('stage') : activeStage.prev().data('stage');
			$.post(appBaseUrl+'cases/changestatus', params, function(data, status, jqXHR) {
				if (data.success) {
					$('.wizard .steps li').removeClass('active');
					$('.wizard .steps li span.badge').removeClass('badge-info');
					if (params.next) {
						activeStage.next().addClass('active').find('span.badge').addClass('badge-info');
					} else {
						activeStage.prev().addClass('active').find('span.badge').addClass('badge-info');
					}

					// Show or hide the stage change buttons
					$('.wizard .actions button').removeClass('hide');
					if (!$('.wizard .steps li.active').prev('li').length) $('.wizard .actions button.prev-btn').addClass('hide');
					if (!$('.wizard .steps li.active').next('li').length) $('.wizard .actions button.next-btn').addClass('hide');
				}
			});
		});
	}

	/* Task details page */

	if($('body.task-details').length) {
		// Action button click handler
		$('.task-actions a').on('click', function(e) {
			if ($(this).data('redirect-url'))	location.href = $(this).data('redirect-url');
			else $($(this).attr('href')).modal('show');

			if ($(this).hasClass('bcz-confirm-active') && $(this).data('bcz-confirm')) {
				$.post(appBaseUrl+'tasks/delete', {'task_id': $(this).parents('.page-actions').data('pageid')}, function(data, status, jqXHR) {
					if (data.success && data.redirectUrl) {
						location.href = data.redirectUrl;
					} else {
						// TODO: handle error response
					}
				});
			}
		});

		// Reassign form submission handler
		$('button#reassign_task').on('click', function() {
			var reassignBtn = $(this);
			var reassignForm = $(this).closest('form');
			reassignForm.find('.alert').addClass('hide');

			$.post(reassignForm.attr('action'), reassignForm.serialize(), function(data, status, jqXHR) {
				if (data.success) {
					if (data.redirectUrl) location.href = data.redirectUrl;
					else location.reload();
				}
			});
		});

		// Status change handler
		$('.wizard .actions').on('click', 'button', function(e) {
			var params = {};
			params.next = $(this).hasClass('next-btn');
			params.prev = !params.next;
			params.id = $(this).parent().data('id');
			var activeStage = $(this).parents('.wizard').find('ul.steps li.active');
			params.status = params.next ? activeStage.next().data('stage') : activeStage.prev().data('stage');
			$.post(appBaseUrl+'tasks/changestatus', params, function(data, status, jqXHR) {
				if (data.success) {
					$('.wizard .steps li').removeClass('active');
					$('.wizard .steps li span.badge').removeClass('badge-info');
					if (params.next) {
						activeStage.next().addClass('active').find('span.badge').addClass('badge-info');
					} else {
						activeStage.prev().addClass('active').find('span.badge').addClass('badge-info');
					}

					// Show or hide the stage change buttons
					$('.wizard .actions button').removeClass('hide');
					if (!$('.wizard .steps li.active').prev('li').length) $('.wizard .actions button.prev-btn').addClass('hide');
					if (!$('.wizard .steps li.active').next('li').length) $('.wizard .actions button.next-btn').addClass('hide');
				}
			});
		});
	}
$(function() {
    $( "#tabs" ).tabs();
  });
});


//leads Status change 
				$('.wizard .actions').on('click', 'button', function(e) {
			var params = {};
			params.next = $(this).hasClass('next-btn');
			params.prev = !params.next;
			params.id = $(this).parent().data('id');
			
			var activeStage = $(this).parents('.wizard').find('ul.steps li.active');
			params.status = params.next ? activeStage.next().data('stage') : activeStage.prev().data('stage');
			$.post(appBaseUrl+'leads/changestatus', params, function(data, status, jqXHR) {
				if (data.success) {
					$('.wizard .steps li').removeClass('active');
					$('.wizard .steps li span.badge').removeClass('badge-info');
					if (params.next) {
						activeStage.next().addClass('active').find('span.badge').addClass('badge-info');
					} else {
						activeStage.prev().addClass('active').find('span.badge').addClass('badge-info');
					}

					// Show or hide the stage change buttons
					$('.wizard .actions button').removeClass('hide');
					if (!$('.wizard .steps li.active').prev('li').length) $('.wizard .actions button.prev-btn').addClass('hide');
					if (!$('.wizard .steps li.active').next('li').length) $('.wizard .actions button.next-btn').addClass('hide');
				}
			});
		});
		
		
			if($('body.campaign-details').length) {
		//leads Status change 
				$('.wizard .actions').on('click', 'button', function(e) {
					
				
					
			var params = {};
			params.next = $(this).hasClass('next-btn');
			params.prev = !params.next;
			params.id = $(this).parent().data('id');
			
			var activeStage = $(this).parents('.wizard').find('ul.steps li.active');
			params.status = params.next ? activeStage.next().data('stage') : activeStage.prev().data('stage');
			$.post(appBaseUrl+'campaign/changestatus', params, function(data, status, jqXHR) {
				
				if (data.success) {
					$('.wizard .steps li').removeClass('active');
					$('.wizard .steps li span.badge').removeClass('badge-info');
					if (params.next) {
						activeStage.next().addClass('active').find('span.badge').addClass('badge-info');
					} else {
						activeStage.prev().addClass('active').find('span.badge').addClass('badge-info');
					}

					// Show or hide the stage change buttons
					$('.wizard .actions button').removeClass('hide');
					if (!$('.wizard .steps li.active').prev('li').length) $('.wizard .actions button.prev-btn').addClass('hide');
					if (!$('.wizard .steps li.active').next('li').length) $('.wizard .actions button.next-btn').addClass('hide');
				}
			});
		});
		
			}
		
		
		
		$(function() {
			
			$('#create_lead').click(function() {
				var lead_fname=$('#lead_fname');
				var lead_lname=$('#lead_lname');
				if((lead_fname.val()=="" && lead_fname.val()==""))
				{
					if(lead_lname.val()=="")
				{
					lead_fname.addClass('parsley-error');
					lead_lname.addClass('parsley-error');
					lead_fname.focus();
						$('#error').html('<p>&nbsp;&nbsp;<ul id="parsley-08755371603183448" class="parsley-error-list" style="display: block;margin-left: 15px;margin-top: -11px;"><li class="required" style="display: list-item;">Enter either First Name or Last Name..</li></ul></p>');
						return false
						
				}
				else
				{
					lead_fname.removeClass('parsley-error');
					lead_lname.removeClass('parsley-error');
					$('#error').html('');
				}
				}
				else
				{
					lead_fname.removeClass('parsley-error');
					lead_lname.removeClass('parsley-error');
					$('#error').html('');
				}
		
			
			});
			
			$( "#lead_fname" ).keydown(function( event ) {
					lead_fname.removeClass('parsley-error');
					lead_lname.removeClass('parsley-error');
					$('#error').html('');
				
				});
				$( "#lead_lname" ).keydown(function( event ) {
					lead_fname.removeClass('parsley-error');
					lead_lname.removeClass('parsley-error');
					$('#error').html('');
				
				});
			
			var input=$('input').val();	
			});
			
			
			
	/*		function confirmBeforeUnload(e) {
        var e = e || window.event;

        // For IE and Firefox
        if (e) {
            e.returnValue = 'This page is asking you to confirm that you want to leave- data you have entered may not be saved';
        }

        // For Safari
        return 'This page is asking you to confirm that you want to leave- data you have entered may not be saved';

    }
    function goodbye(e) {
		
        if (!e) e = window.event;
        //e.cancelBubble is supported by IE - this will kill the bubbling process.
        e.cancelBubble = true;
        e.returnValue = 'This page is asking you to confirm that you want to leave- data you have entered may not be saved?'; //This is displayed on the dialog

        //e.stopPropagation works in Firefox.
        if (e.stopPropagation) {
            e.stopPropagation();
            e.preventDefault();
        }
    }
	
	
	
	$(document).ready(function() {
        
		$('input[name=company_name]').on('change', function() {
			
			window.onbeforeunload = goodbye;
		 });
		 
		 $('input[name=deal_name]').on('change', function() {
			window.onbeforeunload = goodbye;
		 });
		 
		  $('input[name=task_name]').on('change', function() {
			window.onbeforeunload = goodbye;
		 });
		  $('input[name=mobile]').on('change', function() {
			window.onbeforeunload = goodbye;
		 });
		 $('input[name=product_name]').on('change', function() {
			window.onbeforeunload = goodbye;
		 });
		 $('input[name=case_title]').on('change', function() {
			window.onbeforeunload = goodbye;
		 });
		  $('input[name=ship_addr]').on('change', function() {
			window.onbeforeunload = goodbye;
		 });
		 
    });*/
	
	
	$(document).ready(function() {
		
		
						
							
								
						
						
	
  
		
		 $('.icon-remove-sig').click(function() {	
			 $('#opp_company_name').val('');
			 $('#deal_company_id').val('');
			   formmodified=3;
		 });
		 
		 $('.icon-remove-sig-con').click(function() {	
			 $('#deal_contact_id').val('');
			 $('#opp_contact_name').val('');
			   formmodified=3;
		 });
    formmodified=0;

		$(".opp_company_name").on('change', function() {
			
			 formmodified=0;
			
		 });
		 
	
	$("input[name=company_name]").on('change', function() {
			 formmodified=0;
			
		 });
	
	
    $('form *').change(function(){
        formmodified=1;
		
    });

		  
	$('#bcz_search').on('change', function() {
			 formmodified=0;
		 });
		  
	
		 
    window.onbeforeunload = confirmExit;
    function confirmExit() {
		
		
		
        if (formmodified == 1) {
            return "This page is asking you to confirm that you want to leave- data you have entered may not be saved";
        }
		
		
		
    }
    $("button[type='submit']").click(function() {
        formmodified = 0;
    });

 $("#share_btn").click(function() {
        formmodified = 0;
    });
	
	$('.dropdown-menu-opp').click(function() {
		
				 formmodified=3;
		 });
		 
	$('.dropdown-menu-opp-con').click(function() {
		
				 formmodified=3;
		 });	
		 
		
	
	$('#active').click(function() {
		
		//alert(appBaseUrl+'settings/getinactiveUsers');

		 
	
		
		$('.inactive1').hide();
		$('.active1').show();
		
		
		});
		
		$('#inactive').click(function()  {
			
		//alert(appBaseUrl+'settings/getUsers');

		
		$('.inactive1').show();
		$('.active1').hide();
		
		});
		
		// Handling search in the header bar 

		
		// Handling search in the header bar 
  	/* Filters change handler */
	

/* Filters change handler */
	if ($('.dataTables_filter').length) {/*
		$('.dataTables_filter').on('keyup', function(e) {
			var params = {};
			$('.dataTables_filter').each(function() {
				params[$(this).attr('name')] = $(this).val();
			});

			$.post($(this).parents('.dataTables_filter').data(appBaseUrl+'crm/contacts/getcontactssearchjson'), {'filters': $('.dataTables_filter').val()}, function(data, status, jqXHR) {
				$('.bcz-filters-content').html(data);
				
			

				// Construct datatable
				$('table.bcz-data-table').dataTable({
					"bProcessing": true,
					"sDom": "<div class='row'><div class='col-sm-6'><div id='DataTables_Table_0_length' class='dataTables_length'><label>Show <select size='1' name='DataTables_Table_0_length' aria-controls='DataTables_Table_0'><option value='10' selected='selected'>10</option><option value='25'>25</option><option value='50'>50</option><option value='100'>100</option></select> entries</label></div></div><div class='col-sm-6'><div class='dataTables_filter' id='DataTables_Table_0_filter'><label>Search: <input type='text' aria-controls='DataTables_Table_0'></label></div></div><div id='DataTables_Table_0_processing' class='dataTables_processing' style='visibility: hidden;'>Processing...</div></div><'row'<'col-sm-6'l><'col-sm-6'f>r>t<'row'<'col-sm-6'i><'col col-sm-6'p>>",
					"sPaginationType": "full_numbers",
			    "bServerSide": true,
			    "sAjaxSource": $('table.bcz-data-table').data('source') + "?filters=" + JSON.stringify(params),
	    		"fnDrawCallback": bczUtils.dataTableCallback
				});
			});
		});
	*/}
	
	

		/* Filters change handler */
	
	
	





$("#opp_company_name").on('keyup', function () {
	  var bcz_search=$('#opp_company_name').val().length;
	  if(bcz_search>=3)
	  {
		
    	// Remove all the previous results
		$('.dropdown-menu-opp li').remove();

		// Return with status message if no query specified to search
		var searchString = $(this).val().trim();
		if (searchString.length <= 0) {
			$('.dropdown-menu-opp').html('<li class="padder-l-mini text-center">Specify your query</li>').removeClass('hide');
			return; 
		}

		// Get call to search for the specified query string
	
		$.get(appBaseUrl+'search/matchescompanyJson?query='+searchString, function(data, status, jqXHR) {
			var resultsHtml = "";
			if (data.success) {
				
				var cid;
				var jk=0;
				for (var i = 0; i < data.results.length; i++) {
					if(data.results[i].type!="")
					{
						jk++;
					
					labelType="";
					if(data.results[i].type=="contact")
					{
						if(cid!=data.results[i].id)
						{
							cid=data.results[i].id;
						var itemUrl = data.results[i].id;
					resultsHtml += "<li><a href='#' data-cid='"+itemUrl+"' data-val='"+data.results[i].name+"' class='cid'><span class='search-item'>"+data.results[i].name+"</span></a></li>";
					if (i < (data.results.length - 1)) resultsHtml += "<li class='divider'></li>";
					 formmodified=3;
					 
					 $( '<script type="text/javascript" src="'+appBaseUrl+'assets/js/add_deals.js"></script>' ).insertAfter( ".ui-igtrialwatermark" );
					
						}
					}
					else
					{

					var itemUrl = data.results[i].id;
					resultsHtml += "<li><a href='#' data-cid='"+itemUrl+"' data-val='"+data.results[i].name+"' class='cid'><span class='search-item'>"+data.results[i].name+"</span></a></li>";
					if (i < (data.results.length - 1)) resultsHtml += "<li class='divider'></li>";
					if(i==0)
					{
				
					 formmodified=3;
					}
					}
				}
				if(jk==0)
				{
					resultsHtml = '<li class="padder-l-mini text-center">No results found</li>';
				}
			}

			} else {
				resultsHtml = '<li class="padder-l-mini text-center">No results found</li>';
			}

			if (status == 'success') $('.dropdown-menu-opp').html(resultsHtml).removeClass('hide');
			
				$("<script>$(function() {$('.cid').click(function()	{var cid=$(this).data('cid');val=$(this).data('val'); $('#opp_company_name').val(val);$('#deal_company_id').val(cid);$('.dropdown-menu-opp').addClass('hide');});});</script>" ).insertAfter( ".ui-igtrialwatermark" );
				
				
		});
		
	  }
		
		
  });
  
    $("#opp_company_name").on('focus', function () {
  	$('.dropdown-menu-opp').addClass('hide');	// Hide the search results dropdown
  });
   $("body").click(function() {
  	$('.dropdown-menu-opp').addClass('hide');	// Hide the search results dropdown
  });




















$("#opp_contact_name").on('keyup', function () {
	  var bcz_search=$('#opp_contact_name').val().length;
	  if(bcz_search==1)
	  {
		
    	// Remove all the previous results
		$('.dropdown-menu-opp-con li').remove();

		// Return with status message if no query specified to search
		var searchdata='&deal_company_id='+$('#deal_company_id').val()+'&opp_company_name='+$('#opp_company_name').val();
		var searchString = $(this).val().trim();
		if (searchString.length <= 0) {
			$('.dropdown-menu-opp-con').html('<li class="padder-l-mini text-center">Specify your query</li>').removeClass('hide');
			return; 
		}

		// Get call to search for the specified query string
	
		$.get(appBaseUrl+'search/matchesfullcontactJson?query='+searchString+searchdata, function(data, status, jqXHR) {
			var resultsHtml = "";
			if (data.success) {
				
				var cid;
				var jk=0;
				for (var i = 0; i < data.results.length; i++) {
					if(data.results[i].type!="")
					{
						jk++;
					
					labelType="";
					if(data.results[i].type=="contact")
					{
						if(cid!=data.results[i].id)
						{
							cid=data.results[i].id;
						var itemUrl = data.results[i].id;
						if(data.results[i].name!="undefined")
						{
							
					resultsHtml += "<li><a href='#' data-cid='"+itemUrl+"' data-val='"+data.results[i].name+"' class='cidc'><span class='search-item'>"+data.results[i].name+"</span></a></li>";
					if (i < (data.results.length - 1)) resultsHtml += "<li class='divider'></li>";
					 formmodified=3;
					 
					 $( '<script type="text/javascript" src="'+appBaseUrl+'assets/js/add_deals.js"></script>' ).insertAfter( ".ui-igtrialwatermark" );
						}
					
						}
					}
					else
					{

					var itemUrl = data.results[i].id;
					resultsHtml += "<li><a href='#' data-cid='"+itemUrl+"' data-val='"+data.results[i].name+"' class='cidc'><span class='search-item'>"+data.results[i].name+"</span></a></li>";
					if (i < (data.results.length - 1)) resultsHtml += "<li class='divider'></li>";
					if(i==0)
					{
				
					 formmodified=3;
					}
					}
				}
				if(jk==0)
				{
					resultsHtml = '<li class="padder-l-mini text-center">No results found</li>';
				}
			}

			} else {
				resultsHtml = '<li class="padder-l-mini text-center">No results found</li>';
			}

			if (status == 'success') $('.dropdown-menu-opp-con').html(resultsHtml).removeClass('hide');
			
				
				$("<script>$(function() {$('.cidc').click(function()	{var cid=$(this).data('cid');val=$(this).data('val'); $('#opp_contact_name').val(val);$('#deal_contact_id').val(cid);$('.dropdown-menu-opp-con').addClass('hide');});});</script>" ).insertAfter( ".ui-igtrialwatermark" );
				
				
		});
		
	  }
	  else
	  {
		  // Remove all the previous results
		$('.dropdown-menu-opp-con li').remove();

		// Return with status message if no query specified to search
		var searchdata='&deal_company_id='+$('#deal_company_id').val()+'&opp_company_name='+$('#opp_company_name').val();
		var searchString = $(this).val().trim();
		if (searchString.length <= 0) {
			$('.dropdown-menu-opp-con').html('<li class="padder-l-mini text-center">Specify your query</li>').removeClass('hide');
			return; 
		}

		// Get call to search for the specified query string
	
		$.get(appBaseUrl+'search/matchescontactJson?query='+searchString+searchdata, function(data, status, jqXHR) {
			var resultsHtml = "";
			if (data.success) {
				
				var cid;
				var jk=0;
				for (var i = 0; i < data.results.length; i++) {
					if(data.results[i].type!="")
					{
						jk++;
					
					labelType="";
					if(data.results[i].type=="contact")
					{
						if(cid!=data.results[i].id)
						{
							cid=data.results[i].id;
						var itemUrl = data.results[i].id;
						if(data.results[i].name!="undefined")
						{
							
					resultsHtml += "<li><a href='#' data-cid='"+itemUrl+"' data-val='"+data.results[i].name+"' class='cidc'><span class='search-item'>"+data.results[i].name+"</span></a></li>";
					if (i < (data.results.length - 1)) resultsHtml += "<li class='divider'></li>";
					 formmodified=3;
					 
					 $( '<script type="text/javascript" src="'+appBaseUrl+'assets/js/add_deals.js"></script>' ).insertAfter( ".ui-igtrialwatermark" );
						}
					
						}
					}
					else
					{

					var itemUrl = data.results[i].id;
					resultsHtml += "<li><a href='#' data-cid='"+itemUrl+"' data-val='"+data.results[i].name+"' class='cidc'><span class='search-item'>"+data.results[i].name+"</span></a></li>";
					if (i < (data.results.length - 1)) resultsHtml += "<li class='divider'></li>";
					if(i==0)
					{
				
					 formmodified=3;
					}
					}
				}
				if(jk==0)
				{
					resultsHtml = '<li class="padder-l-mini text-center">No results found</li>';
				}
			}

			} else {
				resultsHtml = '<li class="padder-l-mini text-center">No results found</li>';
			}

			if (status == 'success') $('.dropdown-menu-opp-con').html(resultsHtml).removeClass('hide');
			
				$("<script>$(function() {$('.cidc').click(function()	{var cid=$(this).data('cid');val=$(this).data('val'); $('#opp_contact_name').val(val);$('#deal_contact_id').val(cid);$('.dropdown-menu-opp-con').addClass('hide');});});</script>" ).insertAfter( ".ui-igtrialwatermark" );
				
				
		});
	  }
		
		
  });
  
    $("#opp_contact_name").on('focus', function () {
  	$('.dropdown-menu-opp-con').addClass('hide');	// Hide the search results dropdown
  });
   $("body").click(function() {
  	$('.dropdown-menu-opp-con').addClass('hide');	// Hide the search results dropdown
  });



$('body.create-deal .form-horizontal #add_opp').click(function() {
	
				var deal_name=$('#deal_name');
				var deal_amount=$('#deal_amount');
				var stage=$('#stage');
				var exp_close=$('#exp_close');
				
				var opp_company_name=$('#opp_company_name');
				var opp_contact_name=$('#opp_contact_name');
				
				var fal=0;
				
				
				
				
				
				
				if(deal_name.val()=="")
				{
					deal_name.addClass('parsley-error');
					deal_name.focus();
						$('#error2').html('<br/><ul id="parsley-08755371603183448" class="parsley-error-list" style="display: block;margin-top: -11px;"><li class="required" style="display: list-item;">This value is required.</li></ul>');
						
						 fal=1;
						
				}
				
				
				else
				{
					deal_name.removeClass('parsley-error');
					$('#error2').html('');
					 fal=0;
				}
				
				
				
				
				
				if(opp_company_name.val()=="")
				{
					opp_company_name.addClass('parsley-error');
					opp_company_name.focus();
						$('#error').html('<br/><ul id="parsley-08755371603183448" class="parsley-error-list" style="display: block;margin-top: -11px;"><li class="required" style="display: list-item;">This value is required.</li></ul>');
						
						 fal=1;
						
				}
				
				
				else
				{
					opp_company_name.removeClass('parsley-error');
					$('#error').html('');
					if(fal==1)
					{
						 fal=1;
					}
					else
					{
						 fal=0;
					}
				}
				
				if(opp_contact_name.val()=="")
				{
					opp_contact_name.addClass('parsley-error');
					opp_contact_name.focus();
						$('#error1').html('<br/><ul id="parsley-08755371603183448" class="parsley-error-list" style="display: block;margin-top: -11px;"><li class="required" style="display: list-item;">This value is required.</li></ul>');
						 fal=1;
						
				}
				
				
				else
				{
					if(fal==1)
					{
						 fal=1;
					}
					else
					{
						 fal=0;
					}
					opp_contact_name.removeClass('parsley-error');
					$('#error1').html('');
				}
				
				
				if(deal_amount.val()=="")
				{
					deal_amount.addClass('parsley-error');
					deal_amount.focus();
						$('#error3').html('<br/><ul id="parsley-08755371603183448" class="parsley-error-list" style="display: block;margin-top: -11px;"><li class="required" style="display: list-item;">This value is required.</li></ul>');
						 fal=1;
						
				}
				
				
				else
				{
					if(fal==1)
					{
						 fal=1;
					}
					else
					{
						 fal=0;
					}
					deal_amount.removeClass('parsley-error');
					$('#error3').html('');
				}
				
				
				
				
					if(stage.val()=="")
				{
					stage.addClass('parsley-error');
					stage.focus();
						$('#error4').html('<br/><ul id="parsley-08755371603183448" class="parsley-error-list" style="display: block;margin-top: -11px;"><li class="required" style="display: list-item;">This value is required.</li></ul>');
						 fal=1;
						
				}
				
				
				else
				{
					if(fal==1)
					{
						 fal=1;
					}
					else
					{
						 fal=0;
					}
					stage.removeClass('parsley-error');
					$('#error4').html('');
				}
				
				
				
				if(exp_close.val()=="")
				{
					exp_close.addClass('parsley-error');
					exp_close.focus();
						$('#error5').html('<br/><ul id="parsley-08755371603183448" class="parsley-error-list" style="display: block;margin-top: -11px;"><li class="required" style="display: list-item;">This value is required.</li></ul>');
						 fal=1;
						
				}
				
				
				else
				{
					if(fal==1)
					{
						 fal=1;
					}
					else
					{
						 fal=0;
					}
					exp_close.removeClass('parsley-error');
					$('#error5').html('');
				}
				
				
				
				if(fal==1)
		{
		
			return false
		}
			});
			
			
	$('.advancedsettings select.sequence').on('change', function() {
		
		var numbering	=	$(this).find('option:selected').data('id');
		var sequence	=	$(this).find('option:selected').data('sequence');
		var prefix		=	$(this).find('option:selected').data('prefix');
			
		$('#numbering').val(numbering);
		$('#sequence').val(sequence);
		$('#prefix').val(prefix);
		});

		// advancedsettings data submission handler
		

			$('.advancedsettings button#save_org').on('click', function() {
			var saveOrgBtn = $(this);
			var saveOrgForm = $(this).closest('form');
			saveOrgForm.ajaxForm(function(data) {
				$('.mes').html(data);
					
      });
		});
		
		$('.exporta').on('click', function() {
			
			
			var val = $('.export').val();
			
			if(val=="0")
			{
				alert('Please choose any one of the filter to enable the Export button');
				return false
			}
			else
			{
				return true
			}
		});
		
	
	

	
	if( $('.ProductsImportMappingView').length)
	{
		

		
var selects = $('.option-select');
$('.option-select').change(function(){
   var value = $(this).val();
   var count = 0;
   for(var i=0; i<selects.length; i++){
      if($(this).attr('id') != $(selects[0]).attr('id')) {
         var checkVal = $(selects[i]).val();
         if(value == checkVal) {
			 count++;
			 if(count>1)
			 {
            alert("Value already selected, please select a different value");
            $("#"+$(this).attr('id')+" option[value='']").attr("selected", "selected"); $("#s2id_"+$(this).attr('id')+" span").html("-- Choose Option --"); return false;}
         }
      }
   }

});

var selects = $('.option-select');
$('button').click(function(){
   var value = $(this).val();
   var count1 = 0;
   for(var i=0; i<selects.length; i++){
      if($(this).attr('id') != $(selects[0]).attr('id')) {
         var checkVal = $(selects[i]).val();
		 if(checkVal!="")
		 {
         if(value == checkVal) {
			 count1++;
			 if(count1>2)
			 {
            alert("Value already selected, please select a different value");
            $("#"+$(this).attr('id')+" option[value='']").attr("selected", "selected");  $("#s2id_"+$(this).attr('id')+" span").html("-- Choose Option --");return false;}
         }
		 }
      }
   }

});
	}
	
	
		
	if( $('.LeadsImportMappingView').length)
	{
		

		
var selects = $('.option-select');
$('.option-select').change(function(){
   var value = $(this).val();
   var count = 0;
   for(var i=0; i<selects.length; i++){
      if($(this).attr('id') != $(selects[0]).attr('id')) {
         var checkVal = $(selects[i]).val();
         if(value == checkVal) {
			 count++;
			 if(count>1)
			 {
            alert("Value already selected, please select a different value");
            $("#"+$(this).attr('id')+" option[value='']").attr("selected", "selected");
			
			 $("#s2id_"+$(this).attr('id')+" span").html("-- Choose Option --");
			
			 return false;}
         }
      }
   }

});

var selects = $('.option-select');
$('button').click(function(){
	
   var value = $(this).val();
   var count1 = 0;
   for(var i=0; i<selects.length; i++){
      if($(this).attr('id') != $(selects[0]).attr('id')) {
         var checkVal = $(selects[i]).val();
		 if(checkVal!="")
		 {
         if(value == checkVal) {
			 count1++;
			 if(count1>2)
			 {
            alert("Value already selected, please select a different value");
            $("#"+$(this).attr('id')+" option[value='']").attr("selected", "selected"); $("#s2id_"+$(this).attr('id')+" span").html("-- Choose Option --"); return false;}
         }
		 }
      }
   }

});
	}
	
	
		if( $('.ContactsImportMappingView').length)
	{
		

		
var selects = $('.option-select');
$('.option-select').change(function(){
   var value = $(this).val();
   var count = 0;
   for(var i=0; i<selects.length; i++){
      if($(this).attr('id') != $(selects[0]).attr('id')) {
         var checkVal = $(selects[i]).val();
         if(value == checkVal) {
			 count++;
			 if(count>1)
			 {
            alert("Value already selected, please select a different value");
            $("#"+$(this).attr('id')+" option[value='']").attr("selected", "selected");
			
			 $("#s2id_"+$(this).attr('id')+" span").html("-- Choose Option --");
			
			 return false;}
         }
      }
   }

});

var selects = $('.option-select');
$('button').click(function(){
	
   var value = $(this).val();
   var count1 = 0;
   for(var i=0; i<selects.length; i++){
      if($(this).attr('id') != $(selects[0]).attr('id')) {
         var checkVal = $(selects[i]).val();
		 if(checkVal!="")
		 {
         if(value == checkVal) {
			 count1++;
			 if(count1>2)
			 {
            alert("Value already selected, please select a different value");
            $("#"+$(this).attr('id')+" option[value='']").attr("selected", "selected"); $("#s2id_"+$(this).attr('id')+" span").html("-- Choose Option --"); return false;}
         }
		 }
      }
   }

});
	}
	
	
		if( $('.CompaniesImportMappingView').length)
	{
		

		
var selects = $('.option-select');
$('.option-select').change(function(){
   var value = $(this).val();
   var count = 0;
   for(var i=0; i<selects.length; i++){
      if($(this).attr('id') != $(selects[0]).attr('id')) {
         var checkVal = $(selects[i]).val();
         if(value == checkVal) {
			 count++;
			 if(count>1)
			 {
            alert("Value already selected, please select a different value");
            $("#"+$(this).attr('id')+" option[value='']").attr("selected", "selected");
			
			 $("#s2id_"+$(this).attr('id')+" span").html("-- Choose Option --");
			
			 return false;}
         }
      }
   }

});

var selects = $('.option-select');
$('button').click(function(){
	
   var value = $(this).val();
   var count1 = 0;
   for(var i=0; i<selects.length; i++){
      if($(this).attr('id') != $(selects[0]).attr('id')) {
         var checkVal = $(selects[i]).val();
		 if(checkVal!="")
		 {
         if(value == checkVal) {
			 count1++;
			 if(count1>2)
			 {
            alert("Value already selected, please select a different value");
            $("#"+$(this).attr('id')+" option[value='']").attr("selected", "selected"); $("#s2id_"+$(this).attr('id')+" span").html("-- Choose Option --"); return false;}
         }
		 }
      }
   }

});
	}
	
	if( ($('.ProductsImportView').length) || ($('.LeadsImportView').length)|| ($('.ContactsImportView').length)|| ($('.CompaniesImportView').length))
	{
	  $('button').click(function () {
        var avatar = $(".file-input-name").html();
		if( $('.file-input-name').length)
		{
        var extension = avatar.split('.').pop().toUpperCase();
        if(avatar.length < 1) {
            avatarok = 0;
        }
        else if (extension!="CSV"){
            avatarok = 0;
         
			$('#error').html('<ul id="parsley-7413491404149681" class="parsley-error-list" style="display: block;"><li class="required" style="display: list-item;">Supported File Type .CSV Only.</li></ul>');
			 return false;
        }
        else {
            avatarok = 1;
        }
		}
		else
		{
			$('#error').html('<ul id="parsley-7413491404149681" class="parsley-error-list" style="display: block;"><li class="required" style="display: list-item;">This value is required.</li></ul>');
			return false;
		}
    });
		}
		
		
		$('.create_account_deal_modal').click(function () {
			
		$('#create_account_deal_modal').modal();
				return false;
		});
		
			
		$('#create_account').click(function() {
 var company_name= $('.company_name-opp').val();
 $('#opp_company_name').val(company_name);
 $('#create_account_deal_modal').modal('hide');
  });
  

//timepicker
if ($('.create-task').length ){$("#timepicker").kendoTimePicker();}
if($('.edit-task').length){$("#timepicker").kendoTimePicker();}
if($('.lead-details').length ){	$("#timepicker").kendoTimePicker();}
if( $('.deal-details').length ){ $("#timepicker").kendoTimePicker();}
if( $('.company-details').length ){$("#timepicker").kendoTimePicker(); } 
if ($('.case-details').length ) {$("#timepicker").kendoTimePicker(); }
 if( $('.campaign-details').length ) { $("#timepicker").kendoTimePicker();}
 

		
});
	
	


	 
	