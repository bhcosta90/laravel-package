<?php


namespace App\Services;


use App\Repositories\Contracts\UserContract;
use App\Repositories\UserRepository;
use App\Services\Contracts\ApiContract;
use App\Services\Contracts\WebContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService implements WebContract, ApiContract
{
    private UserRepository $repository;
    private Request $request;

    /**
     * UserService constructor.
     * @param UserContract $repository
     * @param Request $request
     */
    public function __construct(UserContract $repository, Request $request)
    {
        /**
         * @var $repository UserRepository
         */
        $this->repository = $repository;
        $this->request = $request;
    }

    /**
     * @param $filter
     * @return LengthAwarePaginator
     */
    public function apiIndex($filter): LengthAwarePaginator
    {
        return $this->index($filter)->paginate();
    }

    private function index($filter)
    {
        $data = $this->repository->orderBy('name', 'asc');
        if (isset($filter['name'])) {
            $data = $data->where('name', $filter['name']);
        }

        if (isset($filter['email'])) {
            $data = $data->where('email', $filter['email']);
        }

        return $data;
    }

    public function webIndex($filter): array
    {
        return [
            'data' => $this->index($filter)->paginate(),
            'filter' => $filter,
        ];
    }

    public function find($id)
    {
        return $this->repository->getById($id);
    }

    public function webDestroy($id)
    {
        $this->repository->deleteById($id);
        return redirect()->route('admin.users.users.index')
            ->withSuccess(__('Usuário deletado com sucesso'));
    }

    public function webUpdate($id, $data)
    {
        $this->repository->updateById($id, $data);
        return redirect()->route('admin.users.users.index')
            ->withSuccess(__('Usuário editado com sucesso'));
    }

    public function webStore($data)
    {
        $this->addPasswordInArray($data);
        $this->repository->create($data);
        return redirect()->route('admin.users.users.index')
            ->withSuccess(__('Usuário cadastrado com sucesso e a senha do usuário é: <b>:password</b>', [
                'password' => $data['password_old'],
            ]));
    }

    private function addPasswordInArray(&$data)
    {
        $data['password'] = Hash::make($password = Str::random(10));
        $data['password_old'] = $password;
        return $data;
    }

    public function apiStore($data)
    {
        $this->addPasswordInArray($data);
        $this->request->request->add(['password' => $data['password_old']]);
        return $this->repository->create($data);
    }

    public function apiUpdate($id, $data)
    {
        $this->repository->updateById($id, $data);
    }

    public function apiDestroy($id)
    {
        $this->repository->deleteById($id);
    }
}
