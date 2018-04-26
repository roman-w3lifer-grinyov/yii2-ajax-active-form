<?php

namespace w3lifer\yii2;

use yii\widgets\ActiveForm as YiiActiveForm;

class AjaxActiveForm extends YiiActiveForm
{
    /**
     * @var string
     */
    public $successCallback = 'function () {}';

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        parent::run();
        $js = $this->getAjaxActiveFormJs();
        $this->getView()->registerJs($js);
    }

    /**
     * @return string
     */
    protected function getAjaxActiveFormJs()
    {
        $id = $this->getId();
        return <<<JS

(function () {

  var \$form = $('#{$id}'); 

  var \$formIsBlocked = false;
  
  \$form.on('beforeSubmit', function () {
    if (\$formIsBlocked) {
      return;
    }
    \$formIsBlocked = true;
    $.ajax(\$form.attr('action'), {
      method: \$form.attr('method'),
      data: \$form.serialize(),
      success: function (response) {
        if (!response.success) {
          if (response.errors) {
            \$form.yiiActiveForm('updateMessages', response.errors);
          }
          return;
        }
        ({$this->successCallback})(response);
      },
      complete: function () {
        \$formIsBlocked = false;
      }
    });
    return false;
  });

})();

JS;
    }
}
