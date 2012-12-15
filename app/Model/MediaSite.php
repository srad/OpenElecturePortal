<?php
include_once 'AbstractMedia.php';
/**
 * Author: Saman Sedighi Rad
 * Email: saman.sr@gmail.com
 * Date: 20.10.12
 * Time: 20:30
 */
class MediaSite extends AbstractMedia {

    /** Used for the post to query the  */
    const POST_HOST = 'videoportal2.uni-frankfurt.de';
    const POST_PATH = '/Mediasite/Catalog/Data/GetPresentationsForFolder';

    /** @var int Http status code returned by socket */
    private $httpStatus;

    /** @var int MediaSite catalog id */
    private $catalogId;

    /** @var string response header and plain text */
    private $header;

    /** @var string response body as object */
    private $body;

    private $videoData;

    /**
     * Allows changing the catalog id on runtime,
     * without using the setter.
     *
     * @param $id MediaSiteGrabber catalog id.
     * @return mixed
     * @throws Exception
     */
    public function fetch($id) {
        $this->videoData = array(
            'data'    => array(),
            'ids'     => array()
        );

        $this->catalogId = $id;
        $this->createPostRequest();

        if (!isset($this->body->PresentationDetailsList)) {
            CakeLog::write('error', 'MediaSite: ' . $this->body->Message);
            throw new Exception($this->body->Message);
        }

        foreach ($this->body->PresentationDetailsList as $videoData) {
            $dateTime = $this->getParsedTimestamp($videoData->FullStartDate);

            $this->videoData['data'][$videoData->Id] = array(
                'title'           => $videoData->Name,
                'thumbnail'       => str_replace('{0:D4}', '1', $videoData->SlideFormatUrl),
                'subtitle'        => '',
                'speaker'         => (isset($videoData->Presenters[0]) ? $videoData->Presenters[0]->Name : null),
                'location'        => '',
                'date'            => $dateTime->format('Y-m-d H:i:s'),
                'description'     => $videoData->Description,
                'flash'           => null,
                'html5'           => null,
                'quicktime'       => null,
                'mp3'             => null,
                'mobile'          => null,
                'silverlight'     => $videoData->PlayerUrl,
                'video_id'        => $videoData->Id
            );
            array_push($this->videoData['ids'], $videoData->Id);
        }
        return $this->videoData;
    }

    /**
     * Overridden from super class.
     * MediaSite timestamp have the format mm.dd.yyyy hh:mm:ss
     *
     * @param string $datetime
     * @return DateTime
     * @throws Exception
     */
    protected function getParsedTimestamp($datetime) {
        // Format: *mm*dd*yyyy*hh*mm*ss*
        $parsedDateTime = preg_match('/^.*(?P<month>\d{2}).*(?P<day>\d{2}).*(?P<year>\d{4}).*(?P<hours>\d{2}).{1}(?P<minutes>\d{2}).{1}(?P<seconds>\d{2}).*$/', $datetime, $match);
        if ($parsedDateTime > 0) {
            return DateTime::createFromFormat('Y-m-d H:i:s', $match['year'].'-'.$match['month'].'-'.$match['day'].' '.$match['hours'].':'.$match['minutes'].':'.$match['seconds']);
        } else {
            throw new Exception(__('Ungültiges Datumsformat für Medientyp aus MediaSite Datenquelle'));
        }
    }

    private function createPostRequest() {
        $fp = fsockopen(self::POST_HOST, 80, $errno, $errstr, 30);

        if (!$fp) {
            CakeLog::write('error', $errstr);
            throw new Exception('Socket error: {$errstr} ({$errno}');
        }
        fwrite($fp, $this->getPostMessage());

        $response = '';
        while (!feof($fp)) {
            $response .= fgets($fp);
        }
        fclose($fp);

        // split the result header from the content
        $response = explode("\r\n\r\n", $response, 2);

        $this->header = isset($response[0]) ? $response[0] : '';
        $this->body = isset($response[1]) ? $response[1] : '';

        // Detect encoding convert to utf8
        $this->body = json_decode($this->body);

        if ($this->body === null) {
            CakeLog::write('error', json_last_error());
            throw new Exception('JSON-Error (json_last_error): ' . json_last_error());
        }
    }

    private function getPostMessage() {
        $postBody = json_encode($this->getPostBody());

        $out = '';
        $out .= "POST " . self::POST_PATH . " HTTP/1.1\r\n";
        $out .= "Host: " . self::POST_HOST . "\r\n";
        $out .= "Accept: application/json, text/javascript, */*; q=0.01\r\n";
        $out .= "Connection: Close\r\n";
        $out .= "Content-Type: application/json; charset=utf-8\r\n";
        $out .= "X-Requested-With: XMLHttpRequest\r\n";
        $out .= "Referer: http://videoportal2.uni-frankfurt.de/mediasite/Catalog/catalogs/kinderuni2012.aspx?state=UclUNexogiDLzx8TQOL9\r\n";
        $out .= "Cookie: MediasiteCatalog_ClientCaps=ScreenHeight=768&ScreenWidth=1366&IsMobile=false\r\n";
        $out .= "Pragma: no-cache\r\n";
        $out .= "Cache-Control: no-cache\r\n";
        $out .= "Content-Length: " . strlen($postBody) . "\r\n\r\n";
        $out .= $postBody;

        return $out;
    }

    /**
     * Create the actual content body which needs to be send to the server.
     * This body has been reverse engineer via the media site.
     */
    private function getPostBody() {
        return array(
            'IsNewFolder'         => true,
            'AuthTicket'          => null,
            'CatalogId'           => $this->catalogId,
            'CurrentFolderId'     => $this->catalogId,
            'RootDynamicFolderId' => null,
            'ItemsPerPage'        => 1000,
            'PageIndex'           => 0,
            'PermissionMask'      => 'Execute',
            'CatalogSearchType'   => 'SearchInFolder',
            'SortBy'              => 'Date',
            'SortDirection'       => 'Ascending',
            'StartDate'           => null,
            'EndDate'             => null,
            'StatusFilterList'    => null
        );
    }
}