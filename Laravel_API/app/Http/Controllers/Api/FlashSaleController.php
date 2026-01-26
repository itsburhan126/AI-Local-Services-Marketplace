<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FlashSale;
use App\Models\FlashSaleItem;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index(Request $request)
    {
        $flashSale = FlashSale::first();

        if (!$flashSale || !$flashSale->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Flash sale is currently inactive.',
                'data' => null
            ], 404);
        }

        $query = FlashSaleItem::where('flash_sale_id', $flashSale->id)
            ->with('service') // Eager load service
            ->orderBy('order', 'asc');

        // Search Logic
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('custom_title', 'like', '%' . $request->search . '%')
                  ->orWhereHas('service', function ($sq) use ($request) {
                      $sq->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Category Filter (if provided)
        if ($request->category_id) {
            $query->whereHas('service', function ($sq) use ($request) {
                $sq->where('category_id', $request->category_id);
            });
        }

        $items = $query->paginate(20);
        
        // Transform items to include calculated final price
        $items->getCollection()->transform(function ($item) {
            $originalPrice = $item->service ? $item->service->price : 0;
            $discountAmount = ($originalPrice * $item->discount_percentage) / 100;
            $finalPrice = $originalPrice - $discountAmount;
            
            $item->original_price = $originalPrice;
            $item->final_price = $finalPrice;
            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => [
                'config' => [
                    'title' => $flashSale->title,
                    'start_time' => $flashSale->start_time,
                    'end_time' => $flashSale->end_time,
                    'bg_color' => $flashSale->bg_color,
                    'text_color' => $flashSale->text_color,
                ],
                'items' => $items
            ]
        ]);
    }
}
