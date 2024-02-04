<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Client;
use \App\Models\Ekualisasi;
use \App\Models\Ekualisasiitem;
use \App\Models\Masapajak;
use Illuminate\Http\Request;
use \App\Models\Ekualisasidetail;
use OpenAdmin\Admin\Widgets\Table;


class EkualisasiController extends AdminController
{
     /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ekualisasi';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    
     protected function grid()
    {
        $grid = new Grid(new Ekualisasi());
        $grid->column('id', 'Detail')->expand(function ($model) {
            $details = $model->ekualisasiDetails()->get();
        
            // Function to process details and calculate quantities
            $processDetails = function ($details, &$quantity, &$jumlah, &$dpp, &$dppg, &$ppn) {
                return $details->map(function ($detail) use (&$quantity, &$jumlah, &$dpp, &$dppg, &$ppn) {
                    $itemName = $detail->item_ekualisasi->item_pemeriksaan ?? 'Unknown';
                    $quantity = $detail->quantity;
                    $jumlah = $detail->jumlah;
                    $dpp = $detail->dpp_faktur_pajak;
                    $dppg = $detail->dpp_gunggung;
                    $ppn = $detail->ppn_pph;
        
                    return [
                        'ID' => $detail->item_pemeriksaan_id,
                        'item_pemeriksaan' => $itemName,
                        'quantity' => number_format($detail->quantity, 0, ",", "."),
                        'dpp_faktur_pajak' => number_format($detail->dpp_faktur_pajak, 0, ",", "."),
                        'dpp_gunggung' => number_format($detail->dpp_gunggung, 0, ",", "."),
                        'ppn_pph' => number_format($detail->ppn_pph, 0, ",", "."),
                        'keterangan' => $detail->keterangan,
                        // 'created_at' => $detail->created_at,
                    ];
                });
            };
        
            $data = $processDetails($details, $quantity, $jumlah, $dpp, $dppg, $ppn);

            return new Table(['ID', 'Item Ekualisasi', 'quantity', 'dpp faktur pajak', 'dpp gungung', 'ppn pph', 'keterangan'], $data->toArray());
        });
        

        $grid->column('client_id', __('Nama Client'))->display(function($clientId) {return Client::find($clientId)->nama_wp;});
        $grid->column('masa_pajak_id', __('Masa Pajak'))->display(function($masapajakId) {return Masapajak::find($masapajakId)->masa_pajak;});
        $grid->column('tanggal_masa_pajak', __('Tanggal Masa Pajak'))->filter('range', 'date');;
        $grid->column('diperiksa_oleh', __('Diperiksa Oleh'));
        $grid->column('mengetahui', __('Mengetahui'));
        $grid->column('status', __('Status'))->label([
            "draft"=>"warning",
            "revision"=>"danger",
            "done"=>"success",
        ]);
        
        // $grid->column('created_at', __('Created at'));
        // $grid->column('updated_at', __('Updated at'));
        // $grid->column('deleted_at', __('Deleted at'));

        $grid->filter(function ($filter) {
            //$filter->expand();
    
            $filter->column(1/2, function ($filter) {
                $filter->equal('client_id')->select(Client::all()->pluck('nama_wp', 'id'));
                $filter->equal('masa_pajak_id')->select(Masapajak::all()->pluck('masa_pajak', 'id'));
            });
    
            $filter->column(1/2, function ($filter) {
                $filter->between('tanggal_masa_pajak')->datetime();
            });
        });

        $grid->editButton()->display(function ($value) {
            // Customize the edit button link
            $url = $this->id;
            return "<a href='detail?pemeriksaan_id={$url}' class='btn btn-xs btn-primary'>Edit Detail</a>";
        });
        //return "<a href='detail?pemeriksaan_id={$url}' class='btn btn-xs btn-primary'>Edit Detail</a>
        

        
            //dd($data);
            $style = <<<STYLE
            <style>
                .table-responsive tr th {
                    text-align: center;
                }
                .table-responsive th,
                .table-responsive td {
                    border: 0.5px solid #ddd; /* Add border to both th and td elements */
                }

                .table-responsive {
                    border-collapse: collapse; /* Collapse borders for better styling */
                    width: 100%; /* Set width to 100% */
                }
            </style>
            STYLE;

            // Add custom styles to the table
            echo $style;

        return $grid;
    }

    protected function processItemPemeriksaan($id)
    {
        
        $details = Ekualisasi::with(['ekualisasiDetails' => function ($query) {
            $query->whereIn('item_pemeriksaan_id', [1, 2]);
        }])
        ->find($id);
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
                $id = $ekualisasiDetail->id;
            }elseif($ekualisasiDetail->item_pemeriksaan_id == 2){
                $quantity -= $ekualisasiDetail->quantity;
                $jumlah -= $ekualisasiDetail->jumlah;
                $dpp -= $ekualisasiDetail->dpp_faktur_pajak;
                $dppg -= $ekualisasiDetail->dpp_gunggung;
                $ppn -= $ekualisasiDetail->ppn_pph;
                $id = $ekualisasiDetail->id;
            }
            // Add more as needed
        }


        echo $quantity;
        echo $jumlah;
        echo $dpp;
        echo $dppg;
        echo $ppn;

        Ekualisasidetail::updateOrInsert(
            [
                'id' => $id+1,
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
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Ekualisasi::with('ekualisasiDetails')->findOrFail($id));
        $show->field('id', __('Id'));
        $show->field('client_id', __('Data Client'))->as(function ($client_id) {
            $client = Client::find($client_id);
        
            if ($client) {
                $nama_wp = $client->nama_wp;
                $npwp_wp = $client->npwp_wp;        
                return "$nama_wp - $npwp_wp";
            } else {
                return '';
            }
        });

        $show->field('masa_pajak_id', __('Masa Pajak'))->as(function($masapajakId) {return Masapajak::find($masapajakId)->masa_pajak;});
        $show->field('tanggal_masa_pajak', __('Tanggal Masa Pajak'));
        $show->field('diperiksa_oleh', __('Diperiksa Oleh'));
        $show->field('mengetahui', __('Mengetahui'));
        $show->field('status', __('Status'))->using(['draft' => 'Draft', 'revision' => 'Revision', 'done' => 'Done']);
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
        $form = new Form(new Ekualisasi());
        $form->tab('Basic info', function ($form) {
            // $form->select('client_id', __("Nama Client"))->options(Client::all()->pluck('nama_wp', 'id'));
            $form->select('client_id',__("Nama Client"))->options(
                Client::select('id','nama_wp','npwp_wp')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        return [$item->id => $item->nama_wp . ' - ' . $item->npwp_wp];
                    })
            );
            $form->select('masa_pajak_id', __("Masa Pajak"))->options(Masapajak::all()->pluck('masa_pajak', 'id'));
            $form->date('tanggal_masa_pajak', __('Tanggal Masa Pajak'))->default(date('Y-m-dd'));
            $form->text('diperiksa_oleh', __('Diperiksa Oleh'));
            $form->text('mengetahui', __('Mengetahui'));
            $form->select('status','Status')->options(['draft' => 'Draft', 'revision' => 'Revision', 'done' => 'Done']);
        })->tab('Detail', function ($form) {
        // Form untuk detail Ekualisasi
            $items = Ekualisasiitem::all()->pluck('item_pemeriksaan', 'id');

            for ($i = 1; $i <= 33; $i++) {
                $form->fieldset('Ekualisasi '.$i, function ($form) use ($i, $items)  {
                    $ekualisasiId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($ekualisasiId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Ekualisasi $i"))
                    ->options($items)
                    ->value($i)
                    ->readonly();
                    $form->text("detail_pemeriksaan.$i.quantity", __("Quantity $i"));
                    $form->text("detail_pemeriksaan.$i.jumlah", __("Jumlah $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_faktur_pajak", __("DPP Faktur Pajak $i"));
                    $form->text("detail_pemeriksaan.$i.dpp_gunggung", __("DPP Gungung $i"));
                    $form->text("detail_pemeriksaan.$i.ppn_pph", __("PPN PPH $i"));
                    $form->text("detail_pemeriksaan.$i.keterangan", __("Keterangan $i"));
                });
            }
        });
        return $form;
    }

    public function editall($id)
    {
        $form = $this->form()->edit($id);

        return $form;
    }

    public function storeall(Request $request)
    {
        // Validate your request data as needed

        // Create Ekualisasi record
        $ekualisasi = Ekualisasi::create([
            'client_id' => $request->input('client_id'),
            'masa_pajak_id' => $request->input('masa_pajak_id'),
            'tanggal_masa_pajak' => $request->input('tanggal_masa_pajak'),
            'diperiksa_oleh' => $request->input('diperiksa_oleh'),
            'mengetahui' => $request->input('mengetahui'),
            'status' => $request->input('status'),
        ]);

        // Save DetailEkualisasi records
        $details = $request->input('detail_pemeriksaan');

        foreach ($details as $detail) {
            Ekualisasidetail::create([
                'pemeriksaan_id' => $ekualisasi->id,
                'item_pemeriksaan_id' => $detail['item_pemeriksaan_id'],
                'quantity' => $detail['quantity'],
                'jumlah' => $detail['jumlah'],
                'dpp_faktur_pajak' => $detail['dpp_faktur_pajak'],
                'dpp_gunggung' => $detail['dpp_gunggung'],
                'ppn_pph' => $detail['ppn_pph'],
                'keterangan' => $detail['keterangan'],
                // Add other fields as needed
            ]);
        }

        return redirect()->to('admin/ekualisasi/masters');
    }

    public function updateall(Request $request, $id)
    {
        // Validate your request data as needed

        // Find the existing Ekualisasi record
        $ekualisasi = Ekualisasi::findOrFail($id);

        // Update Ekualisasi record
        $ekualisasi->update([
            'client_id' => $request->input('client_id'),
            'masa_pajak_id' => $request->input('masa_pajak_id'),
            'tanggal_masa_pajak' => $request->input('tanggal_masa_pajak'),
            'diperiksa_oleh' => $request->input('diperiksa_oleh'),
            'mengetahui' => $request->input('mengetahui'),
            'status' => $request->input('status'),
            // Add other fields as needed
        ]);

        // // Update or create DetailEkualisasi records
        // for ($i = 1; $i <= 33; $i++) {
        //     Ekualisasidetail::updateOrInsert(
        //         ['pemeriksaan_id' => $ekualisasi->id, 'item_pemeriksaan_id' => $request->input("detail_pemeriksaan.$i.item_pemeriksaan_id")],
        //         [
        //             'quantity' => $request->input("detail_pemeriksaan.$i.quantity"),
        //             'jumlah' => $request->input("detail_pemeriksaan.$i.jumlah"),
        //             'dpp_faktur_pajak' => $request->input("detail_pemeriksaan.$i.dpp_faktur_pajak"),
        //             'dpp_gunggung' => $request->input("detail_pemeriksaan.$i.dpp_gunggung"),
        //             'ppn_pph' => $request->input("detail_pemeriksaan.$i.ppn_pph"),
        //             'keterangan' => $request->input("detail_pemeriksaan.$i.keterangan"),
        //             // Add other fields as needed
        //         ]
        //     );
        // }

        return redirect()->to('admin/ekualisasi/masters');
    }

}