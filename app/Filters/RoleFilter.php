<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $user = session()->get('user');
        if (! $user) {
            return redirect()->to('/login');
        }

        $role = $user['role'] ?? null;
        $allowed = $arguments ?? [];
        if ($allowed && ! in_array($role, $allowed, true)) {
            return redirect()->to('/login')->with('error', 'Acces refuse.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
