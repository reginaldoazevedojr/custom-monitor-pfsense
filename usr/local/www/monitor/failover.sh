#!/bin/sh


verifica_link ()
{
    while [ 1 -eq 1 ]
    do
        sleep 20        
        CONT2=0
        while [ $CONT2 -ne 3 ]
        do
            if [ "`ping -c 1 -S 189.44.64.173 8.8.8.8 | awk '{print $6}' | grep ttl | tr '=' ' ' | awk '{print $1}'`" = "ttl" ]; then
                RESP1="v"
            else
                RESP1="f"
            fi
            if [ "`ping -c 1 -S 189.44.64.173 8.8.8.8 | awk '{print $6}' | grep ttl | tr '=' ' ' | awk '{print $1}'`" = "ttl" ]; then
                RESP2="v"
            else
            RESP2="f"
            fi
            if [ "`ping -c 1 -S 189.44.64.173 8.8.8.8 | awk '{print $6}' | grep ttl | tr '=' ' ' | awk '{print $1}'`" = "ttl" ]; then
                RESP3="v"
            else
                RESP3="f"
            fi
            if [ "`ping -c 1 -S 189.44.64.173 8.8.8.8 | awk '{print $6}' | grep ttl | tr '=' ' ' | awk '{print $1}'`" = "ttl" ]; then
                RESP4="v"
            else
                RESP4="f"
            fi
            if [ "$LINK" = "TELEFONICA" ]; then
                if [ "$RESP1" = "v" -a "$RESP2" = "v" -a "$RESP3" = "f" -a "$RESP4" = "f" ] || [ "$RESP1" = "v" -a "$RESP2" = "f" -a "$RESP3" = "f" -a "$RESP4" = "f" ] || [ "$RESP1" = "f" -a "$RESP2" = "v" -a "$RESP3" = "f" -a "$RESP4" = "f" ] || [ "$RESP1" = "f" -a "$RESP2" = "f" -a "$RESP3" = "f" -a "$RESP4" = "f" ]; then
                    CONT2=$(($CONT2+1))
                fi
            elif [ "$LINK" = "GVT" ]; then
                if [ "$RESP1" = "v" -a "$RESP2" = "v" -a "$RESP3" = "v" -a "$RESP4" = "v" ] || [ "$RESP1" = "v" -a "$RESP2" = "f" -a "$RESP3" = "v" -a "$RESP4" = "v" ] || [ "$RESP1" = "f" -a "$RESP2" = "v" -a "$R
                    ESP3" = "v" -a "$RESP4" = "v" ] || [ "$RESP1" = "f" -a "$RESP2" = "f" -a "$RESP3" = "v" -a "$RESP4" = "v" ]; then
                    CONT2=$(($CONT2+1))
                fi
            fi
        done
        if [ "$CONT2" = "3" ] && [ "$LINK" = "TELEFONICA" ]; then
            route del default
            route add default 192.168.25.1
            CONT2=0
        elif [ "$CONT2" = "3" ] && [ "$LINK" = "GVT" ]; then
            route del default
            route add default 189.44.64.169 
            CONT2=0
        fi
    done
}

case "$1" in
    start)
        verifica_link 2>/dev/null & 
        ;;
    stop)
        kill -9 `ps -aux | grep failover.sh | sed q | awk '{print $2}'`
        ;;
    *)
        echo "Usage: `basename $0` {start|stop}" >&2
        exit 64
        ;;
esac
exit 0
