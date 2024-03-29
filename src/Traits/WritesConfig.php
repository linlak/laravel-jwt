<?php
namespace Linlak\Jwt\Traits;

use Illuminate\Support\Str;

trait WritesConfig
{
    /**
     * Get the .env file path.
     *
     * @return string
     */
    protected function envPath()
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }

        // check if laravel version Less than 5.4.17
        if (version_compare($this->laravel->version(), '5.4.17', '<')) {
            return $this->laravel->basePath() . DIRECTORY_SEPARATOR . '.env';
        }

        return $this->laravel->basePath('.env');
    }

    /**
     * Check if the modification is confirmed.
     *
     * @return bool
     */
    protected function isConfirmed($msg = 'This will invalidate all existing tokens. Are you sure you want to override the secret key?')
    {
        return $this->option('force') ? true : $this->confirm($msg);
    }

    /**
     * Display the key.
     *
     * @param  string  $key
     *
     * @return void
     */
    protected function displayKey($key)
    {
        $this->laravel['config']['linjwt.secret'] = $key;

        $this->info("jwt-auth secret [$key] set successfully.");
    }
    protected function addkey($path, $key, $value)
    {
        file_put_contents($path, PHP_EOL . "$key=$value", FILE_APPEND);

        $this->info('.env file updated successfully [' . $key . '=' . $value . ']');
    }
    protected function repkey($path, $key, $value, $conf_item)
    {
        // create new entry
        file_put_contents($path, str_replace(
            $key . '=' . $this->laravel['config']['linjwt.' . $conf_item],
            $key . '=' . $value,
            file_get_contents($path)
        ));

        $this->info($key . ' has been replaced from ' . $this->laravel['config']['linjwt.' . $conf_item] . ' to ' . $value);
    }
}
