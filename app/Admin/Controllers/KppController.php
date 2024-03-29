<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Kpp;
Use App\Admin\Extensions\PageExporter_KPP;
use Barryvdh\DomPDF\Facade as PDF;
use App\Admin\Traits\HookExample;
use Illuminate\Support\MessageBag;

class KppController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Kpp';
    // use HookExample;

    // public function __construct()
    // {
    //     $this->initHooks();
    // }
    /**
     * Make a grid builder.
     *
     * @return Grid
     */

    protected function grid()
    {
        $grid = new Grid(new Kpp());

        $grid->column('id', __('Id'));
        $grid->column('name_kpp', __('Name kpp'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->exporter(new PageExporter_KPP());
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Kpp::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name_kpp', __('Name kpp'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Kpp());

        $form->text('name_kpp', __('Name kpp'));
        $form->saved(function ($form) {
            $success = new MessageBag([
                'title'   => 'Sukses',
                'message' => 'Data '.$form->name_kpp.' ini telah disimpan',
            ]);
            return redirect('/admin/pajak/kpp')->with(compact('success'));
        });

        return $form;
    }
}
