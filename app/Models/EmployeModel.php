<?php

namespace App\Models;

use CodeIgniter\Model;

class EmployeModel extends Model
{
    protected $table = 'employes';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'nom',
        'prenom',
        'email',
        'password',
        'role',
        'departement_id',
        'date_embauche',
        'actif',
    ];

    protected $validationRules = [
        'nom' => 'required',
        'prenom' => 'required',
        'email' => 'required|valid_email|is_unique[employes.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role' => 'required',
        'date_embauche' => 'required|valid_date[Y-m-d]',
        'actif' => 'required|in_list[0,1]',
    ];

    public function findByEmail(string $email)
    {
        return $this->where('email', $email)->first();
    }
}
