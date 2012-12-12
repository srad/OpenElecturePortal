<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Saman
 * Date: 27.10.12
 * Time: 19:44
 * To change this template use File | Settings | File Templates.
 */
class ListingHelper extends AppHelper {

    public function generateRowsRecursively($listings, $className = 'parent', $depth = 0) {
        $html = '';

        foreach ($listings as $listing) {
            $id = $listing['Listing']['id'];
            $name = $listing['Listing']['name'];
            $code = $listing['Listing']['code'];
            $indent = (($depth > 0) ? "'" : "") . str_repeat('--', $depth);

            $html .= '<tr id="list_'.$id.'" class="'.$className.' depth-'.$depth.'">';
            $html .= '<td class="center"><span class="icon-resize-vertical"></span></td>';
            $html .= '<td class="center"><span class="icon-arrow-left"></span></td>';
            $html .= '<td class="center"><span class="icon-arrow-right"></span></td>';
            $html .= '<td>';
            $html .= '    <table style="embedded-col"><tbody><tr>';
            $html .= '    <td>'.$indent.'</td><td><input class="name" type="text" value="'.$name.'" /></td>';
            $html .= '    </tr></tbody></table>';
            $html .= '</td>';
            $html .= '<td><input class="code" type="text" value="'.$code.'" /></td>';
            $html .= '<td><label class="checkbox"><input type="checkbox" />'.__('Deaktiv.').'</label></td>';
            $html .= '<td><label class="checkbox"><input type="checkbox" />'.__('Dyn. expad.').'</label></td>';
            $html .= '<td><label class="checkbox"><input type="checkbox" />'.__('Aufst. Sort.').'</label></td>';
            $html .= '<td class="center"><button class="btn btn-mini">'.__('Hinzuf.').'</button></td>';
            $html .= '<td class="center"><button class="btn btn-danger btn-mini">'.__('Lösch.').'</button></td>';
            $html .= '</tr>';

            if (sizeof($listing['children'] > 0)) {
                $depth += 1;
                $html .= $this->generateRowsRecursively($listing['children'], 'child', $depth);
            }
        }
        return $html;
    }

    public function renderIndex($videos) {
        $html = '<ol class="video-index">';

        foreach($videos as $video) {
            $html .= '<li><a href="#video_'.$video['id'].'">' . $video['title'] . '</a><li>';
        }

        $html .= '</ol>';

        return $html;
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
            $html .= (($isDynamicView) ? '' : '<li>').  $this->_View->element('video_row', array('depth' => $depth, 'videos' => $video)) . (($isDynamicView) ? '' : '</li>');

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
