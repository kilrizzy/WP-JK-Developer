<?php

class Attachment
{

    public $postId;

    public function __construct(){
    }

    public function uploadFeaturedImage($file,$postId=0){
        $this->postId = $postId;
        $filename = basename($file);
        $upload_file = wp_upload_bits($filename, null, file_get_contents($file));
        $attachment_id = false;
        echo $file."<br/>";
        if (!$upload_file['error']) {
            $wp_filetype = wp_check_filetype($filename, null);
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_parent' => $this->postId,
                'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
                'post_content' => '',
                'post_status' => 'inherit'
            );
            $attachment_id = wp_insert_attachment($attachment, $upload_file['file'], $this->postId);
            if (!is_wp_error($attachment_id)) {
                require_once(ABSPATH . "wp-admin" . '/includes/image.php');
                $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
                wp_update_attachment_metadata($attachment_id, $attachment_data);
                update_post_meta($this->postId, '_thumbnail_id', $attachment_id);
            } else {
                $error_string = $attachment_id->get_error_message();
                return $error_string;
            }
        }
        return $attachment_id;
    }

}