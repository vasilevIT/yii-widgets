<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 06/03/2019
 * Time: 02:13
 */

namespace vasilevit\widgets;

use kartik\select2\Select2;
use yii\web\JsExpression;
use yii\web\View;

/**
 * Class LazyRefererInput
 * @package vasilevit\widgets
 */
class LazyRefererInput extends InputWidget
{

    public $data;

    public $url;

    protected $initSelection;
    protected $formatResults;
    protected $processResults;

    protected $initSelectionExpression;
    protected $formatResultsExpression;
    protected $processResultsExpression;

    protected function renderLabel()
    {
        parent::renderLabel();
    }

    /**
     * Render widget
     */
    protected function renderInput()
    {

        $url = $this->url;

        $this->initSelection = <<< 'JS'
        var initSelection = function(element, callback) {
    var title=$(element).val();
    if (title !== "") {
        $.ajax("{url}", {
            dataType: "json",
            type: 'post',
            data: {
                title: '',
                id: title
            }
        }).done(function(data) { callback(data.results);});
    }
}

JS;
        $this->initSelectionExpression = new JsExpression('initSelection');
        $this->formatResultsExpression = new JsExpression('formatResults');
        $this->formatResults = <<< 'JS'
        var formatResults = function (item) {
    if (!item.name) {
        return item.text;
    }
    var markup =
'<div style="100%">' + 
    '<div class="query-result-title">' +
        '<b style="margin-left:5px">' + item.name + '</b>' + 
    '</div>' +
    '<div class="query-result-description">' +
        '<p style="margin-left:5px; font-size:10px; color:#5dade1;"> ' + item.subname +  ' (' + item.id + ')</p>' + 
    '</div>' +
'</div>';
    return '<div style="overflow:hidden;">' + markup + '</div>';
};
JS;
        $this->processResults = '';
        $this->initSelection = str_replace('{url}', $this->url, $this->initSelection);

        $this->pluginOptions = [
            'allowClear' => true,
            'minimumInputLength' => 1,
            'ajax' => [
                'url' => $url,
                'dataType' => 'json',
                'type' => 'get',
                'delay' => 250,
                'data' => new JsExpression('function(params) { return {
                title:params.term,
                id: ' . (int)$this->value . ' 
                }; }'),
            ],
            'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
            'templateSelection' => new JsExpression('function (product) { return product.name; }'),
        ];
        $this->additionalJS();


        // Register the formatting script
        $id = rand(0, 1000) . '_lazy_input';
        $this->getView()->registerJs($this->formatResults, View::POS_HEAD);
        $this->getView()->registerJs($this->initSelection, View::POS_HEAD);
        $this->getView()->registerJs($this->processResults, View::POS_HEAD);

        if (!empty($this->formatResultsExpression)) {
            $this->pluginOptions['templateResult'] = $this->formatResultsExpression;
        }
        if (!empty($this->initSelectionExpression)) {
            $this->pluginOptions['initSelection'] = $this->initSelectionExpression;
        }
        if (!empty($this->processResultsExpression)) {
            $this->pluginOptions['ajax']['processResults'] = $this->processResultsExpression;
        }

        echo Select2::widget([
            'id' => $id,
            'model' => $this->model,
            'attribute' => $this->attribute,
            'language' => 'ru',
            'options' => [
                'placeholder' => $this->options['placeholder'],
                'autocomplete' => 'off',
            ],
            'value' => $this->value,
            'initValueText' => $this->value,
            'pluginOptions' => $this->pluginOptions,
        ]);
    }

    protected function additionalJS()
    {

    }

}