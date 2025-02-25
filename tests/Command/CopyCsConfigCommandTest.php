<?php

declare(strict_types=1);

namespace Clearfacts\Bundle\CodestyleBundle\Tests\Command;

use Clearfacts\Bundle\CodestyleBundle\Command\CopyCsConfigCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use PhpCsFixer\Config;

class CopyCsConfigCommandTest extends TestCase
{
    private const PHPCS_PATH = __DIR__ . '/.php-cs-fixer.dist.php';

    public function setUp(): void
    {
        @unlink(self::PHPCS_PATH);
    }

    public function tearDown(): void
    {
        @unlink(self::PHPCS_PATH);
    }

    public function testExecute(): void
    {
        // Given
        $application = new Application();
        $application->add(new CopyCsConfigCommand());

        $command = $application->find('clearfacts:codestyle:copy-cs-config');
        $commandTester = new CommandTester($command);
        
        // When
        $commandTester->execute([
            '--root' => __DIR__,
        ]);

        // Then
        $commandTester->assertCommandIsSuccessful();
        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('[OK] Copied phpcs config', $output);
        $this->assertFileExists(self::PHPCS_PATH);
        $this->assertStringContainsString(Config::class, file_get_contents(self::PHPCS_PATH));
    }
}
