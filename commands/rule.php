<?php

use App\App;
use App\Command;
use Discord\Builders\Components\ActionRow;
use Discord\Builders\Components\Button;
use Discord\Builders\MessageBuilder;
use Discord\Helpers\Collection;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Discord\Parts\Interactions\Interaction;

$collec = new Collection();

new Command([
  'name' => 'rule',
  'description' => 'Rule Command',
  'ownerOnly' => true,
  'run' => function (Message $message, string $rest, App $app) use ($collec) {

    if(empty($collec->toArray())) {
      $builder = MessageBuilder::new();

      // BUTTONS
      $buttons = ActionRow::new()
        ->addComponent(Button::new(Button::STYLE_LINK)->setLabel('Les TOS de discord')->setUrl('https://discord.com/terms'))
        ->addComponent(Button::new(Button::STYLE_PRIMARY, 'submit_rule')->setLabel('Je suis d\'accord !')->setListener(function (Interaction $interaction) {
          
          $memberRoleID = '883401672955678780';
          if(!$interaction->member->roles->has($memberRoleID)) {
            $interaction->member->addRole($memberRoleID);
          } else {
            $interaction->respondWithMessage(MessageBuilder::new()->setContent('Vous avez déjà validé'), true);
          }
          
        }, $message->discord));

      $embed = [
        "title" => "Règlement du Serveur",
        "description" => "**Règles généraux :**\n\n> - Pas de pseudos vides.\n> - Aucun pseudos inapproprié.\n> - Pas de pseudos sexuellement explicites.\n> - Pas de pseudos offensants.\n> - Pas de pseudos avec Unicode inhabituel ou illisible. *(vous pouvez executer la commande `;pseudo` pour avoir un pseudo correcte)*\n> - Aucune photo de profil inappropriée.\n> - Aucune photo de profil sexuellement explicite.\n> - Pas de photos de profil offensantes.\n> - Pas de PUB mp aux autres membres du serveur.\n\n**Règles pour les chats textuels :**\n\n> - Aucun contenu sexuellement explicite.\n> - Aucun contenu pornographique.\n> - Aucun contenu NSFW.\n> - Aucun contenu illégal.\n> - Pas de piratage.\n> - Aucune attaque personnelle.\n> - Pas de harcèlement.\n> - Pas de sexisme.\n> - Pas de racisme.\n> - Pas de discours de haine.\n> - Pas de langage offensant.\n> - Pas de spam.\n> - Pas d\'emojis abusifs.\n> - Aucun lien vers d\'autres serveurs.\n> - Les modérateurs se réservent le droit de supprimer tout message. *(Sans en abuser)*\n> - Pas de publicité sans permission.\n\n**Règles pour les chats vocaux :**\n\n> - Aucun bruit gênant, fort ou aigu.\n> - Les modérateurs se réservent le droit de vous déconnecter d\'un canal vocal si votre qualité sonore est mauvaise.\n> - Les modérateurs se réservent le droit de déconnecter, de mettre en sourdine, d\'assourdir ou de déplacer des membres vers et depuis les canaux vocaux.\n\n**Autre :**\n\n> - En acceptant les règles, vous acceptez aussi les TOS de discord.com.",
        "color" => $app->color,
        "footer" => [
          "text" => "Les administrateurs se réservent le droit de ne pas respecter certaines règles."
        ]
      ];

      $builder->setEmbeds([$embed])->addComponent($buttons);
      $message->channel->sendMessage($builder)->done(function ($msg) use ($collec) {
        $collec->push(['link' => $msg->link]);
      });
    } else {
      $embed = new Embed($message->discord);
      $embed->setDescription('Allez lire [le règlement]('.$collec->first()['link'].') !')
        ->setColor($app->color);
      $message->channel->sendEmbed($embed);
    }
  }
]);
