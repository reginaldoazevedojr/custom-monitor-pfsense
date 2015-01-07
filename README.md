pfsense
=======

Esta implementação personalizada tem como objetivo monitorar diversos links de internet pelo pfsense 
quando existe uma rede com mais de dois pfsense's remotos.

Instalação
=======

Passo 1
-------
Fazer upload dos arquivos para o pfsense.

Passo 2
-------
Dá permissão de execução para os arquivos ```/usr/local/sbin/failover.sh```, ```/etc/rc.custom_monitor``` e ```/usr/local/etc/rc.d/custom_monitor.sh```

Passo 3
-------
Editar o arquivo ```/usr/local/www/fbegin.inc```, encontre a linha:
```$firewall_menu[] = array(gettext("Virtual IPs"), "/firewall_virtual_ip.php");``` 
Na linha seguinte adicione o seguinte trecho de código:
```$firewall_menu[] = array(gettext("Custom Rules"), "/firewall_custom_rules.php");```


