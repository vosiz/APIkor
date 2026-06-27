<?php

namespace Apikor\Response;

use Vosiz\VaTools\Retval;

class Payload {

    private $Type;  public function GetType() { return $this->Type; }
    private $Data;  public function GetData() { return $this->Data; }

    /**
     * Creates plain text payload
     * @param string $text
     * @return PlainPayload
     */
    public static function CreatePlain(string $text) {

        return new PlainPayload($text);
    }

    /**
     * Creates binary payload
     * @param mixed $data
     * @return BinaryPayload
     */
    public static function CreateBinary($data) {

        return new BinaryPayload($data);
    }

    /**
     * Creates retval payload
     * @param Retval $retval
     * @return RetvalPayload
     */
    public static function CreateRetval(Retval $retval) {

        return new RetvalPayload($retval);
    }

    /**
     * Creates custom payload
     * @param mixed $data
     * @return CustomPayload
     */
    public static function CreateCustom($data) {

        return new CustomPayload($data);
    }

    /**
     * Creates debug payload
     * @param mixed $data
     * @return DebugPayload
     */
    public static function CreateDebug($data) {

        return new DebugPayload($data);
    }

    /**
     * Creates associative array payload
     * @param array $arr
     * @return ArrayPayload
     */
    public static function CreateArray(array $arr) {

        return new ArrayPayload($arr);
    }

    /**
     * Creates single model payload
     * @param mixed $model
     * @return ModelPayload
     */
    public static function CreateModel($model) {

        return new ModelPayload($model);
    }

    /**
     * Creates model collection payload
     * @param array $models
     * @return ModelsPayload
     */
    public static function CreateModels(array $models) {

        return new ModelsPayload($models);
    }

    /**
     * Constructor
     * @param PayloadTypeEnum $type
     * @param mixed $data
     */
    protected function __construct(PayloadTypeEnum $type, $data) {

        $this->Type = $type;
        $this->Data = $data;
    }

}
