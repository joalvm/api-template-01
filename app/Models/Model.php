<?php

namespace App\Models;

use Illuminate\Support\Facades\Config;
use Joalvm\Utils\Model as UtilsModel;

class Model extends UtilsModel
{
    protected ?int $userId = null;

    public function __construct(array $attributes = [])
    {
        $this->setUser(Config::get('user.id'));

        parent::__construct($attributes);
    }

    /**
     * Establece el Id del usuario, este valor es usado para auditor.
     */
    public function setUser(?int $userId)
    {
        $this->userId = to_int($userId);

        return $this;
    }
}
