<?php

namespace urvin\phikaru\tests;

use PHPUnit\Framework\TestCase;
use urvin\phikaru\UrlBuilder;

class UrlBuilderTest extends TestCase
{
    const DEFAULT_URL = 'http://hikaru.local';
    const DEFAULT_URL_2 = 'http://hikaru.local/images';
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
        $builder = new UrlBuilder(self::DEFAULT_URL, self::DEFAULT_SALT);
        $this->assertEquals(
            $this->accessor->getPropertyValue($builder, 'baseUrl'),
            self::DEFAULT_URL
        );
        $this->assertEquals(
            $this->accessor->getPropertyValue($builder, 'signatureSalt'),
            self::DEFAULT_SALT
        );
    }

    public function test__constructOtherUrls()
    {
        $builder = new UrlBuilder(self::DEFAULT_URL_2, self::DEFAULT_SALT);
        $this->assertEquals(
            $this->accessor->getPropertyValue($builder, 'baseUrl'),
            self::DEFAULT_URL_2
        );

        $builder = new UrlBuilder(self::DEFAULT_URL_2 . '/', self::DEFAULT_SALT);
        $this->assertEquals(
            $this->accessor->getPropertyValue($builder, 'baseUrl'),
            self::DEFAULT_URL_2
        );
    }

    public function test__constructFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $builder = new UrlBuilder('', '');
    }

    public function testConstruct()
    {
        $builder = UrlBuilder::construct(self::DEFAULT_URL, self::DEFAULT_SALT);
        $this->assertEquals(
            $this->accessor->getPropertyValue($builder, 'baseUrl'),
            self::DEFAULT_URL
        );
        $this->assertEquals(
            $this->accessor->getPropertyValue($builder, 'signatureSalt'),
            self::DEFAULT_SALT
        );
    }


    public function testExtension()
    {
        $builder = new UrlBuilder(self::DEFAULT_URL, self::DEFAULT_SALT);
        $builder->extension(self::DEFAULT_EXTENSION);
        $this->assertEquals(
            $this->accessor->getPropertyValue($builder, 'extension'),
            self::DEFAULT_EXTENSION
        );
    }

    public function testExtensionFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $builder = new UrlBuilder(self::DEFAULT_URL, self::DEFAULT_SALT);
        $builder->extension('');
    }

    public function testFilename()
    {
        $builder = new UrlBuilder(self::DEFAULT_URL, self::DEFAULT_SALT);
        $builder->filename(self::DEFAULT_FILENAME);
        $this->assertEquals(
            $this->accessor->getPropertyValue($builder, 'filename'),
            self::DEFAULT_FILENAME
        );
    }

    public function testFilenameFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $builder = new UrlBuilder(self::DEFAULT_URL, self::DEFAULT_SALT);
        $builder->filename('');
    }


    public function testWidth()
    {
        $builder = new UrlBuilder(self::DEFAULT_URL, self::DEFAULT_SALT);
        $builder->width(100500);
        $this->assertEquals(
            $this->accessor->getPropertyValue($builder, 'width'),
            100500
        );
    }

    public function testWidthFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $builder = new UrlBuilder(self::DEFAULT_URL, self::DEFAULT_SALT);
        $builder->width(-100);
    }

    public function testHeight()
    {
        $builder = new UrlBuilder(self::DEFAULT_URL, self::DEFAULT_SALT);
        $builder->height(100500);
        $this->assertEquals(
            $this->accessor->getPropertyValue($builder, 'height'),
            100500
        );
    }

    public function testHeightFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $builder = new UrlBuilder(self::DEFAULT_URL, self::DEFAULT_SALT);
        $builder->height(-100);
    }

    public function testCast()
    {
        $builder = new UrlBuilder(self::DEFAULT_URL, self::DEFAULT_SALT);

        $builder->cast(2);
        $this->assertEquals(
            $this->accessor->getPropertyValue($builder, 'cast'),
            2
        );

        $builder->cast(4);
        $this->assertEquals(
            $this->accessor->getPropertyValue($builder, 'cast'),
            6
        );

        $builder->cast(22);
        $this->assertEquals(
            $this->accessor->getPropertyValue($builder, 'cast'),
            22
        );

        $builder->cast(0);
        $this->assertEquals(
            $this->accessor->getPropertyValue($builder, 'cast'),
            0
        );
    }

    public function testCastFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $builder = new UrlBuilder(self::DEFAULT_URL, self::DEFAULT_SALT);
        $builder->cast(-100);
    }

    public function testSignature()
    {
        $builder = new UrlBuilder(self::DEFAULT_URL, self::DEFAULT_SALT);
        $builder->filename(self::DEFAULT_FILENAME);
        $builder->extension(self::DEFAULT_EXTENSION);
        $this->assertEquals(
            $this->accessor->getPropertyMethodResult($builder, 'signature', []),
            '95988922a646dede48653a6ff2675a81'
        );
    }

    public function test__toString()
    {
        $builder = new UrlBuilder(self::DEFAULT_URL, self::DEFAULT_SALT);
        $builder->filename(self::DEFAULT_FILENAME);
        $builder->extension(self::DEFAULT_EXTENSION);
        $this->assertEquals(
            (string)$builder,
            'http://hikaru.local/95988922a646dede48653a6ff2675a81/0x0/0/test_filename.webp'
        );
    }

}
