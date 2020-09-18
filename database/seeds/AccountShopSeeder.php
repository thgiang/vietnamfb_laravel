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
            'shop_id' => 1
        ]);

        $account = \App\Models\Account::create([ // create a new user
            'email' => 'acc1@gmail.com',
            'username' => 'acc1',
            'password' => \Illuminate\Support\Facades\Hash::make('acc1'),
            'fullname' => 'Acc 1',
            'tel' => '0339573051',
            'is_root' => 0,
            'shop_id' => 1
        ]);

        \App\Models\Shop::create([
            'account_id' => $root->id,
            'ref_id' => -1,
            'name' => 'Shop Root Root',
            'domain' => 'http://vietnamfb.local:8081'
        ]);
    }
}
