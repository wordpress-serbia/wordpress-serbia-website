<?php
/**
 * Tests for REST API authentication.
 *
 * @package Wp_Serbia
 */

/**
 * Test the REST API authentication filter.
 */
class RestAuthenticationTest extends WP_UnitTestCase {

	/**
	 * Ensure each test starts with an anonymous request.
	 */
	public function setUp() {
		parent::setUp();

		wp_set_current_user( 0 );
	}

	/**
	 * Anonymous REST API requests should be rejected.
	 */
	public function test_rejects_anonymous_requests() {
		$result = apply_filters( 'rest_authentication_errors', null );

		$this->assertWPError( $result );
		$this->assertSame( 'rest_not_logged_in', $result->get_error_code() );
		$this->assertSame( 'You are not currently logged in.', $result->get_error_message() );
		$this->assertSame( 401, $result->get_error_data()['status'] );
	}

	/**
	 * Logged-in REST API requests should not be affected.
	 */
	public function test_allows_logged_in_requests() {
		$user_id = self::factory()->user->create();
		wp_set_current_user( $user_id );

		$this->assertNull( apply_filters( 'rest_authentication_errors', null ) );
	}

	/**
	 * A successful result from an earlier authentication check should be preserved.
	 */
	public function test_preserves_previous_successful_authentication_result() {
		$this->assertTrue( apply_filters( 'rest_authentication_errors', true ) );
	}

	/**
	 * An error from an earlier authentication check should be preserved.
	 */
	public function test_preserves_previous_authentication_error() {
		$previous_error = new WP_Error( 'existing_error', 'Existing error.' );

		$this->assertSame(
			$previous_error,
			apply_filters( 'rest_authentication_errors', $previous_error )
		);
	}
}
