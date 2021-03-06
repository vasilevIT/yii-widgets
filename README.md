This extension enhances or adds functionality to existing Yii Framework 2 Widgets to make available other bundled features available in Bootstrap 3.0, new HTML 5 features and affiliated Bootstrap extras.

The **yii2-widgets** bundle automatically includes extensions or widgets from these sub repositories for accessing via `\valievIT\widgets\` namespace.
- [yii2-lazy-select2]


## Installation
The preferred way to install this extension is through [composer](http://getcomposer.org/download/). Remember to refer to the [composer.json](https://github.com/kartik-v/yii2-widgets/blob/master/composer.json) for 
this extension's requirements and dependencies. 


### Pre-requisites

> Note: Check the [composer.json](https://github.com/vasilevIT/yii2-widgets/blob/master/composer.json) for this extension's requirements and dependencies. 

### Install

Either run

```
$ php composer.phar require vasilevit/yii2-widgets "*"
```

or add

```
"vasilevit/yii2-widgets": "*"
```

to the ```require``` section of your `composer.json` file.

# Use

```$php
$form->field($model, 'contact_id')->widget(LazyRefererInput::class, [
    'url' => '/api/some-method',
    'options' => [
        'placeholder' => 'Some placeholder',
    ]
])
```