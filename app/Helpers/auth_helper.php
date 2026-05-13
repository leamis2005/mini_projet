<?php

if (! function_exists('auth_user')) {
    function auth_user(): ?array
    {
        $user = session()->get('user');
        return is_array($user) ? $user : null;
    }
}

if (! function_exists('auth_id')) {
    function auth_id(): ?int
    {
        $user = auth_user();
        return $user['id'] ?? null;
    }
}

if (! function_exists('auth_role')) {
    function auth_role(): ?string
    {
        $user = auth_user();
        return $user['role'] ?? null;
    }
}

if (! function_exists('is_role')) {
    function is_role(string $role): bool
    {
        return auth_role() === $role;
    }
}
