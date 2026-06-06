<?php

namespace Modules\FAQ\Repositories;

use Modules\FAQ\Requests\FAQRequest;
use Modules\FAQ\Models\FAQ;

class FAQRepo implements InterfaceFAQ
{
    public function getAll()
    {
        return FAQ::paginate(15);
    }
    public function create(array $data)
    {
        return FAQ::create([
            'question' => $data['question'],
            'answer' => $data['answer'],
        ]);
    }

    public function update($id , $request)
    {
        return FAQ::find($id)->update([
            'question' => $request['question'],
            'answer' => $request['answer'],
        ]);
    }

    public function findById($id)
    {
        return FAQ::findOrFail($id);
    }

    public function delete($id)
    {
        FAQ::find($id)->delete();
    }

}
