<?php
namespace Craft;

class TruncatorVariable
{
    public function truncate($input, $length)
    {
        $tags = $this->buildTags($input);

        $length = $this->setLength($input, $length);

        $string = substr(strip_tags($input), 0, $length);

        if(strlen($string) < $length) {
            echo $string;
        }

        $string = $this->replaceTags($string, $tags);

        echo $string . $this->closeTags($string) . "...";
    }

    /**
     * @param string $input
     *
     * @return string
     */
    public function stripTags($input)
    {
        return strip_tags($input);
    }

    /**
     * Reads through the supplied string and deposits all HTML tags into
     * the returned array.
     *
     * @param string $input
     *
     * @return array
     */
    public function buildTags($input)
    {
        $tags = [];

        for($i = 0; $i < strlen($input); $i++) {
            if((substr($input, $i, 1) == '<') !== false) {
                $j = strpos($input, '>', $i) + 1;
                $tags[$i] = substr($input, $i, $j - $i);
            }
        }

        return $tags;
    }

    /**
     * Checks the cutoff point of the input string and ensures it will not
     * slice any words. If so, it will shorten the string so as to not go
     * over the desired length.
     *
     * @param string $input
     * @param int $length
     *
     * @return int
     */
    public function setLength($input, $length)
    {
        $check = false;

        while($check == false && $length > 0) {
            if(
                preg_match('/[^a-z0-9"\']$/i', substr(strip_tags($input), 0, $length)) ||
                (
                    preg_match('/[a-z0-9"\']$/i', substr(strip_tags($input), 0, $length))
                    && preg_match('/[^a-z0-9"\']$/i', substr(strip_tags($input), 0, $length + 1)))) {
                $check = true;
            }
            else {
                $length--;
            }
        }

        return $length;        
    }

    /**
     * Re-isnerts the appropriate tags into the output string.
     *
     * @param string $string
     * @param array $tags
     *
     * @return string
     */
    public function replaceTags($string, $tags)
    {
        foreach($tags as $key => $value) {
            if((substr($value, 1, 1) == '/') && ($key <= strlen($string))) {
                $string = substr_replace($string, $value, $key, 0);
            }
            if((substr($value, 1, 1) != '/') && ($key < strlen($string))) {
                $string = substr_replace($string, $value, $key, 0);
            }
        }

        return $string;        
    }

    /**
     * Ensures there are no HTML tags in the string. If it finds
     * any, it closes them.
     *
     * @param string $input
     *
     * @return $string;
     */
    public function closeTags($input)
    {
        $tags = $this->buildTags($input);

        $closers = [];

        foreach($tags as $value1) {
            if(substr($value1, 1, 1) !== '/') {
                $match = false;
                foreach($tags as $key2 => $value2) {
                    if(substr_replace($value1, '/', 1, 0) == $value2) {
                        unset($tags[$key2]);
                        $match = true;
                    }
                }
                if($match == false) {
                    $closers[] = $value1;
                }
            }
        }

        foreach($closers as $key => $value) {
            $space = strpos($value, ' ');
            $equal = strpos($value, '=');

            if($space !== false && $equal !== false) {
                $break = $space < $equal ? $space : $equal;
            }
            else if($space !== false) {
                $break = $space;
            }
            else if($equal !== false) {
                $break = $equal;
            }
            else {
                $break = strlen($value) - 1;
            }

            $closers[$key] = "</" . substr($value, 1, $break - 1) . ">";
        }

        return implode(array_reverse($closers));
    }
}