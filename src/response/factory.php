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
     * @param \Exception $exc
     * @param UrlParser|null $parser
     * @return Response
     */
    public static function BadRequest(\Exception $exc, ?UrlParser $parser = null): Response {

        $payload = Payload::CreateException(self::ExcToArray($exc));
        $header  = new Header($payload->GetType(), Header::HTTP_BAD_REQUEST, $parser);
        return Response::Create($header, $payload);
    }

    /**
     * Creates 404 Not Found response
     * @param \Exception $exc
     * @param UrlParser|null $parser
     * @return Response
     */
    public static function NotFound(\Exception $exc, ?UrlParser $parser = null): Response {

        $payload = Payload::CreateException(self::ExcToArray($exc));
        $header  = new Header($payload->GetType(), Header::HTTP_NOT_FOUND, $parser);
        return Response::Create($header, $payload);
    }

    /**
     * Creates 403 Forbidden response
     * @param \Exception $exc
     * @param UrlParser|null $parser
     * @param int $uid
     * @return Response
     */
    public static function Forbidden(\Exception $exc, ?UrlParser $parser = null, int $uid = 0): Response {

        $payload = Payload::CreateException(self::ExcToArray($exc));
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

        $payload = Payload::CreateException(self::ExcToArray($exc));
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

    /**
     * Serializes exception chain to nested array
     * @param \Exception $exc
     * @return array
     */
    private static function ExcToArray(\Exception $exc): array {

        $data = ['message' => $exc->getMessage()];

        $inner = method_exists($exc, 'GetInner') ? $exc->GetInner() : null;
        if($inner !== null)
            $data['inner'] = self::ExcToArray($inner);

        return $data;
    }

}
