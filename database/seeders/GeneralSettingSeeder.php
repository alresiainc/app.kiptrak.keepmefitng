<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\GeneralSetting;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $generalSetting = new GeneralSetting();
        $generalSetting->site_title = 'KIPTRAK';
        $generalSetting->site_description = 'CRM APPLICATION PLATFORM';
        $generalSetting->currency = 1;
        $generalSetting->developed_by = 'Ugo Sunday Raphael';
        $generalSetting->official_notification_email = 'ralphsunny114@gmail.com';
        $generalSetting->attendance_time = '08:00';
        $generalSetting->created_by = 1;
        $generalSetting->status = 'true';
        $generalSetting->save();
    }
}
