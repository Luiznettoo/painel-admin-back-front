<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use RuntimeException;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'user',
        'email',
        'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_token',
        'expires',
    ];

    public function refreshToken(): void {
        $rdn = NULL;

        try {
            $rdn = random_bytes(32);
        } catch (Exception $e) {
            $strong = false;
            $rdn = openssl_random_pseudo_bytes(32, $strong);

            if (!$strong || !$rdn) {
                throw new RuntimeException('IV generation failed');
            }
        }

        try {
            $this->api_token = hash('sha256', $rdn);
            $this->expires = date('Y-m-d H:i:s', time() + (int) config('auth.token_lifetime'));
            $this->save();
        } catch (QueryException $e) {
            if ($e->errorInfo[1] === 1062) { // Unique constraint failed...
                $this->refreshToken();
            }
            else {
                throw $e;
            }
        }
    }

    public function clearToken(): void {
        $this->api_token = NULL;
        $this->expires = NULL;
        $this->save();
    }
}
