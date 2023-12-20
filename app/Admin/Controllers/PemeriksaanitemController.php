<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Pemeriksaandetail;
use \App\Models\Pemeriksaanitem;

class PemeriksaanitemController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Pemeriksaan Item';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Pemeriksaanitem());

        $grid->column('id', __('Id'));
        $grid->column('item_pemeriksaan', __('Item Pemeriksaan'));
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
        $show = new Show(Pemeriksaanitem::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('item_pemeriksaan', __('Item Pemeriksaan'));
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
        $form = new Form(new Pemeriksaanitem());
        $form->text('item_pemeriksaan', __('Item Pemeriksaan'));
        return $form;
    }
}