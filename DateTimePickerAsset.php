<?php

namespace toxufe\yii2_xdan_datetimepicker;

use Yii;
use yii\web\JqueryAsset;

class DateTimePickerAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@vendor/bloody-hell/xdan-datetimepicker';

    public $js = [
        'jquery.datetimepicker.js',
    ];

    public $css = [
        'jquery.datetimepicker.css',
    ];

    public function init()
    {
        parent::init();

        $this->depends[] = JqueryAsset::className();
    }


}