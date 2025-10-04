<?php

namespace App\Services;

use App\Models\Transaction;

class TransactionService
{
    public function getAll()
    {
        return Transaction::with('chartOfAccount.category')->orderBy('created_at', 'desc');
    }

    public function find($id)
    {
        return Transaction::with('chartOfAccount.category')->findOrFail($id);
    }

    public function create(array $data)
    {
        return Transaction::create($data);
    }

    public function update($id, array $data)
    {
        $trx = $this->find($id);
        $trx->update($data);
        return $trx;
    }

    public function delete($id)
    {
        $trx = $this->find($id);
        return $trx->delete();
    }
}
