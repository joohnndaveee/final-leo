<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';
    public $timestamps = true;

    protected $fillable = [
        'reporter_id', 'reporter_type', 'reported_type', 'reported_id',
        'reason', 'description', 'evidence_image',
        'status', 'reviewed_by', 'reviewed_at', 'admin_notes',
    ];

    protected $casts = [
        'reviewed_at' => 'datetime',
    ];

    public function reportedProduct()
    {
        return $this->belongsTo(Product::class, 'reported_id');
    }

    public function reportedSeller()
    {
        return $this->belongsTo(Seller::class, 'reported_id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_id');
    }

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(Admin::class, 'reviewed_by');
    }

    public function getReportedName(): string
    {
        if ($this->reported_type === 'product') {
            return optional($this->reportedProduct)->name ?? 'Deleted Product #' . $this->reported_id;
        }
        if ($this->reported_type === 'seller') {
            return optional($this->reportedSeller)->shop_name ?? 'Deleted Seller #' . $this->reported_id;
        }
        return optional($this->reportedUser)->name ?? 'Deleted User #' . $this->reported_id;
    }
}
