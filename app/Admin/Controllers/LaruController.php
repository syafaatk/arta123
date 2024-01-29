<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Laru;

class LaruController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Laba Rugi';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Laru());

        $grid->column('id', __('Id'));
        $grid->column('client_id', __('Client_Id'));
        $grid->column('tahun', __('Tahun'));
        $grid->column('keterangan', __('Keterangan'));
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
        $show = new Show(Laru::findOrFail($id));

        $show->field('id', __('Id'));
        $grid->field('client_id', __('Client_Id'));
        $grid->field('tahun', __('Tahun'));
        $grid->field('keterangan', __('Keterangan'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Laru);
        $form->text('client_id', __('Data Client'));
        $form->text('tahun', __('Data Ekualisasi Tahunan'));
        $form->text('keterangan', __('Keterangan'));
        $form->textarea('judul_parent', __('Judul'));
        // Subtable fields
        // $form->hasMany('paintings', function (Form\NestedForm $form) {
        //     $form->text('title');
        //     $form->image('body');
        //     $form->datetime('completed_at');
        // });

        // You can use the second parameter to set the label
        // $form->hasMany('paintings','Painting', function (Form\NestedForm $form) {
        //     $form->text('title');
        //     $form->image('body');
        //     $form->date('completed_at')->default(date('Y-m-d'));
        // });

        // You display this relation in three different modes (default, tab & table)
        $form->hasMany('paintings', function ($form) {
            $form->text('parent_id', __('Parent ID'));
            $form->text('item_no', __('No'));
            $form->text('item_name', __('Item'));
        })->mode("table");

        return $form;
    }
}
