<?php

use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Service::query()->truncate();
        \App\Models\ShopService::query()->truncate();

        $s1 = \App\Models\Service::create([
            'name' => 'Facebook Buff',
        ]);

        $s12 = \App\Models\Service::create([
            'name' => 'Buff Follow Cá Nhân',
            'parent_id' => $s1->id,
            'slug' => 'facebook-buff/buff-follow',
            'sku' => 'FB.BUFF.FOLLOW'
        ]);

        $ss1 = \App\Models\ShopService::create([
            'shop_id' => 1,
            'service_id' => $s1->id,
            'service_parent_id' => -1
        ]);

        $ss1 = \App\Models\ShopService::create([
            'shop_id' => 2,
            'service_id' => $s1->id,
            'service_parent_id' => -1
        ]);

        $ss12 = \App\Models\ShopService::create([
            'shop_id' => 1,
            'service_id' => $s12->id,
            'service_parent_id' => $s1->id,
            'price' => 50
        ]);

        $ss12 = \App\Models\ShopService::create([
            'shop_id' => 2,
            'service_id' => $s12->id,
            'service_parent_id' => $s1->id,
            'price' => 60
        ]);
    }
}
