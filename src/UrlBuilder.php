<?php

namespace urvin\phikaru;

class UrlBuilder
{
    const WIDTH_HEIGHT_SEPARATOR = 'x';

    const CAST_RESIZE_TENSILE = 2;
    const CAST_RESIZE_PRECISE = 4;
    const CAST_RESIZE_INVERSE = 8;
    const CAST_TRIM = 16;
    const CAST_EXTENT = 32;
    const CAST_OPAGUE_BACKGROUND = 64;

    /**
     * @var string
     */
    protected $baseUrl;
    /**
     * @var string
     */
    protected $signatureSalt;
    /**
     * @var int
     */
    protected $width = 0;
    /**
     * @var int
     */
    protected $height = 0;
    /**
     * @var int
     */
    protected $cast = 0;
    /**
     * @var string
     */
    protected $filename = '';
    /**
     * @var string
     */
    protected $extension = '';

    /**
     * @param string $baseUrl
     * @param string $signatureSalt
     * @return UrlBuilder
     */
    public static function construct(string $baseUrl, string $signatureSalt)
    {
        return new static($baseUrl, $signatureSalt);
    }

    /**
     * UrlBuilder constructor.
     * @param string $baseUrl
     * @param string $signatureSalt
     */
    public function __construct(string $baseUrl, string $signatureSalt)
    {
        if(empty($baseUrl)) {
            throw new \InvalidArgumentException("Base URL should not be empty");
        }
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->signatureSalt = $signatureSalt;
    }

    /**
     * @param string $filename
     * @return UrlBuilder
     */
    public function filename(string $filename): UrlBuilder
    {
        if(empty($filename)) {
            throw new \InvalidArgumentException("Filename should not be empty");
        }
        $this->filename = $filename;

        return $this;
    }

    /**
     * @param string $extension
     * @return UrlBuilder
     */
    public function extension(string $extension): UrlBuilder
    {
        if(empty($extension)) {
            throw new \InvalidArgumentException("Extension should not be empty");
        }
        $this->extension = $extension;

        return $this;
    }

    /**
     * @param int $cast
     * @return UrlBuilder
     */
    public function cast(int $cast): UrlBuilder
    {
        if(empty($cast)) {
            $this->cast = 0;
        }
        elseif ($cast < 0) {
            throw new \InvalidArgumentException("Cast flag should not be less than nil");
        }
        else {
            $this->cast |= $cast;
        }

        return $this;
    }

    /**
     * @param int $value
     * @return UrlBuilder
     */
    public function width(int $value): UrlBuilder
    {
        if ($value < 0) {
            throw new \InvalidArgumentException("Width should not be less than nil");
        }
        $this->width = $value;
        return $this;
    }

    /**
     * @param int $value
     * @return UrlBuilder
     */
    public function height(int $value): UrlBuilder
    {
        if ($value < 0) {
            throw new \InvalidArgumentException("Width should not be less than nil");
        }
        $this->height = $value;
        return $this;
    }

    /**
     * @return string
     */
    protected function signature(): string
    {
        return md5($this->signatureSalt . $this->width . $this->height . $this->cast . $this->filename);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return join('/', [
            $this->baseUrl,
            $this->signature(),
            $this->width . self::WIDTH_HEIGHT_SEPARATOR . $this->height,
            $this->cast,
            $this->filename . '.' . $this->extension
        ]);
    }
}