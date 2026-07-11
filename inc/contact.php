<?php
/**
 * Lightweight contact form handler (no plugin dependency).
 * Submits to admin-post.php with a nonce + honeypot; emails the site admin.
 *
 * @package realestate
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handle the contact form submission.
 */
function re_handle_contact(): void {
	$redirect = wp_get_referer() ? wp_get_referer() : home_url( '/' );

	// Nonce + honeypot.
	if ( ! isset( $_POST['re_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['re_contact_nonce'] ) ), 're_contact' ) ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $redirect ) );
		exit;
	}
	if ( ! empty( $_POST['re_website'] ) ) { // Honeypot filled → bot.
		wp_safe_redirect( add_query_arg( 'contact', 'sent', $redirect ) );
		exit;
	}

	$name    = isset( $_POST['re_name'] ) ? sanitize_text_field( wp_unslash( $_POST['re_name'] ) ) : '';
	$email   = isset( $_POST['re_email'] ) ? sanitize_email( wp_unslash( $_POST['re_email'] ) ) : '';
	$phone   = isset( $_POST['re_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['re_phone'] ) ) : '';
	$message = isset( $_POST['re_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['re_message'] ) ) : '';

	if ( ! $name || ! is_email( $email ) || ! $message ) {
		wp_safe_redirect( add_query_arg( 'contact', 'error', $redirect ) );
		exit;
	}

	$to      = function_exists( 're_option' ) && re_option( 'contact_email' ) ? re_option( 'contact_email' ) : get_option( 'admin_email' );
	$subject = sprintf( '[%s] Contact from %s', get_bloginfo( 'name' ), $name );
	$body    = "Name: {$name}\nEmail: {$email}\nPhone: {$phone}\n\n{$message}";
	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

	wp_mail( $to, $subject, $body, $headers );

	wp_safe_redirect( add_query_arg( 'contact', 'sent', $redirect ) );
	exit;
}
add_action( 'admin_post_re_contact', 're_handle_contact' );
add_action( 'admin_post_nopriv_re_contact', 're_handle_contact' );
