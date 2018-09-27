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
 * @covers \SebastianBergmann\FinderFacade\Configuration
 */
final class ConfigurationTest extends TestCase
{
    /**
     * @var string
     */
    private $fixtureDir;

    protected function setUp(): void
    {
        $this->fixtureDir = __DIR__ . \DIRECTORY_SEPARATOR . 'fixture' . \DIRECTORY_SEPARATOR;
    }

    public function testXmlFileCanBeParsed(): void
    {
        $configuration = new Configuration($this->fixtureDir . 'test.xml');

        $this->assertEquals(
            [
                'items' => [
                    $this->fixtureDir . 'foo',
                    $this->fixtureDir . 'bar.phtml',
                ],
                'excludes'                  => ['bar'],
                'names'                     => ['*.php'],
                'notNames'                  => ['*.fail.php'],
                'regularExpressionExcludes' => [],
            ],
            $configuration->parse()
        );
    }
}
