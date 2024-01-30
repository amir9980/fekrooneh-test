<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionCollection;
use App\Models\Asset;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function show(Request $request, Asset $asset)
    {
        $transactions = Transaction::query()
            ->where('user_id', $request->user()->id)
            ->where('asset_id', $asset->id)
            ->get();

        return response()->json([
            'message'=>'success',
            'transactions'=>new TransactionCollection($transactions)
        ]);
    }
}
