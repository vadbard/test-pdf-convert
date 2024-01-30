<?php

namespace App\Console\Commands;

use App\Services\ConvertPdf\ImageCutService;
use App\Services\ConvertPdf\PdfConvertService;
use Illuminate\Console\Command;

class ConvertPdf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:pdf {file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Эмуляция бурной деятельности для попапа заказа дизайн-проекта';
    
    public function handle(PdfConvertService $convertService, ImageCutService $cutService)
    {
        $fileName = $convertService->convert($this->argument('file'));

        $result = $cutService->cut($fileName);

        foreach ($result as $resultItem) {
            $this->info($resultItem);
        }
    }
}