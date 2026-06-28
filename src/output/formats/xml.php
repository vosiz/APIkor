<?php

namespace Apikor\Output;

use Apikor\Response\Response;
use VaTools\Format\XmlBuilder;

class XmlFormat extends Formatter {

    /**
     * Formats response as XML
     * @param Response $response
     * @return string
     */
    public function Format(Response $response) {

        $root    = XmlBuilder::CreateRoot('response');
        $header  = XmlBuilder::CreateElement('header');
        $payload = XmlBuilder::CreateElement('payload');

        $h = $response->GetHeader();
        XmlBuilder::CreateElement('guid',      $h->GetGuid()            )->SetParent($header);
        XmlBuilder::CreateElement('version',   (string)$h->GetVersion() )->SetParent($header);
        XmlBuilder::CreateElement('code',      (string)$h->GetCode()    )->SetParent($header);
        XmlBuilder::CreateElement('timestamp', (string)$h->GetTimestamp())->SetParent($header);
        XmlBuilder::CreateElement('type',      $h->GetType()->GetKey()  )->SetParent($header);
        XmlBuilder::CreateElement('uid',       (string)$h->GetUid()     )->SetParent($header);
        XmlBuilder::CreateElement('client',    (string)$h->GetClient()  )->SetParent($header);

        XmlBuilder::CreateElement('data', print_r($response->GetPayload()->GetData(), true))->SetParent($payload);

        $header->SetParent($root);
        $payload->SetParent($root);

        $builder = new XmlBuilder();
        $builder->SetRoot($root);

        return $builder->Render();
    }

}
