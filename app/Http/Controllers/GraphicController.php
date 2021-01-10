<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class GraphicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $graphics_path = env("GRAPHICS_PATH", "");
        $result = [];

        if($graphics_path){
            $files = Storage::files($graphics_path);

            $filtered_files = array_filter( $files, function($val){
                return strpos($val,'/.') === false;
            });


            foreach($filtered_files as $f){
                $result[] = [
                   'path' => asset(str_replace('public', 'storage', $f)),
                   'name' => pathinfo($f, PATHINFO_FILENAME)     
                ];
            }
        }
        return $result;

    }

}
