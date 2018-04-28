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

