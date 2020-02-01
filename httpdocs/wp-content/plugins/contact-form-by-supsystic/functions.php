<?php
/**
 * Set first leter in a string as UPPERCASE
 * @param string $str string to modify
 * @return string string with first Uppercase letter
 */
if(!function_exists('strFirstUp')) {
    function strFirstUp($str) {
        return strtoupper(substr($str, 0, 1)).strtolower(substr($str, 1, strlen($str)));
    }
}
/**
 * Deprecated - class must be created
 */
if(!function_exists('dateToTimestampCfs')) {
    function dateToTimestampCfs($date) {
        if(empty($a))
            return false;
        $a = explode(CFS_DATE_DL, $date);
        return mktime(0, 0, 0, $a[1], $a[0], $a[2]);
    }
}
/**
 * Generate random string name
 * @param int $lenFrom min len
 * @param int $lenTo max len
 * @return string random string with length from $lenFrom to $lenTo
 */
if(!function_exists('getRandName')) {
    function getRandName($lenFrom = 6, $lenTo = 9) {
        $res = '';
        $len = mt_rand($lenFrom, $lenTo);
        if($len) {
            for($i = 0; $i < $len; $i++) {
                $res .= chr(mt_rand(97, 122));	/*rand symbol from a to z*/
            }
        }
        return $res;
    }
}
if(!function_exists('importCfs')) {
    function importCfs($path) {
        if(file_exists($path)) {
            require($path);
            return true;
        }
        return false;
    }
}
if(!function_exists('setDefaultParams')) {
    function setDefaultParams($params, $default) {
        foreach($default as $k => $v) {
            $params[$k] = isset($params[$k]) ? $params[$k] : $default[$k];
        }
        return $params;
    }
}
if(!function_exists('importClassCfs')) {
    function importClassCfs($class, $path = '') {
        if(!class_exists($class)) {
            if(!$path) {
				$classFile = $class;
				if(strpos(strtolower($classFile), CFS_CODE) !== false) {
					$classFile = preg_replace('/'. CFS_CODE. '/i', '', $classFile);
				}
                $path = CFS_CLASSES_DIR. $classFile. '.php';
			}
            return importCfs($path);
        } else {    //If such class already exist - let's check does this is our plugin class or someone else
            /*if(class_exists('ReflectionClass')) {   //ReflectionClass supported begining from php5
                $reflection = new ReflectionClass($class);
                $classFile = $reflection->getFileName();
                if(strpos($classFile, CFS_DIR) === false) {   //Class is not in our plugin directory
                    $conflictWith = substr($classFile, strpos($classFile, 'plugins') + strlen('plugins'. DS));
                    $conflictWith = substr($conflictWith, 0, strpos($conflictWith, DS));
                    $plugins = get_option('active_plugins');
                    if(!empty($plugins)) {
                        for($i = 0; $i < count($plugins); $i++) {
                            if(strpos($plugins[$i], CFS_PLUG_NAME) !== false) {   //Let's remove our plugin from list of active plugins
                                unset($plugins[$i]);
                            }
                        }
                        update_option( 'active_plugins', $plugins );
                    }
                    exit('Sorry, but we have conflict with class name <b style="color: red;">'. $class. '</b> in one of your already installed plugins <b style="color: red;">'. $conflictWith. '</b> located at '. $classFile. '. This means that you can not have both two plugins at one time.');
                }
            }*/
        }
        return false;
    }
}
/**
 * Check if class name exist with prefix or not
 * @param strin $class preferred class name
 * @return string existing class name
 */
if(!function_exists('toeGetClassNameCfs')) {
    function toeGetClassNameCfs($class) {
        $className = '';
		if(class_exists($class. strFirstUp(CFS_CODE))) 
			$className = $class. strFirstUp(CFS_CODE);
		else if(class_exists(CFS_CLASS_PREFIX. $class)) 
            $className = CFS_CLASS_PREFIX. $class;
		else
            $className = $class;
        return $className;
    } 
}
/**
 * Create object of specified class
 * @param string $class class that you want to create
 * @param array $params array of arguments for class __construct function
 * @return object new object of specified class
 */
if(!function_exists('toeCreateObjCfs')) {
    function toeCreateObjCfs($class, $params) {
        $className = toeGetClassNameCfs($class);
        $obj = NULL;
        if(class_exists('ReflectionClass')) {
            $reflection = new ReflectionClass($className);
			try {
				$obj = $reflection->newInstanceArgs($params);
			} catch (ReflectionException $e) {	// If class have no constructor
				$obj = $reflection->newInstanceArgs();
			}
        } else {
            $obj = new $className();
            call_user_func_array(array($obj, '__construct'), $params);
        }
        return $obj;
    }
}
/**
 * Redirect user to specified location. Be advised that it should redirect even if headers alredy sent.
 * @param string $url where page must be redirected
 */
if(!function_exists('redirectCfs')) {
    function redirectCfs($url) {
        if(headers_sent()) {
            echo '<script type="text/javascript"> document.location.href = "'. $url. '"; </script>';
        } else {
            header('Location: '. $url);
        }
        exit();
    }
}
if(!function_exists('in_array_array')) {
    function in_array_array($needle, $haystack) {
        if(is_array($needle)) {
            foreach($needle as $n) {
                if(in_array($n, $haystack))
                    return true;
            }
            return false;
        } else
            return in_array_array($needle, $haystack);
    }
}
if(!function_exists('json_encode_utf_normal')) {
    function json_encode_utf_normal($value) {
        if (is_int($value)) {
            return (string)$value;   
        } elseif (is_string($value)) {
	        $value = str_replace(array('\\', '/', '"', "\r", "\n", "\b", "\f", "\t"), 
	                             array('\\\\', '\/', '\"', '\r', '\n', '\b', '\f', '\t'), $value);
	        $convmap = array(0x80, 0xFFFF, 0, 0xFFFF);
	        $result = "";
	        for ($i = strlen($value) - 1; $i >= 0; $i--) {
	            $mb_char = substr($value, $i, 1);
                $result = $mb_char . $result;
	        }
	        return '"' . $result . '"';                
        } elseif (is_float($value)) {
            return str_replace(",", ".", $value);         
        } elseif (is_null($value)) {
            return 'null';
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif (is_array($value)) {
            $with_keys = false;
            $n = count($value);
            for ($i = 0, reset($value); $i < $n; $i++, next($value)) {
                        if (key($value) !== $i) {
			      $with_keys = true;
			      break;
                        }
            }
        } elseif (is_object($value)) {
            $with_keys = true;
        } else {
            return '';
        }
        $result = array();
        if ($with_keys) {
            foreach ($value as $key => $v) {
                $result[] = json_encode_utf_normal((string)$key) . ':' . json_encode_utf_normal($v);    
            }
            return '{' . implode(',', $result) . '}';                
        } else {
            foreach ($value as $key => $v) {
                $result[] = json_encode_utf_normal($v);    
            }
            return '[' . implode(',', $result) . ']';
        }
    } 
}
/**
 * Prepares the params values to store into db
 * 
 * @param array $d $_POST array
 * @return array
 */
if(!function_exists('prepareParamsCfs')) {
	function prepareParamsCfs(&$d=array(), &$options = array()) {
		if (!empty($d['params'])) {
			if (isset($d['params']['options'])) {
				$options = $d['params']['options'];
				//unset($d['params']['options']);
			}
			if (is_array($d['params'])) {
				$params = utilsCfs::jsonEncode($d['params']);
				$params = str_replace(array('\n\r', "\n\r", '\n', "\r", '\r', "\r"), '<br />', $params);
				$params = str_replace(array('<br /><br />', '<br /><br /><br />'), '<br />', $params);
				$d['params'] = $params;
			}
		} elseif(isset($d['params'])) {
			$d['params']['attr']['class'] = '';
			$d['params']['attr']['id'] = '';
			$params = utilsCfs::jsonEncode($d['params']);
			$d['params'] = $params;
		}
		if(empty($options)) 
			$options = array('value' => array('EMPTY'), 'data' => array());
		if(isset($d['code'])) {
			if ($d['code'] == '') {
				$d['code'] = prepareFieldCodeCfs($d['label']).'_'.rand(0, 9999999);
			}
		}
		return $d;
	}
}
if(!function_exists('prepareFieldCodeCfs')) {
	function prepareFieldCodeCfs($string) {   
		$string = preg_replace("/[^a-zA-Z0-9\s]/"," ",$string);
		$string = preg_replace("/\s+/", " ", $string);
		$string = preg_replace('/ /','',$string);

		$code = substr($string, 0, 8);
		$code = strtolower($code);
		if ($code == '') {
			$code = 'field_'.date('dhis');
		}
		return $code;
	}
}
/**
 * Recursive implode of array
 * @param string $glue imploder
 * @param array $array array to implode
 * @return string imploded array in string
 */
if(!function_exists('recImplode')) {
    function recImplode($glue, $array) {
        $res = '';
        $i = 0;
        $count = count($array);
        foreach($array as $el) {
            $str = '';
            if(is_array($el))
                $str = recImplode('', $el);
            else
                $str = $el;
            $res .= $str;
            if($i < ($count-1))
                $res .= $glue;
            $i++;
        }
        return $res;
    }
}
if(!function_exists('toeObjectToArray')) {
    function toeObjectToArray($data) {
        if ((! is_array($data)) and (! is_object($data))) return $data; //$data;
        $result = array();
        $data = (array) $data;
        foreach ($data as $key => $value) {
            if (is_object($value)) $value = (array) $value;
            if (is_array($value)) 
                $result[$key] = toeObjectToArray($value);
            else
                $result[$key] = $value;
        }
        return $result;
    }
}
/**
 * Correct apply array_map even if array contains sub-arrays
 * @param array $array - input array
 * @return array - result array with array_map applied
 */
if(!function_exists('toeMultArrayMap')) {
    function toeMultArrayMap($callback, $array) {
        if(is_array($array)) {
            foreach($array as $k => $v) {
                if(is_array($v)) {
                    $array[ $k ] = toeMultArrayMap($callback, $v);
                } else {
                    $array[ $k ] = call_user_func($callback, $v);
                }
            }
        } else {
            $array = call_user_func($callback, $array);
        }
        return $array;
    }
}
/**
 * Twig require this function, but it is present not on all servers
 */
if(!function_exists('hash')) {
	function hash($method, $data) {
		return md5($data);
	}
}
if(!function_exists('ctype_alpha')) {
	function ctype_alpha($text) {
		return (bool) preg_match('/[^\pL]+/', $text);
	}
}
/*****/