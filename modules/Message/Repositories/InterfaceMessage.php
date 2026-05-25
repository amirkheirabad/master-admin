<?php

namespace Modules\Message\Repositories;

interface InterfaceMessage
{
    public function getAll();
    public function getActive();
    public function findById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}