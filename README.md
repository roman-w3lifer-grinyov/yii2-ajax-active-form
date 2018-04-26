# yii2-ajax-active-form

- [Installation](#installation)
- [Usage](#usage)

## Installation

``` sh
composer require w3lifer/yii2-ajax-active-form
```

## Usage

- Action:

``` php
/**
 * @return mixed
 * @throws \yii\base\InvalidConfigException
 * @throws \yii\web\BadRequestHttpException
 * @see https://github.com/w3lifer/response-interface
 * @see https://github.com/w3lifer/php-helper
 */
public function actionIndex()
{
    $model = new AjaxActiveFormModel();
    if (Yii::$app->request->isPost) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ResponseInterface::getTrueResponse();
        } else if ($model->errors) {
            $lowerCasedFormName = strtolower($model->formName());
            $errors =
                PhpHelper::add_prefix_to_array_keys(
                    $model->errors,
                    $lowerCasedFormName . '-',
                    false
                );
            return ResponseInterface::getFalseResponse($errors);
        }
        throw new BadRequestHttpException();
    }
    return $this->render('index', compact('model'));
}
```

- View:

``` php
<?php $form = AjaxActiveForm::begin([
    'id' => 'ajax-active-form',
    'successCallback' => 'function (response) { console.log(response); }',
]); ?>

    <?= $form->field($model, 'id')->textInput([
        'readonly' => true,
    ]) ?>

    <?= $form->field($model, 'first_name') ?>

    <?= $form->field($model, 'middle_name') ?>

    <?= $form->field($model, 'last_name') ?>

    <?= Html::submitButton() ?>

<?php AjaxActiveForm::end(); ?>
```
