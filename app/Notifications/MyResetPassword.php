<?php

namespace CodeShopping\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MyResetPassword extends Notification
{
  /**
   * The password reset token.
   *
   * @var string
   */
  public $token;

  /**
   * Create a notification instance.
   *
   * @param  string  $token
   * @return void
   */
  public function __construct($token)
  {
    $this->token = $token;
  }

  /**
   * Get the notification's channels.
   *
   * @param  mixed  $notifiable
   * @return array|string
   */
  public function via($notifiable)
  {
    return ['mail'];
  }

  /**
   * Build the mail representation of the notification.
   *
   * @param  mixed  $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    return (new MailMessage)
      ->line('Você está recebendo este e-mail porque recebemos uma requisição para uma redefinição de senha em sua conta')
      ->action('Redefina sua senha', url(config('app.url').route('password.reset', $this->token, false)))
      ->line('Caso você não tenha solicitado esta redefinição, ignore esta mensagem!');
  }
}
