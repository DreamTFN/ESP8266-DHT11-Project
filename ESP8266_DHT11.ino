#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include "DHT.h"

#define Wifi_Led D0
#define Start_Button D8
#define Stop_Button D7
#define Alert_Led D4
#define Alert_Buzzer D5
#define DHTPIN D6     // Digital pin connected to the DHT sensor
#define DHTTYPE DHT11   // DHT 11

WiFiClient wifiClient;
const char* ssid = "SSID_NAME";
const char* password = "PASSWORD";

int Start_Button_State = 0;
int Stop_Button_State = 0;
String sendval, sendval2, postData;

DHT dht(DHTPIN, DHTTYPE);

unsigned long previousMillis = 0; // Store the last time data was printed
const unsigned long interval = 60000; // Interval for 1 minute (60,000 milliseconds)

void setup() 
{
  Serial.begin(9600);

  dht.begin();  

  pinMode(Wifi_Led, OUTPUT);  
  pinMode(Start_Button, INPUT);
  pinMode(Stop_Button, INPUT);  
  pinMode(Alert_Led, OUTPUT);
  pinMode(Alert_Buzzer, OUTPUT); 

  Serial.println(ssid);
  Serial.print(" Connecting to the network");
  
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) 
  {
    delay(1000);
    Serial.print(".");   
  }  
  
  Serial.println("");
  Serial.println("Connected to the network !");
  Serial.println("IP Address : ");
  Serial.print(WiFi.localIP()); 

  Serial.println("HTTP server started !");   
}

void loop() 
{
  String macAddress = WiFi.macAddress();
  unsigned long currentMillis = millis();
  Start_Button_State = digitalRead(Start_Button);
  Stop_Button_State = digitalRead(Stop_Button);    

  if (currentMillis - previousMillis >= interval) 
  {
    previousMillis = currentMillis;
    
    Serial.print("Time: ");
    Serial.println(currentMillis); 

    float temp = dht.readTemperature();
    float hum = dht.readHumidity();
  
    sendval = String(temp);
    sendval2 = String(hum); 
  
    postData = "mac="+ macAddress + "&temp=" + sendval + "&humidity=" + sendval2;
    Sensor_Write(postData);  
    Serial.print(" postDatant : ");
    Serial.println(postData);     
  }

  if (Start_Button_State == HIGH) 
  {
    digitalWrite(Alert_Led, HIGH);
    digitalWrite(Alert_Buzzer, HIGH);  
    postData = "mac="+ macAddress + "&alert=1";
    Alert_Write(postData);     
  }

  if (Stop_Button_State == HIGH) 
  {
    digitalWrite(Alert_Led, LOW);
    digitalWrite(Alert_Buzzer, LOW);  
    postData = "mac="+ macAddress + "&alert=2";
    Alert_Write(postData);      
  } 

  if (WiFi.status() == WL_CONNECTED)
  {
    digitalWrite(Wifi_Led, HIGH);  
  }
  else
  {
    digitalWrite(Wifi_Led, LOW);  
  }
}

void Alert_Write(String Alert_Val) 
{
  HTTPClient http;
  postData = Alert_Val;
  http.begin(wifiClient, "http://localhost/alert_write.php?" + postData);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpCode = http.POST(postData);
}

void Sensor_Write(String Sensor_Val)
{
  HTTPClient http;
  postData = Sensor_Val;
  http.begin(wifiClient, "http://localhost/sensor_write.php?" + postData);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");
  int httpCode = http.POST(postData);
}
