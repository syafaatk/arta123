<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Grid;
use App\Models\Client;
use App\Models\Ekualisasigroup;
use \App\Models\Ekualisasitahunan;
use App\Models\Ekualisasi;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Show;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Widgets\Table;


class EkualisasiGroupController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Ekualisasi Group';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
{
    $grid = new Grid(new Ekualisasigroup());
   
    // Define the columns for the grid
    $grid->column('id', 'Detail')->expand(function ($model) {
        // Mengambil semua data pemeriksaan yang terkait dengan client_id ini
        //$details = $model->pemeriksaan()->with('MasaPajak', 'clientDataSummary')->get();
        $details = Ekualisasi::getPemeriksaanByClientAndYear($model->client_id, $model->year);
    
        // Fungsi untuk memproses detail dan mengisi variabel referensi
        $processDetails = function ($details) {
            return $details->map(function ($detail) {
                // Mengisi variabel dengan data dari $detail
                $id = $detail->id;
                $client = $detail->clientDataSummary->nama_wp ?? 'Unknown';
                $masa_pajak_id = $detail->MasaPajak->masa_pajak ?? 'Unknown';
                $tanggal_masa_pajak = $detail->tanggal_masa_pajak;
                $diperiksa_oleh = $detail->diperiksa_oleh;
                $mengetahui = $detail->mengetahui;
                $status = $detail->status;
                
                // Set status with appropriate badge
                if ($status == "draft") {
                    $status = "<span class='badge bg-warning'>Draft</span>";
                } elseif ($status == "done") {
                    $status = "<span class='badge bg-success'>Done</span>";
                } else {
                    $status = "<span class='badge bg-danger'>-</span>";
                }
    
                // Menambahkan kolom Action dengan link
                $action = "<a href='".("/admin/ekualisasi/detail?pemeriksaan_id={$id}")."' target='_blank' class='btn btn-primary btn-sm'>Detail</a>";
    
                return [
                    'Nama WP' => $client,
                    'Masa Pajak' => $masa_pajak_id,
                    'Tanggal Masa Pajak' => $tanggal_masa_pajak,
                    'Diperiksa Oleh' => $diperiksa_oleh,
                    'Mengetahui' => $mengetahui,
                    'Status' => $status,
                    'Action' => $action,
                ];
            });
        };
    
        // Memproses data detail
        $data = $processDetails($details);
    
        // Menampilkan data dalam tabel dengan kolom yang telah ditentukan
        return new Table(['Nama WP', 'Masa Pajak', 'Tanggal Masa Pajak', 'Diperiksa Oleh', 'Mengetahui', 'Status', 'Action'], $data->toArray());
    });

    $grid->column('id_tahunan', 'Ekualisasi Tahunan')->expand(function ($model) {        
        $ekualisasiTahunan = Ekualisasitahunan::where('id', $model->id_tahunan)->first();

        // Check if the instance exists
        if ($ekualisasiTahunan) {
            // Use the instance to get the related details
            $details = $ekualisasiTahunan->ekualisasiDetails;
        } else {
            $details = collect(); // Return an empty collection if no instance is found
        }
        //dd($details);
        // Function to process details and calculate quantities
        $processDetails = function ($details, &$quantity, &$jumlah, &$dpp, &$dppg, &$ppn, &$tipeppn) {
            return $details->map(function ($detail) use (&$quantity, &$jumlah, &$dpp, &$dppg, &$ppn, &$tipeppn) {
                $itemName = $detail->item_ekualisasi->item_pemeriksaan ?? 'Unknown';
                $quantity = $detail->quantity;
                //$jumlah = $detail->jumlah;
                $dpp = $detail->dpp_faktur_pajak;
                $dppg = $detail->dpp_gunggung;
                $ppn = $detail->ppn_pph;
                $jumlah = $dpp + $dppg;
                $tipeppn = $detail->item_ekualisasi->tipe_ppn_pph ?? 'Unknown';

                if($tipeppn == 0):
                    $tipe = "<span class='badge bg-warning'>PPn</badge>";
                    $ppn = $detail->ppn_pph;
                    $pph = 0;
                elseif($tipeppn == 1):
                    $tipe = "<span class='badge bg-success'>PPh</badge>";
                    $pph = $detail->ppn_pph;
                    $ppn = 0;
                else:
                    $tipe = "<span class='badge bg-danger'>-</badge>";
                    $ppn = 0;
                    $pph = 0;
                endif;
    
                return [
                    'ID' => $detail->item_pemeriksaan_id,
                    'item_pemeriksaan' => $itemName.' '.$tipe,
                    'quantity' => number_format($detail->quantity, 0, ",", "."),
                    'dpp_faktur_pajak' => number_format($detail->dpp_faktur_pajak, 0, ",", "."),
                    'dpp_gunggung' => number_format($detail->dpp_gunggung, 0, ",", "."),
                    'jumlah' => number_format($jumlah, 0, ",", "."),
                    'ppn' => number_format($ppn, 0, ",", "."),
                    'pph' => number_format($pph, 0, ",", "."),
                    'keterangan' => $detail->keterangan,
                    // 'created_at' => $detail->created_at,
                ];
            });
        };
    
        $data = $processDetails($details, $quantity, $dpp, $dppg,$jumlah, $ppn, $ppn);

        return new Table(['ID', 'Item Ekualisasi', 'quantity', 'dpp faktur pajak', 'dpp gungung','jumlah', 'PPn','PPh', 'Ket'], $data->toArray());
    });
    
    
    
    // Define other columns
    $grid->column('client_id', __('ID Client'));
    $grid->column('nama_wp', __('Nama Client'));
    $grid->column('year', __('Year'));
    $grid->column('total_data', __('Total Data per Year'))->display(function() {
        $totalData = (int) $this->total_data;
        $totalDraft = (int) $this->jumlah_draft;
        $totalComplete = $totalData - $totalDraft;
    
        // Initialize badge colors and texts
        $badgeColor1 = 'secondary';
        $badgeText1 = '';
        $badgeColor2 = 'secondary';
        $badgeText2 = '';
    
        // Determine badge color and text based on conditions
        if ($totalData == 12) {
            if ($totalDraft > 0) {
                if ($totalComplete == 0) {
                    $badgeColor1 = 'danger';
                    $badgeText1 = "Complete: $totalComplete";
                    $badgeColor2 = 'warning';
                    $badgeText2 = "Drafts: $totalDraft";
                } elseif ($totalComplete < 12) {
                    $badgeColor1 = 'warning';
                    $badgeText1 = "Complete: $totalComplete";
                    $badgeColor2 = 'warning';
                    $badgeText2 = "Drafts: $totalDraft";
                } else {
                    $badgeColor1 = 'success';
                    $badgeText1 = "Complete: $totalComplete";
                    $badgeColor2 = 'warning';
                    $badgeText2 = "Drafts: $totalDraft";
                }
            } else {
                $badgeColor1 = 'success';
                $badgeText1 = "Complete: $totalComplete";
                $badgeColor2 = 'success';
                $badgeText2 = "Drafts: $totalDraft";
            }
        } else {
            if ($totalDraft > 0) {
                $badgeColor1 = 'danger';
                $badgeText1 = "Complete: $totalComplete";
                $badgeColor2 = 'warning';
                $badgeText2 = "Drafts: $totalDraft";
            } else {
                $badgeColor1 = 'secondary';
                $badgeText1 = "Complete: $totalComplete";
                $badgeColor2 = 'secondary';
                $badgeText2 = "Drafts: $totalDraft";
            }
        }
    
    
        return "<span class='badge bg-$badgeColor1'>$badgeText1</span> <span class='badge bg-$badgeColor2'>$badgeText2</span>";
    });
    
    
    

    // Customize actions
    $grid->actions(function ($actions) {
        $actions->disableDelete();
        $actions->disableEdit();
        $actions->disableShow();
    });

    // Filter section
    $grid->filter(function ($filter) {
        $filter->expand();

        $filter->column(1/2, function ($filter) {
            $filter->equal('client_id')->select(Client::all()->pluck('nama_wp', 'id'));
        });
        
    });
    
    $grid->column('Detail')->display(function () {
        // Retrieve the id_tahunan for the URL
        $url = $this->id_tahunan;
    
        // Customize the edit button link with a detail URL
        return "<a href='tahunan-detail?id={$url}' target='_blank' class='btn btn-xs btn-primary'>Detail Ekualisasi Tahunan</a>";
    });

    // Disable create button
    $grid->disableCreateButton();
    
    return $grid;
}

}