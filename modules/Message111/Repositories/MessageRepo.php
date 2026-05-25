<?php

namespace Modules\Message\Repositories;

use Modules\Message\Models\Message;
use Illuminate\Support\Facades\DB;

class MessageRepo implements InterfaceMessage
{
    public function getAll()
    {
        return Message::orderBy('order', 'asc')->paginate(10);
    }

    public function getActive()
    {
        return Message::where('is_active', true)->orderBy('order', 'asc')->get();
    }

    public function findById($id)
    {
        return Message::findOrFail($id);
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {
            $message = Message::create($data);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function update($id, array $data)
    {
        DB::beginTransaction();
        try {
            $message = $this->findById($id);
            $message->update($data);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function delete($id)
    {
        $message = $this->findById($id);
        $message->delete();
        return true;
    }
}
