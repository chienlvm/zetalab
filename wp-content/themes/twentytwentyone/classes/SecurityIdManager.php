<?php

/**
 * Class manager securityId
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 * @author ChienLVM
 */

/**
 * This class manager securityId
 */
class SecurityIdManager
{
	/**
	 * key id
	 */
	public $key = '__id';

	/**
	 * Hàm lấy session[key] hiện tại cho mỗi phiên làm việc
	 * @return Id
	 */
	public function getSecurityId()
	{
		$session = $_SESSION[$this->key];
		return $session;
	}

	/**
	 * Method getRandomId
	 * @return Id
	 */
	public function setSecurityId()
	{
		session_start();
		$session = $_SESSION[$this->key];
		if (!isset($session)) {
			$randomId = uniqid();
			$_SESSION[$this->key] = $randomId;
		}
	}
	public function checkId(WP_REST_Request $request)
	{
		$session = '';
		$session = $request->get_param($this->key);
		$currentSession = $this->getSecurityId();
		if (!isset($session) || !isset($currentSession)) {
			throw wp_redirect(home_url('/999')); // redirect đến trang 404
			exit;
		}
		if ($session !== $currentSession) {
			throw wp_redirect(home_url('/999')); // redirect đến trang 404
			exit;
		}
	}
}