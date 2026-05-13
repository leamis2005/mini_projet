<?php

namespace App\Controllers;

use App\Models\DepartementModel;
use App\Models\EmployeModel;

class AuthController extends BaseController
{
    public function index()
    {
        $user = session()->get('user');
        if ($user && isset($user['role'])) {
            return $this->redirectByRole($user['role']);
        }

        return redirect()->to('/login');
    }

    public function loginForm()
    {
        $user = session()->get('user');
        if ($user && isset($user['role'])) {
            return $this->redirectByRole($user['role']);
        }

        return view('auth/login', [
            'pageTitle' => 'Connexion',
        ]);
    }

    public function login()
    {
        $rules = [
            'email' => 'required|valid_email',
            'password' => 'required',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $email = (string) $this->request->getPost('email');
        $password = (string) $this->request->getPost('password');

        $employeModel = new EmployeModel();
        $user = $employeModel
            ->where('email', $email)
            ->where('actif', 1)
            ->first();

        if (! $user || ! password_verify($password, $user['password'])) {
            return redirect()->back()->withInput()->with('error', 'Identifiants incorrects.');
        }

        $departementModel = new DepartementModel();
        $departement = $departementModel->find($user['departement_id']);

        session()->set('user', [
            'id' => $user['id'],
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'email' => $user['email'],
            'role' => $user['role'],
            'departement_id' => $user['departement_id'],
            'departement_nom' => $departement['nom'] ?? null,
        ]);

        return $this->redirectByRole($user['role']);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    private function redirectByRole(string $role)
    {
        if ($role === 'admin') {
            return redirect()->to('/admin');
        }

        if ($role === 'rh') {
            return redirect()->to('/rh');
        }

        return redirect()->to('/employe');
    }
}
