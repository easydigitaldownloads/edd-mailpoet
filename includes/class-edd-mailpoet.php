<?php
/**
 * EDD Mail Chimp class, extension of the EDD base newsletter classs
 *
 * @copyright   Copyright (c) 2013, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       2.0
*/

class EDD_MailPoet extends EDD_Newsletter {

	/**
	 * Sets up the checkout label
	 */
	public function init() {
		global $edd_options;
		if( ! empty( $edd_options['edd_wysija_label'] ) ) {
			$this->checkout_label = trim( $edd_options['edd_wysija_label'] );
		} else {
			$this->checkout_label = __( 'Signup for the newsletter', 'edd_wysija' );
		}

	}

	/**
	 * Retrieves the lists from MailPoet
	 */
	public function get_lists() {

		global $edd_options;

		if( ! class_exists( 'WYSIJA' ) ) {
			return array();
		}

		$modelList   = WYSIJA::get( 'list','model' );
		$wysijaLists = $modelList->get( array( 'name', 'list_id' ), array( 'is_enabled' => 1 ) );

		if( ! empty( $wysijaLists ) ) {
			foreach( $wysijaLists as $list ) {

				$this->lists[ $list['list_id'] ] = $list['name'];
			}
		}
		return (array) $this->lists;
	}

	/**
	 * Registers the plugin settings
	 */
	public function settings( $settings ) {

		$mailpoet_settings = array(
			array(
				'id' => 'edd_wysija_settings',
				'name' => '<strong>' . __( 'MailPoet Settings', 'edd_wysija' ) . '</strong>',
				'desc' => __( 'Configure MailPoet Integration Settings', 'edd_wysija' ),
				'type' => 'header'
			),
			array(
				'id'      => 'edd_wysija_show_checkout_signup',
				'name'    => __( 'Show Signup on Checkout', 'edd_wysija' ),
				'desc'    => __( 'Allow customers to signup for the list selected below during checkout?', 'edd_wysija' ),
				'type'    => 'checkbox'
			),
			array(
				'id' => 'edd_wysija_list',
				'name' => __('Choose a list', 'edd_wysija'),
				'desc' => __('Select the list you wish to subscribe buyers to', 'edd_wysija'),
				'type' => 'select',
				'options' => $this->get_lists()
			),
			array(
				'id' => 'edd_wysija_label',
				'name' => __('Checkout Label', 'edd_wysija'),
				'desc' => __('This is the text shown next to the signup option', 'edd_wysija'),
				'type' => 'text',
				'size' => 'regular'
			),
		);

		return array_merge( $settings, $mailpoet_settings );
	}

	/**
	 * Determines if the checkout signup option should be displayed
	 */
	public function show_checkout_signup() {
		global $edd_options;

		return ! empty( $edd_options['edd_wysija_show_checkout_signup'] );
	}

	/**
	 * Subscribe an email to a list
	 */
	public function subscribe_email( $user_info = array(), $list_id = false, $opt_in_overridde = false ) {

		global $edd_options;

		if( ! class_exists( 'WYSIJA' ) ) {
			return false;
		}

		// Retrieve the global list ID if none is provided
		if( ! $list_id ) {
			$list_id = ! empty( $edd_options['edd_wysija_list'] ) ? $edd_options['edd_wysija_list'] : false;
			if( ! $list_id ) {
				return false;
			}
		}

		$user_data = array(
			'email'     => $user_info['email'],
			'firstname' => $user_info['first_name'],
			'lastname'  => $user_info['last_name'],
		);

		$data = array(
			'user'      => $user_data,
			'user_list' => array( 'list_ids' => array( $list_id ) ),
		);

		$userHelper      = WYSIJA::get( 'user','helper' );
		$model_user_list = WYSIJA::get( 'user_list', 'model' );

		$user        = get_user_by( 'email', $user_data['email'] );
		$lists       = $model_user_list->get_lists( array( $user->ID ) );
		$users_lists = isset( $lists[ $user->ID ] ) ? $lists[ $user->ID ] : array();

		if ( ! in_array( $list_id, $users_lists ) ) {
			$userHelper->addSubscriber( $data );
		}

		if( $userHelper ) {
			return true;
		}

		return false;

	}

}