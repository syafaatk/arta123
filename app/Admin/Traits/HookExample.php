<?php

namespace App\Admin\Traits;

use Illuminate\Support\Facades\Log;
use OpenAdmin\Admin\Facades\Admin;
use OpenAdmin\Admin\Form;

trait HookExample
{
    public function initHooks()
    {
        $this->hook('alterForm', function ($scope, $form) {

            // maybe you want to alter the form looks
            //Admin::css('/vendor/some-css-file', false); // false mean the file is not minified, and is added when minifier is on

            // or add some custom js
            //Admin::js('/vendor/some-js-file', false);   // false mean the file is not minified, and is added when minifier is on

            $form = $this->addFormLogic($form);

            return $form;
        });
    }

    public function addFormLogic($form)
    {
        $form->hidden('time', 'time')->value(time());
        $form->ignore('time');

        $form->saved(function (Form $form) {
            $model = $form->model();
            $diff = time() - request()->time;
            Log::debug("Spend {$diff} seconds editing the form");
            Log::debug("The saved form data is:".json_encode($model));
        });

        return $form;
    }
}