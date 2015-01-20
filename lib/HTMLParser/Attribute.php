<?php
/**
 * @author: mix
 * @date: 20.01.15
 */

namespace HTMLParser;

class Attribute extends Node
{

    private $name;

    private $value;

    public function __construct($name) {

        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value) {
        $this->value = $value;
    }
}