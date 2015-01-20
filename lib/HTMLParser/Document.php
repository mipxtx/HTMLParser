<?php
/**
 * @author: mix
 * @date: 20.01.15
 */

namespace HTMLParser;

class Document implements HasChildrenInterface
{

    private $head;

    private $root;

    public function __construct($head) {
        $this->head = $head;
    }

    public function addChild($node) {
        $this->root = $node;
    }

    public function getName() {
        return "document";
    }

    public function getParent() {
        return null;
    }
}