<?php

namespace Linlak\Jwt\Console\Commands;

use Illuminate\Console\Command;

class CleanTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jwt:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes access tokens that have expired or revoked by user';

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
        //
    }
}
