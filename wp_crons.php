<?php

use boctulus\SW\libs\RunaSync;

// See http://codex.wordpress.org/Plugin_API/Filter_Reference/cron_schedules
add_filter( 'cron_schedules', 'custom_cron_interval' );

function custom_cron_interval( $schedules ) {
    $secs = config()['cronjob_interval']; // 300

    $schedules['custom_cron_interval'] = array(
        'interval' => $secs, // en segundos
        'display' => __( "Every $secs segundos" )
    );
    return $schedules;
}


// Schedule an action if it's not already scheduled
if ( ! wp_next_scheduled( 'custom_cron_interval' ) ) {
    wp_schedule_event( time(), 'custom_cron_interval', 'custom_cron_interval' );
}

add_action( 'custom_cron_interval', 'custom_cron_interval_event_func' );
function custom_cron_interval_event_func() {
    long_exec();
    RunaSync::init();
}