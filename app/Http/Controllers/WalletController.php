<?php

namespace App\Http\Controllers;

use App\Http\Requests\DecreaseRequest;
use App\Http\Requests\IncreaseRequest;
use App\Http\Resources\TransactionResource;
use App\Http\Resources\WalletCollection;
use App\Http\Resources\WalletResource;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletController extends Controller
{
    public function all(Request $request)
    {
        $wallets = $request->user()->load('wallets.asset')->wallets;

        return response()->json([
            'message' => 'success',
            'wallets' => new WalletCollection($wallets)
        ]);
    }

    public function show(Request $request, Wallet $wallet)
    {
        $wallet->load('asset');

        return response()->json([
            'message' => 'success',
            'wallet' => new WalletResource($wallet)
        ]);
    }


    public function increase(IncreaseRequest $request, Wallet $wallet)
    {
        $wallet->load('asset');

        if (!$wallet->validateIncreasedValue($request->value)) {
            return response()->json([
                'message' => 'خطا! موجودی کیف پول از سقف مجاز بیشتر خواهد شد!'
            ], 400);
        }

        DB::beginTransaction();

        $increasedValue = $wallet->value + $request->value;

        $description = __('messages.transaction', ['type' => 'افزایش موجودی به', 'asset' => $wallet->asset->title, 'value' => $request->value, 'date' => now()]);

        $transaction = Transaction::query()->create([
            'user_id' => $request->user()->id,
            'asset_id' => $wallet->asset_id,
            'type' => 'increase',
            'value' => $request->value,
            'status' => true,
            'description' => $description,
            'code' => Str::random(20)
        ]);

        $wallet->update(['value' => $increasedValue]);

        DB::commit();

        return response()->json([
            'message' => 'success',
            'data' => new TransactionResource($transaction)
        ]);
    }


    public function decrease(DecreaseRequest $request, Wallet $wallet)
    {
        $wallet->load('asset');

        if (!$wallet->validateDecreasedValue($request->value)) {
            return response()->json([
                'message' => 'خطا! موجودی کیف پول از کف مجاز کمتر خواهد شد!'
            ], 400);
        }

        DB::beginTransaction();

        $decreasedValue = $wallet->value - $request->value;

        $description = __('messages.transaction', ['type' => 'کاهش موجودی از', 'asset' => $wallet->asset->title, 'value' => $request->value, 'date' => now()]);

        $transaction = Transaction::query()->create([
            'user_id' => $request->user()->id,
            'asset_id' => $wallet->asset_id,
            'type' => 'decrease',
            'value' => $request->value,
            'status' => true,
            'description' => $description,
            'code' => Str::random(20)
        ]);

        $wallet->update(['value' => $decreasedValue]);

        DB::commit();

        return response()->json([
            'message' => 'success',
            'data' => new TransactionResource($transaction)
        ]);
    }
}
