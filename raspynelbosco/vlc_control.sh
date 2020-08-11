# Esegue lo stop sul server
if [[ $2 == "stop" ]];
then
        curl -u :raspy $1:9999/requests/status.xml?command=pl_stop

# Esegue il play sul server
elif [[ $2 == "play" ]];
then
        curl -u :raspy $1:9999/requests/status.xml?command=pl_play

# Esegue il seek sul server
elif [[ $2 == "seek" ]];
then
        if [[ $3 != "" ]];
        then
                curl -u :raspy $1:9999/requests/status.xml?command=seek&val=$3
        else
                echo "Comando non eseugito, la chiamata corretta è vlc_control <ip> <cmd> <params>"
        fi

# List di tutte le tracce presenti nella playlist
elif [[ $2 == "dir" ]];
then
        curl -u :raspy $1:9999/requests/browse.xml

# Visualizza lo status
elif [[ $2 == "status" ]];
then
        curl -u :raspy $1:9999/requests/status.xml 2> /dev/null > status
        if grep -q "<state>stopped</state>" status;
        then
                echo "0"
        else
                echo "1"
        fi

# Riproduce una specifica traccia
elif [[ $2 == "play_track" ]];
then
        if [[ $3 != "" ]];
        then
                curl -u :raspy "$1:9999/requests/status.xml?command=pl_empty" -H 'Accept-Encoding: gzip, deflate'
                curl -u :raspy "$1:9999/requests/status.xml?command=in_play&input=file%3A%2F%2F%2Fvar%2Fwww%2Fhtml%2Fraspynelbosco%2FTracce%2F$3%2F$4" -H 'Accept-Encoding: gzip, deflate'
        else
                echo "Comando non eseugito, la chiamata corretta è vlc_control <ip> <cmd> <params>"
        fi

# Aggiunge la cartella delle tracce
elif [[ $2 == "add" ]]
then
        if [[ $3 != "" ]];
        then
                echo "Aggiungo alla playlist il file tramite MRL file://$3"
                curl -u :raspy $1:9999/requests/?command%3Din_enqueue%26input%3D"file://$3"
        else
                echo "Comando non eseugito, la chiamata corretta è vlc_control <ip> <cmd> <params>"
        fi

# Clear della playlist
elif [[ $2 == "empty" ]];
then
        curl -u :raspy $1:9999/requests/?command=pl_empty

else
        echo "Comando non eseugito, la chiamata corretta è vlc_control <ip> <cmd> <params>"
fi


