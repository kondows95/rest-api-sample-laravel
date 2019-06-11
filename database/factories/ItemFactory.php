<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;
use App\Models\Item;
use App\Models\Category;

$factory->define(Item::class, function (Faker $faker) {
    $word = $faker->word();
    return [
        'name' => $word,
        'price' => $faker->numberBetween($min = 100, $max = 100000),
        'image' => $word.'.png',
        'category_id' => factory(Category::class)->create()->id
    ];
});

