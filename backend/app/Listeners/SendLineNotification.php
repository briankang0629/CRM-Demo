<?php

namespace App\Listeners;

use App\Events\AdminLogin;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Phattarachai\LineNotify\Facade\Line as LineNotify;

class SendLineNotification
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
     * line notify 的設定檔在 config/line-notify
     *
     * @param  AdminLogin  $event
     * @return void
     */
    public function handle(AdminLogin $event)
    {
//        config(['line-notify.access_token' => '']); //動態取代
        if (!is_local()) {
            LineNotify::send($event->notifyMessage());
        }
    }
}
