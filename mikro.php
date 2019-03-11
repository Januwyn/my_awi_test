<?php
# enable sockets in php.ini
# extension=php_sockets.dll

ini_set('display_errors', 10);
ini_set('display_startup_errors', 10);

set_time_limit(2000);
ini_set('max_execution_time', 2000);

ob_implicit_flush(true);
ob_end_flush();

$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === FALSE) {
    die('Error creating socket.');
}

$result = socket_connect($socket, '192.168.212.2', 2202);
if ($result === FALSE) {
    die('Error connecting microphone.');
}

#			--> key-array for hashes to ip <--
$keys = [
	'882c2071d8ce7ea8912929bbea384d2d2e653bf9' => '192.168.212.2'
];

#			--> port <--
$port = '2202';

#			--> conditions <--
if (isset ($_POST)){
	if (isset ($_POST['unmute'])) { $command= '< SET DEVICE_AUDIO_MUTE OFF >'; $post=($_POST['unmute']); $ip = $keys[$_POST['unmute']]; }
	if (isset ($_POST['mute'])) { $command= '< SET DEVICE_AUDIO_MUTE ON >'; $post=($_POST['mute']); $ip = $keys[$_POST['mute']]; }
	if (isset ($_POST['status'])) { $command= '< GET DEVICE_AUDIO_MUTE >'; $post=($_POST['status']); $ip = $keys[$_POST['status']]; }
	action ($ip, $port, $command, $keys, $post);
}

#			-->function <--
function action ($ip, $port, $command, $keys, $post){
	if (!array_key_exists($post,$keys)) die('Access denied');
	$socket = socket_create (AF_INET, SOCK_STREAM, SOL_TCP);
	$result = socket_connect ($socket, $ip, $port);
	if(!$result) return;
	$cmd = $command;
	socket_write($socket, $cmd, strlen($cmd));
	while (($out = socket_read($socket, 2048, PHP_BINARY_READ)) !== FALSE) {
		if ($out === '< REP DEVICE_AUDIO_MUTE ON >'){
			echo 0;
			break;
		}
		if ($out === '< REP DEVICE_AUDIO_MUTE OFF >'){
			echo 1;
			break;
		}
		if (substr($out, -1) === '>') break;
	}
}
socket_close($socket);
?>