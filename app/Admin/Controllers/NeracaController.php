<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Neraca;
use \App\Models\Neracadetail;
use \App\Models\Neracaitem;
use \App\Models\Client;
use Illuminate\Http\Request;

use \App\Models\Ekualisasitahunan;

use OpenAdmin\Admin\Widgets\Table;

class NeracaController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Neraca';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Neraca());
        $grid->column('id', 'Detail')->expand(function ($model) {
            $details = $model->Neracadetails()->get();

            $judulParentJson = json_decode($model->judul_parent, true);
        
            // Function to process details and calculate quantities
            $processDetails = function ($details,$judulParentJson) {
                $data = [];
                $no = 1;
                
                foreach ($judulParentJson as $parentId => $itemName) {
                    // Add parent title before each group of details

                    $data[] = [
                        'Parent_id' => '<ol style="margin-left:-25px;margin-bottom:0px;"><b>'.$parentId.'.</b></ol>',
                        'Item Name' => '<b>'.$itemName.'</b>',
                        'total sebelumnya' => '',
                        'total berjalan' => '',
                    ];
        
                    // Get details for the current parent_id
                    $groupedDetail = $details->where('parent_id', $parentId);
                    $not=1;
                    // Process and append details to data
                    $groupedDetail->each(function ($detail) use (&$data, &$no, &$not, &$detailsblm) {
                        //ddd($detailsblm->total);
                        $data[] = [
                            'Parent_id' => '<ol start="'.$no.'" style="margin-bottom:0px;"><li>'.$detail->item_no.'</li></ol>',
                            'Item Name' => $detail->item_name,
                            'total sebelumnya' => "0",
                            'total berjalan' => number_format($detail->total, 0, ",", "."),
                        ];
                        $not++;
                    });
                    $no++;
                }
        
                return $data;
            };
        
            $data = $processDetails($details, $judulParentJson);
        
            return new Table(['No', 'Item Name','Total Sebelumnya','Total Berjalan'], $data);
        });

        $grid->column('client_id', __('Client_Id'));
        $grid->column('tahun', __('Tahun'));
        $grid->column('keterangan', __('Keterangan'));
        $grid->editButton()->display(function ($value) {
            // Customize the edit button link
            $url = $this->id;
            return "<a href='/admin/neracadetail?neraca_id={$url}' class='btn btn-xs btn-primary'>Edit Detail</a>";
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
        $show = new Show(Neraca::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('client_id', __('Client_Id'));
        $show->field('tahun', __('Tahun'));
        $show->field('keterangan', __('Keterangan'));
        $show->field('file_neraca')->image();

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Neraca);
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
        $form->image('file_neraca', 'File Neraca')->move('files/Neracas/')->rules('mimes:pdf,jpeg,png')->uniqueName();
        $form->divider();
        // You display this relation in three different modes (default, tab & table)
        $form->hasMany('neracadetails', function ($form) {
            $form->text('parent_id', __('Parent ID'));
            $form->text('item_no', __('Item No'));
            $form->text('item_name', __('Item'));
            $form->text('column_order', __('Urutan No'));
        })->mode("table");

        return $form;
    }

    public function storeall(Request $request)
    {
        $neraca = new Neraca();
        $neraca->id = $request->client_id.$request->tahun;
        $neraca->client_id = $request->client_id;
        $neraca->tahun = $request->tahun;
        $neraca->keterangan = $request->keterangan;
        $neraca->judul_parent = '{
            "1": "AKTIVA LANCAR",
            "2": "AKTIVA TETAP",
            "3": "TOTAL AKTIVA",
            "4": "KEWAJIBAN LANCAR",
            "5": "MODAL",
            "6": "TOTAL PASSIVA"
          }';
        $neraca->save();

        // Mendapatkan data item dari tabel neracaitems
        $neracaItems = Neracaitem::select('id','parent_id','item_no','item_name','column_order')->get();

        // Simpan detail neracadetails
        foreach ($neracaItems as $detail) {
            $neracadetail = new Neracadetail();
            $neracadetail->neraca_id = $neraca->id; // Mengambil ID baru yang disimpan dari Neraca
            $neracadetail->parent_id = $detail['parent_id'];
            
            $neracadetail->item_no = $detail['item_no'];
            $neracadetail->item_name = $detail['item_name'];
            $neracadetail->column_order = $detail['column_order'];
            $neracadetail->save();
        }

        return redirect()->to('admin/neraca');
    }
}
