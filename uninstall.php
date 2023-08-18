<?php
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
{
	wp_die( sprintf(
		__( '%s should only be called when uninstalling the plugin.', 'bspdi_inflation' ),
		__FILE__
	) );
	exit();
}