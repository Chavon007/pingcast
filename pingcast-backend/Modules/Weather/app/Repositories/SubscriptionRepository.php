<?php

namespace Modules\Weather\App\Repositories;

use Modules\Weather\App\Interfaces\SubscriptionRepositoryInterface;
use Modules\Weather\Models\Subscription;

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
}