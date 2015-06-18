php-vanilla is a php toolbox to execute _Vanilla aligner_ at different levels (sentences and alineas) on bi-text. It applies theese alignements on document from [europa.eu](http://www.europa.eu).

### What do we want to do? ###

This tools were written to evaluate the impact of the search space on alignment methods of sentences based on their lenghts. This way, we use an implementation of the Gale & Church algorithm (2) : _Vanilla_. _Vanilla_ allows us to observe alignements across a bi-document, this alignements may involved two different levels of granularity : sentences and paragraphes (or _alineas_).

### Usage ###

```
user@machina: php run.php file.lg1.xml file.lg2.xml
              %%will produce the file.lg1.ad.svg, file.lg1.sd.svg and file.lg1.sa.svg
```

#### View of alinea alignments ####

<img src='http://users.info.unicaen.fr/~rbrixtel/img_svn/zone_align/ad.png' width='250px' />

#### View of sentence alignments ####

<img src='http://users.info.unicaen.fr/~rbrixtel/img_svn/zone_align/sa.png' width='250px' />
<img src='http://users.info.unicaen.fr/~rbrixtel/img_svn/zone_align/sd.png' width='250px' />

### Exemple of a bi-document french (fr) - greek (el) from europa ###

<table>
<tr><td><b><code>./exemples/celex_IP-04-901.fr.xml</code></b></td><td><b><code>./exemples/celex_IP-04-901.el.xml</code></b></td></tr>

<tr>
<td valign='top'>
<p align='right'>Bruxelles, le 15 juillet 2004 |</p>
<br>
<br>
<hr/><br>
<br>
<br>
<h3><b>De nouveaux financements pour les<br>
réseaux trans-européens d’énergie et de transport, GALILEO<br>
et Marco-Polo de 2007 à 2013  |</b></h3>
<br>
<br>
<hr/><br>
<br>
<br>
<p><b><i>La Commission propose plus de 22 milliards d’euros sur sept ans<br>
pour les réseaux transeuropéens de transport et d’énergie,<br>
le programme GALILEO et le programme Marco Polo en faveur du transport de fret<br>
par d’autres modes que la route. | « Il est grand temps<br>
aujourd’hui de doter l’Union européenne des ressources<br>
budgétaires suffisantes pour inciter les secteurs public et privé<br>
à investir dans les grandes infrastructures et les technologies<br>
décisives dans l’énergie et les transports pour un<br>
véritable marché intérieur et plus de compétitivité<br>
», a souligné Mme de Palacio. |</i></b></p>
<br>
<br>
<hr/><br>
<br>
<br>
<p>Dans le cadre des prochaines perspectives financières (2007-2013), la<br>
Commission propose une réévaluation profonde du budget des<br>
réseaux transeuropéens de transport et d’énergie (RTE) et<br>
des modalités d’octroi des aides financières. |</p>
<br>
<br>
<hr/><br>
<br>
<br>
...<br>
</td>

<td valign='top'>
<p align='right'>Βρυξέλλες, 15 Ιουλίου 2004 |</p>
<br>
<br>
<hr/><br>
<br>
<br>
<h3><b>Νέοι χρηματοδοτικοί πόροι για τα διευρωπαϊκά δίκτυα<br>
ενέργειας και μεταφορών</b><b> GALILEO και Marco Polo από το 2007 έως το 2013</b> |</h3>
<br>
<br>
<hr/><br>
<br>
<br>
<p><b><i>Η Επιτροπή προτείνει να διατεθεί επί χρονικό διάστημα μίας επταετίας ποσό<br>
που υπερβαίνει τα 22 δισεκατομ. ευρώ για τα διευρωπαϊκά δίκτυα μεταφορών και<br>
ενέργειας, το πρόγραμμα GALILEO και το πρόγραμμα Marco Polo, με το οποίο υποστηρίζεται<br>
η μεταφορά φορτίων με άλλους τρόπους μεταφοράς, εκτός των οδικών μεταφορών. | Όπως<br>
τόνισε η κα de Palacio «horaireσήμερα αποτελεί πιεστική ανάγκη να δοθούν στην Ευρωπαϊκή<br>
Ένωση επαρκείς δημοσιονομικοί πόροι για να παρασχεθούν κίνητρα στο δημόσιο και τον<br>
ιδιωτικό τομέα να επενδύσουν στα μεγάλα έργα υποδομής και τις τεχνολογίες εκείνες που<br>
έχουν καίρια σημασία για την ενέργεια και τις μεταφορές με στόχο τη δημιουργία<br>
μιας πραγματικής εσωτερικής αγοράς και την ενίσχυση της ανταγωνιστικότητας».</i></b></p>
<br>
<br>
<hr/><br>
<br>
<br>
<p>Στο πλαίσιο των δημοσιονομικών προοπτικών για τα επόμενα έτη (2007-2013) η Επιτροπή<br>
προτείνει να επαναξιολογηθούν ριζικά τόσο ο προϋπολογισμός που διατίθεται για τα<br>
διευρωπαϊκά δίκτυα μεταφορών και ενέργειας (ΔΕΔ) όσο και οι διαδικασίες που εφαρμόζονται<br>
για τη χορήγηση των χρηματοδοτικών ενισχύσεων. |</p>
<br>
<br>
<hr/><br>
<br>
<br>
...<br>
</td>
</tr>
</table>

We emphasis the end of Alineas and Sentences in this way :
  * Markup "End of Alinea" : 

&lt;hr/&gt;


  * Markup "End of Sentence" : <br /><strong>|</strong>

The aim of this work is too check if we can use a sentences alignment method for alineas alignment problematic.

### Needs ###

#### Vanilla ####

You need _Vanilla aligner_ to use _php-vanilla_. The sources of the version distributed [here](http://nl.ijs.si/telri/Vanilla/doc/ljubljana/) can be found in `svn/src`. You may have to compile sources.

```
user@machina: cc align.c -lm -o align
```

#### Document name formating ####

Each document  have to be named with their language code as they figure in http://en.wikipedia.org/wiki/Liste_des_codes_ISO_639-1. This code have to be written following this way :

```
documentname.code.xml
```

Exemple for a greek document :

```
celex.el.xml
```

### How to set parameters of _Vanilla_? ###

Even if the Gale & Church algorithm is theoretically language independant, its implementation _Vanilla_ needs some kind of parameters as we can observe in the source code (`./src/align.c`) :

```
253: double foreign_chars_per_eng_char = 1.85;
256:
257: /* variance per english character */
258: double var_per_eng_char = 6.8 ;
```

This setting was used to compile the `align2` program in order to align french and greek texts : because the greek text uses a lot of multibyte caracters, you need to normalize lenght of strings written in different languages.

### How to create tokens from a europa document? ###

```
user@machina: php xhtml2vanilla_sentence_alinea.php ./exemple/file.xml %%will write the output in "./exemple/file.xml.sa.tok"
user@machina: php xhtml2vanilla_sentence_document.php ./exemple/file.xml %%will write the output in "./exemple/file.xml.sd.tok"
user@machina: php xhtml2vanilla_alinea_document.php ./exemple/file.xml %%will write the output in "./exemple/file.xml.as.tok"
```

### Wich command line should i use? ###

<table border='1'>
<blockquote><tr><td /><th>Soft Break (-d)</th><th>Hard Break (-D)</th></tr>
<tr>
<blockquote><td><strong>standart <i>Vanilla</i> use</strong><br /><code>xhtml2sentence_alinea.php</code></td>
<td>End of Sentence</td>
<td>End of Paragraph (<i>or Alinea</i>)</td>
</blockquote></tr>
<tr>
<blockquote><td><strong>XP1 : aligning sentence without Hard Break</strong><br /> <code>xhtml2sentence_document.php</code></td>
<td>End of Sentence</td>
<td>End of Document</td>
</blockquote></tr>
<tr>
<blockquote><td><strong>XP2 : aligning alineas</strong><br /><code>xhtml2alinea_document.php</code></td>
<td>End of Alinea</td>
<td>End of Document</td>
</blockquote></tr>
</table></blockquote>

### How to cut a document in alinea / paragraph? ###

You may use `"\n"` or `"\n\n"` to built a partition of the document, or look at (1)
for more details on a document-knowledge based segmentation. Remember that _Vanilla_ needs a complete and bipartite alignment beetween segments formed by Hard Break;

### How to use these tokens for _Vanilla_? ###

```
user@machina: ./src/align2 -D '.EOP' -d '.EOS' ./exemples/celex_IP-04-901.fr.xml.tok ./exemples/celex_IP-04-901.el.xml.tok
              %%"generate celex_IP-04-901.fr.xml.tok.al"
```

#### Sample of a .tok.al file ####

```
*** Link: 1 - 1 ***
Bruxelles , le 15 juillet 2004 . .EOS
Βρυξέλλες , 15 Ιουλίου 2004 . .EOS
*** Link: 1 - 1 ***
De nouveaux financements pour les réseaux trans-européens d’énergie et de transport , GALILEO et Marco-Polo de 2007 à 2013 . .EOS
Νέοι χρηματοδοτικοί πόροι για τα διευρωπαϊκά δίκτυα ενέργειας και μεταφορών GALILEO και Marco Polo από το 2007 έως το 2013 . .EOS
*** Link: 2 - 1 ***
La Commission propose plus de 22 milliards d’euros sur sept ans pour les réseaux transeuropéens de transport et d’énergie , ... . .EOS
Η Επιτροπή προτείνει να διατεθεί επί χρονικό διάστημα μίας επταετίας ποσό που υπερβαίνει τα 22 δισεκατομ. ευρώ για τα ... . .EOS
...
```

#### .svg view ####

<img src='http://users.info.unicaen.fr/~rbrixtel/img_svn/zone_align/vanilla.png' width='450px' />

### Architecture of php-vanilla ###

```
|-- core.php
|-- exemples
|   |-- celex_IP-04-901.el.xml
|   |-- ...
|   |-- celex_IP-04-901.fr.xml
|   |-- ...
|   |-- celex_IP-04-901.fr.xml.tok.al
|-- read_al.php
|-- run.php
|-- required
|   |-- array
|   |   |-- tool_array.php
|   |-- document
|   |   |-- class_corpus.php
|   |   |-- class_document.php
|   |   |-- class_document_xml.php
|   |-- files
|   |   |-- tool_files_dirs.php
|   |-- maths
|   |   |-- tool_maths.php
|   |-- misc
|   |   |-- class_tag.php
|   |   |-- tool_color.php
|   |-- string
|   |   |-- class_segment.php
|   |   |-- tool_string.php
|   |-- svg
|   |   |-- tool_svg.php
|   |-- tree
|       |-- class_structure.php
|       |-- class_tree.php
|       |-- class_tree_xhtml.php
|       |-- tool_tree.php
|-- src
|   |-- align
|   |-- align2
|   |-- align.c
|   |-- align.c.bak
|   |-- align.c.old
|   |-- exemples
|   |   |-- ...
|   |-- README
|   |-- turinde.txt
|   |-- turinen.txt
|   |-- turinfr.txt
|-- USAGE
|-- xhtml2vanilla_alinea_document.php
|-- xhtml2vanilla_sentence_alinea.php
|-- xhtml2vanilla_sentence_document.php
```

### .bib ###

<p>(1)<strong>Extraction endogène d'une structure de document pour un alignement multilingue</strong><br />
Romain Brixtel<br />
<em>Actes de la 11e Rencontre des Etudiants Chercheurs en Informatique pour le Traitement Automatique des Langues (RECITAL 2007)</em><br />
2007 -- Toulouse, France</p>


<p>(2)<strong>A program for aligning sentences in bilingual corpora.</strong><br />
William A. Gale and Kenneth W. Church.<br />
<em>Computational Linguistics,</em> 19(1):75-102, 1993.</p>