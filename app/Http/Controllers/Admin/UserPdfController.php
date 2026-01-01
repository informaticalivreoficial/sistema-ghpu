<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

class UserPdfController extends Controller
{
    public function profile(User $user)
    {
        $company = $user->company;

        if (! $company) {
            abort(404, 'Empresa não encontrada');
        }

        $user = User::with('company')->findOrFail($user->id);

        $pdf = Pdf::loadView('pdf.user-profile', [
            'user' => $user,
            'company' => $company,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream("perfil-{$user->id}.pdf");
        // ou ->download(...)
    }
}
