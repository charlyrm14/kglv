<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException;

class ContentService {

    /**
     * The function `checkContentType` compares two content type IDs and throws an exception if they
     * are not equal.
     * 
     * @param int model_content_type_id The `model_content_type_id` parameter represents the content
     * type ID of a model, while the `content_type_id` parameter represents the content type ID that
     * you want to compare it with. The `checkContentType` function is used to compare these two IDs
     * and throw an exception if they are not equal
     * @param int content_type_id The `content_type_id` parameter represents the expected content type
     * identifier that the function is checking against. This function is designed to compare the
     * `model_content_type_id` with the `content_type_id` parameter. If they do not match, it will
     * throw an exception indicating that the content type is invalid
     */
    public static function checkContentType(int $model_content_type_id, int $content_type_id): void
    {
        if($model_content_type_id !== $content_type_id) {
            throw new HttpResponseException(response()->json([
                'message' => 'Tipo de contenido invalido'
            ], 422));
        }
    }
}
