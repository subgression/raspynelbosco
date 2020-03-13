#!/bin/sh
echo "Riproduco traccia: " $1
echo "Utente che esegue lo script: "
whoami
omxplayer -o alsa $1
