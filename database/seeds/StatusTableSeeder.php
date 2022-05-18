<?php

use Illuminate\Database\Seeder;
use App\Status;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = new Status();
        $status->id = 1;
        $status->name = 'Progress';
        $status->save();

        $status = new Status();
        $status->id = 2;
        $status->name = 'Tahap II';
        $status->save();

        $status = new Status();
        $status->id = 3;
        $status->name = 'SP 3';
        $status->save();

        $status = new Status();
        $status->id = 4;
        $status->name = 'SP 2 LIDIK';
        $status->save();

        $status = new Status();
        $status->id = 5;
        $status->name = 'Restoratif Justice';
        $status->save();

        $status = new Status();
        $status->id = 6;
        $status->name = 'ADR';
        $status->save();
    }
}
