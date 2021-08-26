<?php
namespace Favorites\Listeners\REST;

use Favorites\Entities\User\UserFavorites;

/**
* Return the total number of favorites for a specified post
*/
class UserFavoriteList extends ListenerBase
{
	/**
	* Favorite Counter
	*/
	private $user_favorites;

	public function __construct($WP_REST_Request)
	{
		parent::__construct();
		$this->user_favorites = new UserFavorites;
		$this->setData($WP_REST_Request);
		$this->sendList();
	}

	private function setData($WP_REST_Request)
	{
		$siteId = $WP_REST_Request->get_param('siteid');
		$userId = get_current_user_id();

		$this->data['siteid'] = ( $siteId ) ? intval( $siteId ) : 1;
		$this->data['user_id'] = ( $userId && $userId !== '' ) ? $userId : null;
	}

	private function sendList()
	{
		try {
			$this->response(array(
				'status' => 'success',
				'data' => $this->user_favorites->getFavoritesArray($this->data['user_id'], $this->data['siteid'])
			));
		} catch ( \Exception $e ){
			return $this->sendError($e->getMessage());
		}

	}
}
