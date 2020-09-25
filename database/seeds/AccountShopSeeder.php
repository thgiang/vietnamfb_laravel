<?php

use Illuminate\Database\Seeder;

class AccountShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Account::query()->truncate(); // truncate user table each time of seeders run
        \App\Models\Shop::query()->truncate(); // truncate user table each time of seeders run
        $root = \App\Models\Account::create([ // create a new user
            'email' => 'root@gmail.com',
            'username' => 'root',
            'password' => \Illuminate\Support\Facades\Hash::make('root'),
            'fullname' => 'Root Shop',
            'tel' => '0339573050',
            'is_root' => 1,
            'shop_id' => 1,
            'has_shop_id' => 1
        ]);

        $accountAndShop = \App\Models\Account::create([ // create a new user
            'email' => 'accandshop1@gmail.com',
            'username' => 'accandshop1',
            'password' => \Illuminate\Support\Facades\Hash::make('accandshop1'),
            'fullname' => 'Acc and shop 1',
            'tel' => '0339573051',
            'is_root' => 0,
            'shop_id' => 1,
            'has_shop_id' => 2,
            'balance' => 1000000
        ]);

        $accountShop1 = \App\Models\Account::create([ // create a new user
            'email' => 'acc1@gmail.com',
            'username' => 'acc1',
            'password' => \Illuminate\Support\Facades\Hash::make('acc1'),
            'fullname' => 'Acc 1',
            'tel' => '0339573052',
            'is_root' => 0,
            'shop_id' => 1,
            'balance' => 1000000
        ]);

        $accountShop2 = \App\Models\Account::create([ // create a new user
            'email' => 'acc2@gmail.com',
            'username' => 'acc2',
            'password' => \Illuminate\Support\Facades\Hash::make('acc2'),
            'fullname' => 'Acc 2',
            'tel' => '0339573053',
            'is_root' => 0,
            'shop_id' => 2,
            'balance' => 1000000
        ]);

        \App\Models\Shop::create([
            'account_id' => $root->id,
            'ref_id' => -1,
            'name' => 'Shop Root',
            'domain' => 'http://vietnamfb.local:8081'
        ]);

        \App\Models\Shop::create([
            'account_id' => $accountAndShop->id,
            'ref_id' => 1,
            'name' => 'Shop 1',
            'domain' => 'http://shop1.vietnamfb.local:8081'
        ]);
    }
}
