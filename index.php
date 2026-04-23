<?php
// if($_SERVER['REMOTE_ADDR'] != '93.72.171.149') {
//   die('server is under the maintaince. please try again later');
// }

error_reporting(E_ALL ^ E_WARNING);

//---------------------------------------------------------------
// SYSTEM FOLDER NAME
//---------------------------------------------------------------
$system_folder = "./lib";

//---------------------------------------------------------------
// APPLICATION FOLDER NAME
//---------------------------------------------------------------
$application_folder = "./app";

//---------------------------------------------------------------
// SET THE SERVER PATH
//---------------------------------------------------------------
if (strpos($system_folder, '/') === FALSE) {
    if (function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE) {
        $system_folder = realpath(dirname(__FILE__)) . '/' . $system_folder;
    }
} else {
    $system_folder = str_replace("\\", "/", $system_folder);
}

//---------------------------------------------------------------
// DEFINE APPLICATION CONSTANTS
//---------------------------------------------------------------
define('EXT', '.php');
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('FCPATH', str_replace(SELF, '', __FILE__));
define('BASEPATH', $system_folder . '/');

if (is_dir($application_folder)) {
    define('APPPATH', $application_folder . '/');
} else {
    if ($application_folder == '') {
        $application_folder = 'app';
    }

    define('APPPATH', BASEPATH . $application_folder . '/');
}

//---------------------------------------------------------------
// DATABASE CONNECTION (mysqli version)
//---------------------------------------------------------------
$db['default']['hostname'] = "localhost";
$db['default']['username'] = "web23923979";
$db['default']['password'] = "j1wZktQI";
$db['default']['database'] = "usr_web23923979_1";

// Подключение к базе данных с mysqli
$link = @mysqli_connect(
    $db["default"]["hostname"],
    $db["default"]["username"],
    $db["default"]["password"],
    $db["default"]["database"]
);

if (!$link) {
    die("Datenbankverbindung fehlgeschlagen: " . mysqli_connect_error());
}

// Устанавливаем кодировку
$query = "SET NAMES utf8;";
mysqli_query($link, $query);

// Получаем языки
$query = "SELECT * FROM language ORDER BY priority DESC";
$result = mysqli_query($link, $query);

$langs = array();
while ($res = mysqli_fetch_assoc($result)) {
    $langs[$res['url_name']] = $res['name'];
}

mysqli_close($link);

global $gLangs;
$gLangs = $langs;

//---------------------------------------------------------------
// LOAD THE FRONT CONTROLLER
//---------------------------------------------------------------
require_once BASEPATH . 'codeigniter/CodeIgniter' . EXT;

/* End of file index.php */
/* Location: ./index.php */
