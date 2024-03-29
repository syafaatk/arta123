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
                        'Parent_id' => '<ol style="margin-left:-25px;margin-bottom:0px;"><b>'.$parentId.'.</b></ol>',
                        'Item Name' => '<b>'.$itemName.'</b>',
                        'Ket' => '',
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
                        if($detail->ket == 0):
                            $ket = "<span class='badge bg-warning'>PPh 21</badge>";
                        elseif($detail->ket == 1):
                            $ket = "<span class='badge bg-success'>Unifikasi</badge>";
                        else:
                            $ket = "<span class='badge bg-danger'>-</badge>";
                        endif;

                        $data[] = [
                            'Parent_id' => '<ol start="'.$no.'" style="margin-bottom:0px;"><li>'.$detail->item_no.'</li></ol>',
                            'Item Name' => $detail->item_name,
                            'Ket' => $ket,
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
        
            return new Table(['No', 'Item Name','Ket', 'final', 'non final', 'total', 'tax'], $data);
        });

        $grid->column('client_id', __('Client_Id'));
        $grid->column('tahun', __('Tahun'));
        $grid->column('keterangan', __('Keterangan'));
        $grid->column('Gross Profit Ratio')->display(function () {
            $pendapatan = Larudetail::where('column_order','3')->where('laru_id', $this->id)->first();
            $gross = Larudetail::where('column_order','8')->where('laru_id', $this->id)->first();
            $netto = Larudetail::where('column_order','36')->where('laru_id', $this->id)->first();
            
            // ddd($gross->total);
            // ddd($pendapatan->total);
            // ddd($pendapatan->total);
            $jumlah = 100*$gross->total/$pendapatan->total;
            return number_format($jumlah, 3, '.', '').' %';
        });
        $grid->column('Net Profit Ratio')->display(function () {
            $pendapatan = Larudetail::where('column_order','3')->where('laru_id', $this->id)->first();
            $gross = Larudetail::where('column_order','8')->where('laru_id', $this->id)->first();
            $netto = Larudetail::where('column_order','36')->where('laru_id', $this->id)->first();
            
            // ddd($gross->total);
            // ddd($pendapatan->total);
            // ddd($pendapatan->total);
            $jumlah = 100*$netto->total/$pendapatan->total;
            return number_format($jumlah, 3, '.', '').' %';
        });
        $grid->editButton()->display(function ($value) {
            // Customize the edit button link
            $url = $this->id;
            return "<a href='/admin/larudetail?laru_id={$url}' class='btn btn-xs btn-primary'>Edit Detail</a>";
        });
        $grid->filter(function ($filter) {
            //$filter->expand();
    
            $filter->column(1/2, function ($filter) {
                $filter->equal('client_id')->select(Client::all()->pluck('nama_wp', 'id'));
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
        $show = new Show(Laru::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('client_id', __('Client_Id'));
        $show->field('tahun', __('Tahun'));
        $show->field('keterangan', __('Keterangan'));
        $show->field('file_laru')->image();

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
        $form->image('file_laru', 'File Laru')->move('files/Larus/')->rules('mimes:pdf,jpeg,png')->uniqueName();
        $form->divider();
        // You display this relation in three different modes (default, tab & table)
        $form->hasMany('larudetails', function ($form) {
            $form->text('parent_id', __('Parent ID'));
            $form->text('item_no', __('Item No'));
            $form->text('item_name', __('Item'));
            $form->text('column_order', __('Urutan No'));
            $form->select('ket',__('Keterangan'))->options([0 => 'PPh 21', 1 => 'Unifikasi']);
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
            "3": "LABA-RUGI BRUTO",
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
