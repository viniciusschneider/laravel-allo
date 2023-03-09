<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
	protected $entity;

    public function __construct(User $user)
    {
        $this->entity = $user;
    }

    public function getUserByEmail($email)
    {
        return $this->entity->firstWhere('email', $email);
    }

    public function create($data)
    {
        return $this->entity->create($data);
    }
}
