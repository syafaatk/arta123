<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Larudetail;

class LarudetailController extends AdminController
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
        $grid = new Grid(new Larudetail());

        $grid->column('id', __('Id'))->hide();
        $grid->column('parent_id', __('No'))->display(function(){return $this->parent_id . '.' . $this->item_no;});
        $grid->column('item_name', __('Item'))->text();
        $grid->column('final', __('Final'))->text();
        $grid->column('non_final', __('Non Final'))->text();
        $grid->column('total', __('Total'))->text();
        $grid->column('tax', __('Tax'))->text();
        $grid->disableCreateButton();
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
        $show = new Show(Larudetail::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('parent_id', __('Parent ID'));
        $show->field('item_no', __('No'));
        $show->field('item_name', __('Item'));
        $show->field('final', __('Final'));
        $show->field('non_final', __('Non Final'));
        $show->field('total', __('Total'));
        $show->field('tax', __('Tax'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Larudetail);
        $form->text('parent_id', __('Parent ID'));
        $form->text('item_no', __('No'));
        $form->text('item_name', __('Item'));
        $form->text('final', __('Final'));
        $form->text('non_final', __('Non Final'));
        $form->text('total', __('Total'));
        $form->text('tax', __('Tax'));

        return $form;
    }
}
