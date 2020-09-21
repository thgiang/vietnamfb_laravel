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

//        $s2 = \App\Models\Service::create([
//            'name' => 'Facebook VIP',
//        ]);

//        $s11 = \App\Models\Service::create([
//            'name' => 'Auto tương tác',
//            'parent_id' => $s1->id,
//            'slug' => 'facebook-buff/auto-tuong-tac',
//            'sku' => 'fb.buff.auto'
//        ]);

        $s12 = \App\Models\Service::create([
            'name' => 'Buff Follow Cá Nhân',
            'parent_id' => $s1->id,
            'slug' => 'facebook-buff/buff-follow',
            'sku' => 'FB.BUFF.FOLLOW'
        ]);

//        $s21 = \App\Models\Service::create([
//            'name' => 'VIP Like',
//            'parent_id' => $s2->id,
//            'slug' => 'facebook-vip/vip-like',
//            'sku' => 'fb.vip.like'
//        ]);
//
//        $s22 = \App\Models\Service::create([
//            'name' => 'VIP Bình luận',
//            'parent_id' => $s2->id,
//            'slug' => 'facebook-vip/vip-comment',
//            'sku' => 'fb.vip.comment'
//        ]);

        $ss1 = \App\Models\ShopService::create([
            'shop_id' => 1,
            'service_id' => $s1->id,
            'service_parent_id' => -1
        ]);

//        $ss2 = \App\Models\ShopService::create([
//            'shop_id' => 1,
//            'service_id' => $s2->id,
//            'service_parent_id' => -1
//        ]);
//
//        $ss11 = \App\Models\ShopService::create([
//            'shop_id' => 1,
//            'service_id' => $s11->id,
//            'service_parent_id' => $s1->id,
//            'price' => 50
//        ]);

        $ss12 = \App\Models\ShopService::create([
            'shop_id' => 1,
            'service_id' => $s12->id,
            'service_parent_id' => $s1->id,
            'price' => 50
        ]);

//        $ss21 = \App\Models\ShopService::create([
//            'shop_id' => 1,
//            'service_id' => $s21->id,
//            'service_parent_id' => $s2->id,
//            'price' => 70
//        ]);
//
//        $ss21 = \App\Models\ShopService::create([
//            'shop_id' => 1,
//            'service_id' => $s22->id,
//            'service_parent_id' => $s2->id,
//            'price' => 80
//        ]);
    }
}
