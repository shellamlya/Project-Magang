<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class WebsiteVisitor
 * @package App\Models
 * Model untuk mencatat dan menghitung pengunjung unik website (Visitor Tracking).
 */
class WebsiteVisitor extends Model
{
    use HasFactory;

    protected $table = 'website_visitors';

    protected $fillable = [
        'session_id',
        'ip_address',
        'user_agent',
        'page_views',
    ];

    protected $casts = [
        'page_views' => 'integer',
    ];
}
