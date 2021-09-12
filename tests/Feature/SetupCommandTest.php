<?php


namespace XbNz\AsusRouter\Tests\Feature;


use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use XbNz\AsusRouter\Router;
use XbNz\AsusRouter\Tests\TestCase;

class SetupCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        File::delete(config_path('router-config.php'));
    }


    /** @test */
    public function running_the_setup_command_subsequently_warns_the_user_of_overwrite_answer_no()
    {
        $this->artisan('vendor:publish', [
            '--tag' => 'router-config',
        ]);

        sleep(1);
        $modifiedDateBeforeCommand = File::lastModified(config_path('router-config.php'));


        $this->artisan('merlin:setup')
            ->expectsConfirmation('You already have a router configuration file in your config directory. Overwrite and proceed?', 'no')->assertExitCode(0);


        $modifiedDateAfterCommand = File::lastModified(config_path('router-config.php'));

        $this->assertEquals($modifiedDateBeforeCommand, $modifiedDateAfterCommand);
    }

    /** @test */
    public function running_the_setup_command_subsequently_warns_the_user_of_overwrite_answer_yes()
    {
        $this->artisan('vendor:publish', [
            '--tag' => 'router-config',
        ]);

        sleep(1);
        $modifiedDateBeforeCommand = File::lastModified(config_path('router-config.php'));

        $this->artisan('merlin:setup')
            ->expectsConfirmation('You already have a router configuration file in your config directory. Overwrite and proceed?', 'yes')
            ->expectsQuestion('Enter your router\'s username', 'ASUS')
            ->expectsQuestion('Enter your router\'s IP address', '192.168.50.1')
            ->expectsQuestion('Enter your router\'s SSH port', '22')
            ->expectsQuestion('Connection timeout (seconds)', '1');

        $modifiedDateAfterCommand = File::lastModified(config_path('router-config.php'));

        $this->assertNotEquals($modifiedDateBeforeCommand, $modifiedDateAfterCommand);
    }

    /** @test */
    public function providing_gibberish_returns_error_with_failed_questions()
    {

        $command = $this->artisan('merlin:setup')
            ->expectsQuestion('Enter your router\'s username', '')
            ->expectsQuestion('Enter your router\'s IP address', '')
            ->expectsQuestion('Enter your router\'s SSH port', '')
            ->expectsQuestion('Connection timeout (seconds)', '')
            ->expectsOutput('You provided an unacceptable ip value')
            ->expectsOutput('You provided an unacceptable port value')
            ->expectsOutput('You provided an unacceptable timeout value');
    }

    /** @test */
    public function providing_valid_answers_saves_config_file_and_prints_env_values()
    {

        $command = $this->artisan('merlin:setup')
            ->expectsQuestion('Enter your router\'s username', 'ASUS')
            ->expectsQuestion('Enter your router\'s IP address', '192.168.50.1')
            ->expectsQuestion('Enter your router\'s SSH port', '22')
            ->expectsQuestion('Connection timeout (seconds)', '1')
            ->expectsOutput('Configuration file created. Please manually paste the values below in your .env file')
            ->expectsOutput(
                "ROUTER_USER=ASUS" . PHP_EOL .
                "ROUTER_IP=192.168.50.1" . PHP_EOL .
                "ROUTER_PORT=22" . PHP_EOL .
                "ROUTER_TIMEOUT=1" . PHP_EOL
            );

    }

}