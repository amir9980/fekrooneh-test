<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConversionRequest;
use App\Http\Resources\TransactionResource;
use App\Models\Asset;
use App\Models\Conversion;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AssetController extends Controller
{

    public function conversion(ConversionRequest $request, Asset $numeratorAsset, Asset $denominatorAsset)
    {
        $user = $request->user();
        $admin = User::query()->where('is_admin', true)->with('wallets')->firstOrFail();

        // get conversion
        $conversion = Conversion::query()
            ->where('numerator_id', $numeratorAsset->id)
            ->where('denominator_id', $denominatorAsset->id)
            ->firstOrFail();

        // get user wallets
        $userWallets = $user->wallets()->whereIn('asset_id', [$numeratorAsset->id, $denominatorAsset->id])->get();
        $numeratorWallet = $userWallets->where('asset_id', $numeratorAsset->id)->first();
        $denominatorWallet = $userWallets->where('asset_id', $denominatorAsset->id)->first();

        if (is_null($numeratorWallet) or is_null($denominatorWallet)) {
            return response()->json([
                'message' => 'خطا! شما فاقد کیف پول موردنظر هستید!'
            ], 500);
        }

        if (!$user->hasEnoughToConvert($conversion, $request->value, $numeratorWallet)) {
            return response()->json([
                'message' => 'خطا! موجودی کیف پول شما کافی نمیباشد!'
            ], 400);
        }

        if (!$denominatorWallet->validateConvertedValue($conversion, $request->value)) {
            return response()->json([
                'message' => 'حطا! موجودی کیف پول مقصد از سقف مجاز بیشتر خواهد شد!'
            ], 400);
        }

        DB::beginTransaction();

        // calculate:
        $increaseValue = $request->value * $conversion->ratio;
        $decreaseValue = $request->value + $conversion->fee;
        $numeratorWalletNewValue = $numeratorWallet->value - $decreaseValue;
        $denominatorWalletNewValue = $denominatorWallet->value + $increaseValue;


        // fee transaction
        $adminWallet = $admin->wallets->where('asset_id', $numeratorAsset->id)->first();
        $description = __('messages.transaction', ['type' => 'افزایش موجودی به', 'asset' => $numeratorAsset->title, 'value' => $conversion->fee, 'date' => now()]);

        $feeTrans = Transaction::query()->create([
            'user_id' => $admin->id,
            'asset_id' => $numeratorAsset->id,
            'type' => 'increase',
            'value' => $conversion->fee,
            'status' => true,
            'description' => $description,
            'code' => Str::random(20)
        ]);
        $adminWallet->update(['value' => $adminWallet->value + $conversion->fee]);


        // decrease transaction
        $description = __('messages.transaction', ['type' => 'کاهش موجودی از', 'asset' => $numeratorAsset->title, 'value' => $decreaseValue, 'date' => now()]);

        $decreaseTrans = Transaction::query()->create([
            'user_id' => $user->id,
            'asset_id' => $numeratorAsset->id,
            'type' => 'decrease',
            'fee' => $conversion->fee,
            'value' => $request->value,
            'status' => true,
            'description' => $description,
            'code' => Str::random(20)
        ]);
        $numeratorWallet->update(['value' => $numeratorWalletNewValue]);

        // increase transaction
        $description = __('messages.transaction', ['type' => 'افزایش موجودی به', 'asset' => $denominatorAsset->title, 'value' => $increaseValue, 'date' => now()]);

        $increaseTrans = Transaction::query()->create([
            'user_id' => $user->id,
            'asset_id' => $denominatorAsset->id,
            'type' => 'increase',
            'fee' => $conversion->fee,
            'value' => $increaseValue,
            'status' => true,
            'description' => $description,
            'code' => Str::random(20)
        ]);
        $denominatorWallet->update(['value' => $denominatorWalletNewValue]);

        DB::commit();

        return response()->json([
            'message' => 'success',
            'transactions' => [
                'increase' => new TransactionResource($increaseTrans),
                'decrease' => new TransactionResource($decreaseTrans),
                'fee' => new TransactionResource($feeTrans),
            ]
        ]);

    }

}
