<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Neracaitem;

class NeracaitemController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Neraca Item';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Neracaitem);

        $grid->column('id', __('Id'));
        $grid->column('parent_id', __('parent_id'))->text();
        $grid->column('item_no', __('Item No'))->text();
        $grid->column('item_name', __('Item Neraca'))->text();

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
        $show = new Show(Neracaitem::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('item_name', __('Item Neraca'));
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
        $form = new Form(new Neracaitem);
        $form->text('item_name', __('Item Neraca'));
        return $form;
    }
}