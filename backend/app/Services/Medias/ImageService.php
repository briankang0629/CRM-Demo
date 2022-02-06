<?php

namespace App\Services\Medias;

use App\Models\Medias\Folder;
use App\Models\Medias\Image;
use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    const ALLOW_FORMAT = [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'heif',
        'webp'
    ];

    /**
     * @var object
     */
    private $image;

    /**
     * @var string
     */
    private $gcsUrl;

    /**
     * ProductService constructor.
     *
     * @param Image $image
     */
    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    /**
     * lists
     *
     * @param array $request
     * @return mixed
     */
    public function lists(array $request)
    {
        //取所有圖片
        $request['type'] = 'image';
        $images = $this->image->search($request)->get();

        return $images;
    }

    /**
     * info
     *
     * @param $id
     * @return Image
     */
    public function info(int $id) :Image
    {
        return $this->image->findOrFail($id);
    }

    /**
     * @param array $request
     * @return array
     * @throws CustomException
     */
    public function store(array $request) : array
    {
        // file 檔案
        if (\Arr::get($request, "uploadFile")) {
            $uploadFile = $request["uploadFile"];
        } else {
            $uploadFile = $request["upload"];
        }

        //驗證
        $folder = \Arr::get($request, 'folder', 'default');

        // 先取得附檔名
        $extension = strtolower(pathinfo($request["filename"] , PATHINFO_EXTENSION));

        // 判斷檔案格式
        if (!in_array($extension , self::ALLOW_FORMAT)) {
            throw new CustomException(trans('common.uploads.invalidFormant'));
        }

        // 上傳到google storage
        $this->gcsUrl = host_code() . '/image/' . $folder;
        $disk = Storage::disk('gcs');
        $imagePath = $disk->put($this->gcsUrl, $uploadFile);
        $fileName = str_replace($this->gcsUrl . '/', '', $imagePath);

        // 取得圖片大小
        $imageInfo = getimagesize($uploadFile);

        //傳送參數
        $image = Image::create([
            'file_name' => $fileName ,
            'origin_name' => $uploadFile->getClientOriginalName() ,
            'type' => 'image' ,
            'extension' => $extension ,
            'size' => $uploadFile->getSize() ,
            'height' => $imageInfo[1] ,// 取得圖片寬度
            'width' => $imageInfo[0] , // 取得圖片高度
            'folder' => $folder ,
        ]);

        //操作記錄
        $this->image->writeLog(14, $image);

        return [
            'uploadId' => $image->upload_id,
            'url' => config('app.image_domain') . '/' . $this->gcsUrl . '/' . $fileName,
        ];
    }

    /**
     * createFolder
     * @param array $request
     */
    public function createFolder(array $request)
    {
        $folder = Folder::create($request);
        $folder->category = 'image';
        $folder->save();
    }

    /**
     * delete
     *
     * @param array $id
     * @return void
     */
    public function delete(array $ids) : void
    {
        $disk = Storage::disk('gcs');
        $images = $this->image->find($ids);

        $images->each(function ($image) use ($disk) {
            $sourceUrl =  host_code() . '/image/' . $image->folder . '/' . $image->file_name;
            $trashUrl = host_code() . '/trash/' . $image->file_name;

            if ($disk->exists($sourceUrl)) {
                $disk->move($sourceUrl, $trashUrl);
            }
        });

        $this->image->whereIn('upload_id', $ids)->delete();
        \DB::table('upload_related')->whereIn('upload_id', $ids)->delete();

        //操作記錄
        $this->image->writeLog(15);
    }

    /**
     * getUrlByUploadId
     *
     * @param integer $uploadId
     * @return string
     */
    public function getUrlByUploadId(int $uploadId): string
    {
        if (!$image = $this->image->find($uploadId)) {
            return '';
        }

        $image_domain = config('app.image_domain') . '/' . host_code() . '/image/';

        return $image_domain . $image['folder'] . '/' . $image['file_name'];
    }
}
