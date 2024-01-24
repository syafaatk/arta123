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
        //$grid->column('id', __('id'));
        // $grid->column('pemeriksaan_id', __('Ekulisasi ID'))->display(function($pemeriksaan_id) {
        //     return 
        //     Ekualisasi::join('client_master', 'client_master.id', '=', 'pemeriksaan.client_id')
        //     ->join('masa_pajak', 'masa_pajak.id', '=', 'pemeriksaan.masa_pajak_id')
        //     ->select('pemeriksaan.id', DB::raw('CONCAT(client_master.nama_wp, " - ", masa_pajak.masa_pajak) AS display_text'))
        //     ->where('pemeriksaan.id', $pemeriksaan_id)
        //     ->value('display_text');
        // });
        $grid->column('item_pemeriksaan_id', __('Item Ekualisasi ID'))->display(function($item_pemeriksaan_id) {return Ekualisasiitem::find($item_pemeriksaan_id)->id.'. '.Ekualisasiitem::find($item_pemeriksaan_id)->item_pemeriksaan;});
        $grid->column('quantity', __('Quantity'))->display(function ($jumlah) {
            return ($this->item_pemeriksaan_id != 3 && $this->item_pemeriksaan_id != 6) ? number_format($jumlah, 0, ',', '.') : $jumlah;
        })->text();
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
        $keteranganOptions = Ekualisasi::join('client_master', 'client_master.id', '=', 'pemeriksaan.client_id')
        ->join('masa_pajak', 'masa_pajak.id', '=', 'pemeriksaan.masa_pajak_id')
        ->select('pemeriksaan.id', DB::raw('CONCAT(client_master.nama_wp, " - ", masa_pajak.masa_pajak) AS display_text'))
        ->pluck('display_text', 'pemeriksaan.id')->toArray();

        $grid->filter(function ($filter) use ($keteranganOptions)  {
            // $filter->expand();
            $filter->column(1/2, function ($filter) use ($keteranganOptions) {
                $filter->equal('pemeriksaan_id', __('Data Ekualisasi'))
                    ->select($keteranganOptions);
            });
    
            $filter->column(1/2, function ($filter) {
                $filter->equal('item_pemeriksaan_id', __('Item Ekualisasi'))->select(Ekualisasiitem::all()->pluck('item_pemeriksaan', 'id'));
            });
        });
        $grid->disableCreateButton();
        $grid->editButton()->display(function ($value) {
            // Customize the edit button link
            if(in_array($this->item_pemeriksaan_id, [3,6,7,8,11,14,19,20,23,26,29,32]))
            {   
                $id = $this->id;
                $pid = $this->pemeriksaan_id;
                $ipid = $this->item_pemeriksaan_id;
                return "<a href='/admin/ekualisasi/detail/process/{$id}/{$pid}/{$ipid}' class='btn btn-xs btn-primary'>Process</a>";
            }
        });
        
        $grid->paginate(33);
        
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

    protected function processItemPemeriksaan($id,$pid,$ipid)
    {
        if($ipid == 3):
            $details = Ekualisasi::with(['ekualisasiDetails' => function ($query) {
                    $query->whereIn('item_pemeriksaan_id', [1,2]);
            }])
            ->find($pid);
        elseif($ipid == 6):
            $details = Ekualisasi::with(['ekualisasiDetails' => function ($query) {
                    $query->whereIn('item_pemeriksaan_id', [4,5]);
            }])
            ->find($pid);
        elseif($ipid == 7):
            $details = Ekualisasi::with(['ekualisasiDetails' => function ($query) {
                    $query->whereIn('item_pemeriksaan_id', [3,6]);
            }])
            ->find($pid);
        elseif($ipid == 8):
            $details = Ekualisasi::with(['ekualisasiDetails' => function ($query) {
                    $query->whereIn('item_pemeriksaan_id', [9,10]);
            }])
            ->find($pid);
        elseif($ipid == 11):
            $details = Ekualisasi::with(['ekualisasiDetails' => function ($query) {
                    $query->whereIn('item_pemeriksaan_id', [12,13]);
            }])
            ->find($pid);
        elseif($ipid == 14):
            $details = Ekualisasi::with(['ekualisasiDetails' => function ($query) {
                    $query->whereIn('item_pemeriksaan_id', [8,11]);
            }])
            ->find($pid);
        elseif($ipid == 19):
            $details = Ekualisasi::with(['ekualisasiDetails' => function ($query) {
                    $query->whereIn('item_pemeriksaan_id', [15,16,17]);
            }])
            ->find($pid);
        elseif($ipid == 20):
            $details = Ekualisasi::with(['ekualisasiDetails' => function ($query) {
                    $query->whereIn('item_pemeriksaan_id', [19,18]);
            }])
            ->find($pid);
        elseif($ipid == 23):
            $details = Ekualisasi::with(['ekualisasiDetails' => function ($query) {
                    $query->whereIn('item_pemeriksaan_id', [21,22]);
            }])
            ->find($pid);
        elseif($ipid == 26):
            $details = Ekualisasi::with(['ekualisasiDetails' => function ($query) {
                    $query->whereIn('item_pemeriksaan_id', [24,25]);
            }])
            ->find($pid);
        elseif($ipid == 29):
            $details = Ekualisasi::with(['ekualisasiDetails' => function ($query) {
                    $query->whereIn('item_pemeriksaan_id', [27,28]);
            }])
            ->find($pid);
        elseif($ipid == 32):
            $details = Ekualisasi::with(['ekualisasiDetails' => function ($query) {
                    $query->whereIn('item_pemeriksaan_id', [30,31]);
            }])
            ->find($pid);
        endif;

        //ddd($details);

        $quantity=0;
        $jumlah=0;
        $dpp=0;
        $dppg=0;
        $ppn=0;

        foreach ($details->ekualisasiDetails as $ekualisasiDetail) {
            // Access properties of each ekualisasiDetail
            if($ekualisasiDetail->item_pemeriksaan_id == 1){
                $quantity = $ekualisasiDetail->quantity;
                $jumlah = $ekualisasiDetail->jumlah;
                $dpp = $ekualisasiDetail->dpp_faktur_pajak;
                $dppg = $ekualisasiDetail->dpp_gunggung;
                $ppn = $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif($ekualisasiDetail->item_pemeriksaan_id == 2){
                $quantity -= $ekualisasiDetail->quantity;
                $jumlah -= $ekualisasiDetail->jumlah;
                $dpp -= $ekualisasiDetail->dpp_faktur_pajak;
                $dppg -= $ekualisasiDetail->dpp_gunggung;
                $ppn -= $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif($ekualisasiDetail->item_pemeriksaan_id == 3){
                $quantity = $ekualisasiDetail->quantity;
                $jumlah = $ekualisasiDetail->jumlah;
                $dpp = $ekualisasiDetail->dpp_faktur_pajak;
                $dppg = $ekualisasiDetail->dpp_gunggung;
                $ppn = $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif ($ekualisasiDetail->item_pemeriksaan_id == 4) {
                $quantity = $ekualisasiDetail->quantity;
                $jumlah = $ekualisasiDetail->jumlah;
                $dpp = $ekualisasiDetail->dpp_faktur_pajak;
                $dppg = $ekualisasiDetail->dpp_gunggung;
                $ppn = $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif ($ekualisasiDetail->item_pemeriksaan_id == 5) {
                $quantity += $ekualisasiDetail->quantity;
                $jumlah += $ekualisasiDetail->jumlah;
                $dpp += $ekualisasiDetail->dpp_faktur_pajak;
                $dppg += $ekualisasiDetail->dpp_gunggung;
                $ppn += $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif($ekualisasiDetail->item_pemeriksaan_id == 6){
                $quantity -= $ekualisasiDetail->quantity;
                $jumlah -= $ekualisasiDetail->jumlah;
                $dpp -= $ekualisasiDetail->dpp_faktur_pajak;
                $dppg -= $ekualisasiDetail->dpp_gunggung;
                $ppn -= $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif ($ekualisasiDetail->item_pemeriksaan_id == 8) {
                $quantity = $ekualisasiDetail->quantity;
                $jumlah = $ekualisasiDetail->jumlah;
                $dpp = $ekualisasiDetail->dpp_faktur_pajak;
                $dppg = $ekualisasiDetail->dpp_gunggung;
                $ppn = $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif ($ekualisasiDetail->item_pemeriksaan_id == 9) {
                $quantity += $ekualisasiDetail->quantity;
                $jumlah += $ekualisasiDetail->jumlah;
                $dpp += $ekualisasiDetail->dpp_faktur_pajak;
                $dppg += $ekualisasiDetail->dpp_gunggung;
                $ppn += $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif($ekualisasiDetail->item_pemeriksaan_id == 10){
                $quantity += $ekualisasiDetail->quantity;
                $jumlah += $ekualisasiDetail->jumlah;
                $dpp += $ekualisasiDetail->dpp_faktur_pajak;
                $dppg += $ekualisasiDetail->dpp_gunggung;
                $ppn += $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif($ekualisasiDetail->item_pemeriksaan_id == 11){
                $quantity -= $ekualisasiDetail->quantity;
                $jumlah -= $ekualisasiDetail->jumlah;
                $dpp -= $ekualisasiDetail->dpp_faktur_pajak;
                $dppg -= $ekualisasiDetail->dpp_gunggung;
                $ppn -= $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif ($ekualisasiDetail->item_pemeriksaan_id == 12) {
                $quantity += $ekualisasiDetail->quantity;
                $jumlah += $ekualisasiDetail->jumlah;
                $dpp += $ekualisasiDetail->dpp_faktur_pajak;
                $dppg += $ekualisasiDetail->dpp_gunggung;
                $ppn += $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif($ekualisasiDetail->item_pemeriksaan_id == 13){
                $quantity += $ekualisasiDetail->quantity;
                $jumlah += $ekualisasiDetail->jumlah;
                $dpp += $ekualisasiDetail->dpp_faktur_pajak;
                $dppg += $ekualisasiDetail->dpp_gunggung;
                $ppn += $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            }  elseif($ekualisasiDetail->item_pemeriksaan_id == 15){
                $quantity = $ekualisasiDetail->quantity;
                $jumlah = $ekualisasiDetail->jumlah;
                $dpp = $ekualisasiDetail->dpp_faktur_pajak;
                $dppg = $ekualisasiDetail->dpp_gunggung;
                $ppn = $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif ($ekualisasiDetail->item_pemeriksaan_id == 16) {
                $quantity += $ekualisasiDetail->quantity;
                $jumlah += $ekualisasiDetail->jumlah;
                $dpp += $ekualisasiDetail->dpp_faktur_pajak;
                $dppg += $ekualisasiDetail->dpp_gunggung;
                $ppn += $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif($ekualisasiDetail->item_pemeriksaan_id == 17){
                $quantity += $ekualisasiDetail->quantity;
                $jumlah += $ekualisasiDetail->jumlah;
                $dpp += $ekualisasiDetail->dpp_faktur_pajak;
                $dppg += $ekualisasiDetail->dpp_gunggung;
                $ppn += $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif($ekualisasiDetail->item_pemeriksaan_id == 18){
                $quantity -= $ekualisasiDetail->quantity;
                $jumlah -= $ekualisasiDetail->jumlah;
                $dpp -= $ekualisasiDetail->dpp_faktur_pajak;
                $dppg -= $ekualisasiDetail->dpp_gunggung;
                $ppn -= $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            }   elseif ($ekualisasiDetail->item_pemeriksaan_id == 19) {
                $quantity += $ekualisasiDetail->quantity;
                $jumlah += $ekualisasiDetail->jumlah;
                $dpp += $ekualisasiDetail->dpp_faktur_pajak;
                $dppg += $ekualisasiDetail->dpp_gunggung;
                $ppn += $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif($ekualisasiDetail->item_pemeriksaan_id == 21){
                $quantity = $ekualisasiDetail->quantity;
                $jumlah = $ekualisasiDetail->jumlah;
                $dpp = $ekualisasiDetail->dpp_faktur_pajak;
                $dppg = $ekualisasiDetail->dpp_gunggung;
                $ppn = $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            }   elseif ($ekualisasiDetail->item_pemeriksaan_id == 22) {
                $quantity -= $ekualisasiDetail->quantity;
                $jumlah -= $ekualisasiDetail->jumlah;
                $dpp -= $ekualisasiDetail->dpp_faktur_pajak;
                $dppg -= $ekualisasiDetail->dpp_gunggung;
                $ppn -= $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif($ekualisasiDetail->item_pemeriksaan_id == 24){
                $quantity = $ekualisasiDetail->quantity;
                $jumlah = $ekualisasiDetail->jumlah;
                $dpp = $ekualisasiDetail->dpp_faktur_pajak;
                $dppg = $ekualisasiDetail->dpp_gunggung;
                $ppn = $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            }   elseif ($ekualisasiDetail->item_pemeriksaan_id == 25) {
                $quantity -= $ekualisasiDetail->quantity;
                $jumlah -= $ekualisasiDetail->jumlah;
                $dpp -= $ekualisasiDetail->dpp_faktur_pajak;
                $dppg -= $ekualisasiDetail->dpp_gunggung;
                $ppn -= $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif($ekualisasiDetail->item_pemeriksaan_id == 27){
                $quantity = $ekualisasiDetail->quantity;
                $jumlah = $ekualisasiDetail->jumlah;
                $dpp = $ekualisasiDetail->dpp_faktur_pajak;
                $dppg = $ekualisasiDetail->dpp_gunggung;
                $ppn = $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            }   elseif ($ekualisasiDetail->item_pemeriksaan_id == 28) {
                $quantity -= $ekualisasiDetail->quantity;
                $jumlah -= $ekualisasiDetail->jumlah;
                $dpp -= $ekualisasiDetail->dpp_faktur_pajak;
                $dppg -= $ekualisasiDetail->dpp_gunggung;
                $ppn -= $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            } elseif($ekualisasiDetail->item_pemeriksaan_id == 30){
                $quantity = $ekualisasiDetail->quantity;
                $jumlah = $ekualisasiDetail->jumlah;
                $dpp = $ekualisasiDetail->dpp_faktur_pajak;
                $dppg = $ekualisasiDetail->dpp_gunggung;
                $ppn = $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            }   elseif ($ekualisasiDetail->item_pemeriksaan_id == 31) {
                $quantity -= $ekualisasiDetail->quantity;
                $jumlah -= $ekualisasiDetail->jumlah;
                $dpp -= $ekualisasiDetail->dpp_faktur_pajak;
                $dppg -= $ekualisasiDetail->dpp_gunggung;
                $ppn -= $ekualisasiDetail->ppn_pph;
                $ids = $ekualisasiDetail->id;
            }
            // Add more as needed
        }
        Ekualisasidetail::updateOrInsert(
            [
                'id' => $id,
            ],
            [
                'quantity' => $quantity,
                'jumlah' => $jumlah,
                'dpp_faktur_pajak' => $dpp,
                'dpp_gunggung' => $dppg,
                'ppn_pph' => $ppn,
                // Add other fields as needed
            ]
        );

        return redirect("admin/ekualisasi/detail?pemeriksaan_id=$pid");
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