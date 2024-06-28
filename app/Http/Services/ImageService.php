<?php

namespace App\Http\Services;

use App\Models\Column;
use App\Models\Image;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{

    public function remove(Image $image): void
    {
        Storage::delete($image->path);
        $image->delete();
    }

    public function add(array $data): Image
    {
        $path = Storage::put(
            'public/' .  Image::ALIASES[$data['class']] . '/' . $data['id'],
            $data['image']
        );

        return Image::create([
            'imageable_id' => $data['id'],
            'imageable_type' => $data['class'],
            'path' => $path
        ]);
    }

}
