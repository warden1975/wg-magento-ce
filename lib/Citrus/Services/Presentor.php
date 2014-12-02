<?php

/**
 * Class Citrus_Services_Presentor
 */
class Citrus_Services_Presentor implements Citrus_Interfaces_Presentor
{

    /**
     * @var Citrus_Interfaces_Formatter
     */
    protected $formatter;

    /**
     * @var array
     */
    protected $children;

    /**
     * @param $formatter
     * @param array $children
     */
    public function __construct($formatter, $children = array())
    {
        $this->formatter = $formatter;
        $this->children = $children;
    }

    /**
     * Add child objects here which will be iterated over by the formatter
     * @param Citrus_Interfaces_Builder $child
     */
    public function addChild(Citrus_Interfaces_Builder $child)
    {
            $this->children[] = $child->build();
    }

    /**
     * Format the data from children and return the data
     * @return mixed
     */
    public function build()
    {
        return $this->formatter->format($this->children);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->children);
    }
    
}

