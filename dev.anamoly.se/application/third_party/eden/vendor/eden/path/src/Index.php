<?php //-->
/**
 * This file is part of the Eden PHP Library.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace Eden\Path;

/**
 * General available methods for common pathing issues
 *
 * @package  Eden
 * @category Path
 * @author   Christian Blanquera <cblanquera@openovate.com>
 * @standard PSR-2
 */
class Index extends Base implements \ArrayAccess
{

    const ERROR_FULL_PATH_NOT_FOUND = 'The path %s or %s was not found.';

    /**
     * Preset and auto format the path
     *
     * @param string|null $path the path string
     *
     * @return void
     */
    public function __construct($path = null)
    {
        //argument 1 must be scalar
        Argument::i()->test(1, 'string', 'null');
        
        if ($path) {
            parent::__construct($this->format($path));
        }
    }

    /**
     * When object to string just give the path
     *
     * @return string
     */
    public function __toString()
    {
        return $this->data;
    }

    /**
     * Attempts to get the full absolute path
     * as described on the server. The path
     * given must exist.
     *
     * @param string|null $root The root path
     *
     * @return Eden\Path\Index
     */
    public function absolute($root = null)
    {
        //argument 1 must be a string or null
        Argument::i()->test(1, 'string', 'null');

        //if path is a directory or file
        if (is_dir($this->data) || is_file($this->data)) {
            return $this;
        }

        //if root is null
        if (is_null($root)) {
            //assume the root is doc root
            $root = $_SERVER['DOCUMENT_ROOT'];
        }

        //get the absolute path
        $absolute = $this->format($this->format($root).$this->data);

        //if absolute is a directory or file
        if (is_dir($absolute) || is_file($absolute)) {
            $this->data = $absolute;
            return $this;
        }

        //if we are here then it means that no path was found so we should throw an exception
        Exception::i()
            ->setMessage(self::ERROR_FULL_PATH_NOT_FOUND)
            ->addVariable($this->data)
            ->addVariable($absolute)
            ->trigger();
    }

    /**
     * Adds a path to the existing one
     *
     * @param *string $path The extra path to append
     *
     * @return Eden\Path\Index
     */
    public function append($path)
    {
        //argument 1 must be a string
        $argument = Argument::i()->test(1, 'string');

        //each argument will be a path
        $paths = func_get_args();

        //for each path
        foreach ($paths as $i => $path) {
            //check for type errors
            $argument->test($i + 1, $path, 'string');
            //add to path
            $this->data .= $this->format($path);
        }

        return $this;
    }

    /**
     * Returns the path array
     *
     * @return array
     */
    public function getArray()
    {
        return explode('/', $this->data);
    }

    /**
     * isset using the ArrayAccess interface
     *
     * @param *scalar|null|bool $offset The key to test if exists
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        //argument 1 must be scalar, null or bool
        Argument::i()->test(1, 'scalar', 'null', 'bool');
        
        return in_array($offset, $this->getArray());
    }

    /**
     * returns data using the ArrayAccess interface
     *
     * @param *scalar|null|bool $offset The key to get
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        //argument 1 must be scalar, null or bool
        Argument::i()->test(1, 'scalar', 'null', 'bool');
        
        $pathArray = $this->getArray();

        if ($offset == 'first') {
            $offset = 0;
        }

        if ($offset == 'last') {
            $offset = count($pathArray) - 1;
        }

        if (is_numeric($offset)) {
            return isset($pathArray[$offset]) ? $pathArray[$offset] : null;
        }

        return null;
    }

    /**
     * Sets data using the ArrayAccess interface
     *
     * @param *scalar|null|bool $offset The key to set
     * @param mixed             $value  The value the key should be set to
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        //argument 1 must be scalar, null or bool
        Argument::i()->test(1, 'scalar', 'null', 'bool');
        
        if (is_null($offset)) {
            $this->append($value);
        } else if ($offset == 'prepend') {
            $this->prepend($value);
        } else if ($offset == 'replace') {
            $this->replace($value);
        } else {
            $pathArray = $this->getArray();
            if ($offset > 0 && $offset < count($pathArray)) {
                $pathArray[$offset] = $value;
                $this->data = implode('/', $pathArray);
            }
        }
    }

    /**
     * unsets using the ArrayAccess interface
     *
     * @param *scalar|null|bool $offset The key to unset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
    }

    /**
     * Adds a path before the existing one
     *
     * @param *string $path The path to prepend
     *
     * @return Eden\Path\Index
     */
    public function prepend($path)
    {
        //argument 1 must be a string
        $error = Argument::i()->test(1, 'string');

        //each argument will be a path
        $paths = func_get_args();

        //for each path
        foreach ($paths as $i => $path) {
            //check for type errors
            $error->test($i + 1, $path, 'string');
            //add to path
            $this->data = $this->format($path).$this->data;
        }

        return $this;
    }

    /**
     * Remove the last path
     *
     * @return Eden\Path\Index
     */
    public function pop()
    {
        //get the path array
        $pathArray = $this->getArray();

        //remove the last
        $path = array_pop($pathArray);

        //set path
        $this->data = implode('/', $pathArray);

        return $path;
    }

    /**
     * Replaces the last path with this one
     *
     * @param *string $path replaces the last path with this
     *
     * @return Eden\Path\Index
     */
    public function replace($path)
    {
        //argument 1 must be a string
        Argument::i()->test(1, 'string');

        //get the path array
        $pathArray = $this->getArray();

        //pop out the last
        array_pop($pathArray);

        //push in the new
        $pathArray[] = $path;

        //assign back to path
        $this->data = implode('/', $pathArray);

        return $this;
    }

    /**
     * Formats the path
     * 1. Must start with forward slash
     * 2. Must not end with forward slash
     * 3. Must not have double forward slashes
     *
     * @param *string $path The path to format
     *
     * @return string
     */
    protected function format($path)
    {
        //replace back slash with forward
        $path = str_replace('\\', '/', $path);

        //replace double forward slash with 1 forward slash
        $path = str_replace('//', '/', $path);

        //if there is a last forward slash
        if (substr($path, -1, 1) == '/') {
            //remove it
            $path = substr($path, 0, -1);
        }

        //if the path does not start with a foward slash
        //and the path does not have a colon
        //(this is a test for windows)
        if (substr($path, 0, 1) != '/' && !preg_match("/^[A-Za-z]+\:/", $path)) {
            //it's safe to add a slash
            $path = '/'.$path;
        }

        return $path;
    }
}
