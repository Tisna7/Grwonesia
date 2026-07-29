<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    protected ReviewService $reviewService;

    public function __construct(ReviewService $reviewService)
    {
        $this->reviewService = $reviewService;
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'order_db_id' => 'nullable|integer',
            'order_id' => 'nullable|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'review' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
            'image_url' => 'nullable|string',
        ]);

        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan masuk untuk menulis ulasan.',
            ], 401);
        }

        // Handle uploaded image file if present
        $imageUrl = $request->input('image_url');
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('reviews', 'public');
            $imageUrl = asset('storage/' . $path);
        }

        $commentText = $request->input('comment') ?: $request->input('review');

        try {
            $review = $this->reviewService->submitReview($user, [
                'product_id' => $request->input('product_id'),
                'order_db_id' => $request->input('order_db_id') ?: $request->input('order_id'),
                'rating' => $request->input('rating'),
                'comment' => $commentText,
                'review' => $commentText,
                'image_url' => $imageUrl,
                'image' => $imageUrl,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil disimpan dan dipublikasikan!',
                'review' => [
                    'id' => $review->id,
                    'productId' => $review->product_id,
                    'orderId' => $review->order_id,
                    'userName' => $review->user_name,
                    'rating' => $review->rating,
                    'date' => 'Hari ini',
                    'comment' => $review->comment ?: $review->review,
                    'imageUrl' => $review->image_url ?: $review->image,
                    'verified' => true,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => implode(' ', array_merge(...array_values($e->errors()))),
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
