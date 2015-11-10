<?php
require_once("functions.php");
page_head("Letná liga FLL");
page_nav();
?>
        <h1>Letná liga FLL</h1>

        <form id="login-form" action="http://kempelen.ii.fmph.uniba.sk/letnaliga/index.php/auth/login" method="post" accept-charset="utf-8">
            <table>
                <tr>
                    <td><p style="margin-bottom: 0; margin-top: 0; font-weight: bold; color: #3399ff;">Prihlásenie</p></td>
                </tr>
                <tr>
                    <td>E-mailová adresa:</td>
                    <td><input type="text" name="mail" value="@"></td>
                </tr>
                <tr>
                    <td>Heslo:</td>
                    <td><input type="password" name="passwd" value=""></td>
                </tr>
                <tr>
                    <td><input type="submit" name="submit" value="Prihlás sa"></td>
                    <td style="text-align: right;"><a href="http://kempelen.ii.fmph.uniba.sk/letnaliga/index.php/auth/register"> Registruj sa </a></td>
                    <p style="color: green;"></p>	</tr>
            </table>
        </form>

        <div id="content">
            <h2>Vitajte a pozrite si:</h2>
            <a href="http://kempelen.ii.fmph.uniba.sk/letnaliga/index.php/archive_control/show_year/2015">Zadania a riešenia letnej ligy</a>
            <h2>Oznamy:</h2>
            <ul>
                <li><i>Letná liga FLL 2015 beží, pridajte sa! Hrá sa o stavebnicu LEGO MINDSTORMS Education EV3!</i></li>
                <li>V prípade ťažkostí s nahrávaním riešenia ho môžete poslať aj mailom na <i>pavel.petrovic@gmail.com</i></li>
            </ul>

            <h2>Chcete uspieť v tohtoročnom ročníku FLL? Ak áno, riešte letnú ligu!</h2>
            <ul>
                <li>štartujeme 12. februára</li>
                <li>bude 10 kôl, ale zapojíť sa môžete do všetkých alebo hoci len do jedného z nich</li>
                <li>pre viacčlenné tímy vo veku 10-16 rokov (nemusíte byť registrovaní na FLL)</li>
                <li>každé dva týždne nové zadanie, na riešenie máte 3 týždne</li>
                <li>vecné ceny</li>
                <li>fair play a zdravý súťažný duch</li>
                <li>ani vy nemôžete chýbať!</li>
            </ul>

            <h2>Pravidlá</h2>
            <ul>
                <li>Na krúžku, v klube alebo doma tím samostatne a načas vyrieši úlohu a odovzdá svoje riešenie na týchto stránkach.</li>
                <li>Riešenie obsahuje: spoločné foto vášho tímu, foto robota, program a video ako robot vyrieši úlohu. (<i>Tip: svoje video na YouTube označte ako &quot;unlisted&quot; a nik ho pred termínom odoslania nenájde, aj keď ho tam už budete mať</i>)</li>
                <li>Môžete použiť iba robotické stavebnice LEGO MINDSTORMS (RCX, NXT, EV3) so základnými senzormi a štandardný programovací jazyk NXT-G, EV3, alebo Robolab.</li>
                <li>Vaše riešenie získa do celkového ligového hodnotenia 0-3 body.</li>
                <li>Riešenia hodnotí skupina nezávislých rozhodcov</li>
                <li>Ak sa vám zdá úloha náročná, zjednodušte si ju podľa potreby!</li>
            </ul>

            <h2>Kompletné výsledky</h2>
            <?php echo show_table(2015)?>

            <h2>Ako hodnotíme?</h2>
            <p>
            Vaše riešenia si dôkladne prezrú títo štyria ľudia: Mišo a Ľubo - študenti informatiky FMFI UK, Miška - doktorandka didaktiky informatiky na FMFI UK a Rišo - líder Robotika.SK:
            </p>
                <div class="jury-img"><img src="http://kempelen.ii.fmph.uniba.sk/letnaliga/hodnot/lubos_miklosovic.jpg"><p>Luboš Miklošovič</p></div>
                <div class="jury-img"><img src="http://kempelen.ii.fmph.uniba.sk/letnaliga/hodnot/michal_fikar.jpg"><p>Michal Fikar</p></div>
                <div class="jury-img"><img src="http://kempelen.ii.fmph.uniba.sk/letnaliga/hodnot/michaela_veselovska.jpg"><p>Michaela Veselovská</p></div>
                <div class="jury-img"><img src="http://kempelen.ii.fmph.uniba.sk/letnaliga/hodnot/richard_balogh.jpg"><p>Richard Balogh</p></div>
            <p>
            Každý z nich nezávisle od ostatných pridelí 0-3 body podľa toho, či riešenie je kompletné (obsahuje obrázky, video, program, dobrý popis a robot robí to, čo má) a nakoľko ich zaujme. Do tabuľky sa vám započíta aritmetický priemer.
            </p>
            <h2>Predchádzajúce ročníky Letnej ligy:</h2>
            <ul>
                <li><strong><a href="http://www.fll.sk/archiv/2014/ll" target="_top">Letná liga 2014</a></strong></li>
                <li><strong><a href="http://www.fll.sk/archiv/2013/letnaliga" target="_top">Letná liga 2013</a></strong></li>
            </ul>

            <br>
            <p><i >Poznámka: Letná liga nie je priamou súčasťou FLL, je určená na predsúťažný tréning a pripravuje ju združenie <a href="http://robotika.sk/" target="_top">Robotika.SK</a></i></p>


        </div>

<?php
page_footer()
?>