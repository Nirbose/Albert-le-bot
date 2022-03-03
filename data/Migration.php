<?php

// use DB\Table;

use App\Database\Blueprint;
use App\Database\DB;

DB::schema()->create('users', function (Blueprint $table) {
    $table->id();
    $table->varchar('userID');
    $table->varchar('guildID');
    $table->int('level');
    $table->float('xp');
    $table->int('quest');
    $table->timestamp();
});

DB::schema()->create('messages', function (Blueprint $table) {
    $table->id();
    $table->varchar('message');
    $table->varchar('authorID');
    $table->varchar('channelID');
    $table->varchar('guildID');
    $table->timestamp();
});

DB::schema()->create('quests', function (Blueprint $table) {
    $table->id();
    $table->varchar('userID');
    $table->varchar('guildID');
    $table->int('quest');
    $table->json('datas');
    $table->timestamp();
});

DB::schema()->create('sentences', function (Blueprint $table) {
    $table->id();
    $table->varchar('guildID');
    $table->text('sentence');
    $table->varchar('type');
    $table->timestamp();
});

DB::schema()->create('roles', function (Blueprint $table) {
    $table->id();
    $table->varchar('guildID');
    $table->varchar('roleID');
    $table->varchar('name');
    $table->timestamp();
});

DB::schema()->create('voice_times', function (Blueprint $table) {
    $table->id();
    $table->varchar('guildID');
    $table->varchar('userID');
    $table->int('time');
    $table->timestamp();
});
