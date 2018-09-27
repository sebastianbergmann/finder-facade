<?php declare(strict_types=1);
/*
 * This file is part of the finder-facade package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\FinderFacade;

use PHPUnit\Framework\TestCase;

/**
 * @covers \SebastianBergmann\FinderFacade\FinderFacade
 *
 * @uses \SebastianBergmann\FinderFacade\Configuration
 */
final class FinderFacadeTest extends TestCase
{
    /**
     * @var string
     */
    private $fixtureDir;

    protected function setUp(): void
    {
        $this->fixtureDir = __DIR__ . \DIRECTORY_SEPARATOR . 'fixture' . \DIRECTORY_SEPARATOR;
    }

    public function testFilesCanBeFoundBasedOnConstructorArguments(): void
    {
        $facade = new FinderFacade(
            [$this->fixtureDir, $this->fixtureDir . 'bar.phtml'],
            ['bar'],
            ['*.php'],
            ['*.fail.php']
        );

        $this->assertEquals(
            [
                $this->fixtureDir . 'bar.phtml',
                $this->fixtureDir . 'foo' . \DIRECTORY_SEPARATOR . 'bar.php',
            ],
            $facade->findFiles()
        );
    }

    public function testFilesCanBeFoundBasedOnXmlConfiguration(): void
    {
        $facade = new FinderFacade;
        $facade->loadConfiguration($this->fixtureDir . 'test.xml');

        $this->assertEquals(
            [
                $this->fixtureDir . 'bar.phtml',
                $this->fixtureDir . 'foo' . \DIRECTORY_SEPARATOR . 'bar.php',
            ],
            $facade->findFiles()
        );
    }
}
