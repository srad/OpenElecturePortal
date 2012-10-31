<?php
/**
 * Author: Saman Sedighi Rad
 * Email: saman.sr@gmail.com
 * Date: 23.10.12
 * Time: 20:22
 */
App::uses('Component', 'Controller');

class ImageResizerComponent extends Component {

    private $image;
    private $mimeType;
    private $url;
    private $width;
    private $height;
    private $thumnail;

    public function getImageFromUrlAndResize($url, $width, $height) {
        $this->url = $url;
        $this->image = file_get_contents($this->url);
        $this->loadImageProperties();

        // TODO: resizing on the fly doesn't work
        /*
                $this->resize($width, $height);
                $this->load();
        */
        return array(
            'image-data' => $this->image,
            'mime-type'  => $this->mimeType
        );
    }

    public function getMimeType() {
        return $this->mimeType;
    }

    private function loadImageProperties() {
        $image_info = getimagesize($this->url);
        $this->width = $image_info[0];
        $this->height = $image_info[1];
        $this->mimeType = $image_info["mime"];
    }

    private function load() {
        switch ($this->mimeType) {
            case "image/jpeg":
                imagejpeg($this->thumnail);
                break;
            case "image/gif":
                imagegif($this->thumnail);
                break;
            case "image/png":
                imagepng($this->thumnail);
                break;
            default:
                throw new Exception('Thumbnail file format not supported');
                break;
        }
    }

    private function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = null) {
        if ($image_type == IMAGETYPE_JPEG) {
            imagejpeg($this->image, $filename, $compression);
        } elseif ($image_type == IMAGETYPE_GIF) {

            imagegif($this->image, $filename);
        } elseif ($image_type == IMAGETYPE_PNG) {

            imagepng($this->image, $filename);
        }
        if ($permissions != null) {

            chmod($filename, $permissions);
        }
    }

    private function getWidth() {
        return $this->width;
    }

    private function getHeight() {
        return $this->height;
    }

    private function resizeToHeight($height) {
        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width, $height);
    }

    private function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width, $height);
    }

    private function scale($scale) {
        $width = $this->getWidth() * $scale / 100;
        $height = $this->getheight() * $scale / 100;
        $this->resize($width, $height);
    }

    private function resize($width, $height) {
        $this->thumnail = imagecreatetruecolor($width, $height);
        imagecopyresized($this->thumnail, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        return;

        $new_image = imagecreatetruecolor($width, $height);
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }
}