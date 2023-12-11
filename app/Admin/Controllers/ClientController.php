<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Client;

class ClientController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Client';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Client());

        $grid->column('id', __('Id'));
        $grid->column('status', __('Status'));
        $grid->column('nama_wp', __('Nama wp'));
        $grid->column('npwp_wp', __('Npwp wp'));
        $grid->column('nama_pj', __('Nama pj'));
        $grid->column('npwp_pj', __('Npwp pj'));
        $grid->column('telp_pj', __('Telp pj'));
        $grid->column('tgl_berdiri', __('Tgl berdiri'));
        $grid->column('tgl_dikukuhkan_pkp', __('Tgl dikukuhkan pkp'));
        $grid->column('masa_berlaku_sertel', __('Masa berlaku sertel'));
        $grid->column('nama_ar', __('Nama ar'));
        $grid->column('telp_ar', __('Telp ar'));
        $grid->column('lokasi_kpp', __('Lokasi kpp'));
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
        $show = new Show(Client::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('status', __('Status'));
        $show->field('nama_wp', __('Nama wp'));
        $show->field('npwp_wp', __('Npwp wp'));
        $show->field('nama_pj', __('Nama pj'));
        $show->field('npwp_pj', __('Npwp pj'));
        $show->field('telp_pj', __('Telp pj'));
        $show->field('tgl_berdiri', __('Tgl berdiri'));
        $show->field('tgl_dikukuhkan_pkp', __('Tgl dikukuhkan pkp'));
        $show->field('masa_berlaku_sertel', __('Masa berlaku sertel'));
        $show->field('nama_ar', __('Nama ar'));
        $show->field('telp_ar', __('Telp ar'));
        $show->field('lokasi_kpp', __('Lokasi kpp'));
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
        $form = new Form(new Client());

        $form->text('status', __('Status'));
        $form->text('nama_wp', __('Nama wp'));
        $form->text('npwp_wp', __('Npwp wp'));
        $form->text('nama_pj', __('Nama pj'));
        $form->text('npwp_pj', __('Npwp pj'));
        $form->text('telp_pj', __('Telp pj'));
        $form->date('tgl_berdiri', __('Tgl berdiri'))->default(date('Y-m-d'));
        $form->date('tgl_dikukuhkan_pkp', __('Tgl dikukuhkan pkp'))->default(date('Y-m-d'));
        $form->number('masa_berlaku_sertel', __('Masa berlaku sertel'));
        $form->text('nama_ar', __('Nama ar'));
        $form->text('telp_ar', __('Telp ar'));
        $form->text('lokasi_kpp', __('Lokasi kpp'));

        return $form;
    }
}
