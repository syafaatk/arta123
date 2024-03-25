<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Ekualisasitahunan;
use \App\Models\Ekualisasitahunandetail;
use \App\Models\Ekualisasiitem;
use \App\Models\Tahunan;
use \App\Models\Client;
use OpenAdmin\Admin\Widgets\Table;
use Illuminate\Support\Facades\DB;

class EkualisasitahunanController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ekualisasi Tahunan';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        
        $grid = new Grid(new Ekualisasitahunan());
        $grid->column('id', 'Detail')->expand(function ($model) {
            $details = $model->ekualisasiDetails()->get();
        
            // Function to process details and calculate quantities
            $processDetails = function ($details, &$quantity, &$jumlah, &$dpp, &$dppg, &$ppn, &$tipeppn) {
                return $details->map(function ($detail) use (&$quantity, &$jumlah, &$dpp, &$dppg, &$ppn, &$tipeppn) {
                    $itemName = $detail->item_ekualisasi->item_pemeriksaan ?? 'Unknown';
                    $quantity = $detail->quantity;
                    //$jumlah = $detail->jumlah;
                    $dpp = $detail->dpp_faktur_pajak;
                    $dppg = $detail->dpp_gunggung;
                    $ppn = $detail->ppn_pph;
                    $jumlah = $dpp + $dppg;
                    $tipeppn = $detail->item_ekualisasi->tipe_ppn_pph ?? 'Unknown';

                    if($tipeppn == 0):
                        $tipe = "<span class='badge bg-warning'>PPn</badge>";
                        $ppn = $detail->ppn_pph;
                        $pph = 0;
                    elseif($tipeppn == 1):
                        $tipe = "<span class='badge bg-success'>PPh</badge>";
                        $pph = $detail->ppn_pph;
                        $ppn = 0;
                    else:
                        $tipe = "<span class='badge bg-danger'>-</badge>";
                        $ppn = 0;
                        $pph = 0;
                    endif;
        
                    return [
                        'ID' => $detail->item_pemeriksaan_id,
                        'item_pemeriksaan' => $itemName.' '.$tipe,
                        'quantity' => number_format($detail->quantity, 0, ",", "."),
                        'dpp_faktur_pajak' => number_format($detail->dpp_faktur_pajak, 0, ",", "."),
                        'dpp_gunggung' => number_format($detail->dpp_gunggung, 0, ",", "."),
                        'jumlah' => number_format($jumlah, 0, ",", "."),
                        'ppn' => number_format($ppn, 0, ",", "."),
                        'pph' => number_format($pph, 0, ",", "."),
                        'keterangan' => $detail->keterangan,
                        // 'created_at' => $detail->created_at,
                    ];
                });
            };
        
            $data = $processDetails($details, $quantity, $dpp, $dppg,$jumlah, $ppn, $ppn);

            return new Table(['ID', 'Item Ekualisasi', 'quantity', 'dpp faktur pajak', 'dpp gungung','jumlah', 'PPn','PPh', 'Ket'], $data->toArray());
        });
        $grid->column('client_id', 'Client ID');
        $grid->column('tahun', 'Tahun');    
        $grid->column('keterangan', 'Nama Ekualisasi Tahunan');
        $grid->disableCreateButton();
        $grid->paginate(33);
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableShow();
        });
        $grid->filter(function ($filter) {
            //$filter->expand();
    
            $filter->column(1/2, function ($filter) {
                $filter->equal('client_id')->select(Client::all()->pluck('nama_wp', 'id'));
            });
        });
        $grid->editButton()->display(function ($value) {
            // Customize the edit button link
            $url = $this->client_id;
            return "<a href='tahunan-detail?client_id={$url}' class='btn btn-xs btn-primary'>Lihat Detail</a>";
        });

        $grid->disableActions();
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
        $show = new Show(Ekualisasitahunan::findOrFail($id));

        $show->field('client_id', __('Id'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Ekualisasitahunan());

        $form->text('item_pemeriksaan_id', __('Item_pemeriksaan'));

        return $form;
    }
}
