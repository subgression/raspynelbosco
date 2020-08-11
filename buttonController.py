from gpiozero import Button
import subprocess

button = Button(4)
user = 0

while True:
    if button.is_pressed:
        subprocess.call(["curl", "192.168.2.1/raspynelbosco/index.php?user=" + user])
        print("Pulsante clickato")