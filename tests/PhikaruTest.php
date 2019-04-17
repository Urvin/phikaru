<?php

namespace urvin\phikaru\tests;

use PHPUnit\Framework\TestCase;
use urvin\phikaru\Phikaru;
use urvin\phikaru\UrlBuilder;

class PhikaruTest extends TestCase
{
    const DEFAULT_URL = 'http://hikaru.local';
    const DEFAULT_SALT = 'not_safe';
    const DEFAULT_FILENAME = 'test_filename';
    const DEFAULT_EXTENSION = 'webp';

    /**
     * @var PrivateAccessor
     */
    protected $accessor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accessor = new PrivateAccessor();
    }

    public function test__construct()
    {
        $phikaru = new Phikaru(self::DEFAULT_URL, self::DEFAULT_SALT);
        $this->assertEquals(
            $this->accessor->getPropertyValue($phikaru, 'baseUrl'),
            self::DEFAULT_URL
        );
        $this->assertEquals(
            $this->accessor->getPropertyValue($phikaru, 'signatureSalt'),
            self::DEFAULT_SALT
        );
    }

    public function test__constructFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $phikaru = new UrlBuilder('', '');
    }

    public function testThumbnail()
    {
        $phikaru = new Phikaru(self::DEFAULT_URL, self::DEFAULT_SALT);
        $this->assertEquals(
            (string)$phikaru->thumbnail(100,200,300, self::DEFAULT_FILENAME, self::DEFAULT_EXTENSION),
            'http://hikaru.local/609ab718f07d21cfb53e4a6bf3bc462b/100x200/300/test_filename.webp'
        );
    }

    public function testUploadFailDestination()
    {
        $this->expectException(\InvalidArgumentException::class);
        $phikaru = new Phikaru(self::DEFAULT_URL, self::DEFAULT_SALT);
        $phikaru->upload('', '');
    }

    public function testUploadFailSource()
    {
        $this->expectException(\InvalidArgumentException::class);
        $phikaru = new Phikaru(self::DEFAULT_URL, self::DEFAULT_SALT);
        $phikaru->upload('destination', '');
    }

    public function testUploadFailSourceFile()
    {
        $this->expectException(\RuntimeException::class);
        $phikaru = new Phikaru(self::DEFAULT_URL, self::DEFAULT_SALT);
        $phikaru->upload('destination', '/path/to/file.xyz');
    }

    public function testRemoveFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $phikaru = new Phikaru(self::DEFAULT_URL, self::DEFAULT_SALT);
        $phikaru->remove('');
    }
}
