<?php

namespace App\Events;

use App\Models\Users\Admin;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminLogin
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $admin;

    private $notifyMessage;

    const MESSAGE = '
    【 管理者登入通報 】
        登入網址：%s
        管理者：%s
        登入時間：%s
        IP：%s
    ';

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
        $this->notifyMessage = vsprintf(
            self::MESSAGE, array(
                full_domain(),
                $admin->name,
                $admin->last_login_time,
                $admin->ip,
            )
        );
    }

    /**
     * notifyMessage
     * @return string
     */
    public function notifyMessage() {
        return $this->notifyMessage;
    }
}
