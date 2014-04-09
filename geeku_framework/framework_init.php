<?php

require_once(TEMPLATEPATH . '/geeku_framework/functions/functions.php');

// Load admin
require_once(TEMPLATEPATH . '/geeku_framework/admin/site-option.php');
require_once(TEMPLATEPATH . '/geeku_framework/admin/functions.php');
require_once(TEMPLATEPATH . '/geeku_framework/admin/admin-functions.php');
require_once(TEMPLATEPATH . '/geeku_framework/admin/admin-interface.php');

$update = get_option('ge_update_notifier');
if($update == 'true' || empty($update))
{
	require_once(TEMPLATEPATH . '/geeku_framework/functions/update-notifier.php');
}
