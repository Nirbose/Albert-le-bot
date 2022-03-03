<?php

include __DIR__.'/vendor/autoload.php';

use App\Command;
use App\Database;
use App\Database\DB;
use App\Handler;
use App\Namespaces\Presence;
use DB\Database as DBDatabase;
use Dotenv\Dotenv;
use Discord\Discord;
use Discord\Parts\User\Activity;
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

try {

    $client->on('ready', function (Discord $client) {
        
        echo "Bot is ready!", PHP_EOL;

        $activity = new Activity($client, [
            'name' => 'les services de Nirbose',
            'type' => Activity::TYPE_LISTENING
        ], true);
        $client->updatePresence($activity, false, 'online');

        Handler::load($client);
        
    });

    Command::create($client);
    
    DB::create();
    require __DIR__.'/data/Migration.php';

    foreach (glob("commands/*/*.php") as $filename) require_once $filename;
    foreach (glob("listeners/*.php") as $filename) require_once $filename;

    $client->run();
} catch (\Discord\Exceptions\IntentException $e) {
    echo $e->getMessage(), PHP_EOL;
}