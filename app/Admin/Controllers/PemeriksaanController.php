<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Client;
use \App\Models\Pemeriksaan;
use \App\Models\Pemeriksaanitem;
use \App\Models\Masapajak;
use Illuminate\Http\Request;
use \App\Models\Pemeriksaandetail;
use OpenAdmin\Admin\Widgets\Table;


class PemeriksaanController extends AdminController
{
     /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Pemeriksaan';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    
     protected function grid()
    {
        $grid = new Grid(new Pemeriksaan());

        $grid->column('id', 'Detail')->expand(function ($model) {
            $details = $model->pemeriksaanDetails()->take(30)->get()->map(function ($detail) {
                // Access the related item_pemeriksaan model to get the 'name'
                $itemName = $detail->item_pemeriksaan->item_pemeriksaan;
        
                return [
                    'ID' => $detail->item_pemeriksaan_id,
                    'item_pemeriksaan' => $itemName,
                    'quantity' => $detail->quantity,
                    'jumlah' => $detail->jumlah,
                    'dpp_faktur_pajak' => $detail->dpp_faktur_pajak,
                    'dpp_gunggung' => $detail->dpp_gunggung,
                    'ppn_pph' => $detail->ppn_pph,
                    'keterangan' => $detail->keterangan,
                    'created_at' => $detail->created_at,
                ];
            });
        
            return new Table(['ID', 'item_pemeriksaan', 'quantity', 'jumlah','dpp faktur pajak','dpp gungung','ppn pph','keterangan','created_at'], $details->toArray());
        });
        $grid->column('client_id', __('Nama Client'))->display(function($clientId) {return Client::find($clientId)->nama_wp;});
        $grid->column('masa_pajak_id', __('Masa Pajak'))->display(function($masapajakId) {return Masapajak::find($masapajakId)->masa_pajak;});
        $grid->column('tanggal_masa_pajak', __('Tanggal Masa Pajak'))->filter('range', 'date');;
        $grid->column('diperiksa_oleh', __('Diperiksa Oleh'));
        $grid->column('mengetahui', __('Mengetahui'));
        
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('deleted_at', __('Deleted at'));

        $grid->filter(function ($filter) {
            $filter->expand();
    
            $filter->column(1/2, function ($filter) {
                $filter->equal('client_id')->select(Client::all()->pluck('nama_wp', 'id'));
                $filter->equal('masa_pajak_id')->select(Masapajak::all()->pluck('masa_pajak', 'id'));
            });
    
            $filter->column(1/2, function ($filter) {
                $filter->between('tanggal_masa_pajak')->datetime();
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
        $show = new Show(Pemeriksaan::with('pemeriksaanDetails')->findOrFail($id));
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
        $form = new Form(new Pemeriksaan());
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
        // Form untuk detail pemeriksaan
        $items = Pemeriksaanitem::all()->pluck('item_pemeriksaan', 'id');

        for ($i = 1; $i <= 31; $i++) {
            if($i >= 1 and $i <= 3 ):
                $form->fieldset('Pemeriksaan '.$i, function ($form) use ($i, $items)  {
                    $pemeriksaanId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($pemeriksaanId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Pemeriksaan $i"))
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
            elseif($i >= 4 && $i <= 7 ):
            $form->fieldset('Pemeriksaan '.$i, function ($form) use ($i, $items)  {
                    $pemeriksaanId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($pemeriksaanId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Pemeriksaan $i"))
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
            elseif($i >= 8 && $i <= 11 ):
            $form->fieldset('Pemeriksaan '.$i, function ($form) use ($i, $items)  {
                    $pemeriksaanId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($pemeriksaanId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Pemeriksaan $i"))
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
            elseif($i >= 12 && $i <= 15 ):
            $form->fieldset('Pemeriksaan '.$i, function ($form) use ($i, $items)  {
                    $pemeriksaanId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($pemeriksaanId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Pemeriksaan $i"))
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
            elseif($i >= 16 && $i <= 21 ):
            $form->fieldset('Pemeriksaan '.$i, function ($form) use ($i, $items)  {
                    $pemeriksaanId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($pemeriksaanId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Pemeriksaan $i"))
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
            elseif($i >= 22 && $i <= 24 ):
            $form->fieldset('Pemeriksaan '.$i, function ($form) use ($i, $items)  {
                    $pemeriksaanId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($pemeriksaanId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Pemeriksaan $i"))
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
            elseif($i >= 25 && $i <= 27 ):
            $form->fieldset('Pemeriksaan '.$i, function ($form) use ($i, $items)  {
                    $pemeriksaanId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($pemeriksaanId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Pemeriksaan $i"))
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
            elseif($i >= 28 && $i <= 30 ):
            $form->fieldset('Pemeriksaan '.$i, function ($form) use ($i, $items)  {
                    $pemeriksaanId = $form->model()->id;
                    $form->hidden("detail_pemeriksaan.$i.pemeriksaan_id")->value($pemeriksaanId);
                    $form->select("detail_pemeriksaan.$i.item_pemeriksaan_id", __("Item Pemeriksaan $i"))
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
            endif;

            
            
            // Tambahkan kolom-kolom lainnya sesuai kebutuhan
        }
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

        // Create Pemeriksaan record
        $pemeriksaan = Pemeriksaan::create([
            'client_id' => $request->input('client_id'),
            'masa_pajak_id' => $request->input('masa_pajak_id'),
            'tanggal_masa_pajak' => $request->input('tanggal_masa_pajak'),
            'diperiksa_oleh' => $request->input('diperiksa_oleh'),
            'mengetahui' => $request->input('mengetahui'),
        ]);

        // Save DetailPemeriksaan records
        for ($i = 1; $i <= 30; $i++) {
            Pemeriksaandetail::create([
                'pemeriksaan_id' => $pemeriksaan->id,
                'item_pemeriksaan_id' => $request->input("detail_pemeriksaan.$i.item_pemeriksaan_id"),
                'quantity' => $request->input("detail_pemeriksaan.$i.quantity"),
                'jumlah' => $request->input("detail_pemeriksaan.$i.jumlah"),
                'dpp_faktur_pajak' => $request->input("detail_pemeriksaan.$i.dpp_faktur_pajak"),
                'dpp_gunggung' => $request->input("detail_pemeriksaan.$i.dpp_gunggung"),
                'ppn_pph' => $request->input("detail_pemeriksaan.$i.ppn_pph"),
                'keterangan' => $request->input("detail_pemeriksaan.$i.keterangan"),
                // Add other fields as needed
            ]);
        }

        return redirect()->to('admin/pemeriksaan/masters');
    }

    public function updateall(Request $request, $id)
    {
        // Validate your request data as needed

        // Find the existing Pemeriksaan record
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        // Update Pemeriksaan record
        $pemeriksaan->update([
            'client_id' => $request->input('client_id'),
            'masa_pajak_id' => $request->input('masa_pajak_id'),
            'tanggal_masa_pajak' => $request->input('tanggal_masa_pajak'),
            'diperiksa_oleh' => $request->input('diperiksa_oleh'),
            'mengetahui' => $request->input('mengetahui'),
            // Add other fields as needed
        ]);

        // Update or create DetailPemeriksaan records
        for ($i = 1; $i <= 30; $i++) {
            Pemeriksaandetail::updateOrInsert(
                ['pemeriksaan_id' => $pemeriksaan->id, 'item_pemeriksaan_id' => $request->input("detail_pemeriksaan.$i.item_pemeriksaan_id")],
                [
                    'quantity' => $request->input("detail_pemeriksaan.$i.quantity"),
                    'jumlah' => $request->input("detail_pemeriksaan.$i.jumlah"),
                    'dpp_faktur_pajak' => $request->input("detail_pemeriksaan.$i.dpp_faktur_pajak"),
                    'dpp_gunggung' => $request->input("detail_pemeriksaan.$i.dpp_gunggung"),
                    'ppn_pph' => $request->input("detail_pemeriksaan.$i.ppn_pph"),
                    'keterangan' => $request->input("detail_pemeriksaan.$i.keterangan"),
                    // Add other fields as needed
                ]
            );
        }

        return redirect()->to('admin/pemeriksaan/masters');
    }

}