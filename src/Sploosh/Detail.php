<?php


namespace Sploosh;


class Detail extends Sploosh
{
    public function getDetail($id)
    {
        $this->addParams(
            [
                'format' => 'json,1.1',
                'eventid' => $id,
            ]
        );

        $this->setAction('query');

        return $this->load();
    }
}
