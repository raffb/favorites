<?php
namespace Favorites\Listeners\REST;

use Favorites\Entities\Post\FavoriteCount as FavoriteCounter;

/**
* Return the total number of favorites for a specified post
*/
class FavoriteCount extends ListenerBase
{
	/**
	* Favorite Counter
	*/
	private $favorite_counter;

	public function __construct($WP_REST_Request)
	{
		parent::__construct();
		$this->favorite_counter = new FavoriteCounter;
		$this->setData($WP_REST_Request);
		$this->sendCount();
	}

	private function setData($WP_REST_Request)
	{
		$postId = $WP_REST_Request->get_param('postid');
		$siteId = $WP_REST_Request->get_param('siteid');
		$this->data['postid'] = ( $postId ) ? intval( $postId ) : null;
		$this->data['siteid'] = ( $siteId ) ? intval( $siteId ) : null;
	}

	private function sendCount()
	{
		$this->response(array(
			'status' => 'success',
			'count' => $this->favorite_counter->getCount($this->data['postid'], $this->data['siteid'])
		));
	}
}
