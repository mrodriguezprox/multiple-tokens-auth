<?php
namespace Livijn\MultipleTokensAuth\Test;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Livijn\MultipleTokensAuth\Traits\HasApiTokens;

class User extends Model implements AuthorizableContract, AuthenticatableContract
{
    use Authorizable, Authenticatable, HasApiTokens;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dps_usuario';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'ID_USUARIO';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    public $username = "usuario";

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    protected $fillable = ['DETALLE', 'FECHA_ALTA', 'NOMBRE', 'USUARIO'/*, 'api_token'*/];

    /**
    * The attributes excluded from the model's JSON form.
    *
    * @var array
    */
    protected $hidden = array('PASSWORD');

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'FECHA_ALTA' => 'datetime',
        //'api_token_created_at' => 'datetime'
    ];

    public function getAuthIdentifier() {
        return 'ID_USUARIO';
    }

    public function getAuthIdentifierName() {
        return 'usuario';
    }

    /**
     * Get the "usuario_cuestionarios" for the "usuario".
     */
    public function usuarioCuestionarios()
    {
        return $this->hasMany('App\Models\UsuarioCuestionario', 'ID_USUARIO');
    }
}
