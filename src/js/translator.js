var dict = {
    SK : 0,
    ENG : 1,
    dictionary : {},

    set : function(key, value){
        dict.dictionary[key] = value;
    },

    get : function(key, toLanguageCode, index){
        var result = dict.dictionary[key];
        if ($.isArray(result[0])){
            return result[index % result.length][toLanguageCode];
        }
        return result[toLanguageCode];
    },

    translate : function(toLanguageCode){
        var translated = [];
        $("[data-trans]").each(function() {
            var tag = $(this).attr("data-trans");
            if ($.inArray(tag, translated) === -1) {
                translated.push(tag);
                $("[data-trans="+tag+"]").html(function(index, originalText){
                    var translation = dict.get(tag, toLanguageCode, index);
                    if ($(this).prop("tagName") === "INPUT"){
                        $(this).val(translation);
                        return originalText;
                    }
                    return translation;
                });
            }
        });
    }
};

/*--------------------------------------LOGIN FORM----------------------------------------*/
dict.set('login-form', [['Prihlásenie', 'Login form'],
                        ['E-mailová adresa:', 'E-mail address:'],
                        ['Heslo:', 'Password:'],
                        ['Prihlásiť sa', 'Log in'],
                        ['Registrácia', 'Registration']

]);

/*--------------------------------------INTRO PAGE----------------------------------------*/
dict.set('main-header', ['Letná liga FLL', 'Summer league FLL']);

dict.set('intro-ul1', [ ['Vitajte a pozrite si:', ''],
                        ['Zadania a riešenia letnej ligy', '']]);

dict.set('intro-ul2', [ ['Oznamy:', ''],
                        ['<i>Letná liga FLL 2015 beží, pridajte sa! Hrá sa o stavebnicu LEGO MINDSTORMS Education EV3!</i>', ''],
                        ['V prípade ťažkostí s nahrávaním riešenia ho môžete poslať aj mailom na <i>pavel.petrovic@gmail.com</i>', '']]);

dict.set('intro-ul3', [ ['Chcete uspieť v tohtoročnom ročníku FLL? Ak áno, riešte letnú ligu!', ''],
                        ['štartujeme 12. februára', ''],
                        ['bude 10 kôl, ale zapojíť sa môžete do všetkých alebo hoci len do jedného z nich', ''],
                        ['pre viacčlenné tímy vo veku 10-16 rokov (nemusíte byť registrovaní na FLL)', ''],
                        ['každé dva týždne nové zadanie, na riešenie máte 3 týždne', ''],
                        ['vecné ceny', ''],
                        ['fair play a zdravý súťažný duch', ''],
                        ['ani vy nemôžete chýbať!', '']]);

dict.set('intro-ul4', [ ['Pravidlá', ''],
                        ['Na krúžku, v klube alebo doma tím samostatne a načas vyrieši úlohu a odovzdá svoje riešenie na týchto stránkach.', ''],
                        ['Riešenie obsahuje: spoločné foto vášho tímu, foto robota, program a video ako robot vyrieši úlohu. (<i>Tip: svoje video na YouTube označte ako &quot;unlisted&quot; a nik ho pred termínom odoslania nenájde, aj keď ho tam už budete mať</i>)', ''],
                        ['Môžete použiť iba robotické stavebnice LEGO MINDSTORMS (RCX, NXT, EV3) so základnými senzormi a štandardný programovací jazyk NXT-G, EV3, alebo Robolab.', ''],
                        ['Vaše riešenie získa do celkového ligového hodnotenia 0-3 body.', ''],
                        ['Riešenia hodnotí skupina nezávislých rozhodcov', ''],
                        ['Ak sa vám zdá úloha náročná, zjednodušte si ju podľa potreby!', '']]);

dict.set('complete-results', ['Kompletné výsledky', '']);

dict.set('table-loading', ['Tabuľka sa načítava', '']);

dict.set('intro-ul5', [ ['Ako hodnotíme?', ''],
                        ['Vaše riešenia si dôkladne prezrú títo štyria ľudia: Mišo a Ľubo - študenti informatiky FMFI UK, Miška - doktorandka didaktiky informatiky na FMFI UK a Rišo - líder Robotika.SK:', ''],
                        ['Každý z nich nezávisle od ostatných pridelí 0-3 body podľa toho, či riešenie je kompletné (obsahuje obrázky, video, program, dobrý popis a robot robí' +
                        ' to, čo má) a nakoľko ich zaujme. Do tabuľky sa vám započíta aritmetický priemer.', ''], '']);

dict.set('intro-ul6', [ ['Predchádzajúce ročníky Letnej ligy:', ''],
                        ['Letná liga 2014', ''],
                        ['Letná liga 2013', '']]);

dict.set('intro-note', ['<i >Poznámka: Letná liga nie je priamou súčasťou FLL, je určená na predsúťažný tréning a pripravuje ju združenie <a href="http://robotika.sk/" target="_top">Robotika.SK</a></i>', ''])