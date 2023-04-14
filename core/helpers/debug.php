<?php declare(strict_types = 1);

use boctulus\SW\core\libs\Debug;
use boctulus\SW\core\libs\Files;
use boctulus\SW\core\libs\StdOut;
use boctulus\SW\core\libs\VarDump;

function show_debug_trace(bool $status = true){
    VarDump::showTrace($status);
}

function hide_debug_trace(){
    VarDump::hideTrace();
}

function show_debug_response(bool $status = true){
    VarDump::showResponse($status);
}

function hide_debug_response(){
    VarDump::hideResponse();
}

function d($val = null, $msg = null, bool $additional_carriage_return = false){
    return VarDump::dd($val, $msg, $additional_carriage_return);	
}	

function _dd($val = null, $msg = null, bool $additional_carriage_return = true){
    return VarDump::dd($val, $msg, $additional_carriage_return);
}

if (!function_exists('dd')){
	function dd($val = null, $msg = null, bool $additional_carriage_return = true){
		return _dd($val, $msg, $additional_carriage_return);
	}
}

function foo(){
    throw new \Exception("FOO");
}

function here(){
    _dd('HERE !');
}

function debug($val, $msg = null, bool $only_admin = false){
	if ($only_admin && !is_admin()){
		return;
	}
	
	if (config()['debug'] && StdOut::$render){
		_dd($val, $msg);
	}

	if (!empty($msg)){
		Files::logger($msg. ': '. var_export($val, true));
	} else {
		Files::logger(var_export($val, true));
	}	
}

function console_log($val, $msg = null, bool $only_admin = false){
	if ($only_admin && !is_admin()){
		return;
	}

	if (!is_cli()){
		?>
		<script>
			console.log('<?= $msg ?>', '<?= $val ?>');		
		</script>
		<?php
	}
}