<?php

namespace Apikor\Response;

use Vosiz\VaTools\Retval;

class ExceptionPayload extends Payload {

    /**
     * Constructor
     * @param array $data Serialized exception chain
     */
    public function __construct(array $data) {

        parent::__construct(PayloadTypeEnum::GetEnum('exception'), $data);
    }

}

class PlainPayload extends Payload {

    /**
     * Constructor
     * @param string $text Plain text
     */
    public function __construct(string $text) {

        parent::__construct(PayloadTypeEnum::GetEnum('plain'), $text);
    }

}

class BinaryPayload extends Payload {

    /**
     * Constructor
     * @param mixed $data Binary data
     */
    public function __construct($data) {

        parent::__construct(PayloadTypeEnum::GetEnum('binary'), $data);
    }

}

class RetvalPayload extends Payload {

    /**
     * Constructor
     * @param Retval $retval
     */
    public function __construct(Retval $retval) {

        parent::__construct(PayloadTypeEnum::GetEnum('retval'), $retval);
    }

}

class CustomPayload extends Payload {

    /**
     * Constructor
     * @param mixed $data Custom data structure
     */
    public function __construct($data) {

        parent::__construct(PayloadTypeEnum::GetEnum('custom'), $data);
    }

}

class DebugPayload extends Payload {

    /**
     * Constructor
     * @param mixed $data Debug info
     */
    public function __construct($data) {

        parent::__construct(PayloadTypeEnum::GetEnum('debug'), $data);
    }

}

class ArrayPayload extends Payload {

    /**
     * Constructor
     * @param array $arr Associative array of primitives
     */
    public function __construct(array $arr) {

        parent::__construct(PayloadTypeEnum::GetEnum('array'), $arr);
    }

}

class ModelPayload extends Payload {

    /**
     * Constructor
     * @param mixed $model Single model instance
     */
    public function __construct($model) {

        parent::__construct(PayloadTypeEnum::GetEnum('model'), $model);
    }

}

class ModelsPayload extends Payload {

    /**
     * Constructor
     * @param array $models Collection of model instances
     */
    public function __construct(array $models) {

        parent::__construct(PayloadTypeEnum::GetEnum('models'), $models);
    }

}
