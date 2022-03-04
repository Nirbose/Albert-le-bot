<?php

include __DIR__.'/vendor/autoload.php';

use App\Commands\Command;
use App\Database\DB;
use App\Handler;
use Dotenv\Dotenv;
use Discord\Discord;
use Discord\WebSockets\Intents;
use Monolog\Logger;

Dotenv::createImmutable(__DIR__)->load();

define("PREFIX", $_ENV["PREFIX"]);
define("COLOR", hexdec($_ENV["COLOR"]));

$client = new Discord([
    'token' => $_ENV['TOKEN'],
    'loadAllMembers' => true,
    'logger' => new Logger('Albert-le-bot'),
    'intents' => Intents::getAllIntents()
]);

foreach (glob("commands/*/*.php") as $filename) require_once $filename;
foreach (glob("listeners/*.php") as $filename) require_once $filename;

Handler::load($client);

Command::create($client);
    
DB::create();
require __DIR__.'/data/Migration.php';

try {

    $client->run();
} catch (\Discord\Exceptions\IntentException $e) {
    echo $e->getMessage(), PHP_EOL;
}