<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmployeeCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    private $name;
    private $username;
    private $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($name, $username, $password)
    {
        $this->name = $name;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Dear '.ucwords($this->name).',')
                    ->subject('Your new account has been created')
                    ->line('Default Username: '.$this->username)
                    ->line('Default Password: '.$this->password)
                    ->line('You are allowed to change your username and password in the system')
                    ->action('Login', url(route('login')))
                    ->line('Thank you for using our Employee Management System!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
