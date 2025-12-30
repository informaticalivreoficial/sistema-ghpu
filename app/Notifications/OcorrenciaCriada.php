<?php

namespace App\Notifications;

use App\Models\Ocorrencia;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OcorrenciaCriada extends Notification
{
    use Queueable;

    public $ocorrencia;

    public function __construct(Ocorrencia $ocorrencia)
    {
        $this->ocorrencia = $ocorrencia;
    }

    public function via($notifiable)
    {
        return ['database']; // ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Nova ocorrência cadastrada: ' . $this->ocorrencia->title,
            'ocorrencia_id' => $this->ocorrencia->id,
            'type' => $this->ocorrencia->type,
            'user_name' => $this->ocorrencia->user->name ?? 'Desconhecido',
            'created_at' => $this->ocorrencia->created_at->format('d/m/Y H:i'),
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => 'Nova ocorrência cadastrada: ' . $this->ocorrencia->title,
            'ocorrencia_id' => $this->ocorrencia->id,
        ];
    }

    

    // public function toDatabase($notifiable)
    // {
    //     return [
    //         'title' => 'Nova ocorrência criada',
    //         'message' => 'Uma nova ocorrência foi cadastrada.',
    //         'ocorrencia_id' => $this->ocorrencia->id,
    //         'type' => 'ocorrencia',
    //     ];
    // }

    
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //         ->subject('Nova ocorrência cadastrada')
    //         ->line('Uma nova ocorrência foi criada.')
    //         ->action('Ver ocorrência', url('/admin/ocorrencias/ocorrencia/'.$this->ocorrencia->id));
    // }

   
    // public function toArray(object $notifiable): array
    // {
    //     return [
    //         //
    //     ];
    // }
}
