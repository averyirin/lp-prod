<?php

if (remove_stale_plugins()) {
     system_message(elgg_echo('Successfully  disabled stale plugins'), 'Success');
} else {
     $string = "Could not update stale plugins";
     register_error(elgg_echo($string));
}

// don't regenerate the simplecache because the plugin won't be
// loaded until next run.  Just invalidate and let it regnerate as needed
elgg_invalidate_simplecache();
elgg_reset_system_cache();




?>
