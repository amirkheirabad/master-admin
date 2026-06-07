<?php

namespace Modules\Ticket\Repositories;

use Modules\Ticket\Models\QuickReply;

class QuickReplyRepo implements InterfaceQuickReply
{
    public function getAll()
    {
        return QuickReply::orderBy('created_at', 'desc')->paginate(15);
    }

    public function getAllList()
    {
        return QuickReply::orderBy('title')->get();
    }

    public function create(array $data)
    {
        return QuickReply::create($data);
    }

    public function update($id, array $data)
    {
        $qr = QuickReply::findOrFail($id);
        $qr->update($data);
        return $qr;
    }

    public function delete($id)
    {
        QuickReply::findOrFail($id)->delete();
    }

    public function findById($id)
    {
        return QuickReply::findOrFail($id);
    }
}