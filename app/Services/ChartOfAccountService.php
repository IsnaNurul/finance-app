<?php
namespace App\Services;

use App\Models\ChartOfAccount;

class ChartOfAccountService
{
    public function getAll()
    {
        return ChartOfAccount::with('category')->orderBy('created_at', 'desc');
    }

    public function find($id)
    {
        return ChartOfAccount::with('category')->findOrFail($id);
    }

    public function create(array $data)
    {
        return ChartOfAccount::create($data);
    }

    public function update($id, array $data)
    {
        $coa = $this->find($id);
        $coa->update($data);
        return $coa;
    }

    public function delete($id)
    {
        $coa = $this->find($id);
        return $coa->delete();
    }
}
