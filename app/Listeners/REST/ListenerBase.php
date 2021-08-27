<?php
namespace Favorites\Listeners\REST;

use Favorites\Config\SettingsRepository;

/**
* Base AJAX class
*/
abstract class ListenerBase
{
	/**
	* Form Data
	*/
	protected $data;

	/**
	* Settings Repo
	*/
	protected $settings_repo;

	public function __construct()
	{
		$this->settings_repo = new SettingsRepository;
		$this->checkLogIn();
	}

	/**
	* Send an Error Response
	* @param $error string
	*/
	protected function sendError($error = null)
	{
		$error = ( $error ) ? $error : __('The nonce could not be verified.', 'favorites');
		return wp_send_json(array(
			'status' => 'error',
			'message' => $error
		));
	}

	/**
	* Check if logged in
	*/
	protected function checkLogIn()
	{
		if ( isset($_POST['logged_in']) && intval($_POST['logged_in']) == 1 ) return true;
		if ( $this->settings_repo->anonymous('display') ) return true;
		if ( $this->settings_repo->requireLogin() ) return $this->response(array('status' => 'unauthenticated'));
		if ( $this->settings_repo->redirectAnonymous() ) return $this->response(array('status' => 'unauthenticated'));
	}

	/**
	* Send a response
	*/
	protected function response($response)
	{
		header( 'Access-Control-Allow-Origin: *' );
		header( 'Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE' );
		header( 'Access-Control-Allow-Credentials: true' );
		return wp_send_json($response);
	}
}
