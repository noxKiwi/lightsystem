#!/bin/bash

# Initialize variables
version="1.1"
message=""
checker=0
maxAttempts=20
debug=""
force=""
quantity=1
app=""
timeOut=10



# Reset is necessary if getopts was used previously in the script.
# It is a good idea to make this local in a function.
OPTIND=1
while getopts ":q:m:c:a:vhf" opt
do
    case $opt in
        c)
            com="$OPTARG"
            if [ "$com" != "start" ] && [ "$com" != "stop" ] && [ "$com" != "status" ]
            then
                echo "ERROR: Unknown command $com" >&2
                exit 1
            fi
            ;;
        a)
            app="$OPTARG"
            ;;
        q)
            quantity="$OPTARG"
            if [ $quantity -gt 6 ]
            then
                echo "ERROR: Max 6 consumer allowed. You try to start $quantity" >&2
                exit 1
            elif [ $quantity -lt 1 ]
            then
                quantity=1
            fi
            ;;
        m)
            mode="$OPTARG"
            if [ "$mode" != "" ] && [ "$mode" != "debug" ]
            then
                echo "ERROR: Unknown mode $mode" >&2
                exit 1
            else
                debug="--logLevel=DEBUG"
            fi
            ;;
        f)
            force="-9"
            ;;
        \?)
            echo "ERROR: Invalid option -$OPTARG" >&2
            exit 1
            ;;
        :)
            echo "ERROR: Option -$OPTARG requires an argument." >&2
            exit 1
            ;;
    esac
done
shift "$((OPTIND-1))" # Shift off the options and optional --.

# Start consumer with parameter app.
startApp() {
    appName=$1
    echo "INFO: startApp ($appName)" >&2
    appCount=$(countConsumers $app)
    if [ $appCount -eq $quantity ]
    then
        echo "INFO: No action required. $appCount of $quantity consumers ($appName) running." >&2
        return
    elif [ $appCount -gt $quantity ]
    then
        echo "WARN: $appCount of $quantity consumers ($appName) running. Consumers will be stopped and started again to have the correct quantity." >&2
        stopApp $appName
        startApp $appName
    fi
    while [ $appCount -lt $quantity ]
    do
        env0=$(countConsumers $app)
        echo "Currently there are $env0 $app instances."
        echo "There shall be $quantity $app instances."

        nohup php $appName.php > /var/log/$appName.log 2>&1 &
        appCount=$(countConsumers $app)
        if [ $appCount -lt $quantity ]
        then
            echo "ERROR: $appName consumer failed to start, start next attempt." >&2
        else
            echo "SUCCESS: $appName consumer started: $appCount of $quantity running." >&2
        fi
        checker=$(($checker+1))
        if [ $checker -gt $maxAttempts ]
        then
            echo "ERROR: Max attempts ($maxAttempts) reached. Please check consumer ($appName)." >&2
            exit 1
        fi
    done
}

countConsumers() {
    appName=$1
    appCount=$(ps aux | grep "$appName" | grep -v "grep" | grep -v "tail" | grep -v "controller.sh" | wc -l)
    echo $appCount
}

listConsumers() {
    appName=$1
    appCount=$(ps aux | grep "$appName" | grep -v "grep" | grep -v "tail" | grep -v "controller.sh")
    echo $appCount
}

# Stop consumer with parameter app.
stopApp() {
    appName=$1
    appCountStart=$(countConsumers $app)
    if [ $appCountStart -gt 0 ]
    then
        kill $force `ps aux | grep -v 'grep' | grep "php $appName" | awk '{print $2}'`
    else
        echo "NOTICE: Cannot stop consumer ($appName). No consumer running." >&2
        return
    fi
    appCount=$(countConsumers $app)
    if [ $appCount -gt 0 ]
    then
        echo "[ERROR]: $appName consumers cannot be stopped." >&2
        ps aux | grep -v 'grep' | grep "listener $appName"
        exit 1
    fi
    echo "[SUCCESS]: $appCount instances of $consumers have been stopped successfully." >&2
    exit 0
}

if [ "$com" = "start" ]
then
    startApp $app
elif [ "$com" = "stop" ]
then
    stopApp $app
elif [ "$com" = "status" ]
then
    appCount=$(countConsumers $app)
    if [ $appCount -gt 0 ]
    then
        echo "[INFO]: There are $appCount processes of $app running:"
        listConsumers $app
    else
        echo "[INFO]: There are NO processes of $app running" >&2
    fi
fi
exit 0

