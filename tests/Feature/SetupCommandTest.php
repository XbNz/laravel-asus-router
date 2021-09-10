<?php


namespace XbNz\AsusRouter\Tests\Feature;


use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use XbNz\AsusRouter\Tests\TestCase;

class SetupCommandTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        File::delete(config_path('router-config.php'));
    }


    /** @test */
    public function running_the_setup_command_for_the_first_time_creates_a_config_file()
    {
        $this->artisan('merlin:setup');
        $this->assertFileExists(config_path('router-config.php'));
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
            ->expectsConfirmation('You already have a router configuration file in your config directory. Overwrite and proceed?', 'yes')->assertExitCode(0);

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
    public function providing_valid_answers_saves_config_file_with_entries()
    {

        $command = $this->artisan('merlin:setup')
            ->expectsQuestion('Enter your router\'s username', 'ASUS')
            ->expectsQuestion('Enter your router\'s IP address', '192.168.50.1')
            ->expectsQuestion('Enter your router\'s SSH port', '22')
            ->expectsQuestion('Connection timeout (seconds)', '1')
            ->execute();

        dd(File::get(config_path('router-config.php')));

    }
}