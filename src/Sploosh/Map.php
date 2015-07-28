<?php namespace Sploosh;

/**
 * Class Map
 * @package Sploosh
 */
Class Map extends Sploosh
{
    /**
     * @param $riverData
     * @param string $id
     * @param string $class
     * @param int $width
     * @param int $height
     * @param string $maptype
     * @param bool $echo
     * @return string
     */
    function usgs_display_map($riverData, $id = 'riverDataMap', $class = 'riverMap', $width = 350, $height = 220, $maptype = 'terrain', $echo = true)
    {
        $baseurl = 'http://maps.googleapis.com/maps/api/staticmap?' . 'key=' . env('GOOGLE_PUBLIC_API_KEY');

//CREATE THE URL PARAMETERS
        $parameters = '';
        $parameters .= '&size=' . $width . 'x' . $height;
        $parameters .= '&format=jpg';
        $parameters .= '&maptype=' . $maptype;

//DEFINE MARKERS
        $i = 1;
        foreach ($riverData as $marker) {
            $parameters .= '&markers=color:green|size:mid|label:' . $i . '|';
            $parameters .= $marker['latitude'] . ',' . $marker['longitude'];
//if ( $i < count($riverData) ) $parameters .= '|';
            $i++;
        }

//SENSOR
        $parameters .= '&sensor=false';

        $mapurl = $baseurl . utf8_encode($parameters);

        if ($echo) {
            echo '<img src="' . $mapurl . '" width="' . $width . '" height="' . $height . '" id="' . $id . '" class="' . $class . '" />';
        } else {
            return $mapurl;
        }

    }
}