<?php
/**
 * @author: mix
 * @date: 20.01.15
 */

namespace HTMLParser;

class Element extends Node implements HasChildrenInterface
{

    private $name;

    private $attributes = [];

    private $children = [];

    function __construct($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    public function addAttribute(Attribute $attr) {
        $this->attributes[] = $attr;
        return $this;
    }

    /**
     * @return Attribute[]
     */
    public function getAttributes() {
        return $this->attributes;
    }

    public function addChild($child) {
        $this->children[] = $child;
        return $this;
    }
}