<?php

namespace CodeShopping\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

  /**
   * Método create deve ser chamado com um nome diferente para não entrar em 'loop'
   * Como criamos o overwrite de 'fill este método não necessário
   * @param array $attributes
   * @return \Illuminate\Database\Eloquent\Model|Authenticatable
   */
/*  public static function createCustom($attributes = array())
    {
      return parent::create($attributes);
    }
*/

  /**
   * Overwrite do método fill. O codigo que antes estava em 'createCustom' para tratamento da password foi retirado
   * de lá e passado para o método 'fill' pois este é invocado pelo 'create' para popular os valores das colunas
   * da respectiva tabela.
   * @param array $attributes
   * @return Authenticatable
   */
  public function fill(array $attributes)
    {
      !isset($attributes['password'])?:$attributes['password']=bcrypt($attributes['password']);
      return parent::fill($attributes);
    }

  /**
   * Get the identifier that will be stored in the subject claim of the JWT.
   *
   * @return mixed
   */
  public function getJWTIdentifier()
  {
    return $this->id;
  }

  /**
   * Return a key value array, containing any custom claims to be added to the JWT.
   *
   * @return array
   */
  public function getJWTCustomClaims()
  {
    return [
      'email' => $this->email,
      'name' => $this->name
    ];
  }
}

