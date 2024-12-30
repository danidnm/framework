<?php

namespace ByDN\Framework;

class Phrase implements \JsonSerializable
{
    /**
     * String for rendering
     *
     * @var string
     */
    private $text;

    /**
     * Arguments for placeholder values
     *
     * @var array
     */
    private $arguments;

    /**
     * Phrase construct
     *
     * @param string $text
     * @param array $arguments
     */
    public function __construct($text, array $arguments = [])
    {
        $this->text = (string)$text;
        $this->arguments = $arguments;
    }

    /**
     * Get phrase base text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Get phrase message arguments
     *
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Render phrase
     *
     * @return string
     */
    public function render()
    {
        try {

            $text = $this->getText();
            $arguments = $this->getArguments();

            if ($arguments) {
                $placeholders = array_map([$this, 'keyToPlaceholder'], array_keys($arguments));
                $pairs = array_combine($placeholders, $arguments);
                $text = strtr($text, $pairs);
            }

            return $text;

        }catch (\Throwable $e) {
            return $this->getText();
        }
    }

    /**
     * Match argument to placeholder
     *
     * @param $key
     * @return string
     */
    private function keyToPlaceholder($key)
    {
        return '%' . (is_int($key) ? (string)($key + 1) : $key);
    }

    /**
     * Defers rendering to the last possible moment (when converted to string)
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @return string
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->render();
    }
}
