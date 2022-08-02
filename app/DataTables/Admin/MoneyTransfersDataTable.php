<?php

namespace App\DataTables\Admin;

use App\Http\Helpers\Common;
use App\Models\Transfer;
use Yajra\DataTables\Services\DataTable;
use Session, Config, Auth;

class MoneyTransfersDataTable extends DataTable
{

    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->editColumn('created_at', function ($transfer) {
                return dateFormat($transfer->created_at);
            })
            ->addColumn('sender', function ($transfer) {
                $sender = isset($transfer->sender->first_name) && !empty($transfer->sender->first_name) ? $transfer->sender->first_name . ' ' . $transfer->sender->last_name : "-";

                return (Common::has_permission(Auth::guard('admin')->user()->id, 'edit_user')) ? '<a href="' . url(Config::get('adminPrefix') . '/users/edit/' . $transfer->sender->id) . '">' . $sender . '</a>' : $sender;
            })
            ->editColumn('amount', function ($transfer) {
                return formatNumber($transfer->amount, $transfer->currency_id);
            })
            ->editColumn('fee', function ($transfer) {
                return ($transfer->fee == 0) ? '-' : formatNumber($transfer->fee, $transfer->currency_id);
            })
            ->addColumn('total', function ($transfer) {
                return '<td><span class="text-' . (($transfer->amount + $transfer->fee > 0) ? 'green">+' : 'red">-') . formatNumber($transfer->amount + $transfer->fee, $transfer->currency_id) . '</span></td>';
            })
            ->editColumn('currency_id', function ($transfer) {
                return isset($transfer->currency->code) && !empty($transfer->currency->code) ? $transfer->currency->code : '';
            })
            ->addColumn('receiver', function ($transfer) {
                if (isset($transfer->receiver->first_name) && !empty($transfer->receiver->first_name)) {
                    $receiver = $transfer->receiver->first_name . ' ' .$transfer->receiver->last_name;
                    $receiverWithLink = (Common::has_permission(Auth::guard('admin')->user()->id, 'edit_user')) ? '<a href="' . url(Config::get('adminPrefix') . '/users/edit/' . $transfer->receiver->id) . '">' . $receiver . '</a>' : $receiver;
                } else {
                    if (!empty($transfer->email)) {
                        $receiver         = $transfer->email;
                        $receiverWithLink = $receiver;
                    } elseif (!empty($transfer->phone)) {
                        $receiver         = $transfer->phone;
                        $receiverWithLink = $receiver;
                    } else {
                        $receiver         = '-';
                        $receiverWithLink = $receiver;
                    }
                }
                return $receiverWithLink;
            })
            ->editColumn('status', function ($transfer) {
                return getStatusLabel($transfer->status);
            })
            ->addColumn('action', function ($transfer) {
                return (Common::has_permission(Auth::guard('admin')->user()->id, 'edit_transfer')) ? '<a href="' . url(Config::get('adminPrefix') . '/transfers/edit/' . $transfer->id) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;' : '';
            })
            ->rawColumns(['sender', 'receiver', 'total', 'status', 'action'])
            ->make(true);
    }

    public function query()
    {
        $status   = isset(request()->status) ? request()->status : 'all';
        $currency = isset(request()->currency) ? request()->currency : 'all';
        $user     = isset(request()->user_id) ? request()->user_id : null;
        $from     = isset(request()->from) ? setDateForDb(request()->from) : null;
        $to       = isset(request()->to) ? setDateForDb(request()->to) : null;
        $query    = (new Transfer())->getTransfersList($from, $to, $status, $currency, $user);

        return $this->applyScopes($query);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'id', 'name' => 'transfers.id', 'title' => 'ID', 'searchable' => false, 'visible' => false]) //hidden

            ->addColumn(['data' => 'uuid', 'name' => 'transfers.uuid', 'title' => 'UUID', 'visible' => false])

            ->addColumn(['data' => 'sender', 'name' => 'sender.last_name', 'title' => 'Last Name', 'visible' => false])         //custom

            ->addColumn(['data' => 'receiver', 'name' => 'receiver.last_name', 'title' => 'Last Name', 'visible' => false])     //custom

            ->addColumn(['data' => 'created_at', 'name' => 'transfers.created_at', 'title' => 'Date'])

            ->addColumn(['data' => 'sender', 'name' => 'sender.first_name', 'title' => 'User']) //custom

            ->addColumn(['data' => 'amount', 'name' => 'transfers.amount', 'title' => 'Amount'])

            ->addColumn(['data' => 'fee', 'name' => 'transfers.fee', 'title' => 'Fees'])

            ->addColumn(['data' => 'total', 'name' => 'total', 'title' => 'Total', 'searchable' => false]) //custom

            ->addColumn(['data' => 'currency_id', 'name' => 'currency.code', 'title' => 'Currency']) //custom

            ->addColumn(['data' => 'receiver', 'name' => 'receiver.first_name', 'title' => 'Receiver']) //custom

            ->addColumn(['data' => 'status', 'name' => 'transfers.status', 'title' => 'Status'])

            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])

            ->parameters(dataTableOptions());
    }
}
