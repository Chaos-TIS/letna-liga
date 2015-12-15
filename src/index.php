<?php

require_once(dirname(__FILE__)."/includes/functions.php");

page_head("Letná liga FLL");
page_nav();
?>
        <?php if (!isset($_SESSION['loggedUser']))
            get_login_form();
        else
            get_logout_button();
        ?>

        <div id="content">
            <h2 data-trans="intro-ul1">Vitajte a pozrite si:</h2>
            <a href="http://kempelen.ii.fmph.uniba.sk/letnaliga/index.php/archive_control/show_year/2015" data-trans="intro-ul1">Zadania a riešenia letnej ligy</a>
            <h2 data-trans="intro-ul2">Oznamy:</h2>
            <ul>
                <li data-trans="intro-ul2"><i>Letná liga FLL 2015 beží, pridajte sa! Hrá sa o stavebnicu LEGO MINDSTORMS Education EV3!</i></li>
                <li data-trans="intro-ul2">V prípade ťažkostí s nahrávaním riešenia ho môžete poslať aj mailom na <i>pavel.petrovic@gmail.com</i></li>
            </ul>

            <h2 data-trans="intro-ul3">Chcete uspieť v tohtoročnom ročníku FLL? Ak áno, riešte letnú ligu!</h2>
            <ul>
                <li data-trans="intro-ul3">štartujeme 12. februára</li>
                <li data-trans="intro-ul3">bude 10 kôl, ale zapojíť sa môžete do všetkých alebo hoci len do jedného z nich</li>
                <li data-trans="intro-ul3">pre viacčlenné tímy vo veku 10-16 rokov (nemusíte byť registrovaní na FLL)</li>
                <li data-trans="intro-ul3">každé dva týždne nové zadanie, na riešenie máte 3 týždne</li>
                <li data-trans="intro-ul3">vecné ceny</li>
                <li data-trans="intro-ul3">fair play a zdravý súťažný duch</li>
                <li data-trans="intro-ul3">ani vy nemôžete chýbať!</li>
            </ul>

            <h2 data-trans="intro-ul4">Pravidlá</h2>
            <ul>
                <li data-trans="intro-ul4">Na krúžku, v klube alebo doma tím samostatne a načas vyrieši úlohu a odovzdá svoje riešenie na týchto stránkach.</li>
                <li data-trans="intro-ul4">Riešenie obsahuje: spoločné foto vášho tímu, foto robota, program a video ako robot vyrieši úlohu. (<i>Tip: svoje video na YouTube označte ako &quot;unlisted&quot; a nik ho pred termínom odoslania nenájde, aj keď ho tam už budete mať</i>)</li>
                <li data-trans="intro-ul4">Môžete použiť iba robotické stavebnice LEGO MINDSTORMS (RCX, NXT, EV3) so základnými senzormi a štandardný programovací jazyk NXT-G, EV3, alebo Robolab.</li>
                <li data-trans="intro-ul4">Vaše riešenie získa do celkového ligového hodnotenia 0-3 body.</li>
                <li data-trans="intro-ul4">Riešenia hodnotí skupina nezávislých rozhodcov</li>
                <li data-trans="intro-ul4">Ak sa vám zdá úloha náročná, zjednodušte si ju podľa potreby!</li>
            </ul>

            <h2 data-trans="complete-results">Kompletné výsledky</h2>
            <script>
                $(document).ready(function(){
                        $("#results").load("includes/get_result_table.php");
                    });
            </script>
            <p id="results"><span  data-trans="table-loading">Tabuľka sa načítava ...</span></p>

            <h2 data-trans="intro-ul5">Ako hodnotíme?</h2>
            <p data-trans="intro-ul5">
            Vaše riešenia si dôkladne prezrú títo štyria ľudia: Mišo a Ľubo - študenti informatiky FMFI UK, Miška - doktorandka didaktiky informatiky na FMFI UK a Rišo - líder Robotika.SK:
            </p>
                <div class="jury-img">
                    <img src="http://kempelen.ii.fmph.uniba.sk/letnaliga/hodnot/lubos_miklosovic.jpg"><p>Luboš Miklošovič</p>
                    <img src="http://kempelen.ii.fmph.uniba.sk/letnaliga/hodnot/michal_fikar.jpg"><p>Michal Fikar</p>
                </div>
                <div class="jury-img">
                    <img src="http://kempelen.ii.fmph.uniba.sk/letnaliga/hodnot/michaela_veselovska.jpg"><p>Michaela Veselovská</p>
                    <img src="http://kempelen.ii.fmph.uniba.sk/letnaliga/hodnot/richard_balogh.jpg"><p>Richard Balogh</p>
                </div>
            <p data-trans="intro-ul5">
            Každý z nich nezávisle od ostatných pridelí 0-3 body podľa toho, či riešenie je kompletné (obsahuje obrázky, video, program, dobrý popis a robot robí to, čo má) a nakoľko ich zaujme. Do tabuľky sa vám započíta aritmetický priemer.
            </p>
            <h2 data-trans="intro-ul6">Predchádzajúce ročníky Letnej ligy:</h2>
            <ul>
                <li><strong><a href="http://www.fll.sk/archiv/2014/ll" target="_top" data-trans="intro-ul6">Letná liga 2014</a></strong></li>
                <li><strong><a href="http://www.fll.sk/archiv/2013/letnaliga" target="_top" data-trans="intro-ul6">Letná liga 2013</a></strong></li>
            </ul>

            <br>
            <p data-trans="intro-note"><i >Poznámka: Letná liga nie je priamou súčasťou FLL, je určená na predsúťažný tréning a pripravuje ju združenie <a href="http://robotika.sk/" target="_top">Robotika.SK</a></i></p>


        </div>

<?php
page_footer()
?>