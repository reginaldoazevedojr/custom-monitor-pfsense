#!/bin/sh

custom_monitor () {
        /etc/rc.custom_monitor start > /dev/null &
}
case "$1" in
    start)
        custom_monitor 2>/dev/null &
        ;;
    stop)
        kill -9 `ps -aux | grep custom_monitor.sh | sed q | awk '{print $2}'`
        ;;
    *)
        echo "Usage: `basename $0` {start|stop}" >&2
        exit 64
        ;;
esac
exit 0
