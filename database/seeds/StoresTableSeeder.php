<?php

use Illuminate\Database\Seeder;

class StoresTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Store::class, 5)->create()->each(function ($store) {
            $store->articles()->saveMany(factory(App\Article::class, 10)->make(['store_id' => $store->id]));
        });
    }
}
