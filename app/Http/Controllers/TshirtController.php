<?php

namespace App\Http\Controllers;

use App\Models\Tshirt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class TshirtController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Tshirt::where('status', Tshirt::STATUS_ACTIVE);
        return $query->latest()->simplePaginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate($this->rules());

        if (isset($input["file"]) && !empty($input["file"])) {
            $input["image"] = $this->uploadImageAndResize($input["file"]);
        }

        $tshirt = Tshirt::create($input);

        return response()->json($tshirt, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tshirt  $tshirt
     * @return \Illuminate\Http\Response
     */
    public function show(Tshirt $tshirt)
    {
        return $tshirt;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tshirt  $tshirt
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tshirt $tshirt)
    {
        $previous_image = '';

        $input = $request->validate($this->rules());

        if (isset($input["file"]) && !empty($input["file"])) {
            $input["image"] = $this->uploadImageAndResize($input["file"]);
            $previous_image = $tshirt->image;
        }

        $result = $tshirt->update($input);

        if ($result && !empty($previous_image)) {
            $this->deleteImages($previous_image);
        }

        return response()->json($tshirt, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tshirt  $tshirt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tshirt $tshirt)
    {
        $previous_image = $tshirt->image;

        $result = $tshirt->delete();

        if ($result && !empty($previous_image)) {
            $this->deleteImages($previous_image);
        }

        return response()->json(null, 204);
    }

    /**
     * Get validation rules
     * 
     * @return array
     */
    protected function rules()
    {
        return [
            'name' => ['required', 'min:3', 'max:100'],
            'file' => ['mimes:jpeg,jpg,png', 'max:10000'],
            'status' => ['required', 'boolean']
        ];
    }

    /**
     * Upload original image to storage and create thumbnail
     * 
     * @return array
     */
    private function uploadImageAndResize(UploadedFile $file)
    {

        $filename = Str::uuid() . '.' . $file->extension();

        $full_image = $file->storeAs(Tshirt::STORAGE_PATH, 'full_' . $filename);

        $img = Image::make(storage_path('app/' . $full_image));

        $img->resize(250, 250, function ($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path('app/' . Tshirt::STORAGE_PATH . '/thumb_' . $filename));

        return $filename;
    }

    /**
     * Delete images related to item
     * 
     * @return void
     */
    private function deleteImages(String $image)
    {

        $files = [
            Tshirt::STORAGE_PATH, 'full_' . $image,
            Tshirt::STORAGE_PATH, 'thumb_' . $image
        ];

        Storage::delete($files);
    }
}
