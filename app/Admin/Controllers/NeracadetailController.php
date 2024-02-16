<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Ekualisasitahunandetail;
use \App\Models\Neracadetail;
use \App\Models\Neraca;
use DB;
use OpenAdmin\Admin\Widgets\Box;

class NeracadetailController extends AdminController
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
        $grid = new Grid(new Neracadetail);
        $grid->header(function ($query) {
            $query->rightJoin('pemeriksaan_tahunan', 'pemeriksaan_tahunan.id', '=', 'neracadetails.neraca_id')
                  ->rightJoin('item_pemeriksaan', 'item_pemeriksaan.id', '=', 'pemeriksaan_tahunan.item_pemeriksaan_id')
                  ->select(DB::raw('item_pemeriksaan.item_pemeriksaan ,pemeriksaan_tahunan.quantity,pemeriksaan_tahunan.dpp_faktur_pajak,pemeriksaan_tahunan.dpp_gunggung,pemeriksaan_tahunan.ppn_pph'));
            $status = $query->whereIn('pemeriksaan_tahunan.item_pemeriksaan_id', [2,11,18,22,25,28,31])
                ->get();
            $doughnut = view('admin.chart.final', compact('status'));
            return new Box('Data Ekualisasi Tahunan', $doughnut);
        });
        $grid->column('column_order', __('No'));
        $grid->column('id', __('Id'))->hide();
        $grid->column('parent_id', __('No'))->display(function(){return $this->parent_id . '.' . $this->item_no;});
        $grid->column('item_name', __('Item'))->text();
        $grid->column('total', __('Total'))->display(function ($jumlah) {
            return ($this->item_no != 36 && $this->item_no != 66) ? number_format($jumlah, 0, ',', '.') : $jumlah;
        })->text();

        $grid->paginate(35);
        $grid->disableCreateButton();
        $keteranganOptions = Neraca::pluck('keterangan', 'id')->toArray();
        $grid->filter(function ($filter) use ($keteranganOptions)  {
            // $filter->expand();
            $filter->column(1/2, function ($filter) use ($keteranganOptions) {
                $filter->equal('neraca_id', __('Data Neraca'))
                    ->select($keteranganOptions);
            });
        });
        $grid->editButton()->display(function ($value) {
            // Customize the edit button link
            if(in_array($this->parent_id, [3]) AND in_array($this->item_no, [1]))
            {   
                $id = $this->id;
                $lid = $this->neraca_id;
                $pid = $this->parent_id;
                $ipid = $this->item_no;
                return "<a href='/admin/neracadetail/process/{$id}/{$lid}/{$pid}/{$ipid}' class='btn btn-xs btn-primary'>Process</a>";
            }
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
        $show = new Show(Neracadetail::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('parent_id', __('Parent ID'));
        $show->field('item_no', __('No'));
        $show->field('item_name', __('Item'));
        $show->field('total', __('Total'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Neracadetail);
        $form->text('parent_id', __('Parent ID'));
        $form->text('item_no', __('No'));
        $form->text('item_name', __('Item'));
        $form->number('total', __('Total'));

        return $form;
    }

    protected function processItemNaru($id,$lid,$pid,$ipid)
    {
        if($pid == 3):
            $details = Neraca::with(['neracadetails' => function ($query) {
                    $query->whereIn('column_order', [1,6]);
            }])
            ->find($lid);
        endif;

        //ddd($details);

        $total=0;

        foreach ($details->neracadetails as $neracaDetail) {
            // Access properties of each neracaDetail
            if($neracaDetail->column_order == 1){
                $total = $neracaDetail->total;
            } elseif($neracaDetail->column_order == 6){
                $total -= $neracaDetail->total;
            }
            // Add more as needed
        }
        Neracadetail::updateOrInsert(
            [
                'id' => $id,
            ],
            [
                'total' => $total,
            ]
        );

        return redirect("admin/neracadetail?neraca_id=$lid");
    }
}
