<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Ekualisasitahunan;
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
        $grid->column('id', __('ID'));
        $grid->column('client_id', 'Detail')->display(function ($clientId) {
            return Client::find($clientId)->nama_wp.'-'.$this->tahun;
        })->sortable();
        $grid->column('item_pemeriksaan_id', __('Item Ekualisasi ID'))->display(function($item_pemeriksaan_id) {return Ekualisasiitem::find($item_pemeriksaan_id)->id.'. '.Ekualisasiitem::find($item_pemeriksaan_id)->item_pemeriksaan;});
        $grid->column('quantity', __('Quantity'))->display(function ($jumlah) {
            return ($this->item_pemeriksaan_id != 3 && $this->item_pemeriksaan_id != 6) ? number_format($jumlah, 0, ',', '.') : $jumlah;
        });
        $grid->column('jumlah', __('Jumlah'))->display(function ($jumlah) {
            return ($this->item_pemeriksaan_id != 3 && $this->item_pemeriksaan_id != 6) ? number_format($jumlah, 0, ',', '.') : $jumlah;
        });
        $grid->column('dpp_faktur_pajak', __('DPP Faktur Pajak'))->display(function ($jumlah) {
            return ($this->item_pemeriksaan_id != 3 && $this->item_pemeriksaan_id != 6) ? number_format($jumlah, 0, ',', '.') : $jumlah;
        });
        $grid->column('dpp_gunggung', __('DPP Gunggung'))->display(function ($jumlah) {
            return ($this->item_pemeriksaan_id != 3 && $this->item_pemeriksaan_id != 6) ? number_format($jumlah, 0, ',', '.') : $jumlah;
        });
        $grid->column('ppn_pph', __('PPN PPH'))->display(function ($jumlah) {
            return ($this->item_pemeriksaan_id != 3 && $this->item_pemeriksaan_id != 6) ? number_format($jumlah, 0, ',', '.') : $jumlah;
        });
        $grid->column('keterangan', __('Keterangan'));

        $grid->disableCreateButton();
        $grid->paginate(29);
        $grid->filter(function ($filter) {
            // $filter->expand();
            $filter->column(1/2, function ($filter) {
                $filter->equal('client_id', __('Data Client'))
                    ->select(Tahunan::pluck('keterangan', 'client_id')); // Assuming 'keterangan' is the display column
            });
        });

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableShow();
          });

          $grid->disableActions();
        //dd($data);
        $style = <<<STYLE
        <style>

            .table tr th, .table tr td {
                border-color: rgba(0, 0, 0, 0.22);
            }
            .table-responsive tr th {
                text-align: center;
                border: 2px solid #ddd; /* Add border to both th and td elements */
            }
            .table-responsive th,
            .table-responsive td {
                border: 2px solid #ddd; /* Add border to both th and td elements */
            }

            .table-responsive {
                border-collapse: collapse; /* Collapse borders for better styling */
                width: 100%; /* Set width to 100% */
            }

            // tr.row-3,tr.row-6,tr.row-7,tr.row-8,tr.row-11,tr.row-14,tr.row-19,tr.row-20,tr.row-23,tr.row-26 {
            //     background-color: rgba(255, 213, 213, 0.51);
            // }

            

        </style>
        STYLE;

        // Add custom styles to the table
        echo $style;

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
