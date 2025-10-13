<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Merchant;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function showTransferToFriend()
    {
        return view('transactions.transfer-friend');
    }

    public function transferToFriend(Request $request)
    {
        $request->validate([
            'account_number' => 'required|exists:users,account_number',
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
            'pin' => 'required|digits:4',
        ]);

        $sender = auth()->user();
        $receiver = User::where('account_number', $request->account_number)->first();

        if (!$sender->verifyPin($request->pin)) {
            return back()->withErrors(['pin' => 'الرقم السري غير صحيح']);
        }

        if ($receiver->id === $sender->id) {
            return back()->withErrors(['account_number' => 'لا يمكن التحويل لنفسك']);
        }

        if ($sender->balance < $request->amount) {
            return back()->withErrors(['amount' => 'رصيدك غير كافٍ']);
        }

        if (!$sender->canSpend($request->amount)) {
            return back()->withErrors(['amount' => 'تجاوزت الحد اليومي']);
        }

        DB::transaction(function () use ($sender, $receiver, $request) {
            $sender->decrement('balance', $request->amount);
            $sender->increment('daily_spent', $request->amount);

            $receiver->increment('balance', $request->amount);

            Transaction::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'transaction_type' => 'transfer',
                'amount' => $request->amount,
                'description' => $request->description ?? 'تحويل لصديق',
                'reference_number' => Transaction::generateReference(),
                'payment_method' => 'transfer',
                'status' => 'completed',
            ]);

            Notification::sendToUser(
                $sender->id,
                'تم التحويل',
                "تم تحويل ₪{$request->amount} إلى {$receiver->name}",
                'success'
            );

            Notification::sendToUser(
                $receiver->id,
                'تم استلام الأموال',
                "تم استلام ₪{$request->amount} من {$sender->name}",
                'success'
            );
        });

        return redirect()->route('dashboard')
            ->with('success', 'تم التحويل بنجاح!');
    }

    public function showPayToMerchant()
    {
        return view('transactions.pay-merchant');
    }

    /**
     * API endpoint to fetch merchants with search functionality
     */
    public function getMerchants(Request $request)
    {
        $search = $request->get('search', '');
        $page = $request->get('page', 1);
        $perPage = 10;

        $query = Merchant::where('is_verified', true);

        // البحث في اسم التاجر أو نوع العمل
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('business_type', 'LIKE', "%{$search}%")
                  ->orWhere('merchant_id', 'LIKE', "%{$search}%");
            });
        }

        $merchants = $query->orderBy('name')
                          ->paginate($perPage, ['*'], 'page', $page);

        // تنسيق البيانات لـ Select2
        $results = $merchants->map(function($merchant) {
            return [
                'id' => $merchant->id,
                'text' => $merchant->name . ' - ' . $merchant->business_type,
                'name' => $merchant->name,
                'business_type' => $merchant->business_type,
                'merchant_id' => $merchant->merchant_id,
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $merchants->hasMorePages()
            ]
        ]);
    }

    public function payToMerchant(Request $request)
    {
        $request->validate([
            'merchant_id' => 'required|exists:merchants,id',
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string|max:255',
            'pin' => 'required|digits:4',
        ]);

        $user = auth()->user();
        $merchant = Merchant::findOrFail($request->merchant_id);

        if (!$user->verifyPin($request->pin)) {
            return back()->withErrors(['pin' => 'الرقم السري غير صحيح']);
        }

        if ($user->balance < $request->amount) {
            return back()->withErrors(['amount' => 'رصيدك غير كافٍ']);
        }

        if (!$user->canSpend($request->amount)) {
            return back()->withErrors(['amount' => 'تجاوزت الحد اليومي']);
        }

        DB::transaction(function () use ($user, $merchant, $request) {
            $user->decrement('balance', $request->amount);
            $user->increment('daily_spent', $request->amount);

            Transaction::create([
                'sender_id' => $user->id,
                'transaction_type' => 'payment',
                'amount' => $request->amount,
                'description' => $request->description,
                'merchant_name' => $merchant->name,
                'reference_number' => Transaction::generateReference(),
                'payment_method' => 'card',
                'status' => 'completed',
            ]);

            Notification::sendToUser(
                $user->id,
                'تم الدفع',
                "تم الدفع ₪{$request->amount} لـ {$merchant->name}",
                'info'
            );
        });

        return redirect()->route('dashboard')
            ->with('success', 'تم الدفع بنجاح!');
    }

    public function history()
    {
        $transactions = Transaction::where('sender_id', auth()->id())
            ->orWhere('receiver_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('transactions.history', compact('transactions'));
    }
}
