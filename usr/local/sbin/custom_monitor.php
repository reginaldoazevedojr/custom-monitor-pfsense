<?php

require("gwlb.inc");
require("interfaces.inc");
require_once("functions.inc");
require_once("filter.inc");
require_once("shaper.inc");

if (
        isset($_GET['destination']) && isset($_GET['gateway']) &&
        isset($_GET['priority']) && isset($_GET['link'])
) {
    
    global $config, $g;
    $gateway = return_gateways_array();
    $config['revision'] = make_config_revision_entry("Unknown");
    $xmlconfig = dump_xml_config($config, $g['xml_rootobj']);
    $config = parse_xml_config("{$g['conf_path']}/config.xml", $g['xml_rootobj']);
    $custom_monitor = $config["custom_monitor"];
    
    if (is_array($custom_monitor)) {
        foreach ($custom_monitor as $key => $value) {
            $destination = explode("_", $key);
            if ($_GET['destination'] == $destination[1]) {
                $config = changePriority(
                        $config, $_GET['gateway'], $_GET['priority'], $_GET['link']
                );
                write_config();
                $retval = 0;
                $retval = filter_configure();
                clear_subsystem_dirty('filter');
                pfSense_handle_custom_code("/usr/local/pkg/firewall_rules/apply");
            }
        }
    }
}

function changePriority($config, $gateway, $priority, $link) {
    foreach ($config["gateways"]["gateway_group"] as $keyGate => $data_gateway) {
        if ($data_gateway['name'] == $gateway) {
            foreach ($data_gateway['item'] as $keyItem => $item) {
                $index = explode("|", $item);
                if ($index[0] == $link) {
                    $index[1] = $priority;
                    $config["gateways"]["gateway_group"][$keyGate]['item'][$keyItem] = implode("|", $index);
                }
            }
        }
    }
    return $config;
}
