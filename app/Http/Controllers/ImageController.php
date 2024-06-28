<?php

namespace App\Http\Controllers;

use App\Http\Requests\Image\StoreRequest;
use App\Http\Services\ImageService;
use App\Models\Image;
use Illuminate\Http\JsonResponse;

class ImageController extends Controller
{
    public function __construct(
        public ImageService $service,
    ) {}

    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $image = $this->service->add($data);

        return response()->json([
            'status' => true,
            'image' => $image,
            'message' => 'Файл успешно добавлен!',
        ]);
    }

    public function destroy(Image $image): JsonResponse
    {
        $this->service->remove($image);

        return response()->json([
            'status' => true,
            'message' => 'Файл успешно удален!',
        ]);
    }

}
