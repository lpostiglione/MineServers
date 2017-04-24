<h2>FAQ</h2>
<h4>Übersicht</h4>
<ol>
    <li><a href="#howtoadd">Wie lege ich einen Server an?</a></li>
    <li><a href="#whynotadd">Warum kann ich meinen Server nicht anlegen?</a></li>
    <li><a href="#insultcomment">Was kann ich gegen beleidigende Kommentare tun?</a></li>
    <li><a href="#serveroffine">Warum wird mein Server als Offline angezeigt, obwohl er Online ist?</a></li>
    <li><a href="#editserver">Wie kann ich meinen Server bearbeiten?</a></li>
    <li><a href="#votereset">Warum verschwinden meine Votes?</a></li>
    <li><a href="#votelink">Wie kann ich auf die Voteseite verlinken?</a></li>
    <li><a href="#votifier">Kann ich Votifier mit dieser Serverliste nutzen?</a></li>
    <li><a href="#api">Wie funktioniert eure API?</a></li>
    <li><a href="#hideplugins">Ich verwende Query will aber nicht dass meine Plugins angezeigt werden.</a></li>
</ol>
<h4 id="howtoadd">Wie lege ich einen Server an?</h4>
<p>Um einen Server anzumelden musst du dich zuerst <b><a href="index.php?site=register">hier</a></b> anmelden. Danach kannst du <b><a href="index.php?site=myservers">hier</a></b> deinen Server anmelden.</p>
<h4 id="whynotadd">Warum kann ich meinen Server nicht anlegen?</h4>
<p>Jemand hat bereits einen Server mit deiner IP eingereicht. Das System erkennt dies als Betrugsversuch und sperrt diese Serveradresse.</p>
<h4 id="insultcomment">Was kann ich gegen beleidigende Kommentare tun?</h4>
<p>Du kannst diese mit Hilfe des Melden Symbols (<i class="icon-warning-sign"></i>) neben dem Kommentar melden. Ein Administrator wird sich dann so schnell wie möglich darum kümmern, diese zu löschen.</p>
<h4 id="serveroffine">Warum wird mein Server als Offline angezeigt, obwohl er Online ist?</h4>
<p>Mögliche Gründe sind:</p>
<ul>
    <li>Der Onlinecheck ist noch nicht gelaufen. Dieser läuft derzeit alle 30-60 Minuten.</li>
    <li>Serveradresse falsch angegeben</li>
    <li>Für diesen Server werden Zusatzprogramme verwendet.</li>
    <li>Der Server lässt eine Abfrage des Status nicht zu</li>
</ul>
<h4 id="editserver">Wie kann ich meinen Server bearbeiten?</h4>
<p>Um deinen Servereintrag zu Bearbeiten musst du dich zuerst einloggen. Als nächstes musst du auf "<a href="?site=myservers">Meine Server</a>" klicken. Dann siehst du alle von dir eingetragenen Server aufgelistet. Neben jedem Server findest du mehrere Buttons, einer davon lautet "Bearbeiten". Wenn du auf diesen klickst, bekommst du die Möglichkeit deinen Server zu bearbeiten.</p>
<h4 id="votereset">Warum verschwinden meine Votes?</h4>
<p>Jede Woche werden die Votes zurückgesetzt, um Chancengleichheit für alte und junge Server zu bieten.</p>
<h4 id="votelink">Wie kann ich auf die Voteseite verlinken?</h4>
<p>Wir betreiben eine eigene Domain nur zu diesem Zweck. Um deine User auf die Voteseite zu leiten, gibst du einfach den Link <code>http://mcvote.eu/(Deine Server ID)/</code> an. Optional kannst du nach dem abschließenden "/" noch einen Benutzernamen einfügen, der dann automatisch in das entsprechende Feld auf der Voteseite eingetragen wird.</p>
<h4 id="votifier">Kann ich Votifier mit dieser Serverliste nutzen?</h4>
<p>Ja!<br>
Unsere Serverliste unterstützt Votifier. Um deine Daten einzutragen musst du dich zuerst einloggen. Als nächstes musst du auf "<a href="?site=myservers">Meine Server</a>" klicken. Dann siehst du alle von dir eingetragenen Server aufgelistet. Neben jedem Server findest du mehrere Buttons, einer davon lautet "Votifier". Wenn du auf diesen klickst, bekommst du die Möglichkeit deine Votifier-Daten einzutragen.
<h4 id="api">Wie funktioniert eure API?</h4>
<p>Um euch ein kleines Tool für eure Homepage zu bieten haben wir eine API eingerichtet. Diese ist wie folgt aufzurufen: <br>
<code>http://www.mineservers.eu/api/[Server ID]</code><br>
Die Ausgabe erfolgt als String im Typ "plain-text" der als delimiter Nullstellen benutzt. Hier findet ihr ein Beispielscript zur Anwendung:</p>
<pre>
&lt;?php
$apiout = file_get_contents("http://mineservers.eu/api/1");
$apitest = explode("\x00", $apiout);
print_r($apitest);
?&gt;
</pre>
<p>Dieses PHP Script würde zb. folgende Ausgabe erzeugen:</p>
<pre>
Array ( 
    [0] => 1.5 
    [1] => Dies ist eine Beispiel MOTD
    [2] => 20
    [3] => 100
    [4] => true 
)
</pre>
<p>Zur Erklärung:<br>
&nbsp;&nbsp;[0] ist die Serverversion<br>
&nbsp;&nbsp;[1] ist die MOTD des Servers<br>
&nbsp;&nbsp;[2] sind die aktiven Spieler<br>
&nbsp;&nbsp;[3] sind die Slots<br>
&nbsp;&nbsp;[4] ist der Online Status des Servers. "true" wenn der Server Online ist und "false" wenn er Offline ist.</p>
<h4 id="hideplugins">Ich verwende Query will aber nicht dass meine Plugins angezeigt werden.</h4>
<p>Die Lösung dafür ist sehr einfach und Serverseitig. (also von dir jederzeit änderbar)<br>
    Alles was du tun musst ist, in der <b>bukkit.yml</b> von deinem Server den Wert <code>query-plugins:</code> auf <code>false</code> setzen.</p>