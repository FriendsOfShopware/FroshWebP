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
    private $collectionsToUse;

    /**
     * @var array
     */
    private $collectionsToIgnore;

    /**
     * @var int
     */
    private $stack;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var bool
     */
    private $force;

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
    public function getCollectionsToUse(): array
    {
        return $this->collectionsToUse;
    }

    /**
     * @return array
     */
    public function getCollectionsToIgnore(): array
    {
        return $this->collectionsToIgnore;
    }

    /**
     * @return int
     */
    public function getStack(): int
    {
        return $this->stack;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * @return bool
     */
    public function isForce(): bool
    {
        return $this->force;
    }
}
