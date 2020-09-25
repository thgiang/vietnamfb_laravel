<?php

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Setting::query()->truncate();
        \App\Models\Setting::create([
            'name' => 'token 1',
            'setting' => 'EAAAAZAw4FxQIBAHZBzccvGggapXf4ZAPZB8GSq1p5hXv5uy9ROZC5WudirNoAo2MGrZBL0N0nFOtDhG1zpJ7d7k9pfkys7sF436we4ZCuiYeBL4iZBZCZBBZAvmr3DwaWhHiCnU8XzhqyWdYPbXZBZAUQcnZBfRiQsoE8yrtx64VSdWSZAsHLjD57sJZC1DSl51930Co9559tjg23NJM2wZDZD',
            'type' => \App\Models\Setting::TYPE_TOKEN,
            'shop_id' => 1
        ]);

        \App\Models\Setting::create([
            'name' => 'token 2',
            'setting' => 'EAAAAZAw4FxQIBAHZBzccvGggapXf4ZAPZB8GSq1p5hXv5uy9ROZC5WudirNoAo2MGrZBL0N0nFOtDhG1zpJ7d7k9pfkys7sF436we4ZCuiYeBL4iZBZCZBBZAvmr3DwaWhHiCnU8XzhqyWdYPbXZBZAUQcnZBfRiQsoE8yrtx64VSdWSZAsHLjD57sJZC1DSl51930Co9559tjg23NJM2wZDZD',
            'type' => \App\Models\Setting::TYPE_TOKEN,
            'shop_id' => 2
        ]);
    }
}
