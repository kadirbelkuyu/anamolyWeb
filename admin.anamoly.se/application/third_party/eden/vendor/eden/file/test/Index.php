<?php //-->
/**
 * This file is part of the Eden PHP Library.
 * (c) 2014-2016 Openovate Labs
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

class EdenFileIndexTest extends PHPUnit_Framework_TestCase
{
    public function testIsFile() 
    {
        $this->assertFalse(eden('file')->set(__DIR__.'/foobar')->isFile());
        $this->assertTrue(eden('file')->set(__FILE__)->isFile());
    }

    public function testGetBase() 
    {
        $this->assertEquals('Index', eden('file')->set(__FILE__)->getBase());
    }

    public function testGetContent() 
    {
        $content = eden('file')->set(__DIR__.'/assets/test.txt')->getContent();
        $this->assertEquals('test', $content);
    }

    public function testGetData() 
    {
        $data = eden('file')->set(__DIR__.'/assets/tagalog.php')->getData();
        $this->assertArrayHasKey('How are you?', $data);
    }

    public function testGetExtension() 
    {
        $this->assertEquals('php', eden('file')->set(__FILE__)->getExtension());
    }

    public function testGetFolder() 
    {
        $this->assertEquals(str_replace('\\', '/', __DIR__), eden('file')->set(__FILE__)->getFolder());
    }

    public function testGetMime() 
    {
        $mime = eden('file')->set(__DIR__.'/assets/stars.gif')->getMime();
        $this->assertEquals('image/gif', $mime);
    }

    public function testGetName() 
    {
        $this->assertEquals('Index.php', eden('file')->set(__FILE__)->getName());
    }

    public function testGetSize() 
    {
        $this->assertTrue(is_int(eden('file')->set(__FILE__)->getSize()));
    }

    public function testGetTime() 
    {
        $this->assertTrue(is_int(eden('file')->set(__FILE__)->getTime()));
    }

    public function testSetContent() 
    {
        eden('file')
            ->set(__DIR__.'/assets/content.txt')
            ->setContent('test2');

        $content = eden('file')
            ->set(__DIR__.'/assets/content.txt')
            ->getContent();

        $this->assertEquals('test2', $content);
    }

    public function testSetData() 
    {
        $data = array('foo' => 'bar');
        eden('file')
            ->set(__DIR__.'/assets/data.php')
            ->setData($data);

        $data = eden('file')
            ->set(__DIR__.'/assets/data.php')
            ->getData();

        $this->assertArrayHasKey('foo', $data);
    }

    public function testRemove() 
    {
        $class = eden('file')
            ->set(__DIR__.'/assets/data.php')
            ->remove();

        $this->assertInstanceOf('Eden\\File\\Index', $class);

        $this->assertFalse(file_exists(__DIR__.'/assets/data.php'));

        $class = eden('file')
            ->set(__DIR__.'/assets/content.txt')
            ->remove();

        $this->assertFalse(file_exists(__DIR__.'/assets/content.txt'));
    }

    public function touch() 
    {
        $class = eden('system')
            ->file(realpath(__DIR__.'/../assets').'/content.txt')
            ->touch();

        $this->assertInstanceOf('Eden\\File\\Index', $class);

        $this->assertTrue(file_exists(__DIR__.'/../assets/content.txt'));

        eden('system')
            ->file(realpath(__DIR__.'/../assets').'/content.txt')
            ->remove();
    }
}