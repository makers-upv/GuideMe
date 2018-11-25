#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <SoftwareSerial.h>

SoftwareSerial Serialito(D2, D1); // Arduino RX, Arduino TX

int incomingByte = 0;   // for incoming serial data
int estado = 1;

const char* ssid = "GuideMe";
const char* password = "makersupv";

void setup () {
  pinMode(D2, INPUT);
  Serial.begin(115200);
  Serialito.begin(115200);
  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("WiFi connected");


  // Print the IP address
  Serial.println(WiFi.localIP());

}

void loop() {

  if (WiFi.status() == WL_CONNECTED) { //Check WiFi connection estado

    HTTPClient http;  //Declare an object of class HTTPClient

    if (estado == 1)
    {
      String query = "http://192.168.137.151:8888/GuideMe/back.php?status=";
      query += (String) estado;
      Serial.println(query);
      http.begin(query);  //Specify request destination
      int httpCode = http.GET();                                                                  //Send the request

      if (httpCode > 0) { //Check the returning code
        estado++;
        String payload = http.getString();   //Get the request response payload
        Serial.println(payload);                     //Print the response payload
        while (!digitalRead(D2))
        {
          Serialito.println(payload);
          delay(1);
        }


        Serial.println(estado);
      }
      http.end();   //Close connection
      delay(1000);
    }
    else
    {

      String query = "http://192.168.137.151:8888/GuideMe/back.php?status=";
      query += (String) estado;
      Serial.println(query);
      http.begin(query);  //Specify request destination
      int httpCode = http.GET();                                                                  //Send the request

      if (httpCode > 0) { //Check the returning code

        String payload = http.getString();   //Get the request response payload
        Serial.println(payload);                     //Print the response payload
        while (!digitalRead(D2))
        {
          Serialito.println(payload);
          delay(1);
        }
        estado++;
        if (estado == 10)
        {
          estado = 1;
        }
        Serial.println(estado);
      }
      http.end();   //Close connection



    }


  }
}
