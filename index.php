<?php

include __DIR__.'/vendor/autoload.php';

use App\Handler;
use App\Namespaces\ApplicationCommand;
use App\Namespaces\Presence;
use Dotenv\Dotenv;
use Discord\Discord;
use Discord\WebSockets\Intents;

Dotenv::createImmutable(__DIR__)->load();

define("PREFIX", $_ENV["PREFIX"]);

foreach (glob("commands/*.php") as $filename) require_once $filename;
foreach (glob("listeners/*.php") as $filename) require_once $filename;

$client = new Discord([
    'token' => $_ENV['TOKEN'],
    'loadAllMembers' => true,
    'intents' => Intents::getAllIntents()
]);

try {

    $client->on('ready', function (Discord $client) {
        
        $appli = new ApplicationCommand($_ENV['TOKEN'], $client->loop, $client->logger);
        $appli->createUserCommand('Testss');
        
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