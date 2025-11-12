<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class Banner extends ObjectModel
{
    public $id_banner;
    public $title;
    public $image;
    public $link;
    public $positions;
    public $date_from;
    public $date_to;
    public $status;
    public $priority;

    public static $definition = [
        'table' => 'dynamic_banners',
        'primary' => 'id_banner',
        'fields' => [
            'title' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true],
            'image' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName'],
            'link' => ['type' => self::TYPE_STRING, 'validate' => 'isUrl'],
            'positions' => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName'],
            'date_from' => ['type' => self::TYPE_DATE],
            'date_to' => ['type' => self::TYPE_DATE],
            'status' => ['type' => self::TYPE_BOOL],
            'priority' => ['type' => self::TYPE_INT],
        ],
    ];
}