=== OS media - HTML5 Featured Video plugin for WordPress ===
Contributors: mariomarino
Requires at least: 3.4
Tested up to: 3.6
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: 
Tags: autoplay, cover image, cover video, embed, embedding, embed youtube, embed vimeo, embed videos, videojs, iframe, loop, player, plugin, responsive, seo, shortcode, youtube, youtube embed, youtube player, youtube videos, video, video analytics, video cover, video HTML5, video seo, vimeo, vimeo player, vimeo embed, vimeo videos

Plugin per l'inserimento di video sia tramite tradizionai shortcode che in modalità "Featured", all'interno di post o pagine WordPress, sia embed da Youtube/Vimeo che self-hosted utilizzando il player HTML5 Videojs.

== Description ==

<a href="http://wordpress.org/extend/plugins/OS-media/description/">Italiano</a> - <a href="">English</a>

Questo Plugin ha l'obiettivo di rendere la propria piattaforma Wordpress una vera e propria stazione multimediale per la distribuzione di audio/video “on-demand”. Ciò attraverso una doppia modalità di inserimento: **"Featured"** per piattaforme video in cui avremo un video "in evidenza" per ogni post/pagina (da usare in abbinamento ad un tema wordpress specifico che stiamo sviluppando!), oppure con il classico meccanismo degli **short code** aggiunti nella textarea. 
Allo stesso tempo, permette l'inserimento nella propria piattaforma Wordpress di video da <a href="http://www.youtube.com/">Youtube</a>, da <a href="http://vimeo.com/">Vimeo</a> e video memorizzati localmente (self-hosted) attraverso un'unica comoda interfaccia relativa ad ogni singolo post/pagina. Utilizza la tecnologia video HTML5 e dunque il nuovo tag <video> e si appoggia all'ottimo player <a href="http://videojs.org/">Videojs.org</a>.
Vi sono due tipi di parametri di configurazione dei video: quelli “generali” (configurabili dalle **opzioni generali** del plugin, come ad es. la skin del player) e i “postmeta”, ovvero quelli relativi ad ogni singolo post/pagina. Nel caso vi siano sovrapposizioni tra i due (vedi ad esempio le dimensioni del player “height” e “width”), i “postmeta” prevalgono su quelli generali. Ogni volta che si crea un nuovo post vengono in esso importati i parametri delle configurazioni generali (tra cui height e width).
Queste funzionalità sono attivabili per ogni singolo post attraverso il metabox "Os media Featured Video". Le modalità **Featured** e **shortcode** sono alternative, ovvero con la prima abbiamo la possibilità di creare una vera e propria piattaforma video di distribuzione on-demand, basata sulla stessa logica delle Featured Image di Wordpress (una per ogni post). Per visualizzare il video in questo caso , basterà aggiungere al Tema Wordpress la funzione PHP:

 OSmedia_video($post->ID)
	 
Mentre nella modalità "shortcode" possono essere inseriti più video anche di tipo diverso (self-hosted e embed) nello stesso post attraverso un meccanismo automatico che genera gli shortcode a partire dalla comoda interfaccia di gestione dei parametri del video. Ovviamente gli shortcode sono gestibili solamente al di fuori della modalità “Featured”.
Per i video caricati in modalità **self-host** attualmente il plugin mette a disposizione l'uploader di Wordpress il quale però si basa su PHP e dunque risente delle configurazioni del server in particolare per la dimensione massima dei file caricati (che normalmente è settata a 2-8 MB, troppo poco per i file video!). Ci sono due soluzioni possibili: 
(1) O si interviente riconfigurando il motore di PHP aumentando il limite di upload (agendo sul parametro "upload_max_filesize" nel file php.ini);
(2) Oppure si utilizza il protocollo FTP per il trasferimento dei file ai quali poi dovremmo puntare indicando l'URL nell'apposito spazio di input, uno per ogni formato video (mp4, webm, ogv, oltre che per la cover image). 

**NOTA:** in modalità **Featured Video** la funzionalità di **preload** dei video è attivata (il caricamento del video parte appena viene caricata la pagina), nel caso contrario in cui i video sono gestiti dagli short code, il preload è disattivato di default per evitare problemi di gestione delle pagine con video multipli. Tale parametro si può anche forzare tramite l’apposito checkbox.
Il parametro **Responsive ratio** forza il rapporto d’aspetto rispetto ai valori height e width inseriti in modo da compensare eventuali problemi di visualizzazione nella modalità “responsive”, di default il valore relativo all’aspect ratio 16:9 sarà: (9:16)*100 = 56.25

**Demo online** relativa alla funzionalità **Featured** gestito dal tema Video Elements" di Press75 (il tema di wordpress OStheme è attualmente in fase di sviluppo):
* <a href="http://openstream.tv/sep/" title="Demo live">Demo online</a>
Altre info su questo plugin sul mio blog:
* <a href="http://www.mariomarino.eu/category/wordpress/" title="OS media for WordPress">Home page</a>

**Shortcode [video]:** Tramite questo shortcode potete inserire un video nel vostro blog di tipo self-hosted, ovvero caricato nel server su cui si appoggia il vostro blog.
**Shortcode [youtube]:** Tramite questo shortcode potete inserire un video in modalità "embed" proveniente dalla piattaforma Youtube.

Esempi di shortcode:

`[video mp4="test.mp4" img="splash.jpg" width="640" height="360"]`
`[youtube id="KTRVYDwfDyU" width="640" height="360" start_m="1" start_s="12" loop="true" showinfo="true" related="true" logo="true"]`


== Installation ==

<a href="http://wordpress.org/extend/plugins/OS-media/installation/">Italiano</a> - <a href="">English</a>

= Installazione automatica =

1. Pannello di amministrazione plugin e opzione `aggiungi nuovo`.
2. Ricerca nella casella di testo `OSmedia`.
3. Posizionati sulla descrizione di questo plugin e seleziona installa.
4. Attiva il plugin dal pannello di amministrazione di WordPress.

= Installazione manuale file ZIP =

1. Scarica il file .ZIP da questa schermata.
2. Seleziona opzione aggiungi plugin dal pannello di amministrazione.
3. Seleziona opzione in alto `upload` e seleziona il file che hai scaricato.
4. Conferma installazione e attivazione plugin dal pannello di amministrazione.

= Installazione manuale FTP =

1. Scarica il file .ZIP da questa schermata e decomprimi.
2. Accedi in FTP alla tua cartella presente sul server web.
3. Copia tutta la cartella `OSmedia` nella directory `/wp-content/plugins/`
4. Attiva il plugin dal pannello di amministrazione di WordPress.


== Screenshots ==

1. Pannello di Amministrazione Player HTML5
2. Gestione video singoli post/pagine


== Changelog ==

<a href="http://wordpress.org/extend/plugins/OSmedia/changelog/">Italiano</a> - <a href="">English</a> 

= Versione 1.0 =
* Youtube: Inserimento shortcode `[youtube url="url"]`.
* Youtube: Possibilità di forzare la visualizzazione dei video Yotube dentro il player Videojs.
* Youtube: Embed del player con tecnica Responsive.
* self-host: Embed del player con tecnica Responsive.


== Upgrade Notice ==

= 1.0 =
Possibilità di forzare la visualizzazione dei video Yotube dentro il player Videojs.