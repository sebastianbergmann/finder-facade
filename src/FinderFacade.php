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

use Symfony\Component\Finder\Finder;

/**
 * Convenience wrapper for Symfony's Finder component.
 *
 * @author    Sebastian Bergmann <sebastian@phpunit.de>
 * @copyright Sebastian Bergmann <sebastian@phpunit.de>
 * @license   http://www.opensource.org/licenses/BSD-3-Clause  The BSD 3-Clause License
 * @link      http://github.com/sebastianbergmann/finder-facade/tree
 * @since     Class available since Release 1.0.0
 */
class FinderFacade
{
    /**
     * @var array
     */
    protected $items = array();

    /**
     * @var array
     */
    protected $excludes = array();

    /**
     * @var array
     */
    protected $names = array();


    /**
     * @var array
     */
    protected $notNames = array();

    /**
     * @param array $items
     * @param array $excludes
     * @param array $names
     * @param array $notNames
     */
    public function __construct(array $items = array(), array $excludes = array(), array $names = array(), array $notNames = array())
    {
        $this->items    = $items;
        $this->excludes = $excludes;
        $this->names    = $names;
        $this->notNames = $notNames;
    }

    /**
     * @return array
     */
    public function findFiles()
    {
        $files   = array();
        $finder  = new Finder;
        $iterate = false;

        foreach ($this->items as $item) {
            if (!is_file($item)) {
                $finder->in($item);
                $iterate = true;
            } else {
                $files[] = realpath($item);
            }
        }

        foreach ($this->excludes as $exclude) {
            $finder->exclude($exclude);
        }

        foreach ($this->names as $name) {
            $finder->name($name);
        }

        foreach ($this->notNames as $notName) {
            $finder->notName($notName);
        }

        if ($iterate) {
            foreach ($finder as $file) {
                $files[] = $file->getRealpath();
            }
        }

        return $files;
    }

    /**
     * @param string $file
     */
    public function loadConfiguration($file)
    {
        $configuration = new Configuration($file);
        $configuration = $configuration->parse();

        $this->items    = $configuration['items'];
        $this->excludes = $configuration['excludes'];
        $this->names    = $configuration['names'];
        $this->notNames = $configuration['notNames'];
    }
}
