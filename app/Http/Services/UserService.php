<?php 
namespace App\Http\Services;
use App\Http\Repositories\Interfaces\UserRepositoryInterface;

class UserService{
    private UserRepositoryInterface $repo;
    public function __construct(UserRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function create($data =[]){
        return $this->repo->create($data);

    }
}
