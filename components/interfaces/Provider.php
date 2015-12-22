<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/22/15
 * Time: 12:33 PM
 */

namespace TRS\AsyncNotification\components\interfaces;


interface Provider {
	/**
	 * @param string $template
	 * @param array $data
	 */
	public function __construct($template, array $data = []);

	/**
	 * @param array $recipient
	 * @return void
	 */
	public function addTo(array $recipient);

	/**
	 * @param $path
	 * @return void
	 */
	public function attach($path);

	/**
	 * @param $path
	 * @return void
	 */
	public function embed($path);

	/**
	 * @param array $data
	 * @return void
	 */
	public function addData(array $data);

	/**
	 * @return void
	 */
	public function send();
} 