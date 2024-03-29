<?php

namespace extend\pinyin;

use Closure;

/**
 * Memory Dict File loader.
 */
class MemoryFileDictLoader implements DictLoaderInterface
{
    /**
     * Data directory.
     *
     * @var string
     */
    protected $path;

    /**
     * Words segment name.
     *
     * @var string
     */
    protected $segmentName = 'words_%s';

    /**
     * Segment files.
     *
     * @var array
     */
    protected $segments = array();

    /**
     * Surname cache.
     *
     * @var array
     */
    protected $surnames = array();

    /**
     * Constructor.
     *
     * @param string $path
     */
    public function __construct($path)
    {
        $this->path = $path;

        for ($i = 0; $i < 100; ++$i) {
            $segment = $path.'/'.sprintf($this->segmentName, $i);

            if (file_exists($segment)) {
                $this->segments[] = (array) include $segment;
            }
        }
    }

    /**
     * Load dict.
     *
     * @param Closure $callback
     */
    public function map(Closure $callback)
    {
        foreach ($this->segments as $dictionary) {
            $callback($dictionary);
        }
    }

    /**
     * Load surname dict.
     *
     * @param Closure $callback
     */
    public function mapSurname(Closure $callback)
    {
        if (empty($this->surnames)) {
            $surnames = $this->path.'/surnames';

            if (file_exists($surnames)) {
                $this->surnames = (array) include $surnames;
            }
        }

        $callback($this->surnames);
    }
}
