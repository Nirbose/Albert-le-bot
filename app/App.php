<?php

namespace App;

use Discord\Helpers\Collection;

class App {

    public int $color = 7096905;

    public Collection $collec;

    public function __construct()
    {
        $this->collec = new Collection([
            'channels' => [
                'personnel_voice' => ['123', '123']
            ]            
        ]);
    }

}