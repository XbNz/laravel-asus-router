<?php


namespace XbNz\AsusRouter\Console;


use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use XbNz\AsusRouter\Router;

class SetupCommand extends Command
{
    protected $signature = 'merlin:setup';
    
    protected $description = 'Quick setup for router configurations.';

    public function handle()
    {
        if (File::exists(config_path('router-config.php'))){
            $confirm = $this->confirm('You already have a router configuration file in your config directory. Overwrite and proceed?', false);

            if ($confirm === false){
                return 0;
            }

            File::delete(config_path('router-config.php'));
        }

        $data = [];
        $data['username'] = $this->ask('Enter your router\'s username');
        $data['ip'] = $this->ask('Enter your router\'s IP address', '192.168.50.1');
        $data['port'] = $this->ask('Enter your router\'s SSH port', '22');
        $data['timeout'] = $this->ask('Connection timeout (seconds)', '1');

        $validator = Validator::make($data, [
            'username' => '',
            'ip' => ['required'],
            'port' => ['required', 'numeric', 'min:1', 'max:65535'],
            'timeout' => ['required', 'numeric', 'min:0'],
        ]);


        try {
            $validated = $validator->validate();
        } catch (ValidationException $e) {
            foreach (array_keys($e->validator->failed()) as $failure) {
                $this->error("You provided an unacceptable {$failure} value");
            }
            return 0;
        }

        $this->call('vendor:publish', [
            '--tag' => 'router-config',
        ]);

        $this->info('Configuration file created. Please manually paste the values below in your .env file');
        $this->info(
            "ROUTER_USER={$validated['username']}" . PHP_EOL .
            "ROUTER_IP={$validated['ip']}" . PHP_EOL .
            "ROUTER_PORT={$validated['port']}" . PHP_EOL .
            "ROUTER_TIMEOUT={$validated['timeout']}" . PHP_EOL
        );

    }
}