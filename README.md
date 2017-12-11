# veebiprogremiseprojekt Piltide kommuun
Veebiprogremmeerimise rühmatöö 2017

Eesmärgiks on luua kommuun, kus saab pilte üles laadida, kommenteerida, valides kas pilt on avalik või privaatne. 
Sihtrühmaks on koolinoore, fotograafia/töötlusega tegelevad inimesed.  
1: Saab sisse logida ja registreerida  
2: Saab pilte üles laadida  
3: Saab profiile kommenteerida  
4: Saab teiste profiile vaadata  
5: Saab parooli muuta  

MYSQLI tegemine:  

CREATE TABLE userinfo (  
username VARCHAR(64),  
password VARCHAR(512),  
email VARCHAR(64),  
gender INT,  
profile_img VARCHAR(512),  
PRIMARY KEY (username)  
);  
  
CREATE TABLE userinfo_img (  
username VARCHAR(64),  
img_name VARCHAR(512) PRIMARY KEY,  
img_privacy INT );  
  
CREATE TABLE comments (  
ID int NOT NULL AUTO_INCREMENT,  
username VARCHAR(64),  
poster VARCHAR(64),  
comment VARCHAR(500),  
PRIMARY KEY (ID)  
);  
  
Krister: Mida juurde õppisin?  
Css-iga tegelemist ja php ja mysqli-ga sidumist. Kasutajasõbraliku veebilehe loomist  
Mis ebaõnnestus?  
Piltide kommenteerimise võimalus + like-mise võimalus.  
Mis oli keeruline?  
Php-ga sidumine, et kõik töötaks normaalselt.  
  
Norman: Mida juurde õppisin?  
mysql ja php sidumine, classidega töötamist ja php muutujatega töötamist  
Mis ebaõnnestus?  
Piltide kommenteerimine, javascriptiga töötamine  
Mis oli keeruline?  
Alguses php classide tööle saamine ja mysql tabelite sidumine.  
