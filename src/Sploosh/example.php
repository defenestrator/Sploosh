<?php

// Example script that returns the most recent real-time discharge readings for all stream sites in Rhode Island
// Uses the services documented at http://waterdata.usgs.gov
// Written by Mark D. Hamill, mdhamill@usgs.gov, 2 June 2010
//
// Note: this is a simple example only. The USGS recommends tools such as curl and wget to retrieve data.

$url = 'http://waterservices.usgs.gov/nwis/iv/?format=waterml&stateCd=id&parameterCd=00060&siteType=ST';

// Fetch the data.
$data = file_get_contents($url);
if (!$data)
{
    echo 'Error retrieving: ' . $url;
    exit;
}

// Remove the namespace prefix for easier parsing
$data = str_replace('ns1:','', $data);

// Load the XML returned into an object for easy parsing
$xml_tree = simplexml_load_string($data);
if ($xml_tree === FALSE)
{
    echo 'Unable to parse USGS\'s XML';
    exit;
}

// The page should not refetch the data more than every fifteen minutes. Hourly is ideal.
// Calculate an offset of 15 minutes
$offset = 3600 * .25;
// Calculate the string in GMT not localtime and add the offset
$expire = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
//output the HTTP header
Header($expire);

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <title>Current USGS streamflow data for Rhode Island</title>
</head>
<body>
<h1>Current USGS streamflow data for Rhode Island</h1>
<p>Retrieved at <?php echo date('H:i:s \o\n M j, Y', time());?></p>
<p>Be kind to USGS servers. Learn about <a href="http://waterservices.usgs.gov/rest/IV-Service.html#modifiedSince">modifiedSince</a> if you need to make frequent queries.</p>
<table border="1">
    <tr>
        <th id="c1">Site Number</th>
        <th id="c2">Site Name</th>
        <th id="c3">Date</th>
        <th id="c4">Time</th>
        <th id="c5">Time Zone</th>
        <th id="c6">Streamflow ft<sup>3</sup>/sec</th>
        <th id="c7">Provisional</th>
    </tr>
    <?php
    foreach ($xml_tree->timeSeries as $site_data)
    {

        $provisional = ($site_data->values->value['qualifiers'] == 'P') ? 'Yes' : '-';

        if ($site_data->values->value['dateTime'] <> '')
        {
            $date = substr($site_data->values->value['dateTime'],0,10);
            $time = substr($site_data->values->value['dateTime'],11,5);
            $tz = substr($site_data->values->value['dateTime'],23);
            $display_date_time = $date . ' ' . $time . ' ' . $tz;
        }
        else
        {
            $display_date_time = '-';
        }

        if ($site_data->values->value == '')
        {
            $value = '-';
        }
        else if ($site_data->values->value == -999999)
        {
            $value = 'UNKNOWN';
            $provisional = '-';
        }
        else
        {
            $value = number_format((float) $site_data->values->value);
        }

        echo "<tr>\n";
        printf("<td headers=\"c1\" style=\"text-align:center\">%s</td>\n", $site_data->sourceInfo->siteCode);
        printf("<td headers=\"c2\">%s</td>\n", $site_data->sourceInfo->siteName);
        printf("<td headers=\"c3\">%s</td>\n", $date);
        printf("<td headers=\"c4\">%s</td>\n", $time);
        printf("<td headers=\"c5\" style=\"text-align:center\">%s</td>\n", $tz);
        printf("<td headers=\"c6\" style=\"text-align:right\">%s</td>\n", $value);
        printf("<td>%s</td>\n", $provisional);
        echo "</tr>\n";
    }
    ?>
</table>
<p>
    <a href="http://validator.w3.org/check?uri=referer"><img
            src="http://www.w3.org/Icons/valid-xhtml10"
            alt="Valid XHTML 1.0 Strict" height="31" width="88" /></a>
</p>
</body>
</html>