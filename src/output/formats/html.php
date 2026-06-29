<?php

namespace Apikor\Output;

use VaTools\Format\HtmlBuilder;
use Apikor\Response\Response;
use Apikor\Response\Header;

class HtmlFormat extends Formatter {

    /**
     * Formats response as full HTML document
     * @param Response $response
     * @return string
     */
    public function Format(Response $response) {

        $html = new HtmlBuilder();
        $h = $response->GetHeader();

        $meta = HtmlBuilder::CreateElement('meta');
        $meta->AddAtts(['charset' => 'utf-8']);
        $html->AddToHead($meta);
        HtmlBuilder::CreateElement('title', 'Response '.$h->GetCode())->SetParent($html->GetHead());

        $html->AddToBody(HtmlBuilder::H(1, 'Response'));

        $html->AddToBody(HtmlBuilder::H(2, 'Header'));
        $html->AddToBody($this->RenderHeader($h));

        $html->AddToBody(HtmlBuilder::H(2, 'Payload'));
        $html->AddToBody($this->RenderData($response->GetPayload()->GetData(), 3));

        return $html->Render();
    }


    /**
     * Renders response header as ul
     * @param Header $h
     * @return VaTools\Format\Html\Elements\HtmlUnorderedList
     */
    private function RenderHeader(Header $h) {

        $ul = HtmlBuilder::Ul();
        $ul->AddItem('guid: '.$h->GetGuid());
        $ul->AddItem('version: '.$h->GetVersion());
        $ul->AddItem('code: '.$h->GetCode());
        $ul->AddItem('timestamp: '.$h->GetTimestamp());
        $ul->AddItem('type: '.$h->GetType()->GetKey());
        $ul->AddItem('uid: '.$h->GetUid());
        $ul->AddItem('client: '.$h->GetClient());

        $req_li = HtmlBuilder::Li('request:');
        $this->RenderData($h->GetRequest(), 4)->SetParent($req_li);
        $ul->SetLast($req_li);

        return $ul;
    }

    /**
     * Recursively renders a value as HTML
     * — scalar/null → p
     * — array       → ul (nested if values are non-scalar)
     * — object      → div with heading + properties
     * @param mixed $data
     * @param int $level Current heading level
     * @return VaTools\Format\Html\HtmlElement
     */
    private function RenderData($data, int $level = 3) {

        if(is_null($data) || is_scalar($data))
            return HtmlBuilder::P(is_null($data) ? 'null' : (string)$data);

        if(is_array($data)) {

            $ul = HtmlBuilder::Ul();

            foreach($data as $key => $val) {

                if(is_null($val) || is_scalar($val)) {

                    $text = is_int($key)
                        ? (is_null($val) ? 'null' : (string)$val)
                        : "$key: ".(is_null($val) ? 'null' : (string)$val);

                    $ul->AddItem($text);

                } else {

                    $prefix = is_int($key) ? '' : "$key:";
                    $li = HtmlBuilder::Li($prefix);
                    $this->RenderData($val, $level)->SetParent($li);
                    $ul->SetLast($li);
                }
            }

            return $ul;
        }

        if(is_object($data)) {

            $div = HtmlBuilder::Div();
            HtmlBuilder::H(min(6, $level), get_class($data))->SetParent($div);

            $props = get_object_vars($data);
            if(!empty($props))
                $this->RenderData($props, $level + 1)->SetParent($div);

            return $div;
        }

        return HtmlBuilder::P(print_r($data, true));
    }
}
