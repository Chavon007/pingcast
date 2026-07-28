<?php

namespace Modules\Weather\App\Repositories;

use Modules\Weather\App\Interfaces\SubscriptionRepositoryInterface;
use Modules\Weather\App\Models\Subscription;


class SubscriptionRepository implements SubscriptionRepositoryInterface
{
    protected Subscription $model;

    public function __construct(Subscription $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

  
    public function updatePlatformHandle(string $subscriptionId, string $chatId)
    {
        $subscription = $this->model->find($subscriptionId);

        if(!$subscription){
            return null;
        }
        $subscription->update([
            "platformHandle" => $chatId,
        ]);

       return $subscription;
    }
}