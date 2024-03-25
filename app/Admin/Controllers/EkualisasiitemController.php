<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Ekualisasidetail;
use \App\Models\Ekualisasiitem;

class EkualisasiitemController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ekualisasi Item';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Ekualisasiitem());

        $grid->column('id', __('Id'));
        $grid->column('item_pemeriksaan', __('Item Ekualisasi'))->text();
        $grid->column('tipe_ppn_pph', __('Tipe PPn/PPh'))->select([0 => 'PPn', 1 => 'PPh', 2 => '-'])->label([
            "0"=>"warning",
            "2"=>"danger",
            "1"=>"success",
        ]);

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
        $show = new Show(Ekualisasiitem::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('item_pemeriksaan', __('Item Ekualisasi'));
        $show->field('tipe_ppn_pph', __('Tipe PPn/PPh'));
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
        $form = new Form(new Ekualisasiitem());
        $form->text('item_pemeriksaan', __('Item Ekualisasi'));
        $form->select('tipe_ppn_pph','Tipe PPn/PPh')->options([0 => 'PPn', 1 => 'PPh', 2 => '-']);
        return $form;
    }
}