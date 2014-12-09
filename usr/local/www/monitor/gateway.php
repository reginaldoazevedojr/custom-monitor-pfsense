<?php


require("gwlb.inc");
require("interfaces.inc");
require_once("functions.inc");
require_once("filter.inc");
require_once("shaper.inc");

//require("config.lib.inc");

global $config, $g;
$gateway = return_gateways_array();
$config['revision'] = make_config_revision_entry("Unknown");
$xmlconfig = dump_xml_config($config, $g['xml_rootobj']);
$config = parse_xml_config("{$g['conf_path']}/config.xml", $g['xml_rootobj']);
$config["filter"]["rule"][2]["gateway"] = "Teste";
write_config();
$retval = 0;
$retval = filter_configure();
clear_subsystem_dirty('filter');
pfSense_handle_custom_code("/usr/local/pkg/firewall_rules/apply");

//var_dump();
