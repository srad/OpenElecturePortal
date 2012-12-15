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
            'data' => array(),
            'ids' => array()
        );

        foreach ($this->getVideoHTMLChunk($id) as $videoHtml) {
            $this->extractVideoData($videoHtml);

            $this->videoData['data'][$this->data['video_id']] = $this->data;
            array_push($this->videoData['ids'], $this->data['video_id']);
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
    private function getVideoHTMLChunk($id) {
        $html = file_get_contents(self::PLS_BASE_URL . $id . '.js');

        $elements = explode('<div class=\\"videoms_clip_box\\">', $html);
        array_shift($elements);

        return $elements;
    }

    /**
     * Using regular expression to extract the html content.
     *
     * This is ugly as hell because the string we have is a javascript string
     * which contains escaped HTML. You can't get worse than that.
     *
     * @param $html
     * @return array
     */
    public function extractVideoData($html) {
        unset($this->data);
        $this->data = array();

        $finds = preg_match('#((?s).*?)<h2>((?s).*?)<\\\/h2>((?s).*?)#i', $html, $matches);
        $this->data['title'] = ($finds > 0) ? trim($matches[2]) : '';

        $finds = preg_match('#((?s).*?)<img.*?src=\\\"((?s).*?)\\\" \\/>((?s).*?)#i', $html, $matches);
        $this->data['thumbnail'] = ($finds > 0) ? $matches[2] : '';

        $finds = preg_match('#((?s).*?)<li><span>(Untertitel|Subtitle):<\\\/span>&nbsp;((?s).*?)<\\\/li>((?s).*?)#i', $html, $matches);
        $this->data['subtitle'] = ($finds > 0) ? trim($matches[3]) : '';

        $finds = preg_match('#((?s).*?)<li><span>(Sprecher|Speaker):<\\\/span>&nbsp;((?s).*?)<\\\/li>((?s).*?)#i', $html, $matches);
        $this->data['speaker'] = ($finds > 0) ? trim($matches[3]) : '';

        $finds = preg_match('#((?s).*?)<li><span>(Ort|Location):<\\\/span>&nbsp;((?s).*?)<\\\/li>((?s).*?)#i', $html, $matches);
        $this->data['location'] = ($finds > 0) ? trim($matches[3]) : '';

        $finds = preg_match('#((?s).*?)<li><span>(Datum|Date):<\\\/span>&nbsp;((?s).*?)<\\\/li>((?s).*?)#i', $html, $matches);
        var_dump($matches);
        var_dump(explode('<li', $html));
        $this->data['date'] = ($finds > 0) ? trim($matches[3]) : '';

        $dateTime = $this->getParsedTimestamp($this->data['date']);
        $this->data['date'] =  $dateTime->format('Y-m-d H:i:s');

        $this->setMediaLinks($html);

        $finds = preg_match('#((?s).*?)<li><span>(Beschreibung|Description):<\\\/span>&nbsp;<p>((?s).*?)<\\\/p><\\\/li>((?s).*?)#i', $html, $matches);
        $this->data['description'] = ($finds > 0) ? trim($matches[3]) : '';
    }

    /**
     * Extracts the video links from vilea.
     *
     * @param $html Raw HTML.
     * @return array Hash of video type => video link
     */
    private function setMediaLinks($html) {
        // All video element are wrapped within this escaped html tags.
        $videosStartElement = '<ul class="\&quot;videoms_player_formats" videoms_format_btn\"="">';
        $videosEndElement = '</ul>';

        $listItems = substr($html, strpos($html, $videosStartElement) + strlen($videosStartElement), strpos($html, $videosEndElement) - strlen($videosStartElement));
        $listItems = explode("<li", $html);

        foreach ($listItems as $item) {
            preg_match('#((?s).*?)<a.*?href=\\\"((?s).*?)\\\".*?#i', $item, $matches);

            if (sizeof($matches) > 0) {
                $match = $matches[2];

                // 'Search Substring' => 'Assign to array Key'
                $subStrings = array(
                    'silverlight' => 'silverlight',
                    'flash'       => 'flash',
                    'html5'       => 'html5',
                    'mp3'         => 'mp3_audio',
                    'mobile'      => 'mobile',
                    'quicktime'   => 'quicktime'
                );

                foreach($subStrings as $key => $subString) {
                    if (strpos($match, $subString) !== false) {
                        $this->data[$key] = $match;

                        if (!isset($this->data['video_id'])) {
                            // The video id is within the url, i.e.: 'http://electure-ms.studiumdigitale.uni-frankfurt.de/vod/clips/4qY7PMq96A/quicktime.mp4'
                            // It's not the same as the vilea videolist id.
                            $url = explode('/', $this->data[$key]);
                            $this->data['video_id'] = $url[sizeof($url) - 2];
                        }
                    }
                }
            }
        }
    }

}