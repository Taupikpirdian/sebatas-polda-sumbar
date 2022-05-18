<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class reportPerkara extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:reportPerkara';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Report jumlah dan list perkara yang diinput perhari, dihit setiap jam 00:00';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // helpers
        reportPerkara();
    }
}
