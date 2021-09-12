<?php


namespace XbNz\AsusRouter\Tests\Unit;


use Illuminate\Support\Facades\Crypt;
use phpseclib3\Crypt\RSA;
use Spatie\Ssh\Ssh;
use Symfony\Component\Process\Process;
use XbNz\AsusRouter\Router;
use XbNz\AsusRouter\RouterSetup;
use XbNz\AsusRouter\Tests\TestCase;

class SystemTest extends TestCase
{
    /** @test */
    public function it_returns_valid_ssh_keys()
    {
        $processMock = $this->createMock(Process::class);
        $processMock->method('getOutput')
            ->willReturn(
                "ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQCXjVwinnGM/KCGGcKxrnt1XKk5aTMRlp+lJmCdcBlOh+sLFs/kqjwBPN0Hf2N9yzntUMOQQOAIrr/1yM+nznGOJ9b6s3veq+xw7RY6+MfsR+SZxz4np5Vu2yXskJZTeAxQcN5MeyLxtOBFaOjPWepHMbpP/2XS7DGblhYEt5JQabdDBcjKGa4Vgl9hNAhQN2Oh375blL23J0vlpyc2eIlh9dJDFxyHBYwiAGlQKeqTZLFQ/C2gaTvIaVA7RcfmQG9lLoQ+AqnTtudIU1KGJK6zFN3RQn2PtF+OZ8TxJwFxnq1AAOPFriEOK56Ji/DUyn03Yv7Cc5LkKFDG3W6KYriN=
                
                random stuff that should be eliminated
                
                ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABAQCXjVwinnGM/KCGGcKxrnt1XKk5aTMRlp+lJmCdcBlOh+sLFs/kqjwBPN0Hf2N9yzntUMOQQOAIrr/1yM+nznGOJ9b6s3veq+xw7RY6+MfsR+SZxz4np5Vu2yXskJZTeAxQcN5MeyLxtOBFaOjPWepHMbpP/2XS7DGblhYEt5JQabdDBcjKGa4Vgl9hNAhQN2Oh375blL23J0vlpyc2eIlh9dJDFxyHBYwiAGlQKeqTZLFQ/C2gaTvIaVA7RcfmQG9lLoQ+AqnTtudIU1KGJK6zFN3RQn2PtF+OZ8TxJwFxnq1AAOPFriEOK56Ji/DUyn03Yv7Cc5LkKFDG3W6KYriN="
            );

        $processMock->method('isSuccessful')
            ->willReturn(true);

        $sshMock = $this->mock(Ssh::class);
        $sshMock->shouldReceive('execute')
            ->andReturn($processMock);

        $router = new Router();
        $keyList = $router->system()->getRsaKeyList();
        RSA::load($keyList[0]);
        RSA::load($keyList[1]);
    }
}