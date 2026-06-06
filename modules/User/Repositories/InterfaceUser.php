<?php

namespace Modules\User\Repositories;
use Modules\User\Models\User;

interface InterfaceUser
{   
    public function quickCreateSeller(array $data): User;
    
}

