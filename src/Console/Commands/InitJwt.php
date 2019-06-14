<?php

namespace Linlak\Jwt\Console\Commands;

use Illuminate\Console\Command;
use Linlak\Jwt\Traits\WritesConfig;
use Illuminate\Support\Str;

class InitJwt extends Command
{
    use WritesConfig;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jwt:init
        {--s|show : Display the key instead of modifying files.}
        {--f|force : Skip confirmation when overwriting an existing key.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates token encription keys';

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
        $key = Str::random(64);
        //
        if ($this->option('show')) {
            $this->comment($key);

            return;
        }

        if (file_exists($path = $this->envPath()) === false) {
            return $this->displayKey($key);
        }
        if (Str::contains(file_get_contents($path), 'JWT_MAX_AGE') === false) {
            $this->addkey($path, 'JWT_MAX_AGE', 3600);
        } else {
            if ($this->isConfirmed('Would you like to reset token max age?')) {
                $this->repkey($path, 'JWT_MAX_AGE', 3600, 'max_age');
            } else {
                $this->comment('Phew... No changes were made to your token max age.');
            }
        }
        if (Str::contains(file_get_contents($path), 'JWT_SECRET') === false) {
            $this->addkey($path, 'JWT_SECRET', $key);
        } else {
            if ($this->isConfirmed()) {
                $this->repkey($path, 'JWT_SECRET', $key, 'secret');
            } else {
                $this->comment('Phew... No changes were made to your secret key.');
            }
        }

        $this->info("Done!");
    }
}
