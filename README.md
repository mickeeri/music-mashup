### Projekt i kursen Webbteknik II (1dv449)

[Körbar applikation](http://me222wm.se/musicmashup/)

[Url för att lägga till och ta bort listor](http://me222wm.se/musicmashup/?bF2AP8GWs2Tc)

[Presentationsfilm](https://www.youtube.com/watch?v=LBUJdVpQHvw&feature=youtu.be&hd=1)

[Rapport](https://github.com/me222wm/1dv449_projekt/blob/master/README.md#projektrapport)

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

Jag använder JavaScript för att söka efter och lägga till album i listan. Dessa skickas sedan till servern via ett ajax-anrop där de sparas i databasen. Anledningen till denna lösning var flexibilitet. Med JavaScript behöver exempelvis inte ladda om sidan. Motiveringen till användningen av Last.fm:s Api för att hitta album är för att det är smart och innehåller all musik man kan tänka sig. Det går exempelvis att skriva "run br" och den hittar albumen "Born to Run" av Bruce Springsteen. 

### Schematisk bild över applikationens beståndsdelar
#### Applikationens arkitektur
![Applikationens arkitektur](https://github.com/me222wm/1dv449_projekt/blob/master/images/mashup-arkitektur.png)
#### Klassdiagram
![Klassdiagram](https://github.com/me222wm/1dv449_projekt/blob/master/images/musicmashupclasses.png)

Diagramet beskriver hur nya listor skapas. Formuläret för att skapa nya listor är skrivet i JavaScript. Där skapas ett JavaScript-objekt av typen AlbumList som tas emot via ett ajaxanrop av AjaxHandler.php. Där skapas motsvarande objekt fast i php som sedan sparas i databasen via en fasadklass. Från php-klassen Album hämtas uri till Spotify's spellistor. 

### Säkerhet och prestanda
För att öka prestanda kontrollerar jag att så mycket som möjligt cachas. Nu är det bara html-dokumenten själv som jag inte kan få att cachas, även om jag specifierar en cache-control i headern [2]. Jag gör även sådana självklara saker som att placera stilmallar i början och JavaScript i slutet, plus att jag använder minifierade filer om det finns [3, s.22, 23]. Jag övervägde att lägga all JavaScript i en fil, men för att bibehålla en bättre struktur delade jag upp dem.

Angående säkerhet validerar jag och filterar sådant som kommer från användar-input eller från webbtjänsterna. Ibland två gånger, både på klient och server. Detta för att minska risken för Cross-site Scripting [1,s.6]. I kontakten med databasen använder jag paramatiserade frågor [1,s.7]. Jag sparar även känsliga uppgifter i en settings-fil som inte laddas upp på Github. 

### Offline-first 
Min ambition var inte att det skulle gå att göra saker på sidan även om den var offline, snarare att användaren skulle bli notifierad om att denne inte hade uppkoppling samt inte behöva förlora data eller möta webbläsarens felsida (dinosaurien i Chrome t.ex.). För detta hittade jag en [artikel om application cache](http://www.html5rocks.com/en/tutorials/appcache/beginner/) och började sätta igång. Det verkade riktigt lovande från början. Application cache låter en cacha alla sidor som användaren har besökt tidigare, samt stilmallar, JavaScript, bilder mm. Det gjorde att det gick att surfa ganska obehindrat på siten även om man var nedkopplad. Men det visade sig snabbt ha sina baksidor. När användaren åter är online laddas fortfarande innehållet från application cachet, även om innehållet är uppdaterat. Innhållet uppdateras endast om manifest-filen (där man specificerar vad som ska cachas) ändras. Efter att ha läst [följande artikel](http://alistapart.com/article/application-cache-is-a-douchebag) beslutade jag mig för att ge upp det. Så som det ser ut nu har jag en liten jQuery-metod som skickar ett ajax-anrop med jämna mellanrum. Om anropet misslyckas utgår den från att användaren är offline och presenterar ett felmeddelande. I demonstrationsfilmen visar jag ett scenario där uppkopplingen förloras under tiden man arbetar med att skapa nya listor. Det fungerar bra om man inte är offline allt för länge. 

### Risker 
När användaren söker efter album visas dessa i dokumentet som sökresultat. Jag har inte hittat någon hundra procent säker metod att filtera/escapa dessa strängar i JavaScript, vilket gör att det eventuellt går att få in skadlig kod. 

Jag använder inte heller "synchronizer token pattern" i formuläret [5]. 

För att lägga till listor behöver man vara "administratör" vilket sker genom att man har tillgång till ett hemligt url. Det upplevs inte lika säkert som en korrekt implementerad inloggning. 

Applikationen är beroende av att Webbresuerna, särskilt Last.fm, fungerar som de ska. 

### Egen reflektion
Jag gjorde några misstag angående val av tekniker. Att bygga ett formulär i JavasScript och använda ajax för att via ett serverspråk lägga in det i databasen har många fördelar. Det går snabbt och man slipper ladda om sidan mm. Men det är också krångligt, och om något går fel på serversidan var det svårt att få något relevant felmeddelande. Det gjorde att jag tvingades skriva mycket extra kod för att validera data både på klient och server. I efterhand skulle jag inte ha skapat ett sådant formulär överhuvudtaget, utan istället försökt skrapa några utvalda musiksidor och försöka automatisera detta så mycket som möjligt. 

### Källor
[1] The Open Web Application Security Project, “OWASP Top 10 - 2013, The Ten Most Critical Web Application Security Risks”, 2013. [Online]. Tillgänglig: http://owasptop10.googlecode.com/files/OWASP%20Top%2010%20-%202013.pdf. [Hämtad: 23 november, 2015].

[2] M. Nottingham, “Caching Tutorial for Web Authors and Webmasters”, 6 maj, 2013 [Online] Tillgänglig: https://www.mnot.net/cache_docs/. [Hämtad: 3 december, 2015].

[3] S. Sounders, High Performance Web Sites, 14 Steps to Faster-Loading Web Sites. Sebastopol: O’Reilly, 2007.
