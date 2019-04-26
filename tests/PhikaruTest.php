<?php

namespace urvin\phikaru\tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use urvin\phikaru\Exception;
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

    public function testUpload()
    {
        $mock = new MockHandler([
            new Response(201, ['Content-Length' => 0]),
            new Response(500, ['Content-Length' => 0]),
        ]);

        $handler = HandlerStack::create($mock);
        $phikaru = new Phikaru(self::DEFAULT_URL, self::DEFAULT_SALT);
        $filename = realpath(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'file.txt');

        $client = new Client([
            'handler' => $handler,
            'base_uri' => self::DEFAULT_URL
        ]);

        $this->accessor->setPropertyValue($phikaru, 'http', $client);

        $this->assertNull(
            $phikaru->upload('destination', $filename)
        );

        $this->expectException(Exception::class);
        $phikaru->upload('destination', $filename);
    }

    public function testRemoveFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $phikaru = new Phikaru(self::DEFAULT_URL, self::DEFAULT_SALT);
        $phikaru->remove('');
    }

    public function testRemove()
    {
        $mock = new MockHandler([
            new Response(200, ['Content-Length' => 0]),
            new Response(500, ['Content-Length' => 0]),
        ]);

        $handler = HandlerStack::create($mock);
        $phikaru = new Phikaru(self::DEFAULT_URL, self::DEFAULT_SALT);

        $client = new Client([
            'handler' => $handler,
            'base_uri' => self::DEFAULT_URL
        ]);

        $this->accessor->setPropertyValue($phikaru, 'http', $client);

        $this->assertNull(
            $phikaru->remove('destination')
        );

        $this->expectException(Exception::class);
        $phikaru->remove('destination');
    }
}
