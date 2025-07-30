<?php

namespace App\Http\Controllers\Api\V1;

use App\Exceptions\BadRequestException;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class UploadController extends BaseController
{
    //

    /**
     * @throws BadRequestException
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->file('image')->isValid()) {
            $fileName = Str::uuid() . '.' . $request->file('image')->getClientOriginalExtension();

            $filePath = $request->file('image')->storeAs('uploads/images', $fileName, 'public');
            $fileUrl = asset('storage/' . $filePath);
            return $this->apiSuccessSingleResponse(
                JsonResource::make([
                    'file_name' => $fileName,
                    'file_path' => 'storage/' . $filePath,
                    'file_url' => $fileUrl,
                ])
            );
        }

        throw new BadRequestException(
            'Invalid image file provided.',
            400
        );
    }
}
