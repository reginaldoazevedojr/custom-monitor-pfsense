#!/bin/sh

while [ 1 -eq 1 ]
do        
    total=0
    prevent=3
    swap=0
    priority=$5
    for i in 2 3 4 5 6 7 8
    do
        conn=`ping -t 3 -c 1 -S $1 $2 | egrep "ttl" | sed -r "s/(.*)(ttl)=([0-9]+)(.*)/\2/g"`        
        val=`echo 2^$i | bc`                
         if [ -z $conn ]; then
            total=`echo $total+$val*-1 | bc`
            if [ $prevent -eq 1 ]; then
               swap=$swap+1
            fi
            prevent=0
        fi                
        if [ "$conn" = "ttl" ]; then
            total=`echo $total+$val | bc`
            if [ prevent = 0 ]; then
                swap=$swap+1
            fi
            prevent=1
        fi        
    done        
    if [ $total -ge -300 -a $total -le 300 ]; then
        if [ $swap -ge 4 ]; then
            if [ $priority != 1 ]; then
                php /usr/local/sbin/custom_monitor.php destination=$3 gateway=$4 priority=1 link=$6
                priority=1
            fi
            echo "instavel"
        else
            echo "indeterminado"
        fi        
    fi
    if [ $total -lt -300 ]; then
        if [ $priority != 2 ]; then
            php /usr/local/sbin/custom_monitor.php destination=$3 gateway=$4 priority=2 link=$6
            priority=2
        fi
        echo "offline"
    fi
    if [ $total -gt 300 ]; then
        if [ $priority != 1 ]; then
            php /usr/local/sbin/custom_monitor.php destination=$3 gateway=$4 priority=1 link=$6
            priority=1
        fi
        echo "online"
    fi
    
    sleep 5
done
#case "$1" in
#    start)
#        verifica_link 2>/dev/null & 
#        ;;
#    stop)
#        kill -9 `ps -aux | grep failover.sh | sed q | awk '{print $2}'`
#        ;;
#    *)
#        echo "Usage: `basename $0` {start|stop}" >&2
#        exit 64
#        ;;
#esac
#exit 0
