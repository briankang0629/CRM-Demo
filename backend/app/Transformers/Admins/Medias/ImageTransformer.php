<?php

namespace App\Transformers\Admins\Medias;

use League\Fractal\TransformerAbstract;

class ImageTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform($image)
    {
        $image_domain = config('app.image_domain') . '/' . host_code() . '/image/';
        return [
            'upload_id' => $image->upload_id,
            'type' => $image->type,
            'url' => $image_domain . $image['folder'] . '/' . $image['file_name'],
            'folder' => $image->folder,
            'size' => $image->size,
            'height' => $image->height,
            'width' => $image->width,
            'origin_name' => $image->origin_name,
            'extension' => $image->extension,
            "created_at" => $image->created_at,
            "updated_at" => $image->updated_at,
        ];
    }
}
