<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Ekualisasitahunandetail;
use \App\Models\Larudetail;
use \App\Models\Laru;
use DB;
use OpenAdmin\Admin\Widgets\Box;

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
        $grid = new Grid(new Larudetail);
        $grid->header(function ($query) {
            $query->rightJoin('pemeriksaan_tahunan', 'pemeriksaan_tahunan.id', '=', 'larudetails.laru_id')
                  ->rightJoin('item_pemeriksaan', 'item_pemeriksaan.id', '=', 'pemeriksaan_tahunan.item_pemeriksaan_id')
                  ->select(DB::raw('item_pemeriksaan.item_pemeriksaan ,pemeriksaan_tahunan.quantity,pemeriksaan_tahunan.dpp_faktur_pajak,pemeriksaan_tahunan.dpp_gunggung,pemeriksaan_tahunan.ppn_pph'));
            $status = $query->whereIn('pemeriksaan_tahunan.item_pemeriksaan_id', [2,8,11,14])
                ->get();
            $doughnut = view('admin.chart.final', compact('status'));
            return new Box('Data Ekualisasi Tahunan', $doughnut);
        });
        $grid->column('column_order', __('No'));
        $grid->column('id', __('Id'))->hide();
        $grid->column('parent_id', __('No'))->display(function(){return $this->parent_id . '.' . $this->item_no;});
        $grid->column('item_name', __('Item'))->text();

        $grid->column('final', __('Final'))->display(function ($jumlah) {
            return ($this->item_no != 36 && $this->item_no != 66) ? number_format($jumlah, 0, ',', '.') : $jumlah;
        })->text();
        $grid->column('non_final', __('Non Final'))->display(function ($jumlah) {
            return ($this->item_no != 36 && $this->item_no != 66) ? number_format($jumlah, 0, ',', '.') : $jumlah;
        })->text();
        $grid->column('total', __('Total'))->display(function ($jumlah) {
            return ($this->item_no != 36 && $this->item_no != 66) ? number_format($jumlah, 0, ',', '.') : $jumlah;
        })->text();
        $grid->column('tax', __('Tax'))->display(function ($jumlah) {
            return ($this->item_no != 36 && $this->item_no != 66) ? number_format($jumlah, 0, ',', '.') : $jumlah;
        })->text();

        $grid->paginate(33);
        $grid->disableCreateButton();
        $keteranganOptions = Laru::pluck('keterangan', 'id')->toArray();
        $grid->filter(function ($filter) use ($keteranganOptions)  {
            // $filter->expand();
            $filter->column(1/2, function ($filter) use ($keteranganOptions) {
                $filter->equal('laru_id', __('Data Laru'))
                    ->select($keteranganOptions);
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
        $form->number('final', __('Final'));
        $form->number('non_final', __('Non Final'));
        $form->number('total', __('Total'));
        $form->number('tax', __('Tax'));

        return $form;
    }
}
