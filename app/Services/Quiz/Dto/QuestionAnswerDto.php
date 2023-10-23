<?php

namespace App\Services\MasterMechanics\Dto;

use App\Services\MasterMechanics\Slabs\QuestionAnswerSlab;
use Illuminate\Contracts\Support\Arrayable;

class QuestionAnswerDto
{

    /**
     * @param array<QuestionAnswerSlab> $slabs
     */
    public function __construct(public readonly array $slabs,)
    {

    }

    public function getKeys()
    {
        return array_map(function ($slab) {
            return $slab->key;
        }, $this->slabs);
    }

    public function getValues()
    {
        return array_map(function ($slab) {
            return $slab->value;
        }, $this->slabs);
    }
}
