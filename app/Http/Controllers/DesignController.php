<?php

namespace App\Http\Controllers;

use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Rules\B64image;
use Intervention\Image\ImageManager;

class DesignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = Design::select('designs.*')
            ->where('designs.status', Design::STATUS_ACTIVE)
            ->latest('designs.created_at');

        return $query->simplePaginate(10);
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

        $input["hash"] = $this->uploadImageAndResize($input['b64img']);

        $design = Design::create($input);

        return response()->json($design, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Design  $design
     * @return \Illuminate\Http\Response
     */
    public function show(Design $design)
    {
        if (Gate::denies('update-design', $design)) {
            abort(response()->json(array('message' => 'You are not allowed to access this resource'), 401));
        }
        return $design;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Design  $design
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Design $design)
    {
        $previous_image = '';

        $input = $request->validate($this->rules());

        if (isset($input["b64img"]) && !empty($input["b64img"])) {
            $input["hash"] = $this->uploadImageAndResize($input["b64img"]);
            $previous_hash = $design->hash;
        }

        $result = $design->update($input);

        if ($result && !empty($previous_hash)) {
            $this->deleteImages($previous_hash);
        }

        return response()->json($design, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Design  $design
     * @return \Illuminate\Http\Response
     */
    public function destroy(Design $design)
    {
        if (Gate::denies('update-design', $design)) {
            abort(response()->json(array('message' => 'You are not allowed to access this resource'), 401));
        }

        $previous_hash = $design->hash;

        $result = $design->delete();

        if ($result && !empty($previous_hash)) {
            $this->deleteImages($previous_hash);
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
            'name' => ['required', 'min:6', 'max:100'],
            'b64img' => ['required', new B64image],
            'status' => ['required', 'numeric', 'between:0,1'],
            'tshirt_id' => ['required', 'integer']
        ];
    }

    /**
     * Upload original image to storage add watermark and create thumb and mid size
     * 
     * @return array
     */
    private function uploadImageAndResize($data)
    {

        $hash = Str::uuid();
        Storage::makeDirectory(Design::STORAGE_PATH . '/' . $hash);

        $img = Image::make($data); 
        
        $watermark = env("WATERMARK_IMAGE", "");
        
        if($watermark){
            $img->insert(storage_path($watermark), 'bottom-right', 0, 0);
        }

        $img->save(storage_path('app/' . Design::STORAGE_PATH . '/' . $hash . '/full.png'));

        $img->resize(600, 600, function ($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path('app/' . Design::STORAGE_PATH . '/' . $hash . '/mid.png'));

        $img->resize(250, 250, function ($constraint) {
            $constraint->aspectRatio();
        })->save(storage_path('app/' . Design::STORAGE_PATH . '/' . $hash . '/thumb.png'));

        return $hash;
    }

    /**
     * Delete images related to item
     * 
     * @return void
     */
    private function deleteImages(String $hash)
    {

        Storage::deleteDirectory(Design::STORAGE_PATH . '/' . $hash);

    }
}
