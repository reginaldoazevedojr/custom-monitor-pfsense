<?php
require("guiconfig.inc");


require_once("functions.inc");
require_once("filter.inc");
require_once("shaper.inc");



$pgtitle = array(gettext("Firewall"), gettext("Custom Rules"));
$shortcut_section = "Custom Rules";

$closehead = false;

$page_filename = "firewall_cutom_rules.php";
include("head.inc");

$config['revision'] = make_config_revision_entry("Unknown");
$xmlconfig = dump_xml_config($config, $g['xml_rootobj']);
$config = parse_xml_config("{$g['conf_path']}/config.xml", $g['xml_rootobj']);
$rule = $config["filter"]['rule'];
$registered = "";

if(isset($config['custom_monitor'])){
    if($config['custom_monitor'] != ""){
        $i = 0;
        foreach($config['custom_monitor'] as $key=>$custom_monitor){
            $name = str_replace("DEST_", "", strtoupper($key));     
            $registered .= ' <div id="group_'. $i .'"><p><b>'.strtoupper($name).'</b><button style="margin-left: 705px;" title="'.$name[1].'" class="closeTracking">Close</button></p><div style="border: 1px gray solid;padding: 10px;">';
            foreach ($custom_monitor as $index=>$link){
                $registered .= '<p>
                                    <label>'.strtoupper($index).'</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>Remote IP</label>&nbsp;<input type="text" name="group[dest_'.$name.']['.$index.']" value="'.$link.'" />
                                  </p>';
            }
            $registered .= '</div></div>';
        }                
    }
}

if (isset($_POST['groupTracking'])) {
    $config['custom_monitor'] = "";
    if(isset($_POST['group'])){
        if (count($_POST['group']) != 0) {
            $group = $_POST['group'];
            foreach ($group as $key => $value) {
                $config['custom_monitor'][$key] = $value;
            }
        }
    }
    system("/etc/rc.custom_monitor stop > /dev/null &");
    write_config();
    $retval = 0;
    $retval = filter_configure();
    clear_subsystem_dirty('filter');
    pfSense_handle_custom_code("/usr/local/pkg/firewall_rules/apply");
    header("location: firewall_custom_rules.php");
    system("/etc/rc.custom_monitor start > /dev/null &");
}

?>
<link rel="stylesheet" href="/javascript/chosen/chosen.css" />
</head>

<body link="#0000CC" vlink="#0000CC" alink="#0000CC">
    <script type="text/javascript" src="/javascript/jquery.ipv4v6ify.js"></script>
    <script src="/javascript/chosen/chosen.jquery.js" type="text/javascript"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript" src="javascript/custom_monitor/add_destination.js"></script>
    <?php include("fbegin.inc"); ?>
    <?php pfSense_handle_custom_code("/usr/local/pkg/firewall_rules/pre_input_errors"); ?>
    <?php if ($input_errors) print_input_errors($input_errors); ?>
    <div>
        <h2>Load Balance - Custom Monitor</h2>
        <form method="post" action="firewall_custom_rules.php">
            <div>
                <div class="boxDestination">
                    <br />
                    <label>Destination: </label>&nbsp;&nbsp;
                    <select class='groupTracking' name='groupTracking' style="width: 150px; height: 22px;">
                        <option value="empty" selected="selected"></option>
                        <?php
                        foreach ($rule as $dados) {
                            $destination = $dados['destination']['address'];
                            if (in_array($destination, $tracking)) {
                                continue;
                            }
                            if ($dados['interface'] == 'lan') {
                                echo "<option value ='" . $destination . "'>" . strtoupper(($destination == "") ? "any" : $destination) . "</option>";
                            }
                        }
                        ?> 
                    </select>
                    &nbsp;&nbsp;
                    <button class="addTracking" style="height: 25px;">Add Tracking</button>
                    <br /><br /><br />
                    <?php echo $registered; ?>
                </div>                                
            </div>
            <div>
                <p style="text-align: center;">
                    <input type="submit" value="Save" class="formbtn" />
                </p>
            </div>
        </form>
    </div>
    <?php include("fend.inc"); ?>
</body>
</html>
