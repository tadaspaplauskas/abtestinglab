<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function () {
    return [
        'name' => 'Tadas Paplauskas',
        'email' => 'testing@abtestinglab.dev',
        'password' => '$2y$10$FSNzRAt9gZvsrVVxLyScFe/bDxGKB8k5o5uIpv4BMHYfjtpHBTzyu',
        'weekly_reports' => 1,
        'test_notifications' => 1,
        'newsletter' => 1,
    ];
});

$factory->define(App\Models\Website::class, function () {
    return [
        'url' => 'abtestinglab.dev/blog.html',
        'title' => 'Testy test',
        'status' => 'enabled',
    ];
});
