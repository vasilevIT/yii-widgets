<?php
/**
 * Created by PhpStorm.
 * User: Anton Vasiliev <bysslaev@gmail.com>
 * Date: 03/04/2019
 * Time: 12:59
 */

namespace vasilevit\widgets;

use yii\helpers\Html;
use yii\web\View;

/**
 * Class LazySuggestionInput
 * @package app\components
 */
class LazySuggestionInput extends InputWidget
{
    public $handler;
    public $id;
    public $url;

    public function renderInput()
    {

        if (empty($this->id)) {
            $this->id = rand(0, 1000) . '_suggestion_input';
        }
        $this->additionalCSS();
        $this->additionalJS();
        echo Html::activeTextInput($this->model, $this->attribute, [
            'class' => 'vasilevit-suggestion-input',
            'id' => $this->id,
            'autocomplete' => 'off',
            'data-url' => '/api/',
        ]);
        echo "<div class='vasilevit-suggestion-list-wrapper'><div id='{$this->id}_list' class='vasilevit-suggestion-list'></div></div>";
    }

    private function additionalCSS()
    {
        $css = <<< 'CSS'
        .vasilevit-suggestion-list-wrapper {
            position: relative;
        }
        
        .vasilevit-suggestion-list {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            border: 1px solid #eee;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 20px #eee;
        }
        .vasilevit-suggestion-list.open {
            display: block;
            
        }

        .vasilevit-suggestion-list .vasilevit-suggestion-list-item {
           padding: 10px;
           border-bottom: 1px solid #eee;
           cursor: pointer;
        }
        
        .vasilevit-suggestion-list .vasilevit-suggestion-list-item:hover {
            background: #d3d6df;
        }

        .vasilevit-suggestion-list .vasilevit-suggestion-list-item .subname {
            color: #4d6987;
            font-size: 11px;
        }
CSS;

        $this->getView()->registerCss($css);
    }

    private function additionalJS()
    {
        $this->handler = <<< 'JS'
            function searchSuggestionInput(input)
            {
                 $.ajax({
                url: '{url}',
                method: 'post',
                dataType: 'json',
                data: {
                    title: $(input).val() 
                },
                success: function(response) {
                    console.log('success', response);
                //    Display list items
                    if (response.results && response.results.length) {
                        $('#{id}_list').html('');
                        for(let i = 0; i < response.results.length; i++){
                            let item = response.results[i];
                            let item_html = $('<div></div>').addClass('vasilevit-suggestion-list-item');
                            item_html.append($('<div></div>').addClass('name').text(item.name));
                            item_html.append($('<div></div>').addClass('subname').text(item.subname));
                            $('#{id}_list').append(item_html);
                        }
                        $('#{id}_list').addClass('open');
                    }
                },
                error: function(response) {
                    console.log('error', response.message);
                }
                });
            }
            
            
            $('#{id}').on('focus', function(e) {
                searchSuggestionInput(this);
                if ($(this).val()) {
                    $('#{id}_list').addClass('open');
                }
            });

            $('#{id}').on('blur', function(e) {
                    setTimeout(function() {
                        $('#{id}_list').removeClass('open');
                    }, 500);
            });
            
            $('#{id}').on('input', function(e) {
              searchSuggestionInput(this);
            });
            
            
            $('#{id}_list').on('click', '.vasilevit-suggestion-list-item', function(e) {
                $('#{id}').val($(this).find('.name').text());
                if ($('#w0').length) {
                    $('#w0').yiiGridView("applyFilter");
                }
            });
JS;
        $this->handler = str_replace('{id}', $this->id, $this->handler);
        $this->handler = str_replace('{url}', $this->url, $this->handler);

        $this->getView()->registerJs($this->handler, View::POS_END);
    }
}