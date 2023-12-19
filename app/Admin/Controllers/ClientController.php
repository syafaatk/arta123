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
use \App\Models\Kppar;

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
        $states = [
            'on' => ['value' => 1, 'text' => 'Ya', 'color' => 'primary'],
            'off' => ['value' => 0, 'text' => 'Bukan', 'color' => 'default'],
        ];
        $grid->column('id', __('Id'));
        $grid->column('nama_wp', __('Detail Wajib Pajak'))->modal('Detail Wajib Pajak',function ($model) {

            $clients = $model->take(1)->get()->map(function ($client) {
                return $this->only(['nama_wp','npwp_wp','npwp_wp_sejak','no_hp', 'tgl_berdiri']);
            });
        
            return new Table(['Nama Wajib Pajak','NPWP Wajib Pajak','Tanggal Npwp Wajib Pajak','No HP', 'Tgl Berdiri'], $clients->toArray());
        });
        $grid->column('klu_id', __('KLU'))->display(function($kluId) {return Klu::find($kluId)->id . '-' . Klu::find($kluId)->name_klu;});

        $grid->column('kppar_id', __('Lokasi KPP'))->display(function ($kppId) {
            $kppar = Kppar::with('masterKpp')->find($kppId);
        
            if ($kppar) {
                $name_kpp = $kppar->masterKpp->name_kpp;
                $name_ar = $kppar->name_ar;
        
                return "$name_kpp - $name_ar";
            } else {
                return '';
            }
        });
        $grid->column('nama_ar', __('Nama Account Representative'))->hide();
        $grid->column('telp_ar', __('Telp Account Representative'))->hide();
        $grid->column('status', __('Status'))->using([0 => 'Badan', 1 => 'Perorangan']);
        $grid->column('bidang_usaha', __('Bidang Usaha'));
        $grid->column('is_umkm', __('UMKM'))->switch($states);
        $grid->column('is_pkp', __('PKP'))->switch($states);
        $grid->column('status_pkp', __('Status PKP'))->hide();
        $grid->column('tgl_dikukuhkan_pkp', __('Tanggal Dikukuhkan PKP'))->hide();    
        
        $grid->column('efin', __('EFIN'))->modal('Detail Wajib Pajak',function ($model) {

            $clients = $model->take(1)->get()->map(function ($client) {
                return $this->only(['email','password_djp']);
            });
        
            return new Table(['Email','Password'], $clients->toArray());
        });
        $grid->column('nama_pj', __('Detail Penanggung Jawab'))->modal('Detail Penanggung Jawab',function ($model) {

            $clients = $model->take(1)->get()->map(function ($client) {
                return $this->only(['nama_pj','npwp_pj','telp_pj']);
            });
        
            return new Table(['Nama Penanggung Jawab','NPWP Penanggung Jawab','Telp Penanggung Jawab'], $clients->toArray());
        });
        $grid->column('masa_berlaku_sertel_sejak', __('Detail Sertifikat Elektronik'))->display(function(){return $this->masa_berlaku_sertel_sejak . ' s/d ' . $this->masa_berlaku_sertel_sampai;});
        $grid->column('created_at', __('Created at'))->hide();
        $grid->column('updated_at', __('Updated at'))->hide();
        $grid->column('deleted_at', __('Deleted at'))->hide();

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
        $show->field('kppar_id', __('KPP'))->as(function ($kppId) {
            $kppar = Kppar::with('masterKpp')->find($kppId);
        
            if ($kppar) {
                $name_kpp = $kppar->masterKpp->name_kpp;
                $name_ar = $kppar->name_ar;
                $telp_ar = $kppar->telp_ar;
        
                return "$name_kpp - $name_ar - $telp_ar";
            } else {
                return '';
            }
        });
        $show->divider();
        $show->field('status', __('Status'))->using([0 => 'Badan', 1 => 'Perorangan']);
        $show->field('nama_wp', __('Nama Wajib Pajak'));
        $show->field('npwp_wp', __('Npwp Wajib Pajak'));
        $show->field('no_hp', __('No Handphone'));
        $show->field('file_ktp')->file();
        $show->field('file_npwp')->file();
        $show->divider();
        $show->field('email', __('Email'));
        $show->field('password_djp', __('Password DJP'));
        $show->field('efin', __('Nomor EFIN'));
        $show->divider();
        $show->field('nama_pj', __('Nama Penanggung Jawab'));
        $show->field('npwp_pj', __('Npwp Penanggung Jawab'));
        $show->field('telp_pj', __('Telp Penanggung Jawab'));
        $show->field('file_npwp_pj')->file();
        $show->field('file_ktp_pj')->file();
        $show->field('tgl_berdiri', __('Tgl berdiri'));
        $show->field('tgl_dikukuhkan_pkp', __('Tgl Dikukuhkan PKP'));
        $show->field('klu_id', __('KLU'))->as(function ($kluId) {
            return Klu::find($kluId)->id . '-' . Klu::find($kluId)->name_klu;;
        });
        $show->field('status_pkp', __('Status Pengusaha Kena Pajak'));
        $show->field('is_umkm', __('UMKM/Non UMKM'))->using([0 => 'Non UMKM', 1 => 'UMKM']);
        $show->field('masa_berlaku_sertel_sejak', __('Masa berlaku Sertifikat Elektronik sejak'));
        $show->field('masa_berlaku_sertel_sampai', __('Masa berlaku Sertifikat Elektronik sampai'));
        $show->field('nama_ar', __('Nama Account Representative'));
        $show->field('telp_ar', __('Telp Account Representative'));
        
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
        $form->text('no_hp', __('No Handphone'));
        $form->divider();
        $form->email('email', __('Email DJP'));
        $form->password('password_djp', __('Password DJP'))->toggleShow();;
        $form->text('efin', __('Nomor Efin'));
        $form->divider();
        $form->text('nama_pj', __('Nama Penanggung Jawab'));
        $form->text('npwp_pj', __('Npwp Penanggung Jawab'));
        $form->text('telp_pj', __('Telp Penanggung Jawab'));
        $form->file('file_ktp_pj', 'File KTP Penanggung Jawab')->move('files/Clients/KTP/')->rules('mimes:pdf,jpeg,png')->uniqueName();
        $form->file('file_npwp_pj', 'File NPWP Penganggung Jawab')->move('files/Clients/NPWP/')->rules('mimes:pdf,jpeg,png')->uniqueName();
        $form->date('tgl_berdiri', __('Tgl berdiri'))->default(date('Y-m-d'));
        $form->divider();
        $form->select('klu_id', __("Keterangan KLU"))->options(Klu::all()->pluck('full_name', 'id'));
        $form->divider();
        $form->radio('is_pkp', __('PKP'))->options([
            0 =>'Non PKP',
            1 =>'PKP',
        ])->when(1, function (Form $form) {
            $form->text('status_pkp', __('Status PKP'));
            $form->date('tgl_dikukuhkan_pkp', __('Tgl dikukuhkan pkp'))->default(date('Y-m-d'));
        });
        $form->divider();
        $form->switch('is_umkm', __('UMKM'));
        $form->date('masa_berlaku_sertel_sejak', __('Masa berlaku Sertifikat Elektronik sejak'))->default(date('Y-m-d'));
        $form->date('masa_berlaku_sertel_sampai', __('Masa berlaku Sertifikat Elektronik sampai'))->default(date('Y-m-d'));
        $form->select('kppar_id',__("KPP - Account Representative"))->options(
            Kppar::with(['masterKpp:id,name_kpp', 'clients'])
                ->select('id', 'kpp_id', 'name_ar')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->id => $item->masterKpp->name_kpp . ' - ' . $item->name_ar];
                })
        );
        $form->text('kppar.name_ar', __("Nama Account Representative"))->disable();
        $form->text('kppar.telp_ar', __('Telp Account Representative'))->disable();

        return $form;
    }
}
