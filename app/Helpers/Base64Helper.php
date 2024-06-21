<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class Base64Helper
{
    public static function saveBase64Image($value, $filename, $path = 'foto_kamar')
    {
        if (!Storage::disk('local')->exists('public/' . $path)) {
            Storage::disk('local')->makeDirectory($path);
        }

        $filename = str_replace(' ', '_', $filename);

        $image_parts = explode(";base64,", $value);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $file = base64_decode($image_parts[1]);
        $file_name = $path . '/' . $filename . '.' . $image_type;
        $file_location = 'public/' . $file_name;
        Storage::disk('local')->put($file_location, $file);

        return $file_name;
    }
}
