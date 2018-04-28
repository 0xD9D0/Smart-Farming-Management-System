# Smart-Farming-Management-System
Smart Animal Farming Management System using freeboard, MySQL, PubNub, Arduino 

We used arduino Mega with the following sensors : 
1) ultrasonic sensor
2) Gas sensor
3) Waterlevel sensor
4) Soil moisture sensor
5) DHT11 temperature and humidity sensor
6) Fire sensor (we used the diode and made a circuit)

We also used a buzzer and an ESP8266 shield for communication. 

The code can work on arduino UNO if we omit the gas sensor ( the code for uno is available as well) 

The system uses PubNub to communcicate with the hardware .. we also added the "ClickSend" block to our PubNub so urgent notifications are sent as SMS.

+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

Brief steps to put this system in use:
1) Register in PubNub and get your publish & subscribe keys
2) Add the ClickSend block to your PubNub account. then register in ClickSend & input your accoutn details in the pubnub block.
3) Connect the circuit 
4) Edit the following in the arduino code :

        a) Your wifi SSID & Password
        
        b) Your PubNub's Publish & Subscribe keys
        
        c) The phone number you want to get alerts on.
        
5) Get a web-hosting or install XAMPP. 
6) Configure MySQL on your server.
6) Create the MySQL Database using the file 
  - [sql.txt](Web App/FarmEntity/sql.txt)
7) Edit the connect-db file
  - [connect-db.php](Web App/FarmEntity/connect-db.php)
8) Edit the following JSON files and add your Subscriber key, channel name etc
  - [alertsdashboard.json](Web App/dashboard/alertsdashboard.json)
  - [statdashboard.json](Web App/dashboard/statdashboard.json)
  - [farmdashboard.json](Web App/dashboard/farmdashboard.json)
  
  
