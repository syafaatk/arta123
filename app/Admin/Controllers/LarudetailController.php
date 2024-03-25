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
                  ->leftJoin('laruitems', 'item_pemeriksaan.item_laru', '=', 'laruitems.id')
                  ->select(DB::raw('pemeriksaan_tahunan.item_pemeriksaan_id,item_pemeriksaan.item_pemeriksaan, item_pemeriksaan.item_laru,item_pemeriksaan.tipe_ppn_pph, laruitems.item_name ,pemeriksaan_tahunan.quantity,pemeriksaan_tahunan.dpp_faktur_pajak,pemeriksaan_tahunan.dpp_gunggung,pemeriksaan_tahunan.ppn_pph'));
            $status = $query->whereIn('pemeriksaan_tahunan.item_pemeriksaan_id', [1,8,19,21,24,27,30])
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

        $grid->paginate(36);
        $grid->disableCreateButton();
        $keteranganOptions = Laru::pluck('keterangan', 'id')->toArray();
        $grid->filter(function ($filter) use ($keteranganOptions)  {
            // $filter->expand();
            $filter->column(1/2, function ($filter) use ($keteranganOptions) {
                $filter->equal('laru_id', __('Data Laru'))
                    ->select($keteranganOptions);
            });
        });
        $grid->editButton()->display(function ($value) {
            // Customize the edit button link
            if(in_array($this->parent_id, [1]) AND in_array($this->item_no, [3]))
            {   
                $id = $this->id;
                $lid = $this->laru_id;
                $pid = $this->parent_id;
                $ipid = $this->item_no;
                return "<a href='/admin/larudetail/process/{$id}/{$lid}/{$pid}/{$ipid}' class='btn btn-xs btn-primary'>Process</a>";
            }elseif(in_array($this->parent_id, [2]) AND in_array($this->item_no, [4]))
            {   
                $id = $this->id;
                $lid = $this->laru_id;
                $pid = $this->parent_id;
                $ipid = $this->item_no;
                return "<a href='/admin/larudetail/process/{$id}/{$lid}/{$pid}/{$ipid}' class='btn btn-xs btn-primary'>Process</a>";
            }else if(in_array($this->parent_id, [3,4,5,6]) AND in_array($this->item_no, [1]))
            {   
                $id = $this->id;
                $lid = $this->laru_id;
                $pid = $this->parent_id;
                $ipid = $this->item_no;
                return "<a href='/admin/larudetail/process/{$id}/{$lid}/{$pid}/{$ipid}' class='btn btn-xs btn-primary'>Process</a>";
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
        $form->text('final', __('Final'));
        $form->text('non_final', __('Non Final'));
        $form->text('total', __('Total'));
        $form->text('tax', __('Tax'));

        return $form;
    }

    protected function processItemLaru($id,$lid,$pid,$ipid)
    {
        if($pid == 1):
            $details = Laru::with(['larudetails' => function ($query) {
                    $query->whereIn('column_order', [1,2]);
            }])
            ->find($lid);
        elseif($pid == 2):
            $details = Laru::with(['larudetails' => function ($query) {
                    $query->whereIn('column_order', [4,5,6]);
            }])
            ->find($lid);
        elseif($pid == 3):
            $details = Laru::with(['larudetails' => function ($query) {
                    $query->whereIn('column_order', [1,7]);
            }])
            ->find($lid);
        elseif($pid == 4):
            $details = Laru::with(['larudetails' => function ($query) {
                    $query->whereIn('column_order', [10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29]);
            }])
            ->find($lid);    
        elseif($pid == 5):
            $details = Laru::with(['larudetails' => function ($query) {
                    $query->whereIn('column_order', [31,32,33,34,35]);
            }])
            ->find($lid);      
        elseif($pid == 6):
            $details = Laru::with(['larudetails' => function ($query) {
                    $query->whereIn('column_order', [8,9,30]);
            }])
            ->find($lid);    
        endif;

        //ddd($details);

        $final=0;
        $nonfinal=0;
        $total=0;
        $tax=0;

        foreach ($details->larudetails as $laruDetail) {
            // Access properties of each laruDetail
            if($laruDetail->column_order == 1){
                $final = $laruDetail->final;
                $nonfinal = $laruDetail->non_final;
                $total = $laruDetail->total;
                $tax = $laruDetail->tax;
            }elseif($laruDetail->column_order == 2){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }if($laruDetail->column_order == 3){
                $final = $laruDetail->final;
                $nonfinal = $laruDetail->non_final;
                $total = $laruDetail->total;
                $tax = $laruDetail->tax;
            }elseif($laruDetail->column_order == 7){
                $final -= $laruDetail->final;
                $nonfinal -= $laruDetail->non_final;
                $total -= $laruDetail->total;
                $tax -= $laruDetail->tax;
            }elseif($laruDetail->column_order == 4){
                $final = $laruDetail->final;
                $nonfinal = $laruDetail->non_final;
                $total = $laruDetail->total;
                $tax = $laruDetail->tax;
            }elseif($laruDetail->column_order == 5){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 6){
                $final -= $laruDetail->final;
                $nonfinal -= $laruDetail->non_final;
                $total -= $laruDetail->total;
                $tax -= $laruDetail->tax;
            }elseif($laruDetail->column_order == 8){
                $final = $laruDetail->final;
                $nonfinal = $laruDetail->non_final;
                $total = $laruDetail->total;
                $tax = $laruDetail->tax;
            }elseif($laruDetail->column_order == 9){
                $final -= $laruDetail->final;
                $nonfinal -= $laruDetail->non_final;
                $total -= $laruDetail->total;
                $tax -= $laruDetail->tax;
            }elseif($laruDetail->column_order == 10){
                $final = $laruDetail->final;
                $nonfinal = $laruDetail->non_final;
                $total = $laruDetail->total;
                $tax = $laruDetail->tax;
            }elseif($laruDetail->column_order == 11){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 12){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 13){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 14){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 15){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 16){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 17){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 18){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 19){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 20){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 21){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 22){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 23){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 24){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 25){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 26){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 27){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 28){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 29){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 30){
                $final -= $laruDetail->final;
                $nonfinal -= $laruDetail->non_final;
                $total -= $laruDetail->total;
                $tax -= $laruDetail->tax;
            }elseif($laruDetail->column_order == 31){
                $final = $laruDetail->final;
                $nonfinal = $laruDetail->non_final;
                $total = $laruDetail->total;
                $tax = $laruDetail->tax;
            }elseif($laruDetail->column_order == 32){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 33){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 34){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }elseif($laruDetail->column_order == 35){
                $final += $laruDetail->final;
                $nonfinal += $laruDetail->non_final;
                $total += $laruDetail->total;
                $tax += $laruDetail->tax;
            }
            // Add more as needed
        }
        Larudetail::updateOrInsert(
            [
                'id' => $id,
            ],
            [
                'final' => $final,
                'non_final' => $nonfinal,
                'total' => $total,
                'tax' => $tax,
            ]
        );

        return redirect("admin/larudetail?laru_id=$lid");
    }
}
