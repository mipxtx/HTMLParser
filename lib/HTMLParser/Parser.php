<?php
/**
 * @author: mix
 * @date: 20.01.15
 */

namespace HTMLParser;

class Parser
{
    public function parseFile($file) {
        return $this->parseString(file_get_contents($file));
    }

    public function parseString($string) {
        $parser = new StringParser($string);

        return $parser->parse();
    }
}