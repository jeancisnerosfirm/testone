<?php

namespace App\DataTables\Admin;

use App\Http\Helpers\Common;
use App\Models\DocumentVerification;
use Yajra\DataTables\Services\DataTable;
use Session, Config, Auth;

class IdentityProofsDataTable extends DataTable
{
    public function ajax()
    {
        return datatables()
            ->eloquent($this->query())
            ->editColumn('created_at', function ($documentVerification) {
                return dateFormat($documentVerification->created_at);
            })
            ->addColumn('user_id', function ($documentVerification) {
                $sender = isset($documentVerification->user->first_name) && !empty($documentVerification->user->first_name) ? $documentVerification->user->first_name . ' ' . $documentVerification->user->last_name : "-";
                return (Common::has_permission(Auth::guard('admin')->user()->id, 'edit_user')) ? '<a href="' . url(Config::get('adminPrefix') . '/users/edit/' . $documentVerification->user->id) . '">' . $sender . '</a>' : $sender;
            })
            ->editColumn('identity_type', function ($documentVerification) {
                return str_replace('_', ' ', ucfirst($documentVerification->identity_type));
            })
            ->editColumn('status', function ($documentVerification) {
                return getStatusLabel($documentVerification->status);
            })
            ->addColumn('action', function ($documentVerification) {
                return (Common::has_permission(Auth::guard('admin')->user()->id, 'edit_identity_verfication')) ? '<a href="' . url(Config::get('adminPrefix') . '/identity-proofs/edit/' . $documentVerification->id) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>&nbsp;' : '';
            })
            ->rawColumns(['user_id', 'status', 'action'])
            ->make(true);
    }

    public function query()
    {
        $status   = isset(request()->status) ? request()->status : 'all';
        $from     = isset(request()->from) ? setDateForDb(request()->from) : null;
        $to       = isset(request()->to) ? setDateForDb(request()->to) : null;
        $query    = (new DocumentVerification())->getDocumentVerificationsList($from, $to, $status);

        return $this->applyScopes($query);
    }

    public function html()
    {
        return $this->builder()
            ->addColumn(['data' => 'id', 'name' => 'document_verifications.id', 'title' => 'ID', 'searchable' => false, 'visible' => false])
            ->addColumn(['data' => 'created_at', 'name' => 'document_verifications.created_at', 'title' => 'Date'])
            ->addColumn(['data' => 'user_id', 'name' => 'user.last_name', 'title' => 'User', 'visible' => false])
            ->addColumn(['data' => 'user_id', 'name' => 'user.first_name', 'title' => 'User'])
            ->addColumn(['data' => 'identity_type', 'name' => 'document_verifications.identity_type', 'title' => 'Identity Type'])
            ->addColumn(['data' => 'identity_number', 'name' => 'document_verifications.identity_number', 'title' => 'Identity Number'])
            ->addColumn(['data' => 'status', 'name' => 'document_verifications.status', 'title' => 'Status'])
            ->addColumn(['data' => 'action', 'name' => 'action', 'title' => 'Action', 'orderable' => false, 'searchable' => false])
            ->parameters(dataTableOptions());
    }
}
