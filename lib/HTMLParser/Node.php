<?php
/**
 * @author: mix
 * @date: 20.01.15
 */

namespace HTMLParser;

abstract class Node
{

    private $parent;

    /**
     * @param mixed $parent
     */
    public function setParent($parent) {
        $this->parent = $parent;
    }

    public function getParent() {
        return $this->parent;
    }
}