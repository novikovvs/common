<?php

namespace Novikovvs\Common\Factories;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Novikovvs\Common\DTOs\AbstractDTO;

interface FactoryInterface
{
    public static function fromRequest(Request $request): AbstractDTO;

    public static function fromRequestWithFiles(Request $request): AbstractDTO;

    public static function fromRequestValidated(Request $request): AbstractDTO;

    public static function fromRequestValidatedWithFiles(Request $request): AbstractDTO;

    public static function fromArray(array $array): AbstractDTO;

    public static function fromCollection(Collection $collection): AbstractDTO;
}
