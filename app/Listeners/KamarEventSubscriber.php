<?php

namespace App\Listeners;

use App\Events\KamarSewaEvent;
use App\Events\SewaKamarEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;

class KamarEventSubscriber
{
    public function kamarDisewakan(SewaKamarEvent $event): void
    {

        // TODO: dimana buat nyatet tagihan ketika pindah baru?
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): void
    {
        // $events->listen(
        //     SewaKamarEvent::class,
        //     [KamarEventSubscriber::class, 'kamarDisewakan']
        // );

    }
}
