<?php //-->
/**
 * This file is part of the Eden PHP Library.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */
 
class EdenPathIndexTest extends PHPUnit_Framework_TestCase
{
	public function testAbsolute() 
    {
		try {
			eden('path')->set('some/path/')->absolute();
		} catch(Exception $e) {
			$this->assertInstanceOf('Eden\\Path\\Exception', $e);	
		}
		
		$class = eden('path')->set(__FILE__)->absolute();
		$this->assertInstanceOf('Eden\\Path\\Index', $class);
    }
	
    public function testAppend() 
    {
		$path = eden('path')->set('some/path/')->append('foo');
		$this->assertEquals('/some/path/foo', (string) $path);
    }

    public function testGetArray() 
    {
		$array = eden('path')->set('some/path/')->getArray();
		$this->assertTrue(in_array('some', $array));
		$this->assertTrue(in_array('path', $array));
    }

    public function testPrepend() 
    {
		$path = eden('path')->set('some/path/')->prepend('foo');
		$this->assertEquals('/foo/some/path', (string) $path);
    }

    public function testPop()
    {
		$this->assertEquals('path', eden('path')->set('some/path/')->pop());
    }

    public function testReplace() 
    {
		$path = eden('path')->set('some/path/')->replace('foo');
		$this->assertEquals('/some/foo', (string) $path);
    }
	
	public function testArrayAccess() 
	{
		$path = eden('path')->set('some/path/');
		$this->assertEquals('some', $path[1]);
		$path['replace'] = 'foo';
		$this->assertEquals('foo', $path['last']);
	}
}
