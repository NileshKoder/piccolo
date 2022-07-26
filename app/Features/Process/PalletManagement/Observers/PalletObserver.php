<?php

namespace App\Features\Process\PalletManagement\Observers;

use App\Features\Process\PalletManagement\Domains\Models\Pallet;

class PalletObserver
{
    /**
     * Handle the pallet "created" event.
     *
     * @param  \App\Pallet  $pallet
     * @return void
     */
    public function created(Pallet $pallet)
    {
        $pallet->masterPallet->updateIsEmpty(false);
    }

    /**
     * Handle the pallet "updated" event.
     *
     * @param  \App\Pallet  $pallet
     * @return void
     */
    public function updated(Pallet $pallet)
    {
        //
    }

    /**
     * Handle the pallet "deleted" event.
     *
     * @param  \App\Pallet  $pallet
     * @return void
     */
    public function deleted(Pallet $pallet)
    {
        $pallet->masterPallet->updateIsEmpty(true);
    }

    /**
     * Handle the pallet "restored" event.
     *
     * @param  \App\Pallet  $pallet
     * @return void
     */
    public function restored(Pallet $pallet)
    {
        //
    }

    /**
     * Handle the pallet "force deleted" event.
     *
     * @param  \App\Pallet  $pallet
     * @return void
     */
    public function forceDeleted(Pallet $pallet)
    {
        //
    }
}
