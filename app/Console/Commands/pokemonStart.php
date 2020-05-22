<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \DB;
use Validator;
use \Artisan;

class pokemonStart extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pokemon:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pokemon Finder installation';

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
        try{
            Artisan::call('config:cache');

            $this->info("");

            $this->info("this is the pokemon finder");

            $this->info("");
            $this->info("so...");
            $this->info("");

            $this->info("do you want some pokemon? lets do it!");

            $dbuser = $this->validate_cmd(function() {
                return $this->ask('world (database) user?', 'root');
            }, ['user','required']);

            $dbpassword = $this->secret('world (database) password? (optional)', '');
            
            $dbname = $this->validate_cmd(function() {
                return $this->ask('world name?', 'pokemonfinder');
            }, ['name','required']);


            $bar = $this->output->createProgressBar(3);


            $this->info("");

            $this->info("setting env variable!");
            $bar->start();

            Artisan::call('env:set db_username '.$dbuser.'');
            Artisan::call('env:set db_password '.$dbpassword.'');
            Artisan::call('env:set db_database '.$dbname.'');

            $this->info("");

            $this->info("env variables saved succefully!");

            $bar->advance();

            $schemaName = $dbname ?: config("database.connections.mysql.database");

            $charset = config("database.connections.mysql.charset",'utf8mb4');

            $collation = config("database.connections.mysql.collation",'utf8mb4_unicode_ci');
    
            config(["database.connections.mysql.database" => null]);
    
            $query = "CREATE DATABASE IF NOT EXISTS $schemaName CHARACTER SET $charset COLLATE $collation;";
    
            DB::statement($query);
    
            config(["database.connections.mysql.database" => $schemaName]);

            $pdo = DB::connection()->getPdo();

            if($pdo){

                $this->info("Database '$dbname' created.");
                
            } else {

                $this->info("Failed to create the database.");

            }

            $this->info("");

            $this->info("migrate database...");

            $bar->advance();

            $this->info("");

            Artisan::call('config:cache');

            Artisan::call('migrate --seed');

            Artisan::output();

            $this->info("migrate success...");

            $bar->advance();

            Artisan::call('key:generate');

            Artisan::call('passport:install');

            
            $this->info("");

            $this->info("pokemon finder ready. gotta catch them all!");

         }
         catch (\Exception $e){
             $this->error($e->getMessage());
         }
    }

    public function putPermanentEnv($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $str .= "\n"; // In case the searched variable is in the last line without \n
        $keyPosition = strpos($str, "{$envKey}=");
        $endOfLinePosition = strpos($str, PHP_EOL, $keyPosition);
        $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
        $str = substr($str, 0, -1);

        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);
    }

    public function validate_cmd($method, $rules)
    {
        $value = $method();
        $validate = $this->validateInput($rules, $value);

        if ($validate !== true) {
            $this->warn($validate);
            $value = $this->validate_cmd($method, $rules);
        }
        return $value;
    }

    public function validateInput($rules, $value)
    {

        $validator = Validator::make([$rules[0] => $value], [ $rules[0] => $rules[1] ]);

        if ($validator->fails()) {

            $error = $validator->errors();
            return $error->first($rules[0]);

        }else{

            return true;

        }

    }
}
