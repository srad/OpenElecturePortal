<?php
App::uses('AppModel', 'Model');
/**
 * @author: Saman Sedighi Rad
 * @email: saman.sr@gmail.com
 * @date: 29.10.12
 * @time: 21:01
 * @property Lecture $Lecture
 */
abstract class AbstractMedia extends AppModel {

    public $useTable = 'videos';

    /**
     * Converts the regular datetime format to unix timestamp.
     * The date parsing needed to be a little more elaborate because the inputs vary a lot.
     *
     * @param $datetime string Must have this form, example: '09.11.2012 11:30:00'
     * @return DateTime DateTime object
     * @throws Exception
     */
    protected function getParsedTimestamp($datetime) {
        // Format: *dd*mm*yyyy*hh*mm*ss*
        $regularDateTime = preg_match('/^.*(?P<day>\d{2}).*(?P<month>\d{2}).*(?P<year>\d{4}).*(?P<hours>\d{2}).{1}(?P<minutes>\d{2}).{1}(?P<seconds>\d{2}).*$/', $datetime, $match);
        if ($regularDateTime > 0) {
            return DateTime::createFromFormat('Y-m-d H:i:s', $match['year'] . '-' . $match['month'] . '-' . $match['day'] . ' ' . $match['hours'] . ':' . $match['minutes'] . ':' . $match['seconds']);
        }
        // Format: *dd*mm*yyyy*hh*mm*
        $regularDateTime = preg_match('/^.*(?P<day>\d{2}).*(?P<month>\d{2}).*(?P<year>\d{4}).*(?P<hours>\d{2}).{1}(?P<minutes>\d{2}).*$/', $datetime, $match);
        if ($regularDateTime > 0) {
            return DateTime::createFromFormat('Y-m-d H:m', $match['year'] . '-' . $match['month'] . '-' . $match['day'] . ' ' . $match['hours'] . ':' . $match['minutes']);
        }
        // Format: *yyyy*mm*dd*hh*mm*
        $regularDateTime2 = preg_match('/^.*(?P<year>\d{4}).*(?P<month>\d{2}).*(?P<day>\d{2}).*(?P<hours>\d{2}).{1}(?P<minutes>\d{2}).*$/', $datetime, $match);
        if ($regularDateTime2 > 0) {
            return DateTime::createFromFormat('Y-m-d H:m', $match['year'] . '-' . $match['month'] . '-' . $match['day'] . ' ' . $match['hours'] . ':' . $match['minutes']);
        }
        // Format: *dd*mm*yyyy*
        $onlyDate = preg_match('/^.*(?P<day>\d{2}).*(?P<month>\d{2}).*(?P<year>\d{4}).*$/', $datetime, $match);
        if ($onlyDate > 0) {
            return DateTime::createFromFormat('Y-m-d', $match['year'] . '-' . $match['month'] . '-' . $match['day']);
        }
        // Format: *yyyy*mm*dd*
        $onlyDate2 = preg_match('/^.*(?P<year>\d{4}).*(?P<month>\d{2}).*(?P<day>\d{2}).*$/', $datetime, $match);
        if ($onlyDate2 > 0) {
            return DateTime::createFromFormat('Y-m-d', $match['year'] . '-' . $match['month'] . '-' . $match['day']);
        }
        throw new Exception(__('Ungültiges Datum für importiertes Medium'));
    }

    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Lecture' => array(
            'className' => 'Lecture',
            'foreignKey' => 'lecture_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    /**
     * hasAndBelongsToMany associations
     *
     * @var array
     */
    public $hasAndBelongsToMany = array(
        'Type' => array(
            'className' => 'Type',
            'joinTable' => 'videos_types',
            'foreignKey' => 'video_id',
            'associationForeignKey' => 'type_name',
            'unique' => 'keepExisting',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );

}
