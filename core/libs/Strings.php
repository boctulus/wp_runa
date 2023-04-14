<?php

/*
    @author  Pablo Bozzolo boctulus@gmail.com
*/

namespace boctulus\SW\core\libs;

class Strings 
{	
	/*
		Extrae la parte numerica de una cadena que contenga una cantidad
		y la castea a un float
	*/
	static function parseCurrency(?string $num, $thousand_sep = null, $decimal_sep = null){
		if ($thousand_sep != null){
			static::replace($thousand_sep, '', $num);
		}

		preg_match('![0-9'.$decimal_sep.']+!', $num, $matches);

		$result = $matches[0] ?? false;

		if ($result !== false && $decimal_sep !== null && $decimal_sep !== '.'){
			static::replace($decimal_sep, '.', $result);
		}

		return $result;
	}

	/*
		Interpreta un string como un número entero con la posibilidad de que contenga separador de miles
	*/
	static function parseInt(?string $num, string $thousand_sep = '.'){
		$num = trim($num);

		if (!preg_match('/(^[-0-9][0-9.]*$)/', $num, $matches)){
			return false;
		}

		if (!Strings::contains($thousand_sep, $num)){
			return (int) $num;
		}

		$pa = explode($thousand_sep, $num);
		
		$ct = count($pa);
		for ($i=1; $i<$ct; $i++){
			if (strlen($pa[$i]) != 3){
				return false;
			}
		}

		return (int) implode('', $pa);
	}

	static function parseIntOrFail(?string $num, string $thousand_sep = '.'){
		if (static::parseInt($num, $thousand_sep) === false){
			throw new \Exception("String '$num' is not an Integer");
		}
	}

	/*
		Intenta hacer un casting de un string numerico a float 

		Evita castear null o false a 0.0
	*/
	static function convertIntoFloat(?string $num = null){
		if ($num === null){
			return null;
		}
	
		if ($num === false){
			return false;
		}

		if (!is_numeric($num)){
			return false;
		}

		return (float) $num;
	}


	static function convertIntoFloatOrFail(?string $num){
		if ($num === null){
			throw new \InvalidArgumentException("'$num' can not be null");
		}
	
		if ($num === false){
			throw new \InvalidArgumentException("'$num' can not be false");
		}

		if (!is_numeric($num)){
			throw new \Exception("Conversion for '$num' fails");
		}

		return (float) $num;
	}

	static function formatNumber($x, string $locale = "it-IT"){
		$nf = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
	
		if ($x > 1000000){
			$nf->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 0);
		} else {
			$nf->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 2);
		}
	
		return $nf->format($x); 
	}	

	/*
		Reemplazo para la función nativa explode()
	*/
	static function explode(?string $str, string $separator = ','){
		return Arrays::trimArray(explode($separator, rtrim(trim($str), $separator)));
	}

	/*
		Cada línea la convierte en un <p>párrafo</p>
	*/
	static function paragraph(?string $str){
		return '<p>' . implode('</p><p>', array_filter(explode("\n", $str))) . '</p>';
	}

	// N-ésimo segmento luego de hacer un explode por $separator
	static function segment(?string $string, string $separator,  int $position){
		$array = explode($separator, $string);

		if (isset($array[$position])){
			return $array[$position];
		}

		return false;
	}

	// N-ésimo segmento luego de hacer un explode por $separator
	static function segmentOrFail(?string $string, string $separator,  int $position){
		$array = explode($separator, $string);

		if (count($array) === 1 && $position > 0){
			throw new \Exception("There is no segments after explode '$string'");
		}

		if (!isset($array[$position])){
			throw new \Exception("There is no segment in position $position after explode '$string'");
		}

		return $array[$position];
	}

	// String antes de la N-ésima ocurrencia del substring
	static function before(?string $string, string $substr, $occurrence = 1){
		$parts = explode($substr, $string, $occurrence +1);

		return $parts[$occurrence -1];
	}

	// String antes de la primera ocurrencia del substring
	static function first(?string $string, string $substr){
		$parts = explode($substr, $string, 2);

		return $parts[0];
	}

	// String después de la primera ocurrencia del substring
	static function after(?string $string, string $substr){
		$parts = explode($substr, $string, 2);

		if (!isset($parts[1])){
			return false;
		}

		return $parts[1];
	}

	// String después de la primera ocurrencia del substring
	static function afterOrFail(?string $string, string $substr){
		$parts = explode($substr, $string, 2);

		if (!isset($parts[1])){
			throw new \Exception("There is nothing after '$substr' for '$string'");
		}

		return $parts[1];
	}

	// String después de la última ocurrencia del substring (que podría ser empty)
	static function last(?string $str, string $substr){
		$parts = explode($substr, $str);

		return $parts[count($parts)-1];
	}

	// String antes de la última ocurrencia del substring
	static function beforeLast(?string $string, string $substr){
		$parts = explode($substr, $string);

		return implode($substr, array_slice($parts, 0, count($parts)-1));
	}

	static function untilLast(?string $string, string $substr){
		$parts = explode($substr, $string);

		return implode($substr, array_slice($parts, 0, count($parts)-1)) . $substr;
	}


	// Segment before the last one
	static function beforeLastSegment(?string $string, string $substr){
		return static::last(static::beforeLast($string, $substr), $substr);
	}

	static function trim($dato = null, bool $even_null = true, bool $auto_cast_numbers = true){
		if ($dato === null){
			if (!$even_null){
				throw new \InvalidArgumentException("Dato can not be null");
			}

			return '';
		}

		if ($auto_cast_numbers){
			if (is_int($dato) || is_float($dato) || is_double($dato)){
				$dato = (string) $dato;
			}
		}

		return trim($dato);
	}

	/*
		Auto-detecta retorno de carro en un string
	*/
	static function carriageReturn(?string $str){
		$qty_rn = substr_count($str, "\r\n");

		if ($qty_rn != 0){
			return "\r\n";
		}

		$qty_r  = substr_count($str, "\r");

		if ($qty_r != 0){
			return "\r";
		}

		$qty_n  = substr_count($str, "\n");
		
		if ($qty_n != 0){
			return "\n";
		}

		return null;
	}
	

	/*
		Remueve del forma eficiente un substring del inicio de una cadena

		- Precaucion: remueve cualquier caracter, no solo espacios, tabs, etc

		- Podria limitarse tambien a los primeros n-caracteres tambien
	*/
	static function lTrim(?string $substr, ?string $str = null){
		if (empty($str)){
			return '';
		}

		$len_sb = strlen($substr);
		$len_ss = strlen($str);

		if ($len_sb > $len_ss){
			return $str;
		}

		if (substr($str, 0, $len_sb) == $substr){
			return substr($str, $len_sb);
		}

		return $str;
	} 

	static function rTrim(?string $needle, string $haystack)
    {
        if (substr($haystack, -strlen($needle)) === $needle){
			return substr($haystack, 0, - strlen($needle));
		}
		return $haystack;
    }

	// alias
	static function removeBeginning($substr, string $string){
		return static::lTrim($substr, $string);
	}

	// alias
	static function removeEnding(?string $substr, string $string){
		return static::rTrim($substr, $string);
	}

	/*
		Ubica la primera ocurrencia de $substr en $string 
		y se come retornos de carro, espacios,... 
		... hasta que se encuentra con algo distinto a derecha
	*/
	static function trimAfter(?string $substr, string $string, int $offset = 0, ?string $chars = " \t\r\n\0\x0B", int $extra_cr = 0){
		if ($string === ''){
			return $string;
		}
		
		$pos = strpos($string, $substr, $offset);

		if ($pos === false){
			return $string;
		}

		$pos += strlen($substr);

		$l = static::left($string,  $pos);
		$r = static::right($string, $pos);

		if ($chars === null){
			$chars = " \t\r\n\0\x0B";
		}

		return $l . str_repeat("\r\n", $extra_cr) . ltrim($r, $chars);
	}

	static function trimEmptyLinesAfter(?string $substr, string $string, int $offset = 0, ?string $chars = " \t\r\n\0\x0B", int $extra_cr = 0){
		if ($string === ''){
			return $string;
		}
		
		$pos = strpos($string, $substr, $offset);

		if ($pos === false){
			return $string;
		}

		$pos += strlen($substr);

		$l = static::left($string,  $pos);
		$r = static::right($string, $pos);

		if ($chars === null){
			$chars = " \t\r\n\0\x0B";
		}
		
		// toca auto-detectar tipo de retorno de carro 
		$cr = static::carriageReturn($string);

		$lines = explode($cr, $r);

		foreach ($lines as $ix => $line){
			if (empty(trim($line, $chars))){
				unset($lines[$ix]);
			} else {
				break;
			}
		}

		return $l . str_repeat($cr, $extra_cr) . implode($cr, $lines);
	}

	static function trimEmptyLinesBefore(?string $substr, string $string, int $offset = 0, ?string $chars = " \t\r\n\0\x0B", int $extra_cr = 0){
		if ($string === ''){
			return $string;
		}
		
		$pos = strpos($string, $substr, $offset);

		if ($pos === false){
			return $string;
		}

		$l = static::left($string,  $pos);
		$r = static::right($string, $pos);

		if ($chars === null){
			$chars = " \t\r\n\0\x0B";
		}
		
		// toca auto-detectar tipo de retorno de carro 
		$cr = static::carriageReturn($string);

		$lines = explode($cr, $l);

		$lines = array_reverse($lines);

		foreach ($lines as $ix => $line){
			if (empty(trim($line, $chars))){
				unset($lines[$ix]);
			} else {
				break;
			}
		}

		$lines = array_reverse($lines);

		return  implode($cr, $lines) . str_repeat($cr, $extra_cr) . $r;
	}


	/*
		Remueve el sustring entre $startingWith y $endingWith
	*/
	static function removeSubstring(?string $startingWith, string $endingWith, string $string){
		if (empty($string)){
			return $string;
		}

		$ini = strpos($string, $startingWith);

		if ($ini === false){
			return $string;
		}

		$end = strpos($string, $endingWith, $ini);

		if ($end === false){
			return $string;
		}

		return substr($string, 0, $ini) . substr($string, $end + strlen($endingWith));
	}

	/*
		Apply tabs to some string
	*/
	static function tabulate(?string $str, int $tabs, ?int $first = null, ?int $last = null){
		$lines = explode(PHP_EOL, $str);

		$cnt = count($lines);
        foreach($lines as $ix => $line){
			if ($first !== null && $ix == 0){
				if ($first > 0){
					$lines[$ix] = str_repeat("\t", $first) . $line;
				}  else {
					$lines[$ix] = substr($line, abs($first));
				}
				continue;
			} 

			if ($last !== null && $ix == $cnt-1){
				if ($last < 0){
					$lines[$ix] = substr($line, abs($last));
				}  else {
					$lines[$ix] = str_repeat("\t", $last) . $line;
				}

				continue;
			}

			if ($tabs < 0){
				$lines[$ix] = substr($line, abs($tabs));
			}  else {
				$lines[$ix] = str_repeat("\t",$tabs) . $line;
			}
        }

        $str = implode(PHP_EOL, $lines);

		return $str;
	}


	/*
		Returns $s1 - $s2
	*/
	static function substract(?string $s1, string $s2){
		$s2_len = strlen($s2);
		$s1_len = strlen($s1);

		if ($s2_len > $s1_len){
			return;
		}

		if (!self::startsWith($s2, $s1)){
			return;
		}

		return substr($s1, $s2_len);
	}

	// alias
	static function diff(?string $s1, string $s2){
		return static::substract($s1, $s2);
	}

	static function trimArray(Array $strings){
		return array_map('trim', $strings);
	}

	static function trimMultiline(?string $str){
		$lines = explode("\n", $str);
		$arr   = static::trimArray($lines);
		return implode("\n", $arr);
	}

	static function trimFromLastOcurrence(?string $substr, string $str){
		$pos = strrpos($str, $substr);

		if ($pos === false){
			return $str;
		}

		return substr($str, 0, $pos);
	}

	/*
		Returns false if fails

		$pattern can be an Array
		$result_position can be an Array 
	*/
	static function match(?string $str, $pattern, $result_position = null){
		if (is_array($pattern)){
			if (is_null($result_position)){
				$result_position = 1;
				$is_pos_array    = false;
			} else {
				$is_pos_array = is_array($result_position);

				if ($is_pos_array){
					if (count($result_position) != count($pattern)){
						throw new \InvalidArgumentException("Number of patterns should be the same as result positions");
					}
				} 
			}

			foreach ($pattern as $ix => $p){
				if (preg_match($p, $str, $matches)){
					if (is_array($result_position)){
						$pos = $result_position[$ix];
					} else {
						$pos = $result_position;
					}

					if (isset($matches[$pos])){
						return $matches[$pos];
					}
				}
			}
		} else {
			if (is_null($result_position)){
				$result_position = 1;
			}			

			if (preg_match($pattern, $str, $matches)){
				if (!isset($matches[$result_position])){
					return false;
				}

				return $matches[$result_position];
			}
		}

		return false;
	}

	static function matchOrFail(?string $str, string $pattern, string $error_msg = null) { 
		if (preg_match($pattern, $str, $matches)){			
			return $matches[1];
		}

		if (empty($error_msg)){
			$error_msg = "String $str does not match with $pattern";
		}

		throw new \Exception($error_msg);
	}

	static function ifMatch(?string $str, $pattern, callable $fn_success, callable $fn_fail = NULL){
		if (preg_match($pattern, $str, $matches)){
			return call_user_func($fn_success, $matches);
		} else if (is_callable($fn_fail)){
			return call_user_func($fn_fail, $matches);
		} else {
			return $matches;
		}
	}

	/*
        Tipo "preg_match()" destructivo

		Va extrayendo substrings que cumplen con un patron mutando la cadena pasada por referencia.
		
		Aplica solo la primera ocurrencia *
		
		En caso de entregarse un callback, se aplica sobre la salida.
	*/
	
    static function slice(string &$str, string $pattern, callable $output_fn = NULL) {
		if (!preg_match('|\((.*)\)|', $pattern)){
			throw new \Exception("Invalid regex expression '$pattern'. It should contains a (group)");
		}

        $ret = null;
        if (preg_match($pattern,$str,$matches)){
            $str = self::replaceFirst($matches[1], '', $str);
            $ret = $matches[1];
        }

        if ($output_fn != NULL){
            $ret = call_user_func($output_fn, $ret);
        }
     
     	return $ret;   
	}


    /*
        preg_match destructivo

        Similar a slice() pero aplica a todas las ocurrencias y no acepta callback.
     */
    static function sliceAll(string &$str, string $pattern) {
        if (preg_match($pattern,$str,$matches)){
            $str = self::replaceFirst($matches[1], '', $str);
            
            return array_slice($matches, 1);
        }
    }

	static function getParamRegex(?string $param_name, ?string $arg_expr = '[a-z0-9A-Z_-]+'){
		$equals = !is_null($arg_expr) ? '[=|:]' : '';		
		return '/^--'.$param_name. $equals . '('.$arg_expr.')$/';
	}

	/*
		$param_name can be string | Array
	*/
	static function matchParam(?string $str, $param_name, ?string $arg_expr = '[a-z0-9A-Z_-]+'){

		if (is_array($param_name)){
			$patt = [];
			foreach ($param_name as $p){
				$patt[] = Strings::getParamRegex($p, $arg_expr);
			}	
		} else {
			$patt =	Strings::getParamRegex($param_name, $arg_expr);
		}

		$res = Strings::match($str, $patt, 1);

		if ($arg_expr === null){
			return ($res !== false); 
		}

		return $res;		
	}

	/*
		Wrap target with delimeter(s)
	*/
	static function enclose($target, string $delimeter = "'", $delimeter2 = null){
		if (empty($delimeter2)){
			$delimeter2 = $delimeter;
		}

		if (is_array($target)) {
			return array_map(function($e) use ($delimeter, $delimeter2){
				return "{$delimeter}$e{$delimeter2}";
			}, $target);
		} else {
			return "{$delimeter}$target{$delimeter2}";
		}
	}
	
	static function backticks($target){
		return static::enclose($target, '`');
	}

	/*
		CamelCase to snake_case
	*/
	static function camelToSnake(?string $name, string $char = '_'){
		$len = strlen($name);

		if ($len== 0)
			return NULL;

		$conv = strtolower($name[0]);
		for ($i=1; $i<$len; $i++){
			$ord = ord($name[$i]);
			if ($ord >=65 && $ord <= 90){
				$conv .= $char . strtolower($name[$i]);		
			} else {
				$conv .= $name[$i];	
			}					
		}		
	
		if ($name[$len-1] == $char){
			$name = substr($name, 0, -1);
		}
	
		return $conv;
	}

	/*
		snake_case to CamelCase
	*/
	static function snakeToCamel(?string $str, bool $force = false){
		if ($force || static::isAllCaps($str)){
			return $str;
		}

        return implode('',array_map('ucfirst',explode('_', $str)));
    }

	static function isAllCaps(?string $str){
		return strtoupper($str) === $str;
	}

    static function startsWith(?string $substr, ?string $text, bool $case_sensitive = true)
	{
		if (empty($text)){
			return;
		}

		if (!$case_sensitive){
			$text = strtolower($text);
			$substr = strtolower($substr);
		}

        $length = strlen($substr);
        return (substr($text, 0, $length) === $substr);
    }

    static function endsWith(?string $substr, string $text, bool $case_sensitive = true)
	{
		if (!$case_sensitive){
			$text = strtolower($text);
			$substr = strtolower($substr);
		}

        return substr($text, -strlen($substr))===$substr;
    }

	/*
		Acomodar al órden de parámetros de PHP 8 con str_contains() 

		y corregir dependencias claro.
	*/
	static function contains(?string $substr, string $text, bool $case_sensitive = true)
	{
		if (!$case_sensitive){
			$text = strtolower($text);
			$substr = strtolower($substr);
		}

		return ($substr !== '' &&  mb_strpos($text, $substr) !== false);
	}

	static function containsAny(Array $substr, $text, $case_sensitive = true)
	{
		foreach ($substr as $s){
			if (self::contains($s, $text, $case_sensitive)){
				return true;
			}
		}
		return false;
	}


	/*	
		Verifica si la palabra está contenida en el texto.

		Works in Hebrew and any other unicode characters
		Thanks https://medium.com/@shiba1014/regex-word-boundaries-with-unicode-207794f6e7ed
		Thanks https://www.phpliveregex.com/
	*/
	static function containsWord(?string $word, string $text, bool $case_sensitive = true) : bool {
		$mod = !$case_sensitive ? 'i' : '';
		
		if (preg_match('/(?<=[\s,.:;_"\']|^)' . $word . '(?=[\s,.:;_"\']|$)/'.$mod, $text)){
			return true;
		} 

		return false;
	}

	static function containsWordButNotStartsWith(?string $word, string $text, bool $case_sensitive = true) : bool {
		return !static::startsWith($word, $text, $case_sensitive) && static::containsWord($word, $text, $case_sensitive);
	}

	/*
		Verifica si *todas* las palabras se hallan en el texto. 
	*/
	static function containsWords(Array $words, string $text, bool $case_sensitive = true) {
		$mod = !$case_sensitive ? 'i' : '';

		foreach($words as $word){
			if (!preg_match('/(?<=[\s,.:;"\']|^)' . $word . '(?=[\s,.:;"\']|$)/'.$mod, $text)){
				return false;
			} 
		}		
		return true;
	}

	/*
		Verifica si al menos una palabra es encontrada en el texto
	*/
	static function containsAnyWord(Array $words, string $text, bool $case_sensitive = true) {
		foreach($words as $word){
			if (self::containsWord($word, $text, $case_sensitive)){
				return true;
			} 
		}	
		return false;	
	}

	/*
		Recupera todas las palabras de un texto

		https://stackoverflow.com/a/10685513/980631
	*/
	static function getWords(?string $str){
		$n_words = preg_match_all('/([a-zA-Z]|\xC3[\x80-\x96\x98-\xB6\xB8-\xBF]|\xC5[\x92\x93\xA0\xA1\xB8\xBD\xBE]){4,}/', $str, $match_arr);
		return $match_arr[0];
	}

	static function equal(?string $s1, string $s2, bool $case_sensitive = true){
		if ($case_sensitive === false){
			$s1 = strtolower($s1);
			$s2 = strtolower($s2);
		}
		
		return ($s1 === $s2);
	}

	static function replace($search, $replace, &$subject, $count = NULL, $case_sensitive = true)
	{
		if ($subject === null){
			return null;
		}

		if ($case_sensitive){
			$subject = str_replace($search, $replace, $subject, $count);
		} else {
			$subject = str_ireplace($search, $replace, $subject, $count);
		}		
	}

	/**
	* String replace nth occurrence
	* 
	* @author	filipkappa
	*/
	static function replaceNth(?string $search, string $replace, string $subject, ?int $occurrence)
	{
		$search = preg_quote($search);
		return preg_replace("/^((?:(?:.*?$search){".--$occurrence."}.*?))$search/", "$1$replace", $subject);
	}
   
	static function removeMultipleSpaces($str){
		return preg_replace('!\s+!', ' ', $str);
	}


	/*
		Atomiza string (divivirlo en caracteres constituyentes)
		Source: php.net
	*/
	static function stringTochars($s){
		return	preg_split('//u', $s, -1, PREG_SPLIT_NO_EMPTY);
	}	
		
	
	/*
		str_replace() de solo la primera ocurrencia
	*/
	static function replaceFirst($pattern, $replace, $subject)
	{
		$pattern = '/'.preg_quote($pattern, '/').'/';
		return preg_replace($pattern, $replace, $subject, 1);
	}
	
	/*
		str_replace() de solo la ultima ocurrencia
	*/
	static function replaceLast($search, $replace, $subject)
	{
		$pos = strrpos($subject, $search);
	
		if($pos !== false)    
			$subject = substr_replace($subject, $replace, $pos, strlen($search));
		
		return $subject;
	}

	/*
		Hace el substr() desde el $ini hasta $fin
		
		@param string 
		@param int indice de inicio
		@param int indice final
		@return string el substr() de inicio a fin	
	*/
	static function middle(?string $str, int $ini, ?int $end = null) : string {
		if (strlen($str) === 0){
			return '';
		}

		if ($end === 0){
			return '';
		} else {
			$len = $end - $ini;
			return substr($str, $ini, $len);
		}
	}

	static function left(?string $str, int $to_pos){
		if ($to_pos === 0){
			return '';
		}

		return substr($str, 0, $to_pos);         
	}

	static function right(?string $str, int $from_pos){
		if ($from_pos === 0){
			return $str;
		}

		return substr($str, $from_pos);        
	}

	static function firstChar(?string $str) : string {
		return substr($str, 0, 1);
	}

	static function lastChar(?string $str) : string {
		return substr($str, -1);
	}

	static function exceptLastChar(?string $str) : string {
		return substr($str, 0, -1);
	}

	// alias for exceptLastChar
	static function untilLastChar(?string $str) : string {
		return substr($str, 0, -1);
	}

	/*
		Parse php class from file
	*/
	static function getClassName(?string $file_str, bool $fully_qualified = true){
		$pre_append = '';
			
		if ($fully_qualified){
			$namespace = Strings::match($file_str, '/namespace[ ]{1,}([^;]+)/');
			$namespace = !empty($namespace) ? trim($namespace) : '';

			if (!empty($namespace)){
				$pre_append = "$namespace\\";
			}
		}	
		
		$class_name = $pre_append . Strings::matchOrFail($file_str, '/class ([a-z][a-z0-9_]+)/i');

		return $class_name;
	}

	/*
		Parse php class given the filename
	*/
	static function getClassNameByFileName(?string $filename, bool $fully_qualified = true){
		$file = file_get_contents($filename);
		return self::getClassName($file, $fully_qualified);
	}

	/*
		https://stackoverflow.com/a/13212994/980631
	*/	
	static function randomString(int $length = 60, bool $include_spaces = true, ?string $base = null){

		if ($base == null){
			$base = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		}

		if ($include_spaces){
			$base .= str_repeat(' ', rand(0, 10));
		}

		return substr(	str_shuffle(str_repeat($x=$base, (int) ceil($length/strlen($x)) )),	1, $length);
	}

	static function randomHexaString(int $length){
		return static::randomString($length, false, '0123456789abcdef');
	}

    /**
	 * Scretet_key generator
	 *
	 * @return string
	 */
	static function secretKeyGenerator(){
		$arr=[];
		for ($i=0;$i<(512/7);$i++){
			$arr[] = chr(rand(32,38));
			$arr[] = chr(rand(40,47));
			$arr[] = chr(rand(58,64));
			$arr[] = chr(rand(65,90));
			$arr[] = chr(rand(91,96));
			$arr[] = chr(rand(97,122));	
			$arr[] = chr(rand(123,126));
		}	
    
        shuffle($arr);
		return substr(implode('', $arr),0,512);
	}

	// https://stackoverflow.com/a/4964352
	function toBase($num, $b=62) {
		$base='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$r = $num  % $b ;
		$res = $base[$r];
		$q = floor($num/$b);
		while ($q) {
		  $r = $q % $b;
		  $q =floor($q/$b);
		  $res = $base[$r].$res;
		}
		return $res;
	}
	
	/*
		Determina si un registro cumple o no con las condiciones expuestas

		Los operadores son practicamente los mismos que los de ApiController

	*/
	static function filter(Array $reg, Array $conditions)
	{
		/*
			Volver búsquedas insensitivas al case (sin implementar)
		*/	
		$case_sensitive = false; 

		$ok = true;
		foreach($conditions as $field => $cond)
		{
			if (!is_array($cond)){                
				if ($cond == 'null' && $reg[$field] === null){                   
					continue;
				}

				if (strpos($cond, ',') === false){
					if ($reg[$field] == $cond){
						continue;
					}
				} else {
					$vals = explode(',', $cond);
					if (in_array($reg[$field], $vals)){
						continue;
					}
				}  
				
				$ok = false;
		
			} else {
				// some operators
		
				foreach($cond as $op => $val)
				{

					if (strpos($val, ',') === false)
					{
						switch ($op) {
							case 'eq':
								if ($reg[$field] == $val){                                    
									continue 2;
								}
								break;
							case 'neq':
								if ($reg[$field] != $val){                
									continue 2;
								}
								break;	
							case 'gt':
								if ($reg[$field] > $val){                           
									continue 2;
								}
								break;	
							case 'lt':
								if ($reg[$field] < $val){                             
									continue 2;
								}
								break;
							case 'gteq':
								if ($reg[$field] >= $val){
									$ok = true;
									continue 2;
								}
								break;	
							case 'lteq':
								if ($reg[$field] <= $val){                     
									continue 2;
								}
								break;	
							case 'contains':
								if (Strings::contains($val, $reg[$field])){                           
									continue 2;
								}
								break;    
							case 'notContains':
								if (!Strings::contains($val, $reg[$field])){                  ;
									continue 2;
								}
								break; 
							case 'startsWith':
								if (Strings::startsWith($val, $reg[$field])){                           
									continue 2;
								}
								break; 
							case 'notStartsWith':
								if (!Strings::startsWith($val, $reg[$field])){               
									continue 2;
								}
								break; 
							case 'endsWith':             
								if (Strings::endsWith($val, $reg[$field])){                 
									continue 2;
								}
								break;      
							case 'notEndsWith':
								if (!Strings::endsWith($val, $reg[$field])){                           
									continue 2;
								}
								break;  
							case 'containsWord':
								if (Strings::containsWord($val, $reg[$field])){                           
									continue 2;
								}
								break;   
							case 'notContainsWord':
								if (!Strings::containsWord($val, $reg[$field])){                           
									continue 2;
								}
								break;  
						
							default:
								throw new \InvalidArgumentException("Operator '$op' is unknown", 1);
								break;
						}

					} else {
						// operadores con valores que deben ser interpretados como arrays
						$vals = explode(',', $val);

						switch ($op) {
							case 'between':
								if (count($vals)>2){
									throw new \InvalidArgumentException("Operator between accepts only two arguments");
								}

								if ($reg[$field] >= $vals[0] && $reg[$field] <= $vals[1]){
									continue 2;
								}
								break;
							case 'notBetween':
								if (count($vals)>2){
									throw new \InvalidArgumentException("Operator between accepts only two arguments");
								}

								if ($reg[$field] < $vals[0] || $reg[$field] > $vals[1]){
									continue 2;
								}
								break;
							case 'in':                            
								if (in_array($reg[$field], $vals)){
									continue 2;
								}
								break;
							case 'notIn':                            
								if (!in_array($reg[$field], $vals)){
									continue 2;
								}
								break; 
							case 'contains':
								if (Strings::containsAny($vals, $reg[$field])){                           
									continue 2;
								}
								break;   
							case 'notContains':
								if (!Strings::containsAny($vals, $reg[$field])){                           
									continue 2;
								}
								break;        
							case 'containsWord':
								if (Strings::containsAnyWord($vals, $reg[$field])){                           
									continue 2;
								}
								break;   
							case 'notContainsWord':
								if (!Strings::containsAnyWord($vals, $reg[$field])){                           
									continue 2;
								}
								break;     

							default:
								throw new \InvalidArgumentException("Operator '$op' is unknown", 1);
								break;    
						}

					}
					$ok = false;

				}
	
				
			}

			if (!$ok){
				break;
			}
		
		} 

		return $ok;
	}

	static function realPathNoCoercive(?string $path){
		$_path = realpath($path);

		return $_path === false ? $path : $_path;
	}

	static function replaceSlashes(?string $path) : string {
		return str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
	}
        
	static function removeUnnecessarySlashes(?string $path) : string {
       	return preg_replace('#/+#','/',$path);
	}

	static function removeTrailingSlash(?string $path = null) : ?string {
		if (empty($path)){
			return $path;
		}

		$path = static::realPathNoCoercive($path);

		if (Strings::endsWith('\\', $path) || Strings::endsWith('/', $path)){
			return substr($path, 0, strlen($path)-1);
		}

		return $path;
	}

	static function removeFirstSlash(?string $path = null) : ?string {
		if (empty($path)){
			return $path;
		}

		if (Strings::startsWith('\\', $path)){
			return substr($path, 1);
		}

		return $path;
	}

	static function addTrailingSlash(?string $path) : string{
		$path = static::realPathNoCoercive($path);

		if (!Strings::endsWith('\\', $path) && !Strings::endsWith('/', $path)){
			return $path . '/';
		}

		return $path;		
	}

	/*
		Desentrelaza un string en dos.
	*/
	static function deinterlace(?string $literal) : Array {
        $arr = str_split($literal);

        $str1 = '';
        for ($i=0; $i<strlen($literal); $i+=2){
            if ($i>strlen($literal)-1){
                break;
            }
            $str1 .= $arr[$i];
        }

        $str2 = '';
        for ($i=1; $i<strlen($literal); $i+=2){
            if ($i>strlen($literal)-1){
                break;
            }
            $str2 .= $arr[$i];
        }
        
        return [$str1, $str2];
    }

	/*
		Entrelaza (hace un merge) de un array de strings
	*/
    static function interlace(Array $str) : string {
        $ret = '';

        if (count($str) === 0){
            return '';
        } 

        if (count($str) === 1){
            return $str[0];
        } 

        $max_len = 0;
        $arr = [];
        foreach ($str as $ix => $s){
			$ls = strlen($s);
            if ($ls > $max_len){
                $max_len = $ls;
            }

            $arr[] = str_split($s);
        }

        for ($i=0; $i<$max_len; $i++){
            foreach ($arr as $a){
                if (isset($a[$i])){
                    $ret .= $a[$i];
                }
            }
        }
        
        return $ret;
    }

	static function removeInlineComments(?string $str) : string {	
		return preg_replace('/\n\s*\n/', "\n", $str);
	}	

	static function removeMultiLineComments(?string $str) : string {	
		return preg_replace('!/\*.*?\*/!s', '', $str);	
	}

	static function removeComments(?string $str) : string {
		return static::removeInlineComments(static::removeMultiLineComments($str));
	}
}


