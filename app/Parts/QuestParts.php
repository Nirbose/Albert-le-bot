<?php

namespace App\Parts;

class QuestParts
{
    public function get()
    {
        return [
            'name' => 'Quest 1',
            'description' => 'Quest 1 description',
            'steps' => [
                'level' => 2,
                'messages' => 30,
                'welcome' => 1
            ],
            'rewards' => [
                'actions' => [
                    $this->addRole('quest')
                ],
                'benefits' => [
                    'Permet d\'avoir les salons vocaux personnalisés'
                ]
            ],
        ];
    }

    public function addRole(string $name)
    {
        return "role";
    }
}