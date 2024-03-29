<?php

namespace Controllers;


use App\Security;
use Attributes\DefaultEntity;
use Attributes\TargetEntity;
use Attributes\TargetRepository;
use Attributes\UsesEntity;

abstract class AbstractController
{

    protected $usesEntity = true;


    protected $repository;

    public function __construct()
    {


            $this->usesEntity =$this->resolveUsesEntity();


        if($this->usesEntity){
            $this->repository = $this->getRepository($this->resolveDefaultEntityName());

        }
    }

    protected function resolveUsesEntity(){
        $reflect = new \ReflectionClass($this);  //$attributes

        $attributes = $reflect->getAttributes(UsesEntity::class);

        if(!empty($attributes)){
            return $attributes[0]->getArguments()["value"];

        }else{
            return true;
        }
    }

    protected function resolveDefaultEntityName(){
        $reflect = new \ReflectionClass($this);  //$attributes

        $attributes = $reflect->getAttributes(DefaultEntity::class);

        return $attributes[0]->getArguments()["entityName"];
    }

    protected function getRepository($entityName){

        $reflect = new \ReflectionClass($entityName);  //$attributes

        $attributes = $reflect->getAttributes(TargetRepository::class);

        $repoName = $attributes[0]->getArguments()["repositoryName"];

        return new $repoName();

    }


    public function render($template, $data){
        return \App\View::render($template, $data);
    }
    public function redirect(? array $params=null){
        return \App\Response::redirect($params);
    }

    public function getUser(){
        return \App\Session::getUser();
    }
    public function get(string $dataType, array $requestBodyParams){
        return \App\Request::get($dataType,$requestBodyParams);
    }

    public function post(string $dataType, array $requestBodyParams){
        return \App\Request::post($dataType,$requestBodyParams);
    }
    public function delete(string $dataType, array $requestBodyParams){
        return \App\Request::delete($dataType,$requestBodyParams);
    }
    public function put(string $dataType, array $requestBodyParams){
        return \App\Request::put($dataType,$requestBodyParams);
    }
    public function json($trucARenvoyerAuClient,?string $methodSpe = null){
        return \App\Response::json($trucARenvoyerAuClient, $methodSpe);
    }

}