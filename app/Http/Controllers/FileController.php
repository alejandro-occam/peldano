<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Response;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    public function showPdf($slug){
        $storagePath = storage_path('app/pdfs_bills/' . $slug);
        try {
            $file = File::get($storagePath);
            $type = File::mimeType($storagePath);
            $response = Response::make($file, 200);
            $response->header("Content-Type", $type);

            return $response;
        } catch (FileNotFoundException $exception) {
            abort(404);
        }
    }

}