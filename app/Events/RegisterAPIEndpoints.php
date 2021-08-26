<?php
namespace Favorites\Events;

use Favorites\Listeners\REST\UserFavoriteList;
use Favorites\Listeners\REST\FavoriteAdd;

class RegisterAPIEndpoints
{
	public function __construct()
	{
		$namespaces = 'favorites/v1';

		register_rest_route( $namespaces, '/posts', array(
			'methods' => 'GET',
			'callback' => array($this, 'getUserFavoriteList'),
			'args' => array(
				'siteid' => array(
					'description' => 'Specify a site ID if used in a multisite installation. Defaults to the current site/blog',
					'type' => 'number'
				)
			),
			'permission_callback' => function () {
				return is_user_logged_in();
			}
		));

		register_rest_route( $namespaces, '/posts/(?P<postid>\d+)', array(
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

	public function getUserFavoriteList($WP_REST_Request)
	{
		new UserFavoriteList($WP_REST_Request);
	}

	public function addFavorite($WP_REST_Request)
	{
		new FavoriteAdd($WP_REST_Request);
	}

}
