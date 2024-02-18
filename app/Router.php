<?php

namespace App;

class Router
{
	private array $routes = [];

	/** $_GET routes method
	 * @param string $path
	 * @param $callback
	 * @return void
	 */
	public function get(string $path, $callback)
	{
		$this->routes['GET'][$path] = $callback;
	}

	/** $_POST routes method
	 * @param string $path
	 * @param $callback
	 * @return void
	 */
	public function post(string $path, $callback)
	{
		$this->routes['POST'][$path] = $callback;
	}

	/** DELETE routes method
	 * @param string $path
	 * @param $callback
	 * @return void
	 */
	public function delete(string $path, $callback)
	{
		$this->routes['DELETE'][$path] = $callback;
	}

	public function handle(string $method, string $uri)
	{
		// Check, if routes is register
		if (array_key_exists($method, $this->routes)) {
			foreach ($this->routes[$method] as $route => $callback) {
				// change dynamic params routes
				$regexRoute = preg_replace('/\/\{([a-zA-Z0-9_]+)\}/', '/([^\/]+)', $route);

				//
				if (preg_match("#^$regexRoute$#", $uri, $matches)) {
					// check, new route in routesList
					$args = array_slice($matches, 1);
					// send to route dynamic params
					call_user_func_array($callback, $args);
					return;
				}
			}
		}

		// if rotes not exist- rev 404 error
		http_response_code(404);
		echo "404 - Not Found";
	}
}
