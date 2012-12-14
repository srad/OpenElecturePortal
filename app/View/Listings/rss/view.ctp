<?php
App::uses('Sanitize', 'Utility');

foreach ($listings['Video'] as $video) {
    $lastUpdate = strtotime($video['video_date']);

    $link = array(
        'controller' => 'listings',
        'action' => 'view',
        $listings['Listing']['id'],
        $listings['Listing']['category_id'],
        $listings['Listing']['term_id'],
    );

    $bodyText = preg_replace('=\(.*?\)=is', '', $video['description']);
    $bodyText = $this->Text->stripLinks($bodyText);
    $bodyText = Sanitize::stripAll($bodyText);
    $bodyText = $this->Text->truncate($bodyText, 400, array(
        'ending' => '...',
        'exact' => true,
        'html' => true,
    ));

    echo $this->Rss->item(array(), array(
        'title' => $video['title'],
        'link' => $link,
        'guid' => array('url' => $link, 'isPermaLink' => 'true'),
        'description' => $bodyText,
        'pubDate' => $video['video_date']
    ));
}