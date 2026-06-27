<?php

namespace Apikor\Response;

use Apikor\Tools\UrlParser;
use Apikor\Core\Models\Model;
use Vosiz\VaTools\Retval;

class ResponseFactory {

    /**
     * Creates 200 OK response
     * @param mixed $data Action result
     * @param UrlParser|null $parser
     * @param int $uid Authenticated user ID
     * @return Response
     */
    public static function Ok($data, ?UrlParser $parser = null, int $uid = 0): Response {

        $payload = self::ResolvePayload($data);
        $header  = new Header($payload->GetType(), Header::HTTP_OK, $parser, $uid);
        return Response::Create($header, $payload);
    }

    /**
     * Creates 400 Bad Request response
     * @param string $msg
     * @param UrlParser|null $parser
     * @return Response
     */
    public static function BadRequest(string $msg, ?UrlParser $parser = null): Response {

        $payload = Payload::CreateRetval(Retval::Fail($msg));
        $header  = new Header($payload->GetType(), Header::HTTP_BAD_REQUEST, $parser);
        return Response::Create($header, $payload);
    }

    /**
     * Creates 404 Not Found response
     * @param string $msg
     * @param UrlParser|null $parser
     * @return Response
     */
    public static function NotFound(string $msg, ?UrlParser $parser = null): Response {

        $payload = Payload::CreateRetval(Retval::Fail($msg));
        $header  = new Header($payload->GetType(), Header::HTTP_NOT_FOUND, $parser);
        return Response::Create($header, $payload);
    }

    /**
     * Creates 403 Forbidden response
     * @param string $msg
     * @param UrlParser|null $parser
     * @param int $uid
     * @return Response
     */
    public static function Forbidden(string $msg, ?UrlParser $parser = null, int $uid = 0): Response {

        $payload = Payload::CreateRetval(Retval::Error($msg));
        $header  = new Header($payload->GetType(), Header::HTTP_FORBIDDEN, $parser, $uid);
        return Response::Create($header, $payload);
    }

    /**
     * Creates 500 Internal Server Error response
     * @param \Exception $exc
     * @param UrlParser|null $parser
     * @return Response
     */
    public static function Error(\Exception $exc, ?UrlParser $parser = null): Response {

        $payload = Payload::CreateRetval(Retval::Exception($exc->getMessage()));
        $header  = new Header($payload->GetType(), Header::HTTP_ERROR, $parser);
        return Response::Create($header, $payload);
    }

    /**
     * Resolves action result to appropriate payload
     * @param mixed $data
     * @return Payload
     */
    public static function ResolvePayload($data): Payload {

        if($data instanceof Payload)
            return $data;

        if(is_string($data))
            return Payload::CreatePlain($data);

        if($data instanceof Model)
            return Payload::CreateModel($data);

        if($data instanceof Retval)
            return Payload::CreateRetval($data);

        if(is_array($data)) {

            $first = reset($data);
            if($first instanceof Model)
                return Payload::CreateModels($data);

            return Payload::CreateArray($data);
        }

        return Payload::CreateCustom($data);
    }

}
