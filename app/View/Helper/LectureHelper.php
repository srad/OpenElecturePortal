<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Saman
 * Date: 27.10.12
 * Time: 19:44
 * To change this template use File | Settings | File Templates.
 */
class LectureHelper extends AppHelper {

    /**
     * Creates an list which allows to jump to a video.
     *
     * @param $videos
     * @param bool $ascending Sort order of the ordered list. Via HTML5 tag.
     * @return string
     */
    public function renderIndex($videos, $ascending = false) {
        $html = '<div class="video-index">';
        $size = sizeof($videos);

        $i = 0;
        foreach($videos as $video) {
            $counter = ($ascending) ? $i : $size - $i;

            $html .= '<li value="'.$counter.'"><a href="#video_'.$video['id'].'">' . $video['title'] . '</a></li>';

            $i += 1;
        }

        return '<div class="video-index expandable"><a><i class="icon-plus"></i> '.__('Index').'</a><ol>' . $html . '</ol></div>';
    }

    /**
     * Recursively renders the video list.
     * There are two types of lists which can be generated:
     *
     * 1. Markup for expandable jquery ui accordion:
     *
     *    This will generate accordion to the jquery ui documentataion:
     *
     *    <h3>Video-List Title</h3>
     *    <div>Video-Lists</div>
     *
     *    If the Video list is an accordion the parent *video list title* is
     *    just a plain header and not part of the accordion.
     *
     * 2. The second type is a regular ul list with all videos.
     *
     * @param $videos
     * @param $isDynamicView
     * @param int $depth
     * @param string $className
     * @return string
     */
    public function renderVideoList($videos, $isDynamicView, $depth = 0, $className = '') {
        $html = '';
        $isRoot = ($depth === 0);

        $className .= ($isDynamicView) ? 'dynamic-list' : 'static-list';

        if (($isRoot && !$isDynamicView) || ($depth === 1 && $isDynamicView)) {
            $html .= '<div class="container-videolist">';
        }

        if (!$isDynamicView) {
            $html .= '<ul class="'. $className . '">';
        }

        foreach ($videos as $video) {
            $html .= (($isDynamicView) ? '' : '<li>').  $this->_View->element('video_row', array('depth' => $depth, 'videos' => $video, 'renderIndex' => !$isDynamicView)) . (($isDynamicView) ? '' : '</li>');

            if (sizeof($video['children']) > 0) {
                // Don't use pre-increment. It's ugly.
                $nextDepth = $depth;
                $nextDepth += 1;

                $html .= $this->renderVideoList($video['children'], $isDynamicView, $nextDepth, ' child' . $nextDepth);
            }
        }
        if (!$isDynamicView) {
            $html .= '</ul>';
        }

        if (($isRoot && !$isDynamicView) || ($depth === 1 && $isDynamicView)) {
            $html .= '</div>';
        }

        return $html;
    }

}
