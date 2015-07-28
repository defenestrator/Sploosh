<?php


namespace Sploosh;

use Carbon\Carbon;

/**
 * Class StreamFlow
 * @package Sploosh
 */
class TimeFrame extends Sploosh
{
    /**
     *
     * @return $this
     */
    public function getLastHour()
    {
        $this->addParams(
            [
                'format' => 'json,1.1',
                'starttime' => Carbon::now('UTC')->subHour(),
                'endtime' => Carbon::now('UTC')
            ]
        );

        return $this->getFlows();
    }

    /**
     *
     * @return $this
     */
    public function getLastTwentyFour()
    {
        $this->addParams(
            [
                'format' => 'json,1.1',
                'starttime' => Carbon::now('UTC')->subDay(),
                'endtime' => Carbon::now('UTC')
            ]
        );

        return $this->getFlows();
    }

    /**
     *
     * @return $this
     */
    public function getLastThirtyDays()
    {
        $this->addParams(
            [
                'format' => 'json,1.1',
                'starttime' => Carbon::now('UTC')->subDays(30),
                'endtime' => Carbon::now('UTC')
            ]
        );

        return $this->getFlows();
    }

    /**
     * @param string $action
     * @return $this
     */
    public function getFlows($action = 'query')
    {
        $this->setAction($action);

        return $this->load();
    }
}
