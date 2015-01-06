<?php
require_once("config.inc");
require_once("globals.inc");
require_once("notices.inc");
require_once("functions.inc");
require_once("filter.inc");
require_once("shaper.inc");

$config['revision'] = make_config_revision_entry("Unknown");
$xmlconfig = dump_xml_config($config, $g['xml_rootobj']);
$config = parse_xml_config("{$g['conf_path']}/config.xml", $g['xml_rootobj']);

$nmServer = $config['system']['hostname'] . '.' . $config['system']['domain'];
$mens = $nmServer . " Informa: \n\n";

if(isset($_GET['online'])){
    send_smtp_message($mens . $_GET['online'], "Status Links Pfsense");
}

if(isset($_GET['offline'])){
    send_smtp_message($mens . $_GET['offline'], "Status Links Pfsense");
}
