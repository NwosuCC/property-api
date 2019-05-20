<?php

namespace App\Listeners;

use App\Events\HouseSaved;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class BroadcastNewHouseNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  HouseSaved  $event
     * @return void
     */
    public function handle(HouseSaved $event)
    {
        info('HouseTest [id = "' . $event->house->id . '"] HouseSaved $event handled in BroadcastNewHouseNotification::class');
    }
}
