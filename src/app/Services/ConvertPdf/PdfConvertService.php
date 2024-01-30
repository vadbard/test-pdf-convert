<?php

namespace App\Services\ConvertPdf;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Imagick;

class PdfConvertService
{
    const WHOLE_WIDTH = 2560;
    const WHOLE_HEIGHT = 1536;

    const RESOLUTION = 144;

    const DIR_NAME = 'convert';

    public function __construct(private Imagick $imagick)
    {
        File::ensureDirectoryExists(Storage::path(static::DIR_NAME));
    }

    public function convert(string $pathToPdf)
    {
        $this->imagick->setResolution(static::RESOLUTION, static::RESOLUTION);

        $this->imagick->readImage($pathToPdf);

        $this->imagick->mergeImageLayers(\Imagick::LAYERMETHOD_FLATTEN);

        $this->imagick->setFormat('jpg');

        if ($this->imagick->getImageHeight() > $this->imagick->getImageWidth()) {
            $this->imagick->rotateImage('#000', 90);
        }

        $this->makeLandscape();

        $this->imagick->resizeImage(static::WHOLE_WIDTH, static::WHOLE_HEIGHT, \Imagick::FILTER_GAUSSIAN, 0, true);

        $this->setCanvas();

        $filePath = Storage::path(static::DIR_NAME . '/' . Str::ulid() . '.jpg');

        $this->imagick->writeImage($filePath);

        return $filePath;
    }

    protected function makeLandscape(): void
    {
        if ($this->imagick->getImageHeight() < $this->imagick->getImageWidth()) {
            return;
        }

        $this->imagick->rotateImage('#000', 90);
    }

    protected function setCanvas(): void
    {
        if ($this->imagick->getImageWidth() === static::WHOLE_WIDTH && $this->imagick->getImageHeight() === static::WHOLE_HEIGHT) {
            return;
        }

        $canvas = $this->imagick->clone();
        $canvas->newImage(static::WHOLE_WIDTH, static::WHOLE_HEIGHT, 'green', 'jpg' );

        $canvas->compositeImage($this->imagick, imagick::COMPOSITE_OVER, 0, 0);

        $this->imagick = $canvas;

    }
}