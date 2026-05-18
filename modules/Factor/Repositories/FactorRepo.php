<?php

namespace Modules\Factor\Repositories;
use Modules\Factor\Models\Category;
use Modules\Factor\Models\Factor;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Hekmatinasser\Verta\Verta;



class FactorRepo implements InterfaceFactor
{
    public function getAllCategories()
    {
        return Category::all();
    }

    public function getCategory()
    {
        return Category::paginate(10);
    }

    public function createCategory(array $data)
    {
        return Category::create([
            'name' => $data['name'],
        ]);
    }

    public function findById($id)
    {
        return Category::findOrFail($id);
    }

    public function updateCategory($id, $request)
    {
        Category::find($id)->update(['name' => $request->name, 'active' => $request->active]);
    }

    public function deleteCategory(int $id)
    {
        Category::find($id)->delete();
    }

    public function filterFactor(Request $request)
    {
        $searchQuery = $request->input('search_query');

        return Factor::query()->with('store', 'category')
            ->when($request->filled('search_query'), function ($q) use ($searchQuery) {
                $q->where(function ($query) use ($searchQuery) {
                    $query->where('id', 'LIKE', '%' . $searchQuery . '%');
                });
            })

            ->when($request->filled('show_status'), function ($q) use ($request) {
                $q->where('show_status', $request->show_status);
            })

            ->when($request->filled('price_status'), function ($q) use ($request) {
                $q->where('price_status', $request->price_status);
            })

            ->when($request->filled('store_id'), function ($q) use ($request) {
                $q->where('store_id', $request->store_id);
            })

            ->when($request->filled('category_id'), function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            })

            ->when($request->filled('factor_date'), function ($q) use ($request) {
                $q->where('factor_date', Verta::parse($request->factor_date)->toCarbon());
            })

            ->when($request->filled('created_at'), function ($q) use ($request) {
                $q->whereDate('created_at', Verta::parse($request->created_at)->toCarbon());
            })

            ->when($request->filled('paid_factor_date'), function ($q) use ($request) {
                $q->where('paid_factor_date', Verta::parse($request->paid_factor_date)->toCarbon());
            })

            ->latest()
            ->paginate(10);
    }

    public function createFactor(array $data)
    {
        return Factor::create([
            'store_id' => $data['store_id'],
            'factor_date' => Verta::parse($data['factor_date'])->toCarbon(),
            'category_id' => $data['category_id'],
            'show_status' => $data['show_status'],
            'hash' => Str::uuid(),
            'price' => $data['price'],
            'name' => $data['name'],
            'phone' => $data['phone'],
            'national_kod' => $data['national_kod'],
            'description' => $data['description'],
        ]);
    }

    public function updateFactor($id, $request)
    {
        \Log::info($request);

        $factor = Factor::find($id);

        $data = [
            'show_status'      => $request['show_status'],
            'price_status'     => $request['price_status'],
            'category_id'      => $request['category_id'],
            'price'            => $request['price'],
            'description'      => $request['description'],
            'paid_factor_date' => Verta::parse($request['paid_factor_date'])->toCarbon() ?? null,
            'factor_date' => Verta::parse($request['factor_date'])->toCarbon(),
        ];

        if (isset($request['image']) && $request['image']) {
            $data['image'] = $request['image']->store('factors', 'public');
        }

        $factor->update($data);
    }

    public function factorById($id)
    {
        return Factor::with('store', 'category')->findOrFail($id);
    }

    public function deleteFactor(int $id)
    {
        Factor::find($id)->delete();
    }

    public function getHashById(int $id)
    {
        return Factor::where('id', $id)->value('hash');
    }


}
