<?php
/**
 * This file is part of workerman.
*
* Licensed under The MIT License
* For full copyright and license information, please see the MIT-LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @author walkor<walkor@workerman.net>
* @copyright walkor<walkor@workerman.net>
* @link http://www.workerman.net/
* @license http://www.opensource.org/licenses/mit-license.php MIT License
*/

use \Workerman\Worker;
use \Workerman\WebServer;
use \Workerman\Connection\TcpConnection;

// Command. For example 'tail -f /var/log/nginx/access.log'.
define('CMD', 'htop');

// Whether to allow client input.
define('ALLOW_CLIENT_INPUT', true);

// Uinix user for command. Recommend nobody www etc. 
define('USER', 'www-data');

require_once __DIR__ . '/Workerman/Autoloader.php';
$worker = new Worker("Websocket://0.0.0.0:7778");
$worker->name = 'phptty';
$worker->user = USER;

$worker->onConnect = function($connection)
{
    $descriptorspec = array(
            0=>array("pipe", "r"),  // stdin is a pipe that the child will read from
            1=>array("pipe", "w"),  // stdout is a pipe that the child will write to
            2=>array("pipe", "w")   // stderr is a file to write to
    );
    unset($_SERVER['argv']);
    $connection->process = proc_open(CMD, $descriptorspec, $pipes, null, array_merge(array('COLUMNS'=>130, 'LINES'=> 50), $_SERVER));
    $connection->pipes = $pipes;
    stream_set_blocking($pipes[0], 0);
    $connection->process_stdout = new TcpConnection($pipes[1]);
    $connection->process_stdout->onMessage = function($process_connection, $data)use($connection)
    {
        $connection->send($data);
    };
    $connection->process_stdin = new TcpConnection($pipes[2]);
    $connection->process_stdin->onMessage = function($process_connection, $data)use($connection)
    {
        $connection->send($data);
    };
};

$worker->onMessage = function($connection, $data)
{
    if(ALLOW_CLIENT_INPUT)
    {
        fwrite($connection->pipes[0], $data);
    }
};

$worker->onClose = function($connection)
{
    $connection->process_stdin->close();
    $connection->process_stdout->close();
    fclose($connection->pipes[0]);
    $connection->pipes = null;
    proc_terminate($connection->process);
    proc_close($connection->process);
    $connection->process = null;
};

$worker->onWorkerStop = function($worker)
{
    foreach($worker->connections as $connection)
    {
        $connection->close();
    }
};

$webserver = new WebServer('http://0.0.0.0:7779');
$webserver->addRoot('localhost', __DIR__ . '/Web');

if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}
