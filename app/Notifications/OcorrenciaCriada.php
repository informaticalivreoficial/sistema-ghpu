<?php

namespace App\Notifications;

use App\Models\Ocorrencia;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OcorrenciaCriada extends Notification
{
    use Queueable;

    public $ocorrencia;
    public $author;

    public function __construct(Ocorrencia $ocorrencia, User $author)
    {
        $this->ocorrencia = $ocorrencia;
        $this->author = $author;
    }

    public function via($notifiable)
    {
        return ['database']; // ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'ocorrencia_id' => $this->ocorrencia->id,
            'message' => 'Ocorrência: ' . $this->ocorrencia->title,

            // 🔑 FUNDAMENTAL
            'author_id' => $this->author->id,
            'company_id' => $this->author->company_id,  

            'type' => $this->ocorrencia->type,
            'user_name' => $this->author->name,
            'cargo' => $this->author->cargo ?? '',
            'url' => route('ocorrencia.pdf', $this->ocorrencia->id),
            'created_at' => $this->ocorrencia->created_at->format('d/m/Y H:i'),
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'ocorrencia_id' => $this->ocorrencia->id,
            'author_id' => $this->author->id,
            'message' => 'Nova ocorrência: ' . $this->ocorrencia->title,
        ];
    }
    
    // public function toMail($notifiable)
    // {
    //     return (new MailMessage)
    //         ->subject('Nova ocorrência cadastrada')
    //         ->line('Uma nova ocorrência foi criada.')
    //         ->action('Ver ocorrência', url('/admin/ocorrencias/ocorrencia/'.$this->ocorrencia->id));
    // }
}
