<?php
/**
 * @author: mix
 * @date: 20.01.15
 */

namespace HTMLParser;

interface HasChildrenInterface
{
    public function addChild($node);

    public function getName();

    public function getParent();
}