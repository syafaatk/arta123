<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Masapajak;
use Illuminate\Support\MessageBag;

class MasapajakController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Masa Pajak';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Masapajak());

        $grid->column('id', __('Id'));
        $grid->column('masa_pajak', __('Masa Pajak'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('deleted_at', __('Deleted at'));

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
        $show = new Show(Masapajak::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('masa_pajak', __('Masa Pajak'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Masapajak());
        $form->text('masa_pajak', __('Masa Pajak'));
        $form->saved(function ($form) {
            $success = new MessageBag([
                'title'   => 'Sukses',
                'message' => 'Data "'.$form->masa_pajak.'" ini telah disimpan',
            ]);
            return redirect('/admin/pajak/masa-pajak')->with(compact('success'));
        });
        return $form;
    }
}