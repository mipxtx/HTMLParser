<?php
/**
 * @author: mix
 * @date: 20.01.15
 */

namespace HTMLParser;

class Comment extends Node
{

    private $comment;

    function __construct($comment) {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getComment() {
        return $this->comment;
    }
}