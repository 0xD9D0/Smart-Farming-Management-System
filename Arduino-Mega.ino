
#include <dht.h>
//#include <SPI.h>
#include "WiFiEsp.h"
#define PubNub_BASE_CLIENT WiFiEspClient
#include <PubNub.h>

#define DHT11_PIN 8
#define buzz 9
#define TrigPin 32
#define EchoPin 30
#define gas_PIN A15

// Initialize DHT sensor.
dht DHT;

// Emulate Serial1 on pins 6/7 if not present
// humidity sensor connected to pin 8
// waterlevel to A0
// soil moisture analog to A1
//fire sensor A15 
// light sensor A5
//buzzer to digital 9
#ifndef HAVE_HWSERIAL1
#include "SoftwareSerial.h"
SoftwareSerial Serial1(6, 7); // RX, TX
#endif
char ssid[] = "wifi ssid";            // your network SSID (name)
char pass[] = "wifi password";        // your network password
int status = WL_IDLE_STATUS;     // the Wifi radio's status
char hconst=0,wconst=0;
WiFiEspClient *client;
float cm;
const int gasThres = 400;
int gasv;
char pubkey[] = "pub-c-7e77063a-9f4d-4e02-83b8-0da0735276b5";
char subkey[] = "sub-c-d17f5088-294f-11e8-86ee-1e8059ef76e6";
char channel[] = "Sensors";
void fire();
void gas();
void moisture(float m);
void water(float l);

void setup()
{



    // initialize serial for debugging
  Serial.begin(9600);
    Serial.println("Serial set up");
  // initialize serial for ESP module
  Serial1.begin(9600);
  // initialize ESP module
  WiFi.init(&Serial1);


  pinMode(buzz, OUTPUT);
    pinMode(TrigPin, OUTPUT);
  pinMode(EchoPin, INPUT);
  pinMode(gas_PIN,INPUT);
  
   // check for the presence of the shield
  if (WiFi.status() == WL_NO_SHIELD) {
    Serial.println("WiFi shield not present");
    // don't continue
    while (true);
  }
    // attempt to connect to WiFi network
  while ( status != WL_CONNECTED) {
    Serial.print("Attempting to connect to WPA SSID: ");
    Serial.println(ssid);
    // Connect to WPA/WPA2 network
    status = WiFi.begin(ssid, pass);
      PubNub.begin(pubkey, subkey);
  Serial.println("PubNub set up");
  }
  Serial.begin(9600);

}


void loop()
{

 
  Serial.println("You're connected to the network");
   if (analogRead(A15) >0){
  fire();
  digitalWrite(buzz,HIGH);
  }
   gasv = analogRead(gas_PIN);
     if (gasv >gasThres){
  gas();
  digitalWrite(buzz,HIGH);
  }
int dhk = DHT.read11(DHT11_PIN);
  // Reading temperature or humidity takes about 250 milliseconds!
  float h = DHT.humidity;
  // Read temperature as Celsius (the default)
  float t = DHT.temperature;

  // Check if any reads failed and exit early (to try again).
  if (isnan(h) || isnan(t)) {
    Serial.println("Failed to read from DHT sensor!");
    return;
  }

float w = analogRead(A0);
w/=10; 

float m= 1024- analogRead(A1);
m/=10;
float l= analogRead(A5);
 digitalWrite(TrigPin, LOW);       
  delayMicroseconds(2);
  digitalWrite(TrigPin, HIGH);
  delayMicroseconds(10);
  digitalWrite(TrigPin, LOW);
  cm = pulseIn(EchoPin, HIGH) / 58.0; //The echo time is converted into cm
  cm = (int(cm * 10.0)) / 10.0;     //Keep one decimal places

  Serial.print("Humidity: ");
  Serial.print(h);
  Serial.print(" %\t");
  Serial.print("Temperature: ");
  Serial.print(t);
  Serial.print(" *C ");
Serial.print("\nWater level: ");
Serial.print(w);
Serial.print(" %");
Serial.print("\nSoil moisture: ");
Serial.print(m);
Serial.print(" %");
Serial.print("\nLux: ");
Serial.print(l);
Serial.print("\n");
Serial.println(analogRead(A15));
  String temperature = String(t);
  String humidity = String(h);
  String waterlevel = String(w);
  String soilmoisture=String(m);
    String lux=String(l);
  // Prepare a JSON payload string 1
  String payload = "{";
  payload += "\"temperature\":"; payload+="\""; payload += temperature; payload+="\""; payload += ",";
  payload += "\"humidity\":"; payload+="\""; payload += humidity; payload+="\""; payload += ",";
    payload += "\"soilmoisture\":"; payload+="\""; payload += soilmoisture; payload+="\""; payload += ",";
       payload += "\"lux\":"; payload+="\""; payload += lux; payload+="\""; payload += ",";
    if (analogRead(A15) >0){
  payload += "\"fire\": \"FIRE\""; payload += ",";
  digitalWrite(buzz,HIGH);
  }
   else{
  payload += "\"fire\": \"No fire\""; payload += ",";
  digitalWrite(buzz,LOW);
   }
           payload += "\"waterlevel\":"; payload+="\""; payload += waterlevel; payload+="\""; 
  payload += "}";
 Serial.println( payload );

  // Send payload
  char attributes[150];
  payload.toCharArray( attributes, 150 );
  Serial.println( attributes );

Serial.println("publishing a message");
  client = PubNub.publish(channel, attributes);
  if (!client) {
    Serial.println("publishing error");
    delay(1000);

  }
  

   delay(3000);
  moisture(m);
   delay(3000);
  water(l);
   delay(3000);
 // client->stop();
 
}

void fire(){
  
  String payload = "{";
  payload += "\"to\":"; payload+="\"009159494934\""; payload += ",";
    payload += "\"body\":"; payload+="\"FIRE!!\""; payload += "}";

  char attributes[100];
  payload.toCharArray( attributes, 100 );
  Serial.println( attributes );

Serial.println("publishing a message");
  client = PubNub.publish(channel, attributes);
  


  }
  

void gas(){
  
  String payload = "{";
  payload += "\"to\":"; payload+="\"009159494934\""; payload += ",";
    payload += "\"body\":"; payload+="\"GAS LEAKAGE!!\""; payload += "}";

  char attributes[100];
  payload.toCharArray( attributes, 100 );
  Serial.println( attributes );

Serial.println("publishing a message");
  client = PubNub.publish(channel, attributes);
    delay(1000);
  
  }
    
void water(float l){
    String payload = "{";
  if (l>30){
  payload += "\"wl\":"; payload+="\"Waterlevel is good.\"";
  }
  else 
  payload += "\"wl\":"; payload+="\"Please fill water.\"";

  payload += ",";
    if (cm<4.5){
     payload += "\"fl\":"; payload+="\"food level is good.\"";
      }
  else {
  payload += "\"fl\":"; payload+="\"Please fill food.\"";
  }
 payload += "}";

  char attributes[150];
  payload.toCharArray( attributes, 150 );
  Serial.println( attributes );

Serial.println("publishing a message");
  client = PubNub.publish(channel, attributes);

return;
  }

void moisture(float m){
    String payload = "{";
 if (m>30)
{  payload += "\"mo\":"; payload+="\"soil moisture is good.\"";
}
else{ 
  payload += "\"mo\":"; payload+="\"Please fill water.\"";
}
  payload += ",";
    if (gasv<gasThres){
     payload += "\"gas\":"; payload+="\"No leakage.\"";
      }
  else {
  payload += "\"gas\":"; payload+="\"LEAKAGE!.\"";
  }

 payload += "}";
  char attributes[150];
  payload.toCharArray( attributes, 150 );
  Serial.println( attributes );

Serial.println("publishing a message");
  client = PubNub.publish(channel, attributes);

  

 return;
  }
