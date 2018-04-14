<?php

namespace App\Components;

use App\Models\Notification;

class NotificationCollection
{
    /**
     * @var Notification[]|array
     */
    private $notifications;

    /**
     * NotificationCollection constructor.
     * @param Notification[] $notifications
     */
    public function __construct(array $notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * Checks if collection is empty
     * @return bool
     */
    public function empty(): bool
    {
        return count($this->notifications) === 0;
    }

    /**
     * @return array
     */
    public function all(): array
    {
        return $this->notifications;
    }
}
