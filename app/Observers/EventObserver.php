<?php

namespace App\Observers;

use App\Modules\Backend\Events\Models\Event;

class EventObserver
{
    /**
     * Handle the event "created" event.
     *
     * @param  \App\Event  $event
     * @return void
     */
    public function created(Event $event)
    {
        if ($event->background_image != 'default-background.jpg') {
            $name = explode(".", $event->background_image);

            $event->add_media_from_disk($name[0], $event->background_image);
        }
    }

    /**
     * Handle the event "updated" event.
     *
     * @param  \App\Event  $event
     * @return void
     */
    public function updated(Event $event)
    {
        //
    }

    /**
     * Handle the event "deleted" event.
     *
     * @param  \App\Event  $event
     * @return void
     */
    public function deleted(Event $event)
    {
        if (!($event->getMedia('event-images'))->isEmpty()) {
            $event->clearMediaCollection('event-images');
        }
    }

    /**
     * Handle the event "restored" event.
     *
     * @param  \App\Event  $event
     * @return void
     */
    public function restored(Event $event)
    {
        //
    }

    /**
     * Handle the event "force deleted" event.
     *
     * @param  \App\Event  $event
     * @return void
     */
    public function forceDeleted(Event $event)
    {
        //
    }
}
