<?php
defined("BASEPATH") or exit("No direct script access allowed");
require APPPATH.'composer/vendor/autoload.php';
use Aws\S3\S3Client;

if (!function_exists('s3_get_client')) {
    function s3_get_client()
    {
        $s3 = S3Client::factory([
            "credentials" => [
                "key" => AWS_ACCESSKEY,
                "secret" => AWS_SECRET_KEY,
            ],
            "region" => "us-east-1",
            "version" => "2006-03-01"
        ]);
        return $s3;
    }
}

if (! function_exists("s3_image_uploader")) {
    function s3_image_uploader($imageSource, $imageName, $mimeType = "")
    {
        $s3 = s3_get_client();
        
        $result = $s3->putObject(
            [
                'Bucket'       => AMAZONS3_BUCKET,
                'Key'          => $imageName,
                'SourceFile'   => $imageSource,
                'ContentType'  => $mimeType,
                'ACL'          => 'public-read',
                'StorageClass' => 'REDUCED_REDUNDANCY'
            ]
        );

        return $result['ObjectURL'];
    }
}

if (! function_exists("generate_video_thumbnail")) {
    function generate_video_thumbnail($video, $get_s3_object = false)
    {
        $thumbnail_image_name = shell_exec("date +%s%N");
        $thumbnail_image_name = filter_var($thumbnail_image_name, FILTER_SANITIZE_NUMBER_INT);
        $thumbnail = getcwd()."/public/thumbnail/";
        $thumb_path = $thumbnail.$thumbnail_image_name.".jpeg";
        if ($get_s3_object) {
            $uploadedVideoName = substr($video, strrpos($video, '/') + 1);
        } else {
            $uploadedVideoName = $video;
        }
        $cmd = "ffmpeg -i $uploadedVideoName -deinterlace -an -ss 1 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $thumb_path 2>&1";
        
        shell_exec($cmd);

        $image = s3_image_uploader($thumb_path, "smartguide/".$thumbnail_image_name.".jpg");
        
        unlink($thumb_path);

        return $image;
    }
}

