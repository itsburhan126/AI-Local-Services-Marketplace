<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'type' => 'required|string|in:gig,provider',
            'id' => 'required|integer',
        ]);

        $user = $request->user();
        $type = $request->type;
        $id = $request->id;
        
        $modelClass = match($type) {
            'gig' => \App\Models\Gig::class,
            'provider' => \App\Models\User::class,
            default => null,
        };

        if (!$modelClass) {
            return response()->json(['status' => 'error', 'message' => 'Invalid type'], 400);
        }

        $model = $modelClass::find($id);
        if (!$model) {
            return response()->json(['status' => 'error', 'message' => 'Item not found'], 404);
        }

        // Check if favorite exists
        $favorite = \App\Models\Favorite::where('user_id', $user->id)
            ->where('favorable_id', $id)
            ->where('favorable_type', $modelClass)
            ->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorite = false;
            $message = 'Removed from favorites';
        } else {
            \App\Models\Favorite::create([
                'user_id' => $user->id,
                'favorable_id' => $id,
                'favorable_type' => $modelClass,
            ]);
            $isFavorite = true;
            $message = 'Added to favorites';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'is_favorite' => $isFavorite,
        ]);
    }
}
