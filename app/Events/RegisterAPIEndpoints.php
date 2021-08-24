<?php
namespace Favorites\Events;

use Favorites\Listeners\REST\FavoriteCount;
use Favorites\Listeners\REST\FavoriteAdd;

class RegisterAPIEndpoints
{
	public function __construct()
	{
		$namespaces = 'favorites/v1/posts';

		register_rest_route( $namespaces, '/(?P<postid>\d+)', array(
			'methods' => 'GET',
			'callback' => array($this, 'getFavoriteCount'),
			'args' => array(
				'postid' => array(
					'required' => true,
					'description' => 'Specify a post to show. Defaults to the current post',
					'type' => 'number'
				),
				'siteid' => array(
					'description' => 'Specify a site ID if used in a multisite installation. Defaults to the current site/blog',
					'type' => 'number'
				)
			)
		));

		register_rest_route( $namespaces, '/(?P<postid>\d+)', array(
			'methods' => 'POST',
			'callback' => array($this, 'addFavorite'),
			'args' => array(
				'postid' => array(
					'required' => true,
					'description' => 'Specify a post to show. Defaults to the current post',
					'type' => 'number'
				),
				'siteid' => array(
					'description' => 'Specify a site ID if used in a multisite installation. Defaults to the current site/blog',
					'type' => 'number'
				)
			),
			'permission_callback' => function () {
				return is_user_logged_in();
			}
		));
	}

	public function getFavoriteCount($WP_REST_Request)
	{
		new FavoriteCount($WP_REST_Request);
	}

	public function addFavorite($WP_REST_Request)
	{
		new FavoriteAdd($WP_REST_Request);
	}

}
