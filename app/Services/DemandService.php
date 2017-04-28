<?php
/**
 * Created by PhpStorm.
 * User: João Marcus
 * Date: 26/04/2017
 * Time: 11:42
 */

namespace Mentor\Services;


use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mentor\Repositories\ActRepositoryEloquent;
use Mentor\Repositories\DemandRepositoryEloquent;
use Mentor\Repositories\PerfomanceRepositoryEloquent;
use Mentor\Repositories\UserRepositoryEloquent;


class DemandService
{

    /**
     * @var DemandRepository
     */
    private $demandRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var PerfomanceRepositoryEloquent
     */
    private $perfomanceRepositoryEloquent;
    /**
     * @var ActRepositoryEloquent
     */
    private $actRepositoryEloquent;

    public function __construct(DemandRepositoryEloquent $demandRepository,
                                UserRepositoryEloquent $userRepository, ActRepositoryEloquent $actRepositoryEloquent)
    {

        $this->demandRepository = $demandRepository;
        $this->userRepository = $userRepository;
        $this->actRepositoryEloquent = $actRepositoryEloquent;
    }

    public function myDemands()
    {
        //Quem tá logado?
        if ($this->getMyAuth()):
            $Who = Auth::user();
            try {
                //Pego as demandas usando o _id do cara logado
                if(Auth::user()->roles == 1):
                    $demand = $this->demandRepository->findByField('user_id', $Who->id);
                else:
                    $demand = $this->demandRepository->findByField('mentor', $Who->id);
                endif;

                return $demand;
            } catch (QueryException $q) {
                $q->getMessage();
            }
        endif;

    }

    public function myDemandsCreate(array $data)
    {

        //Criando a demanda e selecionar o mentor vs qtd

        //Ativa os filtros ??
        $Mentor = $this->_filterIsLowerUserDemands();
        //Formata meu builder
        $idMentor = $this->formatMyBuildQuery($Mentor);

        try {

            //Atualizando a qtd do mentor
            $selectMentor = $this->userRepository->find($idMentor);
            $selectMentor->qtd = $selectMentor->qtd + 1;
            $selectMentor->save();

            $d = $this->demandRepository->create([
                'title' => $data['title'],
                'subject' => $data['subject'],
                'doubt' => $data['doubt'],
                'student' => $data['student'],
                'email' => $data['email'],
                'mentor' => $selectMentor->id,
                'user_id' => $this->getMyUserById()
            ]);

            $this->actRepositoryEloquent->create([
                'area' => $data['area'],
                'demand_id' => $d->id
            ]);



        } catch (QueryException $exception) {
            $exception->getMessage();
        }

    }

    private function getMyAuth()
    {
        return Auth::check();
    }

    private function getMyUserById()
    {
        if ($this->getMyAuth()):
            return Auth::user()->id;
        endif;
    }

    protected function _filterJoinUserInDemands()
    {
        return $HasDemand = DB::table('users')
                ->join('demands', 'users.id', '=', 'demands.user_id')
                ->select('users.*', 'demands.*')
                ->get();

    }

    protected function _filterHasUserDemandsByQtd()
    {
        return $HasQtd = DB::table('users')
                ->select('*')
                ->where('qtd', '>=', 1)
                ->groupBy('id')
                ->get();
    }

    protected function _filterIsLowerUserDemands()
    {
        try {
            //FIXME E se não tiver o usuário com determiada qtd?
            $verifyUserQtd = $users = DB::table('users')->count();
            if($verifyUserQtd):
                return DB::table('users')
                    ->where('qtd', DB::raw("(select min(qtd) from users where roles = 2)"))
                    ->get();
            endif;
        } catch(QueryException $exception) {
            $exception->getMessage();
        }
    }

    private function formatMyBuildQuery(array $stdClass)
    {
        foreach ($stdClass as $myItems)
        {
            return $myItems->id;
        }
    }
}