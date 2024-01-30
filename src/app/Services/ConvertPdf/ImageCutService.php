<?php

namespace App\Services\ConvertPdf;

use Illuminate\Support\Facades\File;
use Imagick;

class ImageCutService
{
    const CUT_WIDTH = 256;
    const CUT_HEIGHT = 256;

    const CUT_X_STEPS = 10;
    const CUT_Y_STEPS = 6;

    const FILENAME_SUFFIX = '_256';

    protected string $fileName;
    protected string $saveDir;

    /**
     * @var string[]
     */
    protected array $croppedFileNames;

    public function __construct(private readonly Imagick $imagick)
    {
    }

    /**
     * @param string $filename
     * @return string[]
     * @throws \ImagickException
     */
    public function cut(string $filename): array
    {
        $this->fileName = $filename;

        $this->makeSaveDir();

        $this->imagick->readImage($this->fileName);

        $i = 0;
        for ($xStep = 0; $xStep < static::CUT_X_STEPS; $xStep++) {
            for ($yStep = 0; $yStep < static::CUT_Y_STEPS; $yStep++) {
                $i++;

                $imagickForCrop = $this->imagick->clone();

                $x = $xStep * static::CUT_WIDTH;
                $y = $yStep * static::CUT_HEIGHT;

                $imagickForCrop->cropImage(static::CUT_WIDTH, static::CUT_HEIGHT, $x, $y);

                $croppedFileName = $this->saveDir . '/' . $i . static::FILENAME_SUFFIX;
                $imagickForCrop->writeImage($croppedFileName);

                $this->croppedFileNames[] = $croppedFileName;
            }
        }

        File::delete($this->fileName);

        return $this->croppedFileNames;
    }

    protected function makeSaveDir(): void
    {
        $pathinfo = pathinfo($this->fileName);

        $this->saveDir = $pathinfo['dirname'] . '/' . $pathinfo['filename'];

        File::ensureDirectoryExists($this->saveDir);
    }
}