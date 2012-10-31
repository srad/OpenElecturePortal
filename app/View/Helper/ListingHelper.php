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
                $html .= $this->generateRowsRecursively($listing['children'], 'child', ++$depth);
            }
        }
        return $html;
    }

}
