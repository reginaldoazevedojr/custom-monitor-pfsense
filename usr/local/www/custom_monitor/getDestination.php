<?php

require_once("functions.inc");
require_once("filter.inc");
require_once("shaper.inc");

if (isset($_POST['tracking'])) {
    
    $tracking = $_POST['tracking'];
    $config['revision'] = make_config_revision_entry("Unknown");
    $xmlconfig = dump_xml_config($config, $g['xml_rootobj']);
    $config = parse_xml_config("{$g['conf_path']}/config.xml", $g['xml_rootobj']);
    $rule = $config["filter"]['rule'];
    foreach ($rule as $dados) {
        $destination = $dados['destination']['address'];        
        if ($dados['interface'] == 'lan') {                        
            if($dados['destination']['address'] == $tracking){
                $loadBalance =  $dados['gateway'];
                foreach ($config["gateways"]["gateway_group"] as $gateway){
                    if($gateway['name'] == $loadBalance){
                        $itens = $gateway['item'];                        
                        echo "<div id='group_".$_POST['ind']."'>";
                        echo "<p><b>".  strtoupper($tracking)."</b><button class='closeTracking' title='".$tracking."' style='margin-left: 705px;'>Close</button></p>";
                        echo "<div style='border: 1px gray solid;padding: 10px;'>";
                        foreach ($itens as $item){                            
                            $i = explode('|', $item);                            
                            foreach($config['interfaces'] as $interface){
                                if($interface['gateway'] == $i[0]){
                                    $titulo = $interface['descr'];
                                }
                            }                            
                            echo '<p>
                                    <label>'.strtoupper($titulo).'</label>
                                    &nbsp;&nbsp;&nbsp;&nbsp;
                                    <label>Remote IP</label>&nbsp;<input type="text" name="group[dest_'.$tracking.']['.$i[0].']" />
                                  </p>
                            ';
                        }
                        echo "</div>";
                        echo "</div><br /><br />";
                    }
                }
            }
        }
    }
}
