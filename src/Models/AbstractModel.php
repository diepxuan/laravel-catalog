<?php

declare(strict_types=1);

/*
 * @copyright  © 2019 Dxvn, Inc.
 *
 * @author     Tran Ngoc Duc <ductn@diepxuan.com>
 * @author     Tran Ngoc Duc <caothu91@gmail.com>
 *
 * @lastupdate 2024-06-15 11:54:56
 */

namespace Diepxuan\Catalog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractModel extends Model
{
    use HasFactory;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection;

    /**
     * Create a new Eloquent model instance.
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->connection = config('database.default');
    }

    public static function boot(): void
    {
        parent::boot();

        self::creating(static function ($model): void {
            // ... code here
        });

        self::created(static function ($model): void {
            // ... code here
        });

        self::updating(static function ($model): void {
            // ... code here
        });

        self::updated(static function ($model): void {
            // ... code here
        });

        self::deleting(static function ($model): void {
            // ... code here
        });

        self::deleted(static function ($model): void {
            // ... code here
        });
    }
}
