<?php

namespace urvin\phikaru;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Phikaru
{
    const UPLOAD_URI = 'upload';
    const REMOVE_URI = 'remove';

    /**
     * @var string
     */
    protected $baseUrl;
    /**
     * @var string
     */
    protected $signatureSalt;
    /**
     * @var Client|null
     */
    protected $http = null;

    /**
     * Phikaru constructor.
     * @param string $baseUrl
     * @param string $signatureSalt
     */
    public function __construct(string $baseUrl, string $signatureSalt)
    {
        if(empty($baseUrl)) {
            throw new \InvalidArgumentException("Base URL should not be empty");
        }

        $this->baseUrl = $baseUrl;
        $this->signatureSalt = $signatureSalt;
    }

    /**
     * @return Client
     */
    protected function http(): Client
    {
        if(empty($this->http)) {
            $this->http = new Client([
                'base_uri' => $this->baseUrl
            ]);
        }
        return $this->http;
    }

    /**
     * Generate thumbnail url via UrlBuilder
     * @param int|null $width
     * @param int|null $height
     * @param int|null $cast
     * @param string|null $filename
     * @param string|null $extension
     * @return UrlBuilder
     */
    public function thumbnail(?int $width = null, ?int $height = null, ?int $cast = null, ?string $filename = null, ?string $extension = null): UrlBuilder
    {
        $builder = UrlBuilder::construct($this->baseUrl, $this->signatureSalt);

        if(!empty($width)) {
            $builder->width($width);
        }
        if(!empty($height)) {
            $builder->height($height);
        }
        if(!empty($cast)) {
            $builder->cast($cast);
        }
        if(!empty($filename)) {
            $builder->filename($filename);
        }
        if(!empty($extension)) {
            $builder->extension($extension);
        }

        return $builder;
    }

    /**
     * @param string $destinationFilename
     * @param string $sourceFilename
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function upload(string $destinationFilename, string $sourceFilename): void
    {
        if(empty($destinationFilename)) {
            throw new \InvalidArgumentException("Destination filename should not be empty");
        }

        if(empty($sourceFilename)) {
            throw new \InvalidArgumentException("Source filename should not be empty");
        }

        if(!is_readable($sourceFilename)) {
            throw new \RuntimeException('Source file is not readable');
        }

        $fileHandler = fopen($sourceFilename, 'r');

        if(!$fileHandler) {
            throw new \RuntimeException('Could not read source file');
        }

        try {
            $this->http()->put(static::UPLOAD_URI . '/' . $destinationFilename, [
                'body' => $fileHandler
            ]);
        } catch (RequestException $e) {
            throw new Exception('Upload exception: ' . $e->getMessage(), 0, $e);
        } finally {
            if(is_resource($fileHandler)) {
                fclose($fileHandler);
            }
        }
    }

    /**
     * @param string $filename
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public function remove(string $filename): void
    {
        if(empty($filename)) {
            throw new \InvalidArgumentException("Filename should not be empty");
        }

        try {
            $this->http()->delete(static::REMOVE_URI . '/' . $filename);
        } catch (RequestException $e) {
            throw new Exception('Remove exception: ' . $e->getMessage(), 0, $e);
        }
    }
}