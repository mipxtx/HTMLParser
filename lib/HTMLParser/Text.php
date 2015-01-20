<?php
/**
 * @author: mix
 * @date: 20.01.15
 */

namespace HTMLParser;

class Text extends Node
{

    private $text;

    function __construct($text) {
        $this->text = $text;
    }
}