<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Pemeriksaan;
use \App\Models\Pemeriksaandetail;
use \App\Models\Pemeriksaanitem;
use Illuminate\Support\Facades\DB;

class PemeriksaandetailController extends AdminController
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
        $grid = new Grid(new Pemeriksaandetail());
        $grid->column('id', __('Id'));
        $grid->column('pemeriksaan_id', __('Pemeriksaan ID'))->display(function($pemeriksaan_id) {
            return 
            Pemeriksaan::join('client_master', 'client_master.id', '=', 'pemeriksaan.client_id')
            ->join('masa_pajak', 'masa_pajak.id', '=', 'pemeriksaan.masa_pajak_id')
            ->select('pemeriksaan.id', DB::raw('CONCAT(client_master.nama_wp, " - ", masa_pajak.masa_pajak) AS display_text'))
            ->where('pemeriksaan.id', $pemeriksaan_id)
            ->value('display_text');
        });
        $grid->column('item_pemeriksaan_id', __('Item Pemeriksaan ID'))->display(function($item_pemeriksaan_id) {return Pemeriksaanitem::find($item_pemeriksaan_id)->id.'. '.Pemeriksaanitem::find($item_pemeriksaan_id)->item_pemeriksaan;});
        $grid->column('quantity', __('Quantity'))->text();
        $grid->column('jumlah', __('Jumlah'))->text();
        $grid->column('dpp_faktur_pajak', __('DPP Faktur Pajak'))->text();
        $grid->column('dpp_gunggung', __('DPP Gunggung'))->text();
        $grid->column('ppn_pph', __('PPN PPH'))->text();
        $grid->column('keterangan', __('Keterangan'))->text();
        $grid->filter(function ($filter) {
            $filter->expand();
            $filter->column(1/2, function ($filter) {
                $filter->equal('pemeriksaan_id', __('Data Pemeriksaan'))->select(Pemeriksaan::join('client_master', 'client_master.id', '=', 'pemeriksaan.client_id')
                ->join('masa_pajak', 'masa_pajak.id', '=', 'pemeriksaan.masa_pajak_id')
                ->select('pemeriksaan.id', DB::raw('CONCAT(client_master.nama_wp, " - ", masa_pajak.masa_pajak) AS display_text'))
                ->pluck('display_text','pemeriksaan.id'));
            });
    
            $filter->column(1/2, function ($filter) {
                $filter->equal('item_pemeriksaan_id', __('Item Pemeriksaan'))->select(Pemeriksaanitem::all()->pluck('item_pemeriksaan', 'id'));
            });
        });
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
        $show = new Show(Pemeriksaandetail::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('item_pemeriksaan_id', __('Item Pemeriksaan ID'));
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
        $form = new Form(new Pemeriksaandetail());
        $form->text('item_pemeriksaan_id', __('Item Pemeriksaan ID'));
        $form->text('quantity', __('Quantity'));
        $form->text('jumlah', __('Jumlah'));
        $form->text('dpp_faktur_pajak', __('DPP Faktur Pajak'));
        $form->text('dpp_gunggung', __('DPP Gunggung'));
        $form->text('ppn_pph', __('PPN PPH'));
        $form->text('keterangan', __('Keterangan'));
        return $form;
    }
}