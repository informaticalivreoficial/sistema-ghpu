<?php

namespace App\Livewire\Dashboard\Ocorrencias;

use App\Models\OcorrenciaTemplate;
use Livewire\Component;

class ConfigPassagemDeTurno extends Component
{
    public ?OcorrenciaTemplate $template = null;

    public string $type = '';
    public ?int $company_id = null;
    public string $title = '';
    public string $content = '';

    public function mount(string $type)
    {
        $user = auth()->user();

        abort_unless(
            $user->isSuperAdmin() || $user->isAdmin() || $user->isManager(),
            403
        );

        $this->type = $type;

        // Admin / Super podem editar template global ou por empresa
        $this->company_id = $user->company_id;

        $this->template = OcorrenciaTemplate::where('type', $type)
            ->where(function ($q) use ($user) {

                if ($user->isSuperAdmin()) {
                    // 🔥 Super Admin vê QUALQUER empresa + global
                    $q->whereNotNull('company_id')
                    ->orWhereNull('company_id');
                } else {
                    // 🔒 Outros só veem a empresa deles + global
                    $q->where('company_id', $this->company_id)
                    ->orWhereNull('company_id');
                }

            })
            // Prioridade: empresa > global
            ->orderByRaw('company_id is null')
            ->first();

        if ($this->template) {
            $this->title   = $this->template->title;
            $this->content = $this->template->content;
        }
    }

    public function save()
    {
        $this->validate([
            'title'   => 'required|string|min:3|max:255',
            'content' => 'required|string|min:10',
        ]);

        if ($this->template) {
            // ✏️ EDIÇÃO — NÃO muda company_id nem type
            $this->template->update([
                'title'      => $this->title,
                'content'    => $this->content,
                'created_by' => auth()->id(), // ou updated_by se quiser
            ]);
        } else {
            // ➕ CRIAÇÃO
            $this->template = OcorrenciaTemplate::create([
                'company_id' => $this->company_id,
                'type'       => $this->type,
                'title'      => $this->title,
                'content'    => $this->content,
                'created_by' => auth()->id(),
            ]);
        }

        $this->dispatch('swal', [
            'title' => 'Salvo!',
            'text'  => 'Configuração atualizada com sucesso.',
            'icon'  => 'success',
        ]);
    }

    public function render()
    {
        return view('livewire.dashboard.ocorrencias.config-passagem-de-turno');
    }
}
