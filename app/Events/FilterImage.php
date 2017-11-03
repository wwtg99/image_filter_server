<?php

namespace App\Events;

use App\Services\ImageFilter;
use Illuminate\Broadcasting\Channel;
use Illuminate\Http\UploadedFile;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class FilterImage
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var ImageFilter
     */
    protected $imageFilter;

    /**
     * Create a new event instance.
     *
     * @param ImageFilter $imageFilter
     */
    public function __construct(ImageFilter $imageFilter)
    {
        $this->imageFilter = $imageFilter;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    /**
     * @return ImageFilter
     */
    public function getImageFilter()
    {
        return $this->imageFilter;
    }

}
