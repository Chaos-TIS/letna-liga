<?php
header('Content-type: text/plain; charset=utf-8');
ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/../session'));
session_start();
$sk = 0;
$eng = 1;
switch ($_POST['key']){
    /*--------------------------------------------------------INTRO1--------------------------------------------------------*/
    case "intro1":
        switch ($_POST['lang']) {
            /*--------------------------------------------------------INTRO1_SK--------------------------------------------------------*/
            case $sk:
        ?>
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
                <li>Riešenie obsahuje: spoločné foto vášho tímu, foto robota, program a video ako robot vyrieši úlohu. (<i>Tip: svoje video na YouTube označte ako &quot;unlisted&quot;
                        a nik ho pred termínom odoslania nenájde, aj keď ho tam už budete mať</i>)
                </li>
                <li>Môžete použiť iba robotické stavebnice LEGO MINDSTORMS (RCX, NXT, EV3) so základnými senzormi a štandardný programovací jazyk NXT-G, EV3, alebo Robolab.</li>
                <li>Vaše riešenie získa do celkového ligového hodnotenia 0-3 body.</li>
                <li>Riešenia hodnotí skupina nezávislých rozhodcov</li>
                <li>Ak sa vám zdá úloha náročná, zjednodušte si ju podľa potreby!</li>
            </ul>

            <h2>Kompletné výsledky</h2>
        <?php
                break;
            /*--------------------------------------------------------INTRO1_ENG--------------------------------------------------------*/
            case $eng:
        ?>
            <h2>Welcome! You shouldn't miss the following:</h2>
            <a href="http://kempelen.ii.fmph.uniba.sk/letnaliga/index.php/archive_control/show_year/2015">Assignments and solutions of the summer league</a>
            <h2>Announcements:</h2>
            <ul>
                <li><i>The summer league 2015 is on, join us! The main prize is a LEGO MINDSTORMS Education EV3 kit !</i></li>
                <li>In case of any difficulties with uploading a solution, you can send it via e-mail to the following address: <i>pavel.petrovic@gmail.com</i></li>
            </ul>

            <h2>Would you like to be successful in this year's FLL? Join the summer league!</h2>
            <ul>
                <li>we are starting on 12th February</li>
                <li>the will be 10 rounds, but you can try to compete in any number of them</li>
                <li>for teams with multiple members of the ages 10-16 (you don't have to be registered in FLL)</li>
                <li>a new assignment every 2 weeks with a 2 week deadline</li>
                <li>material prizes</li>
                <li>fair play and a healthy competitive spirit</li>
                <li>not even you can miss this out!</li>
            </ul>

            <h2>Rules</h2>
            <ul>
                <li>A team solves an assignment in the club or at home and uploads their solution on this website.</li>
                <li>A solution should contain: a photo of the team, a photo of the robot, the program and a video of the robot solving the problem. (<i>Hint: Nobody will find
                        your video on YouTube if you mark it as &quot;unlisted&quot;, even if you upload it before the current deadline</i>)
                </li>
                <li>You can use only LEGO MINDSTORMS (RCX, NXT, EV3) robotic kits with the basic sensors and a standard programing language NXT-G, EV3, or Robolab </li>
                <li>Your solution will be rated with 0-3 points, which will be added to your current year's progress in the summer league</li>
                <li>An independent jury is in charge of rating your solutions</li>
                <li>If you find an assignment too difficult, simplify it according to your needs!</li>
            </ul>

            <h2>Complete results</h2>
        <?php
            break;
        }
        break;
    /*--------------------------------------------------------INTRO2--------------------------------------------------------*/
    case "intro2":

        ob_start(); ?>
        <div class="jury-img">
            <img src="http://kempelen.ii.fmph.uniba.sk/letnaliga/hodnot/lubos_miklosovic.jpg"><p>Luboš Miklošovič</p>
            <img src="http://kempelen.ii.fmph.uniba.sk/letnaliga/hodnot/michal_fikar.jpg"><p>Michal Fikar</p>
        </div>
        <div class="jury-img">
            <img src="http://kempelen.ii.fmph.uniba.sk/letnaliga/hodnot/michaela_veselovska.jpg"><p>Michaela Veselovská</p>
            <img src="http://kempelen.ii.fmph.uniba.sk/letnaliga/hodnot/richard_balogh.jpg"><p>Richard Balogh</p>
        </div>
        <?php $juryImages = ob_get_contents();
        ob_end_clean();

        switch ($_POST['lang']){
            /*--------------------------------------------------------INTRO2_SK--------------------------------------------------------*/
            case $sk:
        ?>
            <h2>Ako hodnotíme?</h2>
            <p>
                Vaše riešenia si dôkladne prezrú títo štyria ľudia: Mišo a Ľubo - študenti informatiky FMFI UK, Miška - doktorandka didaktiky informatiky na FMFI UK a Rišo - líder Robotika.SK:
            </p>
            <?php echo $juryImages?>
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
        <?php
                break;
            /*--------------------------------------------------------INTRO2_ENG--------------------------------------------------------*/
            case $eng:
        ?>
            <h2>How do we rate?</h2>
            <p>
                Your solutions will be precisely analysed by these 4 people: Mišo and Ľubo - IT students of FMFI IK, Miška - PhD of IT didactics FMFI UK and Rišo - leader of Robotika.SK:
            </p>
            <?php echo $juryImages?>
            <p>
                Every one of them will independently assign 0-3 points, depending on the originality and completeness of the solution (it contains pictures, a video, a program,
                a good description and the robot is doing what he should be doing). The arithmetic mean of their ratings will be added to the table.
            </p>
            <h2>Previous years of the summer league:</h2>
            <ul>
                <li><strong><a href="http://www.fll.sk/archiv/2014/ll" target="_top">Summer league 2014</a></strong></li>
                <li><strong><a href="http://www.fll.sk/archiv/2013/letnaliga" target="_top">Summer league 2013</a></strong></li>
            </ul>

            <br>
            <p><i>Note: The summer league is not a direct part of FLL, it should be considered as a pre-competition training brought by <a href="http://robotika.sk/"
                                                                                                                                           target="_top">Robotika.SK</a></i></p>
        <?php
                break;
        }
        break;

    case "sk-league":
        switch ($_POST['lang']){
            case $sk:
                ?>Slovenská liga<?php
                break;
            case $eng:
                ?>Slovak league<?php
                break;
        }
        break;

    case "open-league":
        switch ($_POST['lang']){
            case $sk:
                ?>Open liga<?php
                break;
            case $eng:
                ?>Open league<?php
                break;
        }
        break;

    case "team-name":
        switch ($_POST['lang']){
            case $sk:
                ?>Názov tímu<?php
                break;
            case $eng:
                ?>Team name<?php
                break;
        }
        break;

    case "sum-points":
        switch ($_POST['lang']){
            case $sk:
                ?>Spolu<?php
                break;
            case $eng:
                ?>Sum<?php
                break;
        }
        break;

    case "non-existent-acc":
        switch ($_POST['lang']){
            case $sk:
                ?>Neexistuje účet zaregistrovaný na tento e-mail!<?php
                break;
            case $eng:
                ?>No account is registered with this e-mail!<?php
                break;
        }
        break;

    case "wrong-password":
        switch ($_POST['lang']){
            case $sk:
                ?>Zadali ste nesprávne heslo!<?php
                break;
            case $eng:
                ?>You have entered wrong password!<?php
                break;
        }
        break;

    case "jury-acc-not-validated":
        switch ($_POST['lang']){
            case $sk:
                ?>Tento rozhodcovský účet ešte nebol potvrdený!<?php
                break;
            case $eng:
                ?>This jury account has not been validated yet!<?php
                break;
        }
        break;

    case "db-connection-fail":
        switch ($_POST['lang']){
            case $sk:
                ?>Nepodarilo sa spojiť s databázovým serverom!<?php
                break;
            case $eng:
                ?>It was not possible to connect to the database server!<?php
                break;
        }
        break;

    case "db-query-fail":
        switch ($_POST['lang']){
            case $sk:
                ?>Počas získavania údajov z databázy došlo k chybe!<?php
                break;
            case $eng:
                ?>An error has occured during the execution of a database query!<?php
                break;
        }
        break;

    case "db-choice-fail":
        switch ($_POST['lang']){
            case $sk:
                ?>Nepodarilo sa vybrať databázu!<?php
                break;
            case $eng:
                ?>It was not possible to connect to selected database!<?php
                break;
        }
        break;

    case "":
        switch ($_POST['lang']){
            case $sk:
                ?><?php
                break;
            case $eng:
                ?><?php
                break;
        }
        break;

    /*--------------------------------------------------------TEMPLATE--------------------------------------------------------*/
    case "template":
        /* Commmon parts */
        switch ($_POST['lang']){
            /*--------------------------------------------------------TEMPLATE_SK--------------------------------------------------------*/
            case $sk:
                break;
            /*--------------------------------------------------------TEMPLATE_ENG--------------------------------------------------------*/
            case $eng:
                break;
        }
        break;
}
?>