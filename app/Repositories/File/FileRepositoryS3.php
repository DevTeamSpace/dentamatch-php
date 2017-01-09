<?php

namespace App\Repositories\File;

use Illuminate\Http\Request;
use Storage;

trait FileRepositoryS3 {

    protected $awsObj;

    public function createObject() {
        $this->awsObj = Storage::disk('s3');
    }

    public function generateFilename($userId, $imageType) {
        return $imageType . '/' . uniqid(TRUE);
    }

    public function uploadFileToAWS(Request $request, &$filename, $losslessCompress = 0) {
        $file = file_get_contents($request->file('image')->getRealPath());
        $check = getimagesize($request->file('image')->getRealPath());
        $res = 0;
        if (in_array(strtolower($check['mime']), ['image/jpeg', 'image/jpg', 'image/png'])) {
            $filename = $filename . str_replace("image/", ".", $check['mime']);
            $this->createObject();
            $res = $this->awsObj->put($filename, $file, 'public');
            if ($res != 1) {
                $res = 0;
            }
            return ['res' => $res, 'file' => $filename];
        }
    }

    protected function createCompressedImage(Request $request, $filename, $fileExt) {
        $compressFile = '/usr/share/nginx/html/compressImage/' . uniqid(TRUE) . str_replace("image/", ".", $fileExt);
        $command = 'ffmpeg -i ' . $this->awsObj->url($filename) . ' -vframes 1 -compression_level 0 ' . $compressFile . ' > storage/logs/ffmpeglog.log';
        $res = passthru($command);
        $this->awsObj->put('compress/' . $filename, file_get_contents($compressFile), 'public');
        unlink($compressFile);
    }

    public function deleteFileFromAWS($filename) {
        $this->createObject();
        if ($this->awsObj->exists($filename)) {
            $this->awsObj->delete($filename);
        }
    }

    public function fileExists($filename) {
        $this->createObject();
        $status = false;
        if ($this->awsObj->exists($filename)) {
            $status = true;
        }
        return $status;
    }

    public function upload($image, $type, $name = '', $thumb = 0, $id = '') {
        $filename = '';
        $thumnail_name = '';
        if (is_object($image) && $image->isValid()) {

            $extension = $image->getClientOriginalExtension();
            if ($name == '') {
                $filename = uniqid() . '.' . $extension;
            } else {
                $filename = $this->getFilename($name);
            }

            $path = $this->getPath($type, $id);

            $this->s3->putObjectFile($image->getRealPath(), env('AWS_BUCKET_NAME'), $path . $filename);

            if ($thumb == 1) {
                $f = explode(".", $filename);

                $thumnail_name = $f[0] . "_thumb.png";
                exec('ffmpeg -i ' . $image->getRealPath() . ' -f image2 -vframes 1 ' . public_path('thumbnails/' . $thumnail_name) . ' > storage/logs/ffmpeglog.log');
                $this->s3->putObjectFile(public_path('thumbnails/' . $thumnail_name), env('AWS_BUCKET_NAME'), $path . $thumnail_name);
                unlink(public_path('thumbnails/' . $thumnail_name));
            }
        }
        return array('image' => $filename, 'thumb' => $thumnail_name);
    }

    public function downloadAlbumFolder($localFolderPath, $bucketFolderPath, $bucketUploadPath, $eventAlbum) {
        $result = 0;
        $this->createObject();
        //$files = Storage::allFiles($bucketFolderPath);

        $zip = new \ZipArchive();

        $zip->open($localFolderPath, \ZipArchive::CREATE);

        foreach ($eventAlbum as $file) {
            $fileName = substr(strrchr($file->photoUrl, "/"), 1);
            $zip->addFromString($fileName, $this->awsObj->get($file->photoUrl));
        }
        $zip->close();

        $result = $this->awsObj->put($bucketUploadPath, fopen($localFolderPath, 'r+'), 'public');

        if ($result == 1) {
            unlink($localFolderPath);
        }

        return $result;
    }

    public function downloadEnhancedPhotos($localFolderPath, $bucketUploadPath, $photoArray) {
        $result = 0;
        if (!empty($photoArray)) {
            $this->createObject();
            $files = $photoArray;
            $zip = new \ZipArchive();

            $zip->open($localFolderPath, \ZipArchive::CREATE);

            foreach ($files as $file) {
                $fileName = substr(strrchr($file, "/"), 1);
                $zip->addFromString($fileName, $this->awsObj->get($file));
            }
            $zip->close();

            $result = $this->awsObj->put($bucketUploadPath, fopen($localFolderPath, 'r+'), 'public');

            if ($result == 1) {
                unlink($localFolderPath);
            }
        }
        return $result;
    }

}
