<?php
namespace Favorites\Listeners\REST;

use Favorites\Entities\Favorite\Favorite;
use Favorites\Entities\User\UserRepository;

class FavoriteAdd extends ListenerBase
{
	/**
	* User Repository
	* @var Favorites\Entities\User\UserRepository
	*/
	private $user_repo;

	public function __construct($WP_REST_Request)
	{
		parent::__construct();
		$this->user_repo = new UserRepository;
		$this->setFormData($WP_REST_Request);
		$this->updateFavorite();
	}

	/**
	* Set Form Data
	*/
	private function setFormData($WP_REST_Request)
	{
		$postId = $WP_REST_Request->get_param('postid');
		$siteId = $WP_REST_Request->get_param('siteid');
		$status = $WP_REST_Request->get_param('status');
		$groupId = $WP_REST_Request->get_param('groupid');
		$loggedIn = is_user_logged_in();
		$userId = get_current_user_id();

		$this->data['postid'] = ( $postId ) ? intval( $postId ) : null;
		$this->data['siteid'] = ( $siteId ) ? intval( $siteId ) : 1;
		$this->data['status'] = ( $status == 'active') ? 'active' : 'inactive';
		$this->data['groupid'] = ( $groupId && $groupId !== '' ) ? intval($groupId) : 1;
		$this->data['logged_in'] = ( $loggedIn && $loggedIn !== '' ) ? true : null;
		$this->data['user_id'] = ( $userId && $userId !== '' ) ? $userId : null;
	}

	/**
	* Update the Favorite
	*/
	private function updateFavorite()
	{
		try {
			$this->beforeUpdateAction();
			$favorite = new Favorite;
			$favorite->update($this->data['postid'], $this->data['status'], $this->data['siteid'], $this->data['groupid']);
			$this->afterUpdateAction();
			$this->response(array(
				'status' => 'success'
			));
		} catch ( \Exception $e ){
			return $this->sendError($e->getMessage());
		}
	}

	/**
	* Before Update Action
	* Provides hook for performing actions before a favorite
	*/
	private function beforeUpdateAction()
	{
		$user = ( is_user_logged_in() ) ? get_current_user_id() : null;
		do_action('favorites_before_favorite', $this->data['postid'], $this->data['status'], $this->data['siteid'], $user);
	}

	/**
	* After Update Action
	* Provides hook for performing actions after a favorite
	*/
	private function afterUpdateAction()
	{
		$user = ( is_user_logged_in() ) ? get_current_user_id() : null;
		do_action('favorites_after_favorite', $this->data['postid'], $this->data['status'], $this->data['siteid'], $user);
	}
}
