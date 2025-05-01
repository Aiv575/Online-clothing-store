<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\ReturnItem;
use Illuminate\Http\Request;
use App\Models\ReturnRequest;
use App\Models\Thing;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    public function refund(Request $request, Receipt $receipt)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.thing_id' => 'required|exists:things,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.size' => 'required|string',
            'items.*.reason' => 'required|string|max:150',
            'items.*.image' => 'nullable|image|max:2048',
        ]);

        $totalRefund = 0;

            // Сначала посчитаем итоговую сумму возврата
        foreach ($request->items as $input) {
            $thing = Thing::findOrFail($input['thing_id']);
            $itemInReceipt = $receipt->items->firstWhere(fn ($item) =>
                $item->thing_id == $input['thing_id'] && $item->size == $input['size']
            );

            if (!$itemInReceipt) {
                return back()->withErrors(['items' => 'Один из товаров не найден в этом чеке.']);
            }

            if ($input['quantity'] > $itemInReceipt->quantity) {
                return back()->withErrors(['items' => 'Нельзя вернуть больше, чем куплено.']);
            }

            $totalRefund += $thing->price * $input['quantity'];
        }

        // Создаём основную запись возврата
        $returnRequest = ReturnRequest::create([
            'user_id' => Auth::id(),
            'receipt_id' => $receipt->id,
            'total_refund' => $totalRefund,
            'status' => 'pending',
        ]);

        // Сохраняем каждую строку возврата
        foreach ($request->items as $input) {
            $imagePath = null;
            if (isset($input['image'])) {
                $imagePath = $input['image']->store('refunds', 'public');
            }

            ReturnItem::create([
                'return_request_id' => $returnRequest->id,
                'thing_id' => $input['thing_id'],
                'quantity' => $input['quantity'],
                'size' => $input['size'],
                'price' => Thing::findOrFail($input['thing_id'])->price,
                'reason' => $input['reason'],
                'image' => $imagePath,
            ]);
        }

        return redirect()->route('profile')->with('success', 'Запрос на возврат отправлен');
    }
}
