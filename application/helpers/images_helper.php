<?php 
defined("BASEPATH") OR exit("No direct script access allowed");

if ( ! function_exists(generate_video_thumbnail) ) {
    function generate_video_thumbnail($video, $is_url=true)
    {
        $videoExtension = pathinfo($video["name"], PATHINFO_EXTENSION);

        $videoName = shell_exec("date +%s%N");
        $videoName = filter_var($videoName, FILTER_SANITIZE_NUMBER_INT);
        $videoName = "{$videoName}.{$videoExtension}";
        //generate thumbnail and upload
        $thumbnail_image_name = shell_exec("date +%s%N");
        $thumbnail_image_name = filter_var($thumbnail_image_name, FILTER_SANITIZE_NUMBER_INT);
        $thumbnail = getcwd()."/public/thumbnails/";
        $thumb_path = $thumbnail.$thumbnail_image_name;
        if ( $is_url ) {
            $uploadedVideoName = substr($video, strrpos($video, '/') + 1);
        } else {
            $uploadedVideoName = "";
        }
        shell_exec("ffmpeg -i '".$s3->getAuthenticatedURL(BUCKET_NAME, $uploadedVideoName, 500)."' -deinterlace -an -ss 2 -t 00:00:01 -r 1 -y -vcodec mjpeg -f mjpeg $thumb_path 2>&1");
        $thumb_img = $this->Common_model->s3_uplode($thumbnail_image_name, $thumbnail.$thumbnail_image_name);
        
        unlink($thumb_path);
    }
}