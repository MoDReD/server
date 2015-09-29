<?php
/**
 * @author Jörn Friedrich Dreyer <jfd@butonic.de>
 * @author Morris Jobke <hey@morrisjobke.de>
 * @author Robin Appelman <icewind@owncloud.com>
 * @author Thomas Müller <thomas.mueller@tmit.eu>
 *
 * @copyright Copyright (c) 2015, ownCloud, Inc.
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OC\Session;

/**
 * Class Internal
 *
 * store session data in an in-memory array, not persistent
 *
 * @package OC\Session
 */
class Memory extends Session {
	protected $data;

	public function __construct($name) {
		//no need to use $name since all data is already scoped to this instance
		$this->data = array();
	}

	/**
	 * @param string $key
	 * @param integer $value
	 */
	public function set($key, $value) {
		$this->validateSession();
		$this->data[$key] = $value;
	}

	/**
	 * @param string $key
	 * @return mixed
	 */
	public function get($key) {
		if (!$this->exists($key)) {
			return null;
		}
		return $this->data[$key];
	}

	/**
	 * @param string $key
	 * @return bool
	 */
	public function exists($key) {
		return isset($this->data[$key]);
	}

	/**
	 * @param string $key
	 */
	public function remove($key) {
		$this->validateSession();
		unset($this->data[$key]);
	}

	public function clear() {
		$this->data = array();
	}

	/**
	 * Helper function for PHPUnit execution - don't use in non-test code
	 */
	public function reopen() {
		$this->sessionClosed = false;
	}

	/**
	 * In case the session has already been locked an exception will be thrown
	 *
	 * @throws \Exception
	 */
	private function validateSession() {
		if ($this->sessionClosed) {
			throw new \Exception('Session has been closed - no further changes to the session are allowed');
		}
	}
}
