<?php
/**
 * Library to work with PostgreSQL
 *
 * 
 * @author Oleksii Dovzhenko <zirkonus@gmail.com>
 * @copyright 2010
 * @license http://www.php.net/license/3_01.txt PHP License 3.01
 */

class pgsql {

	private $id;

	public function __construct($host, $port, $dbname, $dbuser, $dbpassword){
		$i = 1;
		while (!$this->id = @pg_pconnect("host=".$host." port=".$port." dbname=".$dbname." user=".$dbuser." password=".$dbpassword)) {
			if ($i == 5) break;
			$i++;
			sleep(1);
			continue;
		}
	}

	public function close() {
		if ($this->id) pg_close($this->id);
	}

	public function get($sql, $params=array()) {
		list($sql, $params) = $this->_prepare_params($sql, $params);
		ksort($params);
		if (!empty($sql)) {
			$result = pg_query_params($this->id, $sql, $params);
			if ($result) {
				if (pg_num_rows($result) > 0) {
					$back = array();
					$name = array();
					for ($k=0; $k<pg_num_fields($result); $k++)
						$name[$k] = pg_field_name($result, $k);
					$p = 0;
					while ($row = pg_fetch_row($result)) {
						for ($i=0; $i<count($row); $i++)
							$back[$p][$name[$i]] = $row[$i];
						$p++;
					}
					return $back;
				}
			}
		}
		return false;
	}

	public function get_row($sql, $params=array()){
		$data = $this->get($sql, $params);
		if ($data) return $data[0];
		return false;
	}

	public function get_val($sql, $params=array()){
		$data = $this->get_row($sql, $params);
		if ($data) return array_shift($data);
		else false;
	}

	public function put($sql, $params=array()) {
		list($sql, $params) = $this->_prepare_params($sql, $params);
		ksort($params);
		if (!empty($sql)) {
			$result = pg_prepare($this->id, "", $sql);
			$result = pg_execute($this->id, "", $params);
			if ($result) {
				if (pg_affected_rows($result)) return true;
			}
		}
		return false;
	}

	private function _prepare_params($q, $params) {
		if (count($params) > 0) {
			$old_q = $q;
			$i = $c = 19999;
			$count = 0;
			$t = array();
			$pref = ":ax_param_";
			foreach ($params as $k=>$v) {
				if (intval($k) > 0) $q = preg_replace('/\$'.$k.'(?![0-9])/', $pref.$i, $q, -1, $count);
				else $q = preg_replace('/:'.$k.'(?![a-zA-Z0-9_])/', $pref.$i, $q, -1, $count);
				if ($count > 0) {
					$t[$pref.$i] = $v;
					$count = 0;
					$i--;
				}
			}
			$i = 1;
			$search = array();
			$replace = array();
			$p = array();
			if (isset($t[$pref.$c])) {
				foreach ($t as $k=>$v) {
					$search[] = $k;
					$replace[] = '$'.$i;
					$p[$i] = $v;
					$i++;
				}
				$q = str_replace($search, $replace, $q);
				return array($q, $p);
			} else return array($old_q, $p);
		} else return array($q, $params);
	}

}
?>