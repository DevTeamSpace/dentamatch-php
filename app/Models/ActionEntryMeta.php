<?php

namespace App\Models;

/**
 * App\Models\ActionEntryMeta
 *
 * @property int $id
 * @property int $category
 * @property int $type
 * @property int|null $user_id
 * @property string|null $data

 */
class ActionEntryMeta
{
    public $type;
    public $requestFields;
    public $responseFields;

    public function __construct($type, $requestFields, $responseFields ) {
        $this->type = $type;
        $this->requestFields = $requestFields;
        $this->responseFields = $responseFields;
    }

}
