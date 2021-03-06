<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Arbify\Models\Project;
use Faker\Generator as Faker;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'visibility' => $faker->randomElement([Project::VISIBILITY_PUBLIC, Project::VISIBILITY_PRIVATE]),
    ];
});
