<?php

namespace App\Livewire\Dashboard\Users;

use App\Http\Requests\Admin\UserRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Form extends Component
{
    use WithFileUploads;

    public User $user;

    public $userId;

    public $foto; // Propriedade para armazenar a foto temporariamente
    public $fotoUrl; // Propriedade para armazenar o caminho da foto após o upload

    public $roles;
    public array $roleLabels = [
        'super-admin' => 'Super Administrador',
        'admin'       => 'Administrador',
        'manager'     => 'Gerente',
        'employee'    => 'Colaborador',
    ];
    public $roleSelected = '';

    public $company_id;
    public $companies = [];  
    public $gender;    

    //Informations about
    public $name, $cargo, $birthday, $naturalness, $civil_status, $avatar, $information;    
    
    //Documents
    public $cpf, $rg, $rg_expedition;

    //Address
    public $zipcode = '', $street, $neighborhood, $city, $state, $complement, $number;

    //Contact
    public $phone, $cell_phone, $whatsapp, $email, $additional_email, $telegram;

    //Social
    public $facebook, $instagram, $linkedin;

    public $code;
    public $code_confirmation;

    public $errorMessage;

    protected function rulesCreate()
    {
        $rules = [
            'name' => 'required|min:3',
            'gender' => 'required|in:masculino,feminino',
            'civil_status' => 'required|in:casado,separado,solteiro,divorciado,viuvo',
            'email' => 'required|email|unique:users,email',
            'cpf' => 'required|cpf|unique:users,cpf',
            'cell_phone' => 'required',
            'information' => 'nullable|string|max:2000',
            'birthday' => 'required|date_format:d/m/Y|before:today',

            'roleSelected' => 'required|in:employee,manager,admin,super-admin',

            'code' => $this->roleSelected !== 'employee'
                ? 'required|min:6|confirmed'
                : 'nullable',
        ];

        return $rules;
    }

    protected function rulesUpdate()
    {
        return [
            'name' => 'required|min:3|max:191',
            'gender' => 'required|in:masculino,feminino',
            'civil_status' => 'required|in:casado,separado,solteiro,divorciado,viuvo',
            'email' => 'required|email|unique:users,email,' . $this->userId,
            'cpf' => 'required|cpf|unique:users,cpf,' . $this->userId,
            'cell_phone' => 'required',
            'birthday' => 'required|date_format:d/m/Y',
            'information' => 'nullable|string|max:2000',

            'code' => $this->roleSelected !== 'employee'
                ? 'required|min:6|confirmed'
                : 'nullable|min:6|confirmed',

            'code_confirmation' => 'same:code',
        ];
    }

    public function mount($userId = null)
    {
        if ($userId) {
            // 🔎 Carrega o usuário PRIMEIRO
            $user = User::findOrFail($userId);

            // 🔐 Autoriza DEPOIS
            $this->authorize('update', $user);

            // Guarda referência se precisar
            $this->userId = $user->id;

            // Preenche os campos
            $this->company_id = $user->company_id;
            $this->name = $user->name;
            $this->cargo = $user->cargo;
            $this->code = $user->code;
            $this->avatar = $user->avatar;
            $this->birthday = $user->birthday;
            $this->gender = $user->gender ?? 'masculino';
            $this->naturalness = $user->naturalness;
            $this->civil_status = $user->civil_status;
            $this->rg = $user->rg;
            $this->rg_expedition = $user->rg_expedition;
            $this->cpf = $user->cpf;
            $this->email = $user->email;
            $this->phone = $user->phone;
            $this->cell_phone = $user->cell_phone;
            $this->whatsapp = $user->whatsapp;
            $this->additional_email = $user->additional_email;
            $this->telegram = $user->telegram;
            $this->number = $user->number;
            $this->zipcode = $user->zipcode;
            $this->street = $user->street;
            $this->neighborhood = $user->neighborhood;
            $this->city = $user->city;
            $this->state = $user->state;
            $this->complement = $user->complement;
            $this->facebook = $user->facebook;
            $this->instagram = $user->instagram;
            $this->linkedin = $user->linkedin;
            $this->information = $user->information;

            // Role
            $this->roleSelected = $user->roles->pluck('name')->first();
            $this->role = $this->roleSelected;
        }

        // 🔹 Carrega empresas APÓS autorização
        if (auth()->user()->hasAnyRole(['super-admin', 'admin'])) {
            $this->companies = Company::orderBy('alias_name')->get();
        }
    }

    public function render()
    {
        return view('livewire.dashboard.users.form');
    }

    public function save()
    {
        $this->userId ? $this->update() : $this->create();
    }

    public function create()
    {
        try {
            $validated = $this->validate($this->rulesCreate());

            // 📸 Upload da foto (se existir)
            if ($this->foto) {
                $validated['avatar'] = $this->foto->store('user', 'public');
            }

            $password = $this->roleSelected !== 'employee'
                ? Hash::make($this->code)
                : Hash::make(Str::random(12));

            // 🏢 Proteção company_id
            $validated['company_id'] = auth()->user()->hasAnyRole(['super-admin', 'admin'])
                ? $this->company_id
                : auth()->user()->company_id;

            // 📦 Merge dos campos extras
            $data = array_merge($validated, [
                'cargo'            => $this->cargo,
                'password'         => $password,
                'naturalness'      => $this->naturalness,
                'rg'               => $this->rg,
                'rg_expedition'    => $this->rg_expedition,
                'phone'            => $this->phone,
                'whatsapp'         => $this->whatsapp,
                'additional_email' => $this->additional_email,
                'telegram'         => $this->telegram,
                'number'           => $this->number,
                'zipcode'          => $this->zipcode,
                'street'           => $this->street,
                'neighborhood'     => $this->neighborhood,
                'city'             => $this->city,
                'state'            => $this->state,
                'complement'       => $this->complement,
                'facebook'         => $this->facebook,
                'instagram'        => $this->instagram,
                'linkedin'         => $this->linkedin,
                'information' => $this->information,
            ]);

            // 🚀 Criação do usuário
            $user = User::create($data);

            // 🛡️ Roles (Spatie)
            $user->syncRoles([$this->roleSelected]);

            // 🧹 Limpa campos sensíveis
            $this->reset(['code', 'code_confirmation', 'foto']);

            // 🔔 Feedback
            $this->dispatch('user-cadastrado');

            return redirect()->route('users.edit', $user->id);

        } catch (\Illuminate\Validation\ValidationException $e) {

            $this->dispatch('toast',
                type: 'error',
                message: $e->validator->errors()->first()
            );

            throw $e;
        }
    }

    public function update()
    {    
        try {
            
            $validated = $this->validate($this->rulesUpdate());
        
            $user = User::findOrFail($this->userId);

            //$this->authorize('update', $user);

            if ($this->foto) {
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $validated['avatar'] = $this->foto->store('user', 'public');
            }

            if (!auth()->user()->hasAnyRole(['super-admin','admin'])) {
                unset($validated['company_id']);
            }

            $data = array_merge($validated, [
                'cargo'            => $this->cargo,
                'naturalness'      => $this->naturalness,
                'rg'               => $this->rg,
                'rg_expedition'    => $this->rg_expedition,
                'phone'            => $this->phone,
                'whatsapp'         => $this->whatsapp,
                'additional_email' => $this->additional_email,
                'telegram'         => $this->telegram,
                'number'           => $this->number,
                'zipcode'          => $this->zipcode,
                'street'           => $this->street,
                'neighborhood'     => $this->neighborhood,
                'city'             => $this->city,
                'state'            => $this->state,
                'complement'       => $this->complement,
                'facebook'         => $this->facebook,
                'instagram'        => $this->instagram,
                'linkedin'         => $this->linkedin,
                'information'      => $this->information,
            ]);
            
            $user->update($data);
            $user->syncRoles([$this->roleSelected]);

            $this->reset(['code', 'code_confirmation', 'foto']);
            $this->dispatch('user-atualizado');
        } catch (\Illuminate\Validation\ValidationException $e) {
            
            $this->dispatch('toast', 
                type: 'error', 
                message: $e->validator->errors()->first()
            );
            throw $e;
        }    
    }   

    public function updatedZipcode(string $value)
    {        
        $this->zipcode = preg_replace('/[^0-9]/', '', $value);

        if(strlen($this->zipcode) === 8){
            $response = Http::get("https://viacep.com.br/ws/{$this->zipcode}/json/")->json();            
            if(!isset($response['erro'])){                
                $this->street = $response['logradouro'] ?? '';
                $this->neighborhood = $response['bairro'] ?? '';
                $this->state = $response['uf'] ?? '';
                $this->city = $response['localidade'] ?? '';
                $this->complement = $response['complemento'] ?? '';      
            }else{                
                $this->addError('zipcode', 'CEP não encontrado.'); 
            }
        }
    }

    public function updatedFoto()
    {
        $this->validateOnly('foto', [
            'foto' => 'nullable|image|max:1024',
        ]);

        $this->fotoUrl = $this->foto->temporaryUrl();
    }

    // public function atualizarData($valor)
    // {
    //     $this->birthday = $valor;
    // }

    public function updatedRoleSelected($value)
    {
        if ($value === 'employee') {
            $this->password = null;
            $this->password_confirmation = null;
        }
    }

}
