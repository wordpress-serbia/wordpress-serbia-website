<?php
/**
 * Plugin Name:     WPSrbija
 * Plugin URI:      https://wordpress-serbia.org/
 * Description:     Custom functionality for WordPress Serbia website
 * Author:          WordPress Serbia Community
 * Author URI:      https://wordpress-serbia.org/
 * Text Domain:     wp-serbia
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Wp_Serbia
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Require Authentication for all REST API requests
// https://developer.wordpress.org/rest-api/frequently-asked-questions/#require-authentication-for-all-requests
add_filter( 'rest_authentication_errors', function( $result ) {
    // If a previous authentication check was applied,
    // pass that result along without modification.
    if ( true === $result || is_wp_error( $result ) ) {
        return $result;
    }

    // No authentication has been performed yet.
    // Return an error if user is not logged in.
    if ( ! is_user_logged_in() ) {
        return new WP_Error(
            'rest_not_logged_in',
            __( 'You are not currently logged in.' ),
            array( 'status' => 401 )
        );
    }

    // Our custom authentication check should have no effect
    // on logged-in requests
    return $result;
});
