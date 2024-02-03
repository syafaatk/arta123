<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Laru;
use \App\Models\Larudetail;
use \App\Models\Laruitem;
use \App\Models\Client;
use Illuminate\Http\Request;

use \App\Models\Ekualisasitahunan;

use OpenAdmin\Admin\Widgets\Table;

class LaruController extends AdminController
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
        $grid = new Grid(new Laru());
        $grid->column('id', 'Detail')->expand(function ($model) {
            $details = $model->Larudetails()->get();
            $judulParentJson = json_decode($model->judul_parent, true);
        
            // Function to process details and calculate quantities
            $processDetails = function ($details, $judulParentJson) {
                $data = [];
                $no = 1;
                foreach ($judulParentJson as $parentId => $itemName) {
                    // Add parent title before each group of details

                    $data[] = [
                        'Parent_id' => '<ol style="margin-left:-25px;margin-bottom:0px;"><b>'.$parentId.'</b></ol>',
                        'Item Name' => '<b>'.$itemName.'</b>',
                        'final' => '',
                        'non final' => '',
                        'total' => '',
                        'tax' => '',
                    ];
        
                    // Get details for the current parent_id
                    $groupedDetail = $details->where('parent_id', $parentId);
                    $not=1;
                    // Process and append details to data
                    $groupedDetail->each(function ($detail) use (&$data, &$no, &$not) {
                        $data[] = [
                            'Parent_id' => '<ol start="'.$no.'" style="margin-bottom:0px;"><li>'.$detail->item_no.'</li></ol>',
                            'Item Name' => $detail->item_name,
                            'final' => number_format($detail->final, 0, ",", "."),
                            'non final' => number_format($detail->non_final, 0, ",", "."),
                            'total' => number_format($detail->total, 0, ",", "."),
                            'tax' => number_format($detail->tax, 0, ",", "."),
                        ];
                        $not++;
                    });
                    $no++;
                }
        
                return $data;
            };
        
            $data = $processDetails($details, $judulParentJson);
        
            return new Table(['No', 'Item Name', 'final', 'non final', 'total', 'tax'], $data);
        });

        $grid->column('client_id', __('Client_Id'));
        $grid->column('tahun', __('Tahun'));
        $grid->column('keterangan', __('Keterangan'));
        $grid->editButton()->display(function ($value) {
            // Customize the edit button link
            $url = $this->id;
            return "<a href='/admin/larudetail?laru_id={$url}' class='btn btn-xs btn-primary'>Edit Detail</a>";
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
        $show = new Show(Laru::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('client_id', __('Client_Id'));
        $show->field('tahun', __('Tahun'));
        $show->field('keterangan', __('Keterangan'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Laru);
        
        $form->select('id',__("Data Ekualisasi Tahunan"))->options(
            Ekualisasitahunan::select('id','keterangan','client_id')
            ->whereIn('id', function ($query) {
                $query->select('id')->from('larus');
            })
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => $item->client_id.'-'.$item->keterangan];
            })
        );
        $form->select('client_id',__("Nama Client"))->options(
            Client::select('id','nama_wp','id')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [$item->id => $item->id . '-' . $item->nama_wp];
                })
        );
        $form->text('tahun', __('Tahun'));
        $form->text('keterangan', __('Keterangan'));
        $form->textarea('judul_parent', __('Judul'));

        // You display this relation in three different modes (default, tab & table)
        $form->hasMany('larudetails', function ($form) {
            $form->text('parent_id', __('Parent ID'));
            $form->text('item_no', __('Item No'));
            $form->text('item_name', __('Item'));
            $form->text('column_order', __('Urutan No'));
        })->mode("table");

        return $form;
    }

    public function storeall(Request $request)
    {
        $laru = new Laru();
        $laru->id = $request->client_id.$request->tahun;
        $laru->client_id = $request->client_id;
        $laru->tahun = $request->tahun;
        $laru->keterangan = $request->keterangan;
        $laru->judul_parent = '{
            "1": "PEREDARAAN USAHA",
            "2": "HARGA POKOK PENJUALAN",
            "3": "LABA-RUGI BRUTO (Penjualan Bersih - HPP)",
            "4": "BIAYA OPERASIONAL",
            "5": "BIAYA LAINNYA",
            "6": "LABA-RUGI BERSIH (Laba Rugi Bruto - Biaya Operasional)"
          }';
        $laru->save();

        // Mendapatkan data item dari tabel laruitems
        $laruItems = Laruitem::select('id','parent_id','item_no','item_name','column_order')->get();

        // Simpan detail larudetails
        foreach ($laruItems as $detail) {
            $larudetail = new Larudetail();
            $larudetail->laru_id = $laru->id; // Mengambil ID baru yang disimpan dari Laru
            $larudetail->parent_id = $detail['parent_id'];
            
            $larudetail->item_no = $detail['item_no'];
            $larudetail->item_name = $detail['item_name'];
            $larudetail->column_order = $detail['column_order'];
            $larudetail->save();
        }

        return redirect()->to('admin/laru');
    }
}
