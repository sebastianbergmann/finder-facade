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

use TheSeer\fDOM\fDOMDocument;

final class Configuration
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * @var fDOMDocument
     */
    private $xml;

    public function __construct(string $file)
    {
        $this->basePath = \dirname($file);

        $this->xml = new fDOMDocument;
        $this->xml->load($file);
    }

    public function parse(string $xpath = ''): array
    {
        $result = [
            'items'                     => [],
            'excludes'                  => [],
            'names'                     => [],
            'notNames'                  => [],
            'regularExpressionExcludes' => [],
        ];

        foreach ($this->xml->getDOMXPath()->query($xpath . 'include/directory') as $item) {
            $result['items'][] = $this->toAbsolutePath($item->nodeValue);
        }

        foreach ($this->xml->getDOMXPath()->query($xpath . 'include/file') as $item) {
            $result['items'][] = $this->toAbsolutePath($item->nodeValue);
        }

        foreach ($this->xml->getDOMXPath()->query($xpath . 'exclude') as $exclude) {
            $result['excludes'][] = $exclude->nodeValue;
        }

        foreach ($this->xml->getDOMXPath()->query($xpath . 'name') as $name) {
            $result['names'][] = $name->nodeValue;
        }

        foreach ($this->xml->getDOMXPath()->query($xpath . 'notName') as $notName) {
            $result['notNames'][] = $notName->nodeValue;
        }

        foreach ($this->xml->getDOMXPath()->query($xpath . 'regularExpressionExcludes') as $regularExpressionExclude) {
            $result['regularExpressionExcludes'][] = $regularExpressionExclude->nodeValue;
        }

        return $result;
    }

    private function toAbsolutePath(string $path): string
    {
        // Check whether the path is already absolute.
        if ($path[0] === '/' || $path[0] === '\\' || (\strlen($path) > 3 && \ctype_alpha($path[0]) &&
            $path[1] === ':' && ($path[2] === '\\' || $path[2] === '/'))) {
            return $path;
        }

        // Check whether a stream is used.
        if (\strpos($path, '://') !== false) {
            return $path;
        }

        return $this->basePath . \DIRECTORY_SEPARATOR . $path;
    }
}
