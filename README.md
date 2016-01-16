### Projekt i kursen Webbteknik II (1dv449)

## Projektidé
Nu när årskiftet närmar sig kommer det som för många musikintresserade är en stor höjdpunkt, nämligen alla årsbästalistor med bästa album, bästa låtar etc. För mig brukar de ligga till grund för mycket av musiklyssnadendet även under nästkommande år. Problemet är att det är svårt att få en bra överblick. Därför vill jag göra en applikation som samlar flera årsbästalistor. 

Det finns mig veterligen inget api som tillhandahåller sådana listor, vilket gör att jag själv får lägga in (eller skrapa) exempelvis de 10 bästa albumen enligt några utvalda siter, men sedan anävnda api:er för att visa spellistor, musikvideor och metadata om artister och album. Kanske kan man också sammanställa rankingen och utse den skiva som ligger höst på listorna. 

[Nostalgilistan](http://www.nostalgilistan.se/) är en mashupapplikation som liknar det jag föreställer mig, men den använder Sveriges radios topplistor, medan jag inte vill sammanställa t.ex. de mest sålda/spelade albumen utan de som musikjournarlister valt ut som de bästa. 

* [Årsbästalista från Pitchfork](http://pitchfork.com/features/staff-lists/9764-the-50-best-albums-of-2015/)
* [Spotify's API för spellistor](https://developer.spotify.com/web-api/)
* [YouTube för musikvideos](https://developers.google.com/youtube/v3/)
* [Metadata från Last.fm](http://www.last.fm/api) 

## Projektrapport 
### Inledning
Min applikation syftar till att - så som det är beskrivet i projektidén - att snabbt och enkelt kunna lägga in listor med de bästa musikalbumen från ett särkilt år enligt en rad olika källor t.ex. Rolling Stones Magazine, The Guardian m.fl. Teknikerna som används är PHP 7.0, JavaScript och en MySQL databas. Sökning efter album sker med ett API från [Last.fm](http://www.last.fm/api). Jag använder även [Spotify](https://developer.spotify.com/web-api/) för att hämta spellistor för varje album. Som front-end ramverk använder jag [Materialize](http://materializecss.com/).

### Schematisk bild över applikationens beståndsdelar
#### Applikationens arkitektur
![Applikationens arkitektur](https://github.com/me222wm/1dv449_projekt/blob/master/images/mashup-arkitektur.png)
#### Klassdiagram
![Klassdiagram](https://github.com/me222wm/1dv449_projekt/blob/master/images/musicmashupclasses.png)

Klassdiagramet beskriver hur nya listor skapas. Formuläret består av en JavaScript-funktion AlbumListMaker som skapar objekt av typen AlbumList och Album som sedan skickas med ett ajaxanrop som tas om hand om av AjaxHandler. Där skapas ett AlbumList objekt motsvarande det som skapades i JavaScript som sedan sparas i databasen via en fasadklass. En länk till Spotify hämtas från klassen Album. 


### Säkerhet och prestanda
För att öka prestanda kontrollerar jag att så mycket som möjligt cachas. Nu är det bara html-dokumenten själv som jag inte kan få att cachas, även om jag specifierar en cache-control i headern. Jag gör även sådana självklara saker som att placera stilmallar i början och javascript i slutet, plus att jag använder minifierade filer om det finns. 

Angående säkerhet validerar jag och filterar sådant som kommer från användar-input eller från webbtjänsterna. Ibland två gånger, både på klient och server. I kontakten med databasen använder jag paramatiserade frågor. Jag sparar även känsliga uppgifter i en settings-fil som inte laddas upp på Github. 

### Offline-first 
Min ambition var inte att det skulle gå att göra saker på sidan även om den var offline, snarare att användaren skulle bli notifierad om att denne inte hade uppkoppling samt inte behöva förlora data eller möta webbläsarens felsida (dinosaurien i Chrome t.ex.). För detta hittade jag en [artikel om application cache](http://www.html5rocks.com/en/tutorials/appcache/beginner/) och började sätta igång. Det verkade riktigt lovande från början. Application cache låter en cacha alla sidor som användaren har besökt tidigare, samt stilmallar, JavaScript, bilder mm. Det gjorde att det gick att surfa ganska obehindrat på siten även om man var nedkopplad. Men det visade sig snabbt ha sina baksidor. När användaren åter är online laddas fortfarande innehållet från application cachet, även om innehållet är uppdaterat. Innhållet uppdateras endast om manifest-filen (där man specificerar vad som ska cachas) ändras. Efter att ha läst [följande artikel](http://alistapart.com/article/application-cache-is-a-douchebag) beslutade jag mig för att ge upp det. Så som det ser ut nu har jag en liten jQuery-metod som skickar ett ajax-anrop med jämna mellanrum. Om anropet misslyckas utgår den från att användaren är offline och presenterar ett felmeddelande. I demonstrationsfilmen visar jag ett scenario där uppkopplingen förloras under tiden man arbetar med att skapa nya listor. 

### Risker 
Begränsad filtrering/sanitering av input som mottas och presenteras på klienten, dvs i JavaScript metoder. 

Inget token i formuläret för att motverka CSRS. 

Ingen inloggning, utan bara en hemlig url för att komma åt administratörsrättigheter. 

### Egen reflektion
Jag gjorde några misstag angående val av tekniker. Att bygga ett formulär i JavasScript och använda ajax för att lägga in det i databasen har många fördelar. Det går snabbt och man slipper ladda om sidan mm. Men det är också krånligt, och om något går fel på serversidan var det svårt att få något relevant felmeddalande. I efterhand skulle jag inte ha skapat ett sådant formulär överhuvudtaget, utan istället försökt skrapa några utvalda musiksidor och försöka automatisera detta så mycket som möjligt. 
