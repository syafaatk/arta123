<?php

namespace App\Observers;
use App\Models\Ekualisasidetail;

class DetailEkualisasiObserver
{
    /**
     * Handle the Ekualisasidetail "updating" event.
     *
     * @param  \App\Models\Ekualisasidetail  $detailPemeriksaan
     * @return void
     */
    public function updating(Ekualisasidetail $detailPemeriksaan)
    {
        // Check if item_pemeriksaan_id is 1 or 2
        if (in_array($detailPemeriksaan->item_pemeriksaan_id, [1, 2])) {
            // Calculate total quantity and jumlah from the two rows above
            $totalQuantity = Ekualisasidetail::where('pemeriksaan_id', $detailPemeriksaan->pemeriksaan_id)
                ->whereIn('item_pemeriksaan_id', [1, 2])
                ->where('id', '<', $detailPemeriksaan->id)
                ->orderByDesc('id')
                ->limit(2)
                ->sum('quantity');

            $totalJumlah = Ekualisasidetail::where('pemeriksaan_id', $detailPemeriksaan->pemeriksaan_id)
                ->whereIn('item_pemeriksaan_id', [1, 2])
                ->where('id', '<', $detailPemeriksaan->id)
                ->orderByDesc('id')
                ->limit(2)
                ->sum('jumlah');

            // Update the corresponding row with item_pemeriksaan_id 3
            Ekualisasidetail::where('pemeriksaan_id', $detailPemeriksaan->pemeriksaan_id)
                ->where('item_pemeriksaan_id', 3)
                ->update([
                    'quantity' => $totalQuantity,
                    'jumlah' => $totalJumlah,
                ]);
        }
    }
}
