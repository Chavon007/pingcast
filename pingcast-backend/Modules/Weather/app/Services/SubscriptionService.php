<?php

namespace Modules\Weather\App\Services;


use Modules\Weather\App\Interfaces\SubscriptionRepositoryInterface;

class SubscriptionService {

public function __construct( 
    protected SubscriptionRepositoryInterface $subscriptionRepository
){

}
public function create(array $data){
    return $this->subscriptionRepository->create($data);
}
}