<?php declare(strict_types=1);

namespace boctulus\SW\core\libs;

class System
{
    static function getOS(){
        return defined('PHP_OS_FAMILY') ? PHP_OS_FAMILY : PHP_OS;
    }

    static function isLinux(){
        $os = static::getOS();

        return ($os == 'Linux');
    }

    static function isWindows(){
        $os = static::getOS();

        return ($os == 'Windows' || $os == 'WIN32' || $os == 'WINNT');
    }

    static function isUnix(){
        $os = static::getOS();

        return (in_array($os, ['Linux', 'BSD', 'Darwin', ' NetBSD', 'FreeBSD', 'Solaris']));
    }

    // https://www.php.net/manual/en/function.is-executable.php#123883
    static function isExecutableInPath(string $filename) : bool
    {
        if (is_executable($filename)) {
            return true;
        }

        if ($filename !== basename($filename)) {
            return false;
        }

        $paths = explode(PATH_SEPARATOR, getenv("PATH"));
        
        foreach ($paths as $path) {

            $f = $path . DIRECTORY_SEPARATOR . $filename;

            if (is_executable($f)) {
                return true;
            }
        }

        return false;
    }

    /*
        Returns PHP path
        as it is needed to be used with runInBackground()

        Pre-requisito: php.exe debe estar en el PATH
    */  
    static function getPHP(){
        $location = System::isWindows() ? shell_exec("where php.exe") : "php";
        return trim($location);
    }

    /*
        https://factory.dev/pimcore-knowledge-base/how-to/execute-php-pimcore

        Ver tambi'en
        https://gist.github.com/damienalexandre/1300820
        https://stackoverflow.com/questions/13257571/call-command-vs-start-with-wait-option
    */
    static function runInBackground(string $cmd, string $output_path = null, $ignore_user_abort = true, int $execution_time = 0)
    {
        ignore_user_abort($ignore_user_abort);
        set_time_limit($execution_time);

        $cmd = trim($cmd);

        switch (PHP_OS_FAMILY) {
            case 'Windows':
                if ($output_path !== null){
                    $cmd .= " >> $output_path";
                }

                $shell = new \COM("WScript.Shell");
                $shell->Run($cmd);
                $shell = null;

                break;
            case 'Linux':
                if ($output_path !== null){
                    $pid = (int) shell_exec("nohup nice -n 19 $cmd > $output_path 2>&1 & echo $!");
                } else {
                    $pid = (int) shell_exec("nohup nice -n 19 $cmd > /dev/null 2>&1 & echo $!");
                }

                break;
            default:
            // unsupported
            return false;
        }

        return $pid ?? null;
    }
}

