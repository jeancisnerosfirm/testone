<?php

namespace App\DataTables\Admin;

use App\Http\Helpers\Common;
use App\Models\MerchantPayment;
use Yajra\DataTables\Services\DataTable;
use Session, Config, Auth;

class MerchantPaymentsDataTable extends DataTable
{

    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->editColumn('created_at', function ($merchant_payment) {
                return dateFormat($merchant_payment->created_at);
            })
            ->editColumn('merchant_id', function ($merchant_payment) {
                $merchant = isset($merchant_payment->merchant->user->first_name) && !empty($merchant_payment->merchant->user->first_name) ? $merchant_payment->merchant->user->first_name . ' ' . $merchant_payment->merchant->user->last_name : "-";
                return (Common::has_permission(Auth::guard('admin')->user()->id, 'edit_user')) ? '<a href="' . url(Config::get('adminPrefix') . '/users/edit/' . $merchant_payment->merchant->user->id) . '">' . $merchant . '</a>' : $merchant;
            })
            ->editColumn('user_id', function ($merchant_payment) {
                if (isset($merchant_payment->user->id)) {
                    $user = isset($merchant_payment->user->first_name) && !empty($merchant_payment->user->first_name) ? $merchant_payment->user->first_name . ' ' . $merchant_payment->user->last_name : '-';
                    $userWithLink = (Common::has_permission(Auth::guard('admin')->user()->id, 'edit_user')) ? '<a href="' . url(Config::get('adminPrefix') . '/users/edit/' . $merchant_payment->user->id) . '">' . $user . '</a>' : $user;
                    return $userWithLink;
                }
                return '';
            })
            ->editColumn('amount', function ($merchant_payment) {
                return formatNumber($merchant_payment->amount, $merchant_payment->currency_id, $merchant_payment->currency_id);
            })
            ->addColumn('fees', function ($merchant_payment) {
                return ($merchant_payment->charge_percentage == 0) && ($merchant_payment->charge_fixed == 0) ? "-" : formatNumber($merchant_payment->charge_percentage + $merchant_payment->charge_fixed, $merchant_payment->currency_id);
            })
            ->editColumn('total', function ($merchant_payment) {
                $total = $merchant_payment->charge_percentage + $merchant_payment->charge_fixed + $merchant_payment->amount;
                if ($total > 0) {
                    if ($merchant_payment->status == 'Refund') {
                        $total = '<td><span class="text-red">-' . formatNumber($total, $merchant_payment->currency_id) . '</span></td>';
                    } else {
                        $total = '<td><span class="text-green">+' . formatNumber($total, $merchant_payment->currency_id) . '</span></td>';
                    }
                } else {
                    $total = '<td><span class="text-red">' . formatNumber($total, $merchant_payment->currency_id) . '</span></td>';
                }
                return $total;
            })
            ->editColumn('currency_id', function ($merchant_payment) {
                return isset($merchant_payment->currency->code) && !empty($merchant_payment->currency->code) ? $merchant_payment->currency->code : '-';
            })
            ->editColumn('payment_method_id', function ($merchant_payment) {
                return ($merchant_payment->payment_method->name == "Mts") ? settings('name') : $merchant_payment->payment_method->name;
            })
            ->editColumn('status', function ($merchant_payment) {
                return getStatusLabel($merchant_payment->status);
            })
            ->addColumn('action', function ($merchant_payment) {
                return (Common::has_permission(Auth::guard('admin')->user()->id, 'edit_merchant_payment')) ? '<a href="' . url(Config::get('adminPrefix') . '/merchant_payments/edit/' . $merchant_payment->id) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;' : '';
            })
            ->rawColumns(['total', 'merchant_id', 'user_id', 'status', 'action'])
            ->make(true);
    }

    public function query()
    {
        $status   = isset(request()->status) ? request()->status : 'all';
        $currency = isset(request()->currency) ? request()->currency : 'all';
        $pm       = isset(request()->payment_methods) ? request()->payment_methods : 'all';
        $from     = isset(request()->from) ? setDateForDb(request()->from) : null;
        $to       = isset(request()->to) ? setDateForDb(request()->to) : null;
        $query    = (new MerchantPayment())->getMerchantPaymentsList($from, $to, $status, $currency, $pm);

        return $this->applyScopes($query);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'id', 'name' => 'merchant_payments.id', 'title' => 'ID', 'searchable' => false, 'visible' => false])
            ->addColumn(['data' => 'created_at', 'name' => 'merchant_payments.created_at', 'title' => 'Date'])
            ->addColumn(['data' => 'uuid', 'name' => 'merchant_payments.uuid', 'title' => 'UUID', 'visible' => false])
            ->addColumn(['data' => 'merchant_id', 'name' => 'merchant.user.last_name', 'title' => 'Merchant U Last Name', 'visible' => false])
            ->addColumn(['data' => 'merchant_id', 'name' => 'merchant.user.first_name', 'title' => 'Merchant'])
            ->addColumn(['data' => 'user_id', 'name' => 'user.last_name', 'title' => 'User Last Name', 'visible' => false])
            ->addColumn(['data' => 'user_id', 'name' => 'user.first_name', 'title' => 'User '])
            ->addColumn(['data' => 'amount', 'name' => 'merchant_payments.amount', 'title' => 'Amount'])
            ->addColumn(['data' => 'fees', 'name' => 'fees', 'title' => 'Fees'])
            ->addColumn(['data' => 'total', 'name' => 'merchant_payments.total', 'title' => 'Total'])
            ->addColumn(['data' => 'currency_id', 'name' => 'currency.code', 'title' => 'Currency'])
            ->addColumn(['data' => 'payment_method_id', 'name' => 'payment_method.name', 'title' => 'Payment Method'])
            ->addColumn(['data' => 'status', 'name' => 'merchant_payments.status', 'title' => 'Status'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
            ->parameters(dataTableOptions());
    }
}
