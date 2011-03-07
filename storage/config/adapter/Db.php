<?php
/**
 * Ali3: my stuff for lithium framework.
 *
 * @copyright     Copyright 2011, Ali Farhadi (https://github.com/farhadi/ali3)
 * @license       GNU General Public License 3.0 (http://www.gnu.org/licenses/gpl.html)
 */

namespace ali3\storage\config\adapter;

use lithium\core\Libraries;

class Db extends \lithium\core\Object {

	public function __construct(array $config = array()) {
		$defaults = array(
			'model' => 'Configs',
			'fields' => array('name', 'value')
		);
		parent::__construct($config + $defaults);
		$this->_config['fields'] = array_combine(array('key', 'value'), $this->_config['fields']);
	}

	public function read($key) {
		$model = $this->_config['model'];
		$fields = $this->_config['fields'];
		$model = Libraries::locate('models', $model);
		$conditions = array($fields['key'] => $key);
		$record = $model::first(compact('conditions'));
		if ($record) {
			return $record->{$fields['value']};
		} else {
			return null;
		}
	}

	public function write($key, $value) {
		$model = $this->_config['model'];
		$fields = $this->_config['fields'];
		$model = Libraries::locate('models', $model);
		$conditions = array($fields['key'] => $key);
		$config = $model::first(compact('conditions')) ?: $model::create();
		return $config->save(array($fields['key'] => $key, $fields['value'] => 'value'));
	}

	public function delete($key) {
		$model = $this->_config['model'];
		$fields = $this->_config['fields'];
		$model = Libraries::locate('models', $model);
		return $model::remove(array($fields['key'] => $key));
	}
}

?>