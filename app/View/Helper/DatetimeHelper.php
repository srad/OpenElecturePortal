<?php
/**
 * Author: Saman Sedighi Rad
 * Email: saman.sr@gmail.com
 * Date: 24.10.12
 * Time: 01:01
 */
class DatetimeHelper extends AppHelper {

    public function __construct(View $view, $settings = array()) {
        parent::__construct($view, $settings);
    }

    public function GetSimpleOrFullDate($timestamp) {
        $parsedDate = date("d.m.Y, H:i:s", strtotime($timestamp));
        $temp = explode(', ', $parsedDate);

        if ($this->isTimeMissing($parsedDate)) {
            $parsedDate = $temp[0];
        }
        else {
            $parsedDate = $temp[0] . ', ' . substr($temp[1], 0, 5) . ' ' . __('Uhr');
        }
        return $parsedDate;
    }

    public function isTimeMissing($timestamp) {
        return strpos($timestamp, '00:00:00');
    }
}

?>