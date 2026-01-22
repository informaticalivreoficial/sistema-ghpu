<?php

namespace App\Livewire\Dashboard\Apartments;

use App\Models\Apartments;
use Illuminate\Support\Str;
use Livewire\Component;

class ApartmentForm extends Component
{
    public ?Apartments $apartment = null;

    public string $name = '';
    public int $capacidade_adultos = 2;
    public int $capacidade_criancas = 0;
    public bool $status = true;
    public ?string $observacoes = null;
    public ?int $company_id = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'capacidade_adultos' => 'required|integer|min:1',
            'capacidade_criancas' => 'nullable|integer|min:0',
            //'status' => 'boolean',
            'company_id' => 'required|exists:companies,id',
            'observacoes' => 'nullable|string',
        ];
    }

    protected function messages(): array
    {
        return [
            'name.required' => 'O nome do apartamento é obrigatório.',
            'company_id.required' => 'Selecione a empresa responsável.',
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => 'nome do apartamento',
            'capacidade_adultos' => 'capacidade de adultos',
            'capacidade_criancas' => 'capacidade de crianças',
            'company_id' => 'empresa',
        ];
    }

    public function mount(?Apartments $apartment = null)
    {
        $this->apartment = $apartment;

        if ($this->apartment) {
            // Preenche os campos para edição
            $this->name = $apartment->name;
            $this->capacidade_adultos = $apartment->capacidade_adultos;
            $this->capacidade_criancas = $apartment->capacidade_criancas;
            $this->company_id = $apartment->company_id;
        } else {
            // Criação: se for Manager/Employee já atribui empresa
            if (!auth()->user()->isSuperAdmin() && !auth()->user()->isAdmin()) {
                $this->company_id = auth()->user()->company_id;
            }
        }
    }

    public function save()
    {
        // Policy: verifica se pode criar ou editar
        if ($this->apartment) {
            $this->authorize('update', $this->apartment);
        } else {
            $this->authorize('create', Apartments::class);
        }

        $this->validate();

        $data = [
            'name' => $this->name,
            'capacidade_adultos' => $this->capacidade_adultos,
            'capacidade_criancas' => $this->capacidade_criancas,
            'status' => $this->status,
            'company_id' => $this->company_id,
        ];

        $data['slug'] = Str::slug($this->name);

        if ($this->apartment) {
            $this->apartment->update($data);
            $this->dispatch('swal', [
                'title' => 'Sucesso!',
                'text' => 'Apartamento Editado com sucesso!',
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false,
            ]);
        } else {
            $this->apartment = Apartments::create($data);
            $this->dispatch('swal', [
                'title' => 'Sucesso!',
                'text' => 'Apartamento cadastrado com sucesso!',
                'icon' => 'success',
                'timer' => 2000,
                'showConfirmButton' => false,
            ]);

            return redirect()->route('apartments.edit', $this->apartment);
        }        
    }
    
    public function render()
    {
        $companies = (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            ? \App\Models\Company::orderBy('alias_name')->get()
            : collect();            
        return 
            view('livewire.dashboard.apartments.apartment-form', [
                'companies' => $companies
            ])
            ->with('title', ($this->apartment ? 'Editar' : 'Cadastrar') . ' Apartamento');
    }
}
