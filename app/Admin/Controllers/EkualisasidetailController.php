<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Ekualisasi;
use \App\Models\Ekualisasidetail;
use \App\Models\Ekualisasiitem;
use Illuminate\Support\Facades\DB;

class EkualisasidetailController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Item Ekualisasi';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Ekualisasidetail());
        $grid->column('id', __('Id'));
        $grid->column('pemeriksaan_id', __('Ekulisasi ID'))->display(function($pemeriksaan_id) {
            return 
            Ekualisasi::join('client_master', 'client_master.id', '=', 'pemeriksaan.client_id')
            ->join('masa_pajak', 'masa_pajak.id', '=', 'pemeriksaan.masa_pajak_id')
            ->select('pemeriksaan.id', DB::raw('CONCAT(client_master.nama_wp, " - ", masa_pajak.masa_pajak) AS display_text'))
            ->where('pemeriksaan.id', $pemeriksaan_id)
            ->value('display_text');
        });
        $grid->column('item_pemeriksaan_id', __('Item Ekualisasi ID'))->display(function($item_pemeriksaan_id) {return Ekualisasiitem::find($item_pemeriksaan_id)->id.'. '.Ekualisasiitem::find($item_pemeriksaan_id)->item_pemeriksaan;});
        $grid->column('quantity', __('Quantity'))->text();
        $grid->column('jumlah', __('Jumlah'))->display(function ($jumlah) {
            return ($this->item_pemeriksaan_id != 3 && $this->item_pemeriksaan_id != 6) ? number_format($jumlah, 0, ',', '.') : $jumlah;
        })->text();
        $grid->column('dpp_faktur_pajak', __('DPP Faktur Pajak'))->display(function ($jumlah) {
            return ($this->item_pemeriksaan_id != 3 && $this->item_pemeriksaan_id != 6) ? number_format($jumlah, 0, ',', '.') : $jumlah;
        })->text();
        $grid->column('dpp_gunggung', __('DPP Gunggung'))->display(function ($jumlah) {
            return ($this->item_pemeriksaan_id != 3 && $this->item_pemeriksaan_id != 6) ? number_format($jumlah, 0, ',', '.') : $jumlah;
        })->text();
        $grid->column('ppn_pph', __('PPN PPH'))->display(function ($jumlah) {
            return ($this->item_pemeriksaan_id != 3 && $this->item_pemeriksaan_id != 6) ? number_format($jumlah, 0, ',', '.') : $jumlah;
        })->text();
        $grid->column('keterangan', __('Keterangan'))->text();
        $grid->filter(function ($filter) {
            $filter->expand();
            $filter->column(1/2, function ($filter) {
                $filter->equal('pemeriksaan_id', __('Data Ekualisasi'))->select(Ekualisasi::join('client_master', 'client_master.id', '=', 'pemeriksaan.client_id')
                ->join('masa_pajak', 'masa_pajak.id', '=', 'pemeriksaan.masa_pajak_id')
                ->select('pemeriksaan.id', DB::raw('CONCAT(client_master.nama_wp, " - ", masa_pajak.masa_pajak) AS display_text'))
                ->pluck('display_text','pemeriksaan.id'));
            });
    
            $filter->column(1/2, function ($filter) {
                $filter->equal('item_pemeriksaan_id', __('Item Ekualisasi'))->select(Ekualisasiitem::all()->pluck('item_pemeriksaan', 'id'));
            });
        });
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
        $show = new Show(Ekualisasidetail::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('item_pemeriksaan_id', __('Item Ekualisasi ID'));
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
        $form = new Form(new Ekualisasidetail());
        $form->text('item_pemeriksaan_id', __('Item Ekualisasi ID'));
        $form->number('quantity', __('Quantity'));
        $form->number('jumlah', __('Jumlah'));
        $form->number('dpp_faktur_pajak', __('DPP Faktur Pajak'));
        $form->number('dpp_gunggung', __('DPP Gunggung'));
        $form->number('ppn_pph', __('PPN PPH'));
        $form->text('keterangan', __('Keterangan'));
        return $form;
    }
}