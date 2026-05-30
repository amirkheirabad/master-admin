<?php

namespace Modules\Stores\Repositories;

interface InterfaceStores
{
    public function index();
    public function create(array $data);
    public function getUsers();
//    public function save($request);
//    public function update();
//    public function delete();
}
