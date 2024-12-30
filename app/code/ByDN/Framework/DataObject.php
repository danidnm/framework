<?php

namespace ByDN\Framework;

abstract class DataObject
{
    const USE_PARENT = true;
    const NO_PARENT = false;

    /**
     * Stores method name to data key array translation
     *
     * @var array
     */
    protected static $_methodNameToKeyCache = [];

    /**
     * Data array
     *
     * @var array
     */
    private $_data = [];

    /**
     * Add data to model
     *
     * @param array $arr
     * @return $this
     */
    public function addData(array $arr)
    {
        if ($this->_data === []) {
            $this->setData($arr);
            return $this;
        }
        foreach ($arr as $index => $value) {
            $this->setData($index, $value);
        }
        return $this;
    }

    /**
     * Set data with key
     * If key is array, it will replace the internal data
     *
     * @param $key
     * @param $value
     * @return $this
     */
    public function setData($key, $value = null)
    {
        if ($key === (array)$key) {
            $this->_data = $key;
        }
        else if (stripos($key, '/') !== false) {
            $this->setDataWithPath($key, $value);
        }
        else {
            $this->_data[$key] = $value;
        }
        return $this;
    }

    /**
     * Saves new data into array with path like 'a/b/c'
     *
     * @param $path
     * @param $value
     * @return void
     */
    public function setDataWithPath($path, $value = null)
    {
        $currentPointer = &$this->_data;
        $keys = explode('/', $path);
        foreach ($keys as $key) {
            if (!is_array($currentPointer)) {
                $currentPointer = [];
            }
            if (!isset($currentPointer[$key])) {
                $currentPointer[$key] = '';
            }
            $currentPointer = &$currentPointer[$key];
        }
        $currentPointer = $value;
    }

    /**
     * Get data by key
     *
     * @param $key
     * @return array|mixed|null
     */
    public function getData($key = '', $useParent = false, $default = '')
    {
        if ('' === $key) {
            return $this->_data;
        }

        if (is_array($key) || strpos($key, '/') !== false) {
            return $this->getDataByPath($key, $useParent, $default);
        }

        $data = $this->_data[$key] ?? null;
        if ($data === null && $key !== null && strpos($key, '/') !== false) {
            $data = $this->getDataByPath($key, $useParent, $default);
        }

        return $data;
    }

    /**
     * Get data by path like 'a/b/c'
     *
     * @param $path
     * @return array|mixed|null
     */
    public function getDataByPath($path, $useParent, $default)
    {
        $currentPointer = &$this->_data;
        $keys = explode('/', $path);
        $lastKey = end($keys);
        while (count($keys) > 0) {

            // Extract next key
            $key = array_shift($keys);

            // If we are allowed to use parent values and the last key is set on this level, save it
            // in case of no value is found later
            if (is_array($currentPointer) && ($useParent == self::USE_PARENT) && isset($currentPointer[$lastKey])) {
                $default = $currentPointer[$lastKey];
            }

            // If the key is not set, we will return an empty string...
            if (!isset($currentPointer[$key])) {
                $currentPointer[$key] = '';
            }

            // Get the value or pass to the next instance
            if ($currentPointer instanceof \Framework\DataObject) {
                return $currentPointer->getDataByPath($keys);
            }
            else {
                $currentPointer = &$currentPointer[$key];
            }
        }
        if ($currentPointer == '') {
            $currentPointer = $default;
        }
        return $currentPointer;
    }

    /**
     * Removes data from data array by key
     *
     * @param $key
     * @return $this
     */
    public function unsetData($key = null)
    {
        if ($key === null) {
            $this->setData([]);
        } elseif (is_string($key)) {
            if (isset($this->_data[$key]) || array_key_exists($key, $this->_data)) {
                unset($this->_data[$key]);
            }
        } elseif ($key === (array)$key) {
            foreach ($key as $element) {
                $this->unsetData($element);
            }
        }
        return $this;
    }

    /**
     * Check if key exists in data array
     *
     * @param $key
     * @return bool
     */
    public function hasData($key = '')
    {
        if (empty($key) || !is_string($key)) {
            return !empty($this->_data);
        }
        return array_key_exists($key, $this->_data);
    }

    /**
     * Translates method name like "setMyValue" into the corresponding key "my_value" in the data array
     *
     * @param $name
     * @return mixed|string
     */
    protected function methodNameToKey($name)
    {
        if (isset(self::$_methodNameToKeyCache[$name])) {
            return self::$_methodNameToKeyCache[$name];
        }

        $result = strtolower(
            trim(
                preg_replace(
                    '/([A-Z]|[0-9]+)/',
                    "_$1",
                    lcfirst(
                        substr(
                            $name,
                            3
                        )
                    )
                ),
                '_'
            )
        );

        self::$_methodNameToKeyCache[$name] = $result;
        return $result;
    }

    /**
     * Magic methods "set", "get", "uns" and "has"
     *
     * @param $method
     * @param $args
     * @return $this|array|bool|mixed|null
     * @throws \ByDN\Framework\Exception
     */
    public function __call($method, $args)
    {
        // Compare 3 first letters of the method name
        switch ($method[0] . ($method[1] ?? '') . ($method[2] ?? '')) {
            case 'get':
                if (isset($args[0]) && $args[0] !== null) {
                    return $this->getData(
                        self::$_methodNameToKeyCache[$method] ?? $this->methodNameToKey($method),
                        $args[0]
                    );
                }

                return $this->getData(
                    self::$_methodNameToKeyCache[$method] ?? $this->methodNameToKey($method),
                    $args[0] ?? null
                );
            case 'set':
                return $this->setData(
                    self::$_methodNameToKeyCache[$method] ?? $this->methodNameToKey($method),
                    $args[0] ?? null
                );
            case 'uns':
                return $this->unsetData(
                    self::$_methodNameToKeyCache[$method] ?? $this->methodNameToKey($method)
                );
            case 'has':
                return isset(
                    $this->_data[
                        self::$_methodNameToKeyCache[$method] ?? $this->methodNameToKey($method)
                    ]
                );
        }

        throw new \ByDN\Framework\Exception(
            'Invalid method %1::%2', [get_class($this), $method]
        );
    }
}
