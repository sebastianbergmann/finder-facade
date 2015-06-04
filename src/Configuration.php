<?php
/*
 * This file is part of the Comparator package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SebastianBergmann\FinderFacade;

use TheSeer\fDOM\fDOMDocument;

/**
 * <code>
 * <fileset>
 *   <include>
 *    <directory>/path/to/directory</directory>
 *    <file>/path/to/file</file>
 *   </include>
 *   <exclude>/path/to/directory</exclude>
 *   <name>*.php</name>
 * </fileset>
 * </code>
 *
 * @author    Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright Sebastian Bergmann <sebastian@phpunit.de>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link      http://github.com/sebastianbergmann/finder-facade/tree
 * @since     Class available since Release 1.0.0
 */
class Configuration
{
    /**
     * @var string
     */
    protected $basePath;

    /**
     * @var fDOMDocument
     */
    protected $xml;

    /**
     * @param string $file
     */
    public function __construct($file)
    {
        $this->basePath = dirname($file);

        $this->xml = new fDOMDocument;
        $this->xml->load($file);
    }

    /**
     * @param  string $xpath
     * @return array
     */
    public function parse($xpath = '')
    {
        $result = array(
            'items'                     => array(),
            'excludes'                  => array(),
            'names'                     => array(),
            'notNames'                  => array(),
            'regularExpressionExcludes' => array()
        );

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

    /**
     * @param  string $path
     * @return string
     */
    protected function toAbsolutePath($path)
    {
        // Check whether the path is already absolute.
        if ($path[0] === '/' || $path[0] === '\\' || (strlen($path) > 3 && ctype_alpha($path[0]) &&
            $path[1] === ':' && ($path[2] === '\\' || $path[2] === '/'))) {
            return $path;
        }

        // Check whether a stream is used.
        if (strpos($path, '://') !== false) {
            return $path;
        }

        return $this->basePath . DIRECTORY_SEPARATOR . $path;
    }
}
