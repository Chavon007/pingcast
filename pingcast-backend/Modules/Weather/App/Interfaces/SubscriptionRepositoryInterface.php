<?php

namespace Modules\Weather\App\Interfaces;

interface SubscriptionRepositoryInterface{

public function create(array $data);

public function updatePlatformHandle(string $subscriptionId, string $chatId);
}
