/********************************************************************************+
 * Thze contents of this file are subject to the Skyzon CRM License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  Skyzon CRM
 * The Initial Developer of the Original Code is Skyzon Technologies.
 * Portions created by Skyzon Technologies are Copyright (C) skyzon technologies.
 * All Rights Reserved.
 +********************************************************************************/

$(function() {
	$('.dropdown-menu-opp a').click(function()
	{
		var val=$(this).data('cid');
		alert(val);
	});
    
});
