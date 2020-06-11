<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View as View;

class PostController extends Controller {

	/**
	 * Guzzle client for requests.
	 *
	 * @since 1.0.0
	 *
	 * @var \GuzzleHttp\Client
	 */
	private $client;

	/**
	 * Guzzle Headers
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $headers = [];

	/**
	 * Site URL
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	private $site_url = '';

	/**
	 * Site Categories
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $categories = [];

	/**
	 * Constructor
	 *
	 * @author Scott Anderson <scott@thriftydeveloper.com>
	 * @since  NEXT
	 */
	public function __construct() {
		$this->site_url = env( 'SITE_URL' );
		$this->client = new \GuzzleHttp\Client();
		$this->authenticate_user();
		$this->categories = $this->get_categories();
	}

	/**
	 * Return editor form.
	 *
	 * @author Scott Anderson <scott@thriftydeveloper.com>
	 * @since  NEXT
	 * @return view
	 */
	public function editor () {
		return view( 'editor', [ 
			'categories' => $this->categories,
		 ]);
	}

	/**
	 * Publish post to website.
	 *
	 * @author Scott Anderson <scott@thriftydeveloper.com>
	 * @since  NEXT
	 * @param Request $request Request object containing form fields.
	 */
	public function upload_post ( Request $request ) {
		$post_id      = json_decode( $this->client->post( $this->site_url . '/wp-json/wp/v2/posts/', [ 'headers' => $this->headers, 'form_params' => $this->format_post( $request ) ] )->getBody() )->id;
		return redirect()->route( 'editor' );
	}

	/**
	 * Get's Bearer Token for Site.
	 *
	 * @author Scott Anderson <scott@thriftydeveloper.com>
	 * @since  NEXT
	 */
	private function authenticate_user() : void {
		$username = env( 'SITE_USERNAME' );
		$password = env( 'SITE_PASSWORD' );

		$response = json_decode( $this->client->post( $this->site_url . '/wp-json/api-bearer-auth/v1/login', [ 
			\GuzzleHttp\RequestOptions::JSON => [
				'username' => $username,
				'password' => $password,
			],
		] )->getBody() );

		$this->headers = [
			'Authorization' => 'Bearer ' . $response->access_token,
			'Accept'        => 'application/json',
		];

	}

	/**
	 * Return all categories from site.
	 *
	 * @author Scott Anderson <scott@thriftydeveloper.com>
	 * @since  NEXT
	 * @return array
	 */
	private function get_categories() : array {
		$categories = [];

		$raw_categories = json_decode( $this->client->get( $this->site_url . '/wp-json/wp/v2/categories/', [ 'headers' => $this->headers, 'form_params' ] )->getBody() );
		foreach ( $raw_categories as $category ) {
			$categories[] = [
				'id'   => $category->id,
				'name' => $category->name,
			];
		}

		return $categories;
	}

	/**
	 * Formats incoming request data into proper api format.
	 *
	 * @author Scott Anderson <scott@thriftydeveloper.com>
	 * @since  NEXT
	 * @param  Request $request Request form date to format for api.
	 * @return array
	 */
	private function format_post( Request $request ) : array {

		$post_format = [
			'title'      => $request['post_title'],
			'content'    => $request['post_content'],
			'status'     => 'publish',
			'date'       => $request['publish_date'],
			'categories' => $request['post_categories'],
		];

		return $post_format;
	}


}
