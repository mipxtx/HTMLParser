<?php
/**
 * @author: mix
 * @date: 20.01.15
 */

namespace HTMLParser;

class StringParser
{

    private $counter = 0;

    private $string;

    private $lenght = 0;

    private $noEndNodes = ["meta", "br", "input", "hr", "img"];

    private $ccline = 0;

    private $ccpos = 0;

    function __construct($string) {
        $this->string = $string;
        $this->lenght = strlen($string);
    }

    public function current() {
        if ($this->counter > $this->lenght) {
            throw new UnexpectedEndException;
        }

        return $this->string[$this->counter];
    }

    public function equal($string) {
        $len = mb_strlen($string);
        $eq = "";
        for ($i = $this->counter; $i < $this->counter + $len; $i++) {
            $eq .= $this->string[$i];
        }

        return strtolower($eq) == $string;
    }

    public function getNext() {
        return $this->string[$this->counter + 1];
    }

    public function step($value) {
        $this->counter += $value;
        if ($this->current() == "\n") {
            $this->ccline += $value;
            $this->ccpos = 0;
        } else {
            $this->ccpos += $value;
        }
    }

    public function next($count = 1) {
        for ($i = 0; $i < $count; $i++) {
            $this->step(1);
        }
    }

    public function back($count) {
        for ($i = 0; $i < $count; $i++) {
            $this->step(-1);
        }
    }

    public function isEnd() {
        return $this->counter > count($this->string);
    }

    public function parse() {
        $head = "";
        while ($this->current() != ">") {

            if (in_array($this->current(), ["'", '"'])) {
                $head .= $this->parseQuoted();
            } else {
                $head .= $this->current();
            }
            $this->next();
        }
        $head .= $this->current();
        $this->next();

        while ($this->current() != "<") {
            $this->next();
        }

        $doc = new Document($head);

        try {
            $doc->addChild($this->parseNode());
        } catch (UnexpectedEndException $e) {
            echo "unexpected end\n";
        }

        return $doc;
    }

    public function parseComment() {
        $this->next(3);
        $body = "";
        while (!$this->equal("-->")) {
            $body .= $this->current();
            $this->next();
        }
        $cc = new Comment($body);
        $this->next(3);

        return $cc;
    }

    public function parseNode() {

        $this->next();
        if ($this->current() == "!") {
            $node = $this->parseComment();
        } else {

            $this->skipWhiteSpaces();
            $nodeName = $this->parseName();
            $this->skipWhiteSpaces();

            $node = new Element($nodeName);

            while ($this->current() != ">" && $this->current() != "/") {
                $this->skipWhiteSpaces();
                $attrName = $this->parseName();

                $attr = new Attribute($attrName);

                $this->skipWhiteSpaces();

                if ($this->current() == "=") {
                    $this->next();
                    $this->skipWhiteSpaces();
                    $value = $this->parseQuoted();
                    $attr->setValue($value);
                }
                $node->addAttribute($attr);
            }

            $this->skipWhiteSpaces();

            if ($this->current() == "/") {
                $this->next();
                if ($this->current() == ">") {
                    $this->next();
                }
            } else {
                $this->next();

                if (!in_array(strtolower($nodeName), $this->noEndNodes)) {
                    $this->parseBody($node);
                }
            }
        }

        return $node;
    }

    public function parseBody(Element $node) {
        $nodeName = $node->getName();
        do {

            if ($this->current() != "<" || ($nodeName == "script" && !$this->equal("</script>"))) {
                $text = "";
                while ($this->current() != "<" || ($nodeName == "script" && !$this->equal("</script>"))) {
                    $text .= $this->current();
                    $this->next();
                }
                if (trim($text)) {
                    $textNode = new Text($text);
                    $node->addChild($textNode);
                }
            } elseif ($this->current() == "<" && $this->getNext() != "/") {
                $childNode = $this->parseNode();
                $node->addChild($childNode);
            } else {
                $this->next(2);
                $endName = $this->parseName();
                if ($endName != $nodeName) {
                    echo "unexpected end of '$endName', $nodeName expected at $this->ccline:$this->ccpos\n";
                    $this->back(strlen("</$endName"));
                } else {
                    $this->skipWhiteSpaces();
                    $this->next();
                }

                break;
            }
        } while (true);
    }

    public function parseQuoted() {
        if (!in_array($this->current(), ["'", '"'])) {
            return $this->parseName();
        }

        $out = "";

        $end = $this->current();
        $this->next();
        while ($this->current() != $end) {
            $out .= $this->current();
            $this->next();
        }
        $this->next();

        return $out;
    }

    public function skipWhiteSpaces() {
        while (in_array($this->current(), [" ", "\t", "\r", "\n"])) {
            $this->next();
        }
    }

    public function parseName() {
        $cc = $this->current();
        $out = "";
        while (preg_match("/[a-zA-Z0-9]/", $cc)) {
            $out .= $cc;
            $this->next();
            $cc = $this->current();
        }

        return $out;
    }
}