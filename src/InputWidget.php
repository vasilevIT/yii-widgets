<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 06/03/2019
 * Time: 02:12
 */

namespace vasilevit\widgets;

/**
 * Class InputWidget
 * @package vasilevit\widgets
 */
class InputWidget extends \yii\widgets\InputWidget
{
    protected $generated_input_id;
    public $options = ['class' => 'form-control'];
    public $pluginOptions;
    public $fieldOptions;
    public $readonly = false;
    public $disabled = false;

    public function init()
    {
        parent::init();

        if (empty($this->generated_input_id)) {
            $this->generated_input_id = strtolower($this->model->formName()) . '-' . strtolower($this->attribute);
        }
    }

    /**
     * Main method.
     */
    public function run()
    {
        parent::run();
        $this->renderWidget();
    }

    protected function renderAsset()
    {

    }

    protected function renderLabel()
    {
        if (!empty($this->fieldOptions['default_value'])) {
            $this->options['value'] = $this->fieldOptions['default_value'];
        }
    }

    protected function renderInput()
    {

    }

    private function renderWidget()
    {
        $this->renderAsset();
        $this->renderLabel();
        $this->renderInput();
    }

}