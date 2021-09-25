<?php

include __DIR__.'/vendor/autoload.php';

use App\Handler;
use App\Namespaces\Presence;
use Dotenv\Dotenv;
use Discord\Discord;


Dotenv::createImmutable(__DIR__)->load();

define("PREFIX", $_ENV["PREFIX"]);

foreach (glob("commands/*.php") as $filename) require_once $filename;
foreach (glob("listeners/*.php") as $filename) require_once $filename;

$client = new Discord([
    'token' => $_ENV['TOKEN'],
]);

try {

    $client->on('ready', function (Discord $client) {
        echo "Bot is ready!", PHP_EOL;

        new Presence($client, [
            'status' => 'online',
            'activity' => ['name' => 'Test']
        ]);

        (new Handler)->handler($client);
        
    });

    $client->run();
} catch (\Discord\Exceptions\IntentException $e) {
    echo $e->getMessage(), PHP_EOL;
}