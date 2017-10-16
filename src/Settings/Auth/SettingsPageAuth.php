<?php declare( strict_types=1 ); # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Settings\Auth;

use Brain\Nonces\ArrayContext;
use Brain\Nonces\NonceInterface;
use Brain\Nonces\WpNonce;

/**
 * @package Inpsyde\GoogleTagManager\Settings
 */
class SettingsPageAuth implements SettingsPageAuthInterface {

	const DEFAULT_CAP = 'manage_options';

	/**
	 * @var string
	 */
	private $cap;

	/**
	 * @var WpNonce
	 */
	private $nonce;

	/**
	 * SettingsPageAuth constructor.
	 *
	 * @param string         $action
	 * @param string         $cap
	 * @param NonceInterface $nonce
	 */
	public function __construct( string $action, $cap = NULL, NonceInterface $nonce = NULL ) {

		$this->cap   = $cap ?? self::DEFAULT_CAP;
		$this->nonce = $nonce ?? new WpNonce( $action );
	}

	/**
	 * {@inheritdoc}
	 */
	public function is_allowed( array $request_data = [] ): bool {

		if ( ! current_user_can( $this->cap ) ) {

			return FALSE;
		}

		if ( is_multisite() && ms_is_switched() ) {

			return FALSE;
		}

		return $this->nonce->validate( new ArrayContext( $request_data ) );
	}

	/**
	 * @return NonceInterface
	 */
	public function nonce(): NonceInterface {

		return $this->nonce;
	}

	/**
	 * @return string
	 */
	public function cap(): string {

		return $this->cap;
	}

}