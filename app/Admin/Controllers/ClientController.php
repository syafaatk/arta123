<?php

namespace App\Admin\Controllers;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use OpenAdmin\Admin\Widgets\Table;
use \App\Models\Client;
use \App\Models\Klu;
use \App\Models\Kpp;

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
        $grid->column('id', __('Id'));
        $grid->column('lokasi_kpp', __('Lokasi KPP'))->display(function($kppId) {return Kpp::find($kppId)->name_kpp;})->modal('detail',function ($model) {

            $clients = $model->take(10)->get()->map(function ($client) {
                return $client->only(['nama_ar','telp_ar', 'klu']);
            });
        
            return new Table(['Nama Account Representative','Telp Account Representative', 'KLU'], $clients->toArray());
        });
        $grid->column('status', __('Status'))->using([0 => 'Badan', 1 => 'Perorangan']);
        $grid->column('bidang_usaha', __('Bidang Usaha'));
        $grid->column('is_umkm', __('UMKM'))->using([0 => 'Bukan', 1 => 'Ya']);

        $grid->column('is_pkp', __('PKP'))->using([0 => 'Bukan', 1 => 'Ya'])->modal('Detail Pengusaha Kena Pajak',function ($model) {

            $clients = $model->take(10)->get()->map(function ($client) {
                return $client->only(['status_pkp','tgl_dikukuhkan_pkp']);
            });
        
            return new Table(['Status PKP','Tgl_Dikukuhkan PKP'], $clients->toArray());
        });
        
        $grid->column('nama_wp', __('Nama Wajib Pajak'));
        $grid->column('npwp_wp', __('Npwp Wajib Pajak'));
        $grid->column('npwp_wp_sejak', __('Tanggal Npwp Wajib Pajak'));
        $grid->column('tgl_berdiri', __('Tanggal berdiri'));
        $grid->column('nama_pj', __('Detail Penanggung Jawab'))->modal('Detail Penanggung Jawab',function ($model) {

            $clients = $model->take(10)->get()->map(function ($client) {
                return $client->only(['nama_pj','npwp_pj','telp_pj']);
            });
        
            return new Table(['Nama Penanggung Jawab','NPWP Penanggung Jawab','Telp Penanggung Jawab'], $clients->toArray());
        });
        $grid->column('masa_berlaku_sertel_sejak', __('Detail Sertifikat Elektronik'))->modal('Detail Sertifikat Elektronik',function ($model) {

            $clients = $model->take(10)->get()->map(function ($client) {
                return $client->only(['masa_berlaku_sertel_sejak','masa_berlaku_sertel_sampai']);
            });
        
            return new Table(['Tanggal Awal Sertifikat Elektronik','Tanggal Berakhir Sertifikat Elektronik'], $clients->toArray());
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
        $show->field('nama_wp', __('Nama Wajib Pajak'));
        $show->field('npwp_wp', __('Npwp Wajib Pajak'));
        $show->divider();
        $show->field('nama_pj', __('Nama Penanggung Jawab'));
        $show->field('npwp_pj', __('Npwp Penanggung Jawab'));
        $show->field('telp_pj', __('Telp Penanggung Jawab'));
        $show->field('tgl_berdiri', __('Tgl berdiri'));
        $show->field('tgl_dikukuhkan_pkp', __('Tgl Dikukuhkan PKP'));
        $show->field('klu', __('KLU'));
        $show->field('status_pkp', __('Status Pengusaha Kena Pajak'));
        $show->field('is_umkm', __('UMKM/Non UMKM'))->using([0 => 'Non UMKM', 1 => 'UMKM']);
        $show->field('masa_berlaku_sertel_sejak', __('Masa berlaku Sertifikat Elektronik sejak'));
        $show->field('masa_berlaku_sertel_sampai', __('Masa berlaku Sertifikat Elektronik sampai'));
        $show->field('nama_ar', __('Nama Account Representative'));
        $show->field('telp_ar', __('Telp Account Representative'));
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
        $form->text('nama_wp', __('Nama Wajib Pajak'));
        $form->text('npwp_wp', __('Npwp Wajib Pajak'));
        $form->date('npwp_wp_sejak', __('Npwp Wajib Pajak Sejak'))->default(date('Y-m-d'));
        $form->text('nama_pj', __('Nama Penanggung Jawab'));
        $form->text('npwp_pj', __('Npwp Penanggung Jawab'));
        $form->text('telp_pj', __('Telp Penanggung Jawab'));
        $form->date('tgl_berdiri', __('Tgl berdiri'))->default(date('Y-m-d'));
        $form->date('tgl_dikukuhkan_pkp', __('Tgl dikukuhkan pkp'))->default(date('Y-m-d'));
        $form->select('klu', __("Keterangan KLU"))->options(Klu::all()->pluck('full_name', 'kode_klu'));
        $form->divider();
        $form->select('is_pkp', __('PKP/Non PKP'))->options([0 => 'Non PKP', 1 => 'PKP']);
        $form->text('status_pkp', __('Status PKP'));
        $form->divider();
        $form->select('is_umkm', __('UMKM/Non UMKM'))->options([0 => 'Non UMKM', 1 => 'UMKM']);
        $form->date('masa_berlaku_sertel_sejak', __('Masa berlaku Sertifikat Elektronik sejak'))->default(date('Y-m-d'));
        $form->date('masa_berlaku_sertel_sampai', __('Masa berlaku Sertifikat Elektronik sampai'))->default(date('Y-m-d'));
        $form->text('nama_ar', __('Nama Account Representative'));
        $form->text('telp_ar', __('Telp Account Representative'));
        $form->text('lokasi_kpp', __('Lokasi kpp'));
        $form->select('lokasi_kpp', __("Keterangan KPP"))->options(Kpp::all()->pluck('name_kpp', 'id'));

        return $form;
    }
}
