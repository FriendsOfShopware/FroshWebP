<?php

namespace FroshWebP\Components\ImageStack;

/**
 * Class Arguments
 * @package FroshWebP\Components\ImageStack
 */
class Arguments
{
    /**
     * @var array
     */
    private $collectionsToUse = [];

    /**
     * @var array
     */
    private $collectionsToIgnore = [];

    /**
     * @var int
     */
    private $stack = 0;

    /**
     * @var int
     */
    private $offset = 0;

    /**
     * @var bool
     */
    private $force = false;

    /**
     * Arguments constructor.
     *
     * @param array $collectionsToUse
     * @param array $collectionsToIgnore
     * @param int $stack
     * @param int $offset
     * @param bool $force
     */
    public function __construct($collectionsToUse, $collectionsToIgnore, $stack, $offset, $force)
    {
        $this->collectionsToUse = $collectionsToUse;
        $this->collectionsToIgnore = $collectionsToIgnore;
        $this->stack = $stack;
        $this->offset = $offset;
        $this->force = $force;
    }

    /**
     * @return array
     */
    public function getCollectionsToUse()
    {
        return $this->collectionsToUse;
    }

    /**
     * @return array
     */
    public function getCollectionsToIgnore()
    {
        return $this->collectionsToIgnore;
    }

    /**
     * @return int
     */
    public function getStack()
    {
        return $this->stack;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * @return bool
     */
    public function isForce()
    {
        return $this->force;
    }
}
