<?php
include_once 'AbstractMedia.php';
/**
 * Author: Saman Sedighi Rad
 * Email: saman.sr@gmail.com
 * Date: 20.10.12
 * Time: 20:29
 */
class Vilea extends AbstractMedia {

    private $videoData = array();

    const PLS_BASE_URL = 'http://electure-ms.studiumdigitale.uni-frankfurt.de/vod/playlists/';

    /**
     * Returns an array of the fetched video data.
     *
     * @param $id
     * @return array
     */
    public function fetch($id) {
        $this->videoData = array(
            'data'    => array(),
            'ids'     => array()
        );

        foreach ($this->getHtml($id) as $videoHtml) {
            $extractVideoData = $this->extractVideoData($videoHtml);

            $this->videoData['data'][$extractVideoData['video_id']] = $extractVideoData;
            array_push($this->videoData['ids'], $extractVideoData['video_id']);
        }
        return $this->videoData;
    }

    /**
     * Fetches html content from the vilea website and
     * returns the raw html chunks for each video block.
     *
     * @param $id
     * @return array
     */
    private function getHtml($id) {
        $html = file_get_contents(self::PLS_BASE_URL . $id . '.html');
        $html = substr($html, strpos($html, '<body>') + 7, strpos($html, '</body>') - 7);

        $elements = explode('<div class="videoms_clip_box">', $html);
        array_shift($elements);

        return $elements;
    }

    /**
     * Using regular expression to extract the html content.
     * TODO: Should probably be moved to a DOM parser, regex for html unreliable.
     * @param $html
     * @return array
     */
    public function extractVideoData($html) {
        $data = array();

        $finds = preg_match('/((?s).*?)<h2>((?s).*?)<\/h2>((?s).*?)/i', $html, $matches);
        $data['title'] = ($finds > 0) ? trim($matches[2]) : '';

        $finds = preg_match('/((?s).*?)<img.*?src=\"((?s).*?)\" \/>((?s).*?)/i', $html, $matches);
        $data['thumbnail'] = ($finds > 0) ? $matches[2] : '';

        $finds = preg_match('/((?s).*?)<li><span>(Untertitel|Subtitle):<\/span>&nbsp;((?s).*?)<\/li>((?s).*?)/i', $html, $matches);
        $data['subtitle'] = ($finds > 0) ? trim($matches[3]) : '';

        $finds = preg_match('/((?s).*?)<li><span>(Sprecher|Speaker):<\/span>&nbsp;((?s).*?)<\/li>((?s).*?)/i', $html, $matches);
        $data['speaker'] = ($finds > 0) ? trim($matches[3]) : '';

        $finds = preg_match('/((?s).*?)<li><span>(Ort|Location):<\/span>&nbsp;((?s).*?)<\/li>((?s).*?)/i', $html, $matches);
        $data['location'] = ($finds > 0) ? trim($matches[3]) : '';

        $finds = preg_match('/((?s).*?)<li><span>(Datum|Date):<\/span>&nbsp;((?s).*?)<\/li>((?s).*?)/i', $html, $matches);
        $data['date'] = ($finds > 0) ? trim($matches[3]) : '';

        $dateTime = $this->getParsedTimestamp($data['date']);
        $data['date'] =  $dateTime->format('Y-m-d H:i:s');

        $finds = preg_match('/((?s).*?)<li><span>(Beschreibung|Description):<\/span>&nbsp;<p>((?s).*?)<\/p><\/li>((?s).*?)/i', $html, $matches);
        $data['description'] = ($finds > 0) ? trim($matches[3]) : '';

        // LINK FLASH
        $data['flash'] = $this->regex_extract($html, "/.*?<li class=\"videoms_flash\".*? href=\"(.*?)\".*?>.*?<\/li>.*?/is");

        // LINK HTML5
        $data['html5'] = $this->regex_extract($html, "/.*?<li class=\"videoms_html5\".*? href=\"(.*?)\".*?>.*?<\/li>.*?/is");

        // LINK QUICKTIME
        $data['quicktime'] = $this->regex_extract($html, "/.*?<li class=\"videoms_quicktime\".*? href=\"(.*?)\".*?>.*?<\/li>.*?/is");

        // LINK MP3
        $data['mp3'] = $this->regex_extract($html, "/.*?<li class=\"videoms_mp3_audio\".*? href=\"(.*?)\".*?>.*?<\/li>.*?/is");

        // LINK MOBILE
        $data['mobile'] = $this->regex_extract($html, "/.*?<li class=\"videoms_mobile\".*? href=\"(.*?)\".*?>.*?<\/li>.*?/is");

        // LINK SILVERLIGHT
        $data['silverlight'] = $this->regex_extract($html, "/.*?<li class=\"videoms_silverlight\".*? href=\"(.*?)\".*?>.*?<\/li>.*?/is");

        $try = 0;
        $data['video_id'] = '';

        while (empty($data['video_id'])) {
            switch ($try) {
                case 0:
                    $data['video_id'] = $this->regex_extract($data['thumbnail'], "/.*?\/clips\/(.*?)\/[^\/]*/i");
                    break;
                case 1:
                    $data['video_id'] = $this->regex_extract($data['flash'], "/.*?\/clips\/(.*?)\/[^\/]*/i");
                    break;
                case 2:
                    $data['video_id'] = $this->regex_extract($data['html5'], "/.*?\/clips\/(.*?)\/[^\/]*/i");
                    break;
                case 3:
                    $data['video_id'] = $this->regex_extract($data['quicktime'], "/.*?\/clips\/(.*?)\/[^\/]*/i");
                    break;
                case 4:
                    $data['video_id'] = $this->regex_extract($data['mp3'], "/.*?\/clips\/(.*?)\/[^\/]*/i");
                    break;
                case 5:
                    $data['video_id'] = $this->regex_extract($data['mobile'], "/.*?\/clips\/(.*?)\/[^\/]*/i");
                    break;
                case 6:
                    $data['video_id'] = $this->regex_extract($data['silverlight'], "/.*?\?peid=([^\/]*)/i");
                    break;
            }

            $try++;

            if ($try > 6) {
                break;
            }
        }
        return $data;
    }

    /*
    extracts from $content via $regex.
    return value: content of first brackets, empty string if no match.
    example: $regex = "/.*?<h2>(.*?)<\/h2>.*?/i" will return TITLE of <h2>TITLE</h2>
    */
    private function regex_extract($content, $regex, $index = 1) {
        preg_match($regex, $content, $match);

        if ($index != 1 && empty($match[$index])) {
            $index = 1;
        }
        return empty($match[1]) ? '' : $match[1];
    }
}

?>