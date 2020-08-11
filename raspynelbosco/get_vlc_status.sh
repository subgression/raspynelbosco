curl -u :raspy $1:9999/requests/status.xml 2> /dev/null > status
        if grep -q "<state>stopped</state>" status;
        then
                echo "0"
        else
                echo "1"
        fi
