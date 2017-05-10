<?php

namespace toxufe\yii2_xdan_datetimepicker;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;

class DateTimePicker extends \yii\widgets\InputWidget
{
    public $clientOptions = [];

    public $language;

    public $formatDate = 'Y/m/d';

    public $format = 'Y/m/d H:i';

    public $step = 60;

    public $yearStart = 1950;

    public $yearEnd = 2050;

    public $dayOfWeekStart = 0;

    public $datePicker = true;

    public $timePicker = true;

    public $renderIcon = '<a href="" class="glyphicon glyphicon-calendar"></a>';

    public $mask;

    public $maskOptions = [];

    public $maskEnabled = true;

    protected function generateMask()
    {
        return str_replace(
            array('d',  'm',  'i',  'H',  's',  'y',  'Y'),
            array('99', '99', '99', '99', '99', '99', '9999'),
            $this->format
        );
    }

    public function init()
    {
        parent::init();

        if(!$this->getId(false) && isset($this->options['id'])){
            $this->setId($this->options['id']);
        }

        $this->options['id'] = $this->getId();

        $this->clientOptions = array_merge([
            'lang'            => $this->language ? : \Yii::$app->language,
            'format'          => $this->format,
            'formatDate'      => $this->formatDate,
            'step'            => $this->step,
            'yearStart'       => $this->yearStart,
            'yearEnd'         => $this->yearEnd,
            'dayOfWeekStart'  => $this->dayOfWeekStart,
            'datepicker'      => boolval($this->datePicker),
            'timepicker'      => boolval($this->timePicker),
            'onShow'          => new JsExpression('function(current_time,$input){
                if($input.attr(\'disabled\') || $input.attr(\'readonly\')){
                    return false;
                }
            }'),
        ], $this->clientOptions);
    }

    protected function renderInput()
    {
        if(!$this->maskEnabled){
            if ($this->hasModel()) {
                return Html::activeTextInput($this->model, $this->attribute, $this->options);
            }

            return Html::textInput($this->name, $this->value, $this->options);
        }

        return MaskedInput::widget([
            'mask'      => $this->mask ? : $this->generateMask(),
            'model'     => $this->hasModel() ? $this->model : null,
            'attribute' => $this->hasModel() ? $this->attribute : null,
            'value'     => $this->hasModel() ? null : $this->value,
            'options'   => $this->options,
            'clientOptions' => $this->maskOptions,
        ]);
    }

    public function run()
    {
        parent::run();

        DateTimePickerAsset::register($this->view);

        echo $this->renderInput();

        if($this->renderIcon){
            echo $this->renderIcon;
        }
        $js = 'jQuery(\'#'.$this->getId().'\').datetimepicker('.Json::encode($this->getClientOptions()).');';

        if($this->renderIcon){
            $js .= 'jQuery(\'#'.$this->getId().'\').next().on(\'click\', function(){
    var $input = jQuery(\'#'.$this->getId().'\');
    if(!$input.attr(\'disabled\') && !$input.attr(\'readonly\')){
        $input.datetimepicker(\'show\');
    }
    return false;
});';
        }

        $this->view->registerJs($js);
    }


    protected function getClientOptions()
    {
        return $this->clientOptions;
    }
}