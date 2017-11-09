<?php

namespace App\Listeners;

use App\Events\FilterImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ImageFilterListener implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * @var string
     */
    protected $cmd = '';

    /**
     * @var int
     */
    protected $thumbnailWidth = 300;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->cmd = config('services.image_filter');
    }

    /**
     * Handle the event.
     *
     * @param  FilterImage  $event
     * @return void
     */
    public function handle(FilterImage $event)
    {
        $cmd = [$this->cmd];
        $imgFilter = $event->getImageFilter();
        $input = $imgFilter->getInputRealPath();
        $cmd[] = "-i $input";
        $filter = $imgFilter->getFilter();
        if ($filter) {
            $cmd[] = "-f $filter";
        } else {
            $cmd[] = '--all-filters';
        }
        $output = $imgFilter->getOutputDirectoryRealPath();
        $cmd[] = "-o $output/";
        if ($imgFilter->isThumbnail()) {
            $cmd[] = "--width $this->thumbnailWidth";
        }
        if ($imgFilter->getParam()) {
            foreach ($imgFilter->getParam() as $k => $v) {
                $cmd[] = "-a $k=$v";
            }
        }
        //run image_filter
        $cmd = implode(' ', $cmd);
        logger($cmd);
        $out = [];
        exec($cmd, $out);
        logger()->info("===Image Filter Output: ", $out);
        Cache::put($imgFilter->getWd(), 2, 180);
    }
}
