<?php

namespace App\Traits;

use App\Models\Purchase;
use App\Models\Quotation;
use App\Models\Sale;
use App\Models\SalePaymentAccounts;
use App\Models\SalePaymentInstallments;

trait CronHelper
{

    public static function verifyAccountPendings($account_id = 0)
    {
        try {
            $where = ($account_id > 0) ? ['id' => $account_id] : [];
            SalePaymentAccounts::where($where)->where('status', '0')
                ->chunk(50, function ($accounts) {
                    foreach ($accounts as $account) {

                        //Check count of pending installments
                        $hasPendingEmis = SalePaymentInstallments::where(['sale_payment_account_id' => $account->id, 'status' => '0'])->count();
                        if ($hasPendingEmis == 0) {
                            //close account - when all dues paid marked
                            $account->update(['status' => '1', 'status_closed_note' => "All dues done closed autometically by script."]);

                            //Sales Model
                            $salesModel = Sale::where(['id' => $account->sale_id])->first();
                            if ($salesModel) {
                                $salesModel->update(['status' => 'close']);

                                //Mark Sold Of Purchase
                                if (intval($salesModel->purchase_id) > 0) {
                                    Purchase::where(['id' => $salesModel->purchase_id])->update(['status' => '2']);
                                }

                                //Quotation Mark As Closed
                                if (intval($salesModel->quotation_id) > 0) {
                                    Quotation::where(['id' => $salesModel->quotation_id])->update(['status' => 'close']);
                                }
                            }
                        }
                    }
                });

            return array(
                'status' => true,
                'message' => 'All accounts status updated successfully.'
            );
        } catch (\Exception $e) {
            return array(
                'status' => false,
                'message' => $e->getMessage()
            );
        }
    }
}
