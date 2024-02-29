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
    protected $title = 'Neraca Detail';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Neracadetail);
        //$grid->column('column_order', __('No'));
        $grid->header(function ($query) {
            $status = $query->rightJoin('neracas', 'neracas.id', '=', 'neracadetails.neraca_id')
                    ->rightJoin('client_master', 'client_master.id', '=', 'neracas.client_id')
                    ->select(DB::raw('neracas.id ,neracas.keterangan,client_master.nama_wp,neracas.tahun'))->get();

            $doughnut = view('admin.chart.neraca', compact('status'));
            return new Box('Data Neraca Detail', $doughnut);
        });
        $grid->column('id', __('Id'))->hide();
        $grid->column('parent_id', __('No'))->display(function(){return $this->parent_id . '.' . $this->item_no;});
        $grid->column('item_name', __('Item'))->text();
        $grid->column('column_order', 'Total Sebelumnya')->display(function ($column_order) {
            return Neracadetail::where('column_order',$column_order)->where('neraca_id',$this->neraca_id-1)->sum('total');
        });
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
            if(in_array($this->parent_id, [1,2,3,4,5,6]) AND in_array($this->column_order, [11,22,33,44,55,66]))
            {   
                $id = $this->id;
                $lid = $this->neraca_id;
                $pid = $this->parent_id;
                $ipid = $this->item_no;
                $cid = $this->column_order;
                return "<a href='/admin/neracadetail/process/{$id}/{$lid}/{$pid}/{$ipid}/{$cid}' class='btn btn-xs btn-primary'>Process</a>";
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

    protected function processItemNeraca($id,$lid,$pid,$ipid,$cid)
    {
        if($cid == 11):
            $details = Neraca::with(['neracadetails' => function ($query) {
                    $query->whereIn('column_order', [1,2,3]);
            }])
            ->find($lid);
        elseif($cid == 22):
            $details = Neraca::with(['neracadetails' => function ($query) {
                    $query->whereIn('column_order', [4,5]);
            }])
            ->find($lid);
        elseif($cid == 33):
            $details = Neraca::with(['neracadetails' => function ($query) {
                    $query->whereIn('column_order', [11,22]);
            }])
            ->find($lid);
        elseif($cid == 44):
            $details = Neraca::with(['neracadetails' => function ($query) {
                    $query->whereIn('column_order', [6,7]);
            }])
            ->find($lid);
        elseif($cid == 55):
            $details = Neraca::with(['neracadetails' => function ($query) {
                    $query->whereIn('column_order', [8,9,10]);
            }])
            ->find($lid);
        elseif($cid == 66):
            $details = Neraca::with(['neracadetails' => function ($query) {
                    $query->whereIn('column_order', [44,55]);
            }])
            ->find($lid);
        endif;

        //ddd($details);

        $total=0;

        foreach ($details->neracadetails as $neracaDetail) {
            // Access properties of each neracaDetail
            if($neracaDetail->column_order == 1){
                $total = $neracaDetail->total;
            } elseif($neracaDetail->column_order == 2){
                $total += $neracaDetail->total;
            } elseif($neracaDetail->column_order == 3){
                $total += $neracaDetail->total;
            } if($neracaDetail->column_order == 4){
                $total = $neracaDetail->total;
            } elseif($neracaDetail->column_order == 5){
                $total += $neracaDetail->total;
            } if($neracaDetail->column_order == 11){
                $total = $neracaDetail->total;
            } elseif($neracaDetail->column_order == 22){
                $total += $neracaDetail->total;
            } if($neracaDetail->column_order == 6){
                $total = $neracaDetail->total;
            } elseif($neracaDetail->column_order == 7){
                $total += $neracaDetail->total;
            } if($neracaDetail->column_order == 8){
                $total = $neracaDetail->total;
            } elseif($neracaDetail->column_order == 9){
                $total += $neracaDetail->total;
            } elseif($neracaDetail->column_order == 10){
                $total += $neracaDetail->total;
            } if($neracaDetail->column_order == 44){
                $total = $neracaDetail->total;
            } elseif($neracaDetail->column_order == 55){
                $total += $neracaDetail->total;
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
