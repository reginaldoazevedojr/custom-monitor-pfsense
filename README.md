Pfsense
=======

Esta implementação personalizada tem como objetivo monitorar diversos links de internet pelo pfsense 
quando existe uma rede com mais de dois pfsense's remotos.<br>
Ela utiliza o gateway ```LoadBalance``` em cada regra de Lan e alterna sua prioridade conforme o status de cada Link de internet.

Instalação
=======

Passo 1
-------
Fazer upload dos arquivos para o pfsense.

Passo 2
-------
Permissão de execução para os arquivos ```/usr/local/sbin/failover.sh```, ```/etc/rc.custom_monitor``` e ```/usr/local/etc/rc.d/custom_monitor.sh```

Passo 3
-------
Editar o arquivo ```/usr/local/www/fbegin.inc```, encontre a linha: <br>
```$firewall_menu[] = array(gettext("Virtual IPs"), "/firewall_virtual_ip.php");``` <br>
Na linha seguinte adicione o trecho de código: <br>
```$firewall_menu[] = array(gettext("Custom Rules"), "/firewall_custom_rules.php");``` <br>

***
<b>Feito estes passos o monitoramento já pode ser configurado pelo pfsense basta acessar o menu ```Firewall``` no item ```Custom Rules```. <br>
Para configurar selecione o Destination e será apresentado os links de monitoramento e em cada link deve-se cadastrar o IP Remoto que determinará o status do link. Para visualizar os logs do monitoramento basta acessar o menu```status``` no item ```System Logs```.</b><br>

<b>Para que a ferramenta funciona o LoadBalance deve está configurado para cada rule de lan utilizada.</b>
