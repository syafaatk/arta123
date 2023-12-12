<?php

namespace App\Admin\Controllers;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use OpenAdmin\Admin\Widgets\Table;
use \App\Models\Client;

class ClientController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Master Client';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Client());

        $grid->column('id', __('Id'))->modal('detail',function ($model) {

            $clients = $model->take(10)->get()->map(function ($client) {
                return $client->only(['nama_ar','telp_ar','tgl_dikukuhkan_pkp', 'klu', 'status_pkp','lokasi_kpp']);
            });
        
            return new Table(['Nama AR','Telp AR','Tanggal PKP', 'KLU', 'Status PKP', 'Lokasi KPP'], $clients->toArray());
        });
        $grid->column('status', __('Status'))->using([0 => 'Badan', 1 => 'Perorangan']);
        $grid->column('bidang_usaha', __('Bidang Usaha'));
        $grid->column('is_umkm', __('UMKM/Non UMKM'))->using([0 => 'Non UMKM', 1 => 'UMKM']);
        $grid->column('nama_wp', __('Nama wp'));
        $grid->column('npwp_wp', __('Npwp wp'));
        $grid->column('npwp_wp_sejak', __('Npwp wp Sejak'));
        $grid->column('tgl_berdiri', __('Tgl berdiri'));
        $grid->column('nama_pj', __('Detail Penanggung Jawab'))->modal('Detail Penanggung Jawab',function ($model) {

            $clients = $model->take(10)->get()->map(function ($client) {
                return $client->only(['nama_pj','npwp_pj','telp_pj']);
            });
        
            return new Table(['Nama Penanggung Jawab','NPWP Penanggung Jawab','Telp Penanggung Jawab'], $clients->toArray());
        });
        $grid->column('masa_berlaku_sertel_sejak', __('Detail Sertel'))->modal('Detail Sertel',function ($model) {

            $clients = $model->take(10)->get()->map(function ($client) {
                return $client->only(['masa_berlaku_sertel_sejak','masa_berlaku_sertel_sampai']);
            });
        
            return new Table(['Tanggal Awal Sertel','Tanggal Berakhir Sertel'], $clients->toArray());
        });
        $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));
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
        $show->field('status', __('Status'))->using([0 => 'Badan', 1 => 'Perorangan']);
        $show->field('file_npwp')->file();
        $show->field('nama_wp', __('Nama wp'));
        $show->field('npwp_wp', __('Npwp wp'));
        $show->divider();
        $show->field('nama_pj', __('Nama pj'));
        $show->field('npwp_pj', __('Npwp pj'));
        $show->field('telp_pj', __('Telp pj'));
        $show->field('tgl_berdiri', __('Tgl berdiri'));
        $show->field('tgl_dikukuhkan_pkp', __('Tgl dikukuhkan pkp'));
        $show->field('klu', __('KLU'));
        $show->field('status_pkp', __('Status PKP'));
        $show->field('is_umkm', __('UMKM/Non UMKM'))->using([0 => 'Non UMKM', 1 => 'UMKM']);
        $show->field('masa_berlaku_sertel_sejak', __('Masa berlaku sertel sejak'));
        $show->field('masa_berlaku_sertel_sampai', __('Masa berlaku sertel sampai'));
        $show->field('nama_ar', __('Nama ar'));
        $show->field('telp_ar', __('Telp ar'));
        $show->field('lokasi_kpp', __('Lokasi kpp'));
        $show->field('file_ktp')->file();
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

        $form->select('status','Status')->options([0 => 'Badan', 1 => 'Perorangan']);
        $form->text('bidang_usaha',__('Bidang usaha'));
        $form->file('file_ktp', 'File KTP')->move('files/Clients/KTP/')->rules('mimes:pdf,jpeg,png')->uniqueName();
        $form->file('file_npwp', 'File NPWP')->move('files/Clients/NPWP/')->rules('mimes:pdf,jpeg,png')->uniqueName();
        $form->text('nama_wp', __('Nama wp'));
        $form->text('npwp_wp', __('Npwp wp'));
        $form->date('npwp_wp_sejak', __('Npwp wp Sejak'))->default(date('Y-m-d'));
        $form->text('nama_pj', __('Nama pj'));
        $form->text('npwp_pj', __('Npwp pj'));
        $form->text('telp_pj', __('Telp pj'));
        $form->date('tgl_berdiri', __('Tgl berdiri'))->default(date('Y-m-d'));
        $form->date('tgl_dikukuhkan_pkp', __('Tgl dikukuhkan pkp'))->default(date('Y-m-d'));
        $form->text('klu', __('KLU'));
        $form->text('status_pkp', __('Status PKP'));
        $form->select('is_umkm', __('UMKM/Non UMKM'))->options([0 => 'Non UMKM', 1 => 'UMKM']);
        $form->date('masa_berlaku_sertel_sejak', __('Masa berlaku sertel sejak'))->default(date('Y-m-d'));
        $form->date('masa_berlaku_sertel_sampai', __('Masa berlaku sertel sampai'))->default(date('Y-m-d'));
        $form->text('nama_ar', __('Nama ar'));
        $form->text('telp_ar', __('Telp ar'));
        $form->text('lokasi_kpp', __('Lokasi kpp'));

        return $form;
    }
}
