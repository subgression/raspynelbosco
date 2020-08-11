# Comando per settare come user ROOT vlc
sed -i 's/geteuid/getppid/' /usr/bin/vlc
# Questo comando consente di aggiungere e di playare la traccia
curl 'http://192.168.1.11:9999/requests/status.xml?command=in_play&input=file%3A%2F%2F%2Fvar%2Fwww%2Fhtml%2Fraspynelbosco%2FTracce%2FCarloMatti_Alchemy_ElectronicSymphony_QuadraphonicVersion%2F01_CarloMatti_Alchemy_Deus_sive_Natura_Quadraphonic_Left.wav' -H 'Authorization: Basic OnJhc3B5' -H 'Accept-Encoding: gzip, deflate'

