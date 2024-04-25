<?php
namespace sgpbm\api;

class Mailchimp
{
	private $apiEndpoint = 'https://<dc>.api.mailchimp.com/3.0';

	/**
	 * Create a new instance
	 * @param string $apiKey Your Mailchimp API key
	 * @throws \Exception
	 */
	public function __construct($apiKey)
	{
		$this->apiKey = $apiKey;

		if (!strpos($this->apiKey, '-')) {
			return false;
		}
		list(, $datacenter) = @explode('-', @$this->apiKey);

		$this->apiEndpoint = str_replace('<dc>', $datacenter, $this->apiEndpoint);
	}

	/**
	 * Make an HTTP GET request - for retrieving data
	 *
	 * @since 1.0.0
	 *
	 * @param   string $urlKey URL of the API request method
	 * @param   array $args Assoc array of arguments (usually your data)
	 * @param   int $timeout Timeout limit for request in seconds
	 *
	 * @return  array|false   Assoc array of API response, decoded from JSON
	 */
	public function get($urlKey, $args = array(), $timeout = 10)
	{
		return $this->makeRequest('get', $urlKey, $args, $timeout);
	}

	/**
	 * Make an HTTP POST request - for retrieving data
	 *
	 * @since 1.0.0
	 *
	 * @param   string $urlKey URL of the API request method
	 * @param   array $args Assoc array of arguments (usually your data)
	 * @param   int $timeout Timeout limit for request in seconds
	 *
	 * @return  array|false   Assoc array of API response, decoded from JSON
	 */
	public function post($urlKey, $args = array(), $timeout = 10)
	{
		return $this->makeRequest('post', $urlKey, $args, $timeout);
	}

	public function makeRequest($httpVerb, $method, $args = array(), $timeout = 10)
	{
		$url = $this->apiEndpoint.'/'.$method;

		$postArgs = array(
			'method' => $httpVerb,
			'headers' => array(
				'Accept' => 'application/vnd.api+json',
				'Content-Type' => 'application/vnd.api+json',
				'User-Agent' => 'mc4wp/4.1.15; WordPress/4.8.5; http://localhost/wordpress',
				'Authorization'=> "apikey ".$this->apiKey,
				'Accept-Language' => 'ru-RU,ru;q=0.8,en-US;q=0.6,en;q=0.4,hy;q=0.2'
			),
			'timeout' => 10,
			'sslverify' => true,
		);

		switch ($httpVerb) {
			case 'get':
				$query = http_build_query($args);
				$url = $url.'?'.$query;
				break;
			case 'post':
				$encodedArgs = json_encode($args);
				$postArgs['body'] = $encodedArgs;
				break;
		}

		$response = wp_remote_request($url, $postArgs);

		return json_decode(wp_remote_retrieve_body($response), true);
	}
}
