<?php

namespace CodeShopping\Http\Controllers\Api;

use CodeShopping\Http\Requests\UserRequest;
use CodeShopping\Http\Resources\UserResource;
use CodeShopping\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use CodeShopping\Http\Controllers\Controller;

class UserController extends Controller
{
  /**
   * Apresenta a listagem dos usuários cadastrados.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $query = User::query();
    $query = $this->onlyTrashedIfRequested($query);
    $users = $query->paginate(10);
    return UserResource::collection($users);
  }


  /**
   * Armazena no banco de dados um novo usuário criado.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(UserRequest $request)
  {
    $requestData = $request->all();
    $user = new User();
    $user->name = $requestData['name'];
    $user->email = $requestData['email'];
    $user->password = bcrypt($requestData['password']);
    $user->save();
    $user->refresh();
    return response(new UserResource($user),200);
  }

  /**
   * Apresenta um usuário específico.
   *
   * @param  User  $user
   * @return \Illuminate\Http\Response
   */
  public function show(User $user)
  {
    return response(new UserResource($user),200);
  }

  /**
   * Atualiza um usuário específico no banco de dados.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  User  $user
   * @return \Illuminate\Http\Response
   */
  public function update(UserRequest $request, User $user)
  {
    $requestData = $request->all();
    $user->name = $requestData['name'];
    $user->email = $requestData['email'];
    $user->password = bcrypt($requestData['password']);
    $user->save();

    return response(new UserResource($user),200);
  }

  /**
   * Remove um usuário do banco de dados.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(User $user)
  {
    $user->delete();

    return response([],204);
  }

  private function  onlyTrashedIfRequested(Builder $query)
  {
    if (\Request::get('excluidos') == 1)
    {
      $query = $query->onlyTrashed();
    }

    return $query;
  }
}
